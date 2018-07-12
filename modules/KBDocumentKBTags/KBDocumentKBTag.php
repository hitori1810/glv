<?php
if(!defined('sugarEntry') || !sugarEntry)
	die('Not A Valid Entry Point');
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


require_once ('include/upload_file.php');


// User is used to store Forecast information.
class KBDocumentKBTag extends SugarBean {

	var $id;
	var $kbdocument_id;
	var $kbtag_id;
	var $created_by;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $kbdocument_name;
	var $team_id;


	var $table_name = "kbdocuments_kbtags";
	var $object_name = "KBDocumentKBTag";
	var $user_preferences;

	var $encodeFields = Array ();

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array ('revision');

	

	var $new_schema = true;
	var $module_dir = 'KBDocumentKBTags';
	
//todo remove leads relationship.
	var $relationship_fields = Array('contract_id'=>'contracts',
	 
		'lead_id' => 'leads'
	 );
	  

    /**
     * This is a depreciated method, please start using __construct() as this method will be removed in a future version
     *
     * @see __construct
     * @deprecated
     */
    public function KBDocumentKBTag()
    {
        self::__construct();
    }

	public function __construct() {
		parent::__construct();
		$this->setupCustomFields('KBDocumentKBTags'); //parameter is module name
		$this->disable_row_level_security = false;
	}

	function save($check_notify = false) {
		return parent::save($check_notify);
	}
	
	function fill_in_additional_detail_fields()
	{
	    $kbdoc = new KBDocument;
	    $kbdoc = $kbdoc->retrieve($this->kbdocument_id);
	    if ( !empty($kbdoc->id) ) {
	        $this->kbdocument_name = $kbdoc->kbdocument_name;
	    }
	}
	function get_summary_text() {
		return "$this->kbdocument_name";
	}

	function is_authenticated() {
		return $this->authenticated;
	}

	function fill_in_additional_list_fields() {
		$this->fill_in_additional_detail_fields();
	}

	function mark_relationships_deleted($id) {
		//do nothing, this call is here to avoid default delete processing since  
		//delete.php handles deletion of document revisions.
	}

	
}
?>