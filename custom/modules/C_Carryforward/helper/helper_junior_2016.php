<?php
function getPaymentCF($team_id, $student_id = '', $start, $end){
    global $current_user;
    $ext_student = '';
    $ext_student_2 = '';
    if(!empty($student_id)){
        $ext_student = "AND (l2.id = '$student_id')";
        $ext_student_2 = "AND student_id = '$student_id'";
    }
    $ext_team ="AND j_payment.team_set_id IN
    (SELECT
    tst.team_set_id
    FROM
    team_sets_teams tst
    INNER JOIN
    team_memberships team_memberships ON tst.team_id = team_memberships.team_id
    AND team_memberships.user_id = '{$current_user->id}'
    AND team_memberships.deleted = 0)";
    if ($current_user->isAdmin())
        $ext_team = '';

    $q1 = "SELECT DISTINCT
        IFNULL(j_payment.id, '') payment_id,
        IFNULL(j_payment.kind_of_course_string, '') kind_of_course_string,
        IFNULL(j_payment.level_string, '') level_string,
        IFNULL(j_payment.class_string, '') class_string,
        IFNULL(l2.id, '') student_id,
        IFNULL(l2.contact_id, '') student_code,
        IFNULL(l2.aims_id, '') aims_id,
        IFNULL(l1.id, '') team_id,
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
            LEFT OUTER JOIN
    (SELECT
        cd.date_input, jp.id payment_id
    FROM
        c_deliveryrevenue cd
    INNER JOIN j_payment jp ON jp.id = cd.ju_payment_id
        #AND jp.payment_type = 'Enrollment'
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
            AND j_payment.team_id = '$team_id'
    GROUP BY j_payment.id) jpm ON j_payment.id = jpm.id
            LEFT JOIN
    (SELECT
        payment_id, MIN(start_study) start_study, MAX(end_study) end_study
    FROM
        j_studentsituations
    WHERE
        IFNULL(payment_id, '') <> ''
            AND deleted = 0
            AND team_id = '$team_id'
            $ext_student_2
    GROUP BY payment_id) gss ON gss.payment_id = j_payment.id
            INNER JOIN
        teams l1 ON j_payment.team_id = l1.id
            AND  l1.deleted = 0 and l1.id = '$team_id'
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
        OR j_payment.settle_date >= '$start'
        OR  date_add(jpm.date_modified, interval 7 hour) >= '$start'
        OR j_payment.class_string LIKE '%-W')
        OR IFNULL(cd.date_input, '1900-01-01') >= '$start'))
        AND (j_payment.payment_date <= '$end'
        OR convert(date_add(j_payment.date_entered, interval 7 hour),date) <= '$end')
        AND j_payment.deleted = 0
        $ext_student
        $ext_team
    GROUP BY j_payment.id
    ORDER BY l2.id ASC
    ";
    return $GLOBALS['db']->fetchArray($q1);
}

function getCollected($payment_id, $end)  {
    // get total payment
    /*$ext_student = '';
    if(!empty($student_id)){
        $ext_student = "AND (l3.id = '$student_id')";
    } */
    $q1 = "  SELECT
    IFNULL(l2.id, '') payment_id,
    IFNULL(GROUP_CONCAT(j_paymentdetail.invoice_number SEPARATOR ' '), '') invoice_number,
    DATE_FORMAT(j_paymentdetail.payment_date, '%m-%Y') month_year,
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
    GROUP BY payment_id, month_year";
    //    AND ((COALESCE(LENGTH(j_paymentdetail.invoice_number),0) > 0))
    $rs = $GLOBALS['db']->query($q1);
    $data = array();
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        $data[$row['payment_id']][$row['month_year']]['invoice_number']   = $row['invoice_number'];
        $data[$row['payment_id']][$row['month_year']]['before_discount']   = $row['before_discount'];
        $data[$row['payment_id']][$row['month_year']]['discount_amount']   = $row['discount_amount'];
        $data[$row['payment_id']][$row['month_year']]['sponsor_amount']    = $row['sponsor_amount'];
        $data[$row['payment_id']][$row['month_year']]['payment_amount']    = $row['payment_amount'];
    }
    return $data;
}

