<?php
require_once("custom/include/_helper/junior_schedule.php");
switch ($_POST['type']) {
    case 'ajaxCheckConvertedLead':
        echo ajaxCheckConvertedLead($_POST['lead_id']);
        break;
    case 'loadTeacherOptions':
        echo loadTeacherOptions($_POST);
        break;
    case 'loadRoomOptions':
        echo loadRoomOptions($_POST);
        break;
    case 'ajaxAddJuniorToSession':
        echo ajaxAddJuniorToSession($_POST['situation_id'], $_POST['ss_id'], $_POST['class_id']);
        break;
    case 'ajaxRemoveJuniorToSession':
        echo ajaxRemoveJuniorToSession($_POST['student_id'], $_POST['ss_id'], $_POST['class_id']);
        break;
    case 'ajaxMovePtDemo':
        echo ajaxMovePtDemo($_POST['student_ids'], $_POST['ss_id']);
        break;
    default:
        break;
}
die;
function ajaxCheckConvertedLead($leadId){
    $leadBean = BeanFactory::getBean("Leads", $leadId);
    return json_encode(array(
        "success" => "1",
        "contact_id" => $leadBean->contact_id,
    ));
}

function loadTeacherOptions($data){
    $start_time = $GLOBALS['timedate']->to_db($data['date_start']);
    $end_time = $GLOBALS['timedate']->to_db($data['date_end']);

    if ($data['session_id'] != '') {
        $sql = " SELECT team.id, team.team_type
        FROM teams team
        INNER JOIN meetings ss ON ss.team_id = team.id AND team.deleted = 0
        AND ss.id = '{$data['session_id']}' AND ss.deleted = 0 ";
    } else {
        $sql = "SELECT id, team_type FROM teams WHERE id = '{$GLOBALS['current_user']->team_id}'";
    }
    //  echo $sql;
    $team = $GLOBALS['db']->fetchOne($sql);

    $teacher_list = getTeacherOfCenter($team['id']);
//
//    foreach($teacher_list as $key => $val) {
//        if(!checkTeacherInDateime($key, $start_time, $end_time, $data['session_id'])) {
//            unset($teacher_list[$key]);
//            continue;
//        }
//        if($team['team_type'] == 'Junior' && !checkTeacherWorking($key, $start_time, $end_time)) {
//            unset($teacher_list[$key]);
//            continue;
//        }
//        if(checkTeacherHolidays($key, array(array('date_start'=> date('Y-m-d H:i:s', strtotime('-7 hours '.$start_time)))))) {
//            unset($teacher_list[$key]);
//            continue;
//        }
//    }
    $teacher_list = array_merge(array('' => '--None--'),$teacher_list);
    return get_select_options_with_id($teacher_list,$data['teacher_id']);
}
function loadRoomOptions($data) {
    $start_time = $GLOBALS['timedate']->to_db($data['date_start']);
    $end_time = $GLOBALS['timedate']->to_db($data['date_end']);

    if ($data['session_id'] != '') {
        $sql = " SELECT team.id, team.team_type
        FROM teams team
        INNER JOIN meetings ss ON ss.team_id = team.id AND team.deleted = 0
        AND ss.id = '{$data['session_id']}' AND ss.deleted = 0 ";
    } else {
        $sql = "SELECT id, team_type FROM teams WHERE id = '{$GLOBALS['current_user']->team_id}'";
    }

    $team = $GLOBALS['db']->fetchOne($sql);

    $room_list = getRoomOfCenter($team['id']);
//    foreach($room_list as $key => $val) {
//        if(!checkRoomInDateime($key, $start_time, $end_time, $data['session_id'])) {
//            unset($room_list[$key]);
//        }
//    }
    $room_list = array_merge(array('' => '--None--'),$room_list);
    echo get_select_options_with_id($room_list,$data['room_id']);
}


function ajaxAddJuniorToSession($situation_id, $ss_id, $class_id){
    require_once("custom/include/_helper/junior_revenue_utils.php");
    require_once("custom/include/_helper/junior_class_utils.php");

    $stu = BeanFactory::getBean('J_StudentSituations', $situation_id);

    $os = array("OutStanding", "Settle", "Enrolled", "Moving In");
    if (!in_array($stu->type, $os)) {
        return json_encode(array(
            "success" => "0",
            "error" => "An Error Occurred, Please Choose another Situation!",
        ));
    }

    if($stu->ju_class_id != $class_id)
        return json_encode(array(
            "success" => "0",
            "error" => "This is not register of this class. You can not add Student to this class!",
        ));

    //Add Student to Class
    addJunToSession($situation_id, $ss_id);

    //Update Situation
    $ses = get_list_lesson_by_situation($class_id, $situation_id, '', '', 'INNER');
    $first = reset($ses);
    $date_first = date('Y-m-d',strtotime("+7 hours ".$first['date_start']));

    $last = end($ses);
    $date_last = date('Y-m-d',strtotime("+7 hours ".$last['date_start']));

    if(!empty($date_last) && !empty($date_first)  ){
        $q3 = "UPDATE j_studentsituations SET start_study = '$date_first', end_study = '$date_last' WHERE id='$situation_id'";
        $GLOBALS['db']->query($q3);
    }
    return json_encode(array(
        "success" => "1",
        "error" => "Student Added Successfully",
    ));

}

function ajaxRemoveJuniorToSession($student_id, $ss_id, $class_id){
    require_once("custom/include/_helper/junior_revenue_utils.php");
    require_once("custom/include/_helper/junior_class_utils.php");

    //Add Student to Class
    $q1 = "SELECT situation_id
    FROM meetings_contacts
    WHERE meeting_id = '$ss_id'
    AND contact_id = '$student_id'
    AND deleted = 0";
    $situation_id = $GLOBALS['db']->getOne($q1);
    if(!empty($situation_id)){
        removeJunFromSession($situation_id, $ss_id);

        //Update Situation
        $ses = get_list_lesson_by_situation($class_id, $situation_id, '', '', 'INNER');
        $first = reset($ses);
        $date_first = date('Y-m-d',strtotime("+7 hours ".$first['date_start']));

        $last = end($ses);
        $date_last = date('Y-m-d',strtotime("+7 hours ".$last['date_start']));

        if(!empty($date_last) && !empty($date_first)  ){
            $q3 = "UPDATE j_studentsituations SET start_study = '$date_first', end_study = '$date_last' WHERE id='$situation_id'";
            $GLOBALS['db']->query($q3);
        }
        return json_encode(array(
            "success" => "1",
            "error" => "Student Removed Successfully",
        ));
    }else{
        return json_encode(array(
            "success" => "0",
            "error" => "An Error Occurred, Please, Try again!",
        ));
    }


}
?>
