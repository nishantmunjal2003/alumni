<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AlumniMapController extends Controller
{
    /**
     * Show alumni map for admin panel.
     */
    public function adminMap()
    {
        // Get passing years that have alumni
        $passingYears = User::whereNotNull('passing_year')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            })
            ->distinct()
            ->orderBy('passing_year', 'desc')
            ->pluck('passing_year');

        return view('admin.alumni.map', compact('passingYears'));
    }

    /**
     * Get alumni location data for map (admin).
     */
    public function getAlumniLocations(Request $request)
    {
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })
            ->where(function ($q) {
                // Check for Residence OR Employment location availability
                $q->where(function ($subQ) {
                    // Check Residence
                    $subQ->whereNotNull('residence_country')
                        ->where(function ($locQ) {
                            $locQ->whereNotNull('residence_city')->where('residence_city', '!=', '')
                                 ->orWhere('residence_address', '!=', '');
                        });
                })->orWhere(function ($subQ) {
                    // Check Employment (only if country is set, assuming country is minimum requirement for map)
                    // Actually, if country is missing but city is there, geocoding might fail or be inaccurate.
                    // Sticking to Country is required for simplicity, or at least city/address.
                    // Let's require Country for logical grouping.
                     $subQ->whereNotNull('employment_country')
                        ->where(function ($locQ) {
                            $locQ->whereNotNull('employment_city')->where('employment_city', '!=', '')
                                 ->orWhere('employment_address', '!=', '');
                        });
                });
            });

        // Apply filters if provided
        if ($request->filled('passing_year')) {
            $query->where('passing_year', $request->passing_year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('profile_status')) {
            $query->where('profile_status', $request->profile_status);
        }

        $alumni = $query->select(
            'id', 'name', 'email', 
            'residence_address', 'residence_city', 'residence_state', 'residence_country', 
            'employment_address', 'employment_city', 'employment_state', 'employment_country', // Added employment fields
            'passing_year', 'course', 'company', 'profile_image', 'current_position'
        )->get();

        return $this->formatAlumniLocationResponse($alumni);
    }

    /**
     * Show alumni map for public/alumni panel.
     */
    public function publicMap()
    {
        // Get passing years that have non-deactivated alumni
        $passingYears = User::whereNotNull('passing_year')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            })
            ->where('status', '!=', 'inactive')
            ->distinct()
            ->orderBy('passing_year', 'desc')
            ->pluck('passing_year');

        return view('alumni.map', compact('passingYears'));
    }

    /**
     * Get alumni location data for map (public - exclude deactivated users).
     */
    public function getPublicAlumniLocations(Request $request)
    {
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })
            ->where('status', '!=', 'inactive') // This allows pending profiles as long as they aren't 'inactive'
            ->where(function ($q) {
                 // Check Residence OR Employment location availability
                $q->where(function ($subQ) {
                    // Check Residence
                    $subQ->whereNotNull('residence_country')
                        ->where(function ($locQ) {
                            $locQ->whereNotNull('residence_city')->where('residence_city', '!=', '')
                                 ->orWhere('residence_address', '!=', '');
                        });
                })->orWhere(function ($subQ) {
                    // Check Employment
                     $subQ->whereNotNull('employment_country')
                        ->where(function ($locQ) {
                            $locQ->whereNotNull('employment_city')->where('employment_city', '!=', '')
                                 ->orWhere('employment_address', '!=', '');
                        });
                });
            });

        // Apply filters if provided
        if ($request->filled('passing_year')) {
            $query->where('passing_year', $request->passing_year);
        }

        $alumni = $query->select(
            'id', 'name', 'email', 
            'residence_address', 'residence_city', 'residence_state', 'residence_country', 
            'employment_address', 'employment_city', 'employment_state', 'employment_country', // Added employment fields
            'passing_year', 'course', 'company', 'profile_image', 'current_position'
        )->get();

        return $this->formatAlumniLocationResponse($alumni);
    }

    /**
     * Helper to format alumni data for map response.
     */
    private function formatAlumniLocationResponse($alumni)
    {
        // Group by location and prepare data
        $locations = [];
        foreach ($alumni as $alumnus) {
            // Determine Location: Priority -> Employment, Fallback -> Residence
            $city = $alumnus->employment_city ?: $alumnus->residence_city;
            $state = $alumnus->employment_state ?: $alumnus->residence_state;
            $country = $alumnus->employment_country ?: $alumnus->residence_country;
            $address = $alumnus->employment_address ?: $alumnus->residence_address;

            // Ensure we have at least a country to group by, otherwise skip (safeguard)
            if (empty($country)) continue;

            $locationParts = [];
            if ($city && trim($city) !== '') {
                $locationParts[] = $city;
            }
            if ($state && trim($state) !== '') {
                $locationParts[] = $state;
            }
            $locationParts[] = $country;

            // If only country is available, maybe use address? 
            // Map usually needs "City, Country" or just "Country" for geocoding.
            
            $locationString = implode(', ', $locationParts);
            $locationKey = strtolower($locationString); // Unique key for grouping

            if (! isset($locations[$locationKey])) {
                $locations[$locationKey] = [
                    'city' => $city,
                    'state' => $state,
                    'country' => $country,
                    'location_string' => $locationString,
                    'alumni' => [],
                ];
            }

            $locations[$locationKey]['alumni'][] = [
                'id' => $alumnus->id,
                'name' => $alumnus->name,
                'email' => $alumnus->email,
                'passing_year' => $alumnus->passing_year,
                'course' => $alumnus->course,
                'company' => $alumnus->company,
                'current_position' => $alumnus->current_position,
                'profile_image' => $alumnus->profile_image,
                'location_source' => $alumnus->employment_country ? 'Work' : 'Home' // Optional: indicate source
            ];
        }

        return response()->json([
            'locations' => array_values($locations),
            'total_alumni' => $alumni->count(),
        ]);
    }
}
