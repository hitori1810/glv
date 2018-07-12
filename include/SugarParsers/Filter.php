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


require_once('include/SugarParsers/FilterDictionary.php');

/**
 * @api
 */
class SugarParsers_Filter
{
    /**
     * List of the filters
     *
     * @var array
     */
    protected $filters = array();

    /**
     * The parse filter that is ready to be decorated
     *
     * @var Array
     */
    protected $parsedFilter;

    /**
     * Default Bean to start with
     *
     * @var SugarBean
     */
    protected $bean;

    /**
     * Links from the bean
     *
     * @var array
     */
    protected $bean_links = array();

    protected $field_tree = array();

    protected $inside_or_and = false;


    public function __construct(SugarBean $bean)
    {
        $this->bean = $bean;
        $this->bean_links = $this->bean->get_linked_fields();
        $this->loadFilters();
    }

    /**
     * Parse A Json String
     *
     * @param string $json
     */
    public function parseJson($json)
    {
        $obj = json_decode($json, true);
        $this->parse($obj);
    }

    /**
     * Parse A decoded JsonString or an Array
     *
     * @param object|array $obj
     */
    public function parse($obj)
    {
        if (is_object($obj)) {
            $obj = $this->objectToArray($obj);
        }
        $this->inside_or_and = false;
        $this->parsedFilter = $this->parseFilterArray($obj);
    }

    protected $current_parent_module = null;

    /**
     *
     * @param array $array
     * @return array
     */
    protected function parseFilterArray($array)
    {
        $_filters = array();
        $stripArrayKeys = false;

        foreach ($array as $key => $value) {

            if($key === '$or') {
                $this->inside_or_and = true;
            }

            $target_module = null;
            $parent_module = null;
            if ($key == '$link' && isset($value['parent_module']) && !empty($value['parent_module'])) {
                $this->current_parent_module = $value['target_module'];
                $parent_module = $value['parent_module'];
                $target_module = $value['target_module'];
                $value = $value['_value'];
            }

            // we we have the class, lets check the value to see if it's an array and contains any more $variables
            // we can ignore this if the key is in as in requires an array of values
            $valueHasVariables = $this->valueArrayHasVariables($value);

            // if the integer is a key, it will screw up the link check, so lets get the key name from the child value
            if (is_int($key)) {
                $_key = array_shift(array_keys($value));
                if (!is_integer($_key)) {
                    $key = $_key;
                }
                $value = array_shift($value);
            }
            // make the key and value be the contents of the array
            $bean = (empty($this->current_parent_module)) ? $this->bean : BeanFactory::getBean($this->current_parent_module);
            $links = $bean->get_linked_fields();
            if (isset($links[$key])) {
                // we have a link filed.  add an and to this before the value
                $valueHasVariables = true;
                $value = array('$link' => array('_value' => array($value), 'parent_module' => $bean->module_dir, 'target_module' => $links[$key]['module']));
            }

            $_field = (isset($bean->field_defs[$key])) ? $bean->field_defs[$key] : array();
            if(isset($_field['name'])) {
                $this->field_tree[] = $_field;
            }

            // since the value is an array with no variables and there is only one, lets explode it out
            if ($valueHasVariables === false && is_array($value) && count($value) === 1) {
                $_key = array_shift(array_keys($value));
                if (!is_integer($_key)) {
                    $key = $_key;
                }
                $value = array_shift($value);
            }

            $_filterKey = count($_filters);
            if (isset($this->filters[$key])) {
                // we have a class to process
                $klass = $this->filters[$key]['class'];
            } else {
                // one doesn't exist so let make sure that the key is not a $variable
                if (substr($key, 0, 1) == "$") {
                    // we need to do something here
                    // for now we just continue
                    continue;
                }
                // just a string (field_name)
                // run the generic
                $_filterKey = $key;

                // make sure key is not a variable
                if (is_string($value) && isset($this->filters[$value])) {
                    $klass = $this->filters[$value]['class'];
                } else {
                    $klass = $this->filters['$is']['class'];
                }
            }

            /**
             * Handle if we have a control variable followed by a string which is not a variable
             */
            if (call_user_func(array($klass, 'isControlVariable')) && is_string($value)) {
                $variable = (isset($this->filters[$value])) ? $value : '$is';
                $_cvKlass = $this->filters[$variable]['class'];
                $cvKlass = new $_cvKlass();
                $cvKlass->filter($value);
                $value = $cvKlass;
                unset($_cvKlass, $cvKlass);
            }



            if (is_array($value) && $key != '$in' && $key != '$between')
            {
                // we need to parse this level
                $value = $this->parseFilterArray($value);
                if (count($value) == 1 && isset($value[0]))
                {
                    // we have one filter that is not assigned to a filed
                    // just store the filter
                    $value = $value[0];
                }
            } elseif ($key == '$in' || $key == '$between') {
                if (!is_array($value))
                {
                    $value = array($value);
                }
                else
                {
                    // take out any keys that may be there since we don't need them
                    $value = array_values($value);
                }
            }

            /* @var $klass SugarParsers_Filter_AbstractFilter */
            if ($valueHasVariables === false || call_user_func(array($klass, 'isControlVariable'))) {
                /* @var $filter SugarParsers_Filter_AbstractFilter */
                $filter = new $klass();
                $filter->filter($value);
                $_f = ($this->inside_or_and) ? end($this->field_tree) : array_pop($this->field_tree);
                if(isset($_f['name'])) {
                    $filter->setKey($_f['name']);
                } else {
                    $filter->setKey($key);
                }
                if ($filter instanceof SugarParsers_Filter_Link) {
                    $filter->setParentModule($parent_module);
                    $filter->setTargetModule($target_module);
                }
            } else {
                $filter = $value;
            }

            if (isset($_filters[$_filterKey])) {
                $stripArrayKeys = true;
                $_filterKey = null;
            }

            if(($filter instanceof SugarParsers_Filter_Or)) {
                array_pop($this->field_tree);
                $this->inside_or_and = false;
            }

            $_filters[$_filterKey] = $filter;
        }

        if ($stripArrayKeys) {
            $_filters = array_values($_filters);
        }

        return $_filters;
    }

    /**
     * Check to see if any keys in the current array have variables as the keys
     *
     * @param array $array
     * @return bool
     */
    protected function valueArrayHasVariables($array)
    {

        if (!is_array($array)) return false;

        $varKeys = array_keys($array);

        foreach ($varKeys as $key) {
            if (isset($this->filters[$key])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Getter for the ParsedObject
     *
     * @return Mixed
     */
    public function getParsedFilter()
    {
        return $this->parsedFilter;
    }

    /**
     * Convert the parseFilter into some decorated type
     *
     * @param SugarParsers_Converter_Interface $decorator
     * @return mixed
     */
    public function convert(SugarParsers_Converter_Interface $decorator)
    {
        return $decorator->convert($this->parsedFilter);
    }

    /**
     * Load the filters from the cache
     */
    protected function loadFilters()
    {
        $fd = new FilterDictionary();
        $this->filters = $fd->loadDictionaryFromStorage();

        foreach ($this->filters as $filter) {
            // load all the classes from the bean
            require_once($filter['file']);
        }
    }


    /**
     * Convert a stdClass into an array
     *
     * @param object|array $d
     * @return array
     */
    protected function objectToArray($d)
    {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /**
             * Return array converted to object
             * Using __FUNCTION__ (Magic constant)
             * for recursive call
             */
            return array_map(array(__CLASS__, __FUNCTION__), $d);
        }
        else {
            // Return array
            return $d;
        }
    }
}