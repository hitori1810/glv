var allow_submit = false;
$(document).ready(function(){
    allow_submit = true;
    $("#j_class_j_gradebook_1j_class_ida").live("change", handleChangeClass) ;
    $("#gradebook_id").live("change", handleChangeGradebook);
    $('.comment').live('click', inputComment);
    $('.select_comment').live('change', function(){
        getComment('');
    });
    $('#find, #load_config').live('click', getGradebookDetail);

    $(".input_mark").live('keydown',function (e) {
        this.value = numberFormat(this.value);
    });

    $('.input_mark').live('blur', handleBlurInput);

    $("#save").live("click",saveInput);

    $('#clear').live('click',function(){
        displayFilter(true);
    });
});

function getComment(over_write_text){
    over_write_text = typeof over_write_text !== 'undefined' ? over_write_text : '';
    var keys = {};
    var values = [];
    var student_name = $("#dialog_student_name").text() + ",";
    values.push(student_name);
    $('.select_comment').each(function(){
        var _this =  $(this);
        var val =  _this.val() ;
        if(val!= '') {
            keys[_this.attr('id')] = val;
            if(_this.attr('id') == 'gradebook_other_comment') {
                values.push(_this.val());
            } else
                values.push($(this).find('option[value="'+val+'"]').text());
        }
    });
    if(over_write_text == '')
        $("[name=dialog_text_comment]").val(values.join(" ")).effect("highlight", {color: '#ff9933'}, 1000);
    else $("[name=dialog_text_comment]").val(over_write_text)
    $("[name=dialog_key_comment]").val(JSON.stringify(keys));
}

function inputComment(){
    var _this = $(this);
    var comment_name = _this.attr('config-name');
    var student_name = _this.closest('tr').find('span.student_name').text();
    var class_name = $('input[name=j_class_j_gradebook_1_name]').val();
    var comment_key = _this.find('input[name="key_teacher_'+comment_name+'[]"]').val();
    var current_cmt = _this.find('input[name="value_teacher_'+comment_name+'[]"]').val();
    try{
        var comment_keys = JSON.parse(comment_key);
    } catch(e) {
        var comment_keys = {};
    }
    $('.dialog_content select').val('');
    $('.gradebook_other_comment').val('');
    $('.gradebook_other_comment').text('');
    for($id in comment_keys){
        if($id == 'gradebook_other_comment') {
            $('#gradebook_other_comment').val(comment_keys[$id]);
            $('#gradebook_other_comment').text(comment_keys[$id]);
        } else {
            $('select#'+$id).val(comment_keys[$id]);
            $('select#'+$id).select2();
        }
        //$('select#'+$id).select2('val',comment_keys[$id]);
    };
    $('.dialog_content select').select2()
    $('.dialog_header span#dialog_student_name').text(student_name);
    $('.dialog_header span#dialog_student_class').text(class_name);
    getComment(current_cmt);
    $('#comment_dialog').hide();
    $('#comment_dialog').dialog({
        title: "Teacher's Comment",
        width: "900px",
        resizable: false,
        modal: true,
        buttons :
        [
            {
                text: "Post",
                class    : 'button primary',
                click: function() {
                    _this.find('input[name="key_teacher_'+comment_name+'[]"]').val( $("[name=dialog_key_comment]").val());
                    _this.find('input[name="value_teacher_'+comment_name+'[]"]').val($("[name=dialog_text_comment]").val());
                    if($("[name=dialog_text_comment]").val().toString().length > 35) {
                        _this.find('.value_teacher_'+comment_name).text($("[name=dialog_text_comment]").val().toString().substring(0,35) + '...');
                    } else if($("[name=dialog_text_comment]").val().toString().length > 0) {
                        _this.find('.value_teacher_'+comment_name).text($("[name=dialog_text_comment]").val().toString());
                    } else {
                        _this.find('.value_teacher_'+comment_name).text('--None--');
                    }
                    _this.find('.value_teacher_'+comment_name).attr('title',$("[name=dialog_text_comment]").val());
                    $(this).dialog("close");
                }
            },{
                text: "Cancel",
                class    : 'button',
                click: function() {
                    $(this).dialog("close");
                }
            }
        ]
    });
}

