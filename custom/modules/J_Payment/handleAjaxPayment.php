<?php
require_once("custom/include/_helper/junior_revenue_utils.php");

switch ($_POST['type']) {
    case 'ajaxGetStudentInfo':
        $result = ajaxGetStudentInfo($_POST['student_id'], $_POST['enrollment_id'], $_POST['payment_type'], $_POST['payment_date'], $_POST['current_team_id']);
        echo $result;
        break;
    case 'ajaxLoadStudent':
        $result = ajaxLoadStudent($_POST['q']);
        echo $result;
        break;
    case 'ajaxGetInvoice':
        $result = ajaxGetInvoice($_POST['payment_detail']);
        echo $result;
        break;
    case 'ajaxUpdatePaymentDetail':
        $result = ajaxUpdatePaymentDetail($_POST['payment_detail'], $_POST['payment_method'], $_POST['card_type'], $_POST['bank_type'], $_POST['payment_date'], $_POST['method_note'], $_POST['handle_action']);
        echo $result;
        break;
    case 'ajaxUndoPayment':
        $result = ajaxUndoPayment($_POST['payment_id'], $_POST['payment_type']);
        echo $result;
        break;
    case 'ajaxRealseInvoiceNo':
        $result = ajaxRealseInvoiceNo($_POST['pay_dtl_id']);
        echo $result;
        break;
    case 'ajaxSaveInvoiceNo':
        $result = ajaxSaveInvoiceNo($_POST['pay_dtl_id'], $_POST['new_invoice_no']);
        echo $result;
        break;
    case 'finish_printing':
        $result = finish_printing($_POST['printing_id']);
        echo $result;
        break;
    case 'caculateDropPayment':
        $result = caculateDropPayment($_POST['payment_id'], $_POST['dl_date']);
        echo $result;
        break;
    case 'createDropPayment':
        $result = createDropPayment();
        echo $result;
        break;
    case 'ajaxConvertPayment':
        $result = ajaxConvertPayment();
        echo $result;
        break;
    case 'autoGetNextInvoice':
        $result = autoGetNextInvoice($_POST['team_id']);
        echo $result;
        break;
    case 'autoCheckInvoiceReleased':
        $result = autoCheckInvoiceReleased($_POST['pay_dtl_id']);
        echo $result;
        break;
    case 'get_class_info_adult':  //Custom Adult
        $result = get_class_info_adult();
        echo $result;
        break;
    case 'submitAddToClass':  //Custom Adult
        $result = submitAddToClass();
        echo $result;
        break;
    case 'caculateDropPaymentAdult':
        $result = caculateDropPaymentAdult($_POST['payment_id'], $_POST['dl_date']);
        echo $result;
        break;
    case 'createDropPaymentAdult':
        $result = createDropPaymentAdult();
        echo $result;
        break;
    case 'enable_delay_fee':
        $result = enable_delay_fee();
        echo $result;
        break;
    case 'ajaxCheckVoucherCode':
        $result = ajaxCheckVoucherCode();
        echo $result;
        break;
    case 'ajax_payment_detail':
        $result = ajax_payment_detail();
        echo $result;
        break;
    case 'ajaxSaveInvoice':
        $result = ajaxSaveInvoice();
        echo $result;
        break;
    case 'ajaxCancelInvoice':
        $result = ajaxCancelInvoice($_POST['payment_detail'], $_POST['description']);
        echo $result;
        break;
}

// ----------------------------------------------------------------------------------------------------------\\

function ajaxGetStudentInfo($student_id, $enrollment_id = '', $payment_type = 'Enrollment', $payment_date, $current_team_id)
{
    global $timedate, $current_user;
    if (empty($payment_date))
        $payment_date = $timedate->nowDate();
    // Get Student Info
    $q1 = "SELECT DISTINCT
    IFNULL(contacts.id, '') student_id,
    CONCAT(IFNULL(contacts.last_name, ''),
    ' ',
    IFNULL(contacts.first_name, '')) student_full_name,
    IFNULL(contacts.contact_id, '') contacts_contact_id,
    IFNULL(contacts.birthdate, '') student_birthdate,
    IFNULL(l1.id, '') l1_id,
    IFNULL(l1.mobile_phone, '') parent_mobile_phone,
    IFNULL(contacts.phone_mobile, '') student_phone_mobile,
    IFNULL(l2.id, '') team_id,
    IFNULL(l2.name, '') team_name,
    IFNULL(l2.team_type, '') team_type,
    IFNULL(l3.id, '') assigned_user_id,
    IFNULL(l6.id, '') student_corporate_id,
    IFNULL(l6.name, '') student_corporate_name,
    CONCAT(IFNULL(l3.last_name, ''),
    ' ',
    IFNULL(l3.first_name, '')) assigned_user_name
    FROM
    contacts
    LEFT JOIN
    c_contacts_contacts_1_c l1_1 ON contacts.id = l1_1.c_contacts_contacts_1contacts_idb
    AND l1_1.deleted = 0
    LEFT JOIN
    c_contacts l1 ON l1.id = l1_1.c_contacts_contacts_1c_contacts_ida
    AND l1.deleted = 0
    INNER JOIN
    teams l2 ON contacts.team_id = l2.id
    AND l2.deleted = 0
    INNER JOIN
    users l3 ON contacts.assigned_user_id = l3.id
    AND l3.deleted = 0

    LEFT JOIN
    accounts_contacts l6_1 ON contacts.id = l6_1.contact_id
    AND l6_1.deleted = 0
    LEFT JOIN
    accounts l6 ON l6.id = l6_1.account_id
    AND l6.deleted = 0

    WHERE
    contacts.id = '$student_id'
    AND contacts.deleted = 0";
    $result = $GLOBALS['db']->query($q1);
    $info = array();
    $row = $GLOBALS['db']->fetchByAssoc($result);
    $student_name = $row['student_full_name'];
    $info['id'] = $row['student_id'];
    $info['student_name'] = $row['student_full_name'];
    $phone = $row['parent_mobile_phone'];
    if (empty($phone))
        $phone = $row['student_phone_mobile'];
    $info['phone'] = $phone;

    if (!empty($row['student_birthdate']))
        $birthdate = $timedate->to_display_date($row['student_birthdate']);
    else $birthdate = "";
    $info['birthday'] = $birthdate;
    $info['assigned_user_id'] = $row['assigned_user_id'];
    $info['assigned_user_name'] = $row['assigned_user_name'];
    $info['team_id'] = $row['team_id'];
    $info['team_name'] = $row['team_name'];
    $info['team_type'] = $row['team_type'];
    $info['student_corporate_id'] = $row['student_corporate_id'];
    $info['student_corporate_name'] = $row['student_corporate_name'];

    //get Relationship info
    $sql = "SELECT DISTINCT
    IFNULL(contacts.id, '') primaryid,
    CONCAT(IFNULL(contacts.last_name, ''),
    ' ',
    IFNULL(contacts.first_name, '')) contacts_full_name
    FROM
    contacts
    INNER JOIN
    contacts_contacts_1_c l1_1 ON contacts.id = l1_1.contacts_contacts_1contacts_ida
    AND l1_1.deleted = 0
    INNER JOIN
    contacts l1 ON l1.id = l1_1.contacts_contacts_1contacts_idb
    AND l1.deleted = 0
    WHERE
    (((l1.id = '$student_id')))
    AND contacts.deleted = 0";
    $rs = $GLOBALS['db']->query($sql);

    $info['relative'] = array();
    while ($row_1 = $GLOBALS['db']->fetchByAssoc($rs)) {
        $info['relative'][$row_1['primaryid']] = $row_1['contacts_full_name'];
    }

    //Get Class List
    $sql_ex = '';
    $qTeam = "AND l4.id = '{$current_user->team_id}'";
    if ($GLOBALS['current_user']->isAdmin()) {
        $qTeam = "";
    }
    $q2 = "SELECT DISTINCT
    IFNULL(l2.id, '') class_id,
    l2.class_code class_code,
    IFNULL(l2.name, '') class_name,
    IFNULL(j_studentsituations.id, '') situation_id,
    IFNULL(j_studentsituations.total_hour, 0) total_hour,
    IFNULL(j_studentsituations.total_amount, 0) total_amount,
    j_studentsituations.type type,
    j_studentsituations.start_study start_study,
    j_studentsituations.end_study end_study,
    l2.start_date class_start,
    l2.end_date class_end,
    IFNULL(l4.id, '') team_id,
    IFNULL(l4.name, '') team_name
    FROM
    j_studentsituations
    INNER JOIN
    contacts l1 ON j_studentsituations.student_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    j_class l2 ON j_studentsituations.ju_class_id = l2.id
    AND l2.deleted = 0
    INNER JOIN
    teams l4 ON j_studentsituations.team_id = l4.id
    AND l4.deleted = 0
    WHERE
    (l1.id = '$student_id')
    $qTeam
    AND j_studentsituations.deleted = 0
    ORDER BY type ASC";
    $rs2 = $GLOBALS['db']->query($q2);
    $class_list = array();
    while ($row = $GLOBALS['db']->fetchByAssoc($rs2)) {
        if ($row['type'] == 'OutStanding') {
            $payment_date_db = date('Y-m-d', strtotime("- 1day " . $timedate->to_db_date($payment_date, false)));
            $payment_date_1 = $timedate->to_display_date($payment_date_db, false);
            $revenue = get_total_revenue($student_id, "'OutStanding'", '', $payment_date_1, $row['class_id'], $row['situation_id']);
            $class_list[$row['situation_id']]['total_revenue_util_now'] = format_number($revenue[0]['total_revenue_hour'], 2, 2);
        }
        $class_list[$row['situation_id']]['student_name'] = $student_name;
        $class_list[$row['situation_id']]['class_id'] = $row['class_id'];
        $class_list[$row['situation_id']]['class_name'] = $row['class_name'];
        $class_list[$row['situation_id']]['class_code'] = $row['class_code'];
        $class_list[$row['situation_id']]['type'] = $row['type'];
        $class_list[$row['situation_id']]['total_hour'] = format_number($row['total_hour'], 2, 2);
        $class_list[$row['situation_id']]['total_amount'] = format_number($row['total_amount']);
        $class_list[$row['situation_id']]['start_study'] = $row['start_study'];
        $class_list[$row['situation_id']]['end_study'] = $row['end_study'];
        $class_list[$row['situation_id']]['class_start'] = $row['class_start'];
        $class_list[$row['situation_id']]['class_end'] = $row['class_end'];
        $class_list[$row['situation_id']]['team_name'] = $row['team_name'];
    }
    //Get List Payment
    //    $qTeam = "AND l3.id = '{$current_user->team_id}'";
    $qTeam = "AND j_payment.team_set_id IN
    (SELECT
    tst.team_set_id
    FROM
    team_sets_teams tst
    INNER JOIN
    team_memberships team_memberships ON tst.team_id = team_memberships.team_id
    AND team_memberships.user_id = '{$current_user->id}'
    AND team_memberships.deleted = 0)";
    if ($GLOBALS['current_user']->isAdmin()) {
        $qTeam = "";
    }
    //Edit by Tung Bui - Open Logic
    $ext_where = "'Transfer In','Moving In', 'Transfer From AIMS',";
    //if ($payment_type == 'Enrollment' || empty($payment_type) || $current_user->isAdmin())
//        $ext_where = "'Transfer In','Moving In', 'Transfer From AIMS',";
//    else $ext_where = "";
    $q3 = "SELECT DISTINCT
    IFNULL(j_payment.id, '') payment_id,
    IFNULL(j_payment.name, '') payment_code,
    IFNULL(j_payment.payment_type, '') payment_type,
    j_payment.payment_date payment_date,
    j_payment.payment_expired payment_expired,
    j_payment.payment_amount payment_amount,
    j_payment.total_hours total_hours,
    j_payment.remain_amount remain_amount,
    j_payment.remain_hours remain_hours,
    j_payment.use_type use_type,
    j_payment.status status,
    j_payment.start_study start_study,
    j_payment.end_study end_study,
    j_payment.description description,
    IFNULL(l2.id, '') assigned_user_id,
    CONCAT(IFNULL(l2.last_name, ''),
    ' ',
    IFNULL(l2.first_name, '')) assigned_user_name,
    IFNULL(l3.id, '') team_id,
    IFNULL(l6.id, '') corporate_id,
    IFNULL(l6.name, '') corporate_name,
    IFNULL(l4.id, '') course_fee_id,
    IFNULL(l3.name, '') team_name,
    IFNULL(l4.name, '') course_fee
    FROM
    j_payment
    INNER JOIN
    contacts_j_payment_1_c l1_1 ON j_payment.id = l1_1.contacts_j_payment_1j_payment_idb
    AND l1_1.deleted = 0
    INNER JOIN
    contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida
    AND l1.deleted = 0
    LEFT JOIN
    users l2 ON j_payment.assigned_user_id = l2.id
    AND l2.deleted = 0
    INNER JOIN
    teams l3 ON j_payment.team_id = l3.id
    AND l3.deleted = 0
    LEFT JOIN
    j_coursefee_j_payment_1_c l4_1 ON j_payment.id = l4_1.j_coursefee_j_payment_1j_payment_idb
    AND l4_1.deleted = 0
    LEFT JOIN
    j_coursefee l4 ON l4.id = l4_1.j_coursefee_j_payment_1j_coursefee_ida
    AND l1.deleted = 0

    LEFT JOIN
    contracts l5 ON j_payment.contract_id = l5.id
    AND l5.deleted = 0
    LEFT JOIN
    accounts l6 ON l5.account_id = l6.id AND l6.deleted = 0

    WHERE
    (((l1.id = '$student_id')
    AND (j_payment.payment_type IN ('Enrollment' , 'Deposit',
    'Cashholder',
    'Delay',
    'Schedule Change',
    'Merge AIMS',
    $ext_where
    'Refund',
    'Placement Test',
    'Tutor Package',
    'Book/Gift'))
    $qTeam))
    AND j_payment.deleted = 0
    ORDER BY payment_date ASC";
    $result = $GLOBALS['db']->query($q3);
    $top_list = array();
    $left_list = array();
    $old_payments = array();

    if ($enrollment_id != '') {
        $enrollment = BeanFactory::getBean('J_Payment', $enrollment_id);
        $old_payments = json_decode(html_entity_decode($enrollment->payment_list), true);
    }

    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        //Check payment detail status
        $paidAmount    = 0;
        $unpaidAmount  = 0;
        $totalAmount   = 0;
        $sqlCountUnpaid = "SELECT IFNULL(id, '') id, status, payment_amount
        FROM j_paymentdetail
        WHERE deleted = 0
        AND payment_id = '{$row['payment_id']}'
        AND status IN ('Paid', 'Unpaid') AND payment_amount > 0";
        $res1 = $GLOBALS['db']->query($sqlCountUnpaid);
        while ($r1 = $GLOBALS['db']->fetchByAssoc($res1)){
            if($r1['status'] == 'Paid')
                $paidAmount += $r1['payment_amount'];
            if($r1['status'] == 'Unpaid')
                $unpaidAmount += $r1['payment_amount'];
            $totalAmount += $r1['payment_amount'];
        }
        if($payment_type == 'Transfer Out' || $payment_type == 'Moving Out' || $payment_type == 'Refund'){
            if($paidAmount < $totalAmount) continue;
        }elseif($paidAmount == 0 && $totalAmount > 0) continue;

        $ext_info = '';
        if ($current_user->team_id != $row['team_id'])
            $ext_info = "({$row['team_name']})";
        if (!empty($row['description']))
            $ext_info_2 = '<br> --' . $row['description'];

        //get payment in student info
        $left_list[$row['payment_id']] = $row['payment_type'] . ': ' . $row['payment_code'] . '-' . format_number($row['payment_amount']) . $ext_info . $ext_info_2;

        $is_old_payment = false;
        if ($row['payment_type'] == "Deposit" || $row['use_type'] == "Amount") {
            if (!empty($old_payments["deposit_list"][$row['payment_id']])) {
                $row['remain_amount'] = $row['remain_amount'] + $old_payments["deposit_list"][$row['payment_id']]['used_amount'];
                $row['remain_hours'] = $row['remain_hours'] + $old_payments["deposit_list"][$row['payment_id']]['used_hours'];
                $is_old_payment = true;
            }
        } else {
            if (!empty($old_payments["paid_list"][$row['payment_id']])) {
                $row['remain_amount'] = $row['remain_amount'] + $old_payments["paid_list"][$row['payment_id']]['used_amount'];
                $row['remain_hours'] = $row['remain_hours'] + $old_payments["paid_list"][$row['payment_id']]['used_hours'];
                $is_old_payment = true;
            }
        }         
        $selectedDiscount = '';

        // get payment in top list
        if ($enrollment_id != $row['payment_id'])   
            if ((($row['remain_amount'] > 0) || ($row['remain_hours'] > 0)) && ($row['status'] != 'Closed') && (!in_array($row['payment_type'], array('Enrollment', 'Refund', 'Placement Test', 'Book/Gift')))) {
                $top_list[$row['payment_id']]['payment_id'] = $row['payment_id'];
                $top_list[$row['payment_id']]['used_discount'] = $selectedDiscount;
                $top_list[$row['payment_id']]['payment_code'] = $row['payment_code'];
                $top_list[$row['payment_id']]['payment_type'] = $GLOBALS['app_list_strings']['payment_type_payment_list'][$row['payment_type']];
                $top_list[$row['payment_id']]['payment_date'] = $timedate->to_display_date($row['payment_date'], true);
                $top_list[$row['payment_id']]['payment_expired'] = $timedate->to_display_date($row['payment_expired'], true);

                $top_list[$row['payment_id']]['is_expired'] = false;
                if (!empty($payment_date) && !array_key_exists($row['payment_id'], $old_payments["deposit_list"]) && !array_key_exists($row['payment_id'], $old_payments["paid_list"])) {
                    if ($row['payment_expired'] < $timedate->to_db_date($payment_date, false))
                        $top_list[$row['payment_id']]['is_expired'] = true;
                }

                $top_list[$row['payment_id']]['invoice_number'] = $row['invoice_number'];
                $top_list[$row['payment_id']]['payment_amount'] = $row['payment_amount'];
                $top_list[$row['payment_id']]['total_hours'] = $row['total_hours'];
                $top_list[$row['payment_id']]['remain_amount'] = $row['remain_amount'];
                $top_list[$row['payment_id']]['remain_hours'] = $row['remain_hours'];
                $top_list[$row['payment_id']]['use_type'] = $row['use_type'];
                $top_list[$row['payment_id']]['course_fee'] = $row['course_fee'];
                $top_list[$row['payment_id']]['course_fee_id'] = $row['course_fee_id'];
                $top_list[$row['payment_id']]['assigned_user_id'] = $row['assigned_user_id'];
                $top_list[$row['payment_id']]['assigned_user_name'] = $row['assigned_user_name'];
                $top_list[$row['payment_id']]['team_id'] = $row['team_id'];
                $top_list[$row['payment_id']]['team_name'] = $row['team_name'];
                $top_list[$row['payment_id']]['checked'] = $is_old_payment ? " checked" : "";
            }   
    }

    //Get loyalty Point
    $loyalty    = getLoyaltyPoint($info['id'],$enrollment_id);
    $year       = '';
    if(!empty($payment_date))
        $year   = date('Y',strtotime($timedate->to_db_date($payment_date, false)));
    $loyalty_rate = getLoyaltyRateOut($loyalty['level'], $current_team_id, $year);
    $accrual_rate = $GLOBALS['app_list_strings']['default_loyalty_rate']['Accrual Rate ('.$loyalty['level'].')'];
    //END: Get loyalty Point

    $content = json_encode(array(
        'success'                => '1',
        'info'                   => $info,
        'loyalty_points'         => $loyalty['points'],
        'mem_code'               => $loyalty['code'],
        'mem_level'              => $loyalty['level'],
        'net_amount'             => $loyalty['net_amount'],
        'mem_level_html'         => $mem_level_html,
        'accrual_rate_value'     => $accrual_rate,
        'loyalty_rate_out_value' => $loyalty_rate['value'],
        'loyalty_rate_out_id'    => $loyalty_rate['id'],
        'class_list'             => $class_list,
        'left_list'              => $left_list,
        'top_list'               => $top_list,
    ));
    return json_encode(array(
        "success" => "1",
        "content" => $content,
    ));
}

