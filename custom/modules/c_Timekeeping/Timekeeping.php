<?php
    function calTimekeeping($start , $end , $team_id = null){
        
        $cr_user_id = $GLOBALS['current_user']->id;     
        $qu2 = "DELETE FROM c_timekeeping WHERE assigned_user_id = '$cr_user_id'";
        $GLOBALS['db']->query($qu2);

        global $timedate;
        $start  = substr($start,0,10);
        $end    = substr($end,0,10);

        $start_ss = date('Y-m-d H:i:s',strtotime("-7 hours ".$start." 00:00:00"));
        $end_ss   = date('Y-m-d H:i:s',strtotime("-7 hours ".$end." 23:59:59"));

        $q1 = "SELECT id, team_id, team_set_id FROM c_teachers WHERE deleted = 0";
        $rs = $GLOBALS['db']->query($q1);
        while($row = $GLOBALS['db']->fetchByAssoc($rs)){
            $teamSetBean = new TeamSet();
            $teams = $teamSetBean->getTeams($row['team_set_id']);
            if(array_key_exists($team_id,$teams)){
                //Lay gio timesheet
                $q2 = "SELECT DISTINCT
                IFNULL(SUM(c_timesheet.duration), 0) c_timesheet_duration
                FROM
                c_timesheet
                INNER JOIN
                c_teachers l1 ON c_timesheet.teacher_id = l1.id
                AND l1.deleted = 0
                INNER JOIN
                teams l3 ON c_timesheet.team_id = l3.id
                AND l3.deleted = 0
                WHERE
                ((((c_timesheet.add_date >= '$start'
                AND c_timesheet.add_date <= '$end'))
                AND (l1.id = '{$row['id']}')))
                AND c_timesheet.deleted = 0
                AND l3.id = '$team_id'";
                $timesheet = $GLOBALS['db']->getOne($q2);

                //Lay gio Practice
                $q3 = "SELECT DISTINCT
                IFNULL(SUM(meetings.teaching_hour), 0) meetings_duration_cal
                FROM
                meetings
                INNER JOIN
                c_teachers l1 ON meetings.teacher_id = l1.id
                AND l1.deleted = 0
                INNER JOIN
                c_classes l2 ON meetings.class_id = l2.id
                AND l2.deleted = 0
                INNER JOIN
                teams l3 ON meetings.team_id = l3.id
                AND l3.deleted = 0
                WHERE
                ((((meetings.date_start >= '$start_ss'
                AND meetings.date_start <= '$end_ss'))
                AND (l1.id = '{$row['id']}')
                AND (l2.type = 'Practice')))
                AND meetings.deleted = 0
                AND l3.id = '$team_id'";
                $practice = $GLOBALS['db']->getOne($q3);

                //Lay gio Connect Club
                $q4 = "SELECT DISTINCT
                IFNULL(SUM(meetings.teaching_hour), 0) meetings_duration_cal
                FROM
                meetings
                INNER JOIN
                c_teachers l1 ON meetings.teacher_id = l1.id
                AND l1.deleted = 0
                INNER JOIN
                c_classes l2 ON meetings.class_id = l2.id
                AND l2.deleted = 0
                INNER JOIN
                teams l3 ON meetings.team_id = l3.id
                AND l3.deleted = 0
                WHERE
                ((((meetings.date_start >= '$start_ss'
                AND meetings.date_start <= '$end_ss'))
                AND (l1.id = '{$row['id']}')
                AND (l2.type = 'Connect Club')))
                AND meetings.deleted = 0
                AND l3.id = '$team_id'";
                $connect = $GLOBALS['db']->getOne($q4);

                //Lay gio Skill
                $q5 = "SELECT DISTINCT
                IFNULL(SUM(meetings.teaching_hour), 0) meetings_duration_cal
                FROM
                meetings
                INNER JOIN
                c_teachers l1 ON meetings.teacher_id = l1.id
                AND l1.deleted = 0
                INNER JOIN
                c_classes l2 ON meetings.class_id = l2.id
                AND l2.deleted = 0
                INNER JOIN
                teams l3 ON meetings.team_id = l3.id
                AND l3.deleted = 0
                WHERE
                ((((meetings.date_start >= '$start_ss'
                AND meetings.date_start <= '$end_ss'))
                AND (l1.id = '{$row['id']}')
                AND (l2.type = 'Skill')))
                AND meetings.deleted = 0
                AND l3.id = '$team_id'";
                $skill = $GLOBALS['db']->getOne($q5);

                //Insert
                $keeping = new c_Timekeeping();

                $keeping->name = 'Teaching hour ';

                $keeping->teacher_id = $row['id'];
                $keeping->date_input = $start;
                $keeping->value_input = format_number($timesheet,2,2);
                $keeping->value_input_2 = format_number($practice,2,2);
                $keeping->value_input_3 = format_number($connect,2,2);
                $keeping->value_input_4 = format_number($skill,2,2);
                $keeping->value_input_5 = format_number($practice + $skill + $connect,2,2);
                $keeping->value_input_6 = format_number($timesheet,2,2);
                $keeping->value_input_7 = format_number($practice + $skill + $timesheet + $connect,2,2);
                $keeping->team_id = $team_id;
                $keeping->team_set_id = $team_id;
                $keeping->assigned_user_id = $cr_user_id;
                if($keeping->value_input_5 > 0 || $keeping->value_input_6 > 0)
                    $keeping->save();   
            }
        }
    }        
?>
