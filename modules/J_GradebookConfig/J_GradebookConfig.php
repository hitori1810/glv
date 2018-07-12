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
require_once('modules/J_GradebookConfig/J_GradebookConfig_sugar.php');
class J_GradebookConfig extends J_GradebookConfig_sugar {
    //config cot diem trong config
    var $gradebook_config_Progress = array(
        'A' => array(
            'name' => 'minitest1',
            'alias' => 'A',
            'label' => 'Mini Test 1',
            'group' => 'Mini Test',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadMinitest',
        ),
        'B' => array(
            'name' => 'minitest2',
            'alias' => 'B',
            'label' => 'Mini Test 2',
            'group' => 'Mini Test',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadMinitest',
        ),
        'C' => array(
            'name' => 'minitest3',
            'alias' => 'C',
            'label' => 'Mini Test 3',
            'group' => 'Mini Test',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadMinitest',
        ),
        'D' => array(
            'name' => 'minitest4',
            'alias' => 'D',
            'label' => 'Mini Test 4',
            'group' => 'Mini Test',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadMinitest',
        ),
        'E' => array(
            'name' => 'minitest5',
            'alias' => 'E',
            'label' => 'Mini Test 5',
            'group' => 'Mini Test',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadMinitest',
        ),
        'F' => array(
            'name' => 'minitest6',
            'alias' => 'F',
            'label' => 'Mini Test 6',
            'group' => 'Mini Test',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadMinitest',
        ),


        'G' => array(
            'name' => 'project1',
            'alias' => 'G',
            'label' => 'Project 1',
            'group' => 'Project',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadProject',

        ),
        'H' => array(
            'name' => 'project2',
            'alias' => 'H',
            'label' => 'Project 2',
            'group' => 'Project',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadProject',

        ),
        'I' => array(
            'name' => 'project3',
            'alias' => 'I',
            'label' => 'Project 3',
            'group' => 'Project',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadProject',

        ),
        'J' => array(
            'name' => 'project4',
            'alias' => 'J',
            'label' => 'Project 4',
            'group' => 'Project',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadProject',
        ),
        'K' => array(
            'name' => 'project5',
            'alias' => 'K',
            'label' => 'Project 5',
            'group' => 'Project',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadProject',
        ),
        'L' => array(
            'name' => 'project6',
            'alias' => 'L',
            'label' => 'Project 6',
            'group' => 'Project',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadProject',
        ),

        'M' => array(
            'name' => 'reading',
            'alias' => 'M',
            'label' => 'Reading',
            'group' => 'Final',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',

        ),
        'N' => array(
            'name' => 'listening',
            'alias' => 'N',
            'label' => 'Listening',
            'group' => 'Final',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',

        ),
        'O' => array(
            'name' => 'speaking',
            'alias' => 'O',
            'label' => 'Speaking',
            'group' => 'Final',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',

        ),
        'P' => array(
            'name' => 'writing',
            'alias' => 'P',
            'label' => 'Writing',
            'group' => 'Final',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',

        ),
        'Q' => array(
            'name' => 'grammarVocab',
            'alias' => 'Q',
            'label' => 'Grammar and Vocabulary',
            'group' => 'Final',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',

        ),
        'R' => array(
            'name' => 'progress',
            'alias' => 'R',
            'label' => 'Progress(%)',
            'group' => '',
            'type'  => 'percent',

            'visible' => true,
            'max_mark' => 100,
            'weight' => 100,
            'readonly' => true,
            'formula' => '=A+B+G+H+I+J+M+N+O+P+Q',

        ),
        'S' => array(
            'name' => 'comment',
            'alias' => 'S',
            'label' => 'Teacher\'s Recommendations',
            'group' => '',
            'type'  => 'comment',

            'visible' => false,
            'max_mark' => 0,
            'comment_list' => '',
            'weight' => 0,
            'readonly' => false,
            'formula' => '',

        ),
    );

    var $gradebook_config_Commitment = array(
        'A' => array(
            'name' => 'attendance',
            'alias' => 'A',
            'label' => 'Attendance(%)',
            'group' => '',
            'type'  => 'percent',

            'visible' => false,
            'max_mark' => 100,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadAttendance',
        ),
        'B' => array(
            'name' => 'homework',
            'alias' => 'B',
            'label' => 'Homework(%)',
            'group' => '',
            'type'  => 'percent',

            'visible' => false,
            'max_mark' => 100,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
            'custom_btn_label' => 'Reload',
            'custom_btn_function' => 'loadHomework',
        ),
        'C' => array(
            'name' => 'participation',
            'alias' => 'C',
            'label' => 'Participation(%)',
            'group' => '',
            'type'  => 'percent',

            'visible' => false,
            'max_mark' => 100,
            'weight' => 0,
            'readonly' => false,
            'formula' => '',

        ),
        'D' => array(
            'name' => 'commitment',
            'alias' => 'D',
            'label' => 'Commitment(%)',
            'group' => '',
            'type'  => 'percent',

            'visible' => true,
            'max_mark' => 100,
            'weight' => 100,
            'readonly' => true,
            'formula' => '=A+B+C',

        ),
        'E' => array(
            'name' => 'comment',
            'alias' => 'E',
            'label' => 'Teacher\'s Recommendations',
            'group' => '',
            'type'  => 'comment',

            'visible' => false,
            'max_mark' => 0,
            'comment_list' => '',
            'weight' => 0,
            'readonly' => false,
            'formula' => '',

        ),
    );

