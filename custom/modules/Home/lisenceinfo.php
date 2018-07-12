<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings, $app_strings, $current_user, $timedate;                   

        $smarty = new Sugar_Smarty(); 
        $smarty->assign('MOD', $mod_strings); 

        // Get lisence info
        $lisence = getLisenceOnlineCRM();    

        $smarty->assign('VERSION',  strtoupper($lisence['version'])); 
        $smarty->assign('EXPIRY_DATE', $timedate->to_display_date($lisence['expire_date'], false)); 

        unset($lisence['version']);
        unset($lisence['expire_date']);

        foreach($lisence as $key => $value){
            //label limit
            if($value == 0 || empty($value)) 
                $limit =  $mod_strings['LBL_ULIMITED']; 
            else 
            $limit =  format_number($value); 

            //show class warning     
            switch($key){
                case "limit_user": 
                    $sql = 'SELECT count(id)
                    FROM users 
                    WHERE deleted = 0 
                    AND id <> "1"
                    AND user_name <> "supper_admin"
                    AND for_portal_only <> 1
                    AND (status != "Reserved" OR status IS NULL)'; 
                    $used = $GLOBALS['db']->getOne($sql); 
                    $usedFormated = $used;
                    break;
                case "limit_center":
                    $sql = 'SELECT count(id)
                    FROM teams 
                    WHERE deleted = 0 
                    AND id <> "1"
                    AND private <> 1';  
                    $used = $GLOBALS['db']->getOne($sql); 
                    $usedFormated = $used;
                    break;
                case "limit_lead": 
                    $sql = 'SELECT count(id)
                    FROM leads 
                    WHERE deleted = 0 '; 
                    $used = $GLOBALS['db']->getOne($sql);
                    $usedFormated = $used;  
                    break;
                case "limit_student": 
                    $sql = 'SELECT count(id)
                    FROM contacts 
                    WHERE deleted = 0 ';  
                    $used = $GLOBALS['db']->getOne($sql);
                    $usedFormated = $used; 
                    break;
                case "limit_mail": 
                    $sql = 'SELECt count(id)
                    FROM emails
                    WHERE deleted <> 1
                    AND type = "campaign" ';  
                    $used = $GLOBALS['db']->getOne($sql); 
                    $usedFormated = $used;
                    break;
                case "limit_disk":  
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
                    $usedFormated = round($used,2);
                    
                    break;
                case "limit_db":
                    $sql = "SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024 / 1024, 4) 'DB Size in MB' 
                    FROM information_schema.tables
                    WHERE table_schema = '{$GLOBALS['sugar_config']['dbconfig']['db_name']}'";
                    $used = $GLOBALS['db']->getOne($sql); 
                    $usedFormated = round($used,2);
                    break;
            }   

            $classWarning = "";
            $percent = 0;
            if($value != 0 && !empty($value) && $used > 0){
                $percent = ($used / $value)*100;
                if($percent >= 80){
                    $classWarning = "warning";
                }              
            }


            $smarty->assign(strtoupper($key), $limit);
            $smarty->assign(strtoupper("USED_".str_replace("limit_","",$key)), $usedFormated);  
            $smarty->assign(strtoupper("CLASS_USED_".str_replace("limit_","",$key)), $classWarning);
            $smarty->assign(strtoupper("PERCENT_".$key), round($percent,2));
        }                                         

        echo $smarty->fetch("custom/modules/Home/tpl/LisenceInfo.tpl");                         