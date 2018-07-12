<?php
    // Do not store anything in this file that is not part of the array or the hook version.  This file will
    // be automatically rebuilt in the future.
    $hook_version = 1;
    $hook_array = Array();
    $hook_array['before_save']      = Array();
    $hook_array['before_save'][]    = Array(1, 'Create name Loyalty', 'custom/modules/J_Loyalty/logicLoyalty.php','logicLoyalty', 'beforeSaveLogic');
    $hook_array['before_save'][]    = Array(2, 'Create Code', 'custom/modules/J_Loyalty/logicLoyalty.php','logicLoyalty', 'addCode');

    $hook_array['process_record']   = Array();
    $hook_array['process_record'][] = Array(1, 'Color', 'custom/modules/J_Loyalty/logicLoyalty.php','logicLoyalty', 'listViewColor');


