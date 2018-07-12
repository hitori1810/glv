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

/*********************************************************************************

 * Description:
 ********************************************************************************/






require_once('include/ListView/ReportListView.php');


// DataSet Attribute is used to store attribute information for a particular data format.
class DataSet_Attribute extends SugarBean {
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

    var $parent_id;			//dataset_layout id
    var $bg_color;			//bg color
    var $cell_size;				//width in column		//height in row
    var $size_type;			//Size Type
    var $style;				//bold, italic
    var $wrap;				//wrap or no wrap text
    var $font_color;
    var $font_size = 0;
    var $format_type;		//Text, Currency, Datastamp
    var $attribute_type;	//Head or Body
    var $display_name;		//Header only
    var $display_type = "Normal";	//Header only	Default, Name, Scalar

    //for the name of the parent if an interlocked data set
    var $parent_name;
    //for the name of the child if an interlocked data set
    var $child_name;

    //for related fields
    var $query_name;
    var $report_name;

    var $table_name = "dataset_attributes";
    var $module_dir = 'DataSets';
    var $object_name = "DataSet_Attribute";
    var $rel_layout_table = "dataset_layouts";
    var $rel_datasets_table = "data_sets";
    var $disable_custom_fields = true;

    var $new_schema = true;

    var $column_fields = Array("id"
        ,"date_entered"
        ,"date_modified"
        ,"modified_user_id"
        ,"created_by"
        ,"parent_id"
        ,"bg_color"
        ,"cell_size"
        ,"size_type"
        ,"style"
        ,"wrap"
        ,"font_color"
        ,"font_size"
        ,"format_type"
        ,"format"
        ,"attribute_type"
        ,"display_name"
        ,"display_type"
        );


    // This is used to retrieve related fields from form posts.
    var $additional_column_fields = Array();

    // This is the list of fields that are in the lists.
    var $list_fields = array();
    // This is the list of fields that are required
    var $required_fields =  array();


    /**
     * This is a depreciated method, please start using __construct() as this method will be removed in a future version
     *
     * @see __construct
     * @deprecated
     */
    public function DataSet_Attribute()
    {
        self::__construct();
    }

    public function __construct() {
        global $dictionary;
        if(isset($this->module_dir) && isset($this->object_name) && !isset($dictionary[$this->object_name])){
            require('metadata/dataset_attributesMetaData.php');
        }
        parent::__construct();

        $this->disable_row_level_security =true;

    }



    function get_summary_text()
    {
        return "$this->display_name";
    }




    /** Returns a list of the associated product_templates
    * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
    * All Rights Reserved.
    * Contributor(s): ______________________________________..
    */

        function create_export_query(&$order_by, &$where)
        {

            $export_object = new CustomQuery();
            return $export_object->create_export_query();


        }



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


    }

    function get_list_view_data(){
        global $app_strings, $mod_strings;
        global $app_list_strings;

        global $current_user;

        if(empty($this->exportable)) $this->exportable="0";

        $temp_array = parent::get_list_view_data();
        $temp_array['NAME'] = (($this->name == "") ? "<em>blank</em>" : $this->name);
        $temp_array['OUTPUT_DEFAULT'] = $app_list_strings['dataset_output_default_dom'][$this->output_default];
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
    $the_query_string = addslashes($the_query_string);
    array_push($where_clauses, "name like '$the_query_string%'");


    $the_where = "";
    foreach($where_clauses as $clause)
    {
        if($the_where != "") $the_where .= " or ";
        $the_where .= $clause;
    }


    return $the_where;

    //end function get_list_view_data
    }


//end class datasets
}

?>
