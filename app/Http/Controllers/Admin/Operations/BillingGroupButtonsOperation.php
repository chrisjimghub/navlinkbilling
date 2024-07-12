<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Billing;
use Illuminate\Support\Str;
use App\Events\BillProcessed;
use App\Models\AccountCredit;
use Illuminate\Support\Carbon;
use App\Rules\BillingMustBeUnpaid;
use Illuminate\Support\Facades\DB;
use App\Rules\UpgradePlanValidDate;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Route;
use App\Rules\UniqueServiceInterruption;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Party;
use Illuminate\Support\Facades\Validator;
use App\Models\AccountServiceInterruption;
use App\Notifications\NewBillNotification;
use App\Rules\MustHaveEnoughAccountCredit;
use LaravelDaily\Invoices\Facades\Invoice;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait BillingGroupButtonsOperation
{
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

        Route::post($segment.'/{id}/payUsingCredit', [
            'as'        => $routeName.'.payUsingCredit',
            'uses'      => $controller.'@payUsingCredit',
            'operation' => 'payUsingCredit',
        ]);

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
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupBillingGroupButtonsDefaults()
    {
        CRUD::allowAccess([
            'pay', 
            'payUsingCredit', 
            'changePlan',
            'serviceInterrupt',
            'sendNotification',
            'downloadInvoice',
        ]);

        // load
        $this->myWidgets();

        CRUD::operation('billingGroupButtons', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
            Widget::add()->type('script')->content('assets/js/admin/swal_helper.js');
        });

        CRUD::operation(['list'], function () {
            $this->crud->enableBulkActions();
            CRUD::addButton('line', 'billingGroupButtons', 'view', 'crud::buttons.billing_group_buttons', 'beginning');
        });
    }

    public function myWidgets()
    {
        Widget::add()->type('script')->content('assets/js/admin/swal_helper.js');
        
        if ( $this->crud->hasAccess('pay') ) {
            Widget::add()->type('script')->content('assets/js/admin/billing_operations/pay.js');
        }

        if ( $this->crud->hasAccess('serviceInterrupt') ) {
            Widget::add()->type('script')->content('assets/js/admin/billing_operations/serviceInterrupt.js');
        }

        if ( $this->crud->hasAccess('sendNotification') ) {
            Widget::add()->type('script')->content('assets/js/admin/billing_operations/sendNotification.js');
        }

        if ( $this->crud->hasAccess('payUsingCredit') ) {
            Widget::add()->type('script')->content('assets/js/admin/billing_operations/payUsingCredit.js');
        }

        if ( $this->crud->hasAccess('changePlan') ) {
            Widget::add()->type('script')->content('assets/js/admin/billing_operations/changePlan.js');
        }
    }

    public function downloadInvoice($id)
    {
        $this->crud->hasAccessOrFail('downloadInvoice');

        $id = $this->crud->getCurrentEntryId() ?? $id;

        // TODO:: validator here

        $billing = Billing::findOrFail($id);

        $client = new Party([
            'custom_fields' => [
                'name'          => 'NavLink Technology FBR-X',
                'address' => 'Brgy. San Isidro Palompon Leyte',
                'phone' => '09958476256 / 09093639756',
            ],
        ]);
        
        $customer = new Party([
            'custom_fields' => [
                'name'          => $billing->account->customer->full_name,
                'Subscription' => $billing->account->plannedApplication->details,
                'address' => $billing->account->customer->address,
                'Email' => $billing->account->customer->email,
                'Contact' => $billing->account->customer->contact_number,
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

            // $deduction = 1;
            $items[] = InvoiceItem::make($item['description'])
                        // ->description('Your product or service description')
                        ->pricePerUnit($amount)
                        ->discount($deduction); // since laravel daily package dont have less or deduction method we can use, so i use this discount instead
        
        }

        $notes = [
            'your multiline',
            'additional notes',
            'in regards of delivery or something else',
        ];
        $notes = implode("<br>", $notes);
        
        $invoice = Invoice::make('receipt')
            // ability to include translated invoice status
            // in case it was paid
            ->status($billing->billingStatus->name)
            ->sequence($billing->id)
            ->serialNumberFormat('{SEQUENCE}')
            ->seller($client)
            ->buyer($customer)
            ->addItems($items)

            ->date(now()->subWeeks(3))
            ->dateFormat(dateHumanReadable())
            
            ->setCustomData([
                'billing_period' => $billing->period,
                'date_cut_off' => Carbon::parse($billing->date_cut_off)->format(dateHumanReadable()),
            ])

            // currency
            ->currencySymbol(config('app-settings.currency_prefix'))
            ->currencyCode('PHP')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator(',')
            ->currencyDecimalPoint('.')

            
            
            ->notes($notes)
            ->payUntilDays(14)
            
            
            ->filename($client->name . ' ' . $customer->name)
            ->logo(public_path('/images/NAVLINK_LOGO.png'))
            // You can additionally save generated invoice to configured disk
            ->save('public');
        
        $link = $invoice->url();
        // Then send email to party with link
        
        // And return invoice itself to browser or have a different view
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
            // Return validation errors as JSON response
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422); // HTTP status code for Unprocessable Entity
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
        return response()->json([
            'msg' => '<strong>'.__('Planned Change').'</strong><br>'.__('The planned application has been successfully updated.'),
        ]);
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
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422); // HTTP status code for Unprocessable Entity
        }

        // mark billing as paid
        $billing->markAsPaid();
        $paid = $billing->save();

        if ($paid) {
            AccountCredit::create([
                'account_id' => $billing->account_id,
                'amount'     => -$billing->total,
            ]);
        } 

        // Return success response
        return response()->json([
            'msg' => '<strong>'.__('Item Paid').'</strong><br>'.__('The item is mark paid using credit successfully.'),
        ]);
    }

    // pay
    public function pay($id)
    {
        $this->crud->hasAccessOrFail('pay');

        $id = $this->crud->getCurrentEntryId() ?? $id;
        
        //Validate request data
        $validator = Validator::make(['id' => $id], [
            'id' => [
                'required',
                'integer',
                'min:1',
                'exists:billings,id',
                new BillingMustBeUnpaid($id),
            ],
        ], [
            'id.required' => 'Invalid billing item.',
            'id.exists' => 'The selected billing item does not exist.', 
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
            
            $billing = Billing::findOrFail($id); 
            $billing->markAsPaid();
            $billing->save(); 
            
            // Find the label for one month advancem ID = 1 = 1 Month advance
            // NOTE:: this is just taking the label of id 1 in contract so it's not neccessary to use snapshots
            $oneMonthAdvanceLabel = $billing->account->contractPeriods()->where('contract_periods.id', 1)->first(); 

            if ($oneMonthAdvanceLabel) {
                // Create account credit for relevant particulars
                foreach ($billing->particulars as $particular) {
                    if (Str::contains(strtolower($particular['description']), strtolower($oneMonthAdvanceLabel->name))) {
                        AccountCredit::create([
                            'account_id' => $billing->account_id,
                            'amount' => $particular['amount'],
                        ]);
                    }
                
                    if (Str::contains(strtolower($particular['description']), strtolower("Deposit Account Credit"))) {
                        AccountCredit::create([
                            'account_id' => $billing->account_id,
                            'amount' => $particular['amount'],
                        ]);
                    }
                }
            }
            
            // Commit the transaction
            DB::commit();

            // Return success response
            return response()->json([
                'msg' => '<strong>'.__('Item Paid').'</strong><br>'.__('The item has been marked as paid successfully.'),
            ]);

        } catch (\Exception $e) {
            // If an error occurs, rollback the transaction
            DB::rollback();
            throw $e; // You may handle or log the exception as needed
        }
    }

    public function sendNotification($id)
    {
        $this->crud->hasAccessOrFail('sendNotification');

        $id = $this->crud->getCurrentEntryId() ?? $id;
        
        //Validate request data
        $validator = Validator::make(['id' => $id], [
            'id' => [
                'required',
                'integer',
                'min:1',
                'exists:billings,id',
                new BillingMustBeUnpaid($id),
            ],
        ], [
            'id.required' => 'Invalid billing item.',
            'id.exists' => 'The selected billing item does not exist.', 
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON response
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422); // HTTP status code for Unprocessable Entity
        }


        $billing = Billing::find($id);

        $customer = $billing->account->customer;

        if ($customer->email) {
            // Notify the customer
            $customer->notify(new NewBillNotification($billing));
           
            $billing->notified_at = now();
    
            return $billing->save();
        }else {
            // send alert that customer has no email   
            return response()->json([
                'msg' => 'Customer has no email.'
            ]);
                    
        }
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
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422); // HTTP status code for Unprocessable Entity
        }

        // Validation passed, proceed to save data
        $serviceInterruption = new AccountServiceInterruption();
        $serviceInterruption->account_id = $accountId;
        $serviceInterruption->date_start = $dateStart;
        $serviceInterruption->date_end = $dateEnd;
        $serviceInterruption->save();

        // NOTE:: no need to dispatch BillProcessed it will automatically dispatch, check AccountServiceInterruption model.

        // Return success response
        return response()->json([
            'msg' => '<strong>'.__('Item Saved').'</strong><br>'.__('The service interruption was saved successfully.'),
        ]);
    }
}