<?php
class J_GradebookConfigViewConfig extends SugarView{
    function J_GradebookConfigViewConfig(){
        $this->bean2 = new J_GradebookConfig();
        $this->bean2->retrieve($_REQUEST['gradebook_config_id']);
        parent::display();
    }

    function display(){
        include_once("custom/modules/J_GradebookConfig/GradebookConfigFunctions.php");
        include_once("custom/include/_helper/junior_gradebook_utils.php");
        global $db, $mod_strings, $app_list_strings,$app_strings,$timedate;

        $ss = new Sugar_Smarty();
        $ss->assign('MOD',$mod_strings);
        $ss->assign('APP',$app_strings);
        $ss->assign('RECORD',$this->bean2->id);
        //
        $team_options = getCenter($this->bean2->team_id);
        $ss->assign('CENTER_OPTIONS',$team_options);
        //
        $htm_koc = getKOCOfCenter($this->bean2->team_id, $this->bean2->koc_id);
        $ss->assign('KOC_OPTIONS',$htm_koc);
        if(empty($this->bean2->weight))
            $this->bean2->weight = '';
        $ss->assign('WEIGHT',$this->bean2->weight);
        $ss->assign('NAME',$this->bean2->name);

        $type_options = get_select_options_with_id($app_list_strings['gradeconfig_type_options'],$this->bean2->type);
        $ss->assign('TYPE_OPTIONS',$type_options);

        $minitest_options = get_select_options_with_id($app_list_strings['gradeconfig_minitest_options'],$this->bean2->minitest);
        $ss->assign('MINITEST_OPTIONS',$minitest_options);
        $ss->assign('MINITEST_SELECTED',$this->bean2->minitest);

        $ss->assign('CONFIG_CONTENT','');

        $ss->display("custom/modules/J_GradebookConfig/tpls/Config.tpl") ;
    }
}
?>