<?php
if(!isset($_POST['record']) || empty($_POST['record'])){
    echo "Please, Select filter options and click Apply to show this report. ";
    $sv = new SugarView();
    $sv->displayFooter();
    die();
}

$filter = str_replace(' ','',$this->where);
$parts  = explode("AND", $filter);

for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "l3.id='") !== FALSE)                   $student_id = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l2.id='") !== FALSE)                   $class_id   = get_string_between($parts[$i]);
}
$ext_class      = '';
if(!empty($class_id))
    $ext_class = "AND m.ju_class_id = '$class_id'";
else {
    echo "Please choose one class per view !";
    die();
}

$ext_student    = '';
if(!empty($student_id))
    $ext_student = "AND c.id = '$student_id'";

global $timedate, $current_user;

$sql_retrive = "SELECT
ac.id, ac.name
FROM
alpha_classroom ac
INNER JOIN
meetings m ON ac.sso_code = m.sso_code
AND m.deleted = 0
AND m.ju_class_id = '$class_id'
AND CONVERT( DATE_ADD(m.date_end, INTERVAL -161 HOUR) , DATE) <= CURDATE()";
$re_id = $GLOBALS['db']->fetchArray($sql_retrive);
require_once('custom/include/_helper/RetrieveRecord.php');
$auth_key = "?username=webs&password=GHhNJ5=26";
$param = array(
    'X_API_KEY: 374cb2ebfe74bd4fec17d0dffb1023c6c4676c3a35a93d20f830ea56ade0039a',
    'Accept: application/json', );
$err_lesson = array();
foreach($re_id as $key=>$value){
    $result = retrieve($value['id'], $auth_key, $param);
    if($result->success == 0)
        $err_lesson[] = $value['name'];
}     
$html = '';
if(!empty($err_lesson)){
    $html .= "Retrieve record Error on Lesson: ".implode(",", $err_lesson).".<br>Please contact administrator to solve it.<br><br>";
}
$html = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html .= '<tr height="20"><th scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Student Name</th>';
$html .= '<th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Lesson</th>';
$html .= '<th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Title</th>';
$html .= '<th scope="col" align="center" colspan="4" class="reportlistViewMatrixThS1" valign="middle" wrap="">LMS Score</th></tr>';

