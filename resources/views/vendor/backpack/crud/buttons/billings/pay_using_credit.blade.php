<script>
    if (typeof payUsingCredit != 'function') {
    $("[data-button-type=payUsingCredit]").unbind('click');

    function payUsingCredit(button) {
        // ask for confirmation before deleting an item
        // e.preventDefault();
        var button = $(button);
        var route = button.attr('data-route');

        swal({
            title: "Warning",
            text: "Are you sure you want to mark this item paid using credit?",
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
                    text: "Pay Using Credit",
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
                                type: result.type,
                                text: result.msg
                            }).show();

                            // Hide the modal, if any
                            $('.modal').modal('hide');
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
</script>