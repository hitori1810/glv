<?php
function getEnrollmentCF($team_id){
    $q1 = "SELECT DISTINCT
    IFNULL(l1.id, '') student_id,
    IFNULL(opportunities.id, '') enrollment_id,
    IFNULL(l2.id, '') l2_id,
    l2.kind_of_course kind_of_course,
    l1.full_student_name full_student_name,
    IFNULL(opportunities.total_hours, 0) total_hours,
    IFNULL(l3.id, '') enrollment_team_id
    FROM
    opportunities
    INNER JOIN
    opportunities_contacts l1_1 ON opportunities.id = l1_1.opportunity_id
    AND l1_1.deleted = 0
    INNER JOIN
    contacts l1 ON l1.id = l1_1.contact_id
    AND l1.deleted = 0
    INNER JOIN
    c_packages_opportunities_1_c l2_1 ON opportunities.id = l2_1.c_packages_opportunities_1opportunities_idb
    AND l2_1.deleted = 0
    INNER JOIN
    c_packages l2 ON l2.id = l2_1.c_packages_opportunities_1c_packages_ida
    AND l2.deleted = 0
    INNER JOIN
    teams l3 ON opportunities.team_id = l3.id
    AND l3.deleted = 0
    WHERE
    (COALESCE(LENGTH(l1.id), 0) > 0)
    AND (opportunities.sales_stage IN ('Success','Closed'))
    AND (l3.id = '$team_id')
    AND opportunities.deleted = 0";

    return $GLOBALS['db']->fetchArray($q1);
}

function getTotalPayIn($team_id, $start, $end)  {
    // get total payment
    $q1 = "SELECT DISTINCT
    IFNULL(l4.id, '') enrollment_id,
    YEAR(c_payments.payment_date) year,
    MONTH(c_payments.payment_date) month,
    (CASE
    WHEN c_payments.payment_type IN ('Normal','Deposit','Extend Balance') THEN 'collected'
    WHEN c_payments.payment_type IN ('Transfer in', 'FreeBalance') THEN 'tranfer_in'
    WHEN c_payments.payment_type IN ('Moving in') THEN 'moving_in'
    ELSE c_payments.payment_type
    END) as payment_type_group,
    IFNULL(SUM(c_payments.payment_amount), 0) total
    FROM
    c_payments
    INNER JOIN
    teams l1 ON c_payments.team_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    c_invoices_c_payments_1_c l3_1 ON c_payments.id = l3_1.c_invoices_c_payments_1c_payments_idb
    AND l3_1.deleted = 0
    INNER JOIN
    c_invoices l3 ON l3.id = l3_1.c_invoices_c_payments_1c_invoices_ida
    AND l3.deleted = 0
    INNER JOIN
    c_invoices_opportunities_1_c l4_1 ON l3.id = l4_1.c_invoices_opportunities_1c_invoices_ida
    AND l4_1.deleted = 0
    INNER JOIN
    opportunities l4 ON l4.id = l4_1.c_invoices_opportunities_1opportunities_idb
    AND l4.deleted = 0
    WHERE
    ((((c_payments.payment_date >= '$start'
    AND c_payments.payment_date <= '$end'))
    AND (c_payments.status = 'Paid')
    AND (l1.id = '$team_id')
    AND (c_payments.payment_type IN ('Normal','Deposit','Extend Balance', 'Moving in',  'Transfer in', 'FreeBalance'))
    AND (c_payments.remain = 0)))
    AND c_payments.deleted = 0
    GROUP BY enrollment_id, year, month, payment_type_group";
    $rs = $GLOBALS['db']->query($q1);
    $data = array();
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        if($row['month'] <= 9) $row['month'] = '0'.$row['month'];
        $data[$row['enrollment_id']][$row['year']][$row['month']][$row['payment_type_group']] = $row['total'];
    }
    return $data;
}

