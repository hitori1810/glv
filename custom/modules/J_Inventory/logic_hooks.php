<?php
    // Do not store anything in this file that is not part of the array or the hook version.  This file will	
    // be automatically rebuilt in the future. 
    $hook_version = 1; 
    $hook_array = Array(); 
    // position, file, function 
    $hook_array['before_save'] = Array(); 
    $hook_array['before_save'][] = Array(101, 'Add Auto-Increment Code', 'custom/modules/J_Inventory/hookInventory.php','hookInventory', 'autoCode'); 
    $hook_array['before_save'][] = Array(1, 'Save Detail Inventory', 'custom/modules/J_Inventory/hookInventory.php','hookInventory', 'handleBeforeSave');
    
    $hook_array['after_save'] = Array(); 
    $hook_array['after_save'][] = Array(1, 'Save Detail Inventory', 'custom/modules/J_Inventory/hookInventory.php','hookInventory', 'handleAfterSave');
     
    $hook_array['before_delete'] = Array(); 
    $hook_array['before_delete'][] = Array(1, 'Delete Inventory', 'custom/modules/J_Inventory/hookInventory.php','hookInventory', 'handleBeforeDelete'); 

    $hook_array['process_record'] = Array(); 
    $hook_array['process_record'][] = Array(1, 'Color', 'custom/modules/J_Inventory/hookInventory.php','hookInventory', 'listViewColorInven');
    $hook_array['process_record'][] = Array(2, 'get detail', 'custom/modules/J_Inventory/hookInventory.php','hookInventory', 'showDetail');

?>