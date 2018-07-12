<?php
echo  showSendSMSScreen();

// Add by Nguyen Tung
if(isset($_POST['type']) && $_POST['type'] == 'getSessionOptionAjax' && $_POST['class_id'] != '') {
    echo getSessionOptions($_POST['class_id']);
} else echo "<option value=''>''</option>";

function showSendSMSScreen(){
    global $current_user,$timedate;
    $ss = new Sugar_Smarty();
    $studentList = "";
    $q1 = "SELECT id,name FROM email_templates WHERE type='sms' AND base_module='J_Class' AND deleted = 0";
    $rs1 = $GLOBALS['db']->query($q1);
    $email_templates_arr = array('' => '');
    while($row = $GLOBALS['db']->fetchByAssoc($rs1) ) {
        $email_templates_arr[$row['id']] = $row['name'];
    }       

    $ss->assign('MOD', return_module_language($GLOBALS['current_language'], 'J_Class'));
    $ss->assign("CURRENT_USER_ID", $current_user->id);
    $ss->assign("TODAY", reset(explode(" ",$timedate->now())));

    $ss->assign("TEMPLATE_OPTIONS", get_select_options_with_id($email_templates_arr, ""));
    $ss->assign("STUDENT_LIST", $studentList);
    $ss->assign("SESSION_ID", "");

    if (!empty($_GET["class_id"])){
        $class = BeanFactory::getBean("J_Class", $_GET["class_id"]);
        $ss->assign("CLASS_ID", $class->id);
        $ss->assign("CLASS_NAME", $class->name);
        $ss->assign("SESSION_LIST", getSessionOptions($_GET["class_id"]));
    }
    return $ss->fetch('custom/modules/J_Class/tpls/send_sms_screen.tpl');
}

function getSessionOptions($classId){
    global $db, $timedate;    

    //get default session
    $sql = "
    SELECT
    mt.id session_id
    FROM  j_class c
    INNER JOIN meetings mt ON c.id = mt.ju_class_id AND mt.deleted = 0 
    AND mt.meeting_type = 'Session'  AND mt.session_status <> 'Canceled'

    WHERE c.id = '{$classId}'
    AND c.class_type = 'Normal Class'
    AND c.deleted = 0 
    AND DATE(DATE_ADD(mt.date_start, INTERVAL 7 HOUR)) <= DATE(NOW())
    ORDER BY mt.date_start DESC
    LIMIT 1
    ";
    $defaultSessionId = $GLOBALS['db']->getOne($sql);

    $sql = "SELECT
    mt.id session_id,
    DATE_ADD(mt.date_start, INTERVAL 7 HOUR) date_start
    FROM  j_class c
    INNER JOIN meetings mt ON c.id = mt.ju_class_id AND mt.deleted = 0 
    AND mt.meeting_type = 'Session'  AND mt.session_status <> 'Canceled'

    WHERE c.id = '$classId'
    AND c.class_type = 'Normal Class'
    AND c.deleted = 0 
    ORDER BY mt.date_start ASC";   
    $result = $GLOBALS['db']->query($sql);

    while($row = $GLOBALS['db']->fetchByAssoc($result)){
        $dateTimeParts = explode(" ", $row['date_start']);
        $timeParts = explode(":", $dateTimeParts[1]);

        $displayDate = $timedate->to_display_date($dateTimeParts[0], true);
        $displayTime = $timeParts[0]."h".$timeParts[1];

        $selected = "";
        if($defaultSessionId == $row['session_id']) $selected = "selected";
        
        $sessionOptions .= "<option $selected date='{$displayDate}' value='{$row['session_id']}'>{$displayDate} &nbsp;-&nbsp; {$displayTime}</option>";

    }

    return $sessionOptions;
}

function getClassOptions(){
    global $current_user;
    // generate option classes for current user
    $q1 = "SELECT DISTINCT
    IFNULL(j_class.id, '') primaryid,
    IFNULL(j_class.name, '') j_class_name
    FROM j_class
    WHERE
    ((COALESCE(LENGTH(j_class.status), 0) > 0
    AND j_class.status != '^^')
    AND j_class.class_type = 'Normal Class'
    AND j_class.team_id = '{$current_user->team_id}'
    AND j_class.deleted = 0";

    /*Comment by Tung Bui - 23/12/2015
    Add below sql to select class by team set is of use

    AND j_class.team_set_id IN
    (SELECT
    tst.team_set_id
    FROM
    team_sets_teams tst
    INNER JOIN
    team_memberships team_memberships ON tst.team_id = team_memberships.team_id
    AND team_memberships.user_id = '".$current_user->id."'
    AND team_memberships.deleted = 0)
    ))

    */
    $rs1 = $GLOBALS['db']->query($q1);
    $classOptions = "";
    while($row = $GLOBALS['db']->fetchByAssoc($rs1) ) {
        $classOptions .= "<option value='{$row['primaryid']}'>{$row['j_class_name']}</option>";
    }
    return $classOptions;
}

?>
