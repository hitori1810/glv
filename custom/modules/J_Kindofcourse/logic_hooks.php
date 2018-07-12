<?php
    // Do not store anything in this file that is not part of the array or the hook version.  This file will	
    // be automatically rebuilt in the future. 
    $hook_version = 1; 
    $hook_array = Array(); 
    $hook_array['before_save'] = Array();  
    $hook_array['before_save'][] = Array(1, 'Logic Hooks Kind Of Course', 'custom/modules/J_Kindofcourse/logicKindOfCourse.php', 'logicKindOfCourse', 'handleSave');   
    $hook_array['after_save'] = Array();  
    $hook_array['after_save'][] = Array(1, 'Add Team', 'custom/modules/J_Kindofcourse/logicKindOfCourse.php', 'logicKindOfCourse', 'addTeam');   
    $hook_array['process_record'] = Array(); 
    $hook_array['process_record'][] = Array(1, 'Color', 'custom/modules/J_Kindofcourse/logicKindOfCourse.php', 'logicKindOfCourse', 'listViewColorKOC');