function ajaxLoadStudent($text_search)
{
    global $current_user, $timedate;
    $sql_team = "AND contacts.team_set_id IN (SELECT
    tst.team_set_id
    FROM
    team_sets_teams tst
    INNER JOIN
    team_memberships team_memberships ON tst.team_id = team_memberships.team_id
    AND team_memberships.user_id = '{$current_user->id}'
    AND team_memberships.deleted = 0)";
    if ($current_user->isAdminForModule('Users')) $sql_team = '';

    $response = array();
    // Create SQL statement
    $query = "SELECT DISTINCT
    IFNULL(contacts.id, '') primaryid,
    CONCAT(IFNULL(contacts.last_name, ''),
    ' ',
    IFNULL(contacts.first_name, '')) student_full_name,
    IFNULL(contacts.contact_id, '') contacts_contact_id,
    IFNULL(contacts.birthdate, '') student_birthdate,
    IFNULL(l1.id, '') l1_id,
    IFNULL(l1.mobile_phone, '') parent_mobile_phone,
    IFNULL(contacts.phone_mobile, '') student_phone_mobile,
    IFNULL(l2.id, '') l2_id,
    IFNULL(l2.name, '') l2_name
    FROM
    contacts
    LEFT JOIN
    c_contacts_contacts_1_c l1_1 ON contacts.id = l1_1.c_contacts_contacts_1contacts_idb
    AND l1_1.deleted = 0
    LEFT JOIN
    c_contacts l1 ON l1.id = l1_1.c_contacts_contacts_1c_contacts_ida
    AND l1.deleted = 0
    LEFT JOIN
    teams l2 ON contacts.team_id = l2.id
    AND l2.deleted = 0
    WHERE
    (((CONCAT(IFNULL(contacts.last_name, ''),
    ' ',
    IFNULL(contacts.first_name, '')) LIKE '%$text_search%')
    OR (l1.mobile_phone LIKE '%$text_search%')))
    $sql_team
    AND contacts.deleted = 0";
    $result = $GLOBALS['db']->query($query);
    // Create result array
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $id = $row['primaryid'];
        $name = $row['student_full_name'];

        $phone = $row['parent_mobile_phone'];
        if (empty($phone))
            $phone = $row['student_phone_mobile'];

        if (!empty($row['student_birthdate']))
            $birthdate = $timedate->to_display_date($row['student_birthdate']);
        else $birthdate = "";

        array_push($response, array(
            "id" => $id,
            "name" => $name,
            "phone_mobile" => $phone,
            "birthdate" => $birthdate,
            "type" => "Student",
        ));
    }

    //Edit by Tung Bui 15/12/2015 - Query Leads
    // Create SQL statement
    $query1 = "SELECT DISTINCT
    IFNULL(leads.id, '') primaryid,
    CONCAT(IFNULL(leads.last_name, ''),' ',IFNULL(leads.first_name, '')) student_full_name,
    IFNULL(leads.birthdate, '') student_birthdate,
    IFNULL(leads.phone_mobile, '') student_phone_mobile
    FROM
    leads
    LEFT JOIN
    teams l2 ON leads.team_id = l2.id
    AND l2.deleted = 0
    WHERE
    CONCAT(IFNULL(leads.last_name, ''),' ',IFNULL(leads.first_name, '')) LIKE '%$text_search%'
    $sql_team
    AND leads.deleted = 0
    AND leads.status <> 'Converted'";
    $result1 = $GLOBALS['db']->query($query1);
    // Create result array
    while ($row1 = $GLOBALS['db']->fetchByAssoc($result1)) {
        $id = $row1['primaryid'];
        $name = $row1['student_full_name'];

        $phone = $row1['parent_mobile_phone'];
        if (empty($phone))
            $phone = $row1['student_phone_mobile'];

        if (!empty($row1['student_birthdate']))
            $birthdate = $timedate->to_display_date($row1['student_birthdate']);
        else $birthdate = "";

        array_push($response, array(
            "id" => $id,
            "name" => $name,
            "phone_mobile" => $phone,
            "birthdate" => $birthdate,
            "type" => "Lead",
        ));
    }

    return json_encode($response);
}

