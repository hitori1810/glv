var round = 0
String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g,'');
}
var month_names = new Array ( ); 
month_names[0] = 'January';
month_names[1] = 'February';
month_names[2] = 'March';
month_names[3] = 'April';
month_names[4] = 'May';
month_names[5] = 'June';
month_names[6] = 'July';
month_names[7] = 'August';
month_names[8] = 'September';
month_names[9] = 'October';
month_names[10] = 'November';
month_names[11] = 'December';

var day_names = new Array ( );
day_names[0] = 'Sunday';
day_names[1] = 'Monday';
day_names[2] = 'Tuesday';
day_names[3] = 'Wednesday';
day_names[4] = 'Thursday';
day_names[5] = 'Friday';
day_names[6] = 'Saturday';
$(document).ready(function(){
    $('#example1').glDatePicker(
        {
            cssName: 'default',
            //startDate: new Date(),
            //endDate: new Date(),
            showPrevNext: true,
            allowOld: true,
            showAlways: true,
            // Possible values: static | absolute | fixed | relative | inherit
            position: 'inherit',
            onChange: function(target, newDate){
                jQuery('#dis_apply_date').html(
                    day_names[newDate.getDay()] + ', ' + 
                    newDate.getDate() + ' ' +  month_names[newDate.getMonth()] + ', ' + newDate.getFullYear()
                );

                $('#date_add').val
                (
                    newDate.getFullYear() + "-" +
                    (newDate.getMonth() + 1) + "-" +
                    newDate.getDate()
                );

                $('#day').val(
                    newDate.getDate()
                );

                ajaxLoadTimesheet();
            },

    });
    //set current date for apply_date 
    var date = new Date();
    jQuery('#dis_apply_date').html(
        day_names[date.getDay()] + ', ' + 
        date.getDate() + ' ' +  month_names[date.getMonth()] + ', ' + date.getFullYear()
    );

    $('#apply_date').val(
        date.getDate() + '/' +  (date.getMonth()+1) + '/' + date.getFullYear()
    );

    $('.btnAddRow').live('click',function(){
        var teacher_ids = $("select[name=tmp_teacher_id]").val();
        var task_name = $('select[name=tmp_task_name]').val();
        var hours = parseInt($('#tmp_hours').val().trim());
        var minutes = parseInt($('#tmp_minutes').val());
        var description = $('#tmp_description').val().trim();

        if(teacher_ids != null){
            $('.select2-container').removeClass('error');
            $('#validate_teacher_id').hide();
            $.each(teacher_ids, function(index ,teacher_id) {
                if(validateAddRow(task_name,hours,description)){
                    insertNewRow(teacher_id,task_name,hours,minutes,description);   
                } 
            });
            calcuHour();
        }else{
            $('.select2-container').addClass('error');
            $('#validate_teacher_id').show();
        }
    });

    jQuery('.btnDelRow').live('click',function(){
        $(this).closest('tr').remove();
        calcuHour(); 
    }); 

    jQuery('#btnSave').live('click',function(){
        ajaxSave();
    });

    ajaxLoadTimesheet();
});

function validateAddRow(task_name,hours,description){
    var flag = true;
    if(task_name == ''){
        $('select[name=tmp_task_name]').addClass('error');
        $('#validate_task').show();
        flag = false;   
    }else{
        $('select[name=tmp_task_name]').removeClass('error');
        $('#validate_task').hide();  
    }

    if(hours >= 24){
        $('#tmp_hours').addClass('error');
        $('#validate_hour').show();
        flag = false;   
    }else{
        $('#tmp_hours').removeClass('error');
        $('#validate_hour').hide();  
    }

    if(description == ''){
        $('#tmp_description').addClass('error');
        $('#validate_description').show();
        flag = false;   
    }else{
        $('#tmp_description').removeClass('error');
        $('#validate_description').hide();  
    }
    return flag;

}

function validateSave(){
    var flag = true;
    $('input#hours').each(function(){
        var check_hours = parseInt($(this).val().trim());
        if(check_hours >= 24){
            $(this).addClass('error');
            flag = false;   
        }else{
            $(this).removeClass('error'); 
        }
    });

    $('input#minutes').each(function(){
        var check_minutes = parseInt($(this).val().trim());
        if(check_minutes > 50){
            $(this).addClass('error');
            flag = false;   
        }else{
            $(this).removeClass('error'); 
        }
    });

    $('input#description').each(function(){
        var check_description = $(this).val().trim();
        if(check_description == ''){
            $(this).addClass('error');
            flag = false;   
        }else{
            $(this).removeClass('error'); 
        }
    });

    var rowCount = $('#timesheets >tbody >tr').length;
    if(rowCount < 0){
        flag = false;  
    }

    return flag; 
}

