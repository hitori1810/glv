<?php
/**
* Created by PhpStorm.
* User: hoangtunglam
* Date: 05/15/2017
* Time: 10:55
*/

$sql = "select contacts.id id, contacts.assigned_user_id assigned_user_id from contacts where contacts.id not in (select j_voucher.student_id from j_voucher where j_voucher.deleted = 0) AND contacts.deleted = 0";
$rs = $GLOBALS['db']->query($sql);
$count = 0;
while ($row = $GLOBALS['db']->fetchByAssoc($rs)){
    $vou                       = new J_Voucher();
    $vou->name                 = strtoupper(create_guid_section(6));
    $vou->discount_amount      =  0;
    $vou->discount_percent     =  0;
    $vou->amount_per_used      =  0;
    $vou->status               =  'Activated';
    $vou->foc_type             =  'Referral';
    $vou->use_time             =  'N';
    $vou->team_id              =  '1';
    $vou->team_set_id          =  '1';
    $vou->student_id           =  $row['id'];
    $vou->start_date           =  '2016-01-01';
    $vou->end_date             =  '2018-12-31';
    $vou->description          =  'Mã giới thiệu bạn bè.';
    $vou->assigned_user_id     = $row['assigned_user_id'];
    $vou->save();
    $count++;
}
echo "$count Code";