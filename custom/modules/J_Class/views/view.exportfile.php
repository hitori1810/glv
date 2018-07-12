<?php
require_once 'custom/include/PHPWord/PhpWord/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();
require_once 'custom/include/PHPExcel/Classes/PHPExcel.php';
include("custom/include/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php");
include("custom/include/PHPExcel/Classes/PHPExcel/IOFactory.php");
include_once("custom/include/utils/file_utils.php");
$studentList = json_decode(html_entity_decode($_REQUEST['studentID']));
$template = $_REQUEST['template'];
$classID = $_REQUEST['classID'];
$certificateNumber = $_REQUEST['certificate_no'];
$class = new J_Class();
$class->retrieve($classID);
$classCode = $class->class_code;
$className = $class->name;
$team = new Team();
$team->retrieve($class->team_id);
$region = $team->region;
global $timedate, $current_user, $sugar_config;
$timedate->get_time_format($current_user);

$forder_template_url = "custom/include/TemplateExcel/Junior";
$forder_upload_file_url = "cache/JuniorTemplate";
if(!file_exists($forder_upload_file_url)) {
    mkdir($forder_upload_file_url, 0777);
}
deleteFileInForder($forder_upload_file_url, 25);
    
if($template == 'In Course Report (New)'){
    require_once("custom/include/_helper/junior_revenue_utils.php");
    $classSessions = get_list_lesson_by_class($classID);

    $lessonPlan = array(
        36    => '1',
        72    => '1',
        108   => '3',
        120   => '2',
        144   => '3',
    );
    $num_periods =$lessonPlan[(int)$class->hours];
    if(empty($num_periods)){
        $num_periods = '1';
    }

    $filename = "InCourseReport_".$num_periods."p_".(int)$class->hours.'hrs';
    $inputFileName = $forder_template_url."/Template_InCourseReport_new_{$num_periods}p.xlsx";
    try {
        $inputFileType  = PHPExcel_IOFactory::identify($inputFileName);
        $objReader      = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel    = $objReader->load($inputFileName);
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

    //set value in sheet SMS
    $sqlGetStudents = "SELECT DISTINCT l1.class_code, contact_id, full_student_name, birthdate, phone_mobile, contacts.picture
    FROM contacts
    INNER JOIN  j_class_contacts_1_c l1_1 ON contacts.id = l1_1.j_class_contacts_1contacts_idb AND l1_1.deleted = 0
    INNER JOIN  j_class l1 ON l1.id = l1_1.j_class_contacts_1j_class_ida AND l1.deleted = 0
    WHERE l1.id='{$classID}'
    AND  contacts.deleted=0 AND ( contacts.id = '" . implode("' OR contacts.id ='", $studentList) . "')" ;
    $rsGetStudents = $GLOBALS['db']->query($sqlGetStudents);

    while($rowStudent = $GLOBALS['db']->fetchByAssoc($rsGetStudents)) {
        $num_ped = (int)$num_periods;
        $smsName                = 'SMS_Record_'.$rowStudent['contact_id'];
        $objSmsWorkSheetBase    = $objPHPExcel->setActiveSheetIndex(2);
        $objSmsWorkSheet        = clone $objSmsWorkSheetBase;
        $objSmsWorkSheet->setTitle($smsName);
        $objPHPExcel->addSheet($objSmsWorkSheet);

        $objPHPExcel->setActiveSheetIndexByName($smsName)
        ->setCellValue('F4', $rowStudent['full_student_name'])
        ->setCellValue('F5', $timedate->to_display_date($rowStudent['birthdate'], false))
        ->setCellValue('F6', $rowStudent['contact_id'])
        ->setCellValue('F7', $rowStudent['class_code'])
        ->setCellValue('F8', $rowStudent['phone_mobile']);
        $cr_i       = 0;
        $cr_hour    = 0;
        for($i = 0; $i < 40; $i++ ){
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.(45+$i), $classSessions[$i]['lesson_number'])
            ->SetCellValue('B'.(45+$i), format_number($classSessions[$i]['till_hour'],2,2)." ");
            $cr_hour += $classSessions[$i]['delivery_hour'];
            if( $cr_hour >= ($class->hours / $num_periods)){
               $cr_i += $i+1;
               break;
            }

        }

        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $src = 'upload/'. $rowStudent['picture'];
        if($src == 'upload/' || !file_exists($src))
            $src = 'themes/default/images/noimage.png';

        $objDrawing->setPath($src);
        $objDrawing->setCoordinates('P4');
        $objDrawing->setHeight(150);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        $num_ped--;
        //SMS Part 2
        if($num_ped > 0){
            $objPHPExcel->getActiveSheet()
            ->setCellValue('F88', $rowStudent['full_student_name'])
            ->setCellValue('F89', $timedate->to_display_date($rowStudent['birthdate'], false))
            ->setCellValue('F90', $rowStudent['contact_id'])
            ->setCellValue('F91', $rowStudent['class_code'])
            ->setCellValue('F92', $rowStudent['phone_mobile']);
            $cr_hour = 0;
            for($i = 0; $i < 40; $i++ ){
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.(45+$i+84), $classSessions[$i+$cr_i]['lesson_number'])
                ->SetCellValue('B'.(45+$i+84), format_number($classSessions[$i+$cr_i]['till_hour'],2,2)." ");
                $cr_hour += $classSessions[$i+$cr_i]['delivery_hour'];
                if( $cr_hour >= ($class->hours / $num_periods)){
                    $cr_i += $i+1;
                    break;
                }
            }

            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $src = 'upload/'. $rowStudent['picture'];
            if($src == 'upload/' || !file_exists($src))
                $src = 'themes/default/images/noimage.png';

            $objDrawing->setPath($src);
            $objDrawing->setCoordinates('P88');
            $objDrawing->setHeight(150);
            $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        }
        $num_ped--;
        //SMS Part 3
        if($num_ped > 0){
            $objPHPExcel->getActiveSheet()
            ->setCellValue('F172', $rowStudent['full_student_name'])
            ->setCellValue('F173', $timedate->to_display_date($rowStudent['birthdate'], false))
            ->setCellValue('F174', $rowStudent['contact_id'])
            ->setCellValue('F175', $rowStudent['class_code'])
            ->setCellValue('F176', $rowStudent['phone_mobile']);
            $cr_hour = 0;
            for($i = 0; $i < 40; $i++ ){
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.(45+$i+84+84), $classSessions[$i+$cr_i]['lesson_number'])
                ->SetCellValue('B'.(45+$i+84+84), format_number($classSessions[$i+$cr_i]['till_hour'],2,2)." ");
                $cr_hour += $classSessions[$i+$cr_i]['delivery_hour'];
                if( $cr_hour >= ($class->hours / $num_periods))
                    break;
            }

            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $src = 'upload/'. $rowStudent['picture'];
            if($src == 'upload/' || !file_exists($src))
                $src = 'themes/default/images/noimage.png';

            $objDrawing->setPath($src);
            $objDrawing->setCoordinates('P172');
            $objDrawing->setHeight(150);
            $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        }
    }
