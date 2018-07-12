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


// This class is used for the Manager Views
require_once('include/SugarForecasting/AbstractForecast.php');
require_once('include/SugarForecasting/Exception.php');
class SugarForecasting_Manager extends SugarForecasting_AbstractForecast implements SugarForecasting_ForecastSaveInterface
{

    /**
     * Default Data Array To Start With
     *
     * @var array
     */
    protected $defaultData = array(
        "amount" => 0,
        "quota" => 0,
        "quota_id" => '',
        "best_case" => 0,
        "likely_case" => 0,
        "worst_case" => 0,
        "best_adjusted" => 0,
        "likely_adjusted" => 0,
        "worst_adjusted" => 0,
        "forecast" => 0,
        "forecast_id" => '',
        "worksheet_id" => '',
        "currency_id" => '-99',
        "base_rate" => 1.0,
        "show_opps" => false,
        "timeperiod_id" => '',
        "id" => '',
        "user_id" => '',
        "version" => 1,
        "name" => '',
        "date_modified" => '',
        "opp_count" => 0,
        "pipeline_opp_count" => 0,
        "pipeline_amount" => 0,
        "closed_amount" => 0
    );


    /**
     * Class Constructor
     *
     * @param array $args       Service Arguments
     */
    public function __construct($args)
    {
        // set the isManager Flag just incase we need it
        $this->isManager = true;

        parent::__construct($args);

        // set the default data timeperiod to the set timeperiod
        $this->defaultData['timeperiod_id'] = $this->getArg('timeperiod_id');
    }

    /**
     * Run all the tasks we need to process get the data back
     *
     * @return array|string
     */
    public function process()
    {
        try {
            $this->loadUsers();
        } catch (SugarForecasting_Exception $sfe) {
            return "";
        }

        $this->loadUsersAmount();
        $this->loadUsersQuota();
        $this->loadForecastValues();
        $this->loadWorksheetAdjustedValues();
        $this->loadManagerAmounts();

        return array_values($this->dataArray);
    }

    /**
     * Load the Users for the passed in user in the $arguments
     *
     * @throws SugarForecasting_Exception
     */
    protected function loadUsers()
    {
        global $current_user, $current_language, $locale;

        $mod_strings = return_module_language($current_language, "Forecasts");

        $args = $this->getArgs();

        if (isset($args['user_id']) && User::isManager($args['user_id'])) {
            /** @var $user User */
            $user = BeanFactory::getBean('Users', $args['user_id']);
        } elseif (!isset($args['user_id']) && User::isManager($current_user->id)) {
            /** @var $user User */
            $user = $current_user;
            $this->setArg('user_id', $user->id);
        } else {
            throw new SugarForecasting_Exception('User Is Not Manager');
        }

        $user_id = $this->getArg('user_id');

        $reportees = $this->getUserReportees($this->getArg('user_id'));

        $data = array();

        // use to_html when call DBManager::fetchByAssoc if encode_to_html isn't defined or not equal false
        // @see Bug #58397 : Comma in opportunity name is exported as #039;
        $encode_to_html = !isset($this->args['encode_to_html']) || $this->args['encode_to_html'] != false;

        foreach ($reportees as $reportee_id => $reportee_username) {
            /** @var $reportee User */
            $reportee = BeanFactory::getBean('Users', $reportee_id, array('encode' => $encode_to_html));
            $default_data = $this->defaultData;
            $default_data['id'] = $reportee_id;
            $default_data['label'] = $locale->formatName($reportee);
            $default_data['name'] = $default_data['label'];

            if ($reportee_id == $user_id) {
                $default_data['show_opps'] = true;
            } else {
                $default_data['show_opps'] = User::isManager($reportee_id) ? false : true;
            }

            $default_data['user_id'] = $reportee_id;
            $data[$reportee->user_name] = $default_data;
        }

        $this->dataArray = $data;
    }

    /**
     * Load the base amounts for the users in the dataArray
     */
    protected function loadUsersAmount()
    {
        $amounts = $this->getUserAmounts();

        foreach ($amounts as $user => $amount) {
            $this->dataArray[$user]['amount'] = $amount['amount'];
        }
    }

