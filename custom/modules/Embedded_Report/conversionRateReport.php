<?php

    $filter = $this->where;
    $parts = explode("AND", $filter);

    $start = get_string_between($parts[0],"'","'");
    $end = get_string_between($parts[1],"'","'");

    $start_period = get_string_between($parts[2],"'","'");
    $end_period = get_string_between($parts[3],"'","'");

    $start_time = date('Y-m-d H:i:s',strtotime($start));
    $end_time = date('Y-m-d H:i:s',strtotime("$end +23 hours"));

    $start_time_period = date('Y-m-d H:i:s',strtotime($start_period));
    $end_time_period = date('Y-m-d H:i:s',strtotime("$end_period +23 hours"));

    $center_name = get_string_between($parts[4],"'","'");

    //======= tru 1 week =====//

    $html  = '<table class="reportlistView" border="0" cellpadding="0" cellspacing="0"><thead>
    <tr>
    <th rowspan="2">Source</th>
    <th colspan="2">Total New Enquiries</th>
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

    //==================== get Campaign ====================//
    $sql_get_campaign = "
    SELECT DISTINCT
    IFNULL(campaigns.id, '') primaryid,
    IFNULL(campaigns. NAME, '') campaigns_name
    FROM
    campaigns
    INNER JOIN teams l1 ON campaigns.team_id = l1.id
    AND l1.deleted = 0
    WHERE
    (
    (
    (
    l1.id = '4e3de4c1-2c5e-6c00-3494-55667b495afe'
    )
    OR (
    l1.id = '4e3de4c1-2c5e-6c00-3494-55667b495afe'
    )
    )
    )
    AND campaigns.deleted = 0
    ";
    //==================== get Lead Source ====================//
    $sql_get_lead_source = "
    SELECT DISTINCT
    IFNULL(contacts.lead_source, '') contacts_lead_source
    FROM
    contacts
    INNER JOIN teams l1 ON contacts.team_id = l1.id
    AND l1.deleted = 0
    WHERE
    (
    (
    (
    l1.id = '4e3de4c1-2c5e-6c00-3494-55667b495afe'
    )
    OR (
    l1.id = '4e3de4c1-2c5e-6c00-3494-55667b495afe'
    )
    )
    )
    AND contacts.deleted = 0
    ";
    $rs_lead_src = $GLOBALS['db']->query($sql_get_lead_source);

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

    $last_rate_enr_eqr = 0;
    $last_rate_pt_tak_res = 0;
    $last_rate_pt_eqr= 0;
    $last_rate_enr_pt_taker= 0;
    $last_rate_enr_demo_taker = 0;
    $last_rate_enr_demo_res =0;


    while($row_src = $GLOBALS['db']->fetchByAssoc($rs_lead_src)) {
        if($row_src['contacts_lead_source'] != ""){

            //==============Get Total new enquiries Now======================//
            $student = getStudent($start,$end,$center_name,$row_src['contacts_lead_source']);

            $lead = getLead($start,$end,$center_name,$row_src['contacts_lead_source']);

            $target = getTarget($start,$end,$center_name,$row_src['contacts_lead_source']);

            $total_eqr =  $student + $lead + $target;
            //== Last Total ==//
            $last_total_eqr += $total_eqr;



            //==============Get Student Lead PT Register======================//
            $count_lead_pt = countLeadRegister($start_time,$end_time,$center_name,$row_src['contacts_lead_source'],'Placement Test');
            $count_student_pt = countStudentRegister($start_time,$end_time,$center_name,$row_src['contacts_lead_source'],'Placement Test');

            //==============Total PT Register======================//
            $count_pt_register  = $count_lead_pt + $count_student_pt;
            $last_total_pt_res +=  $count_pt_register;

            //==============Get Student Lead PT Taken======================//
            $count_lead_pt_taken = countLeadTaken($start_time,$end_time,$center_name,$row_src['contacts_lead_source'],'Placement Test');
            $count_student_pt_taken = countStudentTaken($start_time,$end_time,$center_name,$row_src['contacts_lead_source'],'Placement Test');

            //==============Total PT Taken======================//
            $count_pt_taken = $count_lead_pt_taken + $count_student_pt_taken;
            $last_total_pt_tak += $count_pt_taken;

            //==============Get Student Lead Demo Register======================//
            $count_lead_demo = countLeadRegister($start_time,$end_time,$center_name,$row_src['contacts_lead_source'],'Demo');
            $count_student_demo = countStudentRegister($start_time,$end_time,$center_name,$row_src['contacts_lead_source'],'Demo');

            //==============Total Demo Register======================//
            $count_demo_register = $count_lead_demo + $count_student_demo;
            $last_total_demo_res += $count_demo_register;

            //==============Get Student Lead Demo Taken======================//
            $count_lead_demo_taken = countLeadTaken($start_time,$end_time,$center_name,$row_src['contacts_lead_source'],'Demo');
            $count_student_demo_taken = countStudentTaken($start_time,$end_time,$center_name,$row_src['contacts_lead_source'],'Demo');

            //==============Total Demo Taken======================//
            $count_demo_taken = $count_lead_demo_taken + $count_student_demo_taken;
            $last_total_demo_tak +=  $count_demo_taken;

            //==============Get Total Enrollment Payment======================//
            $get_payment = getPayment($start,$end,$center_name,$row_src['contacts_lead_source']);
            $last_total_payment += $get_payment;

            //======Enroll / Enquiries====//
            $rate_enr_eqr =  format_number(($get_payment/$total_eqr)*100,2,2);
            $last_rate_enr_eqr += $rate_enr_eqr;
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




            //============================Same Period============================//

            //==============Get Total new enquiries Period======================//
            $student_period = getStudent($start_period,$end_period,$center_name,$row_src['contacts_lead_source']);
            $lead_period = getLead($start_period,$end_period,$center_name,$row_src['contacts_lead_source']);
            $target_period = getTarget($start_period,$end_period,$center_name,$row_src['contacts_lead_source']);
            $total_eqr_period =  $student_period + $lead_period + $target_period;
            $last_total_eqr_period += $total_eqr_period;

            //==============Get Student Lead PT Register period======================//
            $count_lead_pt_period = countLeadRegister($start_time_period,$end_time_period,$center_name,$row_src['contacts_lead_source'],'Placement Test');
            $count_student_pt_period = countStudentRegister($start_time_period,$end_time_period,$center_name,$row_src['contacts_lead_source'],'Placement Test');

            //==============Total PT Register _period======================//
            $count_pt_register_period = $count_lead_pt_period + $count_student_pt_period;
            $last_total_pt_res_period +=  $count_pt_register_period;

            //==============Get Student Lead PT Taken======================//
            $count_lead_pt_taken_period = countLeadTaken($start_time_period,$end_time_period,$center_name,$row_src['contacts_lead_source'],'Placement Test');
            $count_student_pt_taken_period = countStudentTaken($start_time_period,$end_time_period,$center_name,$row_src['contacts_lead_source'],'Placement Test');

            //==============Total PT Taken======================//
            $count_pt_taken_period = $count_lead_pt_taken_period + $count_student_pt_taken_period;
            $last_total_pt_tak_period += $count_pt_taken_period;

            //==============Get Student Lead Demo Register Period======================//
            $count_lead_demo_period = countLeadRegister($start_time_period,$end_time_period,$center_name,$row_src['contacts_lead_source'],'Demo');
            $count_student_demo_period = countStudentRegister($start_time_period,$end_time_period,$center_name,$row_src['contacts_lead_source'],'Demo');

            //==============Total Demo Register======================//
            $count_demo_register_period = $count_lead_demo_period + $count_student_demo_period;
            $last_total_demo_res_period +=  $count_demo_register_period;

            //==============Get Student Lead Demo Taken======================//
            $count_lead_demo_taken_period = countLeadTaken($start_time_period,$end_time_period,$center_name,$row_src['contacts_lead_source'],'Demo');
            $count_student_demo_taken_period = countStudentTaken($start_time_period,$end_time_period,$center_name,$row_src['contacts_lead_source'],'Demo');

            //==============Total Demo Taken======================//
            $count_demo_taken_period = $count_lead_demo_taken_period + $count_student_demo_taken_period;
            $last_total_demo_tak_period += $count_demo_taken_period;

            //==============Get Total Enrollment Payment Period======================//
            $get_payment_period = getPayment($start_period,$end_period,$center_name,$row_src['contacts_lead_source']);
            $last_total_payment_period +=  $get_payment_period;







            //============================Fill Result In html============================//
            if($row_src['contacts_lead_source'] == 'Campaign'){
                $rs_campaign_src = $GLOBALS['db']->query($sql_get_campaign);
                while($row_campaign = $GLOBALS['db']->fetchByAssoc($rs_campaign_src)) {
                    $html .='<tr>';
                    //============================Calculate Campaign============================//

                    //==============Get Total new enquiries Now======================//
                    $student_cam = getStudentCampaign($start,$end,$center_name,$row_campaign['primaryid']);
                    $lead_cam = getLeadCampaign($start,$end,$center_name,$row_campaign['primaryid']);
                    $target_cam = getTargetCampaign($start,$end,$center_name,$row_campaign['primaryid']);
                    $total_eqr_cam =  $student_cam + $lead_cam + $target_cam;
                  //  $last_total_eqr += $total_eqr_cam;

                    //==============Get Total new enquiries Period======================//
                    $student_cam_period = getStudentCampaign($start_period,$end_period,$center_name,$row_campaign['primaryid']);
                    $lead_cam_period = getLeadCampaign($start_period,$end_period,$center_name,$row_campaign['primaryid']);
                    $target_cam_period = getTargetCampaign($start_period,$end_period,$center_name,$row_campaign['primaryid']);
                    $total_eqr_cam_period =  $student_cam_period + $lead_cam_period + $target_cam_period;
                    $last_total_eqr_period += $total_eqr_cam_period;

                    //==============Get Student Lead PT Register======================//
                    $count_lead_pt_cam = countLeadRegisterCampaign($start_time,$end_time,$center_name,$row_campaign['primaryid'],'Placement Test');
                    $count_student_pt_cam = countStudentRegisterCampaign($start_time,$end_time,$center_name,$row_campaign['primaryid'],'Placement Test');
                    $count_pt_register_cam  = $count_lead_pt_cam + $count_student_pt_cam;
                    $last_total_pt_res +=  $count_pt_register_cam;

                    //==============Get Student Lead PT Register period======================//
                    $count_lead_pt_cam_period = countLeadRegisterCampaign($start_time_period,$end_time_period,$center_name,$row_campaign['primaryid'],'Placement Test');
                    $count_student_pt_cam_period = countStudentRegisterCampaign($start_time_period,$end_time_period,$center_name,$row_campaign['primaryid'],'Placement Test');
                    $count_pt_register_cam_period = $count_lead_pt_cam_period + $count_student_pt_cam_period;
                    $last_total_pt_res_period +=  $count_pt_register_period;

                    //==============Get Student Lead PT Taken======================//
                    $count_lead_pt_cam_taken = countLeadTakenCampaign($start_time,$end_time,$center_name,$row_campaign['primaryid'],'Placement Test');
                    $count_student_pt_cam_taken = countStudentTakenCampaign($start_time,$end_time,$center_name,$row_campaign['primaryid'],'Placement Test');
                    $count_pt_cam_taken = $count_lead_pt_cam_taken + $count_student_pt_cam_taken;
                    $last_total_pt_tak += $count_pt_cam_taken;
                    //==============Get Student Lead PT Taken======================//
                    $count_lead_pt_taken_cam_period = countLeadTakenCampaign($start_time_period,$end_time_period,$center_name,$row_campaign['primaryid'],'Placement Test');
                    $count_student_pt_cam_taken_period = countStudentTakenCampaign($start_time_period,$end_time_period,$center_name,$row_campaign['primaryid'],'Placement Test');
                    $count_pt_taken_cam_period = $count_lead_pt_taken_cam_period + $count_student_pt_cam_taken_period;
                    $last_total_pt_tak_period += $count_pt_taken_cam_period;


                    //==============Get Student Lead Demo Register======================//
                    $count_lead_demo_cam = countLeadRegisterCampaign($start_time,$end_time,$center_name,$row_campaign['primaryid'],'Demo');
                    $count_student_demo_cam = countStudentRegisterCampaign($start_time,$end_time,$center_name,$row_campaign['primaryid'],'Demo');
                    $count_demo_register_cam  = $count_lead_demo_cam + $count_student_demo_cam;
                    $last_total_demo_res +=  $count_demo_register_cam;

                    //==============Get Student Lead Demo Register period======================//
                    $count_lead_demo_cam_period = countLeadRegisterCampaign($start_time_period,$end_time_period,$center_name,$row_campaign['primaryid'],'Demo');
                    $count_student_demo_cam_period = countStudentRegisterCampaign($start_time_period,$end_time_period,$center_name,$row_campaign['primaryid'],'Demo');
                    $count_demo_register_cam_period = $count_lead_demo_cam_period + $count_student_demo_cam_period;
                    $last_total_demo_res_period +=  $count_demo_register_period;

                    //==============Get Student Lead Demo Taken======================//
                    $count_lead_demo_cam_taken = countLeadTakenCampaign($start_time,$end_time,$center_name,$row_campaign['primaryid'],'Demo');
                    $count_student_demo_cam_taken = countStudentTakenCampaign($start_time,$end_time,$center_name,$row_campaign['primaryid'],'Demo');
                    $count_demo_cam_taken = $count_lead_demo_cam_taken + $count_student_demo_cam_taken;
                    $last_total_demo_tak += $count_demo_cam_taken;
                    //==============Get Student Lead Demo Taken======================//
                    $count_lead_demo_taken_cam_period = countLeadTakenCampaign($start_time_period,$end_time_period,$center_name,$row_campaign['primaryid'],'Demo');
                    $count_student_demo_cam_taken_period = countStudentTakenCampaign($start_time_period,$end_time_period,$center_name,$row_campaign['primaryid'],'Demo');
                    $count_demo_taken_cam_period = $count_lead_demo_taken_cam_period + $count_student_demo_cam_taken_period;
                    $last_total_demo_tak_period += $count_demo_taken_cam_period;

                    //==============Get Total Enrollment Payment======================//
                    $get_payment_cam = getPaymentCampaign($start,$end,$center_name,$row_campaign['primaryid']);
                    $last_total_payment += $get_payment_cam;
                    $get_payment_cam_period = getPaymentCampaign($start_period,$end_period,$center_name,$row_campaign['primaryid']);
                    $last_total_payment_period +=  $get_payment_cam_period;

                    //======Enroll / Enquiries====//
                    $rate_enr_eqr_cam =  format_number(($get_payment_cam/$total_eqr_cam)*100,2,2);
                    $last_rate_enr_eqr += $rate_enr_eqr_cam;
                    //======PT register / Enquiries====//
                    $rate_pt_eqr_cam =  format_number(($count_pt_register_cam/$total_eqr_cam)*100,2,2);
                    $last_rate_pt_eqr  +=  $rate_pt_eqr_cam;
                    //======PT taken / pt_register====//
                    $rate_pt_tak_res_cam =  format_number(($count_pt_cam_taken/$count_pt_register_cam)*100,2,2);
                    $last_rate_pt_tak_res +=  $rate_pt_tak_res_cam;
                    //======Enroll / pttaker====//
                    $rate_enr_pt_taker_cam =  format_number(($get_payment_cam/$count_pt_cam_taken)*100,2,2);
                    $last_rate_enr_pt_taker += $rate_enr_pt_taker_cam;
                    //======Enroll / demotaker====//
                    $rate_enr_demo_taker_cam =  format_number(($get_payment_cam/$count_demo_cam_taken)*100,2,2);
                    $last_rate_enr_demo_taker += $rate_enr_demo_taker_cam;

                    $html .= '<td>'.$row_campaign['campaigns_name'].'</td>';
                    $html .= '<td>'.$total_eqr_cam.'</td>';
                    $html .= '<td>'.$total_eqr_cam_period.'</td>';
                    $html .= '<td>'.$count_pt_register_cam.'</td>';
                    $html .= '<td>'.$count_pt_register_cam_period.'</td>';
                    $html .= '<td>'.$count_pt_cam_taken.'</td>';
                    $html .= '<td>'.$count_pt_taken_cam_period.'</td>';
                    $html .= '<td>'.$count_demo_register_cam.'</td>';
                    $html .= '<td>'.$count_demo_register_period.'</td>';
                    $html .= '<td>'.$count_demo_cam_taken.'</td>';
                    $html .= '<td>'.$count_demo_taken_cam_period.'</td>';
                    $html .= '<td>'.$get_payment_cam.'</td>';
                    $html .= '<td>'.$get_payment_cam_period.'</td>';
                    $html .= '<td>'.$rate_enr_eqr_cam.'</td>';
                    $html .= '<td>'.$rate_pt_eqr_cam.'</td>';
                    $html .= '<td>'.$rate_pt_tak_res_cam.'</td>';
                    $html .= '<td>'.$rate_enr_pt_taker_cam.'</td>';
                    $html .= '<td>'.$rate_enr_demo_taker_cam.'</td>';
                    $html .='</tr>';
                }

            }  else {
                $html .='<tr>';
                $html .= '<td>'.$row_src['contacts_lead_source'].'</td>';
                $html .='<td>'.$total_eqr.'</td>';
                $html .='<td>'.$total_eqr_period.'</td>';
                $html .='<td>'.$count_pt_register.'</td>';
                $html .='<td>'.$count_pt_register_period.'</td>';
                $html .='<td>'.$count_pt_taken.'</td>';
                $html .='<td>'.$count_pt_taken_period.'</td>';
                $html .='<td>'.$count_demo_register.'</td>';
                $html .='<td>'.$count_demo_register_period.'</td>';
                $html .='<td>'.$count_demo_taken.'</td>';
                $html .='<td>'.$count_demo_taken_period.'</td>';
                $html .='<td>'.$get_payment.'</td>';
                $html .='<td>'.$get_payment_period.'</td>';
                $html .='<td>'.$rate_enr_eqr.'</td>';
                $html .='<td>'.$rate_pt_eqr.'</td>';
                $html .='<td>'.$rate_pt_tak_res.'</td>';
                $html .='<td>'.$rate_enr_pt_taker.'</td>';
                $html .='<td>'.$rate_enr_demo_taker.'</td>';



                $html .='</tr>';

            }

        }
    }
    $html .='<tr><td>Total</td>';
    $html .='<td>'.$last_total_eqr.'</td>';
    $html .='<td>'.$last_total_eqr_period.'</td>';
    $html .='<td>'.$last_total_pt_res.'</td>';
    $html .='<td>'.$last_total_pt_res_period.'</td>';
    $html .='<td>'.$last_total_pt_tak.'</td>';
    $html .='<td>'.$last_total_pt_tak_period.'</td>';
    $html .='<td>'.$last_total_demo_res.'</td>';
    $html .='<td>'.$last_total_demo_res_period.'</td>';
    $html .='<td>'.$last_total_demo_tak.'</td>';
    $html .='<td>'.$last_total_demo_tak_period.'</td>';
    $html .='<td>'.$last_total_payment.'</td>';
    $html .='<td>'.$last_total_payment_period.'</td>';
    $html .='<td>'.$last_rate_enr_eqr.'</td>';
    $html .='<td>'.$last_rate_pt_eqr.'</td>';
    $html .='<td>'.$last_rate_pt_tak_res.'</td>';
    $html .='<td>'.$last_rate_enr_pt_taker.'</td>';
    $html .='<td>'.$last_rate_enr_demo_taker.'</td>';

    $html .= '</tbody></table>';
    echo $html;


    function getStudent($start,$end,$center_name,$source){
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
        contacts.lead_source = '$source')))))
        AND contacts.deleted = 0
        ";
        $result = $GLOBALS['db']->getone($sql_student_source);
        return $result;
    }

    function getLead($start,$end,$center_name,$source){
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
        leads.lead_source = '$source')))))
        AND leads.deleted = 0
        ";
        $result = $GLOBALS['db']->getone($sql_lead_source);
        return $result;
    }

    function getTarget($start,$end,$center_name,$source){
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
        prospects.lead_source = '$source'
        )))
        AND (l1.id = '$center_name')))
        AND prospects.deleted = 0
        ";
        $result = $GLOBALS['db']->getone($sql_target_source);
        return $result;
    }

    //==================== get Lead PT Register ====================//
    function countLeadRegister($start,$end,$center_name,$source,$type){
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
        l1.lead_source = '$source'
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
    function countStudentRegister($start,$end,$center_name,$source,$type){
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
        l1.lead_source = '$source'
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
    function getPayment($start,$end,$center_name,$source){
        $sql_get_payment = "
        SELECT count(contacts.id)
        FROM
        contacts
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
        contacts.lead_source = '$source'
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
    function countLeadTaken($start,$end,$center_name,$source,$type){
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
        AND (l1.lead_source = '$source')
        AND (l3.id = '$center_name')
        AND j_ptresult.deleted = 0
        )
        ";

        $result = $GLOBALS['db']->getone($sql_lead);
        return $result;
    }

    //==================== get Student PT Register ====================//
    function countStudentTaken($start,$end,$center_name,$source,$type){
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
        AND (l1.lead_source = '$source')
        AND (l3.id = '$center_name')
        AND j_ptresult.deleted = 0
        )

        ";

        $result = $GLOBALS['db']->getone($sql_student);
        return $result;
    }


    //==================== Campaign ====================//
    function getStudentCampaign($start,$end,$center_name,$campaign_id){
        //==================== get student Source ====================//
        $sql_student_source = "
        SELECT count(contacts.id)
        FROM
        contacts
        INNER JOIN teams l1 ON contacts.team_id = l1.id
        AND l1.deleted = 0
        INNER JOIN campaigns l2 ON contacts.campaign_id = l2.id
        AND l2.deleted = 0
        WHERE
        (
        (
        (
        (
        contacts.date_entered >= '$start'
        AND contacts.date_entered <= '$end'
        )
        )
        AND (
        l1.id = '$center_name'
        )
        AND (
        l2.id = '$campaign_id'
        )
        )
        )
        AND contacts.deleted = 0
        ";
        $result = $GLOBALS['db']->getone($sql_student_source);
        return $result;
    }

    function getLeadCampaign($start,$end,$center_name,$campaign_id){
        //==================== get lEAD Source ====================//
        $sql_lead_source = "
        SELECT count(leads.id)
        FROM
        leads
        INNER JOIN teams l1 ON leads.team_id = l1.id
        AND l1.deleted = 0
        INNER JOIN campaigns l2 ON leads.campaign_id = l2.id
        AND l2.deleted = 0
        WHERE
        (
        (
        (
        (
        leads.date_entered >= '$start'
        AND leads.date_entered <= '$end'
        )
        )
        AND (
        l1.id = '$center_name'
        )
        AND (
        l2.id = '$campaign_id'
        )
        AND (
        (
        leads.converted IS NULL
        OR leads.converted = '0'
        )
        )
        )
        )
        AND leads.deleted = 0
        ";
        $result = $GLOBALS['db']->getone($sql_lead_source);
        return $result;
    }

    function getTargetCampaign($start,$end,$center_name,$campaign_id){
        //==================== get Target Source ====================//
        $sql_target_source = "
        SELECT DISTINCT
        count(prospects.id)
        FROM
        prospects
        INNER JOIN teams l1 ON prospects.team_id = l1.id
        AND l1.deleted = 0
        INNER JOIN campaigns l2 ON prospects.campaign_id = l2.id
        AND l2.deleted = 0
        WHERE
        (
        (
        (
        (
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
        l1.id = '$center_name'
        )
        AND (
        l2.id = '$campaign_id'
        )
        )
        )
        AND prospects.deleted = 0
        ";
        $result = $GLOBALS['db']->getone($sql_target_source);
        return $result;
    }

    //==================== get Lead PT Register ====================//
    function countLeadRegisterCampaign($start,$end,$center_name,$campaign_id,$type){
        $sql_lead = "
        SELECT count(l1.id)
        FROM
        j_ptresult
        INNER JOIN leads_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.leads_j_ptresult_1j_ptresult_idb
        AND l1_1.deleted = 0
        INNER JOIN leads l1 ON l1.id = l1_1.leads_j_ptresult_1leads_ida
        AND l1.deleted = 0
        INNER JOIN teams l3 ON l1.team_id = l3.id
        AND l3.deleted = 0
        INNER JOIN campaigns l4 ON l1.campaign_id = l4.id
        AND l4.deleted = 0
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
        l1.lead_source = '$source'
        )
        )
        )
        AND (
        l3.id = '$center_name'
        )
        AND (l4.id = '$campaign_id')
        )
        )
        AND j_ptresult.deleted = 0
        ";

        $result = $GLOBALS['db']->getone($sql_lead);
        return $result;
    }

    //==================== get Student PT Register ====================//
    function countStudentRegisterCampaign($start,$end,$center_name,$campaign_id,$type){
        $sql_student = "
        SELECT count(l1.id)
        FROM
        j_ptresult
        INNER JOIN contacts_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.contacts_j_ptresult_1j_ptresult_idb
        AND l1_1.deleted = 0
        INNER JOIN contacts l1 ON l1.id = l1_1.contacts_j_ptresult_1contacts_ida
        AND l1.deleted = 0
        INNER JOIN teams l3 ON l1.team_id = l3.id
        AND l3.deleted = 0
        INNER JOIN campaigns l4 ON l1.campaign_id = l4.id
        AND l4.deleted = 0
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
        l1.lead_source = '$source'
        )
        )
        )
        AND (
        l3.id = '$center_name'
        )
        AND (l4.id = '$campaign_id')
        )
        )
        AND j_ptresult.deleted = 0
        ";

        $result = $GLOBALS['db']->getone($sql_student);
        return $result;
    }

    //==================== get Lead  Taken ====================//
    function countLeadTakenCampaign($start,$end,$center_name,$campaign_id,$type){
        $sql_lead = "
        SELECT count(l1.id)
        FROM
        j_ptresult
        INNER JOIN leads_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.leads_j_ptresult_1j_ptresult_idb
        AND l1_1.deleted = 0
        INNER JOIN leads l1 ON l1.id = l1_1.leads_j_ptresult_1leads_ida
        AND l1.deleted = 0
        INNER JOIN teams l3 ON l1.team_id = l3.id
        AND l3.deleted = 0
        INNER JOIN campaigns l4 ON l1.campaign_id = l4.id
        AND l4.deleted = 0
        WHERE(
        j_ptresult.type_result = '$type'
        AND ( j_ptresult.taken_date >= '$start' AND j_ptresult.taken_date <= '$end')
        AND (l4.id = '$campaign_id')
        AND (l3.id = '$center_name')
        AND j_ptresult.deleted = 0
        )
        ";

        $result = $GLOBALS['db']->getone($sql_lead);
        return $result;
    }

    //==================== get Student PT Register ====================//
    function countStudentTakenCampaign($start,$end,$center_name,$campaign_id,$type){
        $sql_student = "
        SELECT count(l1.id)
        FROM
        j_ptresult
        INNER JOIN contacts_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.contacts_j_ptresult_1j_ptresult_idb
        AND l1_1.deleted = 0
        INNER JOIN contacts l1 ON l1.id = l1_1.contacts_j_ptresult_1contacts_ida
        AND l1.deleted = 0
        INNER JOIN teams l3 ON l1.team_id = l3.id
        AND l3.deleted = 0
        INNER JOIN campaigns l4 ON l1.campaign_id = l4.id
        AND l4.deleted = 0
        WHERE(
        j_ptresult.type_result = '$type'
        AND ( j_ptresult.taken_date >= '$start' AND j_ptresult.taken_date <= '$end')
        AND (l4.id = '$campaign_id')
        AND (l3.id = '$center_name')
        AND j_ptresult.deleted = 0
        )

        ";

        $result = $GLOBALS['db']->getone($sql_student);
        return $result;
    }

    //==================== get Payment = Deposit || Casholder || Enrollment ====================//
    function getPaymentCampaign($start,$end,$campaign_id){
        $sql_get_payment = "
        SELECT count(contacts.id)
        FROM
        contacts
        INNER JOIN teams l2 ON contacts.team_id = l2.id
        AND l2.deleted = 0
        INNER JOIN contacts_j_payment_1_c l3_1 ON contacts.id = l3_1.contacts_j_payment_1contacts_ida
        AND l3_1.deleted = 0
        INNER JOIN j_payment l3 ON l3.id = l3_1.contacts_j_payment_1j_payment_idb
        AND l3.deleted = 0
        INNER JOIN campaigns l4 ON contacts.campaign_id = l4.id
        AND l4.deleted = 0
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
        l4.id = '$campaign_id'
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

?>
