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

require_once('include/SugarObjects/templates/basic/Basic.php');

class Company extends Basic
{

    /**
     * This is a depreciated method, please start using __construct() as this method will be removed in a future version
     *
     * @see __construct
     * @deprecated
     */
    public function Company()
    {
        self::__construct();
    }

 	/**
 	 * Constructor
 	 */
    public function __construct()
 	{
 		parent::__construct();
 		$this->emailAddress = new SugarEmailAddress();
 	}
 	
 	/**
 	 * @see parent::save()
 	 */
	public function save($check_notify=false) 
 	{
 	    if(!empty($GLOBALS['resavingRelatedBeans']))
 	    {
 	        return parent::save($check_notify);
 	    } 	    
		$this->add_address_streets('billing_address_street');
		$this->add_address_streets('shipping_address_street');
    	$ori_in_workflow = empty($this->in_workflow) ? false : true;
		$this->emailAddress->handleLegacySave($this, $this->module_dir);
    	$record_id = parent::save($check_notify);
        $override_email = array();
        if(!empty($this->email1_set_in_workflow)) {
            $override_email['emailAddress0'] = $this->email1_set_in_workflow;
        }
        if(!empty($this->email2_set_in_workflow)) {
            $override_email['emailAddress1'] = $this->email2_set_in_workflow;
        }
        if(!isset($this->in_workflow)) {
            $this->in_workflow = false;
        }
        if($ori_in_workflow === false || !empty($override_email)){
            $this->emailAddress->save($this->id, $this->module_dir, $override_email,'','','','',$this->in_workflow);
        }
		return $record_id;
	}
	
 	/**
 	 * Populate email address fields here instead of retrieve() so that they are properly available for logic hooks
 	 *
 	 * @see parent::fill_in_relationship_fields()
 	 */
	public function fill_in_relationship_fields()
	{
	    parent::fill_in_relationship_fields();
	    $this->emailAddress->handleLegacyRetrieve($this);
	}
	
	/**
 	 * @see parent::get_list_view_data()
 	 */
	public function get_list_view_data() 
	{	
		global $system_config;
		global $current_user;

		$temp_array = $this->get_list_view_array();

		$temp_array['EMAIL1'] = $this->emailAddress->getPrimaryAddress($this);

        // Fill in the email1 field only if the user has access to it
        // This is a special case, because getEmailLink() uses email1 field for making the link
        // Otherwise get_list_view_data() shouldn't set any fields except fill the template data
        if (ACLField::hasAccess('email1', $this->module_dir, $GLOBALS['current_user']->id, $this->isOwner($GLOBALS['current_user']->id)))
        {
            $this->email1 = $temp_array['EMAIL1'];
        }

		$temp_array['EMAIL1_LINK'] = $current_user->getEmailLink('email1', $this, '', '', 'ListView');

		return $temp_array;
	}

    /**
     * Default export query for Company based modules
     * used to pick all mails (primary and non-primary)
     *
     * @see SugarBean::create_export_query()
     */
    function create_export_query(&$order_by, &$where, $relate_link_join = '')
    {
        $custom_join = $this->custom_fields->getJOIN(true, true, $where);

        // For easier code reading, reused plenty of time
        $table = $this->table_name;

        if($custom_join)
        {
            $custom_join['join'] .= $relate_link_join;
        }
        $query = "SELECT
					$table.*,
					email_addresses.email_address email_address,
					'' email_addresses_non_primary, " . // email_addresses_non_primary needed for get_field_order_mapping()
					"users.user_name as assigned_user_name ";
        $query .= ", teams.name AS team_name ";
        if($custom_join)
        {
            $query .= $custom_join['select'];
        }

        $query .= " FROM $table ";

        // We need to confirm that the user is a member of the team of the item.
        $this->add_team_security_where_clause($query);

        $query .= "LEFT JOIN users
					ON $table.assigned_user_id=users.id ";

        $query .= getTeamSetNameJoin($table);

        //Join email address table too.
        $query .=  " LEFT JOIN email_addr_bean_rel on $table.id = email_addr_bean_rel.bean_id and email_addr_bean_rel.bean_module = '" . $this->module_dir . "' and email_addr_bean_rel.deleted = 0 and email_addr_bean_rel.primary_address = 1";
        $query .=  " LEFT JOIN email_addresses on email_addresses.id = email_addr_bean_rel.email_address_id ";

        if($custom_join)
        {
            $query .= $custom_join['join'];
        }

        $where_auto = " $table.deleted=0 ";

        if($where != "")
        {
            $query .= "WHERE ($where) AND " . $where_auto;
        }
        else
        {
            $query .= "WHERE " . $where_auto;
        }

        $order_by = $this->process_order_by($order_by);
        if (!empty($order_by)) {
            $query .= ' ORDER BY ' . $order_by;
        }

        return $query;
    }

}
