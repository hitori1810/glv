<?php
$q1 = "SELECT DISTINCT
IFNULL(j_studentsituations.id, '') primary_id,
IFNULL(l2.id, '') assigned_user_id
FROM
j_studentsituations
INNER JOIN
j_payment l1 ON j_studentsituations.payment_id = l1.id
AND l1.deleted = 0
INNER JOIN
users l2 ON l1.assigned_user_id = l2.id
AND l2.deleted = 0
WHERE
((1 = 1))
AND j_studentsituations.deleted = 0";
$rs1 = $GLOBALS['db']->query($q1);
$count = 0;
while($row1 = $GLOBALS['db']->fetchByAssoc($rs1)){
    $q3 = "UPDATE j_studentsituations SET assigned_user_id = {$row1['assigned_user_id']} WHERE id='{$row1['primary_id']}'";
    $GLOBALS['db']->query($q3);    
    $count++;
}
echo "<b>Updated $count Situation</b><br><br>";
?>
