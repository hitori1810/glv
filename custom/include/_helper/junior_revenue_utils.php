<?php
/**
* GET LIST: DOANH THU THEO LỚP THEO HỌC VIÊN
* @param start_display    	: Ngày bắt đầu trên hệ CRM
* @param end_display		: Ngày kết thúc trên CRM
* @param class_id   		: ID lớp
* @param student_id       	: ID Học viên
* @param situation_type     : Loại doanh thu cần lấy 'Enrolled', 'Outstanding'
*/
require_once('custom/include/_helper/junior_schedule.php');
function get_list_revenue($student_id = '', $situation_type = "'Enrolled'", $start_display = '', $end_display = '', $class_id = '', $situation_id = '', $team_id = '', $payment_id = '', $is_not_payment = false, $status = '', $check_sale_type = false){
    global $timedate;

    $ext_student = "AND (l1_1.contact_id = '$student_id')";
    if(empty($student_id))
        $ext_student = "";

    $ext_situation = "AND l3.type IN($situation_type)";
    if($situation_type == "All" || empty($situation_type))
        $ext_situation = "";

    if(!empty($start_display)){
        $start_tz 	= date('Y-m-d H:i:s',strtotime("-7 hours ".$timedate->to_db_date($start_display,false)." 00:00:00"));
        $ext_start = "AND (meetings.date_start >= '$start_tz')";
    }else $ext_start = '';

    if(!empty($end_display)){
        $end_tz 	= date('Y-m-d H:i:s',strtotime("-7 hours ".$timedate->to_db_date($end_display	,false)." 23:59:59"));
        $ext_end = "AND (meetings.date_end <= '$end_tz')";
    }else $ext_end = '';

    $ext_class = "AND (l2.id = '$class_id')";
    if(empty($class_id))
        $ext_class = "";

    $ext_situation_id = "AND (l3.id = '$situation_id')";
    if(empty($situation_id))
        $ext_situation_id = "";

    if (!empty($team_id))
        $ext_team = " AND (l5.id = '$team_id')";
    else $ext_team = " ";
    
    if($GLOBALS['current_user']->is_admin <> "1"){
        $ext_team .= " AND ((meetings.team_set_id IN (SELECT
        tst.team_set_id
        FROM
        team_sets_teams tst
        INNER JOIN
        team_memberships team_memberships ON tst.team_id = team_memberships.team_id
        AND team_memberships.user_id = '{$GLOBALS['current_user']->id}'
        AND team_memberships.deleted = 0)))";
    }

    if(is_array($payment_id) && !empty($payment_id)){
        $ext_payment = "AND (l4.id IN('".implode("','",$payment_id)."'))";
    }else{
        $ext_payment = "AND (l4.id = '$payment_id')";
        if(empty($payment_id))
            $ext_payment = "";
        if($is_not_payment && !empty($payment_id))
            $ext_payment = "AND (l4.id <> '$payment_id')";
    }

    $ext_status = "AND (l2.status <> '$status')";
    if(empty($status))
        $ext_status = "";

    $select_date = "meetings.date_start date_start,";
    //Set Revenue Settle
    if($situation_type == "'Settle'"){
        if(!empty($end_display)){
            $end_tz     = $timedate->to_db_date($end_display    ,false);
            $ext_end = "AND (l4.settle_date <= '$end_tz')";
        }else $ext_end = '';
        if(!empty($start_display)){
            $start_tz     = $timedate->to_db_date($start_display,false);
            $ext_start = "AND (l4.settle_date >= '$start_tz')";
        }else $ext_start = '';
        $select_date = "l4.settle_date date_start,";
    }
    $ext_check_sale_type = "";
    if($check_sale_type)    $ext_check_sale_type = "AND l4.kind_of_course NOT IN ('Outing Trip','Cambridge') AND l4.sale_type IN ('New Sale', 'Retention', 'Not set')";
    $q1 = "SELECT DISTINCT
    IFNULL(meetings.id, '') primaryid,
    $select_date
    meetings.date_end date_end,
    meetings.duration_cal duration_hour,
    meetings.delivery_hour delivery_hour,
    IFNULL(meetings.till_hour, 0) till_hour,
    IFNULL(l3.id, '') situation_id,
    l3.type situation_type,
    IFNULL(l1_1.contact_id, '') student_id,
    IFNULL(l4.id, '') ju_payment_id,
    IFNULL(l4.kind_of_course, '') kind_of_course,
    (CASE
    WHEN (IFNULL(l4.payment_amount, 0) > IFNULL(SUM(l6.payment_amount), 0)) THEN 'Unpaid'
    ELSE 'Paid'
    END) as revenue_status,
    IFNULL(l5.id, '') team_id,
    l3.total_amount total_amount,
    l3.total_hour total_hour,
    IFNULL((total_amount/total_hour), 0) cost_per_hour,
    IFNULL((total_amount/total_hour * delivery_hour), 0) delivery_revenue
    FROM
    meetings
    INNER JOIN
    meetings_contacts l1_1 ON meetings.id = l1_1.meeting_id
    AND l1_1.deleted = 0
    $ext_student
    INNER JOIN
    contacts l1 ON l1.id = l1_1.contact_id
    AND l1.deleted = 0
    INNER JOIN
    j_class l2 ON meetings.ju_class_id = l2.id
    $ext_class
    $ext_status
    AND l2.deleted = 0
    INNER JOIN
    j_studentsituations l3 ON l1_1.situation_id = l3.id
    AND l3.deleted = 0 $ext_situation
    $ext_situation_id
    LEFT JOIN
    j_payment l4 ON l3.payment_id = l4.id
    AND l4.deleted = 0
    LEFT JOIN
    j_paymentdetail l6 ON l6.payment_id = l4.id
    AND l6.deleted = 0 AND l6.status = 'Paid'
    INNER JOIN
    teams l5 ON meetings.team_id = l5.id
    AND l5.deleted = 0 AND l5.team_type = 'Junior'
    WHERE
    ((meetings.deleted = 0
    $ext_team
    $ext_start
    $ext_end
    $ext_payment
    AND (meetings.session_status <> 'Cancelled')))
    $ext_check_sale_type
    GROUP BY primaryid, situation_id
    ORDER BY date_start ASC";
    return $GLOBALS['db']->fetchArray($q1);
}

