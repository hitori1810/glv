function markNotification(id){
    if(typeof(id) == "undefined" || id == '') return ;

    var noti_toggle = $('#n_'+id).find('#nofi_toggle');
    var status      = noti_toggle.text();
    var mark        = 'Read';
    var title       = 'Mark as Unread';
    if(status == 'Read'){
        mark = 'Unread';
        title       = 'Mark as Read';
    }

    noti_toggle.text(mark);
    noti_toggle.closest('tr').attr('class', mark);
    noti_toggle.attr('title', title);
    //Change color
    var oldValue = parseInt($('#notifCount').html());
    var newValue = oldValue - 1;
    if(mark == 'Unread')
        newValue = oldValue + 1;
    updateNotifCount(newValue);

    $.ajax({
        type: "POST",
        url: "index.php?&module=Notifications&action=quickView",
        data:  {
            record  : id,
            mark    : mark,
        },
        success:function(data){
            data = JSON.parse(data);
            if (data.success == "1"){

            }
        },
    });
}

function updateNotifCount(newValue){
    if(newValue >= 0){
        if(newValue == 0)
            $("#notifCount").attr('class', 'notifNoneCount');
        if(newValue > 0)
            $("#notifCount").attr('class', 'notifCount');
        if(newValue < 0) newValue = 0;

        $('#notifCount').html(newValue);
    }
}

$(document).ready(function(){
    $("div#main").mouseup(function(){
        if(typeof DCMenu != 'undefined')
            DCMenu.closeOverlay('','dcmenuSugarCube');
    });
});