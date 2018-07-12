$('#Default_Meetings_Subpanel tbody tr td:nth-child(4) select#status').change(function () {
    if($(this).val() =='Not Held'){
        $('#LBL_OPTION_DATE_MEETING_MODULE').parent('#detailpanel_2').show();   
    }else{
        $('#LBL_OPTION_DATE_MEETING_MODULE').parent('#detailpanel_2').hide();  
    }   
});
$( document ).ready(function() {
    $status = $('#Default_Meetings_Subpanel tbody tr td:nth-child(4) select#status').val();          
    if($status!='Not Held'){
        $('#LBL_OPTION_DATE_MEETING_MODULE').parent('#detailpanel_2').hide();    
    }
});