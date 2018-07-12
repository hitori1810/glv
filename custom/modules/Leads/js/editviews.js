var currentRela      = 1;     

$(document).ready(function(){
    if( $("#status").val() != 'Converted' ){
        $('#status').find('option[value=Converted]').prop('disabled', true).addClass('input_readonly');
    }else{
        $("#status option:not(:selected)").prop('disabled', true).addClass('input_readonly');
    }

    //Show Hide nationality
    $('input:radio[name="radio_national"]').click(function(){
        if($(this).val()== "Việt Nam"){
            $('#nationality').val("Việt Nam");
            $('#nationality').hide();
        } else {
            $('#nationality').show();
            $('#nationality').val("");
        }
    });

    // Show Reason not interested when Edit or Create  
    reasonNotIntersted($('#potential').val());

    // Show Reason not interested wher change potentialLevel
    $('#potential').change(function(){ 
        reasonNotIntersted($('#potential').val());
    });
    
    //choose parent
    $('#btn_c_contacts_leads_1_name').removeAttr('onclick');
    $('#btn_c_contacts_leads_1_name').live('click', function(){
        open_popup("C_Contacts", 600, 400, "", true, false, {"call_back_function":"set_return_contact","form_name":"EditView","field_to_name_array":{"id":"c_contacts_leads_1c_leads_ida","name":"c_contacts_leads_1_name","email1":"Leads0emailAddress0","address":"primary_address_street","mobile_phone":"phone_mobile"}}, "single", true);
    });

    //Copy to Student Contact
    $('#copy_parent_1').click(function(){
        $('#copy_parent_2').text(SUGAR.language.get('Leads','LBL_COPY_TO_STUDENT'));
        $('#copy_parent_2').attr('data-type', 0);
        $('#copy_parent_2').next().css('display', 'none');
        copyToStudentsContact(this, $('#other_mobile').val(), $('#email_parent_1').val());
    });
    $('#copy_parent_2').click(function(){
        $('#copy_parent_1').text(SUGAR.language.get('Leads','LBL_COPY_TO_STUDENT'));
        $('#copy_parent_1').attr('data-type', 0);
        $('#copy_parent_1').next().css('display', 'none');
        copyToStudentsContact(this, $('#phone_other').val(), $('#email_parent_2').val());
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
                currentRela.closest('td').find(".rela_name").val(val);
                currentRela.closest('td').find(".rela_name").trigger('change');
                break;
            case 'rela_id':
                currentRela.closest('td').find(".rela_id").val(val);
                break;
        }
    }
}

// Show popup search student, lead
function clickChooseRela(thisButton){
    currentRela =  thisButton;
    var module = currentRela.closest('tr').find('select#select').val();
    open_popup(module, 600, 400, "", true, false, {"call_back_function":"set_return_rela","form_name":"EditView","field_to_name_array":{"id":"rela_id","name":"rela_name"}}, "single", true);
};

// Clear row relationship
function clickClearRela(thisButton){
    thisButton.closest('td').find(".rela_name").val('');
    thisButton.closest('td').find(".rela_id").val('');
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

//function set_return_contact of open popup
function set_return_contact(popup_reply_data, filter) {
    var form_name = popup_reply_data.form_name;

    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');

        switch (the_key)
        {
            case 'c_contacts_leads_1_name':
                $("#c_contacts_leads_1_name").val(val);
                break;
            case 'c_contacts_leads_1c_leads_ida':
                $("#c_contacts_leads_1c_contacts_ida").val(val);
                break;
            case 'phone_mobile':
                $("#phone_mobile").val(val);
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

// Add by Nguyen Tung 4-6-2018
// Function Show Reason not interested
function reasonNotIntersted(potentialLevel) {
    if(potentialLevel == 'Not Interested') {      
        $('#reason_not_interested_label').closest("tr").find("td:eq(2)").show();    
        $('#reason_not_interested_label').closest("tr").find("td:eq(3)").show();    
        $('#reason_description_label').closest("tr").show();    
    } else {                                                    
        $('#reason_not_interested_label').closest("tr").find("td:eq(2)").hide();    
        $('#reason_not_interested_label').closest("tr").find("td:eq(3)").hide();     
        $('#reason_description_label').closest("tr").hide();    
                                                                
        $('#reason_not_interested').val(''); 
    }
}

function copyToStudentsContact(element, phone, email){
    if($(element).attr('data-type') == 0) {

        $('#phone_mobile').val(phone);
        $('#phone_mobile').attr('readonly', true);
        $('#phone_mobile').css('background-color', 'rgb(220, 220, 220)');
        
        $('#Leads0emailAddress0').val(email);
        $('#Leads0emailAddress0').attr('readonly', true);
        $('#Leads0emailAddress0').css('background-color', 'rgb(220, 220, 220)');
        
        $(element).text(SUGAR.language.get('Leads','LBL_COPIED_TO_STUDENT'));
        $(element).attr('data-type', 1);
        $(element).next().css('display', 'inline');

    } else {
        $(element).text(SUGAR.language.get('Leads','LBL_COPY_TO_STUDENT'));
        $(element).attr('data-type', 0);
        $(element).next().css('display', 'none');

        $('#phone_mobile').removeAttr('readonly');
        $('#phone_mobile').css('background-color', '');
        $('#Leads0emailAddress0').removeAttr('readonly');
        $('#Leads0emailAddress0').css('background-color', '');
    }
}