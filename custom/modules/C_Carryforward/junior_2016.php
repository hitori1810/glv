<?php
if(!isset($_POST['record']) || empty($_POST['record'])){
    echo "Please, Select filter options and click Apply to show this report. ";
    $sv = new SugarView();
    $sv->displayFooter();
    die();
}

require_once("custom/modules/C_Carryforward/helper/helper_junior_2016.php");

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
//    $GLOBALS['db']->query("DELETE FROM c_carryforward WHERE created_by = '$cr_user_id' AND passed <> 0");  //Xóa d? li?u cu
//}

//$start      = '2012-01-01';// Nh? s?a l?i khi sau test nhé 2012-01-01
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

$row_course     = getPaymentCF($team_id, $student_id, $start_cf, $end);

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

$row_collected  = getCollected($payment_id_str, $end);

$row_revenue    = getRevenue($team_id, $str_student_id, $payment_id_str, $start, $end);

$row_settle     = getSettle($team_id, $str_student_id, $payment_id_str, $start, $end);

//$row_cashin     = getCashIn($team_id, $student_id, $start, $end);

//$row_cashout    = getCashOut($team_id, $student_id, $start, $end);

$row_eliminate  = getEliminate($team_id, $str_student_id, $end);

$row_cashin     = getCashIn($payment_id_str, $end);

$row_cashout     = getCashOut($payment_id_str,  $end);

//$row_eliminate     = getEliminate2($payment_id_str, $start, $end);

