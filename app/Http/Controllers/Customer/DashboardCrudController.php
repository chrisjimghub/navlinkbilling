<?php

namespace App\Http\Controllers\Customer;

use App\Models\Account;
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
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customer/dashboard');

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

        $unpaidBills = Billing::monthly()
                        ->notPaid() // this scope is different from unPaid check model 
                        ->get();
                        
        foreach ($unpaidBills as $billing)  {
            $this->currentBillCard($billing);
        }

        $accounts = Account::where('customer_id', auth()->user()->customer_id)
            ->connected()
            ->whereDoesntHave('billings', function ($query) {
                $query->unpaid();
            })
            ->get();
        
        foreach ($accounts as $account) {
            $this->noCurrentBillCard($account);
        }

        return view(backpack_view('customer.dashboard'), $this->data);
    }

    public function currentBillCard(Billing $billing)
    {
        $fee = $this->totalWithPaymongoServiceCharge($billing->total) - $billing->total;
        $fee = currencyFormat($fee);

        $progressClass = 'bg-info';
        $button = '<a href="'.route('dashboard.gcashPay', $billing->id).'" class="btn btn-default" style="background-color: #007bff; color: #ffffff;">'.__('app.gcash_button_pay').'</a>';

        if ($billing->isPending()) {
            $button = '<a href="'.route('dashboard.gcashPay', $billing->id).'" class="btn btn-outline btn-warning" style="">'.__('app.gcash_button_pending').'</a>';
            $progressClass = 'bg-warning';
        }

        $this->data['contents'][] = Widget::make([
            'type'          => 'progress_white',
            'class'         => 'card mb-2',
            'value'         => '
                <div class="row">
                    <div class="col">'.currencyFormat($billing->total).'</div>
                    <div class="col">
                        '.$button.'
                    </div>
                </div> 
            ',
            // 'description'   => 'Using Gcash Pay has '.$fee.' service fee.',
            'description'   => '
                                <span class="text-danger">
                                    Using Gcash Pay has '.$fee.' service fee.
                                </span>
                                <hr class="mb-2 mt-1">'.$billing->account->details,
            'progress'      => 100,
            'progressClass' => 'progress-bar '.$progressClass,
            'hint'          => '
                <div class="" style="text-transform: none;">
                    Bill generation: '.$billing->created_at->format('D, M j, Y').' <br>
                    Cut-off date: '.Carbon::parse($billing->date_cut_off)->format('D, M j, Y').' <br>
                    Bill period: '.$billing->period.' <br>
                </div>
            ',
        ]);
    }

    public function noCurrentBillCard(Account $account)
    {
        $sub = strtolower($account->subscription->name);
        $period = null;

        if ($sub == 'fiber') {
            $period = fiberBillingPeriod(now());
        }elseif ($sub == 'p2p') {
            $period = p2pBillingPeriod(now());
        }else {
            return;
        }

        // before we proceed let's check first if the user already paid this month's bill
        // if the user already paid it then dont show the card.
        $exists = Billing::
            withinBillingPeriod($period['date_start'], $period['date_end'])
            ->where('account_id', $account->id)
            ->where(function ($query) {
                $query->paid()
                    ->orWhere(function ($subQuery) {
                        $subQuery->pending();
                    });
            })
            ->exists();
            

        if ($exists) {
            return;
        }


        $cutOff = Carbon::parse($period['date_cut_off'])->format('D, M j, Y');
        $billPeriod= Carbon::parse($period['date_start'])->format(dateHumanReadable()) . ' - '.
                    Carbon::parse($period['date_end'])->format(dateHumanReadable());

        $subDays = (int) Setting::get('days_before_generate_bill');
        $dateGenerate = Carbon::parse($period['date_end'])->subDays($subDays)->format('D, M j, Y');

        $this->data['contents'][] = Widget::make([
            'type'          => 'progress_white',
            'class'         => 'card mb-2',
            'value'         => '
               <div class="row">
                    <div class="col">'.__('app.customer_portal.next_bill').'</div>
               </div> 
            ',
            'description'   => $account->details,
            'progress'      => 100,
            'progressClass' => 'progress-bar bg-dark',
            'hint'          => '
                <div class="" style="text-transform: none;">
                    Bill generation: '.$dateGenerate.' <br>
                    Cut-off date: '.$cutOff.' <br>
                    Bill period: '.$billPeriod.' <br>
                </div>
            ',
        ]);
    }

}
