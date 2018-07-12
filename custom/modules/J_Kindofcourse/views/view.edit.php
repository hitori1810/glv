<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class J_KindofcourseViewEdit extends ViewEdit{
    const MAX_BOOK_NAME = 3;
    public function __construct(){
        parent::ViewEdit();
    }

    public function preDisplay() {
        parent::preDisplay();
        echo '<script> var max_book_name='.self::MAX_BOOK_NAME.'</script>';
    }

    public function display(){
        $team_type = $GLOBALS['current_user']->team_type;
        if(!empty($this->bean->team_id))
            $team_type = getTeamType($this->bean->team_id);
        $this->ss->assign('team_type',$team_type);
        $maxBook = J_KindofcourseViewEdit::MAX_BOOK_NAME;
        //Chèn một dòng display none trên cùng
        $levelItems = generateEmptyRow(false);

        if (empty($this->bean->content)){
            // Nếu content trống thì generate một dòng trống
            $levelItems .= generateEmptyRow(true);  
        }
        else {
            // Nếu có content thì generate cho content
            $content = json_decode(html_entity_decode($this->bean->content),true);

            foreach($content as $key => $value){
                $ssLevelItem = new Sugar_Smarty();
                $ssLevelItem->assign("DISPLAY","");
                $ssLevelItem->assign("LEVEL_OPTIONS", get_select_options_with_id($GLOBALS['app_list_strings']['level_program_list'],$value['levels']));
                $ssLevelItem->assign("MODULE_OPTIONS", get_select_options_with_id($GLOBALS['app_list_strings']['module_program_list'],$value['modules']));
                $ssLevelItem->assign("HOURS", $value['hours']);
                $ssLevelItem->assign("IS_UPGRADE", $value['is_upgrade']);
                $ssLevelItem->assign("IS_SET_HOUR", $value['is_set_hour']);
                $ssLevelItem->assign("MOD", $GLOBALS['mod_strings']);
                $ssLevelItem->assign("JSON", json_encode($value));
                $levelItems .= $ssLevelItem->fetch('custom/modules/J_Kindofcourse/tpls/levelConfigItem.tpl');
            }
        }

        // Assign tpl level config
        $ssLevelConfig = new Sugar_Smarty();
        $ssLevelConfig->assign("TBODY", $levelItems);
        $ssLevelConfig->assign("MOD", $GLOBALS['mod_strings']);
        $levelConfigHtml = $ssLevelConfig->fetch('custom/modules/J_Kindofcourse/tpls/levelConfig.tpl');
        $this->ss->assign('LEVEL_CONFIG',$levelConfigHtml);

        //Syslabus Generate
        $sylItems = getSyllabusRow('','','',true);
        if (empty($this->bean->syllabus))
            $sylItems .= getSyllabusRow('1','','',false);

        else {
            // Nếu có content thì generate cho content
            $sylls = json_decode(html_entity_decode($this->bean->syllabus),true);
            foreach($sylls as $key => $syl)
                $sylItems .= getSyllabusRow($syl['lesson'],$syl['content'],json_encode($syl),false);
                
        }

        $ssSylabus = new Sugar_Smarty();
        $ssSylabus->assign("TBODYSYL", $sylItems);
        $ssSylabus->assign("MOD", $GLOBALS['mod_strings']);
        $sylabusHtml = $ssSylabus->fetch('custom/modules/J_Kindofcourse/tpls/syllabus_config.tpl');
        $this->ss->assign('SYLLABUS_HTML',$sylabusHtml);

        parent::display();
    }
}
// Generate Add row template
function generateEmptyRow($show){
    $ssLevelItem = new Sugar_Smarty();
    if ($show)$ssLevelItem->assign("DISPLAY", "");
    else $ssLevelItem->assign("DISPLAY", "style='display:none;'");
    $ssLevelItem->assign("MOD", $GLOBALS['mod_strings']);
    $ssLevelItem->assign("LEVEL_OPTIONS", get_select_options_with_id($GLOBALS['app_list_strings']['level_program_list'],""));
    $ssLevelItem->assign("MODULE_OPTIONS", get_select_options_with_id($GLOBALS['app_list_strings']['module_program_list'],""));
    $ssLevelItem->assign("IS_UPGRADE", '1');
    $ssLevelItem->assign("IS_SET_HOUR", '1');
    $ssLevelItem->assign("JSON", "");
    return $ssLevelItem->fetch('custom/modules/J_Kindofcourse/tpls/levelConfigItem.tpl');
}

function getSyllabusRow($lesson, $content, $json, $show){
    if($show)
        $display = 'style="display:none;"';

    $tpl_addrow  = "<tr class='rowSyllabus' $display>";
    $tpl_addrow .= '<td scope="col" align="center">'; 
    $tpl_addrow .= "<input name='json_syl[]' value='$json' class='json_syl' type='hidden'/>"; 
    $tpl_addrow .= '<input name="sys_lesson[]" value="'.$lesson.'" class="sys_lesson" type="text" style="width: 70px;text-align: center;" db-data="100"></td>'; 
    
    $tpl_addrow .= '<td align="center"><textarea id="sys_content" class="sys_content" name="sys_content" rows="2" cols="30">'.$content.'</textarea></td>';

    $tpl_addrow .= "<td style='text-align: left;'><button type='button' class='btnRemoveSyl'><img src='themes/default/images/id-ff-remove-nobg.png' alt='Remove'></button></td>";
    $tpl_addrow .= '</tr>';
    return $tpl_addrow;
}