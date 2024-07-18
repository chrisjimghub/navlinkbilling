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