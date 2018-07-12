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


require_once("include/SugarParsers/Converter/AbstractConverter.php");
/**
 * Convert the Filters into the Reporting Engine Format
 *
 * @api
 */
class SugarParsers_Converter_Report extends SugarParsers_Converter_AbstractConverter
{

    /**
     * Storage of all the Filters
     * @var array
     */
    protected $_reportFilters = array();

    /**
     * Default Control Statement
     *
     * @var string
     */
    protected $controlStatement = "AND";

    /**
     * @var ReportBuilder
     */
    protected $reportBuilder;

    protected $table_key = "self";

    /**
     * This is the var to hold all the link so we can build a key for them
     *
     * @var array
     */
    protected $link_path = array();

    public function __construct(ReportBuilder $reportBuilder)
    {
        $this->setReportBuilder($reportBuilder);
    }

    /**
     * Set the ReportBuilderObject
     *
     * @param ReportBuilder $reportBuilder
     */
    public function setReportBuilder(ReportBuilder $reportBuilder)
    {
        $this->reportBuilder = $reportBuilder;
    }

    /**
     * Convert the filter into a Report Engine Friendly Array
     *
     * @param mixed $value
     * @return array|mixed
     */
    public function convert($value)
    {
        $this->link_path = array('self');

        // handle when the first item is a control variable
        if ($this->checkForAndOrControlVariable(current($value))) {
            // get the first value from the array
            $value = current($value);
            // set the default operator since it's a Control Variable
            $this->controlStatement = $value->getOperator(true);
            // set the $value to the Control Variable Value
            $value = $value->getValue();
        }

        // setup the start of the filter
        $filter = array("Filter_1" => array(
            'operator' => $this->controlStatement,
        ));

        // track if the control variable changes at all.
        $hasMoreControlVariables = false;

        // keep track of the filter groups
        $filter_groups = array();

        // build filter groups
        foreach ($value as $key => $val) {
            // keep the current controlStatement to track if it changes when we parse the filter
            $startControlVariable = $this->controlStatement;
            // parse the filters
            $this->_convert($key, $val);

            // move the filters into the $filter_group variable with the current set controll statement
            $filter_groups[] = array_merge(array('operator' => $this->controlStatement), $this->_reportFilters);

            // track of the control variable change
            if ($startControlVariable !== $this->controlStatement) {
                // it has changed, set the flag to true.
                $hasMoreControlVariables = true;
            }

            // clear out the set _reportFilters
            $this->_reportFilters = array();
            // reset the control statement to what it was when we started
            $this->controlStatement = $startControlVariable;
            // variable cleanup.
            unset($startControlVariable);
        }

        // manage if the control variable changed at all
        if ($hasMoreControlVariables == false) {
            // since it did not change, just add the filter groups to the root of the master $filter
            foreach ($filter_groups as $_filter) {
                // unset operator since we don't need, and it's already set.
                unset($_filter['operator']);
                // do the merge
                $filter["Filter_1"] = array_merge($filter['Filter_1'], $_filter);
            }
        } else {
            // if we only have one filter group and the control variable changed move it up
            if(count($filter_groups) == 1) {
                // since we only have one, just set the Filter_1 to be the only filter in the group with the operator
                // since it's not the default operator
                $filter["Filter_1"] = current($filter_groups);
            } else {
                // merge in all the filter groups while keeping the operator set to what the default was since it
                // changed and we need the actual filter groups
                $filter["Filter_1"] = array_merge($filter["Filter_1"], $filter_groups);
            }
        }

        // return the filter from the converter
        return $filter;
    }

    /**
     * Check to see if an item is an And or Or Control Variable
     *
     * @param SugarParsers_Filter_AbstractFilter $item
     * @return boolean
     */
    protected function checkForAndOrControlVariable($item)
    {
        return ($item instanceof SugarParsers_Filter_And || $item instanceof SugarParsers_Filter_Or);
    }

