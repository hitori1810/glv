<?php
//Them 1 hoc vien Public vao 1 session
function addPubToSession($enr_id , $ss_id ){
    $student_id = $GLOBALS['db']->getOne("SELECT DISTINCT IFNULL(l1.id,'') l1_id FROM opportunities INNER JOIN opportunities_contacts l1_1 ON opportunities.id=l1_1.opportunity_id AND l1_1.deleted=0 INNER JOIN contacts l1 ON l1.id=l1_1.contact_id AND l1.deleted=0 WHERE (((opportunities.id='$enr_id' ))) AND opportunities.deleted=0");

    if(!$student_id)
        return false;

    //Check Enrollment Type
    $q2 = "SELECT sales_stage FROM opportunities WHERE id = '$enr_id'";
    $sales_stage = $GLOBALS['db']->getOne($q2);
    if($sales_stage != 'Success')
        return false;

    //Check duplicate
    $sql = "SELECT id FROM meetings_contacts WHERE meeting_id = '$ss_id' AND contact_id = '$student_id' AND enrollment_id = '$enr_id' AND deleted = 0";
    $id = $GLOBALS['db']->getOne($sql);

    if(empty($id)){
        //Them hoc vien vao session
        $sql = "INSERT INTO meetings_contacts (id, meeting_id, contact_id, required, accept_status, date_modified, deleted, enrollment_id) VALUES ('".create_guid()."', '$ss_id', '$student_id', '1', 'none', '".$GLOBALS['timedate']->nowDb()."', '0', '$enr_id')";
        $GLOBALS['db']->query($sql);

        //Them enrollment vao session
        $session    = BeanFactory::getBean('Meetings', $ss_id);
        $session->load_relationship('opportunities_meetings_1');
        $session->opportunities_meetings_1->add($enr_id);
    }
    return true;
}

//Them 1 hoac nhieu hoc vien public vao lop
function addPubToClass($enroll_id, $class_id, $comfirm = '0'){
    global $timedate;
    if(!is_array($enroll_id))
        $enroll_id =  array($enroll_id);

    $class  = BeanFactory::getBean('C_Classes', $class_id);
    foreach($enroll_id as $enr_id) {

        $student_id = $GLOBALS['db']->getOne("SELECT DISTINCT IFNULL(l1.id,'') l1_id FROM opportunities INNER JOIN opportunities_contacts l1_1 ON opportunities.id=l1_1.opportunity_id AND l1_1.deleted=0 INNER JOIN contacts l1 ON l1.id=l1_1.contact_id AND l1.deleted=0 WHERE (((opportunities.id='$enr_id' ))) AND opportunities.deleted=0");
        if(!$student_id)
            return false;

        //Check duplicate in class
        $q1 = "SELECT id FROM c_classes_contacts_1_c WHERE enrollment_id = '$enr_id' AND c_classes_contacts_1c_classes_ida = '$class_id' AND c_classes_contacts_1contacts_idb = '$student_id' AND deleted = 0";
        $id = $GLOBALS['db']->getOne($q1);

        if(empty($id)){
            //Them hoc vien vao lop
            $q2 = "INSERT INTO c_classes_contacts_1_c VALUES ('".create_guid()."', '".$timedate->nowDb()."', '0', '$class_id', '$student_id', '$enr_id', '')";
            $GLOBALS['db']->query($q2);

            //Them enrollment vao lop
            $class->load_relationship('c_classes_opportunities_1');
            $class->c_classes_opportunities_1->add($enr_id);

        }

        //Them hoc vien vao session va` enrollment vao session
        $class->load_relationship('meetings');
        $rel_cls = $class->meetings->getBeans();
        $now = strtotime($timedate->nowDb());

        foreach ($rel_cls as $session){
            $date_start = strtotime($timedate->to_db($session->date_start));
            if($comfirm == '1'){
                addPubToSession($enr_id,$session->id);
            }else{
                if($date_start > $now){
                    addPubToSession($enr_id,$session->id);
                }
            }
        }

        $q4 = "UPDATE opportunities SET added_to_class='1' WHERE id='$enr_id'";
        $GLOBALS['db']->query($q4);
        //Update Current Stage, Curent Level, Status Learning for this Student
        $q5 = "UPDATE contacts SET  contact_status = 'In Progress' WHERE id='{$student_id}'";
        $GLOBALS['db']->query($q5);

    }
    return true;
}

