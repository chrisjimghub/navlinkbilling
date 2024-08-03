{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>


<x-backpack::menu-dropdown title="Billing" icon="las la-folder-open">
    {{-- <x-backpack::menu-dropdown-header title="Authentication" /> --}}
    <x-backpack::menu-dropdown-item title="Make Payment" icon="las la-credit-card" :link="backpack_url('test')" />
    <x-backpack::menu-dropdown-item title="History" icon="las la-file-invoice" :link="backpack_url('history')" />
</x-backpack::menu-dropdown>