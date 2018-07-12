<?php
    if($_POST['action_save']=='OppSaveInvoice'){
        $bean = BeanFactory::getBean('Opportunities',$_POST['record_use']);
        //update opportunity
        $sql = "UPDATE opportunities SET isinvoice='1', sales_stage='Success' WHERE id='".$bean->id."'";
        $result = $GLOBALS['db']->query($sql);

        $inv = new C_Invoices();
        $inv->invoice_date = $_POST['invoice_date'];
        $inv->amount = $bean->total_in_invoice;
        $inv->amount_in_words = $_POST['amount_in_words_invoice'];
        $inv->balance = $bean->total_in_invoice;

        $pk = BeanFactory::getBean('C_Packages',$bean->c_packages_opportunities_1c_packages_ida);

        $inv->payment_attempts = 0;

        $inv->is_company = $_POST['is_company'];
        if($inv->is_company){
            //Set Type for Student
            $sql = "UPDATE contacts SET type='Public/Corp' WHERE id='{$bean->parent_id}'";
            $result = $GLOBALS['db']->query($sql);
            $inv->company_name = $bean->company_name;
            $inv->tax_code = $bean->tax_code;
            $inv->company_address = $bean->company_address;
        }

        //Add Relationship: invoice - Opportunity
        $inv->c_invoices_opportunities_1opportunities_idb = $bean->id;

        //Add Relationship: invoice - Contact
        $inv->contacts_c_invoices_1contacts_ida = $bean->contact_id;
        $inv->currency_id= $bean->currency_id;
        $inv->description = $bean->description;

        //Team , User assign
        $inv->assigned_user_id = $bean->assigned_user_id;
        $inv->team_id = $bean->team_id;
        $inv->team_set_id = $bean->team_set_id;
        $inv->save();

        //Auto create All Payment with status = "Unpaid" - by MTN

        if($pk->isdiscount != '1'){
            $total_payment = 0;
            for($i = 1; $i <= $pk->number_of_payments; $i++){
                $rate = "payment_rate_".$i;
                $pm = new C_Payments();
                //Add Relationship: Invoice - Payment
                $pm->c_invoices_c_payments_1c_invoices_ida = $inv->id;
                //Add Relationship: Payment - Contact
                $pm->contacts_c_payments_1contacts_ida = $bean->contact_id;

                if($i != $pk->number_of_payments){
                    $pm->payment_amount = round($pk->$rate * $bean->total_in_invoice / 100000) * 1000;
                    $total_payment += $pm->payment_amount;
                }else{
                    $pm->payment_amount = $bean->total_in_invoice - $total_payment;
                }

                $pm->payment_type = 'Normal';
                $pm->payment_method = '';
                $pm->currency_id= $bean->currency_id;
                $pm->status = 'Unpaid';

                //Team , User assign
                $pm->assigned_user_id = $bean->assigned_user_id;
                $pm->team_id = $bean->team_id;
                $pm->team_set_id = $bean->team_set_id;
                if($pm->payment_amount>0)
                    $pm->save();
                else{
                    $pm->status = 'Paid';
                    $pm->payment_amount = 0;
                    $pm->payment_date = $inv->invoice_date;
                    $pm->save();
                    break;
                }
            }
        }else{
            for($i = 1; $i <= $pk->number_of_payments; $i++){
                $after_discount = "after_discount_".$i;
                $payment_type = "payment_type_".$i;
                $pm = new C_Payments();
                //Add Relationship: Invoice - Payment
                $pm->c_invoices_c_payments_1c_invoices_ida = $inv->id;
                //Add Relationship: Payment - Contact
                $pm->contacts_c_payments_1contacts_ida = $bean->contact_id;

                if($bean->discount_amount == $pk->discount_amount){
                    $pm->payment_amount = $pk->$after_discount;
                }else{
                    if($i != $pk->number_of_payments){
                        $pm->payment_amount = (round((($pk->$after_discount * $bean->total_in_invoice)/($pk->price - $pk->discount_amount - $bean->tax_amount)) / 1000)) * 1000;
                        $total_payment += $pm->payment_amount;
                    }else{
                        $pm->payment_amount = $bean->total_in_invoice - $total_payment;
                    }
                }
                $pm->payment_attempt = $i;
                $pm->payment_type = 'Normal';
                $pm->payment_method = '';
                $pm->currency_id= $bean->currency_id;
                $pm->status = 'Unpaid';
                if(!empty($pk->$payment_type)){
                    $td = $GLOBALS['timedate']->nowDbDate();
                    $option         = explode('.',$pk->$payment_type);
                    $begin_obj      = strtotime('+'.$option[1].' month',strtotime($td));
                    $pm->start_pay  = date('Y-m-d',$begin_obj);
                    $end_obj        = strtotime('+'.$option[0].' day',strtotime($pm->start_pay));
                    $pm->end_pay    = date('Y-m-d',$end_obj);
                }

                //Team , User assign
                $pm->assigned_user_id = $bean->assigned_user_id;
                $pm->team_id = $bean->team_id;
                $pm->team_set_id = $bean->team_set_id;
                if($pm->payment_amount>0)
                    $pm->save();
                else{
                    $pm->status = 'Paid';
                    $pm->payment_amount = 0;
                    $pm->save();
                    break;
                }
            }
        }
        header("Location: index.php?module=Opportunities&action=DetailView&record={$bean->id}");
    }elseif($_POST['action_save']=='OppSavePayment'){
        $opp = BeanFactory::getBean('Opportunities',$_POST['record_use']);
        //update opportunity
        $sql = "UPDATE opportunities SET ispayment='1' WHERE id='".$opp->id."';";
        $result = $GLOBALS['db']->query($sql);

        //Update Payments - by Lap Nguyen
        $pm = BeanFactory::getBean('C_Payments',$_POST['payment_id']);

        $pm->payment_method = $_POST['payment_method'];

        if($pm->payment_method == 'CreditDebitCard'){
            $pm->card_type = $_POST['card_type'];
            $pm->card_name = $_POST['card_name'];
            $pm->card_number = $_POST['card_number'];
            $pm->expiration_date = $_POST['expiration_date'];
            $pm->expiration_year = $_POST['expiration_year'];
            $pm->card_rate = $_POST['card_rate'];
            $pm->card_amount = $_POST['card_amount'];
        }elseif($pm->payment_method == 'Loan'){
            $pm->loan_type = $_POST['loan_type'];
            $pm->bank_fee_rate = $_POST['bank_fee_rate'];
            $pm->loan_fee_rate = $_POST['loan_fee_rate'];
            $pm->loan_fee_amount = $_POST['loan_fee_amount'];
            $pm->bank_fee_amount = $_POST['bank_fee_amount'];
            $pm->bank_name = $_POST['bank_name'];
        }

        $pm->payment_amount = $_POST['payment_amount'];
        if($pm->payment_type == 'Transfer in')
            $pm->payment_method = 'Other';
        $pm->status = 'Paid';
        $pm->amount_in_words = $_POST['amount_in_words_payment'];
        $pm->payment_date = $_POST['payment_date_text'];
        //Upload file
        if(isset($_FILES['uploadfile']) && $_FILES['uploadfile']['error']==0){
            $pm->filename = $_FILES['uploadfile']['name'];
            $ext = explode('.',$_FILES['uploadfile']['name']);
            $pm->file_ext = $ext[1];
            $pm->file_mine_type = $_FILES['uploadfile']['type'];
            $destination = "upload://{$pm->id}";
            if (copy($_FILES['uploadfile']['tmp_name'], $destination))
                unlink($_FILES['uploadfile']['tmp_name']);
            else
                $GLOBALS['log']->info("Can not upload attachment of Payment ID: {$pm->id}");
        }

        //Team , User assign
        $pm->assigned_user_id = $opp->assigned_user_id;
        $pm->team_id = $opp->team_id;
        $pm->team_set_id = $opp->team_set_id;
        $pm->save();

        header("Location: index.php?module=Opportunities&action=DetailView&record={$opp->id}");
    }elseif($_POST['action_save']=='InvoiceSavePayment'){
        $inv = BeanFactory::getBean('C_Invoices', $_POST['record_use']);

        //Update Payments - by Lap Nguyen
        $pm = BeanFactory::getBean('C_Payments',$_POST['payment_id']);

        $pm->payment_method = $_POST['payment_method'];

        if($pm->payment_method == 'CreditDebitCard'){
            $pm->card_type = $_POST['card_type'];
            $pm->card_name = $_POST['card_name'];
            $pm->card_number = $_POST['card_number'];
            $pm->expiration_date = $_POST['expiration_date'];
            $pm->expiration_year = $_POST['expiration_year'];
            $pm->card_rate = $_POST['card_rate'];
            $pm->card_amount = $_POST['card_amount'];
        }elseif($pm->payment_method == 'Loan'){
            $pm->loan_type = $_POST['loan_type'];
            $pm->bank_fee_rate = $_POST['bank_fee_rate'];
            $pm->loan_fee_rate = $_POST['loan_fee_rate'];
            $pm->loan_fee_amount = $_POST['loan_fee_amount'];
            $pm->bank_fee_amount = $_POST['bank_fee_amount'];
            $pm->bank_name = $_POST['bank_name'];
        }

        $pm->payment_amount = $_POST['payment_amount'];
        if($pm->payment_type == 'Transfer in')
            $pm->payment_method = 'Other';
        $pm->status = 'Paid';
        $pm->amount_in_words = $_POST['amount_in_words_payment'];
        $pm->payment_date = $_POST['payment_date_text'];
        //Upload file
        if(isset($_FILES['uploadfile']) && $_FILES['uploadfile']['error']==0){
            $pm->filename = $_FILES['uploadfile']['name'];
            $ext = explode('.',$_FILES['uploadfile']['name']);
            $pm->file_ext = $ext[1];
            $pm->file_mine_type = $_FILES['uploadfile']['type'];
            $destination = "upload://{$pm->id}";
            if (copy($_FILES['uploadfile']['tmp_name'], $destination))
                unlink($_FILES['uploadfile']['tmp_name']);
            else
                $GLOBALS['log']->info("Can not upload attachment of Payment ID: {$pm->id}");
        }

        //Team , User assign
        $pm->assigned_user_id = $inv->assigned_user_id;
        $pm->team_id = $inv->team_id;
        $pm->team_set_id = $inv->team_set_id;
        $pm->save();

        header("Location: index.php?module=C_Invoices&action=DetailView&record={$inv->id}");
    }
?>
