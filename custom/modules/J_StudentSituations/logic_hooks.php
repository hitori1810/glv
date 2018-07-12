<?php
    // Do not store anything in this file that is not part of the array or the hook version.  This file will	
    // be automatically rebuilt in the future. 
    $hook_version = 1; 
    $hook_array = Array(); 
    $hook_array['process_record'] = Array(); 
    $hook_array['process_record'][] = Array(1, 'Add button on subpanel', 'custom/modules/J_StudentSituations/logicStudentSituations.php','logicStudentSituations', 'processRecording'); 
    $hook_array['before_save'] = Array();
    $hook_array['before_save'][] = Array(1, 'Handle Save Situation', 'custom/modules/J_StudentSituations/logicStudentSituations.php','logicStudentSituations', 'beforeSave');  
    
    $hook_array['before_delete'] = Array();
    $hook_array['before_delete'][] = Array(1, 'Handle Delete Situation', 'custom/modules/J_StudentSituations/logicStudentSituations.php','logicStudentSituations', 'beforeDelete');