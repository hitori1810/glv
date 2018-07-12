<?php
     global $timedate;
     $now = $timedate->nowDbDate();
     $q1 = "SELECT id, contact_status, enroll_balance FROM contacts WHERE deleted = 0 AND contact_status <> 'Closed'";
     $rs1 = $GLOBALS['db']->query($q1);
     while($row1 = $GLOBALS['db']->fetchByAssoc($rs1)){
       if($row1['enroll_balance'] <= 0){
         $q2 = "UPDATE contacts SET contact_status = 'Closed', closed_date = '$now' WHERE id='{$row1['id']}'";
         $GLOBALS['db']->query($q2);  
       }  
     }
?>                                        
