<?php
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
