<?PHP
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

/**
* THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
*/
require_once('modules/J_Gradebook/J_Gradebook_sugar.php');
class J_Gradebook extends J_Gradebook_sugar {
    var $class;
    var $gradebookConfig;
    var $students;
    var $config;
    var $gradebookDetail;
    /**
    * This is a depreciated method, please start using __construct() as this method will be removed in a future version
    *
    * @see __construct
    * @depreciated
    */
    function J_Gradebook(){
        self::__construct();
    }

    public function __construct(){
        parent::__construct();
    }

    public function _constructDefault($new_config = false, $student_id = '') {
        $this->class = new J_Class();
        $this->class->retrieve($this->j_class_j_gradebook_1j_class_ida);
        //Load 1 Student
        if(empty($student_id)){
            $this->class->load_relationship('j_class_contacts_1');
            $this->students = $this->class->j_class_contacts_1->getBeans();
        }else
            $this->students = array($student_id => $student_id);

        if($new_config){
            $gradebookConfig = new J_GradebookConfig();
            $gradebookConfig->retrieve_by_string_fields(
                array(
                    'team_id'   => $this->class->team_id,
                    'koc_id'    => $this->class->koc_id,
                    'type'      => $this->type,
                    'minitest'  => $this->minitest,
                )
            );
            if(!empty($gradebookConfig->content) && !empty($gradebookConfig->id)){
                $this->grade_config         = $gradebookConfig->content;
                $this->weight               = $gradebookConfig->weight;
                $this->gradebook_config_id  = $gradebookConfig->id;
            }
            //Update Gradebook Name
            if(!empty($gradebookConfig->name) && ($gradebookConfig->name != $this->name)){
                $GLOBALS['db']->query("UPDATE j_gradebook SET name = '{$gradebookConfig->name}' WHERE id = '{$this->id}'");    
            }

        }
        $this->config = json_decode(html_entity_decode($this->grade_config),true);
        if($this->type == 'Overall'){
            $arrOverall = array( 'AA' => array(
                'name' => 'overall',
                'alias' => 'AA',
                'label' => 'Overall(%)',
                'group' => 'Total',
                'type'  => 'percent',

                'visible' => 1,
                'max_mark' => 100,
                'weight' => 100,
                'readonly' => 1,
                'formula' => '',

                ),
            );
            if(!empty($this->config))
                $this->config = $arrOverall + $this->config;
            else $this->config = $arrOverall;
        }
        $this->loadGradebookDetail($student_id);
    }

