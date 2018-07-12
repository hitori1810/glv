<?php
function get_list_student($ext_team = '', $start, $end, $class_id = '',$koc = '', $level = ''){
    $qTeam = "AND ss.team_set_id IN
    (SELECT
    tst.team_set_id
    FROM
    team_sets_teams tst
    INNER JOIN
    team_memberships team_memberships ON tst.team_id = team_memberships.team_id
    AND team_memberships.user_id = '{$GLOBALS['current_user']->id}'
    AND team_memberships.deleted = 0)";
    if ($GLOBALS['current_user']->isAdmin()){
        $qTeam = "";
    }
    $ext_class_id = '';
    if(!empty($class_id))
        $ext_class_id = "AND ss.ju_class_id IN ($class_id)";
    $ext_koc = "AND (koc.kind_of_course IN ('Kindy' , 'Kids', 'Kids Plus', 'Kids Extra')
    OR (koc.kind_of_course = 'Teens'
    AND c.level NOT IN ('Advance'))
    OR (koc.kind_of_course IN ('GE','BE')
    AND c.level NOT IN ('Advance' , 'Upper Inter')))";
    $ext_level = "";
    if(!empty($level))  $ext_level = "AND c.level = '$level'";
    if(!empty($koc)) $ext_koc = "AND koc.kind_of_course = '$koc' ".$ext_level;
    $sql_student = "SELECT
    ss.student_id,
    ss.name student_name,
    fd.first_lesson first_date,
    CASE
    WHEN ss.type = 'Settle' THEN p.payment_date
    ELSE ss.end_study
    END AS end_study,
    ss.total_hour,
    ss.ju_class_id,
    ss.description,
    c.name class_name,
    IFNULL(u.full_user_name, '') class_assigned_to,
    ss.payment_id,
    p.payment_date,
    c.kind_of_course,
    c.level,
    ss.team_id,
    t.name center_name,
    t.code_prefix center_code
    FROM
    j_studentsituations ss
    INNER JOIN
    j_class c ON c.id = ss.ju_class_id AND c.deleted = 0
    $ext_class_id
    INNER JOIN
    teams t on t.id = ss.team_id
    $ext_team
    $qTeam
    inner join
    j_kindofcourse koc ON koc.id = c.koc_id
    $ext_koc
    inner join users u on u.id = c.assigned_user_id and u.deleted = 0
    INNER JOIN
    j_payment p ON p.id = ss.payment_id AND p.deleted = 0
    INNER JOIN
    (SELECT
        student_id, MIN(start_study) first_lesson
    FROM
        j_studentsituations
    WHERE
        deleted = 0
            AND type IN ('Enrolled' , 'Settle', 'Moving in')
    GROUP BY student_id) fd ON fd.student_id = ss.student_id
    LEFT JOIN j_sponsor sp ON sp.payment_id = ss.payment_id and sp.deleted = 0 and sp.name = 'Retake'
    WHERE
    ss.end_study BETWEEN '$start' AND '$end'
    AND ss.deleted = 0
    AND (ss.type IN ('Enrolled' , 'Moving In')
    OR (ss.type = 'Settle'
    AND ROUND(p.payment_amount + p.paid_amount + p.deposit_amount,
    0) = ROUND(ss.total_amount, 0)))
    
    group by student_id, ss.payment_id
    ORDER BY student_id ASC , end_study DESC";

    return $GLOBALS['db']->fetchArray($sql_student);
}

function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
        }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

function get_first_payment_date($team_id, $student_id = ''){
    $ext_team = '';
    if(!empty($team_id))
        $ext_team = "AND (l2.id IN ('$team_id'))";

    $ext_student = '';
    if(!empty($student_id))
        $ext_student = "AND (l1.id = '$student_id')";

    $q1 = "SELECT
    IFNULL(j_payment.payment_date, '') payment_date
    FROM
    j_payment
    INNER JOIN
    contacts_j_payment_1_c l1_1 ON j_payment.id = l1_1.contacts_j_payment_1j_payment_idb
    AND l1_1.deleted = 0
    INNER JOIN
    contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida
    AND l1.deleted = 0
    INNER JOIN
    teams l2 ON j_payment.team_id = l2.id
    AND l2.deleted = 0
    WHERE
    (((j_payment.deleted = 0) $ext_student
    $ext_team))
    ORDER BY payment_date ASC LIMIT 1;";

    $f_date = $GLOBALS['db']->getOne($q1);

    if(empty($f_date))
        $f_date = $GLOBALS['timedate']->nowDbDate();

    return date('Y-m-01',strtotime('- 3month '.$f_date));
}

