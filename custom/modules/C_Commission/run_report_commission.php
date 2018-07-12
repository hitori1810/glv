<?php
    require_once("custom/modules/C_Commission/Commission.php");
    $filter = $this->where;
    $parts = explode("AND", $filter);
    if(count($parts) >= 2){
        $start_date = get_string_between($parts[0],"'","'");
        $end_date    = get_string_between($parts[1],"'","'");
    }

    calCommission($start_date , $end_date);


?>