<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class J_PaymentDetailViewDetail extends ViewDetail{

    public function display(){
        $btn_delete = '';
        if(($GLOBALS['current_user']->isAdmin())){
            $btn_delete = '<input title="Edit" accesskey="i" class="button primary" onclick="var _form = document.getElementById(\'formDetailView\'); _form.return_module.value=\'J_PaymentDetail\'; _form.return_action.value=\'DetailView\'; _form.return_id.value=\''.$this->bean->id.'\'; _form.action.value=\'EditView\';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Edit" id="edit_button" value="Edit">';
            //if($GLOBALS['current_user']->id == '1')
            $btn_delete .= '<input title="Delete" accesskey="d" class="button" onclick="var _form = document.getElementById(\'formDetailView\'); _form.return_module.value=\'J_PaymentDetail\'; _form.return_action.value=\'ListView\'; _form.action.value=\'Delete\'; if(confirm(\'Are you sure you want to delete this record?\')) SUGAR.ajaxUI.submitForm(_form);" type="submit" name="Delete" value="Delete" id="delete_button">';
        }
        $this->ss->assign('BTN_DELETE', $btn_delete);

        parent::display();
    }

}