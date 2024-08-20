<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\GoogleMapCoordinatesValidator;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true; // Set to false if you want to implement authorization logic
    }

    public function rules()
    {
        return [
            'customer_id' => 'required|integer|min:1',
            'planned_application_id' => 'required|integer|min:1',
            'subscription' => 'required|integer|min:1',
            
            // Ensure contractPeriods is an array and has at least 1 item
            'contractPeriods' => 'required|array|min:1',
            // Ensure each item in the contractPeriods array is a valid integer and exists in the contract_periods table
            'contractPeriods.*' => 'integer|exists:contract_periods,id',
            
            // Ensure otcs is an array and has at least 1 item
            'otcs' => 'required|array|min:1',
            // Ensure each item in the otcs array is a valid integer and exists in the otcs table
            'otcs.*' => 'integer|exists:otcs,id',
            
            'accountStatus' => 'required|integer|min:1',
            
            // Validate Google Maps coordinates
            'google_map_coordinates' => ['nullable', new GoogleMapCoordinatesValidator],

            'billingGrouping' => [
                'nullable',
                'exists:billing_groupings,id',
                'required_unless:subscription,4,3' // voucher and piso wifi
            ]


        ];
    }

    public function messages()
    {
        return [
            'customer_id.required' => 'The account name field is required.',
            'planned_application_id.required' => 'The planned application field is required.',
            'subscription.required' => 'The subscription field is required.',

            // Custom error messages for contractPeriods
            'contractPeriods.required' => 'You must select at least one contract period.',
            'contractPeriods.array' => 'The contract periods field must be an array.',
            'contractPeriods.*.integer' => 'Each contract period must be a valid integer.',
            'contractPeriods.*.exists' => 'Each selected contract period must exist in the database.',

            // Custom error messages for otcs
            'otcs.required' => 'You must select at least one one-time charge.',
            'otcs.array' => 'The one-time charges field must be an array.',
            'otcs.*.integer' => 'Each one-time charge must be a valid integer.',
            'otcs.*.exists' => 'Each selected one-time charge must exist in the database.',

            'accountStatus.required' => 'The account status field is required.',

            'billingGrouping.required_unless' => 'The billing grouping field is required unless subscription is hotspot voucher.'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'otcs' => json_decode($this->input('otcs'), true),
            'contractPeriods' => json_decode($this->input('contractPeriods'), true),
        ]);
    }
}