$html .= '<tr height="20"><th scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap=""></th>';
$html .= '<th scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap=""></th>';
$html .= '<th scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap=""></th>';
$html .= '<th scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Grammar</th>';
$html .= '<th scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Reading</th>';
$html .= '<th scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Listening</th>';
$html .= '<th scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Speaking</th></tr>';
/*
$q1 = "SELECT
m.ju_class_id class_id,
s.sis_student_id student_id,
c.full_student_name student_name,
ac.name lesson_name,
ls.title RE_title,
CASE (ls.skill)
WHEN 'Grammar' THEN ls.score
ELSE 0
END 'Grammar',
CASE (ls.skill)
WHEN 'Reading' THEN ls.score
ELSE 0
END 'Reading',
CASE (ls.skill)
WHEN 'Listening' THEN ls.score
ELSE 0
END 'Listening',
CASE (ls.skill)
WHEN 'Speaking' THEN ls.score
ELSE 0
END 'Speaking'
FROM
(SELECT
MAX(score) score,
MAX(passed) passed,
skill,
level,
title,
alpha_student_id,
alpha_classroom_id
FROM
alpha_lessons
GROUP BY alpha_student_id , alpha_classroom_id , unit_id) ls
INNER JOIN
alpha_students s ON s.alpha_student_id = ls.alpha_student_id
AND ls.alpha_classroom_id = s.alpha_classroom_id
INNER JOIN
alpha_classroom ac ON ac.alpha_classroom_id = ls.alpha_classroom_id
INNER JOIN
meetings m ON m.deleted = 0
AND m.sso_code = ac.sso_code
AND m.id = s.session_id
$ext_class
INNER JOIN contacts c on c.id = s.sis_student_id AND c.deleted = 0 $ext_student
GROUP BY s.sis_student_id , ac.sso_code , ls.title
ORDER BY class_id , student_id , CASE
WHEN ac.name LIKE 'Beginner%' THEN 0
WHEN ac.name LIKE 'Elementary%' THEN 1
WHEN ac.name LIKE 'Pre-intermediate%' THEN 2
WHEN ac.name LIKE 'Intermediate%' THEN 3
WHEN ac.name LIKE 'Upper-intermediate%' THEN 4
ELSE 5
END ASC , lesson_name";*/
$q2 = "SELECT
m.ju_class_id class_id,
cc.j_class_contacts_1contacts_idb student_id,
c.full_student_name student_name,
ac.name classroom_name,
al.lesson_name RE_title,
CASE (ls.skill)
WHEN 'Grammar' THEN ls.score
ELSE ''
END 'Grammar',
CASE (ls.skill)
WHEN 'Reading' THEN ls.score
ELSE ''
END 'Reading',
CASE (ls.skill)
WHEN 'Listening' THEN ls.score
ELSE ''
END 'Listening',
CASE (ls.skill)
WHEN 'Speaking' THEN ls.score
ELSE ''
END 'Speaking'
FROM
alpha_lessonname al
INNER JOIN
alpha_classroom ac ON al.alpha_classroom_id = ac.id
INNER JOIN
meetings m ON ac.sso_code = m.sso_code AND m.deleted = 0
$ext_class
AND convert(date_add(m.date_start, interval -161 hour), date) <= CURDATE()
INNER JOIN
j_class_contacts_1_c cc ON cc.j_class_contacts_1j_class_ida = m.ju_class_id
AND cc.deleted = 0
inner join contacts c on c.id = cc.j_class_contacts_1contacts_idb
AND c.deleted = 0
$ext_student
LEFT JOIN
(SELECT
MAX(alpha_lessons.score) score,
MAX(alpha_lessons.passed) passed,
alpha_lessons.skill,
alpha_lessons.level,
alpha_lessons.title,
alpha_lessons.alpha_student_id,
alpha_lessons.alpha_classroom_id,
alpha_students.sis_student_id
FROM
alpha_lessons
INNER JOIN alpha_students ON alpha_lessons.alpha_student_id = alpha_students.alpha_student_id
WHERE
IFNULL(alpha_students.sis_student_id, '') <> ''
GROUP BY alpha_student_id , alpha_classroom_id , unit_id) ls ON ls.alpha_classroom_id = ac.alpha_classroom_id
AND ls.title = al.lesson_name
AND ls.sis_student_id = cc.j_class_contacts_1contacts_idb
GROUP BY student_id , classroom_name , lesson_name
ORDER BY class_id , student_id , CASE
WHEN ac.name LIKE 'Beginner%' THEN 0
WHEN ac.name LIKE 'Elementary%' THEN 1
WHEN ac.name LIKE 'Pre-intermediate%' THEN 2
WHEN ac.name LIKE 'Intermediate%' THEN 3
WHEN ac.name LIKE 'Upper-intermediate%' THEN 4
ELSE 5
END ASC , ac.sis_lesson_number";
$rows = $GLOBALS['db']->fetchArray($q2);
$student_row_span = array();

foreach($rows as $key=>$row){
    $student_row_span[$row['student_id']]['total']+=1;
    $student_row_span[$row['student_id']][$row['classroom_name']]+=1;
    $student_row_span[$row['student_id']]['total_grammar']+= $row['Grammar'];
    if((float)$row['Grammar'] > 0)
        $student_row_span[$row['student_id']]['count_grammar']+=1;
    $student_row_span[$row['student_id']]['total_reading']+= $row['Reading'];
    if((float)$row['Reading'] > 0)
        $student_row_span[$row['student_id']]['count_reading']+=1;
    $student_row_span[$row['student_id']]['total_listening']+= $row['Listening'];
    if((float)$row['Listening'] > 0)
        $student_row_span[$row['student_id']]['count_listening']+=1;
    $student_row_span[$row['student_id']]['total_speaking']+= $row['Speaking'];
    if((float)$row['Speaking'] > 0)
        $student_row_span[$row['student_id']]['count_speaking']+=1;
}

if(!empty($rows)){
    $fr = reset($rows);
    $student_id = $fr['student_id'];
    $lesson_name = $fr['classroom_name'];
    $title_name = $fr['RE_title'];

    $student_row_span[$student_id]['total'] +=1;
    //    $html .= '<tr height="20"><td rowspan = "'.$student_row_span[$student_id]['total'].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""  ><b>'.$fr['student_name'].'</b></td>';
    $html .= '<tr height="20"><td rowspan = "'.$student_row_span[$student_id]['total'].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""  ><b>'."<a href='index.php?module=Contacts&action=DetailView&record={$fr['student_id']}' target='_blank'>{$fr['student_name']}</a>".'</b></td>';
    $html .= '<td rowspan = "'.$student_row_span[$student_id][$lesson_name].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$lesson_name.'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$title_name.'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$fr['Grammar'].'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$fr['Reading'].'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$fr['Listening'].'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$fr['Speaking'].'</td>';
}
//EXPORT HTML

