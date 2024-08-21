{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>


<x-backpack::menu-dropdown title="Accounts" icon="las la-folder-open">
    {{-- <x-backpack::menu-dropdown-header title="Authentication" /> --}}
    <x-backpack::menu-dropdown-item title="Invoice History" icon="las la-file-invoice" :link="backpack_url('billing-history')" />

    @php
        $customerId = auth()->user()->customer_id;
        $hasPisoWifi = modelInstance('Account')->harvestCrud()->where('customer_id', $customerId)->exists();
    @endphp

    @if($hasPisoWifi)
        <x-backpack::menu-dropdown-item title="Piso Wifi" icon="las la-credit-card" :link="backpack_url('piso-wifi')" />
    @endif

</x-backpack::menu-dropdown>