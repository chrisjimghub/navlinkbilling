<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Http\Controllers\Admin\Traits\UtilityHelper;

trait CurrencyFormat {

    use UtilityHelper;


    public function currencyFormat($modifyType, $fieldName)
    {
        $modifyType = ucfirst($modifyType);

        return $this->crud->{'modify'.$modifyType}($fieldName, [
            'type'          => 'number',
            'prefix'        => config('app-settings.currency_prefix'),
            // 'suffix'     => ' PHP',
            'decimals'   => config('app-settings.decimal_precision'),
            'dec_point'     => '.',
            'thousands_sep' => ',',
            
            // dont add wrapper here, or it will affect or override other fields using this, if you want to
            // dont forget to include form control
            // 'wrapper' => [
            //     'class' => 'text-success'
            // ]
        ]);
    }

    public function currencyFormatField($fieldName)
    {
        return $this->currencyFormat('field', $fieldName);
    }
    
    public function currencyFormatColumn($fieldName, $label = null)
    {
        if (!$this->listColumnExist($fieldName)) {
            $this->crud->column($fieldName);
        }

        $this->currencyFormat('column', $fieldName);
    
        if ($label != null) {
            $this->crud->modifyColumn($fieldName, [
                'label' => $label,
            ]);
        }
    }

    public function currencyFormatAccessor($amount, $prefix = true)
    {
        $amount = $this->currencyRound($amount);
        
        if (!$prefix) {

            return number_format($amount, config('app-settings.decimal_precision'), '.', ',');
        }

        return config('app-settings.currency_prefix') . number_format($amount, config('app-settings.decimal_precision'), '.', ',');
    }

    public function currencyRound($amount)
    {
        return round($amount, config('app-settings.decimal_precision'));
    }
    
}