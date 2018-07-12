$(document).ready(function(){
    $('#phone_mobile').width('50%');
    var type = $('#teacher_type').val();
    if(type == 'TA')
        $('#detailpanel_2').hide();
    
    if(type == 'Teacher')
        $('#detailpanel_2').show();
    
});


function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}