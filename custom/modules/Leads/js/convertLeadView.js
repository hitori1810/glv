$(document).ready(function(){
    addToValidate('ConvertLead','Contactsj_school_contacts_1_name', text, true, SUGAR.language.translate('Leads','LBL_SCHOOL_NOTI'));
    addToValidate('ConvertLead','Contactsguardian_name', text, true, SUGAR.language.translate('Leads','LBL_CONTACT_NAME_NOTI'));
    // select 2
    $('#Contactsgender').select2({minimumResultsForSearch: Infinity,width: '150px'});
    $('#Contactslead_source').select2({minimumResultsForSearch: Infinity,width: '250px'});
    $('#Contactspreferred_kind_of_course').select2({width: '250px'});
    
    //add relationship
    $('#tblLevelConfig').multifield({
        section :   '.row_tpl', // First element is template
        addTo   :   '#tbodylLevelConfig', // Append new section to position 
        btnAdd  :   '#btnAddrow', // Button Add id
        btnRemove:  '.btnRemove', // Buton remove id
    });
    // lưu levels, module, book và chuỗi json
    $('#select, .select_rela, .rela_name').live('change',function(){            
        var row = $(this).closest('.row_tpl');
        saveJsonRelationship(row); 
    });
    $('#select, .select_rela, .student_name').trigger('change');
    
    //choose company
    $('#choose_company').live('click', function(){
      open_popup("Accounts", 600, 400, "", true, false, {"call_back_function":"set_return_company","form_name":"EditView","field_to_name_array":{"id":"company_id","name":"company_name"}}, "single", true);  
    });
    
     //choose parent
    $('#btn_Contactsc_contacts_contacts_1_name').removeAttr('onclick');
    $('#btn_Contactsc_contacts_contacts_1_name').live('click', function(){
        open_popup("C_Contacts", 600, 400, "", true, false, {"call_back_function":"set_return_contact","form_name":"EditView","field_to_name_array":{"id":"Contactsc_contacts_contacts_1c_contacts_ida","name":"Contactsc_contacts_contacts_1_name","email1":"Leads0emailAddress0","address":"primary_address_street"}}, "single", true);  
    });
    
});

//function set_return of open popup 
function set_return_rela(popup_reply_data, filter) {
    
    var form_name = popup_reply_data.form_name;

    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');

        switch (the_key)
        {
            case 'rela_name':
                currentRela.parent().find(".rela_name").val(val); 
                currentRela.parent().find(".rela_name").trigger('change');     
                break;
            case 'rela_id':
                currentRela.parent().find(".rela_id").val(val);      
                break;
        }
    }
}

// Show popup search student, lead
function clickChooseRela(thisButton){
    currentRela =  thisButton;
    var module = currentRela.closest('tr').find('select#select').val();
    if(module == "Leads")
        module = "Leads";
    else module = "Contacts";
    open_popup(module, 600, 400, "", true, false, {"call_back_function":"set_return_rela","form_name":"EditView","field_to_name_array":{"id":"rela_id","name":"rela_name"}}, "single", true);
};

// Clear row relationship
function clickClearRela(thisButton){
    thisButton.parent().find(".rela_name").val('');
    thisButton.parent().find(".rela_id").val('');
}

// Make Json to handle Save relationship
function saveJsonRelationship(row){
    var json =  jQuery.parseJSON(row.find("input.jsons").val());
    if(json == null){
        json = {};
    }
    json.index      = row.index();
    json.select     = row.find('#select').val();
    json.select_rela    = row.find('.select_rela').val();

    json.rela_name  = row.find('.rela_name').val();
    json.rela_id    = row.find('.rela_id').val(); 
    var json_str = JSON.stringify(json);
    //Assign json
    row.find("input.jsons").val(json_str); 
}

function set_return_company(popup_reply_data, filter) {
    
    var form_name = popup_reply_data.form_name;

    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');

        switch (the_key)
        {
            case 'company_name':
                this.parent().find(".company_name").val(val);      
                break;
            case 'company_id':
                this.parent().find(".company_id").val(val);      
                break;
        }
    }
}

//function set_return_contact of open popup 
function set_return_contact(popup_reply_data, filter) {
    var form_name = popup_reply_data.form_name;

    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');

        switch (the_key)
        {
            case 'Contactsc_contacts_contacts_1_name':
                $("#Contactsc_contacts_contacts_1_name").val(val);      
                break;
            case 'Contactsc_contacts_contacts_1c_contacts_ida':
                $("#Contactsc_contacts_contacts_1c_contacts_ida").val(val);      
                break;
            case 'Leads0emailAddress0':
                $("#Leads0emailAddress0").val(val);      
                break;
            case 'primary_address_street':
                $("#primary_address_street").val(val);      
                break;
        }
    }
}