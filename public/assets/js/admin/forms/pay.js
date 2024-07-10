if (typeof pay != 'function') {
    $("[data-button-type=pay]").unbind('click');

    function pay(button) {
        // ask for confirmation before deleting an item
        // e.preventDefault();
        var button = $(button);
        var route = button.attr('data-route');

        swal({
            title: "Warning",
            text: "Are you sure you want to mark this item paid?",
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
                    text: "Pay",
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
                        if (result.msg) {
                            
                            if (typeof crud !== 'undefined') {
                                crud.table.ajax.reload();
                            }

                            // Show a success notification bubble
                            new Noty({
                                type: "success",
                                text: result.msg
                            }).show();

                            // Hide the modal, if any
                            $('.modal').modal('hide');
                        } else {
                            // if the result is an array, it means 
                            // we have notification bubbles to show
                            if (result instanceof Object) {
                                // trigger one or more bubble notifications 
                                Object.entries(result).forEach(function(entry, index) {
                                var type = entry[0];
                                entry[1].forEach(function(message, i) {
                                    new Noty({
                                        type: type,
                                        text: message
                                    }).show();
                                });
                                });
                            } else {// Show an error alert
                                swalError("There\'s been an error. Your item might not have been marked as paid.")
                            }			          	  
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
            });
    }
}