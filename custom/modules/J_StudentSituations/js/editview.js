$( document ).ready(function() { 
    generateClassOption('move_to_class');

    ajaxCalToClass();
    ajaxGetFromClass();
    $('#last_lesson_date').live('change',function(){
        checkDataLockDate($(this).attr('id'));
        ajaxCalFromClass();	
    });
    $('#move_to_class_date, #move_to_class_date_end').live('change',function(){
        checkDataLockDate($(this).attr('id'));
        ajaxCalToClass();	
    });
    
    if($('#student_id').val() != ''){
            $('#student_name').addClass('input_readonly').prop('disabled', true); 
            $('#btn_student_name,#btn_clr_student_name').prop('disabled', true); 
    }
});

function generateClassOption(cls_option){
    $('#'+cls_option).multiselect({
        enableFiltering: true,
        buttonWidth: '225px',
        maxHeight: 400,
        enableHTML : true,
        optionLabel: function(element) 
        {
            if(element.index != 0){
                var start_date  = $(element).attr("start_date");
                var end_date    = $(element).attr("end_date");
                var total_hour    = $(element).attr("total_hour");
                var total_amount  = $(element).attr("total_amount");
                var class_name  = $(element).attr("class_name");
                var class_type  = $(element).attr("class_type");
                var sub_text = "<small>";
                sub_text += "<br>Class name: " + class_name;
                if(class_type == 'Normal Class'){
                    sub_text += "<br>Start: " + start_date;
                    sub_text += "<br>Finish: " + end_date;    
                }
                if(cls_option == 'ju_class_name'){
                    sub_text += "<br>Total Hour: " + total_hour;
                    sub_text += "<br>Total Amount: " + total_amount;
                    if(class_type == 'Normal Class')
                        sub_text += "<br>Type: <span class='textbg_green'>" + class_type + "</span>"; 
                    else sub_text += "<br>Type: <span class='textbg_orange'>" + class_type + "</span>";
                }
                sub_text += "</small>";
                return $(element).html() + sub_text;	
            }else return $(element).html();
        },
        onChange: function(option, checked, select) {
            var json_ss 	= option.attr('json_ss');
            var start 		= option.attr('start_date');
            var finish 		= option.attr('end_date');
            var situation_id= option.attr('situation_id');
            var class_type  = option.attr("class_type");
            var html 		= '';
            if(json_ss != '' && json_ss != null){
                obj = JSON.parse(json_ss);
                $.each(obj, function( key, value ) {
                    html +=	'<li>'+value+': '+key+'</li>';       
                });            
            }
            html 	+= '';
            if(cls_option == 'move_to_class'){
                $('#lbl_start_move_to').text(start);
                $('#lbl_finish_move_to').text(finish);
                $('#move_to_class_date_end').val(finish);
                $('#lbl_schedule_move_to').html(html);
                ajaxCalToClass();	
            }else if(cls_option == 'ju_class_name'){
                if(class_type == 'Normal Class'){
                    $('#credit_info_1').show();
                    $('#credit_info_5').hide();
                    $('#lbl_start_move_from').text(start);
                    $('#lbl_finish_move_from').text(finish);
                    $('#lbl_schedule_move_from').html(html);    
                }else if(class_type == 'Waiting Class'){
                    $('#credit_info_1').hide();
                    $('#credit_info_5').show();
                    $('#last_lesson_date').parent().hide();
                    $('#last_lesson_date_label').text('');
                }

                $('input[name=situation_id]').val(situation_id);
                ajaxCalFromClass();	
            }
            if($('#move_to_class').val() == $('#ju_class_name').val() && $('#ju_class_name').val() != '' && $('#move_to_class').val() != ''){
                alertify.error('Can not move to the same class !');
                $('#move_to_class').multiselect('select', '', true);
                $('#move_to_class').multiselect('deselect', option.val(), true);
            }
        },
        filterPlaceholder: 'Select class'
    });
}
//Overwrite Set Return 
function set_student_return(popup_reply_data){
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        if (the_key == 'toJSON') {
            continue;
        } else {
            var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
            switch (the_key)
            {
                case 'student_name':
                    $('#student_name').val(val);
                    break;
                case 'student_id':
                    $('#student_id').val(val);
                    break;
            }
        }
    }
    ajaxGetFromClass();   
}


