<?php

namespace App\Http\Controllers\Customer;

use App\Models\Billing;
use Illuminate\Support\Carbon;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Route;
use Backpack\Settings\app\Models\Setting;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Controllers\Customer\Operations\GcashOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DashboardCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DashboardCrudController extends CrudController
{
    use GcashOperation;

    public function setup()
    {
        CRUD::setRoute(config('backpack.base.route_prefix') . '/dashboard');

        $this->crud->denyAllAccess();
        $this->crud->allowAccess([
            'gcash'
        ]);
    }

    protected function setupExportRoutes($segment, $routeName, $controller)
    {
        Route::get($segment, [
            'as'        => $routeName.'.dashboard',
            'uses'      => $controller.'@dashboard',
            'operation' => 'dashboard',
        ]);
    }    

    public function dashboard()
    {
        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title

        $unpaidBills = Billing::monthly()->unpaid()->get();

        foreach ($unpaidBills as $billing)  {
            $this->currentBill($billing);
        }

        return view(backpack_view('customer.dashboard'), $this->data);
    }

    public function currentBill(Billing $billing)
    {
        $fee = $this->totalWithPaymongoServiceCharge($billing->total) - $billing->total;
        $fee = currencyFormat($fee);
        $this->data['contents'][] = Widget::make([
            'type'          => 'progress_white',
            'class'         => 'card mb-2',
            'value'         => '
                <div class="row">
                    <div class="col">'.currencyFormat($billing->total).'</div>
                    <div class="col">
                        <a href="'.route('dashboard.gcashPay', $billing->id).'" class="btn btn-default" style="background-color: #007bff; color: #ffffff;">Gcash Pay</a>
                    </div>
                </div> 
            ',
            'description'   => 'Using Gcash Pay has '.$fee.' service fee.',
            'progress'      => 100,
            'progressClass' => 'progress-bar bg-success',
            'hint'          => '
                <div class="" style="text-transform: none;">
                    Bill generation: '.$billing->created_at->format('D, M j, Y').' <br>
                    Cut-off date: '.Carbon::parse($billing->date_cut_off)->format('D, M j, Y').' <br>
                    Bill period: '.$billing->period.' <br>
                </div>
            ',
        ]);
        
        // $this->data['contents'][] = Widget::make([
        //     'type'          => 'progress_white',
        //     'class'         => 'card mb-2',
        //     'value'         => '
        //        <div class="row">
        //             <div class="col">'.currencyFormat(0).'</div>
        //        </div> 
        //     ',
        //     'description'   => __('Upcoming Bill'),
        //     'progress'      => 100,
        //     'progressClass' => 'progress-bar bg-success',
        //     'hint'          => '
        //         <div class="" style="text-transform: none;">
        //             Bill generation:  <br>
        //             Cut-off date:  <br>
        //             Bill period:  <br>
        //         </div>
        //     ',
        // ]);
    }

}
