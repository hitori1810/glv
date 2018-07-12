<?php
require_once("custom/include/PHPExcel/Classes/PHPExcel.php");
require_once("custom/include/PHPExcel/Classes/PHPExcel/IOFactory.php");
require_once("custom/include/ConvertMoneyString/convert_number_to_string.php");
ob_clean();

$paymentId = $_REQUEST['record'];
$payment = new J_Payment();
$payment->retrieve($paymentId);
$team_type = getTeamType($payment->team_id);

//Load template
$section = create_guid_section(6);
switch($payment->payment_type){
    case "Refund":
        $inputFileName = "custom/include/TemplateExcel/Form_Refund_$team_type.xlsx";
        $outputFileName = 'custom/uploads/Refund_'.$payment->name.'-'.$section.'.xlsx';
        break;
    case "Delay":
        $inputFileName = "custom/include/TemplateExcel/Form_Delay_$team_type.xlsx";
        $outputFileName = 'custom/uploads/Delay_'.$payment->name.'-'.$section.'.xlsx';
        break;
    case "Moving Out":
    case "Moving In":
        $inputFileName = "custom/include/TemplateExcel/Form_Moving_Center_$team_type.xlsx";
        $outputFileName = 'custom/uploads/Moving_center_'.$payment->name.'-'.$section.'.xlsx';
        break;
    case "Transfer Out":
    case "Transfer In":
        $inputFileName = "custom/include/TemplateExcel/Form_Transfer_$team_type.xlsx";
        $outputFileName = 'custom/uploads/Transfer_'.$payment->name.'-'.$section.'.xlsx';
        break;
    default:
    $inputFileName = "";
    $outputFileName = "";
    break;
}

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
$data = getData($payment);

//Write to Excel file
writeData($payment, $objPHPExcel, $data, $team_type);

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

