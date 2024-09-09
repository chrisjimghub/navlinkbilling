<script>
if (typeof paymentMethodsLists !== 'function') {
    function paymentMethodsLists(data) {
        var html =  `<option>-</option>`; // Initialize an empty string for the HTML

        // Loop through the object using Object.entries
        Object.entries(data).forEach(([id, label]) => {
            // Append an option tag for each key-value pair
            html += `<option value="${id}">${label}</option>`;
        });

        return html; // Return the generated HTML
    }
}

if (typeof payModal != 'function') {
    function payModal(button) {
        var billingId = button.getAttribute('data-billing-id');
        var particulars = JSON.parse(button.getAttribute('data-particulars'));
        var totalBlance = parseFloat(button.getAttribute('data-total'));
        
        var particularsTableRow = '';
        
        // Loop through each item in the particulars array
        particulars.forEach(function(item) {
            // Create a table row for each item
            var amount = item.amount;
            var formattedAmount = amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            var color = amount >= 0 ? 'text-info' : 'text-danger';

            particularsTableRow += '<tr>' +
                '<td class="font-weight-light">' + (item.description || '') + '</td>' +
                '<td class="'+color+'">' + (formattedAmount || '') + '</td>' +
                '</tr>';
        });


        var color = totalBlance >= 0 ? 'text-success' : 'text-danger';
        totalBlance = totalBlance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        // Append the formatted total balance to the table row
        particularsTableRow += '<tr>' +
            '<td class="font-weight-normal">Total Balance</td>' +
            '<td class="'+color+'">' + totalBlance + '</td>' +
            '</tr>';

        console.log(particularsTableRow);

        // Clear any existing modals with the same ID if they exist
        $('#pay-' + billingId).remove();


        var modalHTML = `
            <div class="modal fade" id="pay-${billingId}" tabindex="-1" role="dialog" aria-labelledby="changePlanLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePlanLabel">${button.getAttribute('title')}</h5>
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

                            <div class="form-group required">
                                <label for="table-particulars-${billingId}">
                                    <strong>Particulars</strong>
                                </label>

                                <table id="table-particulars-${billingId}" class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Description</th>
                                            <th scope="col">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${particularsTableRow}
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group required">
                                <label for="payment_method_id-${billingId}">
                                    <strong>Payment Methods</strong>
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">
                                    
                                    <select  onchange="handlePaymentMethodChange(this)" data-billing-id="${billingId}" id="payment_method_id-${billingId}" name="payment_method_id-${billingId}" class="form-control form-select">
                                        <!-- Your select options here -->
                                        <option>Loading...</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group bank-check-group" style="display: none;">
                                <label for="check_issued_date-${billingId}">
                                    <strong>Check Issued Date</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="check_issued_date-${billingId}" required>
                            </div>

                            <div class="form-group bank-check-group" style="display: none;">
                                <label for="check_number-${billingId}">
                                    <strong>Check Number</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="check_number-${billingId}" required>
                            </div>
                            
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <a 
                                href="javascript:void(0)" 
                                onclick="pay(this)" 
                                data-button-type="pay"
                                data-route="${button.getAttribute('data-route')}" 
                                data-billing-id="${billingId}"
                                class="btn btn-success"
                            >
                                <i class="las la-thumbs-up"></i> Pay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append the modal HTML to the body
        $('body').append(modalHTML);
        
        // Show the modal
        $('#pay-' + billingId).modal('show');

        var billingId = button.getAttribute('data-billing-id');
        var routeFetchOptions = button.getAttribute('data-route-fetch-options');

        // ajax get planned App options
        $.ajax({
            type: "GET",
            url: routeFetchOptions,
            success: function (options) {
                // console.log(options)
                $('#payment_method_id-' +billingId).html(paymentMethodsLists(options));
            },
            error: function () {
                swalError('Error fetching payment methods options.')
            }
        });


        // Remove the modal from the DOM when it's closed
        $('#pay-' + billingId).on('hidden.bs.modal', function () {
            $(this).remove();
        });
    }
}


if (typeof pay != 'function') {
    function pay(button) {
        var billingId = button.getAttribute('data-billing-id');
        var route = button.getAttribute('data-route');
        
        // Capture the current values from the modal input fields
        var paymentMethod = $('#payment_method_id-' + billingId).val();
        var checkIssuedDate = $('#check_issued_date-' + billingId).val();
        var checkNumber = $('#check_number-' + billingId).val();

        $.ajax({
            url: route,
            type: 'POST',
            data: {
                // billingId is already is in route so no need,
                paymentMethod: paymentMethod,
                checkIssuedDate: checkIssuedDate,
                checkNumber: checkNumber,
            },
            success: function(result) {
                if (result.msg) {
                    if (typeof crud !== 'undefined') {
                        crud.table.ajax.reload();
                    }
                    
                    new Noty({
                        type: result.type,
                        text: result.msg
                    }).show();
                }

                // Close the modal
                $('#pay-'+billingId).modal('hide');

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
                    swalError('Please contact the administrator.')
                }
            }
        });

        
    }
}


if (typeof handlePaymentMethodChange != 'function') {
    function handlePaymentMethodChange(button) {
        var billingId = button.getAttribute('data-billing-id');
        var selectedValue = $('#payment_method_id-' + billingId).val();

        if (selectedValue == 4) {
            $('.bank-check-group').show();
        } else {
            $('.bank-check-group').hide();
        }
    }

}


</script>