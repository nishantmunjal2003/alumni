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
            ->whereNotNull('residence_country')
            ->whereNotNull('residence_city');

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

        $alumni = $query->select('id', 'name', 'email', 'residence_city', 'residence_state', 'residence_country', 'passing_year', 'course', 'company', 'profile_image')
            ->get();

        // Group by location and prepare data
        $locations = [];
        foreach ($alumni as $alumnus) {
            $locationKey = strtolower(trim($alumnus->residence_city.', '.$alumnus->residence_state.', '.$alumnus->residence_country));

            if (! isset($locations[$locationKey])) {
                $locations[$locationKey] = [
                    'city' => $alumnus->residence_city,
                    'state' => $alumnus->residence_state,
                    'country' => $alumnus->residence_country,
                    'location_string' => trim($alumnus->residence_city.', '.$alumnus->residence_state.', '.$alumnus->residence_country),
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
                'profile_image' => $alumnus->profile_image,
            ];
        }

        return response()->json([
            'locations' => array_values($locations),
            'total_alumni' => $alumni->count(),
        ]);
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
            ->where('status', '!=', 'inactive')
            ->whereNotNull('residence_country')
            ->whereNotNull('residence_city');

        // Apply filters if provided
        if ($request->filled('passing_year')) {
            $query->where('passing_year', $request->passing_year);
        }

        $alumni = $query->select('id', 'name', 'email', 'residence_city', 'residence_state', 'residence_country', 'passing_year', 'course', 'company', 'profile_image')
            ->get();

        // Group by location and prepare data
        $locations = [];
        foreach ($alumni as $alumnus) {
            $locationKey = strtolower(trim($alumnus->residence_city.', '.$alumnus->residence_state.', '.$alumnus->residence_country));

            if (! isset($locations[$locationKey])) {
                $locations[$locationKey] = [
                    'city' => $alumnus->residence_city,
                    'state' => $alumnus->residence_state,
                    'country' => $alumnus->residence_country,
                    'location_string' => trim($alumnus->residence_city.', '.$alumnus->residence_state.', '.$alumnus->residence_country),
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
                'profile_image' => $alumnus->profile_image,
            ];
        }

        return response()->json([
            'locations' => array_values($locations),
            'total_alumni' => $alumni->count(),
        ]);
    }
}
