<?php
function exportErrorFile($csvFile, $contentArray){
    require_once("custom/include/PHPExcel/Classes/PHPExcel.php");
    require_once("custom/include/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php");  
    require_once("custom/include/PHPExcel/Classes/PHPExcel/IOFactory.php");

    $objPHPExcel = new PHPExcel();

    // Set properties
    $objPHPExcel->getProperties()->setCreator("OnlineCRM");
    $objPHPExcel->getProperties()->setLastModifiedBy("OnlineCRM");
    $objPHPExcel->getProperties()->setTitle("Import Targets Template");
    $objPHPExcel->getProperties()->setSubject("Import Targets Template");
    $objPHPExcel->getProperties()->setDescription("Import Targets Template");

    // Write file
    $objPHPExcel->setActiveSheetIndex(0);
    $row = 1;
    foreach($contentArray as $record){
        foreach($record as $index => $value){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, $row, $value);
        }  
        $row++;          
    }

    // Save Excel 2007 file
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $section = create_guid_section(6);
    $file = 'custom/uploads/import_error_file_'.$section.'.xlsx';

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
