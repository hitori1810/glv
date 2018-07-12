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

require_once('modules/ACLActions/actiondefs.php');
/**
 * ACL actions
 * @api
 */
class ACLAction  extends SugarBean
{
    var $module_dir = 'ACLActions';
    var $object_name = 'ACLAction';
    var $table_name = 'acl_actions';
    var $new_schema = true;
    var $disable_custom_fields = true;


    /**
     * This is a depreciated method, please start using __construct() as this method will be removed in a future version
     *
     * @see __construct
     * @deprecated
     */
    public function ACLAction()
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
    *
    * @param STRING $category - the category (e.g module name - Accounts, Contacts)
    * @param STRING $type - the type (e.g. 'module', 'field')
    */
    static function addActions($category, $type='module'){
        global $ACLActions;
        $db = DBManagerFactory::getInstance();
        if(isset($ACLActions[$type])){
            foreach($ACLActions[$type]['actions'] as $action_name =>$action_def){

                $action = new ACLAction();
                $query = "SELECT * FROM " . $action->table_name . " WHERE name='$action_name' AND category = '$category' AND acltype='$type' AND deleted=0 ";
                $result = $db->query($query);
                //only add if an action with that name and category don't exist
                $row=$db->fetchByAssoc($result);
                if (empty($row)) {
                    $action->name = $action_name;
                    $action->category = $category;
                    $action->aclaccess = $action_def['default'];
                    $action->acltype = $type;
                    $action->modified_user_id = 1;
                    $action->created_by = 1;
                    $action->save();

                }
            }

        }else{
            sugar_die("FAILED TO ADD: $category - TYPE $type NOT DEFINED IN modules/ACLActions/actiondefs.php");
        }

    }

    /**
    * static removeActions($category, $type='module')
    * Removes all default actions for a category/type
    *
    * @param STRING $category - the category (e.g module name - Accounts, Contacts)
    * @param STRING $type - the type (e.g. 'module', 'field')
    */
    public static function removeActions($category, $type='module'){
        global $ACLActions;
        $db = DBManagerFactory::getInstance();
        if(isset($ACLActions[$type])){
            foreach($ACLActions[$type]['actions'] as $action_name =>$action_def){

                $action = new ACLAction();
                $query = "SELECT * FROM " . $action->table_name . " WHERE name='$action_name' AND category = '$category' AND acltype='$type' and deleted=0";
                $result = $db->query($query);
                //only add if an action with that name and category don't exist
                $row=$db->fetchByAssoc($result);
                if ($row != null) {
                    $action->mark_deleted($row['id']);
                }
            }
        }else{
            sugar_die("FAILED TO REMOVE: - CATEGORY : $category - TYPE $type NOT DEFINED IN modules/ACLActions/actiondefs.php");
        }
    }

    /**
    * static AccessColor($access)
    *
    * returns the color associated with an access level
    * these colors exist in the definitions in modules/ACLActions/actiondefs.php
    * @param INT $access - the access level you want the color for
    * @return the color either name or hex representation or false if the level does not exist
    */
    protected static function AccessColor($access){
        global $ACLActionAccessLevels;
        if(isset($ACLActionAccessLevels[$access])){

            return $ACLActionAccessLevels[$access]['color'];
        }
        return false;

    }

    /**
    * static AccessName($access)
    *
    * returns the translated name  associated with an access level
    * these label definitions  exist in the definitions in modules/ACLActions/actiondefs.php
    * @param INT $access - the access level you want the color for
    * @return the translated access level name or false if the level does not exist
    */
    static function AccessName($access){
        global $ACLActionAccessLevels;
        if(isset($ACLActionAccessLevels[$access])){
            return translate($ACLActionAccessLevels[$access]['label'], 'ACLActions');
        }
        return false;

    }

    /**
     * static AccessLabel($access)
     *
     * returns the label  associated with an access level
     * these label definitions  exist in the definitions in modules/ACLActions/actiondefs.php
     * @param INT $access - the access level you want the color for
     * @return the access level label or false if the level does not exist
     */
    protected static function AccessLabel($access){
        global $ACLActionAccessLevels;
        if(isset($ACLActionAccessLevels[$access])){
            $label=preg_replace('/(LBL_ACCESS_)(.*)/', '$2', $ACLActionAccessLevels[$access]['label']);
            return strtolower($label);

        }
        return false;

    }

