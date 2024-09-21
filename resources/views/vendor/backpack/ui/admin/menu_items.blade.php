@php
    // Fetch all menus (both main and submenus) in one query
    $menus = \App\Models\Menu::orderBy('lft')->get()->groupBy('parent_id');
    $mainMenus = $menus->get(null, collect()); // Menus without a parent (main menus)
@endphp

@foreach ($mainMenus as $menu)
    {{-- Normal menu --}}
    @if ($menu->label && $menu->url)
        @if ($menu->permissions)
            @canany($menu->permissions)
                @if (!in_array('notifications_list', $menu->permissions))
                    <x-backpack::menu-item title="{{ $menu->label }}" icon="{{ $menu->icon }}" :link="backpack_url($menu->url)" />
                @else
                    @include('vendor.backpack.ui.notification_badge')
                @endif
            @endcanany
        @else
            <x-backpack::menu-item title="{{ $menu->label }}" icon="{{ $menu->icon }}" :link="backpack_url($menu->url)" />
        @endif
        {{-- Separator / header --}}
    @elseif($menu->label && !$menu->icon && !$menu->url)
        @if ($menu->permissions)
            @canany($menu->permissions)
                <x-backpack::menu-separator title="{{ $menu->label }}" />
            @endcanany
        @else
            <x-backpack::menu-separator title="{{ $menu->label }}" />
        @endif
    @endif

    {{-- Sub Menus --}}
    @php
        // Get submenus for this main menu
        $subMenus = $menus->get($menu->id, collect());
        $subMenusPermissions = $subMenus
            ->pluck('permissions')
            ->filter() // Remove null values
            ->flatten() // Flatten the array of arrays into a single array
            ->toArray(); // Convert the collection to an array if needed
    @endphp

    @if ($subMenus->isNotEmpty() && auth()->user()->canAny($subMenusPermissions))
        {{-- Submenu opening dropdown tag --}}
        <x-backpack::menu-dropdown title="{{ $menu->label }}" icon="{{ $menu->icon }}">
    @endif

    {{-- Submenu lists --}}
    @foreach ($subMenus as $subMenu)
        {{-- Normal submenu --}}
        @if ($subMenu->label && $subMenu->url)
            @if ($subMenu->permissions)
                @canany($subMenu->permissions)
                    <x-backpack::menu-dropdown-item title="{{ $subMenu->label }}" icon="{{ $subMenu->icon }}"
                        :link="backpack_url($subMenu->url)" />
                @endcanany
            @else
                <x-backpack::menu-dropdown-item title="{{ $subMenu->label }}" icon="{{ $subMenu->icon }}"
                    :link="backpack_url($subMenu->url)" />
            @endif
            {{-- Submenu separator / header --}}
        @elseif($subMenu->label && !$subMenu->icon && !$subMenu->url)
            @if ($subMenu->permissions)
                @canany($subMenu->permissions)
                    <x-backpack::menu-dropdown-header title="{{ $subMenu->label }}" />
                @endcanany
            @else
                <x-backpack::menu-dropdown-header title="{{ $subMenu->label }}" />
            @endif
        @endif
    @endforeach

    @if ($subMenus->isNotEmpty() && auth()->user()->canAny($subMenusPermissions))
        {{-- Submenu closing dropdown tag --}}
        </x-backpack::menu-dropdown>
    @endif
@endforeach
