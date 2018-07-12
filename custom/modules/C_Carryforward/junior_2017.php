<?php
if(!isset($_POST['record']) || empty($_POST['record'])){
    echo "Please, Select filter options and click Apply to show this report. ";
    $sv = new SugarView();
    $sv->displayFooter();
    die();
}

require_once("custom/modules/C_Carryforward/helper/helper_junior_2017.php");

$filter = str_replace(' ','',$this->where);
$parts  = explode("AND", $filter);

for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "l1.id=") !== FALSE)                  $team_id    = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l1.idIN") !== FALSE)                 $team_id    = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "l2.id=") !== FALSE)                  $student_id = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l2.idIN") !== FALSE)                 $student_id = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "c_carryforward.month=") !== FALSE)   $month      = get_string_between($parts[$i]);
    if(strpos($parts[$i], "c_carryforward.year=") !== FALSE)    $year       = get_string_between($parts[$i]);
}
if(strlen($student_id) < 36) $student_id = '';

$cr_user_id = $GLOBALS['current_user']->id;

$first_date     = get_first_payment_date($team_id, $student_id);

$start     = date('Y-m-01',strtotime("$year-$month-01"));

$end       = date('Y-m-t',strtotime("$year-$month-01")); //Last date of filter mounth

$cr_year   = date('Y');


$html = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody><tr>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">No</th>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Type</th>';
//$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Payment Date</th>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Kind Of Course</th>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Level</th>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Class Code</th>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Student AIMS Code</th>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Student ID</th>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Student Name</th>';
$html .= '<th rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Invoice No</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Beginning balance</th>';
$html .= '<th colspan="5" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Collected in this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Collected Till this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Casholder/Delay - In</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Casholder/Delay - Out</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Revenue in this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Settle in this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Revenue Till this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Moving/Transfer In this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Moving/Transfer Out this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Refund In this period</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Ending balance</th>';
$html .= '<th colspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Outstanding</th></tr>';

$html .= '<tr><th align="center" valign="middle" nowrap="">Total Hrs</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Total Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount Before Discount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Discount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Sponsor</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Payment Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Total Hrs</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Hours</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Hours</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Hours</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Hours</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Hours</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Hours</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Hours</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Hours</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Hours</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Hours</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Hours</th>';
$html .= '<th align="center"  valign="middle" nowrap="">Amount</th></tr>';
$count = 0;
//STUDENT:
$arr_student = array();

$row_payment     = getPaymentCF($team_id, $student_id, $start, $end);

$payment_ids = array();
$student_ids = array();
foreach ($row_payment as $key=>$value){
    $payment_ids[] = $value['payment_id'];
    $student_ids[] = $value['student_id'];
}
$payment_id_str = "'".implode("','",$payment_ids)."'";
$str_student_id = "'".implode("','",array_unique($student_ids))."'";

$row_collected  = getCollected($payment_id_str, $start, $end);

$row_revenue    = getRevenue($team_id, $str_student_id, $payment_id_str, $first_date, $start, $end);

$row_settle     = getSettle($team_id, $str_student_id, $payment_id_str, $first_date, $start, $end);

$row_eliminate  = getEliminate($team_id, $str_student_id, $start, $end);

$row_cashin     = getCashIn($payment_id_str, $start, $end);

$row_cashout    = getCashOut($payment_id_str, $start,  $end);

