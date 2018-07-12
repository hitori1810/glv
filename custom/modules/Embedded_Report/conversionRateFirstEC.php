<?php

    $filter = $this->where;
    $parts = explode("AND", $filter);

    $start = get_string_between($parts[0],"'","'");
    $end = get_string_between($parts[1],"'","'");

    $start_period = get_string_between($parts[2],"'","'");
    $end_period = get_string_between($parts[3],"'","'");

    $center_name = get_string_between($parts[4],"'","'");

    $html  = '<table class="reportlistView" border="0" cellpadding="0" cellspacing="0"><thead>
    <tr>
    <th rowspan="2">First EC</th>

    <th colspan="2">Total New Enquiries</th>
    <th rowspan="2">Targets converted to leads</th>
    <th rowspan="2">Leads converted to students</th>
    <th colspan="2">PT Registration</th>
    <th colspan="2">PT Taken</th>
    <th colspan="2">Demo Registration</th>
    <th colspan="2">Demo Taken</th>
    <th colspan="2">Payment/Enrollments</th>
    <th rowspan="2">Enrol/Enq</th>
    <th rowspan="2">PT Reg/Enq</th>
    <th rowspan="2">PT Taker/PT Reg</th>
    <th rowspan="2">Enrol/PT taker</th>
    <th rowspan="2">Enrol/demo taker</th>
    <th rowspan="2">Enrol/Demo registration</th>
    </tr>
    <tr>
    <th>Actual Number</th>
    <th>Vs same period</th>

    <th>Actual Number</th>
    <th>Vs same period</th>

    <th>Actual Number</th>
    <th>Vs same period</th>

    <th>Actual Number</th>
    <th>Vs same period</th>

    <th>Actual Number</th>
    <th>Vs same period</th>

    <th>Actual Number</th>
    <th>Vs same period</th>

    </tr>
    </thead><tbody>
    ';
    $sql_get_ec = "SELECT DISTINCT IFNULL(users.id, '') id,
    CONCAT(
    IFNULL(users.last_name, ''),
    ' ',
    IFNULL(users.first_name, '')
    ) users_full_name
    FROM
    users
    INNER JOIN teams l1 ON users.default_team = l1.id
    AND l1.deleted = 0
    WHERE
    (
    (
    l1.id = '$center_name'
    )
    )
    AND users.deleted = 0
    ";

    $rs_ec = $GLOBALS['db']->query($sql_get_ec);
    $last_total_eqr = 0;
    $last_total_eqr_period = 0;
    $last_total_lead_converted = 0;
    $last_total_lead = 0;
    $last_total_target_converted = 0;
    $last_total_target = 0;
    $last_total_pt_res = 0;
    $last_total_pt_res_period = 0;
    $last_total_pt_tak = 0;
    $last_total_pt_tak_period = 0;

    $last_total_demo_res = 0;
    $last_total_demo_res_period = 0;
    $last_total_demo_tak = 0;
    $last_total_demo_tak_period = 0;

    $last_total_payment = 0;
    $last_total_payment_period = 0;

    $last_rate_enr_aqr = 0;
    $last_rate_pt_tak_res = 0;
    $last_rate_pt_eqr= 0;
    $last_rate_enr_pt_taker= 0;
    $last_rate_enr_demo_taker = 0;
    $last_rate_enr_demo_res =0;

    while($row_ec = $GLOBALS['db']->fetchByAssoc($rs_ec)) {

        //==============Get Target Converted======================//
        $sql_count_target = "
        SELECT COUNT(prospects.id)
        FROM
        prospects
        INNER JOIN teams l1 ON prospects.team_id = l1.id
        AND l1.deleted = 0
        INNER JOIN users l2 ON prospects.assigned_user_id = l2.id
        AND l2.deleted = 0
        WHERE ((
        (l1.id = '$center_name')
        AND ((((
        prospects.date_entered >= '$start'
        AND prospects.date_entered <= '$end'
        ))
        AND (
        (
        prospects.converted LIKE 'on'
        OR prospects.converted = '1'
        ))
        AND (
        l2.id = '{$row_ec['id']}'
        )))))
        AND prospects.deleted = 0";
        //==============Get lead Converted======================//
        $sql_count_leads = "
        SELECT
        count(leads.id)
        FROM
        leads
        INNER JOIN teams l1 ON leads.team_id = l1.id
        AND l1.deleted = 0
        INNER JOIN users l2 ON leads.assigned_user_id = l2.id
        AND l2.deleted = 0
        WHERE ((((((
        leads.date_entered >= '$start'
        AND leads.date_entered <= '$end'
        )
        )
        AND (
        (
        leads.converted LIKE 'on'
        OR leads.converted = '1'
        )
        )
        AND (
        l2.id = '{$row_ec['id']}'
        )))
        AND (
        l1.id = '$center_name')
        ))
        AND leads.deleted = 0
        ";

        //==============Get Total new enquiries Now======================//
        $student = getStudent($start,$end,$center_name,$row_ec['id']);
        $lead = getLead($start,$end,$center_name,$row_ec['id']);
        $last_total_lead += $lead;
        $target = getTarget($start,$end,$center_name,$row_ec['id']);
        $last_total_target += $target;
        $total_eqr =  $student + $lead + $target;
        //== Last Total ==//
        $last_total_eqr += $total_eqr;

        $start_time = date('Y-m-d H:i:s',strtotime($start));
        $end_time = date('Y-m-d H:i:s',strtotime("$end +23 hours"));

        $start_time_period = date('Y-m-d H:i:s',strtotime($start_period));
        $end_time_period = date('Y-m-d H:i:s',strtotime("$end_period +23 hours"));

        //==============Get Student Lead PT Register======================//
        $count_lead_pt = countLeadRegister($start_time,$end_time,$center_name,$row_ec['id'],'Placement Test');
        $count_student_pt = countStudentRegister($start_time,$end_time,$center_name,$row_ec['id'],'Placement Test');

        //==============Get Student Lead PT Taken======================//
        $count_lead_pt_taken = countLeadTaken($start_time,$end_time,$center_name,$row_ec['id'],'Placement Test');
        $count_student_pt_taken = countStudentTaken($start_time,$end_time,$center_name,$row_ec['id'],'Placement Test');

        //==============Get Student Lead Demo Register======================//
        $count_lead_demo = countLeadRegister($start_time,$end_time,$center_name,$row_ec['id'],'Demo');
        $count_student_demo = countStudentRegister($start_time,$end_time,$center_name,$row_ec['id'],'Demo');

        //==============Get Student Lead Demo Taken======================//
        $count_lead_demo_taken = countLeadTaken($start_time,$end_time,$center_name,$row_ec['id'],'Demo');
        $count_student_demo_taken = countStudentTaken($start_time,$end_time,$center_name,$row_ec['id'],'Demo');

        //==============Total PT Register======================//
        $count_pt_register  = $count_lead_pt + $count_student_pt;
        $last_total_pt_res +=  $count_pt_register;
        //==============Total Demo Register======================//
        $count_demo_register = $count_lead_demo + $count_student_demo;
        $last_total_demo_res += $count_demo_register;
        //==============Total PT Taken======================//
        $count_pt_taken = $count_lead_pt_taken + $count_student_pt_taken;
        $last_total_pt_tak += $count_pt_taken;
        //==============Total Demo Taken======================//
        $count_demo_taken = $count_lead_demo_taken + $count_student_demo_taken;
        $last_total_demo_tak +=  $count_demo_taken;
        //==============Get Led Target Convert======================//
        $target_convert = $GLOBALS['db']->getone($sql_count_target);
        $last_total_target_converted += $target_convert;
        $lead_convert = $GLOBALS['db']->getone($sql_count_leads);
        $last_total_lead_converted += $lead_convert;

        //==============Get Total Enrollment Payment======================//
        $get_payment = getPayment($start,$end,$center_name,$row_ec['id']);
        $last_total_payment += $get_payment;

        //======Enroll / Enquiries====//
        $rate_enr_eqr =  format_number(($get_payment/$total_eqr)*100,2,2);
        $last_rate_enr_aqr += $rate_enr_eqr;
        //======PT register / Enquiries====//
        $rate_pt_eqr =  format_number(($count_pt_register/$total_eqr)*100,2,2);
        $last_rate_pt_eqr  +=  $rate_pt_eqr;
        //======PT taken / pt_register====//
        $rate_pt_tak_res =  format_number(($count_pt_taken/$count_pt_register)*100,2,2);
        $last_rate_pt_tak_res +=  $rate_pt_tak_res;
        //======Enroll / pttaker====//
        $rate_enr_pt_taker =  format_number(($get_payment/$count_pt_taken)*100,2,2);
        $last_rate_enr_pt_taker += $rate_enr_pt_taker;
        //======Enroll / demotaker====//
        $rate_enr_demo_taker =  format_number(($get_payment/$count_demo_taken)*100,2,2);
        $last_rate_enr_demo_taker += $rate_enr_demo_taker;
        //======Enroll / demo register====//
        $rate_enr_demo_res =  format_number(($get_payment/$count_demo_register)*100,2,2);
        $last_rate_enr_demo_res += $rate_enr_demo_res;



        //============================Same Period============================//

        //==============Get Total new enquiries Period======================//
        $student_period = getStudent($start_period,$end_period,$center_name,$row_ec['id']);
        $lead_period = getLead($start_period,$end_period,$center_name,$row_ec['id']);
        $target_period = getTarget($start_period,$end_period,$center_name,$row_ec['id']);
        $total_eqr_period =  $student_period + $lead_period + $target_period;
        $last_total_eqr_period += $total_eqr_period;
        //==============Get Student Lead PT Register period======================//
        $count_lead_pt_period = countLeadRegister($start_time_period,$end_time_period,$center_name,$row_ec['id'],'Placement Test');
        $count_student_pt_period = countStudentRegister($start_time_period,$end_time_period,$center_name,$row_ec['id'],'Placement Test');

        //==============Total PT Register _period======================//
        $count_pt_register_period = $count_lead_pt_period + $count_student_pt_period;
        $last_total_pt_res_period +=  $count_pt_register_period;
        //==============Get Student Lead Demo Register Period======================//
        $count_lead_demo_period = countLeadRegister($start_time_period,$end_time_period,$center_name,$row_ec['id'],'Demo');
        $count_student_demo_period = countStudentRegister($start_time_period,$end_time_period,$center_name,$row_ec['id'],'Demo');

        //==============Total Demo Register======================//
        $count_demo_register_period = $count_lead_demo_period + $count_student_demo_period;
        $last_total_demo_res_period +=  $count_demo_register_period;
        //==============Get Student Lead PT Taken======================//
        $count_lead_pt_taken_period = countLeadTaken($start_time_period,$end_time_period,$center_name,$row_ec['id'],'Placement Test');
        $count_student_pt_taken_period = countStudentTaken($start_time_period,$end_time_period,$center_name,$row_ec['id'],'Placement Test');

        //==============Total PT Taken======================//
        $count_pt_taken_period = $count_lead_pt_taken_period + $count_student_pt_taken_period;
        $last_total_pt_tak_period += $count_pt_taken_period;
        //==============Get Student Lead Demo Taken======================//
        $count_lead_demo_taken_period = countLeadTaken($start_time_period,$end_time_period,$center_name,$row_ec['id'],'Demo');
        $count_student_demo_taken_period = countStudentTaken($start_time_period,$end_time_period,$center_name,$row_ec['id'],'Demo');

        //==============Total Demo Taken======================//
        $count_demo_taken_period = $count_lead_demo_taken_period + $count_student_demo_taken_period;
        $last_total_demo_tak_period += $count_demo_taken_period;
        //==============Get Total Enrollment Payment Period======================//
        $get_payment_period = getPayment($start_period,$end_period,$center_name,$row_ec['id']);
        $last_total_payment_period +=  $get_payment_period;




        //============================Fill Result In html============================//
        $html .= '<tr>';
        $html .= '<td>'.$row_ec['users_full_name'].'</td>';
        $html .= '<td>'.$total_eqr.'</td>';
        $html .= '<td>'.$total_eqr_period.'</td>';
        $html .= '<td>'.$target_convert.'/'.$target.'</td>';
        $html .= '<td>'.$lead_convert.'/'.$lead.'</td>';
        $html .= '<td>'.$count_pt_register.'</td>';
        $html .= '<td>'.$count_pt_register_period.'</td>';
        $html .= '<td>'.$count_pt_taken.'</td>';
        $html .= '<td>'.$count_pt_taken_period.'</td>';
        $html .= '<td>'.$count_demo_register.'</td>';
        $html .= '<td>'.$count_demo_register_period.'</td>';
        $html .= '<td>'.$count_demo_taken.'</td>';
        $html .= '<td>'.$count_demo_taken_period.'</td>';
        $html .= '<td>'.$get_payment.'</td>';
        $html .= '<td>'.$get_payment_period.'</td>';
        //==Enrol/Enq==//
        $html .= '<td>'.$rate_enr_eqr.'</td>';
        //==PT Reg/Enq==//
        $html .= '<td>'.$rate_pt_eqr.'</td>';
        //==PT Taker/PT Reg==//
        $html .= '<td>'.$rate_pt_tak_res.'</td>';
        //==Enrol/PT taker==//
        $html .= '<td>'.$rate_enr_pt_taker.'</td>';
        //==Enrol/demo taker==//
        $html .= '<td>'.$rate_enr_demo_taker.'</td>';
        //==Enrol/Demo registration==//
        $html .= '<td>'.$rate_enr_demo_res.'</td>';
        $html .= '</tr>';
    }
    $html .='<tr><td>Total</td>';
    $html .='<td>'.$last_total_eqr.'</td>';
    $html .='<td>'.$last_total_eqr_period.'</td>';
    $html .= '<td>'.$last_total_target_converted.'/'.$last_total_target.'</td>';
    $html .= '<td>'.$last_total_lead_converted.'/'.$last_total_lead.'</td>';
    $html .= '<td>'.$last_total_pt_res.'</td>';
    $html .= '<td>'.$last_total_pt_res_period.'</td>';
    $html .= '<td>'.$last_total_pt_tak.'</td>';
    $html .= '<td>'.$last_total_pt_tak_period.'</td>';
    $html .= '<td>'.$last_total_demo_res.'</td>';
    $html .= '<td>'.$last_total_demo_res_period.'</td>';
    $html .= '<td>'.$last_total_demo_tak.'</td>';
    $html .= '<td>'.$last_total_demo_tak_period.'</td>';
    $html .= '<td>'.$last_total_payment.'</td>';
    $html .= '<td>'.$last_total_payment_period.'</td>';

    $html .= '<td>'.$last_rate_enr_aqr.'</td>';
    $html .= '<td>'.$last_rate_pt_eqr.'</td>';
    $html .= '<td>'.$last_rate_pt_tak_res.'</td>';
    $html .= '<td>'.$last_rate_enr_pt_taker.'</td>';
    $html .= '<td>'.$last_rate_enr_demo_taker.'</td>';
    $html .= '<td>'.$last_rate_enr_demo_res.'</td>';


    $html .='<tr>';
    echo $html;


    function getStudent($start,$end,$center_name,$ec_id){
        //==================== get student Source ====================//
        $sql_student_source = "
        SELECT count(contacts.id)
        FROM
        contacts
        INNER JOIN users l1 ON contacts.assigned_user_id = l1.id
        AND l1.deleted = 0
        INNER JOIN teams l2 ON contacts.team_id = l2.id
        AND l2.deleted = 0
        WHERE
        ((
        (
        l2.id = '$center_name'
        )

        AND ((((
        contacts.date_entered >= '$start'
        AND contacts.date_entered <= '$end'
        )
        )
        AND (
        l1.id = '$ec_id')))))
        AND contacts.deleted = 0
        ";
        $result = $GLOBALS['db']->getone($sql_student_source);
        return $result;
    }

    function getLead($start,$end,$center_name,$ec_id){
        //==================== get lEAD Source ====================//
        $sql_lead_source = "
        SELECT count(leads.id)
        FROM
        leads
        INNER JOIN teams l1 ON leads.team_id = l1.id
        AND l1.deleted = 0
        INNER JOIN users l2 ON leads.assigned_user_id = l2.id
        AND l2.deleted = 0
        WHERE
        ((
        (l1.id = '$center_name')
        AND ((((
        leads.date_entered >= '$start'
        AND leads.date_entered <= '$end'
        )
        )
        AND (
        (
        leads.converted IS NULL
        OR leads.converted = '0'
        )
        )
        AND (
        l2.id = '$ec_id')))))
        AND leads.deleted = 0
        ";
        $result = $GLOBALS['db']->getone($sql_lead_source);
        return $result;
    }

    function getTarget($start,$end,$center_name,$ec_id){
        //==================== get Target Source ====================//
        $sql_target_source = "
        SELECT
        count(prospects.id)
        FROM
        prospects
        INNER JOIN teams l1 ON prospects.team_id = l1.id
        AND l1.deleted = 0
        INNER JOIN users l2 ON prospects.assigned_user_id = l2.id
        AND l2.deleted = 0
        WHERE
        ((((((
        prospects.date_entered >= '$start'
        AND prospects.date_entered <= '$end'
        )
        )
        AND (
        (
        prospects.converted IS NULL
        OR prospects.converted = '0'
        )
        )
        AND (
        l2.id = '$ec_id'
        )))
        AND (l1.id = '$center_name')))
        AND prospects.deleted = 0
        ";
        $result = $GLOBALS['db']->getone($sql_target_source);
        return $result;
    }


    //==================== get Lead PT Register ====================//
    function countLeadRegister($start,$end,$center_name,$ec_id,$type){
        $sql_lead = "
        SELECT count(l1.id)
        FROM
        j_ptresult
        INNER JOIN leads_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.leads_j_ptresult_1j_ptresult_idb
        AND l1_1.deleted = 0
        INNER JOIN leads l1 ON l1.id = l1_1.leads_j_ptresult_1leads_ida
        AND l1.deleted = 0
        INNER JOIN users l2 ON l1.assigned_user_id = l2.id
        AND l2.deleted = 0
        INNER JOIN teams l3 ON l1.team_id = l3.id
        AND l3.deleted = 0
        WHERE
        (
        (
        (
        (
        (
        j_ptresult.type_result = '$type'
        )
        AND (
        (
        j_ptresult.date_entered >= '$start'
        AND j_ptresult.date_entered <= '$end'
        )
        )
        AND (
        l2.id = '$ec_id'
        )
        )
        )
        AND (
        l3.id = '$center_name'
        )
        )
        )
        AND j_ptresult.deleted = 0
        ";

        $result = $GLOBALS['db']->getone($sql_lead);
        return $result;
    }

    //==================== get Student PT Register ====================//
    function countStudentRegister($start,$end,$center_name,$ec_id,$type){
        $sql_student = "
        SELECT count(l1.id)
        FROM
        j_ptresult
        INNER JOIN contacts_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.contacts_j_ptresult_1j_ptresult_idb
        AND l1_1.deleted = 0
        INNER JOIN contacts l1 ON l1.id = l1_1.contacts_j_ptresult_1contacts_ida
        AND l1.deleted = 0
        INNER JOIN users l2 ON l1.assigned_user_id = l2.id
        AND l2.deleted = 0
        INNER JOIN teams l3 ON l1.team_id = l3.id
        AND l3.deleted = 0
        WHERE
        (
        (
        (
        (
        (
        j_ptresult.type_result = '$type'
        )
        AND (
        (
        j_ptresult.date_entered >= '$start'
        AND j_ptresult.date_entered <= '$end'
        )
        )
        AND (
        l2.id = '$ec_id'
        )
        )
        )
        AND (
        l3.id = '$center_name'
        )
        )
        )
        AND j_ptresult.deleted = 0
        ";

        $result = $GLOBALS['db']->getone($sql_student);
        return $result;
    }

    //==================== get Payment = Deposit || Casholder || Enrollment ====================//
    function getPayment($start,$end,$ec_id){
        $sql_get_payment = "
        SELECT count(contacts.id)
        FROM
        contacts
        INNER JOIN users l1 ON contacts.assigned_user_id = l1.id
        AND l1.deleted = 0
        INNER JOIN teams l2 ON contacts.team_id = l2.id
        AND l2.deleted = 0
        INNER JOIN contacts_j_payment_1_c l3_1 ON contacts.id = l3_1.contacts_j_payment_1contacts_ida
        AND l3_1.deleted = 0
        INNER JOIN j_payment l3 ON l3.id = l3_1.contacts_j_payment_1j_payment_idb
        AND l3.deleted = 0
        WHERE
        (
        (
        (
        (
        (
        l3.payment_type = 'Enrollment'
        )
        OR (l3.payment_type = 'Deposit')
        OR (
        l3.payment_type = 'Cashholder'
        )
        )
        )
        AND (
        (
        (
        l1.id = '$ec_id'
        )
        AND (
        (
        l3.payment_date >= '$start'
        AND l3.payment_date <= '$end'
        )
        )
        )
        )
        AND (l2.id = '$center_name'
        )
        )
        )
        AND contacts.deleted = 0
        ";
        $result = $GLOBALS['db']->getone($sql_get_payment);
        return $result;
    }

    //==================== get Lead  Taken ====================//
    function countLeadTaken($start,$end,$center_name,$ec_id,$type){
        $sql_lead = "
        SELECT count(l1.id)
        FROM
        j_ptresult
        INNER JOIN leads_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.leads_j_ptresult_1j_ptresult_idb
        AND l1_1.deleted = 0
        INNER JOIN leads l1 ON l1.id = l1_1.leads_j_ptresult_1leads_ida
        AND l1.deleted = 0
        INNER JOIN users l2 ON l1.assigned_user_id = l2.id
        AND l2.deleted = 0
        INNER JOIN teams l3 ON l1.team_id = l3.id
        AND l3.deleted = 0
        WHERE(
        j_ptresult.type_result = '$type'
        AND ( j_ptresult.taken_date >= '$start' AND j_ptresult.taken_date <= '$end')
        AND (l2.id = '$ec_id')
        AND (l3.id = '$center_name')
        AND j_ptresult.deleted = 0
        )
        ";

        $result = $GLOBALS['db']->getone($sql_lead);
        return $result;
    }

    //==================== get Student PT Register ====================//
    function countStudentTaken($start,$end,$center_name,$ec_id,$type){
        $sql_student = "
        SELECT count(l1.id)
        FROM
        j_ptresult
        INNER JOIN contacts_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.contacts_j_ptresult_1j_ptresult_idb
        AND l1_1.deleted = 0
        INNER JOIN contacts l1 ON l1.id = l1_1.contacts_j_ptresult_1contacts_ida
        AND l1.deleted = 0
        INNER JOIN users l2 ON l1.assigned_user_id = l2.id
        AND l2.deleted = 0
        INNER JOIN teams l3 ON l1.team_id = l3.id
        AND l3.deleted = 0
        WHERE(
        j_ptresult.type_result = '$type'
        AND ( j_ptresult.taken_date >= '$start' AND j_ptresult.taken_date <= '$end')
        AND (l2.id = '$ec_id')
        AND (l3.id = '$center_name')
        AND j_ptresult.deleted = 0
        )

        ";

        $result = $GLOBALS['db']->getone($sql_student);
        return $result;
    }
?>