for($i = 0; $i < count($row_course); $i++){
    $carry_hrs      = 0;
    $carry_amount   = 0;
    $collected_amount_alloc     = 0;
    $collected_hrs_alloc        = 0;
    $revenue_amount_alloc       = 0;
    $revenue_hrs_alloc          = 0;

    $payment_id  = $row_course[$i]['payment_id'];
    $payment_aims_id = $row_course[$i]['payment_aims_id'];
    $cr_i = $i;
    //each month
    $date_run = $start_run;
    while($date_run <= $end_run){
        $month_year  = date('m-Y', $date_run);
        // s? du d?u k?
        $beginning_hrs    = $carry_hrs;
        $beginning_amount   = $carry_amount;

        // Collected trong k?
        $before_discount  = $row_collected[$payment_id][$month_year]['before_discount'];
        if(empty($before_discount)) $before_discount = 0;

        $discount_amount  = $row_collected[$payment_id][$month_year]['discount_amount'];
        if(empty($discount_amount)) $discount_amount = 0;

        $sponsor_amount   = $row_collected[$payment_id][$month_year]['sponsor_amount'];
        if(empty($sponsor_amount)) $sponsor_amount = 0;

        $collected_amount = $row_collected[$payment_id][$month_year]['payment_amount'];
        if(empty($collected_amount)) $collected_amount = 0;

        $collected_hrs    = $collected_amount / $row_course[$i]['payment_price'];
        if(empty($collected_hrs)) $collected_hrs = 0;
        //B? sung s? TH Sponsor 100% vào báo cáo #bug 02/05/2016
        $pp_type_list = array(
            0 => 'Enrollment',
            1 => 'Cashholder');
        if($month_year == date('m-Y',strtotime($row_course[$i]['payment_date'])) && in_array($row_course[$i]['payment_type'], $pp_type_list) && (100 <= ($row_course[$i]['discount_percent'] + $row_course[$i]['final_sponsor_percent'])) ){
            $collected_hrs = $row_course[$i]['tuition_hours'];
        }

        $invoice_number   = $row_collected[$payment_id][$month_year]['invoice_number'];
        if(empty($invoice_number)) $invoice_number = '';

        $collected_amount_alloc += $collected_amount;
        $collected_hrs_alloc    += $collected_hrs;

        //Cashholder/Delay In k? này
        $cash_in_amount = $row_cashin[$payment_id][$month_year]['delay_cash_in']['amount'];
        if(empty($cash_in_amount)) $cash_in_amount = 0;

        $cash_in_hrs = $row_cashin[$payment_id][$month_year]['delay_cash_in']['hours'];
        if(empty($cash_in_hrs)) $cash_in_hrs = 0;
        if($cash_in_hrs == 0 && $cash_in_amount > 0 && $row_course[$i]['payment_type'] == 'Enrollment'){
            $cash_in_hrs = $cash_in_amount / $row_course[$i]['payment_price'];
        }

        //Cashholder/Delay Out k? này
        $cash_out_amount = $row_cashout[$payment_id][$month_year]['delay_cash_out']['amount'];
        if(empty($cash_out_amount)) $cash_out_amount = 0;

        $cash_out_hrs = $row_cashout[$payment_id][$month_year]['delay_cash_out']['hours'];
        if(empty($cash_out_hrs)) $cash_out_hrs = 0;

        //Revenue + Drop Revenue k? này
        $revenue_amount = $row_revenue[$payment_id][$month_year]['amount'];
        if(empty($revenue_amount)) $revenue_amount = 0;

        $revenue_hrs = $row_revenue[$payment_id][$month_year]['hours'];
        if(empty($revenue_hrs)) $revenue_hrs = 0;

        //Revenue Settle k? này
        $settle_amount = $row_settle[$payment_id][$month_year]['amount'];
        if(empty($settle_amount)) $settle_amount = 0;

        $settle_hrs = $row_settle[$payment_id][$month_year]['hours'];
        if(empty($settle_hrs)) $settle_hrs = 0;

        //Allocated Revenue = Revenue + Settle
        $revenue_amount_alloc += ($revenue_amount + $settle_amount);
        $revenue_hrs_alloc    += ($revenue_hrs + $settle_hrs);

        // Moving Transfer In trong k?
        $mv_tf_in_amount = $row_cashin[$payment_id][$month_year]['moving_transfer_in']['amount'];
        if(empty($mv_tf_in_amount)) $mv_tf_in_amount = 0;

        //B? sung s? Transfer In, Transfer from AIMS, Moving In trong báo cáo # bug 19/04/2016
        //part 1
        $tf_mv_aims = array(
            0 => 'Transfer In',
            1 => 'Transfer From AIMS',
            2 => 'Moving In');
        if($month_year == date('m-Y',strtotime($row_course[$i]['payment_date'])) && in_array($row_course[$i]['payment_type'], $tf_mv_aims)){
            $mv_tf_in_amount = $row_course[$i]['payment_amount'];
        }
        //End: Part 1

        $mv_tf_in_hrs = $row_cashin[$payment_id][$month_year]['moving_transfer_in']['hours'];
        if(empty($mv_tf_in_hrs)) $mv_tf_in_hrs = 0;
        if($mv_tf_in_hrs == 0 && $mv_tf_in_amount > 0 && $row_course[$i]['payment_type'] == 'Enrollment'){
            $mv_tf_in_hrs = $mv_tf_in_amount / $row_course[$i]['payment_price'];
        }

        //part 2
        if($month_year == date('m-Y',strtotime($row_course[$i]['payment_date'])) && in_array($row_course[$i]['payment_type'], $tf_mv_aims)){
            $mv_tf_in_hrs = $row_course[$i]['total_hours'];
        }
        //End: Part 2

        // Moving Transfer Out k? này
        $mv_tf_out_amount = $row_cashout[$payment_id][$month_year]['moving_transfer_out']['amount'];
        if(empty($mv_tf_out_amount)) $mv_tf_out_amount = 0;

        //part 3
        if($cash_out_amount > 0 && in_array($row_course[$i]['payment_type'], $tf_mv_aims)){
            $mv_tf_out_amount += $cash_out_amount;
            $cash_out_amount = 0;
        }
        //End: part 3

        $mv_tf_out_hrs = $row_cashout[$payment_id][$month_year]['moving_transfer_out']['hours'];
        if(empty($mv_tf_out_hrs)) $mv_tf_out_hrs = 0;

        //part 4
        if($cash_out_hrs > 0 && in_array($row_course[$i]['payment_type'], $tf_mv_aims)){
            $mv_tf_out_hrs = $cash_out_hrs;
            $cash_out_hrs = 0;
        }
        //End: part 4

        //Refund k? này
        $refund_amount = $row_cashout[$payment_id][$month_year]['refund']['amount'];
        if(empty($refund_amount)) $refund_amount = 0;
        $refund_hrs      = $refund_amount / $row_course[$i]['payment_price'];

        //Eleminate k? này
        $eleminate_amount = $row_eliminate[$payment_id][$month_year]['amount'];

        //CarryForward k? này
        $carry_amount = $beginning_amount + $collected_amount - $revenue_amount - $settle_amount + $mv_tf_in_amount - $mv_tf_out_amount + $cash_in_amount - $cash_out_amount - $refund_amount - $eleminate_amount;
        $carry_hrs    = $beginning_hrs + $collected_hrs - $revenue_hrs - $settle_hrs + $mv_tf_in_hrs - $mv_tf_out_hrs + $cash_in_hrs - $cash_out_hrs - $refund_hrs;

        $date_run = strtotime("+1 month", $date_run);
    }
    //Lam tron so
    if(abs($carry_amount) < 1000 && abs($carry_hrs) < '0.5'){
        $carry_amount = 0;
        $carry_hrs  = 0;
    }elseif(!empty($payment_aims_id)){
        if(abs($carry_amount) < 1000)      $carry_amount = 0;
        if($carry_amount > -1000 && $carry_hrs < '0.5') $carry_hrs  = 0;
    }
	if(abs($beginning_amount) < 1000 && !empty($payment_aims_id)){
		$beginning_amount = 0;
		$beginning_hrs = 0;
	}
     /* elseif(!empty($payment_aims_id) && abs($carry_amount) < 1000 )
    {
        $carry_amount = 0;
    } elseif(!empty($payment_aims_id) && $carry_amount > -1000 && $carry_hrs < '0.5'){
        $carry_hrs  = 0;
    }
    if(abs($beginning_amount) < 1000 && abs($beginning_hrs) < '0.5'){
        $beginning_amount = 0;
        $beginning_hrs  = 0;
    } */

    //outstanding amount
    $out_standing     = 0;
    $out_standing_hrs = 0;
    $carry_amount_temp  = $carry_amount;
    $carry_hrs_temp = $carry_hrs;

    if($carry_amount < 0){
        $out_standing = abs($carry_amount);
        $carry_amount_temp = 0;
    }
    //    if($carry_hrs != 0 && $carry_amount == 0){   // Fix bug h?c Free Of Charge ko vào CF
    //        $carry_hrs = 0;
    //    }

    //outstanding hour
    if($carry_hrs < 0){
        $out_standing_hrs = abs($carry_hrs);
        $carry_hrs_temp   = 0;
    }
    if($carry_amount != 0 || ($carry_hrs != 0 & $before_discount == $sponsor_amount && $sponsor_amount > 0 )|| abs($beginning_amount) >= 1000 || $collected_amount != 0 || $revenue_hrs != 0 || $settle_hrs != 0 || $cash_out_hrs != 0 || $mv_tf_out_hrs != 0 || $revenue_amount != 0 || $settle_amount != 0 || $mv_tf_in_amount != 0 || $mv_tf_out_amount != 0 || $cash_in_amount != 0 || $cash_out_amount != 0 || $refund_amount != 0){
        $count++;
        $html .= "<tr><td valign='TOP' class='oddListRowS1'>$count</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=J_Payment&action=DetailView&record={$row_course[$cr_i]['payment_id']}' target='_blank'>{$row_course[$cr_i]['payment_type']}</a></td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>{$row_course[$cr_i]['kind_of_course_string']}</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".str_replace("^","",$row_course[$cr_i]['level_string'])."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>{$row_course[$cr_i]['class_string']}</td>";

        //        if(!empty($row_course[$cr_i]['aims_code']))
        $html .= "<td valign='TOP' class='oddListRowS1'></td>";
        //        else
        $html .= "<td valign='TOP' class='oddListRowS1'>{$row_course[$cr_i]['student_code']}</td>";

        $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=Contacts&action=DetailView&record={$row_course[$cr_i]['student_id']}' target='_blank'>{$row_course[$cr_i]['student_name']}</a> </td>";
        $html .= "<td valign='TOP' class='oddListRowS1' style=\"mso-number-format:\@;\" >$invoice_number</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($beginning_hrs,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($beginning_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($before_discount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($discount_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($sponsor_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($collected_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($collected_hrs,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($collected_hrs_alloc,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($collected_amount_alloc)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($cash_in_hrs,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($cash_in_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($cash_out_hrs,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($cash_out_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($revenue_hrs,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($revenue_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($settle_hrs,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($settle_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($revenue_hrs_alloc,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($revenue_amount_alloc)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($mv_tf_in_hrs,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($mv_tf_in_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($mv_tf_out_hrs,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($mv_tf_out_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($refund_hrs,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($refund_amount)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($carry_hrs_temp,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($carry_amount_temp)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($out_standing_hrs,2,2)."</td>";
        $html .= "<td valign='TOP' class='oddListRowS1'>".format_number($out_standing)."</td></tr>";
        $beginning_hrst +=$beginning_hrs;
        $beginning_amountt +=$beginning_amount;
        $before_discountt +=$before_discount;
        $discount_amountt +=$discount_amount;
        $sponsor_amountt +=$sponsor_amount;
        $collected_amountt +=$collected_amount;
        $collected_hrst +=$collected_hrs;
        $collected_hrs_alloct +=$collected_hrs_alloc;
        $collected_amount_alloct +=$collected_amount_alloc;
        $cash_in_hrst +=$cash_in_hrs;
        $cash_in_amountt +=$cash_in_amount;
        $cash_out_hrst +=$cash_out_hrs;
        $cash_out_amountt +=$cash_out_amount;
        $revenue_hrst +=$revenue_hrs;
        $revenue_amountt +=$revenue_amount;
        $settle_hrst +=$settle_hrs;
        $settle_amountt +=$settle_amount;
        $revenue_hrs_alloct +=$revenue_hrs_alloc;
        $revenue_amount_alloct +=$revenue_amount_alloc;
        $mv_tf_in_hrst +=$mv_tf_in_hrs;
        $mv_tf_in_amountt +=$mv_tf_in_amount;
        $mv_tf_out_hrst +=$mv_tf_out_hrs;
        $mv_tf_out_amountt +=$mv_tf_out_amount;
        $refund_hrst +=$refund_hrs;
        $refund_amountt +=$refund_amount;
        $carry_hrs_tempt +=$carry_hrs_temp;
        $carry_amount_tempt +=$carry_amount_temp;
        $out_standing_hrst +=$out_standing_hrs;
        $out_standingt +=$out_standing;
        //Tinh bao cao cho Bac Hung
        $arr_student[$row_course[$i]['student_id']]['count']  += 1;
        if( ($out_standing + $carry_amount_temp) == 0)
            $arr_student[$row_course[$i]['student_id']]['count_zero']  += 1;   //Student Run
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
    if( $value['count'] == $value['count_zero'] ){
        $count_left++;
        //         echo "'$key',<br>";
    }
    $count_student++;
}
echo '<b>Total Students: </b>'.$count_student.'<br>';
echo '<b>Left Students: </b>'.$count_left;

