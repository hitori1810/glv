<?php
    $hook_version = 1; 
    $hook_array = Array(); 
    // position, file, function                          
    $hook_array['before_save'] = Array();  
    $hook_array['before_save'][] = Array(1, 'save schema', 'custom/modules/J_Discount/logicDiscount.php', 'logicDiscount', 'handleSaveDiscount');
    $hook_array['after_save'] = Array();  
    $hook_array['after_save'][] = Array(1, 'Add Team', 'custom/modules/J_Discount/logicDiscount.php', 'logicDiscount', 'addTeam');   
    $hook_array['process_record'] = Array(); 
    $hook_array['process_record'][] = Array(1, 'Color', 'custom/modules/J_Discount/logicDiscount.php','logicDiscount', 'listViewColorDiscount');
    