function saveInput(){
    if(!allow_submit) return;
    var loading = new Spinner();
    loading.show("Saving...")
    var form = jQuery("#InputMark");
    jQuery("#save").parent().append('<span id = "save_loading"><img src="custom/include/images/loading.gif" align="absmiddle">&nbsp;Saving data... </span>');
    jQuery.ajax({  //Make the Ajax Request
        url: "index.php?module=J_Gradebook&sugar_body_only=true&action=ajaxGradebook",
        type: "POST",
        async: true,
        data: form.serialize(),
        dataType: "json",
        success: function(data){
            if(data.success == "1"){
                $('.content input[type=text]').removeAttr('readonly');
                $('.content select option:not(:selected)').show();
                $('.content select').removeClass('readonly');

                //#422
                var t_id  = $('input[name=c_teachers_j_gradebook_1c_teachers_ida]').val();
                var t_name = $('input[name=c_teachers_j_gradebook_1_name]').val();

                $('#gradebook_id').find("option[value='" + $('#gradebook_id').val() + "']").attr("teacher_id",t_id);
                $('#gradebook_id').find("option[value='" + $('#gradebook_id').val() + "']").attr("teacher_name", t_name);
                //end #422

                alertify.success("Saved successfully! ");
                window.onbeforeunload = null;
                loading.hide();
                $("#save_loading").remove();
            } else {
                alertify.error(SUGAR.language.get('J_Gradebook', data.errorLabel));
                loading.hide();
                $("#save_loading").remove();
            }
        },
        error: function(){
            alertify.error("Error! Please try again!");
            loading.hide();
            $("#save_loading").remove();
        }
    });
}

function handleBlurInput(){
    var _this = $(this);
    if(!Number(_this.attr('config-readonly')) && _this.val() > Number(_this.attr('config-max'))) {
        allow_submit = false;
        alertify.error('Please input a valid value! [0-' + _this.attr('config-max') + ']');
        add_error_style('InputMark',_this.attr('id'),'',true);
        _this.focus();
    } else {
        allow_submit = true;
        _this.closest('tr').find('input.input_mark[config-readonly="1"]').each(function(){
            var formula = $(this).attr("config-formula");
            formula = formula.replace("=",'');
            var formula_list = [];
            for(var i = 0; i < formula.length; i++) {
                if(formula[i].charCodeAt() >= 65 && formula[i].charCodeAt() <=90) { //form A->Z
                    var val = Number(_this.closest('tr').find('input.input_mark[config-alias="'+formula[i]+'"]').val());
                    var max = Number(_this.closest('tr').find('input.input_mark[config-alias="'+formula[i]+'"]').attr('config-max'));
                    var weight = Number(_this.closest('tr').find('input.input_mark[config-alias="'+formula[i]+'"]').attr('config-weight'));
                    formula_list.push(val/max * weight);
                } else {
                    formula_list.push(formula[i]);
                }
            }
            var result_eval = Number(eval(formula_list.join('')));

            if($(this).attr('config-alias') == 'T') {
                $(this).parent().find('span.final_result').text(Number(result_eval.toFixed(1)));
            }
            var max_mark = Number($(this).attr('config-max'));;

            if(max_mark) {
                $(this).val((result_eval * max_mark / 100).toFixed(1));
            } else {
                $(this).val(0);
            }
        });
    }
}

function getGradebookDetail(){
    var _this = $(this);
    ajaxStatus.showStatus('Proccessing...');
    _this.parent().append('<span id = "config_loading" ><img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>');

    //Edit by Tung Bui - before load ajax, clean gradebook detail & lock filter
    $(".gradebook_detail").html("");
    $("input[name=grade_config]").val("");
    $("input[name=weight]").val("");
    displayFilter(false);

    jQuery.ajax({
        url: "index.php?module=J_Gradebook&sugar_body_only=true&action=ajaxGradebook",
        type: "POST",
        async: true,
        data:{
            process_type: "getGradebookDetail",
            gradebook_id: $('#gradebook_id').val(),
            reloadconfig: _this.attr('grade-reload'),
        },
        success: function(data){
            data = JSON.parse(data);
            ajaxStatus.hideStatus();
            alertify.success("Completed!");
            $(".gradebook_detail").html(data.html);
            $("input[name=grade_config]").val(JSON.stringify(data.grade_config));
            $("input[name=weight]").val(data.weight);
            if(_this.attr('grade-reload')) $("#config_content tbody tr").find('.input_mark:first').trigger("blur");
            $("#config_loading").remove();
        },
        error: function(){
            ajaxStatus.hideStatus();
            alertify.error("There are errors. Please try again!");
            $("#config_loading").remove();
        },
    });
}

