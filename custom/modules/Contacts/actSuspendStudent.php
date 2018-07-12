<?php
    //add action Suspend Student - 08/08/2014 - by MTN
    if(isset($_POST['student_id'])){
        $student = BeanFactory::getBean('Contacts', $_POST['student_id']);

        if($_POST['type'] == 'Closed'){
            //Update Balance
            $free_balance = $student->free_balance + $student->enroll_balance;
            $sql = "UPDATE contacts SET free_balance=".$free_balance.", enroll_balance = 0, closed_date='{$GLOBALS['timedate']->nowDbDate()}' WHERE id='{$student->id}'";
            $GLOBALS['db']->query($sql); 
        }

        $q2 = "UPDATE contacts SET contact_status = '{$_POST['type']}' WHERE id='{$student->id}'";
        $GLOBALS['db']->query($q2);
        //Remove Student with Session is Not Started
        $q1 = "SELECT DISTINCT IFNULL(meetings.id,'') primaryid ,meetings.date_start meetings_date_start,IFNULL(meetings.meeting_type,'') meetings_meeting_type FROM meetings INNER JOIN meetings_contacts l1_1 ON meetings.id=l1_1.meeting_id AND l1_1.deleted=0 INNER JOIN contacts l1 ON l1.id=l1_1.contact_id AND l1.deleted=0 INNER JOIN c_classes l2 ON meetings.class_id=l2.id AND l2.deleted=0 WHERE (((meetings.date_start >= '{$GLOBALS['timedate']->nowDbDate()}' ) AND (meetings.meeting_type = 'Session' ) AND (l1.id='{$student->id}' ) AND (l2.type = 'Practice' ))) AND meetings.deleted=0";
        $rs1 = $GLOBALS['db']->query($q1);
        while($row1 = $GLOBALS['db']->fetchByAssoc($rs1)){
            $sql = "DELETE FROM meetings_contacts WHERE meeting_id ='{$row1['primaryid']}' AND contact_id = '{$student->id}'";
            $GLOBALS['db']->query($sql);    
        }

        //Remove Student from Class
        if($student->load_relationship('c_classes_contacts_1')){
            $rel_student_class = $student->c_classes_contacts_1->getBeans();
            foreach($rel_student_class as $class_id => $class_value){
                //Remove Student from Class
                $sql = "DELETE FROM c_classes_contacts_1_c WHERE c_classes_contacts_1c_classes_ida = '{$class_id}' AND c_classes_contacts_1contacts_idb = '{$student->id}'";
                $GLOBALS['db']->query($sql);
            }
        }


        //Move Corporate student to Not in class list
        if($student->type == "Corporate"){

            $contract = BeanFactory::getBean('Contracts', $student->contracts_contacts_1contracts_ida);
            $contract->load_relationship('contacts');
            $contract->contacts->add($student->id);

            //Remove this Student from In class list
            $sql_ctr_std = "DELETE FROM contracts_contacts_1_c WHERE contacts_c_contacts_1contacts_ida ='{$student->contracts_contacts_1contracts_ida}' AND contacts_c_contacts_1c_contacts_idb = '{$student->id}'";
            $result_ctr_std = $GLOBALS['db']->query($sql_ctr_std);
        }

        if(!empty($_POST['description'])){
            $note  = new Note();
            $note->contact_id = $_POST['student_id'];
            $note->name = $_POST['description'];
            $note->assigned_user_id = $GLOBALS['current_user']->id;
            $note->save();   
        }
        echo json_encode(array("success" => "done"));
    } else {
        echo json_encode(array("success" => "errors"));
    }
?>
