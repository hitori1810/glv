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


require_once('include/ListView/ReportListView.php');
require_once('modules/DataSets/DataSet_Layout.php');

// DataSet is used to store data formatting for CSQL queries.
class DataSet extends SugarBean {
	var $field_name_map;
	// Stored fields
	var $id;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;

	var $team_id;

	var $name;
	var $description;
	var $query_id;
	var $list_order_y = 0;
	var $output_default;

	//UI settings
	var $table_width = 100;
	var $font_size = 0;
	var $header;
	var $exportable;
	var $prespace_y;
	var $table_width_type;
	var $body_text_color;
	var $header_text_color;
	var $use_prev_header;
	var $header_back_color;
	var $body_back_color;

	//Layout variable
	var $custom_layout;



	//formatting variables - might move this
	var $symbol;


	//sub query information
	var $interlock = false;
	var $sub_id = null;
	var $sub_query = false;


	//for the name of the parent if an interlocked data set
	var $parent_name;
	var $parent_id;

	//for the name of the child if an interlocked data set
	var $child_name;
	var $child_id;

	//for related fields
	var $query_name;
	var $report_name;
	var $report_id;

	var $table_name = "data_sets";
	var $module_dir = 'DataSets';
	var $object_name = "DataSet";
	var $rel_custom_queries = "custom_queries";
	var $rel_dataset_layout = "dataset_layouts";
	var $report_table = 'report_maker';

	var $new_schema = true;

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('query_name', 'report_name');

//Controller Array for list_order element
	var $controller_def = Array(
		 "list_x" => "N"
		,"list_y" => "Y"
		,"parent_var" => "report_id"
		,"start_var" => "list_order_y"
		,"start_axis" => "y"
		);


    /**
     * This is a depreciated method, please start using __construct() as this method will be removed in a future version
     *
     * @see __construct
     * @deprecated
     */
    public function DataSet()
    {
        self::__construct();
    }

	public function __construct() {
		parent::__construct();

		$this->disable_row_level_security =false;

	}



	function get_summary_text()
	{
		return "$this->name";
	}




	/** Returns a list of the associated product_templates
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved.
	 * Contributor(s): ______________________________________..
	*/


	function save_relationship_changes($is_update)
    {
    }


	function mark_relationships_deleted($id)
	{
	}

	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields()
	{
        parent::fill_in_additional_detail_fields();
		$this->get_custom_query();
		$this->get_parent_dataset();
		$this->get_report_name();
		$this->get_child_dataset();
	}


	function get_custom_query(){

		$query = "SELECT cq.name from $this->rel_custom_queries cq, $this->table_name p1 where cq.id = p1.query_id and p1.id = '$this->id' and p1.deleted=0 and cq.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional custom query detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->query_name = $row['name'];
		}
		else
		{
			$this->query_name = '';
		}
	}

	function get_parent_dataset(){
		$query = "SELECT $this->table_name.id, $this->table_name.name from $this->table_name where $this->table_name.id = '$this->parent_id' AND $this->table_name.deleted=0 ";
		$result = $this->db->query($query,true," Error filling in additional parent detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->parent_name = $row['name'];
			$this->parent_id = $row['id'];
		}
		else
		{
			$this->parent_name = '';
			$this->parent_id = '';
		}
	}

	function get_child_dataset(){
		$query = "SELECT $this->table_name.name, $this->table_name.id from $this->table_name where $this->table_name.parent_id = '$this->id' AND $this->table_name.deleted=0 ";
		$result = $this->db->query($query,true," Error filling in additional child detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->child_name = $row['name'];
			$this->child_id = $row['id'];
		}
		else
		{
			$this->child_name = 'None';
			$this->child_id = '';
		}
	}


	function get_report_name(){
		$query = "SELECT $this->report_table.id, $this->report_table.name from $this->table_name
					LEFT JOIN $this->report_table ON $this->report_table.id = '$this->report_id'
					WHERE $this->table_name.deleted=0 AND $this->report_table.deleted=0";
		$result = $this->db->query($query,true," Error filling in report name information: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->report_name = $row['name'];
			$this->report_id = $row['id'];
		}
		else
		{
			$this->report_name = '';
			$this->report_id = '';
		}
	}


	function get_list_view_data(){
		global $app_strings, $mod_strings;
		global $app_list_strings;

		global $current_user;

		if(empty($this->exportable)) $this->exportable="0";

		$temp_array = parent::get_list_view_data();
		$temp_array['NAME'] = (($this->name == "") ? "<em>blank</em>" : $this->name);
		$temp_array['OUTPUT_DEFAULT'] = $app_list_strings['dataset_output_default_dom'][isset($this->output_default) && !empty($this->output_default) ? $this->output_default : 'table'];

		$temp_array['LIST_ORDER_Y'] = $this->list_order_y;
		$temp_array['EXPORTABLE'] = $this->exportable;
		$temp_array['HEADER'] = $this->header;
		$temp_array['QUERY_NAME'] = $this->query_name;
		$temp_array['REPORT_NAME'] = $this->report_name;

		return $temp_array;
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = $this->db->quote($the_query_string);
	array_push($where_clauses, "name like '$the_query_string%'");


	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}


	return $the_where;
}

