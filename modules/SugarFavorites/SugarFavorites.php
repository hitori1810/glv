<?php
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



class SugarFavorites extends Basic 
{	
	public $new_schema = true;
	public $module_dir = 'SugarFavorites';
	public $object_name = 'SugarFavorites';
	public $table_name = 'sugarfavorites';
	public $importable = false;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $module;
    public $record_id;
    public $tag;
    public $record_name;
    public $disable_row_level_security = true;
	
	public static function generateStar(
	    $on, 
	    $module, 
	    $record
	    )
	{
	global $app_strings;
		if ($on)
			return '<div class="star"><div class="on"   title="'.$app_strings['LBL_REMOVE_FROM_FAVORITES'].'" onclick="DCMenu.removeFromFavorites(this, \''.$module. '\',  \''.$record. '\');">&nbsp;</div></div>';
		else
			return '<div class="star"><div class="off"  title="'.$app_strings['LBL_ADD_TO_FAVORITES'].'" onclick="DCMenu.addToFavorites(this, \''.$module. '\',  \''.$record. '\');">&nbsp;</div></div>';
	}
	
	public static function generateGUID(
	    $module, 
	    $record,
	    $user_id = ''
	    )
	{
	    if(empty($user_id))
	        $user_id = $GLOBALS['current_user']->id;
	    
		return md5($module . $record . $user_id);
	}
	
	public static function isUserFavorite(
	    $module, 
	    $record,
	    $user_id = ''
	    )
	{
		$id = SugarFavorites::generateGUID($module, $record, $user_id);

		$focus = new SugarFavorites;
		$focus->retrieve($id);
		
		return !empty($focus->id);
	}

	public static function getUserFavoritesByModule($module = '', User $user = null, $orderBy = "", $limit = -1)
	{
	    if ( empty($user) )
	        $where = " sugarfavorites.assigned_user_id = '{$GLOBALS['current_user']->id}' ";
	    else
	        $where = " sugarfavorites.assigned_user_id = '{$user->id}' ";
	    
        if ( !empty($module) ) {
            if ( is_array($module) ) {
                $where .= " AND sugarfavorites.module IN ('" . implode("','",$module) . "')";
            }
            else {
            	$where .= " AND sugarfavorites.module = '$module' ";
            }
        }
        $focus = new SugarFavorites;
		$response = $focus->get_list($orderBy,$where,0,$limit);
	    
	    return $response['list'];
	}

	public static function getFavoritesByModuleByRecord($module, $id)
	{
		$where = '';
		$orderBy = '';
		$limit = -1;
        if ( !empty($module) ) {
            if ( is_array($module) ) {
                $where .= " sugarfavorites.module IN ('" . implode("','",$module) . "')";
            }
            else {
                $where .= " sugarfavorites.module = '$module' ";
            }
        }
        
        $where .= " AND sugarfavorites.record_id = '{$id}'";

        $focus = new SugarFavorites;
		$response = $focus->get_list($orderBy,$where,0,$limit);

	    return $response['list'];		
	}

	/**
	 * Use a direct DB Query to retreive only the assigned user id's for a module/record. 
	 * @param string $module - module name
	 * @param string $id - guid
	 * @return array $assigned_user_ids - array of assigned user ids
	 */
	public static function getUserIdsForFavoriteRecordByModuleRecord($module, $id) {
		global $db;
		$query = "SELECT assigned_user_id FROM sugarfavorites WHERE module = '$module' AND record_id = '$id' AND deleted = 0";
		$queryResult = $db->query($query);
		$assigned_user_ids = array();
		while($row = $db->fetchByAssoc($queryResult)) {
			$assigned_user_ids[] = $row['assigned_user_id'];
		}
		return $assigned_user_ids;
	}

	public function markRecordDeletedInFavoritesByUser($record_id, $module, $assigned_user_id)
	{
		$query = "UPDATE {$this->table_name} set deleted=1 , module = '{$module}', date_modified = '$date_modified', modified_user_id = NOW() where record_id='{$record_id}' AND assigned_user_id = '{$assigned_user_id}'";
        $this->db->query($query, true, "Error marking favorites deleted: ");
	}

	/**
	 * An easy way to toggle a favorite on and off.
	 * @param string $id 
	 * @param int $deleted 
	 * @return bool
	 */
	public function toggleExistingFavorite($id, $deleted)
	{
		global $current_user;
		$deleted = (int) $deleted;
		if($deleted != 0 && $deleted != 1) {
			return false;
		}

		$query = "UPDATE {$this->table_name} SET deleted = {$deleted}, created_by = '{$current_user->id}', modified_user_id = '{$current_user->id}', assigned_user_id = '{$current_user->id}' WHERE id = '{$id}'";
		$this->db->query($query, true, "Error marking favorites deleted to {$deleted}: ");
		return true;
	}

    public static function markRecordDeletedInFavorites($record_id, $date_modified, $modified_user_id = "")
    {
        $focus = new SugarFavorites();
        $focus->mark_records_deleted_in_favorites($record_id, $date_modified, $modified_user_id);
    }

    public function mark_records_deleted_in_favorites($record_id, $date_modified, $modified_user_id = "")
    {
        if (isset($modified_user))
            $query = "UPDATE $this->table_name set deleted=1 , date_modified = '$date_modified', modified_user_id = '$modified_user_id' where record_id='$record_id'";
        else
            $query = "UPDATE $this->table_name set deleted=1 , date_modified = '$date_modified' where record_id='$record_id'";

        $this->db->query($query, true, "Error marking favorites deleted: ");
    }

	public function fill_in_additional_list_fields()
	{
	    parent::fill_in_additional_list_fields();
	    
	    $focus = loadBean($this->module);
	    if ( $focus instanceOf SugarBean ) {
	        $focus->retrieve($this->record_id);
	        if ( !empty($focus->id) )
	            $this->record_name = $focus->name;
	    }
	}
}