$(document).ready(function(){
    var record_id = $('input[name=record]').val();

    $('#tblLevelConfig').multifield({
        section :   '.row_tpl', // First element is template
        addTo   :   '#tbodylLevelConfig', // Append new section to position
        btnAdd  :   '#btnAddrow', // Button Add id
        btnRemove:  '.btnRemove', // Buton remove id
        prompt  :  false,  //confirm ?
    });

    $('#kind_of_course').on('change',function(){
        $('#name').val($(this).val());
    });

    $('.levels, .modules, .hours, .is_upgrade, .is_set_hour').live('change',function(){
        var row = $(this).closest('.row_tpl');
        if(row.find('.hours').val() < 0)
            row.find('.hours').val(0);
        saveJson(row);
    });

    $('#tblSyllabusContent').multifield({
        section :   '.rowSyllabus', // First element is template
        addTo   :   '#tbodySyllabus', // Append new section to position
        btnAdd  :   '#btnAddSyl', // Button Add id
        btnRemove:  '.btnRemoveSyl', // Buton remove id
        prompt  :  false,
    });
    $('.sys_lesson, .sys_content').live('change',function(){
        var row = $(this).closest('.rowSyllabus');
        saveJsonSyl(row);
    }); 

    removeRow();
});

// Lưu json để đưa xuống database
function saveJson(row){
    json = {};
    json.levels     = row.find('.levels').val();
    json.modules    = row.find('.modules').val();
    json.hours      = row.find('.hours').val();

    if(row.find('.is_upgrade').is(":checked"))
        json.is_upgrade= '1';
    else json.is_upgrade= '0';

    if(row.find('.is_set_hour').is(":checked"))
        json.is_set_hour= '1';
    else json.is_set_hour= '0';

    var json_str    = JSON.stringify(json);
    //Assign json
    row.find("input.jsons").val(json_str);
}

// Lưu syllabus
function saveJsonSyl(row){
    json = {};
    json.lesson     = row.find('.sys_lesson').val();
    json.content    = row.find('.sys_content').val();
    if(json.lesson != ''){
        //Assign json
        var json_str    = JSON.stringify(json);
        row.find("input.json_syl").val(json_str);   
    }
}

//Overwrite check_form to validate
function check_form(formname) {
    //Validate level config
    var validateConfig = true;
    $('.hours,.final_test').each(function () {
        if ($(this).val() == "" && $(this).closest("tr").is(":visible") && ($(this).closest("tr").find('.is_set_hour').is(":checked"))){
            $(this).effect("highlight", {color: '#FF0000'}, 2000);
            validateConfig = false;
        }
    });
    
    return validate_form(formname, '') && validateConfig;
}

function handleAddRow(_tbl){
    var last_tr = _tbl.find('tr:last').prev();
    var last_lesson = last_tr.find('input.sys_lesson').val();
    //assign
    _tbl.find('tr:last').find('input.sys_lesson').val(parseInt(parseInt(last_lesson)+1))
}
function removeRow(){
    $('.btnRemoverow').live('click', function(){
        var row = $(this).closest('tr');
        row.fadeOut(300, function () {
                row.remove();
            });
    });
}

/* Comment by Tung Bui - 24/11/2015
//function set_return of open popup
function set_return_book(popup_reply_data, filter) {
var form_name = popup_reply_data.form_name;

var name_to_value_array = popup_reply_data.name_to_value_array;
for (var the_key in name_to_value_array) {
var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');

switch (the_key)
{
case 'book_name':
currentBook.parent().find(".book_name").val(val);
currentBook.parent().find(".book_name").trigger('change');
break;
case 'book_id':
currentBook.parent().find(".book_id").val(val);
break;
}
}

}

// Show popup search student
function clickChooseBook(thisButton){
currentBook =  thisButton;
open_popup('ProductTemplates', 600, 400, "", true, false, {"call_back_function":"set_return_book","form_name":"EditView","field_to_name_array":{"id":"book_id","name":"book_name"}}, "single", true);
};
*/

