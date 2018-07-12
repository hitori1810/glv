// ==========================================//
// HANDLE CURRENCY FIELD BY LAP NGUYEN       //        
// ==========================================//
var cur_digits = 0;
function numberFormat(nr){
    //remove the existing ,
    var regex = new RegExp('[a-zA-Z'+num_grp_sep+']',"g"); 
    nr = nr.replace(regex,'');
    //force it to be a string
    nr += '';
    //split it into 2 parts  (for numbers with decimals, ex: 125.05125)
    var x = nr.split(dec_sep);
    var p1 = x[0];
    var p2 = x.length > 1 ? dec_sep + x[1] : '';
    //match groups of 3 numbers (0-9) and add , between them
    regex = /(\d+)(\d{3})/;
    while (regex.test(p1)) {
        p1 = p1.replace(regex, '$1' + num_grp_sep + '$2');
    }
    //join the 2 parts and return the formatted number
    return p1 + p2;
}
function check_currency(focus){
    id = $(focus).attr('id');
    val = $(focus).val();
    value = unformatNumber(val,num_grp_sep, dec_sep);
    if(value>0)
        $(focus).val(formatNumber(value,num_grp_sep,dec_sep,cur_digits,cur_digits));  
    else{
        var zero = 0;
        $(focus).val(formatNumber(zero.toFixed(cur_digits),num_grp_sep,dec_sep));
    }
}
$(document).ready(function(){
    $("input.currency").keyup(function(e){
        this.value = numberFormat(this.value);
    });
    //double check currency with SUGAR's funtion
    $('input.currency').live('blur',function(){
        check_currency(this);
    }); 
    //double check currency with SUGAR's funtion
    $('input.currency').live('change',function(){
        check_currency(this);
    });
    //check all currency input when the page loaded
    $('input.currency').each(function(){
        $(this).css("text-align", "right"); 
    }); 
});