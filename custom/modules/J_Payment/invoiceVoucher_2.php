<?php
//export file excel
require_once("custom/include/PHPExcel/Classes/PHPExcel.php");
require_once("custom/include/ConvertMoneyString/convert_number_to_string.php");

global $timedate, $current_user;
$fi = new FilesystemIterator("custom/uploads/InvoiceExcel", FilesystemIterator::SKIP_DOTS);
if(iterator_count($fi) > 10)
    array_map('unlink', glob("custom/uploads/InvoiceExcel/*"));

$objPHPExcel = new PHPExcel();

$invoice = BeanFactory::getBean('J_Invoice', $_REQUEST['record']);
$qTeam = "SELECT code_prefix FROM teams WHERE id = '{$invoice->team_id}'";
$teamShortName = $GLOBALS['db']->getOne($qTeam);

$templateUrl = "custom/include/TemplateExcel/InvoicesVoucher_".$teamShortName.".xlsx";
if (!file_exists($templateUrl))
    $templateUrl = "custom/include/TemplateExcel/InvoicesVoucher.xlsx";

//Import Template
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load($templateUrl);

// Set properties
$objPHPExcel->getProperties()->setCreator("Online CRM");
$objPHPExcel->getProperties()->setLastModifiedBy("Online CRM");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX");

//Get Payment List
$rs1 = $GLOBALS['db']->query("SELECT id, payment_type, parent_type FROM j_payment WHERE id = '{$invoice->payment_id}' AND deleted = 0");
$payment = $GLOBALS['db']->fetchByAssoc($rs1);
if($payment['parent_type'] == 'Leads'){
    $ext_query = "INNER JOIN leads l4 ON l4.id = l3.lead_id AND l4.deleted = 0";
    $ext_select= "IFNULL(l4.id, '') l4_id,
    l4.full_lead_name l4_full_student_name,
    l4.primary_address_street l4_primary_address_street,
    l4.phone_mobile l4_phone_mobile,
    '' l4_contact_id,";
}else{
    $ext_query = "INNER JOIN
    contacts_j_payment_1_c l4_1 ON l3.id = l4_1.contacts_j_payment_1j_payment_idb
    AND l4_1.deleted = 0
    INNER JOIN
    contacts l4 ON l4.id = l4_1.contacts_j_payment_1contacts_ida
    AND l4.deleted = 0";
    $ext_select= "IFNULL(l4.id, '') l4_id,
    l4.full_student_name l4_full_student_name,
    l4.primary_address_street l4_primary_address_street,
    l4.phone_mobile l4_phone_mobile,
    l4.contact_id l4_contact_id,";
}



