<?php
require_once("custom/modules/C_Carryforward/helper/helper_adult_2015.php");
require_once("custom/modules/C_DeliveryRevenue/DeliveryRevenue.php");
$filter = str_replace(' ','',$this->where);
$parts = explode("AND", $filter);

for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "l1.id=") !== FALSE)                  $team_id    = get_string_between($parts[$i]);
    if(strpos($parts[$i], "c_carryforward.month=") !== FALSE)   $month      = get_string_between($parts[$i]);
    if(strpos($parts[$i], "c_carryforward.year=") !== FALSE)    $year       = get_string_between($parts[$i]);
}
$cr_user_id = $GLOBALS['current_user']->id;
if(!empty($team_id) && !empty($month) && !empty($year)){
    $GLOBALS['db']->query("DELETE FROM c_carryforward WHERE created_by = '$cr_user_id' AND passed <> 0");  //Xóa dữ liệu cũ
}

$start      = '2014-01-01';
$start_run  = strtotime($start);
$end        = date('Y-m-t',strtotime("$year-$month-01")); //Last date of filter mounth
$end_run    = strtotime($end);


calDelivery('2014-01-01' , $end, $team_id, '', $cr_user_id);
//PUBLIC STUDENT:

$row_course     = getEnrollmentCF($team_id);

$row_payin      = getTotalPayIn($team_id, $start, $end);

$row_payout     = getTotalPayOut($team_id, $start, $end);

$row_revenue    =  getDeliveryRevenue($start, $end, $cr_user_id);

