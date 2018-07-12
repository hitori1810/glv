<?php

    global $timedate;
    $q1   = "SELECT DISTINCT IFNULL(l1.id,'') l1_id ,CONCAT(IFNULL(l1.last_name,''),' ',IFNULL(l1.first_name,'')) l1_full_name, IFNULL(l1.picture,'') l1_picture,IFNULL(l1.team_id,'') l1_team_id, IFNULL(l1.team_set_id,'') l1_team_set_id  FROM c_memberships INNER JOIN c_memberships_contacts_2_c l1_1 ON c_memberships.id=l1_1.c_memberships_contacts_2c_memberships_ida AND l1_1.deleted=0 INNER JOIN contacts l1 ON l1.id=l1_1.c_memberships_contacts_2contacts_idb AND l1.deleted=0 WHERE (((c_memberships.name='{$_POST['code']}' ))) AND c_memberships.deleted=0";
    $rs1  = $GLOBALS['db']->query($q1);
    $row1 = $GLOBALS['db']->fetchByAssoc($rs1);
    $html = '<table width="100%" height = "100%" border="0" style ="border-left:2px solid white" id = "tbl_result" >';

    if(!isset($row1['l1_id']) || empty($row1['l1_id'])){
        $html .= '<tr><td colspan="2" rowspan="1" id="image" nowrap><img name="no_image" id="no_image" src="custom/themes/default/images/logo.jpg"/><br><br></td></tr>';
        $html .= '<tr><td align="center" nowrap><div> NOT FOUND !</div></td></tr></table>';

        echo json_encode(array(
            "success" => "0",
            "html" => $html,
        ));
    }else{    
        $html   .= '<tr><td colspan="2" id="image" nowrap><img src="index.php?entryPoint=download&id='.$row1['l1_picture'].'&type=SugarFieldImage&isTempFile=1" style="border: 0; width: 220px; height: auto;"><br><br></td></tr>';
        $html   .= '<tr><td align="right" nowrap><div>Membership Code: &nbsp;&nbsp;</div></td><td align="left" nowrap><div id = "code_name">'.$_POST['code'].'</div></td></tr>';
        $html   .= '<tr><td align="right" nowrap><div>Have a nice day: &nbsp;&nbsp;</div></td><td align="left" nowrap><div id = "student_name">'.$row1['l1_full_name'].'</div></td></tr>';

        //Lấy trong 30 phut gần đầy
        $today_time = $timedate->nowDb();
//        $dates      = $timedate->fromDb($timedate->nowDb());
//        $date1      = $timedate->asDb($dates->modify("-90 minute"));
//        $date2      = $timedate->asDb($dates->modify("+180 minute"));

        $q2     = "SELECT DISTINCT
        IFNULL(l1.id, '') l1_id,
        l1.date_start l1_date_start,
        l1.date_end l1_date_end,
        IFNULL(l2.id, '') l2_id,
        CONCAT(IFNULL(l2.last_name, ''),
        ' ',
        IFNULL(l2.first_name, '')) l2_full_name,
        IFNULL(l3.id, '') l3_id,
        IFNULL(l3.name, '') l3_name,
        IFNULL(l1.name, '') l1_name,
        TIMEDIFF('$today_time', l1.date_start) TIME_DIFF,
        TIMESTAMPDIFF(SECOND,'$today_time', l1.date_start) STAMP
        FROM
        contacts
        INNER JOIN
        meetings_contacts l1_1 ON contacts.id = l1_1.contact_id
        AND l1_1.deleted = 0
        INNER JOIN
        meetings l1 ON l1.id = l1_1.meeting_id
        AND l1.deleted = 0
        INNER JOIN
        c_teachers l2 ON l1.teacher_id = l2.id AND l2.deleted = 0
        INNER JOIN
        c_rooms l3 ON l1.room_id = l3.id AND l3.deleted = 0
        WHERE
        (((l1.meeting_type = 'Session')
        AND (contacts.id = '{$row1['l1_id']}')))
        AND contacts.deleted = 0
        ORDER BY ABS(STAMP)
        LIMIT 1";
        $rs2    = $GLOBALS['db']->query($q2);
        $row2 = $GLOBALS['db']->fetchByAssoc($rs2);

        if(!isset($row2['l1_id']) || empty($row2['l1_id'])){
            echo json_encode(array(
                "success" => "1",
                "html" => $html,
            ));
        }else{
            //put in Attendance
            $now = strtotime($timedate->nowDb());
            $from_time = strtotime($row2['l1_date_start']);
            $to_time = strtotime($row2['l1_date_end']);
            if($now <= $from_time){
                $type = 'P';   
            }elseif($now > $from_time && $now < $to_time){
                if(round(abs($now - $from_time) / 60,2) > 15){
                    $type = 'L';   
                }else{
                    $type = 'P';  
                }
            }else{
                $type = 'A';   
            }
            $q3 = "SELECT id FROM c_attendance WHERE student_id ='{$row1['l1_id']}' AND meeting_id = '{$row2['l1_id']}' AND deleted = 0";
            $att_id = $GLOBALS['db']->getOne($q3);
            if(!$att_id){
                $sql = "INSERT INTO c_attendance VALUES ('".create_guid()."', null, '{$timedate->nowDb()}','{$timedate->nowDb()}','1','1',null,0,'{$row1['l1_team_id']}','{$row1['l1_team_set_id']}',null,'".substr($row2['l1_date_start'], 0, 10)."','$type','{$row1['l1_id']}','{$row2['l1_id']}',null)";
                $GLOBALS['db']->query($sql);  
            }
            date_default_timezone_set("Asia/Bangkok");
            $start  =  date('h:i A', strtotime('+7 hours', strtotime($row2['l1_date_start'])));
            $end    =  date('h:i A', strtotime('+7 hours', strtotime($row2['l1_date_end'])));

            $html   .= '<tr><td align="right" nowrap><div>Today you will learn in class: &nbsp;&nbsp;</div></td><td align="left" nowrap><div id = "class_name">'.$row2['l1_name'].'</div></td></tr>'; 
            $html   .= '<tr><td align="right" nowrap><div>With teacher: &nbsp;&nbsp;</div></td><td align="left" nowrap><div id = "teacher_name">'.$row2['l2_full_name'].'</div></td></tr>'; 
            $html   .= '<tr><td align="right" nowrap><div>At room: &nbsp;&nbsp;</div></td><td align="left" nowrap><div id = "room">'.$row2['l3_name'].'</div></td></tr>'; 
            $html   .= '<tr><td align="right" nowrap><div>Time Schedule: &nbsp;&nbsp;</div></td><td align="left" nowrap><div id = "time_schedule">'.$start.' - '.$end.'</div></td></tr>'; 
            $html   .= '</table>';
            echo json_encode(array(
                "success" => "1",
                "html" => $html,
            ));
        }

    }


?>
