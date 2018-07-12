<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class handleDelete {
        function deleteinvoice_payment(&$bean, $event, $arguments)
        {
            global $timedate;
            //Remove sponsor
            if(!empty($bean->sponsor_id)){
                $q0 = "UPDATE c_sponsors SET is_used = 0 WHERE id='{$bean->sponsor_id}'";
                $GLOBALS['db']->query($q0);   
            }
            //Delete Invoice
            $sql_op = "UPDATE opportunities SET sales_stage = 'Deleted', deleted = 0, description = '{$_POST['descriptions']}' WHERE id='$bean->id'";
            $result_op = $GLOBALS['db']->query($sql_op);

            $inv = BeanFactory::getBean('C_Invoices',$bean->c_invoices_opportunities_1c_invoices_ida);
            $inv->load_relationship('contacts_c_invoices_1');
            $inv->contacts_c_invoices_1->delete($inv->id);

            $sql_inv = "UPDATE c_invoices SET status = 'Deleted', description = '".$_POST['descriptions']."' WHERE id='".$inv->id."';";
            $result_inv = $GLOBALS['db']->query($sql_inv);
            //Get Relationship Invoices with all Payment to delete
            $inv->load_relationship('c_invoices_c_payments_1');
            $rel_inv = $inv->c_invoices_c_payments_1->getBeans();
            $flag = true;
            foreach ($rel_inv as $key => $value) {
                $ct = BeanFactory::getBean('Contacts',$bean->contact_id);
                //Handle Payment FreeBalance, Move, Transfer
                if($value->payment_type == 'FreeBalance' || $value->payment_type == 'Transfer in' || $value->payment_type == 'Moving in' ){
                    $sql_pay = "UPDATE c_payments SET remain = {$value->payment_amount} WHERE id='$key'";
                    $result_pay = $GLOBALS['db']->query($sql_pay);

                    //Update Free Balance
                    $free_balance = $GLOBALS['db']->getOne("SELECT free_balance WHERE id = '{$ct->id}'");  
                    $free_balance = $free_balance + $value->payment_amount;  
                    $sql = "UPDATE contacts SET free_balance = $free_balance WHERE id='{$ct->id}'";
                    $result = $GLOBALS['db']->query($sql);
                }else{
                    $value->load_relationship('contacts_c_payments_1');
                    $value->contacts_c_payments_1->delete($value->id);

                    $sql_pay = "UPDATE c_payments SET status = 'Deleted', description = '".$_POST['descriptions']."' WHERE id='$key';";
                    $result_pay = $GLOBALS['db']->query($sql_pay);

                    //Delete Enrollment Balance + Expire duration

                    $pk = BeanFactory::getBean('C_Packages',$bean->c_packages_opportunities_1c_packages_ida);
                    if($value->status == 'Paid'){
                        $ct->enroll_balance = $ct->enroll_balance - $value->payment_amount;
                        if($flag){
                            $ct->hour_balance = (int)$ct->hour_balance - (int)$pk->total_hours;
                            $flag = false;  
                        } 
                        $sql = "UPDATE contacts SET enroll_balance=".$ct->enroll_balance.", hour_balance=".$ct->hour_balance." WHERE id='{$ct->id}'";
                        $result = $GLOBALS['db']->query($sql);
                    }   
                }
            }

            //Xoa session lien quan
            $q3 = "DELETE FROM opportunities_meetings_1_c WHERE opportunities_meetings_1opportunities_ida = '{$bean->id}'";
            $GLOBALS['db']->query($q3);
            $q2 = "DELETE FROM meetings_contacts WHERE enrollment_id = '{$bean->id}' AND contact_id = '{$bean->contact_id}'";
            $GLOBALS['db']->query($q2);

            //xoa hoc vien khoi lop
            $q4 = "DELETE FROM c_classes_contacts_1_c WHERE c_classes_contacts_1contacts_idb = '{$bean->contact_id}' AND enrollment_id = '{$bean->id}'";
            $GLOBALS['db']->query($q4);
            //Xoa enrollment khoi lop
            $q5 = "DELETE FROM c_classes_opportunities_1_c WHERE c_classes_opportunities_1opportunities_idb = '{$bean->id}'";
            $GLOBALS['db']->query($q5);                     
        }
    }
?>
