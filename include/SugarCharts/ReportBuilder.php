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


require_once('modules/Reports/Report.php');

/**
 * PHP Report Builder.  This will create a report on the fly to run via the API
 *
 * @api
 */
class ReportBuilder
{

    /**
     * The Default Structure For A Report JSON
     *
     * @var array
     */
    protected $defaultReport = array(
        'display_columns' => array(),
        'module' => '',
        'group_defs' => array(),
        'summary_columns' => array(),
        'report_name' => '',
        'chart_type' => 'hBarF',
        'do_round' => true,
        'chart_description' => '',
        'numerical_chart_column' => 'count',
        'numerical_chart_column_type' => '',
        'assigned_user_id' => '1',
        'report_type' => 'summary',
        'full_table_list' => array(),
        'filters_def' => array('Filter_1' => array(
            'operator' => 'AND',
        ))
    );

    /**
     * An array of Created Bean
     *
     * @var array
     */
    protected $beans = array();

    /**
     * Mapping of Modules to Table Keys
     *
     * @var array
     */
    protected $table_keys = array();

    /**
     * Mapping of the Links to the Tables
     *
     * @var array
     */
    protected $link_keys = array();

    /**
     * What's the default module
     *
     * @var string
     */
    protected $self_module;

    /**
     * Class Constructor
     *
     * @param string $module    The Default Starting Module
     * @param string $saved_report_id       Load a report from the reporting engine
     */
    public function __construct($module, $saved_report_id = null)
    {
        $this->self_module = $module;
        $this->defaultReport['module'] = $module;
        $this->addModule($this->self_module, 'self');

        // try and load the saved report id
        $this->loadSavedReport($saved_report_id);
    }

    /**
     * Load a saved report from the db
     *
     * @param $saved_report_id
     * @return bool
     */
    public function loadSavedReport($saved_report_id)
    {
        if (is_guid($saved_report_id)) {
            // we have a guid, lest try and load the saved report
            /* @var $saved_report SavedReport */
            $saved_report = BeanFactory::getBean('Reports', $saved_report_id);

            if ($saved_report !== false && $saved_report->module == $this->defaultReport['module']) {
                // we have a loaded report and it matches the base module for report builder
                // lets process it and break it up so we can add stuff to it.
                // now load up a report bean to convert the report since that is where all the code exist
                $report = new Report($saved_report->content);

                $this->setDefaultReport($report->report_def);

                // success, return true
                return true;
            }
        }

        // we did not load a report, return false.
        return false;
    }

    /**
     * Set a default report from an array or a json string.
     *
     * @param $report_def
     * @return bool
     */
    public function setDefaultReport($report_def)
    {
        if (!is_array($report_def)) {
            $report_def = json_decode($report_def, true);
        }

        $this->defaultReport = $report_def;

        foreach ($this->defaultReport['full_table_list'] as $key => $table_def) {
            $this->table_keys[$key] = array('module' => $table_def['module'], 'key' => $key);

            if (isset($table_def['link_def'])) {
                $this->link_keys[$table_def['link_def']['name']] = $key;
            }
        }

        return true;
    }

    /**
     * Return the array that is the default report
     *
     * @return array
     */
    public function getDefaultReport()
    {
        return $this->defaultReport;
    }

    /**
     * Add module to the report
     *
     * @param string $module        The module we are adding
     * @param null|string $key      The key for the module we are adding
     * @return ReportBuilder
     */
    public function addModule($module, $key)
    {
        $bean = $this->getBean($module);

        $this->table_keys[$key] = array('module' => $module, 'key' => $key);

        $this->defaultReport['full_table_list'][$key] = array(
            'value' => $bean->module_dir,
            'module' => $bean->module_dir,
            'label' => $bean->module_dir,
            'parent' => '',
            'children' => array(),
        );

        return $this;
    }

