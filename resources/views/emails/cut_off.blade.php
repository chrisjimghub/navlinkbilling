@component('mail::message')
# Dear Mr./Ms. {{ $billing->account->customer->first_name }} {{ $billing->account->customer->last_name }},

Good day, 

May we follow up the payment for the month of {{ $billing->month }} {{ $billing->year }} to avoid disconnection.

PS. The system will automatically disconnect the internet today after 12 PM.

Thank you and have a good day!

@endcomponent
