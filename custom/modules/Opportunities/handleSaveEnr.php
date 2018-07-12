<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

function saveAll($bean){
	//Handle save Lead: create New Contact
	global $timedate;
	if($bean->parent_type == 'Leads'){
		$lead = BeanFactory::getBean('Leads', $bean->parent_id);
		if(empty($lead->contact_id)){
			$ct = new Contact();

			//Quick save Student
			foreach ($lead->field_defs as $keyField => $aFieldName) {
				$ct->$keyField = $lead->$keyField;
			}
			$ct->date_modified = '';
			$ct->date_entered = '';
			$ct->contact_id = '';
			$ct->id = '';

			$ct->save();

			//Handle save C_Contact - by MTN
			if($lead->guardian_name != ""){
				$c_ct = new C_Contacts();
				$c_ct->name = $lead->guardian_name;
				$c_ct->email1 = $lead->guardian_email;
				$c_ct->address = $lead->primary_address_street;
				$c_ct->save();
				//add relation ship C_Contact with Contact
				if($c_ct->load_relationship('contacts_c_contacts_1')){
					$c_ct->contacts_c_contacts_1->add($bean->contact_id);
				}
			}

			$lead->status = 'Converted';
			$lead->opportunity_id = $bean->id;
			$lead->contact_id = $ct->id;
			$lead->opportunity_name = $bean->name;
			$lead->opportunity_amount = $bean->amount;
			$lead->save();

			//apply new student for editing Enrollment
			$bean->parent_type = 'Contacts';
			$bean->parent_name = $ct->name;
			$bean->parent_id = $ct->id;
			$bean->contact_id = $ct->id;
		}else{
			//Lead is existing
			$ct = BeanFactory::getBean('Contacts',$lead->contact_id);
			$bean->parent_type = 'Contacts';
			$bean->parent_name = $ct->name;
			$bean->parent_id = $ct->id;
			$bean->contact_id = $ct->id;
		}
	}
	//Handle save Student
	if($bean->parent_type == 'Contacts'){

		$sql = "SELECT salutation, last_name, first_name FROM contacts WHERE id = '{$bean->parent_id}'";
		$rs = $GLOBALS['db']->query($sql);
		$row = $GLOBALS['db']->fetchByAssoc($rs);

		//add relationship student - Enrollment
		if($bean->load_relationship('contacts')){
			$bean->contacts->add($bean->parent_id);
			$bean->contact_id = $bean->parent_id;
		}
	}
	//Set Enrollment name
	$bean->name = $row['salutation'] .' '.$row['last_name'] .' '.$row['first_name'] . " - ". $bean->c_packages_opportunities_1_name;

	//Handle save Invoice - Payment for Contact
	//Invoice
	if($bean->isinvoice){
		$inv = new C_Invoices();
		$inv->invoice_date = $bean->date_closed;
		$inv->amount = $bean->total_in_invoice;
		$inv->amount_in_words = $_POST['amount_in_words_invoice'];
		$inv->balance = $bean->total_in_invoice;
		$inv->currency_id= $bean->currency_id;
		$inv->description = $bean->description;
		$inv->is_company = $bean->is_company;
		$inv->payment_attempts = 0;

		if($inv->is_company){
			//Set Type for Student
			$sql = "UPDATE contacts SET type='Public/Corp' WHERE id='{$bean->parent_id}'";
			$result = $GLOBALS['db']->query($sql);
			//Add Rel Corp - Enroll
			$bean->load_relationship('accounts');
			$bean->accounts->add($_POST['company_id_temp']);
			$inv->company_name = $bean->company_name;
			$inv->tax_code = $bean->tax_code;
			$inv->company_address = $bean->company_address;
		}

		//Add Relationship: invoice - Opportunity
		$inv->c_invoices_opportunities_1opportunities_idb = $bean->id;

		//Add Relationship: invoice - Contact
		$inv->contacts_c_invoices_1contacts_ida = $bean->contact_id;

		//Team , User assign
		$inv->assigned_user_id = $bean->assigned_user_id;
		$inv->team_id = $bean->team_id;
		$inv->team_set_id = $bean->team_set_id;
		$inv->save();

		//PAYMENT
		if($bean->ispayment){
			savePayments($bean , $inv->id , $bean->payment_type);
		}
	}
}

