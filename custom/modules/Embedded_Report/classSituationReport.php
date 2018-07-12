<?php
global $timedate;

$filter = $this->where;
$parts = explode("AND", $filter);


for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "meetings.date_start>='") !== FALSE) $start_date = get_string_between($parts[$i]);
    if(strpos($parts[$i], "meetings.date_start<='") !== FALSE) $end_date     = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l2.id='") !== FALSE) $team_id = get_string_between($parts[$i]);
    if(strpos($parts[$i], "meetings.date_start='") !== FALSE){
        $start_date = get_string_between($parts[$i]);
        $end_date   = $start_date;
    }
}
$p_start_date   = date('Y-m-d', strtotime("+7 hours ".$start_date));
$p_end_date     = date('Y-m-d', strtotime("+7 hours ".$end_date));
$month          = date('m', strtotime($p_start_date));
$first_day_p_month  = date('Y-m-d', strtotime('first day of this month '.$p_start_date));
$last_day_p_month   = date('Y-m-d', strtotime('last day of this month '.$p_start_date));
if($p_start_date == $first_day_p_month && $p_end_date == $last_day_p_month){
    $l_start_date   = date('Y-m-d', strtotime('first day of last month '.$p_start_date));
    $l_end_date     = date('Y-m-d', strtotime('last day of last month '.$p_start_date));
}else{
    $daysdiffernce  = date_diff(date_create($p_start_date),date_create($p_end_date));
    $diff           = abs($daysdiffernce->format("%a")) + 1;
    $l_start_date   = date('Y-m-d', strtotime("-$diff day".$p_start_date));
    $l_end_date     = date('Y-m-d', strtotime("-$diff day".$p_end_date));
}

echo '<b>This Period: </b> '. date('d/m', strtotime($p_start_date)).' ➜ '.date('d/m', strtotime($p_end_date)).'   &nbsp;&nbsp;&nbsp;'."\n";
echo '<b>Last Period: </b> '. date('d/m', strtotime($l_start_date)).' ➜ '.date('d/m', strtotime($l_end_date)).'<br style="mso-data-placement:same-cell;"/>';

