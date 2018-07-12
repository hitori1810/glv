<?php
    $hook_version = 1;
    $hook_array = Array();
    $hook_array['before_delete'] = Array();
    $hook_array['before_delete'][] = Array(1, 'Delete', 'custom/modules/C_DeliveryRevenue/logicRevenue.php','logicRevenue', 'deletedRevenue');

    $hook_array['before_save'] = Array();
    $hook_array['before_save'][] = Array(1, 'Logic Hooks Revenue', 'custom/modules/C_DeliveryRevenue/logicRevenue.php', 'logicRevenue', 'handleSaveRevenue');
?>