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

require_once 'modules/ModuleBuilder/parsers/MetaDataFiles.php';

/**
 * Handles migration of wireless and portal metadata for pre-6.6 modules into 6.6+
 * formats. Looks for the following metadata to migrate and remove legacy versions of:
 *  - custom, history and working portal module metadata
 *  - custom, history and working wireless metadata
 *  - base, custom, history and working for deployed and undeployed custom modules wireless metadata
 *  
 * Also looks for the following metadata to remove legacy versions of:
 *  - All OOTB module wireless metadata (will be replaced on upgrade)
 *  - All SugarObject templates wireless metadata (will be replaced on upgrade)
 * 
 * This upgrader should be the last of the upgraders run as it relies on files that
 * are new to 6.6. As part of this upgrader will handle setting of files to be 
 * removed, which will be handled by 
 * ../build/scripts_for_patch/files_to_remove/UpgradeRemoval66x.php
 */
class SidecarMetaDataUpgrader
{
    /**
     * Files to be removed by the upgrader after handling the processing of this
     * script
     * 
     * @var array
     */
    protected static $filesForRemoval = array();

    /**
     * Listing of modules that need to be upgraded
     * 
     * @var array
     */
    protected $upgradeModules = array();
    
    /**
     * The list of paths for both portal and wireless viewdefs under the legacy
     * system.
     *
     * @var array
     */
    protected $legacyFilePaths = array(
        'portal' => array(
            'custom'  => 'custom/portal/',
            'history' => 'custom/portal/',
            'working' => 'custom/working/portal/',
        ),
        'wireless' => array(
            'custom'  => 'custom/',
            'history' => 'custom/history/',
            'working' => 'custom/working/',
        ),
    );

    /**
     * Maps of old metadata file names
     *
     * @var array
     */
    protected $legacyMetaDataFileNames = array(
        'wireless' => array(
            MB_WIRELESSEDITVIEW       => 'wireless.editviewdefs' ,
            MB_WIRELESSDETAILVIEW     => 'wireless.detailviewdefs' ,
            MB_WIRELESSLISTVIEW       => 'wireless.listviewdefs' ,
            MB_WIRELESSBASICSEARCH    => 'wireless.searchdefs' ,
            // Advanced is unneeded since it shares with basic
            //MB_WIRELESSADVANCEDSEARCH => 'wireless.searchdefs' ,
        ),
        'portal' => array(
            MB_PORTALEDITVIEW         => 'editviewdefs',
            MB_PORTALDETAILVIEW       => 'detailviewdefs',
            MB_PORTALLISTVIEW         => 'listviewdefs',
            MB_PORTALSEARCHVIEW       => 'searchformdefs',
        ),
    );

    /**
     * Listing of actual metadata files, by client
     *
     * @var array
     */
    protected $files = array();
    
    /**
     * List of Sidecar*MetaDataUpgrader classes that map to a view type
     * 
     * @var array
     */
    protected $upgraderClassMap = array(
        'list'   => 'List',
        'edit'   => 'Grid',
        'detail' => 'Grid',
        'search' => 'Search',
    );
    
    /**
     * List of failed upgrades
     * 
     * @var array
     */
    protected $failures = array();

    /**
     * Flag to tell the upgrader to write to the log or not. On by default. Can
     * be toggled using {@see toggleWriteToLog()}
     * 
     * @var bool
     */
    protected $writeToLog = true;

    /**
     * Sets the list of files that need to be upgraded. Will look in directories 
     * contained in $legacyFilePaths and will also attempt to identify custom
     * modules that are found within modules/
     */
    public function setFilesToUpgrade() 
    {
        $this->setPortalFilesToUpgrade();
        $this->setMobileFilesToUpgrade();
    }
    
    /**
     * Sets the listing of customized portal module metadata to upgrade
     */
    public function setPortalFilesToUpgrade()
    {
        $this->setUpgradeFiles('portal');
    }
    
