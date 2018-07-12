<?php
/*funcion generate KOC
* ham dung de tao ra KOC cho Junior
*/
function generateKOC(){
    global $current_user;
    $sql_generate = "
    SELECT DISTINCT
    IFNULL(j_kindofcourse.id, '') primaryid,
    IFNULL(j_kindofcourse.name, '') name,
    IFNULL(j_kindofcourse.kind_of_course, '') kind_of_course,
    IFNULL(j_kindofcourse.content, '') content,
    j_kindofcourse.date_entered j_kindofcourse_date_entered
    FROM j_kindofcourse
    WHERE j_kindofcourse.deleted = 0  AND status = 'Active'
    AND
    ((j_kindofcourse.team_set_id IN (SELECT
    tst.team_set_id
    FROM
    team_sets_teams tst
    INNER JOIN
    team_memberships team_memberships ON tst.team_id = team_memberships.team_id
    AND team_memberships.user_id = '{$current_user->id}'
    AND team_memberships.deleted = 0)))
    ORDER BY CASE
    WHEN j_kindofcourse.kind_of_course = 'Academic' THEN 1
    WHEN j_kindofcourse.kind_of_course = 'TOEIC' THEN 2
    WHEN j_kindofcourse.kind_of_course = 'IETLS' THEN 3
    WHEN j_kindofcourse.kind_of_course = 'CERF' THEN 4
    ELSE 6
    END ASC, j_kindofcourse.name
    ";
    return $GLOBALS['db']->fetchArray($sql_generate);
}

