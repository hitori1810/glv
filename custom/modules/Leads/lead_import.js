$(document).ready(function(){

});
function ajax_save_to_crm(){
    data = $("#form-contact").serialize();
    $.ajax({
        url:'http://trungtamanhngu.giaiphapcrm.vn/index.php?entryPoint=lead_import_portal',
        type: "POST",
        data : data,
        dataType: "json",
        success: function(response){
            if(response.success == "1"){
               alert('Success !!'); 
            }else{
              alert('Fail !!');  
            }
        },
    });
}