    /**
    * static getAccessOptions()
    * this is used for building select boxes
    * @return array containg access levels (ints) as keys and access names as values
    */
    protected static function getAccessOptions( $action, $type='module'){
        global $ACLActions;
        $options = array();

        if(empty($ACLActions[$type]['actions'][$action]['aclaccess']))return $options;
        foreach($ACLActions[$type]['actions'][$action]['aclaccess'] as $action){
            $options[$action] = ACLAction::AccessName($action);
        }
        return $options;

    }

    /**
    * function static getDefaultActions()
    * This function will return a list of acl actions with their default access levels
    *
    *
    */
    public static function getDefaultActions($type='module', $action=''){
        $query = "SELECT * FROM acl_actions WHERE deleted=0 ";
        if(!empty($type)){
            $query .= " AND acltype='$type'";
        }
        if(!empty($action)){
            $query .= "AND name='$action'";
        }
        $query .= " ORDER BY category";

        $db = DBManagerFactory::getInstance();
        $result = $db->query($query);
        $default_actions = array();
        while($row = $db->fetchByAssoc($result) ){
            $acl = new ACLAction();
            $acl->populateFromRow($row);
            $default_actions[] = $acl;
        }
        return $default_actions;
    }


    /**
    * static getUserActions($user_id,$refresh=false, $category='', $action='')
    * returns a list of user actions
    * @param GUID $user_id
    * @param BOOLEAN $refresh
    * @param STRING $category
    * @param STRING $action
    * @return ARRAY of ACLActionsArray
    */

    static function getUserActions($user_id,$refresh=false, $category='',$type='', $action=''){
        //check in the session if we already have it loaded
        if(!$refresh && !empty($_SESSION['ACL'][$user_id])){
            if(empty($category) && empty($action)){
                return $_SESSION['ACL'][$user_id];
            }else{
                if(!empty($category) && isset($_SESSION['ACL'][$user_id][$category])){
                    if(empty($action)){
                        if(empty($type)){
                            return $_SESSION['ACL'][$user_id][$category];
                        }
                        if(isset($_SESSION['ACL'][$user_id][$category][$type])) {
                            return $_SESSION['ACL'][$user_id][$category][$type];
                        }
                    }else if(!empty($type) && isset($_SESSION['ACL'][$user_id][$category][$type][$action])){
                        return $_SESSION['ACL'][$user_id][$category][$type][$action];
                    }
                }
            }
        }
        //if we don't have it loaded then lets check against the db
        $additional_where = '';
        $db = DBManagerFactory::getInstance();
        if(!empty($category)){
            $additional_where .= " AND acl_actions.category = '$category' ";
        }
        if(!empty($action)){
            $additional_where .= " AND acl_actions.name = '$action' ";
        }
        if(!empty($type)){
            $additional_where .= " AND acl_actions.acltype = '$type' ";
        }
        $query = "SELECT acl_actions .*, acl_roles_actions.access_override
                    FROM acl_actions
                    LEFT JOIN acl_roles_users ON acl_roles_users.user_id = '$user_id' AND  acl_roles_users.deleted = 0
                    LEFT JOIN acl_roles_actions ON acl_roles_actions.role_id = acl_roles_users.role_id AND acl_roles_actions.action_id = acl_actions.id AND acl_roles_actions.deleted=0
                    WHERE acl_actions.deleted=0 $additional_where ORDER BY category,name";
        $result = $db->query($query);
        $selected_actions = array();
        while($row = $db->fetchByAssoc($result, FALSE) ){
            $acl = new ACLAction();
            $isOverride  = false;
            $acl->populateFromRow($row);
            if(!empty($row['access_override'])){
                $acl->aclaccess = $row['access_override'];
                $isOverride = true;
            }
            if(!isset($selected_actions[$acl->category])){
                $selected_actions[$acl->category] = array();

            }
            if(!isset($selected_actions[$acl->category][$acl->acltype][$acl->name])
                || ($selected_actions[$acl->category][$acl->acltype][$acl->name]['aclaccess'] > $acl->aclaccess
                    && $isOverride
                    )
                ||
                    (!empty($selected_actions[$acl->category][$acl->acltype][$acl->name]['isDefault'])
                    && $isOverride
                    )
                )
            {


                $selected_actions[$acl->category][$acl->acltype][$acl->name] = $acl->toArray();
                $selected_actions[$acl->category][$acl->acltype][$acl->name]['isDefault'] = !$isOverride;
            }

        }

        //only set the session variable if it was a full list;
        if(empty($category) && empty($action)){
            if(!isset($_SESSION['ACL'])){
                $_SESSION['ACL'] = array();
            }
            $_SESSION['ACL'][$user_id] = $selected_actions;
        }else{
            if(empty($action) && !empty($category)){
                if(!empty($type)){
                    if(isset($selected_actions[$category][$type])) {
                        $_SESSION['ACL'][$user_id][$category][$type] = $selected_actions[$category][$type];
                    } else {
                        $_SESSION['ACL'][$user_id][$category][$type] = array();
                    }
                }
                if(isset($selected_actions[$category])) {
                    $_SESSION['ACL'][$user_id][$category] = $selected_actions[$category];
                } else {
                    $_SESSION['ACL'][$user_id][$category] = array();
                }
            }else{
                if(!empty($action) && !empty($category) && !empty($type)){
                    if(isset($selected_actions[$category][$action])) {
                        $_SESSION['ACL'][$user_id][$category][$type][$action] = $selected_actions[$category][$action];
                    } else {
                        $_SESSION['ACL'][$user_id][$category][$type][$action] = array();
                    }

                }
            }
        }
        
        // Sort by translated categories
        uksort($selected_actions, "ACLAction::langCompare");
        return $selected_actions;
    }
    
