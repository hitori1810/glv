var currentStudent = '';
var PARENT_DATA = [];
$(document).ready(function(){

    // select 2
    $('#gender').select2({minimumResultsForSearch: Infinity,width: '150px'});
    $('#graduated_rate').select2({minimumResultsForSearch: Infinity,width: '150px'});
    $('#prefer_level').select2({width: '250px'});

    //Show Hide nationality
    $('input:radio[name="radio_national"]').click(function(){
        if($(this).val()== "Việt Nam"){
            $('#nationality').val("Việt Nam");
            $('#nationality').hide();
        } else {
            $('#nationality').show();
            $('#nationality').val("");
        }
    })
    //edit by Lam Hai
    $('#myLink').pGenerator({
        'bind': 'click',
        'passwordElement': '#portal_password',
        'displayElement': '#portal_password',
        'passwordLength': 8,
        'uppercase': true,
        'lowercase': true,
        'numbers':   false,
        'specialChars': false,
        'onPasswordGenerated': function(generatedPassword) {
            $('#portal_password').val(generatedPassword);
            $('#password_generated').val(generatedPassword);
            $('#password_generated_label1').text(generatedPassword);

            /*alertify.confirm("Do you want to send new account via email ? <br><br>"+$('#Contacts0emailAddress0').val()+'<br><br><br>', function (e) {
            if (e) {
            ajaxSendMailStudent();
            }
            }); */
        }
    });
    $('#portal_password').attr('readonly', true);
    $('#portal_name').attr('readonly', true);
    //$('#portal_password1').attr('readonly', true);

    if($('#portal_password').val() == '' && $('#portal_active').is(':checked'))
    //    $('#myLink').trigger('click');
    $('#password_generated_label1').text('crm123456');
    //end edit

    //choose company
    $('#choose_company').live('click', function(){
        open_popup("Accounts", 600, 400, "", true, false, {"call_back_function":"set_return","form_name":"EditView","field_to_name_array":{"id":"company_id","name":"company_name","phone_office":"phone_work"}}, "single", true);
    });

    //choose parent
    $('#btn_c_contacts_contacts_1_name').removeAttr('onclick');
    $('#btn_c_contacts_contacts_1_name').live('click', function(){
        open_popup("C_Contacts", 600, 400, "", true, false, {"call_back_function":"set_return_contact","form_name":"EditView","field_to_name_array":{"id":"c_contacts_contacts_1c_contacts_ida","name":"c_contacts_contacts_1_name","email1":"Contacts0emailAddress0","address":"primary_address_street","mobile_phone":"phone_mobile"}}, "single", true);
    });
    //auto Complete by Trung Nguyen 2015.01.11
    $.get('index.php?module=Contacts&action=handelAjaxsContact&sugar_body_only=true',
        {
            type  : 'getParentData',
            team_id : $('#id_EditView_team_name_collection_0').val(),
        },
        function (parentdata) {
            PARENT_DATA = jQuery.parseJSON(parentdata);
            $.each(PARENT_DATA, function (i, v) {
                v.metasearch = v.name + ' ' + v.phone;
            });

            $('#guardian_name_autocomplete').textext({
                plugins: 'autocomplete',
                autocomplete: {
                    dropdownMaxHeight : '300px',
                    render: function (suggestion) {
                        var node = $.grep(PARENT_DATA, function (v, i) {
                            return v.metasearch == suggestion
                        })[0];
                        return '<strong>Full Name: </strong> ' + node.name + '<br/>' +
                        '<strong>Phone: </strong> ' + node.phone + '<br/>' +
                        '<strong>Address: </strong> ' + node.address + '<br/>' ;
                    }
                }
            }).bind('getSuggestions', function (e, data) {
                var list = [];
                $.each(PARENT_DATA, function (i, v) {
                    if (data == undefined || data.query.length < 1)
                        list.push(v.metasearch);
                    else if (v.metasearch.toLowerCase().indexOf(data.query.toLowerCase()) > -1) {
                        list.push(v.metasearch);
                    }
                });
                $(this).trigger(
                    'setSuggestions',
                    { result : list }
                );
            }).bind('setInputData', function (e, value) {
                if (value.length > 0) {
                    var node = $.grep(PARENT_DATA, function (v, i) {
                        return v.metasearch == value
                    });
                    if (node != undefined && node.length == 1) {
                        $('#guardian_name_autocomplete').val(node[0].name);
                        $('#c_contacts_contacts_1c_contacts_ida').val(node[0].id);
                        $('#c_contacts_contacts_1_name').val(node[0].name);
                        $('input[name=guardian_name]').val(node[0].name);
                        $('#phone_mobile').val(node[0].phone);
                        $('#primary_address_street').val(node[0].address);
                        $('#Contacts0emailAddress0').val(node[0].email1);
                    }
                    $('#guardian_name_autocomplete').trigger('getSuggestions',[{query:$('#guardian_name_autocomplete').val()}]);
                    $('.text-dropdown').hide();
                }
            }).on('mousedown', function () {
                $('#guardian_name_autocomplete').trigger('toggleDropdown');
            }).on('keyup', function () {
                $('#guardian_name_autocomplete').trigger('toggleDropdown');
            }).on('change',function() {
                if($(this).val() == "") {
                    $('#c_contacts_contacts_1c_contacts_ida').val('');
                    $('#c_contacts_contacts_1_name').val('');
                    $('input[name=guardian_name]').val('');
                    $('#phone_mobile').val('');
                    $('#primary_address_street').val('');
                } else {
                    $('input[name=guardian_name]').val($(this).val());
                }
            });
            $('#guardian_name_autocomplete').val(default_parent.guardian_name);
            $('#c_contacts_contacts_1c_contacts_ida').val(default_parent.c_contacts_contacts_1c_contacts_ida);
            $('#c_contacts_contacts_1_name').val(default_parent.c_contacts_contacts_1_name);
            $('input[name=guardian_name]').val(default_parent.guardian_name);
            $('#phone_mobile').val(default_parent.phone_mobile);
            $('#primary_address_street').val(default_parent.primary_address_street);       
            $('#Contacts0emailAddress0').val(default_parent.email1);
            $('#guardian_name_autocomplete').trigger('getSuggestions',[{query:default_parent.guardian_name}]);
            $('.text-dropdown').hide();
            $('#guardian_name_autocomplete').attr('autocomplete','off');
        }
    );
    $('#guardian_name_autocomplete').attr('placeholder','Nhập tên hoặc số điện thoại để tìm kiếm')
    //$('input[name=guardian_name]').val($('#guardian_name').val());
    //end

    //############### Custom Code for Adult ###################

    var team_type = $('#team_type').val();
    if(team_type == 'Adult'){
        removeFromValidate('EditView', 'guardian_name');
        removeFromValidate('EditView', 'j_school_contacts_1_name');
        $('#guardian_name_label').find('span.required').hide();
        $('#j_school_contacts_1_name_label').find('span.required').hide();
    }

    $('#copy_parent_1').click(function(){
        copyToStudentsContact(this, $('#other_mobile').val(), $('#email_parent_1').val());
    });
    $('#copy_parent_2').click(function(){
        copyToStudentsContact(this, $('#phone_other').val(), $('#email_parent_2').val());
    });
});

