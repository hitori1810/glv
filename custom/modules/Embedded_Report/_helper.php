<?php

    function getListEnquiries($start, $end, $sql_team ){
        // Get list target
        $q1 = "SELECT DISTINCT
        IFNULL(prospects.id, '') id,
        CONCAT(IFNULL(prospects.last_name, ''),' ',IFNULL(prospects.first_name, '')) full_name,
        prospects.date_entered date_entered,
        IFNULL(prospects.age, '') age,
        l3.email_address email_address,
        IFNULL(prospects.primary_address_street,'') primary_address_street,
        IFNULL(prospects.guardian_name, '') guardian_name,
        IFNULL(prospects.phone_mobile, '') phone_mobile,
        IFNULL(prospects.lead_source, '') lead_source,
        IFNULL(l5. NAME, '') campaign_name,
        IFNULL(l5.id, '') campaign_id,
        CONCAT(IFNULL(l2.last_name, ''),' ',IFNULL(l2.first_name, '')) full_name_EC,
        IFNULL(prospects. STATUS, '') status,
        l1.short_name short_name,
        prospects.lead_id lead_id
        FROM
        prospects
        INNER JOIN teams l1 ON prospects.team_id = l1.id
        AND l1.deleted = 0
        INNER JOIN users l2 ON prospects.assigned_user_id = l2.id
        AND l2.deleted = 0
        LEFT JOIN email_addr_bean_rel l3_1 ON prospects.id = l3_1.bean_id
        AND l3_1.deleted = 0
        AND l3_1.primary_address = '1'
        LEFT JOIN email_addresses l3 ON l3.id = l3_1.email_address_id
        AND l3.deleted = 0
        LEFT JOIN campaigns l5 ON prospects.campaign_id = l5.id
        AND l5.deleted = 0
        WHERE((((
        prospects.date_entered >= '{$start}'
        AND prospects.date_entered <= '{$end}'))
        $sql_team ))
        AND prospects.deleted = 0";

        $target_list = $GLOBALS['db']->fetchArray($q1);
        foreach ($target_list as $key => $value){
            if(!empty($value['lead_id'])){
                unset($target_list[$key]);
            }
        }

        $q2 = "SELECT DISTINCT
        IFNULL(leads.id, '') id,
        CONCAT(
        IFNULL(leads.last_name, ''),' ',IFNULL(leads.first_name, '')) full_name,
        leads.date_entered date_entered,
        IFNULL(leads.primary_address_street,'') primary_address_street,
        IFNULL(leads.guardian_name, '') guardian_name,
        l4.email_address email_address,
        IFNULL(leads.phone_mobile, '') phone_mobile,
        IFNULL(leads.lead_source, '') lead_source,
        IFNULL(l2.`name`, '') campaign_name,
        IFNULL(l2.id, '') campaign_id,
        CONCAT(IFNULL(l3.last_name, ''),' ',IFNULL(l3.first_name, '')) full_name_EC,
        IFNULL(leads. STATUS, '') status,
        IFNULL(leads.age, '') age,
        l1.short_name short_name,
        IFNULL(leads.contact_id, '') contact_id_lead
        FROM
        leads
        INNER JOIN teams l1 ON leads.team_id = l1.id
        AND l1.deleted = 0
        LEFT JOIN campaigns l2 ON leads.campaign_id = l2.id
        AND l2.deleted = 0
        INNER JOIN users l3 ON leads.assigned_user_id = l3.id
        AND l3.deleted = 0
        LEFT JOIN email_addr_bean_rel l4_1 ON leads.id = l4_1.bean_id
        AND l4_1.deleted = 0
        AND l4_1.primary_address = '1'
        LEFT JOIN email_addresses l4 ON l4.id = l4_1.email_address_id
        AND l4.deleted = 0
        WHERE((((
        leads.date_entered >= '{$start}'
        AND leads.date_entered <= '{$end}'))
        $sql_team))
        AND leads.deleted = 0";

        $lead_list = $GLOBALS['db']->fetchArray($q2);
        foreach ($lead_list as $key => $value){
            if(!empty($value['contact_id_lead'])){
                unset($lead_list[$key]);
            }
        }

        $q3 = "SELECT DISTINCT
        IFNULL(contacts.id, '') id,
        CONCAT(IFNULL(contacts.last_name, ''),' ',IFNULL(contacts.first_name, '')) full_name,
        contacts.date_entered date_entered,
        IFNULL(contacts.primary_address_street,'') primary_address_street,
        IFNULL(contacts.guardian_name, '') guardian_name,
        l4.email_address email_address,
        IFNULL(contacts.phone_mobile, '') phone_mobile,
        IFNULL(contacts.lead_source, '') lead_source,
        contact_status status,
        IFNULL(l3. NAME, '') campaigns_name,
        IFNULL(l3.id, '') campaigns_id,
        CONCAT(IFNULL(l2.last_name, ''),' ',IFNULL(l2.first_name, '')) full_name_EC,
        IFNULL(contacts.age, '') age,
        l1.short_name short_name,
        preferred_kind_of_course,
        IFNULL(contacts.contact_id, '') contact_id
        FROM
        contacts
        LEFT JOIN campaigns l3 ON contacts.campaign_id = l3.id
        AND l3.deleted = 0
        INNER JOIN users l2 ON contacts.assigned_user_id = l2.id
        AND l2.deleted = 0
        INNER JOIN teams l1 ON contacts.team_id = l1.id
        AND l1.deleted = 0
        LEFT JOIN email_addr_bean_rel l4_1 ON contacts.id = l4_1.bean_id
        AND l4_1.deleted = 0
        AND l4_1.primary_address = '1'
        LEFT JOIN email_addresses l4 ON l4.id = l4_1.email_address_id
        AND l4.deleted = 0
        WHERE(((( contacts.date_entered >= '{$start}'
        AND contacts.date_entered <= '{$end}') )
        $sql_team))
        AND contacts.deleted = 0";
        $student_list = $GLOBALS['db']->fetchArray($q3);

        $full_list = array();
        foreach($target_list as $key=>$value){
            $value['contact_id'] = '';
            $value['inquiry_type'] = 'Target';
            $value['preferred_kind_of_course'] = '';
            $value['payment_status'] = '';
            array_push($full_list,$value);
        }
        foreach($lead_list as $key=>$value){
            $value['contact_id'] = '';
            $value['inquiry_type'] = 'Lead';
            $value['payment_status'] = '';
            array_push($full_list,$value);
        }
        foreach($student_list as $key=>$value){
            $value['inquiry_type'] = 'Student';
            $sql = "SELECT IFNULL(l1.payment_type, '') payment_type
            FROM contacts
            INNER JOIN contacts_j_payment_1_c l1_1 ON contacts.id = l1_1.contacts_j_payment_1contacts_ida
            AND l1_1.deleted = 0
            INNER JOIN j_payment l1 ON l1.id = l1_1.contacts_j_payment_1j_payment_idb
            AND l1.deleted = 0
            WHERE(((contacts.id = '{$value['id']}')))
            AND contacts.deleted = 0
            ORDER BY l1.date_entered DESC LIMIT 1";
            $payment = $GLOBALS['db']->getOne($sql);
            $value['payment_status'] = $payment;
            array_push($full_list,$value);
        }

        return  $full_list;

    }

