@if (Auth::check() && Auth::user()->isCustomer())
    @include(backpack_view('customer.menu_items'))
@else
    @include(backpack_view('admin.menu_items'))
@endif