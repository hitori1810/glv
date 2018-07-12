<?php
    function calCommission($start , $end){
        require_once("custom/modules/C_DeliveryRevenue/DeliveryRevenue.php");
        calDelivery($start , $end);

        $qu2 = "TRUNCATE c_commission";
        $GLOBALS['db']->query($qu2);

        global $timedate;
        $start_l = date('Y-m-d',strtotime('-1 year', $start));
        $end_l = date('Y-m-d',strtotime('-1 year', $end_l));

        $start_ss = date('Y-m-d H:i:s',strtotime("-7 hours ".$start." 00:00:00"));
        $end_ss   = date('Y-m-d H:i:s',strtotime("-7 hours ".$end." 23:59:59"));
        
        $today = $timedate->nowDbDate();

        $sql = "SELECT id, name, sale_target FROM teams WHERE private = 0 AND deleted = 0 AND id != 1";
        $result = $GLOBALS['db']->query($sql);
        while($row = $GLOBALS['db']->fetchByAssoc($result)){
            //Net Collected Revenue
            $q2 = "SELECT DISTINCT
            IFNULL(SUM(c_payments.payment_amount), 0) c_payments_payment_amount
            FROM
            c_payments
            INNER JOIN
            teams l1 ON c_payments.team_id = l1.id
            AND l1.deleted = 0
            INNER JOIN
            users l2 ON c_payments.assigned_user_id = l2.id
            AND l2.deleted = 0
            WHERE
            (((c_payments.payment_type IN ('Normal' , 'Deposit'))
            AND ((c_payments.payment_date >= '$start'
            AND c_payments.payment_date <= '$end'))
            AND (c_payments.status = 'Paid')
            AND (l1.id = '{$row['id']}')))
            AND c_payments.deleted = 0";
            $collected = $GLOBALS['db']->getOne($q2);

            //Refund
            $q3 = "SELECT DISTINCT
            SUM(c_refunds.refund_amount) TOTAL1
            FROM
            c_refunds
            INNER JOIN
            teams l1 ON c_refunds.team_id = l1.id
            AND l1.deleted = 0
            WHERE
            (((c_refunds.refund_date >= '$start'
            AND c_refunds.refund_date <= '$end')
            AND (l1.id = '{$row['id']}')))
            AND c_refunds.deleted = 0
            AND c_refunds.refund_type = 'Normal'";
            $refund = $GLOBALS['db']->getOne($q3);

            $net_collected = ($collected - $refund);
            $sale_target = $row['sale_target'];

            //This Year
            $q5 = "SELECT DISTINCT
            IFNULL(SUM(c_deliveryrevenue.duration), 0) duration,
            IFNULL(SUM(c_deliveryrevenue.amount), 0) amount
            FROM
            c_deliveryrevenue
            INNER JOIN
            teams l2 ON c_deliveryrevenue.team_id = l2.id
            AND l2.deleted = 0
            WHERE
            (((l2.id = '{$row['id']}')
            AND ((c_deliveryrevenue.date_input >= '$start'
            AND c_deliveryrevenue.date_input <= '$end'))))
            AND c_deliveryrevenue.deleted = 0";
            $rs5 = $GLOBALS['db']->query($q5);
            $row5 = $GLOBALS['db']->fetchByAssoc($rs5);
            $total_hour = $row5['duration']; 
            $delivery = $row5['amount'];
            $ave = $delivery / $total_hour;

            $cms = new C_Commission();

            $cms->name = 'Commision ';
            $cms->date_input = $start;
            $cms->value_input = $collected;
            $cms->value_input_2 = $refund;
            $cms->value_input_3 = $net_collected;
            $cms->value_input_4 = $total_hour;
            $cms->value_input_5 = $delivery;
            $cms->value_input_6 = $ave;
            $cms->team_id = $row['id'];
            $cms->team_set_id = $row['id'];
            $cms->assigned_user_id = '1';
            $cms->save();
        }
    } 
?>
