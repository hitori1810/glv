<?php
    //    echo $_POST['parent_type'];
    global $timedate;

    if(!empty($_POST['parent_id'])){
        //check new sale, retention Lead
        $student_id = $_POST['parent_id'];
        if($_POST['parent_type'] == 'Leads'){
            $student_id = $GLOBALS['db']->getOne("SELECT contact_id FROM leads WHERE id = '{$_POST['parent_id']}' AND deleted = 0"); 
            if(empty($student_id)){
                echo json_encode(array("sale_stages" => "New Business"));
                die();  
            }
        }
        $q1 = "SELECT DISTINCT
        IFNULL(opportunities.id, '') primaryid,
        IFNULL(opportunities.name, '') opportunities_name,
        IFNULL(l2.id, '') l2_id,
        l2.interval_package l2_interval_package,
        opportunities.expire_date opportunities_expire_date,
        opportunities.date_closed opportunities_date_closed,
        IFNULL(opportunities.sales_stage, '') opportunities_sales_stage,
        IFNULL(opportunities.opportunity_type, '') opportunities_opportunity_type
        FROM
        opportunities
        INNER JOIN
        opportunities_contacts l1_1 ON opportunities.id = l1_1.opportunity_id
        AND l1_1.deleted = 0
        INNER JOIN
        contacts l1 ON l1.id = l1_1.contact_id
        AND l1.deleted = 0
        INNER JOIN
        c_packages_opportunities_1_c l2_1 ON opportunities.id = l2_1.c_packages_opportunities_1opportunities_idb
        AND l2_1.deleted = 0
        INNER JOIN
        c_packages l2 ON l2.id = l2_1.c_packages_opportunities_1c_packages_ida
        AND l2.deleted = 0
        WHERE
        (((l1.id = '$student_id')))
        AND opportunities.deleted = 0
        ORDER BY opportunities_date_closed DESC";

        $enroll_list = $GLOBALS['db']->fetchArray($q1); 
        // echo $enroll_list;
        if(count($enroll_list) >= 1){
            for($i =0 ; $i < count($enroll_list) ; $i++){
                $int = $enroll_list[$i]['l2_interval_package'];
                $expire_init = strtotime("+ $int month ".$enroll_list[$i]['opportunities_date_closed']);
                $expire = date('Y-m-d',$expire_init);
                $sale_date_init = strtotime($timedate->to_db_date($_POST['sale_date'],false));
                //if(($_POST['package_id'] == $enroll_list[$i]['l2_id']) && ($sale_date_init == strtotime($enroll_list[$i]['opportunities_date_closed'])) ){
                if(($_POST['package_id'] == $enroll_list[$i]['l2_id']) ){
                    echo json_encode(array("sale_stages" => $enroll_list[$i]['opportunities_opportunity_type']));
                    break;
                }
                if($sale_date_init >= $expire_init){
                    echo json_encode(array("sale_stages" => "New Business"));
                    break;
                }else{
                    echo json_encode(array("sale_stages" => "Existing Business"));
                    break;   
                }
            }   

        }else
            echo json_encode(array("sale_stages" => "New Business"));

    }else{
        echo json_encode(array(
            "sale_stages" => "",
        ));
    }

?>
