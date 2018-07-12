<?php
    $hook_version = 1;
    $hook_array = Array();
    // position, file, function

    $hook_array['process_record'] = Array();
    $hook_array['process_record'][] = Array(1, 'Add button export', 'custom/modules/J_Invoice/logicInvoice.php', 'logicInvoice', 'displayButton');

//    $hook_array['before_save'] = Array();
//    $hook_array['before_save'][] = Array(2, 'Handle before save', 'custom/modules/J_Invoice/logicInvoice.php','logicInvoice', 'handleBeforeSave');
//

    $hook_array['before_delete'] = Array();
    $hook_array['before_delete'][] = Array(2, 'Delete Invoice', 'custom/modules/J_Invoice/logicInvoice.php', 'logicInvoice', 'deletedInvoice');

    ?>
