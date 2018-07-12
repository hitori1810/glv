<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class MeetingsViewDetail extends ViewDetail {

    function MeetingsViewDetail(){
        parent::ViewDetail();

    }
    function _displaySubPanels(){
        require_once ('include/SubPanel/SubPanelTiles.php');
        $subpanel = new SubPanelTiles($this->bean, $this->module);

        //Sub-Panel buttons hiding code - 01/08/2014 - by MTN
        if($this->bean->meeting_type == 'Session'){
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['users']);
            //////////////////////--------Hide subpanel Placement Test. by QuyenCao
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['sub_pt_result']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['sub_demo_result']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['meetings_contacts']);
        }elseif($this->bean->meeting_type == 'Placement Test'){
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['users']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['history']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['sub_demo_result']);
        }elseif($this->bean->meeting_type == 'Demo'){
            //unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['users']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['history']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['sub_pt_result']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']);
        }elseif($this->bean->meeting_type == 'Meeting'){
            //////////////////////--------Hide subpanel Placement Test. by QuyenCao
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['sub_pt_result']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['sub_demo_result']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['users']);

        }
        else{
            //////////////////////--------Hide subpanel Placement Test. by QuyenCao
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['sub_pt_result']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['sub_demo_result']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['users']);

        }
        echo $subpanel->display();
    }
    function display() {
        global $app_strings;
        // Dirty trick to clear cache, a must for EditView:
        $th = new TemplateHandler();
        $th->deleteTemplate('Meetings', 'DetailView');

        if(isset($this->bean->id)){
            global $timedate;
            if($this->bean->meeting_type == 'Session'){
                unset($this->dv->defs['panels']['LBL_DEMO']);
                unset($this->dv->defs['panels']['LBL_PT']);
                unset($this->dv->defs['panels']['LBL_MEETING_INFORMATION']);
                unset($this->dv->defs['panels']['LBL_OTHER']);
                //Display class
                $class_code = $GLOBALS['db']->getOne("SELECT class_code FROM j_class WHERE id = '{$this->bean->ju_class_id}'");
                $class = '<a href="index.php?module=J_Class&return_module=J_Class&action=DetailView&record='.$this->bean->ju_class_id.'" TARGET=_blank>'.$class_code.'</a>';
                $this->ss->assign('class',$class);
            }elseif($this->bean->meeting_type == 'Placement Test'){
                unset($this->dv->defs['panels']['LBL_MEETING_INFORMATION']);
                unset($this->dv->defs['panels']['LBL_DEMO']);
                unset($this->dv->defs['panels']['LBL_SESSION']);
                unset($this->dv->defs['panels']['LBL_OTHER']);
                $edit = "<input title='Edit' accesskey='i' class='button primary' onclick=\"var _form = document.getElementById('formDetailView'); _form.return_module.value='Meetings'; _form.return_action.value='DetailView'; _form.return_id.value='{$this->bean->id}'; _form.action.value='EditView';SUGAR.ajaxUI.submitForm(_form);\" type='button' name='Edit' id='edit_button' value='Edit'>";
                $this->ss->assign('EDIT', $edit);

                $delete = "<input title='Delete' accesskey='d' class='button' onclick=\"var _form = document.getElementById('formDetailView'); _form.return_module.value='Meetings'; _form.return_action.value='ListView'; _form.action.value='Delete'; if(confirm('Are you sure you want to delete this record?')) SUGAR.ajaxUI.submitForm(_form);\" type='submit' name='Delete' value='Delete' id='delete_button'>";
                $this->ss->assign('DELETE', $delete);
            }
            elseif($this->bean->meeting_type == 'Demo'){
                unset($this->dv->defs['panels']['LBL_MEETING_INFORMATION']);
                unset($this->dv->defs['panels']['LBL_PT']);
                unset($this->dv->defs['panels']['LBL_SESSION']);
                unset($this->dv->defs['panels']['LBL_OTHER']);
                $edit = "<input title='Edit' accesskey='i' class='button primary' onclick=\"var _form = document.getElementById('formDetailView'); _form.return_module.value='Meetings'; _form.return_action.value='DetailView'; _form.return_id.value='{$this->bean->id}'; _form.action.value='EditView';SUGAR.ajaxUI.submitForm(_form);\" type='button' name='Edit' id='edit_button' value='{$app_strings['LBL_EDIT_BUTTON']}'>";
                $this->ss->assign('EDIT', $edit);

                $delete = "<input title='Delete' accesskey='d' class='button' onclick=\"var _form = document.getElementById('formDetailView'); _form.return_module.value='Meetings'; _form.return_action.value='ListView'; _form.action.value='Delete'; if(confirm('Are you sure you want to delete this record?')) SUGAR.ajaxUI.submitForm(_form);\" type='submit' name='Delete' value='{$app_strings['LBL_DELETE_BUTTON']}' id='delete_button'>";
                $this->ss->assign('DELETE', $delete);
            }elseif($this->bean->meeting_type == 'Meeting'){
                unset($this->dv->defs['panels']['LBL_DEMO']);
                unset($this->dv->defs['panels']['LBL_PT']);
                unset($this->dv->defs['panels']['LBL_SESSION']);
                unset($this->dv->defs['panels']['LBL_OTHER']);
                $edit = "<input title='Edit' accesskey='i' class='button primary' onclick=\"var _form = document.getElementById('formDetailView'); _form.return_module.value='Meetings'; _form.return_action.value='DetailView'; _form.return_id.value='{$this->bean->id}'; _form.action.value='EditView';SUGAR.ajaxUI.submitForm(_form);\" type='button' name='Edit' id='edit_button' value='{$app_strings['LBL_EDIT_BUTTON']}'>";
                $this->ss->assign('EDIT', $edit);
                $this->ss->assign('ADD_ANOTHER_BUTTON', '');

                $delete = "<input title='Delete' accesskey='d' class='button' onclick=\"var _form = document.getElementById('formDetailView'); _form.return_module.value='Meetings'; _form.return_action.value='ListView'; _form.action.value='Delete'; if(confirm('Are you sure you want to delete this record?')) SUGAR.ajaxUI.submitForm(_form);\" type='submit' name='Delete' value='{$app_strings['LBL_DELETE_BUTTON']}' id='delete_button'>";
                $this->ss->assign('DELETE', $delete);
            }
            else{
                unset($this->dv->defs['panels']['LBL_DEMO']);
                unset($this->dv->defs['panels']['LBL_PT']);
                unset($this->dv->defs['panels']['LBL_SESSION']);
                unset($this->dv->defs['panels']['LBL_MEETING_INFORMATION']);
                $edit = "<input title='Edit' accesskey='i' class='button primary' onclick=\"var _form = document.getElementById('formDetailView'); _form.return_module.value='Meetings'; _form.return_action.value='DetailView'; _form.return_id.value='{$this->bean->id}'; _form.action.value='EditView';SUGAR.ajaxUI.submitForm(_form);\" type='button' name='Edit' id='edit_button' value='{$app_strings['LBL_EDIT_BUTTON']}'>";
                $this->ss->assign('EDIT', $edit);
                $this->ss->assign('ADD_ANOTHER_BUTTON', '');

                $delete = "<input title='Delete' accesskey='d' class='button' onclick=\"var _form = document.getElementById('formDetailView'); _form.return_module.value='Meetings'; _form.return_action.value='ListView'; _form.action.value='Delete'; if(confirm('Are you sure you want to delete this record?')) SUGAR.ajaxUI.submitForm(_form);\" type='submit' name='Delete' value='{$app_strings['LBL_DELETE_BUTTON']}' id='delete_button'>";
                $this->ss->assign('DELETE', $delete);

            }
        }
        parent::display();
    }
}
?>