    var $gradebook_config_Overall =  array(
        'A' => array(
            'name' => 'speakingComment',
            'alias' => 'A',
            'label' => 'Speaking/ Kỹ năng nói',
            'group' => '',
            'type'  => 'comment',

            'visible' => false,
            'max_mark' => 0,
            'weight' => 0,
            'comment_list' => '',
            'readonly' => false,
            'formula' => '',

        ),
        'B' => array(
            'name' => 'writingComment',
            'alias' => 'B',
            'label' => 'Speaking/ Kỹ năng nói',
            'group' => '',
            'type'  => 'comment',

            'visible' => false,
            'max_mark' => 0,
            'weight' => 0,
            'comment_list' => '',
            'readonly' => false,
            'formula' => '',

        ),
        'C' => array(
            'name' => 'readingComment',
            'alias' => 'C',
            'label' => 'Reading/ Kỹ năng đọc',
            'group' => '',
            'type'  => 'comment',

            'visible' => false,
            'max_mark' => 0,
            'weight' => 0,
            'comment_list' => '',
            'readonly' => false,
            'formula' => '',

        ),
        'D' => array(
            'name' => 'listeningComment',
            'alias' => 'D',
            'label' => 'Listening/ Kỹ năng nghe',
            'group' => '',
            'type'  => 'comment',

            'visible' => false,
            'max_mark' => 0,
            'weight' => 0,
            'comment_list' => '',
            'readonly' => false,
            'formula' => '',

        ),
        'E' => array(
            'name' => 'transferableComment',
            'alias' => 'E',
            'label' => 'Transferable Skills/ Kỹ năng mềm',
            'group' => '',
            'type'  => 'comment',

            'visible' => false,
            'max_mark' => 0,
            'weight' => 0,
            'comment_list' => '',
            'readonly' => false,
            'formula' => '',

        ),
        'F' => array(
            'name' => 'didWellOn',
            'alias' => 'F',
            'label' => 'You did well on…',
            'group' => 'Comments on final results',
            'type'  => 'comment',

            'visible' => false,
            'max_mark' => 0,
            'weight' => 0,
            'comment_list' => '',
            'readonly' => false,
            'formula' => '',

        ),
        'G' => array(
            'name' => 'needToImprove',
            'alias' => 'G',
            'label' => 'You need to improve...',
            'group' => 'Comments on final results',
            'type'  => 'comment',

            'visible' => false,
            'max_mark' => 0,
            'weight' => 0,
            'comment_list' => '',
            'readonly' => false,
            'formula' => '',

        ),
        'H' => array(
            'name' => 'commentOnFinal',
            'alias' => 'H',
            'label' => 'Comments on final',
            'group' => 'Comments on final results',
            'type'  => 'comment',

            'visible' => false,
            'max_mark' => 0,
            'weight' => 0,
            'comment_list' => '',
            'readonly' => false,
            'formula' => '',

        ),
        'I' => array(
            'name' => 'comment',
            'alias' => 'I',
            'label' => 'Teacher\'s Recommendations',
            'group' => 'Comments on final results',
            'type'  => 'comment',

            'visible' => false,
            'max_mark' => 0,
            'comment_list' => '',
            'weight' => 0,
            'readonly' => false,
            'formula' => '',

        ),
    );

    var $gradebook_config_minitest = array(
        'A' => array(
            'name' => 'reading',
            'alias' => 'A',
            'label' => 'Reading',
            'group' => '',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 25,
            'readonly' => false,
            'formula' => '',
        ),
        'B' => array(
            'name' => 'listening',
            'alias' => 'B',
            'label' => 'Listening',
            'group' => '',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 25,
            'readonly' => false,
            'formula' => '',
        ),
        'C' => array(
            'name' => 'speaking',
            'alias' => 'C',
            'label' => 'Speaking',
            'group' => '',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 25,
            'readonly' => false,
            'formula' => '',
        ),
        'D' => array(
            'name' => 'writing',
            'alias' => 'D',
            'label' => 'Writing',
            'group' => '',
            'type'  => 'score',

            'visible' => false,
            'max_mark' => 10,
            'weight' => 25,
            'readonly' => false,
            'formula' => '',
        ),
        'E' => array(
            'name' => 'overall',
            'alias' => 'E',
            'label' => 'Overall(%)',
            'group' => '',
            'type'  => 'percent',

            'visible' => true,
            'max_mark' => 100,
            'weight' => 100,
            'readonly' => true,
            'formula' => '=A+B+C+D',
        ),
        'F' => array(
            'name' => 'comment',
            'alias' => 'F',
            'label' => 'Comment',
            'group' => '',
            'type'  => 'comment',

            'visible' => false,
            'max_mark' => 0,
            'comment_list' => '',
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
        ),
    );

