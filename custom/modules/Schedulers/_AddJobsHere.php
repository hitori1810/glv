<?php
//$job_strings[] = 'runMassSMSCampaign';
$job_strings[] = 'runCarryForwardReport';
$job_strings[] = 'updateStatusContacts';
$job_strings[] = 'autoUpdateStudentStatus';
$job_strings[] = 'deleteClassPlanning';
$job_strings[] = 'updateClassStatus';
$job_strings[] = 'unreleaseInvoice';
$job_strings[] = 'autoRetriveStudyRecord';
$job_strings[] = 'inactivePortalAccount';
$job_strings[] = 'alertEmailMonthly';
$job_strings[] = 'updateStatusOfClass';

//function runMassSMSCampaign() {
//
//    $GLOBALS['log']->debug('Called:runMassSMSCampaign');
//
//    if (!class_exists('DBManagerFactory')){
//        require('include/database/DBManagerFactory.php');
//    }
//
//    global $beanList;
//    global $beanFiles;
//    require("config.php");
//    require('include/modules.php');
//    if(!class_exists('AclController')) {
//        require('modules/ACL/ACLController.php');
//    }
//
//    require('modules/EmailMan/SMSManDelivery.php');
//    return true;
//}

function runCarryForwardReport() {
    include_once("custom/modules/C_Carryforward/cfff.php");
    return true;

}

function updateStatusContacts() {
    $GLOBALS['log']->debug('BEGIN:updateStatusContacts');
    include_once("custom/modules/Contacts/updateStatusStudents.php");
    $GLOBALS['log']->debug('END:updateStatusContacts');
    return true ;
}

