<?php
/**
 * Created by PhpStorm.
 * User: hoangtunglam
 * Date: 18/11/2017
 * Time: 21:02
 */
$sql =  "SELECT
    pd.student_id,
    DATE_FORMAT(ct.date_entered, '%y%m') AS name_prefix,
    ct.assigned_user_id,
    CASE
        WHEN
            SUM(pd.payment_amount) >= 10000000
                AND SUM(pd.payment_amount) < 30000000
        THEN
            'Blue'
        WHEN
            SUM(pd.payment_amount) >= 30000000
                AND SUM(pd.payment_amount) < 100000000
        THEN
            'Gold'
        WHEN SUM(pd.payment_amount) >= 100000000 THEN 'Platinum'
        ELSE 'N/A'
    END AS rank,
    max(pd.payment_date) upgrade_date
FROM
    j_paymentdetail pd
        INNER JOIN
    (SELECT
        cp.contacts_j_payment_1contacts_ida student_id,
            IFNULL(MAX(p.payment_date), '1900-01-01') AS new_student_date
    FROM
        contacts_j_payment_1_c cp
    INNER JOIN j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
        AND p.deleted = 0
        AND cp.deleted = 0
        AND p.is_new_student = 1
    GROUP BY student_id) nstd ON nstd.student_id = pd.student_id
        AND pd.payment_date >= nstd.new_student_date
        AND pd.payment_date >= '2015-01-01'
        AND pd.status = 'Paid'
        AND pd.deleted = 0
        and pd.payment_amount > 0
        INNER JOIN
    contacts ct ON ct.id = pd.student_id AND ct.deleted = 0
        AND ct.student_type = 'Junior'
GROUP BY student_id
ORDER BY ct.date_entered";

$result = $GLOBALS['db']->fetchArray($sql);
$name_prefix = '';
$sequence = 0;
$count = 0;
foreach ($result as $key=>$value){
    if($value['name_prefix'] !== $name_prefix){
        $sequence = 1;
        $name_prefix = $value['name_prefix'];
    }

    else $sequence++;
    $name = $value['name_prefix'];
    for($i = 0; $i <4- strlen($sequence); $i++  ){
        $name .= '0';
    }
    $name .= $sequence;
    $milliseconds = round(microtime(true) * 1000);

    $member                    = new C_Memberships();
    $member->type              = $value['rank'];
    $member->student_id        = $value['student_id'];
    $member->name              = $name.substr($milliseconds , -3, 3);
    $member->upgrade_date      = $value['upgrade_date'];
    $member->assigned_user_id  = $value['assigned_user_id'];
    $member->save();
    $count++;
}
echo "Da insert thanh cong".$count."record.";