/**
* GET LIST: DOANH THU THEO LỚP THEO SITUATION THEO HỌC VIÊN ...
* @param start_display    	: Ngày bắt đầu trên hệ CRM
* @param end_display		: Ngày kết thúc trên CRM
* @param class_id   		: ID lớp
* @param student_id       	: ID Học viên
* @param situation_type     : Loại doanh thu cần lấy 'Enrolled', 'Outstanding'
*/
function get_total_revenue($student_id = '', $situation_type = "'Enrolled'", $start_display = '', $end_display = '', $class_id = '', $situation_id = '', $payment_id = '', $not_status = ''){
    global $timedate;

    $ext_status = '';
    if(!empty($not_status))
        $ext_status = "AND l2.status <> '$not_status'";

    $ext_student = "AND (l1_1.contact_id = '$student_id')";
    if(empty($student_id))
        $ext_student = "";

    $ext_situation = "AND l3.type IN($situation_type)";
    if($situation_type == "All" || empty($situation_type))
        $ext_situation = "";

    if(!empty($start_display)){
        $start_tz 	= date('Y-m-d H:i:s',strtotime("-7 hours ".$timedate->to_db_date($start_display,false)." 00:00:00"));
        $ext_start = "AND (meetings.date_start >= '$start_tz')";
    }else $ext_start = '';

    if(!empty($end_display)){
        $end_tz 	= date('Y-m-d H:i:s',strtotime("-7 hours ".$timedate->to_db_date($end_display	,false)." 23:59:59"));
        $ext_end = "AND (meetings.date_end <= '$end_tz')";
    }else $ext_end = '';

    $ext_class = "AND (l2.id = '$class_id')";
    if(empty($class_id))
        $ext_class = "";

    $ext_situation_id = "AND (l3.id = '$situation_id')";
    if(empty($situation_id))
        $ext_situation_id = "";

    $ext_payment_id = "AND (l4.id = '$payment_id')";
    if(empty($payment_id))
        $ext_payment_id = "";

    //	IFNULL((l4.deposit_amount + l4.paid_amount + IFNULL(SUM(l5.payment_amount), 0)),
    //0) total_amount,
    //IFNULL((total_amount / (l3.total_amount / l3.total_hour)),
    //0) total_hour,

    $q1 = "SELECT DISTINCT
    IFNULL(l2.id, '') class_id,
    IFNULL(l2.class_code, '') class_code,
    IFNULL(l2.name, '') class_name,
    l2.start_date class_start_date,
    l2.end_date class_end_date,
    IFNULL(l2.main_schedule, '') class_main_schedule,
    IFNULL(l2.short_schedule, '') class_short_schedule,
    l2.class_type class_type,
    IFNULL(l3.id, '') situation_id,
    l3.start_study start_study,
    l3.end_study end_study,
    l3.total_hour total_hour_situa,
    l3.total_amount total_amount_situa,
    IFNULL(SUM((l3.total_amount / l3.total_hour) * meetings.delivery_hour),
    0) total_revenue,
    IFNULL(SUM(meetings.delivery_hour), 0) total_revenue_hour,
    IFNULL(COUNT(meetings.id), 0) count_session
    FROM
    meetings
    INNER JOIN
    meetings_contacts l1_1 ON meetings.id = l1_1.meeting_id
    $ext_student
    AND l1_1.deleted = 0
    INNER JOIN
    contacts l1 ON l1.id = l1_1.contact_id
    AND l1.deleted = 0
    INNER JOIN
    j_class l2 ON meetings.ju_class_id = l2.id
    AND l2.deleted = 0
    $ext_status
    INNER JOIN
    j_studentsituations l3 ON l1_1.situation_id = l3.id
    AND l3.deleted = 0 $ext_situation
    LEFT JOIN
    j_payment l4 ON l3.payment_id = l4.id
    AND l4.deleted = 0
    WHERE
    ((meetings.deleted = 0

    $ext_start $ext_end
    $ext_class
    $ext_situation_id
    $ext_payment_id
    AND (meetings.session_status <> 'Cancelled')))
    GROUP BY situation_id";

    return $GLOBALS['db']->fetchArray($q1);
}

/**
* GET LIST: TỔNG SỐ GIỜ DẠY THEO LỚP
* @param start_display    	: Ngày bắt đầu trên hệ CRM
* @param end_display		: Ngày kết thúc trên CRM
* @param class_id   		: ID lớp
*/
function get_list_lesson_by_class($class_id, $start_display = '', $end_display = '', $arr_type = 'Standard', $not_status = 'Cancelled'){
    global $timedate;

    $ext_start = '';
    if(!empty($start_display)){
        $start_tz 	= date('Y-m-d H:i:s',strtotime("-7 hours ".$timedate->to_db_date($start_display,false)." 00:00:00"));
        $ext_start = "AND (meetings.date_start >= '$start_tz')";
    }
    $ext_end = '';
    if(!empty($end_display)){
        $end_tz 	= date('Y-m-d H:i:s',strtotime("-7 hours ".$timedate->to_db_date($end_display	,false)." 23:59:59"));
        $ext_end = "AND (meetings.date_end <= '$end_tz')";
    }
    $ext_class = "AND (l2.id = '$class_id')";

    $ext_status = "AND (meetings.session_status <> 'Cancelled')";
    if(empty($not_status))
        $ext_status = "";

    $q1 = "SELECT DISTINCT
    IFNULL(l2.id, '') class_id,
    IFNULL(l2.class_code, '') class_code,
    IFNULL(l2.name, '') class_name,
    l2.start_date class_start_date,
    l2.end_date class_end_date,
    l2.hours class_hour,
    IFNULL(meetings.id, '') primaryid,
    meetings.date_start date_start,
    meetings.date_end date_end,
    DATE(CONVERT_TZ(meetings.date_end,'+00:00','+7:00')) date,
    meetings.lesson_number lesson_number,
    IFNULL(meetings.till_hour, 0) till_hour,
    meetings.session_status session_status,
    IFNULL(meetings.delivery_hour, 0) delivery_hour
    FROM
    meetings
    INNER JOIN
    j_class l2 ON meetings.ju_class_id = l2.id
    AND l2.deleted = 0
    WHERE
    ((
    meetings.deleted = 0
    $ext_class
    $ext_start
    $ext_end
    $ext_status))
    ORDER BY date_start ASC";
    if($arr_type == 'Standard')
        return $GLOBALS['db']->fetchArray($q1);
    elseif($arr_type == 'VIP'){
        $rs1 = $GLOBALS['db']->query($q1);
        $row = array();
        while($ss = $GLOBALS['db']->fetchByAssoc($rs1))
            $row[$ss['primaryid']] = $ss['delivery_hour'];

        return $row;
    }
}


