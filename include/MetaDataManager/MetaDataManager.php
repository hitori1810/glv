<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
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


require_once('soap/SoapHelperFunctions.php');
require_once 'modules/ModuleBuilder/parsers/MetaDataFiles.php';
require_once 'include/SugarFields/SugarFieldHandler.php';
SugarAutoLoader::requireWithCustom('include/MetaDataManager/MetaDataHacks.php');
/**
 * This class is for access metadata for all sugarcrm modules in a read only
 * state.  This means that you can not modifiy any of the metadata using this
 * class currently.
 *
 *
 * @method Array getData getData() gets all meta data.
 *
 *
 *  "platform": is a bool value which lets you know if the data is for a mobile view, portal or not.
 *
 */
class MetaDataManager {
    /**
     * SugarFieldHandler, to assist with cleansing default sugar field values
     *
     * @var SugarFieldHandler
     */
    protected $sfh;

    /**
     * The user bean for the logged in user
     *
     * @var User
     */
    protected $user;

    /**
     * The metadata hacks class
     * 
     * @var metaDataHacks
     */
    protected $metaDataHacks;

    /**
     * The constructor for the class.
     *
     * @param User $user A User bean
     * @param array $platforms A list of clients
     * @param bool $public is this a public metadata grab
     */
    function __construct ($user, $platforms = null, $public = false) {
        if ( $platforms == null ) {
            $platforms = array('base');
        }

        $this->user = $user;
        $this->platforms = $platforms;
        $className = SugarAutoLoader::customClass('MetaDataHacks');
        $this->metaDataHacks = new $className();
    }

    /**
     * For a specific module get any existing Subpanel Definitions it may have
     * @param string $moduleName
     * @return array
     */
    public function getSubpanelDefs($moduleName)
    {
        require_once('include/SubPanel/SubPanelDefinitions.php');
        $parent_bean = BeanFactory::getBean($moduleName);
        //Hack to allow the SubPanelDefinitions class to check the correct module dir
        if (!$parent_bean){
            $parent_bean = (object) array('module_dir' => $moduleName);
        }

        $spd = new SubPanelDefinitions($parent_bean, '', '', $this->platforms[0]);
        $layout_defs = $spd->layout_defs;

        if(is_array($layout_defs) && isset($layout_defs['subpanel_setup']))
        {
            foreach($layout_defs['subpanel_setup'] AS $name => $subpanel_info)
            {
                $aSubPanel = $spd->load_subpanel($name, '', $parent_bean);

                if(!$aSubPanel)
                {
                    continue;
                }

                if($aSubPanel->isCollection())
                {
                    $collection = array();
                    foreach($aSubPanel->sub_subpanels AS $key => $subpanel)
                    {
                        $collection[$key] = $subpanel->panel_definition;
                    }
                    $layout_defs['subpanel_setup'][$name]['panel_definition'] = $collection;
                }
                else
                {
                    $layout_defs['subpanel_setup'][$name]['panel_definition'] = $aSubPanel->panel_definition;
                }

            }
        }

        return $layout_defs;
    }

    /**
     * This method collects all view data for a module
     *
     * @param $moduleName The name of the sugar module to collect info about.
     *
     * @return Array A hash of all of the view data.
     */
    public function getModuleViews($moduleName) {
        return $this->getModuleClientData('view',$moduleName);
    }

    /**
     * This method collects all view data for a module
     *
     * @param $moduleName The name of the sugar module to collect info about.
     *
     * @return Array A hash of all of the view data.
     */
    public function getModuleLayouts($moduleName) {
        return $this->getModuleClientData('layout', $moduleName);
    }

    /**
     * This method collects all field data for a module
     *
     * @param string $moduleName    The name of the sugar module to collect info about.
     *
     * @return Array A hash of all of the view data.
     */
    public function getModuleFields($moduleName) {
        return $this->getModuleClientData('field', $moduleName);
    }

    /**
     * The collector method for modules.  Gets metadata for all of the module specific data
     *
     * @param $moduleName The name of the module to collect metadata about.
     * @return array An array of hashes containing the metadata.  Empty arrays are
     * returned in the case of no metadata.
     */
    public function getModuleData($moduleName) {
        require_once('include/SugarSearchEngine/SugarSearchEngineMetadataHelper.php');
        $vardefs = $this->getVarDef($moduleName);

        $data['fields'] = isset($vardefs['fields']) ? $vardefs['fields'] : array();
        $data['views'] = $this->getModuleViews($moduleName);
        $data['layouts'] = $this->getModuleLayouts($moduleName);
        $data['fieldTemplates'] = $this->getModuleFields($moduleName);
        $data['subpanels'] = $this->getSubpanelDefs($moduleName);
        $data['config'] = $this->getModuleConfig($moduleName);

        $data['ftsEnabled'] = SugarSearchEngineMetadataHelper::isModuleFtsEnabled($moduleName);

        $seed = BeanFactory::newBean($moduleName);

        if ($seed !== false) {
            $favoritesEnabled = ($seed->isFavoritesEnabled() !== false) ? true : false;
            $data['favoritesEnabled'] = $favoritesEnabled;
        }

        $data["_hash"] = md5(serialize($data));

        return $data;
    }

