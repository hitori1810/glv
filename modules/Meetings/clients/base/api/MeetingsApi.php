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

require_once('clients/base/api/UnifiedSearchApi.php');
class MeetingsApi extends UnifiedSearchApi {
    public function registerApiRest() {
        return array(
            'moduleSearch' => array(
                'reqType' => 'GET',
                'path' => array('Meetings'),
                'pathVars' => array(''),
                'method' => 'globalSearch',
                'shortHelp' => 'Search records in this module',
                'longHelp' => 'include/api/help/getListModule.html',
            ),

        );
    }


    public function __construct() {
    }
    
    /**
     * This function is the global search
     * @param $api ServiceBase The API class of the request
     * @param $args array The arguments array passed in from the API
     * @return array result set
     */
    public function globalSearch(ServiceBase $api, array $args) {
        require_once('include/SugarSearchEngine/SugarSearchEngineFactory.php');

        // This is required to keep the loadFromRow() function in the bean from making our day harder than it already is.
        $GLOBALS['disable_date_format'] = true;

        $options = $this->parseSearchOptions($api,$args);
        $options['moduleList'] = $options['moduleFilter'] = array('Meetings');

        if(!isset($options['selectFields'])) {
            $options['selectFields'] = array();
        }

        // determine the correct serach engine, don't pass any configs and fallback to the default search engine if the determiend one is down
        $searchEngine = SugarSearchEngineFactory::getInstance($this->determineSugarSearchEngine($api, $args, $options), array(), false);
        if ( $searchEngine instanceOf SugarSearchEngine) {
            $options['custom_where'] = "meetings.date_start >= " . $GLOBALS['db']->now();
            $orderBy = 'date_start ASC, id DESC';
            $orderByData['date_start'] = false;
            $orderByData['id'] = false;
            $options['orderByArray'] = $orderByData;
            $options['orderBy'] = $orderBy;
            $options['resortResults'] = true;
            $options['selectFields'][] = 'date_start';
            $options['selectFields'][] = 'id';
            $recordSet = $this->globalSearchSpot($api,$args,$searchEngine,$options);
            $sortByDateModified = true;
        } else {
            // add an option for filtering out meetings that have already started
            $options['filter']['fieldname'] = 'date_start';
            $options['filter']['type'] = 'range';
            $options['filter']['range']['from'] = gmdate('Y-m-d', strtotime("-30 minutes"));
            $options['filter']['range']['to'] = gmdate('Y-m-d',strtotime('+10 years'));
            $options['filter']['range']['include_lower'] = true;
            $options['filter']['range']['include_upper'] = true;
            $options['sort'][] = array('date_start' => array('order' => 'asc')); 

            $recordSet = $this->globalSearchFullText($api,$args,$searchEngine,$options);
            $sortByDateModified = false;
        }

        return $recordSet;

    }
}