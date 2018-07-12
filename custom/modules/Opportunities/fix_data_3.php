<?php
require_once("custom/include/_helper/junior_class_utils.php");

$q5 = "SELECT DISTINCT
IFNULL(l1.id, '') student_id,
IFNULL(l2.id, '') payment_id,
IFNULL(c_deliveryrevenue.id, '') revenue_id,
c_deliveryrevenue.amount amount,
c_deliveryrevenue.date_input date_input,
c_deliveryrevenue.duration duration,
IFNULL(l3.id, '') team_id,
IFNULL(c_deliveryrevenue.description, '') description,
IFNULL(l4.id, '') assigned_to
FROM
c_deliveryrevenue
INNER JOIN
contacts l1 ON c_deliveryrevenue.student_id = l1.id
AND l1.deleted = 0
INNER JOIN
j_payment l2 ON c_deliveryrevenue.ju_payment_id = l2.id
AND l2.deleted = 0
INNER JOIN
teams l3 ON c_deliveryrevenue.team_id = l3.id
AND l3.deleted = 0
INNER JOIN
users l4 ON c_deliveryrevenue.assigned_user_id = l4.id
AND l4.deleted = 0
WHERE
(((c_deliveryrevenue.id IN ('c213f608-3c1a-ee89-7271-59522a2efc16'))))
AND c_deliveryrevenue.deleted = 0";
$rs5 = $GLOBALS['db']->query($q5);
$count = 0;
while($row = $GLOBALS['db']->fetchByAssoc($rs5)){
    $count++;
    $pm_delay                   = new J_Payment();
    $pm_delay->contacts_j_payment_1contacts_ida = $row['student_id'];
    $pm_delay->payment_type     = 'Delay';
    $pm_delay->use_type         = "Hour";

    $pm_delay->payment_date     = $row['date_input'];
    $pm_delay->payment_expired  = date('Y-m-d', strtotime("+6 months " . $timedate->to_db_date($row['date_input'], false)));
    $pm_delay->payment_amount   = format_number($row['amount']);
    $pm_delay->remain_amount    = format_number($row['amount']);
    $pm_delay->tuition_hours    = format_number($row['duration'], 2, 2);
    $pm_delay->total_hours      = format_number($row['duration'], 2, 2);
    $pm_delay->remain_hours     = format_number($row['duration'], 2, 2);
    $pm_delay->used_hours       = 0;
    $pm_delay->used_amount      = 0;
    $pm_delay->description      = $row['description'];
    $pm_delay->assigned_user_id = $GLOBALS['current_user']->id;
    $pm_delay->team_id          = $row['team_id'];
    $pm_delay->team_set_id      = $row['team_id'];
    $pm_delay->save();
    addRelatedPayment($pm_delay->id, $row['payment_id'], $row['amount'], $row['duration']);

    $GLOBALS['db']->query("UPDATE c_deliveryrevenue SET deleted='1' WHERE id='{$row['revenue_id']}'");
}
echo "$count Revenue Updated !!";
//

