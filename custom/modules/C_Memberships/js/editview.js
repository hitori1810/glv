// Overwirite set_return Parent Type
function set_return_stu(popup_reply_data){
    var form_name = popup_reply_data.form_name;  
    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        if (the_key == 'toJSON') {
            continue;
        } else {
            var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
            switch (the_key)
            {
                case 'c_memberships_contacts_2_name':
                    window.document.forms[form_name].elements['c_memberships_contacts_2_name'].value = val;
                    break;
                case 'c_memberships_contacts_2contacts_idb':
                    window.document.forms[form_name].elements['c_memberships_contacts_2contacts_idb'].value = val;
                    break;
                case 'c_memberships_leads_1_name':
                    window.document.forms[form_name].elements['c_memberships_leads_1_name'].value = val;
                    break;
                case 'c_memberships_leads_1leads_idb':
                    window.document.forms[form_name].elements['c_memberships_leads_1leads_idb'].value = val;
                    break;
                case 'phone_mobile':
                    window.document.forms[form_name].elements['phone_mobile'].value = val;
                    break;
                case 'email1':
                    window.document.forms[form_name].elements['email1'].value = val;
                    break;
                case 'birthdate':
                    window.document.forms[form_name].elements['birthdate'].value = val;
                    break;
            }
        }
    }
    fillText(); 
}

function ajax_check_duplicate(){
    var code = $("#name").val();
    if(code != ''){
        //Reset notified icon.
        $('label#valid_code').css('display', 'none');
        $('label#invalid_valid_code').css('display', 'none');

        //Data to send ajax
        var prefix = $("#name").val();
        var id = $('input[name=record]').val();

        //add Loader.gif
        $("input#checkDuplicate").parent().append("<span style='display: inline;' id='loadding'></span>");
        $("#loadding").html('<img src="custom/include/Ledit/loader.gif" align="absmiddle" width="16">');

        //Ajax
        $.ajax({
            url:'index.php?module=C_Memberships&action=ajaxCheckDuplicate&sugar_body_only=true',
            type:'POST',
            data:{'code' : code, 'id': id },
            dataType: "json",
            async : true,
            success: function(res){
                $("#loadding").remove();
                if(res.success == '0'){ //prefix duplicate
                    $('label#invalid_valid_code').css('display', 'inline');
                    removeFromValidate('EditView','checkDuplicate');
                    addToValidate('EditView', 'checkDuplicate', 'varchar', true, '' );
                } else { //prefix not duplicate (prefix available)
                    $('label#valid_code').css('display', 'inline');
                    removeFromValidate('EditView','checkDuplicate');
                }
            }
        })     
    }   
}

function fillText(){
    $('#phone_mobile_text').text($('#phone_mobile').val());
    $('#email1_text').text($('#email1').val());
    $('#birthdate_text').text($('#birthdate').val());
} 
$(function(){
    $('#webcam').photobooth().on("image",function( event, dataUrl ){
        $( "#gallery" ).html( '<img name="yes_image" src="' + dataUrl + '" >');
        $('#btn_take_photo, #btn_delete_photo').show();
    }); 
    $('#webcam').hide();
    $('#btn_take_photo').click(function(){
        $('#webcam').show();
        $( "#gallery" ).hide();
        $('#btn_take_photo, #btn_delete_photo').hide();   
    }); 
    $('#btn_delete_photo').click(function(){
        $("#gallery").html( '<img name="no_image" src="themes/default/images/noimage.jpg">'); 
        $("#no_image").val('1'); 
    });
    $('.trigger').click(function(){
        $('#webcam').hide();
        $("#gallery").show();
        $('#btn_take_photo, #btn_delete_photo').show();
        $('#image').val($('img[name=yes_image]').attr('src'));
        $("#no_image").val('0');    
    });
    $('#photo_config').closest('td').attr('colSpan', '6');

    fillText();
    $('#btn_c_memberships_contacts_2_name').live('click',function(){
        // Open popup Corporate
        open_popup("Contacts", 600, 400, "", true, true, {
            "call_back_function": "set_return_stu",
            "form_name": "EditView",
            "field_to_name_array": {
                "id": "c_memberships_contacts_2contacts_idb",
                "name": "c_memberships_contacts_2_name",
                "phone_mobile": "phone_mobile",
                "email1": "email1",
                "birthdate": "birthdate",
            },
            }, "Select", true);
    });


    //create by leduytan
    $('#btn_c_memberships_leads_1_name').live('click', function(){
        console.log('sadasdsa');
        open_popup("Leads", 600, 400, "", true, true, {
            "call_back_function": "set_return_stu",
            "form_name": "EditView",
            "field_to_name_array": {
                "id": "c_memberships_leads_1leads_idb",
                "name": "c_memberships_leads_1_name",
                "phone_mobile": "phone_mobile",
                "email1": "email1",
                "birthdate": "birthdate",
            },
            }, "Select", true);

    })

    $("#name").blur(ajax_check_duplicate);

});
function toggle_student(){
    if($("#type").val()=="Student"){
        $("#visitor").hide();
        $("#student").show();
        $("#student").effect("highlight", {color: '#ff9933'}, 1500);
        $("#btn_take_photo").show();
        $("#btn_delete_photo").show();
    }else{
        $("#visitor").show();
        $("#visitor").effect("highlight", {color: '#ff9933'}, 1500);
        $("#student").hide();
        $("#btn_take_photo").hide();
        $("#btn_delete_photo").hide();
        
    }
}


$(document).ready(function(){
    //create by leduytan 2/10/2014
    toggle_student();
    $('#type').change(function(){
        toggle_student();
        $("#c_memberships_contacts_2_name, #c_memberships_contacts_2contacts_idb, #c_memberships_leads_1_name, #c_memberships_leads_1leads_idb").val('');
        $("#phone_mobile_text, #email1_text, #birthdate_text").text('');
    });
})