/**
* Thêm Học viên vào các lớp
* Tạo Situation của học viên trong các lớp
* Đưa vào Start Study - End Study tự động thay đổi Start và End theo lịch lớp
*
*/
function addToClass($bean_payment, $class_list){
    global $timedate;
    require_once('custom/include/_helper/junior_revenue_utils.php');

    $student_id = $bean_payment->contacts_j_payment_1contacts_ida;
    $student    = BeanFactory::getBean('Contacts',$student_id);

    $payment_id = $bean_payment->id;
    $unit_price   = ($bean_payment->payment_amount + $bean_payment->deposit_amount + $bean_payment->paid_amount) / ($bean_payment->total_hours + $bean_payment->paid_hours);

    $classInfoArray = json_decode(html_entity_decode($bean_payment->content),true);

    foreach ($classInfoArray as $class_id => $classInfo){
        $class = BeanFactory::getBean('J_Class' , $class_id);
        //Delete Waiting class hoặc Demo trong lớp trước khi Enroll
        $q1 = "SELECT DISTINCT IFNULL(id, '') situation_id
        FROM j_studentsituations
        WHERE (type IN ('Demo' , 'Waiting Class')) AND (student_id = '$student_id') AND (ju_class_id = '$class_id') AND deleted = 0";
        $rs1 = $GLOBALS['db']->query($q1);
        while($row1= $GLOBALS['db']->fetchByAssoc($rs1)){
            removeJunFromSession($row1['situation_id']);
            $GLOBALS['db']->query("UPDATE j_studentsituations SET deleted=1 WHERE id='{$row1['situation_id']}'");
        }


        if($class->class_type == 'Normal Class'){
            $enrollPart = splitEnroll($bean_payment);
            //Xóa Situation Outstanding
            $firstPart = reset($enrollPart);
            if($firstPart['OutStanding'] == '1'){
                $GLOBALS['db']->query("UPDATE j_studentsituations SET deleted='1' WHERE id='{$firstPart['situation_id']}'");
                //Xóa các quan hệ Outstanding vs Session
                removeJunFromSession($firstPart['situation_id']);
            }
            //Update Student Status
            if($bean_payment->start_study >= date('Y-m-d')){
                $u1 = "UPDATE contacts SET contact_status = 'In Progress' WHERE id = '$student_id'";
                $GLOBALS['db']->query($u1);
            }
            // Chạy từng đoạn để tạo situation hay settle tương ứng
            foreach ($enrollPart as $key => $value) {
                if ($value["type"] == "Settle"){
                    //Tạo số giờ Settle hiện tại
                    if($value['total_hour'] > 0){
                        //Caculate start_hour
                        $dis_start  = $timedate->to_display_date($value['start_study'],false);
                        $dis_end    = $timedate->to_display_date($value['end_study'],false);
                        $sss        = get_list_lesson_by_class($class_id, $dis_start, $dis_end);
                        $first   = reset($sss);
                        $start_hour = format_number($first['till_hour'] - $first['delivery_hour'],2,2);


                        $situ                   = new J_StudentSituations();
                        $situ->name             = $student->full_student_name;
                        $situ->student_type     = 'Student';
                        $situ->type             = 'Settle';
                        $situ->total_hour       = format_number($value['total_hour'],2,2);
                        $situ->total_amount     = format_number($value['total_hour'] * $unit_price);
                        $situ->start_hour       = $start_hour;
                        $situ->start_study      = $value['start_study'];
                        $situ->end_study        = $value['end_study'];
                        $situ->student_id       = $student_id;
                        $situ->ju_class_id      = $class_id;
                        $situ->payment_id       = $bean_payment->id;

                        $situ->settle_from_outstanding_id      = $value['situation_id'];  //Quan hệ với Outstanding để Undo khi cần thiết

                        $situ->assigned_user_id = $bean_payment->assigned_user_id;
                        $situ->team_id          = $bean_payment->team_id;
                        $situ->team_set_id      = $bean_payment->team_id;
                        $situ->save();

                        //Add học viên vào siation Settle
                        for($i = 0; $i < count($sss); $i++)
                            addJunToSession($situ->id , $sss[$i]['primaryid'] );
                    }
                }
                if ($value['type'] == 'Enrolled' || $value['type'] == 'Enrolled_After_Settle'){
                    //Kiểm tra số buổi học còn lại
                    $dis_start  = $timedate->to_display_date($value['start_study'],false);
                    if($value['type'] == 'Enrolled_After_Settle')
                        $dis_start  = $timedate->to_display_date(date('Y-m-d',strtotime("+1 day ".$value['start_study'])),false);

                    $dis_end    = $timedate->to_display_date($value['end_study'],false);
                    $sss = get_list_lesson_by_class($class_id, $dis_start, $dis_end);

                    $total_hour_unfm = 0;
                    for($i = 0; $i < count($sss); $i++)
                        $total_hour_unfm += $sss[$i]['delivery_hour'];

                    $first = reset($sss);
                    $date_first = date('Y-m-d',strtotime("+7 hours ".$first['date_start']));

                    $last = end($sss);
                    $date_last = date('Y-m-d',strtotime("+7 hours ".$last['date_start']));

                    $start_hour = format_number($first['till_hour'] - $first['delivery_hour'],2,2);

                    if($total_hour_unfm > 0){
                        // Tao Situation Enroll
                        $stu_si                 = new J_StudentSituations();
                        $stu_si->name           = $student->full_student_name;
                        $stu_si->student_type   = 'Student';
                        $stu_si->student_id     = $student->id;
                        $stu_si->ju_class_id    = $class_id;
                        $stu_si->payment_id     = $bean_payment->id;
                        $stu_si->total_hour     = format_number($total_hour_unfm,2,2);
                        $stu_si->total_amount   = format_number($total_hour_unfm * $unit_price);
                        $stu_si->start_study    = $timedate->to_display_date($date_first);
                        $stu_si->end_study      = $timedate->to_display_date($date_last);
                        $stu_si->type           = 'Enrolled';
                        $stu_si->start_hour     = $start_hour;

                        $stu_si->assigned_user_id   = $bean_payment->assigned_user_id;
                        $stu_si->team_id            = $bean_payment->team_id;
                        $stu_si->team_set_id        = $bean_payment->team_id;
                        $stu_si->save();
                        // Add học viên vào lớp
                        for($i = 0; $i < count($sss); $i++)
                            addJunToSession($stu_si->id , $sss[$i]['primaryid'] );
                    }
                }
            }
        }elseif($class->class_type == 'Waiting Class'){
            $stu_si = new J_StudentSituations();
            $stu_si->name           = $student->last_name.' '.$student->first_name;
            $stu_si->student_type   = 'Student';
            $stu_si->student_id     = $student->id;
            $stu_si->ju_class_id    = $class->id;
            $stu_si->payment_id     = $bean_payment->id;
            $stu_si->total_hour     = format_number($bean_payment->tuition_hours,2,2);
            $stu_si->total_amount   = format_number($unit_price * $bean_payment->tuition_hours);
            $stu_si->start_study    = $class->start_date;
            $stu_si->end_study      = $class->end_date;
            $stu_si->type           = 'Enrolled';
            $stu_si->start_hour     = format_number(0,2,2);;
            $stu_si->assigned_user_id   = $bean_payment->assigned_user_id;
            $stu_si->team_id            = $bean_payment->team_id;
            $stu_si->team_set_id        = $bean_payment->team_id;
            $stu_si->save();
            //Add Relationship class
            $q4 = "INSERT INTO j_class_contacts_1_c (id, date_modified, deleted, j_class_contacts_1j_class_ida, j_class_contacts_1contacts_idb) VALUES ('".create_guid()."', '".$timedate->nowDb()."', '0', '{$class->id}', '{$student->id}')";
            $GLOBALS['db']->query($q4);
        }
    }

    //Auto set up Portal Account
    if(empty($student->user_id) && !empty($student->id)){
        $user = BeanFactory::newBean('Users');

        $user->user_name = strtolower($student->contact_id);
        $user->password = 'portal123456';

        $user->contact_id = $student->id;
        $user->portal_contact_id = $student->id;
        $user->portal_user = '1';
        $user->for_portal_only = '1';

        $user->first_name = $student->first_name;
        $user->last_name = $student->last_name;
        $user->phone_mobile = $student->phone_mobile;
        $user->address_street = $student->primary_address_street;
        $user->address_city = $student->primary_address_city;
        $user->address_state = $student->primary_address_state;
        $user->address_country = $student->address_country;
        $user->team_id = $student->team_id;
        $user->team_set_id = $student->team_id;
        $user->email1 = $student->email1;
        if($student->portal_active)
            $user->status = 'Active';
        else
            $user->status = 'Inactive';

        $user->save();

        $user->setPreference('date_format','d/m/Y');
        $user->setPreference('time_format','h:ia');
        $user->setPreference('timezone','Asia/Ho_Chi_Minh');
        $user->setPreference('default_locale_name_format','s l f');
        $user->savePreferencesToDB();

        $additionalData = array(
            'link' => false,
            'password' => $user->password,
            'system_generated_password' => '0',
        );
        $user->setNewPassword($additionalData['password'], '0');
        $GLOBALS['db']->query("UPDATE contacts SET user_id = '{$user->id}', portal_name='{$user->user_name}', password_generated='{$user->password}' WHERE id='{$student->id}'");
    }
}
/*  function by Tung Bui 20/11/2016
*   Phân enrollment thành các đoạn
*   Vd: Enroll 1/6 - 1/10, trong đó có 2 oustangding (1/7-1/8) và (15/8-15/9)
*   Kết quà: phân thành mảng
*       + 1/6:  enroll  (bắt đầu một khoảng enroll)
*       + 1/7:  settle  (bắt đầu một khoảng trả tiền outstanding)
*       + 1/8:  enroll
*       + 15/8: settle
*       + 15/9: enroll
*       + 1/10:         (kết thúc)
*/
function splitEnroll($bean){
    $situation_arr = array();
    $json_ost = json_decode(html_entity_decode($bean->outstanding_list),true);
    if($bean->is_outstanding && !empty($json_ost)){
        foreach($json_ost as $situa_id => $situa_value){
            if(in_array($situa_value['class_id'], $_POST['classes'])){
                if(strtotime($bean->payment_date) <= strtotime($situa_value['start_study'])){
                    $situation_arr[] = array(
                        'situation_id'  => $situa_id,
                        'start_study'   => $situa_value['start_study'],
                        'end_study'     => $situa_value['end_study'],
                        'total_hour'    => $situa_value['total_hour'] - $situa_value['total_revenue_util_now'],
                        'type'          => 'Enrolled',
                        'OutStanding'   => '1',
                    );
                }else{
                    $settle_study = '';
                    if(strtotime($bean->payment_date) > strtotime($situa_value['end_study'])){
                        $settle_study = $situa_value['end_study'];
                    }
                    else {
                        $settle_study = date('Y-m-d',strtotime("-1 day ".$bean->payment_date));
                        //                        $settle_study = $bean->payment_date;
                    }
                    $situation_arr[] = array(
                        'situation_id'  => $situa_id,
                        'start_study'   => $situa_value['start_study'],
                        'end_study'     => $settle_study,
                        'total_hour'    => $situa_value['total_revenue_util_now'],
                        'type'          => 'Settle',
                        'OutStanding'   => '1',
                    );
                    $rest_hour = $situa_value['total_hour'] - $situa_value['total_revenue_util_now'];
                    if($rest_hour > 0)
                        $situation_arr[] = array(
                            'situation_id'  => $situa_id,
                            'start_study'   => $settle_study,
                            'end_study'     => $situa_value['end_study'],
                            'total_hour'    => $situa_value['total_hour'] - $situa_value['total_revenue_util_now'],
                            'type'          => 'Enrolled_After_Settle',
                            'OutStanding'   => '1',
                        );

                }
            }
        }
    }else{
        $situation_arr[] = array(
            'situation_id'  => '',
            'start_study'   => $bean->start_study,
            'end_study'     => $bean->end_study,
            'total_hour'    => $bean->tuition_hours,
            'type'          => 'Enrolled',
            'OutStanding'   => '0',
        );
    }
    return $situation_arr;
}

