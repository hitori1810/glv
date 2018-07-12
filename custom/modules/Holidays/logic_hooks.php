<?php
    // Do not store anything in this file that is not part of the array or the hook version.  This file will
    // be automatically rebuilt in the future.
    $hook_version = 1;
    $hook_array = Array();
    $hook_array['after_relationship_delete'] = Array();
    $hook_array['after_relationship_delete'][] = Array(1, 'Remove Teacher Leaving', 'custom/modules/Holidays/logicHolidays.php','logicHolidays', 'handleRemoveRelationship');


