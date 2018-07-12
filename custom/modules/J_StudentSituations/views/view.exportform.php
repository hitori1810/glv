<?php
require_once("custom/include/PHPExcel/Classes/PHPExcel.php");
require_once("custom/include/PHPExcel/Classes/PHPExcel/IOFactory.php");
require_once("custom/include/ConvertMoneyString/convert_number_to_string.php");
ob_clean();
ini_set("display_errors", "On");

$situationId = $_REQUEST['record'];
$situation = new J_StudentSituations();
$situation->retrieve($situationId);

//Load template
$inputFileName = 'custom/include/TemplateExcel/Form_Moving_Class.xlsx';
$section = create_guid_section(6);
$outputFileName = 'custom/uploads/moving_class_'.$section.'.xlsx';

try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

// Set document properties
$objPHPExcel->getProperties()->setCreator($GLOBALS['current_user']->user_name)
->setLastModifiedBy($GLOBALS['current_user']->user_name)
->setTitle("OnlineCRM")
->setSubject("OnlineCRM")
->setDescription("OnlineCRM")
->setKeywords("OnlineCRM")
->setCategory("OnlineCRM");

//Get Data
$data = getData($situation);

//Write to Excel file
writeData($situation, $objPHPExcel, $data);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->setPreCalculateFormulas();
$objWriter->save($outputFileName);

if (file_exists($outputFileName)) {
    ob_end_clean();
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($outputFileName));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($outputFileName));
    ob_clean();
    flush();
    readfile($outputFileName);
    unlink($outputFileName);
    exit;
}

function getData($situation){
    global $timedate;
    $data = array();
    $text = new Integer();

    if($situation->type == "Moving In" || $situation->type == "Moving Out"){
        if ($situation->type == "Moving In"){
            $situationInBean = $situation;
            $situationOutId = $situationInBean->relate_situation_id;
            $situationOutBean = BeanFactory::getBean("J_StudentSituations", $situationOutId);
        }
        else{
            $situationOutBean = $situation;
            $situationInId = $situationInBean->relate_situation_id;
            $situationInBean = BeanFactory::getBean("J_StudentSituations", $situationInId);
        }

        $sql = "SELECT
        IFNULL(j_studentsituations.name,'') situ_out_code
        ,IFNULL(j_studentsituations.total_hour,'') situ_out_hour
        ,IFNULL(j_studentsituations.total_amount,'') situ_out_amount
        ,IFNULL(j_studentsituations.start_study,'') situ_out_date
        ,IFNULL(j_studentsituations.description,'') situ_out_description
        ,IFNULL(l1.name,'') situ_in_code
        ,IFNULL(l1.start_study,'') situ_in_date
        ,IFNULL(l2.name,'') l2_name
        ,IFNULL(l3.contact_id, '') student_code
        ,IFNULL(l3.full_student_name,'') student_name
        ,IFNULL(l3.phone_mobile, '') student_mobile
        ,IFNULL(l3.primary_address_street, '') student_address
        ,IFNULL(l4.name,'') situ_out_center_name
        ,IFNULL(l8.name,'') situ_in_center_name
        ,IFNULL(l5.name,'') relate_payment_code
        ,IFNULL(l5.payment_date,'') relate_payment_date
        ,IFNULL(l5.tuition_fee,'') relate_payment_amount
        ,IFNULL(l6.name,'') situ_out_class_code
        ,IFNULL(l7.name,'') situ_in_class_code
        FROM j_studentsituations
        INNER JOIN j_studentsituations l1 ON j_studentsituations.relate_situation_id=l1.id AND l1.deleted=0
        INNER JOIN teams l2 ON l1.team_id=l2.id AND l2.deleted=0
        INNER JOIN contacts l3 ON l1.student_id=l3.id AND l3.deleted=0
        INNER JOIN teams l4 ON j_studentsituations.team_id=l4.id AND l4.deleted=0
        INNER JOIN j_payment l5 ON j_studentsituations.payment_id = l5.id AND l5.deleted = 0
        INNER JOIN j_class l6 ON j_studentsituations.ju_class_id=l6.id AND l6.deleted=0
        INNER JOIN j_class l7 ON l1.ju_class_id=l7.id AND l7.deleted=0
        INNER JOIN teams l8 ON j_studentsituations.team_id=l8.id AND l4.deleted=0
        WHERE
        j_studentsituations.id = '{$situationOutBean->id}'
        AND j_studentsituations.deleted = 0
        LIMIT 1";
        $rs = $GLOBALS['db']->query($sql);
        $row = $GLOBALS['db']->fetchByAssoc($rs);

        //Situation moving out
        $data["situ_out_code"]          = $row['situ_out_code'];
        $data["situ_out_hour"]          = $row['situ_out_hour'];
        $data["situ_out_amount"]        = $row['situ_out_amount'];
        $data["situ_out_class_code"]    = $row['situ_out_class_code'];
        $data["situ_out_date"]          = $timedate->to_display_date($row['situ_out_date']);
        $data["situ_out_center_name"]   = $row['situ_out_center_name'];

        //Situation moving in
        $data["situ_in_code"]           = $row['situ_in_code'];
        $data["situ_in_class_code"]     = $row['situ_in_class_code'];
        $data["situ_in_date"]           = $timedate->to_display_date($row['situ_in_date']);
        $data["situ_in_center_name"]    = $row['situ_in_center_name'];

        //Student info
        $data["student_name"]           = $row['student_name'];
        $data["student_code"]           = $row['student_code'];
        $data["student_mobile"]         = $row['student_mobile'];
        $data["student_address"]        = $row['student_address'];

        //Related Payment info
        $data["relate_payment_code"]    = $row['relate_payment_code'];
        $data["relate_payment_amount"]  = $row['relate_payment_amount'];
        $data["relate_payment_used_amount"]  = $row['relate_payment_amount'] - $row['situ_out_amount'];
        $data["relate_payment_date"]    = $timedate->to_display_date($row['relate_payment_date']);
    }
    return $data;
}

function writeData($situation, $objPHPExcel, $data){
    if($situation->type == "Moving Out" || $situation->type == "Moving In"){
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D6',  $data['relate_payment_code'])
        ->setCellValue('D8',  $data['student_code'])
        ->setCellValue('D10', $data['student_address'])
        ->setCellValue('D12', $data['situ_out_class_code'])
        ->setCellValue('D14', $data['relate_payment_date'])
        ->setCellValue('D16', $data['situ_out_center_name'])
        ->setCellValue('J7',  $data['situ_out_date'])
        ->setCellValue('I8',  $data['student_name'])
        ->setCellValue('I10', $data['student_mobile'])
        ->setCellValue('I10', $data['student_mobile'])
        ->setCellValue('E19', $data['relate_payment_amount'])
        ->setCellValue('E21', $data['relate_payment_used_amount'])
        ->setCellValue('E23', $data['situ_out_amount'])
        ->setCellValue('E25', "0")
        ->setCellValue('E27', $data['situ_out_amount'])
        ->setCellValue('J19', $data['student_code'])
        ->setCellValue('J21', $data['student_name'])
        ->setCellValue('J23', $data['situ_out_amount'])
        ->setCellValue('J25', $data['situ_out_date'])
        ->setCellValue('J27', $data['situ_in_date'])
        ->setCellValue('J29', $data['situ_out_description'])
        ->setCellValue('J31', $data['situ_in_center_name']);
    }
}


?>