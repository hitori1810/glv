<?php
//FIX 108

require_once("custom/include/_helper/junior_revenue_utils.php");
require_once("custom/include/_helper/junior_class_utils.php");

$q1 = "SELECT DISTINCT
IFNULL(j_studentsituations.id, '') situation_id,
IFNULL(j_studentsituations.ju_class_id, '') class_id,
IFNULL(j_studentsituations.student_id, '') student_id,
IFNULL(j_studentsituations.name, '') situation_name,
j_studentsituations.start_study start_study,
j_studentsituations.end_study end_study,
j_studentsituations.total_amount total_amount,
j_studentsituations.start_hour start_hour,
j_studentsituations.type type,
j_studentsituations.total_hour total_hour
FROM
    j_studentsituations
WHERE
    ABS(start_hour) LIKE '%.05'
        AND deleted = 0
ORDER BY team_id , date_entered DESC";
$rs1 = $GLOBALS['db']->query($q1);
$count = 0;
$count_2 = 0;
$html1 = '';
$html2 = '';


while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
    $ses = get_list_lesson_by_situation($row['class_id'], $row['situation_id'], '', '', 'INNER');
    $cr_ss = '***';
    $cr_hour = 0;
    $cr_lesson = $ses[0]['lesson_number'];
    $flag_lesson = true;
    $cr_lesson_wrong = array();
    for($i = 0; $i < count($ses); $i++){
        $cr_ss = $ses[$i]['situation_id'];
        if(!empty($cr_ss))
            $cr_hour += $ses[$i]['delivery_hour'];

        if($cr_lesson != $ses[$i]['lesson_number']){
            $flag_lesson = false;
            $cr_lesson_wrong[] = $ses[$i]['lesson_number'];
        }
        $cr_lesson++;
    }

    //Dinh dang truong hop
    if( $row['total_hour'] != $cr_hour ){
        $count++;
        $html1.= "<a href='index.php?module=Contacts&action=DetailView&record={$row['student_id']}'>  {$row['situation_name']} </a>  <br>Tổng số giờ Enroll: {$row['total_hour']} <br>Tổng giờ tính revenue: = $cr_hour </b><br><br>";

    }
//    if(!$flag_lesson){
//        $first = reset($ses);
//        $date_first = $timedate->to_display_date(date('Y-m-d',strtotime("+7 hours ".$first['date_start'])), false);
//
//        $html2 .=  "<a href='index.php?module=Contacts&action=DetailView&record={$row['student_id']}'>  {$row['situation_name']} </a> Từ ngày: $date_first. Thứ tự bị add sai từ Session thứ {$cr_lesson_wrong[0]}<br>";
//        $count_2++;
//
//    }

}
if($count> 0){
    echo "<b>Trường hợp 1: Tổng số giờ Enroll khác Tổng số giờ Revenue</b><br>";
    echo $html1;
    echo '----------<br>';
}
//if($count_2 > 0){
//    echo "<b>Trường hợp 2: Các buổi Enroll không theo thứ tự</b><br>";
//    echo $html2;
//    echo '----------<br>';
//}
$ee =  $count;

echo "<b>$ee wrong issue.</b>";