    /**
     * Add A Field via a link, If the link doesn't load it will not process the link in the chart.
     *
     * @param string $link              The Link name to load the field from
     * @param string $field             The field to add to the group by
     * @param string|array $path        The Parent module for the link, this can be a string or an array with the path to the new link
     * @return ReportBuilder
     */
    public function addLink($link, $field = null, $path = null)
    {
        if (empty($path)) {
            $path = array($this->self_module);
        } else if (!is_array($path)) {
            $path = array($path);
        }

        $last_item = array_pop(array_values($path));
        if ($last_item !== $link) {
            array_push($path, $link);
        }

        $key = array();
        $module = null;

        $last_item = array_pop(array_values($path));
        foreach ($path as $step) {
            if (empty($module) && ($step == $this->self_module || $step == "self")) {
                $module = $this->getDefaultModule(true);
                $key[] = $step;
            } else {
                // make this module
                if (!($module instanceof SugarBean)) {
                    $module = $this->getBean($module);
                }

                $_bean_links = $module->get_linked_fields();
                if (isset($_bean_links[$step])) {
                    // we have a link
                    // get the final module and set it
                    $tmp_module = $this->getBean($_bean_links[$step]['module']);
                    if ($tmp_module !== false) {

                        $_field = null;
                        if ($last_item == $step && isset($tmp_module->field_defs[$field])) {
                            // make sure the field exists
                            $_field = $field;
                        }

                        $key[] = $step;

                        // now add the link
                        $this->_addLink($step, join(":", $key), $module, $_field);

                        $module = $tmp_module;

                        continue;
                    }
                } else {
                    return $this;
                }
            }
        }

        return $this;
    }

    /**
     * @param string $link
     * @param string $key
     * @param SugarBean $bean
     * @param string $field
     */
    protected function _addLink($link, $key, $bean, $field = null)
    {
        $links = $bean->get_linked_fields();

        if (isset($links[$link]) && $bean->load_relationship($link)) {
            // we have the link
            /* @var $bean_rel Link2 */
            $bean_rel = $bean->$link;

            $link = $links[$link];

            if (empty($key)) {
                $key = $bean->module_dir . ':' . $link['name'];
            } elseif (is_array($key)) {
                $key = join(":", $key);
            }

            if (strpos($key, "self:") === 0) {
                // replace self with the module name for the self module
                $self_bean = $this->getBean($this->self_module);
                $key = preg_replace("#self:#", $self_bean->module_name . ":", $key, 1);
            }

            //$child_bean = $this->getBean($link['module']);
            $this->table_keys[$key] = array('module' => $link['module'], 'key' => $key);
            //$this->table_keys[$link['module']] = $key;
            $this->link_keys[$link['name']] = $key;
            if (!is_null($field)) {
                $this->addGroupBy($field, $link['module'], $key);
            }

            if (!isset($this->defaultReport['full_table_list'][$key])) {
                $parent = $this->findParentTableKey($bean->module_name, $key, $field);

                $arrLink = array(
                    'name' => $bean->module_dir . ' > ' . $link['module'],
                    'parent' => $parent,
                    'children' => array(),
                    'link_def' => array(
                        'name' => $link['name'],
                        'relationship_name' => $link['relationship'],
                        'bean_is_lhs' => ($bean_rel->getSide() == 'LHS'),
                        'link_type' => $bean_rel->getType(),
                        'label' => $link['module'],
                        'module' => $link['module'],
                        'table_key' => $key,
                    ),
                    'dependents' => array(),
                    'module' => $link['module'],
                    'label' => $this->getLabel($link['vname'], $link['module'])
                );

                $this->defaultReport['full_table_list'][$key] = $arrLink;
            }
        }
    }

