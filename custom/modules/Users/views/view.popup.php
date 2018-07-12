<?php 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

require_once('include/MVC/View/views/view.popup.php'); 

class UsersViewPopup extends ViewPopup{ 
    function UsersViewPopup() 
    { 
        parent::ViewPopup();
    }

    public function display()  {
        global $popupMeta, $mod_strings;

        if(($this->bean instanceOf SugarBean) && !$this->bean->ACLAccess('list')){
            ACLController::displayNoAccess();
            sugar_cleanup(true);
        }

        if(isset($_REQUEST['metadata']) && strpos($_REQUEST['metadata'], "..") !== false) {
            ACLController::displayNoAccess();
            sugar_cleanup(true);
        }

        $popupMeta = SugarAutoLoader::loadPopupMeta($this->module, isset($_REQUEST['metadata'])?$_REQUEST['metadata']:null);
        //
        if(!$GLOBALS['current_user']->isAdmin()) { 
            $sql = "
                SELECT distinct t2.user_id
                FROM team_memberships t2
                INNER JOIN 
                (SELECT team_memberships.team_id
                FROM users 
                INNER JOIN team_memberships ON users.id = team_memberships.user_id AND team_memberships.deleted = 0
                INNER JOIN teams ON teams.id = team_memberships.team_id AND teams.deleted = 0
                WHERE users.id = '". $GLOBALS['current_user']->id . "' AND users.deleted = 0) temp ON t2.team_id = temp.team_id AND t2.deleted = 0

            ";
            $userID = array();
            $result = $GLOBALS['db']->query($sql);

            while( $row = $GLOBALS['db']->fetchByAssoc($result)) {
                $userID[] = $row['user_id'];                   
            }

            $popupMeta['whereStatement'] .= " AND users.id IN ('". implode("', '",$userID) ."')";
        }
        //end
        $defs = $this->loadWithPopup('listviewdefs');
        if(is_array($defs)) {
            $listViewDefs[$this->module] = $defs;
        } elseif(!empty($defs)) {
            require $defs;
        }

        $defs = $this->loadWithPopup('searchdefs');
        if(is_array($defs)) {
            $searchdefs[$this->module]['layout']['advanced_search'] = $defs;
        } elseif(!empty($defs)) {
            require $defs;
        }

        //if you click the pagination button, it will populate the search criteria here
        if(!empty($this->bean) && isset($_REQUEST[$this->module.'2_'.strtoupper($this->bean->object_name).'_offset'])) {
            if(!empty($_REQUEST['current_query_by_page'])) {
                $blockVariables = array('mass', 'uid', 'massupdate', 'delete', 'merge', 'selectCount',
                    'sortOrder', 'orderBy', 'request_data', 'current_query_by_page');
                $current_query_by_page = unserialize(base64_decode($_REQUEST['current_query_by_page']));
                foreach($current_query_by_page as $search_key=>$search_value) {
                    if($search_key != $this->module.'2_'.strtoupper($this->bean->object_name).'_offset'
                    && !in_array($search_key, $blockVariables)) {
                        if (!is_array($search_value)) {
                            $_REQUEST[$search_key] = $GLOBALS['db']->quote($search_value);
                        }
                        else {
                            foreach ($search_value as $key=>&$val) {
                                $val = $GLOBALS['db']->quote($val);
                            }
                            $_REQUEST[$search_key] = $search_value;
                        }
                    }
                }
            }
        }

        if(!empty($listViewDefs) && !empty($searchdefs)){
            require_once('include/Popups/PopupSmarty.php');
            $displayColumns = array();
            $filter_fields = array();
            $popup = new PopupSmarty($this->bean, $this->module);
            $this->bean->ACLFilterFieldList($listViewDefs[$this->module], array("owner_override" => true));
            foreach($listViewDefs[$this->module] as $col => $params) {
                $filter_fields[strtolower($col)] = true;
                if(!empty($params['related_fields'])) {
                    foreach($params['related_fields'] as $field) {
                        //id column is added by query construction function. This addition creates duplicates
                        //and causes issues in oracle. #10165
                        if ($field != 'id') {
                            $filter_fields[$field] = true;
                        }
                    }
                }
                if(!empty($params['default']) && $params['default'])
                    $displayColumns[$col] = $params;
            }
            $popup->displayColumns = $displayColumns;
            $popup->filter_fields = $filter_fields;
            $popup->mergeDisplayColumns = true;
            //check to see if popupdefs contains searchdefs
            $popup->_popupMeta = $popupMeta;
            $popup->listviewdefs = $listViewDefs;
            $popup->searchdefs = $searchdefs;

            if(isset($_REQUEST['query'])){
                $popup->searchForm->populateFromRequest();
            }

            $massUpdateData = '';
            if(isset($_REQUEST['mass'])) {
                foreach(array_unique($_REQUEST['mass']) as $record) {
                    $massUpdateData .= "<input style='display: none' checked type='checkbox' name='mass[]' value='$record'>\n";
                }
            }
            $popup->massUpdateData = $massUpdateData;

            $tpl = 'include/Popups/tpls/PopupGeneric.tpl';
            if(file_exists($this->getCustomFilePathIfExists("modules/{$this->module}/tpls/popupGeneric.tpl")))
            {
                $tpl = $this->getCustomFilePathIfExists("modules/{$this->module}/tpls/popupGeneric.tpl");
            }

            if(file_exists($this->getCustomFilePathIfExists("modules/{$this->module}/tpls/popupHeader.tpl")))
            {
                $popup->headerTpl = $this->getCustomFilePathIfExists("modules/{$this->module}/tpls/popupHeader.tpl");
            }

            if(file_exists($this->getCustomFilePathIfExists("modules/{$this->module}/tpls/popupFooter.tpl")))
            {
                $popup->footerTpl = $this->getCustomFilePathIfExists("modules/{$this->module}/tpls/popupFooter.tpl");
            }

            $popup->setup($tpl);

            //We should at this point show the header and javascript even if to_pdf is true.
            //The insert_popup_header javascript is incomplete and shouldn't be relied on.
            if (isset($this->options['show_all']) && $this->options['show_all'] == false)
            {
                unset($this->options['show_all']);
                $this->options['show_javascript'] = true;
                $this->options['show_header'] = true;
                $this->_displayJavascript();
            }
            insert_popup_header(null, false);
            if(isset($this->override_popup['template_data']) && is_array($this->override_popup['template_data']))
            {
                $popup->th->ss->assign($this->override_popup['template_data']);
            }
            echo $popup->display();

        }else{
            if(SugarAutoLoader::existing('modules/' . $this->module . '/Popup_picker.php')){
                require_once('modules/' . $this->module . '/Popup_picker.php');
            }else{
                require_once('include/Popups/Popup_picker.php');
            }

            $popup = new Popup_Picker();
            $popup->_hide_clear_button = true;
            echo $popup->process_page();
        }
    }
}
?>
