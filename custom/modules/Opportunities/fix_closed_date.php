<?php
    global $timedate;
    $q1 = "SELECT * FROM opportunities WHERE deleted = 0 AND sales_stage='Success'";
    $rs1 = $GLOBALS['db']->query($q1);
    $count = 0;
    while($row1 = $GLOBALS['db']->fetchByAssoc($rs1)){
        $date_modified = $timedate->to_display_date_time($row1['date_modified']);  
        $saledate = $timedate->to_db_date(substr($date_modified,0,10),false);
        if($saledate != $row1['date_closed']){
            $q2 = "UPDATE opportunities SET date_closed='$saledate' WHERE id='{$row1['id']}'";
            $GLOBALS['db']->query($q2);
            $count++;    
        }
    }
    echo "<b>Updated $count</b>";
?>