$class_list         = array();
$upgrade_list       = array();
$koc_list           = array();
$rows    = $GLOBALS['db']->fetchArray($this->query);
foreach( $rows as $key => $row){
    if(!in_array($row['l1_id'], array_keys($class_list)) ){
        $koc_list[$row['l1_kind_of_course']]       += 1;
        $class_list[$row['l1_id']]['class_id']      = $row['l1_id'];
        $class_list[$row['l1_id']]['koc']           = $row['l1_kind_of_course'];
        $class_list[$row['l1_id']]['module']        = $row['l1_modules'];
        $class_list[$row['l1_id']]['start_date']    = $timedate->to_display_date($row['l1_start_date'],false);
        $class_list[$row['l1_id']]['end_date']      = $timedate->to_display_date($row['l1_end_date'],false);
        $class_list[$row['l1_id']]['koclevel']      = $row['l1_kind_of_course'].' '.$row['l1_level'];
        $class_list[$row['l1_id']]['class_name']    = $row['l1_name'];
        $class_list[$row['l1_id']]['class_code']    = $row['l1_class_code'];
        $class_list[$row['l1_id']]['ec_in_charge']  = $row['l3_full_user_name'];
        $class_list[$row['l1_id']]['status']        = $row['l1_status'];
        $class_list[$row['l1_id']]['last_ss_id']    = $row['primaryid'];
        $class_list[$row['l1_id']]['last_ss_time']  = $row['meetings_date_start'];
        $class_list[$row['l1_id']]['last_ss_lesson']= $row['meetings_lesson_number'];
        $class_list[$row['l1_id']]['last_ss_id']            = $row['primaryid'];
        $class_list[$row['l1_id']]['upgrade_to_class_id']   = $row['l5_id'];
        $class_list[$row['l1_id']]['upgrade_to_class_name'] = $row['l5_name'];
        $class_list[$row['l1_id']]['upgrade_to_date']       = !empty($row['l5_date_entered']) ? $timedate->to_display_date_time($row['l5_date_entered']) : '';
        if(!empty($class_list[$row['l1_id']]['upgrade_to_class_id']))
            $upgrade_list[$row['l1_id']] = $class_list[$row['l1_id']]['upgrade_to_class_id'];
    }
    $class_list[$row['l1_id']]['hour_learned'] += $row['meetings_delivery_hour'];
    $class_list[$row['l1_id']]['lesson_per_week'] += 1;

    $this_start = strtotime('+ 7hour '.$row['meetings_date_start']);
    $this_end   = strtotime('+ 7hour '.$row['meetings_date_end']);
    $week_date  = date('D',$this_start);
    $time       = $week_date.": ".date('g:i',$this_start).'-'.date('g:ia',$this_end);


    if(strpos($class_list[$row['l1_id']]['schedule'], $time) === false){
        $class_list[$row['l1_id']]['schedule'] .= $time."<br style=\"mso-data-placement:same-cell;\"/> "."\n";
    }
    $posTea = strpos($class_list[$row['l1_id']]['teacher'], $row['l4_full_teacher_name']);
    if(!empty(trim($row['l4_full_teacher_name']))){
        if($posTea === false)
            $class_list[$row['l1_id']]['teacher']       .= $row['l4_full_teacher_name']." ($week_date); "."<br style=\"mso-data-placement:same-cell;\"/> "."\n";
        else{
            $reSche   = strpos($class_list[$row['l1_id']]['teacher'], '(', $posTea);
            $posSche  = strpos($class_list[$row['l1_id']]['teacher'], ')', $posTea);
            $sche     = substr($class_list[$row['l1_id']]['teacher'], $reSche, $posSche - $reSche);
            if(strpos($sche, $week_date) === false){
                $newstr     = substr_replace($class_list[$row['l1_id']]['teacher'], ','.$week_date, $posSche, 0);
                $class_list[$row['l1_id']]['teacher'] = $newstr;
            }
        }
    }


}
$q2 = "SELECT DISTINCT
IFNULL(l1.id, '') class_id,
COUNT(IFNULL(meetings.id, '')) count_ss
FROM
meetings
INNER JOIN
j_class l1 ON meetings.ju_class_id = l1.id
AND l1.deleted = 0
WHERE
(((l1.id IN ('".implode("','",array_keys($class_list))."')  )
AND (meetings.session_status <> 'Cancelled')))
AND meetings.deleted = 0
GROUP BY l1.id";
$rs2 = $GLOBALS['db']->query($q2);
$ssClass = array();
$cr_class = '';
while($ss = $GLOBALS['db']->fetchByAssoc($rs2) )
    $class_list[$ss['class_id']]['total_lesson'] = $ss['count_ss'];

$l_paid         = get_count_payment(array_keys($class_list), $l_start_date, $l_end_date, 'sponsor0');
$l_sponsor20    = get_count_payment(array_keys($class_list), $l_start_date, $l_end_date, 'sponsor20');
$l_sponsor100   = get_count_payment(array_keys($class_list), $l_start_date, $l_end_date, 'sponsor100');
$l_unpaid       = get_count_payment(array_keys($class_list), $l_start_date, $l_end_date, 'Unpaid');

$p_paid         = get_count_payment(array_keys($class_list), $p_start_date, $p_end_date,'sponsor0');
$p_sponsor20    = get_count_payment(array_keys($class_list), $p_start_date, $p_end_date,'sponsor20');
$p_sponsor100   = get_count_payment(array_keys($class_list), $p_start_date, $p_end_date,'sponsor100');
$p_unpaid       = get_count_payment(array_keys($class_list), $p_start_date, $p_end_date,'Unpaid');

$p_total_amount = get_count_payment(array_keys($class_list), $p_start_date, $p_end_date,'total');
$p_newstudent   = get_count_payment(array_keys($class_list), $p_start_date, $p_end_date,'newstudent');
$p_retention    = get_count_payment(array_keys($class_list), $p_start_date, $p_end_date,'retention');

if(count($upgrade_list) > 0)
    $u_paid         = get_count_payment(array_values($upgrade_list), '', '', 'Upgrade');
