<?php
$classID    = '9224420b-f499-6476-f47d-59ceee29f9e7';
$settleDate = '2017-10-01';

require_once("custom/include/_helper/junior_class_utils.php");
require_once("custom/include/_helper/junior_revenue_utils.php");

global $timedate;
$sql="SELECT DISTINCT
IFNULL(j_studentsituations.id, '') primaryid,
IFNULL(j_studentsituations.name, '') name,
IFNULL(j_studentsituations.type, '') type,
IFNULL(j_studentsituations.status, '') status,
IFNULL(j_studentsituations.description, '') description,
IFNULL(j_studentsituations.student_id, '') student_id,
IFNULL(j_studentsituations.ju_class_id, '') class_id,
IFNULL(j_studentsituations.payment_id, '') payment_id,
IFNULL(j_studentsituations.assigned_user_id, '') assigned_user_id,
IFNULL(j_studentsituations.modified_user_id, '') modified_user_id,
IFNULL(j_studentsituations.created_by, '') created_by,
IFNULL(j_studentsituations.team_id, '') team_id,
IFNULL(l1.class_type, '') class_type,
j_studentsituations.date_modified date_modified,
j_studentsituations.date_modified date_entered,
j_studentsituations.start_hour start_hour,
j_studentsituations.total_amount amount,
j_studentsituations.total_hour hour,
j_studentsituations.start_study start_study,
j_studentsituations.end_study end_study
FROM
j_studentsituations
INNER JOIN
j_class l1 ON j_studentsituations.ju_class_id = l1.id
AND l1.deleted = 0
AND j_studentsituations.ju_class_id = '$classID'
AND j_studentsituations.type IN ('Moving In' , 'Enrolled')
AND j_studentsituations.start_study < '$settleDate'
AND l1.status = 'Planning'
WHERE
j_studentsituations.deleted = 0";
$count = 0;
$result = $GLOBALS['db']->query($sql);
while($row = $GLOBALS['db']->fetchByAssoc($result)){
    $sss = $GLOBALS['db']->fetchArray("SELECT DISTINCT
        IFNULL(j_studentsituations.id, '') situation_id,
        IFNULL(l1.id, '') l1_id,
        l1.till_hour till_hour,
        l1.delivery_hour delivery_hour,
        l1.date_start date_start,
        l1.date_end date_end
        FROM
        j_studentsituations
        INNER JOIN
        meetings_contacts l1_1 ON j_studentsituations.id = l1_1.situation_id
        AND l1_1.deleted = 0
        INNER JOIN
        meetings l1 ON l1.id = l1_1.meeting_id
        AND l1.deleted = 0
        WHERE
        (((j_studentsituations.id = '{$row['primaryid']}')
        AND (l1.session_status <> 'Cancelled')))
        AND j_studentsituations.deleted = 0
        ORDER BY date_start ASC");
    $settle = array(
        'hour'  => 0,
        'count'  => 0,
        'start' => '',
        'end'   => '',
        'start_hour'   => '',
        'sss'   => array(),
    );
    for($i = 0; $i < count($sss); $i++){
        $sss_start = date('Y-m-d', strtotime($sss[$i]['date_start']. ' +7hour'));
        if($sss_start < $settleDate){
            $settle['hour'] += $sss[$i]['delivery_hour'];
            $settle['count'] += 1;
            if($settle['count'] == 1){
                $settle['start'] =  $timedate->to_display_date($sss_start);
                $settle['start_hour'] =  $sss[$i]['till_hour'] - $sss[$i]['delivery_hour'];
            }

            $settle['end']   = $timedate->to_display_date($sss_start);
            $settle['sss'][] = $sss[$i]['l1_id'];
        }
    }

    $enroll = array(
        'hour'  => 0,
        'count'  => 0,
        'start' => '',
        'end'   => '',
        'start_hour'   => '',
        'sss'   => array(),
    );
    for($i = 0; $i < count($sss); $i++){
        $sss_start = date('Y-m-d', strtotime($sss[$i]['date_start']. ' +7hour'));
        if($sss_start >= $settleDate){
            $enroll['hour'] += $sss[$i]['delivery_hour'];
            $enroll['count'] += 1;
            if($enroll['count'] == 1){
                $enroll['start']      =  $timedate->to_display_date($sss_start);
                $enroll['start_hour'] =  $sss[$i]['till_hour'] - $sss[$i]['delivery_hour'];
            }

            $enroll['end']   = $timedate->to_display_date($sss_start);
            $enroll['sss'][] = $sss[$i]['l1_id'];
        }
    }
    $unit_price = $row['amount'] / $row['hour'];
    //Create situation
    if(!empty($settle['hour'])){
        $stu_si                   = new J_StudentSituations();
        $stu_si->name             = $row['name'];
        $stu_si->student_type     = 'Student';
        $stu_si->type             = 'Settle';
        $stu_si->total_hour       = format_number($settle['hour'],2,2);
        $stu_si->total_amount     = format_number($settle['hour'] * $unit_price);

        //caculate start_hour
        $stu_si->start_hour       = $settle['start_hour'];
        $stu_si->start_study      = $settle['start'];
        $stu_si->end_study        = $settle['end'];
        $stu_si->student_id       = $row['student_id'];
        $stu_si->ju_class_id      = $row['class_id'];
        $stu_si->payment_id       = $row['payment_id'];
        if(empty($enroll['hour']))
            $stu_si->description       = $row['description'];

        $stu_si->assigned_user_id = $row['assigned_user_id'];
        $stu_si->team_id          = $row['team_id'];
        $stu_si->team_set_id      = $row['team_id'];

        $stu_si->date_entered     = $row['date_entered'];
        $stu_si->date_modified    = $row['date_modified'];
        $stu_si->created_by       = $row['created_by'];
        $stu_si->modified_user_id = $row['modified_user_id'];

        $stu_si->save();
        for($i = 0; $i < count($settle['sss']); $i++)
            addJunToSession($stu_si->id , $settle['sss'][$i] );
    }


    if(!empty($enroll['hour'])){
        $stu_sie                   = new J_StudentSituations();
        $stu_sie->name             = $row['name'];
        $stu_sie->student_type     = 'Student';
        $stu_sie->type             = 'Enrolled';
        $stu_sie->total_hour       = format_number($enroll['hour'],2,2);
        $stu_sie->total_amount     = format_number($enroll['hour'] * $unit_price);

        //caculate start_hour
        $stu_sie->start_hour       = $enroll['start_hour'];
        $stu_sie->start_study      = $enroll['start'];
        $stu_sie->end_study        = $enroll['end'];
        $stu_sie->student_id       = $row['student_id'];
        $stu_sie->ju_class_id      = $row['class_id'];
        $stu_sie->payment_id       = $row['payment_id'];
        $stu_sie->description      = $row['description'];

        $stu_sie->assigned_user_id = $row['assigned_user_id'];
        $stu_sie->team_id          = $row['team_id'];
        $stu_sie->team_set_id      = $row['team_id'];

        $stu_sie->date_entered     = $row['date_entered'];
        $stu_sie->date_modified    = $row['date_modified'];
        $stu_sie->created_by       = $row['created_by'];
        $stu_sie->modified_user_id = $row['modified_user_id'];

        $stu_sie->save();
        for($i = 0; $i < count($enroll['sss']); $i++)
            addJunToSession($stu_sie->id , $enroll['sss'][$i] );
    }

    //Xóa quan hệ cũ
    $GLOBALS['db']->query("UPDATE j_studentsituations SET deleted='1' WHERE id='{$row['primaryid']}'");
    removeJunFromSession($row['primaryid']);
    //Update Payment Date
    $GLOBALS['db']->query("UPDATE j_payment SET settle_date='$settleDate', is_outstanding=1, note='Chuyển doanh vào Settle: $settleDate' WHERE id='{$row['payment_id']}'");

    $count++;
}
$GLOBALS['db']->query("UPDATE j_class SET status='In Progress' WHERE id='$classID'");

echo $count.' Situation updated ==> So Done !!';
