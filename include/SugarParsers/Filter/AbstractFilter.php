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


require_once("include/SugarParsers/Filter/FilterInterface.php");
abstract class SugarParsers_Filter_AbstractFilter implements SugarParsers_Filter_FilterInterface
{
    /**
     * Default to false to have it ignore the class
     *
     * @var Mixed
     */
    protected $variables = false;

    /**
     * This is the filtered value,
     * It could be any number of things from Arrays to strings
     *
     * @var Mixed
     */
    protected $value;

    /**
     * This should be the key (field name) of each field
     *
     * @var String
     */
    protected $key;

    /**
     * Operator Eg: < > + - =
     *
     * @var null|string
     */
    protected $operator = null;

    /**
     * Not Operator Eg: !=
     *
     * @var null|string
     */
    protected $operator_not = null;

    /**
     * Text Version of the Operator
     *
     * @var null|string
     */
    protected $operator_text = null;

    /**
     * Text Version of the Operator when used with not
     *
     * @var null|string
     */
    protected $operator_not_text = null;

    /**
     * Just store the value so we can use it later
     *
     * @param String $value
     */
    public function filter($value)
    {
        $this->value = $value;
    }

    /**
     * Set the key (usually the field name);
     *
     * @param $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Return the set key
     *
     * @return String
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Return the variables set for each class so we have a mapping
     *
     * @return bool|Mixed
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Return the stored value
     *
     * @return Mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * This is a way to tell if the filter is a control method
     *
     * @static
     * @return bool
     */
    public static function isControlVariable()
    {
        return false;
    }

    /**
     * Return an operator
     *
     * @param bool $text        Return Text Version
     * @param bool $not         Return Not Version
     * @return null|string
     */
    public function getOperator($text = false, $not = false)
    {
        if ($text === true and $not === true) {
            return $this->operator_not_text;
        } elseif ($text === true && $not === false) {
            return $this->operator_text;
        } elseif ($text === false && $not === true) {
            return $this->operator_not;
        } else {
            return $this->operator;
        }
    }


    public function getValueInputs($field_name, $table_key, $operator)
    {
        return array(
            "name" => $field_name,
            "table_key" => $table_key,
            "qualifier_name" => $operator,
            "input_name0" => $this->value
        );
    }
}