    /**
     * Load the Quota's for the users in the dataArray
     */
    protected function loadUsersQuota()
    {
        //getting quotas from quotas table
        $db = DBManagerFactory::getInstance();
        $quota_query = "SELECT u.user_name user_name, q.amount quota, q.id quota_id 
                        FROM quotas q 
                        INNER JOIN users u 
                        ON q.user_id = u.id 
                        WHERE u.deleted = 0 AND u.status = 'Active'
                            AND q.timeperiod_id = '{$this->getArg('timeperiod_id')}'
                            AND ((u.id = '{$this->getArg('user_id')}' and q.quota_type = 'Direct')
                            OR (u.reports_to_id = '{$this->getArg('user_id')}' and q.quota_type = 'Rollup'))
                            AND q.deleted = 0";

        $result = $db->query($quota_query);

        while (($row = $db->fetchByAssoc($result)) != null) {
            $this->dataArray[$row['user_name']]['quota_id'] = $row['quota_id'];
            $this->dataArray[$row['user_name']]['quota'] = $row['quota'];
        }
    }

    /**
     * Get the Worksheet Adjusted Values
     */
    public function loadWorksheetAdjustedValues()
    {
        $args = $this->getArgs();

        global $current_user;
        //getting data from worksheet table for reportees
        $reportees_query = "SELECT u2.user_name, 
                            w.id worksheet_id,
                            w.best_case best_adjusted,
                            w.likely_case likely_adjusted,
                            w.worst_case worst_adjusted,
                            w.forecast_type,
                            w.related_id,
                            w.version,
                            w.quota,
                            w.currency_id,
                            w.base_rate
                            FROM users u
                            INNER JOIN users u2
                                on u.id = u2.reports_to_id or u.id = u2.id
                            INNER JOIN worksheet w
                                on w.user_id = u.id  and w.timeperiod_id = '" . $args['timeperiod_id'] . "'
                                    AND ((w.related_id = u.id and u2.id = u.id) OR (w.related_id = u2.id))
                            WHERE u.deleted = 0 AND
                                u.status = 'Active' AND
                                u2.deleted = 0 AND
                                u2.status = 'Active' AND
                                u.id = '" . $args['user_id'] . "'
                                and w.deleted = 0 ";


        if ($args['user_id'] == $current_user->id) {
            $reportees_query .= "and w.revision = (select max(revision) from worksheet
                                    where user_id = u.id
                                        and related_id = u2.id
                                        and timeperiod_id = '" . $args['timeperiod_id'] . "')";
        } else {
            $reportees_query .= "and w.version = 1";
        }

        $db = DBManagerFactory::getInstance();

        $result = $db->query($reportees_query);