//Them 1 hoc vien Corp vao 1 session
function addCorpToSession($student_id , $ss_id, $ctr_id ){
    if(empty($ctr_id)){
        $ctr_id = $GLOBALS['db']->getOne("SELECT DISTINCT IFNULL(l1.id,'') l1_id FROM contacts INNER JOIN contracts_contacts l1_1 ON contacts.id=l1_1.contact_id AND l1_1.deleted=0 INNER JOIN contracts l1 ON l1.id=l1_1.contract_id AND l1.deleted=0 WHERE (((contacts.type = 'Corporate' ) AND (contacts.id='$student_id' ))) AND contacts.deleted=0");
        if(!$ctr_id)
            return false;
    }
    //Check duplicate
    $sql = "SELECT id FROM meetings_contacts WHERE meeting_id = '$ss_id' AND contact_id = '$student_id' AND contract_id = '$ctr_id' AND deleted = 0";
    $id = $GLOBALS['db']->getOne($sql);

    if(empty($id)){
        //Them hoc vien vao session
        $sql = "INSERT INTO meetings_contacts (id, meeting_id, contact_id, required, accept_status, date_modified, deleted, contract_id) VALUES ('".create_guid()."', '$ss_id', '$student_id', '1', 'none', '".$GLOBALS['timedate']->nowDb()."', '0', '$ctr_id')";
        $GLOBALS['db']->query($sql);

        //Them contract vao session
        $session    = BeanFactory::getBean('Meetings', $ss_id);
        $session->load_relationship('contracts_meetings_1');
        $session->contracts_meetings_1->add($ctr_id);
    }
    return true;
}

//Them 1 hoac nhieu hoc vien Corp vao lop
function addCorpToClass($students_id, $class_id, $ctr_id = '', $comfirm = '0'){
    global $timedate;
    if(!is_array($students_id))
        $students_id =  array($students_id);

    $class  = BeanFactory::getBean('C_Classes', $class_id);

    foreach($students_id as $student_id) {
        if(empty($ctr_id)){
            $ctr_id = $GLOBALS['db']->getOne("SELECT DISTINCT IFNULL(l1.id,'') l1_id FROM contacts INNER JOIN contracts_contacts l1_1 ON contacts.id=l1_1.contact_id AND l1_1.deleted=0 INNER JOIN contracts l1 ON l1.id=l1_1.contract_id AND l1.deleted=0 WHERE (((contacts.type = 'Corporate' ) AND (contacts.id='$student_id' ))) AND contacts.deleted=0");
            if(!$ctr_id)
                return false;
        }

        //Check duplicate in class
        $q1 = "SELECT id FROM c_classes_contacts_1_c WHERE contract_id = '$ctr_id' AND c_classes_contacts_1c_classes_ida = '$class_id' AND c_classes_contacts_1contacts_idb = '$student_id' AND deleted = 0";
        $id = $GLOBALS['db']->getOne($q1);

        if(empty($id)){
            //Them hoc vien vao lop
            $q2 = "INSERT INTO c_classes_contacts_1_c VALUES ('".create_guid()."', '".$timedate->nowDb()."', '0', '$class_id', '$student_id', '', '$ctr_id')";
            $GLOBALS['db']->query($q2);

            //Them contract vao lop
            $class->load_relationship('c_classes_contracts_1');
            $class->c_classes_contracts_1->add($ctr_id);
        }

        //Them hoc vien vao session va` Contract vao session
        $class->load_relationship('meetings');
        $rel_cls = $class->meetings->getBeans();
        $now = strtotime($timedate->nowDb());

        foreach ($rel_cls as $session){
            $date_start = strtotime($timedate->to_db($session->date_start));
            if($comfirm == '1'){
                addCorpToSession($student_id, $session->id, $ctr_id);
            }else{
                if($date_start > $now){
                    addCorpToSession($student_id, $session->id, $ctr_id);
                }
            }
        }

        //Update Current Stage, Curent Level, Status Learning for this Student
        $q5 = "UPDATE contacts SET  contact_status = 'In Progress' WHERE id='{$student_id}'";
        $GLOBALS['db']->query($q5);
    }
    return true;
}