//############## STUDENT ########################
/**
* Auto-Update Student Status
*/
function autoUpdateStudentStatus(){
    $today = date('Y-m-d');

    //UPDATE In Progress - Junior
    $q1 = "SELECT DISTINCT
    IFNULL(contacts.id, '') primaryid
    FROM
    contacts
    INNER JOIN
    j_studentsituations l1 ON contacts.id = l1.student_id
    AND l1.deleted = 0
    INNER JOIN j_class cl ON l1.ju_class_id = cl.id
    AND cl.deleted = 0
    AND cl.class_type = 'Normal Class'
    INNER JOIN
    teams l2 ON contacts.team_id = l2.id
    AND l2.deleted = 0
    WHERE
    (((l1.start_study <= '$today')
    AND (l1.end_study > '$today')
    AND (l1.type IN ('Enrolled','Settle','Moving In'))))
    AND contacts.deleted = 0";
    $row1 = $GLOBALS['db']->fetchArray($q1);
    if(!empty($row1)){
        $u1 = "UPDATE contacts SET contact_status = 'In Progress' WHERE id IN ('".implode("','",array_column($row1, 'primaryid'))."')";
        $GLOBALS['db']->query($u1);
    }

    /*    //UPDATE In Progress - Adult
    $q9 = "SELECT DISTINCT
    IFNULL(contacts.id, '') primaryid
    FROM
    contacts
    INNER JOIN
    teams l1 ON contacts.team_id = l1.id
    AND l1.deleted = 0
    INNER JOIN
    contacts_j_payment_1_c l2_1 ON contacts.id = l2_1.contacts_j_payment_1contacts_ida
    AND l2_1.deleted = 0
    INNER JOIN
    j_payment l2 ON l2.id = l2_1.contacts_j_payment_1j_payment_idb
    AND l2.deleted = 0
    WHERE
    (((l1.team_type = 'Adult')
    AND (l2.start_study <= '$today')
    AND (l2.end_study > '$today')
    AND (l2.payment_type IN ('Cashholder'))))
    AND contacts.deleted = 0";
    $row9 = $GLOBALS['db']->fetchArray($q9);
    if(!empty($row9)){
    $u1 = "UPDATE contacts SET contact_status = 'In Progress' WHERE id IN ('".implode("','",array_column($row9, 'primaryid'))."')";
    $GLOBALS['db']->query($u1);
    }*/

    //UPDATE OutStanding
    $q2 = "SELECT DISTINCT
    IFNULL(contacts.id, '') primaryid
    FROM
    contacts
    INNER JOIN
    j_studentsituations l1 ON contacts.id = l1.student_id
    AND l1.deleted = 0
    WHERE
    (((l1.start_study <= '$today')
    AND (l1.end_study > '$today')
    AND (l1.type IN ('OutStanding'))
    AND contacts.contact_status NOT IN ('In Progress')))
    AND contacts.deleted = 0";
    $row2 = $GLOBALS['db']->fetchArray($q2);
    if(!empty($row2)){
        $u2 = "UPDATE contacts SET contact_status = 'OutStanding' WHERE id IN ('".implode("','",array_column($row2, 'primaryid'))."')";
        $GLOBALS['db']->query($u2);
    }

    //UPDATE Delayed - Not In Progress và outstanding ở trên
    $q3 = "SELECT DISTINCT
    IFNULL(contacts.id, '') primaryid
    FROM
    contacts
    INNER JOIN
    j_studentsituations l1 ON contacts.id = l1.student_id
    AND l1.deleted = 0
    WHERE
    (((l1.start_study <= '$today')
    AND (l1.end_study > '$today')
    AND (l1.type IN ('Delayed','Stopped'))
    AND contacts.id NOT IN ('".implode("','",array_column($row1, 'primaryid'))."','".implode("','",array_column($row2, 'primaryid'))."')))
    AND contacts.deleted = 0";
    $row3 = $GLOBALS['db']->fetchArray($q3);
    if(!empty($row3)){
        $u3 = "UPDATE contacts SET contact_status = 'Delayed' WHERE id IN ('".implode("','",array_column($row3, 'primaryid'))."')";
        $GLOBALS['db']->query($u3);
    }

    //UPDATE Finished
    $q4 = "SELECT DISTINCT
    IFNULL(st.id, '') primaryid
    FROM
    contacts st
    INNER JOIN
    j_studentsituations ss ON ss.student_id = st.id AND ss.deleted = 0
    AND st.deleted = 0
    AND st.contact_status = 'In Progress'
    INNER JOIN j_class cl ON ss.ju_class_id = cl.id
    AND cl.deleted = 0
    AND cl.class_type = 'Normal Class'
    GROUP BY st.id
    HAVING MAX(ss.end_study) <= '$today'";

    $row4 = $GLOBALS['db']->fetchArray($q4);
    if(!empty($row4)){
        $u4 = "UPDATE contacts SET contact_status = 'Finished' WHERE id IN ('".implode("','",array_column($row4, 'primaryid'))."')";
        $GLOBALS['db']->query($u4);
    }

    //Update Stopped Student
    $q5 = "SELECT
    ct.id primaryid,
    MAX(ss.end_study) max_end_study,
    ct2.max_pd max_paymentdate
    FROM
    contacts ct
    INNER JOIN
    j_studentsituations ss ON ct.id = ss.student_id
    AND ct.is_stopped = 0
    AND ct.id NOT IN (SELECT DISTINCT
    student_id
    FROM
    j_studentsituations sss
    WHERE
    sss.deleted = 0
    AND sss.type = 'Outstanding'
    AND sss.end_study >= DATE_ADD(CURDATE(), INTERVAL 1 DAY))
    AND ss.type IN ('Moving In' , 'Enrolled', 'Settle')
    AND ss.deleted = 0
    AND ct.deleted = 0
    INNER JOIN
    j_class c ON c.id = ss.ju_class_id AND c.deleted = 0
    AND c.kind_of_course NOT IN ('Cambridge' , 'Outing Trip')
    AND c.class_type <> 'Waiting Class'
    INNER JOIN
    (SELECT
    cp.contacts_j_payment_1contacts_ida contact_id,
    MAX(p.payment_date) max_pd
    FROM
    contacts_j_payment_1_c cp
    INNER JOIN j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
    AND p.deleted = 0
    AND cp.deleted = 0
    AND p.payment_type IN ('Delay' , 'Transef In', 'Moving In', 'Deposit', 'Cashholder', 'Merge AIMS', 'Transfer From AIMS')
    GROUP BY contact_id
    HAVING SUM(p.remain_amount) < 500000) ct2 ON ct2.contact_id = ct.id
    GROUP BY ss.student_id
    HAVING MAX(ss.end_study) <= CURDATE()
    ORDER BY max_end_study DESC";
    $rs5 = $GLOBALS['db']->query($q5);
    while($row5 = $GLOBALS['db']->fetchByAssoc($rs5))
        $GLOBALS['db']->query("UPDATE contacts SET is_stopped=1 , stopped_date='{$row5['max_end_study']}' WHERE id = '{$row5['primaryid']}'");


    //    //UPDATE Waiting for Class
    //    $u5 = "UPDATE contacts SET contact_status = 'Waiting for class' WHERE contact_status NOT IN ('In Progress', 'OutStanding', 'Delayed', 'Finished') AND deleted = 0";
    //    $GLOBALS['db']->query($u5);
    //
    return true;
}
//End STUDENT

