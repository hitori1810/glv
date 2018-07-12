<?php
    // Do not store anything in this file that is not part of the array or the hook version.  This file will	
    // be automatically rebuilt in the future. 
    $hook_version = 1; 
    $hook_array = Array();


    $hook_array['before_save'] = Array();  
    $hook_array['before_save'][] = Array(2, 'Logic Hooks Class', 'custom/modules/J_Class/logicClass.php', 'logicClass', 'handleSave');   
    $hook_array['before_save'][] = Array(1, 'Class Code', 'custom/modules/J_Class/logicClass.php', 'logicClass', 'addClassCode');   

    $hook_array['before_delete'] = Array();
    $hook_array['before_delete'][] = Array(1, 'Check Bofore Class', 'custom/modules/J_Class/logicClass.php','logicClass', 'checkBeforeDelete'); 

    $hook_array['after_save'] = Array();
    $hook_array['after_save'][] = Array(1, 'Handle Save Test', 'custom/modules/J_Class/logicClass.php','logicClass', 'handleAfterSave'); 

    $hook_array['process_record'] = Array(); 
    $hook_array['process_record'][] = Array(1, 'Color', 'custom/modules/J_Class/logicClass.php','logicClass', 'listViewColorClass');


