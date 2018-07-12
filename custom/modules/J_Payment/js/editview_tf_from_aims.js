$(document).ready(function() {
    $('#payment_date').on('change',function(){
        if(!checkDataLockDate($(this).attr('id'),false))
            return ;
    });
    addToValidate('EditView', 'from_AIMS_center_id', 'enum', true, 'Transfer From');
    addToValidateMoreThan('EditView','payment_amount','decimal',true,'Transfer Amount',1);
    $('#payment_amount').on('change',function(){
        var payment_amount       = Numeric.parse($('#payment_amount').val());
        $(this).val(Numeric.toInt(payment_amount));
    });
    toggle_transfer_type();
    $('#use_type').live('change',function(){
        toggle_transfer_type();    
    }); 
    $('#total_hours').live('blur',function(){
        $(this).val(Numeric.toFloat($(this).val(),2,2));    
    });   
});
function toggle_transfer_type(){
    var type = $('#use_type').val();
    if(type == 'Amount'){
        $('#total_hours').closest('tr').hide().val('');
        removeFromValidate('EditView', 'total_hours');
    }else{
        $('#total_hours').closest('tr').show().val('');
        addToValidateMoreThan('EditView','total_hours','decimal', true, 'Transfer Hours', 1); 
    }
}