foreach ($class_list as $class_id => $class_obj) {
    $class_list[$class_id]['l_paid']        = $l_paid[$class_id];
    $class_list[$class_id]['l_sponsor20']   = $l_sponsor20[$class_id];
    $class_list[$class_id]['l_sponsor100']  = $l_sponsor100[$class_id];
    $class_list[$class_id]['l_unpaid']      = $l_unpaid[$class_id];
    $class_list[$class_id]['l_total']       = $l_paid[$class_id] + $l_sponsor20[$class_id] + $l_sponsor100[$class_id] + $l_unpaid[$class_id];
    $class_list[$class_id]['l_acs']         = $class_list[$class_id]['l_total'];

    $class_list[$class_id]['p_paid']        = $p_paid[$class_id];
    $class_list[$class_id]['p_sponsor20']   = $p_sponsor20[$class_id];
    $class_list[$class_id]['p_sponsor100']  = $p_sponsor100[$class_id];
    $class_list[$class_id]['p_unpaid']      = $p_unpaid[$class_id];
    $class_list[$class_id]['p_total']       = $p_paid[$class_id] + $p_sponsor20[$class_id] + $p_sponsor100[$class_id] + $p_unpaid[$class_id];
    $class_list[$class_id]['p_acs']         = $class_list[$class_id]['p_total'];

    $class_list[$class_id]['p_total_amount']= $p_total_amount[$class_id]['total'];
    $class_list[$class_id]['p_total_paid']  = $p_total_amount[$class_id]['total_paid'];
    $class_list[$class_id]['p_total_unpaid']= $p_total_amount[$class_id]['total_unpaid'];
    $class_list[$class_id]['p_newstudent']  = $p_newstudent[$class_id];
    $class_list[$class_id]['p_retention']   = $p_retention[$class_id];

    $class_list[$class_id]['u_paid']        = $u_paid[$upgrade_list[$class_id]];

    $class_list[$class_id] = array_map(function($value) {
        return $value === NULL ? 0 : $value;
        }, $class_list[$class_id]); // array_map should walk through $array
}