//Xoa hoc vien Public/Corp khoi session
function removeStudentFromSession($student_id, $ss_id){
    $session = BeanFactory::getBean('Meetings',$ss_id);
    if($session->meeting_type != 'Session'){
        //unlink
        $q1 = "DELETE FROM meetings_contacts WHERE meeting_id = '$ss_id' AND contact_id = '$student_id' AND deleted = 0";
        $GLOBALS['db']->query($q1);
        return true;
    }
    //Kiểm tra để tránh nhầm lẫn với lớp Junior
    if($session->type_of_class != "Junior"){
        $type = $GLOBALS['db']->getOne("SELECT type FROM contacts WHERE id = '$student_id' AND deleted = 0");

        if($type == 'Public' || $type == 'Public/Corp' || $type == 'Corporate'){
            //Get Enrollment ID
            $enr_id         = $GLOBALS['db']->getOne("SELECT enrollment_id FROM meetings_contacts WHERE meeting_id = '$ss_id' AND contact_id = '$student_id' AND deleted = 0");
            //Get Contract ID
            $ctr_id         = $GLOBALS['db']->getOne("SELECT contract_id FROM meetings_contacts WHERE meeting_id = '$ss_id' AND contact_id = '$student_id' AND deleted = 0");

            //Xoa enrollment khoi session
            if(!empty($enr_id)){
                $q3 = "DELETE FROM opportunities_meetings_1_c WHERE opportunities_meetings_1opportunities_ida = '$enr_id' AND opportunities_meetings_1meetings_idb = '$ss_id' AND deleted = 0";
                $GLOBALS['db']->query($q3);
            }

            //Xoa contract khoi session
            if(!empty($ctr_id)){
                $q3 = "DELETE FROM contracts_meetings_1_c WHERE contracts_meetings_1contracts_ida = '$ctr_id' AND contracts_meetings_1meetings_idb = '$ss_id' AND deleted = 0";
                $GLOBALS['db']->query($q3);
            }

            //Xoa hoc vien khoi session
            $q2 = "DELETE FROM meetings_contacts WHERE meeting_id = '$ss_id' AND contact_id = '$student_id' AND deleted = 0";
            $GLOBALS['db']->query($q2);

            //Kiểm tra nếu học viên không còn buổi nào trong lớp thì xóa quan hệ Class - HV
            $class_id = $GLOBALS['db']->getOne("SELECT class_id FROM meetings WHERE id = '$ss_id'");

            $count_session = $GLOBALS['db']->getOne("SELECT DISTINCT
                count(IFNULL(meetings.id, '')) count_primary_id
                FROM
                meetings
                INNER JOIN
                c_classes l1 ON meetings.class_id = l1.id
                AND l1.deleted = 0
                INNER JOIN
                meetings_contacts l2_1 ON meetings.id = l2_1.meeting_id
                AND l2_1.deleted = 0
                INNER JOIN
                contacts l2 ON l2.id = l2_1.contact_id
                AND l2.deleted = 0
                WHERE
                (((l1.id = '$class_id')
                AND (l2.id = '$student_id')))
                AND meetings.deleted = 0");
            if($count_session == 0){
                //xoa hoc vien khoi lop
                $q3 = "DELETE FROM c_classes_contacts_1_c WHERE c_classes_contacts_1contacts_idb = '$student_id' AND c_classes_contacts_1c_classes_ida = '$class_id' AND deleted = 0";
                $GLOBALS['db']->query($q3);

                //Xoa contract khoi lop
                if(!empty($ctr_id)){

                    $q4 = "DELETE FROM c_classes_contracts_1_c WHERE c_classes_contracts_1c_classes_ida = '$class_id' AND c_classes_contracts_1contracts_idb = '$ctr_id' AND deleted = 0";
                    $GLOBALS['db']->query($q4);
                }

                //Xoa enrollment khoi lop
                if(!empty($enr_id)){
                    $q5 = "DELETE FROM c_classes_opportunities_1_c WHERE c_classes_opportunities_1c_classes_ida = '$class_id' AND c_classes_opportunities_1opportunities_idb = '$enr_id' AND deleted = 0";
                    $GLOBALS['db']->query($q5);
                }
            }

            return true;
        }else
            return false;
    }else{
        return false;
    }

}

//Xoa hoc vien Public/Corp ra khoi class
function removeFromClass($student_id , $class_id, $delay_date){
    global $timedate;
    $delay_date_php = strtotime("-7 hours ".$timedate->to_db_date($delay_date, false)." 00:00:00");

    $class      = BeanFactory::getBean('C_Classes', $class_id);
    //Xoa hoc vien ra khoi session
    $class->load_relationship('meetings');
    $rel_cls = $class->meetings->getBeans();
    foreach ($rel_cls as $session){
        $date_start = strtotime($timedate->to_db($session->date_start));
        if($date_start >= $delay_date_php){
            removeStudentFromSession($student_id, $session->id);
        }
    }
    return true;
}

//Upgrade 1 lop len 1 lop khac
function upgradeClass($old_class_id, $new_class_id){
    if($old_class_id != $new_class_id && isset($old_class_id) && isset($new_class_id)){
        $new_class = BeanFactory::getBean('C_Classes',$new_class_id);
        //get list enrollment
        $q100 = "SELECT id, l1.c_classes_opportunities_1opportunities_idb enr_id FROM c_classes_opportunities_1_c l1 WHERE l1.c_classes_opportunities_1c_classes_ida = '$old_class_id' AND deleted = 0";
        $rs100 = $GLOBALS['db']->query($q100);
        while($row = $GLOBALS['db']->fetchByAssoc($rs100)){
            addPubToClass($row['enr_id'], $new_class_id, $comfirm = '1');
        }
        //get list corporate
        $q101 = "SELECT DISTINCT IFNULL(l1.id,'') l1_id FROM c_classes INNER JOIN c_classes_contacts_1_c l1_1 ON c_classes.id=l1_1.c_classes_contacts_1c_classes_ida AND l1_1.deleted=0 INNER JOIN contacts l1 ON l1.id=l1_1.c_classes_contacts_1contacts_idb AND l1.deleted=0 WHERE (((c_classes.id='$old_class_id' ) AND (l1.type = 'Corporate' ))) AND c_classes.deleted=0";
        $rs101 = $GLOBALS['db']->query($q101);
        while($row = $GLOBALS['db']->fetchByAssoc($rs101)){
            addCorpToClass($row['l1_id'], $new_class_id, $comfirm = '1');
        }
        $q101 =  "UPDATE c_classes SET description='Đã upgrade lên lớp: {$new_class->name}' WHERE id='$old_class_id'";
        $GLOBALS['db']->query($q101);
        return true;
    }else
        return false;

}

//Move 1 student sang lop khac
function moveStudent($move_list , $new_class_id , $old_class_id, $comfirm = '0' ){
    if($old_class_id != $new_class_id && isset($old_class_id) && isset($new_class_id)){
        //Get Enrollment ID
        $enr_id = $GLOBALS['db']->getOne("SELECT enrollment_id FROM c_classes_contacts_1_c WHERE c_classes_contacts_1c_classes_ida = '$old_class_id' AND c_classes_contacts_1contacts_idb = '$st_id' AND deleted = 0");

        //Get Contract ID
        $ctr_id = $GLOBALS['db']->getOne("SELECT contract_id FROM c_classes_contacts_1_c WHERE c_classes_contacts_1c_classes_ida = '$old_class_id' AND c_classes_contacts_1contacts_idb = '$st_id' AND deleted = 0");

        foreach($move_list as $st_id){
            if(!$st_id)
                return false;
            if(!empty($enr_id))
                addPubToClass($enr_id, $new_class_id, $comfirm);
            if(!empty($ctr_id))
                addCorpToClass($student_id, $new_class_id, $ctr_id, $comfirm);
        }
        return true;
    }else return false;
}

//Remove Public
function removeSession($session_id){
    if(empty($session_id)) return false;
    $sql = "DELETE FROM meetings_contacts WHERE meeting_id ='$session_id' AND deleted = 0";
    $GLOBALS['db']->query($sql);

    $q3 = "DELETE FROM opportunities_meetings_1_c WHERE opportunities_meetings_1meetings_idb = '$session_id' AND deleted = 0";
    $GLOBALS['db']->query($q3);

    $q4 = "DELETE FROM contracts_meetings_1_c WHERE contracts_meetings_1meetings_idb = '$session_id' AND deleted = 0";
    $GLOBALS['db']->query($q4);

    $q3 = "DELETE FROM meetings WHERE id = '$session_id'";
    $GLOBALS['db']->query($q3);
    return true;
}

//Suspend student
function suspendStudent($student_id){
    global $timedate;
    //xoa ra khoi Session chua bat dau
    $q1 = "SELECT DISTINCT IFNULL(meetings.id,'') primaryid ,meetings.date_start meetings_date_start,meetings.date_end meetings_date_end FROM meetings INNER JOIN meetings_contacts l1_1 ON meetings.id=l1_1.meeting_id AND l1_1.deleted=0 INNER JOIN contacts l1 ON l1.id=l1_1.contact_id AND l1.deleted=0 WHERE (((meetings.meeting_type = 'Session' ) AND (l1.id='$student_id' ))) AND meetings.deleted=0";
    $rs1 = $GLOBALS['db']->query($q1);
    $now = strtotime($timedate->nowDb());
    while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
        $date_start = strtotime($row['meetings_date_start']);
        if($date_start > $now)
            removeStudentFromSession($student_id, $row['primaryid']);
    }

    //Update Current Stage, Curent Level, Status Learning for this Student
    $q5 = "UPDATE contacts SET contact_status = 'Stop' WHERE id='{$student_id}'";
    $GLOBALS['db']->query($q5);
    return true;
}

/////////-----Apollo Junior --- Remove Lead From Demo(Meeting)------------////////////////////////////

function removeLeadFromDemo($lead_id, $ss_id){
    //Xoa lead khoi demo
    $q2 = "DELETE FROM meetings_leads WHERE meeting_id = '$ss_id' AND lead_id = '$lead_id' AND deleted = 0";
    $GLOBALS['db']->query($q2);
    return true;
}

?>
