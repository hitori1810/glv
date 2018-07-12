<?php

if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('service/v4_1/SugarWebServiceImplv4_1.php');

/*
*   class SugarWebServiceImplv4_1_custom
*   Author: Hieu Nguyen
*   Date: 2015-06-04
*   Purpose: To handle custom webservices
*/

class SugarWebServiceImplv4_1_custom extends SugarWebServiceImplv4_1
{

    // Get language by a given type and language name
    function get_sugar_language($session, $type, $language)
    {
        $error = new SoapError();

        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return;
        }

        // Process
        if ($type == 'app_list_strings') {
            $langString = return_app_list_strings_language($language);
        } else if ($type == 'app_strings') {
            $langString = return_application_language($language);
        } else {
            $langString = return_module_language($language, $type, true);
        }

        return $langString;
    }

    // Change password
    public function change_password($session, $currentPassword, $newPassword)
    {
        $error = new SoapError();

        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return;
        }

        global $current_user;
        if (preg_match('/[<&>\'"]/', $newPassword, $matches)) {
            return array(
                "status" => "Error",
                "notify" => "Password does not contain &, <, >, \", \' characters.",
            );
        }
        if ($current_user->checkPasswordMD5($currentPassword, $current_user->user_hash)) {
            $current_user->setNewPassword($newPassword, '0', false);
            //$current_user->user_hash = $current_user->getPasswordHash($newPassword);
            //$current_user->save();
            $GLOBALS['db']->query("update contacts set password_generated = '$newPassword' where contact_id = '{$current_user->user_name}'");

            return $current_user->toArray();
        } else {
            return array('status' => 'Error', 'message' => 'Wrong password');
        }
    }  //Tạm thời comment

    /*public function change_password($session, $currentPassword, $newPassword) {
    $error = new SoapError();

    // Authenticate
    if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access',  $error)) {
    return;
    }

    global $current_user;

    if($current_user->checkPasswordMD5($currentPassword, $current_user->user_hash)) {
    $current_user->setNewPassword($newPassword, '0', true) ;
    //$current_user->user_hash = $current_user->getPasswordHash($newPassword);
    //$current_user->save();

    return $current_user->toArray();
    } else {
    return array('status'=>'Error', 'message'=>'Wrong password');
    }
    }*/

    // Set user preferences by a given array of preferences
    function set_user_preferences($session, $preferences)
    {
        $error = new SoapError();

        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return;
        }

        if (!empty($preferences)) {
            global $beanFiles, $current_user;
            require_once($beanFiles['UserPreference']);
            $userPreference = new UserPreference($current_user);

            foreach ($preferences as $key => $value) {
                $userPreference->setPreference($key, $value);
            }

            $userPreference->savePreferencesToDB();
        }

        return array('status' => true);
    }

    // Get all enrollment that belong to the given student id. Added by Hieu Nguyen on 2016-05-31
    // Fix bug by Trung Nguyen 2016.07.05
    function get_enrollment_list($session, $studentId)
    {
        $error = new SoapError();

        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return;
        }

        //get all situation of student
        global $db, $timedate;
        $enrollments = array();
        $sql = "SELECT DISTINCT
        IFNULL(j_studentsituations.id, '') id
        ,IFNULL(l2.name, '') class_name
        ,IFNULL(j_studentsituations.type, '') type
        ,IFNULL(j_studentsituations.total_hour, '') total_hour
        ,IFNULL(j_studentsituations.total_amount, '') total_amount
        ,IFNULL(j_studentsituations.start_study, '') start_study
        ,IFNULL(j_studentsituations.end_study, '') end_study
        ,IFNULL(l3.name,'') team_name
        ,TRIM(CONCAT(u.last_name,' ',u.first_name)) as ec_name

        FROM j_studentsituations
        INNER JOIN contacts l1 ON j_studentsituations.student_id = l1.id AND l1.deleted = 0
        INNER JOIN j_class l2 ON j_studentsituations.ju_class_id = l2.id AND l2.deleted = 0
        INNER JOIN  teams l3 ON j_studentsituations.team_id=l3.id AND l3.deleted=0
        INNER JOIN users u ON u.id = j_studentsituations.assigned_user_id
        WHERE
        l1.id = '{$studentId}'
        AND IFNULL(l2.kind_of_course,'') <> 'Outing Trip'
        AND j_studentsituations.type IN ('Enrolled','OutStanding','Settle','Moving In')
        AND j_studentsituations.deleted = 0
        ORDER BY j_studentsituations.start_study";
        $result = $db->query($sql);
        $situationString = "(";
        while ($row = $db->fetchByAssoc($result)) {
            $nowDb = $timedate->nowDbDate();
            $enrollments[$row['id']] = array(
                'class_name' => $row['class_name'],
                'total_hour' => $row['total_hour'],
                'total_amount' => format_number($row['total_amount']),
                'start_date' => $row['start_study'],
                'end_date' => $row['end_study'],
                'balance' => '0',
                'balance_hour' => $row['total_hour'],
                'center' => $row['team_name'],
                'ec_name' => $row['ec_name']
            );
            //insert to situationString
            if ($situationString != "(") $situationString .= ",";
            $situationString .= "'{$row['id']}'";
        }
        $situationString .= ")";

        //calculate balance
        $sql = "SELECT DISTINCT
        l2.id situation_id
        ,IFNULL(SUM(meetings.delivery_hour),0) total_delivery_hour
        FROM
        meetings
        INNER JOIN meetings_contacts l1_1 ON meetings.id = l1_1.meeting_id AND l1_1.deleted = 0
        INNER JOIN contacts l1 ON l1.id = l1_1.contact_id AND l1.deleted = 0
        INNER JOIN j_studentsituations l2 ON l1_1.situation_id = l2.id AND l2.deleted = 0
        WHERE
        l1.id = '{$studentId}'
        AND l2.id IN " . $situationString . "
        AND meetings.date_start >= CONVERT_TZ('" . date('Y-m-d H:i:s') . "','+07:00','+00:00')
        AND meetings.session_status <> 'Cancelled'
        AND meetings.deleted <> 1
        AND l2.type IN ('Enrolled','Settle','Moving In')
        GROUP BY l2.id
        ";
        //$resultList = array();
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            $nowDb = $timedate->nowDbDate();
            $totalHour = $enrollments[$row['situation_id']]['total_hour'];
            $totalAmount = unformat_number($enrollments[$row['situation_id']]['total_amount']);
            if ($totalHour == 0) $balance = 0;
            else $balance = $totalAmount / $totalHour * $row['total_delivery_hour'];
            $enrollments[$row['situation_id']]['balance'] = format_number($balance);
            $enrollments[$row['situation_id']]['balance_hour'] = $row['total_delivery_hour'];
            //$resultList[] = $enrollments[$row['situation_id']];
        }

        return array_values($enrollments);
    }

    function get_payment_list($session, $studentId)
    {
        $error = new SoapError();

        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return;
        }

        //get all situation of student
        global $db, $timedate;
        $payments = array();
        $sql = "SELECT p.id payment_id
        , p.name payment_code 
        , p.payment_type 
        , p.payment_date  
        , (p.paid_amount + p.deposit_amount + p.payment_amount) total_amount
        , p.tuition_hours total_hours 
        , p.used_amount 
        , p.used_hours 
        , p.remain_amount 
        , p.remain_hours 
        , p.description
        , l2.name team_name
        , l3.full_name assigned_user_name

        FROM j_payment p
        INNER JOIN contacts_j_payment_1_c l1_1 ON l1_1.contacts_j_payment_1j_payment_idb = p.id AND p.deleted <> 1
        INNER JOIN contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida AND l1.deleted <> 1
        INNER JOIN teams l2 ON l2.id = p.team_id AND l2.deleted <> 1
        INNER JOIN users l3 ON l3.id = p.assigned_user_id AND l3.deleted <> 1
        WHERE p.deleted <> 1
        AND l1.id = '{$studentId}'
        ORDER BY p.payment_date DESC
        ";
        $result = $db->query($sql);    
        while ($row = $db->fetchByAssoc($result)) {
            $nowDb = $timedate->nowDbDate();
            $payments[$row['payment_id']] = array(
                'payment_id' => $row['payment_id'],   
                'payment_code' => $row['payment_code'],   
                'payment_type' => $row['payment_type'],   
                'payment_date' => $row['payment_date'],    
                'total_amount' => $row['total_amount'],    
                'total_hours' => $row['total_hours'],    
                'used_amount' => $row['used_amount'],    
                'used_hours' => $row['used_hours'],    
                'remain_amount' => $row['remain_amount'],    
                'remain_hours' => $row['remain_hours'],    
                'description' => $row['description'],    
                'description' => $row['description'],    
                'team_name' => $row['team_name'],    
                'assigned_user_name' => $row['assigned_user_name'],    
            );    

            //Show paid amount, unpaid amount - Tung Bui 08/12/2015
            $sqlPayDtl = "SELECT DISTINCT
            IFNULL(payment_no, '') payment_no,
            IFNULL(status, '') status,
            IFNULL(invoice_number, '') invoice_number,
            IFNULL(payment_amount, '0') payment_amount
            FROM j_paymentdetail
            WHERE payment_id = '{$row['payment_id']}'
            AND deleted = 0
            AND status = 'Unpaid'
            ORDER BY payment_no DESC";
            $resultPayDtl = $db->query($sqlPayDtl);

            $paidAmount     = 0;
            $unpaidAmount   = 0; 
            while($rowPayDtl = $GLOBALS['db']->fetchByAssoc($resultPayDtl)){
                $unpaidAmount += $rowPayDtl['payment_amount'];                                                
            }    

            $payments[$row['payment_id']]['unpaid_amount'] = $unpaidAmount;
            $payments[$row['payment_id']]['paid_amount'] = $payments[$row['payment_id']]['total_amount'] - $unpaidAmount;   

            $payments[$row['payment_id']]['content'] = json_encode($payments[$row['payment_id']]);
        }      

        return array_values($payments);
    }

    function getPaymentDetail($session, $studentId, $paymentId)
    {
        $error = new SoapError();

        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return;
        }

        //get all situation of student
        global $db, $timedate;
        $payments = array();
        $sql = "SELECT p.id payment_id
        , p.name payment_code 
        , p.payment_type 
        , p.payment_date                                                       
        , p.tuition_fee tuition_fee 
        , p.tuition_hours tuition_hours 
        , p.paid_amount paid_amount 
        , p.paid_hours paid_hours 
        , p.amount_bef_discount amount_bef_discount 
        , p.total_after_discount total_after_discount 
        , p.payment_amount payment_amount 
        , p.total_hours total_hours 
        , p.used_amount 
        , p.used_hours 
        , p.deposit_amount 
        , p.remain_amount 
        , p.remain_hours 
        , p.description
        , l2.name team_name
        , CONCAT(l3.last_name,' ',l3.first_name) assigned_user_name

        FROM j_payment p
        INNER JOIN contacts_j_payment_1_c l1_1 ON l1_1.contacts_j_payment_1j_payment_idb = p.id AND p.deleted <> 1
        INNER JOIN contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida AND l1.deleted <> 1
        INNER JOIN teams l2 ON l2.id = p.team_id AND l2.deleted <> 1
        INNER JOIN users l3 ON l3.id = p.assigned_user_id AND l3.deleted <> 1
        WHERE p.deleted <> 1
        AND p.id = '{$paymentId}'
        ORDER BY p.payment_date DESC
        ";
        $result = $db->query($sql);    
        $row = $db->fetchByAssoc($result);

        $payments = array(
            'payment_id' => $row['payment_id'],   
            'payment_code' => $row['payment_code'],   
            'payment_type' => $row['payment_type'],   
            'payment_date' => $row['payment_date'],    
            'tuition_fee' => $row['tuition_fee'],    
            'tuition_hours' => $row['tuition_hours'],    
            'paid_amount' => $row['paid_amount'],    
            'paid_hours' => $row['paid_hours'],    
            'amount_bef_discount' => $row['amount_bef_discount'],    
            'total_after_discount' => $row['total_after_discount'],    
            'payment_amount' => $row['payment_amount'], 
            'total_hours' => $row['total_hours'],    
            'used_amount' => $row['used_amount'],    
            'used_hours' => $row['used_hours'],    
            'deposit_amount' => $row['deposit_amount'],    
            'remain_amount' => $row['remain_amount'],    
            'remain_hours' => $row['remain_hours'],    
            'description' => $row['description'],       
            'team_name' => $row['team_name'],    
            'assigned_user_name' => $row['assigned_user_name'],    
        );    

        //Show paid amount, unpaid amount - Tung Bui 08/12/2015
        $sqlPayDtl = "SELECT DISTINCT
        IFNULL(payment_no, '') payment_no,
        IFNULL(status, '') status,
        IFNULL(invoice_number, '') invoice_number,
        IFNULL(payment_amount, '0') payment_amount,
        IFNULL(payment_date, '0') payment_date
        FROM j_paymentdetail
        WHERE payment_id = '{$paymentId}'
        AND deleted = 0       
        AND status <> 'Canceled'  
        ORDER BY payment_no";
        $resultPayDtl = $db->query($sqlPayDtl);
        $payments['pay_detail_1_amount'] = '';
        $payments['pay_detail_1_date'] = '';
        $payments['pay_detail_2_amount'] = '';
        $payments['pay_detail_2_date'] = '';
        $payments['pay_detail_3_amount'] = '';
        $payments['pay_detail_3_date'] = '';
        $payDetailIndex = 1;
        $paidAmount     = 0;
        $unpaidAmount   = 0; 
        while($rowPayDtl = $GLOBALS['db']->fetchByAssoc($resultPayDtl)){
            if($rowPayDtl['payment_amount'] == 0) continue;

            if($rowPayDtl['status'] == 'Paid'){
                $paidAmount += $rowPayDtl['payment_amount'];    
            }
            elseif($rowPayDtl['status'] == 'Unpaid'){
                $unpaidAmount += $rowPayDtl['payment_amount']; 
                unset($rowPayDtl['payment_date']);   
            }

            $payments['pay_detail_'.$payDetailIndex.'_date'] = $rowPayDtl['payment_date'];
            $payments['pay_detail_'.$payDetailIndex.'_amount'] = number_format($rowPayDtl['payment_amount']);  
            $payments['pay_detail_'.$payDetailIndex.'_status'] = $rowPayDtl['status'];    

            $payDetailIndex++;                                                
        }    

        $payments['total_unpaid_amount'] = $unpaidAmount;
        $payments['total_paid_amount'] = $paidAmount;   

        $payments['content'] = json_encode($payments);     

        return $payments;
    }

    function getPaymentDetailTransfer($session, $studentId, $paymentId)
    {
        $error = new SoapError();

        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return;
        }

        //get all situation of student
        global $db, $timedate;
        $payment = array();
        $sql = "SELECT p.id payment_id
        , p.name payment_code 
        , p.payment_type 
        , p.payment_date                                  
        , p.payment_amount payment_amount 
        , p.total_hours total_hours     
        , p.description
        , l2.name team_name
        , CONCAT(l3.last_name,' ',l3.first_name) assigned_user_name

        FROM j_payment p
        INNER JOIN contacts_j_payment_1_c l1_1 ON l1_1.contacts_j_payment_1j_payment_idb = p.id AND p.deleted <> 1
        INNER JOIN contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida AND l1.deleted <> 1
        INNER JOIN teams l2 ON l2.id = p.team_id AND l2.deleted <> 1
        INNER JOIN users l3 ON l3.id = p.assigned_user_id AND l3.deleted <> 1
        WHERE p.deleted <> 1
        AND p.id = '{$paymentId}'
        ORDER BY p.payment_date DESC
        ";
        $result = $db->query($sql);    
        $row = $db->fetchByAssoc($result);

        $payment = array(
            'payment_id' => $row['payment_id'],   
            'payment_code' => $row['payment_code'],   
            'payment_type' => $row['payment_type'],   
            'payment_date' => $row['payment_date'],                 
            'payment_amount' => number_format($row['payment_amount']), 
            'total_hours' => number_format($row['total_hours']),      
            'description' => $row['description'],       
            'team_name' => $row['team_name'],    
            'assigned_user_name' => $row['assigned_user_name'],    
        );  

        $focus = BeanFactory::getBean('J_Payment', $paymentId);  

        if($focus->payment_type == 'Transfer In'){
            $transfer_in_payment = $focus;
            $transfer_in_student = BeanFactory::getBean("Contacts", $transfer_in_payment->contacts_j_payment_1contacts_ida);    
            $transfer_out_payment = BeanFactory::getBean("J_Payment", $focus->payment_out_id);
            $transfer_out_student = BeanFactory::getBean("Contacts", $transfer_out_payment->contacts_j_payment_1contacts_ida);    
        }   
        elseif($focus->payment_type == 'Transfer Out'){
            $transfer_out_payment = $focus;
            $transfer_out_student = BeanFactory::getBean("Contacts", $transfer_out_payment->contacts_j_payment_1contacts_ida);    
            $focus->load_relationship("ju_payment_in");
            $transfer_in_payment = reset($focus->ju_payment_in->getBeans());
            $transfer_in_student = BeanFactory::getBean("Contacts", $transfer_in_payment->contacts_j_payment_1contacts_ida);   
        }    

        $payment['tranfer_from_student'] = $transfer_out_student->full_name;                                              
        $payment['tranfer_from_center'] = $transfer_out_payment->team_name;                                              
        $payment['tranfer_to_student'] = $transfer_in_student->full_name;                                              
        $payment['tranfer_to_center'] = $transfer_in_payment->team_name;                                              

        return $payment;
    }

    function getPaymentDetailMoving($session, $studentId, $paymentId)
    {
        $error = new SoapError();

        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return;
        }

        //get all situation of student
        global $db, $timedate;
        $payment = array();
        $sql = "SELECT p.id payment_id
        , p.name payment_code 
        , p.payment_type 
        , p.payment_date                                  
        , p.payment_amount payment_amount 
        , p.total_hours total_hours     
        , p.description
        , l2.name team_name
        , CONCAT(l3.last_name,' ',l3.first_name) assigned_user_name

        FROM j_payment p
        INNER JOIN contacts_j_payment_1_c l1_1 ON l1_1.contacts_j_payment_1j_payment_idb = p.id AND p.deleted <> 1
        INNER JOIN contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida AND l1.deleted <> 1
        INNER JOIN teams l2 ON l2.id = p.team_id AND l2.deleted <> 1
        INNER JOIN users l3 ON l3.id = p.assigned_user_id AND l3.deleted <> 1
        WHERE p.deleted <> 1
        AND p.id = '{$paymentId}'
        ORDER BY p.payment_date DESC
        ";
        $result = $db->query($sql);    
        $row = $db->fetchByAssoc($result);

        $payment = array(
            'payment_id' => $row['payment_id'],   
            'payment_code' => $row['payment_code'],   
            'payment_type' => $row['payment_type'],   
            'payment_date' => $row['payment_date'],                 
            'payment_amount' => number_format($row['payment_amount']), 
            'total_hours' => number_format($row['total_hours']),      
            'description' => $row['description'],       
            'team_name' => $row['team_name'],    
            'assigned_user_name' => $row['assigned_user_name'],    
        );  

        $focus = BeanFactory::getBean('J_Payment', $paymentId);  

        if($focus->payment_type == 'Moving In'){
            $moving_in_payment = $focus;
            $moving_out_payment = BeanFactory::getBean("J_Payment", $moving_in_payment->payment_out_id);                    
        }   
        elseif($focus->payment_type == 'Moving Out'){
            $moving_out_payment = $focus;
            $focus->load_relationship("ju_payment_in");
            $moving_in_payment = reset($focus->ju_payment_in->getBeans());                                            
        }    

        $payment['moving_from_center'] = $moving_in_payment->team_name;                                               
        $payment['moving_to_center'] = $moving_out_payment->team_name;                                              

        return $payment;
    }

    function getPaymentDetailDelay($session, $studentId, $paymentId)
    {
        $error = new SoapError();

        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return;
        }

        //get all situation of student
        global $db, $timedate;
        $payment = array();
        $sql = "SELECT p.id payment_id
        , p.name payment_code 
        , p.payment_type 
        , p.payment_date                                  
        , p.payment_amount payment_amount 
        , p.total_hours total_hours     
        , p.description
        , l2.name team_name
        , CONCAT(l3.last_name,' ',l3.first_name) assigned_user_name

        FROM j_payment p
        INNER JOIN contacts_j_payment_1_c l1_1 ON l1_1.contacts_j_payment_1j_payment_idb = p.id AND p.deleted <> 1
        INNER JOIN contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida AND l1.deleted <> 1
        INNER JOIN teams l2 ON l2.id = p.team_id AND l2.deleted <> 1
        INNER JOIN users l3 ON l3.id = p.assigned_user_id AND l3.deleted <> 1
        WHERE p.deleted <> 1
        AND p.id = '{$paymentId}'
        ORDER BY p.payment_date DESC
        ";
        $result = $db->query($sql);    
        $row = $db->fetchByAssoc($result);

        $payment = array(
            'payment_id' => $row['payment_id'],   
            'payment_code' => $row['payment_code'],   
            'payment_type' => $row['payment_type'],   
            'payment_date' => $row['payment_date'],                 
            'payment_amount' => number_format($row['payment_amount']), 
            'total_hours' => number_format($row['total_hours']),      
            'description' => $row['description'],       
            'team_name' => $row['team_name'],    
            'assigned_user_name' => $row['assigned_user_name'],    
        );  

        $focus = BeanFactory::getBean('J_Payment', $paymentId);                                            

        return $payment;
    }

    function getPaymentDetailRefund($session, $studentId, $paymentId)
    {
        $error = new SoapError();

        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return;
        }

        //get all situation of student
        global $db, $timedate;
        $payment = array();
        $sql = "SELECT p.id payment_id
        , p.name payment_code 
        , p.payment_type 
        , p.payment_date                                  
        , p.payment_amount payment_amount 
        , p.refund_revenue refund_revenue     
        , p.description
        , l2.name team_name
        , CONCAT(l3.last_name,' ',l3.first_name) assigned_user_name

        FROM j_payment p
        INNER JOIN contacts_j_payment_1_c l1_1 ON l1_1.contacts_j_payment_1j_payment_idb = p.id AND p.deleted <> 1
        INNER JOIN contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida AND l1.deleted <> 1
        INNER JOIN teams l2 ON l2.id = p.team_id AND l2.deleted <> 1
        INNER JOIN users l3 ON l3.id = p.assigned_user_id AND l3.deleted <> 1
        WHERE p.deleted <> 1
        AND p.id = '{$paymentId}'
        ORDER BY p.payment_date DESC
        ";
        $result = $db->query($sql);    
        $row = $db->fetchByAssoc($result);

        $payment = array(
            'payment_id' => $row['payment_id'],   
            'payment_code' => $row['payment_code'],   
            'payment_type' => $row['payment_type'],   
            'payment_date' => $row['payment_date'],                 
            'payment_amount' => number_format($row['payment_amount']), 
            'refund_revenue' => number_format($row['refund_revenue']),      
            'description' => $row['description'],       
            'team_name' => $row['team_name'],    
            'assigned_user_name' => $row['assigned_user_name'],    
        );  

        $focus = BeanFactory::getBean('J_Payment', $paymentId);                                            

        return $payment;
    }

    function getPaymentDetailBook($session, $studentId, $paymentId)
    {
        $error = new SoapError();

        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return;
        }

        //get all situation of student
        global $db, $timedate;
        $payment = array();
        $sql = "SELECT p.id payment_id
        , p.name payment_code 
        , p.payment_type 
        , p.payment_date                              
        , p.payment_amount payment_amount         
        , p.description
        , l2.name team_name
        , CONCAT(l3.last_name,' ',l3.first_name) assigned_user_name

        FROM j_payment p
        INNER JOIN contacts_j_payment_1_c l1_1 ON l1_1.contacts_j_payment_1j_payment_idb = p.id AND p.deleted <> 1
        INNER JOIN contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida AND l1.deleted <> 1
        INNER JOIN teams l2 ON l2.id = p.team_id AND l2.deleted <> 1
        INNER JOIN users l3 ON l3.id = p.assigned_user_id AND l3.deleted <> 1
        WHERE p.deleted <> 1
        AND p.id = '{$paymentId}'
        ORDER BY p.payment_date DESC
        ";
        $result = $db->query($sql);    
        $row = $db->fetchByAssoc($result);

        $payment = array(
            'payment_id' => $row['payment_id'],   
            'payment_code' => $row['payment_code'],   
            'payment_type' => $row['payment_type'],   
            'payment_date' => $row['payment_date'],    
            'payment_amount' => $row['payment_amount'],       
            'description' => $row['description'],       
            'team_name' => $row['team_name'],    
            'assigned_user_name' => $row['assigned_user_name'],    
        );    

        //Show paid amount, unpaid amount - Tung Bui 08/12/2015
        $sqlPayDtl = "SELECT DISTINCT
        IFNULL(payment_no, '') payment_no,
        IFNULL(status, '') status,
        IFNULL(invoice_number, '') invoice_number,
        IFNULL(payment_amount, '0') payment_amount,
        IFNULL(payment_date, '0') payment_date
        FROM j_paymentdetail
        WHERE payment_id = '{$paymentId}'
        AND deleted = 0       
        AND status <> 'Canceled'  
        ORDER BY payment_no";
        $resultPayDtl = $db->query($sqlPayDtl);  
        $payDetailIndex = 1;
        $paidAmount     = 0;
        $unpaidAmount   = 0; 
        while($rowPayDtl = $GLOBALS['db']->fetchByAssoc($resultPayDtl)){
            if($rowPayDtl['payment_amount'] == 0) continue;

            if($rowPayDtl['status'] == 'Paid'){
                $paidAmount += $rowPayDtl['payment_amount'];    
            }
            elseif($rowPayDtl['status'] == 'Unpaid'){
                $unpaidAmount += $rowPayDtl['payment_amount']; 
                unset($rowPayDtl['payment_date']);   
            }                                              
        }    

        $payment['total_unpaid_amount'] = $unpaidAmount;
        $payment['total_paid_amount'] = $paidAmount;       
        $bookList = $this->getHtmlAddRow($paymentId);       
        $payment['book_list'] = $bookList['html'];        
        
        return $payment;
    }         

    function getHtmlAddRow($payment_id){
        $q1 = "SELECT DISTINCT
        IFNULL(l3.id, '') book_id,
        IFNULL(l3.name, '') book_name,
        IFNULL(j_inventorydetail.id, '') primaryid,
        j_inventorydetail.quantity quantity,
        l3.unit unit,
        j_inventorydetail.price price,
        j_inventorydetail.amount amount,
        IFNULL(l1.id, '') l1_id,
        l1.total_amount total_amount,
        l1.total_quantity total_quantity
        FROM
        j_inventorydetail
        INNER JOIN
        j_inventory l1 ON j_inventorydetail.inventory_id = l1.id
        AND l1.deleted = 0
        INNER JOIN
        j_payment_j_inventory_1_c l2_1 ON l1.id = l2_1.j_payment_j_inventory_1j_inventory_idb
        AND l2_1.deleted = 0
        INNER JOIN
        j_payment l2 ON l2.id = l2_1.j_payment_j_inventory_1j_payment_ida
        AND l2.deleted = 0
        INNER JOIN
        product_templates l3 ON j_inventorydetail.book_id = l3.id
        AND l3.deleted = 0
        WHERE
        (((l2.id = '$payment_id')))
        AND j_inventorydetail.deleted = 0";
        $rs1 = $GLOBALS['db']->query($q1);
        $tpl_addrow = '';
        while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
            $tpl_addrow .= "<tr class='row_tpl'>";
            $tpl_addrow .= '<td>'.$row['book_name'].'</td>';
            $tpl_addrow .= '<td>'.$row['unit'].'</td>';
            $tpl_addrow .= '<td>'.$row['quantity'].'</td>';
            $tpl_addrow .= '<td>'.format_number($row['price']).'</td>';
            $tpl_addrow .= '</tr>';
            $totalAmount = $row['total_amount'];
            $total_quantity = $row['total_quantity'];
        }

        return array(
            'html' => $tpl_addrow,
            'total_amount' => $totalAmount,
            'total_quantity' => $total_quantity,
        );;
    }

    // Get schedule that belong to the given student id
    function get_schedules($session, $studentId)
    {
        $error = new SoapError();

        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', 'Meetings', 'read', 'no_access', $error)) {
            return;
        }

        global $db, $timedate;
        $schedules = array();

        $sql = "SELECT DISTINCT
        m.id,
        DATE(m.date_start) AS date,
        m.date_start,
        m.date_end,
        c.name AS session_name,
        c.name class_name,
        IFNULL(tc.full_teacher_name, '') AS teacher_name,
        m.duration_cal duration,
        '' as room_name
        , IFNULL(a.id, '') AS atd_id
        , IFNULL(a.attended, 0) AS attended
        , IFNULL(a.homework, 0) AS homework
        , IFNULL(a.description, '') AS teacher_comment
        FROM
        meetings m
        INNER JOIN
        meetings_contacts mc ON (m.id = mc.meeting_id AND mc.deleted = 0)                
        AND m.meeting_type = 'Session'
        AND m.session_status != 'Canceled'
        AND m.deleted = 0
        AND mc.contact_id = '$studentId'
        INNER JOIN
        j_class c ON (m.ju_class_id = c.id AND c.deleted = 0)
        AND c.kind_of_course <> 'Outing Trip'
        LEFT JOIN
        c_teachers tc ON tc.id = m.teacher_id AND tc.deleted = 0                                                                            
        LEFT JOIN c_attendance a ON a.student_id = '$studentId' AND a.meeting_id = m.id
        ORDER BY m.date_start DESC";
        $result = $db->query($sql);

        // Fetch the data and return to the client
        while ($row = $db->fetchByAssoc($result)) {
            $schedules[] = $row;
        }

        return $schedules;
    }

    /**
    * get gradebook detail of student in class
    *
    * @param mixed $session
    * @param mixed $student_id
    * @param mixed $class_id
    *
    * @author Trung Nguyen 2016.06.01
    */
    function getGradebookDetail($session, $student_id, $class_id){
        $error = new SoapError();
        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', 'J_Gradebook', 'read', 'no_access', $error))
            return;

        $sql = "SELECT DISTINCT
        IFNULL(j_gradebookdetail.id, '') primaryid,
        IFNULL(l2.id, '') gradebook_id,
        l2.type gradebook_type,
        l2.minitest minitest,
        IFNULL(l3.id, '') class_id,
        IFNULL(l1.id, '') student_id,
        IFNULL(j_gradebookdetail.certificate_level, '') certificate_level,
        IFNULL(j_gradebookdetail.certificate_type, '') certificate_type
        FROM
        j_gradebookdetail
        INNER JOIN
        contacts l1 ON j_gradebookdetail.student_id = l1.id
        AND l1.deleted = 0
        INNER JOIN
        j_gradebook l2 ON j_gradebookdetail.gradebook_id = l2.id
        AND l2.deleted = 0
        INNER JOIN
        j_class_j_gradebook_1_c l3_1 ON l2.id = l3_1.j_class_j_gradebook_1j_gradebook_idb
        AND l3_1.deleted = 0
        INNER JOIN
        j_class l3 ON l3.id = l3_1.j_class_j_gradebook_1j_class_ida
        AND l3.deleted = 0
        WHERE
        (((l1.id = '$student_id')
        AND (l3.id = '$class_id')
        /**! AND (l2.status = 'Approved') */ ))
        AND j_gradebookdetail.deleted = 0
        ORDER BY CASE WHEN
        (gradebook_type = '' OR gradebook_type IS NULL) THEN 0
        WHEN gradebook_type = 'Progress' THEN 1
        WHEN gradebook_type = 'Commitment' THEN 2
        WHEN gradebook_type = 'Overall' THEN 3
        ELSE 4
        END ASC,
        CASE WHEN
        (minitest = '' OR minitest IS NULL) THEN 0
        WHEN minitest = 'minitest1' THEN 1
        WHEN minitest = 'minitest2' THEN 2
        WHEN minitest = 'minitest3' THEN 3
        WHEN minitest = 'minitest4' THEN 4
        WHEN minitest = 'minitest5' THEN 5
        WHEN minitest = 'minitest6' THEN 6
        WHEN minitest = 'project1' THEN 7
        WHEN minitest = 'project2' THEN 8
        WHEN minitest = 'project3' THEN 9
        WHEN minitest = 'project4' THEN 10
        WHEN minitest = 'project5' THEN 11
        WHEN minitest = 'project6' THEN 12
        ELSE 13
        END ASC";
        //return array($sql);
        $result = $GLOBALS['db']->query($sql);
        $data = array();
        while($row = $GLOBALS['db']->fetchByAssoc($result)){
            $focus = BeanFactory::getBean('J_Gradebook', $row['gradebook_id']);
            $data[] = $focus->getDetailForStudent($row['student_id']);
        }
        return $data;
    }

    // Add by Lam Hai 2016.07.01
    function getCertificate($session, $studentID, $classID)
    {
        $error = new SoapError();
        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', 'J_Gradebook', 'read', 'no_access', $error)) {
            return;
        }

        require_once 'custom/include/PHPExcel/PHPExcel/Autoloader.php';
        \PhpOffice\PhpWord\Autoloader::register();
        include_once("modules/J_Class/J_Class.php");
        include_once("custom/include/utils/file_utils.php");

        global $db, $timedate;
        $forder_template_url = "custom/include/TemplateExcel/Junior/certificate";
        $forder_upload_file_url = "cache/JuniorTemplate";
        if (!file_exists($forder_upload_file_url)) {
            mkdir($forder_upload_file_url, 0777);
        }
        deleteFileInForder($forder_upload_file_url, 25);

        $class = new J_Class();
        $class->retrieve($classID);
        $kindOfCourse = $class->kind_of_course;
        $team = new Team();
        $team->retrieve($class->team_id);
        $file_name = $team->code_prefix . "_new.xlsx";
        $region = $team->region;
        $data = array();
        $studentInfo = $db->fetchOne("SELECT full_student_name, birthdate, contact_id FROM contacts WHERE id = '$studentID' ");

        $enddate = $timedate->to_display($class->end_date, $timedate->get_date_format(), 'd/m/y');

        $studentInfo['cetificateno'] = $studentInfo['contact_id'] . (str_replace('/', '', $enddate));
        if ($class->isAdultKOC()) { //case Adult
            $sql = "SELECT SUM(meetings.duration_cal) attendance
            FROM meetings
            INNER JOIN c_attendance a  ON a.meeting_id = meetings.id AND a.deleted = 0
            WHERE meetings.deleted = 0 AND meetings.ju_class_id = '" . $classID . "'
            AND meetings.session_status <> 'Cancelled'
            AND a.student_id = '" . $studentID . "'
            GROUP BY a.student_id ";
            $attendanceHour = $db->getOne($sql);

            $sql = "SELECT final_result mark
            FROM j_gradebookdetail gbdetail
            INNER JOIN j_gradebook ON j_gradebook.id = gbdetail.gradebook_id AND j_gradebook.deleted = 0
            INNER JOIN j_class_j_gradebook_1_c cg ON cg.j_class_j_gradebook_1j_gradebook_idb = j_gradebook.id AND cg.deleted = 0
            WHERE gbdetail.deleted = 0 AND cg.j_class_j_gradebook_1j_class_ida = '$classID'
            AND j_gradebook.type = 'Final'
            AND gbdetail.student_id = '$studentID'";
            $studentMark = $db->getOne($sql);

            $sql = "SELECT DISTINCT contacts.id, l1.class_code, level,
            hours, kind_of_course, level, modules, end_date ,
            SUM(meetings.duration_cal) total
            FROM contacts
            INNER JOIN meetings_contacts mc ON mc.contact_id = contacts.id  AND mc.deleted = 0
            INNER JOIN meetings ON  meetings.id = mc.meeting_id AND meetings.deleted = 0
            INNER JOIN j_class l1 ON meetings.ju_class_id = l1.id AND l1.deleted = 0
            INNER JOIN j_studentsituations ss ON ss.id = mc.situation_id AND ss.deleted = 0
            WHERE contacts.deleted = 0 AND meetings.ju_class_id = '" . $classID . "'
            AND meetings.session_status <> 'Cancelled'
            AND ss.type IN ('Enrolled','Moving In','OutStanding','Settle')
            AND contacts.id = '" . $studentID . "'
            GROUP BY contacts.id ";
            //return array($sql);

            $result2 = $db->query($sql);
            $kindCourse = $class->getKOC();

            while ($row2 = $db->fetchByAssoc($result2)) {
                $attendanceHour = (!empty($attendanceHour)) ? $attendanceHour + 0 : 0;
                $studentMark = (!empty($studentMark)) ? $studentMark + 0 : 0;
                $module = ($row2['modules'] != '') ? $row2['modules'] : "";
                $data['attendance'] = number_format($attendanceHour / $row2['total'] * 100, 0);
                $data['hours'] = number_format($row2['hours'], 0);
                $data['kind_of_course'] = $kindCourse[$row2['kind_of_course']];
                $data['end_date'] = date('F Y', strtotime($row2['end_date']));
                $data['level'] = $row2['level'];
                $data['id'] = $row2['id'];

            }
            $temp = 1;
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($forder_template_url . '/Template_Certificate_Adult.docx');
            $templateProcessor->cloneRow('Student_name', 1);
            $templateProcessor->setValue('Student_name#' . $temp, $studentInfo['full_student_name']);
            $templateProcessor->setValue('Dob#' . $temp, date('d/m/Y', strtotime($studentInfo['birthdate'])));
            $templateProcessor->setValue('Course_title#' . $temp, $data['kind_of_course']);
            $templateProcessor->setValue('Level#' . $temp, $data['level'] . $module);
            $templateProcessor->setValue('Course_hours#' . $temp, number_format($data['hours'], 0));
            $templateProcessor->setValue('Code#' . $temp, $studentInfo['cetificateno']);
            $templateProcessor->setValue('Attendance#' . $temp, number_format($data['attendance'], 0));
            $templateProcessor->setValue('Date#' . $temp, date('F Y', strtotime($data['end_date'])));

            $link = $forder_upload_file_url . "/Template_Certificate_Adult" . (md5($studentID . $classID . date('Y-m-d H:i:s'))) . ".docx";
            if (file_exists($link))
                unlink($link);
            $templateProcessor->saveAs($link);
            $result = array(
                'file_url' => $GLOBALS['sugar_config']['site_url'] . "/" . $link,
                'success' => true,
            );

        } else {     // case Junior
            $sql = "SELECT DISTINCT
            contacts.id,
            gbdetail.final_result,
            gbdetail.certificate_type,
            l1.modules,
            l1.kind_of_course,
            l1.level
            FROM contacts
            INNER JOIN j_class_contacts_1_c l1_1 ON contacts.id = l1_1.j_class_contacts_1contacts_idb AND l1_1.deleted = 0
            INNER JOIN j_class l1 ON l1.id = l1_1.j_class_contacts_1j_class_ida AND l1.deleted = 0
            INNER JOIN j_class_j_gradebook_1_c l2_1 ON l1.id = l2_1.j_class_j_gradebook_1j_class_ida AND l2_1.deleted = 0
            INNER JOIN j_gradebook l2 ON l2.id = l2_1.j_class_j_gradebook_1j_gradebook_idb AND l2.deleted = 0
            INNER JOIN j_gradebookdetail gbdetail ON gbdetail.student_id = contacts.id AND gbdetail.gradebook_id = l2.id
            WHERE l1.id='{$classID}' AND  l1.deleted=0 AND l2.type = 'Final' AND contacts.id = '{$studentID}'
            AND final_result >= 0.5 ";

            $result2 = $db->query($sql);
            $data = array();

            while ($row2 = $db->fetchByAssoc($result2)) {
                $module = ($row2['modules'] == '') ? "" : ' Module ' . $row2['modules'];
                $data['class_module'] = $row2['kind_of_course'] . ' Level ' . $row2['level'] . $module;
                $data['final_result'] = $row2['certificate_type'];
                $data['date_of_issue'] = date('Y.m.d');
            }

            // if($region == "North") {
            $templateProcessor = new \PhpOffice\PHPExcel\TemplateProcessor($forder_template_url . $file_name);
            // }
            /*else {
            //$region = "Name"    ;
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($forder_template_url.$file_name);
            }*/

            $templateProcessor->cloneRow('UserID', 1);
            $temp = 1;
            $templateProcessor->setValue('UserID#1', '');
            $templateProcessor->setValue('Name#1', $studentInfo['full_student_name']);
            $templateProcessor->setValue('Final_Results#1', $data['final_result']);
            $templateProcessor->setValue('DOB#1', date('Y.m.d', strtotime($studentInfo['birthdate'])));
            $templateProcessor->setValue('Class_Module#1', $data['class_module']);
            $templateProcessor->setValue('Code#1', $studentInfo['cetificateno']);
            $templateProcessor->setValue('Date_of_issue#1', date('Y.m.d'));

            $link = $forder_upload_file_url . "/Template_Certificate" . (md5($studentID . $classID . date('Y-m-d H:i:s'))) . ".xlsx";
            if (file_exists($link))
                unlink($link);

            $templateProcessor->saveAs($link);

            $result = array(
                'file_url' => $GLOBALS['sugar_config']['site_url'] . "/" . $link,
                'success' => true,
                'region' => $region,
            );

        }
        return $result;
    }

    //Add by Lam Hoang

    function get_lms_classes_list($session, $studentID)
    {
        $error = new SoapError();
        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', 'J_Gradebook', 'read', 'no_access', $error)) {
            return;
        }

        global $db, $timedate;

        $sql_class = "SELECT
        DISTINCT c.id, CONCAT(c.kind_of_course, ' ', c.level) koc,
        c.class_code,
        c.start_date,
        c.end_date,
        ifnull(c.is_myelt_course,'') is_myelt_course,
        CASE WHEN c.kind_of_course = 'Secondary' THEN 'MyELT' ELSE 'MyNGConnect' END as type,
        ifnull(ct.is_myelt_account,0) is_myelt_account,
        IFNULL(am.membership_link, '') membership_link
        FROM
        j_class_contacts_1_c cct
        INNER JOIN
        j_class c ON c.id = cct.j_class_contacts_1j_class_ida
        AND c.start_date <= CURRENT_DATE()
        AND cct.deleted = 0
        AND c.deleted = 0
        AND cct.j_class_contacts_1contacts_idb = '$studentID'
        AND c.kind_of_course IN ('Kindergarten', 'Primary', 'Secondary')
        INNER JOIN contacts ct on ct.id = cct.j_class_contacts_1contacts_idb
        AND ct.deleted = 0
        INNER JOIN
        j_studentsituations ss ON ss.ju_class_id = c.id
        AND ss.student_id = cct.j_class_contacts_1contacts_idb
        AND ss.type IN ('Enrolled' , 'Moving In', 'Settle', 'Outstanding')
        AND ss.deleted = 0
        LEFT JOIN
        alpha_lms_membership am
        ON am.ju_class_id = c.id
        AND am.contact_id = cct.j_class_contacts_1contacts_idb
        AND am.deleted = 0
        ORDER BY c.start_date DESC";
        $result = array();
        $rs = $db->query($sql_class);
        $direct_link = $GLOBALS['app_list_strings']['myelt_link_list'];
        $seq = 0;
        while ($row = $db->fetchByAssoc($rs)){
            $result[$seq]['class_id'] = $row['id'];
            $result[$seq]['class_code'] = $row['class_code'];
            $result[$seq]['start_date'] = $row['start_date'];
            $result[$seq]['end_date'] = $row['end_date'];
            $result[$seq]['koc'] = $row['koc'];
            $result[$seq]['type'] = $row['type'];
            $result[$seq]['is_myelt_course'] = $row['is_myelt_course'];
            $result[$seq]['is_myelt_account'] = $row['is_myelt_account'];
            $result[$seq]['membership_link'] = $row['membership_link'];
            $result[$seq]['direct_link'] = $direct_link[$row['koc']];
            $seq++;
        }
        return $result;
    }

    function get_loyalty_point($session, $studentID){
        $error = new SoapError();
        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return;
        }
        require_once("custom/include/_helper/junior_revenue_utils.php");
        $result = array();
        $result['loyalty'] = getLoyaltyPoint($studentID);

        //Get Payment Amount
        $q2 = "SELECT DISTINCT
        IFNULL(j_paymentdetail.id, '') primaryid,
        j_paymentdetail.payment_date invoice_date,
        j_paymentdetail.payment_amount payment_amount
        FROM
        j_paymentdetail
        INNER JOIN
        contacts l1 ON j_paymentdetail.student_id = l1.id
        AND l1.deleted = 0
        WHERE
        (((j_paymentdetail.numeric_vat_no > 0)
        AND ((payment_amount > 0))
        AND (l1.id = '$studentID')))
        AND j_paymentdetail.deleted = 0";
        $rows2 = $GLOBALS['db']->fetchArray($q2);
        $result['payment_list'] = $rows2;
        //result
        return $result;
    }

    function update_myelt_status($session, $module, $status, $module_id){
        $error = new SoapError();
        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', 'J_Gradebook', 'read', 'no_access', $error)) {
            return;
        }

        global $db, $timedate;

        switch ($module){
            case "contacts":
                $field_set = "is_myelt_account";
                break;
            case "j_class":
                $field_set = "is_myelt_course";
                break;
            default:
                return;
        }
        $sql_update = "UPDATE {$module} SET {$field_set} = '{$status}' WHERE id = '{$module_id}'";
        $db->query($sql_update);
        return array('status' => 'success');
    }
    function input_lms_membership($session, $student_id, $class_id, $class_code, $membership_link){
        $error = new SoapError();
        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', 'J_Gradebook', 'read', 'no_access', $error)) {
            return;
        }

        global $db, $timedate;

        $sql_insert = "INSERT INTO alpha_lms_membership
        VALUE (uuid(), '{$student_id}', '{$class_id}', '{$class_code}',0,NOW(), '{$membership_link}')";
        return $db->query($sql_insert);
    }
    /*function getThankyouTemplate($studentID, $classID) {
    global $db;
    $sql = "SELECT DISTINCT l1.`name`, contacts.contact_id, l1.end_date, l1.kind_of_course, l1.hours, l1.`level`,l1.modules, full_student_name, birthdate, users.sign, users.title, CONCAT(users.last_name, ' ', users.first_name) username
    FROM contacts
    INNER JOIN  j_class_contacts_1_c l1_1 ON contacts.id = l1_1.j_class_contacts_1contacts_idb AND l1_1.deleted = 0
    INNER JOIN  j_class l1 ON l1.id = l1_1.j_class_contacts_1j_class_ida AND l1.deleted = 0
    INNER JOIN  users  ON users.id = l1.assigned_user_id AND users.deleted = 0
    WHERE l1.id='{$classID}'
    AND  contacts.deleted=0 AND contacts.id = '{$studentID}' ";
    //return array($sql);
    $result = $db->query($sql);
    $data = array();
    while($row = $db->fetchByAssoc($result)) {
    if($row['modules'] =='')
    $module = '';
    else
    $module = ' Module '. $row['modules'];

    $certificateNumber = $row['contact_id']. str_replace('/', '', date('d/m/y', strtotime($row['end_date'])));
    $data['id'] = $studentID;
    $data['full_student_name'] = $row['full_student_name'];
    $data['class_module'] = $row['kind_of_course']. '-'. number_format($row['hours'],0). 'h Level '.$row['level']. $module;
    $data['certificate'] = $certificateNumber;
    $data['UserTitle'] = $row['title'];
    $data['UserName'] = $row['username'];
    $data['id'] = $studentID;
    if( $row['sign'] != '' )
    $data['sign'] = array('src' => 'upload/'. $rowStudent['sign'],'swh'=>'200');
    else
    $data['sign'] = '';

    }

    return $data;
    }*/
    function check_session($session)
    {
        $error = new SoapError();
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return array('status' => false);
        }
        return array('status' => true);
    }

    function generateSessionId($userId)
    {
        require_once('include/entryPoint.php');

        $user = BeanFactory::getBean('Users');
        $success = false;
        $user->retrieve($userId);

        if (!empty($user) && !empty($user->id)) {
            $success = true;
        } else {
            return "";
        }

        if ($success) {
            session_start();

            $user->loadPreferences();
            $_SESSION['is_valid_session'] = true;
            $_SESSION['ip_address'] = query_client_ip();
            $_SESSION['user_id'] = $user->id;
            $_SESSION['type'] = 'user';
            $_SESSION['avail_modules'] = self::$helperObject->get_user_module_list($user);
            $_SESSION['authenticated_user_id'] = $user->id;
            $_SESSION['unique_key'] = $sugar_config['unique_key'];

            return session_id();
        }
        return "";
    }

    //Custom EntryPoint - Lap Nguyen
    function entryPoint($session, $function, $param)
    {
        $error = new SoapError();
        // Authenticate
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', 'read', 'no_access', $error)) {
            return;
        }

        require_once('custom/include/utils/SendingData.php');

        switch ($function) {
            case 'getTeamList':
                $result = getTeamList($param);
                return $result;
                break;
            case 'getUserRoles':
                $result = getUserRoles($param);
                return $result;
                break;
            case 'getSessionBooking':
                $result = getSessionBooking($param);
                return $result;
                break;
            case 'getSSOCode':
                $result = getSSOCode($param);
                return $result;
                break;
            case 'inputBooking':
                $result = inputBooking($param);
                return $result;
                break;
            case 'checkDuplication':
                $result = checkDuplication($param);
                return $result;
                break;
            case 'cancelBooking':
                $result = cancelBooking($param);
                return $result;
                break;
            case 'historyBooking':
                $result = historyBooking($param);
                return $result;
                break;
            case 'getPaymentList':
                $result = getPaymentList($param);
                return $result;
                break;
            default;
                return 'keep me!!';
                die();
                break;
        }

    }
}

?>