    /**
     * Utility Method for finding the parent of the field/module combo
     *
     * @param string $bean_name         Module Name
     * @param string $key               Potential Key name we are working with
     * @param null|string $field        Do we have a field we are working with?
     * @return array|string
     */
    protected function findParentTableKey($bean_name, $key, $field = null)
    {
        $parent = "";
        $potentialParents = $this->getKeyTable($bean_name);
        if (is_array($potentialParents)) {
            if (empty($field) && isset($potentialParents[$key])) {
                unset($potentialParents[$key]);
            } elseif (!empty($field) && isset($potentialParents[$key])) {
                $parent = $key;
            }

            // for now take the first one on what's left
            if (empty($parent)) {
                if (count($potentialParents) >= 1) {
                    $parent = array_shift(array_keys($potentialParents));
                } else {
                    // it's empty, so just set it to self;
                    $parent = "self";
                }
            }
        } else {
            $parent = $potentialParents;
        }

        return $parent;
    }

    /**
     * Add A Field To Group By
     *
     * @param string $field         Which field do we want to group by
     * @param string|null $module   Which module the field belongs to
     * @param string|null $key      Potential Key that we are working with
     * @return ReportBuilder
     */
    public function addGroupBy($field, $module = null, $key = null)
    {
        if (empty($module)) {
            $module = $this->self_module;
        }
        $bean = $this->getBean($module);

        if (isset($bean->field_defs[$field])) {
            $bean_field = $bean->field_defs[$field];

            $this->defaultReport['group_defs'][] = array(
                'name' => $field,
                'label' => $this->getLabel($bean_field['vname'], $bean->module_name),
                'table_key' => $this->findParentTableKey($bean->module_dir, $key, $field),
                'type' => $bean_field['type'],
            );

            $this->addSummaryColumn($field, $bean, $key, array('group_function' => 'sum'));
        }

        return $this;
    }

    /**
     * Return the group_defs from the list
     *
     * @param string $field         we should just look for a field, if found return just that field otherwise return the full list
     * @return mixed
     */
    public function getGroupBy($field = null)
    {
        if (!empty($field)) {
            foreach ($this->defaultReport['group_defs'] as $column) {
                if ($column['name'] == $field) {
                    return $column;
                }
            }
        }

        return $this->defaultReport['group_defs'];
    }

    /**
     * Remove a Group By Def from the report Definition
     *
     * @param array $group_def          The GroupBy Def to remove
     * @return bool
     */
    public function removeGroupBy($group_def)
    {
        foreach ($this->defaultReport['group_defs'] as $key => $gdef) {
            if ($gdef == $group_def) {
                unset($this->defaultReport['group_defs'][$key]);
                return true;
            }
        }

        return false;
    }

    /**
     * Add a Column to the Summary Output
     *
     * @param string $field         Which field to add
     * @param string|null $module   Which module does the field belong to
     * @param string|null $key      Potential Key that we are working with
     * @param array $params         Additional params that can be added to summary columns
     *                               - group_function   - How do we want to group the field
     *                               - qualifier        - How is the field parsed in the reporting engine
     * @return array|boolean        The summary that was added or False if it was not added
     */
    public function addSummaryColumn($field, $module = null, $key = null, $params = array())
    {
        if (!($module instanceof SugarBean)) {
            if (empty($module)) {
                $module = $this->self_module;
            }
            $bean = $this->getBean($module);
        } else {
            $bean = $module;
            $module = $bean->module_dir;
        }

        if (isset($bean->field_defs[$field])) {
            $bean_field = $bean->field_defs[$field];

            $field_label = $this->getLabel($bean_field['vname'], $bean->module_name);
            if(isset($params['group_function'])) {
                $field_label = strtoupper($params['group_function']) . ": " . $field_label;
            } elseif(isset($params['qualifier'])) {
                $field_label = strtoupper($params['qualifier']) . ": " . $field_label;
            }

            // create the new summary record
            $new_summary = array_merge(array(
                'name' => $field,
                'label' => $field_label,
                'field_type' => $bean_field['type'],
                'table_key' => $this->findParentTableKey($module, $key, $field),
            ), $params);

            // since we only want one of each in the summary if one exist with the same label, lets just dump out instead
            // of adding a new one
            $summaries = $this->getSummaryColumns();
            foreach($summaries as $summary) {
                if($summary == $new_summary) {
                    // we have a summary already set, so return false
                    return false;
                }
            }

            // so we don't have one set yet, lets add it
            $this->defaultReport['summary_columns'][] = $new_summary;

            return $new_summary;
        }

        return false;
    }