$sql = "SELECT DISTINCT
SUM(l1.before_discount) l1_before_discount,
SUM(l1.discount_amount) l1_discount_amount,
SUM(l1.sponsor_amount) l1_sponsor_amount,
SUM(l1.payment_amount) l1_payment_amount,
l1.status l1_status,
IFNULL(l2.id, '') payment_id,
IFNULL(l2.kind_of_course_string, '') kind_of_course,
l2.payment_type payment_type,
l2.payment_date payment_date,
$ext_select
IFNULL(j_invoice.id, '') primaryid,
IFNULL(j_invoice.name, '') j_invoice_name,
j_invoice.invoice_date j_invoice_invoice_date,
j_invoice.before_discount j_invoice_before_discount,
j_invoice.total_discount_amount j_invoice_total_discount_amount,
j_invoice.invoice_amount j_invoice_invoice_amount,
IFNULL(l5.id, '') l5_id,
l5.email_address l5_email_address,
CONCAT(IFNULL(l6.last_name, ''),
' ',
IFNULL(l6.first_name, '')) assigned_name
FROM
j_invoice
INNER JOIN
j_paymentdetail l1 ON j_invoice.id = l1.invoice_id
AND l1.deleted = 0
INNER JOIN
j_payment l2 ON l1.payment_id = l2.id AND l2.deleted = 0
INNER JOIN
j_payment l3 ON j_invoice.payment_id = l3.id
AND l3.deleted = 0
$ext_query
LEFT JOIN
email_addr_bean_rel l5_1 ON l4.id = l5_1.bean_id
AND l5_1.deleted = 0
AND l5_1.primary_address = '1'
LEFT JOIN
email_addresses l5 ON l5.id = l5_1.email_address_id
AND l5.deleted = 0
LEFT JOIN
users l6 ON j_invoice.assigned_user_id = l6.id
AND l6.deleted = 0
WHERE
(((j_invoice.id = '{$invoice->id}')))
AND j_invoice.deleted = 0
GROUP BY l2.id, l1.status
ORDER BY CASE WHEN l2.payment_type='Enrollment' THEN 0
WHEN l2.payment_type='Cashholder' THEN 1
WHEN l2.payment_type='Deposit' THEN 2
WHEN l2.payment_type='Placement Test' THEN 10
WHEN l2.payment_type='Book/Gift' THEN 11 ELSE 16 END ASC";
$res     = $GLOBALS['db']->query($sql);
$cur_row = 9;
$count   = 1;
$lastID  = '###';
while($row = $GLOBALS['db']->fetchByAssoc($res)){
    $student_name   =  $row['l4_full_student_name'];
    $student_address=  $row['l4_primary_address_street'];
    $phone_mobile   =  $row['l4_phone_mobile'];
    $student_id     =  $row['l4_contact_id'];
    $student_email  =  $row['l5_email_address'];
    $invoice_name   =  $row['j_invoice_name'];
    $invoice_date   =  $row['j_invoice_invoice_date'];
    $assigned_name  =  $row['assigned_name'];
    $before_discount+= $row['l1_before_discount'];
    $discount_amount+= $row['l1_discount_amount'];
    $sponsor_amount += $row['l1_sponsor_amount'];
    $payment_amount += $row['l1_payment_amount'];
    if($row['l1_status'] == 'Paid')
        $total_paid += $row['l1_payment_amount'];
    else $total_unpaid += $row['l1_payment_amount'];
    $total_before   += $row['l1_before_discount'];

    $content            =  generateContent($objPHPExcel, $row);
    $total_deposit      =  0;
    if($row['payment_type'] == 'Cashholder' || $row['payment_type'] == 'Enrollment'){
        if($lastID != $row['payment_id']){
            $res1 = $GLOBALS['db']->query("SELECT DISTINCT
                IFNULL(j_payment.id, '') primaryid,
                IFNULL(l1.id, '') l1_id,
                IFNULL(l1.name, '') l1_name,
                j_payment.tuition_fee tuition_fee,
                j_payment.tuition_hours tuition_hours,
                IFNULL(j_payment.tuition_fee / j_payment.tuition_hours, 1) price
                FROM
                j_payment
                INNER JOIN
                j_coursefee_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_coursefee_j_payment_1j_payment_idb
                AND l1_1.deleted = 0
                INNER JOIN
                j_coursefee l1 ON l1.id = l1_1.j_coursefee_j_payment_1j_coursefee_ida
                AND l1.deleted = 0
                WHERE
                (((j_payment.id = '{$row['payment_id']}')))
                AND j_payment.deleted = 0");
            $r1  = $GLOBALS['db']->fetchByAssoc($res1);

            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cur_row, $count);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cur_row, $content);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cur_row, "Giờ");
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cur_row, number_format($r1['tuition_hours'],1));
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$cur_row, number_format($r1['price'],0));
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$cur_row, number_format($r1['tuition_fee'],0));
            $cur_row++;
            $count++;
            $lastID = $row['payment_id'];
        }
    }

    if($row['payment_type'] == 'Deposit'){
        //        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cur_row, $count);
        //        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$cur_row, '');
        //        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cur_row, $content);
        //        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cur_row, '');
        //        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cur_row, '');
        //        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$cur_row, '');
        //        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$cur_row, number_format($row['l1_payment_amount'],0));
        $total_deposit += $row['l1_payment_amount'];
        $deposit_date  = $timedate->to_display_date($row['payment_date'], false);
        //        $cur_row++;
        //        $count++;
    }
    if($row['payment_type'] == 'Book/Gift'){
        $res2 = $GLOBALS['db']->query("SELECT DISTINCT
            IFNULL(l3.id, '') book_id,
            IFNULL(l3.name, '') book_name,
            IFNULL(j_inventorydetail.id, '') primaryid,
            j_inventorydetail.quantity quantity,
            l3.unit unit,
            l3.code code,
            j_inventorydetail.price price,
            j_inventorydetail.amount amount,
            IFNULL(l1.id, '') l1_id,
            l1.total_amount total_amount,
            l1.total_quantity total_quantity
            FROM
            j_inventorydetail
            INNER JOIN
            j_inventory l1 ON j_inventorydetail.inventory_id = l1.id
            AND l1.deleted = 0
            INNER JOIN
            j_payment_j_inventory_1_c l2_1 ON l1.id = l2_1.j_payment_j_inventory_1j_inventory_idb
            AND l2_1.deleted = 0
            INNER JOIN
            j_payment l2 ON l2.id = l2_1.j_payment_j_inventory_1j_payment_ida
            AND l2.deleted = 0
            INNER JOIN
            product_templates l3 ON j_inventorydetail.book_id = l3.id
            AND l3.deleted = 0
            WHERE
            (((l2.id = '{$row['payment_id']}')))
            AND j_inventorydetail.deleted = 0");
        while($r2 = $GLOBALS['db']->fetchByAssoc($res2)){

            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cur_row, $count);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$cur_row, $r2['code']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cur_row, $r2['book_name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cur_row, $r2['unit']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cur_row, number_format($r2['quantity'],1));
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$cur_row, number_format($r2['price'],0));
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$cur_row, number_format($r2['amount'],0));
            $cur_row++;
            $count++;
        }
    }
    if($row['payment_type'] == 'Placement Test'){
        while($r2 = $GLOBALS['db']->fetchByAssoc($res2)){
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cur_row, $count);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$cur_row, '');
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cur_row,'Placement Test Fee');
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cur_row, '');
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cur_row, '');
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$cur_row, '');
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$cur_row, number_format($row['l1_payment_amount'],0));
            $cur_row++;
            $count++;
        }
    }
}

