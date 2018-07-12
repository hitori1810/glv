$(document).ready(function(){

});

function showDialogExportForm(){
    $("body").css({ overflow: 'hidden' });
    $("#slc_template").val($("#default_template").val());
    $('#div_dialog_export').dialog({
        resizable    : false,
        width        : 500,
        height       :'auto',
        modal        : true,
        visible      : true,
        position     : ['center',130],
        beforeClose: function(event, ui) {
            $("body").css({ overflow: 'inherit' });
        },
        buttons: {
            "Export":{
                click:function() {
                    var studentID = $("input[name='record']").val();
                    var template = $('#slc_template').val();
                    
                    var url = 'index.php?module=Contacts&action=exportform&template='+template+'&student_id='+studentID;
                    window.open(url,'_blank');
                },
                class   : 'button primary btn_export_form',
                text    : SUGAR.language.get('Contacts','BTN_EXPORT'),
            },
            "Cancel":{
                click:function() {
                    $(this).dialog('close');
                },
                class   : 'button btn_cancel',
                text    : SUGAR.language.get('J_Class','LBL_BTN_CANCEL'),
            },  
        },
    });
};