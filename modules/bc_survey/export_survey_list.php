<?php
//export file excel
require_once("custom/include/PHPExcel/Classes/PHPExcel.php");
require_once("custom/include/ConvertMoneyString/convert_number_to_string.php");



global $timedate, $current_user, $sugar_config;
$fi = new FilesystemIterator("custom/uploads/ExportStudent", FilesystemIterator::SKIP_DOTS);
if(iterator_count($fi) > 10)
    array_map('unlink', glob("custom/uploads/ExportStudent/*"));

$objPHPExcel = new PHPExcel();

$templateUrl = "custom/include/TemplateExcel/ExportSMS.xlsx";

//Import Template
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load($templateUrl);

// Set properties
$objPHPExcel->getProperties()->setCreator("CRM Center");
$objPHPExcel->getProperties()->setLastModifiedBy("CRM Center");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX");

$targetListID = $_REQUEST['target_list_id'];
$targetList   = BeanFactory::getBean('ProspectLists',$targetListID);
//Add data
$sql = "SELECT DISTINCT
IFNULL(l1.id, '') id,
CONCAT(IFNULL(l1.last_name, ''),
' ',
IFNULL(l1.first_name, '')) name,
IFNULL(l2.name, '') parent,
CASE l1_1.related_type
WHEN 'Contacts' THEN 'Student'
END AS related_type,
l1_1.related_type module_name,
l1.phone_mobile mobile,
l3.email_address email,
l1.primary_address_street address,
l1.gender gender,
l1.birthdate birthdate,
l1.lead_source lead_source,
l4.full_user_name assigned_to,
IFNULL(l5.name, '') center
FROM
prospect_lists
INNER JOIN
prospect_lists_prospects l1_1 ON prospect_lists.id = l1_1.prospect_list_id
AND l1_1.deleted = 0
AND l1_1.related_type = 'Contacts'
INNER JOIN
contacts l1 ON l1.id = l1_1.related_id
AND l1.deleted = 0
LEFT JOIN
c_contacts_contacts_1_c l2_1 ON l1.id = l2_1.c_contacts_contacts_1contacts_idb
AND l2_1.deleted = 0
LEFT JOIN
c_contacts l2 ON l2.id = l2_1.c_contacts_contacts_1c_contacts_ida
AND l2.deleted = 0
LEFT JOIN
email_addr_bean_rel l3_1 ON l1.id = l3_1.bean_id
AND l3_1.deleted = 0
AND l3_1.primary_address = '1'
LEFT JOIN
email_addresses l3 ON l3.id = l3_1.email_address_id
AND l3.deleted = 0
LEFT JOIN
users l4 ON l1.assigned_user_id = l4.id
AND l4.deleted = 0
INNER JOIN
teams l5 ON l1.team_id = l5.id AND l5.deleted = 0
WHERE
(((prospect_lists.id = '$targetListID')))
AND prospect_lists.deleted = 0
UNION DISTINCT SELECT DISTINCT
IFNULL(l1.id, '') id,
CONCAT(IFNULL(l1.last_name, ''),
' ',
IFNULL(l1.first_name, '')) name,
IFNULL(l1.guardian_name, '') parent,
CASE l1_1.related_type
WHEN 'Leads' THEN 'Lead'
END AS related_type,
l1_1.related_type module_name,
l1.phone_mobile mobile,
l3.email_address email,
l1.primary_address_street address,
l1.gender gender,
l1.birthdate birthdate,
l1.lead_source lead_source,
l4.full_user_name assigned_to,
IFNULL(l5.name, '') center
FROM
prospect_lists
INNER JOIN
prospect_lists_prospects l1_1 ON prospect_lists.id = l1_1.prospect_list_id
AND l1_1.deleted = 0
AND l1_1.related_type = 'Leads'
INNER JOIN
leads l1 ON l1.id = l1_1.related_id
AND l1.deleted = 0
LEFT JOIN
email_addr_bean_rel l3_1 ON l1.id = l3_1.bean_id
AND l3_1.deleted = 0
AND l3_1.primary_address = '1'
LEFT JOIN
email_addresses l3 ON l3.id = l3_1.email_address_id
AND l3.deleted = 0
LEFT JOIN
users l4 ON l1.assigned_user_id = l4.id
AND l4.deleted = 0
INNER JOIN
teams l5 ON l1.team_id = l5.id AND l5.deleted = 0
WHERE
(((prospect_lists.id = '$targetListID')))
AND prospect_lists.deleted = 0
UNION DISTINCT SELECT DISTINCT
IFNULL(l1.id, '') id,
CONCAT(IFNULL(l1.last_name, ''),
' ',
IFNULL(l1.first_name, '')) name,
IFNULL(l1.guardian_name, '') parent,
CASE l1_1.related_type
WHEN 'Prospects' THEN 'Target'
END AS related_type,
l1_1.related_type module_name,
l1.phone_mobile mobile,
l3.email_address email,
l1.primary_address_street address,
l1.gender gender,
l1.birthdate birthdate,
l1.lead_source lead_source,
l4.full_user_name assigned_to,
IFNULL(l5.name, '') center
FROM
prospect_lists
INNER JOIN
prospect_lists_prospects l1_1 ON prospect_lists.id = l1_1.prospect_list_id
AND l1_1.deleted = 0
AND l1_1.related_type = 'Prospects'
INNER JOIN
prospects l1 ON l1.id = l1_1.related_id
AND l1.deleted = 0
LEFT JOIN
email_addr_bean_rel l3_1 ON l1.id = l3_1.bean_id
AND l3_1.deleted = 0
AND l3_1.primary_address = '1'
LEFT JOIN
email_addresses l3 ON l3.id = l3_1.email_address_id
AND l3.deleted = 0
LEFT JOIN
users l4 ON l1.assigned_user_id = l4.id
AND l4.deleted = 0
INNER JOIN
teams l5 ON l1.team_id = l5.id AND l5.deleted = 0
WHERE
(((prospect_lists.id = '$targetListID')))
AND prospect_lists.deleted = 0";

$rows = $GLOBALS['db']->fetchArray($sql);
$count = 1;
foreach($rows as $row){
    $count++;
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$count, $row['name']);
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$count, $row['parent']);
    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$count, $row['mobile']);
    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$count, $row['related_type']);
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$count, $row['email']);
    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$count, $row['address']);
    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$count, $row['gender']);
    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$count, $row['birthdate']);
    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$count, $row['lead_source']);
    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$count, $row['assigned_to']);
    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$count, $row['center']);
    if(!empty($_REQUEST['survey_id'])){
        $survey_url = $sugar_config['site_url'] . '/survey_submission.php?survey_id=';
        $sugar_survey_Url = $survey_url; //create survey submission url
        $encoded_param = base64_encode($_REQUEST['survey_id'] . '&ctype=' . $row['module_name'] . '&cid=' . $row['id']);
        $sugar_survey_Url = str_replace('survey_id=', 'q=', $sugar_survey_Url);
        $surveyURL = $sugar_survey_Url . $encoded_param;
        $objPHPExcel->getActiveSheet()->SetCellValue('L'.$count, $surveyURL);
    }
}

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle($targetList->name);

// Save Excel 2007 file
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$section = create_guid_section(6);
$file = 'custom/uploads/ExportStudent/TargetList_Member_'.$section.'.xlsx';

$objWriter->save($file);
header('Location: '.$file);