function check_pre_paid($student_id, $end){
    $sql_pre_paid = "SELECT
    cp.contacts_j_payment_1contacts_ida student_id,
    p.id payment_id,
    MAX(p.payment_date) payment_date
    FROM
    contacts_j_payment_1_c cp
    INNER JOIN
    j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
    AND cp.deleted = 0
    AND p.deleted = 0
    AND p.payment_date < '$end'
    AND p.sale_type = 'Retention'
    AND cp.contacts_j_payment_1contacts_ida IN ($student_id)
    INNER JOIN
    j_paymentdetail pd ON pd.payment_id = p.id AND pd.deleted = 0
    AND pd.status = 'Paid'
    AND pd.payment_amount > 500000
    GROUP BY student_id";
    $rs_pre_paid = $GLOBALS['db']->query($sql_pre_paid);
    $data_pre_paid = array();
    while($row_prepaid = $GLOBALS['db']->fetchByAssoc($rs_pre_paid)){
        $data_pre_paid[$row_prepaid['student_id']]['payment_date'] = $row_prepaid['payment_date'];
        $data_pre_paid[$row_prepaid['student_id']]['payment_id'] = $row_prepaid['payment_id'];
    }
    return $data_pre_paid;
}

function check_retention($student_id, $end){
    $sql_check = "SELECT
    cp.contacts_j_payment_1contacts_ida student_id,
    MIN(pd.payment_date)   return_date
    FROM
    contacts_j_payment_1_c cp
    INNER JOIN
    j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
    AND cp.deleted = 0
    AND p.deleted = 0
    INNER JOIN
    j_paymentdetail pd ON pd.payment_id = p.id AND pd.deleted = 0
    AND pd.status = 'Paid'
    AND pd.payment_amount > 0
    AND p.sale_type = 'Retention'
    AND pd.payment_date > '$end'
    AND cp.contacts_j_payment_1contacts_ida IN ($student_id)
    GROUP BY student_id ";
    $rs_reten =  $GLOBALS['db']->query($sql_check);
    $data_reten = array();
    while($row_reten = $GLOBALS['db']->fetchByAssoc($rs_reten)){
        $data_reten[$row_reten['student_id']]['return_date'] = $row_reten['return_date'];
    }
    $sql_tf = "SELECT
    cp.contacts_j_payment_1contacts_ida student_id,
    p.id payment_id,
    p.payment_amount,
    p.payment_date,
    p.team_id
    FROM
    contacts_j_payment_1_c cp
    INNER JOIN
    j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
    AND p.deleted = 0
    AND cp.deleted = 0
    AND p.payment_date > '$end'
    AND p.payment_type IN ('Transfer In' , 'Transfer From AIMS')
    AND cp.contacts_j_payment_1contacts_ida IN ($student_id)
    ORDER BY p.payment_date";
    $rs_tf = $GLOBALS['db']->fetchArray($sql_tf);
    $std_id = '';
    $total_amount = array();
    foreach ($rs_tf as $key=>$value){
        if($std_id == $value['student_id'] && (float)$total_amount[$std_id] > 500000)
            continue;
        else{
            $std_id = $value['student_id'];
            $total_amount[$std_id] += $value['payment_amount'];
            if($total_amount[$std_id] > 500000 && (empty($data_reten[$std_id]['return_date']) || $data_reten[$std_id]['return_date'] > $value['payment_date']))
                $data_reten[$std_id]['return_date'] = $value['payment_date'];
        }
    }

    return $data_reten;
}

