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


class Relationship extends SugarBean {

	var $object_name='Relationship';
	var $module_dir = 'Relationships';
	var $new_schema = true;
	var $table_name = 'relationships';

	var $id;
	var $relationship_name;
	var $lhs_module;
	var $lhs_table;
	var $lhs_key;
	var $rhs_module;
	var $rhs_table;
	var $rhs_key;
	var $join_table;
	var $join_key_lhs;
	var $join_key_rhs;
	var $relationship_type;
	var $relationship_role_column;
	var $relationship_role_column_value;
	var $reverse;

	var $_self_referencing;

    /**
     * This is a depreciated method, please start using __construct() as this method will be removed in a future version
     *
     * @see __construct
     * @deprecated
     */
    public function Relationship()
    {
        self::__construct();
    }

	public function __construct() {
		parent::__construct();
	}

	/*returns true if the relationship is self referencing. equality check is performed for both table and
	 * key names.
	 */
	function is_self_referencing() {
		if (empty($this->_self_referencing)) {
			$this->_self_referencing=false;

			//is it self referencing, both table and key name from lhs and rhs should  be equal.
			if ($this->lhs_table == $this->rhs_table && $this->lhs_key == $this->rhs_key) {
				$this->_self_referencing=true;
			}
		}
		return $this->_self_referencing;
	}

	/*returns true if a relationship with provided name exists*/
	function exists($relationship_name,&$db) {
		$query = "SELECT relationship_name FROM relationships WHERE deleted=0 AND relationship_name = '".$relationship_name."'";
		$result = $db->query($query,true," Error searching relationships table..");
		$row  =  $db->fetchByAssoc($result);
		if ($row != null) {
			return true;
		}

		return false;
	}

	function delete($relationship_name,&$db) {

		$query = "UPDATE relationships SET deleted=1 WHERE deleted=0 AND relationship_name = '".$relationship_name."'";
		$result = $db->query($query,true," Error updating relationships table for ".$relationship_name);

	}


	function get_other_module($relationship_name, $base_module, &$db){
	//give it the relationship_name and base module
	//it will return the module name on the other side of the relationship

		$query = "SELECT relationship_name, rhs_module, lhs_module FROM relationships WHERE deleted=0 AND relationship_name = '".$relationship_name."'";
		$result = $db->query($query,true," Error searching relationships table..");
		$row  =  $db->fetchByAssoc($result);
		if ($row != null) {

			if($row['rhs_module']==$base_module){
				return $row['lhs_module'];
			}
			if($row['lhs_module']==$base_module){
				return $row['rhs_module'];
			}
		}

		return false;


	//end function get_other_module
	}

	function retrieve_by_sides($lhs_module, $rhs_module, &$db){
	//give it the relationship_name and base module
	//it will return the module name on the other side of the relationship

		$query = "SELECT * FROM relationships WHERE deleted=0 AND lhs_module = '".$lhs_module."' AND rhs_module = '".$rhs_module."'";
		$result = $db->query($query,true," Error searching relationships table..");
		$row  =  $db->fetchByAssoc($result);
		if ($row != null) {

			return $row;

		}

		return null;


	//end function retrieve_by_sides
	}

	function retrieve_by_modules($lhs_module, $rhs_module, &$db, $type =''){
	//give it the relationship_name and base module
	//it will return the module name on the other side of the relationship

		$query = "	SELECT * FROM relationships
					WHERE deleted=0
					AND (
					(lhs_module = '".$lhs_module."' AND rhs_module = '".$rhs_module."')
					OR
					(lhs_module = '".$rhs_module."' AND rhs_module = '".$lhs_module."')
					)
					";
		if(!empty($type)){
			$query .= " AND relationship_type='$type'";
		}
		$result = $db->query($query,true," Error searching relationships table..");
		$row  =  $db->fetchByAssoc($result);
		if ($row != null) {

			return $row['relationship_name'];

		}

		return null;


	//end function retrieve_by_sides
	}


	function retrieve_by_name($relationship_name) {

		if (empty($GLOBALS['relationships'])) {
			$this->load_relationship_meta();
		}

//		_ppd($GLOBALS['relationships']);

		if (array_key_exists($relationship_name, $GLOBALS['relationships'])) {

			foreach($GLOBALS['relationships'][$relationship_name] as $field=>$value)
			{
					$this->$field = $value;
			}
		}
		else {
			$GLOBALS['log']->fatal('Error fetching relationship from cache '.$relationship_name);
			return false;
		}
	}

	function load_relationship_meta() {
		if (!file_exists(Relationship::cache_file_dir().'/'.Relationship::cache_file_name_only())) {
			$this->build_relationship_cache();
		}
		include(Relationship::cache_file_dir().'/'.Relationship::cache_file_name_only());
		$GLOBALS['relationships']=$relationships;
	}

	function build_relationship_cache() {
		$query="SELECT * from relationships where deleted=0";
		$result=$this->db->query($query);

		while (($row=$this->db->fetchByAssoc($result))!=null) {
			$relationships[$row['relationship_name']] = $row;
		}
		
		sugar_mkdir($this->cache_file_dir(), null, true);
        $out = "<?php \n \$relationships = " . var_export($relationships, true) . ";";
        sugar_file_put_contents_atomic(Relationship::cache_file_dir() . '/' . Relationship::cache_file_name_only(), $out);
		
        require_once("data/Relationships/RelationshipFactory.php");
        SugarRelationshipFactory::deleteCache();
	}


	public static function cache_file_dir() {
		return sugar_cached("modules/Relationships");
	}
	public static function cache_file_name_only() {
		return 'relationships.cache.php';
	}

	public static function delete_cache() {
		$filename=Relationship::cache_file_dir().'/'.Relationship::cache_file_name_only();
		if (file_exists($filename)) {
			unlink($filename);
		}
        require_once("data/Relationships/RelationshipFactory.php");
        SugarRelationshipFactory::deleteCache();
	}


	function trace_relationship_module($base_module, $rel_module1_name, $rel_module2_name=""){
		global $beanList;
		global $dictionary;

		$temp_module = get_module_info($base_module);

		$rel_attribute1_name = $temp_module->field_defs[strtolower($rel_module1_name)]['relationship'];
		$rel_module1 = $this->get_other_module($rel_attribute1_name, $base_module, $temp_module->db);
		$rel_module1_bean = get_module_info($rel_module1);

		if($rel_module2_name!=""){
			if($rel_module2_name == 'ProjectTask'){
				$rel_module2_name = strtolower($rel_module2_name);
			}
			$rel_attribute2_name = $rel_module1_bean->field_defs[strtolower($rel_module2_name)]['relationship'];
			$rel_module2 = $this->get_other_module($rel_attribute2_name, $rel_module1_bean->module_dir, $rel_module1_bean->db);
			$rel_module2_bean = get_module_info($rel_module2);
			return $rel_module2_bean;

		} else {
			//no rel_module2, so return rel_module2 bean
			return $rel_module1_bean;
		}

	//end function trace_relationship_module
	}




}
?>