    /**
     * Sets the listing of customized mobile module metadata to upgrade. Will 
     * also scrape custom modules (deployed and undeployed) looking for all custom
     * modules and their respective metadata to upgrade.
     */
    public function setMobileFilesToUpgrade()
    {
        $metatype = 'wireless';
        $this->setUpgradeFiles($metatype);
        
        // Get custom modules. We need both DEPLOYED and UNDEPLOYED
        // Undeployed will be those in packages that are NOT in builds but are
        // also in modules
        
        require_once 'modules/ModuleBuilder/MB/ModuleBuilder.php';
        $mb = new ModuleBuilder();
        
        // Set the packages and modules in place
        $mb->getPackages();
        
        // Set the core app module path for checking deployment
        $modulepath = 'modules/';
        
        // Handle module list making. We need to look for metadata in three places:
        // - modules/
        // - custom/modulebuilder/packages/<PACKAGENAMES>/modules/<MODULENAME>/metadata
        // - custom/modulebuilder/builds/<PACKAGENAMES>/SugarModules/modules/<PACKAGEKEY>_<MODULENAME>/metadata
        //
        // The first path will be handled if we don't send the packagename and deployed status
        // The second path will be handled by history types with a package name and undeployed status
        // The last path will be handdled by base types with a package name and undeployed status
        // 
        $this->logUpgradeStatus('Beginning search for custom module mobile metadata...');
        // Count for logging
        $total = 0;
        foreach ($mb->packages as $packagename => $package) {
            // For count logging
            $count = $deployedcount = $undeployedcount = 0;
            $buildpath = $package->getBuildDir() . '/SugarModules/modules/';
            foreach ($package->modules as $module => $mbmodule) {
                $appModulePath = $modulepath . $package->key . '_' . $module;
                $mbbModulePath = $buildpath . $package->key . '_' . $module;
                $packagePath   = $package->getPackageDir() . '/modules/' . $module;
                $deployed = file_exists($appModulePath) && file_exists($mbbModulePath);
                
                // For deployed modules we need to get 
                if ($deployed) {
                    // Reset the module name to the key_module name format
                    $module = $package->key . '_' . $module;
                    
                    // Get the metadata directory
                    $metadatadir = "$appModulePath/metadata/";
                    
                    // Get our upgrade files as base files since these are regular metadata
                    $files = $this->getUpgradeableFilesInPath($metadatadir, $module, $metatype);
                    $count += count($files);
                    $deployedcount += count($files);
                    $this->files = array_merge($this->files, $files);
                    
                    // For deployed modules we still need to handle package dir metadata
                    $metadatadir = "$mbbModulePath/metadata/";
                    
                    // Get our upgrade files as undeployed base type wireless client
                    $files = $this->getUpgradeableFilesInPath($metadatadir, $module, $metatype, 'base', $packagename, false);
                    $count += count($files);
                    $deployedcount += count($files);
                    $this->files = array_merge($this->files, $files);
                } else {
                    // Handle undeployed history metadata
                    $metadatadir = "$packagePath/metadata/";
                    
                    // Get our upgrade files
                    $files = $this->getUpgradeableFilesInPath($metadatadir, $module, $metatype, 'history', $packagename, false);
                    $count += count($files);
                    $undeployedcount += count($files);
                    $this->files = array_merge($this->files, $files);
                }
            }
            $this->logUpgradeStatus("$count upgrade files set for package $packagename: Deployed - $deployedcount, Undeployed - $undeployedcount ...");
            $total += $count;
        }
        $this->logUpgradeStatus('Custom module mobile metadata done.');
        $this->logUpgradeStatus("$total custom module files fetched for conversion");
    }
    
    /**
     * Checks to see if a module is deployed
     * 
     * @param sting $module The name of the module
     * @return bool
     */
    public function isModuleDeployed($module) {
        if (empty($this->deployedModules)) {
            $dirs = glob('modules/*', GLOB_ONLYDIR);
            foreach ($dirs as $dir) {
                $this->deployedModules[$dir] = $dir;
            }
            
            sort($this->deployedModules);
        }
        
        return isset($this->deployedModules[$module]);
    }
    
    /**
     * Processes the entire upgrade process of old to new metadata styles
     */
    public function upgrade() 
    {
        // Set the upgrade file list
        $this->logUpgradeStatus('Setting upgrade file list...');
        $this->setFilesToUpgrade();
        
        // Traverse the files and start parsing and moving
        $this->logUpgradeStatus('Beginning mobile/portal metadata upgrade process...');
        foreach ($this->files as $file) {
            // Get the appropriate upgrade class name for this view type
            $class = $this->getUpgraderClass($file['viewtype']);
            if ($class) {
                if (!class_exists($class, false)) {
                    $classfile = $class . '.php';
                    require_once $classfile;
                }
                
                $upgrader = new $class($this, $file);
                
                // If the upgrade worked for this file, add it to the remove stack
                $this->logUpgradeStatus("Delegating upgrade to $class ...");
                if ($upgrader->upgrade()) {
                    if (!in_array($file['fullpath'], self::$filesForRemoval)) {
                        self::$filesForRemoval[] = $file['fullpath'];
                    }
                } else {
                    $this->registerFailure($file);
                }
                $this->logUpgradeStatus("{$class} :: upgrade() complete...");
            }
        }
        $this->logUpgradeStatus('Mobile/portal metadata upgrade process complete.');
        
        // Add the rest of the OOTB module wireless metadata files to the stack
        $this->cleanupLegacyFiles();
    }
    
