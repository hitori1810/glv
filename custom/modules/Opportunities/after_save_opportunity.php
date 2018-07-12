<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

    class SaveLearn {
        function updateSaveLearn(&$bean, $event, $arguments){
            if($bean->date_entered == $bean->date_modified){
                $q1 = "SELECT DISTINCT
                IFNULL(l3.id, '') l3_id,
                l3.package_type l3_package_type,
                IFNULL(l2.id, '') l2_id,
                l2.payment_amount l2_payment_amount,
                l2.payment_attempt l2_payment_attempt,
                IFNULL(l2.currency_id, '') l2_payment_amount_currency,
                IFNULL(l2.payment_method, '') l2_payment_method,
                IFNULL(l2.payment_type, '') l2_payment_type
                FROM
                opportunities
                LEFT JOIN
                c_invoices_opportunities_1_c l1_1 ON opportunities.id = l1_1.c_invoices_opportunities_1opportunities_idb
                AND l1_1.deleted = 0
                LEFT JOIN
                c_invoices l1 ON l1.id = l1_1.c_invoices_opportunities_1c_invoices_ida
                AND l1.deleted = 0
                LEFT JOIN
                c_invoices_c_payments_1_c l2_1 ON l1.id = l2_1.c_invoices_c_payments_1c_invoices_ida
                AND l2_1.deleted = 0
                LEFT JOIN
                c_payments l2 ON l2.id = l2_1.c_invoices_c_payments_1c_payments_idb
                AND l2.deleted = 0
                INNER JOIN
                c_packages_opportunities_1_c l3_1 ON opportunities.id = l3_1.c_packages_opportunities_1opportunities_idb
                AND l3_1.deleted = 0
                INNER JOIN
                c_packages l3 ON l3.id = l3_1.c_packages_opportunities_1c_packages_ida
                AND l3.deleted = 0
                WHERE
                (((opportunities.id = '{$bean->id}')))
                AND opportunities.deleted = 0
                ";
                $rs1 = $GLOBALS['db']->query($q1);
                while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
                    if($row['l3_package_type'] == 'Save&Learn'){
                        //Update Payment Type
                        $q2 = "UPDATE c_payments SET payment_method='Other', payment_type='Transfer in' WHERE id='{$row['l2_id']}'";
                        $GLOBALS['db']->query($q2); 
                    }
                    if($row['l3_package_type'] == 'Save&Learn & Collect' && $row['l2_payment_attempt'] <= 1){
                        //Update Payment Type
                        $q2 = "UPDATE c_payments SET payment_method='Other', payment_type='Transfer in' WHERE id='{$row['l2_id']}'";
                        $GLOBALS['db']->query($q2); 
                    }   
                }

            }
        }
    }  
?>