/**
* KIỂM TRA HỌC VIÊN CÓ TỒN TẠI TRONG KHOẢNG THỜI GIAN CỦA LỚP HAY KO
* @param start_display    	: Ngày bắt đầu trên hệ CRM
* @param end_display		: Ngày kết thúc trên CRM
* @param class_id   		: ID lớp
*/
function is_exist_in_class($student_id, $start_display, $end_display, $class_id, $situation_type = 'All'){
    $ses_list = get_list_revenue($student_id, $situation_type, $start_display, $end_display, $class_id);
    if(count($ses_list) > 0)
        return true;
    else return false;
}

/**
* KIỂM TRA LEAD CÓ TỒN TẠI TRONG KHOẢNG THỜI GIAN CỦA LỚP HAY KO
* @param Lead ID    		: Ngày bắt đầu trên hệ CRM
* @param class_id   		: ID lớp
*/
function is_exist_lead_in_class($lead_id, $class_id){
    $res = get_lead_in_class($lead_id, $class_id);
    if(count($res) > 0)
        return true;
    else return false;
}

/**
* GET LIST: Lead trong lớp
* @param Lead ID    		: Ngày bắt đầu trên hệ CRM
* @param class_id   		: ID lớp
*/
function get_lead_in_class($lead_id, $class_id){
    $ext_lead_id = '';
    if(!empty($lead_id))
        $ext_lead_id = "AND (l1.id = '$lead_id')";

    $ext_class_id = '';
    if(!empty($class_id))
        $ext_class_id = "AND (l2.id = '$class_id')";

    $q1 = "SELECT DISTINCT
    IFNULL(meetings.id, '') primaryid,
    IFNULL(l1_1.id, '') rel_id,
    IFNULL(meetings.name, '') meetings_name,
    meetings.date_start meetings_date_start,
    meetings.date_end meetings_date_end
    FROM
    meetings
    INNER JOIN
    meetings_leads l1_1 ON meetings.id = l1_1.meeting_id
    AND l1_1.deleted = 0
    INNER JOIN
    leads l1 ON l1.id = l1_1.lead_id AND l1.deleted = 0
    INNER JOIN
    j_class l2 ON meetings.ju_class_id = l2.id
    AND l2.deleted = 0
    WHERE
    ((meetings.deleted = 0
    $ext_lead_id
    $ext_class_id))";
    return $GLOBALS['db']->fetchArray($q1);
}

/**
* GET LIST: Payment Detail
*/
function get_list_payment_detail($payment_id, $team_id = '', $student_id = '',  $start_db = '', $end_db = '', $status = 'Paid', $type = ''){
    $ext_pay = '';
    if(!empty($payment_id))
        $ext_pay = "AND (l1.id = '$payment_id')";

    $ext_team = '';
    if(!empty($team_id))
        $ext_team = "AND (l2.id = '$team_id')";

    $ext_stu = '';
    if(!empty($student_id))
        $ext_stu = "AND (l3.id = '$student_id')";

    $ext_start = '';
    if(!empty($start_db))
        $ext_start = "AND (j_paymentdetail.payment_date >= '$start_db')";

    $ext_end = '';
    if(!empty($end_db))
        $ext_end = "AND (j_paymentdetail.payment_date <= '$end_db')";

    $ext_status = '';
    if(!empty($status))
        $ext_status = "AND (j_paymentdetail.status = '$status')";

    $ext_type = '';
    if(!empty($type))
        $ext_type = "AND (j_paymentdetail.type = '$type')";

    $q1 = "SELECT DISTINCT
    IFNULL(j_paymentdetail.id, '') primaryid,
    IFNULL(j_paymentdetail.name, '') name,
    j_paymentdetail.payment_no payment_no,
    IFNULL(j_paymentdetail.invoice_number, '') invoice_number,
    IFNULL(j_paymentdetail.payment_method, '') payment_method,
    j_paymentdetail.before_discount before_discount,
    j_paymentdetail.discount_amount discount_amount,
    j_paymentdetail.sponsor_amount sponsor_amount,
    j_paymentdetail.payment_amount payment_amount,
    IFNULL(j_paymentdetail.type, '') type,
    j_paymentdetail.payment_method_fee payment_method_fee,
    j_paymentdetail.payment_date payment_date,
    IFNULL(l1.id, '') payment_id,
    l1.payment_amount payment_payment_amount,
    IFNULL(l2.id, '') team_id,
    IFNULL(l3.id, '') student_id,
    l3.full_student_name student_name
    FROM
    j_paymentdetail
    INNER JOIN
    j_payment l1 ON j_paymentdetail.payment_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    teams l2 ON j_paymentdetail.team_id = l2.id
    AND l2.deleted = 0
    LEFT JOIN
    contacts_j_payment_1_c l3_1 ON l1.id = l3_1.contacts_j_payment_1j_payment_idb
    AND l3_1.deleted = 0
    LEFT JOIN
    contacts l3 ON l3.id = l3_1.contacts_j_payment_1contacts_ida
    AND l3.deleted = 0
    WHERE
    (((j_paymentdetail.deleted = 0)
    $ext_pay
    $ext_team
    $ext_stu
    $ext_start
    $ext_end
    $ext_status
    $ext_type))";
    return $GLOBALS['db']->fetchArray($q1);
}