function insertNewRow(teacher_id, task_name, hours, minutes, description){
    var current_user_id     = $('#current_user_id').val();
    var current_user_name   = $('#current_user_name').val();
    var current_team_name   = $('#current_team_name').val();
    var current_team_id     = $('#current_team_id').val();
    html_TR = '';
    html_TR += '<tr>';

    html_TR += '<td valign="bottom" align="left">';
    html_TR += '<input type="hidden" id="teacher_id" name="teacher_id[]" value="'+teacher_id+'"/>';
    html_TR += '<a target="_blank" style="text-decoration: none; vertical-align: -webkit-baseline-middle; font-weight: bold;" href="index.php?module=C_Teachers&action=DetailView&record='+teacher_id+'"> '+$("#tmp_teacher_id option[value='"+teacher_id+"']").text()+' </a>';
    html_TR += '</td>';

    html_TR += '<td>';
    html_TR += '<input type="text" id="task_text" name="task_text[]" value="'+$("#tmp_task_name option[value='"+task_name+"']").text()+'" readonly="true" style="width:150px;" class="disable" />';
    html_TR += '<input type="hidden" id="task_name" name="task_name[]" value="'+task_name+'"/>';
    html_TR += '</td>';

    html_TR += '<td>'; 
    html_TR += '<input type="text" id="hours" name="hours[]" size="2" maxlength="2" value="'+hours +'" class="hours"/>';
    html_TR += '</td>';

    html_TR += '<td>';
    html_TR += '<input type="text" id="minutes" name="minutes[]" size="2" maxlength="2" value="'+minutes +'" class="minutes" /> ';
    html_TR += '</td>';

    html_TR += '<td>';
    html_TR += '<input type="text" id="description" name="description[]"  size="20" value="'+description+'"/>';
    html_TR += '</td>';
    
    html_TR += '<td align="center">';
    html_TR += '<a target="_blank" style="text-decoration: none; vertical-align: -webkit-baseline-middle; font-weight: bold;" href="index.php?module=Employees&action=DetailView&record='+current_user_id+'">'+current_user_name+'</a><input type="hidden" name="created_by[]" value="'+current_user_id+'"/>';
    html_TR += '</td>';
    
    html_TR += '<td align="center">';
    html_TR += '<label style="vertical-align: -webkit-baseline-middle; font-weight: bold;">'+current_team_name+'</a><input type="hidden" name="team_id[]" value="'+current_team_id+'"/>';
    html_TR += '</td>';

    html_TR += '<td>';
    html_TR += '<input type="button" class="btnDelRow" value="Delete" id="btnDel"/>';
    html_TR += '</td>';

    html_TR += '</tr>';

    $('#timesheets').find('tbody').append(html_TR);
}

function calcuHour(){
    var minute = 0;
    $('input#hours').each(function(){
        minute += parseInt($(this).val()) * 60; 
    });
    $('input#minutes').each(function(){
        minute += parseInt($(this).val());
    });
    var hours = minute / 60;
    $('#total_day').text(hours.toFixed(2));
}

function ajaxSave(){
    if(validateSave()){
        //loading icon  
        $("#result").html('<img src="themes/default/images/loading.gif" align="absmiddle" width="16">&nbsp;Waiting data...');
        //prepare
        var form = $('#Editview');
        calcuHour();
        $.ajax({
            url: "index.php?module=C_Timesheet&action=save_timesheet&sugar_body_only=true",
            type: "POST",
            async: true,
            data:  form.serialize(),
            dataType: "json",
            success: function(data){           
                if(data.success == "1"){
                    $("#result").html('Save successfully !');
                    //colorzing calendar
                    var day = $("#day").val();
                    var count = data.count;
                    $('div.day, div.sun, .timesheet').each(function(){
                        var sel = $(this).text();
                        if(sel == day){
                            if(count == '0'){
                                $(this).removeClass('timesheet');
                                $(this).parent().removeClass('gldp-default-timesheet');   
                            }else{
                                $(this).addClass('timesheet');
                                $(this).parent().addClass('gldp-default-timesheet');    
                            }

                        }
                    });
                }else{
                    alert('Some errors occurred! Data can not save, please check again!');   
                }  
            },        
        });   
    }else{
        alert('Some errors occurred! Please, check again.');
    }
}

function ajaxLoadTimesheet(){
    //loading icon
    if($('#date_add').val() != ''){
        $("#result").html('<img src="themes/default/images/loading.gif" align="absmiddle" width="16">&nbsp;Loading data...');
        $.ajax({
            url: "index.php?module=C_Timesheet&action=load_timesheet&sugar_body_only=true",
            type: "POST",
            async: true,
            data:  {date_add: $('#date_add').val()},
            dataType: "json",
            success: function(data){           
                if(data.success == "1"){
                    data.count == 0 ? $("#result").html('No Data !') : $("#result").html('Load successfully !');
                    $('#timesheets').find('tbody').html(data.html);
                    calcuHour();
                }else{
                    $("#result").html('Load data fail, Please reload page !!');   
                }  
            },        
        });    
    }else{
        $('#date_add').val(SUGAR.util.DateUtils.formatDate(new Date(),'H:i','Y-m-d'));
        ajaxLoadTimesheet();   
    }    
}