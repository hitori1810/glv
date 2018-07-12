var timer = null;
$(document).ready(function(){
    $('#btn_back').click(function(){
        parent.history.back();
        return false;
    });

    $('#card_number').focusout(function(){
        $('#card_number').focus();
    });

    $('#card_number').change(ajax_post_code);

    $('#btn_search').click(ajax_post_code);
    $('#card_number').focus();
});
function ajax_post_code(){
    var code = $('#card_number').val();
    $('#SubDiv').show();   
    $('#mainDiv').show();
    $('div#defaut_info').hide();
    $('div#check_info').show();
    $.ajax({
        url: "index.php?entryPoint=AjaxMonitor&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {   
            code: code,
        }, 
        dataType: "json",
        success: function(res){           
            $('#SubDiv').hide();   
            $('#mainDiv').hide();
            $('div#check_info').html(res.html); 
            $('#card_number').focus();
            $('#card_number').val('');

            if (timer) {
                clearTimeout(timer); //cancel the previous timer.
                timer = null;
            }
            timer = setTimeout(function(){
                $('div#defaut_info').show(); 
                $('div#check_info').hide();
                $('div#check_info').html('');
                }, 15000);
        },
        error:function (xhr, ajaxOptions, thrownError){
            $('#SubDiv').hide();   
            $('#mainDiv').hide();   
            alert(xhr.status);
            alert(thrownError);
            $('#card_number').focus();
            $('#card_number').val('');
        }       
    });
}