foreach( $rows as $key => $row){
    if($student_id == $row['student_id']){
        if($lesson_name == $row['classroom_name']){
            if($title_name !== $row['RE_title']){
                $title_name = $row['RE_title'];
                $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$title_name.'</td>';
                $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['Grammar'].'</td>';
                $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['Reading'].'</td>';
                $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['Listening'].'</td>';
                $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['Speaking'].'</td>';
            }
        }else{
            $lesson_name = $row['classroom_name'];
            $title_name = $row['RE_title'];
            $html .= '<td rowspan = "'.$student_row_span[$student_id][$lesson_name].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$lesson_name.'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$title_name.'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['Grammar'].'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['Reading'].'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['Listening'].'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['Speaking'].'</td>';
        }
    }else{
        $html.='</tr>';
        $html.='<tr height="20"><td colspan = "2" scope="col" align="center" class="reportlistViewMatrixRightEmptyData" valign="middle" wrap="">Average Score</td>';

        $html .= '<td scope="col" align="center" class="reportlistViewMatrixRightEmptyData" valign="middle" wrap="">'.format_number($student_row_span[$student_id]['total_grammar']/$student_row_span[$student_id]['count_grammar'],2,2).'</td>';
        $html .= '<td scope="col" align="center" class="reportlistViewMatrixRightEmptyData" valign="middle" wrap="">'.format_number($student_row_span[$student_id]['total_reading']/$student_row_span[$student_id]['count_reading'],2,2).'</td>';
        $html .= '<td scope="col" align="center" class="reportlistViewMatrixRightEmptyData" valign="middle" wrap="">'.format_number($student_row_span[$student_id]['total_listening']/$student_row_span[$student_id]['count_listening'],2,2).'</td>';
        $html .= '<td scope="col" align="center" class="reportlistViewMatrixRightEmptyData" valign="middle" wrap="">'.format_number($student_row_span[$student_id]['total_speaking']/$student_row_span[$student_id]['count_speaking'],2,2).'</td></tr>';
        $student_id = $row['student_id'];
        $lesson_name = $row['classroom_name'];
        $title_name = $row['RE_title'];
        $student_row_span[$student_id]['total'] +=1;
        //        $html .= '<tr height="20"><td rowspan = "'.$student_row_span[$student_id]['total'].'" scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap=""><b>'.$row['student_name'].'</b></td>';
        $html .= '<tr height="20"><td rowspan = "'.$student_row_span[$student_id]['total'].'" scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap=""><b>'."<a href='index.php?module=Contacts&action=DetailView&record={$row['student_id']}' target='_blank'>{$row['student_name']}</a>".'</b></td>';
        $html .= '<td rowspan = "'.$student_row_span[$student_id][$lesson_name].'" scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">'.$lesson_name.'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$title_name.'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['Grammar'].'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['Reading'].'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['Listening'].'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['Speaking'].'</td>';

    }
    $html.='</tr>';
}

$html.='<tr height="20"><td colspan = "2" scope="col" align="center" class="reportlistViewMatrixRightEmptyData" valign="middle" wrap="">Average Score</td>';
$html .= '<td scope="col" align="center" class="reportlistViewMatrixRightEmptyData" valign="middle" wrap="">'.format_number($student_row_span[$student_id]['total_grammar']/$student_row_span[$student_id]['count_grammar'],2,2).'</td>';
$html .= '<td scope="col" align="center" class="reportlistViewMatrixRightEmptyData" valign="middle" wrap="">'.format_number($student_row_span[$student_id]['total_reading']/$student_row_span[$student_id]['count_reading'],2,2).'</td>';
$html .= '<td scope="col" align="center" class="reportlistViewMatrixRightEmptyData" valign="middle" wrap="">'.format_number($student_row_span[$student_id]['total_listening']/$student_row_span[$student_id]['count_listening'],2,2).'</td>';
$html .= '<td scope="col" align="center" class="reportlistViewMatrixRightEmptyData" valign="middle" wrap="">'.format_number($student_row_span[$student_id]['total_speaking']/$student_row_span[$student_id]['count_speaking'],2,2).'</td></tr>';
$html .= "
</tbody></table>";


$html .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="formHeader h3Row">
<tbody><tr>
<td nowrap=""></td><td width="100%"><img height="1" width="1" src="themes/default/images/blank.gif" alt=""></td></tr>
</tbody></table>';

echo $html;

?>