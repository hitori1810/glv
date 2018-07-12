SUGAR.util.doWhen(function() { return $("#formDetailView").length > 0; },
    function() { 
        $('#whole_subpanel_j_inventory_j_inventorydetail_1').appendTo('#detail_inventory');
});
$(document).ready(function(){
    $('.pagination').remove();
    $("td").css("text-align", "left");
    $('#export').live("click",function(){
        window.open("index.php?module=J_Inventory&action=exportInventory&record="+$('input[name="record"]').val(),'_blank');       
    });
});