/**
* update lesson number for class
* Update Start End Date for class
*
* @param mixed $class_id
*
* @author Trung Nguyen 2015.12.12
*/
function updateClassSession($class_id, $koc_id) {
    //Update lesson no
    $q1 = "SELECT DISTINCT
    IFNULL(meetings.id, '') primaryid,
    meetings.date_start date_start,
    meetings.date_end date_end,
    meetings.lesson_number lesson_number,
    meetings.delivery_hour delivery_hour
    FROM
    meetings
    WHERE
    ((meetings.deleted = 0
    AND (meetings.session_status <> 'Cancelled')
    AND (meetings.ju_class_id = '$class_id') ))
    ORDER BY date_start ASC";
    $ss_list            = $GLOBALS['db']->fetchArray($q1);
    $till               = 0;

    //Update Syllabus Defaut
    $is_update_syl = false;
    $content_syl = $GLOBALS['db']->getOne("SELECT syllabus FROM j_kindofcourse WHERE id = '$koc_id'");
    if(!empty($content_syl)){
        $is_update_syl  = true;
        $json_syl       = json_decode(html_entity_decode($content_syl),true);
        $arr_syl        = array();
        foreach ($json_syl as $value)
            $arr_syl[$value['lesson']] = $value['content'];

    }
    $week_no = 0;
    for($i = 0; $i < count($ss_list); $i++){
        $current_week_no = (int)date('W',strtotime("+7 hours ".$ss_list[$i]['date_start']));
        if($last_week_no != $current_week_no){
            $week_no++;
            $last_week_no = $current_week_no;
        }

        $lesson = $i+1;
        $ext_lesson = ", lesson_number= '$lesson'";

        $ext_syl = '';
        if($is_update_syl)
            $ext_syl = ", syllabus_default = '{$arr_syl[$lesson]}'";

        //Update till hours
        $till += $ss_list[$i]['delivery_hour'];

        $q2 = "UPDATE meetings SET till_hour=$till, week_no='W$week_no' $ext_lesson $ext_syl WHERE id='{$ss_list[$i]['primaryid']}'";
        $GLOBALS['db']->query($q2);
    }


    if($bug_lesson_count > 0){
        for($i = 0; $i < count($ss_list); $i++){
            $lesson = $i+1;
            if($is_update_syl)
                $ext_syl = 'syllabus_default =  ';

            $q3 = "UPDATE meetings SET lesson_number='$lesson' WHERE id='{$ss_list[$i]['primaryid']}'";
            $GLOBALS['db']->query($q3);
        }
    }

    //Return DB class start - end date
    $rsClass = array();
    $first = reset($ss_list);
    $start_date = date('Y-m-d',strtotime("+7 hours ".$first['date_start']));

    $last = end($ss_list);
    $end_date = date('Y-m-d',strtotime("+7 hours ".$last['date_start']));

    return array(
        'start_date' => $start_date,
        'end_date'   => $end_date
    );
}

//////////////// CHECK NEW SALE -  BY LAP NGUYEN \\\\\\\\\\\\\\\\\\\\\\\\\
function checkSaleType($payment_id, $pmd_db_date = '', $payment_type = ''){
    global $timedate;
    $ext_koc = '';
    $ext_select = "IFNULL(j_payment.kind_of_course, '') kind_of_course,";
    if($payment_type == 'Enrollment') {
        $ext_koc = "INNER JOIN
        j_studentsituations ss ON j_payment.id = ss.payment_id
        AND ss.deleted = 0
        INNER JOIN
        j_class c ON ss.ju_class_id = c.id
        AND c.deleted = 0
        LEFT JOIN
        j_kindofcourse l3 ON c.koc_id = l3.id AND l3.deleted = 0";
        $ext_select =   "IFNULL(l3.name, '') kind_of_course,";
    }

    $q1 = "SELECT DISTINCT
    IFNULL(j_payment.id, '') payment_id,
    j_payment.tuition_hours tuition_hours,
    j_payment.payment_date payment_date,
    j_payment.payment_amount payment_amount,
    j_payment.paid_amount paid_amount,
    j_payment.deposit_amount deposit_amount,
    IFNULL(j_payment.payment_type, '') payment_type,
    $ext_select
    IFNULL(l1.id, '') team_id,
    IFNULL(l2.id, '') student_id
    FROM
    j_payment
    INNER JOIN
    teams l1 ON j_payment.team_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    contacts_j_payment_1_c l2_1 ON j_payment.id = l2_1.contacts_j_payment_1j_payment_idb
    AND l2_1.deleted = 0
    INNER JOIN
    contacts l2 ON l2.id = l2_1.contacts_j_payment_1contacts_ida
    AND l2.deleted = 0
    $ext_koc
    WHERE
    (((j_payment.id = '$payment_id')))
    AND j_payment.deleted = 0";
    $rs1 = $GLOBALS['db']->query($q1);
    $row = $GLOBALS['db']->fetchByAssoc($rs1);

    if($row['kind_of_course'] == 'Outing Trip' ||$row['kind_of_course'] == 'Cambridge' )
        return '';  //Outing, Cambridge => Not set

    $student_id = $row['student_id'];
    $db_payment_date = $row['payment_date'];
    if(empty($student_id))
        $sale_type = 'Not set';
    else{
        $basic_rule = newsaleSixMonth($student_id, $payment_id, $db_payment_date) && newsaleMovingTransfer($payment_id);
        $sale_type = 'Not set';
        if($basic_rule){
            if($row['payment_type'] == 'Enrollment'){
                $_target = getTargetNewSale($row['team_id']);
                if(($row['payment_amount'] + $row['paid_amount'] + $row['deposit_amount']) >= $_target){
                    if(newsaleRelPay24($row, $basic_rule, $pmd_db_date))
                        $sale_type = 'New Sale';
                    else $sale_type = 'Retention';
                }
            }elseif($row['payment_type'] == 'Deposit'){
                $_target = getTargetNewSale($row['team_id']);
                if($row['payment_amount'] >= $_target){
                    $sale_type = 'New Sale';
                }else{
                    $sale_type = 'Not set';
                }
                // else not set

            }elseif($row['payment_type'] == 'Cashholder'){
                $_target = $row['tuition_hours'];
                if($_target >= 24 ){
                    $sale_type = 'New Sale';
                }else{
                    $sale_type = 'Not set';
                }
                // else not set
            }
        }else
            $sale_type = 'Retention';

    }
    return $sale_type;
}
//Rule 1: Học viên không có lesson học trong 6tháng về trước
function newsaleSixMonth($student_id, $payment_id, $paymentDate){
    $listRevenue = get_list_revenue($student_id,"'Enrolled', 'Moving In', 'Settle'",'','','','','',$payment_id,true,'',true);
    if($listRevenue){
        end($listRevenue);
        $lastSessionEnd = date("Y-m-d",strtotime($listRevenue[key($listRevenue)]["date_end"].' + 7hour + 6months - 1day'));
        $paymentDateStr = date("Y-m-d",strtotime($paymentDate));
        if ($lastSessionEnd > $paymentDate)  return false;
        else return true;
    }else return true;
}

