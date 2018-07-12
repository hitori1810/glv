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


require_once('modules/ACLFields/actiondefs.php');
/**
 * Field-level ACLs
 * @api
 */
class ACLField  extends ACLAction
{
    var $module_dir = 'ACLFields';
    var $object_name = 'ACLField';
    var $table_name = 'acl_fields';
    var $disable_custom_fields = true;
    var $new_schema = true;

    /**
     * This is a depreciated method, please start using __construct() as this method will be removed in a future version
     *
     * @see __construct
     * @deprecated
     */
    public function ACLField()
    {
        self::__construct();
    }

    public function __construct(){
        parent::__construct();
        $this->disable_row_level_security =true;
    }

    /**
    * static addActions($category, $type='module')
    * Adds all default actions for a category/type
    * @internal
    * @param STRING $category - the category (e.g module name - Accounts, Contacts)
    * @param STRING $type - the type (e.g. 'module', 'field')
    */
    static function getAvailableFields($module, $object=false){

        static $exclude = array('deleted', 'assigned_user_id');
        static $modulesAvailableFields = array();
        if(!isset($modulesAvailableFields[$module])){
            if(empty($GLOBALS['dictionary'][$object]['fields'])){
                $class = $GLOBALS['beanList'][$module];
                require_once($GLOBALS['beanFiles'][$class]);
                $mod = new $class();
                if(empty($mod->acl_fields))return array();
                $fieldDefs = $mod->field_defs;
            }else{
                $fieldDefs = $GLOBALS['dictionary'][$object]['fields'];
                if(isset($GLOBALS['dictionary'][$object]['acl_fields']) && $GLOBALS['dictionary'][$object]=== false){
                    return array();
                }
            }

            $availableFields = array();
            foreach($fieldDefs as $field=>$def){

                if((!empty($def['source'])&& $def['source']== 'custom_fields') ||!empty($def['group']) || (empty($def['hideacl']) &&!empty($def['type']) && !in_array($field, $exclude) &&
                    ((empty($def['source'])
                    && $def['type'] != 'id'
                    && (empty($def['dbType']) || ($def['dbType'] != 'id' ))
                    ) || !empty($def['link']) || $def['type'] == 'relate')
                ))
                    {
                        if(empty($def['vname']))$def['vname'] = '';
                        $fkey = (!empty($def['group']))? $def['group']: $field;
                        $label = (!empty($fieldDefs[$fkey]['vname']))?$fieldDefs[$fkey]['vname']:$def['vname'];
                        $fkey = strtolower($fkey);
                        $field = strtolower($field);
                        $required = !empty($def['required']);
                        if($field == 'name'){
                            $required = true;
                        }
                        if(empty($availableFields[$fkey])){
                            $availableFields[$fkey] = array('id'=>$fkey, 'required'=>$required, 'key'=>$fkey, 'name'=> $field, 'label'=>$label, 'category'=>$module, 'role_id'=> '', 'aclaccess'=>ACL_ALLOW_DEFAULT, 'fields'=>array($field=>$label) );
                        }else{
                            if(!empty($required)){
                                $availableFields[$fkey]['required'] = 1;
                            }
                            $availableFields[$fkey]['fields'][strtolower($field)] = $label;
                        }
                }
            }
            $modulesAvailableFields[$module] = $availableFields;
        }
        return $modulesAvailableFields[$module];
    }

