<?php
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


require_once('include/SugarForecasting/AbstractForecast.php');
class SugarForecasting_Individual extends SugarForecasting_AbstractForecast implements SugarForecasting_ForecastSaveInterface
{
    /**
     * Where we store the data we want to use
     *
     * @var array
     */
    protected $dataArray = array();

    /**
     * Run all the tasks we need to process get the data back
     *
     * @param $execute boolean indicating whether or not to execute the query and return the results; defaults to true
     * @return array|string
     */
    public function process()
    {
        global $current_user;
        $db = DBManagerFactory::getInstance();

        $sql = "select o.id opp_id, " .
                       "p.probability, " .
                       "p.commit_stage, " .
                       "o.sales_stage," .
                       "p.date_closed, " .
                       "p.currency_id, " .
                       "o.name, " .
                       "p.best_case, " .
                       "p.worst_case, " .
                       "p.likely_case, " .
                       "p.base_rate, " .
                       "p.assigned_user_id, " .
                       "p.id product_id, " .
                       "p.date_modified, " .
                       "w.id worksheet_id, " .
                       "w.user_id w_user_id, " .
                       "w.best_case w_best_case, " .
                       "w.likely_case w_likely_case, " .
                       "w.worst_case w_worst_case, " .
                       "w.forecast_type w_forecast_type, " .
                       "w.related_id w_related_id, " .
                       "w.version w_version, " .
                       "w.commit_stage w_commit_stage, " .
                       "w.op_probability w_probability, " .
                       "w.currency_id w_currency_id, " .
                       "w.base_rate w_base_rate, " .
                       "w.date_modified w_date_modified " .
                   "from products p " .
                   "inner join timeperiods t " .
                       "on t.id = '" . $this->getArg('timeperiod_id') . "' " .
                       "and p.date_closed_timestamp >= t.start_date_timestamp " .
                       "and p.date_closed_timestamp <= t.end_date_timestamp " .
                       "and p.assigned_user_id = '" . $this->getArg('user_id') . "' " .
                   "inner join opportunities o " .
                       "on p.opportunity_id = o.id " .
                   "left join worksheet w " .
                   "on p.id = w.related_id ";

        if ($this->getArg('user_id') != $current_user->id) {
               $sql .= "and w.version = 1 ";
        }

        $sql .= "where p.deleted = 0 " .
                "and o.deleted = 0 ";
        $result = $db->query($sql);

        // use to_html when call DBManager::fetchByAssoc if encode_to_html isn't defined or not equal false
        // @see Bug #58397 : Comma in opportunity name is exported as #039;
        $encode_to_html = !isset($this->args['encode_to_html']) || $this->args['encode_to_html'] != false;

        while (($row = $db->fetchByAssoc($result, $encode_to_html)) != null) {
            
            /* if we are a manager looking at a reportee worksheet and they haven't committed anything yet 
             * (no worksheet row), we don't want to add this row to the output.
             */
            if ((!isset($row["worksheet_id"]) || empty($row['worksheet_id']))
                && $this->getArg("user_id") != $current_user->id) {
                continue;
            }
            
            $data = array();
            $data["id"] = $row["opp_id"];
            $data["product_id"] = $row["product_id"];
            $data["date_closed"] = !empty($row["date_closed"]) ? substr($row["date_closed"],0,10) : $row["date_closed"];
            $data["sales_stage"] = $row["sales_stage"];
            $data["assigned_user_id"] = $row["assigned_user_id"];
            $data["amount"] = $row["likely_case"];
            $data["worksheet_id"] = "";
            $data["name"] = $row["name"];
            $data["currency_id"] = $row["currency_id"];
            $data["base_rate"] = $row["base_rate"];
            $data["version"] = 1;
            $data["worksheet_id"] = $row["worksheet_id"];
            $data["date_modified"] = $this->convertDateTimeToISO($db->fromConvert($row["date_modified"], "datetime"));

            if (isset($row["worksheet_id"]) && !empty($row['worksheet_id'])) {
                $data["w_date_modified"] = $this->convertDateTimeToISO(
                    $db->fromConvert($row["w_date_modified"], "datetime")
                );
            }
            if (isset($row["worksheet_id"]) && $this->getArg("user_id") != $current_user->id) {
                //use the worksheet data if it exists
                $data["best_case"] = $row["w_best_case"];
                $data["likely_case"] = $row["w_likely_case"];
                $data["worst_case"] = $row["w_worst_case"];
                $data["amount"] = $row["w_likely_case"];
                $data["commit_stage"] = $row["w_commit_stage"];
                $data["probability"] = $row["w_probability"];
                $data["version"] = $row["w_version"];
            } else {
                //Set default values to that of the product"s
                $data["best_case"] = $row["best_case"];
                $data["likely_case"] = $row["likely_case"];
                $data["worst_case"] = $row["worst_case"];
                $data["commit_stage"] = $row["commit_stage"];
                $data["probability"] = $row["probability"];
            }
            $this->dataArray[] = $data;
        }

        return array_values($this->dataArray);
    }


    /**
     * getQuery
     *
     * This is a helper function to allow for the query function to be used in ForecastWorksheet->create_export_query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Save the Individual Worksheet
     *
     * @return mixed|string
     * @throws SugarApiException
     */
    public function save()
    {
        require_once('include/SugarFields/SugarFieldHandler.php');
        /* @var $seed ForecastWorksheet */
        $seed = BeanFactory::getBean("ForecastWorksheets");
        $seed->loadFromRow($this->args);
        $sfh = new SugarFieldHandler();

        foreach ($seed->field_defs as $properties) {
            $fieldName = $properties['name'];

            if(!isset($this->args[$fieldName])) {
               continue;
            }

            if (!$seed->ACLFieldAccess($fieldName,'save') ) {
                // No write access to this field, but they tried to edit it
                global $app_strings;
                throw new SugarApiException(string_format($app_strings['SUGAR_API_EXCEPTION_NOT_AUTHORIZED'], array($fieldName, $this->args['module'])));
            }

            $type = !empty($properties['custom_type']) ? $properties['custom_type'] : $properties['type'];
            $field = $sfh->getSugarField($type);

            if(!is_null($field)) {
               $field->save($seed, $this->args, $fieldName, $properties);
            }
        }

        //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
        $admin = BeanFactory::getBean('Administration');
        $settings = $admin->getConfigForModule('Forecasts');
        if (!isset($settings['has_commits']) || !$settings['has_commits']) {
            $admin->saveSetting('Forecasts', 'has_commits', true, 'base');
            MetaDataManager::clearAPICache();
        }

        $seed->setWorksheetArgs($this->args);
        // we need to set the parent_type and parent_id so it finds it when we try and retrieve the old records
        $seed->parent_type = 'Opportunities';
        $seed->parent_id = $this->getArg('record');
        $seed->saveWorksheet();

        // now lets return the row we just updated

        // make sure that user_id is set, if it's not get the user from the assigned_user_id variable
        $obj_arg_user = $this->getArg('user_id');
        if(empty($obj_arg_user)) {
            $this->setArg('user_id', $this->getArg('assigned_user_id'));
        }

        // get the values for the worksheet
        $return = $this->process();

        // find the one we just saved and return it
        foreach($return as $worksheet) {
            if($worksheet['id'] == $seed->id) {
                return $worksheet;
            }
        }

        // just return empty, although this should never happen
        return '';
    }
}