// Write file
$objPHPExcel->getActiveSheet()->SetCellValue('C19', number_format($before_discount,0));
$objPHPExcel->getActiveSheet()->SetCellValue('C20', number_format($discount_amount + $sponsor_amount,0));
$objPHPExcel->getActiveSheet()->SetCellValue('A20', 'Tổng tiền chiết khấu ('.number_format((($discount_amount + $sponsor_amount) / $before_discount) * 100,0).'%):');
$objPHPExcel->getActiveSheet()->SetCellValue('C21', 0);
$objPHPExcel->getActiveSheet()->SetCellValue('C22', 0);
$objPHPExcel->getActiveSheet()->SetCellValue('C23', 0);

$int = new Integer();
$text = $int->toText($payment_amount);
$objPHPExcel->getActiveSheet()->SetCellValue('C24', $text);

$objPHPExcel->getActiveSheet()->SetCellValue('H19', number_format($payment_amount,0));


if($total_deposit > 0){
    $objPHPExcel->getActiveSheet()->SetCellValue('D20', 'Deposit ngày: '.$deposit_date);
    $objPHPExcel->getActiveSheet()->SetCellValue('H20', number_format($total_deposit,0));
    $objPHPExcel->getActiveSheet()->SetCellValue('D21', 'Tổng tiền đã thanh toán:');
    $objPHPExcel->getActiveSheet()->SetCellValue('H21', number_format($total_paid - $total_deposit,0));
    $objPHPExcel->getActiveSheet()->SetCellValue('D22', 'Còn lại:');
    $objPHPExcel->getActiveSheet()->SetCellValue('H22', number_format($total_unpaid,0));
}else{
    $objPHPExcel->getActiveSheet()->SetCellValue('D20', 'Tổng tiền đã thanh toán:');
    $objPHPExcel->getActiveSheet()->SetCellValue('H20', number_format($total_paid - $total_deposit,0));
    $objPHPExcel->getActiveSheet()->SetCellValue('D21', 'Còn lại:');
    $objPHPExcel->getActiveSheet()->SetCellValue('H21', number_format($total_unpaid,0));
}