for($i = 0; $i < count($row_course); $i++){
    $is_first = true;
    $carry_hours    = $row_course[$i]['total_hours'];
    $carry_amount   = 0;
    $collected      = 0;
    $moving_in      = 0;
    $moving_out      = 0;
    $tranfer_in      = 0;
    $tranfer_out     = 0;
    $delivery_amount = 0;
    $refund          = 0;
    $mv_tf_rf_in     = 0;
    $mv_tf_rf_out    = 0;
    $refund          = 0;
    $total_amount_after_discount    = 0;
    $total_allocated_balance        = 0;
    $total_allocated_hours          = 0;
    $mv_tf_in_allocated             = 0;
    $mv_tf_out_allocated            = 0;
    $refund_allocated               = 0;

    $out_standing       = 0;
    $out_standing_hours = 0;
    //each month
    $date_run = $start_run;
    while($date_run <= $end_run){
        $month  = date('m', $date_run);
        $year   = date('Y', $date_run);

        // số dư đầu kỳ
        $beginning_hours    = $carry_hours;
        $beginning_amount   = $carry_amount;

        // Collected trong kỳ
        $collected = $row_payin[$row_course[$i]['enrollment_id']][$year][$month]['collected'];
        if(empty($collected)) $collected = 0;

        // Moving In trong kỳ
        $moving_in = $row_payin[$row_course[$i]['enrollment_id']][$year][$month]['moving_in'];
        if(empty($moving_in)) $moving_in = 0;

        // Moving out kỳ này
        $moving_out = $row_payout[$row_course[$i]['enrollment_id']][$year][$month]['moving_out'];
        if(empty($moving_out)) $moving_out = 0;

        // Tranfer in kỳ này
        $tranfer_in = $row_payin[$row_course[$i]['enrollment_id']][$year][$month]['tranfer_in'];
        if(empty($tranfer_in)) $tranfer_in = 0;

        // Tranfer out kỳ này
        $tranfer_out = $row_payout[$row_course[$i]['enrollment_id']][$year][$month]['tranfer_out'];
        if(empty($tranfer_out)) $tranfer_out = 0;

        //Delivery Revenue + Convert Delivery Revenue kỳ này
        $delivery_amount = $row_revenue[$row_course[$i]['enrollment_id']][$year][$month]['amount'];
        if(empty($delivery_amount)) $delivery_amount = 0;

        $delivery_hours = $row_revenue[$row_course[$i]['enrollment_id']][$year][$month]['hours'];
        if(empty($delivery_hours)) $delivery_hours = 0;

        //Refund kỳ này
        $refund = $row_payout[$row_course[$i]['enrollment_id']][$year][$month]['refund'];
        if(empty($refund)) $refund = 0;

        $mv_tf_rf_in    = $tranfer_in + $moving_in;
        $mv_tf_rf_out   = $tranfer_out + $moving_out;

        //CarryForward kỳ này
        $carry_amount   = $beginning_amount + $collected - $delivery_amount + $mv_tf_rf_in - $mv_tf_rf_out - $refund;
        $carry_hours    = $beginning_hours - $delivery_hours;

        //    if($carry_amount == 0 && $beginning_amount == 0) break;
        //     else{
        $total_amount_after_discount    += $collected;

        $total_allocated_balance        += $delivery_amount;
        $total_allocated_hours          += $delivery_hours;

        $mv_tf_in_allocated             += $mv_tf_rf_in;
        $mv_tf_out_allocated            += $mv_tf_rf_out;
        $refund_allocated               += $refund;
        //    }
        $date_run = strtotime("+1 month", $date_run);
    }
    if(abs($carry_amount) < 1000){
        $carry_amount = 0;
        $carry_hours  = 0;
    }
    if(abs($beginning_amount) < 1000){
        $beginning_amount = 0;
        $beginning_hours  = 0;
    }
    //outstanding amount
    $carry_amount_temp = $carry_amount;
    if($carry_amount < 0){
        $out_standing = abs($carry_amount);
        $carry_amount_temp = 0;
    }
    //outstanding hour
    $carry_hours_temp = $carry_hours;
    if($carry_hours < 0){
        $out_standing_hours = abs($carry_hours);
        $carry_hours_temp   = 0;
    }

    if($out_standing == 0 && $carry_amount == 0){
        $carry_hours  = 0;
        $carry_hours_temp  = 0;
    }
    if($carry_amount != 0 || $beginning_amount != 0){
        $add_carry = "INSERT INTO c_carryforward (
        id,
        name,
        created_by,
        deleted,
        team_id,
        team_set_id,
        last_stock,
        beginning_hours,
        collected,
        moving_in,
        moving_out,
        tranfer_in,
        tranfer_out,
        delivery,
        total_hour_studied,
        refund,
        total_allocated_hours,
        total_allocated_balance,
        total_amount_after_discount,
        mv_tf_rf_in,
        mv_tf_rf_out,
        mv_tf_in_allocated,
        mv_tf_out_allocated,
        refund_allocated,
        total_hour_left,
        this_stock,
        out_standing,
        carry_amount_temp,
        out_standing_hours,
        carry_hours_temp,
        month,
        year,
        passed,
        student_id,
        enrollment_id,
        kind_of_course) VALUES (
        '".create_guid()."',
        '".'CF: '.$row_course[$i]['full_student_name'].' '.$month.' '.$year."',
        '$cr_user_id',
        0,
        '{$row_course[$i]['enrollment_team_id']}',
        '{$row_course[$i]['enrollment_team_id']}',
        $beginning_amount,
        $beginning_hours,
        $collected,
        $moving_in,
        $moving_out,
        $tranfer_in,
        $tranfer_out,
        $delivery_amount,
        $delivery_hours,
        $refund,
        $total_allocated_hours,
        $total_allocated_balance,
        $total_amount_after_discount,
        $mv_tf_rf_in,
        $mv_tf_rf_out,
        $mv_tf_in_allocated,
        $mv_tf_out_allocated,
        $refund_allocated,
        $carry_hours,
        $carry_amount,
        $out_standing,
        $carry_amount_temp,
        $out_standing_hours,
        $carry_hours_temp,
        '$month',
        '$year',
        1,
        '{$row_course[$i]['student_id']}',
        '{$row_course[$i]['enrollment_id']}',
        '{$row_course[$i]['kind_of_course']}')";

        $GLOBALS['db']->query($add_carry);
    }


}

