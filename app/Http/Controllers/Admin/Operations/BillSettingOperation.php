<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait BillSettingOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupBillSettingRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/billSetting', [
            'as'        => $routeName.'.billSetting',
            'uses'      => $controller.'@billSetting',
            'operation' => 'billSetting',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupBillSettingDefaults()
    {
        CRUD::allowAccess('billSetting');

        CRUD::operation('billSetting', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            CRUD::addButton('top', 'bill_setting', 'view', 'crud::buttons.bill_setting');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     */
    public function billSetting()
    {
        CRUD::hasAccessOrFail('billSetting');

        //Validate request data
        $validator = Validator::make(request()->all(), [
            'enable_auto_bill' => [
                'integer',  
                'in:0,1',  
            ],
            'enable_auto_notification' => [
                'integer',  
                'in:0,1',  
            ],
            
            
            // fiber
            'fiber_day_start' => [
                'required',
                'integer',   
                'between:0,31',
            ],
            'fiber_day_end' => [
                'required',
                'integer',   
                'between:0,31',
            ],
            'fiber_day_cut_off' => [
                'required',
                'integer',   
                'between:0,10',
            ],
            'fiber_billing_period' => [
                'required',
                'string', 
                'in:previous_month_current_month,current_month_current_month,current_month_next_month',
            ],
            // end fiber
            

            // p2p
            'p2p_day_start' => [
                'required',
                'integer',   
                'between:0,31',
            ],
            'p2p_day_end' => [
                'required',
                'integer',   
                'between:0,31',
            ],
            'p2p_day_cut_off' => [
                'required',
                'integer',   
                'between:0,10',
            ],
            'p2p_billing_period' => [
                'required',
                'string', 
                'in:previous_month_current_month,current_month_current_month,current_month_next_month',
            ],
            // end p2p


            'days_before_generate_bill' => [
                'integer',
                'between:0,10',
                // Use required_if rule to make this field required if enable_auto_bill is 1
                'required_if:enable_auto_bill,1',
            ],

            'days_before_send_bill_notification' => [
                'integer',   
                'between:0,10',
                'required_if:enable_auto_notification,1',
            ],

            'days_before_send_cut_off_notification' => [
                'integer',   
                'between:0,3',
                'required_if:enable_auto_notification,1',
            ],

        ], [
            'days_before_generate_bill.required_if' => "The 'when should the bill be auto-generated' field is required when auto generate bill is enabled.",
            'days_before_generate_bill.between' => "The 'when should the bill be auto-generated' field is invalid.",
            'days_before_generate_bill.integer' => "The 'when should the bill be auto-generated' field is invalid.",

            'days_before_send_bill_notification.required_if' => "The 'when should we send customer notifications' field is required when auto send notification is enabled.",
            'days_before_send_bill_notification.between' => "The 'when should we send customer notifications' field is invalid.",
            'days_before_send_bill_notification.integer' => "The 'when should we send customer notifications' field is invalid.",

            'days_before_send_cut_off_notification.required_if' => "The 'when should we send cut-off notifications' field is required when auto send notification is enabled.",
            'days_before_send_cut_off_notification.between' => "The 'when should we send cut-off notifications' field is invalid.",
            'days_before_send_cut_off_notification.integer' => "The 'when should we send cut-off notifications' field is invalid.",
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON response
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422); // HTTP status code for Unprocessable Entity
        }
        
        Setting::set('enable_auto_bill', request()->enable_auto_bill);
        Setting::set('enable_auto_notification', request()->enable_auto_notification);
        Setting::set('days_before_generate_bill', request()->days_before_generate_bill);
        // fiber
        Setting::set('fiber_day_start', request()->fiber_day_start);
        Setting::set('fiber_day_end', request()->fiber_day_end);
        Setting::set('fiber_day_cut_off', request()->fiber_day_cut_off);
        Setting::set('fiber_billing_period', request()->fiber_billing_period);
        // p2p
        Setting::set('p2p_day_start', request()->p2p_day_start);
        Setting::set('p2p_day_end', request()->p2p_day_end);
        Setting::set('p2p_day_cut_off', request()->p2p_day_cut_off);
        Setting::set('p2p_billing_period', request()->p2p_billing_period);
        
        Setting::set('days_before_send_bill_notification', request()->days_before_send_bill_notification);
        Setting::set('days_before_send_cut_off_notification', request()->days_before_send_cut_off_notification);

        return response()->json([
            'msg' => '<strong>'.__('Billing Settings Updated').'</strong><br>'.__('The billing settings have been successfully updated.'),
        ]);
    }
}