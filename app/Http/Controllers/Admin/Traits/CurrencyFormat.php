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
            'wrapper' => [
                'class' => 'text-success'
            ]
        ]);
    }

    public function currencyFormatField($fieldName)
    {
        return $this->currencyFormat('field', $fieldName);
    }
    
    public function currencyFormatColumn($fieldName)
    {
        if (!$this->listColumnExist($fieldName)) {
            $this->crud->column($fieldName);
        }

        return $this->currencyFormat('column', $fieldName);
    }

    public function currencyFormatAccessor($amount)
    {
        $amount = $this->currencyRound($amount);
        
        return config('app-settings.currency_prefix') . number_format($amount, config('app-settings.decimal_precision'), '.', ',');
    }

    public function currencyRound($amount)
    {
        return round($amount, config('app-settings.decimal_precision'));
    }
}