<?php
/*********************************************************************************
* SugarCRM Community Edition is a customer relationship management program developed by
* SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
* 
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU Affero General Public License version 3 as published by the
* Free Software Foundation with the addition of the following permission added
* to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
* IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
* OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
* 
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
* details.
* 
* You should have received a copy of the GNU Affero General Public License along with
* this program; if not, see http://www.gnu.org/licenses or write to the Free
* Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
* 02110-1301 USA.
* 
* You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
* SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
********************************************************************************/

$dictionary['C_SMS'] = array(
    'table'=>'c_sms',
    'audited'=>false,
    'duplicate_merge'=>true,
    'fields'=>array (
        'phone_number' => 
        array (
            'name' => 'phone_number',
            'vname' => 'LBL_PHONE_NUMBER',
            'type' => 'phone',
            'dbType' => 'varchar',
            'len' => 100,
            'reportable' => true,
            'required'=>true,
        ),
        'delivery_status' => 
        array ( 
            'required' => true,
            'name' => 'delivery_status',
            'vname' => 'LBL_DELIVERY_STATUS',
            'type' => 'enum',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'reportable' => true,
            'len' => 100,
            'options' => 'delivery_status_list',
            'studio' => 'visible',
        ),
        'parent_type'=>
        array(
            'name'  =>'parent_type',
            'vname' =>'LBL_PARENT_TYPE',
            'type'  => 'parent_type',
            'dbType'=>'varchar',
            'required'  =>false,
            'group'     =>'parent_name',
            'options'   => 'izeno_sms_module_selected_list',
            'reportable'=>true,
            'len'       =>100,
            'comment' => 'The Sugar object to which the call is related'
        ),
        'parent_name'=>
        array(
            'name'=> 'parent_name',
            'parent_type'=>'record_type_display' ,
            'type_name'=>'parent_type',
            'id_name'=>'parent_id',
            'vname'=>'LBL_LIST_RELATED_TO',
            'type'=>'parent',
            'group'=>'parent_name',
            'source'=>'non-db',
            'options'=> 'izeno_sms_module_selected_list',
            'studio'    => 'visible'
        ),
        'parent_id'=>
        array(
            'name'=>'parent_id',
            'vname'=>'LBL_LIST_RELATED_TO_ID',
            'type'=>'id',
            'group'=>'parent_name',
            'reportable'=>true,
            'comment' => 'The ID of the parent Sugar object identified by parent_type',
        ),
        //Custom Relationship Contact
        'student_name' => array(
            'required'  => true,
            'source'    => 'non-db',
            'name'      => 'student_name',
            'vname'     => 'LBL_STUDENT_NAME',
            'type'      => 'relate',
            'rname'     => 'name',
            'id_name'   => 'parent_id',
            'join_name' => 'contacts',
            'link'      => 'sms_contacts',
            'table'     => 'contacts',
            'isnull'    => 'true',
            'module'    => 'Contacts',
        ),
        
        'sms_contacts' => array(
            'name'          => 'sms_contacts',
            'type'          => 'link',
            'relationship'  => 'contact_smses',
            'module'        => 'Contacts',
            'bean_name'     => 'Contact',
            'source'        => 'non-db',
            'vname'         => 'LBL_STUDENT_NAME',
        ),
        //Custom Relationship Lead
        'lead_name' => array(
            'required'  => true,
            'source'    => 'non-db',
            'name'      => 'lead_name',
            'vname'     => 'LBL_LEAD_NAME',
            'type'      => 'relate',
            'rname'     => 'name',
            'id_name'   => 'parent_id',
            'join_name' => 'leads',
            'link'      => 'sms_leads',
            'table'     => 'leads',
            'isnull'    => 'true',
            'module'    => 'Leads',
        ),
        
        'sms_leads' => array(
            'name'          => 'sms_leads',
            'type'          => 'link',
            'relationship'  => 'lead_smses',
            'module'        => 'Leads',
            'bean_name'     => 'Lead',
            'source'        => 'non-db',
            'vname'         => 'LBL_LEAD_NAME',
        ),
        //END
        //END
        'date_send' => array(
            'name' => 'date_send',
            'vname' => 'LBL_DATE_SEND',
            'type' => 'date',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'display_default' => 'now',
        ),
        'date_in_content' => array(
            'name' => 'date_in_content',
            'vname' => 'LBL_DATE_IN_CONTENT',
            'type' => 'date',
            'massupdate' => 0,
            'no_default' => false,
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'display_default' => 'now',
        ),
        'message_count' => 
        array (
            'required' => false,
            'name' => 'message_count',
            'vname' => 'LBL_MESSAGE_COUNT',
            'type' => 'int',
            'massupdate' => 0,
            'help' => 'Number Class',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '10',
            'size' => '20',
            'enable_range_search' => false,
            'disable_num_format' => '',
            'min' => false,
            'max' => false,
        ),
        'supplier'=>
        array(
            'name'  =>'supplier',
            'vname' =>'LBL_SUPPLIER',
            'type' => 'enum',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 100,
            'size' => '20',
            'options' => 'sms_supplier_options',
            'studio' => 'visible',
            'dependency' => false,
            'massupdate' => 0,
        ),
        //Custom Relationship JUNIOR. Email template - SMS (1-n)  By Tung Bui
        'template_name' => array(
            'required'  => false,
            'source'    => 'non-db',
            'name'      => 'template_name',
            'vname'     => 'LBL_EMAIL_TEMPLATE_NAME',
            'type'      => 'relate',
            'rname'     => 'name',
            'id_name'   => 'template_id',
            'link'      => 'templates_link',
            'table'     => 'email_templates',
            'isnull'    => 'true',
            'module'    => 'EmailTemplates',
        ),

        'template_id' => array(
            'name'              => 'template_id',
            'rname'             => 'id',
            'vname'             => 'LBL_EMAIL_TEMPLATE_ID',
            'type'              => 'id',
            'table'             => 'email_templates',
            'isnull'            => 'true',
            'module'            => 'EmailTemplates',
            'dbType'            => 'id',
            'reportable'        => false,
            'massupdate'        => false,
            'duplicate_merge'   => 'disabled',
        ),

        'templates_link' => array(
            'name'          => 'templates_link',
            'type'          => 'link',
            'relationship'  => 'emailtemplate_sms',
            'module'        => 'EmailTemplates',
            'bean_name'     => 'EmailTemplate',
            'source'        => 'non-db',
            'vname'         => 'LBL_EMAIL_TEMPLATE_NAME',
        ),
    ),
    'relationships'=>array (
    ),
    'optimistic_locking'=>true,
    'unified_search'=>true,
);
if (!class_exists('VardefManager')){
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('C_SMS','C_SMS', array('basic','team_security','assignable'));