function get_retention_in_month($student_id, $start, $end){
    $sql_retention_in_month = "SELECT
    cp.contacts_j_payment_1contacts_ida student_id,
    p.id payment_id,
    pd.payment_amount,
    pd.invoice_number,
    pd.payment_date,
    pd.team_id
    FROM
    contacts_j_payment_1_c cp
    INNER JOIN
    j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
    AND p.deleted = 0
    AND cp.deleted = 0
    INNER JOIN
    j_paymentdetail pd ON p.id = pd.payment_id AND pd.deleted = 0
    AND pd.status = 'Paid'
    AND pd.payment_amount > 0
    AND p.sale_type = 'Retention'
    AND pd.payment_date BETWEEN '$start' AND '$end'
    AND cp.contacts_j_payment_1contacts_ida IN ($student_id)";
    $rs =  $GLOBALS['db']->query($sql_retention_in_month);
    $data = array();
    while($row = $GLOBALS['db']->fetchByAssoc($rs)){
        //$data[$row['student_id']]['payment_id']  += $row['payment_id'];
        $data[$row['student_id']]['payment_amount']  += $row['payment_amount'];
        //$data[$row['student_id']]['invoice_number']  += $row['invoice_number'];
        $data[$row['student_id']]['payment_date']  = max($row['payment_date'],$data[$row['student_id']]['payment_date']);
        if($data[$row['student_id']]['team_id'] !== $row['team_id'])
            $data[$row['student_id']]['team_id']  .= $row['team_id'];
    }
    $sql_transfer_in = "SELECT
    cp.contacts_j_payment_1contacts_ida student_id,
    p.id payment_id,
    p.payment_amount,
    p.payment_date,
    p.team_id
    FROM
    contacts_j_payment_1_c cp
    INNER JOIN
    j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
    AND p.deleted = 0
    AND cp.deleted = 0
    AND p.payment_date BETWEEN '$start' AND '$end'
    AND p.payment_type IN ('Transefer In' , 'Transfer From AIMS')
    AND cp.contacts_j_payment_1contacts_ida IN ($student_id)";
    $rs2 = $GLOBALS['db']->query($sql_transfer_in);
    while($row2 = $GLOBALS['db']->fetchByAssoc($rs2)){
        $data[$row2['student_id']]['payment_amount']  += $row2['payment_amount'];
        $data[$row2['student_id']]['payment_date']  = max($row2['payment_date'],$data[$row2['student_id']]['payment_date']);
        if($data[$row2['student_id']]['team_id'] !== $row2['team_id'])
            $data[$row2['student_id']]['team_id']  .= $row2['team_id'];
    }
    return $data;
}

function get_revenue($team_id, $student_id, $payment_id, $first_date, $start, $end){
    $data =  get_list_revenue_report($team_id, $student_id, $payment_id, "'Enrolled','Moving In','Stopped'", $first_date, $start, $end, 'Planning');

    //Tinh doanh thu Drop
    $ext_student = '';
    if(!empty($student_id))
        $ext_student = "AND (c_deliveryrevenue.student_id IN ($student_id))";

    $ext_team = '';
    if(!empty($team_id))
        $ext_team = "AND (l3.id = '$team_id')";

    $q1 = "SELECT
    IFNULL(l1.id, '') ju_payment_id,
    c_deliveryrevenue.date_input,
    IFNULL(SUM(c_deliveryrevenue.amount), 0) amount
    FROM
    c_deliveryrevenue
    INNER JOIN
    j_payment l1 ON c_deliveryrevenue.ju_payment_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    users l2 ON c_deliveryrevenue.created_by = l2.id
    AND l2.deleted = 0
    INNER JOIN
    teams l3 ON c_deliveryrevenue.team_id = l3.id
    AND l3.deleted = 0
    WHERE
    (((c_deliveryrevenue.passed IS NULL
    OR c_deliveryrevenue.passed = '0')
    AND (c_deliveryrevenue.date_input >= '$first_date'
    AND c_deliveryrevenue.date_input <= '$end')
    $ext_team
    $ext_student))
    AND c_deliveryrevenue.deleted = 0
    GROUP BY ju_payment_id, date_input";
    $rs = $GLOBALS['db']->query($q1);
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        if($row['date_input'] < $start){
            $data[$row['ju_payment_id']]['before_this']['amount']  += $row['amount'];
        }else{
            $data[$row['ju_payment_id']]['this']['amount']  += $row['amount'];
        }
    }
    return $data;
}

function get_settle($team_id, $student_id, $payment_id, $first_date, $start, $end){
    //Tính doanh thu Settle
    $data =  get_list_revenue_report($team_id, $student_id, $payment_id, "'Settle'", $first_date, $start, $end, 'Planning');
    return $data;
}

