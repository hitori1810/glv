//Function get Teacher - 25/07/2014 - by MTN
function findTeacher(){  //update by Trung nguyen 2015.12.22  
    jQuery("#availability_status_1").remove();   
    jQuery("#find_teacher").parent().append("<span id='availability_status_1'><img src='custom/include/images/loader.gif' align='absmiddle' width='16'></span>");
    $.ajax({
        url:'index.php?module=Meetings&action=ajaxMeeting&sugar_body_only=true',
        type:'POST',
        data:{               
            date_start :$('#date_start').val(),
            date_end :$('#date_end').val(),
            type: "loadTeacherOptions",
            session_id: $('input[name=record]').val(),
            teacher_id: $("#teacher_id").val(),
        },
        success:function(data){
            $("#teacher_id").html(data);   
            resetSeletc2('teacher_id',$("#teacher_id").val());
            jQuery("#availability_status_1").remove();
        }
    });
}

//Function get Room - 25/07/2014 - by MTN
function findRoom(){    //update by Trung nguyen 2015.12.22
    jQuery("#availability_status_2").remove();
    jQuery("#find_room").parent().append("<span id='availability_status_2'><img src='custom/include/images/loader.gif' align='absmiddle' width='16'></span>");
    $.ajax({
        url:'index.php?module=Meetings&action=ajaxMeeting&sugar_body_only=true',
        type:'POST',
        data:{                 
            date_start :$('#date_start').val(),
            date_end :$('#date_end').val(),
            type: 'loadRoomOptions',
            session_id: $('input[name=record]').val(),
            room_id: $("#room_id").val(),
        },
        success:function(data){
            $("#room_id").html(data);
            resetSeletc2('room_id',$("#room_id").val());
            jQuery("#availability_status_2").remove();
        }
    }); 
}
//update by Trung nguyen 2015.12.22
function getAllRoomTeacher($type){
    ajaxStatus.showStatus('Loading ...');
    findTeacher();
    findRoom();
    ajaxStatus.hideStatus();      
}

$( document ).ready(function() {
    var meeting_type =  $('#meeting_type').val();

    $('#detailpanel_1 #status').change(function () {
        if($(this).val() =='Not Held'){
            $('#detailpanel_2').show();   
        }else{
            $('#detailpanel_2').hide();  
        }   
    });
    $status = $('#detailpanel_1 select#status').val();          
    if($status!='Not Held'){
        $('#detailpanel_2').hide();    
    } 

    //////////////////////---------Get Meeting type for case: PT Result By Quyen Cao-----------///////////////////
    // var url= window.location.search.substring(1);    var meeting_type = getUrlVars(url)['type'];  
    //Custom show/hide Screen According by Meeting Type - 23/07/2014 - by MTN - Fixed By Quyen.Cao  14/08/2015

    if(meeting_type=="Placement Test" || meeting_type=="Demo"){
        getAllRoomTeacher(meeting_type);  
        if(meeting_type=="Placement Test"){            
            $('#date_end_date').closest('tr').hide();
        } 
        $('#scheduler').hide();
        $('#cal-repeat-block').parent().hide(); 
        $('#delivery_hour_label').closest('tr').hide();
//        $('#teaching_hour_label').closest('tr').hide();  
    }else if(meeting_type == 'Session'){
        getAllRoomTeacher(meeting_type);
        $('#_label').closest('tr').hide();
        $('#check_all').closest('tr').hide(); 
        $('#status').closest('td').hide();
        $('#status').closest('td').prev().hide();
        $('#parent_type').closest('tr').hide();
        $('#scheduler').hide();
        $('#cal-repeat-block').parent().hide();        
    }else{                        
        $('#teacher_name_label').text('');
        $('#teacher_name_label').closest('td').next().empty();
        $('#room_name_label').text('');
        $('#room_name_label').closest('td').next().empty();
        $('#class_name_label').text('');
        $('#class_name_label').closest('td').next().empty();
        $('#delivery_hour_label').closest('tr').hide();
        $('#teaching_hour_label').closest('tr').hide();
        $('#_label').closest('tr').hide();
        $('#check_all').closest('tr').hide();
    }
    ///End Custom Show Hide///    

    //Add plugin Select2 to 2 option option_teacher_name and option_room_name
    $("#teacher_id").select2();
    $("#room_id").select2();

    //custom css 2 option Select2
    $("#s2id_teacher_id").css("width","215px");
    $("#s2id_teacher_id").css("margin-right","7px");

    $("#s2id_room_id").css("width","215px");
    $("#s2id_room_id").css("margin-right","7px");         
    //END: Custom show/hide Screen According by Meeting Type - by MTN

    //Ajax get Teacher, Room - 25/07/2014 - by MTN
    $('#find_teacher').live('click',function(){
        findTeacher();
    });

    $('#find_room').live('click',function(){
        findRoom();
    });
    //END: Ajax get Teacher, Room - 25/07/2014 - by MTN 
    //////////////////////////////////////-------Apolo Junior--------//////////////////////////////////////////////////////
    if(meeting_type == 'Session'|| meeting_type == 'Placement Test'|| meeting_type == 'Demo'){
        calHours();
        $('#date_start_date,#date_start_hours,#date_start_minutes,#date_start_meridiem').attr('onchange','combo_date_start.update(); SugarWidgetScheduler.update_time();calHours();');
        $('#date_end_date,#date_end_hours,#date_end_minutes,#date_end_meridiem').attr('onchange','combo_date_end.update();SugarWidgetScheduler.update_time();calHours();');
        $('#duration').attr('onchange','SugarWidgetScheduler.update_time();calHours();');
    }

});

//get meeting type by Quyen.Cao
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

function calHours(){
    var hour=parseInt($('#duration_hours').val());
    var minutes=parseInt($('#duration_minutes').val());
    if(hour < 0 || minutes < 0){
        $('#delivery_hour,#teaching_hour').val('');   
    }else{
        var cal_hours= hour + (minutes/60);
        $('#delivery_hour,#teaching_hour').val(cal_hours);
    }

    $('#teacher_id').html('<option value = ""> --None-- </option>');
    $('#room_id').html('<option value = ""> --None-- </option>');
    resetSeletc2('teacher_id','');
    resetSeletc2('room_id',''); 
}
//////////////////////////////////////---------------//////////////////////////////////////////////////////
function resetSeletc2(id, val){
    $('#'+id).select2('val',val);
    $("#s2id_" + id).css("width","215px");
    $("#s2id_" + id).css("margin-right","7px");
}
