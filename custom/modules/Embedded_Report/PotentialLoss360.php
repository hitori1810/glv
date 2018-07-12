<?php
$js =
    <<<EOQ
        <script>
        SUGAR.util.doWhen(
        function() {
           return $('#rowid0').find('td').eq(3).length == 1;
        },
        function() {
            $('#rowid0').find('td').eq(1).text('Payment End Date');
            $('#rowid0').find('td').eq(3).text('');
            });
        </script>
EOQ;

echo $js;
if (!isset($_POST['record']) || empty($_POST['record'])) {
    echo "Please, Select filter options and click Apply to show this report. ";
    $sv = new SugarView();
    $sv->displayFooter();
    die();
}

require_once("custom/include/_helper/report_utils.php");

global $current_user, $timedate;
if (($current_user->is_admin <> '1') && !(ACLController::checkAccess('C_Commission', 'Edit', false)) && !ACLController::checkAccess('J_StudentSitations', 'list', false))
    die("You do not have permision to view this report.");
$filter = str_replace(' ', '', $this->where);
$parts = explode("AND", $filter);

for ($i = 0; $i < count($parts); $i++) {
    if (strpos($parts[$i], "l1.id='") !== FALSE) $team_id = get_string_between($parts[$i]);
    if (strpos($parts[$i], "l1.idIN")) $team_id = get_string_between($parts[$i], "('", "')");
    if (strpos($parts[$i], "j_studentsituations.end_study>='") !== FALSE) $start_date = substr(get_string_between($parts[$i]), 0, 10);
    if (strpos($parts[$i], "j_studentsituations.end_study<='") !== FALSE) $end_date = substr(get_string_between($parts[$i]), 0, 10);
    if (strpos($parts[$i], "j_studentsituations.end_study='") !== FALSE) {
        $start_date = get_string_between($parts[$i]);
        $end_date = $start_date;
    }
}
$html = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html .= '<tr height="20">';
$html .= '<th rowspan = 0 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">SEQ</th>';
$html .= '<th rowspan = 0 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Student Name</th>';
$html .= '<th rowspan = 0 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Student Code</th>';
$html .= '<th rowspan = 0 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Birthdate</th>';
$html .= '<th rowspan = 0 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Phone</th>';
$html .= '<th rowspan = 0  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Payment</th>';
$html .= '<th rowspan = 0  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">End Date</th>';
//$html .= '<th rowspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Notes</th>';
//$html .= '<th rowspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Class Name</th>';
//$html .= '<th rowspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Kind of Course</th>';
//$html .= '<th rowspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Level</th>';
$html .= '<th rowspan = 0  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Center Code</th></tr>';

$ext_team = "";
if (!empty($team_id))
    $ext_team = "AND t.id IN ('$team_id')";

$qTeam = "AND ct.team_set_id IN

(SELECT 
tst.team_set_id
FROM
team_sets_teams tst
INNER JOIN
team_memberships team_memberships ON tst.team_id = team_memberships.team_id
AND team_memberships.user_id = '{$current_user->id}'
AND team_memberships.deleted = 0)";
if ($GLOBALS['current_user']->isAdmin()) {
    $qTeam = "";
}

$sql = "SELECT 
    ct.id contact_id,
    ct.full_student_name,
    ct.contact_id contact_code,
    ct.birthdate,
    ct.phone_mobile,
    t.id center_id,
    t.name center_name,
    t.team_type,
    t.code_prefix center_code,
    p.team_id,
    p.id payment_id,
    p.name,
    p.payment_type,
    p.payment_date,
    p.total_after_discount,
    p.remain_amount,
    CASE
        WHEN
            p.payment_type = 'Cashholder'
        THEN
            CASE
                WHEN p.end_study = '0000-00-00' THEN DATE_ADD('$end_date', INTERVAL 1 DAY)
                ELSE p.end_study
            END
        WHEN p.payment_type IN ('Delay' , 'Transfer In', 'Moving In') THEN p.payment_expired
        WHEN p.payment_type IN ('Transfer Out' , 'Moving Out') THEN p.payment_date
    END AS end_date
FROM
    contacts ct
        INNER JOIN
    contacts_j_payment_1_c cp ON cp.contacts_j_payment_1contacts_ida = ct.id
        AND cp.deleted = 0
        AND ct.deleted = 0
        INNER JOIN
    j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
        AND p.deleted = 0
        INNER JOIN
    teams t ON t.id = ct.team_id
    AND t.team_type = 'Adult'
    $ext_team
    $qTeam
WHERE
    (p.payment_type IN ('Delay' , 'Transfer In', 'Moving In')
        AND p.remain_amount > 0)
        OR (p.payment_type = 'Cashholder'
        AND (p.end_study >= '$start_date'
        OR p.end_study = '0000-00-00'))
        OR (p.payment_type = 'Transfer Out'
        AND payment_date BETWEEN '$start_date' AND '$end_date')
ORDER BY ct.id , end_date DESC ";
$rs = $GLOBALS['db']->fetchArray($sql);
$row_student = array();
$student_id = '';
$next = 0;
foreach ($rs as $key => $value) {
    if ($student_id <> $value['contact_id']) {
        $student_id = $value['contact_id'];
        if ($value['end_date'] > $end_date || $value['end_date'] < $start_date) {
            $next = 1;
            continue;
        } else {
            $row_student[$student_id]['full_student_name'] = $value['full_student_name'];
            $row_student[$student_id]['contact_code'] = $value['contact_code'];
            $row_student[$student_id]['birthdate'] = $value['birthdate'];
            $row_student[$student_id]['phone_mobile'] = $value['phone_mobile'];
            $row_student[$student_id]['end_date'] = $value['end_date'];
            $row_student[$student_id]['center_code'] = $value['center_code'];
            $row_student[$student_id]['payment_type'] = $value['payment_type'];
            $row_student[$student_id]['payment_id'] = $value['payment_id'];
            $next = 0;
        }
    } else {
        if ($next = 1 || $row_student[$student_id]['end_date'] >= $value['end_date'])
            continue;
    }
}

$row_student = array_orderby( $row_student, 'center_code', SORT_STRING, 'end_date', SORT_STRING);

$seq = 0;
foreach ($row_student as $key =>$value){
    $html .= '<tr height="20">';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">' . ++$seq . '</td>';
    $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=Contacts&action=DetailView&record=$key' target='_blank'>" . $value['full_student_name'] . "</td>";
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">' . $value['contact_code'] . '</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">' . $timedate->to_display_date($value['birthdate'], false) . '</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">' . $value['phone_mobile'] . '</td>';
    $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=J_Payment&action=DetailView&record={$value['payment_id']}' target='_blank'>" . $value['payment_type'] . "</td>";
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">' . $timedate->to_display_date($value['end_date'], false) . '</td>';
//    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">' . $first['description'] . '</td>';
//    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""><a href="index.php?module=J_Class&action=DetailView&record=' . $first['class_id'] . '" target=\'_blank\'>' . $first['class_name'] . '</td>';
//    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">' . $first['kind_of_course'] . '</td>';
//    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">' . $first['level'] . '</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">' . $value['center_code'] . '</td></tr>';


}

$html .= "</tbody></table>";
echo $html;

//JS
$js =
    <<<EOQ
        <script>
        SUGAR.util.doWhen(
        function() {
           return $('#rowid0').find('td').eq(3).length == 1;
        },
        function() {
            $('#rowid0').find('td').eq(1).text('Payment End Date');
            $('#rowid0').find('td').eq(3).text('');
            });
        </script>
EOQ;

echo $js;

?>