function getData($payment){
    global $timedate;
    $data = array();
    $text = new Integer();

    if($payment->payment_type == "Refund"){
        $sql = "SELECT
        IFNULL(j_payment.name, '') payment_code,
        IFNULL(j_payment.payment_amount, '') payment_amount,
        IFNULL(j_payment.refund_revenue, '') refund_revenue,
        IFNULL(j_payment.payment_date, '') payment_date,
        IFNULL(l2.id, '') team_id,
        IFNULL(l2.name, '') team_name,
        IFNULL(l1.id, '') related_pay_id,
        IFNULL(l1.name, '') related_pay_code,
        SUM(IFNULL(l1.payment_amount + l1.paid_amount + l1.deposit_amount, 0)) related_pay_amount,
        MIN(l1.payment_date) related_pay_date,
        IFNULL(l1.payment_type, '') related_pay_type,

        IFNULL(l3.id, '') student_id,
        IFNULL(l3.contact_id, '') student_code,
        IFNULL(l3.full_student_name, '') student_name,
        IFNULL(l3.phone_mobile, '') student_mobile,
        IFNULL(GROUP_CONCAT(l6.invoice_number SEPARATOR ' '), '') invoice_number,
        IFNULL(l3.primary_address_street, '') student_address
        FROM j_payment
        LEFT JOIN j_payment_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_payment_j_payment_1j_payment_ida AND l1_1.deleted = 0
        LEFT JOIN j_payment l1 ON l1.id = l1_1.j_payment_j_payment_1j_payment_idb  AND l1.deleted = 0

        LEFT JOIN teams l2 ON j_payment.team_id = l2.id AND l2.deleted = 0
        LEFT JOIN contacts_j_payment_1_c l3_1 ON j_payment.id = l3_1.contacts_j_payment_1j_payment_idb AND l3_1.deleted = 0
        LEFT JOIN contacts l3 ON l3.id = l3_1.contacts_j_payment_1contacts_ida AND l3.deleted = 0
        LEFT JOIN j_paymentdetail l6 ON l6.payment_id = l1.id AND l6.deleted = 0
        WHERE
        j_payment.id = '{$payment->id}'
        AND j_payment.deleted = 0
        LIMIT 1";
        $rsPayment = $GLOBALS['db']->query($sql);
        $row = $GLOBALS['db']->fetchByAssoc($rsPayment);

        $data["payment_code"]           = $row['payment_code'];
        $data["payment_date"]           = $timedate->to_display_date($row['payment_date']);
        $data["payment_amount"]         = $row['payment_amount'];
        $data["student_code"]           = $row['student_code'];
        $data["student_name"]           = $row['student_name'];
        $data["student_address"]        = $row['student_address'];
        $data["student_mobile"]         = $row['student_mobile'];
        $data["related_pay_date"]       = $timedate->to_display_date($row['related_pay_date']);
        $data["related_pay_code"]       = $row['related_pay_code'];
        $data["related_pay_id"]         = $row['related_pay_id'];
        $data["related_pay_amount"]     = getRelatedAmount($payment->id);
        if($data["related_pay_amount"] < ($row['payment_amount'] + $row['refund_revenue']))
            $data["related_pay_amount"]     = $row['related_pay_amount'];
        $data["related_used_amount"]    = $data["related_pay_amount"] - $row['payment_amount'] - $row['refund_revenue'];
        $data["related_remain_amount"]  = $row['payment_amount'] + $row['refund_revenue'];
        $data["reduce"]                 = number_format(($row['refund_revenue'] / ($row['refund_revenue'] + $row['payment_amount']) * 100),2);
        $data["invoice_number"]         = $row['invoice_number'];
        $data["admin_fee"]              = $row['refund_revenue'];
        $data["payment_amount_str"]     = $text->toText($row['payment_amount']);
        $data["description"]            = $row['description'];
        $count = 10;
        while ($data["invoice_number"] == '' && $count>0){
            $sql = "SELECT
            IFNULL(j_payment.name, '') payment_code,
            IFNULL(j_payment.payment_amount, '') payment_amount,
            IFNULL(j_payment.refund_revenue, '') refund_revenue,
            IFNULL(j_payment.payment_date, '') payment_date,
            IFNULL(l2.id, '') team_id,
            IFNULL(l2.name, '') team_name,
            IFNULL(l1.id, '') related_pay_id,
            IFNULL(l1.name, '') related_pay_code,
            IFNULL(l1.payment_amount + l1.paid_amount + l1.deposit_amount, 0) related_pay_amount,
            l1.payment_date related_pay_date,
            IFNULL(l1.payment_type, '') related_pay_type,

            IFNULL(l3.id, '') student_id,
            IFNULL(l3.contact_id, '') student_code,
            IFNULL(l3.full_student_name, '') student_name,
            IFNULL(l3.phone_mobile, '') student_mobile,
            IFNULL(GROUP_CONCAT(l6.invoice_number SEPARATOR ' '), '') invoice_number,
            IFNULL(l3.primary_address_street, '') student_address
            FROM j_payment
            LEFT JOIN j_payment_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_payment_j_payment_1j_payment_ida AND l1_1.deleted = 0
            LEFT JOIN j_payment l1 ON l1.id = l1_1.j_payment_j_payment_1j_payment_idb  AND l1.deleted = 0

            LEFT JOIN teams l2 ON j_payment.team_id = l2.id AND l2.deleted = 0
            LEFT JOIN contacts_j_payment_1_c l3_1 ON j_payment.id = l3_1.contacts_j_payment_1j_payment_idb AND l3_1.deleted = 0
            LEFT JOIN contacts l3 ON l3.id = l3_1.contacts_j_payment_1contacts_ida AND l3.deleted = 0
            LEFT JOIN j_paymentdetail l6 ON l6.payment_id = l1.id AND l6.deleted = 0
            WHERE
            j_payment.id = '{$data["related_pay_id"]}'
            AND j_payment.deleted = 0
            LIMIT 1";
            $rsPayment = $GLOBALS['db']->query($sql);
            $row = $GLOBALS['db']->fetchByAssoc($rsPayment);
            $data["related_pay_date"]       = $timedate->to_display_date($row['related_pay_date']);
            $data["related_pay_code"]       = $row['related_pay_code'];
            $data["related_pay_id"]         = $row['related_pay_id'];
            $data["related_pay_amount"]     = $row['related_pay_amount'];
            $data["related_used_amount"]    = $row['related_pay_amount'] - $data['payment_amount'] - $data['admin_fee'];
            $data["related_remain_amount"]  = $data['payment_amount'] + $data['admin_fee'];
            //        $data["reduce"]                 = number_format(($row['refund_revenue'] / ($row['refund_revenue'] + $row['payment_amount']) * 100),2);
            $data["invoice_number"]         = $row['invoice_number'];
            //        $data["admin_fee"]              = $row['refund_revenue'];
            //        $data["payment_amount_str"]     = $text->toText($row['payment_amount']);
            $data["description"]            = $row['description'];
            $count--;
        }

    }
    elseif($payment->payment_type == "Delay"){
        $sql = "SELECT
        IFNULL(j_payment.name, '') payment_code,
        IFNULL(j_payment.payment_amount, '') payment_amount,
        IFNULL(j_payment.refund_revenue, '') refund_revenue,
        IFNULL(j_payment.payment_date, '') payment_date,
        IFNULL(j_payment.payment_expired, '') payment_expired,
        IFNULL(j_payment.tuition_hours, '') payment_hours,
        IFNULL(l4.description, '') payment_description,
        IFNULL(l2.name, '') team_name,
        IFNULL(l1.id, '') related_pay_id,
        IFNULL(l1.name, '') related_pay_code,
        IFNULL(l1.payment_amount + l1.paid_amount + l1.deposit_amount, 0) related_pay_amount,
        IFNULL(l1.payment_date, '') related_pay_date,
        IFNULL(l1.payment_type, '') related_pay_type,
        IFNULL(l3.contact_id, '') student_code,
        IFNULL(l3.full_student_name, '') student_name,
        IFNULL(l3.phone_mobile, '') student_mobile,
        IFNULL(l3.primary_address_street, '') student_address,
        IFNULL(l5.name, '') class_code,
        IFNULL(GROUP_CONCAT(l6.invoice_number SEPARATOR ' '), '') invoice_number,
        IFNULL(l5.level, '') class_level
        FROM j_payment
        LEFT JOIN j_payment_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_payment_j_payment_1j_payment_ida AND l1_1.deleted = 0
        LEFT JOIN j_payment l1 ON l1.id = l1_1.j_payment_j_payment_1j_payment_idb  AND l1.deleted = 0
        LEFT JOIN teams l2 ON j_payment.team_id = l2.id AND l2.deleted = 0
        LEFT JOIN contacts_j_payment_1_c l3_1 ON j_payment.id = l3_1.contacts_j_payment_1j_payment_idb AND l3_1.deleted = 0
        LEFT JOIN contacts l3 ON l3.id = l3_1.contacts_j_payment_1contacts_ida AND l3.deleted = 0
        LEFT JOIN j_studentsituations l4 ON l4.payment_id = j_payment.id AND l4.deleted = 0
        LEFT JOIN j_class l5 ON l5.id = l4.ju_class_id AND l5.deleted = 0
        LEFT JOIN j_paymentdetail l6 ON l6.payment_id = l1.id AND l6.deleted = 0
        WHERE
        j_payment.id = '{$payment->id}'
        AND j_payment.deleted = 0
        LIMIT 1";
        $rsPayment = $GLOBALS['db']->query($sql);
        $row = $GLOBALS['db']->fetchByAssoc($rsPayment);

        $data["payment_code"]           = $row['payment_code'];
        $data["payment_date"]           = $timedate->to_display_date($row['payment_date']);
        $data["payment_expired"]        = $timedate->to_display_date($row['payment_expired']);
        $data["payment_amount"]         = $row['payment_amount'];
        $data["payment_hours"]          = $row['payment_hours'];
        $data["payment_amount_str"]     = $text->toText($row['payment_amount']);
        $data["unit_cost"]              = number_format(($row['payment_amount'] / $row['payment_hours']),0);
        $data["payment_description"]    = $row['payment_description'];

        $data["student_code"]           = $row['student_code'];
        $data["student_name"]           = $row['student_name'];
        $data["student_address"]        = $row['student_address'];
        $data["student_mobile"]         = $row['student_mobile'];

        $data["related_pay_date"]       = $timedate->to_display_date($row['related_pay_date']);
        $data["related_pay_id"]         = $row['related_pay_id'];
        $data["related_pay_code"]       = $row['related_pay_code'];
        $data["related_pay_amount"]     = $row['related_pay_amount'];
        $data["related_used_amount"]    = $row['related_pay_amount'] - $row['payment_amount'] - $row['refund_revenue'];
        $data["related_remain_amount"]  = $row['payment_amount'] + $row['refund_revenue'];
        $data["invoice_number"]         = $row['invoice_number'];
        $data["class_code"]             = $row['class_code'];
        $data["class_level"]            = $row['class_level'];
    }
    elseif($payment->payment_type == "Moving In" || $payment->payment_type == "Moving Out"){
        if ($payment->payment_type == "Moving In"){
            $paymentInId = $payment->id;
            $paymentInBean = BeanFactory::getBean("J_Payment", $payment->id);
            $paymentOutId = $paymentInBean->payment_out_id;
            $paymentOutBean = BeanFactory::getBean("J_Payment", $paymentOutId);
        }else{
            $paymentOutId = $payment->id;
            $paymentOutBean = BeanFactory::getBean("J_Payment", $payment->id);
            $paymentOutBean->load_relationship("ju_payment_in");
            $paymentInBean = reset($paymentOutBean->ju_payment_in->getBeans());
            $paymentInId = $paymentInBean->id;
        }

        $sql = "SELECT
        IFNULL(j_payment.name, '') pay_out_code,
        IFNULL(j_payment.payment_date, '') pay_out_date,
        IFNULL(j_payment.payment_amount, '') pay_out_amount,
        IFNULL(j_payment.description, '') pay_out_description,
        IFNULL(l2.name, '') pay_out_center,
        IFNULL(l1.name, '') related_pay_code,
        IFNULL(l1.id, '') related_pay_id,
        IFNULL(l1.payment_type, '') related_payment_type,
        MIN(l1.payment_date) related_pay_date,
        SUM(IFNULL(l1.payment_amount + l1.paid_amount + l1.deposit_amount, 0)) related_pay_amount,

        IFNULL(l9.name, '') related_pay_code_1,
        IFNULL(l9.id, '') related_pay_id_1,
        IFNULL(l9.payment_type, '') related_payment_type_1,
        l9.payment_date related_pay_date_1,
        IFNULL(l9.payment_amount + l9.paid_amount + l9.deposit_amount, 0) related_pay_amount_1,

        IFNULL(l4.name, '') pay_in_code,
        IFNULL(l4.payment_date, '') pay_in_date,
        IFNULL(l5.name, '') pay_in_center,
        IFNULL(l3.contact_id, '') student_code,
        IFNULL(l3.full_student_name, '') student_name,
        IFNULL(l3.phone_mobile, '') student_mobile,
        IFNULL(GROUP_CONCAT(l6.invoice_number SEPARATOR ' '), '') invoice_number,
        IFNULL(l3.primary_address_street, '') student_address
        FROM
        j_payment
        LEFT JOIN j_payment_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_payment_j_payment_1j_payment_ida AND l1_1.deleted = 0
        LEFT JOIN j_payment l1 ON l1.id = l1_1.j_payment_j_payment_1j_payment_idb  AND l1.deleted = 0

        LEFT JOIN j_payment_j_payment_1_c l9_1 ON l1.id = l9_1.j_payment_j_payment_1j_payment_ida AND l9_1.deleted = 0
        LEFT JOIN j_payment l9 ON l9.id = l9_1.j_payment_j_payment_1j_payment_idb  AND l9.deleted = 0

        LEFT JOIN teams l2 ON j_payment.team_id = l2.id AND l2.deleted = 0
        LEFT JOIN contacts_j_payment_1_c l3_1 ON j_payment.id = l3_1.contacts_j_payment_1j_payment_idb AND l3_1.deleted = 0
        LEFT JOIN contacts l3 ON l3.id = l3_1.contacts_j_payment_1contacts_ida AND l3.deleted = 0
        LEFT JOIN j_payment l4 ON j_payment.id = l4.payment_out_id  AND l4.deleted = 0
        LEFT JOIN teams l5 ON l4.team_id = l5.id AND l5.deleted = 0
        LEFT JOIN j_paymentdetail l6 ON l6.payment_id = l1.id AND l6.deleted = 0
        WHERE
        j_payment.id = '{$paymentOutBean->id}'
        AND j_payment.deleted = 0
        LIMIT 1";
        $result = $GLOBALS['db']->query($sql);
        $row = $GLOBALS['db']->fetchByAssoc($result);

        $data["pay_out_code"]       = $row['pay_out_code'];
        $data["pay_out_date"]       = $timedate->to_display_date($row['pay_out_date']);
        $data["pay_out_center"]     = $row['pay_out_center'];
        $data["pay_out_amount"]     = $row['pay_out_amount'];
        $data["pay_out_description"]= $row['pay_out_description'];

        $data["related_pay_code"]           = $row['related_pay_code'];
        $data["related_pay_id"]             = $row['related_pay_id'];
        $data["related_pay_date"]           = $timedate->to_display_date($row['related_pay_date']);

        $data["related_pay_amount"]     = getRelatedAmount($paymentOutBean->id);
        if($data["related_pay_amount"] < ($row['pay_out_amount']))
            $data["related_pay_amount"]         = $row['related_pay_amount'];
        $data["related_pay_used_amount"]    = $data["related_pay_amount"] - $row['pay_out_amount'];
        if($row['related_payment_type'] == 'Delay'){
            $data["related_pay_code"]           = $row['related_pay_code_1'];
            $data["related_pay_id"]             = $row['related_pay_id_1'];
            $data["related_pay_date"]           = $timedate->to_display_date($row['related_pay_date_1']);
            $data["related_pay_amount"]         = $row['related_pay_amount_1'];
            $data["related_pay_used_amount"]    = $row['related_pay_amount_1'] - $row['pay_out_amount'];
        }
        $data["student_code"]       = $row['student_code'];
        $data["student_name"]       = $row['student_name'];
        $data["student_address"]    = $row['student_address'];
        $data["student_mobile"]     = $row['student_mobile'];

        $data["pay_in_code"]        = $row['pay_in_code'];
        $data["invoice_number"]     = $row['invoice_number'];
        $data["pay_in_date"]        = $timedate->to_display_date($row['pay_in_date']);
        $data["pay_in_center"]      = $row['pay_in_center'];
    }
    elseif($payment->payment_type == "Transfer In" || $payment->payment_type == "Transfer Out"){
        if ($payment->payment_type == "Transfer In"){
            $paymentInId = $payment->id;
            $paymentInBean = BeanFactory::getBean("J_Payment", $payment->id);
            $paymentOutId = $paymentInBean->payment_out_id;
            $paymentOutBean = BeanFactory::getBean("J_Payment", $paymentOutId);
        }
        else{
            $paymentOutId = $payment->id;
            $paymentOutBean = BeanFactory::getBean("J_Payment", $payment->id);
            $paymentOutBean->load_relationship("ju_payment_in");
            $paymentInBean = reset($paymentOutBean->ju_payment_in->getBeans());
            $paymentInId = $paymentInBean->id;
        }

        $sql = "SELECT
        IFNULL(j_payment.name, '') pay_out_code
        ,IFNULL(j_payment.payment_date, '') pay_out_date
        ,IFNULL(j_payment.payment_amount, '') pay_out_amount
        ,IFNULL(j_payment.tuition_hours, '') pay_out_hour
        ,IFNULL(j_payment.description, '') pay_out_description
        ,IFNULL(l2.name, '') pay_out_center
        ,IFNULL(l3.contact_id, '') pay_out_student_code
        ,IFNULL(l3.full_student_name, '') pay_out_student_name
        ,IFNULL(l3.phone_mobile, '') pay_out_student_mobile
        ,IFNULL(l3.primary_address_street, '') pay_out_student_address
        ,IFNULL(l1.name, '') related_pay_code
        ,IFNULL(l1.id, '') related_pay_id
        ,IFNULL(l1.payment_type, '') related_payment_type
        ,MIN(l1.payment_date) related_pay_date
        ,SUM(IFNULL(l1.payment_amount + l1.paid_amount + l1.deposit_amount, 0)) related_pay_amount
        ,
        IFNULL(l9.name, '') related_pay_code_1,
        IFNULL(l9.id, '') related_pay_id_1,
        IFNULL(l9.payment_type, '') related_payment_type_1,
        IFNULL(l9.payment_date, '') related_pay_date_1,
        IFNULL(l9.payment_amount + l9.paid_amount + l9.deposit_amount, 0) related_pay_amount_1,

        IFNULL(l4.name, '') pay_in_code
        ,IFNULL(l4.payment_date, '') pay_in_date
        ,IFNULL(l5.name, '') pay_in_center
        ,IFNULL(l6.contact_id, '') pay_in_student_code
        ,IFNULL(l6.full_student_name, '') pay_in_student_name
        ,IFNULL(l6.phone_mobile, '') pay_in_student_mobile
        ,IFNULL(l6.primary_address_street, '') pay_in_student_address
        FROM
        j_payment
        LEFT JOIN j_payment_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_payment_j_payment_1j_payment_ida AND l1_1.deleted = 0
        LEFT JOIN j_payment l1 ON l1.id = l1_1.j_payment_j_payment_1j_payment_idb  AND l1.deleted = 0


        LEFT JOIN j_payment_j_payment_1_c l9_1 ON l1.id = l9_1.j_payment_j_payment_1j_payment_ida AND l9_1.deleted = 0
        LEFT JOIN j_payment l9 ON l9.id = l9_1.j_payment_j_payment_1j_payment_idb  AND l9.deleted = 0


        LEFT JOIN teams l2 ON j_payment.team_id = l2.id AND l2.deleted = 0
        LEFT JOIN contacts_j_payment_1_c l3_1 ON j_payment.id = l3_1.contacts_j_payment_1j_payment_idb AND l3_1.deleted = 0
        LEFT JOIN contacts l3 ON l3.id = l3_1.contacts_j_payment_1contacts_ida AND l3.deleted = 0
        LEFT JOIN contacts l6 ON l6.id = j_payment.transfer_to_student_id AND l6.deleted = 0
        LEFT JOIN j_payment l4 ON j_payment.id = l4.payment_out_id  AND l4.deleted = 0
        LEFT JOIN teams l5 ON l4.team_id = l5.id AND l5.deleted = 0
        WHERE
        j_payment.id = '{$paymentOutBean->id}'
        AND j_payment.deleted = 0
        LIMIT 1";
        $result = $GLOBALS['db']->query($sql);
        $row = $GLOBALS['db']->fetchByAssoc($result);

        //Payment transfer out
        $data["pay_out_code"]       = $row['pay_out_code'];
        $data["pay_out_date"]       = $timedate->to_display_date($row['pay_out_date']);
        $data["pay_out_center"]     = $row['pay_out_center'];
        $data["pay_out_amount"]     = $row['pay_out_amount'];
        $data["pay_out_hour"]     = $row['pay_out_hour'];
        $data["pay_out_description"]= $row['pay_out_description'];

        //Student in payment transfer out
        $data["pay_out_student_code"]       = $row['pay_out_student_code'];
        $data["pay_out_student_name"]       = $row['pay_out_student_name'];
        $data["pay_out_student_address"]    = $row['pay_out_student_address'];
        $data["pay_out_student_mobile"]     = $row['pay_out_student_mobile'];

        //Payment transfer in
        $data["pay_in_code"]        = $row['pay_in_code'];
        $data["pay_in_date"]        = $timedate->to_display_date($row['pay_in_date']);
        $data["pay_in_center"]      = $row['pay_in_center'];

        //Student in payment transfer in
        $data["pay_in_student_code"]       = $row['pay_in_student_code'];
        $data["pay_in_student_name"]       = $row['pay_in_student_name'];
        $data["pay_in_student_address"]    = $row['pay_in_student_address'];
        $data["pay_in_student_mobile"]     = $row['pay_in_student_mobile'];

        //Related payment
        $data["related_pay_id"]     = $row['related_pay_id'];
        $data["related_pay_code"]   = $row['related_pay_code'];
        $data["related_pay_date"]   = $timedate->to_display_date($row['related_pay_date']);
        $data["related_pay_amount"]     = getRelatedAmount($paymentOutBean->id);
        if($data["related_pay_amount"] < ($row['pay_out_amount']))
            $data["related_pay_amount"]         = $row['related_pay_amount'];
        $data["related_pay_used_amount"] = $data["related_pay_amount"] - $row['pay_out_amount'];

        if($row['related_payment_type'] == 'Delay'){
            $data["related_pay_code"]           = $row['related_pay_code_1'];
            $data["related_pay_id"]             = $row['related_pay_id_1'];
            $data["related_pay_date"]           = $timedate->to_display_date($row['related_pay_date_1']);
            $data["related_pay_amount"]         = $row['related_pay_amount_1'];
            $data["related_pay_used_amount"]    = $row['related_pay_amount_1'] - $row['pay_out_amount'];
        }
    }
    return $data;
}

