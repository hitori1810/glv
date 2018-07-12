$(document).ready(function(){
    if($('#type').val()=='Gift'){
        $('#DEFAULT > tbody > tr:nth-child(7)').hide();
        $('#DEFAULT > tbody > tr:nth-child(6)').show();
        $('#DEFAULT > tbody > tr:nth-child(8)').hide();
    } 
    if($('#type').val()=='Partnership'){
        $('#DEFAULT > tbody > tr:nth-child(6)').hide();
        $('#DEFAULT > tbody > tr:nth-child(7)').show();
        $('#DEFAULT > tbody > tr:nth-child(8)').hide();
    }
    if($('#type').val()=='Reward'){
        $('#DEFAULT > tbody > tr:nth-child(6)').hide();
        $('#DEFAULT > tbody > tr:nth-child(7)').hide();
        $('#DEFAULT > tbody > tr:nth-child(8)').show();
    }
    if($('#type').val()=='Other'){
        $('#DEFAULT > tbody > tr:nth-child(6)').hide();
        $('#DEFAULT > tbody > tr:nth-child(7)').hide();
        $('#DEFAULT > tbody > tr:nth-child(8)').hide();
    }
});