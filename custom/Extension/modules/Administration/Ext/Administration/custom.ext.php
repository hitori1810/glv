<?php
  // Add by Tung Bui - 07/09/2017
  $admin_option_defs = array();
  $admin_option_defs['Administration']['special_config']= array('Administration','LBL_SPECIAL_CONFIG','LBL_SPECIAL_CONFIG_DESC','./index.php?module=Administration&action=generalconfig');

  $admin_group_header[]= array('LBL_GENERAL_CONFIG','',false,$admin_option_defs, 'LBL_GENERAL_CONFIG_DESC');
   
  //-------END------------//
?>