//Create Payment with status = "Paid" - by Lap Nguyen
function savePayments($bean, $inv_id, $payment_type){
	global $timedate;
	if($payment_type != 'FreeBalance'){
		$pm = new C_Payments();
		$pm->id = create_guid();
		$pm->new_with_id = true;
		//Add Relationship: Invoice - Payment
		$pm->c_invoices_c_payments_1c_invoices_ida =  $inv_id;
		//Add Relationship: Payment - Contact
		$pm->contacts_c_payments_1contacts_ida = $bean->contact_id;

		$pm->payment_method = $bean->payment_method;
		if($pm->payment_method == 'CreditDebitCard'){
			$pm->card_type = $bean->card_type;
			$pm->card_name = $bean->card_name;
			$pm->card_number = $bean->card_number;
			$pm->expiration_date = $bean->expiration_date;
			$pm->expiration_year = $bean->expiration_year;
			$pm->card_rate = $bean->card_rate;
			$pm->card_amount = $bean->card_amount;
		}elseif($pm->payment_method == 'Loan'){
			$pm->loan_type = $bean->loan_type;
			$pm->bank_fee_rate = $bean->bank_fee_rate;
			$pm->loan_fee_rate = $bean->loan_fee_rate;
			$pm->loan_fee_amount = $bean->loan_fee_amount;
			$pm->bank_fee_amount = $bean->bank_fee_amount;
			$pm->bank_name = $bean->bank_name;
		}

		$pm->payment_amount = $bean->payment_amount;
		//Update sponsor
		if(!empty($bean->sponsor_id) && $pm->payment_type != 'Deposit'){

			$q100 = "SELECT id, amount, name, sponsor_percent FROM c_sponsors WHERE id= '{$bean->sponsor_id}' AND is_used = 0 AND deleted = 0";
			$rs100 = $GLOBALS['db']->query($q100);
			$row100 = $GLOBALS['db']->fetchByAssoc($rs100);

			if(!empty($row100['amount'])){
				$sponsor_amount = $row100['amount'];
			}else{
				$sponsor_amount = $row100['sponsor_percent'] * $bean->total_in_invoice / 100;
			}

			if($sponsor_amount > (int)$pm->payment_amount)
				$sponsor_amount = (int)$pm->payment_amount;

			$pm->sponsor_amount = $sponsor_amount;

			$q0 = "UPDATE c_sponsors SET is_used = 1 WHERE id='{$bean->sponsor_id}'";
			$GLOBALS['db']->query($q0);
		}

		$pm->payment_date = $bean->payment_date;

		$pm->status = 'Paid';
		$pm->amount_in_words = $_POST['amount_in_words_payment'];
		$pm->currency_id= $bean->currency_id;
		$pm->payment_type = $payment_type;
		if($pm->payment_type == 'Deposit')
			$pm->payment_attempt = 0;
		else
			$pm->payment_attempt = 1;

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
		$pm->assigned_user_id = $bean->assigned_user_id;
		$pm->team_id = $bean->team_id;
		$pm->team_set_id = $bean->team_set_id;
		$pm->save();
		$pk = BeanFactory::getBean('C_Packages',$bean->c_packages_opportunities_1c_packages_ida);
        $bean->total_hours = unformat_number($pk->total_hours);
		if($pk->isdiscount != '1'){
			if($payment_type == 'Normal' && $bean->payment_balance > 0){
				saveUnpaidPayments($bean, $inv_id, 2, $pm->payment_amount);
			}elseif($payment_type == 'Deposit' && $bean->payment_balance > 0){
				$current_total = savePaymentDeposit($bean, $inv_id) + $pm->payment_amount;
				saveUnpaidPayments($bean, $inv_id, 2, $current_total);
			}
		}else{
			if($payment_type == 'Normal' && $bean->payment_balance > 0){
				saveUnpaidPayments_new($bean, $inv_id, 2, $bean->payment_amount);
			}elseif($payment_type == 'Deposit' && $bean->payment_balance > 0){
				$current_total = savePaymentDeposit_new($bean, $inv_id) + $bean->payment_amount;
				saveUnpaidPayments_new($bean, $inv_id, 2, $current_total);
			}
		}
	}else{
		$inv = BeanFactory::getBean('C_Invoices',$inv_id);
		$inv->load_relationship('c_invoices_c_payments_1');
		$total_amount = $bean->total_in_invoice;

		foreach($_POST['payment_list'] as $pay_free_id){
			if($total_amount > 0){

				$inv->c_invoices_c_payments_1->add($pay_free_id);

				//update payment
				$pm_free                                        = BeanFactory::getBean('C_Payments',$pay_free_id);
				$pm_free->c_invoices_c_payments_1c_invoices_ida = $inv_id;
				$total_amount = $total_amount - $pm_free->remain;
				if($total_amount < 0){
					$pm_ = new C_Payments();
					$pm_->drop_from_enrollment_id = $pm_free->drop_from_enrollment_id;
					$pm_->contacts_c_payments_1contacts_ida = $pm_free->contacts_c_payments_1contacts_ida;
					$pm_->payment_type = 'FreeBalance';
					$pm_->payment_method = 'Other';
					$pm_->status = 'Paid';
					$pm_->payment_amount = format_number(abs($total_amount));
					$pm_->remain = format_number(abs($total_amount));
					$pm_->payment_date = $timedate->to_db_date($pm_free->payment_date, false);
					$pm_->assigned_user_id = $pm_free->assigned_user_id;
					$pm_->team_id = $pm_free->team_id;
					$pm_->team_set_id = $pm_free->team_id;
					$pm_->save();
					//Update lại Payment Amount
					$pm_free->payment_amount = $pm_free->payment_amount - abs($total_amount);
				}

				$pm_free->remain = 0;
				if($pm_free->payment_type == 'Deposit'){
					$pm_free->payment_attempt = 0;
				}
				$pm_free->save();

				//Create Tranfer Package
				if($pm_free->payment_type == 'FreeBalance'){
					$rf 									= new C_Refunds();
					$rf->refund_type 						= "Transfer Enrollment";
					$rf->contacts_c_refunds_1contacts_ida 				= $pm_free->contacts_c_payments_1contacts_ida;
					$rf->opportunities_c_refunds_1opportunities_ida 	= $pm_free->drop_from_enrollment_id;
					$rf->refund_amount 						= format_number($pm_free->payment_amount);
					$rf->refund_date 						= $bean->date_closed;
					$rf->refond_method 						= 'Other';
					$rf->description 						= 'Chuyển gói học';

					$rf->assigned_user_id 					= $pm_free->assigned_user_id;
					$rf->team_id 							= $pm_free->team_id;
					$rf->team_set_id 						= $pm_free->team_set_id;
					$rf->save();
				}
			}
		}

		//Update Invoice
		if($bean->payment_balance <= 0)
			$status = "Paid";
		else
			$status = "Unpaid";
		$query= "UPDATE c_invoices SET balance = '{$bean->payment_balance}', status = '$status' WHERE id='{$inv->id}'";
		$rs = $GLOBALS['db']->query($query);

		if($bean->payment_balance > 0)
			savePaymentMove($bean, $inv_id);
	}
}
function savePaymentDeposit($bean, $inv_id){
	$pm = new C_Payments();
	//Add Relationship: Invoice - Payment
	$pm->c_invoices_c_payments_1c_invoices_ida =  $inv_id;
	//Add Relationship: Payment - Contact
	$pm->contacts_c_payments_1contacts_ida = $bean->contact_id;

	$pm->payment_type = 'Normal';
	$pm->payment_method = '';
	$pm->currency_id= $bean->currency_id;
	//get Payment 1 after discount
	$pk = BeanFactory::getBean('C_Packages',$bean->c_packages_opportunities_1c_packages_ida);
	$paymentAmount_1 = round($pk->payment_rate_1 * $bean->total_in_invoice / 100000) * 1000;
	$pm->payment_amount = $paymentAmount_1 - $bean->payment_amount;
	$pm->status = 'Unpaid';
	$pm->payment_attempt = 1;

	//Team , User assign
	$pm->assigned_user_id = $bean->assigned_user_id;
	$pm->team_id = $bean->team_id;
	$pm->team_set_id = $bean->team_set_id;
	if($pm->payment_amount>0)
		$pm->save();
	return $pm->payment_amount;
}
function savePaymentMove($bean, $inv_id){
	$pm = new C_Payments();
	//Add Relationship: Invoice - Payment
	$pm->c_invoices_c_payments_1c_invoices_ida =  $inv_id;
	//Add Relationship: Payment - Contact
	$pm->contacts_c_payments_1contacts_ida = $bean->contact_id;

	$pm->payment_type = 'Normal';
	$pm->payment_method = '';
	$pm->currency_id= $bean->currency_id;
	$pm->payment_amount = $bean->total_in_invoice - $bean->payment_amount;
	$pm->status = 'Unpaid';
	$pm->payment_attempt = 2;

	//Team , User assign
	$pm->assigned_user_id = $bean->assigned_user_id;
	$pm->team_id = $bean->team_id;
	$pm->team_set_id = $bean->team_set_id;
	$pm->save();
}
//Auto create Payment with status = "Unpaid"
function saveUnpaidPayments($bean, $inv_id, $i, $total_payment){
	$pk = BeanFactory::getBean('C_Packages',$bean->c_packages_opportunities_1c_packages_ida);
	for($i; $i <= $pk->number_of_payments; $i++){
		$rate = "payment_rate_".$i;
		$pm = new C_Payments();
		//Add Relationship: Invoice - Payment
		$pm->c_invoices_c_payments_1c_invoices_ida = $inv_id;
		//Add Relationship: Payment - Contact
		$pm->contacts_c_payments_1contacts_ida = $bean->contact_id;

		if($i != $pk->number_of_payments){
			$pm->payment_amount = (round($pk->$rate * $bean->total_in_invoice / 100000)) * 1000;
			$total_payment += $pm->payment_amount;
		}else{
			$pm->payment_amount = $bean->total_in_invoice - $total_payment;
		}

		$pm->payment_attempt = $i;
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
	}
}
function saveUnpaidPayments_new($bean, $inv_id, $i, $total_payment){
	$pk = BeanFactory::getBean('C_Packages',$bean->c_packages_opportunities_1c_packages_ida);
	for($i; $i <= $pk->number_of_payments; $i++){
		$after_discount = "after_discount_".$i;
		$payment_type = "payment_type_".$i;
		$pm = new C_Payments();
		//Add Relationship: Invoice - Payment
		$pm->c_invoices_c_payments_1c_invoices_ida = $inv_id;
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
	}
}
function savePaymentDeposit_new($bean, $inv_id){
	$pm = new C_Payments();
	//Add Relationship: Invoice - Payment
	$pm->c_invoices_c_payments_1c_invoices_ida =  $inv_id;
	//Add Relationship: Payment - Contact
	$pm->contacts_c_payments_1contacts_ida = $bean->contact_id;

	$pm->payment_type = 'Normal';
	$pm->payment_method = '';
	$pm->currency_id= $bean->currency_id;
	$pm->payment_attempt = 1;

	//Cal deposit
	$pk = BeanFactory::getBean('C_Packages',$bean->c_packages_opportunities_1c_packages_ida);
	if($bean->discount_amount == $pk->discount_amount){
		$after_discount_1 = $pk->after_discount_1;
	}else{
		$after_discount_1 = (round((($pk->after_discount_1 * $bean->total_in_invoice)/($pk->price - $pk->discount_amount - $bean->tax_amount)) / 1000)) * 1000;
	}
	$pm->payment_amount = $after_discount_1 - $bean->payment_amount;

	$pm->status = 'Unpaid';

	if(!empty($pk->payment_type_1)){
		$td = $GLOBALS['timedate']->nowDbDate();
		$option         = explode('.',$pk->payment_type_1);
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
	return $pm->payment_amount;
}
class handleSaveEnr {
	function handleSaveEnr(&$bean, $event, $arguments)
	{
		if($bean->date_entered == $bean->date_modified){
			saveAll($bean);
		}
	}
}
?>
