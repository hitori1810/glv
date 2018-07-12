<?php
$today = date('Y-m-d');

//UPDATE In Progress - Junior
$q1 = "SELECT DISTINCT
IFNULL(contacts.id, '') primaryid
FROM
contacts
INNER JOIN
j_studentsituations l1 ON contacts.id = l1.student_id
AND l1.deleted = 0
INNER JOIN j_class cl ON l1.ju_class_id = cl.id
AND cl.deleted = 0
AND cl.class_type = 'Normal Class'
INNER JOIN
teams l2 ON contacts.team_id = l2.id
AND l2.deleted = 0
WHERE
(((l1.start_study <= '$today')
AND (l1.end_study > '$today')
AND (l1.type IN ('Enrolled','Settle','Moving In'))))
AND contacts.deleted = 0";
$row1 = $GLOBALS['db']->fetchArray($q1);
if(!empty($row1)){
    $u1 = "UPDATE contacts SET contact_status = 'In Progress' WHERE id IN ('".implode("','",array_column($row1, 'primaryid'))."')";
    $GLOBALS['db']->query($u1);
}

//UPDATE OutStanding
$q2 = "SELECT DISTINCT
IFNULL(contacts.id, '') primaryid
FROM
contacts
INNER JOIN
j_studentsituations l1 ON contacts.id = l1.student_id
AND l1.deleted = 0
WHERE
(((l1.start_study <= '$today')
AND (l1.end_study > '$today')
AND (l1.type IN ('OutStanding'))
AND contacts.contact_status NOT IN ('In Progress')))
AND contacts.deleted = 0";
$row2 = $GLOBALS['db']->fetchArray($q2);
if(!empty($row2)){
    $u2 = "UPDATE contacts SET contact_status = 'OutStanding' WHERE id IN ('".implode("','",array_column($row2, 'primaryid'))."')";
    $GLOBALS['db']->query($u2);
}

//UPDATE Delayed - Not In Progress và outstanding ở trên
$q3 = "SELECT DISTINCT
IFNULL(contacts.id, '') primaryid
FROM
contacts
INNER JOIN
j_studentsituations l1 ON contacts.id = l1.student_id
AND l1.deleted = 0
WHERE
(((l1.start_study <= '$today')
AND (l1.end_study > '$today')
AND (l1.type IN ('Delayed','Stopped'))
AND contacts.id NOT IN ('".implode("','",array_column($row1, 'primaryid'))."','".implode("','",array_column($row2, 'primaryid'))."')))
AND contacts.deleted = 0";
$row3 = $GLOBALS['db']->fetchArray($q3);
if(!empty($row3)){
    $u3 = "UPDATE contacts SET contact_status = 'Delayed' WHERE id IN ('".implode("','",array_column($row3, 'primaryid'))."')";
    $GLOBALS['db']->query($u3);
}

//UPDATE Finished
$q4 = "SELECT DISTINCT
IFNULL(st.id, '') primaryid
FROM
contacts st
INNER JOIN
j_studentsituations ss ON ss.student_id = st.id AND ss.deleted = 0
AND st.deleted = 0
AND st.contact_status = 'In Progress'
INNER JOIN j_class cl ON ss.ju_class_id = cl.id
AND cl.deleted = 0
AND cl.class_type = 'Normal Class'
GROUP BY st.id
HAVING MAX(ss.end_study) <= '$today'";

$row4 = $GLOBALS['db']->fetchArray($q4);
if(!empty($row4)){
    $u4 = "UPDATE contacts SET contact_status = 'Finished' WHERE id IN ('".implode("','",array_column($row4, 'primaryid'))."')";
    $GLOBALS['db']->query($u4);
}
echo 'updated !!';
?>