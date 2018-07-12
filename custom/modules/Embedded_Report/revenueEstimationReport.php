<?php
    $filter = $this->where;
    $parts = explode("AND", $filter);

    $start = get_string_between($parts[0],"'","'");
    $end = get_string_between($parts[1],"'","'");

    $last_start = date('Y-m-d',strtotime("$start -1 years"));
    $last_end = date('Y-m-d',strtotime("$end -1 years"));

    $last_day_of_month = date("Y-m-t", strtotime($end));

    $name_of_month = date('M',strtotime($end));
    $year = date('Y',strtotime($end));
    //==================== Actual revenue ====================//
    $sql_actual_payment = "
    SELECT
    sum(j_payment.payment_amount) sum_amount
    FROM
    j_payment
    WHERE( j_payment.payment_date >= '$start' AND j_payment.payment_date <= '$end')
    AND j_payment.deleted = 0
    ";
    $result_payment = $GLOBALS['db']->getone($sql_actual_payment);


    //==================== Actual revenue in the same period last year ====================//
    $sql_last_payment = "
    SELECT
    sum(j_payment.payment_amount) sum_amount
    FROM
    j_payment
    WHERE( j_payment.payment_date >= '$last_start' AND j_payment.payment_date <= '$last_end')
    AND j_payment.deleted = 0
    ";
    $result_last = $GLOBALS['db']->getone($sql_last_payment);



    //==================== Estimated revenue ====================//
    $sql_last_month = "
    SELECT
    sum(j_payment.payment_amount) sum_amount
    FROM
    j_payment
    WHERE( j_payment.payment_date >= '$last_start' AND j_payment.payment_date <= '$last_day_of_month')
    AND j_payment.deleted = 0
    ";
    $result_last_month = $GLOBALS['db']->getone($sql_last_month);


    //==================== Estimated revenue ====================//

    $sql_target = "
    SELECT sum(input_value) as target_revenue
    FROM
    j_targetconfig
    WHERE
    (
    (
    (j_targetconfig. MONTH = '$name_of_month')
    AND (j_targetconfig. YEAR = '$year')
    AND (
    j_targetconfig.type_targetconfig_list = 'Target Revenue'
    )
    )
    )
    AND j_targetconfig.deleted = 0
    ";
    $rs_target = $GLOBALS['db']->getone($sql_target);


    $html   = '<table class="reportlistView">';

    $html  .= '<thead>
    <tr>
    <th>Actual revenue</th>
    <th>Actual revenue in the same period last year</th>
    <th>Estimated revenue</th>
    <th>Monthly revenue target</th>
    <tr>
    </thead>';
    $html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<td>'.format_number($result_payment,2,2).'</td>';
    $html .= '<td>'.format_number($result_last,2,2).'</td>';
    $html .= '<td>'.format_number($result_last_month,2,2).'</td>';
    $html .= '<td>'.format_number($rs_target,2,2).'</td>';
    $html .= '</tr>';
    $html .= '</tbody></table>';

    echo $html;
?>