function getRevenue($team_id, $student_id, $payment_id, $start, $end){
    //Tính doanh thu theo giờ
    $data =  get_list_revenue_for_cf($team_id, $student_id, $payment_id, "'Enrolled','Moving In','Stopped'", $start, $end, 'Planning');

    //Tinh doanh thu Drop
    $ext_student = '';
    if(!empty($student_id))
        $ext_student = "AND (c_deliveryrevenue.student_id IN ($student_id))";

    $ext_team = '';
    if(!empty($team_id))
        $ext_team = "AND (l3.id = '$team_id')";

    $q1 = "SELECT
    IFNULL(l1.id, '') ju_payment_id,
    DATE_FORMAT(c_deliveryrevenue.date_input, '%m-%Y') month_year,
    IFNULL(SUM(c_deliveryrevenue.amount), 0) amount,
    IFNULL(SUM(c_deliveryrevenue.duration), 0) hours
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
    AND (c_deliveryrevenue.date_input >= '$start'
    AND c_deliveryrevenue.date_input <= '$end')
    $ext_team
    $ext_student))
    AND c_deliveryrevenue.deleted = 0
    GROUP BY ju_payment_id, month_year";
    $rs = $GLOBALS['db']->query($q1);
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        //Cộng dồn vào Payment ID cũ
        $data[$row['ju_payment_id']][$row['month_year']]['amount']  += $row['amount'];
        $data[$row['ju_payment_id']][$row['month_year']]['hours']   += $row['hours'];
    }
    return $data;
}

function getSettle($team_id, $student_id, $payment_id, $start, $end){
    //Tính doanh thu Settle
    $data =  get_list_revenue_for_cf($team_id, $student_id, $payment_id, "'Settle'", $start, $end, 'Planning');
    return $data;
}

function getCashIn($payment_id, $end){
    /*$ext_student = '';
    if(!empty($student_id)){
        $ext_student = "AND (l3.id IN ($student_id))";
    } */
    $q1 = "SELECT
    IFNULL(j_payment.id, '') payment_id,
    DATE_FORMAT(date_add(j_payment.date_entered, interval 7 hour), '%m-%Y') month_year,
    (CASE
    WHEN l1.payment_type IN ('Transfer In', 'Moving In', 'Transfer From AIMS') THEN 'moving_transfer_in'
    ELSE 'delay_cash_in'
    END) as payment_type_group,
    IFNULL(SUM(l1_1.amount), 0) amount,
    IFNULL(SUM(l1_1.hours), 0) hours
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
    GROUP BY payment_id, month_year, payment_type_group";

    $rs = $GLOBALS['db']->query($q1);
    $data = array();
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        $data[$row['payment_id']][$row['month_year']][$row['payment_type_group']]['amount']     = $row['amount'];
        $data[$row['payment_id']][$row['month_year']][$row['payment_type_group']]['hours']      = $row['hours'];
    }
    return $data;

}

function getCashOut($payment_id, $end){
    /*$ext_student = '';
    if(!empty($student_id)){
        $ext_student = "AND (l3.id IN ($student_id))";
    }*/
    $q1 = "SELECT
    IFNULL(l1.id, '') payment_id_out,
    DATE_FORMAT(date_add(j_payment.date_entered, interval 7 hour), '%m-%Y') month_year,
    (CASE
    WHEN j_payment.payment_type IN ('Transfer Out', 'Moving Out') THEN 'moving_transfer_out'
    WHEN j_payment.payment_type IN ('Refund') THEN 'refund'
    ELSE 'delay_cash_out'
    END) as payment_type_group,
    IFNULL(SUM(l1_1.amount), 0) amount,
    IFNULL(SUM(l1_1.hours), 0) hours
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
    GROUP BY payment_id_out, month_year, payment_type_group";

    $rs = $GLOBALS['db']->query($q1);
    $data = array();
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        $data[$row['payment_id_out']][$row['month_year']][$row['payment_type_group']]['amount']     = $row['amount'];
        $data[$row['payment_id_out']][$row['month_year']][$row['payment_type_group']]['hours']      = $row['hours'];
    }
    return $data;

}
function getEliminate($team_id, $student_id, $end){
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

function get_first_payment_date($team_id, $student_id){
    $ext_team = '';
    if(!empty($team_id))
        $ext_team = "AND (l2.id = '$team_id')";

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


function get_list_revenue_for_cf($team_id = '', $student_id = '', $payment_id = '', $situation_type = "'Enrolled'", $start = '', $end = '', $not_status = ''){

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

    if(!empty($start)){
        $start_tz     = date('Y-m-d H:i:s',strtotime("-7 hours ".$start." 00:00:00"));
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

        if(!empty($start)){
            $ext_start = "AND (l4.settle_date >= '$start')";
        }else $ext_start = '';
        $select_date = "l4.settle_date";
    }
    //    AVG(IFNULL((l3.total_amount/l3.total_hour), 0)) cost_per_hour,
    $q1 = "SELECT DISTINCT
    IFNULL(l4.id, '') ju_payment_id,
    DATE_FORMAT($select_date, '%m-%Y') month_year,
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
    GROUP BY ju_payment_id, month_year";
    $rs = $GLOBALS['db']->query($q1);

    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        $data[$row['ju_payment_id']][$row['month_year']]['amount']  = $row['total_revenue_amount'];
        $data[$row['ju_payment_id']][$row['month_year']]['hours']   = $row['total_revenue_hours'];
    }
    return $data;
}

