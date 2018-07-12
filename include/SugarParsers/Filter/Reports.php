<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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


require_once("include/SugarParsers/Filter/AbstractFilter.php");

/**
 * This is use to trigger the query for reports_to in the reporting engine
 * @api
 */
class SugarParsers_Filter_Reports extends SugarParsers_Filter_AbstractFilter
{
    /**
     * Which Variables trigger this class
     *
     * @var array
     */
    protected $variables = array('$reports');

    /**
     * Text Operator
     *
     * @var string
     */
    protected $operator_text = 'reports_to';

    /**
     * Convert the value to an array since that is what this is looking for
     *
     * @param String $value
     */
    public function filter($value)
    {
        // this needs to be an array
        if(!is_array($value)) {
            $value = array($value);
        }
        $this->value = $value;
    }
}