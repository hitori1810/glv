<?php
if(!isset($_POST['record']) || empty($_POST['record'])){
    echo "Please, Select filter options and click Apply to show this report. ";
    $sv = new SugarView();
    $sv->displayFooter();
    die();
}
//require_once("custom/include/_helper/junior_revenue_utils.php");
require_once("custom/modules/C_Carryforward/helper/helper_adult_2016.php");

$filter = str_replace(' ','',$this->where);
$parts  = explode("AND", $filter);

for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "l1.id=") !== FALSE)                  $team_id    = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l2.id") !== FALSE)                   $student_id = get_string_between($parts[$i]);
    if(strpos($parts[$i], "c_carryforward.month=") !== FALSE)   $month      = get_string_between($parts[$i]);
    if(strpos($parts[$i], "c_carryforward.year=") !== FALSE)    $year       = get_string_between($parts[$i]);
}
if(strlen($student_id) < 36) $student_id = '';

$cr_user_id = $GLOBALS['current_user']->id;
//if(!empty($team_id) && !empty($month) && !empty($year)){
//    $GLOBALS['db']->query("DELETE FROM c_carryforward WHERE created_by = '$cr_user_id' AND passed <> 0");  //Xóa dữ liệu cũ
//}

//$start      = '2012-01-01';// Nhớ sửa lại khi sau test nhé 2012-01-01
$start      = get_first_payment_date($team_id, $student_id);
$start_run  = strtotime($start);
$start_cf     = date('Y-m-01',strtotime("$year-$month-01"));
$start_run_cf  = strtotime($start_cf);
$end        = date('Y-m-t',strtotime("$year-$month-01")); //Last date of filter mounth
$end_run    = strtotime($end);

$html = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody><tr>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">No</th>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Type</th>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Kind Of Course</th>';
//$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Level</th>';
//$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Class Code</th>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Student Code</th>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Student Name</th>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Invoice No</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Beginning balance</th>';
$html .= '<th colspan="5" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Collected in this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Collected Till this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Casholder/Delay - In</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Casholder/Delay - Out</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Revenue in this period</th>';
//$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Settle in this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Revenue Till this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Moving/Transfer In this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Moving/Transfer Out this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Refund In this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Ending balance</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Outstanding</th></tr>';

$html .= '<tr><th align="center" valign="middle" nowrap="">Total Days</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Total Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount Before Discount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Discount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Sponsor</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Payment Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Total Days</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Days</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Days</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
//$html .= '<th align="center"  valign="middle" nowrap="">Days</th>';
//$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Days</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Days</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Days</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Days</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Days</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Days</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Days</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Days</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th></tr>';
$count = 0;
//STUDENT:
$arr_student = array();

$row_course                 = getPaymentCFAdult($team_id, $student_id, $start_cf, $end);
$row_student_sponsor        = getSponsor100($team_id);

$payment_id = array();
$array_student_id = array();
foreach ($row_course as $key=>$value){
    $payment_id[] = $value['payment_id'];
    $array_student_id[] = $value['student_id'];
}
$payment_id_str = "'".implode("','",$payment_id)."'";
$str_student_id = "'".implode("','",array_unique($array_student_id))."'";
//$payment_id = '';

//$row_collected  = getCollected($team_id, $student_id, $start, $end);

$row_collected  = getCollectedAdult($payment_id_str,$start_cf, $end);

$row_revenue    = getRevenueAdult($team_id, $str_student_id, $payment_id_str, $start, $start_cf, $end);

//$row_settle     = getSettleAdult($team_id, $str_student_id, $payment_id_str, $start, $end);

//$row_cashin     = getCashIn($team_id, $student_id, $start, $end);

//$row_cashout    = getCashOut($team_id, $student_id, $start, $end);

//$row_eliminate  = getEliminateAdult($team_id, $str_student_id, $end);

$row_cashin     = getCashInAdult($payment_id_str, $start_cf, $end);

$row_cashout     = getCashOutAdult($payment_id_str, $start_cf,  $end);

//$row_eliminate     = getEliminate2($payment_id_str, $start, $end);

