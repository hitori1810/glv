<?php
    //Add logic hook - 16/07/2014 - by MTN
    $hook_version = 1; 
    $hook_array = Array(); 
    $hook_array['before_save'] = Array(); 
    $hook_array['before_save'][] = Array(1, 'Add Code', 'custom/modules/C_Teachers/before_save.php','before_save_teacher', 'addCode');  
    $hook_array['before_save'][] = Array(2, 'Make Full Name', 'custom/modules/C_Teachers/before_save.php','before_save_teacher', 'make_full_name');  
     
      $hook_array['process_record'] = Array(); 
    $hook_array['process_record'][] = Array(1, 'Color', 'custom/modules/C_Teachers/listview_teacher.php','ListviewTeacher', 'ListviewTeacher'); 

    ?>