    /**
    * load bang diem chi tiet cac hoc vien
    *
    * @param bool $refresh
    *
    * @author Trung Nguyen
    */
    public function loadGradeContent($new_config = false, $refresh = false) {
        //Load config
        $this->_constructDefault($new_config);

        $keys = array();
        $maxs = 0;
        $html = "<table class='table-border' id='config_content' width='100%'>
        <thead>
        <tr>
        <td width='3%' rowspan='2' ><b>No.</b></td>
        <td width='7%' rowspan='2' style=\"text-align:left;\"><b>Avatar</b></td>
        <td width='10%' rowspan='2' style=\"text-align:left;\"><b>Student ID</b></td>
        <td width='10%' rowspan='2' style=\"text-align:left;\"><b>Student Name</b></td>
        <td width='7%' rowspan='2' style=\"text-align:left;\"><b>Nickname</b></td>
        <td width='7%' rowspan='2' style=\"text-align:left;\"><b>Birthdate</b></td>";
        //Add Group Point
        $groups          = array();
        $lastGroupName  = '#***#';
        $keyGroup       = 0;
        foreach($this->config as $key => $params){
            if(!$params['visible']) continue;
            if($lastGroupName != $params['group']){
                $keyGroup++;
                $lastGroupName = $params['group'];
            }
            $groups[$keyGroup]['name']    = $params['group'];
            $groups[$keyGroup]['colspan']+=1;
        }

        foreach($groups as $key => $group)
            $html .=  "<td class='td-mark' colspan='{$group['colspan']}'><b>{$group['name']}</b></td>";
        $html .= '</tr><tr>';

        foreach($this->config as $key => $params){
            if(!$params['visible']) continue;
            if($params['type'] != 'comment'){
                $max_mark = " (".$params['max_mark'].")";
                $html.= "<td width='5%' class ='td-mark'> <b> ".$params['label'].'<br>'.$max_mark." </b>";
                //Custom Button Calculate Homework - Attendance
                if(!empty($params['custom_btn_label']) && !empty($params['custom_btn_function']))
                    $html .= "<br><button type='button' onclick='{$params['custom_btn_function']}(\"{$params['alias']}\");' style='line-height: 10px;' class='button'>{$params['custom_btn_label']}</button>";

                $html .= "</td>";

            }else
                $html .= "<td width='15%' style=\"text-align:left;\"><b>".$params['label']."</b></td>";
        }
        $html .= "</thead><tbody> ";

        $no = 0;
        foreach($this->students as $student_id => $student) {
            $student_mark = $this->gradebookDetail[$student_id];
            $no++;

            $picture = '<a href="javascript:SUGAR.image.lightbox(\'index.php?entryPoint=download&id='.$student->picture.'&type=SugarFieldImage&isTempFile=1\')"><img src="uploadImage/imagesResize/'.$student->picture.'" style="height: 80px;">&nbsp;</a>';
            if(empty($student->picture))
                $picture = '<img src="themes/default/images/noimage.png" style="height: 80px;">';
            $tr = "<tr>
            <td class = 'center'>{$no}</td>
            <td>$picture</td>
            <td>{$student->contact_id}</td>
            <td><span class = 'student_name'>{$student->name}</span>
            <input name='student_id[]' value = '{$student->id}' type='hidden' >
            </td>
            <td>{$student->nick_name}</td>
            <td>{$student->birthdate}</td>";

            foreach($this->config as $key => $params) {
                if(!$params['visible']) continue;

                if($params['type'] != 'comment'){
                    $_mark = format_number($student_mark[$key],1,1);
                    $tr.= "<td class='td-mark'>
                    <input name='{$key}[]'
                    id = '{$student_id}-{$key}'
                    config-data='".($_mark ? $_mark : 0)."'
                    config-max='{$params['max_mark']}'
                    config-weight='{$params['weight']}'
                    config-readonly='{$params['readonly']}'
                    config-formula='{$params['formula']}'
                    config-alias='{$key}'
                    value = '".($_mark ? $_mark : 0 )."' class = 'input_mark'
                    size = '3'
                    ".($params['readonly']?"readonly":"")."
                    ".($key == 'AA' ? " type='hidden'" : " type='text'")."/>
                    ".($key == 'AA' ? "<span class='final_result'>$_mark</span>" : "")."
                    </td>" ;
                }else{
                    if(strlen($student_mark[$params['name'].'_comment_label']) > 30) {
                        $cm = "<span class='value_teacher_".$params['name']."' title='{$student_mark[$params['name'].'_comment_label']}'>".(mb_substr($student_mark[$params['name'].'_comment_label'],0,200,'UTF-8')."...")."</span>";
                    } else if(strlen($student_mark[$params['name'].'_comment_label']) > 0) {
                        $cm = "<span class='value_teacher_".$params['name']."' title='{$student_mark[$params['name'].'_comment_label']}'>".$student_mark[$params['name'].'_comment_label']."</span>";
                    } else {
                        $cm = "<span class='value_teacher_".$params['name']."' title='{$student_mark[$params['name'].'_comment_label']}'>--None--</span>";
                    }
                    $tr .= "<td class='comment' config-name='{$params['name']}' style=\"cursor:pointer\">
                    <input type='hidden' name='key_teacher_".$params['name']."[]' value = '".json_encode($student_mark['comment_key'])."'>
                    $cm
                    <input type='hidden' name='value_teacher_".$params['name']."[]' value = '{$student_mark[$params['name'].'_comment_label']}'>
                    </td>";
                }

                if($no==1){
                    $keys[] =  $key;
                }
            }

            $tr .= "</tr>";
            $html.= $tr;
        }
        $html .= "</tbody>
        </table>
        <input type='hidden' name = 'key' value='".(json_encode($keys))."'>";
        return $html;
    }