    /**
     * Get ACL fields
     * @internal
     * @param string $module
     * @param string $user_id
     * @param string $role_id
     */
    function getFields($module,$user_id='',$role_id=''){
        static $userFields = array();
        $fields = ACLField::getAvailableFields($module, false);
        if(!empty($role_id)){
            $query = "SELECT  af.id, af.name, af.category, af.role_id, af.aclaccess FROM acl_fields af ";
            if(!empty($user_id)){
                $query .= " INNER JOIN acl_roles_users aru ON aru.user_id = '$user_id' AND aru.deleted=0
                            INNER JOIN acl_roles ar ON aru.role_id = ar.id AND ar.id = af.role_id AND ar.deleted = 0";
            }

            $query .=  " WHERE af.deleted = 0 ";
            $query .= " AND af.role_id='$role_id' ";
            $query .= " AND af.category='$module'";
            $result = $GLOBALS['db']->query($query);
            while($row = $GLOBALS['db']->fetchByAssoc($result)){
                if(!empty($fields[$row['name']]) && ($row['aclaccess'] < $fields[$row['name']]['aclaccess'] || $fields[$row['name']]['aclaccess'] == 0) ){
                    $row['key'] = $row['name'];
                    $row['label'] = $fields[$row['name']]['label'];
                    $row['fields'] = $fields[$row['name']]['fields'];
                    if(isset($fields[$row['name']]['required'])) {
                    $row['required'] = $fields[$row['name']]['required'];
                    }
                    $fields[$row['name']] =  $row;
                }
            }
        }

        ksort($fields);
        return $fields;
    }

    /**
     * @internal
     * @param string $role_id
     */
    function getACLFieldsByRole($role_id){
        $query = "SELECT  af.id, af.name, af.category, af.role_id, af.aclaccess FROM acl_fields af ";
        $query .=  " WHERE af.deleted = 0 ";
        $query .= " AND af.role_id='$role_id' ";
        $result = $GLOBALS['db']->query($query);
        while($row = $GLOBALS['db']->fetchByAssoc($result)){
            $fields[$row['id']] =  $row;
        }
        return $fields;
    }

    /**
     * Load user ACLs from the DB
     * @internal
     * @param string $category
     * @param string $object
     * @param string $user_id
     * @param bool $refresh
     */
    static function loadUserFields($category,$object, $user_id, $refresh=false){
        if(!$refresh && isset($_SESSION['ACL'][$user_id][$category]['fields']))
        {
            return $_SESSION['ACL'][$user_id][$category]['fields'];
        }

        if(empty($_SESSION['ACL'][$user_id])) {
            // load actions to prevent cache poisoning for ACLAction
            ACLAction::getUserActions($user_id);
        }
        $query = "SELECT  af.name, af.aclaccess FROM acl_fields af ";
        $query .= " INNER JOIN acl_roles_users aru ON aru.user_id = '$user_id' AND aru.deleted=0
                    INNER JOIN acl_roles ar ON aru.role_id = ar.id AND ar.id = af.role_id AND ar.deleted = 0";
        $query .=  " WHERE af.deleted = 0 ";
        $query .= " AND af.category='$category'";

        $result = $GLOBALS['db']->query($query);

        $allFields = ACLField::getAvailableFields($category, $object);
        $_SESSION['ACL'][$user_id][$category]['fields'] = array();
        while($row = $GLOBALS['db']->fetchByAssoc($result)){
            if($row['aclaccess'] != 0 && (empty($_SESSION['ACL'][$user_id][$category]['fields'][$row['name']]) || $_SESSION['ACL'][$user_id][$category]['fields'][$row['name']] > $row['aclaccess']))
            {
                $_SESSION['ACL'][$user_id][$category]['fields'][$row['name']] = $row['aclaccess'];
                if(!empty($allFields[$row['name']])){
                foreach($allFields[$row['name']]['fields'] as $field=>$label ){
                        $_SESSION['ACL'][$user_id][$category]['fields'][strtolower($field)] = $row['aclaccess'];
                    }
                }
            }
        }

        return $_SESSION['ACL'][$user_id][$category]['fields'];

    }

    public static $field_cache = array();

