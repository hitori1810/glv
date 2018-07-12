$( document ).ready(function() {
    $('#delete_button').removeAttr('onclick');
    $('#delete_button').click(function(){
        var _form = document.getElementById('formDetailView');
        _form.return_module.value='Opportunities'; 
        _form.return_action.value='ListView'; 
        _form.action.value='Delete';

        var confirm = prompt("Please enter your description to confirm delete.", "");

        if (confirm != null) {
            $('#descriptions').val(confirm);
            SUGAR.ajaxUI.submitForm(_form);
        }
    });
});