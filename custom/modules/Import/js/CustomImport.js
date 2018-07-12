/*
* Author: Tung Bui - 09/01/2017
* 
* Config & auto check the default mapping
*/
$( document ).ready(function() { 
    var id_input = $("#hd_default_mapping_record").val();    
    if(id_input != ""){
        $("input[value='custom:"+ id_input +"']").prop("checked", true)
    }   

});