$kocc_list = array();
$html = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
foreach ($class_list as $class_id => $val) {
    $kocc_list[$val['koc']] += 1;
    if($kocc_list[$val['koc']] == 1){
        $html .= get_table_head($val['koc']);
        $no = 1;
        $l_paidt    = 0;
        $l_unpaidt = 0;
        $l_sponsor20t    = 0;
        $l_sponsor100t   = 0;
        $l_totalt        = 0;
        $p_paidt    = 0;
        $p_unpaidt = 0;
        $p_sponsor20t = 0;
        $p_sponsor100t = 0;
        $p_totalt = 0;
        $l_acst= 0;
        $p_acst= 0;

        $p_total_amountt= 0;
        $p_total_paidt= 0;
        $p_total_unpaidt= 0;
        $p_newstudentt= 0;
        $p_retentiont= 0;
    }
    $html .= "<tr><td valign='TOP' class='oddListRowS1'>$no</td>";
    $html .= "<td valign='TOP' class='oddListRowS1'>{$val['koclevel']}</td>";
    $html .= "<td valign='TOP' class='oddListRowS1'>".'<a href=index.php?module=J_Class&offset=1&stamp=1441785563066827100&return_module=J_Class&action=DetailView&record='.$val['class_id'].' target=_blank>'.$val['class_name'].'</a>'."</td>";
    $html .= "<td valign='TOP' class='oddListRowS1'>{$val['class_code']}</td>";
    $html .= "<td valign='TOP' class='oddListRowS1'>{$val['status']}</td>";
    $html .= "<td valign='TOP' class='oddListRowS1'>{$val['ec_in_charge']}</td>";
    $html .= "<td valign='TOP' style='mso-number-format:\@;' class='oddListRowS1'>{$val['schedule']}</td>";
    $html .= "<td valign='TOP' style='mso-number-format:\@;text-align: center;' class='oddListRowS1'>".$val['last_ss_lesson'] .'/'.$val['total_lesson']."</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>".format_number($val['hour_learned'],2,2)."</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['lesson_per_week']}</td>";

    $html .= "<td valign='TOP' style='mso-number-format:\"dd/mm/yyyy\";text-align: left;' class='oddListRowS1'>{$val['start_date']}</td>";
    $html .= "<td valign='TOP' style='mso-number-format:\"dd/mm/yyyy\";text-align: left;' class='oddListRowS1'>{$val['end_date']}</td>";

    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['l_paid']}</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['l_unpaid']}</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['l_sponsor20']}</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['l_sponsor100']}</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['l_total']}</td>";

    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['p_paid']}</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['p_unpaid']}</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['p_sponsor20']}</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['p_sponsor100']}</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['p_total']}</td>";


    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['l_acs']}</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['p_acs']}</td>";

    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>".format_number($val['p_total_amount'])."</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>".format_number($val['p_total_paid'])."</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>".format_number($val['p_total_unpaid'])."</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['p_newstudent']}</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['p_retention']}</td>";

    $html .= "<td valign='TOP' class='oddListRowS1'>".rtrim($val['teacher'], '; ')."</td>";
    $html .= "<td valign='TOP' class='oddListRowS1'>{$val['teacher_type']}</td>";

    $html .= "<td valign='TOP' class='oddListRowS1'>".'<a href=index.php?module=J_Class&offset=1&stamp=1441785563066827100&return_module=J_Class&action=DetailView&record='.$val['upgrade_to_class_id'].' target=_blank>'.$val['upgrade_to_class_name'].'</a>'."</td>";
    $html .= "<td valign='TOP' class='oddListRowS1' style='mso-number-format:\"dd/mm/yyyy h:mm\";text-align: left;'>{$val['upgrade_to_date']}</td>";
    $html .= "<td valign='TOP' style='text-align: center;' class='oddListRowS1'>{$val['u_paid']}</td>";
    $html .= "<td valign='TOP' class='oddListRowS1'>     </td>";
    $html .= "<td valign='TOP' class='oddListRowS1'>     </td></tr>";


    $l_paidt    += $val['l_paid'];
    $l_unpaidt  += $val['l_unpaid'];
    $l_sponsor20t    += $val['l_sponsor20'];
    $l_sponsor100t   += $val['l_sponsor100'];
    $l_totalt        += $val['l_total'];
    $p_paidt    += $val['p_paid'];
    $p_unpaidt  += $val['p_unpaid'];
    $p_sponsor20t    += $val['p_sponsor20'];
    $p_sponsor100t   += $val['p_sponsor100'];
    $p_totalt        += $val['p_total'];
    $l_acst        += $val['l_acs'];
    $p_acst        += $val['p_acs'];

    $p_total_amountt += $val['p_total_amount'];
    $p_total_paidt += $val['p_total_paid'];
    $p_total_unpaidt+= $val['p_total_unpaid'];
    $p_newstudentt+= $val['p_newstudent'];
    $p_retentiont+= $val['p_retention'];

    if($kocc_list[$val['koc']] == $koc_list[$val['koc']]){
        $html .= "<tr><td colspan='12' style='text-align: right;'><h3> Total: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3></td>";
        $html .= "<td><b>".$l_paidt."</b></td>";
        $html .= "<td><b>".$l_unpaidt."</b></td>";
        $html .= "<td><b>".$l_sponsor20t."</b></td>";
        $html .= "<td><b>".$l_sponsor100t."</b></td>";
        $html .= "<td><b>".$l_totalt."</b></td>";

        $html .= "<td><b>".$p_paidt."</b></td>";
        $html .= "<td><b>".$p_unpaidt."</b></td>";
        $html .= "<td><b>".$p_sponsor20t."</b></td>";
        $html .= "<td><b>".$p_sponsor100t."</b></td>";
        $html .= "<td><b>".$p_totalt."</b></td>";


        $html .= "<td><b>".format_number($l_acst / $no,2,2)."</b></td>";
        $html .= "<td><b>".format_number($p_acst / $no,2,2)."</b></td>";

        $html .= "<td><b>".format_number($p_total_amountt)."</b></td>";
        $html .= "<td><b>".format_number($p_total_paidt)."</b></td>";
        $html .= "<td><b>".format_number($p_total_unpaidt)."</b></td>";
        $html .= "<td><b>".$p_newstudentt."</b></td>";
        $html .= "<td><b>".$p_retentiont."</b></td>";

        $html .= "<td></td>";
        $html .= "<td></td>";
        $html .= "<td></td>";
        $html .= "<td></td>";
        $html .= "<td></td>";
        $html .= "<td></td>";
        $html .= "<td></td></tr>";
    }
    $no++;
}
if(count($class_list) == 0)
    $html .= "<td colspan='36'>No Results!!</td>";
$html .= "</tbody></table>";

echo $html;
//##############------------------------------------######################-----------------------

