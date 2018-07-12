<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/




global $timedate;
global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;
global $hilite_bg;
global $sugar_version, $sugar_config;

global $theme;



$GLOBALS['log']->info("Project Dashboard view");

echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$mod_strings['LBL_MY_PROJECTS_DASHBOARD']), true);

$sugar_smarty = new Sugar_Smarty();
///
/// Assign the template variables
///
$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);

$sugar_smarty->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

// MY PROJECTS DASHBOARD ////////////////////////////////////////
$projectBean = new Project();
$projects = array();

$today = $timedate->nowDbDate();
$nextWeek = $timedate->asDbDate( $timedate->getNow()->get('+1 week'));

$query = "SELECT * FROM project WHERE project.assigned_user_id='".$current_user->id."' AND project.estimated_end_date >= '".
        $today."' AND project.is_template=0 AND project.deleted=0 order by project.estimated_end_date ASC";
$result = $projectBean->db->query($query, true, "");
$row = $projectBean->db->fetchByAssoc($result);
while ($row != null) {
    $project = new Project();
    $project->id = $row['id'];
    $project->retrieve();
    array_push($projects, $project);
    $row = $projectBean->db->fetchByAssoc($result);
}

$overdueTasks = array();
$upcomingTasks = array();
$openCases = array();
$resources = array();
$overdueTaskCount = array();
$upcomingTaskCount = array();

foreach ($projects as $project) {
    // Find all overdue tasks/////////////////
    $projectTaskBean = new ProjectTask();
    $query = "SELECT * FROM project_task WHERE project_task.project_id='" .$project->id."' AND project_task.date_finish < '". $today . "' AND project_task.percent_complete < 100 AND project_task.deleted=0 order by project_task.date_finish ASC";
    $result = $projectTaskBean->db->query($query, true, "");
    $row = $projectTaskBean->db->fetchByAssoc($result);
    $count = 0;
    while ($row != null) {
        if ($count == 10) {
            $count++;
            break;
        }
            
        $projectTask = new ProjectTask();
        $projectTask->id = $row['id'];
        $projectTask->retrieve();
        if (isset($overdueTasks[$project->id])) {
            array_push($overdueTasks[$project->id], $projectTask);
        }
        else {
            $overdueTasks[$project->id] = array();
            array_push($overdueTasks[$project->id], $projectTask);
        }
        $count++;
        $row = $projectTaskBean->db->fetchByAssoc($result);
    }
    $overdueTaskCount[$project->id] = $count;
    /////////////////////////////////////////

    // Find all upcoming tasks/////////////////
    $projectTaskBean = new ProjectTask();
    $query = "SELECT * FROM project_task WHERE project_task.project_id='" .$project->id."' AND " .
            "(project_task.date_start BETWEEN '" . $today . "' AND '". $nextWeek . "' OR ".
            "project_task.date_finish BETWEEN '". $today . "' AND '". $nextWeek . "') AND project_task.deleted=0 order by project_task.date_finish ASC";

    $result = $projectTaskBean->db->query($query, true, "");
    $row = $projectTaskBean->db->fetchByAssoc($result);
    $count = 0;
    while ($row != null) {
        if ($count == 10) {
            $count++;
            break;
        }
        $projectTask = new ProjectTask();
        $projectTask->id = $row['id'];
        $projectTask->retrieve();
        if (isset($upcomingTasks[$project->id]))
            array_push($upcomingTasks[$project->id], $projectTask);
        else {
            $upcomingTasks[$project->id] = array();
            array_push($upcomingTasks[$project->id], $projectTask);
        }
        $count++;
        $row = $projectTaskBean->db->fetchByAssoc($result);
    }
    $upcomingTaskCount[$project->id] = $count;
    
    /////////////////////////////////////////
    
    // Find all related Cases /////////////////
    $caseBean = new aCase();
    $query = "SELECT * from cases where id in ".
            "(SELECT case_id from projects_cases where project_id='". $project->id. "' and deleted = 0) and status != '".
            $app_list_strings['case_status_dom']['Closed'] . "'";

    $result = $caseBean->db->query($query, true, "");
    $row = $caseBean->db->fetchByAssoc($result);
    $count = 0;
    while ($row != null) {
        if ($count == 10) {
            $count++;
            break;
        }
        $case = new aCase();
        $case->id = $row['id'];
        $case->retrieve();
        
        if (isset($openCases[$project->id]))
            array_push($openCases[$project->id], $case);
        else {
            $openCases[$project->id] = array();
            array_push($openCases[$project->id], $case);
        }
        $count++;
        $row = $caseBean->db->fetchByAssoc($result);
    }
    $caseCount[$project->id] = $count;
    /////////////////////////////////////////    
    // Find all resources/////////////////

    $userBean = new User();
    $project->load_relationship("user_resources");
    $users = $project->user_resources->getBeans($userBean);
    $contactBean = new Contact();
    $project->load_relationship("contact_resources");
    $contacts = $project->contact_resources->getBeans($contactBean);
    
    for ($i = 0; $i < count($users); $i++) {
        if (!isset ($resources[$users[$i]->id]))
            $resources[$users[$i]->id] = $users[$i]->full_name;
    }
    for ($i = 0; $i < count($contacts); $i++) {
        if (!isset ($resources[$contacts[$i]->id]))
            $resources[$contacts[$i]->id] = $contacts[$i]->full_name;    
    }
    /////////////////////////////////////////
}