function handleChangeGradebook(){
    $(".gradebook_detail").html('');
    $("input[name=grade_config]").val("");
    $("input[name=weight]").val("");
    try{
        var teacher_id = $(this).find("option[value='" + $(this).val() + "']").attr("teacher_id");
        var teacher_name = $(this).find("option[value='" + $(this).val() + "']").attr("teacher_name");
    } catch(e) {
        var teacher_id = "";
        var teacher_name = "";
    }
    $('input[name=c_teachers_j_gradebook_1c_teachers_ida]').val(teacher_id);
    $('input[name=c_teachers_j_gradebook_1_name]').val(teacher_name);
}

function handleChangeClass(){
    //Add by Tung Bui -- Clean some input
    $(".gradebook_detail").html('');
    $("input[name=grade_config]").val("");
    $("input[name=weight]").val("");

    ajaxStatus.showStatus('Proccessing...');
    $("#gradebook_id").parent().append('<span id = "config_loading" ><img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>');
    jQuery.ajax({
        url: "index.php?module=J_Gradebook&sugar_body_only=true&action=ajaxGradebook",
        type: "POST",
        async: true,
        data:{
            process_type: "getGradebook",
            class_id:  $('#j_class_j_gradebook_1j_class_ida').val(),
        },
        success: function(data){
            data = JSON.parse(data);
            ajaxStatus.hideStatus();
            alertify.success("Completed!");
            $("#gradebook_id").html(data.html);
            $("#gradebook_id").val($("#gradebook_id option:first").val());
            $("#gradebook_id").trigger("change");

            $("#config_loading").remove();
        },
        error: function(){
            ajaxStatus.hideStatus();
            alertify.error("There are errors. Please try again!");
            $("#gradebook_id").html("");
            $("#gradebook_id").val($("#gradebook_id option:first").val());
            $("#gradebook_id").trigger("change");

            $("#config_loading").remove();
        },
    });
}

function displayFilter(visible){
    if(visible){
        $("#btn_j_class_j_gradebook_1_name").show();
        $("#btn_c_teachers_j_gradebook_1_name").show();
        $('.content input[type=text]').removeAttr('readonly');
        $('.content select').removeClass('readonly');
        $('.content select option:not(:selected)').show();
    }
    else{
        $("#btn_j_class_j_gradebook_1_name").hide();
        $("#btn_c_teachers_j_gradebook_1_name").hide();
        $('.content input[type=text]').attr('readonly','readonly');
        $('.content select').addClass('readonly');
        $('.content select option:not(:selected)').hide();
    }
}


function loadAttendance(alias){
    var student_list = [];
    $('input[name^="student_id"]').each(function(key, value) {
        student_list[key] =  $(this).val();
    });
    var class_id     = $('#j_class_j_gradebook_1j_class_ida').val();
    var gradebook_id = $('#gradebook_id').val();
    if(student_list.length > 0 && class_id != ''){
        ajaxStatus.showStatus('Proccessing...');
        jQuery.ajax({
            url: "index.php?module=J_Gradebook&sugar_body_only=true&action=ajaxGradebook",
            type: "POST",
            async: true,
            data:{
                process_type: "loadAttendance",
                class_id    : class_id,
                gradebook_id: gradebook_id,
                alias       : alias,
                student_list: student_list,
            },
            success: function(data){
                data = JSON.parse(data);
                ajaxStatus.hideStatus();
                if(data.success == '1'){
                    alertify.success("Completed!");
                    $.each(student_list, function(index, student_id) {
                        $('#'+student_id+'-'+alias).val(data.arrAtt[student_id]['attendance_rate']).trigger('blur').effect("highlight", {color: '#ff9933'}, 1000);
                    });
                }else
                    alertify.error("There are errors. Please try again!");

            },
            error: function(){
                ajaxStatus.hideStatus();
                alertify.error("There are errors. Please try again!");
                $("#config_loading").remove();
            },
        });
    }else{
        alertify.error("No students to show. Please try again later!");
    }
}

