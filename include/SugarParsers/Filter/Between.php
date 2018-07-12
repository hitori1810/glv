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
 * This is used for handling arrays that might contain something in a list
 * @api
 */
class SugarParsers_Filter_Between extends SugarParsers_Filter_AbstractFilter
{
    /**
     * Which Variables trigger this class
     *
     * @var array
     */
    protected $variables = array('$between');

    /**
     * Text Operator
     *
     * @var string
     */
    protected $operator_text = "between";

    /**
     * Not Text Operator
     *
     * @var string
     */
    protected $operator_not_text = "not_between";

    /**
     * Save the value as an array since the value of the field must be in the array
     *
     * @param array $value
     */
    public function filter($value)
    {
        // make sure that the value is an array.
        // if it's not make it an array
        if (!is_array($value)) {
            $value = array($value);
        }

        // save the array value to the keys
        $this->value = $value;
    }

    public function getValueInputs($field_name, $table_key, $operator)
    {
        return array(
            "name" => $field_name,
            "table_key" => $table_key,
            "qualifier_name" => $operator,
            "input_name0" => $this->value[0],
            "input_name1" => $this->value[1]
        );
    }

}