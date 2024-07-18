$(document).ready(function() {
    if ($('#enable_auto_bill').prop('checked')) {
        $('.group_enable_auto_bill').show();
    } else {
        $('.group_enable_auto_bill').hide();
    }

    $('#enable_auto_bill').change(function() {
        if (this.checked) {
            $('.group_enable_auto_bill').show();
        } else {
            $('.group_enable_auto_bill').hide();
        }
    });


    // enable_auto_notification
    if ($('#enable_auto_notification').prop('checked')) {
        $('.group_enable_auto_notification').show();
    } else {
        $('.group_enable_auto_notification').hide();
    }

    $('#enable_auto_notification').change(function() {
        if (this.checked) {
            $('.group_enable_auto_notification').show();
        } else {
            $('.group_enable_auto_notification').hide();
        }
    });
});






if (typeof billSettings != 'function') {

    function billSettings(button) {
        var route = button.getAttribute('data-route');

        // Collect data from modal inputs
        var formData = {
            enable_auto_bill: $('#enable_auto_bill').prop('checked') ? 1 : 0,
            enable_auto_notification: $('#enable_auto_notification').prop('checked') ? 1 : 0,
            days_before_generate_bill: $('#days_before_generate_bill').val(),
            fiber_day_start: $('#fiber_day_start').val(),
            fiber_day_end: $('#fiber_day_end').val(),
            fiber_day_cut_off: $('#fiber_day_cut_off').val(),
            fiber_billing_period: $('#fiber_billing_period').val(),
            p2p_day_start: $('#p2p_day_start').val(),
            p2p_day_end: $('#p2p_day_end').val(),
            p2p_day_cut_off: $('#p2p_day_cut_off').val(),
            p2p_billing_period: $('#p2p_billing_period').val(),
            days_before_send_bill_notification: $('#days_before_send_bill_notification').val(),
            days_before_send_cut_off_notification: $('#days_before_send_cut_off_notification').val()
        };

        // Send AJAX POST request
        $.ajax({
            type: 'POST',
            url: route, 
            data: formData,
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
                $('#billSettingModal').modal('hide'); // Close the modal
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