<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class logicTeachercontract {
    /*handle save day off of teacher*/
    function handleSaveTeachercontract(&$bean, $event, $arguments){
        $result= array();
        for ($i=0; $i<count($_POST['dayoff']);$i++)
            $result[]  = $_POST['dayoff'][$i];
  
        $bean->day_off = encodeMultienumValue($result);
        // handle save name
        $bean->name=$_POST['no_contract'];
        //  save tpye teacher in C_teacher
        $type=$bean->contract_type;
        $sql="UPDATE c_teachers  SET title = '".$type."' where id='".$bean->c_teachers_j_teachercontract_1c_teachers_ida."'";
        $result= $GLOBALS['db']->query($sql);

        //Update Team
        $teacher = BeanFactory::getBean('C_Teachers',$bean->c_teachers_j_teachercontract_1c_teachers_ida);
        $teamSetBean = new TeamSet();
        $teams = $teamSetBean->getTeams($teacher->team_set_id);
        foreach ($teams as $key => $value)
            $team_list[] = $key;
        if(!in_array($bean->team_id,$team_list)){
            $teacher->load_relationship('teams');
            $teacher->teams->add($bean->team_id);
        }

        $code_field = 'name';
        if(empty($bean->$code_field)){
            //Get Prefix
            $res        = $GLOBALS['db']->query("SELECT teacher_id FROM c_teachers WHERE id = '{$bean->c_teachers_j_teachercontract_1c_teachers_ida}'");
            $row        = $GLOBALS['db']->fetchByAssoc($res);
            $prefix     = $row['teacher_id'];
            $table      = $bean->table_name;
            $sep        = '-';
            $first_pad  = '000';
            $padding    = 3;
            $query      = "SELECT $code_field FROM $table WHERE ( $code_field <> '' AND $code_field IS NOT NULL) AND id != '{$bean->id}' AND (LEFT($code_field, ".strlen($prefix).") = '".$prefix."') ORDER BY RIGHT($code_field, $padding) DESC LIMIT 1";
            $result = $GLOBALS['db']->query($query);

            if($row = $GLOBALS['db']->fetchByAssoc($result))
                $last_code = $row[$code_field];
            else
                //no codes exist, generate default - PREFIX + CURRENT YEAR +  SEPARATOR + FIRST NUM
                $last_code = $prefix . $sep  . $first_pad;

            $num = substr($last_code, -$padding, $padding);
            $num++;
            $pads = $padding - strlen($num);
            $new_code = $prefix . $sep ;

            //preform the lead padding 0
            for($i=0; $i < $pads; $i++)
                $new_code .= "0";
            $new_code .= $num;
            $bean->$code_field = $new_code;
        }
    }
    ///to mau id va status Quyen.Cao
    function listViewColorTeacherContract(&$bean, $event, $arguments){
        if($_REQUEST['action']=='Popup'){

        }else{
            $bean->name ="<span class='textbg_orange'>".$bean->name ."</span>";
        }
    }
}

