<?php

namespace App\Http\Requests;

use App\Rules\UniqueAccountBillingType;
use Illuminate\Foundation\Http\FormRequest;

class BillingRequest extends FormRequest
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
        // return [
        //     'account_id' => 'required|integer|min:1',
        //     'billing_type_id' => 'required|exists:billing_types,id',
        //     'date_start' => function ($attribute, $value, $fail) {
        //         if ($this->input('billing_type_id') == 2) {
        //             if (empty($value)) {
        //                 $fail(__('validation.required', ['attribute' => strtolower(__('app.billing_date_start'))]));
        //             }
        //         }
        //     },
        //     'date_end' => function ($attribute, $value, $fail) {
        //         if ($this->input('billing_type_id') == 2) {
        //             if (empty($value)) {
        //                 $fail(__('validation.required', ['attribute' => strtolower(__('app.billing_date_end'))]));
        //             }
        //             $date_start = $this->input('date_start');
        //             if (!empty($date_start) && $value <= $date_start) {
        //                 $fail(__('validation.after', ['attribute' => strtolower(__('app.billing_date_end')), 'date' => strtolower(__('app.billing_date_start'))]));
        //             }
        //         }
        //     },
        //     'date_cut_off' => function ($attribute, $value, $fail) {
        //         if ($this->input('billing_type_id') == 2) {
        //             if (empty($value)) {
        //                 $fail(__('validation.required', ['attribute' => strtolower(__('app.billing_date_cut_off'))]));
        //             }
        //             $date_end = $this->input('date_end');
        //             if (!empty($date_end) && $value <= $date_end) {
        //                 $fail(__('validation.after', ['attribute' => strtolower(__('app.billing_date_cut_off')), 'date' => strtolower(__('app.billing_date_end'))]));
        //             }
        //         }
        //     },
        // ];

        $accountId = $this->input('account_id');
        $billingTypeId = $this->input('billing_type_id');

        return [
            'account_id' => 'required|integer|min:1',
            'billing_type_id' => [
                'required',
                'exists:billing_types,id',
                new UniqueAccountBillingType($accountId, $billingTypeId),
            ],
            'date_start' => function ($attribute, $value, $fail) use ($billingTypeId) {
                if ($billingTypeId == 2) {
                    if (empty($value)) {
                        $fail(__('validation.required', ['attribute' => strtolower(__('app.billing_date_start'))]));
                    }
                }
            },
            'date_end' => function ($attribute, $value, $fail) use ($billingTypeId) {
                if ($billingTypeId == 2) {
                    if (empty($value)) {
                        $fail(__('validation.required', ['attribute' => strtolower(__('app.billing_date_end'))]));
                    }
                    $date_start = $this->input('date_start');
                    if (!empty($date_start) && $value <= $date_start) {
                        $fail(__('validation.after', ['attribute' => strtolower(__('app.billing_date_end')), 'date' => strtolower(__('app.billing_date_start'))]));
                    }
                }
            },
            'date_cut_off' => function ($attribute, $value, $fail) use ($billingTypeId) {
                if ($billingTypeId == 2) {
                    if (empty($value)) {
                        $fail(__('validation.required', ['attribute' => strtolower(__('app.billing_date_cut_off'))]));
                    }
                    $date_end = $this->input('date_end');
                    if (!empty($date_end) && $value <= $date_end) {
                        $fail(__('validation.after', ['attribute' => strtolower(__('app.billing_date_cut_off')), 'date' => strtolower(__('app.billing_date_end'))]));
                    }
                }
            },
        ];
    }

    public function messages()
    {
        return [
            'account_id.required' => __('app.account_field_validation'),
            'billing_type_id.required' => __('validation.required', ['attribute' => strtolower(__('app.billing_type'))]),
            'billing_type_id.exists' => __('validation.exists', ['attribute' => strtolower(__('app.billing_type'))]),
            // 'unique_account_billing_type' => 'The selected account already has a billing type '. __('app.installation_fee'),
        ];
    }
}
