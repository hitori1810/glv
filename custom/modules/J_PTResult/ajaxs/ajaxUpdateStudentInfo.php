<?php
    //echo 
    $rs1 = $GLOBALS['db']->query($this->query);
    $ids = array();
    while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
        $ids[] = $row['primaryid'];
    }

    $sql_update = "
    UPDATE j_ptresult pt 
    LEFT JOIN leads l ON l.id = pt.student_id AND l.deleted = 0
    LEFT JOIN contacts c ON pt.student_id = c.id AND c.deleted = 0
    LEFT JOIN email_addr_bean_rel er ON er.deleted = 0 AND er.primary_address = '1'
    AND er.bean_module = pt.parent AND er.bean_id = pt.student_id
    LEFT JOIN email_addresses em ON em.id = er.email_address_id AND em.deleted = 0
    SET 
    pt.student_name = (CASE pt.parent 
    WHEN 'Leads' THEN TRIM(CONCAT(IFNULL(l.last_name,''),' ',IFNULL(l.first_name,'')))
    WHEN 'Contacts' THEN TRIM(CONCAT(IFNULL(c.last_name,''),' ',IFNULL(c.first_name,'')))
    ELSE '' END),
    pt.student_birthdate = (CASE pt.parent 
    WHEN 'Leads' THEN IFNULL(l.birthdate,NULL)
    WHEN 'Contacts' THEN IFNULL(c.birthdate,NULL)
    ELSE NULL END),
    pt.student_mobile = (CASE pt.parent 
    WHEN 'Leads' THEN IFNULL(l.phone_mobile,'')
    WHEN 'Contacts' THEN IFNULL(c.phone_mobile,'')
    ELSE '' END),
    pt.student_status = (CASE pt.parent 
    WHEN 'Leads' THEN IFNULL(l.status,'')
    WHEN 'Contacts' THEN IFNULL(c.contact_status,'')
    ELSE '' END),
    pt.student_gender = (CASE pt.parent 
    WHEN 'Leads' THEN IFNULL(l.gender,'')
    WHEN 'Contacts' THEN IFNULL(c.gender,'')
    ELSE '' END),
    pt.student_id = (CASE pt.parent 
    WHEN 'Leads' THEN IFNULL(l.id,'')
    WHEN 'Contacts' THEN IFNULL(c.id,'')
    ELSE '' END),
    pt.student_email = em.email_address
    WHERE pt.id IN ('".implode("','",$ids)."') ";
    $GLOBALS['db']->query($sql_update);    
?>