    /**
     * Gets the list of failed upgrades
     * @return array
     */
    public function getFailures()
    {
        return $this->failures;
    }
    
    /**
     * Registers a failed filedata array
     * 
     * @param array $file
     */
    protected function registerFailure($file)
    {
        $this->failures[] = $file;
    }
    
    /**
     * Gets all files for a client that need to be upgraded. This is OOTB install
     * only! Custom modules are handled differently inside the call for mobile
     * file setting.
     * 
     * @param $client
     */
    protected function setUpgradeFiles($client) 
    {
        $this->logUpgradeStatus("Getting $client upgrade files ...");
        
        // Keep track of how many files were added, for logging
        $count = 0;
        
        // Hit the legacy paths list to start the ball rolling 
        if (isset($this->legacyFilePaths[$client]) && is_array($this->legacyFilePaths[$client])) {
            foreach ($this->legacyFilePaths[$client] as $type => $path) {
                // Get the modules from inside the path
                $dirs = glob($path . 'modules/*', GLOB_ONLYDIR);
                if (!empty($dirs)) {
                    foreach ($dirs as $dirpath) {
                        // Get the module to list it in case it needs to be upgraded
                        $module = basename($dirpath);
                        
                        // Get the metadata directory
                        $metadatadir = "$dirpath/metadata/";
                        
                        // Get our upgrade files
                        $files = $this->getUpgradeableFilesInPath($metadatadir, $module, $client, $type);
                        
                        // Increment the count
                        $count += count($files);
                        
                        // Merge them
                        $this->files = array_merge($this->files, $files);
                    }
                }
            }
        }
        $this->logUpgradeStatus("$count $client upgrade files set ...");
    }
    
    /**
     * Gets all metadata files that need to be upgraded for a module
     * 
     * @param string $path    The path to scan for metadata files
     * @param string $module  The module name, used for indexing
     * @param string $client  The client, also used for indexing
     * @param string $type    The type (custom, history, working, base)
     * @param string $package The name of the package for this module if custom
     * @param boolean $deployed Marker to determine if a custom module is deployed or not
     * @return array
     */
    public function getUpgradeableFilesInPath($path, $module, $client, $type = 'base', $package = null, $deployed = true) 
    {
        $return = array();
        if (file_exists($path)) {
            // The second * is to pick up history files
            $files = glob($path . '*.php*'); 
            
            // And if we have any, match them against what we are looking for
            if (!empty($files)) {
                foreach ($files as $file) {
                    if (($data = $this->getUpgradeFileParams($file, $module, $client, $type, $package, $deployed)) !== false) {
                        $return[] = $data;
                    }
                }
            }
        }
        
        return $return;
    }
    
    /**
     * Adds a module name to the list of upgradeable modules, for reporting
     * 
     * @param string $module The module name
     */
    protected function addUpgradeModule($module) 
    {
        if (empty($this->upgradeModules[$module])) {
            $this->upgradeModules[$module] = $module;
        }
    }
    
    /**
     * Gets a view type from a filename
     * 
     * @param string $filename The name of the file to get the view type from
     * @return string
     */
    protected function getViewTypeFromFilename($filename)
    {
        if (strpos($filename, 'list') !== false) {
            return 'list';
        }
        
        if (strpos($filename, 'edit') !== false) {
            return 'edit';
        }
        
        if (strpos($filename, 'detail') !== false) {
            return 'detail';
        }
        
        if (strpos($filename, 'search') !== false) {
            return 'search';
        }
        
        return '';
    }
    
    /**
     * Gets the class name for the upgrader that will carry out the upgrade
     * 
     * @param string $viewtype The view type (list, edit, detail)
     * @return string
     */
    public function getUpgraderClass($viewtype) 
    {
        if (isset($this->upgraderClassMap[$viewtype])) {
            return 'Sidecar' . $this->upgraderClassMap[$viewtype] . 'MetaDataUpgrader';
        }
        
        return false;
    }
    