for($i = 0; $i < count($row_course); $i++){
    $carry_hrs      = 0;
    $carry_amount   = 0;
    $collected_amount_alloc     = 0;
    $collected_hrs_alloc        = 0;
    $revenue_amount_alloc       = 0;
    $revenue_hrs_alloc          = 0;

    $payment_id  = $row_course[$i]['payment_id'];
    $payment_crm_id = $row_course[$i]['crm_payment_id'];

    $revenue_this_amount = $row_revenue[$payment_id]['amount_this'];

    $revenue_this_days = $row_revenue[$payment_id]['days_this'];

    $revenue_till_this_amount = $row_revenue[$payment_id]['amount_till_this'];

    $revenue_till_this_days = $row_revenue[$payment_id]['days_till_this'];

    $invoice_number = $row_collected[$payment_id]['before_this']['invoice_number'];
    if(empty($invoice_number)){
        $invoice_number = $row_collected[$payment_id]['this']['invoice_number'];
    }

    $before_discount = (float)$row_collected[$payment_id]['this']['before_discount'];

    $discount_amount = (float)$row_collected[$payment_id]['this']['discount_amount'];

    $sponsor_amount = (float)$row_collected[$payment_id]['this']['sponsor_amount'];

    $collected_amount_this = (float)$row_collected[$payment_id]['this']['payment_amount'];

    $collected_amount_till_this = $collected_amount_this + (float)$row_collected[$payment_id]['before_this']['payment_amount'];

    $collected_days_till_this = (float)($collected_amount_till_this/$row_course[$i]['payment_price']);

    $collected_days_this = $collected_amount_this/$row_course[$i]['payment_price'];

    if($collected_days_till_this == 0 && $collected_days_this == 0 && $row_course[$i]['total_hours'] > 0 && $row_course[$i]['payment_amount'] == 0 && $row_course[$i]['deposit_amount'] == 0){
        if($row_course[$i]['payment_date'] < $start_cf){
            $collected_days_till_this = $row_course[$i]['total_hours'];
        }else{
            $collected_days_till_this = $row_course[$i]['total_hours'];
            $collected_days_this = $row_course[$i]['total_hours'];
        }
    }

    //delay-cash-in
    $cash_in_this_amount = (float)$row_cashin[$payment_id]['this']['delay_cash_in']['amount'];

    $cash_in_this_days   = (float)$row_cashin[$payment_id]['this']['delay_cash_in']['days'];

    if($cash_in_this_days == 0)
        $cash_in_this_days  = $cash_in_this_amount/$row_course[$i]['payment_price'];
    $cash_in_before_this_amount = (float)$row_cashin[$payment_id]['before_this']['delay_cash_in']['amount'];

    $cash_in_before_this_days  = (float)$row_cashin[$payment_id]['before_this']['delay_cash_in']['days'];

    if(empty($cash_in_before_this_days))
        $cash_in_before_this_days  = $cash_in_before_this_amount/$row_course[$i]['payment_price'];
    //delay-cash-out
    $cash_out_this_amount = (float)$row_cashout[$payment_id]['this']['delay_cash_out']['amount'];

    $cash_out_this_days  = (float)$row_cashout[$payment_id]['this']['delay_cash_out']['days'];

    if(empty($cash_out_this_days))
        $cash_out_this_days  = $cash_out_this_amount/$row_course[$i]['payment_price'];

    $cash_out_before_this_amount = (float)$row_cashout[$payment_id]['before_this']['delay_cash_out']['amount'];

    $cash_out_before_this_days  = (float)$row_cashout[$payment_id]['before_this']['delay_cash_out']['days'];

    if(empty($cash_out_before_this_days))
        $cash_out_before_this_days  = $cash_out_before_this_amount/$row_course[$i]['payment_price'];
    //moving-transfer-in
    $mv_tf_in_this_amount = (float)$row_cashin[$payment_id]['this']['moving_transfer_in']['amount'];

    $mv_tf_in_this_days = (float)$row_cashin[$payment_id]['this']['moving_transfer_in']['days'];

    $mv_tf_in_before_this_amount = (float)$row_cashin[$payment_id]['before_this']['moving_transfer_in']['amount'];

    $mv_tf_in_before_this_days = (float)$row_cashin[$payment_id]['before_this']['moving_transfer_in']['days'];

    $mv_tf_out_this_amount = (float)$row_cashout[$payment_id]['this']['moving_transfer_out']['amount'];

    $mv_tf_out_this_days = $row_cashout[$payment_id]['this']['moving_transfer_out']['days'];

    $mv_tf_out_before_this_amount = (float)$row_cashout[$payment_id]['before_this']['moving_transfer_out']['amount'];

    $mv_tf_out_before_this_days = (float)$row_cashout[$payment_id]['before_this']['moving_transfer_out']['days'];

    //bổ sung transfer In
    $mv_tf = array(
        0 => 'Transfer In',
        1 => 'Moving In');
    if(in_array($row_course[$i]['payment_type'], $mv_tf)) {
        if(strtotime($start_cf) > strtotime($row_course[$i]['payment_date'])){
            $mv_tf_in_before_this_amount = $row_course[$i]['payment_amount'];
        }elseif($end >= $row_course[$i]['payment_date']){
            $mv_tf_in_this_amount =    $row_course[$i]['payment_amount'];
        }else{
            $mv_tf_in_before_this_amount = 0;
            $mv_tf_in_this_amount = 0;
            $mv_tf_out_before_this_amount = 0;
            $mv_tf_out_this_amount = 0;
        }
        $mv_tf_out_before_this_amount = $cash_out_before_this_amount;
        $mv_tf_out_before_this_days = $cash_out_before_this_days;
        $cash_out_before_this_amount = 0;
        $cash_out_before_this_days = 0;

        $mv_tf_out_this_amount =    $cash_out_this_amount;
        $mv_tf_out_this_days =    $cash_out_this_days;
        $cash_out_this_amount = 0;
        $cash_out_this_days = 0;
    }


    //tinh so ngay transfer

    if(empty($mv_tf_in_this_days))
        $mv_tf_in_this_days  = $mv_tf_in_this_amount/$row_course[$i]['payment_price'];

    if(empty($mv_tf_in_before_this_days))
        $mv_tf_in_before_this_days  = $mv_tf_in_before_this_amount/$row_course[$i]['payment_price'];

    //moving-transfer-out

    if(empty($mv_tf_out_this_days)){
        $mv_tf_out_this_days = $mv_tf_out_this_amount/$row_course[$i]['payment_price'];
    }
    if(empty($mv_tf_out_before_this_days)){
        $mv_tf_out_before_this_days = $mv_tf_out_before_this_amount/$row_course[$i]['payment_price'];
    }

    $refund_this_amount = (float)$row_cashout[$payment_id]['this']['refund']['amount'];

    $refund_this_days = (float)$row_cashout[$payment_id]['this']['refund']['days'];
    if(empty($refund_this_days)){
        $refund_this_days = $refund_this_amount/$row_course[$i]['payment_price'];
    }

    $refund_before_this_amount = (float)$row_cashout[$payment_id]['before_this']['refund']['amount'];

    $refund_before_this_days = (float)$row_cashout[$payment_id]['before_this']['refund']['days'];
    if(empty($refund_before_this_days)){
        $refund_before_this_days = $refund_before_this_amount/$row_course[$i]['payment_price'];
    }
    /*if($row_course[$i]['payment_amount']==0 && $row_course[$i]['deposit_amount']==0 && $row_course[$i]['paid_amount']==0 && $row_course[$i]['tuition_hours']>0 && $row_course[$i]['paid_hours']== 0){
        //        $beginning_days = $row_course[$i]['tuition_hours'] - $revenue_till_this_days + $revenue_this_days;
        $collected_days_till_this = $row_course[$i]['tuition_hours'];
        if($collected_days_till_this > 0)  $collected_days_this = $row_course[$i]['tuition_hours'];
    }*/
    if($row_course[$i]['payment_type'] == 'Delay' && strlen($payment_crm_id) == 36){
        if(strtotime($row_course[$i]['payment_date'])< strtotime($start_cf)){
            $mv_tf_in_before_this_amount =    $row_course[$i]['payment_amount'];
            $mv_tf_in_before_this_days = $row_course[$i]['tuition_hours'];
        }  else {
            $mv_tf_in_this_amount = $row_course[$i]['payment_amount'];
            $mv_tf_in_this_days = $row_course[$i]['tuition_hours'];
        }

    }

    $beginning_amount = $collected_amount_till_this - $collected_amount_this - $revenue_till_this_amount + $revenue_this_amount + $mv_tf_in_before_this_amount - $mv_tf_out_before_this_amount + $cash_in_before_this_amount - $cash_out_before_this_amount - $refund_before_this_amount;

    $beginning_days = $collected_days_till_this - $collected_days_this - $revenue_till_this_days + $revenue_this_days + $mv_tf_in_before_this_days - $mv_tf_out_before_this_days + $cash_in_before_this_days - $cash_out_before_this_days - $refund_before_this_days;


    //    var_dump($row_course);
    //    var_dump($row_course[$i]['payment_amount']);
    // var_dump($row_course[$i]['deposit_amount']);
    //    var_dump($row_course[$i]['paid_amount']);
    //    var_dump($row_course[$i]['tuition_hours']);
    //    var_dump($row_course[$i]['paid_hours']);
    //add payment delay tu phan he cu


    $carry_amount = $beginning_amount + $collected_amount_this - $revenue_this_amount + $mv_tf_in_this_amount - $mv_tf_out_this_amount + $cash_in_this_amount - $cash_out_this_amount - $refund_this_amount;

    $carry_days = $beginning_days + $collected_days_this - $revenue_this_days + $mv_tf_in_this_days - $mv_tf_out_this_days + $cash_in_this_days - $cash_out_this_days - $refund_this_days;

    $cr_i = $i;
    //DELAY -> GRADEBOOK

    //Lam tron so
    if(abs($carry_amount) < 1000 && abs($carry_days) < '0.5'){
        $carry_amount = 0;
        $carry_days  = 0;
    } elseif(!empty($payment_aims_id) && $carry_amount > -1000 && $carry_days < '0.5'){
        $carry_days  = 0;
    }
    if(abs($beginning_amount) < 1000 && abs($beginning_days) < '0.5'){
        $beginning_amount = 0;
        $beginning_days  = 0;
    }

    //outstanding amount
    $out_standing     = 0;
    $out_standing_days = 0;
    $carry_amount_temp  = $carry_amount;
    $carry_days_temp = $carry_days;

    if($carry_amount < 0){
        $out_standing = abs($carry_amount);
        $carry_amount_temp = 0;
    }
    //    if($carry_hrs != 0 && $carry_amount == 0){   // Fix bug học Free Of Charge ko vào CF
    //        $carry_hrs = 0;
    //    }

    //outstanding hour
    if($carry_days < 0){
        $out_standing_days = abs($carry_days);
        $carry_days_temp   = 0;
        //        //Fix bug Outstanding 1 day
        //        $pay_start_db   = $row_course[$cr_i]['start_study'];
        //        $run_remain     = $row_course[$cr_i]['tuition_hours'];
        //        if(!empty($pay_start_db))
        //            $pay_end_db = cal_finish_date_adult($pay_start_db, $run_remain);
        //
        //        if(!empty($pay_end_db))
        //            $GLOBALS['db']->query("UPDATE j_payment SET end_study='$pay_end_db' WHERE id = '{$row_course[$cr_i]['payment_id']}'");
        //
        //

    }
    if($carry_amount != 0 || $carry_days != 0 || $beginning_amount != 0 || $collected_amount_this != 0 || $mv_tf_in_this_amount != 0 || $mv_tf_out_this_amount != 0 || $cash_in_this_amount != 0 || $cash_out_this_amount != 0 || $refund_this_amount != 0){
        $count++;
        $html .= "<tr><td valign='TOP' class='oddListRowS1'>$count</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=J_Payment&action=DetailView&record={$row_course[$cr_i]['payment_id']}' target='_blank'>{$row_course[$cr_i]['payment_type']}</a></td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>{$row_course[$cr_i]['kind_of_course_string']}</td>";
        //        $html .= "<td valign='TOP' class='oddListRowS1'>{$row_course[$cr_i]['level_string']}</td>";
        //        $html .= "<td valign='TOP' class='oddListRowS1'>{$row_course[$cr_i]['class_string']}</td>";

        if(!empty($row_course[$cr_i]['aims_code']))
            $html .= "<td valign='TOP' class='oddListRowS1'></td>";
        else
            $html .= "<td valign='TOP' class='oddListRowS1'>{$row_course[$cr_i]['student_code']}</td>";

        $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=Contacts&action=DetailView&record={$row_course[$cr_i]['student_id']}' target='_blank'>{$row_course[$cr_i]['student_name']}</a> </td>";
        $html .= "<td valign='TOP' class='oddListRowS1' style=\"mso-number-format:\@;\" >$invoice_number</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($beginning_days,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($beginning_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($before_discount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($discount_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($sponsor_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($collected_amount_this)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($collected_days_this,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($collected_days_till_this,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($collected_amount_till_this)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($cash_in_this_days,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($cash_in_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($cash_out_this_days,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($cash_out_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($revenue_this_days,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($revenue_this_amount)."</td>";
        //        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($settle_hrs,2,2)."</td>";
        //        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($settle_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($revenue_till_this_days,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($revenue_till_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($mv_tf_in_this_days,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($mv_tf_in_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($mv_tf_out_this_days,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($mv_tf_out_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($refund_this_days,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($refund_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($carry_days_temp,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($carry_amount_temp)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($out_standing_days,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($out_standing)."</td></tr>";
        $beginning_hrst +=$beginning_days;
        $beginning_amountt +=$beginning_amount;
        $before_discountt +=$before_discount;
        $discount_amountt +=$discount_amount;
        $sponsor_amountt +=$sponsor_amount;
        $collected_amountt +=$collected_amount_this;
        $collected_hrst +=$collected_days_this;
        $collected_hrs_alloct +=$collected_days_till_this;
        $collected_amount_alloct +=$collected_amount_till_this;
        $cash_in_hrst +=$cash_in_this_days;
        $cash_in_amountt +=$cash_in_this_amount;
        $cash_out_hrst +=$cash_out_this_days;
        $cash_out_amountt +=$cash_out_this_amount;
        $revenue_hrst +=$revenue_this_days;
        $revenue_amountt +=$revenue_this_amount;
        $revenue_hrs_alloct +=$revenue_till_this_days;
        $revenue_amount_alloct +=$revenue_till_this_amount;
        $mv_tf_in_hrst +=$mv_tf_in_this_days;
        $mv_tf_in_amountt +=$mv_tf_in_this_amount;
        $mv_tf_out_hrst +=$mv_tf_out_this_days;
        $mv_tf_out_amountt +=$mv_tf_out_this_amount;
        $refund_hrst +=$refund_this_days;
        $refund_amountt +=$refund_this_amount;
        $carry_hrs_tempt +=$carry_days_temp;
        $carry_amount_tempt +=$carry_amount_temp;
        $out_standing_dayst +=$out_standing_days;
        $out_standingt +=$out_standing;
        //Tinh bao cao cho Bac Hung
        $arr_student[$row_course[$i]['student_id']]['count']  += 1;
        if( ($out_standing + $carry_amount_temp) == 0)
            $arr_student[$row_course[$i]['student_id']]['count_zero']  += 1;   //Student Run
    }
}
$html .= "<tr>
<td colspan='6'><h3><span>Grand Total</span></h3></td>
<td><b>".format_number($beginning_hrst,2,2)."</b></td>
<td><b>".format_number($beginning_amountt)."</b></td>
<td><b>".format_number($before_discountt)."</b></td>
<td><b>".format_number($discount_amountt)."</b></td>
<td><b>".format_number($sponsor_amountt)."</b></td>
<td><b>".format_number($collected_amountt)."</b></td>
<td><b>".format_number($collected_hrst,2,2)."</b></td>
<td><b>".format_number($collected_hrs_alloct,2,2)."</b></td>
<td><b>".format_number($collected_amount_alloct)."</b></td>
<td><b>".format_number($cash_in_hrst,2,2)."</b></td>
<td><b>".format_number($cash_in_amountt)."</b></td>
<td><b>".format_number($cash_out_hrst,2,2)."</b></td>
<td><b>".format_number($cash_out_amountt)."</b></td>
<td><b>".format_number($revenue_hrst,2,2)."</b></td>
<td><b>".format_number($revenue_amountt)."</b></td>
<td><b>".format_number($revenue_hrs_alloct,2,2)."</b></td>
<td><b>".format_number($revenue_amount_alloct)."</b></td>
<td><b>".format_number($mv_tf_in_hrst,2,2)."</b></td>
<td><b>".format_number($mv_tf_in_amountt)."</b></td>
<td><b>".format_number($mv_tf_out_hrst,2,2)."</b></td>
<td><b>".format_number($mv_tf_out_amountt)."</b></td>
<td><b>".format_number($refund_hrst,2,2)."</b></td>
<td><b>".format_number($refund_amountt)."</b></td>
<td><b>".format_number($carry_hrs_tempt,2,2)."</b></td>
<td><b>".format_number($carry_amount_tempt)."</b></td>
<td><b>".format_number($out_standing_dayst,2,2)."</b></td>
<td><b>".format_number($out_standingt)."</b></td>
</tr>
</tbody></table>";
$html .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="formHeader h3Row">
<tbody><tr>
<td nowrap=""></td><td width="100%"><img height="1" width="1" src="themes/default/images/blank.gif" alt=""></td></tr>
</tbody></table>';

echo $html;
//For
$count_left = 0;
$count_student = 0;
foreach($arr_student as $key=>$value){
    if( $value['count'] == $value['count_zero'] && !in_array($key, $row_student_sponsor)){
        $count_left++;
        //         echo "'$key',<br>";
    }
    $count_student++;
}
echo 'Total Students:'.$count_student.'<br><br>';
echo 'Left Students:'.$count_left.'<br><br>';

?>