    var $gradebook_config_project = array(
        'A' => array(
            'name' => 'task_completion',
            'alias' => 'A',
            'label' => 'Task Completion',
            'group' => '',
            'type'  => 'percent',

            'visible' => false,
            'max_mark' => 100,
            'weight' => 25,
            'readonly' => false,
            'formula' => '',
        ),
        'B' => array(
            'name' => 'planning_organisation',
            'alias' => 'B',
            'label' => 'Planning & Organisation',
            'group' => '',
            'type'  => 'percent',

            'visible' => false,
            'max_mark' => 100,
            'weight' => 25,
            'readonly' => false,
            'formula' => '',
        ),
        'C' => array(
            'name' => 'language_grammar',
            'alias' => 'C',
            'label' => 'Language & Grammar',
            'group' => '',
            'type'  => 'percent',

            'visible' => false,
            'max_mark' => 100,
            'weight' => 25,
            'readonly' => false,
            'formula' => '',
        ),
        'D' => array(
            'name' => 'pronuncation_delivery',
            'alias' => 'D',
            'label' => 'Pronuncation & Delivery',
            'group' => '',
            'type'  => 'percent',

            'visible' => false,
            'max_mark' => 100,
            'weight' => 25,
            'readonly' => false,
            'formula' => '',
        ),
        'E' => array(
            'name' => 'fluency',
            'alias' => 'E',
            'label' => 'Fluency',
            'group' => '',
            'type'  => 'percent',

            'visible' => false,
            'max_mark' => 100,
            'weight' => 25,
            'readonly' => false,
            'formula' => '',
        ),
        'F' => array(
            'name' => 'range_accuracy',
            'alias' => 'F',
            'label' => 'Range & Accuracy',
            'group' => '',
            'type'  => 'percent',

            'visible' => false,
            'max_mark' => 100,
            'weight' => 25,
            'readonly' => false,
            'formula' => '',
        ),
        'G' => array(
            'name' => 'sub_skill_aim',
            'alias' => 'G',
            'label' => 'Sub Skill Aim',
            'group' => '',
            'type'  => 'percent',

            'visible' => false,
            'max_mark' => 100,
            'weight' => 25,
            'readonly' => false,
            'formula' => '',
        ),
        'H' => array(
            'name' => 'participation',
            'alias' => 'H',
            'label' => 'Participation',
            'group' => '',
            'type'  => 'percent',

            'visible' => false,
            'max_mark' => 100,
            'weight' => 25,
            'readonly' => false,
            'formula' => '',
        ),
        'I' => array(
            'name' => 'overall',
            'alias' => 'I',
            'label' => 'Overall(%)',
            'group' => '',
            'type'  => 'percent',

            'visible' => true,
            'max_mark' => 100,
            'weight' => 100,
            'readonly' => true,
            'formula' => '=A+B+C+D',
        ),
        'J' => array(
            'name' => 'comment',
            'alias' => 'J',
            'label' => 'Comment',
            'group' => '',
            'type'  => 'comment',

            'visible' => false,
            'max_mark' => 0,
            'comment_list' => '',
            'weight' => 0,
            'readonly' => false,
            'formula' => '',
        ),
    );
    /**
    * This is a depreciated method, please start using __construct() as this method will be removed in a future version
    *
    * @see __construct
    * @depreciated
    */
    function J_GradebookConfig(){
        self::__construct();
    }

    public function __construct(){
        parent::__construct();
    }

    public function loadConfigContent($koc_id, $config_type, $minitest) {
        $config_array = json_decode(html_entity_decode($this->content),true);
        if(empty($config_array) || $config_array == '{}' || !is_array($config_array))
            $config_array = array();

        return $this->toHtmlContent($config_array, $koc_id, $config_type, $minitest);
    }