/////////////////////////////////The following functions process sub queries for reports

	function process_interlock($list_array){
		global $mod_strings;

		$sub_data_set = new DataSet();
		$sub_data_set->retrieve($this->sub_id);
		$sub_data_set->sub_query = true;

			//OUTPUT THE SUB-DATASET
			$data_set = new CustomQuery();
			$data_set->retrieve($sub_data_set->query_id);
			$data_set->sub_query_array = $list_array;
			$SubView = new ReportListView();
			$SubView->initNewXTemplate( 'modules/CustomQueries/QueryView.html',$mod_strings);
			$SubView->setDisplayHeaderAndFooter(false);
			$SubView->setup($data_set, $sub_data_set, "main", "CUSTOMQUERY", true);

		return $SubView->processDataSet();

	//end function process_interlock
	}

	function check_interlock(){

		$query = "	SELECT id from $this->table_name
					WHERE deleted=0
					AND parent_id = '$this->id'
					";
		$result = $this->db->query($query,true," Error checking for interlock: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null) {
			$this->interlock = true;
			$this->sub_id = $row['id'];
		} else {
			$this->interlock = false;
			$this->sub_id = null;
		}

//end function check_interlock
}

/////////////////Custom Layout Functions//////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////


	function enable_custom_layout(){

		//First examine the query
		$query_object = new CustomQuery();
		$query_object->retrieve($this->query_id);
		$query_object->get_custom_results(false, true);

		if(!empty($query_object->column_array)){
			foreach($query_object->column_array as $key => $value){
				if(empty($value)) $column_name[$key] = "&nbsp;";
				$layout_object = new DataSet_Layout();
				$layout_object->construct($this->id, "Column", $key, "Normal", $value);
			}
		//if empty column_array
		}

	//end function enable_custom_layout
	}

	function disable_custom_layout(){

		$layout_object = new DataSet_Layout();
		$layout_object->clear_all_layout($this->id);


	//end function disable_custom_layout
	}

	function get_layout_id_from_parent_value($parent_value){

		$query = "	SELECT id from $this->rel_dataset_layout
						WHERE parent_value='$parent_value'
						AND deleted=0
						AND parent_id = '$this->id'
					 ";
		$result = $this->db->query($query,true," get layout id: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if(!empty($row['id']) && $row['id']!=""){
			return $row['id'];
		} else {
			return false;
		}

	//end function get_layout_id_from_parent_value
	}

	function get_layout_array($hide_columns=false){

		$layout_object = new DataSet_Layout();
		return	$layout_object->get_layout_array($this->id, $hide_columns);

	//end function get_layout_array
	}

	function format_accounting($amount){

		$formatted_amount =   $this->symbol."". number_format($amount);
		return $formatted_amount;

	//end function format_accounting
	}

	function format_date($date_value)
	{
		global $timedate;
		$return_val = $timedate->to_display_date($date_value);

		return $return_val;
	}

	function format_datetime($date_value)
	{
		global $timedate;
		$return_val = $timedate->to_display_date_time($date_value);

		return $return_val;
	}

	function setup_money_symbol(){
		global $current_language, $current_user, $mod_strings, $app_list_strings;
		$app_strings = return_application_language($current_language);
		//$symbol = $app_strings['LBL_CURRENCY_SYMBOL'];

		$currency = new Currency();
		if($current_user->getPreference('currency') ){
			$currency->retrieve($current_user->getPreference('currency'));
			$symbol = $currency->symbol;
		}else{
			$currency->retrieve('-99');
			$symbol = $currency->symbol;
		}

	$this->symbol = $symbol;

	//end function setup_money_symbol
	}


	function export_csv(){
		global $current_user, $current_language, $mod_strings;
		$mod_strings = return_module_language($current_language, $this->module_dir);

		//outputs CSV content
		$query_object = new CustomQuery();
		$query_object->retrieve($this->query_id);
		//check for query running error
		$result_message = $query_object->get_custom_results(true);
		if($result_message['result']=="Valid"){
		//run the export
			$query = html_entity_decode($query_object->custom_query, ENT_QUOTES);
			$result = $this->db->query($query,true,"Error exporting custom query output to dataset: "."<BR>$query");

			$fields_array = $this->db->getFieldsArray($result, true);

			//get a temp header array if the attributes are available
			$layout_array = $this->get_layout_array();
			//reorganize the fields_array if necessary;

			foreach ($fields_array as $key => $default_name){
				if(!empty($layout_array[$default_name]['display_name'])){
					$fields_array[$key] = $layout_array[$default_name]['display_name'];
				}
			//end foreach loop
			}

			$header = implode("\",\"",array_values($fields_array));
			$header = "\"" .$header;
			$header .= "\"\r\n";
			$content = $header;

			$column_list = implode(",",array_values($fields_array));

			while($val = $this->db->fetchByAssoc($result,false)){

				$new_arr = array();

				foreach (array_values($val) as $value){
					array_push($new_arr, preg_replace("/\"/","\"\"",$value));
				}

				$line = implode("\",\"",$new_arr);
				$line = "\"" .$line;
				$line .= "\"\r\n";

				$content .= $line;
			//end while statement
			}

			return $content;


		//end if the query is a valid query
		} else {
			return $mod_strings['LBL_INVALID_QUERY'];
		}

	//end function scheduled_export
	}

//end class datasets
}

?>