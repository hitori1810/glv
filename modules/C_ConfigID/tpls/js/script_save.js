$(document).ready(function(){
    //Validate
    addToValidate('configinfo', 'code_field', 'enum', true,'Field');
    addToValidate('configinfo', 'name', 'varchar', true,'Name');
    addToValidate('configinfo', 'custom_field', 'varchar', true,'Custom Field Name');
    addToValidate('configinfo', 'custom_format', 'varchar', true,'Custom Date Format');
    addToValidate('configinfo', 'zero_padding', 'enum', true,'Padding');
    addToValidate('configinfo', 'first_num', 'int', true,'First Number');
    addToValidate('configinfo', 'date_format', 'enum', true,'Reset by period of format require date format ');

    $("#save_config").click(function(){
        if(check_form('configinfo')){
            ajax($(this).attr('action'),$(this).attr('record_id'));
            $(".entry-form").fadeOut("fast");  
        }
    });

    $("#add_new").click(function(){
        $(".entry-form").fadeIn("fast");
        $('#save_config').attr('action','save');    
        $('#save_config').attr('record_id','');
        $('#zero_padding,#custom_format,#date_format,#code_separator,#name,#custom_field,#code_field,#module_name').val('');
        $('#date_format').trigger('change');	
    });

    $("#close").click(function(){
        $(".entry-form").fadeOut("fast");	
    });

    $("#cancel").click(function(){
        $(".entry-form").fadeOut("fast");	
    });

    toggle_reset_each_year();
    $("#is_reset").live('change',toggle_reset_each_year);

    $(".del_bt").live("click",function(){
        if(confirm("Do you really want to delete this record ?")){
            ajax("delete",$(this).closest('tr').find('input[name=record_id]').val());
        }
    });
    $(".edit_bt").live("click",function(){
        var json = jQuery.parseJSON($(this).closest('tr').find('input[name=json_en]').val());
        $('#save_config').attr('action','update');    
        $('#save_config').attr('record_id',json.id);
        $('select[name=module_name]').val(json.module_name);
        ajax_get_module_fields(json.module_name, json.code_field);
        $('input[name=name]').val(json.prefix);
        $('input[name=code_separator]').val(json.code_separator);
        if(json.is_reset == '1')
            $('#is_reset').prop('checked',true);  
        else
            $('#is_reset').prop('checked',false);

        $('select[name=date_format]').val(json.date_format);
        if($('select[name=date_format]').val() == '' && json.date_format != ''){
            $('select[name=date_format]').val('custom').trigger('change');      
            $('#custom_format').val(json.date_format);  
        }


        $('select[name=zero_padding]').val(json.zero_padding);
        $('input[name=first_num]').val(json.first_num);
        $(".entry-form").fadeIn("fast");

    });
    //Handle change option Date Format
    toggle_date_format();
    $('select[name=date_format]').live('change',toggle_date_format);
    //Handle change option Fields
    toggle_field();
    $('select[name=code_field]').live('change',toggle_field);
    //Handle change option Padding
    toggle_padding();
    $('select[name=zero_padding]').live('change',toggle_padding);
    //Handle change module name
    $('select[name=module_name]').live('change',function(){
        ajax_get_module_fields($(this).val(),'');
    });
});

