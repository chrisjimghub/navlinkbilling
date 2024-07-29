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




@php
$unreadNotificationsCount = backpack_user()->unreadNotifications()->count();
@endphp

<a class="nav-link" href="{{ backpack_url('notification') }}">
	<i class="la la-bell fs-2 me-1">
		<small
            class="unreadnotificationscount badge badge-secondary pull-right {{($unreadNotificationsCount)? 'bg-primary' : 'bg-secondary'}}"
            data-toggle="tooltip"
            title="{{ $unreadNotificationsCount }} unread notifications"
            >{{ $unreadNotificationsCount }}
        </small>
	</i>
</a>

@push('after_styles')
<style type="text/css">
	.unreadnotificationscount {
		font-size: 60% !important;
	}
</style>
@endpush

@push('after_scripts')
    <script>
        var fetchUnreadCount = function() {
            fetch("{{route('notification.unreadCount')}}",
                {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    data.count = parseInt(data.count);
                    let prevCount;
                    let notificationCountEls = document.getElementsByClassName('unreadnotificationscount');
                    for (var i=0; i<notificationCountEls.length;i++) {
                        prevCount = parseInt(notificationCountEls[i].innerHTML);
                        notificationCountEls[i].innerHTML = data.count;
                    }
                    if(data.last_notification && prevCount < data.count) {
                        let type = ['success', 'warning', 'error', 'info'].includes(data.last_notification.type) ? data.last_notification.type : "info";
                        var message = data.last_notification.message;

                        new Noty({
                            type: type,
                            text: message,
                            // timeout: 600000,
                            timeout: 10000,
                            closeWith: ['button'],
                            buttons: [
                                Noty.button('See All Notifications', 'ml-2 btn btn-default btn-sm', function () {
                                    window.location = '{{backpack_url('notification')}}';
                                }, {id: 'button1', 'data-status': 'ok'})
                            ]
                        }).show();
                    }
                    setTimeout(fetchUnreadCount, 1000);
                });
        }
        setTimeout(fetchUnreadCount, 2000)
    </script>
@endpush