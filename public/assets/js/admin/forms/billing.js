crud.field('billing_type_id').onChange(function(field) {
    if (field.value == 2) { // Monthly
        crud.field('date_start').show();
        crud.field('date_end').show();
        crud.field('date_cut_off').show();
        // crud.field('particulars').show();

        // crud.field('particulars').subfield('description', 1).input.value = 'Internet Subscription Fee';
        

    } else {
        // installment
        crud.field('date_start').hide();
        crud.field('date_end').hide();
        crud.field('date_cut_off').hide();
        // crud.field('particulars').hide();
    }   

    // lorem ipsum lorem    
    
}).change();

