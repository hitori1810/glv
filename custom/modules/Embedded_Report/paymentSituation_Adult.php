<?php
require_once("custom/include/_helper/junior_schedule.php");
$pm_ids = array();
$rows = array();

$rs1 = $GLOBALS['db']->query($this->query);
while($row1 = $GLOBALS['db']->fetchByAssoc($rs1)){
    $rows[$row1['primaryid']]['id']           = $row1['primaryid'];
    $rows[$row1['primaryid']]['start_study']  = $row1['j_payment_start_study'];
    $rows[$row1['primaryid']]['end_study']    = $row1['j_payment_end_study'];
    array_push($pm_ids , $row1['primaryid']);
}

$q2 = "SELECT DISTINCT
IFNULL(j_payment.id, '') primaryid,
j_payment.deposit_amount deposit_amount,
j_payment.paid_amount paid_amount,
j_payment.paid_hours paid_hours,
j_payment.tuition_hours tuition_hours,
j_payment.tuition_fee tuition_fee,
j_payment.payment_amount payment_amount,
j_payment.remain_amount remain_amount,
j_payment.remain_hours remain_hours
FROM
j_payment
WHERE
(((j_payment.id IN ('".implode("','",$pm_ids)."'))))
AND j_payment.deleted = 0";

$rs2 = $GLOBALS['db']->query($q2);
while($row2 = $GLOBALS['db']->fetchByAssoc($rs2)){
    $rows[$row2['primaryid']]['deposit_amount']    = $row2['deposit_amount'];
    $rows[$row2['primaryid']]['paid_amount']       = $row2['paid_amount'];
    $rows[$row2['primaryid']]['paid_hours']        = $row2['paid_hours'];
    $rows[$row2['primaryid']]['tuition_hours']     = $row2['tuition_hours'];
    $rows[$row2['primaryid']]['tuition_fee']       = $row2['tuition_fee'];
    $rows[$row2['primaryid']]['payment_amount']    = $row2['payment_amount'];
    $rows[$row2['primaryid']]['holiday_list']      = $row2['holiday_list'];
    $rows[$row2['primaryid']]['remain_amount']     = $row2['remain_amount'];
    $rows[$row2['primaryid']]['remain_hours']      = $row2['remain_hours'];
}


global $timedate;
$today          = $timedate->nowDbDate();
$holiday_list   = get_list_holidays_adult();

foreach( $rows as $key => $row){
    if(!empty($row['start_study']) && !empty($row['end_study'])){
        if($today < $row['start_study']){
            $remain_amount = ($row['paid_amount'] + $row['payment_amount'] + $row['deposit_amount']);
            $remain_hours  = $row['tuition_hours'];

        }elseif($today >= $row['start_study'] && $today <= $row['end_study']){
            $arr_range      = getDatesFromRange($row['start_study'], $today);

            $arr_learned    = array_diff($arr_range, $holiday_list);
            $count_learned  = count($arr_learned);
            $total_amount   = ($row['paid_amount'] + $row['payment_amount'] + $row['deposit_amount']);
            $remain_amount =  $total_amount - ($count_learned * ( $total_amount / $row['tuition_hours']));
            $remain_hours  = $row['tuition_hours'] - $count_learned;

        }elseif($today > $row['end_study']){
            $remain_amount = 0;
            $remain_hours  = 0;
        }
        if($remain_amount != $row['remain_amount'] || $remain_hours != $row['remain_hours'])
            $GLOBALS['db']->query("UPDATE j_payment SET remain_amount = '$remain_amount', remain_hours='$remain_hours' WHERE id = '{$row['id']}'");
    }
}

?>