for($i = 0; $i < count($row_payment); $i++){
    $carry_hours      = 0;
    $carry_amount   = 0;
    $collected_amount_alloc     = 0;
    $collected_hrs_alloc        = 0;
    $revenue_amount_alloc       = 0;
    $revenue_hrs_alloc          = 0;

    $payment_id                 = $row_payment[$i]['payment_id'];
    $payment_aims_id            = $row_payment[$i]['payment_aims_id'];

    $revenue_this_amount        = $row_revenue[$payment_id]['this']['amount'];
    $revenue_this_hours         = $row_revenue[$payment_id]['this']['hours'];
    $revenue_before_this_amount = $row_revenue[$payment_id]['before_this']['amount'];
    $revenue_before_this_hours  = $row_revenue[$payment_id]['before_this']['hours'];

    $settle_this_amount         = $row_settle[$payment_id]['this']['amount'];
    $settle_this_hours          = $row_settle[$payment_id]['this']['hours'];
    $settle_before_this_hours   = $row_settle[$payment_id]['before_this']['hours'];
    $settle_before_this_amount  = $row_settle[$payment_id]['before_this']['amount'];

    $invoice_number             = $row_collected[$payment_id]['before_this']['invoice_number'];
    if(empty($invoice_number)){
        $invoice_number = $row_collected[$payment_id]['this']['invoice_number'];
    }

    $before_discount        = (float)$row_collected[$payment_id]['this']['before_discount'];
    $discount_amount        = (float)$row_collected[$payment_id]['this']['discount_amount'];
    $sponsor_amount         = (float)$row_collected[$payment_id]['this']['sponsor_amount'];
    $collected_this_amount  = (float)$row_collected[$payment_id]['this']['payment_amount'];
    $collected_amount_till_this = $collected_this_amount + (float)$row_collected[$payment_id]['before_this']['payment_amount'];
    $collected_this_hours       = $collected_this_amount / $row_payment[$i]['payment_price'];
    $collected_hours_till_this  = $collected_amount_till_this / $row_payment[$i]['payment_price'];

    //delay-cash-in
    $cash_in_this_amount    = (float)$row_cashin[$payment_id]['this']['delay_cash_in']['amount'];
    $cash_in_this_hours     = (float)$row_cash_in[$payment_id]['this']['delay_cash_in']['hours'];
    if($cash_in_this_hours == 0)
        $cash_in_this_hours = $cash_in_this_amount/$row_payment[$i]['payment_price'];
    $cash_in_before_this_amount = (float)$row_cashin[$payment_id]['before_this']['delay_cash_in']['amount'];
    $cash_in_before_this_hours  = (float)$row_cash_in[$payment_id]['before_this']['delay_cash_in']['hours'];
    if(empty($cash_in_before_this_hours))
        $cash_in_before_this_hours  = $cash_in_before_this_amount/$row_payment[$i]['payment_price'];

    $cash_out_this_amount   = (float)$row_cashout[$payment_id]['this']['delay_cash_out']['amount'];
    $cash_out_this_hours    = (float)$row_cashout[$payment_id]['this']['delay_cash_out']['hours'];
    if(empty($cash_out_this_hours))
        $cash_out_this_hours= $cash_out_this_amount/$row_payment[$i]['payment_price'];
    $cash_out_before_this_amount    = (float)$row_cashout[$payment_id]['before_this']['delay_cash_out']['amount'];
    $cash_out_before_this_hours     = (float)$row_cashout[$payment_id]['before_this']['delay_cash_out']['hours'];
    if(empty($cash_out_before_this_hours))
        $cash_out_before_this_hours = $cash_out_before_this_amount/$row_payment[$i]['payment_price'];

    $mv_tf_in_this_amount   = (float)$row_cashin[$payment_id]['this']['moving_transfer_in']['amount'];
    $mv_tf_in_this_hours    = (float)$row_cashin[$payment_id]['this']['moving_transfer_in']['hours'];
    /*if(empty($mv_tf_in_this_hours))
    $mv_tf_in_this_hours =  $mv_tf_in_this_amount/$row_payment[$i]['payment_price'];*/
    $mv_tf_in_before_this_amount    = (float)$row_cashin[$payment_id]['before_this']['moving_transfer_in']['amount'];
    $mv_tf_in_before_this_hours     = (float)$row_cashin[$payment_id]['before_this']['moving_transfer_in']['hours'];
    /*if(empty($mv_tf_in_before_this_hours))
    $mv_tf_in_before_this_hours =  $mv_tf_in_before_this_amount/$row_payment[$i]['payment_price'];
    */
    //moving-transfer-out
    $mv_tf_out_this_amount          = (float)$row_cashout[$payment_id]['this']['moving_transfer_out']['amount'];
    $mv_tf_out_this_hours           = (float)$row_cashout[$payment_id]['this']['moving_transfer_out']['hours'];
    $mv_tf_out_before_this_amount   = (float)$row_cashout[$payment_id]['before_this']['moving_transfer_out']['amount'];
    $mv_tf_out_before_this_hours    = (float)$row_cashout[$payment_id]['before_this']['moving_transfer_out']['hours'];

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
        $mv_tf_out_before_this_hours    = $cash_out_before_this_hours;
        $cash_out_before_this_amount    = 0;
        $cash_out_before_this_hours     = 0;
        $mv_tf_out_this_amount          = $cash_out_this_amount;
        $mv_tf_out_this_hours           = $cash_out_this_hours;
        $cash_out_this_amount           = 0;
        $cash_out_this_hours            = 0;
    }
    if(empty($mv_tf_in_this_hours))
        $mv_tf_in_this_hours  = $mv_tf_in_this_amount/$row_payment[$i]['payment_price'];
    if(empty($mv_tf_in_before_this_hours))
        $mv_tf_in_before_this_hours  = $mv_tf_in_before_this_amount/$row_payment[$i]['payment_price'];
    if(empty($mv_tf_out_this_hours)){
        $mv_tf_out_this_hours = $mv_tf_out_this_amount/$row_payment[$i]['payment_price'];
    }
    if(empty($mv_tf_out_before_this_hours)){
        $mv_tf_out_before_this_hours = $mv_tf_out_before_this_amount/$row_payment[$i]['payment_price'];
    }


    $refund_this_amount         = (float)$row_cashout[$payment_id]['this']['refund']['amount'];
    $refund_this_hours          = (float)$row_cashout[$payment_id]['this']['refund']['hours'];
    $refund_before_this_amount  = (float)$row_cashout[$payment_id]['before_this']['refund']['amount'];
    $refund_before_this_hours   = (float)$row_cashout[$payment_id]['before_this']['refund']['hours'];

    $eleminate_this         = $row_eliminate[$payment_id]['this']['amount'];
    $eleminate_before_this  = $row_eliminate[$payment_id]['before_this']['amount'];

