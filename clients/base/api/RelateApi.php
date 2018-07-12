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


require_once('clients/base/api/ListApi.php');

class RelateApi extends ListApi {
    public function registerApiRest() {
        return array(
            'listRelatedRecords' => array(
                'reqType' => 'GET',
                'path' => array('<module>','?','link','?'),
                'pathVars' => array('module','record','','link_name'),
                'method' => 'listRelated',
                'shortHelp' => 'List related records to this module',
                'longHelp' => 'include/api/help/module_relate_help.html',
            ),
        );
    }

    public function __construct() {
        $this->defaultLimit = $GLOBALS['sugar_config']['list_max_entries_per_subpanel'];
    }
    
    public function listRelated($api, $args) {
        // Load up the bean
        $record = BeanFactory::getBean($args['module'], $args['record']);

        if ( empty($record) ) {
            throw new SugarApiExceptionNotFound('Could not find parent record '.$args['record'].' in module '.$args['module']);
        }
        if ( ! $record->ACLAccess('view') ) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: '.$args['module']);
        }
        // Load up the relationship
        $linkName = $args['link_name'];
        if ( ! $record->load_relationship($linkName) ) {
            // The relationship did not load, I'm guessing it doesn't exist
            throw new SugarApiExceptionNotFound('Could not find a relationship named: '.$args['link_name']);
        }
        // Figure out what is on the other side of this relationship, check permissions
        $linkModuleName = $record->$linkName->getRelatedModuleName();
        $linkSeed = BeanFactory::newBean($linkModuleName);
        if ( ! $linkSeed->ACLAccess('list') ) {
            throw new SugarApiExceptionNotAuthorized('No access to list records for module: '.$linkModuleName);
        }

        $options = $this->parseArguments($api, $args, $linkSeed);

        $linkParams = array(
            'where' => !empty($options['where']) ? $options['where'] : "",
            'deleted' => !empty($options['deleted']) ? $options['deleted'] : false,
            'orderby' => !empty($options['orderBy']) ? $options['orderBy'] : "",
        );

        $offset = !empty($options['offset']) ? $options['offset'] : 0;
        $limit = !empty($options['limit']) ? $options['limit'] : $this->defaultLimit;

        // If we want the last page, here is the magic to get there.
        if($offset === 'end'){
            $result = $record->$linkName->query($linkParams);
            $totalCount = sizeof($result['rows']);
            if ($totalCount > 0)
                $offset = (floor(($totalCount -1) / $limit)) * $limit;
        }

        $linkParams['offset'] = $offset;
        //Add one to the limit so we can figure out if there are more pages
        $linkParams['limit'] = $limit + 1;

        $relatedBeans = $record->$linkName->getBeans($linkParams);
        $count = sizeof($relatedBeans);
        if ( $count > $limit ) {
            $nextOffset = $offset + $limit;
            //Remove the last entry to keep the result set the correct page size
            array_pop($relatedBeans);
        } else {
            $nextOffset = -1;
        }

        $response = array();
        $response["next_offset"] = $nextOffset;
        $api->action = 'list';
        $response["records"] = $this->formatBeans($api, $args, $relatedBeans);
        return $response;
    }
}
