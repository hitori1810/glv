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


class FilterDictionary
{
    /**
     * Where is this stored at
     *
     * @var string
     */
    protected $cacheDir;

    public function __construct()
    {
        $this->cacheDir = sugar_cached('include/SugarParsers/Filters/');
    }

    public function resetCache()
    {
        $dictFile = $this->cacheDir . 'FilterDictionary.php';
        @unlink($dictFile);
    }

    public function loadDictionaryFromStorage()
    {
        $dictFile = $this->cacheDir . 'FilterDictionary.php';
        if (!file_exists($dictFile)) {
            // No stored service dictionary, I need to build them
            $this->buildAllDictionaries();
        }

        // create the variable just in case.
        $filterDictionary = array();

        // load the cache file
        require($dictFile);

        // return the variable from the cache file
        return $filterDictionary;
    }

    protected function saveDictionaryToStorage($storageData)
    {
        if (!is_dir($this->cacheDir)) {
            sugar_mkdir($this->cacheDir, null, true);
        }

        sugar_file_put_contents($this->cacheDir . 'FilterDictionary.php', '<' . "?php\n\$filterDictionary = " . var_export($storageData, true) . ";\n");

    }

    protected function buildAllDictionaries()
    {
        foreach(SugarAutoLoader::getFilesCustom('include/SugarParsers/Filter', false, "php") as $file) {
            require_once $file;
            $fileClass = SugarAutoLoader::customClass('SugarParsers_Filter_' . substr(basename($file), 0, -4));
            if (!(class_exists($fileClass)
                    && is_subclass_of($fileClass, 'SugarParsers_Filter_AbstractFilter'))
                ) {
                    // Either the class doesn't exist, or it's not a subclass of SugarApi, regardless, we move on
                    continue;
            }
            /* @var $obj SugarParsers_Filter_AbstractFilter */
            $obj = new $fileClass();
            $variables = $obj->getVariables();

            foreach($variables as $var) {
            	$filterRegistry[$var] = array('class' => $fileClass, 'file' => $file);
            }
        }

        $this->saveDictionaryToStorage($filterRegistry);
    }
}
