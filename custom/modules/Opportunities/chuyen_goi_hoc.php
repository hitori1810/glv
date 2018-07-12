<?php

$pm_free = BeanFactory::getBean('C_Payments',$_GET['payment_id']);
if(!empty($_GET['payment_id'])){
	$rf= new C_Refunds();
	$rf->refund_type 						= "Transfer Enrollment";
	$rf->contacts_c_refunds_1contacts_ida 				= $pm_free->contacts_c_payments_1contacts_ida;
	$rf->opportunities_c_refunds_1opportunities_ida 	= $pm_free->drop_from_enrollment_id;
	$rf->refund_amount 						= $pm_free->payment_amount;
	$rf->refund_date 						= $pm_free->payment_date;
	$rf->refond_method 						= 'Other';
	$rf->description 						= 'Chuyển gói học';

	$rf->assigned_user_id 					= $pm_free->assigned_user_id;
	$rf->team_id 							= $pm_free->team_id;
	$rf->team_set_id 						= $pm_free->team_set_id;
	$rf->save();
	echo "DONE";	
}

?>