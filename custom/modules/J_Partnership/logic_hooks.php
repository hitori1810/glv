<?php
  
    $hook_version = 1; 
    $hook_array = Array(); 
    $hook_array['before_save'] = Array();  
    $hook_array['before_save'][] = Array(1, 'Save Schema', 'custom/modules/J_Partnership/logicPartnership.php', 'before_save_partnership', 'saveSchema'); 
    $hook_array['after_save'] = Array();  
    $hook_array['after_save'][] = Array(1, 'Add Team', 'custom/modules/J_Partnership/logicPartnership.php', 'before_save_partnership', 'addTeam');   
    $hook_array['process_record'] = Array(); 
    $hook_array['process_record'][] = Array(1, 'Color', 'custom/modules/J_Partnership/logicPartnership.php', 'before_save_partnership', 'listViewColorPartner');
