var student_id_ptdemo;
var student_name_ptdemo;
$(document).ready(function () {
    //hide subpanel default
    $('#list_subpanel_sub_pt_result table:eq(1)').hide();
    //table sorter
    dragTable();
    //handle checkbox attended
    $('#form_result :checkbox').live("click", function(){
        if($(this).is(':checked')) {
            $(this).closest('tr').find('.check_attended').val(1);
        }else{
            $(this).closest('tr').find('.check_attended').val(0);
        }
    });

    //Auto validate number
    $('input.score_result_koc, input.writing, input.speaking, input.listening, input.reading').keyup(function(){
        this.value = numberFormat(this.value);
    });

    ///calculate limit row
    cal_limit_row();

    $('#first_day_mt').val($('#date_start').text());

    //set multifield
    $('#diagnosis_list').multifield({
        section: '.pt_template',
        addTo   :   '#tbodyPT', // Append new section to position
        btnAdd:'#btnAddRow',
        btnRemove:'.btn-delete',
        min: 0,
    });
    ///---------Save PT Result-----------////////////////
    $(".writing, .speaking, .listening, .reading").live("change", function(){
        // validate scores 1->100
        if (Numeric.parse($(this).val()) < 0 || Numeric.parse($(this).val()) > 100) {
            $(this).val('0');
        } else {
            $(this).val(Numeric.toInt($(this).val(),2,2));
        }

        var tr_template = $(this).closest('tr').find('input, textarea, select');   
        ajaxUpdatePTResult(tr_template);

    });
    $(".score_result_koc").each(function() {
        handleScore($(this),false);
    });
    $(".result_koc").live("change", function(){
        handleScore($(this),true);
    });
    $(".score_result_koc").live("change", function(){
        $(this).val(Numeric.toInt($(this).val()));
        handleScore($(this),true);
    });
    $(".write_levels,.listen_levels,.speak_levels,.result_levels").live("change", function(){
        var level_selected = $(this).val();
        $(this).closest('td').find('.select2-chosen:last').text(level_selected);
        $(this).closest('tr').find('input.attended').prop('checked',true);
        var tr_template = $(this).closest('tr').find('input, textarea, select');
        ajaxUpdatePTResult(tr_template);
    });
    $(".ec_note, .teacher_comment").live('change',function(){
        var tr_template = $(this).closest('tr').find('input, textarea, select');
        ajaxUpdatePTResult(tr_template);
    });
    //    $(".attended").live('change',function(){
    //        var tr_template = $(this).closest('tr').find('input');
    //        if($(this).is(':checked')) {
    //            $(this).closest('tr').addClass("color_template");
    //            $(this).closest('tr').find('.check_attended').val(1);
    //            $(this).closest('tr').find('.add_to_pt').attr('style', 'display:none');
    //        }else{
    //            tr_template.closest('tr').removeClass("color_template");
    //            tr_template.closest('tr').find('.check_attended').val(0);
    //            tr_template.closest('tr').find('.add_to_pt').attr('style', 'display:block');
    //        }
    //        var tr_template = $(this).closest('tr').find('input, textarea, select');
    //        ajaxUpdatePTResult(tr_template);
    //    });
    //-----------end Save PT Result---------------/////////////

    $('#diagnosis_list tbody tr').each(function() {
        var check_attended = $(this).find(".check_attended").val();
        if(check_attended == 0){
            $(this).find('.add_to_pt').attr('style', 'display:block');
            $(this).removeClass("color_template");
        }
    });

});

