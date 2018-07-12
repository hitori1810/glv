<?php 

require_once("custom/include/_helper/junior_revenue_utils.php");

switch ($_POST['type']) {
    case 'ajaxGetFromClass':
        $result = ajaxGetFromClass($_POST['student_id']);
        echo $result;
        break;
    case 'ajaxCalFromClass':
        echo ajaxCalFromClass($_POST['student_id'], $_POST['from_class_id'], $_POST['last_lesson_date'], $_POST['situation_id'] );
        break;
    case 'ajaxCalToClass':
        echo ajaxCalToClass($_POST['move_to_class_id'], $_POST['move_to_class_date'],  $_POST['move_to_class_date_end'], $_POST['student_id']  );
        break;	
    case 'ajaxUndo':
        echo ajaxUndo($_POST['situation_id'] );
        break;
}

// ----------------------------------------------------------------------------------------------------------\\

function ajaxGetFromClass($student_id){

    global $current_user, $timedate;
    // Get Class Info
    $row = get_total_revenue($student_id, "'Enrolled', 'Moving In'");
    // Get Waiting Class
    $row2 = get_waiting_class($student_id);

    $classOptions = "<select id='ju_class_name' name='ju_class_name'><option value='' start_date='-none-' end_date='-none-' class_name='-none-' json_ss=''>- Select a Class -</option>";
    for($i = 0; $i < count($row); $i++){
        $start_date 		=   $timedate->to_display_date($row[$i]['start_study'],true);
        $end_date   		=   $timedate->to_display_date($row[$i]['end_study'],true);
        $classOptions 		.= "<option value='{$row[$i]['class_id']}' total_amount='".format_number($row[$i]['total_amount_situa'])."' total_hour='".format_number($row[$i]['total_hour_situa'],2,2)."' class_type='{$row[$i]['class_type']}' start_date='$start_date' end_date='$end_date' situation_id='{$row[$i]['situation_id']}' class_name='{$row[$i]['class_name']}' json_ss='{$row[$i]['class_short_schedule']}'>{$row[$i]['class_code']}</option>";
    }
    for($i = 0; $i < count($row2); $i++){
        $classOptions         .= "<option value='{$row2[$i]['class_id']}' total_amount='".format_number($row2[$i]['total_amount'])."' total_hour='".format_number($row2[$i]['total_hour'],2,2)."' class_type='{$row2[$i]['class_type']}' situation_id='{$row2[$i]['primaryid']}' class_name='{$row2[$i]['class_name']}'>{$row2[$i]['class_code']}</option>";
    }
    $classOptions 		.= "</select>";
    return json_encode(array(
        "success" => "1",
        "html" => $classOptions,
    ));
}

function ajaxCalFromClass($student_id, $from_class_id, $last_lesson_date, $situation_id){
    global $timedate, $current_user;
    //Check Leson date
    $from_class     = BeanFactory::getBean('J_Class', $from_class_id);
    if($from_class->class_type == 'Normal Class'){
        $last_lesson_date_db     = $timedate->to_db_date($last_lesson_date,false);
        $q1 = "SELECT DISTINCT IFNULL(meetings.id,'') primaryid FROM meetings INNER JOIN j_class l1 ON meetings.ju_class_id=l1.id AND l1.deleted=0 WHERE (((DATE(CONVERT_TZ(meetings.date_start,'+00:00','+7:00')) = '{$last_lesson_date_db}') AND (l1.id='$from_class_id' ))) AND meetings.deleted=0 AND (meetings.session_status <> 'Cancelled')";
        $id_ = $GLOBALS['db']->getOne($q1);
        if(empty($id_))
            return json_encode(array(
                "success" => "0",     
                "error" => "Last lesson date not in class schedule !",     
            )); 
        $situation_list = get_total_revenue($student_id, '', $from_class->start_date, $last_lesson_date, $from_class_id, $situation_id);

        $total_hour     = 0;
        $total_amount     = 0;
        $used_hour         = 0;
        $used_amount     = 0;
        
    //Check number of studied lesson
        $dateNow = $timedate->nowDb();
        $q2 = "SELECT COUNT(DISTINCT IFNULL(meetings.id,'')) primaryid FROM meetings INNER JOIN j_class l1 ON meetings.ju_class_id=l1.id AND l1.deleted=0 WHERE (((DATE(CONVERT_TZ(meetings.date_start,'+00:00','+7:00')) >= '{$last_lesson_date_db}') AND meetings.date_start <= '{$dateNow}' AND (l1.id='$from_class_id' ))) AND meetings.deleted=0 AND (meetings.session_status <> 'Cancelled')";
        
        $closedSession = $GLOBALS['db']->getOne($q2);
        
        for($i = 0; $i < count($situation_list); $i++){
            $total_hour     += $situation_list[$i]['total_hour_situa'];
            $total_amount     += $situation_list[$i]['total_amount_situa'];
            $used_hour         += $situation_list[$i]['total_revenue_hour'];
            $used_amount     += $situation_list[$i]['total_revenue'];
        } 
    }else{
        $situation = BeanFactory::getBean('J_StudentSituations', $situation_id);
        $total_hour     = unformat_number($situation->total_hour);
        $total_amount   = unformat_number($situation->total_amount);
        $used_hour      = 0;
        $used_amount    = 0;
        $closedSession  = 0;
    }
    
    return json_encode(array(
        "success" => "1",   
        "total_hour" => format_number($total_hour,2,2),   
        "total_amount" => format_number($total_amount),   
        "used_hour" => format_number($used_hour,2,2),   
        "used_amount" => format_number($used_amount),   
        "moving_hour" => format_number($total_hour - $used_hour,2,2),   
        "moving_amount" => format_number($total_amount - $used_amount),
        "closed_session" => $closedSession,     
    ));
}

