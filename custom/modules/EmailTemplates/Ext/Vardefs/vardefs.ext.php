<?php 
 //WARNING: The contents of this file are auto-generated


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$dictionary["EmailTemplate"]["fields"]["survey_id"] = array(
    'name' => 'survey_id',
    'vname' => 'LBL_SURVEY_ID',
    'type' => 'id',
    'required' => false,
    'reportable' => false,
    'comment' => 'Unique identifier'
);



$dictionary["EmailTemplate"]["fields"]["sms_only"] = array (
    'name' => 'sms_only',
    'vname' => 'LBL_SMS_ONLY',
    'type' => 'bool',
    'reportable'=>false,
    'comment' => 'Distinguishes the template from the one for Emails'
);



//Custom Relationship JUNIOR. Email template - SMS (1-n)  By Tung Bui
$dictionary["EmailTemplate"]["fields"]["sms_link"] = array (
    'name' => 'sms_link',
    'type' => 'link',
    'relationship' => 'emailtemplate_sms',
    'module' => 'C_SMS',
    'bean_name' => 'C_SMS',
    'source' => 'non-db',          
    'vname' => 'LBL_SMS',
);
$dictionary["EmailTemplate"]["relationships"]["emailtemplate_sms"] = array (
    'lhs_module'        => 'EmailTemplates',
    'lhs_table'            => 'email_templates',
    'lhs_key'            => 'id',
    'rhs_module'        => 'C_SMS',
    'rhs_table'            => 'c_sms',
    'rhs_key'            => 'template_id',
    'relationship_type'    => 'one-to-many'
);


?>