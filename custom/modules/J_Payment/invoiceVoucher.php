<?php
//export file excel
require_once("custom/include/PHPExcel/Classes/PHPExcel.php");
require_once("custom/include/ConvertMoneyString/convert_number_to_string.php");

global $timedate, $current_user;
$fi = new FilesystemIterator("custom/uploads/InvoiceExcel", FilesystemIterator::SKIP_DOTS);
if(iterator_count($fi) > 10)
    array_map('unlink', glob("custom/uploads/InvoiceExcel/*"));

$objPHPExcel = new PHPExcel();

$payment = BeanFactory::getBean('J_PaymentDetail', $_REQUEST['record']);
$qTeam = "SELECT code_prefix FROM teams WHERE id = '{$payment->team_id}'";
$teamShortName = $GLOBALS['db']->getOne($qTeam);

$templateUrl = "custom/include/TemplateExcel/ReceiptVoucher_".$teamShortName.".xlsx";
if (!file_exists($templateUrl))
    $templateUrl = "custom/include/TemplateExcel/ReceiptVoucher.xlsx";

//Import Template
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load($templateUrl);

// Set properties
$objPHPExcel->getProperties()->setCreator("Online CRM");
$objPHPExcel->getProperties()->setLastModifiedBy("Online CRM");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX");

//Add data
$sql = "SELECT DISTINCT
IFNULL(l1.id, '') payment_id,
IFNULL(l1.name, '') l1_name,
IFNULL(l1.company_name, '') company_name,
IFNULL(l1.tax_code, '') tax_code,
IFNULL(l1.kind_of_course_string, '') kind_of_course,
IFNULL(l1.account_id, '') company_id,
IFNULL(l1.company_address, '') company_address,
IFNULL(l4.name, '') course_fee_name,
l1.final_sponsor_percent final_sponsor_percent,
l4.type_of_course_fee type_of_course_fee,
l1.tuition_fee payment_tuition_fee,
l1.tuition_hours payment_tuition_hours,
IFNULL(j_paymentdetail.id, '') primaryid,
IFNULL(j_paymentdetail.name, '') name,
j_paymentdetail.payment_date payment_date,
j_paymentdetail.printed_date printed_date,
IFNULL(j_paymentdetail.payment_method, '') payment_method,
j_paymentdetail.before_discount before_discount,
j_paymentdetail.discount_amount discount_amount,
j_paymentdetail.sponsor_amount sponsor_amount,
j_paymentdetail.type type,
j_paymentdetail.payment_no payment_no,
j_paymentdetail.payment_amount payment_amount,
l1.tuition_hours tuition_hours,
l1.deposit_amount deposit_amount,
l1.paid_amount paid_amount,
l1.payment_type payment_type,
l1.parent_type parent_type,
l1.description description,
j_paymentdetail.description detail_description,
j_paymentdetail.is_discount is_discount,
IFNULL(l2.id, '') l2_id,
IFNULL(l5.team_type, '') team_type,
CONCAT(IFNULL(l2.last_name, ''),
' ',
IFNULL(l2.first_name, '')) assigned_user_name,
IFNULL(l3.id, '') l3_id,
CONCAT(IFNULL(l3.last_name, ''),
' ',
IFNULL(l3.first_name, '')) student_name,
l3.primary_address_street student_address,
CONCAT(IFNULL(la.last_name, ''),
' ',
IFNULL(la.first_name, '')) lead_name,
la.primary_address_street lead_address
FROM
j_paymentdetail
LEFT JOIN
j_payment l1 ON j_paymentdetail.payment_id = l1.id
AND l1.deleted = 0
LEFT JOIN
users l2 ON l1.assigned_user_id = l2.id
AND l2.deleted = 0
LEFT JOIN
contacts_j_payment_1_c l3_1 ON l1.id = l3_1.contacts_j_payment_1j_payment_idb
AND l3_1.deleted = 0
LEFT JOIN
contacts l3 ON l3.id = l3_1.contacts_j_payment_1contacts_ida
AND l3.deleted = 0
LEFT JOIN
leads la ON l1.lead_id = la.id
AND la.deleted = 0
LEFT JOIN
j_coursefee_j_payment_1_c l4_1 ON l1.id = l4_1.j_coursefee_j_payment_1j_payment_idb
AND l4_1.deleted = 0
LEFT JOIN
j_coursefee l4 ON l4.id = l4_1.j_coursefee_j_payment_1j_coursefee_ida
AND l4.deleted = 0
INNER JOIN
teams l5 ON l1.team_id = l5.id
AND l5.deleted = 0
WHERE
(((j_paymentdetail.id = '{$_REQUEST['record']}')))
AND j_paymentdetail.deleted = 0";
$res     = $GLOBALS['db']->query($sql);
$r         = $GLOBALS['db']->fetchByAssoc($res);


