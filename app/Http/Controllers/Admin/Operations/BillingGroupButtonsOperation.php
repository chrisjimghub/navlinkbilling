<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Billing;
use App\Models\AccountCredit;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
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

        // TODO:: use credit
        // TODO:: upgrade plan
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupBillingGroupButtonsDefaults()
    {
        CRUD::allowAccess('billingGroupButtons');

        CRUD::operation('billingGroupButtons', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            CRUD::addButton('line', 'billingGroupButtons', 'view', 'crud::buttons.billing_group_buttons', 'beginning');
        });
    }

    public function pay($id)
    {
        $this->crud->hasAccessOrFail('buttonPay');

        try {
            DB::beginTransaction();

            $billing = Billing::findOrFail($id); 

            // Update billing status
            $billing->billing_status_id = 1;
            
            $billing->save(); 
            
            // Find the label for one month advancem ID = 1 = 1 Month advance
            // NOTE:: this is just taking the label of id 1 in contract so it's not neccessary to use snapshots
            $oneMonthAdvanceLabel = $billing->account->contractPeriods()->where('contract_periods.id', 1)->first(); 

            if ($oneMonthAdvanceLabel) {
                // Create account credit for relevant particulars
                foreach ($billing->particulars as $particular) {
                    if ($particular['description'] == $oneMonthAdvanceLabel->name) {
                        AccountCredit::create([
                            'account_id' => $billing->account_id,
                            'amount' => $particular['amount'],
                        ]);
                    }
                }
            }
            
            // Commit the transaction
            DB::commit();

            return true;

        } catch (\Exception $e) {
            // If an error occurs, rollback the transaction
            DB::rollback();
            throw $e; // You may handle or log the exception as needed
        }
    }
}