<?php
    $js = <<<EOQ
    <script>
    $(function(){ $('#printPDFButton').hide(); });
    </script>
EOQ;
    echo $js;

    if(!isset($_POST['record']) || empty($_POST['record'])){
        die();
    }

    require_once 'custom/include/PHPExcel/Classes/PHPExcel.php';
    include("custom/include/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php");
    include("custom/include/PHPExcel/Classes/PHPExcel/IOFactory.php");

    $objPHPExcel = new PHPExcel();

    //Import Template
    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    $objPHPExcel = $objReader->load("custom/include/TemplateExcel/Commission.xlsx");

    // Set properties
    $objPHPExcel->getProperties()->setCreator("OnlineCRM");
    $objPHPExcel->getProperties()->setLastModifiedBy("OnlineCRM");
    $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
    $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
    $objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX");

    //get Now DB Date
    global $timedate;

    $filter = $this->where;
    $parts = explode("AND", $filter);

    $beginDB = get_string_between($parts[0],"'","'");
    $begin = $timedate->to_display_date($beginDB,false);

    $endDB = get_string_between($parts[1],"'","'");
    $end = $timedate->to_display_date($endDB,false);

    $team_id = get_string_between($parts[2],"'","'");

    //Get Total New Sale
    $sql = "SELECT DISTINCT
    SUM(c_payments.payment_amount) AS TOTAL1
    FROM
    c_payments
    INNER JOIN
    c_invoices_c_payments_1_c l1_1 ON c_payments.id = l1_1.c_invoices_c_payments_1c_payments_idb
    AND l1_1.deleted = 0
    INNER JOIN
    c_invoices l1 ON l1.id = l1_1.c_invoices_c_payments_1c_invoices_ida
    AND l1.deleted = 0
    INNER JOIN
    c_invoices_opportunities_1_c l2_1 ON l1.id = l2_1.c_invoices_opportunities_1c_invoices_ida
    AND l2_1.deleted = 0
    INNER JOIN
    opportunities l2 ON l2.id = l2_1.c_invoices_opportunities_1opportunities_idb
    AND l2.deleted = 0
    INNER JOIN
    teams l3 ON c_payments.team_id = l3.id
    AND l3.deleted = 0
    INNER JOIN
    users l4 ON c_payments.assigned_user_id = l4.id
    AND l4.deleted = 0
    WHERE
    (((c_payments.payment_type IN ('Normal' , 'Deposit'))
    AND (c_payments.payment_date >= '$beginDB'
    AND c_payments.payment_date <= '$endDB')
    AND (c_payments.status = 'Paid')
    AND (l3.id = '$team_id')
    AND (l2.opportunity_type = 'New Business')))
    AND c_payments.deleted = 0
    ORDER BY c_payments.payment_date ASC";
    $c7 = $GLOBALS['db']->getOne($sql);
    if(!$c7)
        $c7 = 0;

    //Get Total Retention
    $sql = "SELECT DISTINCT
    SUM(c_payments.payment_amount) AS TOTAL1
    FROM
    c_payments
    INNER JOIN
    c_invoices_c_payments_1_c l1_1 ON c_payments.id = l1_1.c_invoices_c_payments_1c_payments_idb
    AND l1_1.deleted = 0
    INNER JOIN
    c_invoices l1 ON l1.id = l1_1.c_invoices_c_payments_1c_invoices_ida
    AND l1.deleted = 0
    INNER JOIN
    c_invoices_opportunities_1_c l2_1 ON l1.id = l2_1.c_invoices_opportunities_1c_invoices_ida
    AND l2_1.deleted = 0
    INNER JOIN
    opportunities l2 ON l2.id = l2_1.c_invoices_opportunities_1opportunities_idb
    AND l2.deleted = 0
    INNER JOIN
    teams l3 ON c_payments.team_id = l3.id
    AND l3.deleted = 0
    INNER JOIN
    users l4 ON c_payments.assigned_user_id = l4.id
    AND l4.deleted = 0
    WHERE
    (((c_payments.payment_type IN ('Normal' , 'Deposit'))
    AND (c_payments.payment_date >= '$beginDB'
    AND c_payments.payment_date <= '$endDB')
    AND (c_payments.status = 'Paid')
    AND (l3.id = '$team_id')
    AND (l2.opportunity_type = 'Existing Business')))
    AND c_payments.deleted = 0
    ORDER BY c_payments.payment_date ASC";
    $c6 = $GLOBALS['db']->getOne($sql);
    if(!$c6)
        $c6 = 0;
    //Get total Refund

    $sql = "SELECT DISTINCT
    SUM(c_refunds.refund_amount) AS TOTAL1
    FROM
    c_refunds
    INNER JOIN
    teams l1 ON c_refunds.team_id = l1.id
    AND l1.deleted = 0
    WHERE
    (((c_refunds.refund_type = 'Normal')
    AND (c_refunds.refund_date >= '$beginDB'
    AND c_refunds.refund_date <= '$endDB')
    AND (l1.id = '$team_id')))
    AND c_refunds.deleted = 0";
    $c8 = $GLOBALS['db']->getOne($sql);
    if(!$c8)
        $c8 = 0;

    //Cal Net Payment
    $c9 = $c7 + $c6 - $c8;

    //Number of user
    $sql4 = "SELECT DISTINCT
    IFNULL(c_payments.id, '') primaryid,
    IFNULL(c_payments.name, '') c_payments_name,
    c_payments.payment_date c_payments_payment_date,
    c_payments.subtotal c_payments_subtotal,
    IFNULL(c_payments.currency_id, '') C_PAYMENTS_SUBTOTAL_CUC4A9CF,
    c_payments.view_discount c_payments_view_discount,
    IFNULL(c_payments.currency_id, '') C_PAYMENTS_VIEW_DISCOUEAE7FD,
    c_payments.payment_amount c_payments_payment_amount,
    IFNULL(c_payments.currency_id, '') C_PAYMENTS_PAYMENT_AMOF1CE92,
    IFNULL(c_payments.payment_type, '') c_payments_payment_type,
    IFNULL(c_payments.payment_attempt, '') c_payments_payment_attempt,
    IFNULL(l2.id, '') l2_id,
    IFNULL(l2.name, '') l2_name,
    IFNULL(l4.id, '') l4_id,
    CONCAT(IFNULL(l4.last_name, ''),
    ' ',
    IFNULL(l4.first_name, '')) l4_full_name
    FROM
    c_payments
    LEFT JOIN
    c_invoices_c_payments_1_c l1_1 ON c_payments.id = l1_1.c_invoices_c_payments_1c_payments_idb
    AND l1_1.deleted = 0
    LEFT JOIN
    c_invoices l1 ON l1.id = l1_1.c_invoices_c_payments_1c_invoices_ida
    AND l1.deleted = 0
    LEFT JOIN
    c_invoices_opportunities_1_c l2_1 ON l1.id = l2_1.c_invoices_opportunities_1c_invoices_ida
    AND l2_1.deleted = 0
    LEFT JOIN
    opportunities l2 ON l2.id = l2_1.c_invoices_opportunities_1opportunities_idb
    AND l2.deleted = 0
    INNER JOIN
    teams l3 ON c_payments.team_id = l3.id
    AND l3.deleted = 0
    INNER JOIN
    users l4 ON c_payments.assigned_user_id = l4.id
    AND l4.deleted = 0
    WHERE
    (((c_payments.payment_type IN ('Normal' , 'Deposit'))
    AND (c_payments.payment_date >= '$beginDB'
    AND c_payments.payment_date <= '$endDB')
    AND (c_payments.status = 'Paid')
    AND (l3.id = '$team_id')
    AND (l2.opportunity_type IN ('Existing Business' , 'New Business'))))
    AND c_payments.deleted = 0
    ORDER BY l4_id ASC , c_payments_payment_date ASC";
    $rs4 = $GLOBALS['db']->query($sql4);
    $i=0;
    $user_id = '';
    while($row4 = $GLOBALS['db']->fetchByAssoc($rs4)){
        if($user_id != $row4['l4_id']){
            $i++;
            $user_id = $row4['l4_id'];
        }
    }
    $c10 = $i + 1;

    $c11 = 0.5 / 100 * ($c6 - $c8)/$c10;

    $c12 = 0.85;

    $c13 = 0.85;

    $c14 = $c13 * $c11;

    $h13 = $c14;

    $h14 = $c13 * 0.2 /100 * $c9;
    $sql = "SELECT name FROM teams WHERE id = '{$team_id}'";
    $a4 = $GLOBALS['db']->getOne($sql);


    //Set Cell
    $objPHPExcel->getActiveSheet()->SetCellValue('A3', $begin.' - '.$end);
    $objPHPExcel->getActiveSheet()->SetCellValue('A4', $a4);

    $objPHPExcel->getActiveSheet()->SetCellValue('C6', $c6);
    $objPHPExcel->getActiveSheet()->SetCellValue('C7', $c7);
    $objPHPExcel->getActiveSheet()->SetCellValue('C8', $c8);
    $objPHPExcel->getActiveSheet()->SetCellValue('C9', '=C6+C7-C8');
    $objPHPExcel->getActiveSheet()->SetCellValue('C10', $c10);
    $objPHPExcel->getActiveSheet()->SetCellValue('C11', '=0.5/100*(C6-C8)/C10');
    $objPHPExcel->getActiveSheet()->SetCellValue('C12', $c12);
    $objPHPExcel->getActiveSheet()->SetCellValue('C13', '=IF(C12<0.8,0,IF(AND(C12>=0.8,C12<1),C12,IF(AND(C12>=1,C12<=1.2),1.5*C12,2*C12)))');
    $objPHPExcel->getActiveSheet()->SetCellValue('C14', '=C13*C11');
    $objPHPExcel->getActiveSheet()->SetCellValue('H13', '=C14');
    $objPHPExcel->getActiveSheet()->SetCellValue('H14', '=C13*0.2/100*C9');

    $i = 16;

    //Get List User By Team
    $stt = 0;
    $sql = "SELECT DISTINCT
    l3.user_name l3_user_name,
    l3.first_name l3_first_name,
    l3.last_name l3_last_name,
    SUM(IFNULL(IFNULL(c_payments.payment_amount, 0),
    IFNULL(c_payments.payment_amount, 0))) sum_user,
    COUNT(*) count
    FROM
    c_payments
    INNER JOIN
    c_invoices_c_payments_1_c l1_1 ON c_payments.id = l1_1.c_invoices_c_payments_1c_payments_idb
    AND l1_1.deleted = 0
    INNER JOIN
    c_invoices l1 ON l1.id = l1_1.c_invoices_c_payments_1c_invoices_ida
    AND l1.deleted = 0
    INNER JOIN
    c_invoices_opportunities_1_c l2_1 ON l1.id = l2_1.c_invoices_opportunities_1c_invoices_ida
    AND l2_1.deleted = 0
    INNER JOIN
    opportunities l2 ON l2.id = l2_1.c_invoices_opportunities_1opportunities_idb
    AND l2.deleted = 0
    INNER JOIN
    users l3 ON c_payments.assigned_user_id = l3.id
    AND l3.deleted = 0
    INNER JOIN
    teams l4 ON c_payments.team_id = l4.id
    AND l4.deleted = 0
    WHERE
    ((((c_payments.payment_date >= '$beginDB'
    AND c_payments.payment_date <= '$endDB'))
    AND (l2.sales_stage = 'Success')
    AND (c_payments.payment_type IN ('Normal' , 'Deposit'))
    AND (l2.opportunity_type = 'New Business')
    AND (c_payments.status = 'Paid')))
    AND c_payments.deleted = 0
    AND (l4.id = '$team_id')
    GROUP BY l3.user_name
    ORDER BY l3_user_name ASC";
    $rs = $GLOBALS['db']->query($sql);
    while($row = $GLOBALS['db']->fetchByAssoc($rs)){
        $i++;
        $stt++;
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $stt);
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, $row['l3_last_name'].' '.$row['l3_first_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, $row['sum_user']);
        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, '0.02');
        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$i, '=SUM(C'.$i.'*D'.$i.')');
        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, '=IF($C$13>0,$C$13,$C$12)*E'.$i.'');
        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, '=$C$14');
        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$i, '=F'.$i.'+G'.$i.'');
    }

//    $objPHPExcel->getActiveSheet()->setTitle('Commission');

    // Save Excel 2007 file
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter->save('custom/uploads/'. 'CommissionReport('.$timedate->to_db_date($begin) .'-'.$timedate->to_db_date($end).').xlsx');
    header('Location: custom/uploads/'. 'CommissionReport('.$timedate->to_db_date($begin) .'-'.$timedate->to_db_date($end).').xlsx');

    //Lưu ý: Không nên save file excel trong thư mục có từ khóa upload nếu không sẽ không hiển thị popup save file.
    //ví dụ: custom/upload/Payment.xlsx, hay upload/Payment.xlsx, hay custom/modules/C_Payments/upload/Payment.xlsx....
?>
