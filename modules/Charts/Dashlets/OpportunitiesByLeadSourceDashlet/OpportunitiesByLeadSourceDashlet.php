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





require_once('include/Dashlets/DashletGenericChart.php');

class OpportunitiesByLeadSourceDashlet extends DashletGenericChart 
{
    public $pbls_lead_sources = array();
    public $pbls_ids          = array();
    
    /**
     * @see DashletGenericChart::$_seedName
     */
    protected $_seedName = 'Opportunities';
    
    /**
     * @see DashletGenericChart::displayOptions()
     */
    public function displayOptions()
    {
        global $app_list_strings;
        
        $selected_datax = array();
        if (!empty($this->pbls_lead_sources) && sizeof($this->pbls_lead_sources) > 0)
            foreach ($this->pbls_lead_sources as $key)
                $selected_datax[] = $key;
        else
            $selected_datax = array_keys($app_list_strings['lead_source_dom']);
        
        $this->_searchFields['pbls_lead_sources']['options'] = array_filter($app_list_strings['lead_source_dom']);
        $this->_searchFields['pbls_lead_sources']['input_name0'] = $selected_datax;
        
        if (!isset($this->pbls_ids) || count($this->pbls_ids) == 0)
			$this->_searchFields['pbls_ids']['input_name0'] = array_keys(get_user_array(false));
        
        return parent::displayOptions();
    }
    
    /**
     * @see DashletGenericChart::display()
     */
    public function display() 
    {
    	global $current_user, $sugar_config;
        require("modules/Charts/chartdefs.php");
        $chartDef = $chartDefs['pipeline_by_lead_source'];
        
        require_once('include/SugarCharts/SugarChartFactory.php');
        $sugarChart = SugarChartFactory::getInstance();
        $sugarChart->is_currency = true; 
        $currency_symbol = $sugar_config['default_currency_symbol'];
        if ($current_user->getPreference('currency')){
            
            $currency = new Currency();
            $currency->retrieve($current_user->getPreference('currency'));
            $currency_symbol = $currency->symbol;
        }
        $subtitle = translate('LBL_OPP_SIZE', 'Charts') . " " . $currency_symbol . "1" . translate('LBL_OPP_THOUSANDS', 'Charts');
        $sugarChart->setProperties('', $subtitle, $chartDef['chartType']);
        $sugarChart->base_url = $chartDef['base_url'];
        $sugarChart->group_by = $chartDef['groupBy'];
        $sugarChart->url_params = array();
        if ( count($this->pbls_ids) > 0 )
            $sugarChart->url_params['assigned_user_id'] = array_values($this->pbls_ids);	
        $sugarChart->getData($this->constructQuery());
        $sugarChart->data_set = $sugarChart->sortData($sugarChart->data_set, 'lead_source', true);
		$xmlFile = $sugarChart->getXMLFileName($this->id);
		$sugarChart->saveXMLFile($xmlFile, $sugarChart->generateXML());
	
		return $this->getTitle('<div align="center"></div>') . 
            '<div align="center">' . $sugarChart->display($this->id, $xmlFile, '100%', '480', false) . '</div>'. $this->processAutoRefresh();
    }  
    
    /**
     * @see DashletGenericChart::constructQuery()
     */
    protected function constructQuery()
    {
		$query = "SELECT lead_source,sum(total_in_invoice/1000) as total,count(*) as opp_count ".
                    "FROM opportunities ";
		$this->getSeedBean()->add_team_security_where_clause($query);
		$query .= "WHERE opportunities.deleted=0 ";
		if ( count($this->pbls_ids) > 0 )
            $query .= "AND opportunities.assigned_user_id IN ('".implode("','",$this->pbls_ids)."') ";
        if ( count($this->pbls_lead_sources) > 0 )
            $query .= "AND opportunities.lead_source IN ('".implode("','",$this->pbls_lead_sources)."') ";
		else
            $query .= "AND opportunities.lead_source IN ('".implode("','",array_keys($GLOBALS['app_list_strings']['lead_source_dom']))."') ";
        $query .= "GROUP BY lead_source ORDER BY total DESC";

        return $query;		
	}
}