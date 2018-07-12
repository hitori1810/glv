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


require_once('include/MVC/View/SugarView.php');
require_once('include/SugarSearchEngine/SugarSearchEngineFactory.php');
require_once('include/SugarSearchEngine/SugarSearchEngineMetadataHelper.php');

class ViewFts extends SugarView
{
    private $fullView = FALSE;
    private $templateName = '';
    private $rsTemplateName = 'fts_full_rs.tpl';

    public function __construct()
    {
        $this->fullView = !empty($_REQUEST['full']) ? TRUE : FALSE;

        if($this->fullView)
        {
            $this->options = array('show_title'=> true,'show_header'=> true,'show_footer'=> true,'show_javascript'=> true,'show_subpanels'=> false,'show_search'=> false);
            $this->templateName = 'fts_full.tpl';
        }
        else
        {
            $this->options = array('show_title'=> false,'show_header'=> false,'show_footer'=> false,'show_javascript'=> false,'show_subpanels'=> false,'show_search'=> false);
            $this->templateName = 'fts_spot.tpl';
        }
        parent::__construct();

    }
    /**
     * @see SugarView::display()
     */
    public function display($return = false, $encode = false)
    {
        $offset = isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0;
        $resultSetOnly = !empty($_REQUEST['rs_only']) ? $_REQUEST['rs_only'] : FALSE;
        $refreshModuleFilter = !empty($_REQUEST['refreshModList']) ? $_REQUEST['refreshModList'] : FALSE;

        $limit = ( !empty($GLOBALS['sugar_config']['max_spotresults_initial']) ? $GLOBALS['sugar_config']['max_spotresults_initial'] : 10 );
        $indexOffset = $offset / $limit;
        $moduleFilter = !empty($_REQUEST['m']) ? $_REQUEST['m'] : FALSE;
        $filteredModules =  $this->getFilterModules();
        //If no modules have been passed in then lets check user preferences.
        if($moduleFilter === FALSE)
        {
            $moduleFilter = SugarSearchEngineMetadataHelper::getUserEnabledFTSModules();
        }
        $append_wildcard = !empty($_REQUEST['append_wildcard']) ? $_REQUEST['append_wildcard'] : FALSE;
        $options = array('current_module' => $this->module, 'moduleFilter' => $moduleFilter, 'append_wildcard' => $append_wildcard);

        if( $this->fullView || $refreshModuleFilter)
        {
            $options['apply_module_facet'] = TRUE;
        }

        $searchEngine = SugarSearchEngineFactory::getInstance();
        $queryString = !empty($_REQUEST['q']) ? $_REQUEST['q'] : '';
        $trimmed_query = trim($queryString);
        $rs = $searchEngine->search($trimmed_query, $offset, $limit, $options);
        if($rs == null)
        {
            $totalHitsFound = 0;
            $totalTime = 0;
            $hitsByModule = array();
        }
        else
        {
            $totalHitsFound = $rs->getTotalHits();
            $totalTime = $rs->getTotalTime();
            $hitsByModule = $rs->getModuleFacet();
        }
        $query_encoded = urlencode($trimmed_query);



        $showMoreDivStyle = ($totalHitsFound > $limit) ? '' : "display:none;";
        $this->ss->assign('showMoreDivStyle', $showMoreDivStyle);
        $this->ss->assign('indexOffset', $indexOffset);
        $this->ss->assign('offset', $offset);
        $this->ss->assign('limit', $limit);
        $this->ss->assign('totalHits', $totalHitsFound);
        $this->ss->assign('totalTime', $totalTime);
        $this->ss->assign('queryEncoded', $query_encoded);
        $this->ss->assign('resultSet', $rs);
        $this->ss->assign('APP_LIST', $GLOBALS['app_list_strings']);
        $template = SugarAutoLoader::existingCustomOne("include/MVC/View/tpls/{$this->templateName}");
        $rsTemplate = SugarAutoLoader::existingCustomOne("include/MVC/View/tpls/{$this->rsTemplateName}");
        $this->ss->assign('rsTemplate', $rsTemplate);

        if( $this->fullView )
        {
            $this->ss->assign('filterModules',$this->filterModuleListByTypes($filteredModules['enabled'], $hitsByModule));
            if($resultSetOnly)
            {
                $out = array('results' => $this->ss->fetch($rsTemplate), 'totalHits' => $totalHitsFound, 'totalTime' => $totalTime);
                if( $refreshModuleFilter )
                    $out['mod_filter'] = $this->ss->fetch('include/MVC/View/tpls/fts_modfilter.tpl');

                return $this->sendOutput(json_encode($out));
            }

            $this->ss->assign('enabled_modules', json_encode($filteredModules['enabled']));
            $this->ss->assign('disabled_modules', json_encode($filteredModules['disabled']));
        }

        $contents = $this->ss->fetch($template);
        return $this->sendOutput($contents, $return, $encode);

    }

    /**
     * Given the enable module list and a facet result set for the last query, add
     * a count to the filter module list.
     *
     * @param $modulelist
     * @param $facetResults
     * @return mixed
     */
    protected function filterModuleListByTypes($modulelist, $facetResults )
    {
        if($facetResults === FALSE)
            return $modulelist;

        foreach($modulelist as &$moduleEntry)
        {
            if( isset($facetResults[$moduleEntry['module']]) )
                $moduleEntry['count'] = $facetResults[$moduleEntry['module']];
            else
            {
                if (empty($_REQUEST['m']) || in_array($moduleEntry['module'], $_REQUEST['m']))
                {
                    $moduleEntry['count'] = 0;
                }
                else
                {
                    $moduleEntry['count'] = '';
                }
            }
        }

        return $modulelist;
    }


    protected function sendOutput($contents, $return = false, $encode = false)
    {
        if($encode)
            $contents = json_encode(array('results' => $contents));
        if($return)
            return $contents;
        else
            echo $contents;
    }

    /**
     * Get the enabled and disabled modules for the datatable
     *
     * @return array
     */
    protected function getFilterModules()
    {
        $filteredEnabled = SugarSearchEngineMetadataHelper::getUserEnabledFTSModules();
        $userDisabled = $GLOBALS['current_user']->getPreference('fts_disabled_modules');
        $userDisabled = explode(",", $userDisabled);

        $userDisabled = $this->translateModulesList($userDisabled);
        $filteredEnabled = $this->translateModulesList($filteredEnabled);
        sort($filteredEnabled);

        return array('enabled' => $filteredEnabled, 'disabled' => $userDisabled);
    }

    /**
     * Translate a list of modules to the format expected by our YUI datatables.
     *
     * @param $module
     * @return array
     */
    protected function translateModulesList($module)
    {
        $modulesTranslated = array();
        asort($module);
        foreach($module as $m)
        {
            $moduleName = isset($GLOBALS['app_list_strings']['moduleList'][$m]) ? $GLOBALS['app_list_strings']['moduleList'][$m] : $m;
            $modulesTranslated[] = array('module'=> $m, 'label' => $moduleName);
        }
        return $modulesTranslated;
    }
}

