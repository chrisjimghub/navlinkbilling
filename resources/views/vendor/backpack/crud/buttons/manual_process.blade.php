@if ($crud->hasAccessToAny([
    'manual_generate_bill', 
]))


<div class="btn-group">
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="las la-recycle"></i>
        {{ __('Manual Process') }}
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">

        @if ($crud->hasAccess('manual_generate_bill'))
            <li>
                <a href="javascript:void(0)" class="btn btn-sm btn-link" data-toggle="modal" data-target="#generateBillModal">
                    <i class="las la-plus"></i>
                    {{ __('Generate Bill') }}
                </a>
            </li>
        @endif    
    </ul>
</div>       

@endif


{{-- 
    since modal bill settings is already using @push before_scripts,
    i can only use this after_scripts, so either of the modal backdrop wont get broken
 --}}
@push('after_scripts')

<div class="modal fade" id="generateBillModal" tabindex="-1" role="dialog" aria-labelledby="generateBillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateBillModalLabel">{{ __('Generate Bill') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

               


            <div class="form-group">
                <label><strong>{{ __('Account Subscription:') }}</strong></label>
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="form-check">
                            <input 
                                class="form-check-input" type="checkbox" id="fiber" name="generate_bill[]" value="fiber">
                            <label class="form-check-label" for="fiber">{{ __('FIBER') }}</label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="form-check">
                            <input 
                                class="form-check-input" type="checkbox" id="p2p" name="generate_bill[]" value="p2p">
                            <label class="form-check-label" for="p2p">{{ __('P2P') }}</label>
                        </div>
                    </li>
                </ul>
            </div>
                


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" 
                    data-route="{{ route('billing.manualGenerateBill') }}"
                    onclick="manualGenerateBill(this)
                ">
                    <i class="las la-save"></i> Create
                </button>

            </div>
        </div>
    </div>
</div>


<script>
if (typeof manualGenerateBill != 'function') {
    function manualGenerateBill(button) {
        var route = button.getAttribute('data-route');
        
        var generateBillList = $('input[name="generate_bill[]"]:checked').map(function() {
            return this.value;
        }).get();


        $.ajax({
            type: 'POST',
            url: route, 
            data: {
                generate_bill : generateBillList
            },
            success: function(response) {
                // console.log(response)

                if (response.msg) {
                    new Noty({
                        text: response.msg,
                        type: 'success'
                    }).show();
                }

                // Handle success response, if needed
                // console.log('Form data submitted successfully:', response);
                $('#generateBillModal').modal('hide'); // Close the modal

                if (typeof crud !== 'undefined') {
                    crud.table.ajax.reload();
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