$(document).ready(function(){
     //add schema discount
    $('#tblLevelConfig').multifield({
        section :   '.row_tpl', // First element is template
        addTo   :   '#tbodylLevelConfig', // Append new section to position 
        btnAdd  :   '#btnAddrow', // Button Add id
        btnRemove:  '.btnRemove', // Buton remove id,
        /* fnCloneSection: function(){
        alert(1);
        }*/
    });
});

//function set_return of open popup 
function set_return_schema(popup_reply_data, filter) {
    var form_name = popup_reply_data.form_name;

    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');

        switch (the_key)
        {
            case 'discount_name':
                currentDiscount.parent().find(".discount_name").val(val);      
                break;
            case 'discount_id':
                currentDiscount.parent().find(".discount_id").val(val);      
                break;
        }
    }
}

// Show popup search discount
function clickChooseDiscount(thisButton){
    currentDiscount =  thisButton;
    open_popup('J_Discount', 600, 400, "", true, false, {"call_back_function":"set_return_schema","form_name":"EditView","field_to_name_array":{"id":"discount_id","name":"discount_name"}}, "single", true);
};

function clickClearDiscount(thisButton){
    thisButton.parent().find(".discount_name").val('');
    thisButton.parent().find(".discount_id").val('');
}