crud.field('auto_generate_bill').onChange(function(field) {
    if (field.value == 1) {  
        crud.field('bill_generate_days_before_end_of_billing_period').show();
    } else {
        crud.field('bill_generate_days_before_end_of_billing_period').hide();
    }   
}).change();


crud.field('auto_send_bill_notification').onChange(function(field) {
    if (field.value == 1) {  
        crud.field('bill_notification_days_after_the_bill_created').show();
    } else {
        crud.field('bill_notification_days_after_the_bill_created').hide();
    }   
}).change();


crud.field('auto_send_cut_off_notification').onChange(function(field) {
    if (field.value == 1) {  
        crud.field('bill_cut_off_notification_days_before_cut_off_date').show();
    } else {
        crud.field('bill_cut_off_notification_days_before_cut_off_date').hide();
    }   
}).change();
