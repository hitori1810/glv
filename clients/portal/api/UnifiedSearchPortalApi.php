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

require_once 'clients/base/api/UnifiedSearchApi.php';

class UnifiedSearchPortalApi extends UnifiedSearchApi {
    /**
     * This function is used to determine the search engine to use
     * @param $api ServiceBase The API class of the request
     * @param $args array The arguments array passed in from the API
     * @param $options array An array of options to pass through to the search engine, they get translated to the $searchOptions array so you can see exactly what gets passed through
     * @return string name of the Search Engine
     */
    protected function determineSugarSearchEngine(ServiceBase $api, array $args, array $options)
    {
        return 'SugarSearchEngine';
    }
}
