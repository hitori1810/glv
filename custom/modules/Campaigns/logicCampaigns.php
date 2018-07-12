<?php
class logicCampaigns {
    function beforeSaveCampaign(&$bean, $event, $arguments) {
        //Mass Update Lead
        if($_POST['module'] == $bean->module_name){
            if(!empty($bean->fetched_row) && $bean->fetched_row['lead_source'] != $bean->lead_source){
                $GLOBALS['db']->query("UPDATE leads SET lead_source = '{$bean->lead_source}' WHERE campaign_id = '{$bean->id}' AND lead_source = '{$bean->fetched_row['lead_source']}' AND deleted = 0");
            }
        }
    }
}