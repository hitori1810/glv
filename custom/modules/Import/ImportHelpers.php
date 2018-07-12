<?php
function exportErrorFile($csvFile, $contentArray, $importModule){
    require_once("custom/include/PHPExcel/Classes/PHPExcel.php");
    require_once("custom/include/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php");  
    require_once("custom/include/PHPExcel/Classes/PHPExcel/IOFactory.php");

    $objPHPExcel = new PHPExcel();
    $templateUrl = "custom/uploads/TemplateImport/Template_Import_Lead.xlsx";

    //Customize template file for special case
    if($importModule == "Leads") $templateUrl = "custom/uploads/TemplateImport/Template_Import_Lead_".$GLOBALS['current_language'].".xlsx";

    //Load Template file
    if(!empty($templateUrl) && $csvFile != "error_1.csv"){
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');   
        $objPHPExcel = $objReader->load($templateUrl);
    }

    // Set properties
    $objPHPExcel->getProperties()->setCreator("OnlineCRM");
    $objPHPExcel->getProperties()->setLastModifiedBy("OnlineCRM");
    $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
    $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
    $objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX");

    // Write file
    $objPHPExcel->setActiveSheetIndex(0);
    $row = 2;
    foreach($contentArray as $record){
        foreach($record as $index => $value){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, $row, $value);
        }  
        $row++;          
    }

    // Save Excel 2007 file
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $section = create_guid_section(6);
    $file = 'custom/upload/import_error_file_'.$section.'.xlsx';

    $objWriter->save($file);

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

}
?>
