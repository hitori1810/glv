<?php
    $filter = $this->where;
    $parts = explode("AND", $filter);

    $start = get_string_between($parts[0]);
    $end     = get_string_between($parts[1]);

    global $db, $timedate;
    $start_date = $timedate->to_db_date($timedate->to_display_date($start),false);
    $end_date = $timedate->to_db_date($timedate->to_display_date($end),false);

    $last_start_date = date('Y-m-d',strtotime("-1 years $start_date"));
    $last_end_date = date('Y-m-d',strtotime("-1 years $end_date"));

    $year_compare = date('Y',strtotime($last_start_date));
    $year_this = date('Y',strtotime($start_date));
    $centers = getCenterIds($parts['2']);

    $getTagert = "SELECT team_id, SUM(input_value) as target
    FROM j_targetconfig
    WHERE deleted = 0 AND `year` = '$year_this' AND type_targetconfig_list='New Sale'
    GROUP BY team_id";
    $targets = $db->query($getTagert) ;
    $sqlcenterCode = "SELECT * FROM teams WHERE deleted = 0";
    $result = $db->query($sqlcenterCode);
    $centerList = array();

    while($center = $db->fetchByAssoc($result)) {
        $centerList[$center['id']] = $center;
    }
    while($target= $db->fetchByAssoc($targets)) {
        $centerList[$target['team_id']]['target'] = $target['target'];
    }

    $sql = "(SELECT month(l.date_entered) month_create,
    year(l.date_entered) year_create, l.team_id center,
    CONCAT( CASE month(l.date_entered)
    WHEN 1 THEN 'Jan'
    WHEN 2 THEN 'Feb'
    WHEN 3 THEN 'Mar'
    WHEN 4 THEN 'Apr'
    WHEN 5 THEN 'May'
    WHEN 6 THEN 'Jun'
    WHEN 7 THEN 'Jul'
    WHEN 8 THEN 'Aug'
    WHEN 9 THEN 'Sep'
    WHEN 10 THEN 'Oct'
    WHEN 11 THEN 'Nov'
    WHEN 12 THEN 'Dec'
    END, ' - ', year(l.date_entered)) date_create,
    count(l.id) number_enquiries
    FROM leads l
    WHERE l.deleted = 0
    AND l.date_entered BETWEEN '$start_date' AND '$end_date'
    AND l.team_id IN ('".implode("','",$centers)."')
    GROUP BY year_create, month_create, center
    ORDER BY year_create, month_create)
    UNION
    (SELECT 'month' month_create, 'year' year_create, l.team_id center,
    'compare' date_create, count(l.id) number_enquiries
    FROM leads l
    WHERE l.deleted = 0
    AND l.date_entered BETWEEN '$last_start_date' AND '$last_end_date'
    AND l.team_id IN ('".implode("','",$centers)."')
    GROUP BY year_create, month_create, center)
    ";
    $result = $db->query($sql);
    $data_student = array();
    $displayCenter = array();
    while($row = $db->fetchByAssoc($result)) {
        $data_student[$row['date_create']][$row['center']] = $row['number_enquiries'];
        if($row['date_create'] != 'compare') {
            $data_student['total'][$row['center']] += $row['number_enquiries'];

            if($centerList[$row['center']]['team_type'] == 'Adult') {
                $data_student['total']['this_adult'] += $row['number_enquiries']; // sum ad
            }else {
                $data_student['total']['this_junior'] += $row['number_enquiries']; // sum ju
            }
            $data_student['total']['this'] += $row['number_enquiries'];   //sum ad + ju
        } else {
            if($centerList[$row['center']]['team_type'] == 'Adult') {
                $data_student['total']['compare_adult'] += $row['number_enquiries']; //sum ad last year
            }else {
                $data_student['total']['compare_junior'] += $row['number_enquiries'];  //sum ju last year
            }
            $data_student['total']['compare'] += $row['number_enquiries']; //sum ju + ad last year
        }

        if(!in_array($row['center'],$displayCenter))  $displayCenter[] = $row['center'];      //center show in colum
    }

    $table1 = "<table <table class=\"reportDataChildtablelistView\" width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" border=\"1\" >
    <thead>
    <tr style='height: 30px;'>
    <th valign='middle' align='center' scope='col' style='background:yellow !important;'>
    Number of enquiries
    </th> ";
    $total_row = "<tr><td valign='middle' align='center' scope='col' style='background:#FFEB9C !important; font-weight:bold;'>Total</td>";
    $compare_row = "<tr><td valign='middle' align='center' scope='col' style='background:#C6EFCE !important; font-weight:bold;'>Compare to ".$year_compare."</td>";
    $diff_row = "<tr><td valign='middle' align='center' scope='col' style='background:#FFC7CE !important; font-weight:bold;'>Difference</td>";
    for($i=0; $i<count($displayCenter); $i++) {
        $table1.= "<th valign='middle' nowrap='' align='center' scope='col' style='background:#00B0F0 !important;'>
        ".($centerList[$displayCenter[$i]]['short_name']?$centerList[$displayCenter[$i]]['short_name']:$centerList[$displayCenter[$i]]['name'])."</th>";
        $total_row .= "<td valign='middle' align='center' scope='col' style='background:#FFEB9C !important; font-weight:bold;'>".($data_student['total'][$displayCenter[$i]] + 0)."</td>";
        $compare_row .= "<td valign='middle' align='center' scope='col' style='background:#C6EFCE !important; font-weight:bold;'>".($data_student['compare'][$displayCenter[$i]] + 0)."</td>";
        $diff_row .= "<td valign='middle' align='center' scope='col' style='background:#FFC7CE !important; font-weight:bold;'>".($data_student['total'][$displayCenter[$i]] - $data_student['compare'][$displayCenter[$i]] + 0)."</td>";
    }
    $total_row .= "</tr>";
    $compare_row .= "</tr>";
    $diff_row .= "</tr>";
    $table1.= "</tr></thead><tbody>";
    $next = date('Y-m-01',strtotime($start_date));
    while(1) {
        $month_year = date('M - Y',strtotime($next));
        $table1.= "<tr><td valign='middle' nowrap='' align='center' scope='col' >$month_year</td>";
        for($i=0; $i < count($displayCenter); $i++) {
            $table1.= "<td valign='middle' nowrap='' align='center' scope='col'>"
            .($data_student[$month_year][$displayCenter[$i]] + 0).
            "</td>";
        }
        $table1.= "</tr>";
        if($month_year == date('M - Y',strtotime($end_date))) break;
        else $next = date('Y-m-d',strtotime("+1 months $next"));
    }
    $table1.= "</tr>"
    .$total_row
    .$compare_row
    .$diff_row
    ."</tbody>
    </table>
    <br>
    ";
    echo $table1;

    //tabel 2
    $table2 = "<table <table class=\"reportDataChildtablelistView\" width=\"33%\" cellspacing=\"1\" cellpadding=\"1\" border=\"1\" >
    <thead>
    <tr style='height: 30px;'>
    <th valign='middle' align='center' scope='col' style='background:yellow !important;'>Number of enquiries </th>
    <th valign='middle' align='center' scope='col' style='background:#FFEB9C !important;'>$year_this</th>
    <th valign='middle' align='center' scope='col' style='background:#FFEB9C !important;'>$year_compare</th>
    <th valign='middle' align='center' scope='col' style='background:#FFEB9C !important;'>$year_this vs $year_compare</th>
    </tr>
    </thead>
    <tbody>
    <tr style='height: 30px;'>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>Total Adult</td>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>".($data_student['total']['this_adult'] + 0)."</td>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>".($data_student['total']['compare_adult'] + 0)."</td>
    <td valign='middle' align='center' scope='col' style='background:#FFC7CE !important;'>"
    .($data_student['total']['compare_adult'] ? format_number(($data_student['total']['this_adult'] - $data_student['total']['compare_adult'])/$data_student['total']['compare_adult']*100,0,0)."%":"N/A")."</td>
    </tr>
    <tr style='height: 30px;'>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>Total Junior</td>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>".($data_student['total']['this_junior'] + 0)."</td>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>".($data_student['total']['compare_junior'] +0)."</td>
    <td valign='middle' align='center' scope='col' style='background:#FFC7CE !important;'>"
    .($data_student['total']['compare_junior'] ? format_number(($data_student['total']['this_junior'] - $data_student['total']['compare_junior'])/$data_student['total']['compare_junior']*100,0,0)."%":"N/A")."</td>
    </tr>
    <tr style='height: 30px;'>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>Total Inquiries</td>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>".($data_student['total']['this'] + 0)."</td>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>".($data_student['total']['compare'] + 0)."</td>
    <td valign='middle' align='center' scope='col' style='background:#FFC7CE !important;'>"
    . ($data_student['total']['compare'] ? format_number(($data_student['total']['this'] - $data_student['total']['compare'])/$data_student['total']['compare']*100,0,0)."%":"N/A")."</td>
    </tr>
    </tbody>
    </table>
    <br> <br>";
    echo $table2;

    $sql = "(SELECT month(l.payment_date) month_create,
    year(l.payment_date) year_create, l.team_id center,
    CONCAT( CASE month(l.payment_date)
    WHEN 1 THEN 'Jan'
    WHEN 2 THEN 'Feb'
    WHEN 3 THEN 'Mar'
    WHEN 4 THEN 'Apr'
    WHEN 5 THEN 'May'
    WHEN 6 THEN 'Jun'
    WHEN 7 THEN 'Jul'
    WHEN 8 THEN 'Aug'
    WHEN 9 THEN 'Sep'
    WHEN 10 THEN 'Oct'
    WHEN 11 THEN 'Nov'
    WHEN 12 THEN 'Dec'
    END, ' - ', year(l.payment_date)) date_create,
    count(l.id) number_newsale
    FROM j_payment l
    WHERE l.deleted = 0 AND l.sale_type = 'New Sale'
    AND l.payment_date BETWEEN '$start_date' AND '$end_date'
    AND l.team_id IN ('".implode("','",$centers)."')
    GROUP BY year_create, month_create, center
    ORDER BY year_create, month_create)
    UNION
    (SELECT month(l.date_closed) month_create,
    year(l.date_closed) year_create, l.team_id center,
    CONCAT( CASE month(l.date_closed)
    WHEN 1 THEN 'Jan'
    WHEN 2 THEN 'Feb'
    WHEN 3 THEN 'Mar'
    WHEN 4 THEN 'Apr'
    WHEN 5 THEN 'May'
    WHEN 6 THEN 'Jun'
    WHEN 7 THEN 'Jul'
    WHEN 8 THEN 'Aug'
    WHEN 9 THEN 'Sep'
    WHEN 10 THEN 'Oct'
    WHEN 11 THEN 'Nov'
    WHEN 12 THEN 'Dec'
    END, ' - ', year(l.date_closed)) date_create,
    count(l.id) number_newsale
    FROM opportunities l
    WHERE l.deleted = 0 AND l.opportunity_type = 'New Sale' AND l.sales_stage != 'Deleted'
    AND l.date_closed BETWEEN '$start_date' AND '$end_date'
    AND l.team_id IN ('".implode("','",$centers)."')
    GROUP BY year_create, month_create, center
    ORDER BY year_create, month_create)
    UNION
    (SELECT 'month' month_create, 'year' year_create, l.team_id center,
    'compare' date_create, count(l.id) number_newsale
    FROM j_payment l
    WHERE l.deleted = 0 AND l.sale_type = 'New Sale'
    AND l.payment_date BETWEEN '$last_start_date' AND '$last_end_date'
    AND l.team_id IN ('".implode("','",$centers)."')
    GROUP BY year_create, month_create, center)
    UNION
    (SELECT 'month' month_create, 'year' year_create, l.team_id center,
    'compare' date_create, count(l.id) number_newsale
    FROM opportunities l
    WHERE l.deleted = 0 AND l.opportunity_type = 'New Sale' AND l.sales_stage != 'Deleted'
    AND l.date_closed BETWEEN '$last_start_date' AND '$last_end_date'
    AND l.team_id IN ('".implode("','",$centers)."')
    GROUP BY year_create, month_create, center)
    ";
    $result = $db->query($sql);
    $sale_data = array();
    $displayCenter = array();
    while($row = $db->fetchByAssoc($result)) {
        $sale_data[$row['date_create']][$row['center']] += $row['number_newsale'];//center trong 1 thang
        $sale_data['total_month'][$row['date_create']] += $row['number_newsale'];//tong thang cua tat ca center

        if($row['date_create'] != 'compare') {
            $sale_data['total_center'][$row['center']] += $row['number_newsale'];

            if($centerList[$row['center']]['team_type'] == 'Adult') {
                $sale_data['total_this_adult'] += $row['number_newsale'];
            }else {
                $sale_data['total_this_junior'] += $row['number_newsale'];
            }
            $sale_data['total_center']['this'] += $row['number_newsale'];
        } else {
            if($centerList[$row['center']]['team_type'] == 'Adult') {
                $sale_data['total_compare_adult'] += $row['number_newsale'];
            }else {
                $sale_data['total_compare_junior'] += $row['number_newsale'];
            }
        }

        if(!in_array($row['center'],$displayCenter))  $displayCenter[] = $row['center'];
    }
    $table1 = "<table <table class=\"reportDataChildtablelistView\" width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" border=\"1\" >
    <thead>
    <tr style='height: 30px; '>
    <th valign='middle' align='center' scope='col' style='background:yellow !important; width: 15%;'>
    Number of new sales
    </th> ";
    $total_row = "<tr><td valign='middle' align='center' scope='col' style='background:#FFEB9C !important; font-weight:bold;'>Total</td>";
    $compare_row = "<tr><td valign='middle' align='center' scope='col' style='background:#C6EFCE !important; font-weight:bold;'>Compare to ".$year_compare."</td>";
    $compare_target = "<tr><td valign='middle' align='center' scope='col' style='background:#C6EFCE !important; font-weight:bold;'>Compare to Target</td>";
    $diff_row = "<tr><td valign='middle' align='center' scope='col' style='background:#FFC7CE !important; font-weight:bold;'>Difference to ".$year_compare."</td>";
    $diff_target = "<tr><td valign='middle' align='center' scope='col' style='background:#FFC7CE !important; font-weight:bold;'>Difference to Target</td>";
    $rate_center = "<tr><td valign='middle' align='center' scope='col' style='background:yellow !important; font-weight:bold;'>Conversion rate/ center</td>";
    $total_target = 0;
    for($i=0; $i<count($displayCenter); $i++) {
        $table1.= "<th valign='middle' nowrap='' align='center' scope='col' style='background:#00B0F0 !important;'>
        ".($centerList[$displayCenter[$i]]['short_name']?$centerList[$displayCenter[$i]]['short_name']:$centerList[$displayCenter[$i]]['name'])."</th>";
        $total_row .= "<td valign='middle' align='center' scope='col' style='background:#FFEB9C !important; font-weight:bold;'>".($sale_data['total_center'][$displayCenter[$i]] + 0)."</td>";
        $compare_row .= "<td valign='middle' align='center' scope='col' style='background:#C6EFCE !important; font-weight:bold;'>".($sale_data['compare'][$displayCenter[$i]] + 0)."</td>";
        $compare_target .= "<td valign='middle' align='center' scope='col' style='background:#C6EFCE !important; font-weight:bold;'>".($centerList[$displayCenter[$i]]['target'] + 0)."</td>";
        $diff_row .= "<td valign='middle' align='center' scope='col' style='background:#FFC7CE !important; font-weight:bold;'>".($sale_data['total_center'][$displayCenter[$i]] - $sale_data['compare'][$displayCenter[$i]] + 0)."</td>";
        $diff_target .= "<td valign='middle' align='center' scope='col' style='background:#FFC7CE !important; font-weight:bold;'>".($sale_data['total_center'][$displayCenter[$i]] - $centerList[$displayCenter[$i]]['target'] + 0)."</td>";
        $rate_center .= "<td valign='middle' align='center' scope='col' style='background:yellow !important; font-weight:bold;'>".($data_student['total'][$displayCenter[$i]]?(format_number($sale_data['total_center'][$displayCenter[$i]]/$data_student['total'][$displayCenter[$i]]*100,0,0))."%":"N/A")."</td>";

        $total_target += $centerList[$displayCenter[$i]]['target'];
    }
    $total_row .= "<td valign='middle' align='center' scope='col' style='background:#FFEB9C !important; font-weight:bold;'>".($sale_data['total_center']['this'] + 0)."</td></tr>";
    $compare_row .= "<td valign='middle' align='center' scope='col' style='background:#C6EFCE !important; font-weight:bold;'>".($sale_data['total_month']['compare'] + 0)."</td></tr>";
    $compare_target .= "<td valign='middle' align='center' scope='col' style='background:#C6EFCE !important; font-weight:bold;'>".($total_target + 0)."</td></tr>";
    $diff_row .= "<td valign='middle' align='center' scope='col' style='background:#FFC7CE !important; font-weight:bold;'>".($sale_data['total_center']['this'] - $sale_data['total_month']['compare'] + 0)."</td></tr>";
    $diff_target .= "<td valign='middle' align='center' scope='col' style='background:#FFC7CE !important; font-weight:bold;'>".($sale_data['total_center']['this'] - $total_target + 0)."</td></tr>";
    $rate_center .= "<td valign='middle' align='center' scope='col' style='background:yellow !important; font-weight:bold;'>".($data_student['total']['this']?(format_number($sale_data['total_center']['this']/$data_student['total']['this']*100,0,0))."%":"N/A")."</td></tr>";
    $table1.= "<th valign='middle' nowrap='' align='center' scope='col' style='background:#00B0F0 !important;'>Total</th></tr></thead><tbody>";
    $next = date('Y-m-01',strtotime($start_date));
    while(1) {
        $month_year = date('M - Y',strtotime($next));
        $table1.= "<tr><td valign='middle' nowrap='' align='center' scope='col' >$month_year</td>";
        for($i=0; $i < count($displayCenter); $i++) {
            $table1.= "<td valign='middle' nowrap='' align='center' scope='col'>"
            .($sale_data[$month_year][$displayCenter[$i]] + 0).
            "</td>";
        }
        $table1.= "<td valign='middle' nowrap='' align='center' scope='col' style='background:#00B0F0 !important;' >"
        .($sale_data['total_month'][$month_year] + 0).
        "</td></tr>";
        if($month_year == date('M - Y',strtotime($end_date))) break;
        else $next = date('Y-m-d',strtotime("+1 months $next"));
    }
    $table1.= "</tr>"
    .$total_row
    .$compare_target
    .$diff_target
    .$rate_center
    .$compare_row
    .$diff_row
    ."</tbody>
    </table>
    <br>
    ";
    echo $table1;
    $table2 = "<table <table class=\"reportDataChildtablelistView\" width=\"33%\" cellspacing=\"1\" cellpadding=\"1\" border=\"1\" >
    <thead>
    <tr style='height: 30px;'>
    <th valign='middle' align='center' scope='col' style='background:yellow !important;'>Conversion rate/ center </th>
    <th valign='middle' align='center' scope='col' style='background:#FFEB9C !important;'>Total new sales</th>
    <th valign='middle' align='center' scope='col' style='background:#FFEB9C !important;'>Total enquiries</th>
    <th valign='middle' align='center' scope='col' style='background:#FFEB9C !important;'>Conversion rate</th>
    </tr>
    </thead>
    <tbody>
    <tr style='height: 30px;'>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>Adut</td>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>".($sale_data['total_this_adult'] + 0)."</td>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>".($data_student['total']['this_adult'] + 0)."</td>
    <td valign='middle' align='center' scope='col' style='background:#FFC7CE !important;'>".($data_student['total']['this_adult']?format_number($sale_data['total_this_adult']/$data_student['total']['this_adult']*100,0,0)."%":"N/A")."</td>
    </tr>
    <tr style='height: 30px;'>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>Junior</td>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>".($sale_data['total_this_junior'] + 0)."</td>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>".($data_student['total']['this_junior']  +  0)."</td>
    <td valign='middle' align='center' scope='col' style='background:#FFC7CE !important;'>".($data_student['total']['this_junior']?format_number($sale_data['total_this_junior']/$data_student['total']['this_junior']*100,0,0)."%":"N/A")."</td>
    </tr>
    <tr style='height: 30px;'>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>Total</td>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>".($sale_data['total_center']['this'] + 0)."</td>
    <td valign='middle' align='center' scope='col' style='background:#C6EFCE !important;'>".($data_student['total']['this'] + 0)."</td>
    <td valign='middle' align='center' scope='col' style='background:#FFC7CE !important;'>"
    . ($data_student['total']['this'] ? format_number($sale_data['total_center']['this']/$data_student['total']['this']*100,0,0)."%":"N/A")."</td>
    </tr>
    </tbody>
    </table>
    <br> ";
    echo $table2;



    function getCenterIds($where){
        $sql = "SELECT l1.id
        FROM teams l1
        WHERE l1.deleted = 0 AND ($where";
        $rs = $GLOBALS['db']->query($sql);
        $ids = array();
        while ($r = $GLOBALS['db']->fetchByAssoc($rs)) {
            $ids[] = $r['id'] ;
        }
        return $ids;
    }

    function print_debug($var){
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }
?>