//    //set value in sheet Test Result
//    $rsGetStudents2 = $GLOBALS['db']->query($sqlGetStudents);
//    while($rowStudent = $GLOBALS['db']->fetchByAssoc($rsGetStudents2)) {
//        $testName = 'Test_Report_'. $rowStudent['contact_id'];
//        $objTestWorkSheetBase = $objPHPExcel->setActiveSheetIndex(3);
//        $objTestWorkSheet = clone $objTestWorkSheetBase;
//        $objTestWorkSheet->setTitle($testName);
//        $objPHPExcel->addSheet($objTestWorkSheet);
//
//        $objPHPExcel->setActiveSheetIndexByName($testName)
//        ->setCellValue('G2', $rowStudent['full_student_name'])
//        ->setCellValue('G3', $timedate->to_display_date($rowStudent['birthdate'], false))
//        ->setCellValue('G4', $rowStudent['contact_id'])
//        ->setCellValue('G5', $rowStudent['class_code'])
//        ->setCellValue('G6', $rowStudent['phone_mobile']);
//
//        $objDrawing = new PHPExcel_Worksheet_Drawing();
//
//        $src = 'upload/'. $rowStudent['picture'];
//        if($src == 'upload/' || !file_exists($src))
//            $src = 'themes/default/images/noimage.png';
//
//        $objDrawing->setPath($src);
//        $objDrawing->setCoordinates('O2');
//        $objDrawing->setHeight(100);
//        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
//    }
    $objPHPExcel->getSheetByName('SMS')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
