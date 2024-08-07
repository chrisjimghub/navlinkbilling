@if ($crud->hasAccess('generatePortalAccount'))
<a href="javascript:void(0)" onclick="generatePortalAccountEntry(this)"
	data-route="{{ url($crud->route.'/'.$entry->getKey().'/generate-portal-account') }}"
	class="btn btn-sm btn-link text-success" data-button-type="generatePortalAccount">
	<i class="las la-plus-circle"></i>
	{{ __('Portal Account') }}
</a>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>
	if (typeof generatePortalAccountEntry != 'function') {
	  function generatePortalAccountEntry(button) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		var route = $(button).attr('data-route');

		swal({
		  title: "{!! trans('backpack::base.warning') !!}",
		  text: "Create new account or generate password?",
		  icon: "warning",
		  buttons: {
		  	cancel: {
				text: "{!! trans('backpack::crud.cancel') !!}",
				value: null,
				visible: true,
				className: "bg-secondary",
				closeModal: true,
			},
			delete: {
				text: "Yes, Please!",
				value: true,
				visible: true,
				className: "bg-success",
				},
			},
		  dangerMode: true,
		}).then((value) => {
			if (value) {
				$.ajax({
			      url: route,
			      type: 'POST',
			      success: function(result) {
					// console.log(result);
					if (result.msg) {
						if (typeof crud !== 'undefined') {
							crud.table.ajax.reload();
						}
					
						// Show a success notification bubble
						new Noty({
							type: result.type,
							text: result.msg
						}).show();
					
						showUserCredentialsModal(result.email, result.password);
					} 
			      },
			      error: function(xhr) {
                        // Handle validation errors or other errors
                        if (xhr.status === 422) {
                            // Display validation errors to the user
                            var errors = xhr.responseJSON.errors;
                            errors.forEach(function(errorMsg) {
                                new Noty({
                                    text: errorMsg,
                                    type: 'error'
                                }).show();
                            });
                        } else {
                            // Handle other types of errors
                            swalError('Please contact the administrator.')
                        }
                    }
			  });
			}
		});

      }
	}
	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('generatePortalAccountEntry');
</script>




<script>
if (typeof showUserCredentialsModal != 'function') {
	function showUserCredentialsModal(email, password) {
        // Create modal HTML
        const modalHTML = `
            <div class="modal fade" id="userCredentialsModal" tabindex="-1" aria-labelledby="userCredentialsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userCredentialsModalLabel">Customer Portal Account</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="modal-email-input">Email:</label>
                                <input type="email" id="modal-email-input" class="form-control" value="${email}" readonly>
                            </div>
                            <div class="form-group mt-3">
                                <label for="modal-password-input">Password:</label>
                                <div class="input-group">
                                    <input type="password" id="modal-password-input" class="form-control" value="${password}" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="button" id="toggle-modal-password-visibility">
                                            <i class="las la-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append modal to body
        document.body.insertAdjacentHTML('beforeend', modalHTML);

        // Show the modal
        $('#userCredentialsModal').modal('show');

        // Toggle password visibility
        document.getElementById('toggle-modal-password-visibility').addEventListener('click', function () {
            var passwordInput = document.getElementById('modal-password-input');
            var icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Remove modal from DOM when hidden
        $('#userCredentialsModal').on('hidden.bs.modal', function () {
            document.getElementById('userCredentialsModal').remove();
        });
    }
}
</script>



@if (!request()->ajax()) @endpush @endif