    /**
    * lay diem chi tiet cua mot hoc vien trong bang diem
    *
    * @param String: Student CRMID
    *
    * @author Trung Nguyen
    */
    public function getDetailForStudent($student_id) {
        $this->_constructDefault(false, $student_id);
        $student_mark = $this->gradebookDetail[$student_id];

        $gradebook              = array();
        $gradebook['name']      = $this->name;
        $gradebook['weight']    = $this->weight;
        $gradebook['type']      = $this->type;
        $gradebook['minitest']  = $this->minitest;
        $gradebook['date_input']= $this->date_input;
        $gradebook['center']    = $this->team_name;

        $gradebookDetail= array();
        foreach($this->config as $key => $params) {
            if(!$params['visible']) continue;
            //Total result of this gradebook
            if(!empty($params['formula']) || ($key == 'AA' && !empty($student_mark[$key])))
                $gradebook['result']    = round($student_mark[$key],2);

            $gradebookDetail[$params['label']]['label']     = $params['label'];
            $gradebookDetail[$params['label']]['weight']    = $params['weight'];
            $gradebookDetail[$params['label']]['max_mark']  = $params['max_mark'];
            $gradebookDetail[$params['label']]['mark']      = round($student_mark[$key],2);
            if($params['type'] == 'comment')
                $gradebookDetail[$params['label']]['mark'] = $student_mark[$params['name'].'_comment_label'];
            $gradebookDetail[$params['label']]['per']       = $params['max_mark'] + 0 > 0 ? (round(($student_mark[$key] ? $student_mark[$key] + 0 : 0 )/$params['max_mark']*100, 1, 1 )) : "";
            $gradebook['detail']        = $gradebookDetail;
        }
        return $gradebook;
    }

    /**
    * lay diem chi tiet cua cac hoc vien trong bang diem
    *
    * @author Trung Nguyen
    */
    function loadGradebookDetail($student_id = '') {
        //Load 1 student
        if(!empty($student_id)){
            $ext1 = "AND l3.id = '$student_id'";
            $ext2 = "AND student_id = '$student_id'";
        }

        if($this->type == 'Overall'){
            //Update calculate Overall
            $q1 = "SELECT DISTINCT
            IFNULL(j_gradebookdetail.id, '') primaryid,
            IFNULL(l1.id, '') gradebook_id,
            IFNULL(l3.id, '') student_id,
            l1.type type,
            IFNULL(j_gradebookdetail.final_result, 0) final_result,
            ROUND(((j_gradebookdetail.final_result * l1.weight)/100),2) count_on_total
            FROM
            j_gradebookdetail
            INNER JOIN
            j_gradebook l1 ON j_gradebookdetail.gradebook_id = l1.id
            AND l1.deleted = 0
            INNER JOIN
            j_class_j_gradebook_1_c l2_1 ON l1.id = l2_1.j_class_j_gradebook_1j_gradebook_idb
            AND l2_1.deleted = 0
            INNER JOIN
            j_class l2 ON l2.id = l2_1.j_class_j_gradebook_1j_class_ida
            AND l2.deleted = 0
            INNER JOIN
            contacts l3 ON j_gradebookdetail.student_id = l3.id
            AND l3.deleted = 0 $ext1
            WHERE
            (((l2.id = '{$this->class->id}')
            AND (l1.type IN ('Progress' , 'Commitment', 'Overall'))
            AND (l1.minitest = '' OR l1.minitest)))
            AND j_gradebookdetail.deleted = 0
            ORDER BY student_id";
            $rs1 = $GLOBALS['db']->query($q1);
            $grades = array();
            while($row1 = $GLOBALS['db']->fetchByAssoc($rs1)){
                $grades[$row1['student_id']][$row1['type']]  = $row1['count_on_total'];
                if($row1['type'] == 'Overall'){
                    $grades[$row1['student_id']][$row1['type']]             = $row1['final_result'];
                    $grades[$row1['student_id']]['overall_gradebook_id']    = $row1['primaryid'];
                }
            }
            foreach($this->students as $student_id => $student){
                $progress   = (!empty($grades[$student_id]['Progress'])) ? (float)($grades[$student_id]['Progress']) : 0;
                $commitment = (!empty($grades[$student_id]['Commitment'])) ? (float)($grades[$student_id]['Commitment']) : 0;
                $overall    = (!empty($grades[$student_id]['Overall'])) ? (float)($grades[$student_id]['Overall']) : 0;
                //Re-calculate Overall
                $re_overall = round($progress + $commitment,2);
                $overall_id = $grades[$student_id]['overall_gradebook_id'];
                if($re_overall != $overall && !empty($overall_id)){
                    $cerArr = $this->calLevelCertificate($re_overall);
                    $GLOBALS['db']->query("UPDATE j_gradebookdetail SET final_result=$re_overall, certificate_type='{$cerArr['type']}', certificate_level='{$cerArr['level']}' WHERE id='$overall_id'");
                }
                //Re-assign Total mark
                $grades[$student_id]['Overall'] = $re_overall;
            }
        }

        $sql = "SELECT student_id, content FROM j_gradebookdetail WHERE deleted = 0 AND gradebook_id = '{$this->id}' $ext2";
        $this->gradebookDetail = array();
        $result     = $GLOBALS['db']->query($sql);
        $countGrade = 0;
        while($row  = $GLOBALS['db']->fetchByAssoc($result)){
            $countGrade++;
            $this->gradebookDetail[$row['student_id']] =  json_decode(html_entity_decode($row['content']),true);
            if($this->type == 'Overall')
                $this->gradebookDetail[$row['student_id']]['AA'] = format_number($grades[$row['student_id']]['Overall'],1,1);
        }
        //If no result - Load final
        foreach($this->students as $student_id => $student)
            if(empty($this->gradebookDetail[$student_id]['AA']))
                $this->gradebookDetail[$student_id]['AA'] = format_number($grades[$student_id]['Overall'],1,1);
    }
    function calLevelCertificate($final_result){
        $final_result = unformat_number($final_result);
        if($final_result >= 90) return array('type' => 'Distinction with Honours','level' => 'A*');
        if($final_result >= 80) return array('type' => 'Distinction','level' => 'A');
        if($final_result >= 65) return array('type' => 'Merit','level' => 'B');
        if($final_result >= 50) return array('type' => 'Pass','level' => 'C');
        if($final_result >= 40) return array('type' => 'Pass','level' => 'D');
        if($final_result >= 20) return array('type' => 'Narrow Fail','level' => 'NF');
        if($final_result >= 0)  return array('type' => 'Clear Fail','level' => 'CF');
    }

