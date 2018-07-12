<?php
function parse_template_SMS($tpl_id = '', $module_dir = '', $focus_id = ''){
    global $timedate;
    //Check 1
    if(empty($tpl_id))
        return '';

    //Check 2
    $q1 = "SELECT body FROM email_templates WHERE id = '$tpl_id' AND deleted = 0";
    $body = $GLOBALS['db']->getOne($q1);

    global $current_user;

    //Replace Team Code
    $focus = BeanFactory::getBean($module_dir, $focus_id);
    $team_code = $GLOBALS['db']->getOne("SELECT short_name FROM teams WHERE id = '{$focus->team_id}'");

    $body = replaceSMS("\$team_name", $team_code, $body,"{P1}");
    $body = replaceSMS("\$team_code", $team_code, $body,"{P1}");

    //Replace name
    $body = str_replace("\$name", $focus->name, $body);
    $body = str_replace("\$class_name", $focus->name, $body);
    $body = str_replace("\$ten_lop", $focus->name, $body);

    //Replace Tran trong, Cam on, Lien he
    $body = str_replace("\$tran_trong", 'Tran trong', $body);
    $body = str_replace("\$cam_on", 'Xin cam on!', $body);
    $assigned_phone = $GLOBALS['db']->getOne("SELECT phone_work FROM users WHERE id = '{$current_user->id}'");
    $lien_he = $assigned_phone != ""? 'Lien he: '.$assigned_phone.'.' : "";
    $body = str_replace("\$lien_he", $lien_he, $body);
    $body = str_replace("\$chiet_khau", 'Ap dung chiet khau / Khuyen mai...', $body);

    //Replace today
    $dateSplit = explode(" ",$timedate->now());
    $body = str_replace("\$hom_nay", $dateSplit[0], $body);
    $body = str_replace("\$today", $dateSplit[0], $body);

    /**
    * Generate Template
    */
    //SINH NHẬT
    if($tpl_id == '6725a10d-55b1-f1a8-e952-56137d4f7e81'){
        $body = str_replace("\$student_name", $focus->last_name.' '.$focus->first_name, $body);
        $body = str_replace("\$full_name", $focus->last_name.' '.$focus->first_name, $body);
    }
    //TÌNH TRẠNG HỌC VIÊN
    if($tpl_id == '40395c9c-92a0-dbef-84ab-561383ba5da3'){
        $body = str_replace("\$student_name", $focus->last_name.' '.$focus->first_name, $body);
        $body = str_replace("\$full_name", $focus->last_name.' '.$focus->first_name, $body);

    }
    //BÀI HỌC
    if($tpl_id == 'e5c619a7-eda0-fce3-5529-56138364489a'){

    }
    //KHAI GIẢNG
    if($tpl_id == 'dca5c40c-d512-5c97-4374-56137ea8ab5c'){
        $sql = "SELECT
        meetings.date_start date_start
        FROM
        meetings
        INNER JOIN
        j_class l1 ON meetings.ju_class_id = l1.id
        AND l1.deleted = 0
        WHERE
        (((l1.id = '{$focus->id}')))
        AND meetings.deleted = 0
        ORDER BY meetings.date_start ASC
        LIMIT 1";
        $rs = $GLOBALS['db']->query($sql);
        if($row = $GLOBALS['db']->fetchByAssoc($rs)){
            $body = str_replace("\$start_date", $timedate->to_display_date($row['date_start'],true), $body);
            $body = str_replace("\$start_hour", $timedate->to_display_time($row['date_start'],true), $body);
        }

    }
    //LỊCH KIỂM TRA
    if($tpl_id == 'edb9c841-baf9-0adb-1ed4-56138351d1a1'){
        $sql = "SELECT meetings.lesson_number
        FROM meetings
        INNER JOIN j_class l1 ON meetings.ju_class_id = l1.id
        AND l1.deleted = 0
        WHERE l1.id = '{$focus->id}'AND meetings.deleted = 0
        AND CONVERT_TZ(meetings.date_end, '+00:00', '+7:00') <= NOW()
        ORDER BY meetings.lesson_number DESC
        LIMIT 1";
        $lastSessionNumber = $GLOBALS['db']->getOne($sql);
        if ($lastSessionNumber <= $focus->lesson_midterm_test){
            $body = str_replace("\$sms_exam_type", "giua khoa", $body);
            $testSessionNumber = $focus->lesson_midterm_test;
        }
        else{
            $body = str_replace("\$sms_exam_type", "cuoi khoa", $body);
            $testSessionNumber = $focus->lesson_final_test;
        }

        $sql = "SELECT CONVERT_TZ(meetings.date_start, '+00:00', '+7:00')
        FROM meetings
        INNER JOIN j_class l1 ON meetings.ju_class_id = l1.id AND l1.deleted = 0
        WHERE
        (l1.id = '{$focus->id}')
        AND meetings.deleted = 0
        AND meetings.lesson_number = ".$testSessionNumber."
        LIMIT 1";
        $testSessionDate = $GLOBALS['db']->getOne($sql);

        $dayOfWeek  = array("Monday" => "thu hai","Tuesday" => "thu ba","Wednesday" => "thu tu","Thursday" => "thu nam","Friday" => "thu sau","Saturday" => "thu bay","Sunday" => "Chu nhat");
        $week_date =  date('l',strtotime($testSessionDate));

        $body = replaceSMS("\$thu", $dayOfWeek[$week_date], $body,"{P4}");
        $body = replaceSMS("\$ngay_kiem_tra", $timedate->to_display_date($testSessionDate, false), $body,"{P5}");
    }
    //NGHỈ BUỔI HỌC - CÓ LỊCH HỌC BÙ
    if($tpl_id == 'b6438e9d-de4a-76d7-107d-561382a9bd72'){
        $body = replaceSMS("\$ngay_nghi",$timedate->to_display_date($focus->last_date_off, true),$body,"{P3}");
        $body = replaceSMS("\$gio_hoc",$timedate->to_display_date($focus->last_cover_date, true),$body,"{P4}");
        $body = replaceSMS("\$ngay_hoc",$timedate->to_display_date($focus->last_cover_date, true),$body,"{P5}");
    }
    //NGHỈ BUỔI HỌC - KHÔNG CÓ LỊCH BÙ
    if($tpl_id == '8081a4e2-f717-64a7-ec5b-561e1654286c'){

    }
    //LÙI LỊCH KHAI GIẢNG
    if($tpl_id == '60d202cd-a9ca-664e-5477-56137f139fc0'){
        $body = replaceSMS("\$ngay_doi_lich", $focus->start_date, $body,"{P3}");
        $body = replaceSMS("\$ly_do", $focus->change_reason, $body,"{P4}");
    }
    //LỚP KẾT THÚC
    if($tpl_id == '91205823-510d-78a7-6ab3-561380c9a55c'){
        $sql = "SELECT meetings.date_start
        FROM meetings INNER JOIN j_class l1 ON meetings.ju_class_id = l1.id
        AND l1.deleted = 0 WHERE l1.id = '{$focus->id}'
        AND meetings.deleted = 0
        ORDER BY meetings.lesson_number DESC LIMIT 1";
        $lastSessionDatetime = $GLOBALS['db']->getOne($sql);

        $body = replaceSMS("\$sms_last_session_date", $timedate->to_display_date($lastSessionDatetime,true), $body,"{P3}");

        $sqlGetNextClassStartDate = 'SELECT l1.name class_name, meetings.date_start date_start
        FROM meetings INNER JOIN j_class l1 ON meetings.ju_class_id = l1.id AND l1.deleted = 0
        WHERE l1.id = (
        SELECT j_class_j_class_1j_class_idb
        FROM j_class_j_class_1_c
        WHERE j_class_j_class_1j_class_ida =  "'.$focus->id.'"  AND deleted <> 1)
        AND meetings.deleted = 0
        ORDER BY meetings.lesson_number ASC LIMIT 1';
        $rs = $GLOBALS['db']->query($sqlGetNextClassStartDate);
        $row = $GLOBALS['db']->fetchByAssoc($rs);
        $body = replaceSMS("\$sms_next_class_name", $row['class_name'], $body,"{P4}");
        $body = replaceSMS("\$sms_next_class_start_date", $timedate->to_display_date($row['date_start'],true), $body,"{P5}");

    }
    // ĐỔI LỊCH HỌC
    if($tpl_id == '371ee5a3-6386-1f6d-5588-561381518e1a'){
        $body = replaceSMS("\$ngay_thay_doi", $focus->change_date_from, $body,"{P4}");
    }

    //Convert Vietnamese to English
    $body = viToEn($body);

    return $body;
}

// Generate bien sau khi da an nut gui
function last_parse_SMS($content = '', $module_dir = '', $focus_id = ''){
    $focus = BeanFactory::getBean($module_dir, $focus_id);
    if(!empty($focus->last_name) || !empty($focus->first_name)){
        $content = str_replace("\$ten_hoc_vien", $focus->last_name.' '.$focus->first_name, $content);
        $content = str_replace("\$student_name", $focus->last_name.' '.$focus->first_name, $content);
        $content = str_replace("\$full_name", $focus->last_name.' '.$focus->first_name, $content);
        $content = str_replace("\$full_student_name", $focus->last_name.' '.$focus->first_name, $content);
        $content = str_replace("\$first_name", $focus->first_name, $content);
        $content = str_replace("\$last_name", $focus->last_name, $content);
    }
    return $content;
}

function replaceSMS($oldStr,$newStr,$content,$defaultStr = ""){
    $newStr = $newStr != ""? $newStr : $defaultStr;
    return str_replace($oldStr, $newStr, $content);
}
