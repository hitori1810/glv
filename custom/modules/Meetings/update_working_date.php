<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

    class WorkingDateMeeting {
        function updateWorkingDateMeeting(&$bean, $event, $arguments)
        {
            if($bean->parent_type == 'Leads'){
                $poten = 'Medium';
                switch ($bean->status) {
                    case 'Held':
                        $poten = 'High';
                        break;
                    case 'Not Held':
                        $poten = 'Low';
                        break;
                }

                $sql = "UPDATE leads SET date_modified ='".$GLOBALS['timedate']->nowDb()."', working_date='".subStr($bean->date_start,0,10)."', potential = '$poten' WHERE id='".$bean->parent_id."'";
                $rs = $GLOBALS['db']->query($sql);
            }    
        }
    }
?>
