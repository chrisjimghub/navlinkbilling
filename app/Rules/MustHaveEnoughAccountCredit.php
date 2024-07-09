<?php

namespace App\Rules;

use App\Http\Controllers\Admin\Traits\CurrencyFormat;
use Closure;
use App\Models\Account;
use Illuminate\Contracts\Validation\ValidationRule;

class MustHaveEnoughAccountCredit implements ValidationRule
{
    use CurrencyFormat;

    protected $account;
    protected $amountToPay;

    public function __construct(Account $account, $amountToPay)
    {
        $this->account = $account;
        $this->amountToPay = $amountToPay;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // failed validation if no remaining credit
        if ($this->account->remaining_credits < $this->amountToPay) {
            $fail($this->message($this->account->remaining_credits));
        }
    }

    public function message($credit)
    {
        return 'This account does not have enough credit to complete the transaction. <br>Current balance: ' . $this->currencyFormatAccessor($credit) . ' credits.';
    }
}
