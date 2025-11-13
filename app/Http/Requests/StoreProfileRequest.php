<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $hasProofDocument = $user && $user->proof_document;
        $hasProfileImage = $user && $user->profile_image;

        return [
            // Alumni Details
            'passing_year' => 'required|string|max:10',
            'course' => 'required|in:B.Tech CSE,B.Tech ECE,B.Tech EE,B.Tech ME',
            'proof_document' => ($hasProofDocument ? 'nullable' : 'required').'|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'residence_address' => 'required|string|max:500',
            'residence_city' => 'required|string|max:255',
            'residence_state' => 'required|string|max:255',
            'residence_country' => 'required|string|max:255',
            'aadhar_number' => 'nullable|string|max:12|regex:/^[0-9]{12}$/',
            'date_of_birth' => 'nullable|date|before:today',
            'wedding_anniversary_date' => 'nullable|date|before_or_equal:today',
            'profile_image' => ($hasProfileImage ? 'nullable' : 'required').'|image|mimes:jpg,jpeg,png|max:2048',

            // Employment Details
            'company' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'employment_type' => 'required|in:Govt,Non-Govt',
            'employment_address' => 'required|string|max:500',
            'employment_city' => 'required|string|max:255',
            'employment_state' => 'required|string|max:255',
            'employment_pincode' => 'required|string|max:10|regex:/^[0-9]{6}$/',
            'phone' => 'required|string|max:20',
            'alternate_email' => 'nullable|email|max:255',
            'linkedin_url' => 'nullable|url|max:255',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'passing_year.required' => 'Passing year is required.',
            'course.required' => 'Course/Major is required.',
            'course.in' => 'Please select a valid course.',
            'residence_address.required' => 'Current residence address is required.',
            'residence_city.required' => 'City is required.',
            'residence_state.required' => 'State is required.',
            'residence_country.required' => 'Country is required.',
            'aadhar_number.regex' => 'Aadhar number must be exactly 12 digits.',
            'company.required' => 'Company name is required.',
            'designation.required' => 'Designation is required.',
            'employment_type.required' => 'Employment type is required.',
            'employment_type.in' => 'Please select either Govt or Non-Govt.',
            'employment_address.required' => 'Employment address is required.',
            'employment_city.required' => 'City is required.',
            'employment_state.required' => 'State is required.',
            'employment_pincode.required' => 'Pincode is required.',
            'employment_pincode.regex' => 'Pincode must be exactly 6 digits.',
            'phone.required' => 'Phone number is required.',
            'alternate_email.email' => 'Alternate email must be a valid email address.',
            'linkedin_url.url' => 'LinkedIn profile link must be a valid URL.',
        ];
    }
}