function get_list_revenue_report($team_id = '', $student_id = '', $payment_id = '', $situation_type = "'Enrolled'", $first_date ,$start = '', $end = '', $not_status = ''){

    $ext_team = "AND (l5.id = '$team_id')";
    if(empty($team_id))
        $ext_team = "";

    $ext_student = "AND (l1.id IN ($student_id))";
    if(empty($student_id))
        $ext_student = "";

    $ext_payment_id  = "AND l4.id IN ($payment_id)";
    if(empty($payment_id))
        $ext_payment_id = "";

    $ext_situation = "AND l3.type IN($situation_type)";
    if($situation_type == "All" || empty($situation_type))
        $ext_situation = "";

    if(!empty($first_date)){
        $start_tz     = date('Y-m-d H:i:s',strtotime("-7 hours ".$first_date." 00:00:00"));
        $ext_start = "AND (meetings.date_start >= '$start_tz')";
    }else $ext_start = '';

    if(!empty($end)){
        $end_tz     = date('Y-m-d H:i:s',strtotime("-7 hours ".$end." 23:59:59"));
        $ext_end = "AND (meetings.date_end <= '$end_tz')";
    }else $ext_end = '';

    $ext_status = "AND (l2.status <> '$not_status')";
    if(empty($not_status))
        $ext_status = "";

    $select_date = "DATE_ADD(meetings.date_start, INTERVAL 7 HOUR)";
    //Set Revenue Settle
    if($situation_type == "'Settle'"){
        if(!empty($end)){
            $ext_end = "AND (l4.settle_date <= '$end')";
        }else $ext_end = '';

        if(!empty($first_date)){
            $ext_start = "AND (l4.settle_date >= '$first_date')";
        }else $ext_start = '';
        $select_date = "l4.settle_date";
    }
    //    AVG(IFNULL((l3.total_amount/l3.total_hour), 0)) cost_per_hour,
    $q1 = "SELECT DISTINCT
    IFNULL(l4.id, '') ju_payment_id,
    #DATE_FORMAT($select_date, '%m-%Y') month_year,
    CASE
    WHEN
    CONVERT($select_date, DATE) < '$start'
    THEN
    'before_this'
    ELSE 'this'
    END AS till_time,
    SUM(IFNULL(meetings.delivery_hour, 0)) total_revenue_hours,
    SUM(IFNULL((IFNULL((l3.total_amount/l3.total_hour), 0) * meetings.delivery_hour), 0)) total_revenue_amount
    FROM
    meetings
    INNER JOIN
    meetings_contacts l1_1 ON meetings.id = l1_1.meeting_id
    AND l1_1.deleted = 0
    INNER JOIN
    contacts l1 ON l1.id = l1_1.contact_id
    AND l1.deleted = 0
    INNER JOIN
    j_class l2 ON meetings.ju_class_id = l2.id
    AND l2.deleted = 0
    INNER JOIN
    j_studentsituations l3 ON l1_1.situation_id = l3.id
    AND l3.deleted = 0 $ext_situation
    LEFT JOIN
    j_payment l4 ON l3.payment_id = l4.id
    AND l4.deleted = 0
    INNER JOIN
    teams l5 ON meetings.team_id = l5.id
    AND l5.deleted = 0
    WHERE
    ((meetings.deleted = 0
    $ext_student
    $ext_payment_id
    $ext_start $ext_end
    $ext_team
    $ext_status
    AND (meetings.session_status <> 'Cancelled')))
    GROUP BY ju_payment_id, till_time";
    $rs = $GLOBALS['db']->query($q1);

    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        $data[$row['ju_payment_id']][$row['till_time']]['amount']  = $row['total_revenue_amount'];
    }
    return $data;
}

function get_cash_out($payment_id, $start, $end){
    /*$ext_student = '';
    if(!empty($student_id)){
    $ext_student = "AND (l3.id IN ($student_id))";
    }*/
    $q1 = "SELECT
    IFNULL(l1.id, '') payment_id_out,
    CASE
    WHEN
    DATE_ADD(j_payment.date_entered,
    INTERVAL 7 HOUR) < '$start'
    THEN
    'before_this'
    ELSE 'this'
    END AS till_time,
    (CASE
    WHEN j_payment.payment_type IN ('Transfer Out', 'Moving Out') THEN 'moving_transfer_out'
    WHEN j_payment.payment_type IN ('Refund') THEN 'refund'
    ELSE 'delay_cash_out'
    END) as payment_type_group,
    IFNULL(SUM(l1_1.amount), 0) amount
    FROM
    j_payment
    INNER JOIN
    j_payment_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_payment_j_payment_1j_payment_ida
    AND l1_1.deleted = 0
    INNER JOIN
    j_payment l1 ON l1.id = l1_1.j_payment_j_payment_1j_payment_idb
    AND l1.deleted = 0
    WHERE
    l1.id IN ($payment_id)
    AND convert(date_add(j_payment.date_entered, interval 7 hour),date) <= '$end'
    AND j_payment.deleted = 0
    GROUP BY payment_id_out, till_time, payment_type_group";

    $rs = $GLOBALS['db']->query($q1);
    $data = array();
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        $data[$row['payment_id_out']][$row['till_time']][$row['payment_type_group']]['amount'] = $row['amount'];
    }
    return $data;

}

