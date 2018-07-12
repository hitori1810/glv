<?php
//---------------------------Save PT Demo From Module Leads By Quyen
$meeting_id=$_POST['meeting_id'];
$lead_id=$_POST['lead_id'];
$lead_name=$_POST['lead_name'];
$parent = $_POST['parent'];
switch ($_POST['type']) {
    case 'ajaxSavePT':
        $result = ajaxSavePT($meeting_id,$lead_id, $lead_name,$parent);
        break;
    case 'ajaxRemovePT':
        $result = ajaxRemovePT($_POST['pt_id']);
        break;
    case 'ajaxSaveDemo':
        $result = ajaxSaveDemo($meeting_id,$lead_id, $lead_name, $parent);
        break;
    case 'ajaxRemoveDemo':
        $result = ajaxRemovePT($_POST['pt_id']);
        break;
}
echo $result;
die;
// -----------------------------------------------------------------------------------------------------\\
function checkStudentExitsInSchedule($lead_id, $meeting_id, $parent) {
    $sql = "SELECT count(pt.id)
    FROM j_ptresult pt
    INNER JOIN meetings_j_ptresult_1_c l1_1 ON pt.id = l1_1.meetings_j_ptresult_1j_ptresult_idb AND l1_1.deleted = 0
    INNER JOIN meetings l1 ON l1.id = l1_1.meetings_j_ptresult_1meetings_ida AND l1.deleted = 0
    WHERE pt.student_id = '$student_id' AND l1.id = '$meeting_id' AND pt.parent = '$parent' AND pt.deleted = 0";
    return $GLOBALS['db']->getOne($sql)?true:false;
}
function ajaxSavePT($meeting_id, $lead_id, $lead_name,  $parent){
    global $timedate;
    //sql get order max and time range
    if(checkStudentExitsInSchedule($lead_id, $meeting_id, $parent)) {
        return false;
    }
    $sql_get_order="
    SELECT MAX(j_ptresult.pt_order)
    FROM   j_ptresult
    INNER JOIN meetings_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.meetings_j_ptresult_1j_ptresult_idb AND l1_1.deleted = 0
    INNER JOIN meetings l1 ON l1.id = l1_1.meetings_j_ptresult_1meetings_ida AND l1.deleted = 0
    WHERE   l1.id = '$meeting_id' AND j_ptresult.deleted = 0 " ;
    $order_max =  $GLOBALS['db']->getOne($sql_get_order) + 0;
    if($order_max==0){
        $sql_get_first_time="SELECT date_start, name, team_id, team_set_id
        FROM meetings WHERE id='$meeting_id' AND deleted = 0 ";
        $row_time = $GLOBALS['db']->fetchOne($sql_get_first_time);

        $result = new J_PTResult();
        $result->time_start = $row_time['date_start'];
        $result->time_end    = date('Y-m-d H:i:s',strtotime($row_time['date_start']. " +10 minutes"));
    }else{
        ///sql get last row pt
        $sql_get_pt="
        SELECT DISTINCT
        Ifnull(l1.id, '')         l1_id,
        l1.time_range             l1_time_range,
        l1.name l1_name,
        Ifnull(j_ptresult.id, '') primaryid,
        j_ptresult.pt_order       j_ptresult_pt_order,
        j_ptresult.time_start     j_ptresult_time_start,
        j_ptresult.time_end       j_ptresult_time_end,
        l1.team_id,
        l1.team_set_id
        FROM   j_ptresult INNER JOIN meetings_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.meetings_j_ptresult_1j_ptresult_idb AND l1_1.deleted = 0
        INNER JOIN meetings l1 ON l1.id = l1_1.meetings_j_ptresult_1meetings_ida AND l1.deleted = 0
        WHERE l1.id = '$meeting_id' AND j_ptresult.deleted = 0 AND  j_ptresult.pt_order = $order_max " ;
        $row_pt = $GLOBALS['db']->fetchOne($sql_get_pt);

        $result=new J_PTResult();
        $time_prev= $row_pt['j_ptresult_time_end'];
        $time_range= $row_pt['l1_time_range'] + 0;
        $result->time_start = $time_prev;
        $result->time_end   = date('Y-m-d H:i:s',strtotime("$time_prev +$time_range minutes"));


    }
    $result->pt_order = $order_max + 1;
    $result->parent = $parent;
    $result->attended = 0;
    $result->type_result="Placement Test";
    $result->team_id = $row_pt['team_id'];
    $result->team_set_id = $row_pt['team_set_id'];
    $result->assigned_user_id = $GLOBALS['current_user']->id;
    $result->name = $row_pt['l1_name'].' - '.$lead_name;
    $result->student_id   =  $lead_id;

    $result->meetings_j_ptresult_1meetings_ida = $meeting_id;
    $result->leads_j_ptresult_1leads_ida = $lead_id;
    $result->save();

    //update status leads
    $rela_lead = BeanFactory::getBean('Leads',$lead_id);
    if($rela_lead->status != 'Converted'){
        $rela_lead->status = 'PT/Demo';
        $rela_lead->save();
    }
    return true;
}

