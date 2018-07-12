<?php
    $hook_version = 1;
    $hook_array = Array();
    $hook_array['before_save'] = Array();
    $hook_array['before_save'][] = Array(1, 'set name', 'custom/modules/J_Gradebook/GradebookLogicHooks.php', 'GradebookLogicHooks', 'genagateName');
    $hook_array['before_save'][] = Array(2, 'update before save', 'custom/modules/J_Gradebook/GradebookLogicHooks.php', 'GradebookLogicHooks', 'updateBeforeSave');
    $hook_array['before_delete'] = Array();
    $hook_array['before_delete'][] = Array(1, 'Delete Gradebook', 'custom/modules/J_Gradebook/GradebookLogicHooks.php','GradebookLogicHooks', 'checkBeforeDelete'); 

?>
