<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingGroupingRequest extends FormRequest
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
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'unique:billing_groupings,name,' . $this->route('id'),
            ],

            'billingCycle' => [
                'required',
                'string', 
                'in:1,2,3',
            ],
           
            'day_start' => [
                'required',
                'integer',   
                'between:0,31',
            ],
            'day_end' => [
                'required',
                'integer',   
                'between:0,31',
            ],
            'day_cut_off' => [
                'required',
                'integer',   
                'between:0,10',
            ],

            'auto_generate_bill' => [
                'integer',
                'in:0,1',
            ],
            'auto_send_bill_notification' => [
                'integer',
                'in:0,1',
            ],
            'auto_send_cut_off_notification' => [
                'integer',
                'in:0,1',
            ],
            'bill_generate_days_before_end_of_billing_period' => [
                'nullable',
                'integer',
                'between:0,10',
                'required_if:auto_generate_bill,1',
            ],
            'bill_notification_days_after_the_bill_created' => [
                'nullable',
                'integer',
                'between:0,10',
                'required_if:auto_send_bill_notification,1',
            ],
            'bill_cut_off_notification_days_before_cut_off_date' => [
                'nullable',
                'integer',
                'between:0,3',
                'required_if:auto_send_cut_off_notification,1',
            ],
        ];
    }

    public function messages()
    {
        return [
            'day_start.required' => "The 'date start' field is required.",
            'day_start.integer' => "The 'date start' field must be an integer.",
            'day_start.between' => "The 'date start' field must be between 1 and 31.",
            
            'day_end.required' => "The 'date end' field is required.",
            'day_end.integer' => "The 'date end' field must be an integer.",
            'day_end.between' => "The 'date end' field must be between 1 and 31.",
            
            'day_cut_off.required' => "The 'date cut off' field is required.",
            'day_cut_off.integer' => "The 'date cut off' field must be an integer.",
            'day_cut_off.between' => "The 'date cut off' field must be between 1 and 10 or 'same day as date end'.",


            'bill_generate_days_before_end_of_billing_period.required_if' => "The 'when should the bill be auto-generated' field is required when auto generate bill is enabled.",
            'bill_generate_days_before_end_of_billing_period.between' => "The 'when should the bill be auto-generated' field is invalid.",
            'bill_generate_days_before_end_of_billing_period.integer' => "The 'when should the bill be auto-generated' field is invalid.",

            'bill_notification_days_after_the_bill_created.required_if' => "The 'when should we send customer notifications' field is required when auto send bill notification is enabled.",
            'bill_notification_days_after_the_bill_created.between' => "The 'when should we send customer notifications' field is invalid.",
            'bill_notification_days_after_the_bill_created.integer' => "The 'when should we send customer notifications' field is invalid.",

            'bill_cut_off_notification_days_before_cut_off_date.required_if' => "The 'when should we send cut-off notifications' field is required when auto send cut off notification is enabled.",
            'bill_cut_off_notification_days_before_cut_off_date.between' => "The 'when should we send cut-off notifications' field is invalid.",
            'bill_cut_off_notification_days_before_cut_off_date.integer' => "The 'when should we send cut-off notifications' field is invalid.",
        ];
    }
}
