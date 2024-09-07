@push('after_scripts')
<script>
    crud.field('status').onChange(function(field) {
        if (field.value == 1) {  
            crud.field('paymentMethod').show();
        } else {
            crud.field('paymentMethod').hide();
        }   
    }).change();    

    crud.field('paymentMethod').onChange(function(field) {
        if (field.value == 4) {  
            crud.field('bank_details').show();
        } else {
            crud.field('bank_details').hide();
        }   
    }).change();    
</script>    
@endpush