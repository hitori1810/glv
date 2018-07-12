<?php
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


require_once('include/MVC/View/views/view.list.php');

class UsersViewList extends ViewList
{
    public function preDisplay()
    {
        
        /**
        * Echo js for handle set_return_and_save_user
        * 
        * @author   Thuan Nguyen
        */
        $this->setReturnAndSaveUserJS();
        $this->openTemplatePopup();
        //END: Echo js for handle set_return_and_save_user
        
        //bug #46690: Developer Access to Users/Teams/Roles
        if (!$GLOBALS['current_user']->isAdminForModule('Users') && !$GLOBALS['current_user']->isDeveloperForModule('Users'))
        {
            //instead of just dying here with unauthorized access will send the user back to his/her settings
            SugarApplication::redirect('index.php?module=Users&action=DetailView&record=' . $GLOBALS['current_user']->id);
        }
        $this->lv = new ListViewSmarty();
        /**
        * Copy user template reference
        * 
        * @author   Thuan Nguyen
        */
        $this->lv->actionsMenuExtraItems[]  = $this->buildApplyUserTemplateLink();  
        // END: Copy user template reference
        $this->lv->delete = false;
        $this->lv->email = false;
    }

 	public function listViewProcess()
 	{
 		$this->processSearchForm();
		$this->lv->searchColumns = $this->searchForm->searchColumns;

		if(!$this->headers)
			return;
		if(empty($_REQUEST['search_form_only']) || $_REQUEST['search_form_only'] == false){
			$this->lv->ss->assign("SEARCH",true);
			if(!empty($this->where)){
					$this->where .= " AND";
			}
                        $this->where .= " (users.status !='Reserved' or users.status is null) ";
			$this->lv->setup($this->seed, 'include/ListView/ListViewGeneric.tpl', $this->where, $this->params);
			$savedSearchName = empty($_REQUEST['saved_search_select_name']) ? '' : (' - ' . $_REQUEST['saved_search_select_name']);
			echo $this->lv->display();
		}
 	}
    
    /**
    * Build copy user template reference link
    * 
    * @author   Thuan Nguyen
    * @return   string HTML
    */
    function buildApplyUserTemplateLink($loc = 'top') {
        global $app_strings, $mod_strings;
        unset($_REQUEST[session_name()]);
        unset($_REQUEST['PHPSESSID']);
        $this->seed = $this->bean;
        $current_query_by_page = base64_encode(serialize($_REQUEST));

        $js = <<<EOF
            if(sugarListView.get_checks_count() < 1) {
                alert('{$app_strings['LBL_LISTVIEW_NO_SELECTED']}');
                return false;
            }
            if ( document.forms['targetlist_form'] ) {
                var form = document.forms['users_form'];
                form.reset;
            } else
                var form = document.createElement ( 'form' ) ;
            form.setAttribute ( 'name' , 'users_form' );
            form.setAttribute ( 'method' , 'post' ) ;
            form.setAttribute ( 'action' , 'index.php' );
            document.body.appendChild ( form ) ;
            if ( !form.module ) {
                var input = document.createElement('input');
                input.setAttribute ( 'name' , 'module' );
                input.setAttribute ( 'value' , '{$this->seed->module_dir}' );
                input.setAttribute ( 'type' , 'hidden' );
                form.appendChild ( input ) ;
                var input = document.createElement('input');
                input.setAttribute ( 'name' , 'action' );
                input.setAttribute ( 'value' , 'ApplyUserTemplate' );
                input.setAttribute ( 'type' , 'hidden' );
                form.appendChild ( input ) ;
            }
            if ( !form.uids ) {
                var input = document.createElement('input');
                input.setAttribute ( 'name' , 'uids' );
                input.setAttribute ( 'type' , 'hidden' );
                form.appendChild ( input ) ;
            }
            if ( !form.user_template ) {
                var input = document.createElement('input');
                input.setAttribute ( 'name' , 'user_template' );
                input.setAttribute ( 'type' , 'hidden' );
                form.appendChild ( input ) ;
            }
            if ( !form.return_module ) {
                var input = document.createElement('input');
                input.setAttribute ( 'name' , 'return_module' );
                input.setAttribute ( 'type' , 'hidden' );
                form.appendChild ( input ) ;
            }
            if ( !form.return_action ) {
                var input = document.createElement('input');
                input.setAttribute ( 'name' , 'return_action' );
                input.setAttribute ( 'type' , 'hidden' );
                form.appendChild ( input ) ;
            }
            if ( !form.select_entire_list ) {
                var input = document.createElement('input');
                input.setAttribute ( 'name' , 'select_entire_list' );
                input.setAttribute ( 'value', document.MassUpdate.select_entire_list.value);
                input.setAttribute ( 'type' , 'hidden' );
                form.appendChild ( input ) ;
            }
            if ( !form.current_query_by_page ) {
                var input = document.createElement('input');
                input.setAttribute ( 'name' , 'current_query_by_page' );
                input.setAttribute ( 'value', '{$current_query_by_page}' );
                input.setAttribute ( 'type' , 'hidden' );
                form.appendChild ( input ) ;
            }
            open_template_popup('Users','600','400','',true,false,{ 'call_back_function':'set_return_and_save_users','form_name':'users_form','field_to_name_array':{'id':'user_template'} } );
EOF;
        $js = str_replace(array("\r","\n"),'',$js);
        return "<a href='javascript:void(0)' id=\"targetlist_listview_". $loc ." \" onclick=\"$js\">{$mod_strings['LBL_APPLY_USER_TEMPLATE_BUTTON_LABEL']}</a>";
    }
    //END: Build copy user template reference link
    