function ajaxCalToClass( $move_to_class_id, $move_to_class_date, $move_to_class_date_end , $student_id){

    global $timedate;
    //Check Moving Leson date
    $move_to_class_date_db 	= $timedate->to_db_date($move_to_class_date	,false);
    $q1 = "SELECT DISTINCT IFNULL(meetings.id,'') primaryid FROM meetings INNER JOIN j_class l1 ON meetings.ju_class_id=l1.id AND l1.deleted=0 WHERE (((DATE(CONVERT_TZ(meetings.date_start,'+00:00','+7:00')) = '{$move_to_class_date_db}') AND (l1.id='$move_to_class_id' ))) AND meetings.deleted=0 AND (meetings.session_status <> 'Cancelled')";
    $id_ = $GLOBALS['db']->getOne($q1);
    if(empty($id_))
        return json_encode(array(
            "success" => "0",     
            "error" => "Move To Class Date not in class schedule !",     
        ));
    //Check Moving Leson date end
    $move_to_class_date_end_db 	= $timedate->to_db_date($move_to_class_date_end	,false);
    $q1 = "SELECT DISTINCT IFNULL(meetings.id,'') primaryid FROM meetings INNER JOIN j_class l1 ON meetings.ju_class_id=l1.id AND l1.deleted=0 WHERE (((DATE(CONVERT_TZ(meetings.date_start,'+00:00','+7:00')) = '{$move_to_class_date_end_db}') AND (l1.id='$move_to_class_id' ))) AND meetings.deleted=0 AND (meetings.session_status <> 'Cancelled')";
    $id_ = $GLOBALS['db']->getOne($q1);
    if(empty($id_))
        return json_encode(array(
            "success" => "3",     
            "error" => "Finish Moving Date not in class schedule !",     
        ));
    //Check Existing in situation
    $res = is_exist_in_class($student_id, $move_to_class_date, $move_to_class_date_end, $move_to_class_id);
    if($res)
        return json_encode(array(
            "success" => "4",     
            "error" => "Student already exist in the class !",     
        ));


    $move_to_row 	= get_list_lesson_by_class($move_to_class_id, $move_to_class_date, '');
    //đề xuất ngày end date
    $end_date = '';
    $remaining_hour = 0;
    $target_hour = unformat_number($_POST['moving_hour']);
    for($i = 0; $i < count($move_to_row); $i++){
        $remaining_hour += $move_to_row[$i]['delivery_hour'];
        $class_hour = $move_to_row[$i]['class_hour'];
        $end_date = $timedate->to_display_date($move_to_row[$i]['date_end']);
        if($remaining_hour == $target_hour)
            break;

    }
    $moving_time = $move_to_class_date.' - '.$end_date;
    
    //Check number of studied lesson
        $dateNow = $timedate->nowDb();
        $q2 = "SELECT COUNT(DISTINCT IFNULL(meetings.id,'')) primaryid FROM meetings INNER JOIN j_class l1 ON meetings.ju_class_id=l1.id AND l1.deleted=0 WHERE (((DATE(CONVERT_TZ(meetings.date_start,'+00:00','+7:00')) >= '{$move_to_class_date_db}') AND meetings.date_start <= '{$dateNow}' AND meetings.date_start <= '{$move_to_class_date_end_db}' AND (l1.id='$move_to_class_id' ))) AND meetings.deleted=0 AND (meetings.session_status <> 'Cancelled')";
        
        $closedSession = $GLOBALS['db']->getOne($q2);
    
    return json_encode(array(
        "success" => "1",   
        "total_hour" 	=> format_number($class_hour,2,2), 
        "studied_hour"=> format_number($class_hour - $remaining_hour,2,2),   
        "remaining_hour"=> format_number($remaining_hour,2,2),   
        "end_date"=> $end_date,   
        "moving_time"=> $moving_time,
        "closed_session" => $closedSession,   
    ));
}

function ajaxUndo($situation_id){
    require_once("custom/include/_helper/junior_revenue_utils.php");
    require_once("custom/include/_helper/junior_class_utils.php");
    $situation = BeanFactory::getBean('J_StudentSituations', $situation_id);
    $undo_obj = json_decode(html_entity_decode($situation->json_moving), true);

    //Update Related situation
    $related_situation = BeanFactory::getBean('J_StudentSituations', $undo_obj['related_situation']['id']);
    $related_situation->end_study      = $undo_obj['related_situation']['end_study'];
    $related_situation->total_hour     = $undo_obj['related_situation']['total_hour'];
    $related_situation->total_amount   = $undo_obj['related_situation']['total_amount'];
    $related_situation->save();

    //Add học viên vào lớp cũ
    for($i = 0; $i < count($undo_obj['remove_session']); $i++)
        addJunToSession($related_situation->id , $undo_obj['remove_session'][$i] );	

    //Xóa học viên vào lớp mới
    for($i = 0; $i < count($undo_obj['add_session']); $i++)
        removeJunFromSession($undo_obj['moving_in']['id'], $undo_obj['add_session'][$i] );

    //Remove Moving Out
    $GLOBALS['db']->query("DELETE FROM j_studentsituations WHERE id = '{$undo_obj['moving_out']['id']}'");

    //Remove Moving In
    $GLOBALS['db']->query("DELETE FROM j_studentsituations WHERE id = '{$undo_obj['moving_in']['id']}'");


    return json_encode(array(
        "success" => "1",   
        "student_id" 	=> $related_situation->student_id, 
    ));	

}