//############## CLASS ########################
/**
* Auto-Delete Class Planning
*/
function deleteClassPlanning(){
    $date = new DateTime('first day of last month');
    $filter_date = $date->format('Y-m-d');
    if(empty($filter_date)){
        $GLOBALS['log']->security("Crontab deleteClassPlanning failed!!");
        return true;
    }
    //Test Cron
    $q1 = "SELECT
    jc.id class_id
    FROM
    j_class jc
    WHERE
    jc.status = 'Planning'
    AND jc.class_type <> 'Waiting Class'
    AND jc.start_date < '$filter_date'
    AND jc.deleted = 0
    AND jc.id NOT IN (SELECT
    ju_class_id
    FROM
    j_studentsituations
    WHERE
    deleted = 0 AND ju_class_id <> ''
    GROUP BY ju_class_id
    HAVING COUNT(ju_class_id) > 0)";
    $rs1 = $GLOBALS['db']->query($q1);
    while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
        $q2 = "DELETE FROM meetings WHERE ju_class_id = '{$row['class_id']}' ";
        $GLOBALS['db']->query($q2);

        $q3 = "UPDATE j_class SET deleted = 1 WHERE id = '{$row['class_id']}' ";
        $GLOBALS['db']->query($q3);
    }

    return true;
}
/**
* Auto-Update Finish Class
*/
function updateClassStatus($GET){
    $q1 = "UPDATE j_class SET status = 'Finish' WHERE (((end_date <= '".date('Y-m-d')."' ) AND (status IN ('In Progress') ) AND (class_type <> 'Waiting Class' ))) AND deleted=0";
    $GLOBALS['db']->query($q1);
    //Update In Progress
    $q2 = "UPDATE j_class SET status = 'In Progress' WHERE (((start_date = '".date('Y-m-d', strtotime("+1 day ". date('Y-m-d')))."' ) AND (status ='Planning') AND (class_type <> 'Waiting Class' ))) AND deleted=0";
    $GLOBALS['db']->query($q2);
    return true;
}
//End CLASS

