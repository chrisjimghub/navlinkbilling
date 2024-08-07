<?php

namespace App\Http\Controllers\Customer\Operations;

use App\Http\Controllers\Admin\Traits\SendNotifications;
use App\Models\Billing;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Luigel\Paymongo\Facades\Paymongo;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait GcashOperation
{
    use CurrencyFormat;
    use SendNotifications;

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupGcashRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/gcashPay', [
            'as'        => $routeName.'.gcashPay',
            'uses'      => $controller.'@gcashPay',
            'operation' => 'gcashPay',
        ]);

        Route::get($segment.'/{id}/gcashSuccess', [
            'as'        => $routeName.'.gcashSuccess',
            'uses'      => $controller.'@gcashSuccess',
            'operation' => 'gcashSuccess',
        ]);

        Route::get($segment.'/{id}/gcashFailed', [
            'as'        => $routeName.'.gcashFailed',
            'uses'      => $controller.'@gcashFailed',
            'operation' => 'gcashFailed',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupGcashDefaults()
    {
        CRUD::allowAccess('gcash');

        CRUD::operation('gcash', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation(['list', 'show'], function () {
            CRUD::addButton('line', 'gcash', 'view', 'crud::buttons.customer.gcash', 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     */
    public function gcashPay($id)
    {
        CRUD::hasAccessOrFail('gcash');

        if (!$this->gcashValidations()) {
            return redirect($this->crud->route);
        }
        
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $billing = Billing::findOrFail($id);

        $gcashSource = Paymongo::source()->create([
            'type' => 'gcash',
            'amount' => $this->totalWithPaymongoServiceCharge($billing->total),
            'currency' => 'PHP',
            'redirect' => [
                // 'success' => route('billing-history.gcashSuccess', $id),
                // 'failed' => route('billing-history.gcashFailed', $id)
                'success' => url($this->crud->route.'/'.$id.'/'.'gcashSuccess'),
                'failed' => url($this->crud->route.'/'.$id.'/'.'gcashFailed')
            ],
            'description' => 'Bill for the Month of '.$billing->month.' '.$billing->year.': '. $this->currencyFormatAccessor($billing->total),
            'statement_descriptor' => config('app.name'),
            'billing' => [
                "name" => $billing->account->customer->full_name,
                "phone" => $billing->account->customer->contact_number ?? '',
                "email" => $billing->account->customer->email ?? auth()->user()->email,
                "address" => [
                    "line1" => $billing->account->customer->barangay ?? '',
                    "line2" => $billing->account->customer->block_street ?? '',
                    "city" => $billing->account->customer->city_or_municipality ?? '',
                ],
            ]
        ]);

        if ($gcashSource) {
            $billing->paymongo_reference_number = $gcashSource->id;
            $billing->markAsPending();
            $billing->saveQuietly();

            return redirect($gcashSource->redirect['checkout_url']);
        }
    }

    private function gcashValidations()
    {
        $validator = Validator::make(['id' => request()->id], [
            'id' => [
                'required',
                'integer',
                Rule::in(Billing::notPaid()->pluck('id')->toArray()), // Warning: make sure you use the scope: notPaid and no the method unPaid, their is a big difference
            ],
        ], [
            'id.required' => 'Invalid billing item.',
            'id.exists' => 'The selected billing item does not exist.', 
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            alertValidatorError($validator);
            return false;
        }

        return true;
    }

    public function totalWithPaymongoServiceCharge($total)
    {
        /* 
        Total with Fee = Original Total / 1−Transaction Fee Percentage

        Where: 
            • Original Total is the amount you want to receive after the fee is deducted (e.g., 1299).
            • Transaction Fee Percentage is the fee percentage in decimal form (e.g., 2.5% = 0.025).

        Using the given example:
            • Original Total=1299
            • Transaction Fee Percentage=0.025

        So,
            Total with Fee  = 1299 / 1−0.025
                            = 1299 / 0.975
                            = 1332.31
        */

        $transactionServiceCharge = (float) Setting::get('paymongo_service_charge') / 100; 
        $totalWithFee = $total / (1 - $transactionServiceCharge);

        return $totalWithFee;
    }

    public function gcashSuccess($id)
    {
        CRUD::hasAccessOrFail('gcash');

        if (!$this->gcashValidations()) {
            return redirect($this->crud->route);
        }

        $id = $this->crud->getCurrentEntryId() ?? $id;
        
        $billing = Billing::findOrFail($id);
        $reference = $billing->paymongo_reference_number;
        
        if (Str::startsWith($reference, 'pay_')) {
            alertInfo('The bill is already paid.');
            return redirect($this->crud->route);
        }
        
        if (!Str::startsWith($reference, 'src_')) {
            alertError('Whoops, something went wrong. Invalid Reference #.');
            return redirect($this->crud->route);
        }

        $source = Paymongo::source()->find($reference);
        $payment = Paymongo::payment()->create([
            'amount' => $source->amount,
            'currency' => $source->currency,
            'description' => $source->description,
            'statement_descriptor' => $source->statement_descriptor,
            'source' => [
                'id' => $source->id,
                'type' => 'source'
            ]
        ]);

        if ($payment && strtolower($payment->status) == 'paid') {
            $billing->paymongo_reference_number = $payment->id;
            $billing->markAsPaid();
            $billing->paymentMethodGcash();
            $billing->saveQuietly();
            
            $this->customerOnlinePaymentNotification($billing);

            alertSuccess('The bill has been paid successfully.');
            return redirect($this->crud->route);
        }
    }

    public function gcashFailed()
    {
        CRUD::hasAccessOrFail('gcash');

        alertError('Payment didn’t get through. Please try again later.');
        return redirect($this->crud->route);
    }
}