    /**
     * Get the config for a specific module from the Administration Layer
     *
     * @param string $moduleName        The Module we want the data back for.
     * @return array
     */
    public function getModuleConfig($moduleName) {
        /* @var $admin Administration */
        $admin = BeanFactory::getBean('Administration');
        return $admin->getConfigForModule($moduleName, $this->platforms[0]);
    }

    /**
     * The collector method for relationships.
     *
     * @return array An array of relationships, indexed by the relationship name
     */
    public function getRelationshipData() {
        require_once('data/Relationships/RelationshipFactory.php');
        $relFactory = SugarRelationshipFactory::getInstance();

        $data = $relFactory->getRelationshipDefs();
        foreach ( $data as $relKey => $relData ) {
            unset($data[$relKey]['table']);
            unset($data[$relKey]['fields']);
            unset($data[$relKey]['indices']);
            unset($data[$relKey]['relationships']);
        }

        $data["_hash"] = md5(serialize($data));

        return $data;
    }

    /**
     * Gets vardef info for a given module.
     *
     * @param $moduleName The name of the module to collect vardef information about.
     * @return array The vardef's $dictonary array.
     */
    public function getVarDef($moduleName) {

        require_once("data/BeanFactory.php");
        $obj = BeanFactory::getObjectName($moduleName);

        require_once("include/SugarObjects/VardefManager.php");
        global $dictionary;
        VardefManager::loadVardef($moduleName, $obj);
        if ( isset($dictionary[$obj]) ) {
            $data = $dictionary[$obj];
        }

        // vardefs are missing something, for consistancy let's populate some arrays
        if (!isset($data['fields']) ) {
            $data['fields'] = array();
        }

        // Bug 56505 - multiselect fields default value wrapped in '^' character
        $data['fields'] = $this->normalizeFielddefs($data['fields']);

        if (!isset($data['relationships'])) {
            $data['relationships'] = array();
        }

        // loop over the fields to find if they can be sortable
        // get the indexes on the module and the first field of each index
        $indexes = array();
        if(isset($data['indices'])) {
            foreach($data['indices'] AS $index) {
                if(isset($index['fields'][0]))
                {
                    $indexes[$index['fields'][0]] = $index['fields'][0];
                }
            }
        }

        // If sortable isn't already set THEN
        //      Set it sortable to TRUE, if the field is indexed.
        //      Set sortable to FALSE, otherwise. (Bug56943, Bug57644)
        $isIndexed = !empty($indexes);
        foreach($data['fields'] AS $field_name => $info) {
            if(!isset($data['fields'][$field_name]['sortable'])){
                $data['fields'][$field_name]['sortable'] = false;
                if($isIndexed && isset($indexes[$field_name])) {
                    $data['fields'][$field_name]['sortable'] = true;
                }
            }
        }

        return $data;
    }

