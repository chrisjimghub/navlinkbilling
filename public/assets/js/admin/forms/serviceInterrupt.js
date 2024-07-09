if (typeof serviceInterruptModal != 'function') {
    function serviceInterruptModal(button) {
        var billingId = button.getAttribute('data-billing-id');
        
        // Clear any existing modals with the same ID if they exist
        $('#serviceInterruptModal-' + billingId).remove();

        // Create the modal HTML structure
        var modalHTML = `
            <div class="modal fade" id="serviceInterruptModal-${billingId}" tabindex="-1" role="dialog" aria-labelledby="serviceInterruptModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="serviceInterruptModalLabel">Service Interruption</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="account_id-${billingId}" value="${button.getAttribute('data-account-id')}">
                            <div class="form-group">
                                <label for="account_details">
                                    <strong>Account</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="account_details" name="account_details" value="${button.getAttribute('data-account-details')}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="date_start-${billingId}">
                                    <strong>Date Start</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="date_start-${billingId}" required>
                            </div>
                            <div class="form-group">
                                <label for="date_end-${billingId}">
                                    <strong>Date End</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="date_end-${billingId}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <a 
                                href="javascript:void(0)" 
                                onclick="serviceInterrupt(this)" 
                                data-button-type="serviceInterrupt"
                                data-route="${button.getAttribute('data-route')}" 
                                data-billing-id="${billingId}"
                                class="btn btn-success"
                            >
                                <i class="las la-exclamation-triangle"></i> Save
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append the modal HTML to the body
        $('body').append(modalHTML);

        // Show the modal
        $('#serviceInterruptModal-' + billingId).modal('show');

        // Remove the modal from the DOM when it's closed
        $('#serviceInterruptModal-' + billingId).on('hidden.bs.modal', function () {
            $(this).remove();
        });
    }
}

if (typeof serviceInterrupt != 'function') {
    function serviceInterrupt(button) {
        var billingId = button.getAttribute('data-billing-id');
        var route = button.getAttribute('data-route');
        
        // Debug: Print current modal field values before capturing
        // console.log('Before capturing values:');
        // console.log({
        //     'date_start': $('#date_start-' + billingId).val(),
        //     'date_end': $('#date_end-' + billingId).val(),
        //     'account_id': $('#account_id-' + billingId).val()
        // });

        // Capture the current values from the modal input fields
        var dateStart = $('#date_start-' + billingId).val();
        var dateEnd = $('#date_end-' + billingId).val();
        var accountId = $('#account_id-' + billingId).val();

        // console.log('After capturing values:');
        // console.log({
        //     billingId,
        //     route,
        //     dateStart,
        //     dateEnd,
        //     accountId
        // });

        $.ajax({
            url: route,
            type: 'PUT',
            data: {
                date_start: dateStart,
                date_end: dateEnd,
                account_id: accountId,
            },
            success: function(result) {
                // console.log('Success:', result);

                if (typeof crud !== 'undefined') {
                    crud.table.ajax.reload();
                }

                if (result) {
                    new Noty({
                        type: "success",
                        text: result.msg
                    }).show();
                }

                // Close the modal
                $('#serviceInterruptModal-'+billingId).modal('hide');

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
                }
            }
        });

        
    }
    
}