$sugar_smarty->assign("RESOURCES", $resources);
$sugar_smarty->assign("PROJECTS", $projects);
$sugar_smarty->assign("UPCOMING_TASKS", $upcomingTasks);
$sugar_smarty->assign("OVERDUE_TASKS", $overdueTasks);
$sugar_smarty->assign("OPEN_CASES", $openCases);
$sugar_smarty->assign("BG_COLOR", $hilite_bg);
$sugar_smarty->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());
//todo: also add the owner's managers 

$sugar_smarty->assign("DATE_FORMAT", $current_user->getPreference('datef'));
$sugar_smarty->assign("CURRENT_USER", $current_user->id);

// MY PROJECT TASKS DASHBOARD ////////////////////////////////////////
$myOverdueTasks = array();
$myUpcomingTasks = array();

// Find all my overdue tasks/////////////////
$projectTaskBean = new ProjectTask();
$query = "SELECT * FROM project_task WHERE project_task.resource_id like '".$current_user->id."' AND project_task.date_finish < '". $today . "' AND project_task.percent_complete < 100 AND project_task.deleted=0 order by project_task.date_finish ASC";
$result = $projectTaskBean->db->query($query, true, "");
$row = $projectTaskBean->db->fetchByAssoc($result);
$myOverDueTasksCount = 0;
while ($row != null) {
    $projectTask = new ProjectTask();
    $projectTask->id = $row['id'];
    $projectTask->retrieve();
    array_push($myOverdueTasks, $projectTask);
    $myOverDueTasksCount++;
    $row = $projectTaskBean->db->fetchByAssoc($result);
}
/////////////////////////////////////////
// Find all upcoming tasks/////////////////
$projectTaskBean = new ProjectTask();
$query = "SELECT * FROM project_task WHERE project_task.resource_id like '" .$current_user->id."' AND " .
        "(project_task.date_start BETWEEN '" . $today . "' AND '". $nextWeek . "' OR ".
        "project_task.date_finish BETWEEN '". $today . "' AND '". $nextWeek . "') AND project_task.deleted=0 order by project_task.date_finish ASC";

$result = $projectTaskBean->db->query($query, true, "");
$row = $projectTaskBean->db->fetchByAssoc($result);
$myUpcomingTasksCount = 0;
while ($row != null) {
    $projectTask = new ProjectTask();
    $projectTask->id = $row['id'];
    $projectTask->retrieve();
    array_push($myUpcomingTasks, $projectTask);
    $myUpcomingTasksCount++;
    $row = $projectTaskBean->db->fetchByAssoc($result);
}
/////////////////////////////////////////
$sugar_smarty->assign("MY_UPCOMING_TASKS", $myUpcomingTasks);
$sugar_smarty->assign("MY_OVERDUE_TASKS", $myOverdueTasks);

$sugar_smarty->assign("OVERDUE_TASKS_COUNT", $overdueTaskCount);
$sugar_smarty->assign("UPCOMING_TASKS_COUNT", $upcomingTaskCount);

echo $sugar_smarty->fetch('modules/Project/Dashboard.tpl');
?>
