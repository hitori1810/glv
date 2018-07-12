<?php
    class TrainerCron{
        function create_pdf_tc(){
            //            require_once("custom/modules/C_Teachers/ScheduleItems.php");
            //            global $current_user;
            //            $team_id = $current_user->team_id;
            //            $sql = "SELECT id, teacher_id, room_id
            //            FROM meetings
            //            WHERE team_id = {$team_id} AND deleted = 0 
            //            GROUP BY teacher_id";
            //            $rs = $GLOBALS['db']->query($sql);
            //            while($row = $GLOBALS['db']->fetchByAssoc($rs)){
            //                $si = ScheduleItems::getPDF($row['teacher_id'],'M');
            require_once('custom/modules/C_Attendance/_helperdatetime.php');
            require_once("custom/include/tcpdf/tcpdf.php");
            require_once("custom/modules/C_Teachers/_helper.php");
            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('OnlineCRM');
            $pdf->SetTitle('PDF_Lichday');
            $pdf->SetSubject('PDF Lichday');
            $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

            // set default header data
            global $timedate;
            $teacher_id =  $_POST["record"];
            $sql = "SELECT CONCAT(first_name,' ', last_name) as name FROM c_teachers WHERE id = '{$teacher_id}'"; 
            $teacher_name = $GLOBALS['db']->getOne($sql);
            $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Teaching Schedule - Teacher '.$teacher_name,"OnlineCRM - OnlineCRM English\nWHERE THE BEST BECOME BETTER");
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
            // set header and footer fonts
            $pdf->setHeaderFont(Array('freesans', '', '10'));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // ---------------------------------------------------------
            // set font
            $pdf->SetFont('freesans',''); 

            // add a page
            $pdf->AddPage('L', 'A4');
            // column titles
            $header = array(translate('LBL_NO'),translate('LBL_DAY'), translate('LBL_DATE'), translate('LBL_START_TIME'), translate('LBL_END_TIME'), translate('LBL_CLASS'), translate('LBL_ROOM'), translate('LBL_TYPE'));
            // print colored table
            $pdf->SetFillColor(255, 0, 0);
            $pdf->SetTextColor(255);
            $pdf->SetDrawColor(128, 0, 0);
            $pdf->SetLineWidth(0.3);
            // Header
            $w = array(25, 30, 30, 30, 30, 50, 40, 33);
            $num_headers = count($header);
            for($i = 0; $i < $num_headers; ++$i) {
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
            }
            $pdf->Ln();
            // Color and font restoration
            $pdf->SetFillColor(224, 235, 255);
            $pdf->SetTextColor(0);
            // Data
            $fill = 0;
            $stt = 1;
            global $timedate;
            $now_obj=CalendarUtils::get_first_day_of_week($timedate->getNow());
            $startdate_obj = $now_obj->get("+7 day"); 
            $enddate_obj = $now_obj->get("+14 day");
            //$sql = "SELECT LAST_DAY( NOW( ) - INTERVAL 1 
            //                    MONTH ) + INTERVAL 1 
            //                    DAY AS first_day, LAST_DAY( NOW( ) ) AS last_day"; 
            //            $rs = $GLOBALS['db']->query($sql);
            //            while ($row = $GLOBALS['db']->fetchByAssoc($rs)){
            //                $first_day = $row['first_day'];
            //                $last_day = $row['last_day'];
            //            }
            $first_day = reset(array_keys(getmonth()));
            $last_day = end(array_keys(getmonth()));

            $sql = "SELECT name, room_id, id, type, date_start, date_end 
            FROM meetings
            WHERE teacher_id = '{$teacher_id}' AND deleted = 0 AND left(date_start,10) between '$first_day' and '$last_day'
            ORDER BY date_start";
            $rs = $GLOBALS['db']->query($sql);
            while($row = $GLOBALS['db']->fetchByAssoc($rs)){
                $pdf->Cell($w[0], 6, number_format($stt), 'LR', 0, 'C', $fill);
                //get day of week
                $day_of_week_temp =  $GLOBALS['timedate']->to_display_date($row['date_start']);
                $day_of_week = $GLOBALS['timedate']->to_db_date($day_of_week_temp, false);
                //end                
                $pdf->Cell($w[1], 6, date('l', strtotime($day_of_week)), 'LR', 0, 'L', $fill);
                $pdf->Cell($w[2], 6, $GLOBALS['timedate']->to_display_date($row['date_start']), 'LR', 0, 'C', $fill, '', 1);
                $pdf->Cell($w[3], 6, $GLOBALS['timedate']->to_display_time($row['date_start']), 'LR', 0, 'C', $fill);
                $pdf->Cell($w[4], 6, $GLOBALS['timedate']->to_display_time($row['date_end']), 'LR', 0, 'C', $fill);
                $pdf->Cell($w[5], 6, $row['name'], 'LR', 0, 'L', $fill,'',1);
                $sql1 = "Select name from c_rooms where id = '{$row['room_id']}'";
                $rs1 = $GLOBALS['db']->query($sql1);
                while($row1 = $GLOBALS['db']->fetchByAssoc($rs1)){
                    $pdf->Cell($w[6], 6, $row1['name'], 'LR', 0, 'L', $fill,'',1);
                }
                $pdf->Cell($w[7], 6, $row['type'], 'LR', 0, 'L', $fill,'',1);
                $pdf->Ln();
                $fill=!$fill;
                $stt++;
            }
            $pdf->Cell(array_sum($w), 0, '', 'T');
            // close and output PDF document
            //ob_end_clean();
            $fileNL = "custom/uploads/pdf/"."Teaching_Schedule_".$teacher_id.".pdf";
            $pdf->Output($fileNL, 'F'); 
        }

        function send_mail_tc(){
            require_once("include/SugarPHPMailer.php");
            require_once("include/workflow/alert_utils.php");
            require_once("custom/modules/C_Teachers/_helper.php");
            global $current_user;
            $team_id = $current_user->team_id;
            $admin = new Administration();
            $admin->retrieveSettings();
            $teacher_id =  $_POST["record"]; 
            $gv = BeanFactory::getBean('C_Teachers',$teacher_id); 
            $mail = new SugarPHPMailer;
            setup_mail_object($mail, $admin); 
            $mail->addAddress($gv->email1, vn_str_filter($gv->name));  // Add a recipient
            $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
            $mail->addAttachment('custom/uploads/pdf/'.'Teaching_Schedule_'.$teacher_id.'.pdf','Teaching_Schedule_'.vn_str_filter($gv->name).'.pdf');         // Add attachments
            $mail->isHTML(true); //  Set email format to HTML
            $mail->Subject = '[Atlantic English] Teaching Schedule for '.vn_str_filter($gv->name);
            $mail->Body    = 'Dear <b>'.vn_str_filter($gv->name).'!</b><br>AtlanticCRM send you a PDF file, this is teaching schedule for you on this month.<br> Thank you!';

            if(!$mail->Send()) 
            {
                $GLOBALS['log']->warn("Notifications: error sending e-mail (method: {$mail->Mailer}), (error: {$mail->ErrorInfo})");
            }

        }
    }
?>
