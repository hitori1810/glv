<?php

    //==================== Total new sales ====================//
    $sql_get_sale = "
    SELECT 
    count(j_payment.id) 
    FROM
    j_payment
    INNER JOIN j_studentsituations l1 ON j_payment.id = l1.payment_id
    AND l1.deleted = 0
    INNER JOIN j_class l2 ON l1.ju_class_id = l2.id
    AND l2.deleted = 0
    INNER JOIN teams l3 ON l2.team_id = l3.id
    AND l3.deleted = 0
    WHERE
    (((l3.team_type = 'Junior')))
    AND
    j_payment.deleted = 0
    AND j_payment.payment_type='Enrollment'
    ";
    $result_sale = $GLOBALS['db']->getone($sql_get_sale);

    //==================== Total students ====================//
    $sql_get_student = "
    SELECT 
    count(l3.id) 
    FROM
    j_payment
    INNER JOIN j_studentsituations l1 ON j_payment.id = l1.payment_id
    AND l1.deleted = 0
    INNER JOIN teams l2 ON l1.team_id = l2.id
    AND l2.deleted = 0
    INNER JOIN contacts l3 ON l1.student_id = l3.id
    AND l3.deleted = 0
    WHERE
    (
    (
    (l2.team_type = 'Junior')
    AND (
    j_payment.payment_type = 'Enrollment'
    )
    )
    )
    AND j_payment.deleted = 0 
    ";
    $result_student = $GLOBALS['db']->getone($sql_get_student);

    //==================== Total class ====================//
    $sql_get_class = "
    SELECT DISTINCT
    COUNT(DISTINCT l1.id) l1__count
    FROM
    j_studentsituations
    INNER JOIN j_class l1 ON j_studentsituations.ju_class_id = l1.id
    AND l1.deleted = 0
    INNER JOIN teams l2 ON l1.team_id = l2.id
    AND l2.deleted = 0
    WHERE
    (((l2.team_type = 'Junior')))
    AND j_studentsituations.deleted = 0 
    ";
    $result_class = $GLOBALS['db']->getone($sql_get_class);

    //==================== Total teaching hours ====================//
    $sql_get_hour = "
    SELECT DISTINCT
    sum(
    IFNULL(meetings.teaching_hour, 0)
    ) meetings_sum_teaching_hour
    FROM
    meetings
    INNER JOIN j_class l1 ON meetings.ju_class_id = l1.id
    AND l1.deleted = 0
    INNER JOIN teams l2 ON l1.team_id = l2.id
    AND l2.deleted = 0
    WHERE
    (((l2.team_type = 'Junior')))
    AND meetings.deleted = 0  
    ";
    $result_hour = $GLOBALS['db']->getone($sql_get_hour);

    $html   = '<table class="reportlistView">';
    $html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<td>Total new sales</td>';
    $html .= '<td>'.$result_sale.'</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Total students</td>';
    $html .= '<td>'.$result_student.'</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Total classes</td>';
    $html .= '<td>'.$result_class.'</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>ACS</td>';
    $html .= '<td>'.format_number($result_student/$result_class,2,2).'</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Total teaching hours</td>';
    $html .= '<td>'.$result_hour.'</td>';             
    $html .= '</tr>';             
    $html .= '</tbody></table>'; 

    //==================== Total teaching hours ====================//
    $sql_get_koc = "
    SELECT DISTINCT
    IFNULL(j_class.kind_of_course, '') j_class_kind_of_course
    FROM
    j_class
    INNER JOIN teams l1 ON j_class.team_id = l1.id
    AND l1.deleted = 0
    WHERE
    (((l1.team_type = 'Junior')))
    AND j_class.deleted = 0   
    ";
    $result_koc = $GLOBALS['db']->query($sql_get_koc);


    $html   .= '<br><br><table class="reportlistView">';

    $html  .= '<thead>
    <tr>
    <th rowspan="3">Kind of course</th>
    <th rowspan="3">Number of students</th>
    <th colspan="4">New sale</th>   
    <th colspan="5">Classes</th>   
    <th rowspan="3">Total classes(vs Target:)</th>   
    <th rowspan="3">ACS(vs Target:)</th>   
    <th rowspan="3">Total teaching hours (vs Target:)</th>   
    <th colspan="2">Students\' payment</th>   
    <th rowspan="3">ACS (fully paid)</th>   
    <tr>
    <tr>
    <th>Package 36 hours</th>
    <th>Package 72 hours</th>
    <th>Package 108 hours</th>
    <th>Total New Sale</th>

    <th>1 ls/w(vs Target:)</th>
    <th>2 ls/w(vs Target:)</th>
    <th>3 times/w</th>
    <th>Weekday</th>
    <th>Late shift</th>

    <th> 11-100% sponsored</th>
    <th> Paid (fully paid & 1 - 10%)</th>
    </tr>
    </thead>';
    $html .= '<tbody>';
    $total_student = 0;
    $total_36 = 0;
    $total_72 = 0;
    $total_108 = 0;
    $total_sum = 0;
    $total_class = 0;
    $total_hour = 0;
    $total_sponsor = 0;
    while($row_koc = $GLOBALS['db']->fetchByAssoc($result_koc)) {

        //==================== Total students the KOC ====================//
        $sql_get_student_koc = "
        SELECT 
        count(l4.id) l4_id
        FROM
        j_payment
        INNER JOIN j_studentsituations l1 ON j_payment.id = l1.payment_id
        AND l1.deleted = 0
        INNER JOIN teams l2 ON l1.team_id = l2.id
        AND l2.deleted = 0
        INNER JOIN j_class l3 ON l1.ju_class_id = l3.id
        AND l3.deleted = 0
        INNER JOIN contacts l4 ON l1.student_id = l4.id
        AND l4.deleted = 0
        WHERE
        (
        (
        (l2.team_type = 'Junior')
        AND (l3.kind_of_course = '{$row_koc['j_class_kind_of_course']}')
        AND (
        j_payment.payment_type = 'Enrollment'
        )
        )
        )
        AND j_payment.deleted = 0 
        ";
        $result_student_koc = $GLOBALS['db']->getone($sql_get_student_koc);

        //==================== Count payment theo KOC - Package hour ====================//
        $pac_36 = count_payment_package(36,$row_koc['j_class_kind_of_course']); 
        $pac_72 = count_payment_package(72,$row_koc['j_class_kind_of_course']); 
        $pac_108 = count_payment_package(108,$row_koc['j_class_kind_of_course']);
        $sum_pac = $pac_36 + $pac_72 + $pac_108; 
        $html .= '<tr>';
        $html .= '<td>'.$row_koc['j_class_kind_of_course'].'</td>';
        $html .= '<td>'.$result_student_koc.'</td>';
        $html .= '<td>'.$pac_36.'</td>';
        $html .= '<td>'.$pac_72.'</td>';
        $html .= '<td>'.$pac_108.'</td>';
        $html .= '<td>'.$sum_pac.'</td>';

        $total_student += $result_student_koc;
        $total_36 += $pac_36;
        $total_72 += $pac_72;
        $total_108 += $pac_108;
        $total_sum += $sum_pac; 

        //==================== Total class the KOC ====================//
        $sql_get_class_koc = "
        SELECT DISTINCT
        COUNT(DISTINCT l1.id) l1__count
        FROM
        j_studentsituations
        INNER JOIN j_class l1 ON j_studentsituations.ju_class_id = l1.id
        AND l1.deleted = 0
        INNER JOIN teams l2 ON l1.team_id = l2.id
        AND l2.deleted = 0
        WHERE
        (
        (
        (l2.team_type = 'Junior')
        AND (l1.kind_of_course = '{$row_koc['j_class_kind_of_course']}')
        )
        )
        AND j_studentsituations.deleted = 0
        ";
        $result_class_koc = $GLOBALS['db']->getone($sql_get_class_koc);
        $total_class += $result_class_koc;


        //==================== Total teaching hour the KOC ====================//
        $sql_get_hour_koc = "
        SELECT DISTINCT
        sum(
        IFNULL(meetings.teaching_hour, 0)
        ) meetings_sum_teaching_hour
        FROM
        meetings
        INNER JOIN j_class l1 ON meetings.ju_class_id = l1.id
        AND l1.deleted = 0
        INNER JOIN teams l2 ON l1.team_id = l2.id
        AND l2.deleted = 0
        WHERE
        (
        (
        (l2.team_type = 'Junior')
        AND (l1.kind_of_course = '{$row_koc['j_class_kind_of_course']}')
        )
        )
        AND meetings.deleted = 0
        ";
        $result_hour_koc = $GLOBALS['db']->getone($sql_get_hour_koc);
        $total_hour +=  $result_hour_koc;

        //==================== Total sponsor the KOC ====================//
        $sql_get_sponsor_koc = "
        SELECT count(j_payment.id)
        FROM
        j_payment
        INNER JOIN j_studentsituations l1 ON j_payment.id = l1.payment_id
        AND l1.deleted = 0
        INNER JOIN j_class l2 ON l1.ju_class_id = l2.id
        AND l2.deleted = 0
        INNER JOIN teams l3 ON l2.team_id = l3.id
        AND l3.deleted = 0
        WHERE
        (
        (
        (l3.team_type = 'Junior')
        AND (
        j_payment.sponsor_percent BETWEEN 11
        AND 100
        )
        AND (l2.kind_of_course = '{$row_koc['j_class_kind_of_course']}')
        )
        )
        AND j_payment.deleted = 0
        ";
        $result_spnsor_koc = $GLOBALS['db']->getone($sql_get_sponsor_koc);
        $total_sponsor +=  $result_spnsor_koc;

        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td>'.$result_class_koc.'</td>';
        $html .= '<td>'.format_number($result_student_koc/$result_class_koc,2,2).'</td>';
        $html .= '<td>'.$result_hour_koc.'</td>';
        $html .= '<td>'.$result_spnsor_koc.'</td>';
        $html .= '</tr>';
    }
    $html .= '<tr>';
    $html .= '<td>Total</td>';
    $html .= '<td>'.$total_student.'</td>';
    $html .= '<td>'.$total_36.'</td>';
    $html .= '<td>'.$total_72.'</td>';
    $html .= '<td>'.$total_108.'</td>';
    $html .= '<td>'.$total_sum.'</td>';
    $html .= '<td></td>';
    $html .= '<td></td>';
    $html .= '<td></td>';
    $html .= '<td></td>';
    $html .= '<td></td>';
    $html .= '<td>'.$total_class.'</td>';
    $html .= '<td>'.format_number($total_student/$total_class,2,2).'</td>';
    $html .= '<td>'.$total_hour.'</td>';
    $html .= '<td>'.$total_sponsor.'</td>';
    $html .= '</tr>';
    $html .= '</tbody></table>';


    $html .= '<br><br><table class="reportlistView">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Classes changes</th>';
    $html .= '<th>Number</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<td>Cancelled</td>';
    $html .= '<td></td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Finished</td>';
    $html .= '<td></td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Closed</td>';
    $html .= '<td></td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Merged</td>';
    $html .= '<td></td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Opened</td>';
    $html .= '<td></td>';             
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Make-up</td>';
    $html .= '<td></td>';             
    $html .= '</tr>';             
    //$html .= '</tbody></table>';


    //$html .= '<table class="reportlistView">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Enquiry and Direct Marketing</th>';
    $html .= '<th>Number</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    //$html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<td>Talk to us</td>';
    $html .= '<td></td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Live chat</td>';
    $html .= '<td></td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Walk in & call in</td>';
    $html .= '<td></td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Total enquiries</td>';
    $html .= '<td></td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Total telesales lead from enquiries</td>';
    $html .= '<td></td>';             
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Direct events this week</td>';
    $html .= '<td></td>';             
    $html .= '</tr>';             
    //$html .= '</tbody></table>';


    //$html .= '<table class="reportlistView">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>PT</th>';
    $html .= '<th>Number</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    //$html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<td>PT register</td>';
    $html .= '<td></td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>PT taker</td>';
    $html .= '<td></td>';
    $html .= '</tr>';             
    $html .= '</tbody></table>';

    echo $html;

    function count_payment_package($hour,$koc){
        $sql_count_payment = "
        SELECT count(j_payment.id)
        FROM
        j_payment
        INNER JOIN j_coursefee_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_coursefee_j_payment_1j_payment_idb
        AND l1_1.deleted = 0
        INNER JOIN j_coursefee l1 ON l1.id = l1_1.j_coursefee_j_payment_1j_coursefee_ida
        AND l1.deleted = 0
        INNER JOIN j_studentsituations l2 ON j_payment.id = l2.payment_id
        AND l2.deleted = 0
        INNER JOIN j_class l3 ON l2.ju_class_id = l3.id
        AND l3.deleted = 0
        INNER JOIN teams l4 ON l3.team_id = l4.id
        AND l4.deleted = 0
        WHERE
        (
        (
        (l1.type_of_course_fee = '$hour')
        AND (l3.kind_of_course = '$koc')
        AND (l4.team_type = 'Junior')
        )
        )
        AND j_payment.deleted = 0 
        ";
        $rs_count_pm= $GLOBALS['db']->getone($sql_count_payment);
        return $rs_count_pm;
    }
?>