//Rule 2: Nếu sử dụng payment moving/transfer
function newsaleMovingTransfer($payment_id){
    $q1 = "SELECT DISTINCT
    COUNT(IFNULL(l1.id, '')) count
    FROM
    j_payment
    INNER JOIN
    j_payment_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_payment_j_payment_1j_payment_ida
    AND l1_1.deleted = 0
    INNER JOIN
    j_payment l1 ON l1.id = l1_1.j_payment_j_payment_1j_payment_idb
    AND l1.deleted = 0
    WHERE
    (((j_payment.id = '$payment_id')
    AND (l1.payment_type IN ('Transfer In', 'Transfer From AIMS'))))
    AND j_payment.deleted = 0";
    $count = $GLOBALS['db']->getOne($q1);
    if($count > 0) return false;
    else return true;
}

//Rule 3 - 4: Học viên đóng Enrollment có Dep/Cash < or <= 24h -> Sale Type là -none-:
function newsaleRelPay24($payment, $currentSaleType, $pmd_db_date = ''){
    global $timedate;    //get Team Code
    $q1 = "SELECT code_prefix FROM teams WHERE id = '{$payment['team_id']}'";
    $code_prefix = $GLOBALS['db']->getOne($q1);
    $mainday = (int)$GLOBALS['app_list_strings']['new_sale_range'][$code_prefix];
    if (empty($mainday) || !isset($mainday)) $mainday = 30;

    $payment_date_int = $payment['payment_date'];
    if($currentSaleType) $currentSaleType = 'New Sale';
    else $currentSaleType = 'Retention';
    $q1 = "SELECT DISTINCT
    IFNULL(l1.id, '') l1_id,
    IFNULL(l1.name, '') l1_name,
    l1.total_hours l1_total_hours,
    l1.payment_date l1_payment_date,
    l1.sale_type  l1_sale_type,
    l1.sale_type_date  l1_sale_type_date,
    l1.payment_amount l1_payment_amount
    FROM
    j_payment
    INNER JOIN
    j_payment_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_payment_j_payment_1j_payment_ida
    AND l1_1.deleted = 0
    INNER JOIN
    j_payment l1 ON l1.id = l1_1.j_payment_j_payment_1j_payment_idb
    AND l1.deleted = 0
    WHERE
    (((j_payment.id = '{$payment['payment_id']}')
    AND (l1.sale_type IN ('Retention', 'New Sale', 'Not set'))
    AND (l1.payment_type IN ('Deposit', 'Cashholder'))))
    AND j_payment.deleted = 0";
    $rs1 = $GLOBALS['db']->query($q1);
    while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
        $rel_date_int = date("Y-m-d",strtotime("+$mainday days ".$row['l1_payment_date']));
        if($row['l1_sale_type'] == 'Not set'){
            // TH 1 - 2
            if($currentSaleType == 'New Sale' && !empty($payment_date_int) && $payment_date_int > $rel_date_int)
                $currentSaleType =  'Retention';
            if(empty($pmd_db_date))
                $GLOBALS['db']->query("UPDATE j_payment SET sale_type = '$currentSaleType', sale_type_date = '{$payment['payment_date']}' WHERE id = '{$row['l1_id']}'");
            else
                $GLOBALS['db']->query("UPDATE j_payment SET sale_type = '$currentSaleType', sale_type_date = '$pmd_db_date' WHERE id = '{$row['l1_id']}'");

        }else{
            //TH3 & 4:
            if($currentSaleType == 'New Sale' && !empty($payment_date_int) && $payment_date_int > $rel_date_int)
                $currentSaleType =  'Retention';

            //TH5:
            if($row['l1_sale_type'] == 'Retention')
                $currentSaleType =  'Retention';

            //Fix bug thiếu Sale Type Date
            if(empty($row['l1_sale_type_date']))
                $GLOBALS['db']->query("UPDATE j_payment SET sale_type_date = '{$row['l1_payment_date']}' WHERE id = '{$row['l1_id']}'");
        }
    }
    if($currentSaleType == 'New Sale')
        return true;
    else return false;
}

function getTargetNewSale($team_id){
    $q1 = "SELECT l2.code_prefix code_prefix FROM teams LEFT JOIN teams l2 ON teams.parent_id = l2.id AND l2.deleted = 0 WHERE teams.id = '$team_id'";
    $parent_prefix = $GLOBALS['db']->getOne($q1);

    $amount = (int)$GLOBALS['app_list_strings']['new_sale_target_deposit'][$parent_prefix];
    if(empty($amount)) $amount=5280000;

    return $amount;
}
// END CHECK NEW SALE

//Get Waiting Class Enrolled from Student
function get_waiting_class($student_id){
    $q1 = "SELECT DISTINCT
    IFNULL(j_studentsituations.id, '') primaryid,
    IFNULL(j_studentsituations.name, '') name,
    IFNULL(l2.name, '') class_name,
    IFNULL(l2.id, '') class_id,
    IFNULL(l2.class_code, '') class_code,
    l2.class_type class_type,
    j_studentsituations.total_amount total_amount,
    j_studentsituations.total_hour total_hour
    FROM
    j_studentsituations
    INNER JOIN
    contacts l1 ON j_studentsituations.student_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    j_class l2 ON j_studentsituations.ju_class_id = l2.id
    AND l2.deleted = 0
    WHERE
    (((l1.id = '$student_id')
    AND (l2.class_type = 'Waiting Class')
    AND (j_studentsituations.type = 'Enrolled')))
    AND j_studentsituations.deleted = 0";
    return $GLOBALS['db']->fetchArray($q1);
}

function get_list_lesson_by_situation($class_id = '', $situation_id, $start_display = '', $end_display = '', $join = 'LEFT'){
    global $timedate;

    //    if(is_array($situation_id))
    //        $in_ext =  "IN ('".implode("','",$situation_id)."')";
    //    else $in_ext = "= '$situation_id'";

    $ext_start = '';
    if(!empty($start_display)){
        $start_tz     = date('Y-m-d H:i:s',strtotime("-7 hours ".$timedate->to_db_date($start_display,false)." 00:00:00"));
        $ext_start = "AND (meetings.date_start >= '$start_tz')";
    }
    $ext_end = '';
    if(!empty($end_display)){
        $end_tz     = date('Y-m-d H:i:s',strtotime("-7 hours ".$timedate->to_db_date($end_display    ,false)." 23:59:59"));
        $ext_end = "AND (meetings.date_end <= '$end_tz')";
    }
    $ext_class = '';
    if(!empty($class_id))
        $ext_class = "AND (l2.id = '$class_id')";

    $q1 = "SELECT DISTINCT
    IFNULL(l2.id, '') class_id,
    IFNULL(l3.id, '') situation_id,
    IFNULL(meetings.id, '') primaryid,
    meetings.lesson_number lesson_number,
    meetings.date_start date_start,
    meetings.date_end date_end,
    meetings.delivery_hour delivery_hour
    FROM
    meetings
    INNER JOIN
    j_class l2 ON meetings.ju_class_id = l2.id
    AND l2.deleted = 0
    $join JOIN
    meetings_contacts l1_1 ON meetings.id = l1_1.meeting_id
    AND l1_1.deleted = 0 AND l1_1.situation_id = '$situation_id'
    $join JOIN
    j_studentsituations l3 ON l1_1.situation_id = l3.id
    AND l3.deleted = 0 AND l3.id = '$situation_id'
    WHERE
    ((meetings.deleted = 0
    $ext_class
    $ext_start
    $ext_end
    AND (meetings.session_status <> 'Cancelled')))
    ORDER BY date_start ASC";

    return $GLOBALS['db']->fetchArray($q1);
}