function writeData($payment, $objPHPExcel, $data, $team_type){
    if($team_type == 'Junior'){
        if($payment->payment_type == "Refund"){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J6', $data['payment_code'])
            ->setCellValue('J7', $data['payment_date'])
            ->setCellValue('E8', $data['related_pay_code'])
            ->setCellValue('D10', $data['student_code'])
            ->setCellValue('I10', $data['student_name'])
            ->setCellValue('D12', $data['student_address'])
            ->setCellValue('I12', $data['student_mobile'])
            ->setCellValue('D16', $data['related_pay_date'])
            ->setCellValue('I16', $data['invoice_number'])
            ->setCellValue('E20', $data['related_pay_amount'])
            ->setCellValue('E22', $data['related_used_amount'])
            ->setCellValue('E24', $data['related_remain_amount'])
            ->setCellValue('E28', $data['related_remain_amount'])
            ->setCellValue('J20', $data['related_remain_amount'])
            ->setCellValue('J22', $data['reduce'])
            ->setCellValue('J24', $data['payment_amount'])
            ->setCellValue('H27', $data['payment_amount_str'])
            ->setCellValue('H30', $data['description']);
        }
        elseif($payment->payment_type == "Delay"){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('H6', $data['payment_code']) //No
            ->setCellValue('H7', $data['payment_date']) //Date
            ->setCellValue('D9', $data['student_code']) //Student Code
            ->setCellValue('G9', $data['student_name']) //Student Name
            ->setCellValue('D11', $data['student_address']) // Student Address
            ->setCellValue('G11', $data['student_mobile']) //Student phone
            ->setCellValue('D13', $data['class_code']) // Class Code
            ->setCellValue('D16', $data['related_pay_date'])//Date of payment
            ->setCellValue('D18', $data['related_pay_amount']) //Amount paid
            ->setCellValue('G13', $data['class_level']) //Class level
            ->setCellValue('I18', $data['related_used_amount']) //Amount of studied lesson
            ->setCellValue('E20', $data['payment_amount']) //Amount of paid lessons left
            ->setCellValue('I20', $data['unit_cost']) //Amount of 1 hours
            ->setCellValue('D23', $data['payment_description'])// Description
            ->setCellValue('D25', $data['payment_date']) //Date of payment
            ->setCellValue('G25', $data['payment_expired']); // payment expired
        }
        elseif($payment->payment_type == "Moving In" || $payment->payment_type == "Moving Out"){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E6', $data['related_pay_code']) //Rdf No.
            ->setCellValue('J6', $data['pay_out_code']) //No.
            ->setCellValue('D8', $data['student_code']) //Student Code
            ->setCellValue('I8', $data['student_name']) //Student Name
            ->setCellValue('J7', $data['pay_out_date']) //Date
            ->setCellValue('D10', $data['student_address']) //AddressAddress
            ->setCellValue('I10', $data['student_mobile']) //Telephone
            ->setCellValue('D14', $data['pay_out_date']) //Date of payment
            ->setCellValue('D16', $data['pay_out_center']) //Center moving from
            ->setCellValue('E19', $data['related_pay_amount']) //Amount paid
            ->setCellValue('E21', $data['related_pay_used_amount']) //Amount of studied lesson
            ->setCellValue('E23', $data['pay_out_amount']) //Amount of paid lessons left
            ->setCellValue('E25', "0")
            ->setCellValue('E28', $data['pay_out_amount']) //Amount accept tranfer
            ->setCellValue('J19', $data['student_code']) //Student receive code
            ->setCellValue('J21', $data['student_name']) //Student receive name
            ->setCellValue('J23', $data['pay_out_amount']) //Amount accept tranfer
            ->setCellValue('J25', $data['pay_out_date']) //Moving out date
            ->setCellValue('J27', $data['pay_in_date']) //Moving in date
            ->setCellValue('J29', $data['description']) //Amount accept tranfer
            ->setCellValue('J31', $data['pay_in_center']); //Amount accept tranfer
        }
        elseif($payment->payment_type == "Transfer In" || $payment->payment_type == "Transfer Out"){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E6', $data['related_pay_code']) //Rdf No.
            ->setCellValue('D8', $data['pay_out_student_code']) //Student Code
            ->setCellValue('D10', $data['pay_out_student_address']) //Address
            ->setCellValue('D14', $data['related_pay_date']) //Date of Payment
            ->setCellValue('D16', $data['pay_out_center']) //Date of Payment
            ->setCellValue('J6', $data['pay_out_code']) //No.
            ->setCellValue('J7', $data['pay_out_date']) //Date
            ->setCellValue('I8', $data['pay_out_student_name']) //Student Name
            ->setCellValue('I10', $data['pay_out_student_mobile']) //Mobile
            ->setCellValue('E19', $data['related_pay_amount']) //Amount Paid
            ->setCellValue('E21', $data['related_pay_used_amount']) //Amount of studied lessons
            ->setCellValue('E23', $data['pay_out_amount']) //Amount of paid lessons left
            ->setCellValue('E25', "0") //Transfer from other student
            ->setCellValue('E27', $data['pay_out_amount']) //Amount accept tranfer
            ->setCellValue('J19', $data['pay_in_student_code']) //Receiving Student Code
            ->setCellValue('J21', $data['pay_in_student_name']) //Receiving Student Name
            ->setCellValue('J23', $data['pay_out_amount']) //Amount Converter
            ->setCellValue('J25', $data['pay_in_date']) //Transfer Date
            ->setCellValue('J27', $data['pay_out_description']) //Reason
            ->setCellValue('J29', $data['pay_in_center']); //Center Name of Transfer In
        }
    }else{
        if($payment->payment_type == "Refund"){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('H8', $data['payment_code'])
            ->setCellValue('H9', $data['payment_date'])

            ->setCellValue('H12', $data['student_code'])
            ->setCellValue('C12', $data['student_name'])
            ->setCellValue('B14', $data['student_address'])
            ->setCellValue('H14', $data['student_mobile'])
            ->setCellValue('C18', $data['related_pay_date'])
            ->setCellValue('H18', $data['invoice_number'])
            ->setCellValue('C21', $data['related_pay_amount'])
            ->setCellValue('C23', $data['related_used_amount'])
            ->setCellValue('C25', $data['related_remain_amount'])
            ->setCellValue('C29', $data['related_remain_amount'])

            ->setCellValue('H21', $data['related_remain_amount'])
            ->setCellValue('H23', $data['admin_fee'])
            ->setCellValue('H25', $data['payment_amount'])
            ->setCellValue('E29', $data['payment_amount_str'])
            ->setCellValue('E33', $data['description']);
        }
        elseif($payment->payment_type == "Delay"){
            $row_class = get_class_info($data['related_pay_id']);
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G8', $data['payment_code']) //No
            ->setCellValue('G9', $data['payment_date']) //Date
            ->setCellValue('G12', $data['student_code']) //Student Code
            ->setCellValue('B12', $data['student_name']) //Student Name
            ->setCellValue('B14', $data['student_address']) // Student Address
            ->setCellValue('G14', $data['student_mobile']) //Student phone
            ->setCellValue('B16', $row_class['level']) //Class level
            ->setCellValue('G16', $row_class['class_code']) // Class Code
            ->setCellValue('B18', $data['related_pay_date']) //Date of payment
            ->setCellValue('G18', $data['invoice_number']) //Date of payment
            ->setCellValue('B20', $data['related_pay_amount'])//Date of payment
            ->setCellValue('B22', $data['related_remain_amount']) //Amount of studied lesson
            ->setCellValue('B24', $data['payment_description'])
            ->setCellValue('B26', $data['payment_date'])
            ->setCellValue('E26', $data['payment_expired'])
            ->setCellValue('G20', $data['related_used_amount']); // payment expired
        }
        elseif($payment->payment_type == "Moving In" || $payment->payment_type == "Moving Out"){
            $row_class = get_class_info($data['related_pay_id']);
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G8', $data['pay_out_code']) //No.
            ->setCellValue('G9', $data['pay_out_date']) //Date
            ->setCellValue('B11', $data['student_code']) //Student Code
            ->setCellValue('F11', $data['student_name']) //Student Name

            ->setCellValue('B13', $data['student_address']) //AddressAddress
            ->setCellValue('F13', $data['student_mobile']) //Telephone
            ->setCellValue('B15', $row_class['level']) //Class level
            ->setCellValue('F15', $row_class['class_code']) // Class Code
            ->setCellValue('B17', $data['related_pay_date']) //Date of payment
            ->setCellValue('F17', $data['invoice_number']) //Date of payment
            ->setCellValue('B19', $data['pay_out_center']) //Center moving from
            ->setCellValue('C23', $data['related_pay_amount'])
            ->setCellValue('C25', $data['related_pay_used_amount']) //Amount of studied lesson
            ->setCellValue('C27', $data['pay_out_amount'])
            ->setCellValue('C29', "0")
            ->setCellValue('C31', $data['pay_out_amount'])
            ->setCellValue('G23', $data['student_name']) //Student receive code
            ->setCellValue('G25', $data['student_code']) //Student receive name
            ->setCellValue('G27', $data['pay_out_amount']) //Amount accept tranfer
            ->setCellValue('G29', $data['pay_out_date']) //Moving out date
            ->setCellValue('G31', $data['pay_in_date']) //Moving in date
            ->setCellValue('G33', $data['pay_out_description']) //Amount accept tranfer
            ->setCellValue('G35', $data['pay_in_center']);
        }
        elseif($payment->payment_type == "Transfer In" || $payment->payment_type == "Transfer Out"){
            $row_class = get_class_info($data['related_pay_id']);
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F9', $data['pay_out_date']) //Date
            ->setCellValue('F12', $data['pay_out_student_code']) //Student Code
            ->setCellValue('B12', $data['pay_out_student_name']) //Student Name

            ->setCellValue('B14', $data['pay_out_student_address']) //AddressAddress
            ->setCellValue('F14', $data['pay_out_student_mobile']) //Telephone
            ->setCellValue('B16', $row_class['level']) //Class level
            ->setCellValue('F16', $row_class['class_code']) // Class Code
            ->setCellValue('B18', $data['related_pay_date']) //Date of payment
            ->setCellValue('F18', $data['invoice_number']) //Date of payment
            ->setCellValue('B20', $data['pay_out_center']) //Center moving from
            ->setCellValue('C24', $data['related_pay_amount'])
            ->setCellValue('C26', $data['related_pay_used_amount']) //Amount of studied lesson
            ->setCellValue('C28', $data['pay_out_amount'])
            ->setCellValue('C30', "0")
            ->setCellValue('C32', $data['pay_out_amount'])
            ->setCellValue('G24', $data['pay_in_student_name']) //Student receive code
            ->setCellValue('G26', $data['pay_in_student_code']) //Student receive name
            ->setCellValue('G28', $data['pay_out_amount']) //Amount accept tranfer
            ->setCellValue('G30', $data['pay_out_date']) //Moving out date
            ->setCellValue('G32', $data['pay_out_description']) //Amount accept tranfer
            ->setCellValue('G35', $data['pay_in_center']);
        }
    }

}

