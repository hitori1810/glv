<?php
$studentId = $_REQUEST['student_id'];
$template = $_REQUEST['template'];
$student = new Contact();
$student = $student->retrieve($studentId);

//Load template
$inputFileName = "custom/include/TemplateExcel/RegistrationForm/".$template;
$section = create_guid_section(6);
$outputFileName = 'custom/uploads/registraion_form_'.$section.'.docx';

//Get Data
$data = getData($student);

//Write to Word  file
writeData($student, $data, $inputFileName, $outputFileName);

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

function getData($student){
    global $timedate;
    $data = array();

    $sql = "SELECT DISTINCT IFNULL(contacts.id,'') id
    ,IFNULL(contacts.full_student_name,'') full_name
    ,IFNULL(contacts.gender,'') gender
    ,contacts.birthdate birthdate
    ,IFNULL(contacts.nationality,'') nationality
    ,IFNULL(contacts.primary_address_street,'') address
    ,IFNULL(contacts.phone_home,'') home_phone
    ,IFNULL(contacts.phone_mobile,'') mobile_phone
    ,IFNULL(contacts.guardian_name,'') parent_name
    ,IFNULL(l2.email_address,'') email
    ,IFNULL(l1.name,'') company_name
    ,IFNULL(contacts.phone_work,'') company_phone
    ,IFNULL(l1.phone_fax,'') company_fax
    ,IFNULL(l1.tax_code,'') company_tax
    ,CONCAT(l1.billing_address_street,' ', l1.billing_address_city) company_address

    FROM contacts
    LEFT JOIN  accounts_contacts l1_1 ON contacts.id=l1_1.contact_id AND l1_1.deleted=0
    LEFT JOIN  accounts l1 ON l1.id=l1_1.account_id AND l1.deleted=0
    INNER JOIN  email_addr_bean_rel l2_1 ON contacts.id=l2_1.bean_id AND l2_1.deleted=0 AND l2_1.primary_address = '1'
    INNER JOIN  email_addresses l2 ON l2.id=l2_1.email_address_id AND l2.deleted=0
    WHERE contacts.id='{$student->id}'
    AND  contacts.deleted=0 ";
    $rs = $GLOBALS['db']->query($sql);
    $row = $GLOBALS['db']->fetchByAssoc($rs);

    $data["full_name"]          = $row['full_name'];
    $data["gender"]             = $row['gender'];
    $data["birthdate"]          = explode('/',$timedate->to_display_date($row['birthdate']));
    $data["nationality"]        = $row['nationality'] == "Việt Nam"? "X" : "";
    $data["address"]            = $row['address'];
    $data["home_phone"]         = $row['home_phone'];
    $data["mobile_phone"]       = $row['mobile_phone'];
    $data["parent_name"]        = $row['parent_name'];
    $data["email"]              = $row['email'];
    $data["company_name"]       = $row['company_name'];
    $data["company_phone"]      = $row['company_phone'];
    $data["company_fax"]        = $row['company_fax'];
    $data["company_address"]    = $row['company_address'];
    $data["company_tax"]        = $row['company_tax'];

    return $data;
}

function writeData($payment, $data, $inputFileName, $outputFileName){
    require_once 'custom/include/PHPWord/PhpWord/Autoloader.php';
    \PhpOffice\PhpWord\Autoloader::register();
    include_once("modules/J_Class/J_Class.php");
    include_once("custom/include/utils/file_utils.php");
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($inputFileName);

    $male =  $data["gender"] == "Male" ? "X" : "";
    $female =  $data["gender"] == "Female" ? "X" : "";
    if($data["nationality"] == "Việt Nam" || $data["nationality"] == ""){
        $nation_vn = "X";
        $nation_other = "";
        $nation_name = "";
    }
    else{
        $nation_vn = "";
        $nation_other = "X";
        $nation_name = $data["nationality"];
    }


    $templateProcessor->setValue('name', $data["full_name"]);
    $templateProcessor->setValue('male', $male);
    $templateProcessor->setValue('female', $female);
    $templateProcessor->setValue('day', $data["birthdate"][0]);
    $templateProcessor->setValue('month', $data["birthdate"][1]);
    $templateProcessor->setValue('year', $data["birthdate"][2]);
    $templateProcessor->setValue('nation_vn', $nation_vn);
    $templateProcessor->setValue('nation_other', $nation_other);
    $templateProcessor->setValue('nation_name', $nation_name);
    $templateProcessor->setValue('address', $data["address"]);
    $templateProcessor->setValue('home_phone', $data["home_phone"]);
    $templateProcessor->setValue('mobile_phone', $data["mobile_phone"]);
    $templateProcessor->setValue('parent_name', $data["parent_name"]);
    $templateProcessor->setValue('email', $data["email"]);
    $templateProcessor->setValue('company_name', $data["company_name"]);
    $templateProcessor->setValue('company_phone', $data["company_phone"]);
    $templateProcessor->setValue('company_fax', $data["company_fax"]);
    $templateProcessor->setValue('company_address', $data["company_address"]);
    $templateProcessor->setValue('company_tax', $data["company_tax"]);

    $templateProcessor->saveAs($outputFileName);
}


?>