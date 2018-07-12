<?php
  $q1 = "SELECT id, reports_to_id FROM users WHERE reports_to_id <> null OR  reports_to_id <> '' AND deleted = 0;";
    $rs1 = $GLOBALS['db']->query($q1);
    $count = 0;
    while($row1 = $GLOBALS['db']->fetchByAssoc($rs1)){
        $user = BeanFactory::getBean('Users',$row1['id']);
        $user->reports_to_id = '';
        $user->reports_to_name = '';
        $user->save();
        $count++;
    }
    echo "<b>Updated $count Users</b><br><br>";
?>
