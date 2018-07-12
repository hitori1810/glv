var record_id = $('input[name=record]').val();
var duplicate_id = $('input[name=duplicateId]').val();
if (typeof duplicate_id == 'undefined')
    duplicate_id = '';
var ug_koc = '';
var ug_level = '';
var ug_module = '';

$(document).ready(function(){
    generateOption();
    $('#kind_of_course, #level').on('change',function(){
        $('#koc_id').val($('#kind_of_course option:selected').attr('koc_id'));
        $('#short_course_name').val($('#kind_of_course option:selected').attr('short_course_name'));
        generateOption();
    });
    $('#class_type').live('change',function(){
        ajaxStatus.showStatus(SUGAR.language.get('J_Class', 'LBL_CHANGE_CLASS_TYPE')+' <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
        var class_type =  $('#class_type').val();
        if(class_type == 'Normal Class'){
            window.location.href = "index.php?module=J_Class&action=EditView&return_module=J_Class&return_action=DetailView&class_type=Normal%20Class";
        }else{
            window.location.href = "index.php?module=J_Class&action=EditView&return_module=J_Class&return_action=DetailView&class_type=Waiting%20Class";
        }
        ajaxStatus.hideStatus();
    });
    $('.studentsituations_description').live('change',function(){
        var _this = $(this);
        $.ajax({
            url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
            type: "POST",
            async: true,
            data: {
                type : 'saveStudentSituationDescription',
                studentsituation_id : _this.attr('ss_id'),
                description: _this.val(),
            },
            success: function(res){

            },
        });

    });
});

function handleAddWaiting(){
    open_popup($('#waiting_class_parent_type').val(),600, 400, "", true, false, {"call_back_function":"set_return_waiting","form_name":"DetailView","field_to_name_array":{"id":"id"}}, "single", true);
}

function set_return_waiting(popup_reply_data, filter){
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    ajaxStatus.showStatus('Saving ...');

    var student_id  = name_to_value_array['id'];
    var parent_type = $('#waiting_class_parent_type').val();

    $.ajax({
        type: "POST",
        url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
        data:{
            type        : 'handleWaitingClass',
            student_id  : student_id,
            class_id    : $('input[name=record]').val(),
            parent      : parent_type,
            act         : 'addWaitingClass',
        },
        dataType: "json",
        success:function(data){
            ajaxStatus.hideStatus();
            showSubPanel('j_class_studentsituations', null, true);
            alertify.success(SUGAR.language.get('J_Class','LBL_ADD_WAITING_SUCCESS'));
        }
    });
}
function ajaxDeleteWaitingClass(situation_id){
    alertify.confirm(SUGAR.language.get('J_Class', 'LBL_CONFIRM_REMOVE_RECORD'), function (e) {
        if (e) {
            ajaxStatus.showStatus(SUGAR.language.get('J_Class', 'LBL_REMOVING')+'...');
            $.ajax({
                type: "POST",
                url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
                data:{
                    type            : 'handleWaitingClass',
                    situation_id    : situation_id,
                    act             : 'deleteWaitingClass',
                },
                dataType: "json",
                success:function(data){
                    ajaxStatus.hideStatus();
                    showSubPanel('j_class_studentsituations', null, true);
                    alertify.success('Remove Successfully !');
                }
            });
        }
    });
}

//showing Kind Of Course
function generateOption(){
    var kind_of_course = $('#kind_of_course').val();
    var level_selected = $('#level').val();
    var module_selected = $('#modules').val();
    var objs =  $.parseJSON($('#kind_of_course :selected').attr('json'));
    if(objs != '' && typeof objs == 'undefined'){
        //Adult variable
        var team_type              = $('#team_type').val();
        var class_type_adult       = $('#class_type_adult').val();
        var arr_type               = [ "Skill", "Connect Club", "Connect Event"];
        //Generate options level
        if(kind_of_course != '' && kind_of_course != null){
            $('#hours').prop('readonly',true).addClass('input_readonly');
            $('#level').prop('disabled',false).removeClass('input_readonly');

            // Clear all select list items except first one
            $('#level').find('option:gt(0)').remove();
            $.each( objs, function( key, obj ) {
                if(obj.levels != '')
                    $('#level').append('<option label="'+obj.levels+'" value="'+obj.levels+'">'+obj.levels+'</option>');
            });
            if ($('#level option').size() == 1){
                $('#level').prop('disabled',true).addClass('input_readonly');
                $('#modules').prop('disabled',true).addClass('input_readonly');
            }
            else {
                $('#level').prop('disabled',false).removeClass('input_readonly');
            }
            $('#level').val(level_selected);

            //Generate options module
            $('#modules').find('option:gt(0)').remove();
            if(level_selected != '' && level_selected != null){
                $.each( objs, function( key, koc ) {
                    if(koc.levels == level_selected){
                        $.each( koc.modules, function( key, module ) {
                            if(module != "")
                                $('#modules').append('<option label="'+module+'" value="'+module+'">'+module+'</option>');
                        });
                        //If level do not have module
                        if ($('#modules option').size() == 1)
                            $('#modules').prop('disabled',true).addClass('input_readonly');
                        else
                            $('#modules').prop('disabled',false).removeClass('input_readonly');
                    }
                });
                $('#modules').val(module_selected);
            }else
                $('#modules').prop('disabled',true).addClass('input_readonly');

        }
        getClassHours();
    }
}
function getClassHours(){
    if(record_id != ''){
        return false;
    }
    var kind_of_course = $('#kind_of_course').val();
    var level_selected = $('#level').val();
    var objs =  $.parseJSON($('#kind_of_course :selected').attr('json'));

    if (kind_of_course == "" || (($('#level option').size() == 1) && kind_of_course == "")){
        $('#hours').val("");
        return false;
    }
    $.each( objs, function( key, koc ) {
        if(koc.levels == level_selected){
            $('#hours').val(koc.hours);

            if(koc.is_set_hour == '1' || typeof koc.is_set_hour == 'undefined')
                $('#hours').prop('readonly',true).addClass('input_readonly');
            else
                $('#hours').prop('readonly',false).removeClass('input_readonly');
        }
    });
}