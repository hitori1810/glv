<?php
$js = <<<EOQ
    <script>
    $(function(){
    $('#printPDFButton').hide();
    $('#exportReportButton').hide();
    });
    </script>
EOQ;
echo $js;
$sv = new SugarView();
$sv->displayFooter();
if(!isset($_POST['record']) || empty($_POST['record'])){
    die();
}


require_once 'custom/include/PHPExcel/Classes/PHPExcel.php';
include("custom/include/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php");
include("custom/include/PHPExcel/Classes/PHPExcel/IOFactory.php");

$objPHPExcel = new PHPExcel();


//Import Template
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load("custom/include/TemplateExcel/Bangkebanra.xlsx");

// Set properties
$objPHPExcel->getProperties()->setCreator("OnlineCRM");
$objPHPExcel->getProperties()->setLastModifiedBy("OnlineCRM");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX");

//get Now DB Date
global $timedate;

$filter = $this->where;
$filter = str_replace(' ','',$this->where);
$parts = explode("AND", $filter);

for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "j_paymentdetail.payment_date>=") !== FALSE) $beginDB = get_string_between($parts[$i]);
    if(strpos($parts[$i], "j_paymentdetail.payment_date<=") !== FALSE) $endDB     = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l1.id=") !== FALSE) $team_id = get_string_between($parts[$i]);
}
$begin = $timedate->to_display_date($beginDB,false);
$end = $timedate->to_display_date($endDB,false);

$sql = $this->query;

$i = 17;
$stt = 1;
$total_DT = 0;
$total_VAT = 0;
$flag1 = true;
$flag2 = true;
$flag3 = true;
$flag4 = true;

$styleAlignLeft = array(
    'alignment' => array(
        'wrap'       => true,
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),
);
$styleAlignCenter = array(
    'alignment' => array(
        'wrap'       => true,
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
);
$styleBorder = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        )
    )
);
$objPHPExcel->getActiveSheet()->SetCellValue('B7', "Kỳ tính thuế: $begin - $end");

//NONE VAT
$rs = $GLOBALS['db']->query($sql);
while($row = $GLOBALS['db']->fetchByAssoc($rs)){
    if($flag1){
        $objPHPExcel->getActiveSheet()->mergeCells('B'. $i .':H'. $i );
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, '1. Hàng hoá, dịch vụ không chịu thuế GTGT:');
        $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleAlignLeft);
        $flag1 = false;
        $totaldt1 = 0;
        $totalvat1 = 0;
        $i++;
    }

    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$i, $stt);
    $objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$i, $row['j_paymentdetail_serial_no']);
    $objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$i, $row['J_PAYMENTDETAIL_INVOIC3CE203']);
    $objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$i, $GLOBALS['timedate']->to_display_date($row['J_PAYMENTDETAIL_PAYMEN74FF09'],false));

    if($row['l4_full_name'] != '')
        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, $row['l4_full_name']);
    else
        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, $row['l3_full_name']);
    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, (string)$row['l2_tax_code']);
    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$i, $row['J_PAYMENTDETAIL_CONTEN4F89FC']);

    if($row['j_paymentdetail_status'] == 'Cancelled')
        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$i, 'Hóa đơn hủy '.$row['j_paymentdetail_description']);
    else
        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$i, $row['j_paymentdetail_description']);

    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$i, format_number($row['J_PAYMENTDETAIL_PAYMENF729B2'],0,0));
    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$i, format_number($row['J_PAYMENTDETAIL_PAYMENF729B2'] * 0 ,0,0));
    if($row['j_paymentdetail_status'] == 'Paid'){
        $total_DT += $row['J_PAYMENTDETAIL_PAYMENF729B2'];
        $total_VAT =$total_VAT + ($row['J_PAYMENTDETAIL_PAYMENF729B2'] * 0) ;
        $totaldt1 += $row['J_PAYMENTDETAIL_PAYMENF729B2'];
        $totalvat1 = $totalvat1 +  ($row['J_PAYMENTDETAIL_PAYMENF729B2'] * 0 );
    }

    $stt++;
    $i++;
}
if(!$flag1){
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, 'Tổng');
    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$i, format_number($totaldt1,0,0));
    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$i, format_number($totalvat1,0,0));
    $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':K'.$i)->getFont()->setBold(true);
    $i++;
}
//END: NONE VAT

$objPHPExcel->getActiveSheet()->getStyle('B17:K'. ($i-1))->applyFromArray($styleBorder);
$i = $i + 3;

$date = $timedate->nowDbDate();
$date = explode('-', $date);
$day = $date[2];
$month   = $date[1];
$year  = $date[0];

$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':F'.$i);
$objPHPExcel->getActiveSheet()->mergeCells('B'. ($i+1) .':F'. ($i+1) );

$objPHPExcel->getActiveSheet()->mergeCells('I'. ($i+3) .':K'. ($i+3) );
$objPHPExcel->getActiveSheet()->mergeCells('I'. ($i+4) .':K'. ($i+4) );
$objPHPExcel->getActiveSheet()->mergeCells('I'. ($i+5) .':K'. ($i+5) );
$objPHPExcel->getActiveSheet()->mergeCells('I'. ($i+6) .':K'. ($i+6) );

$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, 'Tổng doanh thu hàng hóa, dịch vụ bán ra: '.format_number($total_DT,0,0));
$objPHPExcel->getActiveSheet()->SetCellValue('B'.($i+1), 'Tổng thuế GTGT của hàng hóa, dịch vụ bán ra: '.format_number($total_VAT,0,0));
$objPHPExcel->getActiveSheet()->getStyle('B'.$i.':F'. ($i+1))->applyFromArray($styleAlignLeft);

$objPHPExcel->getActiveSheet()->SetCellValue('I'.($i+3), 'Ngày '.$day.' tháng '.$month.' năm '. $year);
$objPHPExcel->getActiveSheet()->SetCellValue('I'.($i+4), 'NGƯỜI NỘP THUẾ hoặc');
$objPHPExcel->getActiveSheet()->SetCellValue('I'.($i+5), 'ĐẠI DIỆN HỢP PHÁP CỦA NGƯỜI NỘP THUẾ');
$objPHPExcel->getActiveSheet()->SetCellValue('I'.($i+6), ' Ký tên, đóng dấu (ghi rõ họ tên và chức vụ)');
$objPHPExcel->getActiveSheet()->getStyle('I'.($i+3).':K'. ($i+6))->applyFromArray($styleAlignCenter);

// Save Excel 2007 file
$team = BeanFactory::getBean('Team',$team_id);
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('custom/uploads/'. 'BanKeHD('.$team->name.'_'.$timedate->to_db_date($begin) .'-'.$timedate->to_db_date($end).').xlsx');
header('Location: custom/uploads/'. 'BanKeHD('.$team->name.'_'.$timedate->to_db_date($begin).'-'.$timedate->to_db_date($end).').xlsx');
?>
