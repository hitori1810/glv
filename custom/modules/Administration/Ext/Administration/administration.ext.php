<?php 
 //WARNING: The contents of this file are auto-generated

 
            

      






          
//include_once 'custom/modules/Configurator/asteriskConfig.php';
//$response = checklicence();
//
//if($response=="active")
//{
//    $redirect_action = "configurator&status=success";
//
//}
//else
//{
//    $redirect_action = "asteriskUserInfo&status=1";
//}
//
//$admin_options_defs=array();
//$admin_options_defs['Administration']['AsteriskConfiguration']=array(       
//    'ASTERISKPANELSETTINGS',
//    'LBL_ASTERISK_CONFIGURATION_TITLE',
//    'LBL_CONFIGURATION_DESC',
//    './index.php?module=Configurator&action='.$redirect_action.''
//);
//
//$admin_options_defs['Administration']['AsteriskActivation']=array(       
//    'ASTERISKPANELACTIVATION',
//    'LBL_ACTIVATION_TITLE',
//    'LBL_ACTIVATION_DESC',
//    './index.php?module=Configurator&action=asteriskUserInfo'
//);        
//
//$admin_options_defs['Administration']['AddonStatus']=array(       
//    'ADDONSTATUS',
//    'LBL_TECHEXTENSION_ADDON_STATUS',
//    'LBL_TECHEXTENSION_ADDON_STATUS_DESC',
//    './index.php?module=Configurator&action=restartaddon'
//);      
//
//$admin_options_defs['Administration']['SupportGuide']=array(       
//    'ADDONSUPPORTGUIDE',
//    'LBL_TECHEXTENSION_ADDON_SUPPORTGUIDE',
//    'LBL_TECHEXTENSION_ADDON_SUPPORTGUIDE_DESC',
//    'http://www.techextension.com/how-to-install-sugar-crm-asterisk-integration-sugarcrm-modules'
//);
//
//$admin_group_header[]=array(
//    'LBL_ASTERISKGROUP_TITLE',
//    'LBL_ASTERISKGROUP_DESC',
//    false,
//    $admin_options_defs,
//);      



  // Add by Tung Bui - 07/09/2017
  $admin_option_defs = array();
  $admin_option_defs['Administration']['special_config']= array('Administration','LBL_SPECIAL_CONFIG','LBL_SPECIAL_CONFIG_DESC','./index.php?module=Administration&action=generalconfig');

  $admin_group_header[]= array('LBL_GENERAL_CONFIG','',false,$admin_option_defs, 'LBL_GENERAL_CONFIG_DESC');
   
  //-------END------------//


 
			
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
		


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
//if (!defined('sugarEntry') || !sugarEntry)
//    die('Not A Valid Entry Point');
//
//$admin_option_defs = array();
//
//$admin_option_defs['Administration']['license_configuration_link'] = array(
//    '',
//    'LBL_SURVEY_LICENSE_CONFIGURATION',
//    'LBL_SURVEY_LICENSE_CONFIGURATION_DESC',
//    'index.php?module=bc_survey&action=license'
//);
//
//$admin_option_defs['Administration']['healthstatus'] = array(
//    '',
//     'LBL_HEALTH_CHECK',
//    'LBL_HEALTH_CHECK_DESC',
//    'index.php?module=bc_survey&action=healthstatus'
//);
//
//$admin_option_defs['Administration']['bc_survey_automizer'] = array(
//    '',
//     'LBL_SURVEY_AUTOMIZER',
//    'LBL_AUTOMIZER_DESC',
//    'index.php?module=bc_survey_automizer&action=ListView'
//);
//$admin_option_defs['Administration']['survey_smtp'] = array(
//    '',
//     'LBL_SURVEY_SMTP_SETTING',
//    'LBL_SURVEY_SMTP_SETTING_DESC',
//    'index.php?module=Administration&action=surveysmtp'
//);
//$admin_group_header[] = array(
//    'LBL_SURVEY_CONF_TITLE',
//    '',
//    false,
//    $admin_option_defs,
//    'LBL_LICENSE_CONFIGURATION_TITLE'
//);

?>