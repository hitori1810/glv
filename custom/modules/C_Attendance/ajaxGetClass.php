<?php
    require_once('custom/modules/C_Attendance/_helperdatetime.php');
    $temp = array();
    $class_array = array();
    if($_POST["ac_for"]=="today")
        $temp = getcurrentday();
    else
        if($_POST["ac_for"]=="this_week")
            $temp = getweek();
        else
        {
            if($_POST["ac_for"]=="this_month")
                $temp = getmonth();
            else 
            {
                $temp = getcmonth($_POST['select_month']);
            }
    }
    if($temp)
    {
        $date_start_temp = reset(array_keys($temp));
        $date_end_temp = end(array_keys($temp));
        $sql = "
        SELECT class_id  
        FROM meetings
        WHERE team_id='{$GLOBALS['current_user']->team_id}' AND deleted = 0 AND LEFT( date_start, 10 ) between '{$date_start_temp}' and '{$date_end_temp}' 
        GROUP BY class_id";
        $result = $GLOBALS['db']->query($sql);
        
        $select = "<select id='class_id' style='min-width:300px;' name='class_id'>";
        while($row = $GLOBALS['db']->fetchByAssoc($result))
        {
            $sql_1 = "
            SELECT name 
            FROM c_classes
            WHERE deleted = 0 AND id = '{$row['class_id']}'
            ";
            
            $class_name = $GLOBALS['db']->getOne($sql_1);
            $select .= "<option value = '{$row['class_id']}'>".$class_name."</option>";
        }
        $select .= "</select>";         
    }
    $js = '
    <script type="text/javascript">        
        $(document).ready(function() {
                  $("#class_id").select2({placeholder: "Select Class",allowClear: true});
        });
    </script>
    ';
    echo json_encode(array(
        "success" => "1",
        "select" => $select.$js,
    ));
?>