    /**
     * Gets the ACL's for the module, will also expand them so the client side of the ACL's don't have to do as many checks.
     *
     * @param string $module The module we want to fetch the ACL for
     * @param object $userObject The user object for the ACL's we are retrieving.
     * @param object|bool $bean The SugarBean for getting specific ACL's for a module
     * @return array Array of ACL's, first the action ACL's (access, create, edit, delete) then an array of the field level acl's
     */
    public function getAclForModule($module,$userObject,$bean=false) {
        $obj = BeanFactory::getObjectName($module);

        $outputAcl = array('fields'=>array());

        if (!SugarACL::moduleSupportsACL($module)) {
            foreach ( array('admin', 'access','view','list','edit','delete','import','export','massupdate') as $action ) {
                $outputAcl[$action] = 'yes';
            }
        } else {
            $context = array(
                    'user' => $userObject,
                );
            if($bean instanceof SugarBean) {
                $context['bean'] = $bean;
            }

            // if the bean is not set, or a new bean.. set the owner override
            // this will allow fields marked Owner to pass through ok.
            if($bean == false || empty($bean->id) || (isset($bean->new_with_id) && $bean->new_with_id == true)) {
                $context['owner_override'] = true;
            }
            
            $moduleAcls = SugarACL::getUserAccess($module, array(), $context);

            // Bug56391 - Use the SugarACL class to determine access to different actions within the module
            foreach(SugarACL::$all_access AS $action => $bool) {
                $outputAcl[$action] = ($moduleAcls[$action] == true || !isset($moduleAcls[$action])) ? 'yes' : 'no';
            }

            // is the user an admin user for the module
            $outputAcl['admin'] = ($userObject->isAdminForModule($module)) ? 'yes' : 'no';

            // Only loop through the fields if we have a reason to, admins give full access on everything, no access gives no access to anything
            if ( $outputAcl['access'] == 'yes') {

                // Currently create just uses the edit permission, but there is probably a need for a separate permission for create
                $outputAcl['create'] = $outputAcl['edit'];

                // Now time to dig through the fields
                $fieldsAcl = array();
                // we cannot use ACLField::getAvailableFields because it limits the fieldset we return.  We need all fields
                // for instance assigned_user_id is skipped in getAvailableFields, thus making the acl's look odd if Assigned User has ACL's
                // only assigned_user_name is returned which is a derived ["fake"] field.  We really need assigned_user_id to return as well.
                if(empty($GLOBALS['dictionary'][$module]['fields'])){
                    if($bean === false) {
                        $bean = BeanFactory::newBean($module);
                    }
                    if(empty($bean->acl_fields)) {
                        $fieldsAcl = array();
                    } else {
                        $fieldsAcl = $bean->field_defs;
                    }
                } else{
                    $fieldsAcl = $GLOBALS['dictionary'][$module]['fields'];
                    if(isset($GLOBALS['dictionary'][$module]['acl_fields']) && $GLOBALS['dictionary'][$module]=== false){
                        $fieldsAcl = array();
                    }   
                }
                // get the field names

                SugarACL::listFilter($module, $fieldsAcl, $context, array('add_acl' => true));
                $fieldsAcl = $this->metaDataHacks->fixAcls($fieldsAcl);
                foreach ( $fieldsAcl as $field => $fieldAcl ) {
                    switch ( $fieldAcl['acl'] ) {
                        case SugarACL::ACL_READ_WRITE:
                            // Default, don't need to send anything down
                            break;
                        case SugarACL::ACL_READ_ONLY:
                            $outputAcl['fields'][$field]['write'] = 'no';
                            $outputAcl['fields'][$field]['create'] = 'no';
                            break;
                        case 2:
                            $outputAcl['fields'][$field]['read'] = 'no';
                            break;
                        case SugarACL::ACL_NO_ACCESS:
                        default:
                            $outputAcl['fields'][$field]['read'] = 'no';
                            $outputAcl['fields'][$field]['write'] = 'no';
                            $outputAcl['fields'][$field]['create'] = 'no';
                            break;
                    }
                }
            }
        }
        $outputAcl['_hash'] = md5(serialize($outputAcl));
        return $outputAcl;
    }

    /**
     * Fields accessor, gets sugar fields
     *
     * @return array array of sugarfields with a hash
     */
    public function getSugarFields()
    {
        return $this->getSystemClientData('field');
    }

    /**
     * Views accessor Gets client views
     *
     * @return array
     */
    public function getSugarViews()
    {
        return $this->getSystemClientData('view');
    }

    /**
     * Gets client layouts, similar to module specific layouts except used on a
     * global level by the clients consuming this data
     *
     * @return array
     */
    public function getSugarLayouts()
    {
        return $this->getSystemClientData('layout');
    }

    /**
     * Gets client files of type $type (view, layout, field) for a module or for the system
     *
     * @param string $type The type of files to get
     * @param string $module Module name (leave blank to get the system wide files)
     * @return array
     */
    public function getSystemClientData($type)
    {
        // This is a semi-complicated multi-step process, so we're going to try and make this as easy as possible.
        // This should get us a list of the client files for the system
        $fileList = MetaDataFiles::getClientFiles($this->platforms, $type);

        // And this should get us the contents of those files, properly sorted and everything.
        $results = MetaDataFiles::getClientFileContents($fileList, $type);

        return $results;
    }

    public function getModuleClientData($type, $module)
    {
        return MetaDataFiles::getModuleClientCache($this->platforms, $type, $module);
    }

    /**
     * The collector method for the module strings
     *
     * @param string $moduleName The name of the module
     * @param string $language The language for the translations
     * @return array The module strings for the requested language
     */
    public function getModuleStrings( $moduleName, $language = 'en_us' ) {
        // Bug 58174 - Escaped labels are sent to the client escaped
        // TODO: SC-751, fix the way languages merge
        $strings = return_module_language($language,$moduleName);
        if (is_array($strings)) {
            foreach ($strings as $k => $v) {
                $strings[$k] = $this->decodeStrings($v);
            }
        }

        return $strings;
    }

