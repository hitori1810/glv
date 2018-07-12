<?php
    $filter = $this->where;
    $parts = explode("AND", $filter);

    $thisCenter = get_string_between($parts[0]);
    $month     = get_string_between($parts[1]);
    $year     = get_string_between($parts[2]);
    $dateReport = date('Y-m-t',strtotime($year."-".$month."-01"));
    global $db, $timedate;
    $start_date = $timedate->to_db_date($timedate->to_display_date($start),false);
    $end_date = $timedate->to_db_date($timedate->to_display_date($end),false);

    //$thisCenter = "4676d1e2-8d4e-5611-38e8-537da8e79494"; // center import;
    $sql = "SELECT inven.from_inventory_list as object_from_bean,
    (CASE inven.from_inventory_list
    WHEN 'Accounts' THEN supplier.id
    WHEN 'Teams' THEN fromcenter.id
    END) as object_from_id,
    (CASE inven.from_inventory_list
    WHEN 'Accounts' THEN supplier.name
    WHEN 'Teams' THEN fromcenter.name
    END) as object_from_name,
    inven.to_inventory_list as object_to_bean,
    (CASE inven.to_inventory_list
    WHEN 'Accounts' THEN corp.id
    WHEN 'Teams' THEN tocenter.id
    WHEN 'Contacts' THEN student.id
    WHEN 'C_Teachers' THEN teacher.id
    END) as object_to_id,
    (CASE inven.to_inventory_list
    WHEN 'Accounts' THEN corp.name
    WHEN 'Teams' THEN tocenter.name
    WHEN 'Contacts' THEN TRIM(CONCAT(IFNULL(student.first_name,''),' ',IFNULL(student.last_name,'')))
    WHEN 'C_Teachers' THEN TRIM(CONCAT(IFNULL(teacher.first_name,''),' ',IFNULL(teacher.last_name,'')))
    END) as object_to_name,
    inven.type as inven_type,
    YEAR(inven.date_create) as year_invent,
    MONTH(inven.date_create) as month_invent,
    book.id as book_id, book.name book_name, book.`code` book_code,
    book.cost_price book_price_before, book.discount_price book_price_after,
    SUM(detail.quantity) as total_quantity
    FROM j_inventory inven
    INNER JOIN j_inventorydetail detail ON detail.deleted = 0 AND inven.id = detail.inventory_id
    INNER JOIN product_templates book ON book.id = detail.book_id
    LEFT JOIN accounts supplier ON supplier.id = inven.from_supplier_id
    LEFT JOIN teams fromcenter ON fromcenter.id = inven.from_team_id
    LEFT JOIN accounts corp ON corp.id = inven.to_corp_id
    LEFT JOIN teams tocenter ON tocenter.id = inven.to_team_id
    LEFT JOIN contacts student ON student.id = inven.to_student_id
    LEFT JOIN c_teachers teacher ON teacher.id = inven.to_teacher_id
    WHERE inven.deleted = 0 AND inven.`status` = 'Confirmed'
    AND (CASE inven.type
    WHEN 'Import' THEN inven.to_team_id = '$thisCenter'
    WHEN 'Tranfer' THEN (inven.to_team_id = '$thisCenter' OR inven.from_team_id = '$thisCenter')
    WHEN 'Sale' THEN inven.from_team_id = '$thisCenter'
    END)
    AND inven.date_create <= '$dateReport'
    GROUP BY year_invent, month_invent, object_from_id, object_to_id, inven_type,  book_id
    ORDER BY year_invent, month_invent "    ;
    // echo $sql;
    $result = $GLOBALS['db']->fetchArray($sql) ;
    $datas = array();
    for($i = 0; $i < count($result); $i++) {
        $row = $result[$i];
        $datas[$row['book_id']][$row['year_invent']][$row['month_invent']][] = $row;
    }
    $start_year  = $result[0]['year_invent'] + 0;
    $start_month  = $result[0]['month_invent'] + 0;
    $end    = $result[count($result)-1];
    //processing
    $report_datas = array();
    foreach($datas as $book_id => $data) {
        $begin_quantity = 0;
        $end_quantity = $begin_quantity;
        $infor = array();
        while (1) {
            $begin_quantity = $end_quantity;
            for($i = 0; $i < count($data[$start_year][$start_month]); $i++) {
                $record = $data[$start_year][$start_month][$i];
                $infor = $record;
                if($record['inven_type'] == 'Import') {
                    $end_quantity += $record['total_quantity'];
                } else if($record['inven_type'] == 'Tranfer'){
                    if($record['object_from_id'] == $thisCenter && $record['object_from_bean'] == "Teams") {
                        $end_quantity -= $record['total_quantity'];
                    } else if($record['object_to_id'] == $thisCenter && $record['object_to_bean'] == "Teams") {
                        $end_quantity += $record['total_quantity'];
                    }
                } else if($record['inven_type'] == 'Sale') {
                    $end_quantity -= $record['total_quantity'];
                }
            }
            if ($start_year * $start_month >= $end['month_invent'] * $end['year_invent']) {
                $record = $data[$start_year][$start_month];
                if($begin_quantity == 0 && $end_quantity == 0 && empty($record)) {
                    break;
                }
                $report_datas[$book_id]['book_name'] = $infor['book_name'] ;
                $report_datas[$book_id]['book_code'] = $infor['book_code'] ;
                $report_datas[$book_id]['book_price_before'] = format_number($infor['book_price_before'] + 0,0,0) ;
                $report_datas[$book_id]['book_price_after'] = format_number($infor['book_price_after'] + 0,0,0);
                $report_datas[$book_id]['book_price_discount'] =format_number( ($infor['book_price_before'] - $infor['book_price_after']) / $infor['book_price_before'] * 100,2,2);
                $report_datas[$book_id]['begin_quantity'] = $begin_quantity ;
                $report_datas[$book_id]['end_quantity'] = $end_quantity ;
                $report_datas[$book_id]['import_quantity'] = 0;
                $report_datas[$book_id]['import_detail'] = "";
                $report_datas[$book_id]['to_team_quantity'] = 0;
                $report_datas[$book_id]['to_team_detail'] = "";
                $report_datas[$book_id]['to_corp_quantity'] = 0;
                $report_datas[$book_id]['to_corp_detail'] = "";
                $report_datas[$book_id]['to_tea_quantity'] = 0;
                $report_datas[$book_id]['to_tea_detail'] = "";
                $report_datas[$book_id]['to_student_quantity'] = 0;
                $report_datas[$book_id]['to_student_detail'] = "";

                if($record) {
                    for($i = 0; $i < count($record); $i++) {
                        $row = $record[$i];
                        if($row['inven_type'] == 'Import') {
                            $report_datas[$book_id]['import_quantity'] += $row['total_quantity'];
                            $report_datas[$book_id]['import_detail'] .= $row['object_from_name'].":".$row['total_quantity'].";";
                        } else if($row['inven_type'] == 'Tranfer'){
                            if($row['object_from_id'] == $thisCenter && $row['object_from_bean'] == "Teams") {
                                if($row['object_to_bean'] == "Teams") {
                                    $report_datas[$book_id]['to_team_quantity'] += $row['total_quantity'];
                                    $report_datas[$book_id]['to_team_detail'] .= $row['object_to_name'].":".$row['total_quantity'].";";
                                }else if($row['object_to_bean'] == "Accounts") {
                                    $report_datas[$book_id]['to_corp_quantity'] += $row['total_quantity'];
                                    $report_datas[$book_id]['to_corp_detail'] .= $row['object_to_name'].":".$row['total_quantity'].";";
                                }else if($row['object_to_bean'] == "C_Teachers") {
                                    $report_datas[$book_id]['to_tea_quantity'] += $row['total_quantity'];
                                    $report_datas[$book_id]['to_tea_detail'] .= $row['object_to_name'].":".$row['total_quantity'].";";
                                }
                            } else if($row['object_to_id'] == $thisCenter && $row['object_to_bean'] == "Teams") {
                                $report_datas[$book_id]['import_quantity'] += $row['total_quantity'];
                                $report_datas[$book_id]['import_detail'] .= $row['object_from_name'].":".$row['total_quantity'].";";
                            }
                        } else if($row['inven_type'] == 'Sale') {
                            $report_datas[$book_id]['to_student_quantity'] += $row['total_quantity'];
                            $report_datas[$book_id]['to_student_detail'] .= $row['object_to_name'].":".$row['total_quantity'].";";
                        }
                    }
                }
                break;
            } else {
                $start_month ++;
                if($start_month > 12) {
                    $start_month = 1;
                    $start_year++;
                }
            }
        }
    }

    $table1 = "<table <table class=\"reportDataChildtablelistView\" width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" border=\"1\" >
    <thead>
    <tr style=''>
    <th rowspan = 3 valign='middle' align='center' scope='col' style='background:yellow !important;'>STT </th>
    <th rowspan = 3 valign='middle' align='center' scope='col' style='background:yellow !important;'>Item Code </th>
    <th rowspan = 3 valign='middle' align='center' scope='col' style='background:yellow !important;'>Title </th>
    <th rowspan = 3 valign='middle' align='center' scope='col' style='background:yellow !important;'>Unit Price before discount </th>
    <th rowspan = 3 valign='middle' align='center' scope='col' style='background:yellow !important;'>Discount </th>
    <th rowspan = 3 valign='middle' align='center' scope='col' style='background:yellow !important;'>Unit Price after discount </th>
    <th rowspan = 3 valign='middle' align='center' scope='col' style='background:yellow !important;'>Beginning Balance</th>
    <th rowspan = 2 colspan = 2 valign='middle' align='center' scope='col' style='background:yellow !important;'>In</th>
    <th colspan = 9 valign='middle' align='center' scope='col' style='background:yellow !important;'>Out</th>
    <th rowspan = 3 valign='middle' align='center' scope='col' style='background:yellow !important;'>Ending Quantity</th>
    </tr>
    <tr>
    <th  colspan = 2 valign='middle' align='center' scope='col' style='background:yellow !important;'>Center</th>
    <th colspan = 2 valign='middle' align='center' scope='col' style='background:yellow !important;'>Corp</th>
    <th colspan = 2 valign='middle' align='center' scope='col' style='background:yellow !important;'>Teacher</th>
    <th colspan = 2 valign='middle' align='center' scope='col' style='background:yellow !important;'>Student</th>
    <th rowspan = 2 valign='middle' align='center' scope='col' style='background:yellow !important;'>Total Qty</th>
    </tr>
    <tr>
    <th valign='middle' align='center' scope='col' style='background:yellow !important;'>Qty</th>
    <th valign='middle' align='center' scope='col' style='background:yellow !important;'>Detail</th>
    <th valign='middle' align='center' scope='col' style='background:yellow !important;'>Qty</th>
    <th valign='middle' align='center' scope='col' style='background:yellow !important;'>Detail</th>
    <th valign='middle' align='center' scope='col' style='background:yellow !important;'>Qty</th>
    <th valign='middle' align='center' scope='col' style='background:yellow !important;'>Detail</th>
    <th valign='middle' align='center' scope='col' style='background:yellow !important;'>Qty</th>
    <th valign='middle' align='center' scope='col' style='background:yellow !important;'>Detail</th>
    <th valign='middle' align='center' scope='col' style='background:yellow !important;'>Qty</th>
    <th valign='middle' align='center' scope='col' style='background:yellow !important;'>Detail</th>
    </tr>
    </thead>
    <tbody>";
    $index = 0;
    $total = array() ;
    foreach($report_datas as $key => $data) {
        $index ++;
        $table1 .="<tr>
        <td valign='middle' nowrap='' align='center' scope='col' >$index</td>
        <td valign='middle' nowrap='' align='center' scope='col' >{$data['book_code']}</td>
        <td valign='middle' nowrap='' align='left' scope='col' >{$data['book_name']}</td>
        <td valign='middle' nowrap='' align='right' scope='col' >{$data['book_price_before']}</td>
        <td valign='middle' nowrap='' align='center' scope='col' >{$data['book_price_discount']}</td>
        <td valign='middle' nowrap='' align='right' scope='col' >{$data['book_price_after']}</td>
        <td valign='middle' nowrap='' align='center' scope='col' >{$data['begin_quantity']}</td>

        <td valign='middle' nowrap='' align='center' scope='col' >{$data['import_quantity']}</td>
        <td valign='middle' align='left' scope='col' >{$data['import_detail']}</td>

        <td valign='middle' nowrap='' align='center' scope='col' >{$data['to_team_quantity']}</td>
        <td valign='middle' align='left' scope='col' >{$data['to_team_detail']}</td>

        <td valign='middle' nowrap='' align='center' scope='col' >{$data['to_corp_quantity']}</td>
        <td valign='middle' align='left' scope='col' >{$data['to_corp_detail']}</td>

        <td valign='middle' nowrap='' align='center' scope='col' >{$data['to_tea_quantity']}</td>
        <td valign='middle' align='left' scope='col' >{$data['to_tea_detail']}</td>

        <td valign='middle' nowrap='' align='center' scope='col' >{$data['to_student_quantity']}</td>
        <td valign='middle' align='left' scope='col' >{$data['to_student_detail']}</td>

        <td valign='middle' align='center' scope='col' >".($data['to_student_quantity'] + $data['to_tea_quantity'] + $data['to_corp_quantity'] +$data['to_team_quantity'])."</td>
        <td valign='middle' align='center' scope='col' >{$data['end_quantity']}</td>
        </tr>";

        $total['begin_quantity'] += $data['begin_quantity'];
        $total['import_quantity'] += $data['import_quantity'];
        $total['to_team_quantity'] += $data['to_team_quantity'];
        $total['to_corp_quantity'] += $data['to_corp_quantity'];
        $total['to_tea_quantity'] += $data['to_tea_quantity'];
        $total['to_student_quantity'] += $data['to_student_quantity'];
        $total['to_quantity'] += ($data['to_team_quantity'] + $data['to_corp_quantity']+ $data['to_tea_quantity'] + $data['to_student_quantity']);
        $total['end_quantity'] += $data['end_quantity'];
    }
    $table1 .="</tbody><tfoot>
    <tr>
    <td  colspan = 3 valign='middle' align='center' scope='col' style='background:green !important;'><b>Total</b></td>
    <td  colspan = 3 valign='middle' align='center' scope='col' style='background:green !important;'></td>
    <td  valign='middle' align='center' scope='col' style='background:green !important;'>{$total['begin_quantity']}</td>
    <td  valign='middle' align='center' scope='col' style='background:green !important;'>{$total['import_quantity']}</td>
    <td  valign='middle' align='center' scope='col' style='background:green !important;'></td>
    <td  valign='middle' align='center' scope='col' style='background:green !important;'>{$total['to_team_quantity']}</td>
    <td  valign='middle' align='center' scope='col' style='background:green !important;'></td>
    <td  valign='middle' align='center' scope='col' style='background:green !important;'>{$total['to_corp_quantity']}</td>
    <td  valign='middle' align='center' scope='col' style='background:green !important;'></td>
    <td  valign='middle' align='center' scope='col' style='background:green !important;'>{$total['to_tea_quantity']}</td>
    <td  valign='middle' align='center' scope='col' style='background:green !important;'></td>
    <td  valign='middle' align='center' scope='col' style='background:green !important;'>{$total['to_student_quantity']}</td>
    <td  valign='middle' align='center' scope='col' style='background:green !important;'></td>
    <td  valign='middle' align='center' scope='col' style='background:green !important;'>{$total['to_quantity']}</td>
    <td  valign='middle' align='center' scope='col' style='background:green !important;'>{$total['end_quantity']}</td>
    </tr>
    </tfoot>
    </table>
    ";
    echo $table1;
    // echo "<pre>";
    // print_r($report_datas);
    // echo "<pre>";

?>