    private static function langCompare($a, $b) 
    {
        global $app_list_strings;
        // Fallback to array key if translation is empty
        $a = empty($app_list_strings['moduleList'][$a]) ? $a : $app_list_strings['moduleList'][$a];
        $b = empty($app_list_strings['moduleList'][$b]) ? $b : $app_list_strings['moduleList'][$b];
        if ($a == $b)
            return 0;
        return ($a < $b) ? -1 : 1;
    }
    
    /**
    * (static/ non-static)function hasAccess($is_owner= false , $access = 0)
    * checks if a user has access to this acl if the user is an owner it will check if owners have access
    *
    * This function may either be used statically or not. If used staticlly a user must pass in an access level not equal to zero
    * @param boolean $is_owner
    * @param int $access
    * @return true or false
    */
    static function hasAccess($is_owner=false, $access = 0){

        if($access != 0 && $access == ACL_ALLOW_ALL || ($is_owner && $access == ACL_ALLOW_OWNER))return true;
       //if this exists, then this function is not static, so check the aclaccess parameter
        if(isset($this) && isset($this->aclaccess)){
            if($this->aclaccess == ACL_ALLOW_ALL || ($is_owner && $this->aclaccess == ACL_ALLOW_OWNER))
            return true;
        }
        return false;
    }









