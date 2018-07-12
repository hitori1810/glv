<?php
    require_once("custom/modules/c_Timekeeping/Timekeeping.php");
    $filter = $this->where;
    $parts = explode("AND", $filter);
    if(count($parts) >= 3){
        $start_date = get_string_between($parts[0],"'","'");
        $end_date    = get_string_between($parts[1],"'","'");
        $team_id    = get_string_between($parts[2],"'","'");
    }

    calTimekeeping($start_date , $end_date , $team_id);

?>