<?php
function getLisenceOnlineCRM(){         
    /*IMPORTANT -- PLEASE INPUT BELOW INFO*/
                                                                    
    $version = 'Ultimate'; //Free, Standard, Profesional, Ultimate 
    $version = 'Free'; //Free, Standard, Profesional, Ultimate    
    $version = 'Ultimate'; //Free, Standard, Profesional, Ultimate    
    $expireDate = '2018-10-05';

    /*IMPORTANT -- PLEASE INPUT ABOVE INFO*/

    $result = array(
        'version'       => $version, 
        'expire_date'   => $expireDate,
        'limit_user'    => 0, // 0 = unlimited
        'limit_center'  => 0,
        'limit_lead'    => 0,
        'limit_student' => 0,
        'limit_mail'    => 0,
        'limit_disk'    => 0, //GB
        'limit_db'      => 0, //GB
    );

    //switch($version){
//        case "Free": 
//            $result['limit_user']       = 3;
//            $result['limit_center']     = 1;
//            $result['limit_lead']       = 10000;
//            $result['limit_student']    = 200;
//            $result['limit_disk']       = 10;
//            $result['limit_db']         = 10;
//            break;  
//        case "Standard":                            
//            $result['limit_student']    = 1000;
//            $result['limit_disk']       = 50;
//            $result['limit_db']         = 50;
//            break;  
//        case "Profesional":        
//            $result['limit_student']    = 2000;
//            $result['limit_disk']       = 100;
//            $result['limit_db']         = 100;
//            break;  
//        case "Ultimate":  
//        default:
//            break;    
//    }
//
        return $result;
}

// Added by Hieu Nguyen on 2018-04-06 to check license and limit number of users for cloud package
function checkLicense($creatingUser = false) {
    global $db;

    $info = '
    Hotline: 0935 543 543<br/>
    Email: info@onlinecrm.vn<br/>
    Website: www.onlinecrm.vn<br/>
    ';

    // Get lisence info
    $lisence = getLisenceOnlineCRM();


    // Check expire date
    if(!empty($lisence['expire_date']) && (date('Y-m-d') > $lisence['expire_date'])){
        die($GLOBALS['app_strings']['LBL_LISENCE_EXPIRIED']);  
    }                                   

    if($_REQUEST['module'] != "Home" && $_REQUEST['module'] != "lisenceinfo" && $_REQUEST['entryPoint'] != "lisenceinfo"){
        checkUserLimit();
        checkLeadLimit();
        checkStudentLimit();
        checkStorageLimit();     
    }          
}

function checkUserLimit($creatingAction = false){
    // Get lisence info
    $lisence = getLisenceOnlineCRM();   

    // Check user limit
    if(!empty($lisence['limit_user']) && $lisence['limit_user'] <> 0){
        $sql = 'SELECT count(id)
        FROM users 
        WHERE deleted = 0 
        AND id <> "1"
        AND user_name <> "supper_admin"
        AND for_portal_only <> 1
        AND (status != "Reserved" OR status IS NULL)';

        $userCount = $GLOBALS['db']->getOne($sql);

        if($creatingAction && $userCount == $lisence['limit_user']) {                                                                                                               
            $label = str_replace("limit_number",$lisence['limit_user'],$GLOBALS['app_strings']['LBL_LISENCE_WARNING_LIMIT_USERS']);
            $label = str_replace("cloud_version",$lisence['version'],$label);

            die($label);;
        }    

        if($userCount > $lisence['limit_user']) {
            $label = $GLOBALS['app_strings']['LBL_LISENCE_WARNING_LIMIT_STOP'];    
            $label .= '<br><a href="index.php?entryPoint=lisenceinfo">'.$GLOBALS['app_strings']['LBL_CHECK_LISENCE_NOW'].'</a>';
            die($label);
        } 
    } 
}

function checkLeadLimit($creatingAction = false){  
    // Get lisence info
    $lisence = getLisenceOnlineCRM();

    if(!empty($lisence['limit_lead']) && $lisence['limit_lead'] <> 0){
        $sql = 'SELECT count(id)
        FROM leads 
        WHERE deleted = 0 ';

        $leadCount = $GLOBALS['db']->getOne($sql);

        if($creatingAction && $leadCount == $lisence['limit_lead']) {
            $label = str_replace("limit_number",$lisence['limit_lead'],$GLOBALS['app_strings']['LBL_LISENCE_WARNING_LIMIT_LEADS']);
            $label = str_replace("cloud_version",$lisence['version'],$label);

            die($label);
        } 

        if($leadCount > $lisence['limit_lead']) {                           
            $label = $GLOBALS['app_strings']['LBL_LISENCE_WARNING_LIMIT_STOP'];    
            $label .= '<br><a href="index.php?entryPoint=lisenceinfo">'.$GLOBALS['app_strings']['LBL_CHECK_LISENCE_NOW'].'</a>';
            die($label);
        }    
    }  
}

function checkStudentLimit($creatingAction = false){  
    // Get lisence info
    $lisence = getLisenceOnlineCRM();

    if(!empty($lisence['limit_student']) && $lisence['limit_student'] <> 0){
        $sql = 'SELECT count(id)
        FROM contacts 
        WHERE deleted = 0 ';

        $studentCount = $GLOBALS['db']->getOne($sql);

        if($creatingAction && $studentCount == $lisence['limit_student']) {     
            $label = str_replace("limit_number",$lisence['limit_student'],$GLOBALS['app_strings']['LBL_LISENCE_WARNING_LIMIT_STUDENTS']);
            $label = str_replace("cloud_version",$lisence['version'],$label);

            die($label);
        } 

        if($studentCount > $lisence['limit_student']) {                   
            $label = $GLOBALS['app_strings']['LBL_LISENCE_WARNING_LIMIT_STOP'];    
            $label .= '<br><a href="index.php?entryPoint=lisenceinfo">'.$GLOBALS['app_strings']['LBL_CHECK_LISENCE_NOW'].'</a>';
            die($label);
        }    
    }  
}

function checkStorageLimit(){  
    // Get lisence info
    $lisence = getLisenceOnlineCRM();

    if(!empty($lisence['limit_disk']) && $lisence['limit_disk'] <> 0){  
        $used = shell_exec('du -sh ');
        $used = trim($used,"\t.\n");
        $unit = substr($used, -1);
        $used = str_replace("$unit","",$used);

        if($unit == "K"){
            $used = $used/1024/1024;    
        }
        elseif($unit == "M"){
            $used = $used/1024;
        }                      

        if($used > $lisence['limit_disk']) {                 
            $label = $GLOBALS['app_strings']['LBL_LISENCE_WARNING_LIMIT_STOP'];    
            $label .= '<br><a href="index.php?entryPoint=lisenceinfo">'.$GLOBALS['app_strings']['LBL_CHECK_LISENCE_NOW'].'</a>';
            die($label);
        } 
    }  
    if(!empty($lisence['limit_db']) && $lisence['limit_db'] <> 0){  
        $sql = "SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024 / 1024, 4) 'DB Size in MB' 
        FROM information_schema.tables
        WHERE table_schema = '{$GLOBALS['sugar_config']['dbconfig']['db_name']}'";
        $used = $GLOBALS['db']->getOne($sql);


        if($used > $lisence['limit_db']) {              
            $label = $GLOBALS['app_strings']['LBL_LISENCE_WARNING_LIMIT_STOP'];    
            $label .= '<br><a href="index.php?entryPoint=lisenceinfo">'.$GLOBALS['app_strings']['LBL_CHECK_LISENCE_NOW'].'</a>';
            die($label);
        }
    }  
}
?>
