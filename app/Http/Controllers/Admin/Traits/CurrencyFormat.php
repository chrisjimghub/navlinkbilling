<?php

namespace App\Http\Controllers\Admin\Traits;

trait CurrencyFormat {

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
        ]);
    }

    public function currencyFormatField($fieldName)
    {
        return $this->currencyFormat('field', $fieldName);
    }
    
    public function currencyFormatColumn($fieldName)
    {
        return $this->currencyFormat('column', $fieldName);
    }

    public function currencyFormatAccessor($amount)
    {
        // return $amount;

        return self::CURRENCY_PREFIX . number_format($amount, 0, '.', ',');
    }
}