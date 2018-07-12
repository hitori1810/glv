<?php
include_once('include/MVC/preDispatch.php');
require_once('include/entryPoint.php');
switch ($_GET['type']) {
    case 'drop_expired':
        $result = drop_expired($_GET);
        break;
    default;
        echo 'What\'s do you want ?? ';
        die();
        break;
}


//############## CRON EXPIRED DATE - DROP TO REVENUE AUTOMATIC
function drop_expired($get){
    //die();
    $date = new DateTime('first day of last month');
    $filter_date = $date->format('Y-m-d');
    global $timedate;
    $q1 = "SELECT DISTINCT
    IFNULL(j_payment.id, '') primaryid,
    IFNULL(j_payment.name, '') payment_name,
    IFNULL(j_payment.payment_type, '') payment_type,
    j_payment.payment_expired payment_expired,
    j_payment.remain_amount remain_amount,
    j_payment.remain_hours remain_hours,
    IFNULL(l1.id, '') center_id,
    IFNULL(l1.name, '') center_name,
    IFNULL(l2.id, '') student_id,
    CONCAT(IFNULL(l2.last_name, ''),
            ' ',
            IFNULL(l2.first_name, '')) student_name,
    IFNULL(l3.id, '') user_id
FROM
    j_payment
        INNER JOIN
    teams l1 ON j_payment.team_id = l1.id
        AND l1.deleted = 0
        INNER JOIN
    contacts_j_payment_1_c l2_1 ON j_payment.id = l2_1.contacts_j_payment_1j_payment_idb
        AND l2_1.deleted = 0
        INNER JOIN
    contacts l2 ON l2.id = l2_1.contacts_j_payment_1contacts_ida
        AND l2.deleted = 0
        INNER JOIN
    users l3 ON j_payment.assigned_user_id = l3.id
        AND l3.deleted = 0
WHERE
    (((((j_payment.payment_type IN ('Deposit' , 'Delay',
        'Transfer In',
        'Moving In',
        'Transfer From AIMS',
        'Merge AIMS',
        'Placement Test',
        'Enrollment',
        'Cashholder'))
        AND (j_payment.status = 'Success')
        AND (((j_payment.remain_amount > 0)
        OR (j_payment.remain_hours > 0)))))))
        AND j_payment.deleted = 0
        AND j_payment.name IN ('TG-REF-413943' , 'TG-REF-395059',
        'TG-REF-400134',
        'TG-REF-001683',
        'TG-REF-210446',
        'TG-REF-370345',
        'TG-REF-163839',
        'TG-REF-384208',
        'TG-REF-001980',
        'TG-PAY-000259',
        'TG-PAY-002052',
        'TG-REF-401835',
        'TG-REF-372302',
        'TG-REF-392059',
        'TG-REF-416361',
        'TG-REF-143496',
        'TG-REF-089817',
        'TG-REF-156405',
        'TG-REF-413913',
        'TG-REF-394409',
        'TG-REF-403974',
        'TG-REF-392794',
        'TG-PAY-148807',
        'TG-REF-399306',
        'TG-REF-416498',
        'TG-REF-403975',
        'TG-REF-384609',
        'TG-PAY-001943',
        'TG-REF-400616',
        'TG-REF-090963',
        'TG-REF-414773',
        'TG-REF-415665',
        'TG-REF-008980',
        'TG-REF-092331',
        'TG-REF-001597',
        'TG-REF-394423',
        'TG-REF-414774',
        'TG-REF-409617',
        'TG-REF-404140',
        'TG-REF-391147',
        'TG-REF-413186',
        'TG-PAY-004921',
        'TG-REF-263033',
        'TG-REF-263031',
        'TG-REF-263035',
        'TG-PAY-304034',
        'TG-REF-377738',
        'TG-REF-416367',
        'TG-REF-087093',
        'TG-REF-002085',
        'TG-REF-372937',
        'TG-REF-161580',
        'TG-REF-399309',
        'TG-REF-368737',
        'TG-REF-403977',
        'TG-PAY-000427',
        'TG-REF-377574',
        'TG-REF-411305',
        'TG-PAY-009096',
        'TG-REF-304259',
        'HQV-REF-422471',
        'HQV-REF-383350',
        'HQV-PAY-213879',
        'HQV-PAY-221626',
        'HQV-PAY-225227',
        'HQV-PAY-220618',
        'HQV-PAY-224499',
        'HQV-REF-405922',
        'HQV-PAY-220940',
        'HQV-REF-212721',
        'HQV-REF-225449',
        'HQV-REF-421164',
        'HQV-REF-421162',
        'HQV-REF-421153',
        'HQV-REF-222792',
        'HQV-REF-421166',
        'HQV-REF-421165',
        'HQV-REF-421149',
        'HQV-PAY-214519',
        'HQV-PAY-225533',
        'HQV-PAY-225663',
        'HQV-REF-225451',
        'HQV-REF-406896',
        'HQV-REF-221576',
        'HQV-PAY-225020',
        'HQV-PAY-224576',
        'HQV-REF-225450',
        'HQV-PAY-223124',
        'HQV-PAY-223237',
        'HQV-PAY-223740',
        'HQV-PAY-224776',
        'HQV-PAY-224882',
        'HQV-PAY-221280',
        'HQV-REF-225229',
        'HQV-PAY-222743',
        'HQV-REF-418747',
        'HQV-PAY-213941',
        'HQV-REF-415732',
        'HQV-PAY-225993',
        'HQV-PAY-224121',
        'HQV-PAY-215403',
        'HQV-PAY-219631',
        'HQV-PAY-225055',
        'HQV-PAY-221692',
        'HQV-PAY-222906',
        'HQV-REF-407202',
        'HQV-PAY-215821',
        'HQV-REF-416040',
        'HQV-PAY-218508',
        'HQV-REF-427185',
        'HQV-PAY-216697',
        'HQV-REF-413171',
        'HQV-REF-420795',
        'HQV-PAY-223395',
        'HQV-PAY-222080',
        'HQV-REF-417462',
        'HQV-REF-407207',
        'HQV-PAY-225637',
        'HQV-REF-224837',
        'HQV-REF-376818',
        'HQV-REF-407206',
        'HQV-REF-407208',
        'HQV-REF-215155',
        'HQV-REF-413971',
        'HQV-PAY-225347',
        'HQV-PAY-225644',
        'HQV-PAY-224743',
        'HQV-REF-407209',
        'HQV-REF-407210',
        'HQV-PAY-221379',
        'HQV-PAY-213903',
        'HQV-PAY-222049',
        'HQV-PAY-223577',
        'HQV-REF-222907',
        'HQV-PAY-214334',
        'HQV-REF-224460',
        'HQV-REF-413994',
        'HQV-REF-223482',
        'HQV-PAY-223741',
        'HQV-PAY-218225',
        'HQV-REF-429093',
        'HQV-REF-225143',
        'HQV-REF-411732',
        'HQV-REF-430455',
        'HQV-REF-224339',
        'HQV-REF-223719',
        'HQV-REF-219519',
        'HQV-REF-423710',
        'HQV-REF-372240',
        'HQV-REF-222965',
        'HQV-PAY-220399',
        'HQV-REF-224909',
        'HQV-REF-423700',
        'HQV-PAY-220536',
        'HQV-REF-224111',
        'HQV-REF-427594',
        'HQV-PAY-372459',
        'HQV-REF-224359',
        'HQV-REF-422518',
        'HQV-PAY-224659',
        'HQV-REF-378240',
        'HQV-PAY-304573',
        'HQV-REF-414208',
        'HQV-REF-418710',
        'HQV-REF-225144',
        'HQV-REF-220921',
        'HQV-REF-414526',
        'HQV-REF-414521',
        'HQV-PAY-222066',
        'HQV-PAY-222073',
        'HQV-REF-225938',
        'HQV-REF-225217',
        'HQV-PAY-225907',
        'HQV-REF-224042',
        'HQV-PAY-218239',
        'HQV-PAY-222776',
        'HQV-PAY-218754',
        'HQV-REF-432255',
        'HQV-REF-220132',
        'HQV-REF-213777',
        'HQV-PAY-224057',
        'HQV-REF-399888',
        'HQV-REF-428656',
        'HQV-PAY-224712',
        'HQV-REF-375004',
        'HQV-REF-426131',
        'HQV-REF-410103',
        'HQV-PAY-220799',
        'HQV-REF-427060',
        'HQV-PAY-224763',
        'HQV-REF-412049',
        'HQV-REF-419373',
        'HQV-REF-422639',
        'HQV-REF-368811',
        'HQV-REF-411426',
        'HQV-PAY-225477',
        'HQV-REF-405237',
        'HQV-REF-219077',
        'HQV-REF-372238',
        'HQV-PAY-212650',
        'HQV-PAY-225181',
        'HQV-PAY-220677',
        'HQV-REF-390957',
        'HQV-PAY-225883',
        'HQV-PAY-213082',
        'HQV-PAY-223632',
        'HQV-REF-407216',
        'HQV-REF-219410',
        'HQV-REF-413985',
        'HQV-PAY-225502',
        'HQV-REF-224691',
        'HQV-PAY-214333',
        'HQV-PAY-218128',
        'HQV-PAY-224485',
        'HQV-PAY-225351',
        'HQV-PAY-225588',
        'HQV-PAY-368089',
        'HQV-REF-399612')";
    //    "";
    $count = 0;
    $drs = $GLOBALS['db']->fetchArray($q1);
    for($i = 0; $i < count($drs); $i++){
//        $q2 = "DELETE FROM c_deliveryrevenue WHERE ju_payment_id = '{$drs[$i]['primaryid']}' AND passed = 0";
//        $GLOBALS['db']->query($q2);

        $delivery = new C_DeliveryRevenue();
        $delivery->name = 'Drop revenue from payment '.$drs[$i]['payment_name'];
        $delivery->student_id = $drs[$i]['student_id'];
        //Get Payment ID
        $delivery->ju_payment_id = $drs[$i]['primaryid'];
        $delivery->type = 'Junior';
        $delivery->amount = format_number($drs[$i]['remain_amount']);
        $delivery->duration = format_number($drs[$i]['remain_hours'],2,2);
        $delivery->date_input = '2017-11-30';
        $delivery->session_id = '1';
        $delivery->passed = 0;
        $delivery->description = ' Dropped Revenue. Payment '.$drs[$i]['payment_name'].' expired at '.$timedate->to_display_date($drs[$i]['payment_expired'],false);
        $delivery->team_id = $drs[$i]['center_id'];
        $delivery->team_set_id = $drs[$i]['center_id'];
        $delivery->cost_per_hour = format_number($drs[$i]['remain_amount'] / $drs[$i]['remain_hours'],2,2);
        $delivery->assigned_user_id = $drs[$i]['user_id'];
        $delivery->revenue_type = 'Enrolled';
        $delivery->save();  ///#####

        $q3 = "UPDATE j_payment SET remain_amount=0, remain_hours=0, status='Closed'  WHERE id = '{$delivery->ju_payment_id}' AND deleted=0";
        $GLOBALS['db']->query($q3);
        $count++;
    }
    echo "Droped $count";
}

?>
