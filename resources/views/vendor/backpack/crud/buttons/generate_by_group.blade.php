@if ($crud->hasAccess('generateByGroup'))
    <a href="javascript:void(0)" class="btn btn-success" data-style="zoom-in" data-toggle="modal" data-target="#generateByGroup">
        <i class="las la-satellite-dish"></i>
        {{ __('Generate Bill') }}
    </a>

    @push('before_scripts')
        <div class="modal fade" id="generateByGroup" tabindex="-1" role="dialog" aria-labelledby="generateByGroup" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="generateByGroup">{{ __('Generate Bill') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">


                    <div class="form-group">
                        <label><strong>{{ __('Billing Groupings:') }}</strong></label>
                        <ul class="list-group">

                            @foreach(modelInstance('BillingGrouping')::all() as $group)
                                <li class="list-group-item">
                                    <div class="form-check">
                                        <input 
                                        class="form-check-input" type="checkbox" id="{{ $group->name }}" name="generate_bill[]" value="{{ $group->id }}">
                                        <label class="form-check-label" for="fiber">{{ $group->name }}</label>
                                    </div>
                                </li>
                            @endforeach
                            
                        </ul>
                    </div>
                        

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" 
                            data-route="{{ url($crud->route) . '/generate-by-group' }}"
                            onclick="generateByGroup(this)
                        ">
                            <i class="las la-save"></i> Create
                        </button>

                    </div>
                </div>
            </div>
        </div>

        @include('crud::inc.loader')
    @endpush



    @push('after_scripts')
        <script>
            if (typeof generateByGroup != 'function') {
                function generateByGroup(button) {
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
                        beforeSend: function() {
                            // Show the loader before sending the request
                            $("#loading-screen").show();
                        },
                        success: function(response) {
                            console.log(response)
                            if (response.msg) {
                                new Noty({
                                    text: response.msg,
                                    type: response.type
                                }).show();
                            }

                            // Handle success response, if needed
                            // console.log('Form data submitted successfully:', response);
                            $('#generateByGroup').modal('hide'); // Close the modal

                            // Uncheck all checked checkboxes with name="generate_bill[]"
                            $('input[name="generate_bill[]"]:checked').prop('checked', false);

                            if (typeof crud !== 'undefined') {
                                crud.table.ajax.reload();
                            }

                            $("#loading-screen").hide();
                        },
                        error: function(xhr, status, error) {
                            $("#loading-screen").hide();

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
        
@endif