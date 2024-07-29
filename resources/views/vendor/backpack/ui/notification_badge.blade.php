@php
    $unreadNotificationsCount = backpack_user()->unreadNotifications()->count();
@endphp

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url($menu->url) }}">
        <i class="{{ $menu->icon }} nav-icon"></i> 
        {!! $menu->label !!}        
        <small
            class="unreadnotificationscount badge badge-secondary pull-right {{($unreadNotificationsCount)? 'bg-primary' : 'bg-secondary'}}"
            data-toggle="tooltip"
            title="{{ $unreadNotificationsCount }} unread notifications"
            >{{ $unreadNotificationsCount }}
        </small>
    </a>
</li>


@push('after_styles')
<style type="text/css">
	.unreadnotificationscount {
		font-size: 60% !important;
	}
</style>
@endpush


@if(config('backpack.databasenotifications.enable_ajax_count'))
	@push('after_scripts')
		<script>
			var fetchUnreadCount = function() {
				if(window.disableNotificationAjax) return false;
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
						@if(config('backpack.databasenotifications.enable_toasts'))
						if(data.last_notification && prevCount < data.count) {
							let type = ['success', 'warning', 'error', 'info'].includes(data.last_notification.type) ? data.last_notification.type : "info";
							var message = data.last_notification.message;

							// if message_long is present, show that in the notification too
							if (data.last_notification.message_long !== null) {
								message = '<strong>' + data.last_notification.message + '</strong><br>' + data.last_notification.message_long;
							}

							new Noty({
								type: type,
								text: message,
								timeout: 10000,
								closeWith: ['button'],
								buttons: [
									Noty.button('See All Notifications', 'ml-2 btn btn-default btn-sm', function () {
										window.location = '{{backpack_url('notification')}}';
									}, {id: 'button1', 'data-status': 'ok'})
								]
							}).show();
						}
						@endif
						setTimeout(fetchUnreadCount, 1000);
					});
			}
			setTimeout(fetchUnreadCount, 2000)
		</script>
	@endpush
@endif