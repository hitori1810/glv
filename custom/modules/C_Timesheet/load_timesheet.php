<?php
if(isset($_POST['date_add'])){
    //PHP 5.3
    //$date_add = DateTime::createFromFormat('Y-n-j',$_POST['date_add'])->format('Y-m-d');
    global $current_user;
    $cr_user_id = $current_user->id;
    $date_add = date('Y-m-d', strtotime($_POST['date_add']));

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

    global $timedate;
    $q1 = "SELECT
    IFNULL(c_timesheet.id, '') id,
    c_timesheet.description description,
    add_date,
    teacher_id,
    hours,
    minutes,
    task_name,
    CONCAT(IFNULL(users.last_name, ''),
    ' ',
    IFNULL(users.first_name, '')) user_full_name,
    c_timesheet.date_entered date_entered,
    c_timesheet.created_by created_by,
    IFNULL(l1.id, '') team_id,
    IFNULL(l1.name, '') team_name
    FROM
    c_timesheet
    INNER JOIN
    users ON c_timesheet.created_by = users.id
    AND users.deleted = 0
    INNER JOIN
    teams l1 ON c_timesheet.team_id = l1.id
    AND l1.deleted = 0
    WHERE
    add_date = '$date_add'
    $ext_team
    AND c_timesheet.deleted = 0
    ORDER BY date_entered";
    $rs1 = $GLOBALS['db']->query($q1);
    $html = '';
    $count = 0;
    if(ACLController::checkAccess('C_Timesheet', 'edit', true) || ($current_user->isAdmin()))
        $addPermission = true;
    else $addPermission = false;
    while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
        $q2 = "SELECT id, last_name, first_name, salutation FROM c_teachers WHERE id = '{$row['teacher_id']}'";
        $rs2 = $GLOBALS['db']->query($q2);
        $row2 = $GLOBALS['db']->fetchByAssoc($rs2);

        $html .= '<tr>';
        $html .= '<td valign="bottom" align="left">';
        $html .= '<input type="hidden" id="teacher_id" name="teacher_id[]" value="'.$row['teacher_id'].'"/>';
        $html .= '<a target="_blank" style="text-decoration: none; vertical-align: -webkit-baseline-middle; font-weight: bold;" href="index.php?module=C_Teachers&action=DetailView&record='.$row['teacher_id'].'"> '.$row2['salutation'].' '.$row2['last_name'].' '.$row2['first_name'].' </a>';
        $html .= '</td>';

        $html .= '<td>';
        $html .= '<input type="text" id="task_text" name="task_text[]" value="'.$GLOBALS['app_list_strings']['timesheet_tasklist_list'][$row['task_name']].'" readonly="true" style="width:150px;" class="disable" />';
        $html .= '<input type="hidden" id="task_name" name="task_name[]" value="'.$row['task_name'].'"/>';
        $html .= '</td>';

        $html .= '<td>';
        $html .= '<input type="text" id="hours" name="hours[]" size="2" maxlength="2" value="'.$row['hours'].'" class="hours"/>';
        $html .= '</td>';

        $html .= '<td>';
        $html .= '<input type="text" id="minutes" name="minutes[]" size="2" maxlength="2" value="'.$row['minutes'].'" class="minutes" /> ';
        $html .= '</td>';

        $html .= '<td>';
        $html .= '<input type="text" id="description" name="description[]"  size="20" value="'.$row['description'].'"/>';
        $html .= '</td>';

        $html .= '<td><a target="_blank" style="text-decoration: none; vertical-align: -webkit-baseline-middle; font-weight: bold;" href="index.php?module=Employees&action=DetailView&record='.$row['created_by'].'">'.$row['user_full_name'].'</a><input type="hidden" name="created_by[]" value="'.$row['created_by'].'"/></td>';

        $html .= '<td align="center"><label style="vertical-align: -webkit-baseline-middle; font-weight: bold;">'.$row['team_name'].'</a><input type="hidden" name="team_id[]" value="'.$row['team_id'].'"/></td>';
        //.$timedate->to_display_time($row['date_entered']).' - '
        $html .= '<td>';
        //       if($cr_user_id == $row['created_by'] || $current_user->isAdminForModule('Users'))
        //if($cr_user_id == $row['created_by'] )
        if($addPermission)
            $html .= '<input type="button" class="btnDelRow" value="Delete" id="btnDel"/>';
        $html .= '</td>';

        $html .= '</tr>';
        $count++;
    }
    echo json_encode(array(
        "success" => "1",
        "count" => $count,
        "html" => $html,
    ));
}else{
    echo json_encode(array(
        "success" => "0",
    ));
}
?>
