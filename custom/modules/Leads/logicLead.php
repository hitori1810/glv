<?php
class logicLead{
    //Save relationship
    function saveRelationship(&$bean, $event, $arguments){
        global $timedate;
        if(!empty($_POST['assigned_user_id_2']) && !isset($_POST['assigned_user_id']))
            $bean->assigned_user_id = $_POST['assigned_user_id_2'];

        if(!empty($_POST['birthdate_2']) && !isset($_POST['birthdate']))
            $bean->birthdate = $timedate->to_db_date($_POST['birthdate_2'],false);

        if(!empty($_POST['first_name_2']) && !isset($_POST['first_name']))
            $bean->first_name = $_POST['first_name_2'];

        if(!empty($_POST['last_name_2']) && !isset($_POST['last_name']))
            $bean->last_name = $_POST['last_name_2'];

        if(!empty($_POST['phone_mobile_2']) && !isset($_POST['phone_mobile']))
            $bean->phone_mobile = $_POST['phone_mobile_2'];

        $bean->full_lead_name = $bean->last_name .' '. $bean->first_name;
        if(!empty($bean->birthdate))
            $bean->birthmonth  = date('n', strtotime($bean->birthdate));
        else $bean->birthmonth = '';
        $bean->assistant = viToEn($bean->full_lead_name);
        if($_POST['module'] == 'Import'){
            //            $user_id = $GLOBALS['db']->getOne("SELECT id FROM users WHERE user_name = '{$bean->created_by}' AND deleted = 0");
            //            if(!empty($user_id))
            //                $bean->created_by = $user_id;
            //
            //            $bean->date_modified    = $bean->date_entered;
            //            $bean->modified_user_id = $bean->created_by;
            if(!empty($bean->pt_score)){
                $meetingId = $GLOBALS['db']->getOne('SELECT id FROM meetings WHERE name="IMPORT LEAD TO PT - '.$bean->team_name.'"');
                if(empty($meetingId)){
                    $ss = new Meeting();
                    $ss->name = 'IMPORT LEAD TO PT - '.$bean->team_name;
                    $ss->date_start = $timedate->nowDb();
                    $ss->type = 'Sugar';
                    $ss->duration_hours = 1;
                    $ss->duration_minutes = 0;
                    $ss->type_of_class = 'Junior';
                    $ss->meeting_type = 'Placement Test';
                    $ss->teaching_hour = 1;
                    $ss->delivery_hour = 1;
                    $ss->update_vcal = false;

                    $ss->team_id        = $bean->team_id;
                    $ss->team_set_id    = $bean->team_id;
                    $ss->assigned_user_id = $bean->assigned_user_id;
                    $ss->save();
                    $meetingId = $ss->id;
                }
                $result = new J_PTResult();
                $result->team_id            = $bean->team_id;
                $result->team_set_id        = $bean->team_set_id;
                $result->assigned_user_id   = $bean->assigned_user_id;
                $result->attended           = 1;
                $result->parent             = "Leads";
                $result->ec_note            = '';
                $result->score              = $bean->pt_score;
                if(!empty($bean->prefer_level)){
                    $result->result         = $bean->prefer_level;
                    $parts                  = explode(' ',$result->result);
                    $result->result_koc     = $parts[0];
                    $result->result_lvl     = trim(str_replace($result_koc,'',$result->result));
                }
                $result->meetings_j_ptresult_1meetings_ida = $meetingId;
                $result->leads_j_ptresult_1leads_ida = $bean->id;
                $result->type_result        = "Placement Test";
                $result->name               = $bean->last_name.' '.$bean->first_name;
                $result->student_id         = $bean->id;
                $result->save();
                $bean->last_pt_result = $bean->prefer_level;
            }
        }

        //save team type - Also do when import
        $lead_type = $GLOBALS['db']->getOne("SELECT team_type FROM teams WHERE id = '{$bean->team_id}'");
        $bean->team_type = $lead_type;

        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save'){
            //Delete old relationship
            $GLOBALS['db']->query("DELETE FROM leads_leads_1_c WHERE leads_leads_1leads_ida='{$bean->id}'");
            $GLOBALS['db']->query("DELETE FROM leads_contacts_1_c WHERE leads_contacts_1leads_ida='{$bean->id}'");
            $bean->load_relationship('leads_leads_1');
            $bean->load_relationship('leads_contacts_1');
            //Save json relationship
            foreach ($_POST["jsons"] as $key => $json){
                if($key>0){
                    $jsons_rela = json_decode(html_entity_decode($json));
                    if($jsons_rela->select == "Leads"){
                        $bean->leads_leads_1->add($jsons_rela->rela_id);
                        //Update related type
                        $GLOBALS['db']->query("UPDATE leads_leads_1_c SET related= '{$jsons_rela->select_rela}' WHERE leads_leads_1leads_ida='{$bean->id}' AND leads_leads_1leads_idb = '{$jsons_rela->rela_id}' AND deleted = 0");
                    }
                    if ($jsons_rela->select == "Contacts"){
                        $bean->leads_contacts_1->add($jsons_rela->rela_id);
                        //Update related type
                        $GLOBALS['db']->query("UPDATE leads_contacts_1_c SET related= '{$jsons_rela->select_rela}' WHERE leads_contacts_1leads_ida='{$bean->id}' AND leads_contacts_1contacts_idb = '{$jsons_rela->rela_id}' AND deleted = 0");
                    }
                }
            }
        }
        if (isset($_POST['prospect_id']) &&  !empty($_POST['prospect_id'])) {
            $prospect=new Prospect();
            $prospect->retrieve($_POST['prospect_id']);
            $prospect->lead_id= $bean->id;
            // Set to keep email in target
            $prospect->in_workflow = true;
            $prospect->converted = true;
            $prospect->save();

            //Copy Log Call
            $GLOBALS['db']->query("UPDATE calls SET parent_id = '{$bean->id}', parent_type = 'Leads' WHERE parent_id = '{$prospect->id}' AND deleted = 0");

            $GLOBALS['db']->query("INSERT INTO calls_leads (id, call_id, lead_id, required, accept_status, date_modified, deleted)
                SELECT id, id, '{$bean->id}', 1, 'none', date_modified, deleted FROM calls WHERE parent_id = '{$bean->id}' AND deleted = 0;");

            //Copy Task
            $GLOBALS['db']->query("UPDATE tasks SET parent_id = '{$bean->id}', parent_type = 'Leads' WHERE parent_id = '{$prospect->id}' AND deleted = 0");

            //Copy Meeting
            $GLOBALS['db']->query("UPDATE meetings SET parent_id = '{$bean->id}', parent_type = 'Leads' WHERE parent_id = '{$prospect->id}' AND deleted = 0 AND meeting_type = 'Meeting'");

            $GLOBALS['db']->query("INSERT INTO meetings_leads (id, meeting_id, lead_id, required, accept_status, date_modified, deleted)
                SELECT id, id, '{$bean->id}', 1, 'none', date_modified, deleted FROM meetings WHERE parent_id = '{$bean->id}' AND deleted = 0;");
        }

        //Update PT/Demo
        $GLOBALS['db']->query("UPDATE j_ptresult SET last_name='{$bean->last_name}', first_name='{$bean->first_name}', student_name='".$bean->last_name.' '.$bean->first_name."', student_mobile='{$bean->phone_mobile}', student_email='{$bean->email1}', student_birthdate='{$bean->birthdate}', parent_name='{$bean->guardian_name}', assigned_user_id='{$bean->assigned_user_id}' WHERE student_id='{$bean->id}'");

        //Update Status
        if($bean->status != 'Converted'){
            if($bean->potential == 'Ready To PT/Demo')
                $bean->status = 'PT/Demo';
            elseif($bean->potential == 'Not Interested'){
                $bean->status = 'Dead';
            }elseif($bean->status == 'Dead' && $bean->potential != 'Not Interested')
                $bean->status = 'Recycled';
        }
    }
    function listviewcolor(&$bean, $event, $arguments) {
        $colorClass = '';
        switch($bean->status) {
            case 'New':
                $colorClass = "textbg_green";
                break;
            case 'Assigned':
                $colorClass = "textbg_bluelight";
                break;
            case 'In Process':
                $colorClass = "textbg_blue";
                break;
            case 'Converted':
                $colorClass = "textbg_red";
                break;
            case 'PT/Demo':
                $colorClass = "textbg_violet";
                break;
            case 'Recycled':
                $colorClass = "textbg_orange";
                break;
            case 'Dead':
                $colorClass = "textbg_black";
                break;
            default :
                $colorClass = "No_color";
        }
        $tmp_status = "<span class=$colorClass>".translate('lead_status_dom','',$bean->status)."</span>";
        //        if($bean->status == 'PT/Demo'){
        //            $lastPT     = $GLOBALS['db']->getOne("SELECT last_pt_result FROM leads WHERE id = '{$bean->id}'");
        //            $tmp_status .= '<br>'.$lastPT;
        //        }
        $bean->status = $tmp_status;
    }

    function notify_lead($bean, $event, $arguments) {
        global $current_user;
        if((empty($bean->fetched_row)) || (!empty($bean->fetched_row) && $bean->fetched_row['assigned_user_id'] != $bean->assigned_user_id)) {

            //get the parent bean
            $parent_bean = BeanFactory::getBean($bean->module_name, $bean->id);
            //initialize notification bean
            $notification_bean = BeanFactory::getBean("Notifications");

            $notification_bean->name = $GLOBALS['app_list_strings']['parent_type_display'][$bean->module_name].": ".$bean->name . translate('LBL_ASSIGNED_INFO','Notifications');
            //assigned user should be record assigned user
            $notification_bean->assigned_user_id = $bean->assigned_user_id;
            $notification_bean->parent_id = $bean->id;
            $GLOBALS['db']->query("DELETE FROM notifications WHERE parent_id='{$bean->id}'");
            $notification_bean->parent_type =  $bean->module_name;
            $notification_bean->created_by = $bean->modified_user_id;
            //set is_read to no
            $notification_bean->is_read = 0;
            //set the level of severity
            $notification_bean->severity = "Info";
            $notification_bean->save();
        }
    }

    function beforeDeleteLead(&$bean, $event, $arguments){
        $count_rel = $GLOBALS['db']->getOne("SELECT DISTINCT
            COUNT(j_payment.id) count_payment
            FROM
            j_payment
            INNER JOIN
            leads l1 ON j_payment.lead_id = l1.id
            AND l1.deleted = 0
            WHERE
            (((l1.id = '{$bean->id}')))
            AND j_payment.deleted = 0");

        if($count_rel > 0){
            echo '
            <script type="text/javascript">
            alert("You can not delete this lead!\nReason: Payments of this lead still exists.");
            location.href=\'index.php?module=Leads&action=DetailView&record='.$bean->id.'\';
            </script>';
            die();
        }

        $count_pt = $GLOBALS['db']->getOne("SELECT DISTINCT
            COUNT(j_ptresult.id) j_ptresult__count
            FROM
            j_ptresult
            INNER JOIN
            leads_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.leads_j_ptresult_1j_ptresult_idb
            AND l1_1.deleted = 0
            INNER JOIN
            leads l1 ON l1.id = l1_1.leads_j_ptresult_1leads_ida
            AND l1.deleted = 0
            WHERE
            (((l1.id = '{$bean->id}')))
            AND j_ptresult.deleted = 0");

        if($count_pt > 0){
            echo '
            <script type="text/javascript">
            alert("You can not delete this Lead!\nReason: Placenment Test/Demo result of this lead still exists.");
            location.href=\'index.php?module=Leads&action=DetailView&record='.$bean->id.'\';
            </script>';
            die();
        }

    }

    // Add by Nguyen Tung 12-6-2018

    function getInfoFacebook($bean, $event, $arg) {
        global $db, $config;

        if(!$bean->picture && $bean->facebook) {

            $userId = getUserId($bean->facebook);

            if($userId) {
                $pictureId = create_guid();
                $urlImg = 'http://graph.facebook.com/'.$userId.'/picture?height=168&width=168';
                $contentImg = file_get_contents($urlImg);

                $img     = 'upload/'.$pictureId;
                $imgResize     = 'uploadImage/imagesResize/'.$pictureId;

                file_put_contents($img, $contentImg);
                file_put_contents($imgResize, $contentImg);

                $bean->picture = $pictureId;
                $bean->save();
            }
        }
    }

    // Before Save -- Add by Tung Bui
    function checkLeadLimit($bean, $event, $arg) {
        checkLeadLimit(true);  
    }
}
?>