//############## PAYMENT ########################
/**
* Un-release Invoice
*/
function unreleaseInvoice($get){
    $sql = "UPDATE j_paymentdetail SET is_release=0 WHERE is_release=1";
    $GLOBALS['db']->query($sql);

    $sql2 = "UPDATE j_configinvoiceno SET release_list='', finish_printing=1 WHERE deleted=0";
    $GLOBALS['db']->query($sql2);

    //    $sql2 = "UPDATE j_configinvoiceno SET finish_printing=1 WHERE deleted=0";
    //    $GLOBALS['db']->query($sql2);

    //Remove data rac
    $sql3 = "DELETE FROM c_deliveryrevenue WHERE passed = 1";
    $GLOBALS['db']->query($sql3);

    //Fix bug Add Demo Lead convert to Student
    $q1 = "SELECT DISTINCT
    IFNULL(meetings_contacts.id, '') primaryid,
    IFNULL(meetings_contacts.contact_id, '') contact_id,
    IFNULL(meetings_contacts.meeting_id, '') meeting_id,
    date(DATE_ADD(mt.date_start,INTERVAL 7 hour)) date_start,
    st.start_study start_study,
    IFNULL(st.id, '') situation_demo_id
    FROM
    meetings_contacts
    INNER JOIN
    meetings mt ON mt.id = meetings_contacts.meeting_id
    AND mt.deleted = 0
    INNER JOIN
    j_class cl ON mt.ju_class_id = cl.id
    AND cl.deleted = 0
    LEFT JOIN
    contacts ct ON ct.id = meetings_contacts.contact_id
    AND ct.deleted = 0
    LEFT JOIN
    j_studentsituations st ON st.student_id = ct.id AND st.deleted = 0
    AND st.type = 'Demo'
    WHERE
    meetings_contacts.deleted = 0
    AND (meetings_contacts.situation_id = ''
    OR meetings_contacts.situation_id IS NULL)";
    $rs1 = $GLOBALS['db']->query($q1);
    while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
        if(empty($row['situation_demo_id'])){
            $GLOBALS['db']->query("DELETE FROM meetings_contacts WHERE id='{$row['primaryid']}'");
        }else{
            if($row['date_start'] == $row['start_study']){
                $GLOBALS['db']->query("UPDATE meetings_contacts SET situation_id = '{$row['situation_demo_id']}' WHERE id = '{$row['primaryid']}'");
            }else{
                $GLOBALS['db']->query("DELETE FROM meetings_contacts WHERE id='{$row['primaryid']}'");
            }
        }
    }


    return true;
}

function autoRetriveStudyRecord(){
    /*$sql = "SELECT id FROM alpha_classroom WHERE id > 0";
    $rs = $GLOBALS['db']->fetchArray($sql);
    $auth_key = "?username=webs&password=GHhNJ5=26";
    foreach($rs as $key=>$value){
    set_time_limit(500);
    $url = "http://portal360.Atlantic.edu.vn/admin/elearning/retrievejson/{$value['id']}$auth_key";
    $success = 0;
    while(!$success){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $json_string = curl_exec($ch);
    curl_close($ch);
    $passed_arr = json_decode($json_string,true);
    $success = $passed_arr['success'];
    }
    } */
    require_once('custom/include/_helper/RetrieveRecord.php');
    $sql = "SELECT id FROM alpha_classroom WHERE alpha_delete = 0";
    $rs = $GLOBALS['db']->fetchArray($sql);
    $auth_key = "?username=webs&password=GHhNJ5=26";
    $param = array(
        'X_API_KEY: 374cb2ebfe74bd4fec17d0dffb1023c6c4676c3a35a93d20f830ea56ade0039a',
        'Accept: application/json', );
    foreach($rs as $key=>$value){
        $result[] = retrieve($value['id'], $auth_key, $param);
    }
    return true;
}



function inactivePortalAccount(){
    $sql = "SELECT
    u.id
    FROM
    contacts_j_payment_1_c cp
    INNER JOIN
    j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
    AND cp.deleted = 0
    AND p.deleted = 0
    AND p.payment_type = 'Cashholder'
    AND p.status <> 'Closed'
    INNER JOIN
    teams t ON t.id = p.team_id
    AND t.team_type = 'Adult'
    INNER JOIN
    contacts c ON c.id = cp.contacts_j_payment_1contacts_ida
    AND c.deleted = 0
    INNER JOIN
    users u ON u.id = c.user_id AND u.deleted = 0
    AND u.status = 'Active'
    GROUP BY cp.contacts_j_payment_1contacts_ida
    HAVING MAX(case when p.end_study = '0000-00-00' then date_add(p.payment_date, interval p.tuition_hours day) else
    ifnull(p.end_study,date_add(p.payment_date, interval p.tuition_hours day)) end) = DATE_ADD(CURDATE(), INTERVAL - 1 DAY)";
    $rs = $GLOBALS['db']->query($sql);
    $arr_student = array();
    while ($row = $GLOBALS['db']->fetchByAssoc($rs)){
        $arr_student[] = $row['id'];
    }
    $ext_student = "'".implode("','",$arr_student)."'";
    $GLOBALS['db']->query("UPDATE users SET status = 'Inactive' WHERE id IN ($ext_student)");
    return true;
}

