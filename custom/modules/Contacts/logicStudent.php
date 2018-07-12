<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class logicStudent{
    function addCode(&$bean, $event, $arguments){
        $code_field = 'contact_id';
        if(empty($bean->$code_field)){
            //Get Prefix
            $res        = $GLOBALS['db']->query("SELECT teams.code_prefix, team_type FROM teams WHERE id = '{$bean->team_id}'");
            $row        = $GLOBALS['db']->fetchByAssoc($res);
            $prefix     = $row['code_prefix'];
            $year       = date('y',strtotime('+ 7hours'. (!empty($bean->date_entered) ? $bean->date_entered : $bean->fetched_row['date_entered'])));
            $table      = $bean->table_name;
            $sep        = '-';
            $first_pad  = '00000';
            $padding    = 5;
            $query = "SELECT $code_field FROM $table WHERE ( $code_field <> '' AND $code_field IS NOT NULL) AND id != '{$bean->id}' AND (LEFT($code_field, ".strlen($prefix.$year).") = '".$prefix.$year."') ORDER BY RIGHT($code_field, $padding) DESC LIMIT 1";

            $result = $GLOBALS['db']->query($query);
            if($row = $GLOBALS['db']->fetchByAssoc($result))
                $last_code = $row[$code_field];
            else
                //no codes exist, generate default - PREFIX + CURRENT YEAR +  SEPARATOR + FIRST NUM
                $last_code = $prefix . $year . $sep  . $first_pad;


            $num = substr($last_code, -$padding, $padding);
            $num++;
            $pads = $padding - strlen($num);
            $new_code = $prefix . $year . $sep;

            //preform the lead padding 0
            for($i=0; $i < $pads; $i++)
                $new_code .= "0";
            $new_code .= $num;

            //write to database - Logic: Before Save
            $bean->$code_field = $new_code;
        }
    }

    //BEFORE SAVE: Handle Import student
    function importStudent(&$bean, $event, $arguments) {
        if($_POST['module'] == 'Import'){
            $user_id = $GLOBALS['db']->getOne("SELECT id FROM users WHERE user_name = '{$bean->created_by}' AND deleted = 0");
            if(!empty($user_id))
                $bean->created_by = $user_id;

            $bean->date_modified    = $bean->date_entered;
            $bean->modified_user_id = $bean->created_by;
        }

        //Xử lý Import Corp
        if($_POST['module'] == 'Import' && isset($_SESSION['contract_id'])){
            $ctr = BeanFactory::getBean('Contracts',$_SESSION['contract_id']);
            //Add relationship Student -> Contract
            if($bean->load_relationship('contracts'))
                $bean->contracts->add($ctr->id);


        }
    }

    function handleStudentLogic(&$bean, $event, $arguments){
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

        $bean->full_student_name = $bean->last_name. ' '.$bean->first_name;
        if(!empty($bean->birthdate))
            $bean->birthmonth  = date('n', strtotime($bean->birthdate));
        else $bean->birthmonth = '';
        $bean->assistant = viToEn($bean->full_student_name);

        $user_id = $GLOBALS['db']->getOne("SELECT id FROM users WHERE user_name = '{$bean->created_by}' AND deleted = 0");
        if(!empty($user_id))
            $bean->created_by = $user_id;

        //Save Parent
        if(!empty($bean->phone_mobile)) {
            $parent_team = $GLOBALS['db']->getOne("SELECT DISTINCT
                IFNULL(l1.id, '') l1_id
                FROM
                teams
                INNER JOIN
                teams l1 ON teams.parent_id = l1.id
                AND l1.deleted = 0
                WHERE
                (((teams.id = '{$bean->team_id}')))
                AND teams.deleted = 0");
            $sql    = "SELECT id FROM c_contacts WHERE mobile_phone = '{$bean->phone_mobile}'";
            $parent_id     = $GLOBALS['db']->getOne($sql);
            if($parent_id) {
                $contacts = BeanFactory::getBean('C_Contacts', $row['id']);
            } else{
                $contacts = new C_Contacts();
            }
            $contacts->name                 =  $bean->guardian_name;
            $contacts->address              =  $bean->primary_address_street;
            $contacts->mobile_phone         =  $bean->phone_mobile;
            $contacts->email1               =  $bean->email1;
            $contacts->team_id              =  $parent_team;
            $contacts->team_set_id          =  $parent_team;
            $contacts->assigned_user_id     = $bean->assigned_user_id;
            $contacts->save();

            $bean->load_relationship('c_contacts_contacts_1');
            $bean->c_contacts_contacts_1->add($contacts->id);
        }

        //Xử lý convert Lead to Student
        if(isset($_REQUEST['lead_id']) && $_REQUEST['lead_id'] !='') {
            $lead = BeanFactory::getBean('Leads',$_REQUEST['lead_id']);
            if($lead->id) {
                $lead->contact_id   = $bean->id;
                $lead->in_workflow  = true;
                $lead->status       = 'Converted';
                $lead->save();
                //Copy PT/Demo
                $lead->load_relationship('leads_j_ptresult_1');
                $pt_id = $lead->leads_j_ptresult_1->get();
                for($i = 0; $i < count($pt_id); $i++) {
                    $pt = BeanFactory::getBean('J_PTResult',$pt_id[$i]);
                    if($pt->id) {
                        $pt->parent = "Contacts";
                        $pt->student_id = $bean->id;
                        $pt->save();

                        $pt->load_relationship('leads_j_ptresult_1');
                        $pt->leads_j_ptresult_1->delete($pt->id, $lead->id);

                        $pt->load_relationship('contacts_j_ptresult_1');
                        $pt->contacts_j_ptresult_1->add($bean->id);
                    }
                }
                //Copy Log Call
                $GLOBALS['db']->query("UPDATE calls SET parent_id = '{$bean->id}', parent_type = 'Contacts' WHERE parent_id = '{$lead->id}' AND deleted = 0");
                $GLOBALS['db']->query("INSERT INTO calls_contacts (id, call_id, contact_id, required, accept_status, date_modified, deleted)
                    SELECT id, call_id, '{$bean->id}', required, accept_status, date_modified, deleted FROM calls_leads WHERE lead_id = '{$lead->id}' AND deleted = 0;");
                $GLOBALS['db']->query("DELETE FROM calls_leads WHERE lead_id = '{$lead->id}' AND deleted = 0");

                //Copy Task
                $GLOBALS['db']->query("UPDATE tasks SET parent_id = '{$bean->id}', parent_type = 'Contacts' WHERE parent_id = '{$lead->id}' AND deleted = 0");

                //Copy SMS
                $GLOBALS['db']->query("UPDATE c_sms SET parent_id = '{$bean->id}', parent_type = 'Contacts' WHERE parent_id = '{$lead->id}' AND deleted = 0");

                //Copy Payment
                $q10 = "SELECT DISTINCT
                IFNULL(j_payment.id, '') primaryid,
                IFNULL(j_payment.parent_type, '') parent_type,
                j_payment.payment_date payment_date,
                IFNULL(j_payment.payment_type, '') payment_type
                FROM
                j_payment
                INNER JOIN
                leads l1 ON j_payment.lead_id = l1.id
                AND l1.deleted = 0
                WHERE
                (((l1.id = '{$lead->id}')))
                AND j_payment.deleted = 0";
                $rs10 = $GLOBALS['db']->query($q10);
                while($row = $GLOBALS['db']->fetchByAssoc($rs10)) {
                    $payment = BeanFactory::getBean('J_Payment',$row['primaryid']);
                    $payment->load_relationship('contacts_j_payment_1');
                    $payment->contacts_j_payment_1->add($bean->id);
                    $GLOBALS['db']->query("UPDATE j_payment SET lead_id='', parent_type = 'Contacts' WHERE id = '{$row['primaryid']}' AND deleted = 0");
                    $GLOBALS['db']->query("UPDATE j_paymentdetail SET student_id='{$bean->id}' WHERE payment_id = '{$row['primaryid']}' AND deleted = 0");
                }

                $GLOBALS['db']->query("UPDATE c_sms SET parent_id = '{$bean->id}', parent_type = 'Contacts' WHERE parent_id = '{$lead->id}' AND deleted = 0");

                //Copy Meeting
                $GLOBALS['db']->query("UPDATE meetings SET parent_id = '{$bean->id}', parent_type = 'Contacts' WHERE parent_id = '{$lead->id}' AND deleted = 0 AND meeting_type = 'Meeting'");
                $GLOBALS['db']->query("INSERT INTO meetings_contacts (id, meeting_id, contact_id, required, accept_status, date_modified, deleted, situation_id)
                    SELECT id, meeting_id, '{$bean->id}', required, accept_status, date_modified, deleted, situation_id FROM meetings_leads WHERE lead_id = '{$lead->id}' AND deleted = 0;");
                $GLOBALS['db']->query("DELETE FROM meetings_leads WHERE lead_id = '{$lead->id}' AND deleted = 0");
                //Copy situation Demo
                $GLOBALS['db']->query("UPDATE j_studentsituations SET student_type='Student', student_id = '{$bean->id}', lead_id = '' WHERE lead_id = '{$lead->id}' AND deleted = 0 AND type = 'Demo'");
            }
        }

        //Save relationship
        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save'){
            //Delete old relationship
            $bean->load_relationship('contacts_contacts_1');
            $bean->load_relationship('leads_contacts_1');
            $bean->contacts_contacts_1->delete($bean->id);
            $bean->leads_contacts_1->delete($bean->id);

            //Save json relationship
            if(!empty($_POST["jsons"])){
                foreach ($_POST["jsons"] as $key => $json){
                    if($key>0){
                        $jsons_rela = json_decode(html_entity_decode($json));
                        if($jsons_rela->select == "Leads"){
                            $bean->leads_contacts_1->add($jsons_rela->rela_id);
                            //Update relationship
                            $resultID2  = $GLOBALS['db']->getone("SELECT id FROM leads_contacts_1_c WHERE leads_contacts_1leads_ida='".$jsons_rela->rela_id."' AND leads_contacts_1contacts_idb = '".$bean->id."' AND DELETED =0" );
                            $GLOBALS['db']->query("UPDATE leads_contacts_1_c SET related= '".$jsons_rela->select_rela."' WHERE id='".$resultID2."'");
                        }
                        if ($jsons_rela->select == "Contacts"){
                            $bean->contacts_contacts_1->add($jsons_rela->rela_id);
                            //Update rela student A
                            $resultID1  = $GLOBALS['db']->getone("SELECT id FROM contacts_contacts_1_c WHERE contacts_contacts_1contacts_ida='".$bean->id."' AND contacts_contacts_1contacts_idb = '".$jsons_rela->rela_id."' AND DELETED =0");
                            $GLOBALS['db']->query("UPDATE contacts_contacts_1_c SET related= '".$jsons_rela->select_rela."' WHERE id='".$resultID1."'");
                            //Update rela student B
                            $resultID2  = $GLOBALS['db']->getone("SELECT id FROM contacts_contacts_1_c WHERE contacts_contacts_1contacts_ida='".$jsons_rela->rela_id."' AND contacts_contacts_1contacts_idb = '".$bean->id."' AND DELETED =0" );
                            $GLOBALS['db']->query("UPDATE contacts_contacts_1_c SET related= '".$jsons_rela->select_rela."' WHERE id='".$resultID2."'");
                        }
                    }
                }
            }
        }

        //Update PT/Demo
        $GLOBALS['db']->query("UPDATE j_ptresult 
            SET last_name='{$bean->last_name}'
            , first_name='{$bean->first_name}'
            , student_name='".$bean->last_name.' '.$bean->first_name."'
            , student_mobile='{$bean->phone_mobile}'
            , student_email='{$bean->email1}'
            , student_birthdate='{$bean->birthdate}'
            , parent_name='{$bean->guardian_name}'
            , assigned_user_id='{$bean->assigned_user_id}' 
            WHERE student_id='{$bean->id}'");

        //Load voucher config 
        $voucherType = 'amount'; 
        $voucherValue = 0; 
        $queryConfig = "
        SELECT name, value
        FROM config
        WHERE category = 'custom'
        AND name IN ('refer_voucher_type', 'refer_voucher_value')
        ";
        $rsVoucher = $GLOBALS['db']->query($queryConfig);
        while($row = $GLOBALS['db']->fetchByAssoc($rsVoucher)) {
            if($row['name'] == 'refer_voucher_type') $voucherType = $row['value'];
            if($row['name'] == 'refer_voucher_value') $voucherValue = $row['value'];
        }
        
        $voucherPercent = 0;
        $voucherAmount = 0;
        if($voucherType == 'amount'){
            
        }

        //Create Voucher Code
        if(empty($bean->fetched_row)){
            $vou                       = new J_Voucher();
            $vou->name                 = strtoupper(create_guid_section(6));
            $vou->discount_amount      =  0;
            $vou->discount_percent     =  0;
            $vou->amount_per_used      =  0;
            $vou->status               =  'Activated';
            $vou->foc_type             =  'Referral';
            $vou->use_time             =  'N';
            $vou->team_id              =  '1';
            $vou->team_set_id          =  '1';
            $vou->student_id           =  $bean->id;
            $vou->start_date           =  '2016-01-01';
            $vou->end_date             =  '2018-12-31';
            $vou->description          =  'Mã giới thiệu bạn bè.';
            $vou->assigned_user_id     = $bean->assigned_user_id;
            $vou->save();
        }

        //Update R.E Result when email changed
        if($bean->student_type == 'Adult' && !empty($bean->fetched_row) && $bean->fetched_row['email1'] != $bean->email1 ){
            $GLOBALS['db']->query("UPDATE alpha_students SET email = '{$bean->email1}' WHERE sis_student_id = '{$bean->id}'");
        }
    }

    //PROCESS RECORD: Colorzing Listview
    function listviewcolor_Stu(&$bean, $event, $arguments) {
        global $current_user;
        $bean->custom_checkbox_class='<input type="checkbox" class="custom_checkbox" module_name="Contacts" onclick="handleCheckBox($(this));" value="'.$bean->id.'"  student_name="'.$bean->full_student_name.'"/>';
        $bean->contact_id = '<span class="textbg_blue">'.$bean->contact_id.'</span>';
    
        if ($_REQUEST['module']=='J_Class'){
            $bean->subpanel_button = '<input title="'.$GLOBALS['mod_strings']['LBL_CREATE_FEEDBACK'].'" onclick="window.open(\'index.php?module=J_Feedback&action=EditView&return_module=J_Feedback&return_action=DetailView&return_id=&j_class_j_feedback_1j_class_ida='.$_REQUEST['record'].'&contacts_j_feedback_1contacts_ida='.$bean->id.'&contacts_j_feedback_1_name='.$bean->full_student_name.'\',\'_blank\')" class="button primary" type="submit" name="btn_create_feedback" id="btn_create_feedback" value="'.$GLOBALS['mod_strings']['LBL_CREATE_FEEDBACK'].'">';
        }
        if ($_REQUEST['module']=='Contracts'){
            $bean->checkbox='<input type="checkbox" class="custom_checkbox" module_name="contacts" onclick="handleCheckBox($(this));" value="'.$bean->id.'"/>';
            $bean->subpanel_button = '<div style="display: inline-flex;"><input type="button" name="remove" class="button" id="'.$bean->id.'" value="Remove"></div>';
        }

        //Show Check box on Subpanel
        if ($_REQUEST['module']=='Meetings'){
            require_once('custom/include/_helper/class_utils.php');
            $meeting = BeanFactory::getBean('Meetings',$_REQUEST['record']);

            if($meeting->type_of_class != "Junior"){
                if(checkDataLockDate($meeting->date_end))
                    $bean->subpanel_button = '<div style="display: inline-flex;">
                    <input type="button" name="remove" class="button remove_student" id="'.$bean->id.'" value="Remove">
                    </div>';
                else
                    $bean->subpanel_button = '<label style="color: #E61718;">Locked by date</label>';
            }
        }
    }

    function autoGenerateAccountPortal(&$bean, $event, $arguments) {

        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save'){
            require_once("modules/Users/User.php");
            require_once('include/formbase.php');
            $flag_create_new_user = false;
            if(empty($bean->user_id)){
                $flag_create_new_user = true;
                $user = BeanFactory::newBean('Users');
            }else
                $user = BeanFactory::getBean('Users',$bean->user_id);
            if($user == false || empty($user)){
                $flag_create_new_user = true;
                $user = BeanFactory::newBean('Users');
            }

            $bean->portal_name = strtolower($bean->contact_id);

            $user->user_name = $bean->portal_name;
            if($flag_create_new_user){
                $team_type = getTeamType($bean->team_id);
                if($team_type = 'Adult')
                    $user->password = 'portal2017';
                else $user->password = 'portal2017';
            }

            $user->contact_id = $bean->id;
            $user->portal_contact_id = $bean->id;
            $user->portal_user = '1';
            $user->for_portal_only = '1';

            $user->first_name = $bean->first_name;
            $user->last_name = $bean ->last_name;
            $user->title = $bean->title;
            $user->department = $bean->department;
            $user->phone_work = $bean->phone_work;
            $user->phone_mobile = $bean->phone_mobile;
            $user->phone_other = $bean->phone_other;
            $user->phone_fax = $bean->phone_fax;
            $user->phone_home = $bean->phone_home;
            $user->address_street = $bean->primary_address_street;
            $user->address_city = $bean->primary_address_city;
            $user->address_state = $bean->primary_address_state;
            $user->address_country = $bean->primary_address_country;
            $user->team_id = $bean->team_id;
            $user->team_set_id = $bean->team_id;
            if($bean->portal_active)
                $user->status = 'Active';
            else
                $user->status = 'Inactive';

            $user->save();

            $user->setPreference('date_format','d/m/Y');
            $user->setPreference('time_format','h:ia');
            $user->setPreference('timezone','Asia/Ho_Chi_Minh');
            $user->setPreference('default_locale_name_format','s l f');
            $user->savePreferencesToDB();

            //Check duplidate email
            $q1 = "SELECT er.id, er.deleted
            FROM email_addr_bean_rel er
            INNER JOIN email_addresses e ON e.id = er.email_address_id AND e.deleted = 0
            AND e.email_address_caps = '".strtoupper($bean->email1)."'
            WHERE er.bean_module = 'Users' AND er.bean_id='{$user->id}'";
            $row = $GLOBALS['db']->fetchOne($q1);
            if(empty($row['id'])){  //add email to email relationship with user
                $sea = new SugarEmailAddress;
                $sea->addAddress($bean->email1, true);
                $sea->save($user->id, "Users");
            } else if ($row['deleted']){   //Update deleted = 0 . Bug: Can not create user email by code
                $q3 = "UPDATE email_addr_bean_rel SET deleted='0' WHERE id='{$row['id']}'";
                $GLOBALS['db']->query($q3);
            }
            if($flag_create_new_user){
                $additionalData = array(
                    'link' => false,
                    'password' => $user->password,
                    'system_generated_password' => '0',
                );
                $user->setNewPassword($additionalData['password'], '0');
                $GLOBALS['db']->query("UPDATE contacts SET password_generated='{$user->password}' WHERE id='{$bean->id}'");
            }
            //update infor user to contacts
            $GLOBALS['db']->query("UPDATE contacts SET user_id = '{$user->id}', portal_name = '{$user->user_name}' WHERE id = '{$bean->id}'");

            //Xử lý User remove team Primary
            if($bean->date_modified != $bean->date_entered){
                $teamSetBean = new TeamSet();
                $teams = $teamSetBean->getTeams($bean->team_set_id);
                $team_list = array();
                $countDifTeam = 0;
                foreach ($teams as $key => $value)
                    $team_list[] = $key;
                //get list Team Payment ID
                $qTeams = $GLOBALS['db']->fetchArray("SELECT DISTINCT
                    IFNULL(l2.id, '') team_id
                    FROM
                    j_payment
                    INNER JOIN
                    contacts_j_payment_1_c l1_1 ON j_payment.id = l1_1.contacts_j_payment_1j_payment_idb
                    AND l1_1.deleted = 0
                    INNER JOIN
                    contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida
                    AND l1.deleted = 0
                    INNER JOIN
                    teams l2 ON j_payment.team_id = l2.id
                    AND l2.deleted = 0
                    WHERE
                    (((l1.id = '{$bean->id}')))
                    AND j_payment.deleted = 0");
                if(!empty($qTeams)){
                    foreach($qTeams as $value){
                        if(!in_array($value['team_id'],$team_list)){
                            $team_list[] = $value['team_id'];
                            $countDifTeam++;
                        }
                    }
                    if($countDifTeam > 0){
                        $bean->load_relationship('teams');
                        $bean->teams->replace($team_list);
                    }
                }

            }

        }
    }

    function handleAddRelationship(&$bean, $event, $arguments){
        if ($arguments['related_module'] =='Contracts'){
            $number_of_student = $GLOBALS['db']->getOne("SELECT DISTINCT
                COUNT(contacts.id) contacts__allcount
                FROM
                contacts
                INNER JOIN
                contracts_contacts l1_1 ON contacts.id = l1_1.contact_id
                AND l1_1.deleted = 0
                INNER JOIN
                contracts l1 ON l1.id = l1_1.contract_id
                AND l1.deleted = 0
                WHERE
                (((l1.id = '{$arguments['related_bean']->id}')))
                AND contacts.deleted = 0") + 1;
            if( $number_of_student > $arguments['related_bean']->number_of_student){
                echo '
                <script type="text/javascript">
                alertify.alert(" Hợp đồng đã đủ số lượng học viên "+"'.($number_of_student - 1).' / '.($arguments['related_bean']->number_of_student).'");
                location.href=\'index.php?module=Contracts&action=DetailView&record='.$arguments['related_bean']->id.'\';
                </script>';
                die();
            }
            global $timedate;
            // Add Student to account
            if($bean->load_relationship('accounts'))
                $bean->accounts->add($arguments['related_bean']->account_id);

            //Change Student Type
            $bean->type = 'Corporate';
            $GLOBALS['db']->query("UPDATE contacts SET type = '{$bean->type}' WHERE deleted = 0 AND id = '{$bean->id}'");

            if($bean->type == 'Corporate'){
                $payment_id = $GLOBALS['db']->getOne("SELECT DISTINCT
                    IFNULL(j_payment.id, '') primaryid
                    FROM
                    j_payment
                    INNER JOIN
                    contracts l1 ON j_payment.contract_id = l1.id
                    AND l1.deleted = 0
                    INNER JOIN
                    contacts_j_payment_1_c l2_1 ON j_payment.id = l2_1.contacts_j_payment_1j_payment_idb
                    AND l2_1.deleted = 0
                    INNER JOIN
                    contacts l2 ON l2.id = l2_1.contacts_j_payment_1contacts_ida
                    AND l2.deleted = 0
                    WHERE
                    (((l1.id = '{$arguments['related_bean']->id}')
                    AND (l2.id = '{$bean->id}')
                    AND (j_payment.payment_type = 'Corporate' )))
                    AND j_payment.deleted = 0");  //Check Duplicate
                if(empty($payment_id)){
                    $pm_cop = new J_Payment();
                }else{
                    $pm_cop = BeanFactory::getBean('J_Payment',$payment_id);
                }
                //Tạo payment Corporate
                $amount_per_student = $arguments['related_bean']->total_contract_value / $arguments['related_bean']->number_of_student;

                $pm_cop->payment_type      = 'Corporate';
                $pm_cop->remain_amount        = $amount_per_student;
                $pm_cop->tuition_fee          = $amount_per_student;
                $pm_cop->amount_bef_discount  = $amount_per_student;
                $pm_cop->total_after_discount = $amount_per_student;
                $pm_cop->payment_amount       = $amount_per_student;
                $pm_cop->payment_date         = $timedate->to_db_date($arguments['related_bean']->customer_signed_date, false);

                $pm_cop->sale_type_date       = $timedate->to_db_date($arguments['related_bean']->customer_signed_date, false);
                $pm_cop->sale_type            = 'Not Set';
                $pm_cop->tuition_hours        = $arguments['related_bean']->duration_hour;
                $pm_cop->total_hours          = $arguments['related_bean']->duration_hour;
                $pm_cop->remain_hours         = $arguments['related_bean']->duration_hour;
                $pm_cop->total_sessions       = $arguments['related_bean']->duration_session;
                $pm_cop->remain_sessions      = $arguments['related_bean']->duration_session;
                $pm_cop->contract_id          = $arguments['related_bean']->id;
                $pm_cop->kind_of_course_360   = $arguments['related_bean']->kind_of_course;
                $pm_cop->kind_of_course       = $arguments['related_bean']->kind_of_course;
                $pm_cop->kind_of_course_string= $arguments['related_bean']->kind_of_course;

                $pm_cop->number_of_skill      = 0;
                $pm_cop->number_of_practice   = 0;
                $pm_cop->number_of_connect    = 0;

                if(empty($pm_cop->end_study)){
                    $pm_cop->start_study      = '';
                    $pm_cop->end_study        = '';
                }
                $pm_cop->note                 = 'Học viên đến từ hợp đồng : '.$arguments['related_bean']->name;
                $pm_cop->assigned_user_id     = $arguments['related_bean']->assigned_user_id;
                $pm_cop->team_id              = $bean->team_id;
                $pm_cop->team_set_id          = $bean->team_id;
                $pm_cop->save();
                if(empty($payment_id)){
                    if($bean->load_relationship('contacts_j_payment_1'))
                        $bean->contacts_j_payment_1->add($pm_cop->id);
                }
            }
        }
    }
         

    function showSituationType(&$bean, $event, $arguments) {
        if($_GET["module"] == "Meetings"){
            $sql = "SELECT situ.type
            FROM meetings_contacts rela
            INNER JOIN j_studentsituations situ ON situ.id = rela.situation_id AND situ.deleted <> 1
            WHERE rela.deleted <> 1
            AND rela.meeting_id = '{$_GET["record"]}'
            AND rela.contact_id = '{$bean->id}'";
            $bean->situation_type = $GLOBALS['db']->getOne($sql);
        }
    }

    function handleRemoveRelationship(&$bean, $event, $arguments){
        if ($arguments['related_module'] =='Contracts'){

            $rs1 = $GLOBALS['db']->query("SELECT DISTINCT
                IFNULL(j_payment.id, '') primaryid
                FROM
                j_payment
                INNER JOIN
                contacts_j_payment_1_c l1_1 ON j_payment.id = l1_1.contacts_j_payment_1j_payment_idb
                AND l1_1.deleted = 0
                INNER JOIN
                contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida
                AND l1.deleted = 0
                WHERE
                (((l1.id = '{$bean->id}')
                AND (j_payment.payment_type = 'Corporate')
                AND (j_payment.contract_id = '{$arguments['related_bean']->id}')))
                AND j_payment.deleted = 0");
            while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
                $pm_cop = BeanFactory::getBean('J_Payment',$row['primaryid']);
                $pm_cop->mark_deleted();
            }
            //    $number_of_student = $arguments['related_bean']->number_of_student - 1;
            //            $GLOBALS['db']->query("UPDATE contracts SET number_of_student = '$number_of_student' WHERE id = '{$arguments['related_bean']->id}'");

        }
    }      

    /**
    * when deleted contact then delete  its user portal
    *
    * @param mixed $bean
    * @param mixed $event
    * @param mixed $arg
    */
    function autoDeleteAccountPortal($bean, $event, $arg) {
        if($bean->user_id) {
            require_once("modules/Users/User.php");
            $user = new User();
            $user->retrieve($bean->user_id);
            if($user->id) {
                $user->mark_deleted($user->id);
            }
        }
    }

    function beforeDeleteStudent(&$bean, $event, $arguments){
        $count_rel = $GLOBALS['db']->getOne("SELECT DISTINCT
            COUNT(IFNULL(j_payment.id, '')) count_rel
            FROM
            j_payment
            INNER JOIN
            contacts_j_payment_1_c l1_1 ON j_payment.id = l1_1.contacts_j_payment_1j_payment_idb
            AND l1_1.deleted = 0
            INNER JOIN
            contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida
            AND l1.deleted = 0
            WHERE
            (((l1.id = '{$bean->id}')))
            AND j_payment.deleted = 0");

        if($count_rel > 0){
            echo '
            <script type="text/javascript">
            alert("You can not delete this student!\nReason: Payments of this student still exists.");
            location.href=\'index.php?module=Contacts&action=DetailView&record='.$bean->id.'\';
            </script>';
            die();

            $count_pt = $GLOBALS['db']->getOne("SELECT DISTINCT
                COUNT(j_ptresult.id) j_ptresult__allcount
                FROM
                j_ptresult
                INNER JOIN
                contacts_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.contacts_j_ptresult_1j_ptresult_idb
                AND l1_1.deleted = 0
                INNER JOIN
                contacts l1 ON l1.id = l1_1.contacts_j_ptresult_1contacts_ida
                AND l1.deleted = 0
                WHERE
                (((l1.id = '{$bean->id}')))
                AND j_ptresult.deleted = 0");

            if($count_pt > 0){
                echo '
                <script type="text/javascript">
                alert("You can not delete this Student!\nReason: Placenment Test/Demo result of this Student still exists.");
                location.href=\'index.php?module=Contacts&action=DetailView&record='.$bean->id.'\';
                </script>';
                die();
            }
        }else
            $GLOBALS['db']->query("UPDATE leads SET contact_id = '', status='In Process' WHERE contact_id='{$bean->id}'");

    }

    // Add by Nguyen Tung 6-6-2018
    function createLead($bean, $event, $arg) {
        // After Save
        global $db;

        if(!$leadConvertId) {

            // Add Lead
            $lead = new Lead();
            $lead->j_school_leads_1j_school_ida = $_POST['j_school_contacts_1j_school_ida'];
            $lead->contact_id = $bean->id;
            $lead->campaign_id = $bean->campaign_id;
            $lead->email1 = $bean->email1;
            $lead->team_set_id = $bean->team_set_id;
            $lead->team_id = $bean->team_id;
            foreach ($_POST as $key => $value) {
                $lead->$key = $value;
            }
            $lead->save();

            // Add email_address into Lead
            $dateCreated = $bean->fetched_row['date_entered'];

            $email = new EmailAddress();
            $email->email_address = $bean->email1;
            $email->invalid_email = $bean->invalid_email;
            $email->opt_out = $bean->email_opt_out;
            $email->date_created = $dateCreated;
            $email->save();

            $id = create_guid(); 
            $sql = "INSERT INTO email_addr_bean_rel (id, email_address_id, bean_id, bean_module, primary_address, reply_to_address, date_created, date_modified, deleted) 
                    VALUES ('$id', '$email->id', '$lead->id', 'Leads', 1, 0, '$bean->date_entered', '$dateCreated', 0)";
            $db->query($sql);
        }

    }

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
    // End by Nguyen Tung

    // Before Save -- Add by Tung Bui
    function checkStudentLimit($bean, $event, $arg) {
        checkStudentLimit(true);  
    }
}
?>
