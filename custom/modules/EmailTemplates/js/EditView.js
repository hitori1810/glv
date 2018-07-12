$(document).ready(function(){
    displayByType();
    
    $("select[name='type']").on("change", function(){
        displayByType();    
    });
});

function displayByType(){
    if($("select[name='type']").val() == "sms"){
        $("#toggle_textonly").prop( "checked", true );
        $(".tr_attachments").hide();
        $("#upload_form").hide();
        toggle_text_only();
    }
    else{                  
        $(".tr_attachments").show();
        $("#upload_form").show();
        toggle_text_only();                           
    }
}