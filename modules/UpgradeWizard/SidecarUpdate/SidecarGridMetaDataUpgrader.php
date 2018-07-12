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

// This will need to be pathed properly when packaged
require_once 'SidecarAbstractMetaDataUpgrader.php';

class SidecarGridMetaDataUpgrader extends SidecarAbstractMetaDataUpgrader
{
    /**
     * The metadata array key for the panels/data section. Wireless calls them
     * panels, portal call them data.
     * 
     * @var array
     */
    protected $panelKeys = array(
        'portaledit'     => 'data',
        'portaldetail'   => 'data',
        'wirelessedit'   => 'panels',
        'wirelessdetail' => 'panels',
    );
    
    /**
     * Converts the legacy Grid metadata to Sidecar style
     * 
     * Because there were additions to the grid metadata files, the upgrader will
     * actually not use the full legacy metadata but only the panel defs. The rest
     * of the metadata will come from the 6.6+ style metadata.
     */
    public function convertLegacyViewDefsToSidecar() {
        $this->logUpgradeStatus('Converting ' . $this->client . ' ' . $this->viewtype . ' view defs for ' . $this->module);
        
        // Leave the original legacy viewdefs in tact
        $defs = $this->legacyViewdefs;
        
        // Find out which panel key to use based on viewtype and client
        $panelKey = $this->panelKeys[$this->client.$this->viewtype];
        if (isset($defs[$panelKey])) {
            $fields = array();
            
            // Necessary for setting the proper field array types
            $maxcols = isset($defs['templateMeta']['maxColumns']) ? intval($defs['templateMeta']['maxColumns']) : 2;
            foreach ($defs[$panelKey] as $row) {
                $cols = count($row);
                // Assumption here is that Portal and Wireless will never have 
                // more than 2 columns in the old setup
                if ($cols == 1) {
                    $displayParams = array('colspan' => $maxcols);
                    // Either a string field name or an instruction
                    if (is_string($row[0])) {
                        if ($maxcols == 1) {
                            $fields[] = $row[0];
                        } else {
                            $fields[] = array('name' => $row[0], 'displayParams' => $displayParams);
                        }
                    } else {
                        // Some sort of instruction set
                        if (is_array($row[0])) { 
                            if (isset($row[0]['field'])) {
                                // Old style field now maps to name
                                $field = $row[0]['field'];
                                unset($row[0]['field']);
                                $fields[] = array_merge(
                                    array('name' => $field), 
                                    $row[0], 
                                    $maxcols == 1 ? array() : array('displayParams' => $displayParams)
                                );
                            } else {
                                // Fallback... take it as is
                                $fields[] = $row[0];
                            }
                        }
                    }
                } else {
                    // We actually have the necessary col count
                    foreach ($row as $field) {
                        if (is_string($field)) {
                            $fields[] = $field;
                        } elseif (isset($field['field'])) {
                            $fields[] = $field['field'];
                        }
                    }
                    
                }
            }
            
            // Bug 55568 - new metadata was not included for custom metadata
            // conversion from pre-6.6 installations.
            // Grab the new metadata for this module. For undeployed modules we 
            // need to get the metadata from the SugarObject type.
            if (!isset($GLOBALS['moduleList'])) {
                require 'include/modules.php';
            } else {
                $moduleList = $GLOBALS['moduleList'];
            }
            // Clean up client to mobile for wireless clients
            $client = $this->client == 'wireless' ? 'mobile' : $this->client;
            
            // The new defs array - this should contain OOTB defs for the module
            $newdefs = array();
            
            // If there are defs for this module, grab them
            if (in_array($this->module, $moduleList)) {
                $newdefsFile = 'modules/' . $this->module . '/clients/' . $client . '/views/' . $this->viewtype . '/' . $this->viewtype . '.php';
                if (file_exists($newdefsFile)) {
                    require $newdefsFile;
                    if (isset($viewdefs[$this->module][$client]['view'][$this->viewtype])) {
                        $newdefs = $viewdefs[$this->module][$client]['view'][$this->viewtype];
                    }
                }
            } 
            
            // Fallback to the object type if there were no defs found
            // Bug 57216 - Upgrade wizard was dying on undeployed modules getType
            if (empty($newdefs) && $this->deployed) {
                require_once 'modules/ModuleBuilder/Module/StudioModuleFactory.php';
                $sm = StudioModuleFactory::getStudioModule($this->module);
                $moduleType = $sm->getType();
                $newdefsFile = 'include/SugarObjects/templates/' . $moduleType . '/clients/' . $client . '/views/' . $this->viewtype . '/' . $this->viewtype . '.php';
                if (file_exists($newdefsFile)) {
                    require $newdefsFile;
                } else {
                    $newdefsFile = 'include/SugarObjects/templates/basic/clients/' . $client . '/views/' . $this->viewtype . '/' . $this->viewtype . '.php';
                    if (file_exists($newdefsFile)) {
                        require $newdefsFile;
                    }
                }
                
                // See if there are viewdefs defined that we can use
                if (isset($viewdefs['<module_name>'][$client]['view'][$this->viewtype])) {
                    $newdefs = $viewdefs['<module_name>'][$client]['view'][$this->viewtype];
                }
            }
            
            // If we still don't have new viewdefs then fall back onto the old 
            // ones. This shouldn't happen, but we need to make sure we have defs
            if (empty($newdefs)) {
                $newdefs = $defs;
            }
            
            // Set the new panel defs from the fields that were just converted
            $paneldefs = array(array('label' => 'LBL_PANEL_DEFAULT', 'fields' => $fields));
            
            // Kill the data (old defs) and panels (new defs) elements from the defs
            unset($newdefs['data'], $newdefs['panels']);
            
            // Create, or recreate, the panel defs
            $newdefs['panels'] = $paneldefs;
            
            // Clean up the module name for saving
            $module = $this->getNormalizedModuleName();
            $this->logUpgradeStatus("Setting new $client {$this->type} view defs internally for $module");
            // Setup the new defs
            $this->sidecarViewdefs[$module][$client]['view'][$this->viewtype] = $newdefs;
        }
    }
}