    /**
     * Internal Method to Convert
     *
     * @param string|integer $key
     * @param SugarParsers_Filter_AbstractFilter $value
     */
    protected function _convert($key, $value)
    {

        // check to see if the key is a link
        $removeLinkLevel = false;
        if ($value instanceof SugarParsers_Filter_Link) {
            $removeLinkLevel = $this->parseLinkFilter($key, $value);
            $value = $value->getValue();
        }

        if ($value instanceof SugarParsers_Filter_AbstractFilter && call_user_func(array($value,'isControlVariable'))) {
            if (!($value instanceOf SugarParsers_Filter_Not)) {
                $this->controlStatement = $value->getOperator(true, $this->is_not);
            } else if ($value instanceof SugarParsers_Filter_Not) {
                $this->is_not = true;
            }
            $value = $value->getValue();
        }

        if (is_array($value)) {
            foreach ($value as $v) {
                //We need to retain the top level key so we pass in the top level key (variable name) and call _convert once more
                $this->_convert($key, $v);
            }
        } else {
            if (is_integer($key)) {
                $key = $value->getKey();
            }
            // create a new filter
            $filter = $this->createFilter($key, $value);

            if (!empty($filter)) {
                $this->_reportFilters[] = $filter;
            }
        }

        if ($removeLinkLevel === true && count($this->link_path) > 1) {
            // remove the link path
            array_pop($this->link_path);
        }
    }

    /**
     * @param string $field_name        Name of the field we are modifying
     * @param SugarParsers_Filter_AbstractFilter $value
     * @return array
     */
    protected function createFilter($field_name, $value)
    {
        $operator = $value->getOperator(true, $this->is_not);
        // we need to check to see if the files exist
        $table_key = join(":", $this->link_path);

        if (strpos($table_key, "self:") === 0) {
            // replace self with the module name for the self module
            $self_bean = $this->reportBuilder->getBeanFromTableKey('self');
            $table_key = preg_replace("#self:#", $self_bean->module_name . ":", $table_key, 1);
        }

        /* @var $def_bean SugarBean */
        $def_bean = $this->reportBuilder->getBeanFromTableKey($table_key);

        // make sure the field_name comes from the actual filter not the table key (links are screwy), also ignore
        // any keys are contain a $ variable
        if ($field_name !== $value->getKey()) {
            $tmpKey = $value->getKey();
            if (strstr($tmpKey, '$') === false && !empty($tmpKey)) {
                $field_name = $tmpKey;
            }
        }

        if ($this->checkFieldExist($def_bean, $field_name)) {
            return $value->getValueInputs($field_name, $table_key, $operator);
        }

        return array();
    }

    /**
     * @param SugarBean $bean       Which bean are we checking
     * @param string $field         The field we are looking for
     * @return bool
     */
    protected function checkFieldExist($bean, $field)
    {
        if (isset($bean->field_defs[$field]) && $bean->field_defs[$field]['type'] != "link") {
            return true;
        }
        return false;
    }

    protected function parseLinkFilter($link_name, SugarParsers_Filter_Link $filter_link)
    {
        /* @var SugarBean $bean */
        $bean = BeanFactory::getBean($filter_link->getParentModule());

        if ($link_name !== $filter_link->getKey()) {
            $tmpKey = $filter_link->getKey();
            if (strstr($tmpKey, '$') === false && !empty($tmpKey)) {
                $link_name = $tmpKey;
            }
        }

        // no bean found, just return it
        if ($bean === false) {
            return false;
        }

        // now that we have the bean, lets make sure that the link exists
        $links = $bean->get_linked_fields();

        if (isset($links[$link_name])) {

            $this->reportBuilder->addLink($link_name, null, $this->link_path);

            // success we have a link.
            $this->link_path[] = $link_name;

            return true;
        }

        return false;
    }

    /**
     * @param string $link
     * @return bool|array
     */
    protected function checkLinkExistsInReportBuilder($link)
    {
        // make sure the link was added to the ReportBuilder
        $rbLinkKey = $this->reportBuilder->getLinkTable($link);
        // if we got an array back, try adding it
        if (is_array($rbLinkKey)) {
            $this->reportBuilder->addLink($link);
            $rbLinkKey = $this->reportBuilder->getLinkTable($link);
        }

        // strange it still didn't add it, must be a bad link. return false.
        if (is_array($rbLinkKey)) {
            return false;
        }

        return $rbLinkKey;
    }
}