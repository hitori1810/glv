<?php
function getGradebook($class_id, $gradebook_id = '') {
    return array('html' => getGradebookSelectOptions($class_id, $gradebook_id));
}

function getGradebookDetail($gradebook_id, $reloadconfig) {
    $gradebook = BeanFactory::getBean('J_Gradebook',$gradebook_id);
    return array(
        'html'          => $gradebook->loadGradeContent($reloadconfig + 0),
        'grade_config'  => $gradebook->config,
        'weight'        => $gradebook->weight,
    );
}
function getGradebookSelectOptions($class_id, $gradebook_id = '') {
    $class = new J_Class();
    $class->retrieve($class_id);
    $class->load_relationship('j_class_j_gradebook_1');
    $gradebooks = $class->j_class_j_gradebook_1->getBeans();
    $list = array();
    $select_html = "";
    foreach($gradebooks as $id => $bean) {
        if($id == $gradebook_id) $selected = "selected";
        else  $selected = "";
        $select_html .= "<option value = '{$id}' label = '{$bean->name}' $selected
        teacher_id = '{$bean->c_teachers_j_gradebook_1c_teachers_ida}'
        teacher_name = '{$bean->c_teachers_j_gradebook_1_name}'>
        {$bean->name}</option>";
        $list[$id] = $bean->name;
    }
    return $select_html;//get_select_options_with_id($list, $gradebook_id);
}

