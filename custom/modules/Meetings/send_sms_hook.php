<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

    class SMS_Hook {
        function SMS_Hook(&$bean, $event, $arguments){
            require_once("custom/modules/C_SMS/SMS/sms.php");
            $sql = "
            SELECT DISTINCT IFNULL(l1.id,'') l1_id ,IFNULL(l1.type,'') l1_type, date_start, date_end, l1.name 
            FROM meetings 
            INNER JOIN c_classes l1 ON meetings.class_id=l1.id AND l1.deleted=0 
            WHERE (((meetings.id='{$bean->id}' ))) AND meetings.deleted=0
            ";
            $rs = $GLOBALS['db']->query($sql);
            $row = $GLOBALS['db']->fetchByAssoc($rs);
            //get date
            $date = date('l, F dS', strtotime('+7 hour',strtotime($row['date_start'])));

            $time_end = date('H:i',strtotime('+7 hour',strtotime($row['date_end'])));

            $time_start = date('H:i',strtotime('+7 hour',strtotime($row['date_start'])));

            $sessionDateTime = $date.", ".$time_start." - ".$time_end;
            $class_name = $row['name'];

            if(($row['l1_type']=="Skill"||$row['l1_type']=='Connect Club') && $bean->meeting_type == "Session")
            {
                if($arguments['related_module']=="Leads")
                $sql = "SELECT CONCAT( IFNULL( leads.last_name,  '' ) ,  ' ', IFNULL( leads.first_name,  '' ) ) full_name, id, first_name, phone_mobile
                FROM leads
                WHERE deleted =0
                ORDER BY date_modified DESC 
                LIMIT 1";
                else if($arguments['related_module']=="Contacts") 
                $sql = "SELECT CONCAT( IFNULL( contacts.last_name,  '' ) ,  ' ', IFNULL( contacts.first_name,  '' ) ) full_name, id, first_name, phone_mobile
                FROM contacts
                WHERE deleted =0
                ORDER BY date_modified DESC 
                LIMIT 1";
                ;
                $rs = $GLOBALS['db']->query($sql);
                $row = $GLOBALS['db']->fetchByAssoc($rs);
                $tpls = new EmailTemplate();
                $tpls->retrieve_by_string_fields(array('name'=>'SMS TEMPLATE: BOOKING ONLINE SUCCESS','deleted'=>0));
                $content = str_replace('@@full_name@@',$row['full_name'],$tpls->body); 
                $content = str_replace('@@class_name@@',$class_name,$content); 
                $content = str_replace('@@sessionDateTime@@',$sessionDateTime,$content); 
                if(!isset($content)||empty($content))
                {
                    echo json_encode(array(
                        "success" => "0",
                    ));
                    return false;            
                }

                //$content = "Atlantic360: Chao{$row['leads_full_name']}! Ban da dang ky thanh cong vao lop {$class_name}. Thoi gian: {$sessionDateTime}. Vui long co mat dung gio!";
                $sms = new sms();
                $send1 = $sms->send_message($row['phone_mobile'],$content,$arguments['related_module'],$row['id']);
            } 
        }
    } 
?>
