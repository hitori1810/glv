<?php
    $filter = $this->where;
    $parts = explode("AND", $filter);

    $start = get_string_between($parts[0],"'","'");
    $end = get_string_between($parts[1],"'","'");

    $html  = '<table class="reportlistView"><thead>
    <tr>
    <th  >First EC</th>
    <th  >Date</th>
    <th >Receipt/Invoice No</th>
    <th  >Student Code</th>
    <th  >Student Name</th>
    <th  >Kind of Course</th>
    <th  >Level</th>
    <th  >Class Code</th>
    <th  >Type</th>
    <th  >Before Discount</th>
    <th  >Discount Amount</th>
    <th  >Discount Percent</th>
    <th  >Total Discount Amount</th>
    <th  >Total Discount Percent</th>
    <th  >Sponsor Amount</th>
    <th  >Sponsor Percent</th>
    <th  >Total Sponsor Amount</th>
    <th  >Total Sponsor Percent</th>
    <th >Payment Method</th>
    <th  >Commission Percent</th>
    <th  >Commission</th>
    </tr>
    </thead><tbody>
    ';
    $html .= '<tbody>';


    //==================== Group EC ====================//
    $sql_group_ec = "
    SELECT DISTINCT
    IFNULL(l1.id, '') ec_id,
    CONCAT(
    IFNULL(l1.last_name, ''),
    ' ',
    IFNULL(l1.first_name, '')
    ) l1_full_name
    FROM
    j_payment
    INNER JOIN users l1 ON j_payment.assigned_user_id = l1.id
    AND l1.deleted = 0
    INNER JOIN j_paymentdetail l2 ON j_payment.id = l2.payment_id
    AND l2.deleted = 0
    INNER JOIN contacts_j_payment_1_c l3_1 ON j_payment.id = l3_1.contacts_j_payment_1j_payment_idb
    AND l3_1.deleted = 0
    INNER JOIN contacts l3 ON l3.id = l3_1.contacts_j_payment_1contacts_ida
    AND l3.deleted = 0
    LEFT JOIN j_studentsituations l4 ON j_payment.id = l4.payment_id
    AND l4.deleted = 0
    LEFT JOIN j_class l5 ON l4.ju_class_id = l5.id
    AND l5.deleted = 0
    WHERE
    (((j_payment.sale_type = 'New Sale')
    AND (
    (j_payment.payment_date >= '$start' AND j_payment.payment_date <= '$end'))))
    AND j_payment.deleted = 0
    ";
    $rs_ec = $GLOBALS['db']->query($sql_group_ec);

    while($row_ec = $GLOBALS['db']->fetchByAssoc($rs_ec)) {

        //==================== get new sale theo EC ====================//
        $sql_get_new_sale = "
        SELECT DISTINCT
        IFNULL(l1.id, '') l1_id,
        CONCAT(
        IFNULL(l1.last_name, ''),
        ' ',
        IFNULL(l1.first_name, '')
        ) l1_full_name,
        IFNULL(j_payment.id, '') primaryid,
        j_payment.payment_date j_payment_payment_date,
        IFNULL(l2.id, '') l2_id,
        l2.invoice_number l2_invoice_number,
        IFNULL(l3.id, '') l3_id,
        l3.contact_id l3_contact_id,
        l3.full_student_name l3_full_student_name,
        IFNULL(l5.id, '') l5_id,
        l5.kind_of_course l5_kind_of_course,
        l5. LEVEL l5_level,
        l5.modules l5_modules,
        l5.class_code l5_class_code,
        IFNULL(j_payment.payment_type, '') j_payment_payment_type,
        j_payment.amount_bef_discount before_discount,
        j_payment.sub_discount_amount discount_amount,
        j_payment.sub_discount_percent discount_percent,
        j_payment.discount_amount j_payment_discount_amount,
        j_payment.discount_percent j_payment_discount_percent,
        j_payment.sponsor_amount j_payment_sponsor_amount,
        j_payment.sponsor_percent j_payment_sponsor_percent,
        j_payment.final_sponsor j_payment_final_sponsor,
        j_payment.final_sponsor_percent J_PAYMENT_FINAL_SPONSO90ED0F,
        IFNULL(
        j_payment.payment_method,
        ''
        ) j_payment_payment_method
        FROM
        j_payment
        INNER JOIN users l1 ON j_payment.assigned_user_id = l1.id
        AND l1.deleted = 0
        INNER JOIN j_paymentdetail l2 ON j_payment.id = l2.payment_id
        AND l2.deleted = 0
        INNER JOIN contacts_j_payment_1_c l3_1 ON j_payment.id = l3_1.contacts_j_payment_1j_payment_idb
        AND l3_1.deleted = 0
        INNER JOIN contacts l3 ON l3.id = l3_1.contacts_j_payment_1contacts_ida
        AND l3.deleted = 0
        LEFT JOIN j_studentsituations l4 ON j_payment.id = l4.payment_id
        AND l4.deleted = 0
        LEFT JOIN j_class l5 ON l4.ju_class_id = l5.id
        AND l5.deleted = 0
        WHERE
        (((j_payment.sale_type = 'New Sale')
        AND ((j_payment.payment_date >= '$start' AND j_payment.payment_date <= '$end')
        )
        AND (l1.id = '{$row_ec['ec_id']}')))
        AND j_payment.deleted = 0
        ";
        $rs_new_sale = $GLOBALS['db']->query($sql_get_new_sale);
        $total_bef_dis = 0;
        $total_dis_amount = 0;
        $total_dis_per = 0;
        $total_sum_dis_amount = 0;
        $total_sum_dis_per = 0;
        $total_spo_amount = 0;
        $total_spo_per = 0;
        $total_sum_spo_amount = 0;
        $total_sum_spo_per = 0;
        while($row_new_sale = $GLOBALS['db']->fetchByAssoc($rs_new_sale)) {
            $html .= '<tr>';
            $html .= '<td>'.$row_new_sale['l1_full_name'].'</td>';
            $html .= '<td>'.$row_new_sale['j_payment_payment_date'].'</td>';
            $html .= '<td>'.$row_new_sale['l2_invoice_number'].'</td>';
            $html .= '<td>'.$row_new_sale['l3_contact_id'].'</td>';
            $html .= '<td>'.$row_new_sale['l3_full_student_name'].'</td>';
            $html .= '<td>'.$row_new_sale['l5_kind_of_course'].'</td>';
            $html .= '<td>'.$row_new_sale['l5_level'].' '.$row_new_sale['l5_modules'].'</td>';
            $html .= '<td>'.$row_new_sale['l5_class_code'].'</td>';
            $html .= '<td>'.$row_new_sale['j_payment_payment_type'].'</td>';


            $html .= '<td>'.$row_new_sale['before_discount'].'</td>';
            $total_bef_dis += $row_new_sale['before_discount'];

            $html .= '<td>'.$row_new_sale['discount_amount'].'</td>';
            $total_dis_amount += $row_new_sale['discount_amount'];

            $html .= '<td>'.$row_new_sale['discount_percent'].'</td>';
            $total_dis_per += $row_new_sale['discount_percent'];

            $html .= '<td>'.$row_new_sale['j_payment_discount_amount'].'</td>';
            $total_sum_dis_amount += $row_new_sale['j_payment_discount_amount'];

            $html .= '<td>'.$row_new_sale['j_payment_discount_percent'].'</td>';
            $total_sum_dis_per += $row_new_sale['j_payment_discount_percent'];

            $html .= '<td>'.$row_new_sale['j_payment_sponsor_amount'].'</td>';
            $total_spo_amount += $row_new_sale['j_payment_sponsor_amount'];

            $html .= '<td>'.$row_new_sale['j_payment_sponsor_percent'].'</td>';
            $total_spo_per += $row_new_sale['j_payment_sponsor_percent'];

            $html .= '<td>'.$row_new_sale['j_payment_final_sponsor'].'</td>';
            $total_sum_spo_amount += $row_new_sale['j_payment_final_sponsor'];

            $html .= '<td>'.$row_new_sale['J_PAYMENT_FINAL_SPONSO90ED0F'].'</td>';
            $total_sum_spo_per += $row_new_sale['J_PAYMENT_FINAL_SPONSO90ED0F'];

            $html .= '<td>'.$row_new_sale['j_payment_payment_method'].'</td>';

        }
        $html .= '</tr>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th colspan="9" style="text-align:right">Total: </th>';
        $html .= '<th>'.format_number($total_bef_dis).'</th>';
        $html .= '<th>'.format_number($total_dis_amount).'</th>';
        $html .= '<th>'.format_number($total_dis_per).'</th>';
        $html .= '<th>'.format_number($total_sum_dis_amount).'</th>';
        $html .= '<th>'.format_number($total_sum_dis_per).'</th>';
        $html .= '<th>'.format_number($total_spo_amount).'</th>';
        $html .= '<th>'.format_number($total_spo_per).'</th>';
        $html .= '<th>'.format_number($total_sum_spo_amount).'</th>';
        $html .= '<th colspan="5" style="text-align:left">'.$total_sum_spo_per.'</th>';
        $html .= '</tr>';
        $html .= '</thead>';
    }

    $html .= '</tbody></table>';
    $html  .= '<br><br><table class="reportlistView"><thead><tr>
    <th>First EC</th>
    <th>Amount</th>
    <th>Number of new sales (36 hours)</th>
    <th>Number of new sales (72 hours)</th>
    <th>Number of new sales (108 hours)</th>
    <th>Total number of new sales</th>
    </tr></thead>';

    $html .= '<tbody>';
    $total_36 = 0;
    $total_72 = 0;
    $total_108 = 0;
    $total_sum = 0;
    $sum_amount = 0;
    $rs_ec_count = $GLOBALS['db']->query($sql_group_ec);
    while($row_ec = $GLOBALS['db']->fetchByAssoc($rs_ec_count)) {

        //==================== sưm payment amount theo EC ====================//
        $sql_sum_amount = "
        SELECT DISTINCT
        sum(
        IFNULL(
        IFNULL(j_payment.payment_amount, 0) / (
        SELECT
        conversion_rate
        FROM
        currencies
        WHERE
        id = j_payment.currency_id
        ),
        IFNULL(j_payment.payment_amount, 0)
        )
        ) J_PAYMENT_SUM_PAYMENT_88E72A
        FROM
        j_payment
        INNER JOIN users l1 ON j_payment.assigned_user_id = l1.id
        AND l1.deleted = 0
        WHERE
        (((l1.id = '{$row_ec['ec_id']}')
        AND (j_payment.sale_type = 'New Sale')
        AND ((j_payment.payment_date >= '$start'AND j_payment.payment_date <= '$end'))))
        AND j_payment.deleted = 0
        ";
        $result_sum = $GLOBALS['db']->getone($sql_sum_amount);
        $pac_36 = count_pac_ec($row_ec['ec_id'],36,$start,$end);
        $pac_72 = count_pac_ec($row_ec['ec_id'],72,$start,$end);
        $pac_108 = count_pac_ec($row_ec['ec_id'],108,$start,$end);
        $sum_pac = $pac_36 + $pac_72 + $pac_108;
        $html .= '<tr>';
        $html .= '<td>'.$row_ec['l1_full_name'].'</td>';
        $html .= '<td>'.format_number($result_sum).'</td>';
        $html .= '<td>'.$pac_36.'</td>';
        $html .= '<td>'.$pac_72.'</td>';
        $html .= '<td>'.$pac_108.'</td>';
        $html .= '<td>'.$sum_pac.'</td>';

        $html .= '</tr>';
        $total_36 += $pac_36;
        $total_72 += $pac_72;
        $total_108 += $pac_108;
        $total_sum += $sum_pac;
        $sum_amount += $result_sum;
    }

    $html .= '<thead><tr>
    <th style="text-align:right">Total:</th>
    <th>'.format_number($sum_amount).'</th>
    <th>'.$total_36.'</th>
    <th>'.$total_72.'</th>
    <th>'.$total_108.'</th>
    <th>'.$total_sum.'</th>
    </tr></thead>';
    $html .= '</tbody></table>';
    echo $html;

    function count_pac_ec($ec_id,$pac,$from,$to){
        $count_pac = "
        SELECT DISTINCT
        COUNT(j_payment.id) j_payment__allcount
        FROM
        j_payment
        INNER JOIN users l1 ON j_payment.assigned_user_id = l1.id
        AND l1.deleted = 0
        INNER JOIN j_coursefee_j_payment_1_c l2_1 ON j_payment.id = l2_1.j_coursefee_j_payment_1j_payment_idb
        AND l2_1.deleted = 0
        INNER JOIN j_coursefee l2 ON l2.id = l2_1.j_coursefee_j_payment_1j_coursefee_ida
        AND l2.deleted = 0
        WHERE
        (((l1.id = '$ec_id')
        AND (j_payment.sale_type = 'New Sale'
        )
        AND (
        (j_payment.payment_date >= '$from' AND j_payment.payment_date <= '$to'
        ))
        AND (l2.type_of_course_fee = '$pac')))
        AND j_payment.deleted = 0
        ";
        $rs_count = $GLOBALS['db']->getone($count_pac);
        return $rs_count;
    }
?>
