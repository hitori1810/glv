<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/


class InsideViewLogicHook {

    const urlBase = 'https://my.insideview.com/iv/crm/';

    protected function handleFieldMap($bean, $mapping) {
        $outArray = array();
        foreach ( $mapping as $dest => $src ) {
            // Initialize it to an empty string, so it is always set
            $outArray[$dest] = '';

            if ( is_array($src) ) {
                // The source can be any one of a number of fields
                // we must go deeper.
                foreach ( $src as $src2 ) {
                    if ( isset($bean->$src2) ) {
                        $outArray[$dest] = $bean->$src2;
                        break;
                    }
                }
            } else {
                if ( isset($bean->$src) ) {
                    $outArray[$dest] = $bean->$src;
                }
            }
        }

        $outStr = '';
        foreach ( $outArray as $k => $v ) {
            $outStr .= $k.'='.rawurlencode(html_entity_decode($v,ENT_QUOTES)).'&';
        }
        
        $outStr = rtrim($outStr,'&');
        
        return $outStr;
    }

    protected function getAccountFrameUrl($bean, $extraUrl) {
        $url = self::urlBase.'analyseAccount.do?crm_context=account&';
        $fieldMap = array('crm_account_name'=>'name',
                          'crm_account_id'=>'id',
                          'crm_account_website'=>'website',
                          'crm_account_ticker'=>'ticker_symbol', 
                          'crm_account_city'=>array('primary_address_city', 'secondary_address_city', 'billing_address_city', 'shipping_address_city'), 
                          'crm_account_state'=>array('primary_address_state', 'secondary_address_state', 'billing_address_state', 'shipping_address_state'), 
                          'crm_account_country'=>array('primary_address_country', 'secondary_address_country', 'billing_address_country', 'shipping_address_country'), 
                          'crm_account_postalcode'=>array('primary_address_postalcode', 'secondary_address_postalcode', 'billing_address_postalcode', 'shipping_address_postalcode')
        );
        
        $url .= $this->handleFieldMap($bean,$fieldMap).'&'.$extraUrl;
        
        return $url;

    }

    protected function getOpportunityFrameUrl($bean, $extraUrl) {
        $url = self::urlBase.'analyseAccount.do?crm_context=opportunity&';
        $fieldMap = array('crm_account_name'=>'account_name',
                          'crm_account_id'=>'account_id',
                          'crm_opportunity_id'=>'id',
        );
        
        $url .= $this->handleFieldMap($bean,$fieldMap).'&'.$extraUrl;
        
        return $url;

    }
    protected function getLeadFrameUrl($bean, $extraUrl) {
        $url = self::urlBase.'analyseAccount.do?crm_context=lead&';
        $fieldMap = array('crm_lead_id'=>'id',
                          'crm_lead_firstname'=>'first_name',
                          'crm_lead_lastname'=>'last_name',
                          'crm_lead_title'=>'title',
                          'crm_account_id'=>'id',
                          'crm_account_name'=>'account_name',
                          'crm_account_website'=>'website',
        );
        
        $url .= $this->handleFieldMap($bean,$fieldMap).'&'.$extraUrl;
        
        return $url;

    }
    protected function getContactFrameUrl($bean, $extraUrl) {
        $url = self::urlBase.'analyseExecutive.do?crm_context=contact&';
        $fieldMap = array('crm_object_id'=>'id',
                          'crm_fn'=>'first_name',
                          'crm_ln'=>'last_name',
                          'crm_email'=>'email',
                          'crm_account_id'=>'account_id',
                          'crm_account_name'=>'account_name',
        );
        
        $url .= $this->handleFieldMap($bean,$fieldMap).'&'.$extraUrl;
        
        return $url;

    }


    public function showFrame($event, $args) {
        if ( $GLOBALS['app']->controller->action != 'DetailView' ) {
            return;
        }
        require_once('include/connectors/utils/ConnectorUtils.php');

        $bean = $GLOBALS['app']->controller->bean;

        // Build the base arguments
        static $userFieldMap = array('crm_user_id' => 'id',
                                     'crm_user_fn' => 'first_name',
                                     'crm_user_ln' => 'last_name',
                                     'crm_user_email' => 'email1',
        );


        if ( $GLOBALS['current_user']->id != '1' ) {
            $extraUrl = $this->handleFieldMap($GLOBALS['current_user'],$userFieldMap);
        } else {
            // Need some extra code here for the '1' admin user
            $myUserFieldMap = $userFieldMap;
            unset($myUserFieldMap['crm_user_id']);
            $extraUrl = 'crm_user_id='.urlencode($GLOBALS['sugar_config']['unique_key']).'&'.$this->handleFieldMap($GLOBALS['current_user'],$myUserFieldMap);
        }

        $system_name = (!empty($GLOBALS['system_config']->settings['system_name'])) ? $GLOBALS['system_config']->settings['system_name'] : '';

        $extraUrl .= '&crm_org_id='.urlencode($GLOBALS['sugar_config']['unique_key'])
            .'&crm_org_name='.urlencode($system_name)
            .'&crm_server_url='.urlencode($GLOBALS['sugar_config']['site_url'])
            .'&crm_session_id=&crm_version=v62&crm_deploy_id=3&crm_size=400&is_embed_version=true';
        
        // Use the per-module functions to build the frame
        if ( is_a($bean,'Account') ) {
            $url = $this->getAccountFrameUrl($bean, $extraUrl);
        } else if ( is_a($bean,'Contact') ) {
            $url = $this->getContactFrameUrl($bean, $extraUrl);
        } else if ( is_a($bean,'Lead') ) {
            $url = $this->getLeadFrameUrl($bean, $extraUrl);
        } else if ( is_a($bean, 'Opportunity') ) {
            $url = $this->getOpportunityFrameUrl($bean, $extraUrl);
        } else {
            $url = '';
        }

        if ( $url != '' ) {
            // Check if the user should be shown the frame or not
            $smarty = new Sugar_Smarty();
            $tplName = 'modules/Connectors/connectors/sources/ext/rest/insideview/tpls/InsideView.tpl';
            require_once('include/connectors/utils/ConnectorUtils.php');
            $connector_language = ConnectorUtils::getConnectorStrings('ext_rest_insideview');
            $smarty->assign('connector_language', $connector_language);
            $smarty->assign('logo',getWebPath('modules/Connectors/connectors/sources/ext/rest/insideview/images/insideview.png'));
            $smarty->assign('video',getWebPath('modules/Connectors/connectors/sources/ext/rest/insideview/images/video.png'));

            $smarty->assign('close',getWebPath('modules/Connectors/connectors/sources/ext/rest/insideview/images/close.png'));
            $smarty->assign('logo_expanded',getWebPath('modules/Connectors/connectors/sources/ext/rest/insideview/images/insideview_expanded.png'));
            $smarty->assign('logo_collapsed',getWebPath('modules/Connectors/connectors/sources/ext/rest/insideview/images/insideview_collapsed.png'));

            $smarty->assign('AJAX_URL',$url);
            $smarty->assign('APP', $GLOBALS['app_strings']);

            if ( $GLOBALS['current_user']->getPreference('allowInsideView','Connectors') != 1 )
            {
                $smarty->assign('showInsideView',false);

            }else {
                $smarty->assign('showInsideView',true);
                $smarty->assign('URL',$url);
                //echo "<div id='insideViewDiv' style='width:100%;height:400px;overflow:hidden'><iframe id='insideViewFrame' src='$url' style='border:0px; width:100%;height:480px;overflow:hidden'></iframe></div>";
            }
            echo $smarty->fetch($tplName);
        }
    }
}
