$(document).ready(function(){
    $('#currency_id_select').width('21%');
    $('#actual_cost').live('keyup', caculateCost);
    $('#estimated_enquiries').live('keyup', caculateCost);
});
//function tính  estimated_enquiries
function caculateCost(){
    var x = unformatNumber($('#actual_cost').val(), num_grp_sep, dec_sep);
    var y = unformatNumber($('#estimated_enquiries').val(), num_grp_sep, dec_sep);
    if(y!=0){
        var result=x/y;
    }
     $('#cost_enquiry').val(formatNumber(result.toFixed(2), num_grp_sep, dec_sep))  ;
}