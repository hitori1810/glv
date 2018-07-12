<?php

require_once('custom/include/_helper/junior_class_utils.php');
//############## ENTRY POINT
function getUserRoles($param)
{
    //get roles of user to login livechart dashboard
    $user_id = $param['user_id'];
    $rolesList = $GLOBALS['db']->fetchArray("SELECT
        acl_roles.id id, acl_roles.name name
        FROM
        acl_roles
        INNER JOIN
        acl_roles_users ON acl_roles_users.user_id = '$user_id'
        AND acl_roles_users.role_id = acl_roles.id
        AND acl_roles_users.deleted = 0
        WHERE
        acl_roles.deleted = 0");
    if (!empty($rolesList)) {
        return array(
            "success" => "1",
            "value_list" => json_encode($rolesList),
        );
    } else return array(
        "success" => "0",
        "value_list" => '',
        );
}

function getTeamList($param)
{
    $user_id = $param['user_id'];
    /*$user_id = $GLOBALS['db']->getOne("SELECT
    u.id
    FROM
    users u
    INNER JOIN
    email_addr_bean_rel eref ON eref.bean_module = 'Users'
    AND eref.bean_id = u.id
    AND eref.deleted = 0
    AND u.deleted = 0
    AND u.status = 'Active'
    INNER JOIN
    email_addresses ea ON ea.id = eref.email_address_id
    AND ea.deleted = 0
    AND ea.email_address = '$email'
    AND u.for_portal_only = 0");*/
    $sql_team = "SELECT DISTINCT
    IFNULL(l2.id, '') team_id,
    IFNULL(l2.code_prefix, '') team_name
    FROM
    users
    INNER JOIN
    teams l1 ON users.default_team = l1.id
    and users.id = '$user_id'
    AND l1.deleted = 0
    INNER JOIN
    team_memberships l2_1 ON users.id = l2_1.user_id
    AND l2_1.deleted = 0
    INNER JOIN
    teams l2 ON l2.id = l2_1.team_id AND l2.deleted = 0
    and l2.code_prefix like '%.%'
    and l2.team_type = 'Junior'
    ORDER BY team_name";
    $team_list = $GLOBALS['db']->fetchArray($sql_team);
    if (!empty($team_list))
        return array(
            "success" => "1",
            "value_list" => json_encode($team_list),
        );
    else return array(
        "success" => "0",
        "value_list" => '',
        );
}

function getSessionBooking($param)
{
    global $timedate;
    $student_id = $param['student_id'];
    $start = $param['start'];
    $end = $param['end'];
    $class_type = $param['class_type'];

    $ext_class_type = "AND (jc.class_type_adult IN('Skill', 'Connect Club', 'Connect Event'))";
    if (!empty($class_type))
        $ext_class_type = "AND (jc.class_type_adult IN ('$class_type'))";

    $ext_start = '';
    if (!empty($start)) {
        $start_tz = date('Y-m-d H:i:s', strtotime("-7 hours " . $start . " 00:00:00"));
        $ext_start = "AND (m.date_start >= GREATEST(date_add(current_timestamp(), interval -7 hour),'$start_tz'))";
    }

    $ext_end = '';
    if (!empty($end)) {
        $end_tz = date('Y-m-d H:i:s', strtotime("-7 hours " . $end . " 23:59:59"));
        $ext_end = "AND (m.date_start <= '$end_tz')";
    }
    $q1 = "SELECT DISTINCT
    IFNULL(contacts.id, '') primaryid,
    CONCAT(IFNULL(contacts.last_name, ''),' ',IFNULL(contacts.first_name, '')) full_name,
    IFNULL(contacts.current_level, '') current_level,
    IFNULL(contacts.contact_status, '') contact_status,
    IFNULL(l1.id, '') center_id
    FROM
    contacts
    INNER JOIN
    teams l1 ON contacts.team_id = l1.id
    AND l1.deleted = 0
    WHERE
    (((contacts.id = '$student_id')))
    AND contacts.deleted = 0";
    $rs1 = $GLOBALS['db']->query($q1);
    $student = $GLOBALS['db']->fetchByAssoc($rs1);
    if (empty($student)) {
        return array(
            "success" => "0",
            "notify" => 'Can not load Student!',
        );
    }

    $sql_get_current_lv = "SELECT DISTINCT
    CONCAT('^', jc.level, '^')  level, jc.start_date, jc.end_date
    FROM
    j_class jc
    INNER JOIN
    j_studentsituations jss ON jss.ju_class_id = jc.id
    AND jss.deleted = 0
    AND jc.deleted = 0
    AND jc.class_type_adult IN ('Practice' , 'Waiting Class')
    AND ((CURDATE() BETWEEN jc.start_date AND jc.end_date)
    OR jc.end_date < CURDATE()
    OR jc.start_date > CURDATE())
    WHERE
    jss.student_id = '$student_id'
    ORDER BY CASE
    WHEN CURDATE() BETWEEN jc.start_date AND jc.end_date THEN 0
    WHEN jc.end_date < CURDATE() THEN 2
    WHEN jc.start_date > CURDATE() THEN 1
    END LIMIT 1";

    $cur_level_arr = $GLOBALS['db']->fetchArray($sql_get_current_lv);
    $cur_level = $cur_level_arr[0]['level'];
    if (empty($cur_level))
        return array(
            "success" => "0",
            "notify" => 'Please contact your Study Manager, the system cannot define your current level',
            "notify_vi" => 'Không xác định được level hiện tại của bạn, vui lòng liên hệ bộ phận hỗ trợ.!',
        );
    $q2 = "SELECT DISTINCT
    IFNULL(m.id, '') session_id,
    IFNULL(m.name, '') topic_name,
    IFNULL(DAYNAME(DATE_ADD(m.date_start, interval 7 hour)), '') day_name,
    CONVERT(DATE_ADD(m.date_start, interval 7 hour),date) date_start,
    CONVERT(DATE_ADD(m.date_start, interval 7 hour),time) start_time,
    CONVERT(DATE_ADD(m.date_end, interval 7 hour),time) end_time,
    m.duration_cal,
    IFNULL(jc.id, '') class_id,
    IFNULL(jc.name, '') class_name,
    jc.class_code class_code,
    IFNULL(jc.max_size, 0) total_seat,
    IFNULL(cr.name, '') room_name,
    CONCAT(IFNULL(ct.first_name, ''),' ',IFNULL(ct.last_name, '')) teacher_name,
    IFNULL(t.name, '') center_name,
    IFNULL(COUNT(DISTINCT c.id), 0) used_seat,
    (IFNULL(jc.max_size, 0) - IFNULL(COUNT(DISTINCT c.id), 0)) remain_seat,
    IFNULL(bs.status, 'Booking') booking_status
    FROM
    meetings m
    INNER JOIN
    j_class jc ON m.ju_class_id = jc.id
    AND jc.deleted = 0
    LEFT JOIN
    c_rooms cr ON m.room_id = cr.id
    AND cr.deleted = 0
    LEFT JOIN
    c_teachers ct ON m.teacher_id = ct.id
    AND ct.deleted = 0
    LEFT JOIN
    meetings_contacts mc ON m.id = mc.meeting_id AND mc.deleted = 0
    LEFT JOIN
    (SELECT
    meeting_id, contact_id, 'Booked' AS status
    FROM
    meetings_contacts
    WHERE
    contact_id = '$student_id'
    AND deleted = 0) bs ON bs.meeting_id = m.id
    LEFT JOIN
    contacts c ON c.id = mc.contact_id
    AND c.deleted = 0
    INNER JOIN
    teams t ON jc.team_id = t.id AND t.deleted = 0
    WHERE
    m.deleted = 0
    $ext_start
    $ext_end
    $ext_class_type
    AND (jc.kind_of_course_adult IN ('Flexi','Premium','Access'))
    AND (jc.level LIKE '%$cur_level%' )
    AND (jc.status IN ('Planning' , 'In Progress'))
    AND (t.id = '{$student['center_id']}')
    AND (t.team_type = 'Adult')
    GROUP BY m.id , m.name , m.week_date ,  m.date_start , jc.id , jc.name , jc.class_code , jc.max_size , cr.name , ct.full_teacher_name , t.name
    ORDER BY date_start ASC";
    $result = $GLOBALS['db']->fetchArray($q2);
    if (empty($result)) {
        return array(
            "success" => "0",
            "notify" => 'We are sorry, no lessons are available at your level!',
            "notify_vi" => 'Không có buổi học nào phù hợp với level của bạn.',
        );
    } else {
        return array(
            "success" => "1",
            "value_list" => json_encode($result),
        );
    }
}

function inputBooking($param)
{
    require_once('custom/include/_helper/junior_class_utils.php');
    global $timedate;

    $student_id = $param['student_id'];
    $session_id = $param['session_id'];
    //check so luong hoc vien trong lop
    $sql_remain_slot = "SELECT
    c.max_size - COUNT(mc.id) remain_slot, c.class_type_adult
    FROM
    meetings m
    INNER JOIN
    j_class c ON c.id = m.ju_class_id
    INNER JOIN
    meetings_contacts mc ON mc.meeting_id = m.id AND mc.deleted = 0
    AND m.id = '$session_id'
    GROUP BY m.id";
    $result = reset($GLOBALS['db']->fetchArray($sql_remain_slot));
    if (!empty($result) && $result['remain_slot'] <= 0 && $result['class_type_adult'] <> "Connect Event")
        return array(
            "success" => "0",
            "notify" => "This session was full.",
            "notify_vn" => "Buổi học này đã hết chỗ."
        );

    //Check payment
    $sql_check_booked = "SELECT
    id
    FROM
    meetings_contacts
    WHERE
    contact_id = '$student_id'
    AND meeting_id = '$session_id'
    AND deleted = 0";
    if (!empty($GLOBALS['db']->getOne($sql_check_booked))) {
        return array(
            "success" => "0",
            "notify" => "You have completed registering this lesson.",
            "notify_vi" => "Bạn đã đăng ký buổi học này trước đây.",
        );
    }
    $sql_session_info = "SELECT
    m.ju_class_id class_id,
    jc.class_type_adult class_type,
    m.till_hour till_hour,
    m.delivery_hour delivery_hour,
    DATE_ADD(m.date_start, INTERVAL 7 HOUR) date_start,
    current_timestamp() cur_time
    FROM
    meetings m
    INNER JOIN
    j_class jc ON jc.id = m.ju_class_id
    WHERE
    m.id = '$session_id'";
    $ssi = $GLOBALS['db']->fetchArray($sql_session_info);
    $session_info = reset($ssi);
    $class_type = $session_info['class_type'];
    $ext_class_type = '';
    if (strtotime($session_info['date_start']) <= strtotime($session_info['cur_time']))
        return array(
            "success" => "0",
            "notify" => "This session has been started.",
        );
    switch ($class_type) {
        case 'Skill':
            $ext_class_type = " jp.number_of_skill";
            break;
        case 'Connect Club':
            $ext_class_type = " jp.number_of_connect";
            break;
        case 'Connect Event':
            $ext_class_type = " jp.number_of_connect";
            break;
        default;
            return array(
                "success" => "0",
                "notify" => "This class type has not support booking yet.",
            );
    }
    $sql_payment_info = "SELECT
    jp.id  payment_id
    FROM
    j_payment jp
    INNER JOIN
    contacts_j_payment_1_c cjp ON cjp.contacts_j_payment_1j_payment_idb = jp.id
    AND cjp.deleted = 0
    AND jp.deleted = 0
    AND jp.payment_type IN ('Cashholder','Corporate')
    #AND jp.status <> 'Closed'
    AND cjp.contacts_j_payment_1contacts_ida = '$student_id'
    AND (('{$session_info['date_start']}' BETWEEN jp.start_study AND jp.end_study)
    OR  ('{$session_info['date_start']}' <= date_add(jp.payment_date, interval jp.total_hours day)))
    LEFT JOIN
    (SELECT
    jss.payment_id, COUNT(mc.id) used_session
    FROM
    meetings_contacts mc
    INNER JOIN meetings m ON m.id = mc.meeting_id AND m.deleted = 0
    AND mc.deleted = 0
    INNER JOIN j_class jc ON jc.id = m.ju_class_id
    AND jc.class_type_adult = ''
    AND mc.contact_id = ''
    INNER JOIN j_studentsituations jss ON jss.id = mc.situation_id
    AND jss.deleted = 0
    GROUP BY jss.payment_id) cs ON cs.payment_id = jp.id
    WHERE
    (IFNULL(cs.used_session, 0) < $ext_class_type
    OR $ext_class_type = 0)";
    $payment_info = $GLOBALS['db']->getOne($sql_payment_info);
    if (empty($payment_info)) {
        return array(
            "success" => "0",
            "notify" => "You do not have any available payments.",
            "notify_vi" => "Bạn không có khoản thanh toán nào đủ điều kiện.",
        );
    }

    $payment = BeanFactory::getBean('J_Payment', $payment_info);

    $unit_price = ($payment->payment_amount + $payment->deposit_amount + $payment->paid_amount) / ($payment->total_hours);

    $situ = new J_StudentSituations();
    $situ->id = create_guid();
    $situ->new_with_id = true;
    addJunToSession($situ->id, $session_id, $student_id);
    $total_hours = $session_info['delivery_hour'];

    $situ->total_amount = format_number(($total_hours * $unit_price));//Doanh thu theo gio
    $situ->total_hour = format_number($total_hours, 2, 2);

    $situ->start_study = $session_info['date_start'];
    $situ->end_study = $session_info['date_start'];

    $rs = $GLOBALS['db']->query("SELECT CONCAT(IFNULL(last_name, ''),' ',IFNULL(first_name, '')) name FROM contacts WHERE id = '$student_id' AND deleted = 0");
    $row_stu = $GLOBALS['db']->fetchByAssoc($rs);

    $situ->name = $row_stu['name'];
    $situ->student_type = 'Student';
    $situ->type = 'Enrolled';
    $situ->student_id = $student_id;
    $situ->ju_class_id = $session_info['class_id'];
    $situ->payment_id = $payment->id;
    $situ->start_hour = $session_info['till_hour'] - $session_info['delivery_hour'];

    $situ->assigned_user_id = $payment->assigned_user_id;
    $situ->team_id = $payment->team_id;
    $situ->team_set_id = $payment->team_id;
    $situ->save();
    $sql_check_class_ref = "SELECT
    id
    FROM
    j_class_contacts_1_c
    WHERE
    deleted = 0
    AND j_class_contacts_1contacts_idb = '$student_id'
    AND j_class_contacts_1j_class_ida = '{$session_info['class_id']}'";
    $result = $GLOBALS['db']->getOne($sql_check_class_ref);
    if (empty($result)) {
        $sql_insert = "INSERT INTO j_class_contacts_1_c
        VALUES ('" . create_guid() . "',CURRENT_TIMESTAMP(),0,'{$session_info['class_id']}','$student_id')";
        $GLOBALS['db']->query($sql_insert);
    }
    return array(
        "success" => "1",
        "notify" => "Successful registration.",
        "notify_vi" => "Đăng ký thành công.",
    );
}

function checkDuplication($param)
{
    global $timedate;
    //Prepare WHERE
    $name           = $param['student_name'];
    $phone_number   = $param['phone'];
    $email_address  = trim($param['email'], ' ');
    $birthdate      = $param['birth_date'];

    $ext_lead_name = '';
    $ext_student_name = '';
    if(!empty($name)){
        $ext_lead_name      = " AND (ts.full_lead_name = '$name')";
        $ext_student_name   = " AND (ts.full_student_name = '$name')";
    }

    $ext_mobile = '';
    if(!empty($phone_number))
        $ext_mobile = " AND (ts.phone_mobile = '$phone_number')";

    $ext_birthdate = '';
    if(!empty($birthdate))
        $ext_birthdate = " AND (ts.birthdate = '$birthdate')";

    $ext_email = '';
    if(!empty($email_address) && empty($birthdate))
        $ext_email = " INNER JOIN
        email_addr_bean_rel l1_1 ON ts.id = l1_1.bean_id
        AND l1_1.deleted = 0
        AND l1_1.primary_address = '1'
        INNER JOIN
        email_addresses l1 ON l1.id = l1_1.email_address_id
        AND l1.deleted = 0 AND (l1.email_address = '$email_address')";
    //SQL STUDENT
    $sql_student = "SELECT DISTINCT
    IFNULL(ts.id, '') primaryid,
    IFNULL(ts.full_student_name, '') name,
    IFNULL(l2.id, '') team_id,
    IFNULL(l2.name, '') team_name
    FROM
    contacts ts
    $ext_email
    INNER JOIN
    teams l2 ON ts.team_id = l2.id AND l2.deleted = 0
    WHERE
    ts.deleted = 0
    $ext_student_name
    $ext_mobile
    $ext_birthdate";

    //SQL LEAD
    $sql_lead = "SELECT DISTINCT
    IFNULL(ts.id, '') primaryid,
    IFNULL(ts.full_lead_name, '') name,
    IFNULL(l2.id, '') team_id,
    IFNULL(l2.name, '') team_name
    FROM
    leads ts
    $ext_email
    INNER JOIN
    teams l2 ON ts.team_id = l2.id AND l2.deleted = 0
    WHERE
    ts.deleted = 0
    $ext_lead_name
    $ext_mobile
    $ext_birthdate";

    $rs1        = $GLOBALS['db']->query($sql_student);
    $student    = $GLOBALS['db']->fetchByAssoc($rs1);
    if (!empty($student)){
        return array(
            "success"       => "0", //Duplicated Student
            "module"        => 'Contacts',
            "center"        => $student['team_name'],
            "student_id"    => $student['primaryid'],
            "student_name"  => $student['name'],
        );
    }else{
        $rs2    = $GLOBALS['db']->query($sql_lead);
        $lead   = $GLOBALS['db']->fetchByAssoc($rs2);
        if(!empty($lead)){
            return array(
                "success"       => "0", //Duplicated Lead
                "module"        => 'Leads',
                "center"        => $lead['team_name'],
                "student_id"    => $lead['primaryid'],
                "student_name"  => $lead['name'],
            );
        }
    }
    return array(
        "success" => "1",
    );
}

//comment by Lam Hoang
/*
function getSSOCode($param){
global $timedate;

$student_id     = $param['student_id'];
$start_db       = date('Y-m-d H:i:s',strtotime("-7day - 7hour ".$timedate->nowDbDate()));
$end_db         = date('Y-m-d H:i:s',strtotime("+7day - 7hour ".$timedate->nowDbDate()));

$sql = "SELECT DISTINCT
IFNULL(meetings.sso_code, '') sso_code
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
((((meetings.date_start >= '$start_db'
AND meetings.date_start <= '$end_db'))
AND (meetings.meeting_type = 'Session')
AND (l1.class_type_adult = 'Practice')
AND (l2.id = '$student_id')))
AND meetings.deleted = 0";
$rs = $GLOBALS['db']->query($sql);

$sso_arr    = $GLOBALS['app_list_strings']['map_sso_code_dom'];
$sso_return = array();
while($row = $GLOBALS['db']->fetchByAssoc($rs)){
$sso_return[$row['sso_code']] = array_search($row['sso_code'], $sso_arr);
}
return array(
"success" => "1",
"value_list" => json_encode($sso_return),
);
}
*/

function getSSOCode($param)
{
    $student_id = $param['student_id'];
    $add_week = $GLOBALS['db']->fetchArray("SELECT additional_week, show_add_week FROM contacts WHERE id = '$student_id'");
    if ($add_week[0]['show_add_week'] == 0)
        $ext_where = "WHERE (CURDATE() BETWEEN convert(jss.date_entered, date) AND jc.end_date)";
    else $ext_where = "WHERE IFNULL(als.end_date, date_add(jc.end_date, interval {$add_week[0]['additional_week']}*7 day)) >= CURDATE()";
    $sql = "SELECT
    m.id,
    m.ju_class_id sis_class_id,
    IFNULL(m.sso_code, '') sso_code,
    ac.name classroom_name,
    ac.id class_room_id,
    DATE_ADD(m.date_start, INTERVAL 7 HOUR) date_start,
    current_timestamp() cur_time,
    IFNULL(als.start_date,CASE
    WHEN
    DATE_ADD(jc.start_date,
    INTERVAL - 7 DAY) >= CONVERT( DATE_ADD(jss.date_entered,
    INTERVAL 7 HOUR) , DATE)
    THEN
    DATE_ADD(jc.start_date,
    INTERVAL - 7 DAY)
    ELSE CONVERT( DATE_ADD(jss.date_entered,
    INTERVAL 7 HOUR) , DATE)
    END) AS start_study,
    IFNULL(als.end_date,date_add(jc.end_date,interval {$add_week[0]['additional_week']}*7 day)) end_study,
    als.end_date
    FROM
    meetings m
    INNER JOIN
    j_class jc ON jc.id = m.ju_class_id AND m.deleted = 0
    AND m.sso_code <> ''
    AND jc.deleted = 0
    AND jc.class_type_adult = 'Practice'
    AND m.session_status <> 'Cancelled'
    #AND DATEDIFF(CONVERT(DATE_ADD(m.date_start, INTERVAL 7 HOUR), date) , current_date()) <= 7
    INNER JOIN
    j_studentsituations jss ON jss.ju_class_id = jc.id
    AND jss.deleted = 0
    #AND (CURDATE() BETWEEN convert(jss.date_entered, date) AND jc.end_date)
    AND jss.student_id = '$student_id'
    LEFT JOIN
    alpha_classroom ac ON m.sso_code = ac.sso_code
    left join alpha_students als on als.sis_student_id = jss.student_id and als.sis_class_id = jc.id and als.session_id = m.id
    $ext_where
    ORDER BY date_start DESC";
    $rs = $GLOBALS['db']->query($sql);
    //    $sso_arr    = $GLOBALS['app_list_strings']['map_sso_code_dom'];
    //    $classid_arr = $GLOBALS['app_list_strings']['map_class_room_id_dom'];
    $sso_return = array();
    //    $valid_sso = array();
    //    $next = true;
    while ($row = $GLOBALS['db']->fetchByAssoc($rs)) {
        //        if($next){
        //            $sso_return[$row['sso_code']]['class_room_name'] = array_search($row['sso_code'], $sso_arr);
        $sso_return[$row['sso_code']]['class_room_name'] = $row['classroom_name'];
        $sso_return[$row['sso_code']]['class_room_id'] = $row['class_room_id'];
        //            $sso_return[$row['sso_code']]['class_room_id'] = array_search($row['sso_code'], $classid_arr);
        $sso_return[$row['sso_code']]['session_id'] = $row['id'];
        $sso_return[$row['sso_code']]['sis_class_id'] = $row['sis_class_id'];
        $sso_return[$row['sso_code']]['start_study'] = $row['start_study'];
        $sso_return[$row['sso_code']]['end_study'] = $row['end_study'];
        //    if($row['date_start'] > $row['cur_time'])
        //                $next = false;
        //        }
    }
    /*if(isset($sso_return['APOLLO_E4L18']))
    unset($sso_return['APOLLO_E4L18']);*/
    if (!empty($sso_return)) {
        return array(
            "success" => "1",
            "value_list" => json_encode($sso_return),
        );
    } else {
        return array(
            "success" => "0",
            "notify" => "Your E-learning lessons are only activated 7 days before a class’ startdate.",
            "notify_vi" => "Bạn chỉ được học trực tuyến trước khi buổi học diễn ra 7 ngày.",
        );
    }

}

function cancelBooking($param)
{
    $student_id = $param['student_id'];
    $session_id = $param['session_id'];

    $sql_check_time = "SELECT
    m.id session_id, mc.situation_id situation_id
    FROM
    meetings_contacts mc
    INNER JOIN
    meetings m ON m.id = mc.meeting_id AND m.deleted = 0
    AND mc.deleted = 0
    AND m.id = '$session_id'
    AND mc.contact_id = '$student_id'
    AND DATE_ADD(m.date_start, INTERVAL - 17 HOUR) > CURRENT_TIMESTAMP()";

    $rs = $GLOBALS['db']->fetchArray($sql_check_time);
    if (!empty($rs)) {
        $rs1 = reset($rs);
        removeJunFromSession($rs1['situation_id'], $session_id);
        $check_situ = $GLOBALS['db']->getOne("SELECT count(id) FROM meetings_contacts WHERE situation_id = '{$rs1['situation_id']}' and deleted = 0");
        if ($check_situ == 0) {
            $GLOBALS['db']->query("delete from j_studentsituations where id = '{$rs1['situation_id']}'");
        }
        return array(
            "success" => "1",
            "notify" => "Successful cancellation.",
            "notify_vi" => "Hủy đăng ký thành công.",
        );
    } else {
        return array(
            "success" => "0",
            "notify" => "You can only cancel your registration at least 24 hours before a class starts.",
            "notify_vi" => "Bạn phải hủy đăng ký ít nhất 24h trước khi buổi học bắt đầu."
        );
    }
}

function historyBooking($param)
{
    $student_id = $param['student_id'];
    $sql = "SELECT
    m.id session_id,
    jc.class_type_adult type,
    CONCAT(CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , TIME),
    '-',
    CONVERT( DATE_ADD(m.date_end, INTERVAL 7 HOUR) , TIME)) schedule_time,
    IFNULL(m.name, '') topic_name,
    IFNULL(ct.full_teacher_name, '') teacher_name,
    DAYNAME(DATE_ADD(m.date_start, INTERVAL 7 HOUR)) day_name,
    CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , DATE) schedule_date,
    t.name center_name,
    IFNULL(cr.name, '') room_name
    FROM
    meetings_contacts mc
    INNER JOIN
    meetings m ON m.id = mc.meeting_id AND m.deleted = 0
    AND mc.deleted = 0
    INNER JOIN
    j_class jc ON m.ju_class_id = jc.id AND jc.deleted = 0
    AND jc.class_type_adult IN ('Skill' , 'Connect Club', 'Connect Event')
    INNER JOIN
    teams t ON t.id = jc.team_id
    LEFT JOIN
    c_teachers ct ON ct.id = m.teacher_id
    LEFT JOIN
    c_rooms cr ON cr.id = m.room_id
    WHERE mc.contact_id = '$student_id'";
    $rs = $GLOBALS['db']->fetchArray($sql);
    if (empty($rs)) {
        return array(
            "success" => "0",
            "notify" => "You have not registered for any classes or you have cancelled all the registrations.",
            "notify_vi" => "Bạn chưa đăng ký buổi học nào hoặc đã hủy tất cả đăng ký.",
        );
    } else {
        return array(
            "success" => "1",
            "value_list" => json_encode($rs),
        );
    }
}