/*Function checkSituationInClass() để kiểm tra student bất kỳ có tồn tại situation nào hay không
* Tham số truyền vào:
* id của lớp
* id của student đó
*/
function checkSituationInClass($id_class,$id_student){
    $sql_get_situ = "SELECT DISTINCT
    IFNULL(j_studentsituations.id, '') primaryid
    FROM j_studentsituations
    INNER JOIN contacts l1 ON j_studentsituations.student_id = l1.id AND l1.deleted = 0
    INNER JOIN j_class l2 ON j_studentsituations.ju_class_id = l2.id AND l2.deleted = 0
    WHERE l1.id = '$id_student'
    AND l2.id = '$id_class'
    AND j_studentsituations.deleted = 0";

    $result_get = $GLOBALS['db']->query($sql_get_situ);
    $row_get= $GLOBALS['db']->fetchByAssoc($result_get);
    if(empty($row_get)){
        $GLOBALS['db']->query("DELETE FROM j_class_contacts_1_c
            WHERE j_class_contacts_1contacts_idb ='{$student_id}'
            AND j_class_contacts_1j_class_ida = '{$id_class}'");
    }
}

/**
* add Student To Session
*/
function addJunToSession($situation_id , $ss_id , $student_id = ''){
    if(empty($situation_id))
        die();

    if(empty($student_id))
        $student_id = $GLOBALS['db']->getOne("SELECT student_id FROM j_studentsituations WHERE id='$situation_id'");

    if(empty($student_id))
        return false;

    //Check duplicate
    $q1 = "SELECT id
    FROM meetings_contacts
    WHERE meeting_id = '$ss_id'
    AND contact_id = '$student_id'
    AND situation_id = '$situation_id'
    AND deleted = 0";
    $id = $GLOBALS['db']->getOne($q1);

    if(empty($id)){
        //Them hoc vien vao session
        $q2 = "INSERT INTO meetings_contacts
        (id, meeting_id, contact_id, required, accept_status, date_modified, deleted, situation_id) VALUES
        ('".create_guid()."', '$ss_id', '$student_id', '1', 'none', '".$GLOBALS['timedate']->nowDb()."', '0', '$situation_id')";
        $GLOBALS['db']->query($q2);
    }

    //Add Relationship class
    $classId = $GLOBALS['db']->getOne("SELECT ju_class_id FROM meetings WHERE id = '$ss_id' AND deleted = 0");
    $q3 = "SELECT id
    FROM j_class_contacts_1_c
    WHERE j_class_contacts_1j_class_ida = '$classId'
    AND j_class_contacts_1contacts_idb = '$student_id'
    AND deleted = 0";
    $rel_class_id = $GLOBALS['db']->getOne($q3);
    if(empty($rel_class_id)){
        $q4 = "INSERT INTO j_class_contacts_1_c (id, date_modified, deleted, j_class_contacts_1j_class_ida, j_class_contacts_1contacts_idb) VALUES ('".create_guid()."', '".$GLOBALS['timedate']->nowDb()."', '0', '$classId', '$student_id')";
        $GLOBALS['db']->query($q4);
    }
}
//Add Lead To Session
function addLeadToSession($situation_id , $ss_id , $lead_id = ''){
    if(empty($situation_id))
        die();

    if(empty($lead_id))
        $lead_id = $GLOBALS['db']->getOne("SELECT lead_id FROM j_studentsituations WHERE id='$situation_id'");

    if(empty($lead_id))
        return false;

    //Check duplicate
    $q1 = "SELECT id
    FROM meetings_leads
    WHERE meeting_id = '$ss_id'
    AND lead_id = '$lead_id'
    AND situation_id = '$situation_id'
    AND deleted = 0";
    $id = $GLOBALS['db']->getOne($q1);

    if(empty($id)){
        //Them hoc vien vao session
        $q2 = "INSERT INTO meetings_leads
        (id, meeting_id, lead_id, required, accept_status, date_modified, deleted, situation_id) VALUES
        ('".create_guid()."', '$ss_id', '$lead_id', '1', 'none', '".$GLOBALS['timedate']->nowDb()."', '0', '$situation_id')";
        $GLOBALS['db']->query($q2);
    }

    //    //Add Relationship class
    //    $classId = $GLOBALS['db']->getOne("SELECT ju_class_id FROM meetings WHERE id = '$ss_id' AND deleted = 0");
    //    $q3 = "SELECT id
    //    FROM j_class_leads_1_c
    //    WHERE j_class_leads_1j_class_ida = '$classId'
    //    AND j_class_leads_1leads_idb = '$lead_id'
    //    AND deleted = 0";
    //    $rel_class_id = $GLOBALS['db']->getOne($q3);
    //    if(empty($rel_class_id)){
    //        $q4 = "INSERT INTO j_class_leads_1_c (id, date_modified, deleted, j_class_leads_1j_class_ida, j_class_leads_1leads_idb) VALUES ('".create_guid()."', '".$GLOBALS['timedate']->nowDb()."', '0', '$classId', '$lead_id')";
    //        $GLOBALS['db']->query($q4);
    //    }
}

/**
* Xóa học viên ra khỏi session
*/
function removeJunFromSession($situation_id, $meeting_id){
    if(empty($situation_id))
        die();

    $ext_meeting = '';
    if(!empty($meeting_id))
        $ext_meeting = "AND meeting_id = '$meeting_id'";
    $GLOBALS['db']->query("DELETE FROM meetings_contacts WHERE situation_id = '$situation_id' $ext_meeting");

    $rs1        = $GLOBALS['db']->query("SELECT ju_class_id, student_id FROM j_studentsituations WHERE id = '$situation_id'");
    $rel_class  = $GLOBALS['db']->fetchByAssoc($rs1);
    $student_id = $rel_class['student_id'];
    $classId    = $rel_class['ju_class_id'];
    //Remove Relationship class
    $q2= "SELECT DISTINCT
    IFNULL(COUNT(meetings.id), 0) meetings__allcount
    FROM
    meetings
    INNER JOIN
    j_class l1 ON meetings.ju_class_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    meetings_contacts l2_1 ON meetings.id = l2_1.meeting_id
    AND l2_1.deleted = 0
    INNER JOIN
    contacts l2 ON l2.id = l2_1.contact_id
    AND l2.deleted = 0
    WHERE
    (((l1.id = '$classId')
    AND (l2.id = '$student_id')))
    AND meetings.deleted = 0";
    $countSession = $GLOBALS['db']->getOne($q2);
    if ($countSession == 0){
        $sqlRemoveClass = "DELETE FROM j_class_contacts_1_c
        WHERE deleted <> 1
        AND j_class_contacts_1j_class_ida = '$classId'
        AND j_class_contacts_1contacts_idb = '$student_id'";
        $result = $GLOBALS['db']->query($sqlRemoveClass);
    }
}

//Remove Lead From Session
function removeLeadFromSession($situation_id, $meeting_id){
    if(empty($situation_id))
        die();

    $ext_meeting = '';
    if(!empty($meeting_id))
        $ext_meeting = "AND meeting_id = '$meeting_id'";
    $GLOBALS['db']->query("DELETE FROM meetings_leads WHERE situation_id = '$situation_id' $ext_meeting");
    //
    //    $rs1        = $GLOBALS['db']->query("SELECT ju_class_id, lead_id FROM j_studentsituations WHERE id = '$situation_id'");
    //    $rel_class  = $GLOBALS['db']->fetchByAssoc($rs1);
    //    $lead_id    = $rel_class['lead_id'];
    //    $classId    = $rel_class['ju_class_id'];
    //    //Remove Relationship class
    //    $q2= "SELECT DISTINCT
    //    IFNULL(COUNT(meetings.id), 0) meetings__allcount
    //    FROM
    //    meetings
    //    INNER JOIN
    //    j_class l1 ON meetings.ju_class_id = l1.id
    //    AND l1.deleted = 0
    //    INNER JOIN
    //    meetings_leads l2_1 ON meetings.id = l2_1.meeting_id
    //    AND l2_1.deleted = 0
    //    INNER JOIN
    //    leads l2 ON l2.id = l2_1.lead_id
    //    AND l2.deleted = 0
    //    WHERE
    //    (((l1.id = '$classId')
    //    AND (l2.id = '$lead_id')))
    //    AND meetings.deleted = 0";
    //    $countSession = $GLOBALS['db']->getOne($q2);
    //    if ($countSession == 0){
    //        $sqlRemoveClass = "DELETE FROM j_class_leads_1_c
    //        WHERE deleted = 0
    //        AND j_class_leads_1j_class_ida = '$classId'
    //        AND j_class_leads_1leads_idb = '$lead_id'";
    //        $result = $GLOBALS['db']->query($sqlRemoveClass);
    //    }
}



/**
* Kiểm tra nếu học viên không còn session trong lớp thì deleted quan hệ học viên với lớp
*/
function deleteRelStudentClass($class_id , $student_id ){
    $q1 = "SELECT DISTINCT
    COUNT(IFNULL(meetings.id,'')) count_ss
    FROM meetings
    INNER JOIN  j_class l1 ON meetings.ju_class_id=l1.id AND l1.deleted=0
    INNER JOIN  meetings_contacts l2_1 ON meetings.id = l2_1.meeting_id AND l2_1.deleted=0
    INNER JOIN  contacts l2 ON l2.id=l2_1.contact_id AND l2.deleted=0
    WHERE ((l2.id = '$student_id'
    AND (l1.id='$class_id')))
    AND  meetings.deleted=0 ";
    $count_ss = $GLOBALS['db']->getOne($q1);
    if($count_ss == 0)
        $GLOBALS['db']->query("UPDATE j_class_contacts_1_c SET deleted = 1 WHERE j_class_contacts_1j_class_ida='$class_id' AND j_class_contacts_1contacts_idb='$student_id' AND deleted = 0");
    else return false;
    return true;
}

/* functiond Lấy danh sách student kèm thông tin giờ học trong khoảng thời gian
*/
function GetStudentsProcessInClass($class_id, $start_change = ''){
    require_once("custom/include/_helper/junior_revenue_utils.php");
    global $timedate;
    //Lấy tất cả Situation có liên quan đến change lịch
    if(!empty($start_change))
        $ext_start_change = "AND (j_studentsituations.end_study >= '{$timedate->to_db_date($start_change,false)}')";

    $q1 = "SELECT DISTINCT
    IFNULL(j_studentsituations.id, '') situation_id,
    IFNULL(j_studentsituations.student_id, '') student_id,
    IFNULL(j_studentsituations.name, '') student_name,
    j_studentsituations.start_study start_study,
    j_studentsituations.end_study end_study,
    IFNULL(j_studentsituations.start_hour, 0) start_hour,
    IFNULL(j_studentsituations.total_hour, 0) total_hour,
    IFNULL(j_studentsituations.payment_id, '') payment_id,
    IFNULL(l2.status, '') payment_status,
    j_studentsituations.type type
    FROM j_studentsituations
    INNER JOIN
    j_class l1 ON j_studentsituations.ju_class_id = l1.id
    AND l1.deleted = 0
    LEFT JOIN
    j_payment l2 ON j_studentsituations.payment_id = l2.id
    AND l2.deleted = 0
    WHERE
    (((l1.id = '$class_id')
    AND (j_studentsituations.type IN ('Enrolled' , 'OutStanding',
    'Settle',
    'Moving In'))
    $ext_start_change))
    AND j_studentsituations.deleted = 0
    ORDER BY student_id, start_hour ASC";
    $rs1 = $GLOBALS['db']->query($q1);

    $res = array();
    while($st = $GLOBALS['db']->fetchByAssoc($rs1)){
        $res[$st['situation_id']]['start_hour']     = $st['start_hour'];
        $res[$st['situation_id']]['total_hour']     = $st['total_hour'];
        $res[$st['situation_id']]['situa_type']     = $st['type'];
        $res[$st['situation_id']]['start_study']    = $st['start_study'];
        $res[$st['situation_id']]['end_study']      = $st['end_study'];
        $res[$st['situation_id']]['student_id']     = $st['student_id'];
        $res[$st['situation_id']]['student_name']   = $st['student_name'];
        $res[$st['situation_id']]['payment_id']     = $st['payment_id'];
        $res[$st['situation_id']]['payment_status'] = $st['payment_status'];

    }
    return $res;
}

function addStudentToNewSessions($situationArr, $class_id, $start_change = ''){
    require_once("custom/include/_helper/junior_revenue_utils.php");
    global $timedate;
    $ext_start_change = '';
    if(!empty($start_change)){
        $start_tz     = date('Y-m-d H:i:s',strtotime("-7 hours ".$timedate->to_db_date($start_change,false)." 00:00:00"));
        $ext_start_change = "AND (l1.date_start >= '$start_tz')";
    }
    //Remove tất cả học viên từ ngày change
    $q1 = "SELECT DISTINCT
    IFNULL(meetings_contacts.id, '') primaryid
    FROM
    meetings_contacts
    INNER JOIN
    meetings l1 ON l1.id = meetings_contacts.meeting_id
    AND l1.deleted = 0
    INNER JOIN
    j_studentsituations l2 ON meetings_contacts.situation_id = l2.id
    AND l2.deleted = 0 AND l2.id IN ('".implode("','",array_keys($situationArr))."')
    INNER JOIN
    j_class l3 ON l2.ju_class_id = l3.id
    AND l3.deleted = 0 AND l3.id = '$class_id'
    WHERE
    ((meetings_contacts.deleted = 0
    $ext_start_change))
    ORDER BY date_start ASC";
    $removeArray = $GLOBALS['db']->fetchArray($q1);
    $class = BeanFactory::getBean("J_Class", $class_id);
    if(!empty($removeArray))
        $GLOBALS['db']->query("DELETE FROM meetings_contacts WHERE id IN ('".implode("','",array_column($removeArray,'primaryid'))."')");
    //Get list New Session by Class
    $ss = get_list_lesson_by_class($class_id);
    $paidSituationType = array('Enrolled','Moving In','Settle');
    //render session by date
    $sessions_date = array();
    foreach($ss as $ind=>$value){
        $sessions_date[$value['date']]['primaryid'][]   =  $value['primaryid'];
        $sessions_date[$value['date']]['date']          =  $value['date'];
        $sessions_date[$value['date']]['till_hour']     =  $value['till_hour'];
        $sessions_date[$value['date']]['delivery_hour'] +=  $value['delivery_hour'];
        $sessions_date[$value['date']]['date_start']    =  $value['date_start'];
        $sessions_date[$value['date']]['date_end']      =  $value['date_end'];
    }
    foreach($situationArr as $si_id => $si_value){
        $date_first  = '';
        $date_last   = '';
        foreach($sessions_date as $ss_date => $ss_value){
            $ss_start_till = $ss_value['till_hour'] - $ss_value['delivery_hour'];
            if( ($si_value['total_hour']  >= 0 ) &&  ($si_value['start_hour'] <= $ss_start_till)){
                //Caculate First - Last Session
                $si_value['total_hour'] -= $ss_value['delivery_hour'];
                if($si_value['total_hour'] >= 0) {
                    if(empty($date_first))
                        $date_first = $ss_value['date'];
                    $date_last = $ss_value['date'];
                    foreach($ss_value['primaryid'] as $key => $ss_id )
                        addJunToSession($si_id , $ss_id);
                }
                //Apply viec change Schedule
                else{
                    $delay_hour = $si_value['total_hour'] + $ss_value['delivery_hour'];
                    if($_POST['accept_schedule_change'] == '1' && $delay_hour > 0 && $si_value['payment_status'] != 'Closed'){

                        $situa = BeanFactory::getBean("J_StudentSituations", $si_id);
                        $delay_amount = ($situa->total_amount / $situa->total_hour) * $delay_hour;
                        if(in_array($situa->type, $paidSituationType)){ // Tao payment Change Schedule
                            $pm_delay                                   = new J_Payment();
                            $pm_delay->delay_situation_id               = $si_id;
                            $pm_delay->contacts_j_payment_1contacts_ida = $situa->student_id;
                            $pm_delay->payment_type         = 'Schedule Change';
                            $pm_delay->payment_date         = date('Y-m-d');
                            $pm_delay->payment_expired      = date('Y-m-d',strtotime("+6 months ".$pm_delay->payment_date));
                            $pm_delay->payment_amount       = $delay_amount;
                            $pm_delay->remain_amount        = $delay_amount;
                            $pm_delay->tuition_hours        = $delay_hour;
                            $pm_delay->total_hours          = $delay_hour;
                            $pm_delay->remain_hours         = $delay_hour;
                            $pm_delay->used_hours           = 0;
                            $pm_delay->used_amount          = 0;
                            $pm_delay->note                 = "Auto-Generate when changed schedule.";
                            $pm_delay->assigned_user_id     = $GLOBALS['current_user']->id;
                            $pm_delay->team_id              = $situa->team_id;
                            $pm_delay->team_set_id          = $situa->team_id;
                            $pm_delay->save();
                            addRelatedPayment($pm_delay->id, $situa->payment_id, $delay_amount, $delay_hour);
                        }
                        $new_hour          = $situa->total_hour - $delay_hour;
                        $new_amount        = $situa->total_amount - $delay_amount;
                        if($new_hour == 0 && $new_amount == 0)
                            $GLOBALS['db']->query("UPDATE j_studentsituations SET deleted = 1 WHERE id = '{$situa->id}'");

                        $GLOBALS['db']->query("UPDATE j_studentsituations SET total_amount = $new_amount, total_hour = $new_hour WHERE id = '{$situa->id}'");
                    }
                }

            }
        }

        if(!empty($date_first) && !empty($date_last)){
            //Update Situation Time
            $q3 = "UPDATE j_studentsituations SET start_study = '$date_first', end_study = '$date_last' WHERE id='$si_id'";
            $GLOBALS['db']->query($q3);
        }
    }
}

/**
* ham lay ngay ke tiep buoi cuoi cung cua lop hoc khi cancel sesion
*
* @param mixed $class_id
*/
function getEndNextTimeSession($class_id, $duration) {
    require_once("custom/include/_helper/junior_schedule.php");

    $rs1 = $GLOBALS['db']->query("SELECT j_class.content content,
        l1.team_type team_type
        FROM
        j_class
        INNER JOIN
        teams l1 ON j_class.team_id = l1.id
        AND l1.deleted = 0
        WHERE
        j_class.id = '$class_id'");
    $class          = $GLOBALS['db']->fetchByAssoc($rs1);
    $run_datetime   = $GLOBALS['db']->getOne("SELECT CONVERT_TZ(MAX(date_start),'+00:00','+7:00') date_start FROM meetings WHERE ju_class_id = '$class_id' AND deleted = 0 AND session_status <> 'Cancelled'");
    $run_begin_date = date('Y-m-d',strtotime($run_datetime));
    $run_date       = $run_begin_date;
    $run_time       = date('H:i:s',strtotime($run_datetime));
    $run_date_display = $GLOBALS['timedate']->to_display_date($run_date,false);
    $holiday_list   = getPublicHolidays($run_date_display,'', $class['team_type']);

    $json           = json_decode(html_entity_decode($class['content']),true);
    $schedule       = $json['schedule'];
    $count_while = 0;
    if(!empty($schedule)){
        while($duration > 0){
            $count_while++;
            if($count_while > 1000){
                die();
            }
            $chck_day   = date('D',strtotime($run_date));
            if (array_key_exists($chck_day, $schedule) && !array_key_exists($run_date, $holiday_list)){
                foreach($schedule[$chck_day] as $key => $sche){
                    if(($run_date == $run_begin_date)){
                        if($sche['start_time'] > $run_time){
                            $run_time = $sche['start_time'];
                            $duration = 0;
                            break;
                        }elseif($key == (count($schedule[$chck_day]) - 1))
                            $run_date   = date('Y-m-d',strtotime('+1 day'. $run_date));
                    }else{
                        $run_time = $sche['start_time'];
                        $duration = 0;
                        break;
                    }
                }
            }else
                $run_date   = date('Y-m-d',strtotime('+1 day'. $run_date));
        }
    }

    return $run_date.' '.$run_time;
}

function addRelatedPayment($payment_id, $get_from_payment_id, $amount, $hour){
    $sql = "DELETE FROM j_payment_j_payment_1_c WHERE j_payment_j_payment_1j_payment_ida='$payment_id' AND j_payment_j_payment_1j_payment_idb='$get_from_payment_id' AND deleted = 0";
    $GLOBALS['db']->query($sql);
    if(empty($amount)) $amount = 0;
    if(empty($hour)) $hour = 0;

    $sql2 = "INSERT INTO j_payment_j_payment_1_c
    (id, date_modified, deleted, j_payment_j_payment_1j_payment_ida, j_payment_j_payment_1j_payment_idb, hours, amount) VALUES
    ('".create_guid()."','".$GLOBALS['timedate']->nowDb()."',0, '$payment_id', '$get_from_payment_id', $hour, $amount)";
    $GLOBALS['db']->query($sql2);
}

function removeRelatedPayment($payment_id, $get_from_payment_id = ''){
    if(!empty($get_from_payment_id)){
        $sql = "DELETE FROM j_payment_j_payment_1_c WHERE j_payment_j_payment_1j_payment_ida='$payment_id' AND j_payment_j_payment_1j_payment_idb='$get_from_payment_id' AND deleted = 0";
        $GLOBALS['db']->query($sql);
    }else{
        $sql = "DELETE FROM j_payment_j_payment_1_c WHERE j_payment_j_payment_1j_payment_ida='$payment_id' AND deleted = 0";
        $GLOBALS['db']->query($sql);

        $sql = "DELETE FROM j_payment_j_payment_1_c WHERE j_payment_j_payment_1j_payment_idb='$payment_id' AND deleted = 0";
        $GLOBALS['db']->query($sql);
    }
}

function getListIssue($class_id){
    require_once('custom/include/_helper/junior_revenue_utils.php');
    global $timedate;

    $q1 = "SELECT DISTINCT
    IFNULL(j_studentsituations.id, '') situation_id,
    IFNULL(l1.id, '') class_id,
    IFNULL(j_studentsituations.student_id, '') student_id,
    IFNULL(j_studentsituations.name, '') situation_name,
    j_studentsituations.start_study start_study,
    j_studentsituations.end_study end_study,
    j_studentsituations.total_amount total_amount,
    j_studentsituations.total_hour total_hour,
    l1.hours class_hour
    FROM
    j_studentsituations
    INNER JOIN
    j_class l1 ON j_studentsituations.ju_class_id = l1.id
    AND l1.deleted = 0 AND l1.class_type = 'Normal Class'
    INNER JOIN
    j_payment l2 ON j_studentsituations.payment_id = l2.id
    AND l2.deleted = 0
    WHERE
    (( (j_studentsituations.type IN ('Enrolled' , 'OutStanding',
    'Settle',
    'Moving In'))
    AND (l2.status <> 'Closed')
    AND (l1.id = '$class_id')))
    AND j_studentsituations.deleted = 0";

    $rs1 = $GLOBALS['db']->query($q1);
    $count = 0;
    $count_2 = 0;
    $html1 = '';
    $html2 = '';

    while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
        $ses = get_list_lesson_by_situation($row['class_id'], $row['situation_id'], '', '', 'INNER');
        $cr_ss = '***';
        $cr_hour = 0;
        $cr_lesson = $ses[0]['lesson_number'];
        $flag_lesson = true;
        $cr_lesson_wrong = array();
        for($i = 0; $i < count($ses); $i++){
            $cr_ss = $ses[$i]['situation_id'];
            if(!empty($cr_ss))
                $cr_hour += $ses[$i]['delivery_hour'];

            if($cr_lesson != $ses[$i]['lesson_number']){
                $flag_lesson = false;
                $cr_lesson_wrong[] = $ses[$i]['lesson_number'];
            }
            $cr_lesson++;
        }

        //Dinh dang truong hop
        if( $row['total_hour'] != $cr_hour ){
            $count++;
            $html1.= "<a href='index.php?module=Contacts&action=DetailView&record={$row['student_id']}'>  {$row['situation_name']} </a>  <br>Totall Enroll Hours: {$row['total_hour']} <br>Revenue Hour: = $cr_hour </b><br><br>";
        }
        if(!$flag_lesson){
            $first = reset($ses);
            $date_first = $timedate->to_display_date(date('Y-m-d',strtotime("+7 hours ".$first['date_start'])), false);

            $html2 .=  "<a href='index.php?module=Contacts&action=DetailView&record={$row['student_id']}'>  {$row['situation_name']} </a> Từ ngày: $date_first. Thứ tự bị add sai từ Session thứ {$cr_lesson_wrong[0]}<br>";
            $count_2++;
        }
    }
}

function generateSmartSchedule($class_id){
    require_once('custom/include/_helper/junior_revenue_utils.php');
    global $timedate;

    $sss = get_list_lesson_by_class($class_id);
    $schedule   = array();
    for($i = 0; $i < count($sss); $i++){

        $this_start = strtotime('+ 7hour '.$sss[$i]['date_start']);
        $this_end   = strtotime('+ 7hour '.$sss[$i]['date_end']);

        $this_date  = date('d/m/Y',$this_start);
        $week_date  = date('D',$this_start);
        $time       = date('g:i',$this_start).' - '.date('g:ia',$this_end);

        $schedule[$week_date.' '.$time][] = $this_date;
    }
    foreach($schedule as $key => $value){
        $first  = reset($value);
        $last   = end($value);
        $schedule_obj[$key]       = "$first &#x279c; $last";
    }
    return json_encode($schedule_obj);
}

function addToClassAdult($payment_id, $class_id, $start_study, $end_study){
    require_once('custom/include/_helper/junior_revenue_utils.php');
    global $timedate;
    $payment = BeanFactory::getBean('J_Payment', $payment_id);
    //Don gia theo gio
    $unit_price   = ($payment->payment_amount + $payment->deposit_amount + $payment->paid_amount) / ($payment->total_hours_adult);

    $student_id = $payment->contacts_j_payment_1contacts_ida;

    //Kiểm tra học viên đã có trong lớp chưa ?
    $ss_list    = get_list_revenue($student_id, "'Enrolled', 'Moving In', 'Settle'",$start_study, $end_study, $class_id );
    $sss        = get_list_lesson_by_class($class_id, $start_study, $end_study);
    if(count($ss_list) > 0){
        $class_name = $GLOBALS['db']->getOne("SELECT name FROM j_class WHERE id = '$class_id'");
        return json_encode(array(
            "success"         => "0",
            "notify"         => "This student is already exist in class $class_name!!",
        ));
    }

    //Kiểm tra ngày bắt đầu học phải lớn hơn Payment Date
    $start_study_db        = $timedate->to_db_date($start_study,false);
    $end_study_db          = $timedate->to_db_date($end_study,false);
    $payment_date_db       = $timedate->to_db_date($payment->payment_date,false);

    if($start_study_db < $payment_date_db){
        return json_encode(array(
            "success"         => "0",
            "notify"         => "Cannot add before Payment Date: {$payment->payment_date}",
        ));
    }
    if($payment->status == 'Closed'){
        return json_encode(array(
            "success"        => "0",
            "notify"         => "Payment is closed!",
        ));
    }
    //Kiểm tra ngày Start - Finish
    if( (empty($payment->start_study) && !empty($payment->end_study)) || (!empty($payment->start_study) && empty($payment->end_study)) ){
        return json_encode(array(
            "success"         => "0",
            "notify"         => "An error has occurred. Please, contact your system administrator",
        ));
    }elseif(!empty($payment->start_study) && !empty($payment->end_study)){
        if($end_study_db > $timedate->to_db_date($payment->end_study,false))
            return json_encode(array(
                "success"         => "0",
                "notify"         => "Cannot add before after End Study: {$payment->end_study}",
            ));
    }

    //Kiểm tra và tạo Situation
    $q1 = "SELECT DISTINCT
    IFNULL(j_studentsituations.id, '') primaryid,
    IFNULL(j_studentsituations.type, '') type,
    j_studentsituations.start_study start_study,
    j_studentsituations.end_study end_study
    FROM
    j_studentsituations
    INNER JOIN
    j_class l1 ON j_studentsituations.ju_class_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    j_payment l2 ON j_studentsituations.payment_id = l2.id
    AND l2.deleted = 0
    WHERE
    (((l1.id = '$class_id')
    AND (l2.id = '{$payment->id}')))
    AND j_studentsituations.deleted = 0";
    $rs1    = $GLOBALS['db']->query($q1);
    $row_si = $GLOBALS['db']->fetchByAssoc($rs1);
    $rs2    = $GLOBALS['db']->query("SELECT CONCAT(IFNULL(last_name, ''),' ',IFNULL(first_name, '')) name FROM contacts WHERE id = '$student_id' AND deleted = 0");
    $row_stu = $GLOBALS['db']->fetchByAssoc($rs2);
    $ss_start   = reset($sss);
    $ss_end     = end($sss);
    //caculate start_hour
    $total_hours = 0;
    $start_hour = format_number($ss_start['till_hour'] - $ss_start['delivery_hour'],2,2);
    if(empty($row_si['primaryid'])){
        $situ                   = new J_StudentSituations();
        $situ->id               = create_guid();
        $situ->new_with_id      = true;
        foreach($sss as $key => $value){
            addJunToSession($situ->id , $value['primaryid'], $student_id);
            $total_hours += $value['delivery_hour'];
        }
        $situ->start_study      = $timedate->to_display_date($ss_start['date_end']);
        $situ->end_study        = $timedate->to_display_date($ss_end['date_end']);
        $situ->start_hour       = $start_hour;
    }else{
        $situ                     = BeanFactory::getBean('J_StudentSituations',$row_si['primaryid']);
        foreach($sss as $key => $value){
            addJunToSession($situ->id , $value['primaryid'], $student_id);
            $total_hours += $value['delivery_hour'];
        }


        if($ss_start['date_end']  < $situ->start_study)
            $situ->start_study      = $timedate->to_display_date($ss_start['date_end']);
        if($ss_end['date_end']  > $situ->end_study)
            $situ->end_study        = $timedate->to_display_date($ss_end['date_end']);

        if( $situ->start_hour > $start_hour  )
            $situ->start_hour       = $start_hour;
    }

    $situ->name             = $row_stu['name'];
    $situ->student_type     = 'Student';
    $situ->type             = 'Enrolled';
    $situ->student_id       = $student_id;
    $situ->ju_class_id      = $class_id;
    $situ->payment_id       = $payment->id;


    //caculate tuition day
    $situ->total_amount     = format_number( $situ->total_amount + ($total_hours * $unit_price));//Doanh thu theo gio
    $situ->total_hour       = format_number( $situ->total_hour + $total_hours ,2,2);

    $situ->assigned_user_id = $payment->assigned_user_id;
    $situ->team_id          = $payment->team_id;
    $situ->team_set_id      = $payment->team_id;
    $situ->save();
    // Save Payment
    if(empty($payment->start_study) && empty($payment->end_study) || (!empty($payment->start_study) && !empty($payment->end_study) && ($start_study_db < $timedate->to_db_date($payment->start_study,false)))){
        $pay_start_db   =  $start_study_db;
        $run_remain     = $payment->tuition_hours;
        $pay_end_db     = cal_finish_date_adult($pay_start_db, $run_remain);
        $GLOBALS['db']->query("UPDATE j_payment SET start_study='$pay_start_db', end_study='$pay_end_db' WHERE id='{$payment->id}'");
    }else{
        //Fix bug Outstanding 1 day
        $pay_start_db   = $timedate->to_db_date($payment->start_study,false);
        $run_remain     = $payment->tuition_hours;
        if(!empty($pay_start_db))
            $pay_end_db = cal_finish_date_adult($pay_start_db, $run_remain);

        if(!empty($pay_end_db) && ($pay_end_db != $timedate->to_db_date($payment->end_study,false)))
            $GLOBALS['db']->query("UPDATE j_payment SET end_study='$pay_end_db' WHERE id = '{$payment->id}'");

    }

    //Create Record LMS Report
    $q10 = "SELECT
    IFNULL(g.id, '') gradebook_id, g.team_id team_id, IFNULL(gd.id, '') gradebookdetail_id
    FROM
    j_gradebook g
    INNER JOIN
    j_class_j_gradebook_1_c cg ON cg.j_class_j_gradebook_1j_gradebook_idb = g.id
    AND cg.j_class_j_gradebook_1j_class_ida = '$class_id' AND cg.deleted = 0 AND g.deleted = 0
    AND g.type = 'LMS'
    LEFT JOIN
    j_gradebookdetail gd ON gd.gradebook_id = g.id
    AND gd.deleted = 0
    AND gd.student_id = '$student_id'";
    $rs10 = $GLOBALS['db']->query($q10);
    $row_lms = $GLOBALS['db']->fetchByAssoc($rs10);
    if(!empty($row_lms['gradebook_id'])){
        if(empty($row_lms['gradebookdetail_id'])){
            $gbd                = new J_GradebookDetail();
            $gbd->team_id       = $row_lms['team_id'];
            $gbd->team_set_id   = $row_lms['team_id'];

            $gbd->student_id    = $student_id;
            $gbd->gradebook_id  = $row_lms['gradebook_id'];
            $gbd->j_class_id    = $class_id;
            $gbd->date_input    = $timedate->nowDbDate();
            $gbd->total_mark    = 0;
            $gbd->final_result  = 0;
            $gbd->content       = "{\"comment_key\":null,\"comment_label\":\"\"}";
            $gbd->save();
        }
    }else{
        return json_encode(array(
            "success"         => "1",
            "notify"         => "Saved successfully. \n Notification: This class does'nt not have Gradebook LMS.",
        ));
    }
    return json_encode(array(
        "success"         => "1",
        "notify"         => "Saved successfully !!",
    ));
}
function getUnpaidPaymentByClass($class_id){
    $q1 = "SELECT DISTINCT
    IFNULL(l2.id, '') l2_id
    FROM
    j_studentsituations
    INNER JOIN
    j_class l1 ON j_studentsituations.ju_class_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    j_payment l2 ON j_studentsituations.payment_id = l2.id
    AND l2.deleted = 0
    INNER JOIN
    j_paymentdetail l3 ON l2.id = l3.payment_id AND l3.deleted = 0
    WHERE
    (((l1.id = '$class_id')
    AND (l3.status = 'Unpaid')
    AND (j_studentsituations.type IN ('Enrolled' , 'Settle', 'Moving In'))))
    AND j_studentsituations.deleted = 0";
    $rs1 = $GLOBALS['db']->query($q1);
    $unpaidList = array();
    while($row= $GLOBALS['db']->fetchByAssoc($rs1))
        $unpaidList[] = $row['l2_id'];
    return $unpaidList;
}
?>
