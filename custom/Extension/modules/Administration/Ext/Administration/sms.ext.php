<?php 
			
$admin_option_defs = array();

$admin_option_defs['Administration']['config1'] = array(
				'icon_AdminMobile',
				'Gateway Settings',
				"Configures a provider's gateway details",
				'./index.php?module=Administration&action=smsConfig'
		);   
				
$admin_group_header[]= array(
				'Short Message Service (SMS)',
				'',
				false,
				$admin_option_defs, 
				'A module that integrates short messaging service.'
		);
		
?>