    /**
     * Filter fields list by ACLs
     * NOTE: works with global ACLs
	 * @internal
     * @param array $list Field list. Will be modified.
     * @param string $category Module for ACL
     * @param string $user_id
     * @param bool $is_owner Should owner-only ACLs be counted?
     * @param bool $by_key use list keys
     * @param int $min_access Minimal access level to require
     * @param bool $blank_value Put blank string in place of removed fields?
     * @param bool $addACLParam Add 'acl' key with acl access value?
     * @param string $suffix Field suffix to strip from the list.
     */
    function listFilter(&$list,$category, $user_id, $is_owner, $by_key=true, $min_access = 1, $blank_value=false, $addACLParam=false, $suffix='')
    {
        foreach($list as $key=>$value){
            if($by_key){
                $field = $key;
                if(is_array($value) && !empty($value['group'])){

                    $field = $value['group'];
                }
            }else{
                if(is_array($value)){
                    if(!empty($value['group'])){
                        $value = $value['group'];
                    }else if(!empty($value['name'])){
                        $value = $value['name'];
                    }else{
                        $value = '';
                    }
                }
                $field = $value;
            }
            if(isset(self::$field_cache['lower'][$field])){
                $field = self::$field_cache['lower'][$field];
            } else {
                $oField = $field;
                $field = strtolower($field);
                if(!empty($suffix))$field = str_replace($suffix, '', $field);
                self::$field_cache['lower'][$oField] = $field;
            }
            if(!isset(self::$field_cache[$is_owner][$field])){
                $context = array("user_id" => $user_id);
                if($is_owner) {
                    $context['owner_override'] = true;
                }
                $access = SugarACL::getFieldAccess($category, $field, $context);
                self::$field_cache[$is_owner][$field] = $access;
            }else{
                $access = self::$field_cache[$is_owner][$field];
            }
            if($addACLParam){
                $list[$key]['acl'] = $access;
            }else if($access< $min_access){
                if($blank_value){
                    $list[$key] = '';
                }else{
                    unset($list[$key]);
                }
            }
        }
    }


   /**
    * hasAccess
    *
    * This function returns an integer value representing the access level for a given field of a module for
    * a user.  It also takes into account whether or not the user needs to have ownership of the record (assigned to the user)
    *
    * Returns 0 - for no access
    * Returns 1 - for read access
    * returns 2 - for write access
    * returns 4 - for read/write access
    * @internal
    * @param String $field The name of the field to retrieve ACL access for
    * @param String $module The name of the module that contains the field to lookup ACL access for
    * @param String $user_id The user id of the user instance to check ACL access for
    * @param boolean $is_owner Boolean value indicating whether or not the field access should also take into account ownership access
    * @return Integer value indicating the ACL field level access
    */
    static function hasAccess($field, $module,$user_id, $is_owner){
        static $is_admin = null;
        if (is_null($is_admin)) {
            $is_admin = is_admin($GLOBALS['current_user']);
        }
        if ($is_admin) {
            return 4;
        }

        if(!isset($_SESSION['ACL'][$user_id][$module]['fields'][$field])){
            return 4;
        }
        $access = $_SESSION['ACL'][$user_id][$module]['fields'][$field];

        if($access == ACL_READ_WRITE || ($is_owner && ($access == ACL_READ_OWNER_WRITE || $access == ACL_OWNER_READ_WRITE))){
            return 4;
        }elseif($access == ACL_READ_ONLY || $access==ACL_READ_OWNER_WRITE){
            return 1;
        }
        return 0;
    }

    /**
     * @internal
     * @param string $module
     * @param string $role_id
     * @param string $field_id
     * @param string $access
     */
    function setAccessControl($module, $role_id, $field_id, $access)
    {
        $acl = new ACLField();
        $id = md5($module. $role_id . $field_id);
        if(!$acl->retrieve($id) ){
            //if we don't have a value and its never been saved no need to start now
            if(empty($access))return false;
            $acl->id = $id;
            $acl->new_with_id = true;
        }

        $acl->aclaccess = $access;
        $acl->category = $module;
        $acl->name = $field_id;
        $acl->role_id = $role_id;
        $acl->save();

    }
}