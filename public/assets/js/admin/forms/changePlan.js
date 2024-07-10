if (typeof changePlanModal != 'function') {
    function buildOptionGroupHtml(data) {
        var html;

        // Iterating over the outer object
        for (let location in data) {
            html += '<optgroup label="'+location+'">';
            
            // Iterating over the inner object for each location
            for (let id in data[location]) {
                var label = data[location][id];

                html += '<option value="'+id+'" data-location="'+location+'">'+label+'</option>';
            }
            
            html += '</optgroup>';
        }

        return html;
    }
}

if (typeof changePlanModal != 'function') {
    function changePlanModal(button) {
        var billingId = button.getAttribute('data-billing-id');
        var routeFetchOptions = button.getAttribute('data-route-fetch-options');
        
        // Clear any existing modals with the same ID if they exist
        $('#changePlan-' + billingId).remove();

        var modalHTML = `
            <div class="modal fade" id="changePlan-${billingId}" tabindex="-1" role="dialog" aria-labelledby="changePlanLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePlanLabel">Change Planned Application</h5>
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


                            <div class="form-group required" element="div" bp-field-wrapper="true" bp-field-name="planned_application_id" bp-field-type="select_grouped_planned_application" bp-section="crud-field">
                                <label for="planned_application">
                                    <strong>Planned Application</strong>
                                    <span class="text-danger">*</span>
                                </label>


                                <div class="spinner-border spinner-border-sm d-none" role="status" id="spinner">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                
                                <div class="input-group">
                                    
                                    <select id="planned_application_id" name="planned_application_id" class="form-control form-select">
                                        <!-- Your select options here -->
                                        <option>Loading...</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="change_date_start-${billingId}">
                                    <strong>Change Date Start</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="change_date_start-${billingId}" required>
                            </div>
                            
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <a 
                                href="javascript:void(0)" 
                                onclick="changePlannedApplication(this)" 
                                data-button-type="serviceInterrupt"
                                data-route="${button.getAttribute('data-route')}" 
                                data-billing-id="${billingId}"
                                class="btn btn-success"
                            >
                                <i class="las la-save"></i> Save
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append the modal HTML to the body
        $('body').append(modalHTML);
        
        // Show the modal
        $('#changePlan-' + billingId).modal('show');

        // ajax get planned App options
        $.ajax({
            type: "GET",
            url: routeFetchOptions,
            beforeSend: function() {
                showSpinner();
            },
            success: function (options) {
                // console.log(options)

                // Create the modal HTML structure

                // TODO:: must make his current plan app as default or selected

                $('#planned_application_id').html(buildOptionGroupHtml(options));

                hideSpinner();
            },
            error: function () {
                swalError('Error fetching planned application options.')
            }
        });


        // Remove the modal from the DOM when it's closed
        $('#changePlan-' + billingId).on('hidden.bs.modal', function () {
            $(this).remove();
        });
    }
}