function get_cash_in($payment_id, $start, $end){
    /*$ext_student = '';
    if(!empty($student_id)){
    $ext_student = "AND (l3.id IN ($student_id))";
    } */
    $q1 = "SELECT
    IFNULL(j_payment.id, '') payment_id,
    CASE
    WHEN
    DATE_ADD(j_payment.date_entered,
    INTERVAL 7 HOUR) < '$start'
    THEN
    'before_this'
    ELSE 'this'
    END AS till_time,
    (CASE
    WHEN l1.payment_type IN ('Transfer In', 'Moving In', 'Transfer From AIMS') THEN 'moving_transfer_in'
    ELSE 'delay_cash_in'
    END) as payment_type_group,
    IFNULL(SUM(l1_1.amount), 0) amount
    FROM
    j_payment
    INNER JOIN
    j_payment_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_payment_j_payment_1j_payment_ida
    AND l1_1.deleted = 0
    INNER JOIN
    j_payment l1 ON l1.id = l1_1.j_payment_j_payment_1j_payment_idb
    AND l1.deleted = 0
    WHERE
    j_payment.id in ($payment_id)
    AND convert(date_add(j_payment.date_entered, interval 7 hour),date) <= '$end'
    AND j_payment.deleted = 0
    GROUP BY payment_id, till_time, payment_type_group";
    $rs = $GLOBALS['db']->query($q1);
    $data = array();
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        $data[$row['payment_id']][$row['till_time']][$row['payment_type_group']]['amount'] = $row['amount'];
    }
    //    var_dump($data); die();
    return $data;

}

function get_collected($payment_id, $start, $end)  {
    // get total payment
    /*$ext_student = '';
    if(!empty($student_id)){
    $ext_student = "AND (l3.id = '$student_id')";
    } */
    $q1 = "  SELECT
    IFNULL(l2.id, '') payment_id,
    IFNULL(GROUP_CONCAT(j_paymentdetail.invoice_number SEPARATOR ' '), '') invoice_number,
    CASE
    WHEN
    j_paymentdetail.payment_date < '$start'
    THEN
    'before_this'
    ELSE 'this'
    END AS till_time,
    IFNULL(SUM(j_paymentdetail.before_discount), 0) before_discount,
    IFNULL(SUM(j_paymentdetail.discount_amount), 0) discount_amount,
    IFNULL(SUM(j_paymentdetail.sponsor_amount), 0) sponsor_amount,
    IFNULL(SUM(j_paymentdetail.payment_amount), 0) payment_amount
    FROM
    j_paymentdetail
    INNER JOIN
    /*teams l1 ON j_paymentdetail.team_id = l1.id
    AND l1.deleted = 0
    INNER JOIN */
    j_payment l2 ON j_paymentdetail.payment_id = l2.id
    AND l2.deleted = 0
    INNER JOIN
    contacts_j_payment_1_c l3_1 ON l2.id = l3_1.contacts_j_payment_1j_payment_idb
    AND l3_1.deleted = 0
    INNER JOIN
    contacts l3 ON l3.id = l3_1.contacts_j_payment_1contacts_ida
    AND l3.deleted = 0
    WHERE
    j_paymentdetail.payment_id in ($payment_id)
    AND j_paymentdetail.payment_date <= '$end'
    AND j_paymentdetail.status = 'Paid'
    AND j_paymentdetail.deleted = 0
    GROUP BY payment_id, till_time";
    //    AND ((COALESCE(LENGTH(j_paymentdetail.invoice_number),0) > 0))
    $rs = $GLOBALS['db']->query($q1);
    $data = array();
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){

        $data[$row['payment_id']][$row['till_time']]['invoice_number']   = $row['invoice_number'];
        $data[$row['payment_id']][$row['till_time']]['before_discount']   = $row['before_discount'];
        $data[$row['payment_id']][$row['till_time']]['discount_amount']   = $row['discount_amount'];
        $data[$row['payment_id']][$row['till_time']]['sponsor_amount']    = $row['sponsor_amount'];
        $data[$row['payment_id']][$row['till_time']]['payment_amount']    = $row['payment_amount'];
    }
    return $data;
}

