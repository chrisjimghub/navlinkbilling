<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Billing;
use App\Models\AccountCredit;

use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\alert;
use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait PaidBillOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupPaidBillRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/paidBill', [
            'as'        => $routeName.'.paidBill',
            'uses'      => $controller.'@paidBill',
            'operation' => 'paidBill',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupPaidBillDefaults()
    {
        CRUD::allowAccess('paidBill');

        CRUD::operation('paidBill', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            // CRUD::addButton('top', 'paid_bill', 'view', 'crud::buttons.paid_bill');
            CRUD::addButton('line', 'paid_bill', 'view', 'crud::buttons.paid_bill', 'beginning');
        });
    }

    public function paidBill($id)
    {
        $this->crud->hasAccessOrFail('paidBill');

        try {
            DB::beginTransaction();

            $billing = Billing::findOrFail($id); 

            // Update billing status
            $billing->billing_status_id = 1;
            
            // $billing->save(); // TODO:: uncomment after
            
            // capture from snapshots

            // Find the label for one month advancem ID = 1 = 1 Month advance
            $oneMonthAdvanceLabel = $billing->account->contractPeriods()->where('contract_periods.id', 1)->first();

            // if ($oneMonthAdvanceLabel) {
            //     // Create account credit for relevant particulars
            //     foreach ($billing->particulars as $particular) {
            //         if ($particular['description'] == $oneMonthAdvanceLabel->name) {
            //             AccountCredit::create([
            //                 'account_id' => $billing->account_id,
            //                 'amount' => $particular['amount'],
            //             ]);
            //         }
            //     }
            // }
            
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