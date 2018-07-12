<?php
    global $timedate;
    $q1 = "SELECT * FROM c_teachers WHERE deleted = 0";
    $rs1 = $GLOBALS['db']->query($q1);
    $count = 0;
    while($row1 = $GLOBALS['db']->fetchByAssoc($rs1)){
        
      $q2 = "UPDATE c_teachers SET full_teacher_name='".$row1['first_name'].' '.$row1['last_name']."' WHERE id='{$row1['id']}'";
      $GLOBALS['db']->query($q2);
      $count++;
    }
    echo "<b>Updated $count</b>";
?>