function getTotalPayOut($team_id, $start, $end){
    $q1 = "SELECT DISTINCT
    IFNULL(l1.id, '') enrollment_id,
    YEAR(c_refunds.refund_date) year,
    MONTH(c_refunds.refund_date) month,
    (CASE
    WHEN c_refunds.refund_type IN ('Moving out') THEN 'moving_out'
    WHEN c_refunds.refund_type IN ('Transfer out', 'Transfer Enrollment') THEN 'tranfer_out'
    WHEN c_refunds.refund_type IN ('Normal') THEN 'refund'
    ELSE c_refunds.refund_type
    END) as refund_type_group,
    IFNULL(SUM(c_refunds.refund_amount), 0) total
    FROM
    c_refunds
    INNER JOIN
    teams l4 ON c_refunds.team_id = l4.id
    AND l4.deleted = 0
    INNER JOIN
    opportunities_c_refunds_1_c l1_1 ON c_refunds.id = l1_1.opportunities_c_refunds_1c_refunds_idb
    AND l1_1.deleted = 0
    INNER JOIN
    opportunities l1 ON l1.id = l1_1.opportunities_c_refunds_1opportunities_ida
    AND l1.deleted = 0
    WHERE
    (((c_refunds.refund_type IN ('Moving out',  'Transfer out', 'Transfer Enrollment', 'Normal'))
    AND ((c_refunds.refund_date >= '$start'
    AND c_refunds.refund_date <= '$end'))
    AND (l4.id = '$team_id')))
    AND c_refunds.deleted = 0
    GROUP BY enrollment_id, year, month, refund_type_group";
    $rs = $GLOBALS['db']->query($q1);
    $data = array();
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        if($row['month'] <= 9) $row['month'] = '0'.$row['month'];
        $data[$row['enrollment_id']][$row['year']][$row['month']][$row['refund_type_group']] = $row['total'];
    }
    return $data;
}

function getDeliveryRevenue($start, $end, $cr_user_id){
    $q1 = "SELECT DISTINCT
    IFNULL(l1.id, '') enrollment_id,
    YEAR(c_deliveryrevenue.date_input) year,
    MONTH(c_deliveryrevenue.date_input) month,
    IFNULL(SUM(c_deliveryrevenue.amount), 0) amount,
    IFNULL(SUM(c_deliveryrevenue.duration), 0) hours
    FROM
    c_deliveryrevenue
    INNER JOIN
    opportunities l1 ON c_deliveryrevenue.enrollment_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    users l2 ON c_deliveryrevenue.created_by = l2.id
    AND l2.deleted = 0
    WHERE
    ((((((c_deliveryrevenue.passed IS NULL
    OR c_deliveryrevenue.passed = '0'))
    OR (l2.id = '$cr_user_id')))
    AND ((((c_deliveryrevenue.date_input >= '$start'
    AND c_deliveryrevenue.date_input <= '$end'))))))
    AND c_deliveryrevenue.deleted = 0
    GROUP BY enrollment_id, year, month";
    $rs = $GLOBALS['db']->query($q1);
    $data = array();
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        if($row['month'] <= 9) $row['month'] = '0'.$row['month'];
        $data[$row['enrollment_id']][$row['year']][$row['month']]['amount']  = $row['amount'];
        $data[$row['enrollment_id']][$row['year']][$row['month']]['hours']   = $row['hours'];
    }
    return $data;
}

function getDepositCF($team_id){
    $qu3 = "SELECT DISTINCT
    IFNULL(c_payments.id, '') payment_id,
    IFNULL(c_payments.name, '') c_payments_name,
    c_payments.payment_amount payment_amount,
    IFNULL(l2.id, '') student_id,
    CONCAT(IFNULL(l2.last_name, ''),
    ' ',
    IFNULL(l2.first_name, '')) full_student_name,
    c_payments.payment_date payment_date,
    c_payments.remain c_payments_remain,
    IFNULL(l3.id, '') l3_id,
    IFNULL(l3.name, '') l3_name
    FROM
    c_payments
    LEFT JOIN
    c_invoices_c_payments_1_c l1_1 ON c_payments.id = l1_1.c_invoices_c_payments_1c_payments_idb
    AND l1_1.deleted = 0
    LEFT JOIN
    c_invoices l1 ON l1.id = l1_1.c_invoices_c_payments_1c_invoices_ida
    AND l1.deleted = 0
    LEFT JOIN
    contacts_c_payments_1_c l2_1 ON c_payments.id = l2_1.contacts_c_payments_1c_payments_idb
    AND l2_1.deleted = 0
    LEFT JOIN
    contacts l2 ON l2.id = l2_1.contacts_c_payments_1contacts_ida
    AND l2.deleted = 0
    INNER JOIN
    teams l3 ON c_payments.team_id = l3.id
    AND l3.deleted = 0
    WHERE
    (((c_payments.payment_type = 'Deposit')
    AND (c_payments.status = 'Paid')
    AND ((l1.id IS NULL OR l1.id = ''))
    AND ((COALESCE(LENGTH(l2.id), 0) > 0))
    AND (c_payments.remain > 0)
    AND (l3.id = '$team_id')))
    AND c_payments.deleted = 0";

    return $GLOBALS['db']->fetchArray($qu3);
}