function alertEmailMonthly(){
    require_once("include/SugarPHPMailer.php");
    require_once("include/workflow/alert_utils.php");
    require_once("custom/modules/Schedulers/helperScheduler.php");
    //Load Email all Center
    $q0 = "SELECT DISTINCT
    IFNULL(teams.id, '') primaryid,
    IFNULL(teams.name, '') name,
    IFNULL(teams.short_name, '') short_name,
    IFNULL(teams.cm_email, '') cm_email,
    IFNULL(teams.ec_email, '') ec_email,
    IFNULL(teams.efl_email, '') efl_email,
    IFNULL(teams.short_name, '') short_name,
    IFNULL(teams.team_type, '') team_type,
    IFNULL(teams.region, '') region
    FROM
    teams
    LEFT JOIN
    teams l1 ON teams.id = l1.parent_id
    AND l1.deleted = 0
    WHERE
    ((((l1.id IS NULL OR l1.id = ''))
    AND ((teams.private IS NULL
    OR teams.private = '0'))
    AND (teams.team_type IN ('Adult' , 'Junior'))
    AND (teams.region IN ('South' , 'North'))
    AND ((teams.ec_email IS NOT NULL
    OR teams.ec_email <> ''))))
    AND teams.deleted = 0";
    $center_list = $GLOBALS['db']->fetchArray($q0);

    //Task 2: Danh sach lop chua submit In Progress
    $admin = new Administration();
    $admin->retrieveSettings();
    foreach($center_list as $key => $center){
        //$center['ec_email']     = 'lap.nguyen@Atlantic.edu.vn';
        //$center['efl_email']    = 'lap.nguyen@Atlantic.edu.vn';
        //$center['short_name']   = 'LAP NGUYEN';
        //$center['team_type']    = 'Adult';
        $mail = new SugarPHPMailer;
        setup_mail_object($mail, $admin);
        $mail->addAddress($center['ec_email'],'EC '.$center['short_name']);  // Add a recipient
        if(!empty($center['efl_email']))
            $mail->addAddress($center['efl_email'], 'EFL '.$center['short_name']);
        //        $mail->AddCC('it.hcm@Atlantic.edu.vn', 'IT HCM');
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = '[SIS] MONTH-END TASK LIST';
        $mail->Body    = fillHTMLBody($center);

        if(!$mail->Send())
        {
            $GLOBALS['log']->warn("Notifications: error sending e-mail (method: {$mail->Mailer}), (error: {$mail->ErrorInfo})");
        }

    }
    return true;

}
//End PAYMENT

// Anđ by Tung Nguyen 6-6-2018
function updateStatusOfClass() {
    global $db, $timedate;

    $timedate->getInstance()->userTimezone();
    $currenrDateTimeUser = $timedate->now();
    
    $dbDateUser = $timedate->to_db_date($currenrDateTimeUser, true);

    // Update status In Progress
    $sql = "UPDATE j_class, (
                SELECT
                    c.id as id
                FROM
                    j_class c
                INNER JOIN meetings mt ON c.id = mt.ju_class_id
                WHERE
                    mt.meeting_type = 'Session'
                AND c.class_type = 'Normal Class'
                AND c.`status` = 'Planning'
                AND mt.session_status <> 'Canceled'
                AND c.deleted = 0
                AND mt.deleted = 0
                AND MIN(DATE(mt.date_start)) <= '$dbDateUser'
                GROUP BY
                    c.id  
                ) as A
            SET j_class.`status` = 'In Progress'
            WHERE A.id = j_class.id";
    $db->query($sql)
        

    // Update status Closed
    $sql1 = "UPDATE j_class, (
                SELECT
                    c.id as id
                FROM
                    j_class c
                INNER JOIN meetings mt ON c.id = mt.ju_class_id
                WHERE
                    mt.meeting_type = 'Session'
                AND c.class_type = 'Normal Class'
                AND c.`status` = 'In Progress'
                AND mt.session_status <> 'Canceled'
                AND c.deleted = 0
                AND mt.deleted = 0
                AND MAX(DATE(mt.date_start)) <= '$dbDateUser'
                GROUP BY
                    c.id  
                ) as A
            SET j_class.`status` = 'Closed'
            WHERE A.id = j_class.id";
    $db->query($sql1)

    return true;
}
?>