function handleAddRow() {
    var time_bef;
    var time_bef_1;
    var time_range=parseInt($('#time_range').val());
    $('#time_range').attr('readonly', true);
    $('#time_range').addClass("input_readonly");
    ///////-------case remove all table add first//////
    if($('#tbodyPT tr:visible').length-1<=0){
        //$('#tbodyPT tr:last').hide();
        var time_start=$('#time_start_pt').val();
        $('#tbodyPT tr:visible').find('.start').text(time_start);
        $('#tbodyPT tr:visible').find('.start_hidden').val(time_start);

        //////////Calculate Time//////////////////
        $('#tbodyPT tr:visible').find('.end').text(addTimeMinutes(time_start,time_range));
        $('#tbodyPT tr:visible').find('.end_hidden').val(addTimeMinutes(time_start,time_range));
        //////////End Calculate Time//////////////////
    }

    ///Case after fisrt row//
    else{
        //$('#tbodyPT tr:last').hide();
        var old_to_time=$('#tbodyPT tr:last-child').prev().find('.end').text();
        $('#tbodyPT tr:last-child').find('.start').text(old_to_time);
        $('#tbodyPT tr:last-child').find('.start_hidden').val(old_to_time);

        //////////Calculate Time//////////////////
        $('#tbodyPT tr:last-child').find('.end').text(addTimeMinutes(old_to_time,time_range));
        $('#tbodyPT tr:last-child').find('.end_hidden').val(addTimeMinutes(old_to_time,time_range));
        //////////End Calculate Time//////////////////
    }
    //renum order table//
    $('#tbodyPT tr').each(function () {
        count = $(this).parent().children().index($(this)) + 1;
        $(this).find('.priority').html(count-1);
        $(this).find('.order').val(count-1);
    });
    //////// End renum order table

    sortTime();
    //$('#tbodyPT tr:last-child').find('select.level').select2({minimumResultsForSearch: Infinity,width: '120px'});
    //$('#tbodyPT tr:last-child').find('select.koc').select2({minimumResultsForSearch: Infinity,width: '120px'});
}

function handleRemoveRow(){
    renumber_table('#diagnosis_list');
    saveResultAll();
}

//Renumber table rows
function renumber_table(tableID) {
    $(tableID + " tr").each(function () {
        count = $(this).parent().children().index($(this)) + 1;

        //////////-- this is the line that I need to change
        $(this).find('.priority').html(count-1);
        $(this).find('.order').val(count-1);
        //////// --- I would like to put sequencial count in the "priority filed, in the sequence INPUT.

    });

    ///sort time
    sortTime();
}

function sortTime(){
    var time_range=parseInt($('#time_range').val());
    var time_start = [];
    var time_end = [];
    var time_bef;
    var num = $(".pt_template").length;

    var old_to_time=$('#time_start_pt').val();

    time_start.push(old_to_time);
    for ( var i=1 ; i < num ; i++){
        var j = i-1;
        var old_to_time = time_start[j];
        out_time = addTimeMinutes(old_to_time,time_range);
        time_start.push(out_time);
        time_end.push(out_time);
    }
    for ( var i=0 ; i < num ; i++){
        var old_to_time = time_end[0];
        out_time = addTimeMinutes(old_to_time,time_range);
        time_end.push(out_time);

    }
    for (var i=0 ;i < (time_start).length -1 ; i ++){
        var j = i+2;
        $('#tbodyPT tr:nth-child('+j+')').find('.start').text(time_start[i]);
        $('#tbodyPT tr:nth-child('+j+')').find('.start_hidden').val(time_start[i]);
        $('#tbodyPT tr:nth-child('+j+')').find('.end').text(time_end[i]);
        $('#tbodyPT tr:nth-child('+j+')').find('.end_hidden').val(time_end[i]);
    }
}

function saveResultAll(){
    ajaxStatus.showStatus('Saving...');
    $.ajax({
        type: "POST",
        url: "index.php?module=Meetings&action=saveResult&sugar_body_only=true",
        data: $('#form_result').serialize()+'&type=ajaxRemovePTResult',
        success:function(data){
            ajaxStatus.hideStatus();
        }
    });
}

function dragTable(){
    //Helper function to keep table row from collapsing when being sorted
    var fixHelperModified = function (e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function (index) {
            $(this).width($originals.eq(index).width());
        });
        return $helper;
    };

    //Make diagnosis table sortable
    $("#diagnosis_list tbody").sortable({
        helper: fixHelperModified,
        stop: function (event, ui) {
            renumber_table('#diagnosis_list');
            saveResultAll();
        }
    });
}