function ajax(action,id)    {
    if(action =="save" || action =="update")
        data = $("#configinfo").serialize()+"&act="+action+"&id="+id;
    else if(action == "delete"){
        data = "act="+action+"&id="+id;
    }
    $.ajax({
        url:'index.php?module=C_ConfigID&action=save_config&sugar_body_only=true',
        type: "POST",
        data : data,
        dataType: "json",
        success: function(res){
            if(res.success == "1"){
                var row_id = res.record_id;
                if(action == "save"){
                    $(".entry-form").fadeOut("fast",function(){

                        var date_format = $('select[name=date_format]').find('option[value="'+res.date_format+'"]').text();
                        if(date_format == '')
                            date_format = res.date_format;
                        var is_reset = '';
                        (res.is_reset == '1') ? is_reset = '<input checked disabled type="checkbox">' : is_reset = '<input disabled type="checkbox">'; 

                        $("#table-list").append("<tr><td><input type='hidden' name='json_en' value='"+res.json_en+"'><input type='hidden' name='record_id' value='"+res.record_id+"'>"+res.name+"</td><td>"+res.code_separator+"</td><td>"+res.code_field+"</td><td>"+res.module_name+"</td><td>"+date_format+"</td><td>"+is_reset+"</td><td>"+res.zero_padding+"</td><td>"+res.first_num+"</td><td style='min-width:120px;'><a href='javascript:void(0)' style='text-decoration: none;' class='edit_bt'><i class='icon icon-edit'></i> Edit</a>&nbsp&nbsp<a href='javascript:void(0)' style='text-decoration: none;' class='del_bt'><i class='icon icon-trash'></i> Delete</a> </td></tr>");    
                        $("#table-list tr:last").effect("highlight", {              
                            color: '#4BADF5'
                            }, 1000);
                    });          
                }else if(action == "delete"){
                    $("input[value='"+row_id+"']").closest("tr").effect("highlight", {
                        color: '#4BADF5'
                        }, 1000);
                    $("input[value='"+row_id+"']").closest("tr").fadeOut();
                }else if(action == 'update'){
                    $("input[value='"+row_id+"']").closest("tr").remove(); 
                    var date_format = $('select[name=date_format]').find('option[value="'+res.date_format+'"]').text();
                    if(date_format == '')
                        date_format = res.date_format;
                    var is_reset = '';
                    (res.is_reset == '1') ? is_reset = '<input checked disabled type="checkbox">' : is_reset = '<input disabled type="checkbox">'; 

                    $("#table-list").append("<tr><td><input type='hidden' name='json_en' value='"+res.json_en+"'><input type='hidden' name='record_id' value='"+res.record_id+"'>"+res.name+"</td><td>"+res.code_separator+"</td><td>"+res.code_field+"</td><td>"+res.module_name+"</td><td>"+date_format+"</td><td>"+is_reset+"</td><td>"+res.zero_padding+"</td><td>"+res.first_num+"</td><td style='min-width:120px;'><a href='javascript:void(0)' style='text-decoration: none;' class='edit_bt'><i class='icon icon-edit'></i> Edit</a>&nbsp&nbsp<a href='javascript:void(0)' style='text-decoration: none;' class='del_bt'><i class='icon icon-trash'></i> Delete</a> </td></tr>");    

                }
            }
        },
        error: function(res){
            alert("Unexpected error! Try again.");
        }
    });
}
function toggle_date_format(){
    if($('select[name=date_format]').val() == 'custom'){
        $('input[name=custom_format]').show();
        addToValidate('configinfo', 'custom_format', 'varchar', true,'Custom Date Format');
    }else{
        $('input[name=custom_format]').val($('select[name=date_format]').val()).hide();
        removeFromValidate('configinfo','custom_format'); 
    }
}
function toggle_padding(){
    var num = parseInt($('select[name=zero_padding]').val());
    var padding = '';
    for($i = 1; $i <= num; $i++){
        padding += '0';
    }
    $('input[name=first_num]').val(padding);
}
function toggle_reset_each_year(){
    if($("#is_reset").is(':checked')){
//        $("select[name=date_format]").val('y');
        addToValidate('configinfo', 'date_format', 'enum', true,'Reset each year require date format ');
    }else{
        removeFromValidate('configinfo','date_format');
    }
    toggle_date_format();
}
function ajax_get_module_fields(module_name,selected){
    $.ajax({
        url: "index.php?module=C_ConfigID&action=ajax_get_options&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            module_name: module_name,
        },
        dataType: "json", 
        success: function(res){
            if (res.success == '1') {
                $('select[name=code_field]').closest('div').html(res.html);
            }
            if(selected != '')
                $('select[name=code_field]').val(selected);    
        },       
    }); 
}
function toggle_field(){
    if($('select[name=code_field]').val() == 'new_field'){
        $('input[name=custom_field]').show();
        addToValidate('configinfo', 'custom_field', 'varchar', true,'Custom Field Name');
    }else{
        $('input[name=custom_field]').hide();
        removeFromValidate('configinfo','custom_field'); 
    }  
}