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


    class SugarWidgetFieldId extends SugarWidgetReportField
    {

        function queryFilterIs(&$layout_def)
        {
            return $this->_get_column_select($layout_def)."='".$GLOBALS['db']->quote($layout_def['input_name0'])."'\n";
        }
        //Add filter by Lap Nguyen
        function queryFilterone_of($layout_def) {
            $arr = array ();
            foreach ($layout_def['input_name0'] as $value)
            {
                $arr[] = $this->reporter->db->quoted($value);
            }
            $str = implode(",", $arr);
            return $this->_get_column_select($layout_def)." IN (".$str.")\n";
        }

        function queryFilternot_one_of($layout_def) {
            $arr = array ();
            foreach ($layout_def['input_name0'] as $value)
            {
                $arr[] = $this->reporter->db->quoted($value);
            }
            $str = implode(",", $arr);
            return $this->_get_column_select($layout_def)." NOT IN (".$str.")\n";
        }
		//END
    }

?>
