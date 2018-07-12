<?php
///////////////////////--------------------------------------------/////////////////
function getSubSituation(){
    $args = func_get_args();
    $id_class = $args[0]['class_id'];
    global $timedate;
    $today = $timedate->nowDbDate();
    //Đánh số thứ tự Situation
    $sql = "SELECT
    j_studentsituations.id,
    j_studentsituations.no_increasement,
    j_studentsituations.name,
    j_studentsituations.phone_situation,
    j_studentsituations.student_type,
    j_studentsituations.type,
    j_studentsituations.status,
    j_studentsituations.start_study,
    j_studentsituations.end_study,
    j_studentsituations.total_hour,
    j_studentsituations.total_amount,
    j_studentsituations.description,
    j_studentsituations.team_id,
    (case when length(j_studentsituations.student_id) < 36 then  j_studentsituations.lead_id  else j_studentsituations.student_id end)  student_id,
    j_studentsituations.parent_name,
    j_studentsituations.assigned_user_id,
    j_studentsituations.team_set_id,
    'j_class_studentsituations' panel_name
    FROM
    j_studentsituations
    INNER JOIN
    j_class ju_studentsituations_rel ON j_studentsituations.ju_class_id = ju_studentsituations_rel.id
    AND ju_studentsituations_rel.deleted = 0
    WHERE
    (j_studentsituations.ju_class_id = '$id_class')
    AND j_studentsituations.deleted = 0
    GROUP BY (CASE
    WHEN LENGTH(j_studentsituations.student_id) < 36 THEN j_studentsituations.lead_id
    ELSE j_studentsituations.student_id
    END) ,start_study desc, type DESC";
    $rs1 = $GLOBALS['db']->query($sql);

    $student_id = '###';
    $last_status = array();
    $num = 0;
    $array_not_count = array('Delayed','Stopped','Demo','Moving Out','OutStanding');
    while($r = $GLOBALS['db']->fetchByAssoc($rs1)){

        $u_num = '';
        if($student_id != $r['student_id']){
            $status = '###';
            $num++;
            if($r['no_increasement'] != $num)
                $u_num = $num;
        }else{
            if($r['no_increasement'] != 0)
                $u_num = 0;
        }


        if(in_array($r['type'],$array_not_count)){
            $status  = '';
        }else{

            if($today < $r['start_study'])
                $status = 'Not Started';
            elseif($r['start_study'] <= $today && $today <= $r['end_study'])
                $status = 'In Progress';
            elseif($r['end_study'] < $today){
                if(($status == 'In Progress' || $status == 'Not Started' || $status == 'Finished' || $status == '') )
                    $status ='';
                else $status ='Finished';
            }
        }
        if($u_num != $r['no_increasement'] && strlen($u_num) > 0)
            $GLOBALS['db']->query("UPDATE j_studentsituations SET no_increasement=$u_num WHERE id='{$r['id']}'");
        if($status != $r['status'])
            $GLOBALS['db']->query("UPDATE j_studentsituations SET status='$status' WHERE id='{$r['id']}'");
        $student_id = $r['student_id'];
    }
    return $sql;
}
?>