//function set_return of open popup
function set_return_parent(popup_reply_data, filter) {
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    var check = checkStudentLeadInPT(name_to_value_array['id'],$("input[name='record']").val(),'Placement Test');
    var parent_type = $('#parent_type').val();
    if(check){
        $("#btnAddRow").trigger( "click" );
        $('#tbodyPT tr:last').find('.attended').prop('checked',false);
        $('#tbodyPT tr:last').find('.check_attended').val(0);
        var _lastrow = $('#tbodyPT tr:last');
        for (var the_key in name_to_value_array) {
            var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
            switch (the_key)
            {
                case 'name':
                    _lastrow.find('.pt_name').text(val);
                    break;
                case 'id':
                    _lastrow.find('.pt_id').val(val);
                    _lastrow.find('.parent').val(parent_type);
                    _lastrow.find('.pt_name').attr('href', 'index.php?module=' + parent_type + '&action=DetailView&record='+val);
                    break;
                case 'birthdate':
                    _lastrow.find('.birthdate').text(val);
                    break;
                case 'phone_mobile':
                    _lastrow.find('.phone_mobile').text(val);
                    break;
                case 'assigned_user_name':
                    _lastrow.find('.assigned_user_name').text(val);
                    break;
                case 'lead_source':
                    _lastrow.find('.lead_source').text(val);
                    break;
                case 'c_contacts_contacts_1_name':
                case 'guardian_name':
                    if(val != "undefined")
                        _lastrow.find('.parent').text(val);
                    break;
            }
        }
        saveResult(_lastrow.find('input, textarea, select'));
    }else{
        alertify.error(SUGAR.language.get('Meetings','LBL_EXIST_STUDENT'));
    }
}

// Show popup choose Lead or Student In PT
function clickChooseLead(){
    var module = $('#parent_type').val();
    var duration = parseInt($('input#limit_row_pt').val());
    var count_row_table=$('#tbodyPT tr').length - 1;
    open_popup(module,600, 400, "", true, false, {"call_back_function":"set_return_parent","form_name":"DetailView","field_to_name_array":{"name":"name","id":"id","birthdate":"birthdate","phone_mobile":"phone_mobile","assigned_user_name":"assigned_user_name","guardian_name":"guardian_name","c_contacts_contacts_1_name":"c_contacts_contacts_1_name","lead_source":"lead_source"}}, "single", true);
}

function saveResult(tr_template){
    ajaxStatus.showStatus('Saving ...');
    $.ajax({
        type: "POST",
        url: "index.php?module=Meetings&action=saveResult&sugar_body_only=true",
        data:tr_template.serialize()+'&first_day_mt='+$('#first_day_mt').val()
        +'&get_pt_date='+$('#get_pt_date').val()+'&meeting_id='+$("input[name='record']").val()
        +'&time_range='+$('#time_range').val()+'&type=ajaxSaveSelect',
        dataType: "json",
        success:function(data){
            if(data.success == "1"){
                tr_template.closest('tr').find('.id_of_result, .custom_checkbox').val(data.id_result);
                ajaxStatus.hideStatus();
            }else{
                ajaxStatus.hideStatus();
                alertify.alert('Đã có lỗi xảy ra, vui lòng kiểm tra lại thông tin!');
            }
        }
    });
}

