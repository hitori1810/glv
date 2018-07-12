<?php

$rs1 = $GLOBALS['db']->query($this->query);
while($row1 = $GLOBALS['db']->fetchByAssoc($rs1)){
    $situa_id = $row1['primaryid'];
    $sql = "SELECT DISTINCT
    IFNULL(j_studentsituations.id, '') primaryid,
    IFNULL(j_studentsituations.name, '') j_studentsituations_name,
    IFNULL(j_studentsituations.student_type, '') student_type,
    IFNULL(l1.id, '') l1_id,
    CONCAT(IFNULL(l1.last_name, ''),
    ' ',
    IFNULL(l1.first_name, '')) l1_full_name,
    l1.phone_mobile l1_phone_mobile,
    IFNULL(l2.id, '') l2_id,
    l2.email_address l2_email_address,
    l1.status l1_status,
    l1.birthdate l1_birthdate,
    IFNULL(l3.id, '') l3_id,
    l3.contact_id l3_contact_id,
    CONCAT(IFNULL(l3.last_name, ''),
    ' ',
    IFNULL(l3.first_name, '')) l3_full_name,
    l3.phone_mobile l3_phone_mobile,
    IFNULL(l4.id, '') l4_id,
    l4.email_address l4_email_address,
    l3.contact_status l3_contact_status,
    l3.birthdate l3_birthdate
    FROM
    j_studentsituations
    LEFT JOIN
    leads l1 ON j_studentsituations.lead_id = l1.id
    AND l1.deleted = 0
    LEFT JOIN
    email_addr_bean_rel l2_1 ON l1.id = l2_1.bean_id
    AND l2_1.deleted = 0
    AND l2_1.primary_address = '1'
    LEFT JOIN
    email_addresses l2 ON l2.id = l2_1.email_address_id
    AND l2.deleted = 0
    LEFT JOIN
    contacts l3 ON j_studentsituations.student_id = l3.id
    AND l3.deleted = 0
    LEFT JOIN
    email_addr_bean_rel l4_1 ON l3.id = l4_1.bean_id
    AND l4_1.deleted = 0
    AND l4_1.primary_address = '1'
    LEFT JOIN
    email_addresses l4 ON l4.id = l4_1.email_address_id
    AND l4.deleted = 0
    WHERE
    (((j_studentsituations.id = '$situa_id')))
    AND j_studentsituations.deleted = 0";
    $rs2 = $GLOBALS['db']->query($sql);
    $row = $GLOBALS['db']->fetchByAssoc($rs2);
    if($row['student_type'] == 'Student'){
        $student_name   = $row['l3_full_name'];
        $student_id     = $row['l3_contact_id'];
        $birthdate      = $row['l3_birthdate'];
        $mobile         = $row['l3_phone_mobile'];
        $email          = $row['l4_email_address'];
        $status         = $row['l3_contact_status'];
    }elseif($row['student_type'] == 'Lead'){
        $student_name   = $row['l1_full_name'];
        $student_id     = '';
        $birthdate      = $row['l1_birthdate'];
        $mobile         = $row['l1_phone_mobile'];
        $email          = $row['l2_email_address'];
        $status         = $row['l1_status'];  
    }

    $GLOBALS['db']->query("UPDATE j_studentsituations SET student_id_fake='$student_id', name='$student_name', birthdate_fake='$birthdate', status_fake='$status', email_fake='$email', phone_situation='$mobile' WHERE id='$situa_id'");
}
?>