    function getGradebookDetailView() {
        $this->_constructDefault();
        $html = "<div id='report_results'>
        <table id = 'markdetail_content' class = 'mark-table-border' width='100%'>
        <thead>
        <tr>
        <td width='3%' rowspan='2'><b>No.</b></td>
        <td width='7%' rowspan='2' style=\"text-align:left;\"><b>Avatar</b></td>
        <td width='10%' rowspan='2' style=\"text-align:left;\"><b>Student ID</b></td>
        <td width='10%' rowspan='2' style=\"text-align:left;\"><b>Student Name</b></td>
        <td width='5%' rowspan='2' style=\"text-align:left;\"><b>Nickname</b></td>
        <td width='7%' rowspan='2' style=\"text-align:left;\"><b>Birthdate</b></td>";
        //Add Group Point
        $groups          = array();
        $lastGroupName  = '#***#';
        $keyGroup       = 0;
        foreach($this->config as $key => $params){
            if(!$params['visible']) continue;
            if($lastGroupName != $params['group']){
                $keyGroup++;
                $lastGroupName = $params['group'];
            }
            $groups[$keyGroup]['name']    = $params['group'];
            $groups[$keyGroup]['colspan']+=1;
        }

        foreach($groups as $key => $group)
            $html .=  "<td class='td-mark' colspan='{$group['colspan']}'><b>{$group['name']}</b></td>";
        $html .= '</tr><tr>';

        foreach($this->config as $key => $params){
            if(!$params['visible']) continue;
            if($params['type'] != 'comment'){
                $max_mark = '';
                if(!empty($params['max_mark']))
                    $max_mark = "<br> (".$params['max_mark'].")";
                $html.= "<td class ='td-mark'> <b> ".$params['label'].$max_mark." </b></td>";
            }else
                $html .= "<td width='15%' style=\"text-align:left;\"><b>".$params['label']."</b></td>";
        }
        $html .= "</thead><tbody> ";
        $no = 0;

        foreach($this->students as $student_id => $student){
            //        if(!isset($this->gradebookDetail[$student_id]))
            //                continue;
            $student_mark = $this->gradebookDetail[$student_id];
            $no++;

            $picture = '<a href="javascript:SUGAR.image.lightbox(\'index.php?entryPoint=download&id='.$student->picture.'&type=SugarFieldImage&isTempFile=1\')"><img src="uploadImage/imagesResize/'.$student->picture.'" style="height: 80px;">&nbsp;</a>';
            if(empty($student->picture))
                $picture = '<img src="themes/default/images/noimage.png" style="height: 80px;">';


            $tr = "<tr>
            <td class = 'center'>{$no}</td>
            <td>$picture</td>
            <td><a href='index.php?module=Contacts&action=DetailView&record=$student_id'>{$student->contact_id}</a></td>
            <td><span class = 'student_name'><a href='index.php?module=Contacts&action=DetailView&record=$student_id'>{$student->name}</a></span>
            </td>
            <td>{$student->nick_name}</td>
            <td>{$student->birthdate}</td>";

            foreach($this->config as $key => $params) {
                if(!$params['visible']) continue;
                if($params['type'] != 'comment'){
                    $_mark = format_number($student_mark[$key],1,1);
                    $tr.= "<td class='td-mark'><span id = '{$student_id}-{$key}' class = 'input_mark' >$_mark</td>";
                }else{
                    if(strlen($student_mark[$params['name'].'_comment_label']) > 0) {
                        $cm = "<span class='value_teacher_".$params['name']."' title='{$student_mark[$params['name'].'_comment_label']}'>".$student_mark[$params['name'].'_comment_label']."</span>";
                    } else {
                        $cm = "<span class='value_teacher_".$params['name']."' title='{$student_mark[$params['name'].'_comment_label']}'>--None--</span>";
                    }
                    $tr .= "<td class='comment'>
                    $cm
                    </td>";
                }
            }
            $tr .= "</tr>";
            $html.= $tr;
        }
        $html .= "</tbody>
        </table>";
        return $html;
    }