    /**
     * Adds to the stack of files for removal all wireless metadata that is 
     * currently living in OOTB modules and in all of the SugarObject templates
     * metadata directories.
     */
    protected function cleanupLegacyFiles() 
    {
        // In addition to all of the files we already worked on, we need to include
        // the OOTB wireless metadata files that fit the bill.
        $moduledirs = glob('modules/*', GLOB_ONLYDIR);
        foreach ($moduledirs as $moduledir) {
            $files = glob("$moduledir/metadata/*.php");
            foreach ($files as $filepath) {
                $filename = basename($filepath);
                
                // Handle history files and such
                if (is_numeric(substr($filename, -4))) {
                    $filename = substr($filename, 0, strpos('.php', $filename));
                } else {
                    $filename = basename($filename, '.php');
                }
                
                // If this file name is an upgrade file and it hasn't been stacked, stack it
                if (!empty($this->legacyMetaDataFileNames['wireless']) && in_array($filename, $this->legacyMetaDataFileNames['wireless']) && !in_array($filepath, self::$filesForRemoval)) {
                    self::$filesForRemoval[] = $filepath;
                }
            }
        }
        
        // And lastly we need to handle SugarObject Templates
        $path = 'include/SugarObjects/templates/';
        $SugarObjectsPaths = glob($path . '*', GLOB_ONLYDIR);
        foreach ($SugarObjectsPaths as $objPath) {
            $path = "$objPath/metadata/";
            $files = glob($path . '*.php');
            foreach ($files as $file) {
                $filename = basename($file, '.php');
                
                // If this file name is an upgrade file and it hasn't been stacked, stack it
                if (!empty($this->legacyMetaDataFileNames['wireless']) && in_array($filename, $this->legacyMetaDataFileNames['wireless']) && !in_array($file, self::$filesForRemoval)) {
                    self::$filesForRemoval[] = $file;
                }
            }
        }
    }
    
    /**
     * Gets a listing of all files that will need to be removed as part of the 
     * metadata upgrade to 6.6
     * 
     * @static
     * @return array
     */
    public static function getFilesForRemoval() 
    {
        return self::$filesForRemoval;
    }

    /**
     * Gets the listing of files that are to be upgraded
     * 
     * @return array
     */
    public function getFilesForUpgrade() 
    {
        return $this->files;
    }

    /**
     * Gets the count of the files that are to be upgraded
     * 
     * @return int
     */
    public function getCountOfFilesForUpgrade() 
    {
        return count($this->files);
    }

    /**
     * Logs a message to the upgrade wizard if logging is turned on
     * 
     * @param $message
     */
    public function logUpgradeStatus($message) 
    {
        if ($this->writeToLog) {
            if (!function_exists('logThis')) {
                require_once 'modules/UpgradeWizard/uw_utils.php';
            }
            
            logThis("Sidecar Upgrade: $message");
        }
    }

    /**
     * Toggles the writeToLog flag
     */
    public function toggleWriteToLog() 
    {
        $this->writeToLog = !$this->writeToLog;
    }

    /**
     * Gets the current value of the log write status
     * 
     * @return bool
     */
    public function getWriteToLogStatus() 
    {
        return $this->writeToLog;
    }

    /**
     * Gets the file data array needed for the upgraders to process the upgrade
     * 
     * @param string $file
     * @param string $module
     * @param string $client
     * @param string $type
     * @param string $package
     * @param bool $deployed
     * @return array Array of file params if found, false otherwise
     */
    public function getUpgradeFileParams($file, $module, $client, $type = 'base', $package = null, $deployed = true) 
    {
        // Timestamp for history files
        $timestamp = null;
        
        // Handle history file handling different
        $history = is_numeric(substr($file, -4));
        
        // In the case of undeployed modules, type may be set to base
        // If it is, and there is a history file, set type to history
        // This is primarily for saving new defs using the MetaDataFiles
        // class to get the correct name of the metadata file
        if ($history && !$deployed && $type == 'base') {
            $type = 'history';
        }
        
        // Only hit history files for history types with a timestamp
        // Unless we are looking at undeployed modules
        if (($history && $type != 'history') || (!$history && $type == 'history') && $deployed) {
            return false;
        }
        
        if ($history) {
            $parts = explode(':', str_replace('.php_', ':', $file));
            $filename  = basename($parts[0]);
            $timestamp = $parts[1];
        } else {
            $filename = basename($file, '.php');
        }
        
        if (!empty($this->legacyMetaDataFileNames[$client]) && in_array($filename, $this->legacyMetaDataFileNames[$client])) {
            // Success! We have a full file path. Add this module to the stack
            $this->addUpgradeModule($module);
            
            return array(
                'client'    => $client,
                'module'    => $module,
                'type'      => $type,
                'basename'  => $filename,
                'timestamp' => $timestamp,
                'fullpath'  => $file,
                'package'   => $package,
                'deployed'  => $deployed,
                'viewtype'  => $this->getViewTypeFromFilename($filename),
            );
        }
        
        return false;
    }
}