function loadHomework(alias){
    var student_list = [];
    $('input[name^="student_id"]').each(function(key, value) {
        student_list[key] =  $(this).val();
    });
    var class_id     = $('#j_class_j_gradebook_1j_class_ida').val();
    var gradebook_id = $('#gradebook_id').val();
    if(student_list.length > 0 && class_id != ''){
        ajaxStatus.showStatus('Proccessing...');
        jQuery.ajax({
            url: "index.php?module=J_Gradebook&sugar_body_only=true&action=ajaxGradebook",
            type: "POST",
            async: true,
            data:{
                process_type: "loadHomework",
                class_id    : class_id,
                gradebook_id: gradebook_id,
                alias       : alias,
                student_list: student_list,
            },
            success: function(data){
                data = JSON.parse(data);
                ajaxStatus.hideStatus();
                if(data.success == '1'){
                    alertify.success("Completed!");
                    $.each(student_list, function(index, student_id) {
                        $('#'+student_id+'-'+alias).val(data.arrAtt[student_id]['homework_rate']).trigger('blur').effect("highlight", {color: '#ff9933'}, 1000);
                    });
                }else
                    alertify.error("There are errors. Please try again!");

            },
            error: function(){
                ajaxStatus.hideStatus();
                alertify.error("There are errors. Please try again!");
                $("#config_loading").remove();
            },
        });
    }else{
        alertify.error("No students to show. Please try again later!");
    }
}

function loadMinitest(alias){
    var student_list = [];
    $('input[name^="student_id"]').each(function(key, value) {
        student_list[key] =  $(this).val();
    });
    var class_id     = $('#j_class_j_gradebook_1j_class_ida').val();
    var gradebook_id = $('#gradebook_id').val();
    if(student_list.length > 0 && class_id != ''){
        ajaxStatus.showStatus('Proccessing...');
        jQuery.ajax({
            url: "index.php?module=J_Gradebook&sugar_body_only=true&action=ajaxGradebook",
            type: "POST",
            async: true,
            data:{
                process_type: "loadMinitest",
                class_id    : class_id,
                gradebook_id: gradebook_id,
                alias       : alias,
                student_list: student_list,
            },
            success: function(data){
                data = JSON.parse(data);
                ajaxStatus.hideStatus();
                if(data.success == '1'){
                    alertify.success("Completed!");
                    $.each(student_list, function(index, student_id) {
                        $('#'+student_id+'-'+alias).val(data.arrAtt[student_id]['rate']).trigger('blur').effect("highlight", {color: '#ff9933'}, 1000);
                    });
                }else
                    alertify.error("There are errors. Please try again!");

            },
            error: function(){
                ajaxStatus.hideStatus();
                alertify.error("There are errors. Please try again!");
                $("#config_loading").remove();
            },
        });
    }else{
        alertify.error("No students to show. Please try again later!");
    }
}
function loadProject(alias){
    var student_list = [];
    $('input[name^="student_id"]').each(function(key, value) {
        student_list[key] =  $(this).val();
    });
    var class_id     = $('#j_class_j_gradebook_1j_class_ida').val();
    var gradebook_id = $('#gradebook_id').val();
    if(student_list.length > 0 && class_id != ''){
        ajaxStatus.showStatus('Proccessing...');
        jQuery.ajax({
            url: "index.php?module=J_Gradebook&sugar_body_only=true&action=ajaxGradebook",
            type: "POST",
            async: true,
            data:{
                process_type: "loadProject",
                class_id    : class_id,
                gradebook_id: gradebook_id,
                alias       : alias,
                student_list: student_list,
            },
            success: function(data){
                data = JSON.parse(data);
                ajaxStatus.hideStatus();
                if(data.success == '1'){
                    alertify.success("Completed!");
                    $.each(student_list, function(index, student_id) {
                        $('#'+student_id+'-'+alias).val(data.arrAtt[student_id]['rate']).trigger('blur').effect("highlight", {color: '#ff9933'}, 1000);
                    });
                }else
                    alertify.error("There are errors. Please try again!");

            },
            error: function(){
                ajaxStatus.hideStatus();
                alertify.error("There are errors. Please try again!");
                $("#config_loading").remove();
            },
        });
    }else{
        alertify.error("No students to show. Please try again later!");
    }
}