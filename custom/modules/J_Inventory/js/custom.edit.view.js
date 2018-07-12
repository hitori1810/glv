$(document).ready(function(){
    $('#from_object_id').select2();
    //$('#from_inventory_list').select2();
    //$('#to_inventory_list').select2();
    //$('select#status').select2({minimumResultsForSearch: Infinity,width: '100px'});
    $('select.list_book:visible').select2({width: '190px',placeholder: "Select a book"}) 
    var $inven_type = $('#type').val(); 

    $('#example').multifield({
        section     : '.tr_template',
        addTo       : '#tbodyPT',
        btnAdd      : '#btnAdd',
        btnRemove   : '.btnRemove',
        max:15,
        min: 1, 
    } );

    getToObjectOptions($('#to_inventory_list').val());

    $("#to_inventory_list").live('change',function(){
        getToObjectOptions($('#to_inventory_list').val());
    }) ; 
    $("#from_inventory_list").live('change',function(){
        var _this = $(this);
        getFromObjectOptions($('#from_inventory_list').val());
        if(_this.val() == 'TeamsParent') {
            $("#to_inventory_list option[value!=Teams]").hide();
            $('#to_inventory_list').val('Teams');
        } else {
            $("#to_inventory_list option[value!=Teams]").show();
        }
        $('#to_inventory_list').trigger('change');
    }) ;
    //check form
    //Remove event save
    $('#SAVE_HEADER, #SAVE_FOOTER').removeAttr('onclick');
    $('#SAVE_HEADER, #SAVE_FOOTER').click(function(){
        var _form = document.getElementById('EditView');
        _form.action.value='Save'; 
        if(validate()&& check_form('EditView'))
            SUGAR.ajaxUI.submitForm(_form);
        return false;
    });     

    //fill partno, unit, price  from database
    $(".list_book").live("change", function(){
        var _thisrow = $(this).closest('tr');
        var element = $(this).find('option:selected'); 
        var part_no = element.attr("part_no");
        var unit = element.attr("unit");
        var price = Number(element.attr("price"));
        _thisrow.find('.part_no').text(part_no);
        _thisrow.find('.unit').text(unit);
        _thisrow.find('.price').val(formatNumber(price, num_grp_sep, dec_sep));
        _thisrow.find(".quantity").trigger('keyup');          
    });

    //enter quantity update amount    
    $(".quantity").live('keyup', function() {
        var p = Number(unformatNumber($(this).parent().parent().find(".price").val(), num_grp_sep, dec_sep));             
        var q = Number($(this).val());           
        var kq = q * p ;
        $(this).parent().parent().find(".amount").val(formatNumber(kq, num_grp_sep, dec_sep));  
        sum();                         
    });    

    //set input inventory detail readonly
    $('#total_quantity').addClass("input_readonly");
    $('#total_amount').addClass("input_readonly");       
    //-------case: live change of from list----------------
    $('#from_object_id').live('change', function(){
        if($('#TeamsParent').val() != 'Import')
            getToObjectOptions($('#to_inventory_list').val());
        $("#to_object_id option").prop('disabled', false);
        var choose_team = $(this).val();    
        $("#to_object_id option[value='"+ choose_team + "']").attr('disabled', true ); 
    });

    //-------case: live change of to list----------------
    $('#to_object_id').live('change', function(){       
        $("#from_object_id option").prop('disabled', false);
        var choose_team= $(this).val();    
        $("#from_object_id option[value='"+ choose_team + "']").attr('disabled', true ); 
    }); 
    $('#from_object_id, #to_object_id').trigger('change');
});  
function getToObjectOptions($object) { 
    $('#to_inventory_list').after('<img id = "loading_img" class = "loading_image" src="themes/default/images/loading.gif" width = "19px" align="absmiddle">');     
    ajaxStatus.showStatus('Processing...');
    $.ajax({
        type: "POST",
        async:false,
        url: "index.php?module=J_Inventory&action=ajaxInventory&sugar_body_only=true",
        data: {
            type: "getToObjectOptions",
            object: $object, 
            center_id: $('#from_object_id').val(), 
            to_object_key: $('#to_object_key').val(), 
            from_object: $('#from_inventory_list').val(), 
            record: $('input[name=record]').val(),
        },
        success:function(data){             
            $('select#to_object_id').html(data);
            if($object == 'Teams' && $('#from_inventory_list').val() == 'Teams') {
                var choose_team = $('#from_object_id').val();    
                $("#to_object_id option[value='"+ choose_team + "']").attr('disabled', true );
                if ($("#to_object_id").val() == choose_team) {
                    $("#to_object_id").val('');
                }
                $('#to_object_id').trigger('change');
            }
            ajaxStatus.hideStatus();
            $('select#to_object_id').select2({width:'200px'});
            $('#loading_img').remove();
        }
    });

}  
function getFromObjectOptions($object) { 
    $('#from_inventory_list').after('<img id = "loading_img" class = "loading_image" src="themes/default/images/loading.gif" width = "19px" align="absmiddle">');     
    ajaxStatus.showStatus('Processing...');
    $.ajax({
        type: "POST",
        async:false,
        url: "index.php?module=J_Inventory&action=ajaxInventory&sugar_body_only=true",
        data: {
            type: "getFromObjectOptions",
            object: $object, 
            //center_id: $('#from_object_id').val(), 
            //from_object_key: $('#to_object_key').val(), 
            record: $('input[name=record]').val(),
        },
        success:function(data){             
            $('select#from_object_id').html(data);
            ajaxStatus.hideStatus();
            $('select#from_object_id').select2({width:'200px'});
            $('#loading_img').remove();
        }
    });

}  

function sum(){
    var total_amount = 0;
    $(".amount:visible").each(function() {
        total_amount += Number(unformatNumber($(this).val(), num_grp_sep, dec_sep));
    });
    $("#total_amount").val(formatNumber(total_amount, num_grp_sep, dec_sep)); 
    var total_quantity = 0;
    $(".quantity:visible").each(function() {
        total_quantity += Number($(this).val());
    });
    $("#total_quantity").val(total_quantity);
}    

function validate(){
    var result=true;
    $('.tr_template:visible').each(function() {         
        var quantity = $(this).find('.quantity').val();
        var price = $(this).find('input[name*=price]')[0].value;

        if(quantity == 0 || quantity == "" || price == 0 || price == ""){
            $(this).find('.quantity').effect("highlight", {color: '#E81D25'}, 1000);
            $(this).find('.price').effect("highlight", {color: '#E81D25'}, 1000);
            result=false;
        }
    });
    return result; 
}  

function handleAddRow() {   
    $('.tr_template:visible').last().find('select.list_book').select2({width: '190px',placeholder: "Select a book"});
    $('.tr_template:visible').last().find('.quantity').val(1);
    //$('.tr_template:visible').last().css('display','flex'); 
}

function handleRemoveRow(){
    sum(); 
}