if (typeof sendNotificationEntry != 'function') {
    $("[data-button-type=sendNotification]").unbind('click');

    function sendNotificationEntry(button) {
        // ask for confirmation before resending a notification
        var button = $(button);
        var route = button.attr('data-route');
        var resend = button.attr('data-resend');
        // Function to handle the AJAX request
        function sendNotificationRequest() {
            $.ajax({
                url: route,
                type: 'POST',
                success: function(result) {
                    console.log(result);
                    if (result == 1) {
                        // Reload the CRUD table
                        if (typeof crud !== 'undefined') {
                            crud.table.ajax.reload();
                        }

                        // Show a success notification
                        new Noty({
                            type: "success",
                            text: "<strong>Notification Sent</strong><br>The bill was sent successfully."
                        }).show();

                        // Hide any modal
                        $('.modal').modal('hide');
                    } else if (result.msg) {
                        // Show an error notification with the received message
                        new Noty({
                            type: "warning",
                            text: "<strong>Notification Not Sent</strong><br>" +result.msg
                        }).show();

                        swalError(result.msg);

                    } else {
                        swalError("There\'s been an error. Your item might not have been processed.");
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
                        swalError('Please contact the administrator.')
                    }
                }
            });
        }

        // Check if 'resend' is not empty
        if (!resend.trim()) {
            // If 'resend' is empty, directly trigger the AJAX request without showing SweetAlert
            sendNotificationRequest();
        } else {
            // If 'resend' is not empty, show SweetAlert confirmation
            swal({
                title: "Warning",
                text: "Are you sure you want to resend the notification?",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "Cancel",
                        value: null,
                        visible: true,
                        className: "bg-secondary",
                        closeModal: true,
                    },
                    delete: {
                        text: "Resend",
                        value: true,
                        visible: true,
                        className: "bg-success",
                    },
                },
                dangerMode: true,
            }).then((value) => {
                if (value) {
                    // Perform AJAX request
                    sendNotificationRequest();
                }
            });
        }
    }
}