    function getConfigHTML($content = ''){
        if(!empty($content)){
            $title_html     = "";
            $alias_html     = "";
            $visible_html   = "";
            $max_mark_html  = "";
            $weight_html    = "";
            $readonly_html  = "";
            $formula_html   = "";

            //Add Group Point
            $groups          = array();
            $lastGroupName  = '#***#';
            $keyGroup       = 0;
            foreach($content as $key => $defaut) {
                if(!$defaut['visible']) continue;
                if($lastGroupName != $defaut['group']){
                    $keyGroup++;
                    $lastGroupName = $defaut['group'];
                }
                $groups[$keyGroup]['name']    = $defaut['group'];
                $groups[$keyGroup]['colspan']+=1;

                $title_html .= "<td class = 'center no-bottom-border'><b>".$defaut['label']."</b></td>";
                $alias_html .= "<td class = 'center no-top-border'><b>(".$defaut['alias'].")</b></td>";
                if($defaut['type'] == 'comment'){
                    $max_mark_html .= "<td class = 'center'><span alias='{$key}' class = ' max_mark input_mark'> ".$GLOBALS['app_list_strings']['option_comment_list'][$defaut['comment_list']]."</span></td>";
                    $weight_html .= "<td class = 'center'></td>";
                    $readonly_html .= "<td class = 'center'></td>";
                    $formula_html .= "<td class = 'center'></td>";
                }else{
                    $max_mark_html .= "<td class = 'center'>
                    <span alias='{$key}' class = ' max_mark input_mark'> ".$defaut['max_mark']."</span>
                    </td>";
                    $weight_html .= "<td class = 'center'>
                    <span alias='{$key}' class = 'weight input_weight'>".$defaut['weight']."</span>
                    </td>";
                    $readonly_html .= "<td class = 'center'>
                    <span class = 'cf_readonly' alias='{$key}'> ".($defaut['readonly']?"Yes":"No")." </span>
                    </td>";
                    $formula_html .= "<td class = 'center'>
                    <span alias='{$key}' class = 'input_formula formula' >{$defaut['formula']}</span>
                    </td>";
                }

            }
            //Generate Group
            foreach($groups as $key => $group)
                $html .=  "<td class = 'center' colspan='{$group['colspan']}'><b>{$group['name']}</b></td>";

            $table = "
            <table id = 'config_content' class = 'table-border' width='100%' cellpadding=0 cellspacing=0>
            <thead>
            <tr>
            <td rowspan = 3> </td>
            $group_html
            </tr>
            <tr>
            $title_html
            </tr>
            <tr>
            $alias_html
            </tr>
            </thead>
            <tbody>
            <tr>
            <td><b>Max mark</b></td>
            $max_mark_html
            </tr>
            <tr>
            <td><b>Weight(%)</b></td>
            $weight_html
            </tr>
            <tr>
            <td><b>Readonly</b></td>
            $readonly_html
            </tr>
            <tr>
            <td><b>Formula</b></td>
            $formula_html
            </tr>
            </tbody>
            </table>
            </div>";
            return $table;
        }else
            return '';
    }
}
?>