function ajaxGetInvoice($payment_detail_id)
{
    global $timedate, $current_user;
    $paymentDetail = BeanFactory::getBean('J_PaymentDetail', $payment_detail_id);
    if (!empty($paymentDetail->invoice_number)) return;
    $team_id = $paymentDetail->team_id;
    $sql2 = "SELECT IFNULL(id, '') id,
    IFNULL(description, '') description,
    IFNULL(invoice_no_from, '') invoice_no_from,
    IFNULL(invoice_no_to, '') invoice_no_to,
    IFNULL(serial_no, '') serial_no,
    IFNULL(serial_no_2, '') serial_no_2,
    IFNULL(invoice_no_from_2, '') invoice_no_from_2,
    IFNULL(invoice_no_to_2, '') invoice_no_to_2,
    IFNULL(invoice_no_current, '') invoice_no_current
    FROM j_configinvoiceno WHERE team_id= '{$team_id}' LIMIT 1";
    $rs2 = $GLOBALS['db']->query($sql2);
    $row2 = $GLOBALS['db']->fetchByAssoc($rs2);
    $configId = $row2['id'];
    $configDescription = $row2['description'];
    if (!empty($configId)) {
        $config = BeanFactory::getBean('J_ConfigInvoiceNo', $configId);
        $objNextInvoice = autoGetNextInvoice($team_id);
        $objNextInvoice = json_decode($objNextInvoice, true);

        $newInvoiceStatus = $objNextInvoice['status'];
        $newInvoiceNo = $objNextInvoice['nextInvoiceNo'];
        $newSerial = $objNextInvoice['serial'];

        //Check Invoice config is ACTIVE
        if ($config->active == 1 && $newInvoiceStatus != '--out of range--') {
            //Check new Invoice No
            $q4 = "SELECT j_configinvoiceno.id primary_id,
            l3.full_student_name l3_full_student_name,
            IFNULL(l1.invoice_number, '') invoice_number,
            IFNULL(l2.id, '') payment_id,
            IFNULL(l2.name, '') payment_name,
            CONCAT(IFNULL(l9.first_name, ''),
            ' ',
            IFNULL(l9.last_name, '')) l9_full_name
            FROM j_configinvoiceno
            INNER JOIN
            j_paymentdetail l1 ON l1.id = j_configinvoiceno.pmd_id_printing
            AND l1.deleted = 0
            INNER JOIN
            j_payment l2 ON l1.payment_id = l2.id
            AND l2.deleted = 0
            INNER JOIN
            contacts_j_payment_1_c l3_1 ON l2.id = l3_1.contacts_j_payment_1j_payment_idb
            AND l3_1.deleted = 0
            INNER JOIN
            contacts l3 ON l3.id = l3_1.contacts_j_payment_1contacts_ida
            AND l3.deleted = 0
            INNER JOIN
            users l9 ON j_configinvoiceno.modified_user_id = l9.id
            AND l9.deleted = 0
            WHERE
            j_configinvoiceno.team_id= '{$team_id}'
            AND j_configinvoiceno.deleted = 0
            AND j_configinvoiceno.finish_printing = 0";
            $rs4 = $GLOBALS['db']->query($q4);
            $row4 = $GLOBALS['db']->fetchByAssoc($rs4);
            if (!empty($row4['primary_id']))
                return json_encode(array(
                    "success" => "0",
                    "errorLabel" => "Can not get invoice no, because the
                    <br>Invoice no.: &nbsp;<b>{$row4['invoice_number']}</b>
                    <br>Student: &nbsp;<b>{$row4['l3_full_student_name']}</b>
                    <br>Created by: &nbsp;<b>{$row4['l9_full_name']}</b><br>
                    not finished printing task . <br><br>Please, click <i>Finish Printing</i> in <a target='_blank' href=\"index.php?module=J_Payment&action=DetailView&record={$row4['payment_id']}\">{$row4['payment_name']}</a> and get invoice no again.",
                ));

            else {
                //Check range of invoice no
                $paymentDetail->invoice_number = $newInvoiceNo;
                $paymentDetail->numeric_vat_no = $paymentDetail->invoice_number;
                $paymentDetail->serial_no = $newSerial;
                $paymentDetail->printed_date = $timedate->nowDbDate();
                $config->invoice_no_current = $newInvoiceNo;
                $config->save();
                $paymentDetail->save();

                //Update change Range Invoice
                $update_history = '';
                if ($newInvoiceStatus == '1->2') {
                    $_history = $configDescription . $timedate->now() . ': Serial: ' . $row2['serial_no'] . ' From: ' . $row2['invoice_no_from'] . ' To: ' . $row2['invoice_no_to'] . "\n";
                    $update_history = ", description = '$_history', invoice_no_from = '', serial_no = '', invoice_no_to = '' ";
                }

                if ($newInvoiceStatus == '2->1') {
                    $_history = $configDescription . $timedate->now() . ': Serial: ' . $row2['serial_no_2'] . ' From: ' . $row2['invoice_no_from_2'] . ' To: ' . $row2['invoice_no_to_2'] . "\n";
                    $update_history = ", description = '$_history', invoice_no_from_2 = '', serial_no_2 = '', invoice_no_to_2 = '' ";
                }

                //Update finish finish printing
                $GLOBALS['db']->query("UPDATE j_configinvoiceno SET finish_printing = 0, pmd_id_printing='{$paymentDetail->id}', modified_user_id='{$current_user->id}' $update_history WHERE deleted = 0 AND team_id= '{$team_id}'");

                //Update Is Stopped
                if(!empty($payment->contacts_j_payment_1contacts_ida))
                    $GLOBALS['db']->query("UPDATE contacts SET is_stopped=0 WHERE id='{$payment->contacts_j_payment_1contacts_ida}' AND is_stopped=1 AND deleted=0");

                return json_encode(array(
                    "success" => "1",
                    "errorLabel" => "",
                    "sale_type" => $sale_type,
                    "sale_type_date" => $timedate->to_display_date($paymentDetail->payment_date, false),
                ));
            }
        } else {
            return json_encode(array(
                "success" => "0",
                "errorLabel" => "The invoice number is out of range, Please, contact administrator!",
            ));
        }
    } else {
        return json_encode(array(
            "success" => "0",
            "errorLabel" => "The invoice number is out of range, Please, contact administrator!",
        ));
    }
}