function saveInputMark($_data) {
    global $timedate, $current_user;
    $gradebook = new J_Gradebook();
    $gradebook->retrieve($_data['gradebook_id']);

    if(empty($gradebook->id)){
        return json_encode(array(
            "success" => "0",
            "errorLabel" => "LBL_GRADEBOOK_NOT_FOUND",
        ));
    }

    $gradebook->grade_config = $_data['grade_config'];
    $gradebook->weight = $_data['weight'];
    $gradebook->c_teachers_j_gradebook_1c_teachers_ida = $_data['c_teachers_j_gradebook_1c_teachers_ida'];
    $gradebook->c_teachers_j_gradebook_1_name = $_data['c_teachers_j_gradebook_1_name'];
    $gradebook->date_input = $timedate->nowDate();  // request of mrHung
    $gradebook->save();

    $keys = json_decode(html_entity_decode($_data['key']),true);
    //danh dau xoa cac detail cu
    $dateModifield = $timedate->nowDb();
    $GLOBALS['db']->query("UPDATE j_gradebookdetail
        SET deleted = 1,
        date_modified = '{$dateModifield}',
        description = 'delete by function saveInputMark.',
        modified_user_id = '{$current_user->id}'
        WHERE gradebook_id = '{$gradebook->id}'
        AND deleted <> 1");
    $GLOBALS['db']->query("UPDATE j_gradebook
        SET date_modified = '$dateModifield',
        modified_user_id = '{$current_user->id}'
        WHERE id = '{$gradebook->id}'");

    $countSaveErrors = 0; //Add by Tung Bui - 03.10.2016 - Check log save gradebook detail

    $gradebook->_constructDefault();
    $config_array = $gradebook->config;
    for($i = 0; $i < count($_data['student_id']); $i++){
        $detail                 = new J_GradebookDetail();
        $detail->student_id     = $_data['student_id'][$i];
        $detail->gradebook_id   = $gradebook->id;
        $detail->team_id        = $gradebook->team_id;
        $detail->team_set_id    = $gradebook->team_set_id;

        //Set value mark
        $content = array();
        foreach($keys as $key){
            if($config_array[$key]['type'] != 'comment'){
                $_mark                  = unformat_number($_data[$key][$i]);
                if($_mark <= 0) $_mark  = 0;

                $content[$key]          = $_mark;
                $detail->final_result   = format_number($_mark,1,1);
            }else{
                $comment_name = $config_array[$key]['name'];
                $content[$comment_name.'_key'] = json_decode(html_entity_decode($_data['key_teacher_'.$comment_name][$i]),true);
                $content[$comment_name.'_comment_label'] = $_data['value_teacher_'.$comment_name][$i];
            }
        }
        $detail->content = json_encode($content, JSON_UNESCAPED_UNICODE);
        $detail->j_class_id = $gradebook->j_class_j_gradebook_1j_class_ida;
        $detail->teacher_id = $gradebook->c_teachers_j_gradebook_1c_teachers_ida;
        $detail->date_input = $gradebook->date_input;

        //Set level
        $cerArr = calLevelCertificate($detail->final_result);
        $detail->certificate_type = $cerArr['type'];
        $detail->certificate_level= $cerArr['level'];

        try {
            $detail->save();
        }catch(Exception $e){
            $countSaveErrors++;
        }

    }

    //Add by Tung Bui - 03.10.2016 - Check log save gradebook detail
    if($countSaveErrors == 0){
        return json_encode(array(
            "success" => "1",
        ));
    }else{
        return json_encode(array(
            "success" => "0",
            "errorLabel" => "LBL_SAVE_INPUT_GRADEBOOK_UNSUCCESSFULL",
        ));
    }
}

function showConfig($gradebook_id) {
    $gradebook = BeanFactory::getBean('J_Gradebook',$gradebook_id);
    $gradebook->_constructDefault(false);
    return json_encode(array(
        'html' =>  $gradebook->getConfigHTML($gradebook->config),
    ));
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

//add by Lam Hai 22/7/2016
function checkDuplicateTest($gradebook_id, $class_id, $type, $minitest) {
    $ext_minitest = "";
    if(!empty($minitest))
        $ext_minitest = "AND j_gradebook.minitest = '$minitest'";

    $sql = "SELECT count(j_gradebook.id)
    FROM j_gradebook
    INNER JOIN j_class_j_gradebook_1_c cg ON j_gradebook.id = cg.j_class_j_gradebook_1j_gradebook_idb AND cg.deleted = 0
    WHERE j_gradebook.deleted = 0
    AND cg.j_class_j_gradebook_1j_class_ida = '$class_id'
    AND j_gradebook.type = '$type'
    $ext_minitest
    AND j_gradebook.id != '$gradebook_id'";
    $count = $GLOBALS['db']->getOne($sql);
    if($count > 0)
        return true;

    return false;
}

function loadAttendance(){
    if(empty($_POST['class_id']) || empty($_POST['student_list']))
        return json_encode(array(
            'success' => '0',
        ));

    $q2 = "SELECT DISTINCT
    IFNULL(l2.id, '') student_id,
    SUM(IFNULL(j_studentsituations.total_hour, 0)) total_hour
    FROM
    j_studentsituations
    INNER JOIN
    j_class l1 ON j_studentsituations.ju_class_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    contacts l2 ON j_studentsituations.student_id = l2.id
    AND l2.deleted = 0 AND l2.id IN ('".implode("','",$_POST['student_list'])."')
    WHERE
    (((l1.id = '{$_POST['class_id']}')))
    AND j_studentsituations.deleted = 0
    GROUP BY l2.id";
    $rs2 = $GLOBALS['db']->query($q2);
    while($row2 = $GLOBALS['db']->fetchByAssoc($rs2))
        $arrAtt[$row2['student_id']]['total_hour'] = $row2['total_hour'];

    $q1 = "SELECT DISTINCT
    IFNULL(l3.id, '') student_id,
    SUM(absent_for_hour) total_absent
    FROM
    c_attendance
    INNER JOIN
    meetings l1 ON c_attendance.meeting_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    j_class l2 ON l1.ju_class_id = l2.id
    AND l2.deleted = 0
    INNER JOIN
    contacts l3 ON c_attendance.student_id = l3.id
    AND l3.deleted = 0 AND l3.id IN ('".implode("','",$_POST['student_list'])."')
    WHERE
    (((l2.id = '{$_POST['class_id']}') AND ((c_attendance.attended LIKE 'on' OR c_attendance.attended = '1'))))
    AND c_attendance.deleted = 0
    GROUP BY l3.id , l3.full_student_name";
    $rs = $GLOBALS['db']->query($q1);
    while($row1 = $GLOBALS['db']->fetchByAssoc($rs))
        $arrAtt[$row1['student_id']]['total_absent']    = $row1['total_absent'];

    foreach($_POST['student_list'] as $student_id)
        $arrAtt[$student_id]['attendance_rate'] = format_number(($arrAtt[$student_id]['total_absent'] / $arrAtt[$student_id]['total_hour']) * 100,1,1);


    return json_encode(array(
        'success'   => '1',
        'arrAtt'    => $arrAtt,
    ));
}

function loadHomework(){
    if(empty($_POST['class_id']) || empty($_POST['student_list']))
        return json_encode(array(
            'success' => '0',
        ));
    $arrAtt = array();
    $q2 = "SELECT DISTINCT
    IFNULL(l2.id, '') student_id,
    COUNT(DISTINCT l3.id) count_session
    FROM
    j_class
    INNER JOIN
    j_studentsituations l1 ON j_class.id = l1.ju_class_id
    AND l1.deleted = 0
    INNER JOIN
    contacts l2 ON l1.student_id = l2.id AND l2.deleted = 0 AND l2.id IN ('".implode("','",$_POST['student_list'])."')
    INNER JOIN
    meetings_contacts l3_1 ON l1.id = l3_1.situation_id
    AND l3_1.deleted = 0
    INNER JOIN
    meetings l3 ON l3.id = l3_1.meeting_id
    AND l3.deleted = 0
    WHERE
    (((j_class.id = '{$_POST['class_id']}')))
    AND j_class.deleted = 0
    GROUP BY l2.id";
    $rs2 = $GLOBALS['db']->query($q2);
    while($row2 = $GLOBALS['db']->fetchByAssoc($rs2))
        $arrAtt[$row2['student_id']]['count_session'] = $row2['count_session'];

    $q1 = "SELECT DISTINCT
    IFNULL(l3.id, '') student_id,
    COUNT(DISTINCT c_attendance.id) count_homework
    FROM
    c_attendance
    INNER JOIN
    meetings l1 ON c_attendance.meeting_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    j_class l2 ON l1.ju_class_id = l2.id
    AND l2.deleted = 0
    INNER JOIN
    contacts l3 ON c_attendance.student_id = l3.id
    AND l3.deleted = 0 AND l3.id IN ('".implode("','",$_POST['student_list'])."')
    WHERE
    (((l2.id = '{$_POST['class_id']}') AND ((c_attendance.homework LIKE 'on' OR c_attendance.homework = '1'))))
    AND c_attendance.deleted = 0
    GROUP BY l3.id , l3.full_student_name";
    $rs = $GLOBALS['db']->query($q1);
    while($row1 = $GLOBALS['db']->fetchByAssoc($rs))
        $arrAtt[$row1['student_id']]['count_homework']    = $row1['count_homework'];

    foreach($_POST['student_list'] as $student_id)
        $arrAtt[$student_id]['homework_rate'] = format_number(($arrAtt[$student_id]['count_homework'] / $arrAtt[$student_id]['count_session']) * 100,1,1);


    return json_encode(array(
        'success'   => '1',
        'arrAtt'    => $arrAtt,
    ));
}

function loadProject(){
    if(empty($_POST['class_id']) || empty($_POST['student_list']) || empty($_POST['gradebook_id']))
        return json_encode(array(
            'success' => '0',
        ));
    $gradebook = BeanFactory::getBean('J_Gradebook', $_POST['gradebook_id']);
    $gradebook->_constructDefault(false);
    $_col = $gradebook->config[$_POST['alias']];
    //Get col
    $gradebook_col = new J_Gradebook();
    $gradebook_col->retrieve_by_string_fields(array('name' => $gradebook->name.'-'.$_col['name']));
    $gradebook_col->_constructDefault(false);
    $total_alias = 'I';
    foreach($gradebook_col->config as $key => $param){
        if(!empty($param['formula']))
            $total_alias = $param['alias'];
    }
    $arrAtt = array();

    foreach($_POST['student_list'] as $student_id){
        $_mark = $gradebook_col->gradebookDetail[$student_id][$total_alias];
        if($_col['type'] == 'score') $_mark = $_mark/10;
        $arrAtt[$student_id]['rate'] = format_number($_mark,1,1);
    }

    return json_encode(array(
        'success'   => '1',
        'arrAtt'    => $arrAtt,
    ));
}
?>
