crud.field('planned_application_id').onChange(function(field) {
    // Get the selected option
    var selectedOption = field.input.options[field.input.selectedIndex];
    

    // Check if there is a selected option and if it has a data-location attribute
    if (selectedOption && selectedOption.getAttribute('data-location')) {
        // Get the original label text before making changes
        var originalText = selectedOption.textContent;

        console.log(originalText);
        
        // Get the value of the data-location attribute
        var locationValue = selectedOption.getAttribute('data-location');

        // Modify the text of the selected option by appending the location value
        if (locationValue) {
            selectedOption.textContent = locationValue + " - " + selectedOption.textContent; // Append the location value before the existing label text
        }

        // Add an event listener to revert changes when another option is selected
        field.input.addEventListener('change', function() {
            // Restore the original label text when a new selection is made
            selectedOption.textContent = originalText;
        });
    }
}).change();


crud.field('subscription').onChange(function(field) {
    if (field.value == 4 || field.value == 3) { // 4. hotspot voucher , 3. Piso wifi
        crud.field('billingGrouping').hide();
    } else {
        crud.field('billingGrouping').show();
    }   
}).change();