function get_list_revenue_adult($student_id = '', $start_date = '', $end_date = '', $team_id = ''){
    $holiday_list = get_list_holidays_adult_revenue($start_date, $end_date);
    $ext_student = '';
    if(!empty($student_id))
        $ext_student = "AND c.id = '$student_id'";

    $ext_team = "AND l5.id = '$team_id'";
    if(empty($team_id))
        $ext_team = "AND ((jp.team_set_id IN (SELECT
        tst.team_set_id
        FROM
        team_sets_teams tst
        INNER JOIN
        team_memberships team_memberships ON tst.team_id = team_memberships.team_id
        AND team_memberships.user_id = '{$GLOBALS['current_user']->id}'
        AND team_memberships.deleted = 0)))";


    $sql_get_payment_list = "
    SELECT
    IFNULL(jp.id, '') payment_id,
    IFNULL(c.id, '') student_id,
    IFNULL(l5.id, '') team_id,
    DATE_FORMAT(selected_date, '%Y-%m') month_year,
    min(selected_date) revenue_start,
    max(selected_date) revenue_end,
    (jp.payment_amount + jp.deposit_amount + jp.paid_amount) / jp.tuition_hours AS payment_price
    FROM
    (SELECT
    ADDDATE('$start_date', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) selected_date
    FROM
    (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) v
    INNER JOIN
    j_payment jp ON jp.start_study <= '$end_date'
    AND jp.end_study >= '$start_date'
    AND jp.deleted = 0
    INNER JOIN
    contacts_j_payment_1_c cjp ON cjp.contacts_j_payment_1j_payment_idb = jp.id
    AND cjp.deleted = 0
    INNER JOIN
    contacts c ON c.id = cjp.contacts_j_payment_1contacts_ida
    AND c.deleted = 0 $ext_student
    INNER JOIN
    teams l5 ON jp.team_id = l5.id AND l5.deleted = 0
    AND l5.team_type = 'Adult' $ext_team
    WHERE
    selected_date BETWEEN jp.start_study AND jp.end_study
    AND selected_date <= '$end_date'
    GROUP BY payment_id , student_id , month_year
    ORDER BY student_id";
    $payment_list = array();
    $rs = $GLOBALS['db']->query($sql_get_payment_list);
    while($row_payment = $GLOBALS['db']->fetchByAssoc($rs)){
        if($row_payment['student_id'] == 'c40526d0-01d9-5793-04cd-54aa7275231e'){
            $a = 123;
        }
        $delivery_day = count_revenue_date_adult($row_payment['revenue_start'], $row_payment['revenue_end'], $holiday_list);
        $payment_list[] = array(
            'student_id'        => $row_payment['student_id'],
            'delivery_day'      => $delivery_day,
            'delivery_revenue'  => $delivery_day * $row_payment['payment_price'],
            'date_start'        => $row_payment['revenue_start'],
            'cost_per_day'      => $row_payment['payment_price'],
            'payment_id'        => $row_payment['payment_id'],
            'team_id'           => $row_payment['team_id'],
        );
    }
    return $payment_list;
}

function count_revenue_date_adult($start_date, $end_date, $holiday_list){
    $revenue_day = 0;
    $end = strtotime($end_date);
    $run_date = strtotime($start_date);
    while($run_date <= $end){
        if(!in_array($run_date,$holiday_list))
            $revenue_day += 1;
        $run_date = strtotime('+1 day', $run_date);
    }
    return $revenue_day;
}
function cal_finish_date_adult($pay_start_db, $run_remain){
    $holidays = get_list_holidays_adult($pay_start_db);
    $run_date = $pay_start_db;
    $holiday_list = array();
    while($run_remain > 1){
        if(!in_array($run_date , $holidays))
            $run_remain-=1;
        else
            $holiday_list[] = $run_date;

        $run_date = date('Y-m-d',strtotime('+1 day ', strtotime($run_date)));
    }
    $pay_end_db = $run_date;
    return $pay_end_db;
}

function check_new_student($contact_id, $payment_date, $date_entered = ''){
    //set is_new_student status cac payment tao sau = 0
    $sql_get_payment_id = "select p.id from
    j_payment p
    INNER JOIN
    contacts_j_payment_1_c cp ON cp.contacts_j_payment_1contacts_ida = '$contact_id'
    AND cp.contacts_j_payment_1j_payment_idb = p.id
    AND p.deleted = 0
    AND cp.deleted = 0
    INNER JOIN
    j_paymentdetail pd ON pd.payment_id = p.id
    AND pd.payment_amount > 0
    AND pd.status = 'paid'
    AND pd.deleted = 0
    and p.is_new_student = 1
    GROUP BY pd.payment_id
    HAVING MIN(pd.payment_date) > '$payment_date'
    AND TIMESTAMPDIFF(MONTH,
    '$payment_date',
    MIN(pd.payment_date)) BETWEEN 0 AND 6";
    $pmt_id = $GLOBALS['db']->getOne($sql_get_payment_id);
    if (!empty($pmt_id))
        $GLOBALS['db']->query("UPDATE j_payment SET is_new_student = 0 WHERE id = '$pmt_id'");
    $sql = "SELECT
    cp.contacts_j_payment_1contacts_ida,
    cp.contacts_j_payment_1j_payment_idb
    FROM
    contacts_j_payment_1_c cp
    INNER JOIN
    j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
    AND p.deleted = 0
    AND cp.deleted = 0
    INNER JOIN
    j_paymentdetail pd ON pd.payment_id = p.id AND pd.deleted = 0
    AND pd.status = 'Paid'
    AND pd.payment_amount > 0
    AND TIMESTAMPDIFF(MONTH,
    pd.payment_date,
    '$payment_date') < 6
    and pd.payment_date <= '$payment_date'
    AND p.sale_type <> ''
    AND p.kind_of_course not in ('Outing Trip','Cambridge')
    AND cp.contacts_j_payment_1contacts_ida = '$contact_id'
    AND p.payment_type NOT IN ('Transfer In','Moving In', 'Transfer From AIMS')
    group by contacts_j_payment_1contacts_ida, contacts_j_payment_1j_payment_idb
    ORDER BY pd.payment_date, pd.date_entered";
    $arr_rs = $GLOBALS['db']->fetchArray($sql);
    if (count($arr_rs) == 1)
        return 1;
    else return 0;
}