//    $eleminate_this         = 0;
//    $eleminate_before_this  = 0;

    if($row_payment[$i]['payment_amount'] ==0 && $row_payment[$i]['deposit_amount'] ==0 && $row_payment[$i]['paid_amount']==0 && $row_payment[$i]['tuition_hours']>0){
        $collected_hours_till_this = $row_payment[$i]['tuition_hours'];
        if($collected_hours_till_this > 0 && $row_payment[$i]['payment_date'] >= $start )  $collected_this_hours = $collected_hours_till_this;
    }

    $revenue_amount_alloc   = $revenue_this_amount + $revenue_before_this_amount + $settle_this_amount + $settle_before_this_amount;
    $revenue_hrs_alloc      = $revenue_this_hours + $revenue_before_this_hours + $settle_this_hours + $settle_before_this_hours;


    $beginning_hours    = $collected_hours_till_this - $collected_this_hours - $revenue_hrs_alloc +$settle_this_hours + $revenue_this_hours + $mv_tf_in_before_this_hours - $mv_tf_out_before_this_hours + $cash_in_before_this_hours - $cash_out_before_this_hours - $refund_before_this_hours;

    $beginning_amount   = $collected_amount_till_this - $collected_this_amount - $revenue_amount_alloc + $revenue_this_amount + $settle_this_amount + $mv_tf_in_before_this_amount - $mv_tf_out_before_this_amount + $cash_in_before_this_amount - $cash_out_before_this_amount - $refund_before_this_amount - $eleminate_before_this ;

    $carry_hours        = $beginning_hours + $collected_this_hours - $revenue_this_hours -$settle_this_hours  + $mv_tf_in_this_hours - $mv_tf_out_this_hours + $cash_in_this_hours - $cash_out_this_hours - $refund_this_hours;

    $carry_amount       = $beginning_amount + $collected_this_amount - $revenue_this_amount -$settle_this_amount + $mv_tf_in_this_amount - $mv_tf_out_this_amount + $cash_in_this_amount - $cash_out_this_amount - $refund_this_amount - $eleminate_this ;

    $cr_i = $i;

    //Lam tron so
    if(abs($carry_amount) < 1000 && abs($carry_hours) < '0.5'){
        $carry_amount = 0;
        $carry_hours = 0;
    }elseif(!empty($payment_aims_id)){
        if(abs($carry_amount) < 1000)      {
            $carry_hours = 0;
            $carry_amount = 0;
        }
    }
    if(abs($beginning_amount) < 1000 && abs($beginning_hours) < '0.5' ){
        $beginning_amount   = 0;
        $beginning_hours    = 0;
    }elseif(!empty($payment_aims_id)){
        if(abs($beginning_amount)< 1000){
            $beginning_amount = 0;
            $beginning_hours = 0;
        }
    }

    //outstanding amount
    $out_standing     = 0;
    $out_standing_hrs = 0;
    $carry_amount_temp= $carry_amount;
    $carry_hrs_temp   = $carry_hours;

    if($carry_amount < 0){
        $out_standing = abs($carry_amount);
        $carry_amount_temp = 0;
    }
    if($carry_hours < 0){
        $out_standing_hrs = abs($carry_hours);
        $carry_hrs_temp   = 0;
    }