    /**
     * Get the Summary Columns
     *
     * @param null|string $field            Field to return, if no field is found or specified, the full list will be returned
     * @return mixed
     */
    public function getSummaryColumns($field = null)
    {
        if (!empty($field)) {
            // todo: support for multiple summaries with the same field name but different group_functions or qualifiers
            foreach ($this->defaultReport['summary_columns'] as $column) {
                if ($column['name'] == $field) {
                    return $column;
                }
            }
        }

        return $this->defaultReport['summary_columns'];
    }

    /**
     * Remove a summary column
     *
     * @param array $summary_column             Which column to remove
     * @return bool
     */
    public function removeSummaryColumn($summary_column)
    {
        foreach ($this->defaultReport['summary_columns'] as $key => $sdef) {
            if ($sdef == $summary_column) {
                unset($this->defaultReport['summary_columns'][$key]);
                return true;
            }
        }

        return false;
    }

    /**
     * Set which summary is set at the x-Axis in the chart
     *
     * @param $summary
     */
    public function setXAxis($summary)
    {
        // set this as the x-axis
        $this->removeSummaryColumn($summary);
        $old = array_splice($this->defaultReport['summary_columns'], 0, 1, array($summary));
        $this->defaultReport['summary_columns'][] = array_pop($old);
    }

    /**
     * Set which summary is set as the y-Axis in the Chart
     *
     * @param $summary
     */
    public function setYAxis($summary)
    {
        // first remove the one we are adding
        $this->removeSummaryColumn($summary);
        $old = array_splice($this->defaultReport['summary_columns'], 1, 1, array($summary));
        $this->defaultReport['summary_columns'][] = array_pop($old);
    }

    /**
     * Set the numerical_chart_column from a summary column
     *
     * @param string|array      $summary_column     The field we want to have being the chart column
     * @return boolean          True on success and false if the field is not found or doesn't contain a group_function definition
     */
    public function setChartColumn($summary_column)
    {
        // if it's just string try and find the column
        if (!is_array($summary_column)) {
            // try and find the field
            $summary_column = $this->getSummaryColumns($summary_column);
        }

        if (!isset($summary_column['name'])) {
            // field not found
            return false;
        }

        // we have a valid file so let set it up
        if (!isset($summary_column['group_function'])) {
            // no group by function
            return false;
        }

        $name = $summary_column['table_key'] . ":" . $summary_column['name'];
        if ($summary_column['name'] != "count") {
            $name .= ":" . $summary_column['group_function'];
        }

        $this->defaultReport['numerical_chart_column'] = $name;
        $this->defaultReport['numerical_chart_column_type'] = $summary_column['field_type'];

        // success!
        return true;
    }

    /**
     * Return the current set chart column
     *
     * @return mixed
     */
    public function getChartColumn()
    {
        return $this->defaultReport['numerical_chart_column'];
    }

    /**
     * Return the current set chart column type
     *
     * @return mixed
     */
    public function getChartColumnType()
    {
        return $this->defaultReport['numerical_chart_column_type'];
    }

    /**
     * Add A Count Column to the Summary
     *
     * @return ReportBuilder
     */
    public function addSummaryCount()
    {
        $this->defaultReport['summary_columns'][] = array(
            'name' => 'count',
            'label' => 'Count',
            'table_key' => 'self',
            'group_function' => "count",
            'field_type' => ''
        );

        return $this;
    }

