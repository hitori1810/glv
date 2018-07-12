<?php
function calDeliveryJunior($start , $end , $team_id = '', $team_one_of_id = '', $student_id = '', $class_id = '', $type = 'Normal', $status = '', $payment_id = ''){
    require_once("custom/include/_helper/junior_revenue_utils.php");

    global $timedate;
    $cr_user_id = $GLOBALS['current_user']->id;

    $start_display     = $timedate->to_display_date($start,false);
    $end_display     = $timedate->to_display_date($end,false);

    if(empty($cr_user_id))
        $cr_user_id = '1';

    $situation_type = str_replace('MovingIn','Moving In',$type); // Fix bug khoảng trắng "Moving In" ko chạy đc báo cáo Revenue
    if($type == 'Normal' || empty($situation_type))
        $situation_type = "'Enrolled','Moving In','Stopped'"; //Chú ý điều kiện Stopped là TH để Drop doanh thu

    //Xoa Du lieu
    $q1 = "DELETE FROM c_deliveryrevenue WHERE created_by = '$cr_user_id' AND passed = 1";
    $GLOBALS['db']->query($q1);
    $arr_team_id = array($team_id);
    if(empty($team_id) && !empty($team_one_of_id))
        $arr_team_id = explode(",", $team_one_of_id);

    //Adjust Is One Of Team
    foreach($arr_team_id as $team_id){
        $team_id = str_replace("'","",$team_id);
        $team_type = $GLOBALS['db']->getOne("SELECT DISTINCT
            l1.team_type team_type
            FROM
            saved_reports
            INNER JOIN
            teams l1 ON saved_reports.team_id = l1.id
            AND l1.deleted = 0
            WHERE
            (((saved_reports.id = '{$_POST['record']}')))
            AND saved_reports.deleted = 0");
        if($team_type == 'Adult'){
            $rs = get_list_revenue_adult($student_id, $start, $end, $team_id);
            for($i=0; $i<count($rs);$i+=500){
                $output = array_slice($rs, $i, min(500, count($rs)-$i));
                $arr_add_row = array();
                foreach($output as $key => $value){
                    $arr_add_row[] = "('".create_guid()."',
                    'Hoc phi theo ngay Adult',
                    '{$value['student_id']}',
                    '{$value['delivery_day']}',
                    '{$value['delivery_revenue']}',
                    '{$value['date_start']}',
                    '{$value['cost_per_day']}',
                    1,
                    '{$value['team_id']}',
                    '{$value['team_id']}',
                    '1',
                    '$cr_user_id',
                    0,
                    'Adult',
                    '{$value['payment_id']}')";
                }
                $add_row = "INSERT INTO c_deliveryrevenue (id, name, student_id, duration, amount, date_input, cost_per_hour, passed, team_id, team_set_id, assigned_user_id,created_by, deleted, type, ju_payment_id)
                VALUES ".implode(",",$arr_add_row);
                $GLOBALS['db']->query($add_row);
            }
        }else{
            $rs = get_list_revenue($student_id, $situation_type, $start_display, $end_display, $class_id, '', $team_id, $payment_id , false, $status);
            for($i=0; $i<count($rs);$i+=500){
                $output = array_slice($rs, $i, min(500, count($rs)-$i));
                $arr_add_row = array();
                foreach($output as $key => $value){
                    $arr_add_row[] = "('".create_guid()."',
                    'Hoc phi theo gio Junior',
                    '{$value['student_id']}',
                    '{$value['delivery_hour']}',
                    '{$value['delivery_revenue']}',
                    '".date('Y-m-d',strtotime('+ 7hour '.$value['date_start']))."',
                    '{$value['cost_per_hour']}',
                    '{$value['primaryid']}',
                    1,
                    '{$value['team_id']}',
                    '{$value['team_id']}',
                    '1',
                    '$cr_user_id',
                    0,
                    'Junior',
                    '{$value['situation_type']}',
                    '{$value['revenue_status']}',
                    '{$value['situation_id']}',
                    '{$value['ju_payment_id']}')";
                }
                $add_row =  "INSERT INTO c_deliveryrevenue (id, name, student_id, duration, amount, date_input, cost_per_hour, session_id, passed, team_id, team_set_id, assigned_user_id, created_by, deleted, type, revenue_type, status, situation_id, ju_payment_id
                ) VALUES ".implode(",",$arr_add_row);
                $GLOBALS['db']->query($add_row);
            }
        }
    }
}