$objPHPExcel->getActiveSheet()->SetCellValue('C4', $student_name);
$objPHPExcel->getActiveSheet()->SetCellValue('C5', $student_address);
$objPHPExcel->getActiveSheet()->SetCellValue('C6', $phone_mobile);
$objPHPExcel->getActiveSheet()->SetCellValue('G2', $invoice_name);
$objPHPExcel->getActiveSheet()->SetCellValue('G3', $timedate->to_display_date($invoice_date,false));
$objPHPExcel->getActiveSheet()->SetCellValue('G4', $student_id);
$objPHPExcel->getActiveSheet()->SetCellValue('G6', $student_email);
$objPHPExcel->getActiveSheet()->SetCellValue('F32',$assigned_name);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle($invoice_name);

////Lock file
//$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
//$objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
//$objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
//$objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
//$objPHPExcel->getActiveSheet()->getProtection()->setPassword('7779');

// Save Excel 2007 file
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$section = create_guid_section(6);
$file = 'custom/uploads/InvoiceExcel/Invoice_'.$invoice_name.'-'.$section.'.xlsx';

$objWriter->save($file);
//download to browser         /custom/uploads/default.xlsx
//$src = 'https://view.officeapps.live.com/op/view.aspx?src='.$GLOBALS['sugar_config']['site_url'].'/'.$file;
//header('Location: '.$file);

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    unlink($file);
    exit;
} 

function generateContent($objPHPExcel, $r){
    switch ($r['payment_type']) {
        case "Enrollment":
            $sql_get_class="SELECT DISTINCT
            l2.name l2_class_name,
            l2.kind_of_course kind_of_course
            FROM
            j_payment
            INNER JOIN j_studentsituations l1 ON j_payment.id = l1.payment_id
            AND l1.deleted = 0
            INNER JOIN j_class l2 ON l1.ju_class_id = l2.id
            AND l2.deleted = 0
            WHERE
            (((j_payment.id = '{$r['payment_id']}')))
            AND j_payment.deleted = 0";
            $result_get_class = $GLOBALS['db']->query($sql_get_class);
            $is_first = true;
            $count_outing = 0;
            $count_cambridge = 0;
            $content = "Thu tiền học phí lớp: ";
            while($row = $GLOBALS['db']->fetchByAssoc($result_get_class)) {
                if(!empty($row['l2_class_name']))
                    if($is_first){
                        $content .= $row['l2_class_name'];
                        $is_first = false;
                    }  else
                        $content .= ",".$row['l2_class_name'];
                if($row['kind_of_course'] == 'Outing Trip')
                    $count_outing ++;
            }
            if($count_outing > 0) $content = 'Thu tiền ngoại khóa.';

            break;
        case "Cashholder":
        case "Deposit":
            $content = "Thu tiền đặt cọc khóa {$r['kind_of_course']}.";
            break;
        case "Placement Test":
            $content = "Thu tiền kiểm tra trình độ.";
            break;
        case "Book/Gift":
            $q1 = "SELECT DISTINCT
            IFNULL(l3.id, '') book_id,
            IFNULL(l3.name, '') book_name,
            IFNULL(j_inventorydetail.id, '') primaryid,
            j_inventorydetail.quantity quantity,
            l3.unit unit,
            j_inventorydetail.price price,
            j_inventorydetail.amount amount,
            IFNULL(l1.id, '') l1_id,
            l1.total_amount total_amount,
            l1.total_quantity total_quantity
            FROM
            j_inventorydetail
            INNER JOIN
            j_inventory l1 ON j_inventorydetail.inventory_id = l1.id
            AND l1.deleted = 0
            INNER JOIN
            j_payment_j_inventory_1_c l2_1 ON l1.id = l2_1.j_payment_j_inventory_1j_inventory_idb
            AND l2_1.deleted = 0
            INNER JOIN
            j_payment l2 ON l2.id = l2_1.j_payment_j_inventory_1j_payment_ida
            AND l2.deleted = 0
            INNER JOIN
            product_templates l3 ON j_inventorydetail.book_id = l3.id
            AND l3.deleted = 0
            WHERE
            (((l2.id = '{$r['payment_id']}')))
            AND j_inventorydetail.deleted = 0";
            $rs1 = $GLOBALS['db']->query($q1);
            $content = "Thu tiền sách: ";
            while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
                $content .= $row['book_name']." ({$row['quantity']})";
            }
            break;
    }
    return $content;
}