    /**
     * Add Filter
     *
     * @param $filter
     */
    public function addFilter($filter)
    {
        if (isset($filter['Filter_1'])) {
            $filter = $filter['Filter_1'];
        }

        // make sure that something is set so we don't throw a notice
        if (!isset($this->defaultReport['filters_def']['Filter_1'])) {
            $this->defaultReport['filters_def'] = array('Filter_1' => array());
        }

        // make sure all filters are in the proper format
        foreach ($this->defaultReport['filters_def']['Filter_1'] as $key => $f) {
            if (!is_integer($key)) continue;

            if (!isset($f['operator'])) {
                $this->defaultReport['filters_def']['Filter_1'][$key] = array('operator' => 'AND', $f);
            }
        }

        $this->defaultReport['filters_def']['Filter_1'][] = $filter;

        if (count($this->defaultReport['filters_def']['Filter_1']) == 1) {
            // move it up
            $this->defaultReport['filters_def']['Filter_1'] = array_shift($this->defaultReport['filters_def']['Filter_1']);
        }
    }

    /**
     * Return the current list of filters
     *
     * @return mixed
     */
    public function getFilters()
    {
        return $this->defaultReport['filters_def'];
    }

    /**
     * Return the Data as a JSON String
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->defaultReport);
    }

    /**
     * Return the report as an array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->defaultReport;
    }

    /**
     * Get the SugarBean
     *
     * @param string $module    Which Module To Load
     * @return SugarBean
     */
    public function getBean($module)
    {
        if (!isset($this->beans[$module])) {
            $this->beans[$module] = BeanFactory::getBean($module);
        }

        return $this->beans[$module];
    }

    /**
     * Return a specify key if a module is set, if not return the whole array
     *
     * @param string $module        Specific module to get a table key for.
     * @return array|string
     */
    public function getKeyTable($module = null)
    {
        // find all the array that match the current module
        $found = array();

        foreach ($this->table_keys as $key => $map) {
            if ($map['module'] == $module) {
                $found[$key] = $map;
            }
        }

        // if we found none, return the whole array
        if (empty($found)) {
            return $this->table_keys;
        }

        // if we only have one return the key
        if (count($found) == 1) {
            return array_shift(array_keys($found));
        }

        // just return all found.
        return $found;

    }

    /**
     * Convert A Table Key into a SugarBean
     *
     * @param string $tableKey          Table key we are working with
     * @return SugarBean|boolean
     */
    public function getBeanFromTableKey($tableKey)
    {
        $module = false;
        foreach ($this->table_keys as $key => $map) {
            if ($key == $tableKey) {
                $module = $map['module'];
                break;
            }
        }
        return ($module === false) ? false : $this->getBean($module);
    }

    /**
     * Return a specify key if a link is passed in, if not return the whole array
     *
     * @param string $link        Specific link to get a table key for.
     * @return array|string
     */
    public function getLinkTable($link = null)
    {
        if (is_null($link) || !isset($this->link_keys[$link])) {
            return $this->link_keys;
        } else {
            return $this->link_keys[$link];
        }
    }

    /**
     * Return the default module set.
     *
     * @param boolean $asBean       Return the default module as a SugarBean instance
     * @return string|SugarBean
     */
    public function getDefaultModule($asBean = false)
    {
        if ($asBean == true) {
            return $this->getBean($this->self_module);
        }

        return $this->self_module;
    }

    /**
     * Change the chart type,  If the value is not valid, it will default to hBarF.
     *
     * @param $chartType
     */
    public function setChartType($chartType)
    {
        $validCharts = array('hBarF', 'vBarF', 'pieF', 'lineF', 'funnelF');

        if (in_array($chartType, $validCharts)) {
            $this->defaultReport['chart_type'] = $chartType;
        } else {
            $this->defaultReport['chart_type'] = 'hBarF';
        }
    }

    /**
     * Return the ChartType Setting
     *
     * @return string
     */
    public function getChartType()
    {
        return $this->defaultReport['chart_type'];
    }

    /**
     * Handler for returning a parsed label value
     *
     * @param string $label     The label we want to find
     * @param string|null $module       Override the default ReportBuilder Module
     * @return mixed
     */
    protected function getLabel($label, $module = null)
    {
        global $current_language;

        if(empty($module)) {
            $module = $this->getDefaultModule();
        }
        $mod_strings = return_module_language($current_language, $module);

        return get_label($label, $mod_strings);
    }
}