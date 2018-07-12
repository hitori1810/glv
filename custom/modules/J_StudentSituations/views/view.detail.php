<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class J_StudentSituationsViewDetail extends ViewDetail {

    function J_StudentSituationsViewDetail(){
        parent::ViewDetail();
    }
    function display() {
        global $current_user;
        // Dirty trick to clear cache, a must for EditView:
        //		$th = new TemplateHandler();
        //		$th->deleteTemplate('J_StudentSituations', 'DetailView');

        if($this->bean->type == 'Moving Out'){
            //			unset($this->dv->defs['panels']['LBL_DEFAUT']);
            $bt_undo = '';
            if($current_user->isAdmin())
                $bt_undo ='<input type="button" class="primary button" name="btn_undo" id="btn_undo" value="Undo" />';
            $this->ss->assign('bt_undo',$bt_undo);

        }
        $relate_a = '';
        if(!empty($this->bean->relate_situation_id))
            $relate_a = '<a href="index.php?module=J_StudentSituations&action=DetailView&record='.$this->bean->relate_situation_id.'"><span id="relate_situation_id" class="sugar_field">>>Link</span></a>';
        $this->ss->assign('relate_a',$relate_a);

        if($current_user->isAdmin())
            $this->ss->assign('is_admin',true);   

        //Show button export form
        $exportTypes = array("Moving Out","Moving In");
        $btnExportForm = "";
        if(in_array($this->bean->type,$exportTypes)){
            $btnExportForm .= '<input class="button" type="button" value="'.$GLOBALS['mod_strings']['BTN_EXPORT_FORM'].'" id="btn_export_form" onclick="location.href = \'index.php?module=J_StudentSituations&action=exportform&record='. $this->bean->id .'\'">';    
        }
        $this->ss->assign('EXPORT_FROM_BUTTON',$btnExportForm); 

        parent::display();
    }
    function _displaySubPanels(){
        require_once ('include/SubPanel/SubPanelTiles.php');
        $subpanel = new SubPanelTiles($this->bean, $this->module);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_studentsituations_c_sms']);
        echo $subpanel->display();   
    }
}
?>