function getPaymentList($param)
{
    $student_id = $param['student_id'];
    $sql = "SELECT
    jp.id payment_id,
    jp.payment_type,
    #jp.name payment_name,
    jp.payment_amount + deposit_amount + paid_amount AS total_amount,
    jp.payment_amount AS payment_amount,
    jp.deposit_amount + jp.paid_amount AS freebalance_amount,
    IFNULL(jpjp.amount, 0) used_amount,
    case when jp.start_study = '0000-00-00' then '' else jp.start_study end start_date,
    case when jp.end_study = '0000-00-00' then jp.payment_expired else jp.end_study end payment_expired_date,
    total_hours AS total_days,
    CASE
    WHEN
    jp.start_study > CURDATE()
    THEN
    DATEDIFF(jp.end_study, jp.start_study) + 1 - (SELECT
    COUNT(id)
    FROM
    holidays
    WHERE
    deleted = 0 AND apply_for = 'apollo_360'
    AND LENGTH(teacher_id) < 36
    AND type = 'Public Holiday'
    AND (holiday_date BETWEEN jp.start_study AND jp.end_study))
    ELSE DATEDIFF(jp.end_study, CURDATE()) + 1 - (SELECT
    COUNT(id)
    FROM
    holidays
    WHERE
    deleted = 0 AND apply_for = 'apollo_360'
    AND LENGTH(teacher_id) < 36
    AND type = 'Public Holiday'
    AND (holiday_date BETWEEN CURDATE() AND jp.end_study))
    END AS remain_days,
    IFNULL(jcf.name, '') course_fee_name,
    u.full_user_name ec_name,
    t.name  center_name
    FROM
    j_payment jp
    INNER JOIN
    contacts_j_payment_1_c cjp ON cjp.contacts_j_payment_1j_payment_idb = jp.id
    AND jp.deleted = 0
    AND cjp.deleted = 0
    AND cjp.contacts_j_payment_1contacts_ida = '$student_id'
    INNER JOIN
    users u ON u.id = jp.created_by
    INNER JOIN
    teams t ON t.id = jp.team_id
    LEFT JOIN
    (SELECT
    j_payment_j_payment_1j_payment_idb used_id,
    SUM(amount) amount
    FROM
    j_payment_j_payment_1_c
    WHERE
    deleted = 0
    GROUP BY j_payment_j_payment_1j_payment_idb) jpjp ON jpjp.used_id = jp.id
    LEFT JOIN
    j_paymentdetail jpd ON jpd.payment_id = jp.id
    AND jpd.deleted = 0
    LEFT JOIN
    j_coursefee_j_payment_1_c jcfjp ON jcfjp.j_coursefee_j_payment_1j_payment_idb = jp.id
    AND jcfjp.deleted = 0
    LEFT JOIN
    j_coursefee jcf ON jcf.id = jcfjp.j_coursefee_j_payment_1j_coursefee_ida
    AND jcf.deleted = 0
    WHERE
    jp.payment_type in ('Deposit','Cashholder','Transfer In')
    AND IFNULL(jpd.status, 'Paid') = 'Paid'
    ORDER BY jp.payment_date";
    $result = $GLOBALS['db']->fetchArray($sql);
    if (!empty($result)) {
        return array(
            "success" => "1",
            "value_list" => json_encode($result),
        );
    } else {
        return array(
            "success" => "0",
            "notify" => "You do not have any available payments.",
            "notify_vi" => "Bạn chưa có khoản thanh toán nào.",
        );
    }

}

?>