function get_count_payment($class_ids, $start = '', $end = '', $type = ''){
    $ext_start = "AND (l5.session_status <> 'Cancelled')";
    if(!empty($start)){
        $start_tz     = date('Y-m-d H:i:s',strtotime("-7 hours ".$start." 00:00:00"));
        $ext_start .= " AND (l5.date_start >= '$start_tz') ";
    }
    $ext_end = '';
    if(!empty($end)){
        $end_tz     = date('Y-m-d H:i:s',strtotime("-7 hours ".$end." 23:59:59"));
        $ext_end = " AND (l5.date_start <= '$end_tz') ";
    }
    $ext_sponsor = '';
    if($type == 'sponsor0')
        $ext_sponsor = 'AND (j_studentsituations.total_amount > 0)  AND ((((( l2.total_after_discount - l2.amount_bef_discount) /l2.total_after_discount) * 100) < 20) OR (l2.paid_hours = l2.tuition_hours)) '; //0 -> 20
    if($type == 'sponsor20')
        $ext_sponsor = 'AND (j_studentsituations.total_amount > 0 ) AND (((( l2.total_after_discount - l2.amount_bef_discount) /l2.total_after_discount) * 100) >= 20)'; //20 -> 100
    if($type == 'sponsor100')
        $ext_sponsor = 'AND (j_studentsituations.total_amount = 0 )'; //100% Sponsor
    if($type == 'Upgrade') //Count Paid in Upgrade Class
        $ext_sponsor = '';

    $ext_status = "AND (l4.status = 'Paid')";
    if($type == 'Unpaid')
        $ext_status = "AND (l4.status = 'Unpaid')";

    $ext_newstu = '';
    if($type == 'newstudent'){
        $ext_status = '';
        $ext_newstu = "AND (l2.sale_type IN('New Sale', 'Not set'))";
    }

    if($type == 'retention'){
        $ext_status = '';
        $ext_newstu = "AND (l2.sale_type IN('Retention'))";
    }

    $ext_select = '';
    $ext_JOIN_schedule = "INNER JOIN
    j_paymentdetail l4 ON l2.id = l4.payment_id AND l4.deleted = 0 $ext_status
    INNER JOIN
    meetings_contacts l5_1 ON j_studentsituations.id = l5_1.situation_id
    AND l5_1.deleted = 0
    INNER JOIN
    meetings l5 ON l5.id = l5_1.meeting_id
    AND l5.deleted = 0";
    if($type == 'total'){
        $ext_select = ",SUM(IFNULL(l2.paid_amount + l2.deposit_amount, 0)) sum_payment_paid,
        SUM(IFNULL(l2.paid_amount + l2.deposit_amount + l2.payment_amount, 0)) sum_total_amount";
        $ext_JOIN_schedule = "";
        $ext_end    = '';
        $ext_start  = '';
        //Sum Paid Payment Detail
        $sql2 = "SELECT DISTINCT
        IFNULL(l1.id, '') class_id,
        SUM(IFNULL(l4.payment_amount, 0)) sum_payment_detail
        FROM
        j_studentsituations
        INNER JOIN
        j_class l1 ON j_studentsituations.ju_class_id = l1.id
        AND l1.deleted = 0
        INNER JOIN
        j_payment l2 ON j_studentsituations.payment_id = l2.id
        AND l2.deleted = 0
        LEFT JOIN
        j_paymentdetail l4 ON l2.id = l4.payment_id AND l4.deleted = 0
        AND (l4.status = 'Paid')
        WHERE
        (((j_studentsituations.type IN ('Enrolled' , 'Settle', 'Moving In'))
        AND (l1.id IN ('".implode("','",$class_ids)."'))))
        AND j_studentsituations.deleted = 0
        GROUP BY l1.id
        ORDER BY l1.id ASC";
        $rs2 = $GLOBALS['db']->query($sql2);
        $sum_pmd = array();
        while($row2 = $GLOBALS['db']->fetchByAssoc($rs2)){
          $sum_pmd[$row2['class_id']] = $row2['sum_payment_detail'];
        }
    }

    $sql = "SELECT DISTINCT
    IFNULL(l1.id, '') class_id,
    COUNT(DISTINCT l3.id) count_student$ext_select
    FROM
    j_studentsituations
    INNER JOIN
    j_class l1 ON j_studentsituations.ju_class_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    j_payment l2 ON j_studentsituations.payment_id = l2.id
    AND l2.deleted = 0
    INNER JOIN
    contacts l3 ON j_studentsituations.student_id = l3.id
    AND l3.deleted = 0
    $ext_JOIN_schedule
    WHERE
    (((j_studentsituations.type IN ('Enrolled' , 'Settle', 'Moving In'))
    AND (l1.id IN ('".implode("','",$class_ids)."'))
    $ext_start
    $ext_end
    $ext_sponsor
    $ext_newstu
    ))
    AND j_studentsituations.deleted = 0
    GROUP BY l1.id
    ORDER BY l1.id ASC";
    $rs = $GLOBALS['db']->query($sql);
    $res = array();

    while($row = $GLOBALS['db']->fetchByAssoc($rs)){
        if($type == 'total'){
            $res[$row['class_id']]['total']         = $row['sum_total_amount'];
            $res[$row['class_id']]['total_paid']    = $sum_pmd[$row['class_id']] + $row['sum_payment_paid'];
            $total_unpaid = $res[$row['class_id']]['total'] - $res[$row['class_id']]['total_paid'];
            if($total_unpaid <= 0) $total_unpaid = 0;
            $res[$row['class_id']]['total_unpaid']  = $total_unpaid;
        }else
            $res[$row['class_id']]   = $row['count_student'];
    }


    return $res;
}

