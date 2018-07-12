<?php
    $hook_version = 1; 
    $hook_array = Array(); 
    // position, file, function 
    $hook_array['after_save'] = Array();  
    $hook_array['after_save'][] = Array(1, 'Add Team', 'custom/modules/Reports/logicReport.php', 'logicReport', 'addTeam');   

