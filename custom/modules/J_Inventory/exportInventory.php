<?php
require_once 'custom/include/PHPExcel/Classes/PHPExcel.php';
require_once("custom/include/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php");
require_once("custom/include/PHPExcel/Classes/PHPExcel/IOFactory.php");

$fi = new FilesystemIterator("custom/uploads/exportExcel", FilesystemIterator::SKIP_DOTS);
if(iterator_count($fi) > 10)
    array_map('unlink', glob("custom/uploads/exportExcel/*"));

$objPHPExcel = new PHPExcel();

//Import Template
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load("custom/include/TemplateExcel/MaterialDeliveryNote.xlsx");

// Set properties
$objPHPExcel->getProperties()->setCreator("OnlineCRM");
$objPHPExcel->getProperties()->setLastModifiedBy("OnlineCRM");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX");

//Add data
$details = BeanFactory::getBean('J_Inventory',$_REQUEST['record']);

//get to list
if($details->type == "Sell"){
    $payment_id = $this->bean->j_payment_j_inventory_1j_payment_ida;
    $sql = "SELECT DISTINCT
    IFNULL(l3.id, '') l3_id,
    IFNULL(l3.code, '') book_code,
    IFNULL(l3.name, '') book_name,
    IFNULL(l3.unit, '') book_unit,
    IFNULL(j_inventorydetail.id, '') primaryid,
    j_inventorydetail.price price,
    j_inventorydetail.quantity quantity,
    j_inventorydetail.amount amount,
    j_inventorydetail.description description,
    IFNULL(l1.id, '') l1_id,
    IFNULL(l1.date_create, '') date_create,
    l1.total_quantity total_quantity,
    l1.total_amount total_amount,
    IFNULL(l5.id, '') l5_id,
    IFNULL(l5.name, '') team_name,
    IFNULL(l4.id, '') l4_id,
    CONCAT(IFNULL(l4.last_name, ''),
    ' ',
    IFNULL(l4.first_name, '')) student_name
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
    INNER JOIN
    contacts_j_payment_1_c l4_1 ON l2.id = l4_1.contacts_j_payment_1j_payment_idb
    AND l4_1.deleted = 0
    INNER JOIN
    contacts l4 ON l4.id = l4_1.contacts_j_payment_1contacts_ida
    AND l4.deleted = 0
    INNER JOIN
    teams l5 ON l1.team_id = l5.id
    AND l5.deleted = 0
    WHERE
    (((l2.id = '$payment_id')))
    AND j_inventorydetail.deleted = 0";
    $res = $GLOBALS['db']->query($sql);
    $r   = $GLOBALS['db']->fetchByAssoc($res);
}

$objPHPExcel->getActiveSheet()->SetCellValue('C8', $r['student_name']);
$objPHPExcel->getActiveSheet()->SetCellValue('C10',$r['team_name']);
$objPHPExcel->getActiveSheet()->SetCellValue('I9', $details->date_create);
$objPHPExcel->getActiveSheet()->SetCellValue('I8', $details->name);
$objPHPExcel->getActiveSheet()->SetCellValue('C9', $details->request_no);
$objPHPExcel->getActiveSheet()->SetCellValue('F28', $details->total_quantity);
$objPHPExcel->getActiveSheet()->SetCellValue('F28', $details->total_quantity);
$objPHPExcel->getActiveSheet()->SetCellValue('H28', $details->total_amount);

//get detail inventory
$res1 = $GLOBALS['db']->query($sql);
$i=0;
while($r = $GLOBALS['db']->fetchByAssoc($res1)){
    $row = 14 + $i;
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $i+1);
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, $r['book_code']);
    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, $r['book_name']);
    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, $GLOBALS['app_list_strings']['unit_ProductTemplates_list'][$r['book_unit']]);
    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$row, $r['quantity']);
    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$row, format_number($r['price']));
    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$row, format_number($r['amount']));
    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$row, $r['description']);
    $i++;
}
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle($details->name);

// Save Excel 2007 file
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$section = create_guid_section(6);
$file = 'custom/uploads/exportExcel/MaterialDelivery_'.$details->name.'-'.$section.'.xlsx';

$objWriter->save($file);
//download to browser         /custom/uploads/default.xlsx
$src = 'https://view.officeapps.live.com/op/view.aspx?src='.$GLOBALS['sugar_config']['site_url'].'/'.$file;
header('Location: '.$file);
?>