function get_table_head($koc = ''){
    $html = "<tr><td colspan='38' style='text-align: left;'><h3 style='color: #000 !important;'>".strtoupper($koc)." CLASSES</h3></td></tr>";
    $html .= '<tr><th rowspan="3" align="center" class="reportlistViewThS1" valign="middle" nowrap="">No.</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Level</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Class Name</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Class Code</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Class Status</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle" nowrap="">EC In Charge</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" style="width: 150px;" valign="middle" nowrap="">Schedule</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle">Lesson <br style="mso-data-placement:same-cell;"/>Studied</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle">Hours learned <br style="mso-data-placement:same-cell;"/>/period</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle">No of lesson/ <br style="mso-data-placement:same-cell;"/>period</th>';


    $html .= '<th colspan="2" rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Time</th>';
    $html .= '<th colspan="10" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Number of Student</th>';
    $html .= '<th colspan="2" rowspan="2" align="center" class="reportlistViewThS1" valign="middle" nowrap="">ACS</th>';

    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Total Amount</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Total Paid</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Total Unpaid</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle" nowrap="">New Students</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Retention Student</th>';


    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" style="width: 150px;" valign="middle" nowrap="">Teacher</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle">Teacher <br style="mso-data-placement:same-cell;"/>Type</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle">Class <br style="mso-data-placement:same-cell;"/>Upgrade</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle">Upgrade <br style="mso-data-placement:same-cell;"/>Date</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle" style="width: 70px;">No of <br style="mso-data-placement:same-cell;"/>paid in <br style="mso-data-placement:same-cell;"/>upgrading <br style="mso-data-placement:same-cell;"/>class</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle">Opening <br style="mso-data-placement:same-cell;"/>probability</th>';
    $html .= '<th rowspan="3" align="center" class="reportlistViewThS1" valign="middle" nowrap="">Note</th></tr>';

    $html .= '<tr><th colspan="5" align="center" valign="middle" nowrap="">Last period</th>';
    $html .= '<th colspan="5" align="center" valign="middle" nowrap="">This period</th></tr>';

    $html .= '<tr><th align="center" valign="middle" nowrap="">Start</th>';
    $html .= '<th align="center" valign="middle" nowrap="">Finish</th>';

    $html .= '<th align="center" valign="middle" nowrap="">Paid</th>';
    $html .= '<th align="center" valign="middle" nowrap="">Unpaid</th>';
    $html .= '<th align="center" valign="middle">Sponsor <br style="mso-data-placement:same-cell;"/>20➜100%</th>';
    $html .= '<th align="center" valign="middle">Sponsor <br style="mso-data-placement:same-cell;"/>100%</th>';
    $html .= '<th align="center" valign="middle" nowrap="">Total</th>';

    $html .= '<th align="center" valign="middle" nowrap="">Paid</th>';
    $html .= '<th align="center" valign="middle" nowrap="">Unpaid</th>';
    $html .= '<th align="center" valign="middle">Sponsor <br style="mso-data-placement:same-cell;"/>20➜100%</th>';
    $html .= '<th align="center" valign="middle">Sponsor <br style="mso-data-placement:same-cell;"/> 100%</th>';
    $html .= '<th align="center" valign="middle" nowrap="">Total</th>';

    $html .= '<th align="center" valign="middle" nowrap="">Last period</th>';
    $html .= '<th align="center" valign="middle" nowrap="">This period</th></tr>';
    return $html;
}