    /**
    * static function userHasAccess($user_id, $category, $action, $is_owner = false)
    *
    * @param GUID $user_id the user id who you want to check access for
    * @param STRING $category the category you would like to check access for
    * @param STRING $action the action of that category you would like to check access for
    * @param BOOLEAN OPTIONAL $is_owner if the object is owned by the user you are checking access for
    */
    public static function userHasAccess($user_id, $category, $action,$type='module', $is_owner = false){
       global $current_user;
       if($current_user->isAdminForModule($category)&& !isset($_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess'])){
        return true;
        }
        //check if we don't have it set in the cache if not lets reload the cache
        if(ACLAction::getUserAccessLevel($user_id, $category, 'access', $type) < ACL_ALLOW_ENABLED) return false;
        if(empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
            ACLAction::getUserActions($user_id, false);

        }
        if(!empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
            if($action == 'access' && $_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess'] == ACL_ALLOW_ENABLED) return true;
            return ACLAction::hasAccess($is_owner, $_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess']);
        }
        return false;

    }
    /**
    * function getUserAccessLevel($user_id, $category, $action,$type='module')
    * returns the access level for a given category and action
    *
    * @param GUID  $user_id
    * @param STRING $category
    * @param STRING $action
    * @param STRING $type
    * @return INT (ACCESS LEVEL)
    */
    public static function getUserAccessLevel($user_id, $category, $action,$type='module'){
        if(empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
            ACLAction::getUserActions($user_id, false);

        }
        if(!empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
            if (!empty($_SESSION['ACL'][$user_id][$category][$type]['admin']) && $_SESSION['ACL'][$user_id][$category][$type]['admin']['aclaccess'] >= ACL_ALLOW_ADMIN)
            {
                // If you have admin access for a module, all ACL's are allowed
                return $_SESSION['ACL'][$user_id][$category][$type]['admin']['aclaccess'];
            }
            return  $_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess'];
        }
    }

    /**
    * STATIC function userNeedsOwnership($user_id, $category, $action,$type='module')
    * checks if a user should have ownership to do an action
    *
    * @param GUID $user_id
    * @param STRING $category
    * @param STRING $action
    * @param STRING $type
    * @return boolean
    */
    public static function userNeedsOwnership($user_id, $category, $action,$type='module'){
        //check if we don't have it set in the cache if not lets reload the cache

        if(empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
            ACLAction::getUserActions($user_id, false);

        }


        if(!empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
            return $_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess'] == ACL_ALLOW_OWNER;
        }
        return false;

    }
    /**
    *
    * static pass by ref setupCategoriesMatrix(&$categories)
    * takes in an array of categories and modifes them adding display information
    *
    * @param unknown_type $categories
    */
    public static function setupCategoriesMatrix(&$categories){
        global $ACLActions, $current_user;
        $names = array();
        $disabled = array();
        foreach($categories as $cat_name=>$category){
            foreach($category as $type_name=>$type){
                foreach($type as $act_name=>$action){
                    $names[$act_name] = isset($ACLActions[$type_name]['actions'][$act_name])
                        ? translate($ACLActions[$type_name]['actions'][$act_name]['label'], 'ACLActions')
                        : $act_name;
                    $categories[$cat_name][$type_name][$act_name]['accessColor'] = ACLAction::AccessColor($action['aclaccess']);
                    if($type_name== 'module'){

                        if($act_name != 'aclaccess' && $categories[$cat_name]['module']['access']['aclaccess'] == ACL_ALLOW_DISABLED){
                            $categories[$cat_name][$type_name][$act_name]['accessColor'] = 'darkgray';
                            $disabled[] = $cat_name;
                        }

                    }
                    $categories[$cat_name][$type_name][$act_name]['accessName'] = ACLAction::AccessName($action['aclaccess']);
                    $categories[$cat_name][$type_name][$act_name]['accessLabel'] = ACLAction::AccessLabel($action['aclaccess']);

                    if($cat_name=='Users'&& $act_name=='admin'){
                        $categories[$cat_name][$type_name][$act_name]['accessOptions'][ACL_ALLOW_DEFAULT]=ACLAction::AccessName(ACL_ALLOW_DEFAULT);;
                        $categories[$cat_name][$type_name][$act_name]['accessOptions'][ACL_ALLOW_DEV]=ACLAction::AccessName(ACL_ALLOW_DEV);;
                    }
                    else{
                    $categories[$cat_name][$type_name][$act_name]['accessOptions'] =  ACLAction::getAccessOptions($act_name, $type_name);
                    }
                }
            }
        }

        if(!is_admin($current_user)){
            foreach($disabled as $cat_name){
                unset($categories[$cat_name]);
            }
        }
        return $names;
    }



    /**
    * function toArray()
    * returns this acl as an array
    *
    * @return array of fields with id, name, access and category
    */
    function toArray(){
        $array_fields = array('id', 'aclaccess');
        $arr = array();
        foreach($array_fields as $field){
            $arr[$field] = $this->$field;
        }
        return $arr;
    }

    /**
    * function fromArray($arr)
    * converts an array into an acl mapping name value pairs into files
    *
    * @param Array $arr
    */
    function fromArray($arr){
        foreach($arr as $name=>$value){
            $this->$name = $value;
        }
    }

    /**
    * function clearSessionCache()
    * clears the session variable storing the cache information for acls
    *
    */
    function clearSessionCache(){
        unset($_SESSION['ACL']);
    }













}