//    $objPHPExcel->getSheetByName('TestRecord')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

    $filename = "custom/uploads/".$filename."_".create_guid_section(6).".xlsx";
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter->save($filename);
    ob_end_clean();
    if (file_exists($filename)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($filename));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        ob_clean();
        flush();
        readfile($filename);
        unlink($filename);
        exit;
    }
}

//export Thank you template
elseif (strpos($template, 'Thanks you Template') !== false) {   //
    if(strpos($template, '(New)') !== false){
        $inputFileName = $forder_template_url.'/thankyou/'.$team->code_prefix.'_new.xlsx';
        if (!file_exists($inputFileName)) $inputFileName = $forder_template_url.'/thankyou/default_new.xlsx';
    }else{
        $inputFileName = $forder_template_url.'/thankyou/'.$team->code_prefix.'.xlsx';
        if (!file_exists($inputFileName)) $inputFileName = $forder_template_url.'/thankyou/default.xlsx';
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

    //Get data
    $sqlGetStudents = "SELECT DISTINCT l1.`name`, contacts.contact_id, l1.end_date, l1.kind_of_course, l1.hours, l1.`level`,l1.modules, full_student_name,contacts.first_name first_name, birthdate, users.sign, users.title, CONCAT(users.last_name, ' ', users.first_name) username
    FROM contacts
    INNER JOIN  j_class_contacts_1_c l1_1 ON contacts.id = l1_1.j_class_contacts_1contacts_idb AND l1_1.deleted = 0
    INNER JOIN  j_class l1 ON l1.id = l1_1.j_class_contacts_1j_class_ida AND l1.deleted = 0
    INNER JOIN  users  ON users.id = l1.assigned_user_id AND users.deleted = 0
    WHERE l1.id='{$classID}'
    AND  contacts.deleted=0 AND ( contacts.id = '" . implode("' OR contacts.id ='", $studentList) . "')" ;
    $rsGetStudents = $GLOBALS['db']->query($sqlGetStudents);
    $sheetCount = 0;

    while($rowStudent = $GLOBALS['db']->fetchByAssoc($rsGetStudents)) {
        if(strlen($rowStudent['full_student_name']) <= 31)
            $sheet_name = $rowStudent['full_student_name'];
        else    $sheet_name = $rowStudent['first_name'];
        if(strpos($template, '(New)') !== false){
            if($sheetCount > 0){
                //Clone sheet
                $objWorkSheetBase = $objPHPExcel->setActiveSheetIndex(0);
                $objWorkSheet = clone $objWorkSheetBase;
                $objWorkSheet->setTitle($sheet_name);
                $objPHPExcel->addSheet($objWorkSheet);
                $objPHPExcel->setActiveSheetIndexByName($sheet_name);
            }
            else  $objPHPExcel->setActiveSheetIndex($sheetCount)->setTitle($sheet_name);

            //Write date
            $module = '';
            //            if($rowStudent['modules'] == '')
            //            else $module = ' Module '. $rowStudent['modules'];

            $certificateNumber = $rowStudent['contact_id']. str_replace('/', '', date('d/m/y', strtotime($rowStudent['end_date'])));

            $objPHPExcel->setActiveSheetIndex($sheetCount)
            ->setCellValue('E21', $rowStudent['full_student_name'])
            ->setCellValue('F23', date('d/m/Y', strtotime($rowStudent['birthdate'])))
            ->setCellValue('F28', $rowStudent['kind_of_course']. ' Level '.$rowStudent['level']. $module )
            ->setCellValue('M39', "".$certificateNumber)
            ->setCellValue('M40', date('d/m/Y'));
            $sheetCount++;
        }else{
            if($sheetCount > 0){
                //Clone sheet
                $objWorkSheetBase = $objPHPExcel->setActiveSheetIndex(0);
                $objWorkSheet = clone $objWorkSheetBase;
                $objWorkSheet->setTitle($sheet_name);
                $objPHPExcel->addSheet($objWorkSheet);
                $objPHPExcel->setActiveSheetIndexByName($sheet_name);
            }
            else  $objPHPExcel->setActiveSheetIndex($sheetCount)->setTitle($sheet_name);

            //Write date
            $module = '';
            //            if($rowStudent['modules'] == '') ;
            //            else $module = ' Module '. $rowStudent['modules'];

            $certificateNumber = $rowStudent['contact_id']. str_replace('/', '', date('d/m/y', strtotime($rowStudent['end_date'])));

            $objPHPExcel->setActiveSheetIndex($sheetCount)
            ->setCellValue('C4', $rowStudent['full_student_name'])
            ->setCellValue('C6', "Has participated in the course ".$rowStudent['kind_of_course']. ' Level '.$rowStudent['level']. $module )
            ->setCellValue('G18', "Certificate No: ".$certificateNumber);
            $sheetCount++;
        }
    }

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $section = create_guid_section(6);
    $outputFileName = 'custom/uploads/Thankyou_Certificate_'.$section.'.xlsx';
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
    }
    exit;
}
//export Certificate Junior template

