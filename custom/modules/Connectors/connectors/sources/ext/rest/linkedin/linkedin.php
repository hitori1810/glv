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

require_once('include/connectors/sources/ext/rest/rest.php');
class ext_rest_linkedin extends ext_rest {
	public function __construct(){
		parent::__construct();
		$this->_enable_in_wizard = false;
		$this->_enable_in_hover = true;
	}
	
	/*
	 * getItem
	 *
	 * As the linked in connector does not have a true API call, we simply
	 * override this abstract method
	 */
	public function getItem($args=array(), $module=null){}


    /*
	 * getList
	 *
	 * As the linked in connector does not have a true API call, we simply
	 * override this abstract method

	 */

    public function getList($args = array(), $module = null)
    {
        $params = array('count' => 10, 'start' => 0);

        if (!empty($args['maxResults']))
            $params['count'] = $args['maxResults'];

        if (!empty($args['startIndex']))
            $params['start'] = $args['startIndex'];


        $results = FALSE;

        try
        {
            $queryFields = "(id,first-name,last-name,industry,headline,summary,location:(name,country:(code)),positions:(title,summary,company:(name)))";
            $url = "http://api.linkedin.com/v1/people/~/connections:$queryFields";
            $response = $this->_eapm->makeRequest("GET", $url, $params);
            $results = $this->formatListResponse($response);
        }

        catch (Exception $e)
        {
            $GLOBALS['log']->fatal("Unable to retrieve item list for linkedin connector.");
        }

        return $results;
    }


    private function formatListResponse($resp)
    {
        $records = array();
        $xmlResp = simplexml_load_string($resp);
        if ($xmlResp === FALSE)
            throw new Exception('Unable to parse list response');

        foreach ($xmlResp->person as $person)
        {
            $tmp = array();
            $this->convertPersonListResponeToArray($person, $tmp);
            $records[] = $tmp;

        }

        return array('totalResults' => (int)$xmlResp->attributes()->total,
                     'startIndex' => (int)$xmlResp->attributes()->start,
                     'records' => $records);
    }


    private function convertPersonListResponeToArray(SimpleXMLElement $xmlResp, &$result, $suffix = '')
    {
        foreach ((array)$xmlResp as $k => $v)
        {
            $key = !empty($suffix) ? "{$suffix}-{$k}" : $k;
            if ($v instanceof SimpleXMLElement) {
                $this->convertPersonListResponeToArray($v, $result, $key);
            }

            else if (is_array($v)) //Skip over attributes
            {
                if ($k == 'position')
                {
                    $latestPosition = $v[0];
                    $result['company_name'] = (string)$latestPosition->company->name;
                    $result['title'] = (string)$latestPosition->title;
                    $result['position-summary'] = (string)$latestPosition->summary;

                }
            }
            else
            {
                $result[$key] = $v;
            }
        }
    }
}

?>