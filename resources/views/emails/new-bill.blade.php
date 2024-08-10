
@component('mail::message')
# Dear Mr./Ms. {{ $billing->account->customer->first_name }} {{ $billing->account->customer->last_name }}, 
We enclose your internet subscription fee for {{ $billing->date_end }}, amounting to {{ number_format($billing->total, 2) }} pesos. Please note that your payment for the month of {{ $billing->month }} will be due on {{ $billing->date_cut_off }}.

**Account Disconnection:** Every {{ ordinal($billing->account->billingGrouping->bill_cut_off_notification_days_before_cut_off_date) }} day after due date.

Failure to pay the amount will result in automatic disconnection. The activation fee is worth 250 pesos. Process your payment at our office, or pay via GCash, Bank Transfer, or Palawan. 
**GCash #**: 09603060155 - NARVYL BAGUIO

**NOTE:** Please secure a screenshot of your digital receipt and send it to our Facebook: Nav Link

THANK YOU!

**PS.** This is only a reminder for your due date.

@endcomponent
