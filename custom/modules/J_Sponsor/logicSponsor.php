<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class logicSponsor {
    function handleSaveSponsor(&$bean, $event, $arguments){
        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save'){
            if(!empty($bean->fetched_row)){//In Case Edit
                if(!empty($bean->voucher_id) && !empty($bean->payment_id)){
                    $vou = BeanFactory::getBean('J_Voucher',$bean->voucher_id);
                    $pay = BeanFactory::getBean('J_Payment',$bean->payment_id);
                    if($bean->sponsor_number != $vou->name){

                        $bean->foc_type = $vou->foc_type;
                        $bean->name     = $vou->foc_type;
                        $bean->sponsor_number = $vou->name;

                        if(!empty($bean->discount_id)){
                            $GLOBALS['db']->query("DELETE FROM j_payment_j_discount_1_c
                                WHERE j_payment_j_discount_1j_payment_ida = '{$bean->payment_id}' AND j_payment_j_discount_1j_discount_idb = '{$bean->discount_id}'");
                            $bean->discount_id = '';
                        }

                        if($bean->type == 'Discount'){
                            $sponsor_percent = unformat_number(format_number($bean->total_down / $pay->amount_bef_discount * 100,2,2) );
                            $sponsor_amount  = $bean->total_down;

                            $discount_percent = $pay->discount_percent -  $sponsor_percent;
                            $discount_amount  = $pay->discount_amount  -  $sponsor_amount;

                            $GLOBALS['db']->query("UPDATE j_payment
                                SET
                                discount_percent = $discount_percent,
                                discount_amount = $discount_amount,
                                final_sponsor_percent = $sponsor_percent,
                                final_sponsor = $sponsor_amount
                                WHERE id = '{$bean->payment_id}'");

                            $GLOBALS['db']->query("UPDATE j_paymentdetail
                                SET
                                discount_amount = $discount_amount,
                                sponsor_amount = $sponsor_amount
                                WHERE payment_id = '{$bean->payment_id}' AND is_discount = 1");

                            $bean->type     = 'Sponsor';
                        }
                    }
                }
            }
        }
    }
}

