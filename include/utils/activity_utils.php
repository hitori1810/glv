<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    /*********************************************************************************
    * By installing or using this file, you are confirming on behalf of the entity
    * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
    * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
    * http://www.sugarcrm.com/master-subscription-agreement
    *
    * If Company is not bound by the MSA, then by installing or using this file
    * you are agreeing unconditionally that Company will be bound by the MSA and
    * certifying that you have authority to bind Company accordingly.
    *
    * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
    ********************************************************************************/


    function build_related_list_by_user_id($bean, $user_id,$where) {
        $bean_id_name = strtolower($bean->object_name).'_id';
        //Custom Show Session - By Lap Nguyen
        $only = $_REQUEST['only'];
        ($only == 'Session' && $bean->table_name =='meetings') ? 
        $select = "SELECT {$bean->table_name}.* from {$bean->table_name} " : 
        $select = "SELECT {$bean->table_name}.* from {$bean->rel_users_table},{$bean->table_name} ";

        //END - Custom Show Session
        $auto_where = ' WHERE ';
        if(!empty($where)) {
            $auto_where .= $where. ' AND ';
        }
        //Custom code: show Calendar follow $_REQUEST['only'] - By Lap Nguyen
        if($bean->table_name =='meetings'){
            switch ($only) {
                case 'Testing':
                    $auto_where .= " {$bean->rel_users_table}.{$bean_id_name}={$bean->table_name}.id AND {$bean->rel_users_table}.user_id='{$user_id}' AND {$bean->table_name}.deleted=0 AND {$bean->rel_users_table}.deleted=0 AND {$bean->table_name}.meeting_type = 'Testing'";
                    break;
                case 'Meeting':
                    $auto_where .= " {$bean->rel_users_table}.{$bean_id_name}={$bean->table_name}.id AND {$bean->rel_users_table}.user_id='{$user_id}' AND {$bean->table_name}.deleted=0 AND {$bean->rel_users_table}.deleted=0 AND {$bean->table_name}.meeting_type = 'Meeting'";
                    break;
                case 'Consultant':
                    $auto_where .= " {$bean->rel_users_table}.{$bean_id_name}={$bean->table_name}.id AND {$bean->rel_users_table}.user_id='{$user_id}' AND {$bean->table_name}.deleted=0 AND {$bean->rel_users_table}.deleted=0 AND {$bean->table_name}.meeting_type = 'Consultant'";
                    break;
                case 'Session':
                    $auto_where .= " {$bean->table_name}.deleted=0 AND {$bean->table_name}.meeting_type = 'Session'";
                    if(isset($_GET['class_id'])){
                        $_SESSION['class_id_view_on_calendar'] = $_GET['class_id'];
                    }
                    if(isset($_SESSION['class_id_view_on_calendar'])){
                        $auto_where .=" AND {$bean->table_name}.class_id = '{$_SESSION['class_id_view_on_calendar']}'";    
                    }
                    break;
                default:
                    $auto_where .= " {$bean->rel_users_table}.{$bean_id_name}={$bean->table_name}.id AND {$bean->rel_users_table}.user_id='{$user_id}' AND {$bean->table_name}.deleted=0 AND {$bean->rel_users_table}.deleted=0 AND {$bean->table_name}.meeting_type <> 'Session'";
            }
        }else      
            $auto_where .= " {$bean->rel_users_table}.{$bean_id_name}={$bean->table_name}.id AND {$bean->rel_users_table}.user_id='{$user_id}' AND {$bean->table_name}.deleted=0 AND {$bean->rel_users_table}.deleted=0";

        //END custom: By Lap Nguyen

        $bean->add_team_security_where_clause($select);

        $query = $select.$auto_where;

        $result = $bean->db->query($query, true);

        $list = array();

        while($row = $bean->db->fetchByAssoc($result)) {
            $newbean = clone $bean;
            $row = $newbean->convertRow($row);
            $newbean->fetched_row = $row;
            $newbean->fromArray($row);

            $newbean->processed_dates_times = array();
            $newbean->check_date_relationships_load();
            $newbean->fill_in_additional_detail_fields();

            $list[] = $newbean;
        }

        return $list;
    }

?>
