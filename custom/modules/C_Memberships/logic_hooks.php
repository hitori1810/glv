<?php
    $hook_version = 1;
    $hook_array = Array();

    $hook_array['before_save'] = Array();
    $hook_array['before_save'][] = Array(1, 'create Card', 'custom/modules/C_Memberships/logicMembership.php','logicMembership', 'handleBeforeSave');

    $hook_array['process_record'] = Array();
    $hook_array['process_record'][] = Array(1, 'Color Status', 'custom/modules/C_Memberships/logicMembership.php','logicMembership', 'listViewColor');

?>