    /**
    * Build JS for handle save and return
    * 
    * @author   Thuan Nguyen
    */
    function setReturnAndSaveUserJS() {
        $js = <<<MOF
        <script>
            function set_return_and_save_users(popup_reply_data) {
                var form_name = popup_reply_data.form_name;
                var name_to_value_array = popup_reply_data.name_to_value_array;
                var form_index = document.forms.length - 1;
                sugarListView.get_checks();
                var uids = document.MassUpdate.uid.value;
                if (uids == '') {
                    return false;
                }
                for (var the_key in name_to_value_array) {
                    if (the_key == 'toJSON') {} else {
                        for (i = form_index; i >= 0; i--) {
                            if (form_name == window.document.forms[form_index]) {
                                form_index = i;
                                break;
                            }
                        }
                        window.document.forms[form_index].elements[get_element_index(form_index, the_key)].value = name_to_value_array[the_key];
                        SUGAR.util.callOnChangeListers(window.document.forms[form_index].elements[get_element_index(form_index, the_key)]);
                    }
                }
                window.document.forms[form_index].elements[get_element_index(form_index, "return_module")].value = window.document.forms[form_index].elements[get_element_index(form_index, "module")].value;
                window.document.forms[form_index].elements[get_element_index(form_index, "return_action")].value = 'ListView';
                window.document.forms[form_index].elements[get_element_index(form_index, "uids")].value = uids;
                window.document.forms[form_index].submit();
            }
        </script>
MOF;
        echo $js;
    }
    
    /**
    * Build JS for open template popup
    * 
    * @author   Thuan Nguyen
    */
    function openTemplatePopup() {
        $js = <<<MOF
        <script>
            function open_template_popup(module_name, width, height, initial_filter, close_popup, hide_clear_button, popup_request_data, popup_mode, create, metadata) {
                if (typeof(popupCount) == "undefined" || popupCount == 0)
                 popupCount = 1;
                window.document.popup_request_data = popup_request_data;
                window.document.close_popup = close_popup;
                width = (width == 600) ? 800 : width;
                height = (height == 400) ? 800 : height;
                URL = 'index.php?' + 'module=' + module_name + '&action=TemplatePopup';
                if (initial_filter != '') {
                 URL += '&query=true' + initial_filter;
                }
                if (hide_clear_button) {
                 URL += '&hide_clear_button=true';
                }
                windowName = module_name + '_popup_window' + popupCount;
                popupCount++;
                windowFeatures = 'width=' + width + ',height=' + height + ',resizable=1,scrollbars=1';
                if (popup_mode == '' && popup_mode == 'undefined') {
                 popup_mode = 'single';
                }
                URL += '&mode=' + popup_mode;
                if (create == '' && create == 'undefined') {
                 create = 'false';
                }
                URL += '&create=' + create;
                if (metadata != '' && metadata != 'undefined') {
                 URL += '&metadata=' + metadata;
                }
                if (popup_request_data.jsonObject) {
                 var request_data = popup_request_data.jsonObject;
                } else {
                 var request_data = popup_request_data;
                }
                var field_to_name_array_url = '';
                if (request_data && request_data.field_to_name_array != 'undefined') {
                 for (var key in request_data.field_to_name_array) {
                     if (key.toLowerCase() != 'id') {
                         field_to_name_array_url += '&field_to_name[]=' + encodeURIComponent(key.toLowerCase());
                     }
                 }
                }
                if (field_to_name_array_url) {
                 URL += field_to_name_array_url;
                }
                win = window.open(URL, windowName, windowFeatures);
                if (window.focus) {
                 win.focus();
                }
                win.popupCount = popupCount;
                return win;
                }
        </script>
MOF;
        echo $js;
    }
}