function get_eliminate($team_id, $student_id, $end){
    $ext_student = '';
    if(!empty($student_id))
        $ext_student = "AND (l3.id IN ($student_id))";
    $q1 = "SELECT
    IFNULL(l1.id, '') payment_id,
    DATE_FORMAT(c_carryforward.date_input, '%m-%Y') month_year,
    IFNULL(SUM(c_carryforward.this_stock), 0) eliminate_amount
    FROM
    c_carryforward
    INNER JOIN
    j_payment l1 ON l1.id = c_carryforward.payment_id
    AND l1.deleted = 0
    INNER JOIN
    teams l2 ON c_carryforward.team_id = l2.id
    AND l2.deleted = 0
    INNER JOIN
    contacts_j_payment_1_c l3_1 ON l1.id = l3_1.contacts_j_payment_1j_payment_idb
    AND l3_1.deleted = 0
    INNER JOIN
    contacts l3 ON l3.id = l3_1.contacts_j_payment_1contacts_ida
    AND l3.deleted = 0
    WHERE
    (((c_carryforward.date_input <= '$end')
    AND (l2.id = '$team_id')
    $ext_student
    AND c_carryforward.type = 'Junior'))
    AND c_carryforward.deleted = 0
    GROUP BY payment_id , month_year";

    $rs = $GLOBALS['db']->query($q1);
    $data = array();
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        $data[$row['payment_id']][$row['month_year']]['amount']    = $row['eliminate_amount'];
        // $data[$row['payment_id']][$row['month_year']]['hours']     = $row['eliminate_hour'];
    }
    return $data;
}

function get_list_payment($student_list, $start, $end){
    $sql_payment_list = "SELECT DISTINCT
    IFNULL(j_payment.id, '') payment_id,
    IFNULL(j_payment.kind_of_course_string, '') kind_of_course_string,
    IFNULL(j_payment.level_string, '') level_string,
    IFNULL(j_payment.class_string, '') class_string,
    IFNULL(l2.id, '') student_id,
    IFNULL(l2.contact_id, '') student_code,
    IFNULL(l2.aims_code, '') aims_code,
    IFNULL(l2.aims_id, '') aims_id,
    IFNULL(l1.id, '') team_id,
    IFNULL(sp.name, '') sponsor_type,
    CONCAT(IFNULL(l2.last_name, ''),
    ' ',
    IFNULL(l2.first_name, '')) student_name,
    j_payment.payment_date payment_date,
    IFNULL(j_payment.payment_type, '') payment_type,
    IFNULL(j_payment.tuition_hours, 0) tuition_hours,
    IFNULL(j_payment.total_hours, 0) total_hours,
    IFNULL(j_payment.discount_percent, 0) discount_percent,
    IFNULL(j_payment.final_sponsor_percent, 0) final_sponsor_percent,
    IFNULL(j_payment.payment_amount, 0) payment_amount,
    IFNULL(j_payment.deposit_amount, 0) deposit_amount,
    IFNULL(j_payment.paid_amount, 0) paid_amount,
    IFNULL((j_payment.payment_amount + j_payment.deposit_amount) / (j_payment.total_hours),
    0) payment_price,
    IFNULL(j_payment.aims_id,0) payment_aims_id
    FROM
    j_payment
    LEFT JOIN j_sponsor sp on sp.payment_id = j_payment.id and sp.deleted = 0 and sp.name = 'Retake'
    LEFT OUTER JOIN
    (SELECT
    cd.date_input, jp.id payment_id
    FROM
    c_deliveryrevenue cd
    INNER JOIN j_payment jp ON jp.id = cd.ju_payment_id
    AND jp.payment_type = 'Enrollment'
    WHERE
    cd.passed = 0 AND cd.deleted = 0
    AND cd.type = 'Junior') cd ON cd.payment_id = j_payment.id
    INNER JOIN
    (SELECT
    j_payment.id,
    MAX(IFNULL(jp2.date_entered, j_payment.date_entered)) AS date_modified
    FROM
    j_payment
    LEFT JOIN j_payment_j_payment_1_c jjp ON j_payment.id = jjp.j_payment_j_payment_1j_payment_idb
    LEFT JOIN j_payment jp2 ON jp2.id = jjp.j_payment_j_payment_1j_payment_ida
    WHERE
    j_payment.deleted = 0

    GROUP BY j_payment.id) jpm ON j_payment.id = jpm.id
    LEFT JOIN
    (SELECT
    payment_id, MIN(start_study) start_study, MAX(end_study) end_study
    FROM
    j_studentsituations
    WHERE
    IFNULL(payment_id, '') <> ''
    AND deleted = 0
    AND student_id in ($student_list)
    GROUP BY payment_id) gss ON gss.payment_id = j_payment.id
    INNER JOIN
    teams l1 ON j_payment.team_id = l1.id
    AND  l1.deleted = 0
    INNER JOIN
    contacts_j_payment_1_c cjp ON j_payment.id = cjp.contacts_j_payment_1j_payment_idb
    AND cjp.deleted = 0
    INNER JOIN
    contacts l2 ON l2.id = cjp.contacts_j_payment_1contacts_ida
    AND l2.deleted = 0
    WHERE
    ((j_payment.payment_type IN ('Deposit' , 'Cashholder',
    'Delay',
    'Transfer In',
    'Transfer From AIMS',
    'Moving In',
    'Merge AIMS',
    'Placement Test',
    'Schedule Change')
    AND (j_payment.remain_amount > 0
    OR (j_payment.payment_amount - j_payment.used_amount > 0
    AND j_payment.payment_expired >= '$start')
    OR date_add(jpm.date_modified, interval 7 hour) >= '$start'))
    OR (j_payment.payment_type = 'Enrollment'
    AND (ifnull(gss.end_study,j_payment.end_study) >= '$start'
    OR  date_add(jpm.date_modified, interval 7 hour) >= '$start'
    OR j_payment.class_string LIKE '%-W'
    OR IFNULL(cd.date_input, '1900-01-01') >= '$start')))
    AND (j_payment.payment_date <= '$end'
    OR convert(date_add(j_payment.date_entered, interval 7 hour),date) <= '$end')
    AND j_payment.deleted = 0
    and l2.id in ($student_list)
    GROUP BY j_payment.id
    ORDER BY l2.id ASC";

    return $GLOBALS['db']->fetchArray($sql_payment_list);
}