//DEPOSIT STUDENT:
$row_course     = getDepositCF($team_id);
for($i = 0; $i < count($row_course); $i++){
    $is_first = true;
    $carry_hours    = 0;
    $carry_amount   = 0;
    $collected      = 0;
    $moving_in      = 0;
    $moving_out      = 0;
    $tranfer_in      = 0;
    $tranfer_out     = 0;
    $delivery_amount = 0;
    $refund          = 0;
    $mv_tf_rf_in     = 0;
    $mv_tf_rf_out    = 0;
    $refund          = 0;
    $total_amount_after_discount    = 0;
    $total_allocated_balance        = 0;
    $total_allocated_hours          = 0;
    $mv_tf_in_allocated             = 0;
    $mv_tf_out_allocated            = 0;
    $refund_allocated               = 0;

    $out_standing       = 0;
    $out_standing_hours = 0;
    //each month
    $date_run = $start_run;
    while($date_run <= $end_run){
        $month  = date('m', $date_run);
        $year   = date('Y', $date_run);

        // số dư đầu kỳ
        $beginning_hours    = $carry_hours;
        $beginning_amount   = $carry_amount;

        // Collected trong kỳ
        if( (date('m', strtotime($row_course[$i]['payment_date'])) == $month ) &&  (date('Y', strtotime($row_course[$i]['payment_date'])) == $year ))
            $collected = $row_course[$i]['payment_amount'];
        else
            $collected = 0;

        // Moving In trong kỳ
        $moving_in = 0;

        // Moving out kỳ này
        $moving_out = 0;

        // Tranfer in kỳ này
        $tranfer_in = 0;

        // Tranfer out kỳ này
        $tranfer_out = 0;

        //Delivery Revenue + Convert Delivery Revenue kỳ này
        $delivery_amount = 0;
        $delivery_hours = 0;

        //Refund kỳ này
        $refund = 0;

        $mv_tf_rf_in    = $tranfer_in + $moving_in;
        $mv_tf_rf_out   = $tranfer_out + $moving_out;

        //CarryForward kỳ này
        $carry_amount   = $beginning_amount + $collected - $delivery_amount + $mv_tf_rf_in - $mv_tf_rf_out - $refund;
        $carry_hours    = $beginning_hours - $delivery_hours;

        //    if($carry_amount == 0 && $beginning_amount == 0) break;
        //     else{
        $total_amount_after_discount    += $collected;

        $total_allocated_balance        += $delivery_amount;
        $total_allocated_hours          += $delivery_hours;

        $mv_tf_in_allocated             += $mv_tf_rf_in;
        $mv_tf_out_allocated            += $mv_tf_rf_out;
        $refund_allocated               += $refund;
        //    }
        $date_run = strtotime("+1 month", $date_run);
    }
    if(abs($carry_amount) < 1000){
        $carry_amount = 0;
        $carry_hours  = 0;
    }
    if(abs($beginning_amount) < 1000){
        $beginning_amount = 0;
        $beginning_hours  = 0;
    }
    //outstanding amount
    $carry_amount_temp = $carry_amount;
    if($carry_amount < 0){
        $out_standing = abs($carry_amount);
        $carry_amount_temp = 0;
    }
    //outstanding hour
    $carry_hours_temp = $carry_hours;
    if($carry_hours < 0){
        $out_standing_hours = abs($carry_hours);
        $carry_hours_temp   = 0;
    }

    if($out_standing == 0 && $carry_amount == 0){
        $carry_hours  = 0;
        $carry_hours_temp  = 0;
    }
    if($carry_amount != 0 || $beginning_amount != 0){
        $add_carry = "INSERT INTO c_carryforward (
        id,
        name,
        created_by,
        deleted,
        team_id,
        team_set_id,
        last_stock,
        beginning_hours,
        collected,
        moving_in,
        moving_out,
        tranfer_in,
        tranfer_out,
        delivery,
        total_hour_studied,
        refund,
        total_allocated_hours,
        total_allocated_balance,
        total_amount_after_discount,
        mv_tf_rf_in,
        mv_tf_rf_out,
        mv_tf_in_allocated,
        mv_tf_out_allocated,
        refund_allocated,
        total_hour_left,
        this_stock,
        out_standing,
        carry_amount_temp,
        out_standing_hours,
        carry_hours_temp,
        month,
        year,
        passed,
        student_id,
        payment_id,
        kind_of_course) VALUES (
        '".create_guid()."',
        '".'CF: '.$row_course[$i]['full_student_name'].' '.$month.' '.$year."',
        '$cr_user_id',
        0,
        '{$row_course[$i]['l3_id']}',
        '{$row_course[$i]['l3_id']}',
        $beginning_amount,
        $beginning_hours,
        $collected,
        $moving_in,
        $moving_out,
        $tranfer_in,
        $tranfer_out,
        $delivery_amount,
        $delivery_hours,
        $refund,
        $total_allocated_hours,
        $total_allocated_balance,
        $total_amount_after_discount,
        $mv_tf_rf_in,
        $mv_tf_rf_out,
        $mv_tf_in_allocated,
        $mv_tf_out_allocated,
        $refund_allocated,
        $carry_hours,
        $carry_amount,
        $out_standing,
        $carry_amount_temp,
        $out_standing_hours,
        $carry_hours_temp,
        '$month',
        '$year',
        1,
        '{$row_course[$i]['student_id']}',
        '{$row_course[$i]['payment_id']}',
        'Chưa xác định')";

        $GLOBALS['db']->query($add_carry);
    }


}

