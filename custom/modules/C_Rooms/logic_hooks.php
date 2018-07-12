<?php
    //Add logic hook - 17/07/2014 - by MTN
    $hook_version = 1; 
    $hook_array = Array(); 
    $hook_array['before_save'] = Array(); 
    $hook_array['before_save'][] = Array(1, 'Add Code', 'custom/include/_helper/AddCode.php','Code', 'addCode');  
?>