        while (($row = $db->fetchByAssoc($result)) != null) {
            $this->dataArray[$row['user_name']]['worksheet_id'] = $row['worksheet_id'];
            $this->dataArray[$row['user_name']]['best_adjusted'] = $row['best_adjusted'];
            $this->dataArray[$row['user_name']]['likely_adjusted'] = $row['likely_adjusted'];
            $this->dataArray[$row['user_name']]['worst_adjusted'] = $row['worst_adjusted'];
            $this->dataArray[$row['user_name']]['currency_id'] = $row['currency_id'];
            $this->dataArray[$row['user_name']]['base_rate'] = $row['base_rate'];
            $this->dataArray[$row['user_name']]['version'] = $row['version'];
            if ($row['version'] == 0) {
                $this->dataArray[$row['user_name']]['quota'] = $row['quota'];
            }

        }
    }

    /**
     * This function returns the best, likely and worst case values from the forecasts table for the manager
     * associated with the user_id class variable.  It is a helper function used by the manager worksheet api
     * to return forecast related information.
     */
    protected function loadForecastValues()
    {
        //Partially optimized.. Don't delete
        /*$data = array();

        $sql = "select u.user_name, f.id, f.best_case, f.likely_case, f.worst_case, f.forecast_type, f.date_modified " .
                "from forecasts f " .
                "inner join users u " .
                    "on f.user_id = u.id " .
                        "and (u.reports_to_id = '" . $this->user_id . "' " .
                             "or u.id = '" . $this->user_id . "') " .
                "where f.timeperiod_id = '" . $this->timeperiod_id . "' " .
                    "and ((f.user_id = '" . $this->user_id . "' and f.forecast_type = 'Direct') " .
                         "or (f.user_id <> '" . $this->user_id . "' and f.forecast_type = 'Rollup'))" .
                    "and f.deleted = 0 " .
                    "and f.date_modified = (select max(date_modified) from forecasts where user_id = u.id and timeperiod_id = '" . $this->timeperiod_id . "')";
        $result = $GLOBALS['db']->query($sql);

        while(($row=$GLOBALS['db']->fetchByAssoc($result))!=null)
        {
            $data[$row['user_name']]['best_case'] = $row['best_case'];
            $data[$row['user_name']]['likely_case'] = $row['likely_case'];
            $data[$row['user_name']]['worst_case'] = $row['worst_case'];
            $data[$row['user_name']]['forecast_id'] = $row['id'];
            $data[$row['user_name']]['date_modified'] = $row['date_modified'];
        }

        return $data;*/

        $args = $this->getArgs();

        $query = "SELECT id, user_name
                    FROM users
                    WHERE reports_to_id = '" . $args['user_id'] . "'
                        AND deleted = 0
                        AND status = 'Active'";
        $db = DBManagerFactory::getInstance();
        $result = $db->query($query);

        $ids = array();
        while ($row = $db->fetchByAssoc($result)) {
            $ids[$row['id']] = $row['user_name'];
        }

        //Add the manager's data as well
        /** @var $user User */
        $user = BeanFactory::getBean('Users', $args['user_id']);
        $ids[$args['user_id']] = $user->user_name;

        foreach ($ids as $id => $user_name) {
            // if the reportee is the manager, we need to get the roll up amount instead of the direct amount
            $forecast_type = (User::isManager($id) && $id != $args['user_id']) ? 'ROLLUP' : 'DIRECT';
            $forecast_query = sprintf(
                "SELECT id, best_case, likely_case, worst_case, date_modified, currency_id, base_rate,
                        opp_count, pipeline_opp_count, pipeline_amount, closed_amount
                    FROM forecasts
                    WHERE timeperiod_id = '%s'
                        AND forecast_type = '%s'
                        AND user_id = '%s'
                        AND deleted = 0
                    ORDER BY forecasts.date_modified DESC",
                $args['timeperiod_id'],
                $forecast_type,
                $id
            );

            $result = $db->limitQuery($forecast_query, 0, 1);

            while ($row = $db->fetchByAssoc($result)) {
                $this->dataArray[$user_name]['best_case'] = $row['best_case'];
                // make sure that adjusted is not equal to zero, this might be over written by the loadWorksheetAdjustedValues call
                $this->dataArray[$user_name]['best_adjusted'] = $row['best_case'];
                $this->dataArray[$user_name]['likely_case'] = $row['likely_case'];
                // make sure that adjusted is not equal to zero, this might be over written by the loadWorksheetAdjustedValues call
                $this->dataArray[$user_name]['likely_adjusted'] = $row['likely_case'];
                $this->dataArray[$user_name]['worst_case'] = $row['worst_case'];
                // make sure that adjusted is not equal to zero, this might be over written by the loadWorksheetAdjustedValues call
                $this->dataArray[$user_name]['worst_adjusted'] = $row['worst_case'];
                $this->dataArray[$user_name]['forecast_id'] = $row['id'];
                $this->dataArray[$user_name]['date_modified'] = $this->convertDateTimeToISO(
                    $db->fromConvert($row['date_modified'], 'datetime')
                );
                $this->dataArray[$user_name]['currency_id'] = $row['currency_id'];
                $this->dataArray[$user_name]['base_rate'] = $row['base_rate'];
                $this->dataArray[$user_name]['opp_count'] = $row['opp_count'];
                $this->dataArray[$user_name]['pipeline_opp_count'] = $row['pipeline_opp_count'];
                $this->dataArray[$user_name]['pipeline_amount'] = $row['pipeline_amount'];
                $this->dataArray[$user_name]['closed_amount'] = $row['closed_amount'];

            }
        }
    }

    /**
     * If any of the users are managers, we need their amount fields to be equal to their committed amount + the committed
     * amounts for the people who report to them.
     */
    protected function loadManagerAmounts()
    {
        $user_id = $this->getArg('user_id');
        foreach ($this->dataArray as $rep => $val) {
            if (empty($val['forecast_id'])) {
                $this->dataArray[$rep]['amount'] = 0;
            } else {
                if ($val['user_id'] != $user_id && $val['show_opps'] == false) {
                    // this is for a a manager only row
                    // we need to get their total amount including sales reps.
                    // first get the reportees that have a forecast submitted for this time period
                    $manager_reportees_forecast = $this->getUserReporteesWithForecasts(
                        $val['user_id'],
                        $this->getArg('timeperiod_id')
                    );
                    // second, we need to get the data all the reporting users
                    $manager_data = $this->getUserAmounts($val['user_id']);
                    // third we only process the users that actually have a committed forecast;
                    foreach ($manager_data as $name => $m_data) {
                        if (in_array($name, $manager_reportees_forecast)) {
                            // add it to the managers amount
                            $this->dataArray[$rep]['amount'] += $m_data['amount'];
                        }
                    }
                }
            }
        }
    }

    /**
     * Get the report data with filters.
     *
     * @param null|string $user_id
     * @return array
     */
    protected function getUserAmounts($user_id = null)
    {
        if (empty($user_id)) {
            $user_id = $this->getArg('user_id');
        }

        $sql = "SELECT u.user_name, sum(amount) as amount
                FROM opportunities o
                INNER JOIN timeperiods t
                    on t.start_date_timestamp <= o.date_closed_timestamp
                    and t.end_date_timestamp >= o.date_closed_timestamp
                INNER JOIN users u
                    ON o.assigned_user_id = u.id
                    and (u.reports_to_id = '{$user_id}'
                        OR u.id = '{$user_id}')
                WHERE u.deleted=0
                    AND u.status = 'Active'
                    AND t.id = '{$this->getArg('timeperiod_id')}'
                GROUP BY u.user_name";

        $db = DBManagerFactory::getInstance();

        $results = $db->query($sql);

        $return = array();
        while ($row = $db->fetchByAssoc($results)) {
            $return[$row['user_name']] = array('amount' => $row['amount']);
        }

        return $return;
    }

    /**
     * Save the Manager Worksheet
     *
     * @return string
     * @throws SugarApiExceptionNotAuthorized
     */
    public function save()
    {
        require_once('include/SugarFields/SugarFieldHandler.php');
        /* @var $seed ForecastManagerWorksheet */
        $seed = BeanFactory::getBean('ForecastManagerWorksheets');
        $seed->loadFromRow($this->getArgs());
        $sfh = new SugarFieldHandler();

        foreach ($seed->field_defs as $properties) {
            $fieldName = $properties['name'];

            if (!isset($args[$fieldName])) {
                continue;
            }

            if (!$seed->ACLFieldAccess($fieldName, 'save')) {
                // No write access to this field, but they tried to edit it
                throw new SugarApiExceptionNotAuthorized('Not allowed to edit field ' . $fieldName . ' in module: ' . $args['module']);
            }

            $type = !empty($properties['custom_type']) ? $properties['custom_type'] : $properties['type'];
            $field = $sfh->getSugarField($type);

            if ($field != null) {
                $field->save($seed, $args, $fieldName, $properties);
            }
        }

        //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
        $admin = BeanFactory::getBean('Administration');
        $settings = $admin->getConfigForModule('Forecasts');
        if (!isset($settings['has_commits']) || !$settings['has_commits']) {
            $admin->saveSetting('Forecasts', 'has_commits', true, 'base');
            MetaDataManager::clearAPICache();
        }

        $seed->setWorksheetArgs($this->getArgs());
        $seed->saveWorksheet();
    }
}