function get_class_info($related_pay_id){
    $rs_class = $GLOBALS['db']->query("SELECT
        ss.payment_id, c.class_code, c.level
        FROM
        j_studentsituations ss
        INNER JOIN
        j_class c ON c.id = ss.ju_class_id AND ss.deleted = 0
        AND c.deleted = 0
        AND c.class_type_adult = 'Practice'
        WHERE
        ss.payment_id = '{$related_pay_id}'
        ORDER BY ss.end_study DESC
        LIMIT 1");
    $row_class = $GLOBALS['db']->fetchByAssoc($rs_class);
    return $row_class;
}

function getRelatedAmount($payment_id){
    $rs_class = "SELECT DISTINCT
    SUM(IFNULL(IFNULL(l2.payment_amount, 0) / 1,
    IFNULL(l2.payment_amount, 0))) sum_payment_amount
    FROM
    j_payment
    INNER JOIN
    j_payment_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_payment_j_payment_1j_payment_ida
    AND l1_1.deleted = 0
    INNER JOIN
    j_payment l1 ON l1.id = l1_1.j_payment_j_payment_1j_payment_idb
    AND l1.deleted = 0
    INNER JOIN
    j_paymentdetail l2 ON l1.id = l2.payment_id AND l2.deleted = 0
    WHERE
    (((j_payment.id = '$payment_id')
    AND (l2.status = 'Paid')))
    AND j_payment.deleted = 0";
    return $GLOBALS['db']->getOne($rs_class);
}

?>