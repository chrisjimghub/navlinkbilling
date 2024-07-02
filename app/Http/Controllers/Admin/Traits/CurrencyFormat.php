<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Http\Controllers\Admin\Traits\UtilityHelper;

trait CurrencyFormat {

    use UtilityHelper;

    const CURRENCY_PREFIX = '₱ ';
    

    public function currencyFormat($modifyType, $fieldName)
    {
        $modifyType = ucfirst($modifyType);

        return $this->crud->{'modify'.$modifyType}($fieldName, [
            'type'          => 'number',
            'prefix'        => self::CURRENCY_PREFIX,
            // 'suffix'     => ' PHP',
            'decimals'   => 2,
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
        // return $amount;

        return self::CURRENCY_PREFIX . number_format($amount, 0, '.', ',');
    }

    public function currencyRound($amount)
    {
        return round($amount, 2);
    }
}