function addTimeMinutes(input,time_range){
    var  output, hours, minutes, time, new_time, new_to_time;
    if(input.indexOf('.')==-1){//no exist "."
        time_bef=input.split(":");
        if(time_bef[1].indexOf(' ')==-1){ ///no exist space
            if(time_bef[1].indexOf('pm')==-1&&time_bef[1].indexOf('PM')==-1&&time_bef[1].indexOf('AM')==-1&&time_bef[1].indexOf('am')==-1){//case: 23:00{
                hours = parseInt(time_bef[0]);
                minutes = parseInt(time_bef[1]);
                time = {hour:hours, minute:minutes};
                new_time = Date.today().at(time);
                new_to_time = new_time.addMinutes(time_range);
                output = new_to_time.toString("HH:mm");
            }else{ //case 11:00pm 11:00PM
                hours = parseInt(time_bef[0]);
                minutes = parseInt(time_bef[1].substr(0,2));
                time = {hour:hours, minute:minutes};
                new_time = Date.today().at(time);
                new_to_time = new_time.addMinutes(time_range);
                var session=time_bef[1].slice(-2);
                if(session.indexOf('m')==-1){//AM-PM
                    if(time_bef[1].indexOf('p')==-1){ //A
                        output=new_to_time.toString("hh:mmtt");
                    } else{//p
                        output=new_to_time.toString("hh:mmtt").replace("A", "P");
                    }

                }else{ //am-pm
                    if(session.indexOf('p')==-1){ //a
                        output=new_to_time.toString("hh:mmtt").toLowerCase();
                    } else{//p
                        output=new_to_time.toString("hh:mmtt").toLowerCase().replace("a", "p");
                    }
                }
            }
        }else{ ///exist space
            time_bef_1=time_bef[1].split(' ');
            hours= parseInt(time_bef[0]);
            minutes=parseInt(time_bef_1[0])
            time = {hour:hours, minute:minutes};
            new_time = Date.today().at(time);
            new_to_time=new_time.addMinutes(time_range);
            if(time_bef[1].indexOf('m')==-1){//AM-PM
                if(time_bef[1].indexOf('p')==-1){ //A
                    output=new_to_time.toString("hh:mm tt");
                } else{//p
                    output=new_to_time.toString("hh:mm tt").replace("A", "P");
                }

            }else{ //am-pm
                if(time_bef[1].indexOf('p')==-1){ //a
                    output=new_to_time.toString("hh:mm tt").toLowerCase();
                } else{//p
                    output=new_to_time.toString("hh:mm tt").toLowerCase().replace("a", "p");
                }
            }
        }
    }
    else{//exist "."
        time_bef=input.split(".");
        if(time_bef[1].indexOf(' ')==-1){ ///no exist space
            if(time_bef[1].indexOf('pm')==-1&&time_bef[1].indexOf('PM')==-1&&time_bef[1].indexOf('AM')==-1&&time_bef[1].indexOf('am')==-1){//case: 23:00{
                hours = parseInt(time_bef[0]);
                minutes = parseInt(time_bef[1]);
                time = {hour:hours, minute:minutes};
                new_time = Date.today().at(time);
                new_to_time = new_time.addMinutes(time_range);
                output = new_to_time.toString("HH.mm");
            }else{ //case 11:00pm 11:00PM
                hours = parseInt(time_bef[0]);
                minutes = parseInt(time_bef[1].substr(0,2));
                time = {hour:hours, minute:minutes};
                new_time = Date.today().at(time);
                new_to_time = new_time.addMinutes(time_range);
                var session=time_bef[1].slice(-2);
                if(session.indexOf('m')==-1){//AM-PM
                    if(time_bef[1].indexOf('p')==-1){ //A
                        output=new_to_time.toString("hh.mmtt");
                    } else{//p
                        output=new_to_time.toString("hh.mmtt").replace("A", "P");
                    }

                }else{ //am-pm
                    if(session.indexOf('p')==-1){ //a
                        output=new_to_time.toString("hh.mmtt").toLowerCase();
                    } else{//p
                        output=new_to_time.toString("hh.mmtt").toLowerCase().replace("a", "p");
                    }
                }
            }
        }else{ ///exist space
            time_bef_1=time_bef[1].split(' ');
            hours= parseInt(time_bef[0]);
            minutes=parseInt(time_bef_1[0])
            time = {hour:hours, minute:minutes};
            new_time = Date.today().at(time);
            new_to_time=new_time.addMinutes(time_range);
            if(time_bef[1].indexOf('m')==-1){//AM-PM
                if(time_bef[1].indexOf('p')==-1){ //A
                    output=new_to_time.toString("hh.mm tt");
                } else{//p
                    output=new_to_time.toString("hh.mm tt").replace("A", "P");
                }

            }else{ //am-pm
                if(time_bef[1].indexOf('p')==-1){ //a
                    output=new_to_time.toString("hh.mm tt").toLowerCase();
                } else{//p
                    output=new_to_time.toString("hh.mm tt").toLowerCase().replace("a", "p");
                }
            }
        }
    }
    return output;
}

function cal_limit_row(){
    var limit_row_pt=0;
    if($('#time_range').val()!=""){//neu time range khac rong
        limit_row_pt=parseInt($('#duration_pt').val())/parseInt($('#time_range').val());
    }else{//neu time range bang rong thi mac dinh cho time range=10p
        $('#time_range').val(10);
        limit_row_pt=parseInt($('#duration_pt').val())/parseInt($('#time_range').val());
    }
    $('#limit_row_pt').val(limit_row_pt);
}

function checkTimeRange(){
    var duration_default = parseInt($('#duration_pt').val());
    if(parseInt($('#time_range').val()) > duration_default ){
        $('#time_range').val('');
    }
    cal_limit_row();
}

