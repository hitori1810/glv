<?php
if(isset($_POST['date_add'])){

    global $current_user;
    $date_add = date('Y-m-d', strtotime($_POST['date_add']));   
    //Delete all timesheet at date add
    $ext_team = "AND c_timesheet.team_set_id IN
    (SELECT 
    tst.team_set_id
    FROM
    team_sets_teams tst
    INNER JOIN
    team_memberships team_memberships ON tst.team_id = team_memberships.team_id
    AND team_memberships.user_id = '{$current_user->id}'
    AND team_memberships.deleted = 0)";
    if($current_user->isAdmin())
        $ext_team = '';

    $q1     = "SELECT id FROM c_timesheet WHERE add_date='$date_add' $ext_team AND deleted = 0";
    $rs1    = $GLOBALS['db']->query($q1);
    while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
        if(!empty($row['id'])){
            $q4     = "DELETE FROM c_timesheet WHERE id='{$row['id']}'";
            $GLOBALS['db']->query($q4);
            $q3     = "DELETE FROM meetings WHERE timesheet_id='{$row['id']}'";
            $GLOBALS['db']->query($q3);  
        }else{
            $GLOBALS['log']->security("Serious error: DELETE SESSIONS - User ID: {$GLOBALS['current_user']->id} - Date: {$GLOBALS['timedate']->nowDate()}"); 
            echo json_encode(array(
                "success" => "0",
            )); 
        }
    }


    //Insert new Timesheet
    $count = 0;
    for($i = 0; $i < count($_POST['teacher_id']) ; $i++){
        $count++;

        $q2 = "SELECT id, last_name, first_name, team_id, salutation FROM c_teachers WHERE id = '{$_POST['teacher_id'][$i]}'";
        $rs2 = $GLOBALS['db']->query($q2);
        $row = $GLOBALS['db']->fetchByAssoc($rs2);

        $ts = new C_Timesheet();
        $ts->name           = $row['salutation'].' '.$row['last_name'].' '.$row['first_name'].' - '.$_POST['task_name'][$i];
        $ts->description    = $_POST['description'][$i];
        $ts->team_id        = $_POST['team_id'][$i];
        $ts->team_set_id    = $ts->team_id;
        $ts->hours          = $_POST['hours'][$i];
        $ts->minutes        = $_POST['minutes'][$i];
        $ts->teacher_id     = $_POST['teacher_id'][$i];
        $ts->duration       = $_POST['hours'][$i] + ($_POST['minutes'][$i]/60);
        $ts->task_name      = $_POST['task_name'][$i];
        $ts->add_date       = $date_add;
        $ts->assigned_user_id = $_POST['created_by'][$i];
        $ts->save();
        $name = $ts->task_name.': '.$ts->description;
        //        if($ts->task_name == 'Placement Test' || $ts->task_name == 'Demo'){
        //            
        //        }


        $ss = new Meeting(); //Create Record
        $ss->name           = $name;
        $ss->date_start     = date('Y-m-d', strtotime($_POST['date_add'])).' 10:00:00';
        $ss->type           = 'Sugar';
        $ss->duration_hours     = $_POST['hours'][$i];
        $ss->duration_minutes   = $_POST['minutes'][$i];

        $ss->type_of_class  = 'Junior'; 
        $ss->meeting_type   = $ts->task_name; 
        $ss->timesheet_id   = $ts->id; 
        $ss->teacher_id     = $ts->teacher_id; 
        $ss->teaching_hour  = $ts->duration;
        $ss->delivery_hour  = $ss->teaching_hour;
        $ss->update_vcal    = false;

        $ss->team_id        = $ts->team_id;
        $ss->team_set_id    = $ts->team_id;
        $ss->assigned_user_id = $ts->assigned_user_id;
        $ss->save();
    }
    echo json_encode(array(
        "success" => "1",
        "count" => $count,
    )); 
}else{
    echo json_encode(array(
        "success" => "0",
    ));
}