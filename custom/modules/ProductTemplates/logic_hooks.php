<?php
   $hook_version = 1; 
    $hook_array = Array(); 
    $hook_array['before_save'] = Array();  
    $hook_array['before_save'][] = Array(1, 'Save Supplier', 'custom/modules/ProductTemplates/logicProductTemplates.php', 'before_save_supplier', 'saveSupplier'); 
    
    $hook_array['process_record'] = Array(); 
$hook_array['process_record'][] = Array(1, 'Color','custom/modules/ProductTemplates/logicProductTemplates.php', 'before_save_supplier', 'listViewColorBook');