function autoGetNextInvoice($team_id)
{
    $sql = "SELECT id,
    IFNULL(invoice_no_from, 0) invoice_no_from,
    IFNULL(invoice_no_to, 0) invoice_no_to,
    IFNULL(serial_no, '') serial_no,
    IFNULL(serial_no_2, '') serial_no_2,
    IFNULL(invoice_no_current, 0) invoice_no_current,
    IFNULL(invoice_no_from_2, 0) invoice_no_from_2,
    IFNULL(invoice_no_to_2, 0) invoice_no_to_2
    FROM j_configinvoiceno
    WHERE team_id='{$team_id}'
    LIMIT 1";

    $rs = $GLOBALS['db']->query($sql);
    $row = $GLOBALS['db']->fetchByAssoc($rs);

    $status = 'Normal';//Flag chuyển Range
    $serial = '';
    $nextInvoiceNo = '';

    //Nếu current nằm giữa range nào thì chạy range đó
    if (($row['invoice_no_current'] < $row['invoice_no_to']) && ($row['invoice_no_current'] >= ($row['invoice_no_from'] - 1))) {

        $nextInvoiceNo = intval($row['invoice_no_current']) + 1;
        $serial = $row['serial_no'];
        if ($row['invoice_no_current'] == $row['invoice_no_to_2'])//TH chuyen range lien tiep
            $status = '2->1';

    } elseif (($row['invoice_no_current'] < $row['invoice_no_to_2']) && ($row['invoice_no_current'] >= ($row['invoice_no_from_2'] - 1))) {

        $nextInvoiceNo = intval($row['invoice_no_current']) + 1;
        $serial = $row['serial_no_2'];
        if ($row['invoice_no_current'] == $row['invoice_no_to'])//TH chuyen range lien tiep
            $status = '1->2';

    } else {
        if ($row['invoice_no_current'] == $row['invoice_no_to'] && (!empty($row['invoice_no_from_2']) && !empty($row['invoice_no_to_2']))) {
            //Nếu range 1 hết chuyển range 2
            $nextInvoiceNo = intval($row['invoice_no_from_2']);
            $serial = $row['serial_no_2'];
            $status = '1->2';
        } elseif ($row['invoice_no_current'] == $row['invoice_no_to_2'] && (!empty($row['invoice_no_from']) && !empty($row['invoice_no_to']))) {
            //Nếu range 2 hết chuyển range 1
            $nextInvoiceNo = intval($row['invoice_no_from']);
            $serial = $row['serial_no'];
            $status = '2->1';
        } else {
            $nextInvoiceNo = '--out of range--';
            $status = '--out of range--';
        }
    }

    if (is_numeric($nextInvoiceNo))
        $nextInvoiceNo = str_pad($nextInvoiceNo, 5, '0', STR_PAD_LEFT);

    return json_encode(array(
        "success" => "1",
        "nextInvoiceNo" => $nextInvoiceNo,
        "serial" => $serial,
        "status" => $status,
    ));
}
function autoCheckInvoiceReleased($pmd_id){
    //Current range
    $res = $GLOBALS['db']->query("SELECT DISTINCT
        IFNULL(j_paymentdetail.id,'') id,
        IFNULL(j_paymentdetail.invoice_number,'') invoice_number,
        IFNULL(j_paymentdetail.is_release,0) is_release,
        IFNULL(l2.release_list,'') release_list
        FROM j_paymentdetail
        INNER JOIN
        j_configinvoiceno l2 ON j_paymentdetail.team_id = l2.team_id
        AND l2.deleted = 0
        WHERE j_paymentdetail.id='$pmd_id'");
    $r              = $GLOBALS['db']->fetchByAssoc($res);
    $releaseList    = json_decode(html_entity_decode($r['release_list']),true);

    return json_encode(array(
        "id"            => $r['id'],
        "invoice_number"=> $r['invoice_number'],
        "is_release"    => $r['is_release'],
        "release_list"  => $releaseList,


    ));
}

function ajaxUpdatePaymentDetail($payment_detail_id, $payment_method, $card_type, $bank_type, $payment_date, $method_note, $handle_action)
{
    global $timedate;

    $pmd = BeanFactory::getBean('J_PaymentDetail',$payment_detail_id);
    $payment_date = $timedate->to_db_date($payment_date, false);
    $_fee = 0;
    $m_note = '';
    if ($payment_method == 'Card')
        $_fee = floatval($GLOBALS['app_list_strings']['card_rate'][$card_type]) * $pmd->payment_amount / 100;
    elseif ($payment_method == 'Bank Transfer') {
        $_fee = floatval($GLOBALS['app_list_strings']['bank_rate'][$bank_type]) * $pmd->payment_amount / 100;
        $card_type = $bank_type;
    } elseif ($payment_method == 'Other')
        $m_note = $method_note;
    else
        $card_type = '';

    $pmd->payment_method    = $payment_method;
    $pmd->payment_method_fee= format_number($_fee);
    $pmd->status            = 'Paid';
    $pmd->payment_date      = $payment_date;
    $pmd->card_type         = $card_type;
    $pmd->method_note       = $m_note;
    $pmd->created_by        = $GLOBALS['current_user']->id;
    $pmd->save();

    // update remain_amount in table contacts
    update_remain_last_date($pmd->student_id);

    //Update Sale Type
    $q1 = "SELECT IFNULL(SUM(j_paymentdetail.payment_amount), 0) payment_amount_paid,
    l1.payment_amount payment_amount
    FROM j_paymentdetail
    INNER JOIN
    j_payment l1 ON j_paymentdetail.payment_id = l1.id
    AND l1.deleted = 0
    WHERE j_paymentdetail.payment_id = '{$pmd->payment_id}'
    AND j_paymentdetail.status = 'Paid'
    AND j_paymentdetail.deleted = 0";
    $rs1 = $GLOBALS['db']->query($q1);
    $row = $GLOBALS['db']->fetchByAssoc($rs1);
    $sale_type = 'Not set';
    if ($row['payment_amount_paid'] > 0)
        $sale_type = checkSaleType($pmd->payment_id, $pmd->payment_date);
    $ext_new_student = '';

    $payment = BeanFactory::getBean('J_Payment', $pmd->payment_id);
    if (($sale_type == 'New Sale' || $sale_type == 'Not set')) {
        $is_new_student = $payment->is_new_student;
        if ($is_new_student == 0) {
            $is_new_student = check_new_student($payment->contacts_j_payment_1contacts_ida, $pmd->payment_date, $timedate->to_db($payment->date_entered));
        }
        $ext_new_student = ", is_new_student = {$is_new_student}";
    }

    $GLOBALS['db']->query("UPDATE j_payment SET sale_type = '$sale_type', sale_type_date='{$pmd->payment_date}' $ext_new_student WHERE id = '{$pmd->payment_id}'");

    //Tổng số tiền đã trả
    $q2 = "SELECT DISTINCT
    IFNULL(payment_no, '') payment_no,
    IFNULL(status, '') status,
    IFNULL(payment_amount, '0') payment_amount
    FROM j_paymentdetail
    WHERE payment_id = '{$pmd->payment_id}'
    AND deleted <> 1
    AND status <> 'Cancelled'
    ORDER BY payment_no";
    $res2 = $GLOBALS['db']->query($q2);
    $paidAmount = 0;
    $unpaidAmount = 0;
    while ($rowPayDtl = $GLOBALS['db']->fetchByAssoc($res2)) {
        if ($rowPayDtl['status'] == "Unpaid")
            $unpaidAmount += $rowPayDtl['payment_amount'];
        else
            $paidAmount += $rowPayDtl['payment_amount'];

    }
    // Add by Nguyen Tung 26-6-2078
    if($unpaidAmount == 0)
        $GLOBALS['db']->query("UPDATE j_payment SET status_paid = 'Fully Paid' WHERE id ='$pmd->payment_id'");
    elseif ($paidAmount > 0) $GLOBALS['db']->query("UPDATE j_payment SET status_paid = 'Partly Paid' WHERE id ='$pmd->payment_id'");
    else $GLOBALS['db']->query("UPDATE j_payment SET status_paid = 'Unpaid' WHERE id ='$pmd->payment_id'");
    // End by Nguyen Tung

    return json_encode(array(
        "success" => "1",
        "paid" => format_number($paidAmount),
        "unpaid" => format_number($unpaidAmount),
        "sale_type" => $sale_type,
        "sale_type_date" => $timedate->to_display_date($pmd->payment_date,false),
        "paidNum" => $paidAmount,
        "unpaidNum" => $unpaidAmount,
    ));

}

function ajaxUndoPayment($paymentId, $paymentType)
{
    require_once('custom/include/_helper/junior_class_utils.php');
    //Get bean of payemtn out & payment in
    if ($paymentType == "Refund") {
        $paymentOutId = $paymentId;
        $paymentOutBean = BeanFactory::getBean("J_Payment", $paymentId);
    } else {
        if (in_array($paymentType, array("Transfer In", "Moving In"))) {
            $paymentInId = $paymentId;
            $paymentInBean = BeanFactory::getBean("J_Payment", $paymentId);
            $paymentOutId = $paymentInBean->payment_out_id;
            $paymentOutBean = BeanFactory::getBean("J_Payment", $paymentOutId);
        } else { //Transfer out, moving out
            $paymentOutId = $paymentId;
            $paymentOutBean = BeanFactory::getBean("J_Payment", $paymentId);
            $paymentOutBean->load_relationship("ju_payment_in");
            $paymentInBean = reset($paymentOutBean->ju_payment_in->getBeans());
            $paymentInId = $paymentInBean->id;
        }
    }

    $fromStudentId = $paymentOutBean->contacts_j_payment_1contacts_ida;

    //TODO - check remain amount of payment in, if remain != payment amount -> die();
    if ($paymentInBean->remain_amount != $paymentInBean->payment_amount) {
        return json_encode(array(
            "success" => "0",
        ));
    }

    //TODO - restore ralated payment
    $sqlRelatedPay = "SELECT
    DISTINCT j_payment_j_payment_1j_payment_idb id,
    amount,
    hours
    FROM j_payment_j_payment_1_c
    WHERE j_payment_j_payment_1j_payment_ida = '{$paymentOutId}'
    AND deleted = 0";
    $resultRealtedPay = $GLOBALS['db']->query($sqlRelatedPay);
    while ($rowRelatedPay = $GLOBALS['db']->fetchByAssoc($resultRealtedPay)) {
        $pay_id = $rowRelatedPay["id"];
        $hours = $rowRelatedPay["hours"];
        $amount = $rowRelatedPay["amount"];

        $payment_drop_id = $pay_id;
        // Cộng giờ và tiền revenue
        if ($paymentType == "Refund") {
            $sqlDelRevenue = "SELECT amount, duration FROM c_deliveryrevenue WHERE ju_payment_id = '$payment_drop_id' AND deleted <> 1";
            $rs_re = $GLOBALS['db']->query($sqlDelRevenue);
            while ($row_re = $GLOBALS['db']->fetchByAssoc($rs_re)) {
                $hours += $row_re['duration'];
                $amount += $row_re['amount'];
            }
        }
        $hours = unformat_number(format_number($hours, 2, 2));
        $amount = unformat_number(format_number($amount));
        $sqlUpdatePay = "UPDATE j_payment
        SET
        used_amount     = used_amount - $amount,
        used_hours      = used_hours - $hours,
        remain_amount   = remain_amount + $amount,
        remain_hours    = remain_hours + $hours
        WHERE id = '$pay_id'
        AND deleted <> 1";

        $GLOBALS['db']->query($sqlUpdatePay);

        //UNDO REFUND: delete revenue record
        if ($paymentType == "Refund") {
            $sqlDelRevenue = "DELETE FROM c_deliveryrevenue
            WHERE ju_payment_id = '{$payment_drop_id}'
            AND deleted = 0 AND passed = 0";
            $GLOBALS['db']->query($sqlDelRevenue);
        }
    }


    //Delete relationship payment - related payment record
    removeRelatedPayment($paymentOutId);

    //Delete payment out & payment in  , Remove Relationship Contact - Payment
    $sqlDelPayment = "UPDATE j_payment
    SET deleted = 1
    WHERE id = '{$paymentOutId}'";
    $GLOBALS['db']->query($sqlDelPayment);

    $GLOBALS['db']->query("UPDATE contacts_j_payment_1_c
        SET deleted = 1
        WHERE contacts_j_payment_1j_payment_idb = '{$paymentOutId}'");

    if (!empty($paymentInId)) {
        $sqlDelPayment_2 = "UPDATE j_payment
        SET deleted = 1
        WHERE id = '{$paymentInId}'";
        $GLOBALS['db']->query($sqlDelPayment_2);

        $GLOBALS['db']->query("UPDATE contacts_j_payment_1_c
            SET deleted = 1
            WHERE contacts_j_payment_1j_payment_idb = '{$paymentInId}'");
    }


    //UNDO MOVING: Set primary team for student
    if (in_array($paymentType, array("Moving Out", "Moving In"))) {
        $studentBean = BeanFactory::getBean("Contacts", $fromStudentId);
        $studentBean->team_id = $paymentOutBean->team_id;
        $studentBean->save();
    }

    if(!empty($paymentOutBean))
        update_remain_last_date($paymentOutBean->contacts_j_payment_1contacts_ida);
    if(!empty($paymentInBean))
        update_remain_last_date($paymentInBean->contacts_j_payment_1contacts_ida);

    return json_encode(array(
        "success" => "1",
    ));
}

function ajaxRealseInvoiceNo($pay_dtl_id)
{
    $payDtlBean = BeanFactory::getBean('J_PaymentDetail', $pay_dtl_id);
    $invoiceNo = $payDtlBean->invoice_number;
    //Add invoice no to realse list
    $sqlConfInvoice = "SELECT id FROM j_configinvoiceno WHERE deleted <> 1 AND team_id = '{$payDtlBean->team_id}'";
    $confInvoiceId = $GLOBALS['db']->getOne($sqlConfInvoice);
    $confInvoiceBean = BeanFactory::getBean('J_ConfigInvoiceNo', $confInvoiceId);
    $releaseList = json_decode(html_entity_decode($confInvoiceBean->release_list), true);
    if(count($releaseList) < 2 || array_key_exists($pay_dtl_id,$releaseList)){
        if (!empty($releaseList)) {
            if (!in_array($invoiceNo, $releaseList)) $releaseList[$pay_dtl_id] = $invoiceNo;
        } else {
            $releaseList = array();
            $releaseList[$pay_dtl_id] = $invoiceNo;
        }

        $confInvoiceBean->release_list = json_encode($releaseList);
        $confInvoiceBean->save();
        //Change status to release
        $payDtlBean->is_release = 1;
        $payDtlBean->save();
        return json_encode(array(
            "success" => "1",
            'notify'  => 'No. '.$payDtlBean->invoice_number.' released!'
        ));
    }else{
        $releasePayID   = $GLOBALS['db']->fetchArray("SELECT id, payment_id FROM j_paymentdetail WHERE id IN ("."'".implode("','",array_keys($releaseList))."'".") ORDER BY FIELD(id, "."'".implode("','",array_keys($releaseList))."'".")");
        $releasePayID   = array_column($releasePayID,'payment_id');

        $releaseList    = array_values($releaseList);
        $notification   = "<b style='color:red;'>Release Invoice Number Failed!</b>.<br> Please, Complete the swap 2 invoice numbers: <b><a target='_blank' href=\"index.php?module=J_Payment&action=DetailView&record={$releasePayID[0]}\">{$releaseList[0]}</a> &#x21c4; <a target='_blank' href=\"index.php?module=J_Payment&action=DetailView&record={$releasePayID[1]}\">{$releaseList[1]}</a></b><br>before start a new swap.";
        return json_encode(array(
            "success" => "0",
            "notify" => $notification,
        ));
    }
}

function ajaxSaveInvoiceNo($pay_dtl_id, $new_invoice_no)
{
    //Check this invoice no
    $qu1 = $GLOBALS['db']->query("SELECT DISTINCT
        IFNULL(id, '') primaryid,
        IFNULL(team_id, '') team_id,
        IFNULL(invoice_number, '') invoice_number
        FROM
        j_paymentdetail
        WHERE
        id = '$pay_dtl_id'
        AND deleted = 0
        AND is_release=1");
    $payDetail = $GLOBALS['db']->fetchByAssoc($qu1);
    if(empty($payDetail)){
        return json_encode(array(
            "success" => "0",
            "errorLabel" => "The swap completed !!",
        ));
    }
    $qu2 = $GLOBALS['db']->query("SELECT
        IFNULL(id, '') primaryid,
        IFNULL(release_list, '') release_list
        FROM
        j_configinvoiceno
        WHERE
        team_id = '{$payDetail['team_id']}'
        AND deleted = 0");
    $confInvoice = $GLOBALS['db']->fetchByAssoc($qu2);

    $releaseList = json_decode(html_entity_decode($confInvoice['release_list']), true);
    if (!array_key_exists($pay_dtl_id, $releaseList) || !in_array($new_invoice_no, $releaseList)){
        return json_encode(array(
            "success" => "0",
            "errorLabel" => "Can not save this invoice because it was used!",
        ));
    }else{
        //Save new invoice no
        $GLOBALS['db']->query("UPDATE j_paymentdetail SET invoice_number='$new_invoice_no', numeric_vat_no=" . intval($new_invoice_no) . ", is_release=0 WHERE id='$pay_dtl_id'");
        $beginreleaseList = $releaseList;
        $beginreleaseList = array_values($beginreleaseList);
        //Remove this invoice no in release list
        unset($releaseList[$pay_dtl_id]);
        $errorLabel = "No. $new_invoice_no saved !";
        if($payDetail['invoice_number'] != $new_invoice_no){
            $GLOBALS['db']->query("UPDATE j_paymentdetail SET invoice_number='{$payDetail['invoice_number']}', numeric_vat_no=" . intval($payDetail['invoice_number']) . ", is_release=0 WHERE id='".key($releaseList)."'");
            unset($releaseList[key($releaseList)]);
            $errorLabel = 'Successful Swapping:'."{$beginreleaseList[0]} &#x21c4; {$beginreleaseList[1]}";
        }
    }
    //Save new Config
    $wq1 = "UPDATE j_configinvoiceno SET release_list='" . json_encode($releaseList) . "' WHERE id='{$confInvoice['primaryid']}'";
    $GLOBALS['db']->query($wq1);
    return json_encode(array(
        "success" => "1",
        "errorLabel" => $errorLabel,
    ));
}

function finish_printing($printing_id)
{
    if (!empty($printing_id)) {
        //Update finish finish printing
        $GLOBALS['db']->query("UPDATE j_configinvoiceno SET finish_printing = 1, pmd_id_printing='' WHERE deleted = 0 AND id= '$printing_id'");

        return json_encode(array(
            "success" => "1",
        ));
    } else
        return json_encode(array(
            "success" => "0",
        ));

}

function caculateDropPayment($payment_id, $dl_date)
{
    $payment = BeanFactory::getBean('J_Payment', $payment_id);
    // Get Total amount
    $row1 = get_list_payment_detail($payment_id);
    $total_amount = 0;
    for ($i = 0; $i < count($row1); $i++)
        $total_amount += $row1[$i]['payment_amount'];
    if ($payment->payment_type == 'Enrollment')
        $total_amount += $payment->paid_amount + $payment->deposit_amount;

    if ($payment->payment_type == 'Cashholder')
        $total_amount += $payment->paid_amount + $payment->deposit_amount;

    if ($payment->payment_type == 'Delay' || $payment->payment_type == 'Merge AIMS' || $payment->payment_type == 'Transfer From AIMS' || $payment->payment_type == 'Transfer In' || $payment->payment_type == 'Moving In' || $payment->payment_type == 'Deposit' || $payment->payment_type == 'Schedule Change')
        $total_amount = $payment->payment_amount;
    $price = (($payment->payment_amount + $payment->paid_amount + $payment->deposit_amount) / ($payment->total_hours + $payment->paid_hours));

    // Get Used amount
    $row2 = get_total_revenue($payment->contacts_j_payment_1contacts_ida, "'Enrolled', 'Moving In', 'Settle'", '', $dl_date, '', '', $payment_id, 'Planning');
    $used_amount = 0;
    for ($i = 0; $i < count($row2); $i++)
        $used_amount += $row2[$i]['total_revenue'];
    $sql_get_delay = "SELECT
    SUM(pp.amount) amount
    FROM
    j_payment_j_payment_1_c pp
    INNER JOIN
    j_payment p ON p.id = pp.j_payment_j_payment_1j_payment_ida
    AND p.deleted = 0
    AND pp.deleted = 0
    #AND p.payment_type = 'Delay'
    AND pp.j_payment_j_payment_1j_payment_idb = '{$payment->id}'
    GROUP BY pp.j_payment_j_payment_1j_payment_idb";
    $delay_amount = $GLOBALS['db']->getOne($sql_get_delay);
    /*if($payment->payment_type != 'Enrollment')
    $used_amount += $payment->payment_amount - $payment->remain_amount;
    else*/
    $used_amount += $delay_amount;
    return json_encode(array(
        "success" => "1",
        "total_amount" => format_number($total_amount),
        "used_amount" => format_number($used_amount),
        "drop_amount" => format_number($total_amount - $used_amount),
        "drop_hour" => format_number(($total_amount - $used_amount) / $price, 2, 2),
        "drop_amount_raw" => ($total_amount - $used_amount),
        "drop_hour_raw" => unformat_number(format_number(($total_amount - $used_amount) / $price, 2, 2)),
    ));
}

function createDropPayment()
{
    global $current_user, $timedate;
    require_once('custom/include/_helper/junior_class_utils.php');

    $payment = BeanFactory::getBean('J_Payment', $_POST['payment_id']);
    $unit_price = ($payment->payment_amount + $payment->deposit_amount + $payment->paid_amount) / ($payment->total_hours + $payment->paid_hours);
    if ($_POST['drop_amount'] < 0)
        return json_encode(array(
            "success" => "0",
        ));

    if ($_POST['drop_type'] == 'drop_to_delay') {
        //Payment Delay
        $pm_delay = new J_Payment();
        $pm_delay->contacts_j_payment_1contacts_ida = $payment->contacts_j_payment_1contacts_ida;
        $pm_delay->payment_type = 'Delay';
        $pm_delay->use_type = "Amount";

        $pm_delay->payment_date = $_POST['dl_date'];
        $pm_delay->payment_expired = date('Y-m-d', strtotime("+6 months " . $timedate->to_db_date($_POST['dl_date'], false)));
        $pm_delay->payment_amount = format_number($_POST['drop_amount']);
        $pm_delay->remain_amount = format_number($_POST['drop_amount']);
        $pm_delay->tuition_hours = format_number($_POST['drop_hour'], 2, 2);
        $pm_delay->total_hours = format_number($_POST['drop_hour'], 2, 2);
        $pm_delay->remain_hours = format_number($_POST['drop_hour'], 2, 2);
        $pm_delay->used_hours = 0;
        $pm_delay->used_amount = 0;
        $pm_delay->description = $_POST['dl_reason'];
        $pm_delay->assigned_user_id = $current_user->id;
        $pm_delay->team_id = $payment->team_id;
        $pm_delay->team_set_id = $payment->team_id;
        $pm_delay->save();

        addRelatedPayment($pm_delay->id, $payment->id, $_POST['drop_amount'], $_POST['drop_hour']);
        //Remove student from Session

    } elseif ($_POST['drop_type'] == 'drop_to_revenue') {
        $delivery = new C_DeliveryRevenue();
        $delivery->name = 'Drop revenue from payment ' . $payment->name;
        $delivery->student_id = $payment->contacts_j_payment_1contacts_ida;
        //Get Payment ID
        $delivery->ju_payment_id = $payment->id;
        $delivery->type = 'Junior';
        $delivery->amount = format_number($_POST['drop_amount']);
        $delivery->duration = format_number($_POST['drop_amount'] / $unit_price, 2, 2);
        $delivery->date_input = $timedate->to_db_date($_POST['dl_date'], false);
        $delivery->cost_per_hour = 0;
        $delivery->session_id = '1';
        $delivery->passed = 0;
        $delivery->description = $_POST['dl_reason'];
        $delivery->team_id = $payment->team_id;
        $delivery->team_set_id = $payment->team_id;
        $delivery->cost_per_hour = $unit_price;
        $delivery->assigned_user_id = $current_user->id;
        $delivery->revenue_type = 'Enrolled';
        $delivery->save();
    }
    //    if ($payment->payment_type == 'Enrollment') {
    //        $remove_from_date_db = $timedate->to_display_date(date('Y-m-d', strtotime("+1 day " . $timedate->to_db_date($_POST['dl_date'], false))), false);
    //        $row2 = get_list_revenue('', "'Enrolled', 'Moving In'", $remove_from_date_db, '', '', '', '', $_POST['payment_id'], false, 'Planning');
    //        $arr_situation = array();
    //        $remove_date_db = $timedate->to_db_date($remove_from_date_db, false);
    //        if (!empty($row2)) {
    //            for ($i = 0; $i < count($row2); $i++) {
    //                removeJunFromSession($row2[$i]['situation_id'], $row2[$i]['primaryid']);
    //                if (!in_array($row2[$i]['situation_id'], $arr_situation))
    //                    $arr_situation[] = $row2[$i]['situation_id'];
    //            }
    //            foreach ($arr_situation as $key => $value) {
    //                $situ = BeanFactory::getBean('J_StudentSituations', $value);
    //                if ($remove_date_db < $situ->end_study && $situ->start_study > $remove_date_db)
    //                    $situ->end_study = $remove_from_date_db;
    //                else $situ->deleted = 1;
    //                $situ->save();
    //            }
    //        }
    //    }
    $GLOBALS['db']->query("UPDATE j_payment SET status='Closed', remain_hours = 0, remain_amount = 0, do_not_drop_revenue=0 WHERE id = '{$payment->id}'");
    update_remain_last_date($payment->contacts_j_payment_1contacts_ida);
    return json_encode(array(
        "success" => "1",
    ));
}

function ajaxConvertPayment()
{
    global $timedate, $current_user;
    $tuition_hours      = unformat_number($_POST['tuition_hours']);
    $remain_hours       = unformat_number($_POST['remain_hours']);
    $convert_to_type    = $_POST['convert_to_type'];
    $payment_id         = $_POST['payment_id'];
    $payment = BeanFactory::getBean('J_Payment', $payment_id);
    if (!empty($payment_id) || empty($payment)) {
        $new_payment_type = $payment->payment_type;
        if($convert_to_type == 'To Hour'){
            if($payment->payment_type == 'Deposit')
                $new_payment_type = 'Cashholder';

            $GLOBALS['db']->query("UPDATE j_payment SET tuition_hours=$tuition_hours, total_hours=$tuition_hours, remain_hours=$remain_hours, use_type='Hour', payment_type='$new_payment_type', note='{$payment->payment_type} has been Converted to $new_payment_type - " . $timedate->now() . " by {$current_user->user_name}' WHERE id='$payment_id'");
        }else{
            if($payment->payment_type == 'Cashholder')
                $new_payment_type = 'Deposit';
            if( (($new_payment_type == 'Deposit') && ($payment->payment_amount != $payment->remain_amount)) || $payment->use_type == 'Amount' )
                return json_encode(array(
                    "success" => "0",
                ));
            $GLOBALS['db']->query("UPDATE j_payment SET tuition_hours=$tuition_hours, total_hours=$tuition_hours, remain_hours=$remain_hours, use_type='Amount', payment_type='$new_payment_type', note='{$payment->payment_type} has been Converted to $new_payment_type - (Old Total Hour - Remain Hour : {$payment->tuition_hours} - {$payment->remain_hours}) " . $timedate->now() . " by {$current_user->user_name}' WHERE id='$payment_id'");
        }

        return json_encode(array(
            "success" => "1",
        ));

    }else
        return json_encode(array(
            "success" => "0",
        ));
}

// ------------------------------------ADULT--------------------------------------------------\\
function get_class_info_adult()
{
    global $timedate;
    $q1 = "SELECT DISTINCT
    IFNULL(j_class.id, '') primaryid,
    IFNULL(j_class.class_code, '') j_class_class_code,
    IFNULL(j_class.name, '') j_class_name,
    IFNULL(j_class.class_type_adult, '') j_class_class_type_adult,
    IFNULL(j_class.kind_of_course_adult, '') kind_of_course_adult,
    IFNULL(j_class.level, '') j_class_level,
    j_class.start_date j_class_start_date,
    j_class.end_date j_class_end_date,
    IFNULL(l1.id, '') l1_id,
    IFNULL(l1.name, '') l1_name,
    IFNULL(j_class.short_schedule, '') short_schedule,
    l2.team_type l2_team_type
    FROM
    j_class
    LEFT JOIN
    j_class_j_class_1_c l1_1 ON j_class.id = l1_1.j_class_j_class_1j_class_ida
    AND l1_1.deleted = 0
    LEFT JOIN
    j_class l1 ON l1.id = l1_1.j_class_j_class_1j_class_idb
    AND l1.deleted = 0
    INNER JOIN
    teams l2 ON j_class.team_id = l2.id
    AND l2.deleted = 0
    WHERE
    (((j_class.id = '{$_POST['class_id']}')))
    AND j_class.deleted = 0";
    $rs1 = $GLOBALS['db']->query($q1);
    $row_class = $GLOBALS['db']->fetchByAssoc($rs1);

  
    $htmlS = '';
    $short_schedule = json_decode(html_entity_decode($row_class['short_schedule']));
    foreach ($short_schedule as $key => $value) {
        $htmlS .= '<li>';
        $htmlS .= $value . ': ' . $key;
        $htmlS .= '</li>';
    }

    //Get Session
    $q2 = "SELECT DISTINCT
    IFNULL(meetings.id, '') primaryid,
    IFNULL(l1.id, '') class_id,
    meetings.date_start date_start,
    meetings.lesson_number lesson_number,
    meetings.delivery_hour delivery_hour
    FROM
    meetings
    INNER JOIN
    j_class l1 ON meetings.ju_class_id = l1.id
    AND l1.deleted = 0
    WHERE
    (((l1.id IN ('{$row_class['primaryid']}')  )
    AND (meetings.session_status <> 'Cancelled')))
    AND meetings.deleted = 0
    ORDER BY class_id, date_start ASC";
    $rs2 = $GLOBALS['db']->query($q2);
    $ssClass = array();
    $key = 0;
    $start_study = '';
    $today = strtotime($timedate->nowDbDate());
    while ($ss = $GLOBALS['db']->fetchByAssoc($rs2)) {
        $ssClass[$key]['date'] = date('Y-m-d', strtotime("+7 hours " . $ss['date_start']));
        $ssClass[$key]['hour'] = format_number($ss['delivery_hour'], 2, 2);

        if (strtotime($ssClass[$key]['date']) >= $today && empty($start_study))
            $start_study = $timedate->to_display_date($ss['date_start']);

        $key++;
    }
    $end_study = '';
    if ($today <= strtotime($row_class['j_class_end_date']))
        $end_study = $timedate->to_display_date($row_class['j_class_end_date'], false);

    $json_ss = json_encode($ssClass);
    //Public Student
    if (!empty($_POST['payment_id'])) {
        //Get Remain
        $q3 = "SELECT DISTINCT
        IFNULL(j_payment.id, '') primaryid,
        j_payment.number_of_connect j_payment_number_of_connect,
        j_payment.number_of_practice j_payment_number_of_practice,
        j_payment.paid_amount paid_amount,
        j_payment.paid_hours paid_hours,
        j_payment.deposit_amount deposit_amount,
        j_payment.final_sponsor final_sponsor,
        j_payment.discount_amount discount_amount,
        j_payment.amount_bef_discount amount_bef_discount,
        j_payment.payment_amount payment_amount,
        j_payment.end_study,
        j_payment.number_of_skill j_payment_number_of_skill,
        SUM(IFNULL(l1.payment_amount, 0)) pmt_total_amount,
        j_payment.final_sponsor_percent
        FROM
        j_payment
        INNER JOIN
        j_paymentdetail l1 ON j_payment.id = l1.payment_id AND l1.deleted = 0 AND l1.status = 'Paid'
        WHERE
        (((j_payment.id = '{$_POST['payment_id']}')))
        AND j_payment.deleted = 0";
        $rs3 = $GLOBALS['db']->query($q3);
        $row_payment = $GLOBALS['db']->fetchByAssoc($rs3);

        if ($row_payment['pmt_total_amount'] == 0 && (($row_payment['final_sponsor'] + $row_payment['discount_amount']) != $row_payment['amount_bef_discount']) && $row_payment['paid_hours'] == 0 && $row_payment['deposit_amount'] == 0 && !empty($row_payment['end_study']) && $row_payment['end_study'] < $GLOBALS['timedate']->nowDbDate()) {
            return json_encode(array(
                "success" => "0",
                "notify" => "<span class=\"ui-icon ui-icon-alert\" style=\"float:left;\"></span> Payment này đã hết hạn hoặc chưa thanh toán đủ, hãy kiểm tra lại !!",
            ));
        }
    }
    // Corporate Student
    if (!empty($_POST['contract_id'])) {
        //       $contract = BeanFactory::getBean('Contract', $_POST['contract_id']);
        //       if($contract->status != 'signed'){
        //          return json_encode(array(
        //                "success"         => "0",
        //                "notify"         => "<span class=\"ui-icon ui-icon-alert\" style=\"float:left;\"></span> Contract  !!",
        //            ));
        //       }
    }

    return json_encode(array(
        "success" => "1",
        "class_code" => $row_class['j_class_class_code'],
        "class_name" => $row_class['j_class_name'],
        "class_type" => $row_class['j_class_class_type_adult'],
        "start_date" => $timedate->to_display_date($row_class['j_class_start_date'], false),
        "end_date" => $timedate->to_display_date($row_class['j_class_end_date'], false),
        "koc" => $row_class['kind_of_course_adult'],
        "level" => $row_class['j_class_level'],
        "start_study" => $start_study,
        "end_study" => $end_study,
        "schedule" => $htmlS,
        "session_list" => $json_ss,
        "remain_practice" => $row_payment['j_payment_number_of_practice'],
        "remain_skill" => $row_payment['j_payment_number_of_skill'],
        "remain_connect" => $row_payment['j_payment_number_of_connect'],
    ));
}

function submitAddToClass()
{
    require_once('custom/include/_helper/junior_class_utils.php');
    //Public Student
    if (!empty($_POST['payment_id'])) {
        return addToClassAdult($_POST['payment_id'], $_POST['class_id'], $_POST['start_study'], $_POST['end_study']);
    }

    // Corporate Student
    if (!empty($_POST['contract_id'])) {
        //Get List Student + Payment Corporate
        $q20 = "SELECT DISTINCT
        IFNULL(contacts.id, '') student_id,
        IFNULL(l1.id, '') contract_id,
        CONCAT(IFNULL(contacts.last_name, ''),
        ' ',
        IFNULL(contacts.first_name, '')) student_name,
        IFNULL(l2.id, '') payment_id,
        IFNULL(l2.name, '') payment_name,
        l2.start_study start_study,
        l2.end_study end_study,
        l2.payment_date payment_date,
        l2.payment_amount payment_amount,
        l2.date_entered date_entered
        FROM
        contacts
        INNER JOIN
        contracts_contacts l1_1 ON contacts.id = l1_1.contact_id
        AND l1_1.deleted = 0
        INNER JOIN
        contracts l1 ON l1.id = l1_1.contract_id
        AND l1.deleted = 0 AND (l1.id = '{$_POST['contract_id']}')
        INNER JOIN
        contacts_j_payment_1_c l2_1 ON contacts.id = l2_1.contacts_j_payment_1contacts_ida
        AND l2_1.deleted = 0
        INNER JOIN
        j_payment l2 ON l2.id = l2_1.contacts_j_payment_1j_payment_idb
        AND l2.deleted = 0 AND (l2.payment_type = 'Corporate')
        AND l2.contract_id = l1.id
        WHERE
        contacts.deleted = 0 AND contacts.id IN ('" . implode("','", explode(',', $_POST['student_list'])) . "')
        GROUP BY student_id, date_entered ASC";

        $row_pays = $GLOBALS['db']->fetchArray($q20);
        $count_student_pay = array_count_values(array_column($row_pays, 'student_id'));
        $row_pay_2d = array();
        $student_id = '';
        $added_student = array();
        foreach ($row_pays as $key => $row_pay) {  //Xét trường hợp 1 Payment
            if ($count_student_pay[$row_pay['student_id']] == 1) {
                $row_cop[$row_pay['student_id']]['student_name'] = $row_pay['student_name'];
                $row_cop[$row_pay['student_id']]['payment_id'] = $row_pay['payment_id'];
                $added_student[] = $row_pay['student_id'];
            } else { //Xét trường hợp hơn 2 Payment - Convert Array
                if ($student_id != $row_pay['student_id'])
                    $i2d = 0;
                $row_pay_2d[$row_pay['student_id']][$i2d]['payment_id'] = $row_pay['payment_id'];
                $row_pay_2d[$row_pay['student_id']][$i2d]['student_name'] = $row_pay['student_name'];
                $row_pay_2d[$row_pay['student_id']][$i2d]['start_study'] = $row_pay['start_study'];
                $row_pay_2d[$row_pay['student_id']][$i2d]['end_study'] = $row_pay['end_study'];
                $student_id = $row_pay['student_id'];
                $i2d++;
            }
        }
        //Xử lý mảng 2D
        $today = date('Y-m-d');

        foreach ($row_pay_2d as $student_id => $row_pay_2) {
            foreach ($row_pay_2 as $key => $row_pay) {
                //TH1: Tìm Payment đang sử dụng
                if ($today >= $row_pay['start_study'] && $today <= $row_pay['end_study'] && (!in_array($student_id, $added_student))) {
                    $row_cop[$student_id]['student_name'] = $row_pay['student_name'];
                    $row_cop[$student_id]['payment_id'] = $row_pay['payment_id'];
                    $added_student[] = $student_id;
                }
            }
        }
        foreach ($row_pay_2d as $student_id => $row_pay_2) {
            foreach ($row_pay_2 as $key => $row_pay) {
                //TH2: Nếu không có Payment đang sử dụng thì chọn cái đầu tiên
                if (!in_array($student_id, $added_student)) {
                    $row_cop[$student_id]['student_name'] = $row_pay['student_name'];
                    $row_cop[$student_id]['payment_id'] = $row_pay['payment_id'];
                    $added_student[] = $student_id;
                }
            }
        }

        $res_cop = array();
        foreach ($row_cop as $key => $value) {
            $res_cop[$key]['student_name'] = $value['student_name'];
            $res_cop[$key]['result'] = addToClassAdult($value['payment_id'], $_POST['class_id'], $_POST['start_study'], $_POST['end_study']);
        }
        $contract = BeanFactory::getBean('Contracts', $_POST['contract_id']);
        if ($contract->load_relationship('contracts_j_class_1'))
            $contract->contracts_j_class_1->add($_POST['class_id']);
        return json_encode(array(
            "success" => "1",
            "result" => $res_cop,
        ));
    }
}

function caculateDropPaymentAdult($payment_id, $dl_date)
{
    $payment = BeanFactory::getBean('J_Payment', $payment_id);
    // Get Total amount
    global $timedate;
    $hide_drop_delay = '0';
    $hide_drop_revenue = '1';
    if ($GLOBALS['current_user']->isAdmin()) {
        $hide_drop_revenue = '0';
    }
    if($payment->status != 'Success')
        return json_encode(array(
            "success" => "0",
            "hide_drop_delay" => '1',
            "hide_drop_revenue" => '1',
        ));
    if ($payment->payment_type == 'Cashholder' || $payment->payment_type == 'Corporate') {
        $pmd_payment_amount = $GLOBALS['db']->getOne("
            SELECT DISTINCT
            SUM(IFNULL(IFNULL(l1.payment_amount, 0) / 1,
            IFNULL(l1.payment_amount, 0))) l1_sum_payment_amount
            FROM
            j_payment
            INNER JOIN
            j_paymentdetail l1 ON j_payment.id = l1.payment_id
            AND l1.deleted = 0
            WHERE
            (((j_payment.id = '{$payment->id}')
            AND (l1.status = 'Paid')))
            AND j_payment.deleted = 0");

        if (!empty($payment->start_study) && !empty($payment->end_study)) {
            $today = $timedate->to_db_date($dl_date, false);
            $start_study = $timedate->to_db_date($payment->start_study, false);
            $end_study = $timedate->to_db_date($payment->end_study, false);
            $total_amount = $payment->paid_amount + $pmd_payment_amount + $payment->deposit_amount;
            $pm_total_amount = $payment->paid_amount + $payment->deposit_amount + $payment->payment_amount;

            if ($today >= $start_study && $today <= $end_study) {
                $holiday_list = get_list_holidays_adult($start_study);
                $arr_range = getDatesFromRange($start_study, $today);
                $arr_learned = array_diff($arr_range, $holiday_list);
                $count_learned = count($arr_learned);


                $remain_amount = $total_amount - ($count_learned * ($pm_total_amount / $payment->tuition_hours));
                $remain_hours = $payment->tuition_hours - $count_learned;
            } elseif ($today < $start_study) {

                $remain_amount = $total_amount;
                $remain_hours = $payment->tuition_hours;
            } elseif ($today > $end_study) {

                $remain_amount = 0;
                $remain_hours = 0;
            }

            if ($total_amount != $pm_total_amount)
                $remain_hours = 0;
        } elseif (empty($payment->start_study) && empty($payment->end_study)) {
            $total_amount = $pmd_payment_amount + $payment->paid_amount + $payment->deposit_amount;
            $remain_amount = $total_amount;
            $remain_hours = $payment->tuition_hours;
        } else {
            return json_encode(array(
                "success" => "0",
                "hide_drop_delay" => '1',
                "hide_drop_revenue" => '1',
            ));
        }
    } elseif ($payment->payment_type == 'Delay') {
        $total_amount = $payment->payment_amount;
        $remain_amount = $payment->remain_amount;
        $remain_hours = $payment->remain_hours;
        $hide_drop_delay = '1';
    }
    return json_encode(array(
        "success" => "1",
        "total_amount" => format_number($total_amount),
        "used_amount" => format_number($total_amount - $remain_amount),
        "drop_amount" => format_number($remain_amount),
        "drop_day" => format_number($remain_hours, 2, 2),
        "hide_drop_delay" => $hide_drop_delay,
        "hide_drop_revenue" => $hide_drop_revenue,
    ));
}

function createDropPaymentAdult()
{
    global $current_user, $timedate;
    require_once('custom/include/_helper/junior_class_utils.php');
    //Check Number Again
    $res    = json_decode(caculateDropPaymentAdult($_POST['payment_id'],$_POST['dl_date']),true);
    if($res['success']){
        $payment = BeanFactory::getBean('J_Payment', $_POST['payment_id']);
        $payment_amount = $GLOBALS['db']->getOne("
            SELECT DISTINCT
            SUM(IFNULL(IFNULL(l1.payment_amount, 0) / 1,
            IFNULL(l1.payment_amount, 0))) l1_sum_payment_amount
            FROM
            j_payment
            INNER JOIN
            j_paymentdetail l1 ON j_payment.id = l1.payment_id
            AND l1.deleted = 0
            WHERE
            (((j_payment.id = '{$payment->id}')
            AND (l1.status = 'Paid')))
            AND j_payment.deleted = 0");
        $unit_price = ($payment_amount + $payment->deposit_amount + $payment->paid_amount) / ($payment->total_hours + $payment->paid_hours);
        if ($_POST['drop_amount'] < 0 || ($_POST['drop_amount'] == 0 && $_POST['drop_day'] == 0))
            return json_encode(array(
                "success" => "0",
            ));

        //Check Sum Again
        if(($res['drop_amount'] != format_number($_POST['drop_amount'])) || ($res['drop_day'] != format_number($_POST['drop_day'], 2, 2))){
            return json_encode(array(
                "success" => "0",
            ));
        }
        //Handle
        $start_study = $timedate->to_db_date($payment->start_study, false);
        $end_study = $timedate->to_db_date($payment->end_study, false);
        $dl_date = $timedate->to_db_date($_POST['dl_date'], false);
        if (empty($payment->start_study) || empty($payment->end_study)) {
            $end_study = null;
            $start_study = null;
        } else {
            if ($end_study > $dl_date) {
                $end_study = $dl_date;
            }
        }
        $GLOBALS['db']->query("UPDATE j_payment SET status='Closed', remain_hours = 0, remain_amount = 0, start_study = '$start_study', end_study = '$end_study', do_not_drop_revenue=0 WHERE id = '{$payment->id}'");

        if ($_POST['drop_type'] == 'drop_to_delay') {
            //Payment Delay
            $pm_delay = new J_Payment();
            $pm_delay->contacts_j_payment_1contacts_ida = $payment->contacts_j_payment_1contacts_ida;
            $pm_delay->payment_type = 'Delay';
            $pm_delay->use_type = "Hour";
            if ($_POST['drop_day'] == 0)
                $pm_delay->use_type = 'Amount';
            $pm_delay->payment_date = $_POST['dl_date'];
            $pm_delay->payment_expired = date('Y-m-d', strtotime("+6 months " . $timedate->to_db_date($_POST['dl_date'], false)));
            $pm_delay->payment_amount = format_number($_POST['drop_amount']);
            $pm_delay->remain_amount = format_number($_POST['drop_amount']);
            $pm_delay->tuition_hours = format_number($_POST['drop_day'], 2, 2);
            $pm_delay->total_hours = format_number($_POST['drop_day'], 2, 2);
            $pm_delay->remain_hours = format_number($_POST['drop_day'], 2, 2);
            $pm_delay->start_study = '';
            $pm_delay->end_study = '';
            if ($payment->payment_type == 'Corporate')
                $pm_delay->contract_id = $payment->contract_id;
            $pm_delay->kind_of_course_360 = $payment->kind_of_course_360;
            $pm_delay->used_hours = 0;
            $pm_delay->used_amount = 0;
            $pm_delay->description = $_POST['dl_reason'];
            $pm_delay->assigned_user_id = $current_user->id;
            $pm_delay->team_id = $payment->team_id;
            $pm_delay->team_set_id = $payment->team_id;
            $pm_delay->save();

            addRelatedPayment($pm_delay->id, $payment->id, $_POST['drop_amount'], $_POST['drop_day']);
            //Remove student from Session
            $remove_from_date_db = $timedate->to_display_date(date('Y-m-d', strtotime("+1 day " . $timedate->to_db_date($_POST['dl_date'], false))), false);
            $row2 = get_list_revenue('', "'Enrolled', 'Moving In'", $remove_from_date_db, '', '', '', '', $_POST['payment_id']);
            $arr_situation = array();
            for ($i = 0; $i < count($row2); $i++) {
                removeJunFromSession($row2[$i]['situation_id'], $row2[$i]['primaryid']);
                if (!in_array($row2[$i]['situation_id'], $arr_situation))
                    $arr_situation[] = $row2[$i]['situation_id'];
            }
            //Xử lý Situation
            $q1 = "SELECT DISTINCT
            l3.id situation_id,
            IFNULL(MIN(DATE_FORMAT(CONVERT_TZ(meetings.date_start,'+0:00','+7:00'),'%Y-%m-%d')), '') start_study,
            IFNULL(MAX(DATE_FORMAT(CONVERT_TZ(meetings.date_start,'+0:00','+7:00'),'%Y-%m-%d')), '') end_study
            FROM
            meetings
            INNER JOIN
            meetings_contacts l1_1 ON meetings.id = l1_1.meeting_id
            AND l1_1.deleted = 0
            INNER JOIN
            j_studentsituations l3 ON l1_1.situation_id = l3.id
            AND l3.deleted = 0
            WHERE
            ((meetings.deleted = 0
            AND (meetings.session_status <> 'Cancelled')
            AND (l3.id IN ('" . implode("','", $arr_situation) . "'))))
            GROUP BY situation_id";
            $rs1 = $GLOBALS['db']->query($q1);
            while ($row_1 = $GLOBALS['db']->fetchByAssoc($rs1)) {
                $GLOBALS['db']->query("UPDATE j_studentsituations SET start_study='{$row_1['start_study']}', end_study='{$row_1['end_study']}' WHERE id='{$row_1['situation_id']}'");
                if (($key = array_search($row_1['situation_id'], $arr_situation)) !== false)
                    unset($arr_situation[$key]);
            }
            $GLOBALS['db']->query("UPDATE j_studentsituations SET deleted=1 WHERE id IN ('" . implode("','", $arr_situation) . "')");

        } elseif ($_POST['drop_type'] == 'drop_to_revenue') {
            $delivery = new C_DeliveryRevenue();
            $delivery->name = 'Drop revenue from payment ' . $payment->name;
            $delivery->student_id = $payment->contacts_j_payment_1contacts_ida;
            //Get Payment ID
            $delivery->ju_payment_id = $payment->id;
            $delivery->type = 'Adult';
            $delivery->amount = format_number($_POST['drop_amount']);
            $delivery->duration = format_number($_POST['drop_amount'] / $unit_price, 2, 2);
            $delivery->date_input = $timedate->to_db_date($_POST['dl_date'], false);
            $delivery->cost_per_hour = 0;
            $delivery->session_id = '1';
            $delivery->passed = 0;
            $delivery->description = $_POST['dl_reason'];
            $delivery->team_id = $payment->team_id;
            $delivery->team_set_id = $payment->team_id;
            $delivery->cost_per_hour = $unit_price;
            $delivery->assigned_user_id = $current_user->id;
            $delivery->revenue_type = 'Enrolled';
            $delivery->save();
        }

        return json_encode(array(
            "success" => "1",
        ));

    }else{
        return json_encode(array(
            "success" => "0",
        ));
    }

}

function enable_delay_fee()
{
    require_once("custom/include/_helper/junior_class_utils.php");
    global $current_user, $timedate;
    $pm_delay = BeanFactory::getBean('J_Payment', $_POST['payment_id']);
    if ($pm_delay->remain_hours > 0) {
        $rel_cash_id = $GLOBALS['db']->getOne("SELECT j_payment_j_payment_1j_payment_idb FROM j_payment_j_payment_1_c WHERE j_payment_j_payment_1j_payment_ida = '{$_POST['payment_id']}' AND deleted = 0");
        if (!empty($rel_cash_id))
            $rel_cash = BeanFactory::getBean('J_Payment', $rel_cash_id);

        $bean = new J_Payment();
        $bean->payment_type = 'Cashholder';
        if (!empty($pm_delay->contract_id))
            $bean->payment_type = 'Corporate';
        $bean->contract_id = $pm_delay->contract_id;
        $bean->contacts_j_payment_1contacts_ida = $pm_delay->contacts_j_payment_1contacts_ida;
        $bean->remain_amount = format_number(unformat_number($pm_delay->remain_amount));
        $bean->tuition_fee = $bean->remain_amount;
        $bean->amount_bef_discount = 0;
        $bean->total_after_discount = 0;
        $bean->payment_amount = 0;
        $bean->paid_amount = $bean->remain_amount;
        $bean->paid_hours = format_number(unformat_number($pm_delay->remain_hours), 2, 2);
        $bean->payment_date = $timedate->to_db_date($_POST['edf_return_date'], false);
        $bean->sale_type = 'Not set';
        $bean->sale_type_date = $bean->payment_date;
        $bean->tuition_hours = $bean->paid_hours;
        $bean->total_hours = 0;
        $bean->remain_hours = $bean->paid_hours;
        if (!empty($rel_cash_id))
            $bean->kind_of_course_360 = $rel_cash->kind_of_course_360;
        else $bean->kind_of_course_360 = $pm_delay->kind_of_course_360;

        $bean->number_of_skill = 0;
        $bean->number_of_practice = 0;
        $bean->number_of_connect = 0;
        $bean->payment_list = '{"paid_list":{"' . $pm_delay->id . '":{"id":"' . $pm_delay->id . '","used_amount":' . unformat_number($bean->paid_amount) . ',"used_hours":' . unformat_number($bean->remain_hours) . '}},"deposit_list":{}}';

        $bean->payment_expired = '';
        $bean->start_study = '';
        $bean->end_study = '';


        $bean->assigned_user_id = $pm_delay->assigned_user_id;
        $bean->team_id = $pm_delay->team_id;
        $bean->team_set_id = $pm_delay->team_id;
        $bean->save();

        addRelatedPayment($bean->id, $pm_delay->id, $pm_delay->remain_amount, $pm_delay->remain_hours);
        $GLOBALS['db']->query("UPDATE j_payment SET remain_amount = 0, used_amount={$pm_delay->remain_amount}, used_hours={$pm_delay->remain_hours}, remain_hours = 0 WHERE id = '{$pm_delay->id}'");
        return json_encode(array(
            "success" => "1",
            "cashholder_id" => $bean->id,
        ));
    } else {
        return json_encode(array(
            "success" => "0",
        ));
    }


}

function ajaxCheckVoucherCode(){
    global $timedate;
    if (!empty($_POST['voucher_code'])) {
        $q1 = "SELECT DISTINCT
        IFNULL(j_voucher.id, '') voucher_id,
        IFNULL(j_voucher.foc_type, '') foc_type,
        IFNULL(j_voucher.name, '') voucher_code,
        IFNULL(j_voucher.status, '') status,
        CONCAT(IFNULL(l1.last_name, ''),
        ' ',
        IFNULL(l1.first_name, '')) student_name,
        IFNULL(l1.id, '') student_id,
        IFNULL(j_voucher.use_time, '') use_time,
        IFNULL(j_voucher.amount_per_used, 0) amount_per_used,
        j_voucher.used_time voucher_used_time,
        j_voucher.redempt_time voucher_redempt_time,
        j_voucher.discount_amount discount_amount,
        j_voucher.discount_percent discount_percent,
        j_voucher.description description,
        j_voucher.start_date start_date,
        j_voucher.end_date end_date,
        COUNT(l2.id) used_time
        FROM
        j_voucher
        LEFT JOIN
        contacts l1 ON j_voucher.student_id = l1.id
        AND l1.deleted = 0
        LEFT JOIN  j_sponsor l2 ON j_voucher.id=l2.voucher_id AND l2.deleted=0
        WHERE
        (((j_voucher.name = '{$_POST['voucher_code']}')
        AND ((1 = 1))))
        AND j_voucher.deleted = 0
        GROUP BY j_voucher.id
        ORDER BY voucher_id ASC";
        $rs1 = $GLOBALS['db']->query($q1);
        $row = $GLOBALS['db']->fetchByAssoc($rs1);
        if (!empty($row)){
            $row['student_name'] = '<a target="_blank" href="index.php?module=Contacts&amp;action=DetailView&amp;record=' . $row['student_id'] . '">' . $row['student_name'] . '</a>';
            //TH: Người sử dụng là Owner
            if ($row['student_id'] == $_POST['student_id'] && !empty($row['student_id'])){

                $row['discount_amount'] = $row['used_time'] * $row['amount_per_used'];
                $row['discount_percent'] = 0;
                //    $row['status']           = 'Expired';
                $row['student_name'] .= ' (<b style="color:blue;"> Is Owner</b>)';
                $row['redempt_time'] = $row['voucher_redempt_time'];
            }else{// TH: Người sử dụng là Refferer
                if ($row['use_time'] != 'N' && $row['used_time'] >= $row['use_time']) {
                    $row['status'] = 'Expired';
                }
                $row['redempt_time'] = $row['voucher_redempt_time'];
            }
            //Update Used !
            if ($row['voucher_used_time'] != $row['used_time']) {
                $GLOBALS['db']->query("UPDATE j_voucher SET used_time={$row['used_time']} WHERE id = '{$row['voucher_id']}'");
            }
            //Check so lan su dung referral code
            if($row['foc_type'] == 'Referral'){
                $ext_payment = '';
                if(!empty($_POST['payment_id']))
                    $ext_payment = " AND l1.id <> '{$_POST['payment_id']}'";
                $countUsedRefferral = $GLOBALS['db']->getOne("SELECT DISTINCT
                    IFNULL(COUNT(l3.id),0) allcount
                    FROM
                    contacts
                    INNER JOIN
                    contacts_j_payment_1_c l1_1 ON contacts.id = l1_1.contacts_j_payment_1contacts_ida
                    AND l1_1.deleted = 0
                    INNER JOIN
                    j_payment l1 ON l1.id = l1_1.contacts_j_payment_1j_payment_idb
                    AND l1.deleted = 0 $ext_payment
                    INNER JOIN
                    j_sponsor l2 ON l1.id = l2.payment_id AND l2.deleted = 0
                    INNER JOIN
                    j_voucher l3 ON l2.voucher_id = l3.id AND l3.deleted = 0
                    WHERE
                    (((l3.foc_type = 'Referral')
                    AND (contacts.id = '{$_POST['student_id']}')))
                    AND contacts.deleted = 0");
                if(empty($countUsedRefferral))
                    $countUsedRefferral = 0;

                if($countUsedRefferral > 0){
                    return json_encode(array("success" => "-1",));
                }
            }

            return json_encode(array(
                "success" => "1",
                "voucher_id" => $row['voucher_id'],
                "voucher_code" => $row['voucher_code'],
                "foc_type" => $row['foc_type'],
                "status" => $row['status'],
                "student_name" => $row['student_name'],
                "use_time" => $row['use_time'],
                "used_time" => $row['used_time'],
                "redempt_time" => $row['redempt_time'],
                "discount_amount" => format_number($row['discount_amount']),
                "discount_percent" => format_number($row['discount_percent'], 2, 2),
                "description" => $row['description'],
                "start_date" => $timedate->to_display_date($row['start_date'], false),
                "end_date" => $timedate->to_display_date($row['end_date'], false),
            ));

        }else return json_encode(array("success" => "0",));

    } else
        return json_encode(array("success" => "0",));

}


function ajax_payment_detail(){
    global $timedate;
    $eid_from   = $timedate->to_db_date($_POST['eid_from'],false);
    $eid_to     = $timedate->to_db_date($_POST['eid_to'],false);
    $payment_id = $_POST['record_id'];
    $invoice_id = $_POST['invoice_id'];
    $eid_invoice_date = $_POST['eid_invoice_date'];
    $team_id    = $GLOBALS['current_user']->team_id;
    if (!empty($payment_id)) {
        $ext_inv = '';
        if(!empty($invoice_id)) $ext_inv =  " OR (l3.invoice_id = '$invoice_id')"; //Edit case

        $payment = BeanFactory::getBean('J_Payment', $payment_id);
        if($payment->parent_type == 'Leads'){
            $student_id = $payment->lead_id;
            $ext_query = "leads INNER JOIN
            j_payment l2 ON l2.lead_id = leads.id AND (leads.id = '$student_id')
            AND l2.deleted = 0";
        }else{
            $student_id = $payment->contacts_j_payment_1contacts_ida;
            $ext_query = "contacts INNER JOIN
            contacts_j_payment_1_c l2_1 ON contacts.id = l2_1.contacts_j_payment_1contacts_ida
            AND (contacts.id = '$student_id') AND l2_1.deleted = 0
            INNER JOIN
            j_payment l2 ON l2.id = l2_1.contacts_j_payment_1j_payment_idb
            AND l2.deleted = 0";
        }

        $q1 = "SELECT DISTINCT
        IFNULL(l2.id, '') l2_id,
        IFNULL(l2.name, '') l2_name,
        IFNULL(l2.payment_type, '') l2_payment_type,
        IFNULL(l3.id, '') l3_id,
        l3.payment_no l3_payment_no,
        IFNULL(l3.status, '') l3_status,
        IFNULL(l3.invoice_id, '') l3_invoice_id,
        IFNULL(l4.id, '') team_id,
        l3.payment_date l3_payment_date,
        IFNULL(l3.before_discount, 0) l3_before_discount,
        IFNULL(l3.discount_amount, 0) l3_discount_amount,
        IFNULL(l3.sponsor_amount, 0) l3_sponsor_amount,
        IFNULL(l3.loyalty_amount, 0) l3_loyalty_amount,
        IFNULL(l3.payment_amount, 0) l3_payment_amount,
        IFNULL(l3.name, '') l3_name
        FROM
        $ext_query
        INNER JOIN
        j_paymentdetail l3 ON l2.id = l3.payment_id AND l3.deleted = 0
        INNER JOIN
        teams l4 ON l2.team_id = l4.id
        AND l4.deleted = 0
        WHERE
        ((((l2.payment_date >= '$eid_from'
        AND l2.payment_date <= '$eid_to'))
        AND (l3.status IN ('Paid', 'Unpaid'))
        AND ((l3.invoice_number IS NULL
        OR l3.invoice_number = ''))
        $ext_inv
        ))
        ORDER BY CASE WHEN l2.payment_type='Enrollment' THEN 0
        WHEN l2.payment_type='Cashholder' THEN 1
        WHEN l2.payment_type='Deposit' THEN 2
        WHEN l2.payment_type='Placement Test' THEN 10
        WHEN l2.payment_type='Book/Gift' THEN 11 ELSE 16 END ASC,
        l2_id, l3_payment_date DESC";
        $rs1 = $GLOBALS['db']->query($q1);
        $html = '';
        $count = 0;

        while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
            $count++;
            $checked = '';
            if(!empty($invoice_id)){
                if($row['l3_invoice_id'] == $invoice_id)
                    $checked = 'checked';
            }elseif($row['l2_id'] == $payment_id) $checked = 'checked';
            $team_id = $row['team_id'];
            $html .= '<tr>';
            $html .= '<td class="center"><input type="checkbox" '.$checked.' style="vertical-align: initial;zoom: 1.2;" id="eid_detail_id'.$count.'" class="eid_detail_id custom_checkbox" module_name="J_PaymentDetail" onclick="handleCheckBox($(this));calculateInvoice();" value="'.$row['l3_id'].'"/>';
            $html .= '<input type="hidden" class="edi_before_discount" value="'.$row['l3_before_discount'].'"/>';
            $html .= '<input type="hidden" class="edi_discount_amount" value="'.($row['l3_discount_amount'] + $row['l3_sponsor_amount'] + $row['l3_loyalty_amount']).'"/>';
            $html .= '<input type="hidden" class="edi_payment_amount" value="'.$row['l3_payment_amount'].'"/>';
            $html .= '</td>';
            $html .= '<td class="center" nowrap >'.$timedate->to_display_date($row['l3_payment_date'],false).'</td>';
            $html .= '<td class="center" nowrap >'.$row['l2_name'].'</td>';
            $html .= '<td class="center" nowrap >'.$GLOBALS['app_list_strings']['payment_type_payment_list'][$row['l2_payment_type']].'</td>';
            $html .= '<td class="center" nowrap >'.format_number($row['l3_before_discount']).'</td>';
            $html .= '<td class="center" nowrap >'.format_number($row['l3_discount_amount'] + $row['l3_sponsor_amount'] + $row['l3_loyalty_amount']).'</td>';
            $html .= '<td class="center" nowrap >'.format_number($row['l3_payment_amount']).'</td>';
            $html .= '<td class="center" nowrap >'.$GLOBALS['app_list_strings']['status_paymentdetail_list'][$row['l3_status']].'</td>';
            $html .= '</tr>';
        }
        if($count == 0){
            $html .= '<td class="center" colspan="8">No Payment Avaiable</td>';
        }
        //Get Invoice No
        if(!empty($invoice_id)){   //Edit case
            $rs2 = $GLOBALS['db']->query("SELECT DISTINCT id, IFNULL(name, '') invoice_no, serial_no, invoice_date FROM j_invoice WHERE id = '$invoice_id'");
            $row2 = $GLOBALS['db']->fetchByAssoc($rs2);
        }else{
            $row2 = array(
                'invoice_date'  => $timedate->nowDbDate(),
                'serial_no'     => '', 
                'invoice_no'    => generateInvNo($team_id, $timedate->nowDbDate()));  
        }
        return json_encode(array(
            "success"   => "1",
            "html"      => $html,
            "count"     => $count,
            "invoice_id"    => $invoice_id,
            "invoice_date"  => $timedate->to_display_date($row2['invoice_date']),
            "serial_no"     => $row2['serial_no'],
            "invoice_no"    => $row2['invoice_no'],

        ));
    }else return json_encode(array("success" => "0",));

}

function ajaxSaveInvoice(){
    global $timedate;
    $eid_invoice_date    = $timedate->to_db_date($_POST['eid_invoice_date'],false);
    $idArray             = explode(",", $_POST['idsChecked']);
    $payment_id          = $_POST['record_id'];
    $invoice_id          = $_POST['invoice_id'];
    $eid_invoice_serial  = strtoupper($_POST['eid_invoice_serial']);
    $eid_invoice_no      = strtoupper($_POST['eid_invoice_no']);

    if(!empty($invoice_id)){  //Delete before Edit
        $GLOBALS['db']->query("UPDATE j_paymentdetail SET invoice_number='', serial_no='', numeric_vat_no=0, invoice_id='' WHERE invoice_id = '$invoice_id' AND deleted = 0");
        $GLOBALS['db']->query("UPDATE j_invoice SET deleted=1 WHERE id='$invoice_id' AND deleted = 0");
    }

    if (!empty($payment_id) && !empty($idArray)){

        $payment = BeanFactory::getBean('J_Payment', $payment_id);
        if($payment->parent_type == 'Leads'){
            $student_id = $payment->lead_id;
            $ext_query = "INNER JOIN
            leads l1 ON j_paymentdetail.student_id = l1.id
            AND l1.deleted = 0";
        }else{
            $student_id = $payment->contacts_j_payment_1contacts_ida;
            $ext_query = "INNER JOIN
            contacts l1 ON j_paymentdetail.student_id = l1.id
            AND l1.deleted = 0";
        }


        $q1 = "SELECT DISTINCT
        IFNULL(l1.id, '') l1_id,
        SUM(IFNULL(IFNULL(j_paymentdetail.before_discount, 0) / 1,
        IFNULL(j_paymentdetail.before_discount, 0))) before_discount,
        SUM(IFNULL(IFNULL(j_paymentdetail.discount_amount, 0) / 1,
        IFNULL(j_paymentdetail.discount_amount + j_paymentdetail.sponsor_amount + j_paymentdetail.loyalty_amount, 0))) discount_amount,
        SUM(IFNULL(IFNULL(j_paymentdetail.payment_amount, 0) / 1,
        IFNULL(j_paymentdetail.payment_amount, 0))) payment_amount,
        COUNT(*) count
        FROM
        j_paymentdetail
        $ext_query
        WHERE
        (((j_paymentdetail.id IN('".implode("','",$idArray)."'))
        AND (j_paymentdetail.status IN ('Paid', 'Unpaid'))
        AND ((j_paymentdetail.invoice_number IS NULL
        OR j_paymentdetail.invoice_number = ''))))
        AND j_paymentdetail.deleted = 0
        GROUP BY l1.id
        ORDER BY l1_id ASC";
        $rs1 = $GLOBALS['db']->query($q1);
        $row = $GLOBALS['db']->fetchByAssoc($rs1);
        if(($row['before_discount'] == $_POST['eid_total_before_discount']) && ($row['discount_amount'] == $_POST['eid_total_discount_amount']) && ($row['payment_amount'] == $_POST['eid_total_after_discount'])){
            $payment = BeanFactory::getBean('J_Payment',$payment_id);
            $invNew = new J_Invoice();
            $invNew->name                   = $eid_invoice_no;
            $invNew->invoice_date           = $eid_invoice_date;
            $invNew->before_discount        = format_number($row['before_discount']);
            $invNew->total_discount_amount  = format_number($row['discount_amount']);
            $invNew->invoice_amount         = format_number($row['payment_amount']);
            $invNew->serial_no              = $eid_invoice_serial;
            $invNew->payment_id             = $payment_id;
            $invNew->team_id                = $payment->team_id;
            $invNew->team_set_id            = $payment->team_set_id;
            $invNew->assigned_user_id       = $payment->assigned_user_id;
            $invNew->save();
            //Update Payment Detail
            $GLOBALS['db']->query("UPDATE j_paymentdetail SET invoice_number='{$invNew->name}', serial_no='{$invNew->serial_no}', numeric_vat_no=".intval($invNew->name).", invoice_id='{$invNew->id}'  WHERE id IN ('".implode("','",$idArray)."') AND deleted = 0");

            return json_encode(array("success" => "1",));
        }else
            return json_encode(array("success" => "0",));

    }else return json_encode(array("success" => "0",));

}


function ajaxCancelInvoice($invoice_id, $description){
    $invoice_id = $_POST['invoice_id'];
    if(!empty($invoice_id)){  //Update Cancelled
        $GLOBALS['db']->query("UPDATE j_paymentdetail SET invoice_number='', serial_no='', numeric_vat_no=0, invoice_id='' WHERE invoice_id = '$invoice_id' AND deleted = 0");
        $GLOBALS['db']->query("UPDATE j_invoice SET status='Cancelled',description='$description' WHERE id='$invoice_id' AND deleted = 0");
        return json_encode(array("success" => "1",));
    }else{
        return json_encode(array("success" => "0",));
    }
}

function generateInvNo($team_id, $inv_date){
    //Get Prefix
    $res        = $GLOBALS['db']->query("SELECT teams.code_prefix, team_type FROM teams WHERE id = '{$team_id}'");
    $row        = $GLOBALS['db']->fetchByAssoc($res);
    $prefix     = $row['code_prefix'];
    $year       = date('ym',strtotime('+ 7hours'. $inv_date));
    $table      = 'j_invoice';
    $code_field = 'name';
    $sep        = '-';
    $first_pad  = '0000';
    $padding    = 4;
    $query = "SELECT $code_field FROM $table WHERE ( $code_field <> '' AND $code_field IS NOT NULL) AND id != '{$id}' AND (LEFT($code_field, ".strlen($prefix.$year).") = '".$prefix.$year."') ORDER BY RIGHT($code_field, $padding) DESC LIMIT 1";

    $result = $GLOBALS['db']->query($query);
    if($row = $GLOBALS['db']->fetchByAssoc($result))
        $last_code = $row[$code_field];
    else
        //no codes exist, generate default - PREFIX + CURRENT YEAR +  SEPARATOR + FIRST NUM
        $last_code = $prefix . $year . $sep  . $first_pad;


    $num = substr($last_code, -$padding, $padding);
    $num++;
    $pads = $padding - strlen($num);
    $new_code = $prefix . $year . $sep;

    //preform the lead padding 0
    for($i=0; $i < $pads; $i++)
        $new_code .= "0";
    $new_code .= $num;

    //return
    return $new_code;
}