<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Billing;
use App\Events\BillProcessed;
use App\Models\AccountCredit;
use App\Models\PaymentMethod;
use Illuminate\Support\Carbon;
use App\Rules\BillingMustBeUnpaid;
use Illuminate\Support\Facades\DB;
use App\Rules\BankCheckRepeatField;
use App\Rules\UpgradePlanValidDate;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Route;
use App\Rules\UniqueServiceInterruption;
use LaravelDaily\Invoices\Classes\Party;
use Illuminate\Support\Facades\Validator;
use App\Models\AccountServiceInterruption;
use App\Rules\MustHaveEnoughAccountCredit;
use LaravelDaily\Invoices\Facades\Invoice;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use App\Http\Controllers\Admin\Traits\FetchOptions;
use App\Http\Controllers\Admin\Traits\AdvancePayment;
use App\Http\Controllers\Admin\Traits\SendNotifications;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait BillingGroupButtonsOperation
{
    use SendNotifications;
    use AdvancePayment;
    use FetchOptions;

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupBillingGroupButtonsRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/pay', [
            'as'        => $routeName.'.pay',
            'uses'      => $controller.'@pay',
            'operation' => 'pay',
        ]);

        Route::post($segment.'/{id}/sendNotification', [
            'as'        => $routeName.'.sendNotification',
            'uses'      => $controller.'@sendNotification',
            'operation' => 'sendNotification',
        ]);

        Route::put($segment.'/{id}/serviceInterrupt', [
            'as'        => $routeName.'.serviceInterrupt',
            'uses'      => $controller.'@serviceInterrupt',
            'operation' => 'serviceInterrupt',
        ]);

        // Route::post($segment.'/{id}/payUsingCredit', [
        //     'as'        => $routeName.'.payUsingCredit',
        //     'uses'      => $controller.'@payUsingCredit',
        //     'operation' => 'payUsingCredit',
        // ]);

        Route::post($segment.'/{id}/changePlan', [
            'as'        => $routeName.'.changePlan',
            'uses'      => $controller.'@changePlan',
            'operation' => 'changePlan',
        ]);

        Route::get($segment.'/{id}/downloadInvoice', [
            'as'        => $routeName.'.downloadInvoice',
            'uses'      => $controller.'@downloadInvoice',
            'operation' => 'downloadInvoice',
        ]);

        Route::get($segment.'/fetchPaymentMethodOption', [
            'as'        => $routeName.'.fetchPaymentMethodOption',
            'uses'      => $controller.'@fetchPaymentMethodOption',
            'operation' => 'fetchPaymentMethodOption',
        ]);
    }

    public function fetchPaymentMethodOption()
    {
        return $this->paymentMethodLists();
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupBillingGroupButtonsDefaults()
    {
        CRUD::allowAccess([
            'pay', 
            // 'payUsingCredit', 
            'changePlan',
            'serviceInterrupt',
            'sendNotification',
            'downloadInvoice',
        ]);

        Widget::add()->type('script')->content('assets/js/admin/swal_helper.js');

        CRUD::operation('billingGroupButtons', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
            Widget::add()->type('script')->content('assets/js/admin/swal_helper.js');
        });

        CRUD::operation(['list', 'show'], function () {
            $button = config('backpack.ui.view_namespace') == 'backpack.theme-coreuiv2::' ? 'billing_group_buttons' : 'billing_group_buttons_bs5';
            CRUD::addButton('line', 'billingGroupButtons', 'view', 'crud::buttons.'.$button, 'beginning');
        });
    }

    public function downloadInvoice($id)
    {
        $this->crud->hasAccessOrFail('downloadInvoice');

        $id = $this->crud->getCurrentEntryId() ?? $id;

        $validator = Validator::make(['id' => $id], [
            'id' => [
                'required',
                'integer',
                'min:1',
                'exists:billings,id',
            ],
        ], [
            'id.required' => 'Invalid billing item.',
            'id.exists' => 'The selected billing item does not exist.', 
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON response
            alertValidatorError($validator);
            return redirect()->back();
        }


        $billing = Billing::findOrFail($id);

        $customer = new Party([
            'custom_fields' => [
                'name'          => $billing->account->customer->full_name,
                'plan' => $billing->account_planned_application_details,
                'address' => $billing->account->customer->address,
                // 'Email' => $billing->account->customer->email,
                'Contact' => $billing->account->customer->contact_number,
                'subscription' => $billing->account_subscription_name,
            ],
        ]);

        $items = [];

        foreach ($billing->particulars as $item) {
            $amount = 0;
            $deduction = 0;

            if ($item['amount'] > 0) {
                $amount = $item['amount'];
            }else {
                $deduction = abs($item['amount']);
            }
            
            $items[] = InvoiceItem::make($item['description'])
                        ->pricePerUnit($amount)
                        // since laravel daily package dont have less or deduction method, so i use this discount instead
                        ->discount($deduction); 
        }

        
        $invoice = Invoice::make('receipt')
            ->status($billing->billingStatus->name)
            ->sequence($billing->id)
            ->serialNumberFormat('{SEQUENCE}')
            ->buyer($customer)
            ->addItems($items)

            ->date($billing->created_at)
            ->dateFormat(dateHumanReadable())
            
            ->setCustomData([
                'is_monthly_fee'         => $billing->isMonthlyFee(),
                'billing_type'           => $billing->billingType->name,
                'billing_payment_method' => $billing->paymentMethod->name ?? '',
                'billing_period'         => $billing->period,
                'date_cut_off'           => Carbon::parse($billing->date_cut_off)->format(dateHumanReadable()),
            ])

            
            ->notes(__('invoices::invoice.notes_content'))
            
            ->filename($customer->custom_fields['name'] .' - '. $billing->period)
            ->logo(public_path(config('invoices.project_logo')));
        
        // And return invoice itself to browser or have a different view
        // return $invoice->stream();
        // return $invoice->download();

        return $this->downloadInvoiceType($invoice);
    }

    public function downloadInvoiceType($invoice)
    {
        return $invoice->stream();
    }

    public function changePlan($id)
    {
        $this->crud->hasAccessOrFail('changePlan');

        $id = $this->crud->getCurrentEntryId() ?? $id;

        //Validate request data
        $validator = Validator::make(array_merge(request()->all(), ['id' => $id]), [
            'id' => [
                'required',
                'integer',
                'min:1',
                'exists:billings,id', 
                new BillingMustBeUnpaid($id)
            ],
            'planned_application_id' => [
                'required',
                'integer',
                'min:1',
                'exists:planned_applications,id',
            ],
            'date_change' => [
                'required',
                'date',
                new UpgradePlanValidDate($id) 
                // must be between the billing_start and end or equal
            ] 
        ], [
            'id.required' => 'Invalid billing item.',
            'id.exists' => 'The selected billing item does not exist.', 
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return notyValidatorError($validator);
        }

        $billing = Billing::findOrFail($id);
        
        // Get the current value of before_account_snapshots and modify it, use temporary variable so laravel wont cause an error
        // by using variable first we allow laravel to let him cast the value of json column to array and now we can assign
        // the date_change before saving it. This variable: $beforeAccountSnapshot = [];

        // if he keep upgrading the plan or the before_account_snapshot is not empty then update only the date_change,
        // because upgrading the plan the latest/new plan is save in account_snapshot. we need to retain the old account 
        // that's why we only updated the date_change. Because before_account_snapshot is account before upgraded.
        $beforeAccountSnapshot = [];
        $beforeAccountSnapshot = $billing->before_account_snapshot ?? $billing->account_snapshot;

        $beforeAccountSnapshot['date_change'] = request()->date_change;
        $billing->before_account_snapshot = $beforeAccountSnapshot;
        $billing->saveQuietly(); 
        
        // Update account planned application, since the Account model doesnt trigger the BillProcessed event automatically 
        // because i dispatch the event in Account Controller to include the pivot table changes. So let's update the account 
        // planned application here and then dispatch the BillProcessed event manually, that's why we save it Quietly above to
        // save resources and also copy the account_snapshots value to before_account_snapshots column.
        $billing->account()->update(['planned_application_id' => request()->planned_application_id]);
        event(new BillProcessed($billing));

        // Return success response
        return notySuccess('The planned application has been successfully updated.');
    }

    public function payUsingCredit($id)
    {
        $this->crud->hasAccessOrFail('payUsingCredit');

        $id = $this->crud->getCurrentEntryId() ?? $id;
        
        $billing = Billing::findOrFail($id);

        //Validate request data
        $validator = Validator::make(['id' => $id], [
            'id' => [
                'required',
                'integer',
                'min:1',
                'exists:billings,id',
                new BillingMustBeUnpaid($id),
                new MustHaveEnoughAccountCredit($billing->account, $billing->total),
            ],
        ], [
            'id.required' => 'Invalid billing item.',
            'id.exists' => 'The selected billing item does not exist.', 
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON response
            return notyValidatorError($validator);
        }

        // mark billing as paid
        $billing->markAsPaid();
        $billing->payment_method_id = 2; // 2 = Credit
        $paid = $billing->saveQuietly();

        if ($paid) {
            AccountCredit::create([
                'account_id' => $billing->account_id,
                'amount'     => -$billing->total,
            ]);
        } 

        // Return success response
        return notySuccess('The item is mark paid using credit successfully.');
    }

    // pay
    public function pay($id)
    {
        $this->crud->hasAccessOrFail('pay');

        $id = $this->crud->getCurrentEntryId() ?? $id;
        
        //Validate request data
        $validator = Validator::make(
            array_merge(['id' => $id], request()->all()), 
        [
            'id' => [
                'required',
                'integer',
                'min:1',
                'exists:billings,id',
                new BillingMustBeUnpaid($id),
            ],
            'paymentMethod' => [
                'required',
                'in:1,3,4' // cash,gcash,bank/check
            ],
            'checkIssuedDate' => [
                'sometimes',
                'required_if:paymentMethod,4,', 
            ],
            'checkNumber' => [
                'sometimes',
                'required_if:paymentMethod,4,', 
            ],
        ], [
            'id.required' => 'Invalid billing item.',
            'id.exists' => 'The selected billing item does not exist.', 
            'paymentMethod.required_if' => __('app.vouchers.validation.payment_method'),
            'checkIssuedDate.required_if' => __('app.vouchers.validation.check_issued_date'),
            'checkNumber.required_if' => __('app.vouchers.validation.check_number'),
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON response
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422); // HTTP status code for Unprocessable Entity
        }


        try {
            DB::beginTransaction();
            $paymentMethod = request()->input('paymentMethod');

            $billing = Billing::findOrFail($id); 
            $billing->payment_method_id = $paymentMethod;

            if ($paymentMethod == 4) { //bank/check
                $paymentDetails = $billing->payment_details ?? [];
                $paymentDetails = [
                    'check_issued_date' => request()->input('checkIssuedDate'),
                    'check_number' => request()->input('checkNumber'),
                ];
                $billing->payment_details = $paymentDetails;
            }

            $billing->markAsPaid();
            $billing->saveQuietly();  

            $this->advancePayment($billing);

            DB::commit();

            return notySuccess('The item has been marked as paid successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            throw $e; 
        }
    }

    public function sendNotification($id)
    {
        $this->crud->hasAccessOrFail('sendNotification');

        $id = $this->crud->getCurrentEntryId() ?? $id;
        
        $billing = Billing::find($id);

        if (!$billing) {
            return notyError('The selected billing item does not exist.');
        }

        $customer = $billing->account->customer;
        $email = $customer->email; // add this to valdiator and must be valid and required 

        //Validate request data
        $customer = $billing->account->customer;
        $email = $customer->email; // Get the customer's email

        // Validate request data
        $validator = Validator::make(
            ['id' => $id, 'email' => $email],
            [
                'id' => [
                    'required',
                    'integer',
                    'min:1',
                    'exists:billings,id',
                    new BillingMustBeUnpaid($id),
                ],
                'email' => [
                    'required',
                    'email',
                ],
            ],
            [
                'id.required' => 'Invalid billing item.',
                'id.exists' => 'The selected billing item does not exist.',
                'email.required' => 'The customer email is required.',
                'email.email' => 'The customer email must be a valid email address.',
            ]
        );

        // Check if validation fails
        if ($validator->fails()) {
            return notyValidatorError($validator);            
        }

        $this->billNotification($customer, $billing);

        return notySuccess('Bill notification has been sent successfully!');

    }
    
    public function serviceInterrupt($id)
    {
        $this->crud->hasAccessOrFail('serviceInterrupt');

        $accountId = request()->account_id;
        $dateStart = request()->date_start;
        $dateEnd = request()->date_end;

        // Validate request data
        $validator = Validator::make(array_merge(request()->all(), ['id' => $id]), [
            'date_start' => 'required|date',
            'date_end' => 'required|date|after:date_start',
            // Apply custom rule for uniqueness and non-overlapping intervals
            'account_id' => [
                'required',
                'integer',
                'min:1',
                new UniqueServiceInterruption($accountId, $dateStart, $dateEnd)
            ],

            'id' => [
                'required',
                'integer',
                'min:1',
                'exists:billings,id', 
                new BillingMustBeUnpaid($id)
            ],

        ], [
            'date_start.required' => 'The date start field is required.',
            'date_end.required' => 'The date end field is required.',
            'date_end.after' => 'The date end field must be a date after date start.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON response
            return notyValidatorError($validator);
        }

        // Validation passed, proceed to save data
        $serviceInterruption = new AccountServiceInterruption();
        $serviceInterruption->account_id = $accountId;
        $serviceInterruption->date_start = $dateStart;
        $serviceInterruption->date_end = $dateEnd;
        $serviceInterruption->save();

        // NOTE:: no need to dispatch BillProcessed it will automatically dispatch, check AccountServiceInterruption model.

        // Return success response
        return notySuccess('The service interruption was saved successfully.');
    }
}