// Write file
$objPHPExcel->getActiveSheet()->SetCellValue('E3', 'Số: '.$r['name']);
$objPHPExcel->getActiveSheet()->SetCellValue('E39', 'Số: '.$r['name']);

//Prepare
$date       = explode('-', $r['payment_date']);
$day        = $date[2];
$month      = $date[1];
$year       = $date[0];
$objPHPExcel->getActiveSheet()->SetCellValue('C7', 'Ngày '.$day.' tháng '.$month.' năm '.$year);
$objPHPExcel->getActiveSheet()->SetCellValue('C44', 'Ngày '.$day.' tháng '.$month.' năm '.$year);

$student_name     = $r['student_name'];
$student_address  = $r['student_address'];
if($r['parent_type'] == 'Leads'){
    $student_name     = $r['lead_name'];
    $student_address  = $r['lead_address'];
}

$objPHPExcel->getActiveSheet()->SetCellValue('C13', html_entity_decode_utf8($student_address));
$objPHPExcel->getActiveSheet()->SetCellValue('C50', html_entity_decode_utf8($student_address));

$objPHPExcel->getActiveSheet()->SetCellValue('C10', $student_name);
$objPHPExcel->getActiveSheet()->SetCellValue('C47', $student_name);

if($_REQUEST['type'] == "corporate" || $_REQUEST['type'] == "both"){
    $q2         = "SELECT tax_code, billing_address_street, name FROM accounts WHERE id = '{$r['company_id']}'";
    $rs2        = $GLOBALS['db']->query($q2);
    $r_company  = $GLOBALS['db']->fetchByAssoc($rs2);
    if($_REQUEST['type'] == "corporate"){
        $objPHPExcel->getActiveSheet()->SetCellValue('C10', $r_company['name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('C47', $r_company['name']);
    }
    $objPHPExcel->getActiveSheet()->SetCellValue('C13', html_entity_decode_utf8($r_company['billing_address_street']) );
    $objPHPExcel->getActiveSheet()->SetCellValue('C50', html_entity_decode_utf8($r_company['billing_address_street']) );
}

$content = generateContent($objPHPExcel, $r);
$objPHPExcel->getActiveSheet()->SetCellValue('C16', $content);
$objPHPExcel->getActiveSheet()->SetCellValue('C53', $content);

// - Money to String
$int = new Integer();
$text = $int->toText($r['payment_amount']);
$objPHPExcel->getActiveSheet()->SetCellValue('C19', number_format($r['payment_amount'],0));
$objPHPExcel->getActiveSheet()->SetCellValue('C56', number_format($r['payment_amount'],0));
$objPHPExcel->getActiveSheet()->SetCellValue('C22', $text);
$objPHPExcel->getActiveSheet()->SetCellValue('C59', $text);

// - Method
if ($r['payment_method'] == "Cash") $pmmt="TM";
else $pmmt="CK";
$objPHPExcel->getActiveSheet()->SetCellValue('E25', $pmmt);
$objPHPExcel->getActiveSheet()->SetCellValue('E62', $pmmt);

$objPHPExcel->getActiveSheet()->SetCellValue('E30', $student_name);
$objPHPExcel->getActiveSheet()->SetCellValue('E67', $student_name);

$GLOBALS['db']->query("UPDATE j_paymentdetail SET content_vat_invoice = '$content' WHERE id = '{$r['primaryid']}'");

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle($r['name']);

////Lock file
//$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
//$objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
//$objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
//$objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
//$objPHPExcel->getActiveSheet()->getProtection()->setPassword('7779');

// Add logo
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Sample image');
$objDrawing->setPath('custom/themes/default/images/company_logo.png');          
$objDrawing->setHeight(50);
$objDrawing->setCoordinates('A3');    
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Sample image');
$objDrawing->setPath('custom/themes/default/images/company_logo.png');          
$objDrawing->setHeight(50);
$objDrawing->setCoordinates('A39');    
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

// Save Excel 2007 file
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$section = create_guid_section(6);
$file = 'custom/uploads/InvoiceExcel/Receipt_'.$r['l1_name'].'-'.$section.'.xlsx';

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
            $content = "Thu tiền khóa học tiếng anh {$r['kind_of_course']}.";
            break;
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