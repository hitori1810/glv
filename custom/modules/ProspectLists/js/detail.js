var record_id = $('input[name=record]').val();
$( document ).ready(function() {
    $('#export_button').on('click',function() {
        create_SendSurveydiv(record_id,'ProspectLists');
        $('table.zebra').find('tbody').find('.button').hide();
        select_surveys('survey','export_target_list');
    });
});