function getCorpCF($team_id){
    $qu1 = "SELECT
    IFNULL(contacts.id, '') student_id,
    CONCAT(IFNULL(contacts.last_name, ''),
    ' ',
    IFNULL(contacts.first_name, '')) full_student_name,
    IFNULL(l1.id, '') contract_id,
    IFNULL(l1.amount_per_student, 0) amount_per_student,
    IFNULL(l1.hours_per_student, 0) hours_per_student,
    l1.date_entered l1_date_entered,
    l1.customer_signed_date l1_customer_signed_date,
    l1.kind_of_course kind_of_course,
    IFNULL(l3.name, '') contract_type,
    IFNULL(l2.id, '') contract_team_id
    FROM
    contacts
    INNER JOIN
    contracts_contacts l1_1 ON contacts.id = l1_1.contact_id
    AND l1_1.deleted = 0
    INNER JOIN
    contracts l1 ON l1.id = l1_1.contract_id
    AND l1.deleted = 0
    INNER JOIN
    teams l2 ON l1.team_id = l2.id AND l2.deleted = 0
    LEFT JOIN
    contract_types l3 ON l3.id = l1.type AND l3.deleted = 0
    WHERE
    ((((COALESCE(LENGTH(contacts.id), 0) > 0))))
    AND l2.id = '$team_id'
    AND contacts.deleted = 0";

    return $GLOBALS['db']->fetchArray($qu1);
}


function getTotalPayOutCorp($start, $end){
    $q1 = "SELECT DISTINCT
    IFNULL(contracts.from_contract_id, '') from_contract_id,
    IFNULL(contracts.drop_student_id, '') drop_student_id,
    YEAR(contracts.closed_date) year,
    MONTH(contracts.closed_date) month,
    IFNULL(SUM(contracts.amount_per_student), 0) amount_per_student
    FROM contracts
    WHERE
    (contracts.closed_date >= '$start'
    AND contracts.closed_date <= '$end')
    AND deleted = 0
    GROUP BY from_contract_id, drop_student_id, year, month";
    $rs = $GLOBALS['db']->query($q1);
    $data = array();
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        if($row['month'] <= 9) $row['month'] = '0'.$row['month'];
        $data[$row['from_contract_id']][$row['drop_student_id']][$row['year']][$row['month']] = $row['amount_per_student'];
    }
    return $data;
}

function getDeliveryRevenueCorp($start, $end, $cr_user_id){
    $q1 = "SELECT DISTINCT
    IFNULL(l3.id, '') contract_id,
    IFNULL(l2.id, '') student_id,
    YEAR(c_deliveryrevenue.date_input) year,
    MONTH(c_deliveryrevenue.date_input) month,
    IFNULL(SUM(c_deliveryrevenue.amount), 0) amount,
    IFNULL(SUM(c_deliveryrevenue.duration), 0) hours
    FROM
    c_deliveryrevenue
    INNER JOIN
    users l1 ON c_deliveryrevenue.created_by = l1.id
    AND l1.deleted = 0
    INNER JOIN
    contacts l2 ON c_deliveryrevenue.student_id = l2.id
    AND l2.deleted = 0
    INNER JOIN
    contracts l3 ON c_deliveryrevenue.contract_id = l3.id
    AND l3.deleted = 0
    WHERE
    (((l1.id = '$cr_user_id')
    AND ((c_deliveryrevenue.date_input >= '$start'
    AND c_deliveryrevenue.date_input <= '$end'))))
    AND c_deliveryrevenue.deleted = 0
    AND (c_deliveryrevenue.enrollment_id IS NULL OR c_deliveryrevenue.enrollment_id = '')
    GROUP BY contract_id, student_id, year, month";
    $rs = $GLOBALS['db']->query($q1);
    $data = array();
    while($row = $GLOBALS['db']->fetchbyAssoc($rs)){
        if($row['month'] <= 9) $row['month'] = '0'.$row['month'];
        $data[$row['contract_id']][$row['student_id']][$row['year']][$row['month']]['amount']  = $row['amount'];
        $data[$row['contract_id']][$row['student_id']][$row['year']][$row['month']]['hours']   = $row['hours'];
    }
    return $data;

}

?>
