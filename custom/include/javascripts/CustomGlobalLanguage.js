$(document).ready(function(){
    //Event Change Global Language in Footer - by MTN - 06/05/2015
    $('#global_language').change(function(){
        $("#availability_status").remove();
        $("#global_language").parent().append("<span id='availability_status'></span>");
        $("#availability_status").html('<img src="themes/default/images/loading.gif" align="absmiddle" width="16">');
        $.ajax({
            url: "index.php?entryPoint=changeGlobalLanguage&sugar_body_only=true",
            type: "POST",
            async: true,
            data:
            {   
                global_language:    $('#global_language').val(),
            },              
            success: function(data){
                $("#availability_status").remove();
                location.reload();
            },
        });
    })
});