function checkStudentLeadInPT(id,meeting_id,type){
    var check = false;
    $.ajax({
        type: "POST",
        async:false,
        url: "index.php?module=Meetings&action=saveResult&sugar_body_only=true",
        data:{
            student_id : id,
            meeting_id : meeting_id,
            meeting_type :  type,
            type : 'checkStudentLeadMeeting'
        },
        dataType: "json",
        success:function(data){
            if(data.id_result) {
                check = true;
            }else{
                check = false;
            }
        }
    });
    return check;
}

function ajaxUpdatePTResult(tr_template){   
    $.ajax({
        type: "POST",
        url: "index.php?module=Meetings&action=saveResult&sugar_body_only=true",
        data:tr_template.serialize()+'&type=ajaxUpdatePTResult',
        dataType: "json",
        success:function(data){
            if(data.success == "1"){    
            }else{                       
                alertify.alert('Đã có lỗi xảy ra, vui lòng kiểm tra lại thông tin!');
            }

        }
    });
}

function beforeRemoveSection(section) {
    ajaxStatus.showStatus('Deleting...');
    var result = false;
    $.ajax({
        type: "POST",
        url: "index.php?module=Meetings&action=saveResult&sugar_body_only=true",
        async: false,
        data:{
            type: 'deletePTResult',
            result_id: $(section).find('.id_of_result').val(),
        },
        dataType: "json",
        success:function(data){
            if(data.success == "1"){
                result = true;
                ajaxStatus.hideStatus();
            }else{
                ajaxStatus.hideStatus();
                alertify.alert('Đã có lỗi xảy ra, vui lòng thử lại sau!');
            }
        }
    });
    return result;
}

function handleScore(inputS, is_update){
    var tr_res = inputS.closest('tr');
    if(inputS.hasClass("score_result_koc")){
        if(inputS.val() != '' && tr_res.find(".result_koc").val() != ''){
            inputS.closest('tr').find('input.attended').prop('checked',true);
            inputS.closest('tr').find('.check_attended').val(1);
            inputS.closest('tr').find('.attended_label').removeClass('no_attended').addClass('yes_attended').text('Yes');
        }else{
            inputS.closest('tr').find('input.attended').prop('checked',false);
            inputS.closest('tr').find('.check_attended').val(0);
            inputS.closest('tr').find('.attended_label').removeClass('yes_attended').addClass('no_attended').text('No');
        }

//        if(typeof SUGAR.language.languages['app_list_strings'] != 'undefined'){
//            var score_pt_list = SUGAR.language.languages['app_list_strings']['koc_score_pt_list'];
//            var score = parseInt(inputS.val());
//            var catched = false;
//            var result_koc = inputS.closest('tr').find('.result_koc');
//            if(is_update) result_koc.effect("highlight", {color: '#ff9933'}, 1000);
//            result_koc.find('option').not(':first').hide();
//            var selected = result_koc.val();
//            var catch_sel = 0;
//            $.each(score_pt_list, function( index, value ) {
//                var range = index.toString().split('-');
//                if(score >= parseInt(range[0]) && score <= parseInt(range[1])){
//                    var options = value.split('|');
//                    $.each(options, function( ind, option ) {
//                        result_koc.find('option[value="' + option + '"]').show();
//                        if(selected == option) catch_sel++;
//                    });
//                    catched = true;
//                    return false;
//                }
//            });
//            if(!catched)
//                result_koc.find('option').not(':first').show();
//            if(catch_sel == 0)
//                result_koc.val('');
//        }
    }
    if(inputS.hasClass("result_koc")){
        if(inputS.val() != '' && tr_res.find(".score_result_koc").val() != ''){
            inputS.closest('tr').find('input.attended').prop('checked',true);
            inputS.closest('tr').find('.check_attended').val(1);
            inputS.closest('tr').find('.attended_label').removeClass('no_attended').addClass('yes_attended').text('Yes');
        }else{
            inputS.closest('tr').find('input.attended').prop('checked',false);
            inputS.closest('tr').find('.check_attended').val(0);
            inputS.closest('tr').find('.attended_label').removeClass('yes_attended').addClass('no_attended').text('No');
        }
    }

    if(is_update){
        var tr_template = inputS.closest('tr').find('input, textarea, select');
        ajaxUpdatePTResult(tr_template);
    }

}