    public function toHtmlContent($configs, $koc_id, $config_type, $minitest) {
        if(empty($koc_id) || empty($config_type)) return '';

        $koc_rs = $GLOBALS['db']->fetchArray("SELECT
            IFNULL(kc.year, '') koc_year,
            IFNULL(kc.kind_of_course, '') kind_of_course,
            IFNULL(kc.short_course_name, '') short_course_name
            FROM
            j_kindofcourse kc WHERE kc.id ='$koc_id' AND kc.deleted = 0");
        $config_name = 'gradebook_config_'.$config_type;

        //Customize minitest
        if(!empty($minitest)){
            $minitest = preg_replace('/[0-9]+/', '', $minitest);
            $config_name = 'gradebook_config_'.$minitest;
        }

        $config_default = $this->$config_name;
        if(empty($config_default) || !isset($config_default))
            $config_default = $this->gradebook_config_Overall;

        //$group          = array();
        $group_html     = "";
        $title_html     = "";
        $alias_html     = "";
        $visible_html   = "";
        $max_mark_html  = "";
        $weight_html    = "";
        $readonly_html  = "";
        $formula_html   = "";
        $comment_list   = $GLOBALS['app_list_strings']['option_comment_list'];

        foreach($config_default as $key => $defaut) {
            $config     = isset($configs[$key]) ? $configs[$key] : $defaut;
            $prex       = $defaut['name']."_";

            if($config['visible']) $class = ''; else $class = 'readonly';
            //$group[$defaut['group']] += 1;

            $_label = (!empty($config['label'])) ? ($config['label']) : ($defaut['label']);
            $_group = (!empty($config['group'])) ? ($config['group']) : ($defaut['group']);
            $group_html .= "<td class='center'><textarea class='$class label' name='{$prex}group' alias='{$key}' ".(($config['visible'] && !$config['readonly']) ? "" : "readonly").">$_group</textarea></td>";
            $title_html .= "<td class='center'><textarea class='$class label' name='{$prex}label' alias='{$key}' ".(($config['visible'] && !$config['readonly']) ? "" : "readonly").">$_label</textarea></td>";
            $alias_html .= "<td class = 'center'><b>(".$defaut['alias'].")</b></td>";
            $visible_html .= "<td class = 'center'><input type = 'checkbox' _name='{$defaut['name']}' name = '{$prex}visible' alias='{$key}' class = 'visible' config_type='{$defaut['type']}' value = '1' ".( $config['visible'] ?"checked":"")."></td>";
            if($defaut['type'] != 'comment'){
                $max_mark_html .= "<td class = 'center'><input type = 'text' name = '{$prex}max_mark' alias='{$key}' ".($config['visible'] && !$config['readonly']?"": "readonly")." value = '".$config['max_mark']."' class = 'max_mark input_mark $class'></td>";
            }else{
                $max_mark_html .= "<td class = 'center'>";
                $max_mark_html .= "<select style='width: 100px;' name='{$prex}comment_list' alias='{$key}' ".($config['visible'] && !$config['readonly']?"": "disabled").">";
                foreach($comment_list as $appList=>$commnentList){
                    $com_sel = '';
                    if($appList == $config['comment_list']) $com_sel = 'selected';
                    $max_mark_html .= "<option $com_sel value='$appList'>$commnentList</option>";
                }
                $max_mark_html .= "</select>";
            }

            $weight_html .= "<td class = 'center'>";
            if($defaut['type'] != 'comment')
                $weight_html .= "<input type = 'text' name = '{$prex}weight' alias='{$key}' ".($config['visible']?"": "readonly")." value = '".$config['weight']."' class = 'weight input_weight $class'>";
            $weight_html .= "</td>";

            $readonly_html .= "<td class = 'center'>";
            if($defaut['type'] != 'comment')
                $readonly_html .= "<input type = 'checkbox' _name='{$defaut['name']}' class='cf_readonly' name = '{$prex}readonly' alias='{$key}' ".($config['visible']?"": "disabled")." value = '1' ".($config['readonly']?"checked":"").">";
            $readonly_html .= "</td>";

            $formula_html .= "<td class='center'><input type = 'text' style='text-transform: uppercase; ".((!empty($config['formula']) || $config['readonly']) ? "" : "display:none;" )."' alias='{$key}' value = '{$config['formula']}' class = 'input_formula formula' name = '{$prex}formula' ></td>";
        }
        //        foreach($group as $label => $colspan)
        //            $group_html .=  "<td class = 'center' colspan='$colspan'><b>$label</b></td>";

        $table = "
        <input type = 'hidden' id = 'config_content_js' value = '{}'>
        <table id = 'config_content' class='table-border' width='100%'>
        <thead>
        <tr>
        <td><b>Group</b></td>
        $group_html
        </tr>
        <tr>
        <td><b>Title</b></td>
        $title_html
        </tr>
        <tr>
        <td><b>Alias</b></td>
        $alias_html
        </tr>
        </thead>
        <tbody>
        <tr>
        <td><b>Visible</b></td>
        $visible_html
        </tr>
        <tr>
        <td><b>Max mark</b></td>
        $max_mark_html
        </tr>
        <tr>
        <td><b>Weight (%)</b></td>
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
        </table>";

        return $table;
    }
}
?>