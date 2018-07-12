<?php
    // Do not store anything in this file that is not part of the array or the hook version.  This file will
    // be automatically rebuilt in the future.
    $hook_version = 1;
    $hook_array = Array();
    // position, file, function
    $hook_array['before_save'] = Array();
    $hook_array['before_save'][] = Array(1, 'workflow', 'include/workflow/WorkFlowHandler.php','WorkFlowHandler', 'WorkFlowHandler');
    $hook_array['before_save'][] = Array(2, 'Update Working Date', 'custom/modules/Meetings/setLeadStatus.php','leadMeetingStatus', 'setLeadStatus');
    $hook_array['before_save'][] = Array(3, 'Update Teacher ID, Room ID when Edit', 'custom/modules/Meetings/handleSaveMeeting.php', 'handleSaveMeetings', 'handleSaveMeetings');
    $hook_array['before_save'][] = Array(4, 'Add Auto-Increment Code', 'custom/modules/Meetings/handleSaveMeeting.php','handleSaveMeetings', 'addCode');
    $hook_array['before_save'][] = Array(5, 'Save Testing', 'custom/modules/Meetings/handleSaveMeeting.php', 'handleSaveMeetings', 'handleSavePT');

    $hook_array['before_delete'] = Array();
    $hook_array['before_delete'][] = Array(1, 'Delete PT Demo', 'custom/modules/Meetings/handleSaveMeeting.php','handleSaveMeetings', 'beforeDeleteSchedule');

    $hook_array['process_record'] = Array();
    $hook_array['process_record'][] = Array(1, 'Add Color for Session Status', 'custom/modules/Meetings/listview_color.php','ListviewLogicHookMeetings', 'listviewcolor_Meetings');

?>