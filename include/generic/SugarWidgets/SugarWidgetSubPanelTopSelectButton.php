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




    require_once('include/generic/SugarWidgets/SugarWidgetSubPanelTopButton.php');

    class SugarWidgetSubPanelTopSelectButton extends SugarWidgetSubPanelTopButton
    {
        //button_properties is a collection of properties associated with the widget_class definition. layoutmanager
        function SugarWidgetSubPanelTopSelectButton($button_properties=array())
        {
            $this->button_properties=$button_properties;
        }

        public function getWidgetId()
        {
            return parent::getWidgetId(false) . 'select_button';
        }

        public function getDisplayName()
        {
            return $GLOBALS['app_strings']['LBL_SELECT_BUTTON_LABEL'];
        }
        //widget_data is the collection of attributes associated with the button in the layout_defs file.
        function display(&$widget_data)
        {
            global $app_strings;

            /**
            * if module is hidden or subpanel for the module is hidden - doesn't show select button
            */
            if ( SugarWidget::isModuleHidden( $widget_data['module'] ) )
            {
                return '';
            }

            $initial_filter = '';

            $this->title     = $this->getTitle();
            $this->accesskey = $this->getAccesskey();
            $this->value     = $this->getDisplayName();

            if (is_array($this->button_properties)) {
                if( isset($this->button_properties['title'])) {
                    $this->title = $app_strings[$this->button_properties['title']];
                }
                if( isset($this->button_properties['accesskey'])) {
                    $this->accesskey = $app_strings[$this->button_properties['accesskey']];
                }
                if( isset($this->button_properties['form_value'])) {
                    $this->value = $app_strings[$this->button_properties['form_value']];
                }
                if( isset($this->button_properties['module'])) {
                    $this->module_name = $this->button_properties['module'];
                }
            }


            $focus = $widget_data['focus'];
            if(ACLController::moduleSupportsACL($widget_data['module']) && !ACLController::checkAccess($widget_data['module'], 'list', true)){
                $button = ' <input type="button" name="' . $this->getWidgetId() . '" id="' . $this->getWidgetId() . '" class="button"' . "\n"
                . ' title="' . $this->title . '"'
                . ' value="' . $this->value . "\"\n"
                .' disabled />';
                return $button;
            }

            //refresh the whole page after end of action?
            $refresh_page = 0;
            if(!empty($widget_data['subpanel_definition']->_instance_properties['refresh_page'])){
                $refresh_page = 1;
            }

            $subpanel_definition = $widget_data['subpanel_definition'];
            $button_definition = $subpanel_definition->get_buttons();

            $subpanel_name = $subpanel_definition->get_name();
            if (empty($this->module_name)) {
                $this->module_name = $subpanel_definition->get_module_name();
            }
            $link_field_name = $subpanel_definition->get_data_source_name(true);
            $popup_mode='Single';
            if(isset($widget_data['mode'])){
                $popup_mode=$widget_data['mode'];
            }
            if(isset($widget_data['initial_filter_fields'])){
                if (is_array($widget_data['initial_filter_fields'])) {
                    foreach ($widget_data['initial_filter_fields'] as $value=>$alias) {
                        if (isset($focus->$value) and !empty($focus->$value)) {
                            $initial_filter.="&".$alias . '='.urlencode($focus->$value);
                        }
                    }
                }
            }
            $create="true";
            if(isset($widget_data['create'])){
                $create=$widget_data['create'];
            }
            $return_module = $_REQUEST['module'];
            $return_action = 'SubPanelViewer';
            $return_id = $_REQUEST['record'];

            //field_to_name_array
            $fton_array= array('id' => 'subpanel_id');
            if(isset($widget_data['field_to_name_array']) && is_array($widget_data['field_to_name_array'])){
                $fton_array=array_merge($fton_array,$widget_data['field_to_name_array']);
            }

            $return_url = "index.php?module=$return_module&action=$return_action&subpanel=$subpanel_name&record=$return_id&sugar_body_only=1";

            $popup_request_data = array(
                'call_back_function' => 'set_return_and_save_background',
                'form_name' => 'DetailView',
                'field_to_name_array' => $fton_array,
                'passthru_data' => array(
                    'child_field' => $subpanel_name,
                    'return_url' => urlencode($return_url),
                    'link_field_name' => $link_field_name,
                    'module_name' => $subpanel_name,
                    'refresh_page'=>$refresh_page,
                ),
            );

            // bugfix #57850 add marketing_id to the request data to allow filtering based on it
            if (!empty($_REQUEST['mkt_id']))
            {
                $popup_request_data['passthru_data']['marketing_id'] = $_REQUEST['mkt_id'];
            }

            if (is_array($this->button_properties) && !empty($this->button_properties['add_to_passthru_data']))
            {
                $popup_request_data['passthru_data']= array_merge($popup_request_data['passthru_data'],$this->button_properties['add_to_passthru_data']);
            }

            if (is_array($this->button_properties) && !empty($this->button_properties['add_to_passthru_data']['return_type'])) {

                if ($this->button_properties['add_to_passthru_data']['return_type']=='report') {
                    $initial_filter = "&module_name=". urlencode($widget_data['module']);
                }
            }
            //acl_roles_users_selectuser_button

            $json_encoded_php_array = $this->_create_json_encoded_popup_request($popup_request_data);
            //Custom Popup By -  Lap Nguyen
            $name_id = $this->getWidgetId();
            if($this->widget_id == 'contracts_contacts' && $_REQUEST['module'] == 'Contracts'){
                $onlick = '';
                $this->title     = translate('LBL_SELECT_CLASS');
                $this->value     = translate('LBL_SELECT_CLASS');
                $name_id         = "btn_sel_atc";
                $class = 'button primary';
                $bt_ip = '<input style="margin-left: 7px;" type="button" class="button" id="import_student" value="Import From Excel" onclick="location.href=\'index.php?module=Contracts&action=saveSession&sugar_body_only=true&contract_id='.$this->parent_bean->id.'\'"/>
                <input type="button" name="contracts_contacts_select_button" id="contracts_contacts_select_button" class="button" title="Select Student" value="Select Student" onclick="open_popup(&quot;Contacts&quot;,600,400,&quot;&type_advanced=Corporate&quot;,true,true,{&quot;call_back_function&quot;:&quot;set_return_and_save_background&quot;,&quot;form_name&quot;:&quot;DetailView&quot;,&quot;field_to_name_array&quot;:{&quot;id&quot;:&quot;subpanel_id&quot;},&quot;passthru_data&quot;:{&quot;child_field&quot;:&quot;contacts&quot;,&quot;return_url&quot;:&quot;index.php%3Fmodule%3DContracts%26action%3DSubPanelViewer%26subpanel%3Dcontacts%26record%3D2af28bc6-ec24-d2d1-3be2-5562a02ab582%26sugar_body_only%3D1&quot;,&quot;link_field_name&quot;:&quot;contacts&quot;,&quot;module_name&quot;:&quot;contacts&quot;,&quot;refresh_page&quot;:0}},&quot;MultiSelect&quot;,true);">
                ';
            }else{
                $onlick = "open_popup(\"$this->module_name\",600,400,\"$initial_filter\",true,true,$json_encoded_php_array,\"$popup_mode\",$create);";
                $class = 'button';
                $bt_ip = '';
            }
            return $bt_ip.' <input type="button" name="' . $name_id . '" id="' . $name_id . '" class="'.$class.'"' . "\n"
            . ' title="' . $this->title . '"'
            . ' value="' . $this->value . "\"\n"
            . " onclick='$onlick' />\n";
            //END Custom
        }

        /**
        * @return string
        */
        protected function getTitle()
        {
            return translate('LBL_SELECT_BUTTON_TITLE');
        }

        /**
        * @return string
        */
        protected function getAccesskey()
        {
            return translate('LBL_SELECT_BUTTON_KEY');
        }

    }
?>