//CORPORATE STUDENT:
$row_course     = getCorpCF($team_id);

$row_payout     = getTotalPayOutCorp($start, $end);

$row_revenue    =  getDeliveryRevenueCorp($start, $end, $cr_user_id);

for($i = 0; $i < count($row_course); $i++){
    $is_first = true;
    $carry_hours    = $row_course[$i]['hours_per_student'];
    $carry_amount   = 0;
    $collected      = 0;
    $moving_in      = 0;
    $moving_out      = 0;
    $tranfer_in      = 0;
    $tranfer_out     = 0;
    $delivery_amount = 0;
    $refund          = 0;
    $mv_tf_rf_in     = 0;
    $mv_tf_rf_out    = 0;
    $refund          = 0;
    $total_amount_after_discount    = 0;
    $total_allocated_balance        = 0;
    $total_allocated_hours          = 0;
    $mv_tf_in_allocated             = 0;
    $mv_tf_out_allocated            = 0;
    $refund_allocated               = 0;

    $out_standing       = 0;
    $out_standing_hours = 0;
    //each month
    $date_run = $start_run;
    while($date_run <= $end_run){
        $month  = date('m', $date_run);
        $year   = date('Y', $date_run);

        // số dư đầu kỳ
        $beginning_hours    = $carry_hours;
        $beginning_amount   = $carry_amount;

        // Collected trong kỳ
        $contract_type = $row_course[$i]['contract_type'];
        if($contract_type != 'Moving' && $contract_type != 'Transfer'){
            if((date('m', strtotime($row_course[$i]['l1_customer_signed_date'])) == $month) && (date('Y', strtotime($row_course[$i]['l1_customer_signed_date'])) == $year))
                $collected = $row_course[$i]['amount_per_student'];
            else
                $collected = 0;
        }else $collected = 0;

        // Moving In trong kỳ
        if($contract_type == 'Moving' || $contract_type == 'Transfer'){
            if((date('m', strtotime($row_course[$i]['l1_customer_signed_date'])) == $month) && (date('Y', strtotime($row_course[$i]['l1_customer_signed_date'])) == $year))
                $moving_in = $row_course[$i]['amount_per_student'];
            else
                $moving_in = 0;
        }else $moving_in = 0;

        // Moving out kỳ này
        $moving_out = $row_payout[$row_course[$i]['contract_id']][$row_course[$i]['student_id']][$year][$month];
        if(empty($moving_out)) $moving_out = 0;

        // Tranfer in kỳ này
        $tranfer_in = 0;

        // Tranfer out kỳ này
        $tranfer_out = 0;

        //Delivery Revenue + Convert Delivery Revenue kỳ này
        $delivery_amount = $row_revenue[$row_course[$i]['contract_id']][$row_course[$i]['student_id']][$year][$month]['amount'];
        if(empty($delivery_amount)) $delivery_amount = 0;

        $delivery_hours = $row_revenue[$row_course[$i]['contract_id']][$row_course[$i]['student_id']][$year][$month]['hours'];
        if(empty($delivery_hours)) $delivery_hours = 0;

        //Refund kỳ này
        $refund = 0;

        $mv_tf_rf_in    = $tranfer_in + $moving_in;
        $mv_tf_rf_out   = $tranfer_out + $moving_out;

        //CarryForward kỳ này
        $carry_amount   = $beginning_amount + $collected - $delivery_amount + $mv_tf_rf_in - $mv_tf_rf_out - $refund;
        $carry_hours    = $beginning_hours - $delivery_hours;

        //    if($carry_amount == 0 && $beginning_amount == 0) break;
        //     else{
        $total_amount_after_discount    += $collected;

        $total_allocated_balance        += $delivery_amount;
        $total_allocated_hours          += $delivery_hours;

        $mv_tf_in_allocated             += $mv_tf_rf_in;
        $mv_tf_out_allocated            += $mv_tf_rf_out;
        $refund_allocated               += $refund;
        //    }
        $date_run = strtotime("+1 month", $date_run);
    }
    if(abs($carry_amount) < 1000){
        $carry_amount = 0;
        $carry_hours  = 0;
    }
    if(abs($beginning_amount) < 1000){
        $beginning_amount = 0;
        $beginning_hours  = 0;
    }
    //outstanding amount
    $carry_amount_temp = $carry_amount;
    if($carry_amount < 0){
        $out_standing = abs($carry_amount);
        $carry_amount_temp = 0;
    }
    //outstanding hour
    $carry_hours_temp = $carry_hours;
    if($carry_hours < 0){
        $out_standing_hours = abs($carry_hours);
        $carry_hours_temp   = 0;
    }

    if($out_standing == 0 && $carry_amount == 0){
        $carry_hours  = 0;
        $carry_hours_temp  = 0;
    }
    if($carry_amount != 0 || $beginning_amount != 0){
        $add_carry = "INSERT INTO c_carryforward (
        id,
        name,
        created_by,
        deleted,
        team_id,
        team_set_id,
        last_stock,
        beginning_hours,
        collected,
        moving_in,
        moving_out,
        tranfer_in,
        tranfer_out,
        delivery,
        total_hour_studied,
        refund,
        total_allocated_hours,
        total_allocated_balance,
        total_amount_after_discount,
        mv_tf_rf_in,
        mv_tf_rf_out,
        mv_tf_in_allocated,
        mv_tf_out_allocated,
        refund_allocated,
        total_hour_left,
        this_stock,
        out_standing,
        carry_amount_temp,
        out_standing_hours,
        carry_hours_temp,
        month,
        year,
        passed,
        student_id,
        contract_id,
        kind_of_course) VALUES (
        '".create_guid()."',
        '".'CF: Corporate '.$row_course[$i]['full_student_name'].' '.$month.' '.$year."',
        '$cr_user_id',
        0,
        '{$row_course[$i]['contract_team_id']}',
        '{$row_course[$i]['contract_team_id']}',
        $beginning_amount,
        $beginning_hours,
        $collected,
        $moving_in,
        $moving_out,
        $tranfer_in,
        $tranfer_out,
        $delivery_amount,
        $delivery_hours,
        $refund,
        $total_allocated_hours,
        $total_allocated_balance,
        $total_amount_after_discount,
        $mv_tf_rf_in,
        $mv_tf_rf_out,
        $mv_tf_in_allocated,
        $mv_tf_out_allocated,
        $refund_allocated,
        $carry_hours,
        $carry_amount,
        $out_standing,
        $carry_amount_temp,
        $out_standing_hours,
        $carry_hours_temp,
        '$month',
        '$year',
        1,
        '{$row_course[$i]['student_id']}',
        '{$row_course[$i]['contract_id']}',
        '{$row_course[$i]['kind_of_course']}')";

        $GLOBALS['db']->query($add_carry);
    }


}


