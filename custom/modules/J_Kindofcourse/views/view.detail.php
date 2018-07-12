<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class J_KindofcourseViewDetail extends ViewDetail {
    function J_KindofcourseViewDetail(){
        parent::ViewDetail();
    }
    function display() {
        //get list levels to array
        $tmpContent = json_decode(html_entity_decode($this->bean->content),true);
        $levelConfigHtml = '<table style="width:50%"><thead><tr>';
        $levelConfigHtml .= '<th>'.translate('LBL_LEVEL','J_Kindofcourse').'</th>';
        $levelConfigHtml .= '<th>'.translate('LBL_MODULE','J_Kindofcourse').'</th>';
        $levelConfigHtml .= '<th>'.translate('LBL_HOURS','J_Kindofcourse').'</th>';
        $levelConfigHtml .= '<th>'.translate('LBL_IS_SET_HOUR','J_Kindofcourse').'</th>';
        $levelConfigHtml .= '<th>'.translate('LBL_IS_UPGRADE','J_Kindofcourse').'</th>';
        $levelConfigHtml .= '</tr><tbody>';

        foreach($tmpContent as $key => $value){
            $levelConfigHtml .= '<tr>';
            $levelConfigHtml .= '<td style="text-align:center; width:20%">'.(($value['levels'] == '') ? '-none-' : $value['levels']).'</td>';
            $levelConfigHtml .= '<td style="text-align:center; width:20%">'.implode(",", $value['modules']).'</td>';
            $levelConfigHtml .= '<td style="text-align:center; width:15%">'.$value['hours'].'</td>';
            $levelConfigHtml .= '<td style="text-align:center; width:20%"> '.(($value['is_set_hour'] == '1') ? '<input type="checkbox" name="is_set_hour" checked disabled>' : '<input type="checkbox" name="is_set_hour" disabled>').' </td>';
            $levelConfigHtml .= '<td style="text-align:center; width:20%"> '.(($value['is_upgrade'] == '1') ? '<input type="checkbox" name="is_upgrade" checked disabled>' : '<input type="checkbox" name="is_upgrade" disabled>').' </td>';
            $levelConfigHtml .= '</tr>';
        }
        $levelConfigHtml .= '</tbody></table>';
        $this->ss->assign('LEVEL_CONFIG',$levelConfigHtml);


        $sylls = json_decode(html_entity_decode($this->bean->syllabus),true);
        $syllHtml = '<table style="width:50%"><thead><tr>';
        $syllHtml .= '<th>'.translate('LBL_LESSON','J_Kindofcourse').'</th>';
        $syllHtml .= '<th>'.translate('LBL_CONTENT_SYLLABUS','J_Kindofcourse').'</th>';
        $syllHtml .= '</tr><tbody>';
        foreach($sylls as $key => $value){
            $syllHtml .= '<tr>';
            $syllHtml .= '<td style="text-align:center; width:10%">'.($value['lesson']).'</td>';
            $syllHtml .= '<td style="text-align:center; width:40%">'.($value['content']).'</td>';
            $syllHtml .= '</tr>';
        }
        $syllHtml .= '</tbody></table>';
        $this->ss->assign('SYLLABUS_HTML',$syllHtml);

        $team_type = getTeamType($this->bean->team_id);
        $this->ss->assign('team_type',$team_type);
        parent::display();
    }
}
?>