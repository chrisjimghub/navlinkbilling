@php
	$menus = \App\Models\Menu::whereNull('parent_id')->orderBy('lft')->get();
@endphp

@foreach ($menus as $menu)

    {{-- normal menu --}}
    @if($menu->label && $menu->url)

        @if($menu->permissions)
            @canany($menu->permissions)
                <x-backpack::menu-item 
                    title="{{ $menu->label }}" 
                    icon="{{ $menu->icon }}" 
                    :link="backpack_url($menu->url)" 
                />                
            @endcanany
        @else
            <x-backpack::menu-item 
                title="{{ $menu->label }}" 
                icon="{{ $menu->icon }}" 
                :link="backpack_url($menu->url)" 
            />
        @endif

    {{-- separator / header --}}
    @elseif($menu->label && !$menu->icon && !$menu->url)

        @if($menu->permissions)
            @canany($menu->permissions)
                <x-backpack::menu-separator 
                    title="{{ $menu->label }}" 
                />                
            @endcanany
        @else
            <x-backpack::menu-separator 
                title="{{ $menu->label }}" 
            />
        @endif

    @endif


    {{-- Sub Menu --}}
    @php
        $subMenus = \App\Models\Menu::where('parent_id', $menu->id)->orderBy('lft')->get();
    @endphp

    @if($subMenus->isNotEmpty())
          @php
              $subMenusPermissions = $subMenus->pluck('permissions')  
                                        ->filter()  // Remove null values
                                        ->flatten() // Flatten the array of arrays into a single array
                                        ->toArray(); // Convert the collection to an array if needed
          @endphp     

    @endif

    @if($subMenus->isNotEmpty() && auth()->user()->canAny($subMenusPermissions))
        {{-- subMenu opening dropdown tag --}}
        <x-backpack::menu-dropdown title="{{ $menu->label }}" icon="{{ $menu->icon }}">
    @endif

    {{-- subMenu lists --}}
    @foreach ($subMenus as $subMenu)

        {{-- normal subMenu --}}
        @if($subMenu->label && $subMenu->url)

            @if($subMenu->permissions)
                @canany($subMenu->permissions)  
                    <x-backpack::menu-dropdown-item 
                        title="{{ $subMenu->label }}" 
                        icon="{{ $subMenu->icon }}" 
                        :link="backpack_url($subMenu->url)" 
                    />             
                @endcanany
            @else
                <x-backpack::menu-dropdown-item 
                    title="{{ $subMenu->label }}" 
                    icon="{{ $subMenu->icon }}" 
                    :link="backpack_url($subMenu->url)" 
                />
            @endif

        {{-- subMenu separator / header --}}
        @elseif($subMenu->label && !$subMenu->icon && !$subMenu->url)

            @if($subMenu->permissions)
                @canany($subMenu->permissions)
                    <x-backpack::menu-dropdown-header 
                        title="{{ $subMenu->label }}" 
                    />    
                @endcanany
            @else
                <x-backpack::menu-dropdown-header 
                    title="{{ $subMenu->label }}" 
                />
            @endif

        @endif

    {{-- end subMenus foreach --}}
    @endforeach

    @if($subMenus->isNotEmpty() && auth()->user()->canAny($subMenusPermissions))
        {{-- subMenu closing dropdown tag --}}
        </x-backpack::menu-dropdown>
    @endif

@endforeach
<x-backpack::menu-item title="Notifications" icon="la la-question" :link="backpack_url('notification')" />