function set_next_payment($contact_id, $payment_id, $invoice_date){
    $sql = "SELECT  p.id FROM
    contacts_j_payment_1_c cp
    INNER JOIN
    j_payment p ON cp.contacts_j_payment_1contacts_ida = '$contact_id'
    AND cp.deleted = 0
    AND p.deleted = 0
    AND cp.contacts_j_payment_1j_payment_idb = p.id
    AND p.id <> '$payment_id'
    INNER JOIN
    j_paymentdetail pd ON pd.deleted = 0 AND pd.payment_id = p.id
    AND pd.status = 'paid'
    AND pd.payment_amount > 0
    GROUP BY pd.payment_id
    HAVING MIN(pd.payment_date) >= '$invoice_date'
    ORDER BY MIN(pd.payment_date)
    LIMIT 1";
    $row = $GLOBALS['db']->fetchArray($sql);
    return $GLOBALS['db']->query("UPDATE j_payment SET is_new_student = 1 WHERE id = '{$row[0]['id']}' ");
}

function update_remain_last_date($student_id){
    //    $sql_remain = "SELECT
    //    SUM(final.total_amount)
    //    FROM
    //    (SELECT
    //    cp.contacts_j_payment_1contacts_ida student_id,
    //    SUM(pd.payment_amount) total_amount, 'paid' as type_count
    //    FROM
    //    contacts_j_payment_1_c cp
    //    INNER JOIN j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
    //    AND p.deleted = 0
    //    AND cp.deleted = 0
    //    AND cp.contacts_j_payment_1contacts_ida = '$student_id'
    //    INNER JOIN j_paymentdetail pd ON p.id = pd.payment_id AND pd.deleted = 0
    //    AND pd.status = 'Paid'
    //    GROUP BY student_id UNION SELECT
    //    ss.student_id, SUM(- ss.total_amount) total_amount,  'enrolled' as type_count
    //    FROM
    //    j_studentsituations ss
    //    INNER JOIN j_class c ON ss.ju_class_id = c.id AND c.deleted = 0
    //    AND ss.deleted = 0
    //    AND ss.type IN ('Enrolled', 'Moving In', 'Settle')
    //    AND c.class_type = 'Normal Class'
    //    AND ss.student_id = '$student_id'
    //    INNER JOIN j_payment p ON p.id = ss.payment_id AND p.deleted = 0
    //    GROUP BY ss.student_id UNION SELECT
    //    cp.contacts_j_payment_1contacts_ida student_id,
    //    SUM(p.payment_amount) total_amount, 'mv In' as type_count
    //    FROM
    //    contacts_j_payment_1_c cp
    //    INNER JOIN j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
    //    AND p.deleted = 0
    //    AND cp.deleted = 0
    //    AND cp.contacts_j_payment_1contacts_ida = '$student_id'
    //    AND p.payment_type IN ('Transfer In' , 'Moving In', 'Transfer From AIMS')
    //    GROUP BY student_id UNION SELECT
    //    cp.contacts_j_payment_1contacts_ida student_id,
    //    SUM(- p.payment_amount) total_amount, 'mv Out' as type_count
    //    FROM
    //    contacts_j_payment_1_c cp
    //    INNER JOIN j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
    //    AND p.deleted = 0
    //    AND cp.deleted = 0
    //    AND cp.contacts_j_payment_1contacts_ida = '$student_id'
    //    AND p.payment_type IN ('Transfer Out' , 'Moving Out', 'Refund')
    //    GROUP BY student_id UNION SELECT
    //    dr.student_id, SUM(-dr.amount) total_amount, 'drop' as type_count
    //    FROM
    //    j_payment p
    //    INNER JOIN c_deliveryrevenue dr ON dr.ju_payment_id = p.id
    //    AND p.deleted = 0
    //    AND dr.deleted = 0
    //    AND dr.passed = 0
    //    AND p.id not in (select payment_id from j_studentsituations where deleted = 0 and type in ('Enrolled', 'Settle', 'moving in') and student_id ='$student_id')
    //    AND dr.type = 'Junior'
    //    AND dr.student_id = '$student_id'
    //    GROUP BY dr.student_id) final
    //    GROUP BY student_id";
    //    $remain_amount = $GLOBALS['db']->getOne($sql_remain);
    //
    $sql_last_date = "SELECT
    MAX(CONVERT( DATE_ADD(m.date_end, INTERVAL 7 hour) , DATE))
    FROM
    meetings_contacts mc
    INNER JOIN
    meetings m ON mc.meeting_id = m.id AND m.deleted = 0
    AND mc.deleted = 0
    AND mc.contact_id = '$student_id'
    AND m.meeting_type = 'Session'
    AND m.session_status <> 'Cancelled'
    INNER JOIN
    j_studentsituations ss ON ss.id = mc.situation_id
    AND ss.deleted = 0
    INNER JOIN
    j_payment p ON p.id = ss.payment_id AND p.deleted = 0
    INNER JOIN
    j_class c ON c.id = m.ju_class_id AND c.deleted = 0
    AND c.class_type <> 'Waiting Class'";
    $last_date = $GLOBALS['db']->getOne($sql_last_date);
    //Update
    $sql_update = "UPDATE contacts SET stopped_date = '$last_date' WHERE id = '$student_id'";
    $GLOBALS['db']->query($sql_update);
}

