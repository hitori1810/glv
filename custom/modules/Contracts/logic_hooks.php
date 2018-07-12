<?php
  //  //Add logic $hook_version = 1;
  $hook_array = Array();
  $hook_array['before_save']      = Array();
  $hook_array['before_save'][]    = Array(1, 'Add Code', 'custom/modules/Contracts/logicContract.php','logicContract', 'addCode');
  $hook_array['before_save'][]    = Array(2, 'Calculate Paid Amount', 'custom/modules/Contracts/logicContract.php','logicContract', 'beforeSaveContract');
  
  $hook_array['after_save']       = Array();
  $hook_array['after_save'][]     = Array(1, 'Create Payment Detail', 'custom/modules/Contracts/logicContract.php','logicContract', 'afterSaveContract');
  
  $hook_array['before_delete'] = Array();
  $hook_array['before_delete'][] = Array(2, 'Delete Contract', 'custom/modules/Contracts/logicContract.php','logicContract', 'deletedContract');
  
?>