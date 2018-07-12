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













/**
 *
 */
class ProjectResource extends SugarBean {
	// database table columns
	var $id;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;
	var $team_id;
	var $deleted;

	// related information
	var $modified_by_name;
	var $created_by_name;
	var $team_name;

	var $project_id;
	var $resource_id;
	var $resource_type;
	
	var $object_name = 'ProjectResource';
	var $module_dir = 'ProjectResources';
	var $new_schema = true;
	var $table_name = 'project_resources';

	//////////////////////////////////////////////////////////////////
	// METHODS
	//////////////////////////////////////////////////////////////////


    /**
     * This is a depreciated method, please start using __construct() as this method will be removed in a future version
     *
     * @see __construct
     * @deprecated
     */
    public function ProjectResource()
    {
        self::__construct();
    }


	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}    
	
}
?>
