@if (Auth::check() && Auth::user()->isCustomer())
    @include(backpack_view('customer.menu_items'))
@else
    @include(backpack_view('admin.menu_items'))
@endif
<x-backpack::menu-item title="Billing groupings" icon="la la-question" :link="backpack_url('billing-grouping')" />