function toggleActive(){
    if($('#portal_active').is(':checked')){
        //$('#portal_name').val($('#contact_id').val().toLowerCase());
        //$('#myLink').trigger('click');
        //$('#portal_name').trigger('change');
    }
}

function ajaxSendMailStudent(){
    ajaxStatus.showStatus('Sending Message...');
    var record = $("input[name='record']").val();
    var username = $("#portal_name").val();
    var password = $("#password_generated").val();
    var email = $('#Contacts0emailAddress0').val();
    if(record != ''){
        $.ajax({
            url: "index.php?module=Contacts&action=send_email&sugar_body_only=true",
            type: "POST",
            async: true,
            data:
            {
                record: record,
                username: username,
                password: password,
                email: email,
            },
            dataType: "json",
            success: function(data){
                if(data.success == "1"){
                    alertify.success("Email sent successfully.");
                    $('#SAVE_HEADER').trigger('click');
                }
                else{
                    alertify.error("Something was wrong. Please try again! ");
                    ajaxStatus.hideStatus();
                }

            }
        });
    }else{
        alertify.error("Something was wrong. Please try again! ");
        ajaxStatus.hideStatus();
    }
}

//function set_return of open popup
function set_return_student(popup_reply_data, filter) {
    var form_name = popup_reply_data.form_name;

    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');

        switch (the_key)
        {
            case 'student_name':
                currentStudent.closest('td').find(".student_name").val(val).trigger('change');
                break;
            case 'student_id':
                currentStudent.closest('td').find(".student_id").val(val);
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
            case 'c_contacts_contacts_1_name':
                $("#c_contacts_contacts_1_name").val(val);
                break;
            case 'c_contacts_contacts_1c_contacts_ida':
                $("#c_contacts_contacts_1c_contacts_ida").val(val);
                break;
            case 'phone_mobile':
                $("#phone_mobile").val(val);
                break;
            case 'Contacts0emailAddress0':
                $("#Contacts0emailAddress0").val(val);
                break;
            case 'primary_address_street':
                $("#primary_address_street").val(val);
                break;
        }
    }
}

//Show popup search student
function clickChooseStudent(thisButton){
    currentStudent =  thisButton;
    var module = currentStudent.closest('tr').find('select#select').val();

    open_popup(module, 600, 400, "", true, false, {"call_back_function":"set_return_student","form_name":"EditView","field_to_name_array":{"id":"student_id","name":"student_name"}}, "single", true);
};

//Clear student
function clickClearStudent(thisButton){
    thisButton.closest('td').find(".student_name").val('');
    thisButton.closest('td').find(".student_id").val('');
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

    json.rela_name  = row.find('.student_name').val();
    json.rela_id    = row.find('.student_id').val();
    var json_str = JSON.stringify(json);
    //Assign json
    row.find("input.jsons").val(json_str);
}

function copyToStudentsContact(element, phone, email){
    $('#phone_mobile').val(phone);    
    $('#Contacts0emailAddress0').val(email);
    alertify.success(SUGAR.language.get('Contacts', 'LBL_COPY_SUCCESS'));
}