    /**
     * The collector method for the app strings
     * 
     * @param string $lang The language you wish to fetch the app strings for
     * @return array The app strings for the requested language
     */
    public function getAppStrings($lang = 'en_us' ) {
        $strings = return_application_language($lang);
        if (is_array($strings)) {
            foreach ($strings as $k => $v) {
                $strings[$k] = $this->decodeStrings($v);
            }
        }
        return $strings;        
    }

    /**
     * The collector method for the app strings
     *
     * @param string $lang The language you wish to fetch the app list strings for
     * @return array The app list strings for the requested language
     */
    public function getAppListStrings($lang = 'en_us') {
        $strings = return_app_list_strings_language($lang);
        if (is_array($strings)) {
            foreach ($strings as $k => $v) {
                $strings[$k] = $this->decodeStrings($v);
            }
        }
        return $strings;        
    }


    public static function getPlatformList()
    {
        $platforms = array();
        // remove ones with _
        foreach(SugarAutoLoader::getFilesCustom("clients", true) as $dir) {
            $dir = basename($dir);
            if($dir[0] == '_') {
                continue;
            }
            $platforms[$dir] = true;
        }

        return array_keys($platforms);
    }

    /**
     * Cleans field def default values before returning them as a member of the
     * metadata response payload
     *
     * Bug 56505
     * Cleans default value of fields to strip out metacharacters used by the app.
     * Used initially for cleaning default multienum values.
     *
     * @param array $fielddefs
     * @return array
     */
    protected function normalizeFielddefs(Array $fielddefs) {
        $this->getSugarFieldHandler();

        foreach ($fielddefs as $name => $def) {
            if (isset($def['type'])) {
                $type = !empty($def['custom_type']) ? $def['custom_type'] : $def['type'];

                $field = $this->sfh->getSugarField($type);

                $fielddefs[$name] = $field->getNormalizedDefs($def);
            }
        }

        return $fielddefs;
    }

    /**
     * Gets the SugarFieldHandler object
     *
     * @return SugarFieldHandler The SugarFieldHandler
     */
    protected function getSugarFieldHandler() {
        if (!$this->sfh instanceof SugarFieldHandler) {
            $this->sfh = new SugarFieldHandler;
        }

        return $this->sfh;
    }

    /**
     * Recursive decoder that handles decoding of HTML entities in metadata strings
     * before returning them to a client
     *
     * @param mixed $source
     * @return array|string
     */
    protected function decodeStrings($source) {
        if (is_string($source)) {
            return html_entity_decode($source, ENT_QUOTES, 'UTF-8');
        } else {
            if (is_array($source)) {
                foreach ($source as $k => $v) {
                    $source[$k] = $this->decodeStrings($v);
                }
            }

            return $source;
        }
    }

    /**
     * Clears the API metadata cache of all cache files
     *
     * @param bool $deleteModuleClientCache Should we also delete the client file cache of the modules
     * @static
     */
    public static function clearAPICache( $deleteModuleClientCache = true ){
        if ( $deleteModuleClientCache ) {
            // Delete this first so there is no race condition between deleting a metadata cache
            // and the module client cache being stale.
            MetaDataFiles::clearModuleClientCache();
        }

        // Wipe out any files from the metadata cache directory
        $metadataFiles = glob(sugar_cached('api/metadata/').'*');
        if ( is_array($metadataFiles) ) {
            foreach ( $metadataFiles as $metadataFile ) {
                // This removes the file and the reference from the map. This does
                // NOT save the file map since that would be expensive in a loop
                // of many deletes.
                unlink($metadataFile);
            }
        }
        
        // clear the platform cache from sugar_cache to avoid out of date data
        $platforms = self::getPlatformList();
        foreach($platforms as $platform) {
            $platformKey = $platform == "base" ?  "base" : implode(",", array($platform, "base"));
            $hashKey = "metadata:$platformKey:hash";
            sugar_cache_clear($hashKey);
        }
    }
    
    /**
     * Gets server information
     * 
     * @return array of ServerInfo
     */
    public function getServerInfo() {
        global $sugar_flavor;
        global $sugar_version;
        global $timedate;

        $data['flavor'] = $sugar_flavor;
        $data['version'] = $sugar_version;
        
        $fts_enabled = SugarSearchEngineFactory::getFTSEngineNameFromConfig();
        if(!empty($fts_enabled) && $fts_enabled != 'SugarSearchEngine') {
            $data['fts'] = array(
                'enabled' =>  true,
                'type'    =>  $fts_enabled,
            );
        } else {
            $data['fts'] = array(
                'enabled' =>  false,
            );
        }

        //Always return dates in ISO-8601
        $date = new SugarDateTime();
        $data['server_time'] = $timedate->asIso($date, $GLOBALS['current_user']);
        $data['gmt_time'] = gmdate('Y-m-d\TH:i:s') . '+0000';

        return $data;
    }
}