function ajaxRemovePT($pt_id){
    $thisResult = new J_PTResult();
    $thisResult->retrieve($pt_id);
    $thisSessionId = $thisResult->meetings_j_ptresult_1meetings_ida;
    if($thisResult->id) {
        $sql = "SELECT pt.*
        FROM j_ptresult pt
        INNER JOIN meetings_j_ptresult_1_c mpt ON mpt.meetings_j_ptresult_1j_ptresult_idb = pt.id AND mpt.deleted = 0
        INNER JOIN meetings m ON m.id= mpt.meetings_j_ptresult_1meetings_ida AND m.deleted = 0
        WHERE pt.deleted = 0 AND m.id = '$thisSessionId'
        ORDER BY pt.pt_order";
        $ptresults = $GLOBALS['db']->fetchArray($sql);
        $ischange = false;
        //lap lai thu tu cua cac pt cua meeting do truoc khi xoa pt yeu cau
        for($i = 0; $i < count($ptresults); $i++) {
            $ptresult = $ptresults[$i] ;
            if($ptresult['pt_order'] > $thisResult->pt_order
            && $thisResult->pt_order != '' && $ptresult['pt_order'] != '') {
                $ischange = true;
            }
            if($ischange) {
                $nextPT = new J_PTResult();
                $nextPT->retrieve($ptresult['id']);
                $nextPT->pt_order = $ptresults[$i-1]['pt_order'];
                $nextPT->time_start = $ptresults[$i-1]['time_start'];
                $nextPT->time_end = $ptresults[$i-1]['time_end'];
                $nextPT->save();
            }
        }
        $thisResult->mark_deleted($thisResult->id);
    }
    return true;
}

function ajaxSaveDemo($meeting_id,$lead_id, $lead_name, $parent){
    if(checkStudentExitsInSchedule($lead_id, $meeting_id, $parent)) {
        return false;
    }
    $sql_get_first_time="SELECT date_start, name, team_id, team_set_id
    FROM meetings WHERE id='$meeting_id' AND deleted = 0 ";
    $row_time = $GLOBALS['db']->fetchOne($sql_get_first_time);

    $result=new J_PTResult();
    $result->parent = $parent;
    $result->type_result= "Demo";
    $result->name = $row_time['name'].' - '.$lead_name;
    $result->team_id = $row_time['team_id'];
    $result->team_set_id = $row_time['team_set_id'];
    $result->assigned_user_id = $GLOBALS['current_user']->id;
    $result->student_id   =  $lead_id;
    $result->attended = 0;

    $result->meetings_j_ptresult_1meetings_ida = $meeting_id;
    $result->leads_j_ptresult_1leads_ida = $lead_id;
    $result->save();

    //update status leads
    $rela_lead = BeanFactory::getBean('Leads',$lead_id);
    if($rela_lead->status != 'Converted'){
        $rela_lead->status = 'PT/Demo';
        $rela_lead->save();
    }
    return true;
}

//---------------------------End Save PT Demo From Module Leads By Quyen

?>
