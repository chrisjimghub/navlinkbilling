@if ($crud->hasAccess('apiConfigSettings'))
    <a href="javascript:void(0)" class="btn btn-warning" data-toggle="modal" data-target="#apiConfigSettingsModal">
        <i class="la la-cog"></i> 
        {{ __('API Config Settings') }}
    </a>
@endif


@push('before_scripts')
    
<div class="modal fade" id="apiConfigSettingsModal" tabindex="-1" role="dialog" aria-labelledby="apiConfigSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="apiConfigSettingsModalLabel">{{ __('API Config Settings') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="url">Url</label>
                    <input type="text" class="form-control" value="{{ Setting::get('raisepon_url') ?? '' }}" id="url" placeholder="Enter url">
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" value="{{ Setting::get('raisepon_username') ?? '' }}" id="username" placeholder="Enter username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" value="{{ Setting::get('raisepon_password') ?? '' }}" id="password" placeholder="Enter password">
                </div>
                

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                <button type="button" class="btn btn-success" 
                data-route="{{ route('raisepon2.apiTestConnection') }}"
                onclick="apiTestConnection(this)">
                    <i class="las la-server"></i></i> Test Connection
                </button>

                <button type="button" class="btn btn-warning" 
                data-route="{{ route('raisepon2.apiConfigSettings') }}"
                onclick="apiConfigSettings(this)">
                    <i class="las la-save"></i> Save
                </button>

            </div>
        </div>
    </div>
</div>


@endpush


@push('after_scripts')
<script>
if (typeof apiConfigSettings != 'function') {
    function apiConfigSettings(button) {
        var route = button.getAttribute('data-route');

        // Collect data from modal inputs
        var formData = {
            url: $('#url').val(),
            username: $('#username').val(),
            password: $('#password').val(),
        };

        // Send AJAX POST request
        $.ajax({
            type: 'POST',
            url: route, 
            data: formData,
            success: function(response) {
                if (response.msg) {
                    new Noty({
                        type: response.type,
                        text: response.msg,
                    }).show();
                }

                // Handle success response, if needed
                // console.log('Form data submitted successfully:', response);
                $('#apiConfigSettingsModal').modal('hide'); // Close the modal
            },
            error: function(xhr, status, error) {
                // console.log('Error:', xhr.responseJSON.errors);
                // Handle validation errors or other errors
                if (xhr.status === 422) {
                    // Display validation errors to the user
                    var errors = xhr.responseJSON.errors;
                    errors.forEach(function(errorMsg) {
                        // Example: Display error messages using a notification library
                        new Noty({
                            text: errorMsg,
                            type: 'error'
                        }).show();
                    });
                } else {
                    // Handle other types of errors
                    new Noty({
                        text: 'Error submitting form. Please try again.',
                        type: 'error'
                    }).show();
                }
            }
        });
    }
}


if (typeof apiTestConnection != 'function') {
    function apiTestConnection(button) {
        var route = button.getAttribute('data-route');

        // Collect data from modal inputs
        var formData = {
            url: $('#url').val(),
            username: $('#username').val(),
            password: $('#password').val(),
        };

        // Send AJAX POST request
        $.ajax({
            type: 'POST',
            url: route, 
            data: formData,
            success: function(response) {
                console.log(response)

                if (response.msg) {
                    new Noty({
                        text: response.msg,
                        type: 'success'
                    }).show();
                }else if (response.error) {
                    new Noty({
                        text: response.error,
                        type: 'error'
                    }).show();
                }

                
            },
            error: function(xhr, status, error) {
                // console.log('Error:', xhr.responseJSON.errors);
                // Handle validation errors or other errors
                if (xhr.status === 422) {
                    // Display validation errors to the user
                    var errors = xhr.responseJSON.errors;
                    errors.forEach(function(errorMsg) {
                        // Example: Display error messages using a notification library
                        new Noty({
                            text: errorMsg,
                            type: 'error'
                        }).show();
                    });
                } else {
                    // Handle other types of errors
                    new Noty({
                        text: 'Error submitting form. Please try again.',
                        type: 'error'
                    }).show();
                }
            }
        });
    }
}
</script>
@endpush