//    //Test Add Wrong Data
//    if(!empty($payment_aims_id) && $carry_amount != $row_payment[$i]['remain_amount'] && $row_payment[$i]['remain_amount'] == 0 && $carry_amount > 0 && $row_payment[$i]['payment_type'] != 'Enrollment'){
//        $elemina = $carry_amount - $row_payment[$i]['remain_amount'];
//        $GLOBALS['db']->query("UPDATE j_payment SET aims_wrong_amount = $elemina WHERE id = '$payment_id'");
//    }

    if($carry_amount_temp != 0 || ($sponsor_amount == $before_discount && $sponsor_amount > 0 ) || $revenue_this_hours > 0 || $settle_this_hours > 0 || $cash_in_this_hours > 0 || $cash_out_this_hours > 0 || $beginning_amount > 0 || $collected_this_amount != 0 || $revenue_this_amount != 0 || $settle_this_amount != 0 ||  $mv_tf_in_this_amount != 0 || $mv_tf_out_this_amount != 0 || $cash_in_this_amount != 0 || $cash_out_this_amount != 0 || $refund_this_amount != 0 || ($out_standing >0 && empty($payment_aims_id))){
        $count++;
        $html .= "<tr><td valign='TOP' class='oddListRowS1'>$count</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=J_Payment&action=DetailView&record={$row_payment[$cr_i]['payment_id']}' target='_blank'>{$row_payment[$cr_i]['payment_type']}</a></td>";
    //    $html .= "<td valign='TOP' class='oddListRowS1'>{$row_payment[$cr_i]['payment_date']}</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>{$row_payment[$cr_i]['kind_of_course_string']}</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".str_replace("^","",$row_payment[$cr_i]['level_string'])."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>{$row_payment[$cr_i]['class_string']}</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'></td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>{$row_payment[$cr_i]['student_code']}</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=Contacts&action=DetailView&record={$row_payment[$cr_i]['student_id']}' target='_blank'>{$row_payment[$cr_i]['student_name']}</a> </td>";
        $html .= "<td valign='TOP' class='oddListRowS1' style=\"mso-number-format:\@;\" >$invoice_number</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($beginning_hours,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($beginning_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($before_discount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($discount_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($sponsor_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($collected_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($collected_this_hours,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($collected_hours_till_this,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($collected_amount_till_this)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($cash_in_this_hours,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($cash_in_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($cash_out_this_hours,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($cash_out_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($revenue_this_hours,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($revenue_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($settle_this_hours,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($settle_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($revenue_hrs_alloc,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($revenue_amount_alloc)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($mv_tf_in_this_hours,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($mv_tf_in_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($mv_tf_out_this_hours,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($mv_tf_out_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($refund_this_hours,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($refund_this_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($carry_hrs_temp,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($carry_amount_temp)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($out_standing_hrs,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($out_standing)."</td></tr>";
        $beginning_hrst         += round($beginning_hours,2);
        $beginning_amountt      += round($beginning_amount);
        $before_discountt       += round($before_discount);
        $discount_amountt       += round($discount_amount);
        $sponsor_amountt        += round($sponsor_amount);
        $collected_amountt      += round($collected_this_amount);
        $collected_hrst         += round($collected_this_hours,2);
        $collected_hrs_alloct   += round($collected_hours_till_this,2);
        $collected_amount_alloct+= round($collected_amount_till_this);
        $cash_in_amountt        += round($cash_in_this_amount);
        $cash_in_hrst           += round($cash_in_this_hours,2);
        $cash_out_hrst          += round($cash_out_this_hours,2);
        $cash_out_amountt       += round($cash_out_this_amount);
        $revenue_hrst           += round($revenue_this_hours,2);
        $revenue_amountt        += round($revenue_this_amount);
        $settle_hrst            += round($settle_this_hours,2);
        $settle_amountt         += round($settle_this_amount);
        $revenue_hrs_alloct     += round($revenue_hrs_alloc,2);
        $revenue_amount_alloct  += round($revenue_amount_alloc);
        $mv_tf_in_hrst          += round($mv_tf_in_this_hours,2);
        $mv_tf_in_amountt       += round($mv_tf_in_this_amount);
        $mv_tf_out_hrst         += round($mv_tf_out_this_hours,2);
        $mv_tf_out_amountt      += round($mv_tf_out_this_amount);
        $refund_hrst            += round($refund_this_hours,2);
        $refund_amountt         += round($refund_this_amount);
        $carry_hrs_tempt        += round($carry_hrs_temp,2);
        $carry_amount_tempt     += round($carry_amount_temp);
        $out_standing_hrst      += round($out_standing_hrs,2);
        $out_standingt          += round($out_standing);
        //Tinh bao cao cho Bac Hung
        $arr_student[$row_payment[$i]['student_id']]['count']  += 1;
        if( ($out_standing + $carry_amount_temp) == 0)
            $arr_student[$row_payment[$i]['student_id']]['count_zero']  += 1;    //Student Run
    }
}

$html .= "<tr>
<td colspan='9'><h3><span>Grand Total</span></h3></td>
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
<td><b>".format_number($settle_hrst,2,2)."</b></td>
<td><b>".format_number($settle_amountt)."</b></td>
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
<td><b>".format_number($out_standing_hrst,2,2)."</b></td>
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
    if( $value['count'] == $value['count_zero'] )
        $count_left++;

    $count_student++;
}
echo '<b>Total Students: </b>'.$count_student.'<br>';
echo '<b>Left Students: </b>'.$count_left;