elseif(strpos($template, 'Certificate') !== false) {

    if(strpos($template, '(New)') !== false){
        $inputFileName = $forder_template_url.'/certificate/'.$team->code_prefix.'_new.xlsx';
        if (!file_exists($inputFileName)) $inputFileName = $forder_template_url.'/certificate/default_new.xlsx';
    }else{
        $inputFileName = $forder_template_url.'/certificate/'.$team->code_prefix.'.xlsx';
        if (!file_exists($inputFileName)) $inputFileName = $forder_template_url.'/certificate/default.xlsx';
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
    $sqlGetStudents = "SELECT DISTINCT
    l1.class_code,
    contacts.id,
    contacts.birthdate,
    contacts.contact_id,
    contacts.full_student_name,
    contacts.first_name,
    l1.kind_of_course,
    l1.level,
    l1.modules,
    l1.end_date ,
    gbdetail.final_result,
    gbdetail.certificate_type
    FROM contacts
    INNER JOIN j_class_contacts_1_c l1_1 ON contacts.id = l1_1.j_class_contacts_1contacts_idb AND l1_1.deleted = 0
    INNER JOIN j_class l1 ON l1.id = l1_1.j_class_contacts_1j_class_ida AND l1.deleted = 0
    INNER JOIN j_class_j_gradebook_1_c l2_1 ON l1.id = l2_1.j_class_j_gradebook_1j_class_ida AND l2_1.deleted = 0
    INNER JOIN j_gradebook l2 ON l2.id = l2_1.j_class_j_gradebook_1j_gradebook_idb AND l2.deleted = 0
    INNER JOIN j_gradebookdetail gbdetail ON gbdetail.student_id = contacts.id AND gbdetail.gradebook_id = l2.id AND gbdetail.deleted = 0
    WHERE l1.id='{$classID}'
    AND l1.deleted=0 AND l2.type = 'Overall'
    AND gbdetail.certificate_type != ''
    AND contacts.id IN ('".implode("','",$studentList)."')
    ";
    $rsGetStudents = $GLOBALS['db']->query($sqlGetStudents);
    $count = $GLOBALS['db']->getRowCount($rsGetStudents);

    $sheetCount = 0;
    while($rowStudent = $GLOBALS['db']->fetchByAssoc($rsGetStudents)) {
        if(strlen($rowStudent['full_student_name']) <= 31)
            $sheet_name =  $rowStudent['full_student_name'];
        else    $sheet_name =  $rowStudent['first_name'];
        if(strpos($template, '(New)') !== false){
            if($sheetCount > 0){
                //Clone sheet
                $objWorkSheetBase = $objPHPExcel->setActiveSheetIndex(0);
                $objWorkSheet = clone $objWorkSheetBase;
                $objWorkSheet->setTitle($sheet_name);
                $objPHPExcel->addSheet($objWorkSheet);
                $objPHPExcel->setActiveSheetIndexByName($sheet_name);
            }
            else  $objPHPExcel->setActiveSheetIndex($sheetCount)->setTitle($sheet_name);

            //Write date
            $module = '';
            //            if($rowStudent['modules'] =='') ;
            //            else $module = ' Module '. $rowStudent['modules'];

            if(!$rowStudent['birthdate']) $birthDay = '';
            else $birthDay = date('d/m/Y',strtotime($rowStudent['birthdate']));

            $certificateNumber = $rowStudent['contact_id']. str_replace('/', '', date('d/m/y', strtotime($rowStudent['end_date'])));

            $objPHPExcel->setActiveSheetIndex($sheetCount)
            ->setCellValue('D23', $rowStudent['full_student_name'])
            ->setCellValue('E26', $birthDay)
            ->setCellValue('G29', $rowStudent['certificate_type'])
            ->setCellValue('J29', $rowStudent['kind_of_course']. ' Level '.$rowStudent['level']. $module)
            ->setCellValue('N42', $certificateNumber)
            ->setCellValue('N43', date('d/m/Y'));
            $sheetCount++;
        }else{
            if($sheetCount > 0){
                //Clone sheet
                $objWorkSheetBase = $objPHPExcel->setActiveSheetIndex(0);
                $objWorkSheet = clone $objWorkSheetBase;
                $objWorkSheet->setTitle($sheet_name);
                $objPHPExcel->addSheet($objWorkSheet);
                $objPHPExcel->setActiveSheetIndexByName($sheet_name);
            }
            else  $objPHPExcel->setActiveSheetIndex($sheetCount)->setTitle($sheet_name);

            //Write date
            $module = '';
            //            if($rowStudent['modules'] =='') $module = '';
            //            else $module = ' Module '. $rowStudent['modules'];

            if(!$rowStudent['birthdate']) $birthDay = '';
            else $birthDay = date('d.m.Y',strtotime($rowStudent['birthdate']));

            $certificateNumber = $rowStudent['contact_id']. str_replace('/', '', date('d/m/y', strtotime($rowStudent['end_date'])));

            $objPHPExcel->setActiveSheetIndex($sheetCount)
            ->setCellValue('B4', $rowStudent['full_student_name'])
            ->setCellValue('B6', "D.O.B. ".$birthDay)
            ->setCellValue('B8', "Has achieved ".$rowStudent['certificate_type']." in ".$rowStudent['kind_of_course']. ' Level '.$rowStudent['level']. $module)
            ->setCellValue('G18', "Certificate No: ".$certificateNumber)
            ->setCellValue('G20', "Date of issue: ".date('Y.m.d'));
            $sheetCount++;
        }

    }

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $section = create_guid_section(6);
    $outputFileName = 'custom/uploads/Template_Certificate_'.$section.'.xlsx';
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
}
//export Certificate Adult template   

function get_level($lev){
    switch($lev){
        case "Inter" :
            return "Intermediate";
            break;
        case "Upper Inter":
            return "Upper-Intermediate";
            break;
        case "Pre Inter":
            return "Pre-Intermediate";
            break;
        case "Beginner":
            return "Beginner";
            break;
        case "Advance":
            return "Advance";
            break;
        case "Master":
            return "Master";
            break;
    }
}
?>