function getLoyaltyPoint($student_id, $not_payment_id='', $include_pmd_gross_id = ''){
    if(empty($student_id))
        return 0;

    $ext_not_pm_id = '';
    if(!empty($not_payment_id)){
        $ext_not_pm_id1 = "AND (l4.payment_id <> '$not_payment_id')";
        $ext_not_pm_id2 = "AND (lt.payment_id <> '$not_payment_id')";
    }

    $current_payment['gross_amount'] = 0;
    $current_payment['payment_amount'] = 0;
    if(!empty($include_pmd_gross_id)){
        $rs6             = $GLOBALS['db']->query("SELECT IFNULL((discount_amount + sponsor_amount + loyalty_amount),0) gross_amount, payment_amount FROM j_paymentdetail WHERE id = '$include_pmd_gross_id' AND deleted = 0");
        $current_payment = $GLOBALS['db']->fetchByAssoc($rs6);
        if(empty($current_payment['payment_amount'])){
            $current_payment['gross_amount']    = 0;
            $current_payment['payment_amount']  = 0;
        }
    }

    $loyalty_rank = $GLOBALS['app_list_strings']['loyalty_rank_list'];

    $q4 = "SELECT DISTINCT
    IFNULL(contacts.id, '') primaryid,
    IFNULL(contacts.contact_id, '') student_id,
    IFNULL(l2.name, '') code,
    IFNULL(l2.type, '') level,
    IFNULL(contacts.full_student_name, '') student_name,
    IFNULL(SUM(l4.payment_amount), 0) net_amount,
    IFNULL(p.total_point, 0) points
    FROM
    contacts
    INNER JOIN
    j_paymentdetail l4 ON contacts.id = l4.student_id
    AND l4.deleted = 0 AND l4.payment_date >= IFNULL((SELECT
    p.payment_date
    FROM
    j_payment p
    INNER JOIN
    contacts_j_payment_1_c cp ON cp.contacts_j_payment_1j_payment_idb = p.id
    AND p.deleted = 0
    AND p.is_new_student = 1
    AND cp.deleted = 0
    AND cp.contacts_j_payment_1contacts_ida = '$student_id'
    ORDER BY payment_date DESC
    LIMIT 1),'1900-01-01')
    LEFT JOIN
    c_memberships l2 ON l2.student_id = l4.student_id
    AND l2.deleted = 0
    LEFT JOIN
    (SELECT
    student_id, SUM(point) total_point
    FROM
    j_loyalty lt
    WHERE
    lt.deleted = 0 AND lt.student_id = '$student_id' $ext_not_pm_id2) p  ON p.student_id = contacts.id
    WHERE
    (((contacts.id = '$student_id')
    $ext_not_pm_id1
    AND (l4.status = 'Paid')
    AND (l4.payment_date > '2015-01-01')
    AND ((l4.payment_amount > 0))))
    AND contacts.deleted = 0";
    $rs4 = $GLOBALS['db']->query($q4);
    $row4 = $GLOBALS['db']->fetchByAssoc($rs4);
    //result
    $net_amount = 0 + $current_payment['gross_amount'];
    if(!empty($row4))
        $net_amount = $row4['net_amount'] + $current_payment['gross_amount'];

    if($net_amount < $loyalty_rank['Blue']){
        $level        = 'N/A';
        $next_level   = 'Blue';
        $current_rate = format_number((($net_amount - $loyalty_rank[$level]) / ($loyalty_rank[$next_level] - $loyalty_rank[$level])) * 100,0,0);
    }
    if($net_amount >= $loyalty_rank['Blue']){
        $level        = 'Blue';
        $next_level   = 'Gold';
        $current_rate = format_number((($net_amount - $loyalty_rank[$level]) / ($loyalty_rank[$next_level] - $loyalty_rank[$level])) * 100,0,0);
    }
    if($net_amount >= $loyalty_rank['Gold']){
        $level        = 'Gold';
        $next_level   = 'Platinum';
        $current_rate = format_number((($net_amount - $loyalty_rank[$level]) / ($loyalty_rank[$next_level] - $loyalty_rank[$level])) * 100,0,0);
    }
    if($net_amount >= $loyalty_rank['Platinum']){
        $level        = 'Platinum';
        $next_level   = '';
        $current_rate = '100';
    }
    return array(
        'student_id'=> $row4['primaryid'],
        'code'      => $row4['code'],
        'level'     => $level,
        'next_level'=> $next_level,
        'current_rate'=> $current_rate,
        'points'    => $row4['points'],
        'net_amount'=> $row4['net_amount'],
    );
}
function getLoyaltyRateOut($mem_level, $team_id = '', $year = ''){
    //    if(empty($team_id))
    //        $team_id = $GLOBALS['current_user']->team_id;
    //    if(empty($year))
    //        $year = date('Y');
    //    $rs_rate     = $GLOBALS['db']->query("SELECT id, value FROM j_targetconfig WHERE team_id='$team_id' AND deleted=0 AND type='Loyalty Redemption Rate ($mem_level)' AND year='$year' AND time_unit='$year' AND frequency='Yearly'");
    //    $redemp_rate= $GLOBALS['db']->fetchByAssoc($rs_rate);
    //    if(empty($redemp_rate))
    $redemp_rate = array('id' => '','value' => $GLOBALS['app_list_strings']['default_loyalty_rate']['Conversion Rate']);
    return $redemp_rate;
}
function updateMembership($student_id, $updated_level, $upgrade_date){
    $rs4 = $GLOBALS['db']->query("SELECT DISTINCT
        IFNULL(c_memberships.id, '') id,
        IFNULL(c_memberships.name, '') name,
        IFNULL(c_memberships.type, '') level
        FROM
        c_memberships
        INNER JOIN
        contacts l1 ON l1.id = c_memberships.student_id
        AND l1.deleted = 0
        WHERE
        (((l1.id = '$student_id')))
        AND c_memberships.deleted = 0");
    $mem_row = $GLOBALS['db']->fetchByAssoc($rs4);
    if(empty($mem_row['id'])){  //Tạo mới membership
        $member                    = new C_Memberships();
        $member->type              = $updated_level;
        $member->student_id        = $student_id;
        $member->upgrade_date      = $upgrade_date;
        $member->assigned_user_id  = $GLOBALS['current_user']->id;
        $member->save();
    }
    if(!empty($mem_row['id']) && $mem_row['level'] != $updated_level){ //Nâng hạng
        $GLOBALS['db']->query("UPDATE c_memberships SET type = '$updated_level' WHERE deleted = 0 AND id='{$mem_row['id']}'");
    }
}
?>
