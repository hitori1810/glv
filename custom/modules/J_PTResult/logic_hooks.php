<?php
    // Do not store anything in this file that is not part of the array or the hook version.  This file will
    // be automatically rebuilt in the future.
    $hook_version = 1;
    $hook_array = Array();
    // position, file, function
    $hook_array['process_record'] = Array();
    $hook_array['process_record'][] = Array(1, 'Color Status', 'custom/modules/J_PTResult/listviewButton.php','ListviewLogicHookPT', 'listViewButton');

    $hook_array['before_save'] = Array();
    $hook_array['before_save'][] = Array(2, 'update Info', 'custom/modules/J_PTResult/PTResultLogicHook.php','PTResultLogicHook', 'updateStudentInfo');
   ?>