function ajaxGetFromClass(){
    var student_id = $('#student_id').val();
    if(student_id == '') return ;
    ajaxStatus.showStatus('Processing...');
    $.ajax({
        url: "index.php?module=J_StudentSituations&action=handleAjaxStudentSituations&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  {
            type         : 'ajaxGetFromClass',
            student_id   : student_id,
        },
        dataType: "json",
        success: function(res){
            ajaxStatus.hideStatus();
            if(res.success == "1"){                           
                $('#ju_class_name').closest('td').html(res.html);
                generateClassOption('ju_class_name');
                if($_GET('from_class_id') != '' || $_GET('from_class_id')!== undefined){
                    $('#ju_class_name').multiselect('select', $_GET('from_class_id'), true);
                //    $('#ju_class_name').val($_GET('from_class_id')).multiselect("destroy").addClass('input_readonly').find('option:not(:selected)').prop('disabled', true);
                }
            }
        },        
    });


}

function ajaxCalFromClass(){
    var student_id 		= $('#student_id').val();
    var from_class_id 	= $('#ju_class_name').val();
    var situation_id 	= $('input[name=situation_id]').val();
    var last_lesson_date= $('#last_lesson_date').val();
    $('#lbl_total_hour_old, #lbl_payment_amount_old, #lbl_used_hour_old, #lbl_used_amount_old, #lbl_moving_hour_old, #lbl_moving_amount_old').text('0');
    $('input[name=moving_hour]').val('0');
    $('input[name=moving_amount]').val('0');
    if(student_id == '' || from_class_id == ''|| last_lesson_date == '') return ;

    ajaxStatus.showStatus('Processing...');
    $.ajax({
        url: "index.php?module=J_StudentSituations&action=handleAjaxStudentSituations&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  {
            type         		: 'ajaxCalFromClass',
            student_id   		: student_id,
            from_class_id   	: from_class_id,
            situation_id   		: situation_id,
            last_lesson_date   	: last_lesson_date,
        },
        dataType: "json",
        success: function(res){
            ajaxStatus.hideStatus();
            if(res.success == "1"){                           
                $('#lbl_total_hour_old').text(res.total_hour).effect("highlight", {color: '#3594FF'}, 2000);
                $('#lbl_payment_amount_old').text(res.total_amount).effect("highlight", {color: '#3594FF'}, 2000);
                $('#lbl_used_hour_old').text(res.used_hour).effect("highlight", {color: '#3594FF'}, 2000);
                $('#lbl_used_amount_old').text(res.used_amount).effect("highlight", {color: '#3594FF'}, 2000);
                $('#lbl_moving_hour_old').text(res.moving_hour).effect("highlight", {color: '#3594FF'}, 2000);
                $('#lbl_moving_amount_old').text(res.moving_amount).effect("highlight", {color: '#3594FF'}, 2000);
                $('input[name=used_hour]').val(res.used_hour);
                $('input[name=used_amount]').val(res.used_amount);
                $('input[name=moving_hour]').val(res.moving_hour);
                $('input[name=moving_amount]').val(res.moving_amount);
                $('input[name=from_class_closed_session]').val(res.closed_session);
                if(res.closed_session > 0)
                    alertify.error(res.closed_session +" sessions finished in From Class!");    
            }else{
                alertify.error(res.error);
                $('#last_lesson_date').effect("highlight", {color: 'red'}, 2000);
            }
        },        
    });


}


