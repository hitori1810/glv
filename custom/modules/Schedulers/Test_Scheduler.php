<?php
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
//foreach($center_list as $key => $center){
$center['ec_email']     = 'vietnamknight@gmail.com';
$center['efl_email']    = 'vietnamknight@gmail.com';
$center['short_name']   = 'LAP NGUYEN';
$center['team_type']    = 'Adult';
$mail = new SugarPHPMailer;
setup_mail_object($mail, $admin);
$mail->addAddress($center['ec_email'],'EC '.$center['short_name']);  // Add a recipient
if(!empty($center['efl_email']))
    $mail->addAddress($center['efl_email'], 'EFL '.$center['short_name']);
$mail->AddCC('vietnamknight@gmail.com', 'IT HCM');
$mail->isHTML(true); //Set email format to HTML
$mail->Subject = '[SIS] MONTH-END TASK LIST';
$mail->Body    = fillHTMLBody($center);

if(!$mail->Send())
{
    $GLOBALS['log']->warn("Notifications: error sending e-mail (method: {$mail->Mailer}), (error: {$mail->ErrorInfo})");
}
//}
return true;