function get_list_retake($std_list, $end){
    $sql_retake = "SELECT
    ss.student_id
FROM
    j_studentsituations ss
        INNER JOIN
    j_sponsor sp ON sp.payment_id = ss.payment_id
        AND ss.deleted = 0
        AND sp.deleted = 0
        AND (sp.name = 'Retake' OR sp.percent = 100)
        AND ss.student_id IN ($std_list)
        AND ss.end_study > '$end'";
    return $GLOBALS['db']->fetchArray($sql_retake);
}

function calculate_cf( $row_payment,  $row_collected,  $row_cashin,  $row_cashout,  $row_revenue,  $row_settle, $start = ''){
    $data = array();
    for($i = 0; $i < count($row_payment); $i++){
        $collected_amount_alloc     = 0;
        $revenue_amount_alloc       = 0;

        $payment_id  = $row_payment[$i]['payment_id'];

        $revenue_this_amount = $row_revenue[$payment_id]['this']['amount'];

        $revenue_till_this_amount = $row_revenue[$payment_id]['before_this']['amount'] + $row_revenue[$payment_id]['this']['amount'];

        $settle_this_amount = $row_settle[$payment_id]['this']['amount'];

        $settle_before_this_amount = $row_settle[$payment_id]['before_this']['amount'];

        $invoice_number = $row_collected[$payment_id]['before_this']['invoice_number'];
        if(empty($invoice_number)){
            $invoice_number = $row_collected[$payment_id]['this']['invoice_number'];
        }

        $before_discount = (float)$row_collected[$payment_id]['this']['before_discount'];

        $discount_amount = (float)$row_collected[$payment_id]['this']['discount_amount'];

        $sponsor_amount = (float)$row_collected[$payment_id]['this']['sponsor_amount'];

        $sponsor_type = $row_payment[$i]['sponsor_type'];

        $collected_amount_this = (float)$row_collected[$payment_id]['this']['payment_amount'];

        $collected_amount_till_this = $collected_amount_this + (float)$row_collected[$payment_id]['before_this']['payment_amount'];

        //delay-cash-in
        $cash_in_this_amount = (float)$row_cashin[$payment_id]['this']['delay_cash_in']['amount'];

        $cash_in_before_this_amount = (float)$row_cashin[$payment_id]['before_this']['delay_cash_in']['amount'];

        $cash_out_this_amount = (float)$row_cashout[$payment_id]['this']['delay_cash_out']['amount'];

        $cash_out_before_this_amount = (float)$row_cashout[$payment_id]['before_this']['delay_cash_out']['amount'];

        $mv_tf_in_this_amount = (float)$row_cashin[$payment_id]['this']['moving_transfer_in']['amount'];

        $mv_tf_in_before_this_amount = (float)$row_cashin[$payment_id]['before_this']['moving_transfer_in']['amount'];

        //moving-transfer-out
        $mv_tf_out_this_amount = (float)$row_cashout[$payment_id]['this']['moving_transfer_out']['amount'];

        $mv_tf_out_before_this_amount = (float)$row_cashout[$payment_id]['before_this']['moving_transfer_out']['amount'];

        //bổ sung transfer In
        $mv_tf = array(
            0 => 'Transfer In',
            1 => 'Moving In',
            2 => 'Transfer From AIMS');
        if(in_array($row_payment[$i]['payment_type'], $mv_tf)) {
            if(strtotime($start) > strtotime($row_payment[$i]['payment_date'])) {
                $mv_tf_in_before_this_amount = $row_payment[$i]['payment_amount'];
            }
            else {
                $mv_tf_in_this_amount =    $row_payment[$i]['payment_amount'];
            }
            $mv_tf_out_before_this_amount   = $cash_out_before_this_amount;
//            $mv_tf_out_before_this_hours    = $cash_out_before_this_hours;
            $cash_out_before_this_amount    = 0;
//            $cash_out_before_this_hours     = 0;
            $mv_tf_out_this_amount          = $cash_out_this_amount;
//            $mv_tf_out_this_hours           = $cash_out_this_hours;
            $cash_out_this_amount           = 0;
//            $cash_out_this_hours            = 0;
        }
        /*if(empty($mv_tf_in_this_hours))
            $mv_tf_in_this_hours  = $mv_tf_in_this_amount/$row_payment[$i]['payment_price'];
        if(empty($mv_tf_in_before_this_hours))
            $mv_tf_in_before_this_hours  = $mv_tf_in_before_this_amount/$row_payment[$i]['payment_price'];
        if(empty($mv_tf_out_this_hours)){
            $mv_tf_out_this_hours = $mv_tf_out_this_amount/$row_payment[$i]['payment_price'];
        }
        if(empty($mv_tf_out_before_this_hours)){
            $mv_tf_out_before_this_hours = $mv_tf_out_before_this_amount/$row_payment[$i]['payment_price'];
        }*/

        $refund_this_amount = (float)$row_cashout[$payment_id]['this']['refund']['amount'];

        $refund_before_this_amount = (float)$row_cashout[$payment_id]['before_this']['refund']['amount'];

        $beginning_amount = $collected_amount_till_this - $collected_amount_this - $revenue_till_this_amount + $revenue_this_amount + $mv_tf_in_before_this_amount - $mv_tf_out_before_this_amount + $cash_in_before_this_amount - $cash_out_before_this_amount - $refund_before_this_amount - $settle_before_this_amount;

        $carry_amount = $beginning_amount + $collected_amount_this - $revenue_this_amount + $mv_tf_in_this_amount - $mv_tf_out_this_amount + $cash_in_this_amount - $cash_out_this_amount - $refund_this_amount - $settle_this_amount;

        $cr_i = $i;

        //Lam tron so
        if(abs($carry_amount) < 1000){
            $carry_amount = 0;
        }
        if(abs($beginning_amount) < 1000 ){
            $beginning_amount = 0;
        }

        //outstanding amount
        $out_standing     = 0;
        $carry_amount_temp  = $carry_amount;

        if($carry_amount < 0){
            $out_standing = abs($carry_amount);
            $carry_amount_temp = 0;
        }

        if($carry_amount != 0 || $sponsor_type == 'Retake' || $beginning_amount != 0 || $collected_amount_this != 0 || $settle_this_amount != 0 || $revenue_this_amount != 0 ||  $mv_tf_in_this_amount != 0 || $mv_tf_out_this_amount != 0 || $cash_in_this_amount != 0 || $cash_out_this_amount != 0 || $refund_this_amount != 0){
            $count++;
            $beginning_amountt +=$beginning_amount;
            $before_discountt +=$before_discount;
            $discount_amountt +=$discount_amount;
            $sponsor_amountt +=$sponsor_amount;
            $collected_amountt +=$collected_amount_this;
            $collected_hrs_alloct +=$collected_days_till_this;
            $collected_amount_alloct +=$collected_amount_till_this;
            $cash_in_amountt +=$cash_in_this_amount;
            $cash_out_amountt +=$cash_out_this_amount;
            $revenue_amountt +=$revenue_this_amount;
            $revenue_amount_alloct +=$revenue_till_this_amount;
            $mv_tf_in_amountt +=$mv_tf_in_this_amount;
            $mv_tf_out_amountt +=$mv_tf_out_this_amount;
            $refund_amountt +=$refund_this_amount;
            $carry_amount_tempt +=$carry_amount_temp;
            $out_standingt +=$out_standing;
            //Tinh bao cao cho Bac Hung
            $data[$row_payment[$i]['student_id']]['carry_amount']  += $carry_amount_temp;
            if($row_payment[$i]['payment_type'] == 'Enrollment' && $carry_amount_temp > 0){
                $data[$row_payment[$i]['student_id']]['Enrollment']  += 1;
            }

            /*if( ($carry_amount_temp) <= 500000)
            $arr_student[$row_payment[$i]['student_id']]['count_zero']  += 1;*/   //Student Run
        }
    }
    return $data;
}
?>