function ajaxCalToClass(){
    var student_id 				= $('#student_id').val();
    var move_to_class_id 		= $('#move_to_class').val();
    var moving_hour 			= $('input[name=moving_hour]').val();
    var move_to_class_date 		= $('#move_to_class_date').val();
    var move_to_class_date_end 	= $('#move_to_class_date_end').val();
    $('#lbl_total_hour_new, #lbl_studied_hour_new, #lbl_remaining_hour_new').text('0');
    $('input[name=remaining_hour]').val('0');
    if( move_to_class_id == ''|| student_id == ''|| move_to_class_date == '' || move_to_class_id == $('#ju_class_name').val()) return ;

    ajaxStatus.showStatus('Processing...');
    $.ajax({
        url: "index.php?module=J_StudentSituations&action=handleAjaxStudentSituations&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  {
            type         		: 'ajaxCalToClass',
            student_id   		: student_id,
            move_to_class_id   	: move_to_class_id,
            move_to_class_date  : move_to_class_date,
            move_to_class_date_end  : move_to_class_date_end,
            moving_hour  			: moving_hour,
        },
        dataType: "json",
        success: function(res){
            ajaxStatus.hideStatus();
            if(res.success == "1"){                           
                $('#lbl_total_hour_new').text(res.total_hour).effect("highlight", {color: '#3594FF'}, 2000);
                $('#lbl_moving_time').html(res.moving_time).effect("highlight", {color: '#3594FF'}, 2000);
                $('#lbl_remaining_hour_new').text(res.remaining_hour).effect("highlight", {color: '#3594FF'}, 2000);
                $('input[name=remaining_hour]').val(res.remaining_hour).effect("highlight", {color: '#3594FF'}, 2000);;
                if(res.end_date != ''){
                    $('#move_to_class_date_end').val(res.end_date).effect("highlight", {color: '#3594FF'}, 2000);
                }
                $('input[name=move_to_class_closed_session]').val(res.closed_session);
                if(res.closed_session > 0)
                    alertify.error(res.closed_session +" sessions finished in Move To Class!");
                    
            }else if(res.success == "0"){
                alertify.error(res.error);
                $('#move_to_class_date').effect("highlight", {color: 'red'}, 2000);
            }else if(res.success == "3"){
                alertify.error(res.error);
                $('#move_to_class_date_end').effect("highlight", {color: 'red'}, 2000);
            }else if(res.success == "4"){
                alertify.error(res.error);
                $('#move_to_class_date_end,#move_to_class_date').effect("highlight", {color: 'red'}, 2000);
            }
        },        
    });


}

//Overwrite check_form to validate                              
function check_form(formname) {
    //Validate timepicker
    var moving_hour 	= Numeric.parse($('input[name=moving_hour]').val());
    var moving_amount 	= Numeric.parse($('input[name=moving_amount]').val());
    var remaining_hour 	= Numeric.parse($('input[name=remaining_hour]').val());
    var fromClassClosedSession = $('input[name=from_class_closed_session]').val(); 
    var moveToClassClosedSession = $('input[name=move_to_class_closed_session]').val();
    var closedSession = true;
    
    var flag = true;
    if(remaining_hour == 0){
        flag = false;
        alertify.error('Remaining Hour is required!');
    }

    if(moving_hour == 0){
        flag = false;
        alertify.error('Moving Hour is required!');
    }

    if(moving_hour != remaining_hour){
        flag = false;
        alertify.error('Moving Hour is not equal Remaining Hour!');
    }

    if(fromClassClosedSession > 0) {
        if(moveToClassClosedSession > 0)
            closedSession = confirm(fromClassClosedSession + " session finished in From Class and " + moveToClassClosedSession + " session finished in Move To Class! Do you want to confirm?");
        else
            closedSession = confirm(fromClassClosedSession + " session finished in From Class! Do you want to confirm?");           
    }
    else
        if(moveToClassClosedSession > 0)
            closedSession = confirm(moveToClassClosedSession + " session finished in Move To Class! Do you want to confirm?");

    return validate_form(formname, '') && flag && closedSession; 
}
function $_GET(param) {
    var vars = {};
    window.location.href.replace( location.hash, '' ).replace( 
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function( m, key, value ) { // callback
            vars[key] = value !== undefined ? value : '';
        }
    );

    if ( param ) {
        return vars[param] ? vars[param] : null;    
    }
    return vars;
}