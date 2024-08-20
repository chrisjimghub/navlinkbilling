<?php

namespace App\Http\Controllers\Admin;

use App\Models\Billing;
use Illuminate\Support\Carbon;
use App\Rules\UniqueMonthlyHarvest;
use App\Rules\ParticularsRepeatField;
use Backpack\CRUD\app\Library\Widget;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Controllers\Admin\Operations\HarvestedOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class WifiHarvestCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WifiHarvestCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { edit as traitEdit;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    

    use CrudExtend;
    use HarvestedOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Billing::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/wifi-harvest');
        CRUD::setEntityNameStrings('wifi harvest', 'wifi harvests');
    
        $this->userPermissions('wifi_harvests');

        $this->crud->query->whereHas('account', function ($query) {
            $query->harvestCrud();
        });
    }

    // TODO::
    // filters: or a filter field number that return total > then the inputed amount
        // Profit
        // Break Even
        // Net Loss

    /**
     * Define what happens when the List operation is loaded.
     * -
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->widgets();

        $this->accountColumnDetails(label: __('app.account'));

        $this->crud->column([
            'name' => 'date_start',
            'type' => 'date',
            'label' => __('app.wifi_harvest.date')
        ]);
        
        $this->crud->column([
            'name' => 'particulars',
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->particularDetails;
            },
            'escaped' => false
        ]);

        
        $this->currencyFormatColumn(fieldName: 'total', label: __('app.wifi_harvest.total'));

        $this->crud->column([
            'name' => 'account.installed_address',
            'label' => __('app.account_installed_address'),
            'limit' => 255,
        ]);

        $this->crud->column([
            'name' => 'billing_status_id',
            'label' => __('app.wifi_harvest.status'),
            'type' => 'closure',
            'function' => function ($entry) {

                $this->denyAccessIf($entry->id);
 
                return $entry->billingStatus->badge;
            },
            'escaped' => false
        ]);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation([
            'account_id' => [
                'required',
                'integer',
                new UniqueMonthlyHarvest(),
            ],
            'billing_type_id' => [
                'required',
                'exists:billing_types,id',
                'in:3',
            ],
            'particulars' => [
                new ParticularsRepeatField()
            ],
            'date_start' => [
                'required',
                'date',
                'date_format:Y-m-d',
            ]
        ], [
            'date_start.required' => __('app.wifi_harvest.date_required')
        ]);

        
        $this->accountFieldHarvest(label: __('app.account'));

        $this->crud->field([
            'name' => 'date_start',
            'label' => __('app.wifi_harvest.date'),
            'type' => 'date',
            'default' => date('Y-m-d'),
        ]);

        $this->crud->field([
            'name' => 'billing_type_id', 
            'type'  => 'hidden',
            'value' => 3, // Harvest Piso Wifi
        ]);

        $hint = '<span class="text-success">';
        if ($this->crud->getOperation() == 'create') {
            $hint .= __('app.wifi_harvest.particulars_hint');
        }else {
            $hint .= __('app.wifi_harvest.particulars_hint_edit');
        }
        $hint .= '</span>';

        $this->crud->field([   // repeatable
            'name'  => 'particulars',
            'label' => __('app.billing_particulars'),
            'type'  => 'repeat',
            'fields' => [ // also works as: "fields"
                [
                    'name'    => 'description',
                    'type'    => 'text',
                    'label'   => __('app.billing_description'),
                    'wrapper' => ['class' => 'form-group col-sm-6'],
                ],
                [
                    'name'    => 'amount',
                    'type'    => 'number',
                    'label'   => 'Amount',
                    'wrapper' => ['class' => 'form-group col-sm-6'],
                    'attributes' => ["step" => "any"],
                ],
            ],
            'hint' => $hint,
            'init_rows' => 0, // number of empty rows to be initialized, by default 1
            // 'min_rows' => 1, // minimum rows allowed, when reached the "delete" buttons will be hidden
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function widgets()
    {
        if ($this->crud->getOperation() == 'list') {

            $billing = Billing::whereHas('account', function ($query) {
                $query->harvestCrud();
            });

            $date = Carbon::now();
            $month = $date->month;
            $year = $date->year;

            $billingSchedule = clone $billing;
            $billingHarvested = clone $billing;
            $totalSchedule = $billingSchedule->whereDate('date_start', $date)->count();
            $harvested = $billingHarvested->whereDate('date_start', $date)->harvested()->count();
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $harvested.'/'.$totalSchedule,
                'description'   => 'Today\'s Schedule',
                'progress'      => widgetProgress($harvested, $totalSchedule), 
                'progressClass' => 'progress-bar bg-success',
                'hint'          => 'Piso Wi-Fi units scheduled for harvest today.',
            ];

            $billingForDaily = clone $billing;
            $total = $billingForDaily->whereDate('date_start', $date)->get()->sum('total');
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $this->currencyFormatAccessor($total),
                'description'   => 'Daily Income',
                'progress'      => widgetProgress(now()->hour, 24), 
                'progressClass' => 'progress-bar bg-info',
                'hint'          => 'Today\'s harvest for '.now()->format(dateHumanReadable()).'.',
            ];

            $billingForMonth = clone $billing;
            $total = $billingForMonth->whereMonth('date_start', $month)->get()->sum('total');
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $this->currencyFormatAccessor($total),
                'description'   => 'Monthly Income',
                'progress'      => widgetProgress(now()->day, now()->daysInMonth()), 
                'progressClass' => 'progress-bar bg-warning',
                'hint'          => 'This month\'s harvest for '.now()->format('M, Y').'.',
            ];

            $billingForYear = clone $billing;
            $total = $billingForYear->whereYear('date_start', $year)->get()->sum('total');
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $this->currencyFormatAccessor($total),
                'description'   => 'Annual Income',
                'progress'      => widgetProgress(now()->month, 12), 
                'progressClass' => 'progress-bar bg-dark',
                'hint'          => 'Total revenue for the year '.date('Y').'.',
            ];

            Widget::add()->to('before_content')->type('div')->class('row')->content($contents);
        }
    }

    public function edit($id)
    {
        $this->denyAccessIf($id);

        $response = $this->traitEdit($id);

        return $response;
    }

    public function destroy($id)
    {
        $this->denyAccessIf($id);

        $response = $this->traitDestroy($id);

        return $response;
    }

    private function denyAccessIf($id)
    {
        $bill = Billing::findOrFail($id);

        if ($bill->isHarvested()) { 
            $this->crud->denyAccess(['update', 'delete']);
        }
    }
}
