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
// created: 2014-10-08 08:28:57
$dictionary["bc_survey"]["fields"]["bc_survey_accounts"] = array (
  'name' => 'bc_survey_accounts',
  'type' => 'link',
  'relationship' => 'bc_survey_accounts',
  'source' => 'non-db',
  'module' => 'Accounts',
  'bean_name' => 'Account',
  'vname' => 'LBL_BC_SURVEY_ACCOUNTS_FROM_ACCOUNTS_TITLE',
);


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:57
$dictionary["bc_survey"]["fields"]["bc_survey_bc_survey_questions"] = array (
  'name' => 'bc_survey_bc_survey_questions',
  'type' => 'link',
  'relationship' => 'bc_survey_bc_survey_questions',
  'source' => 'non-db',
  'module' => 'bc_survey_questions',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_BC_SURVEY_BC_SURVEY_QUESTIONS_FROM_BC_SURVEY_QUESTIONS_TITLE',
);


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:57
$dictionary["bc_survey"]["fields"]["bc_survey_bc_survey_template"] = array (
  'name' => 'bc_survey_bc_survey_template',
  'type' => 'link',
  'relationship' => 'bc_survey_bc_survey_template',
  'source' => 'non-db',
  'module' => 'bc_survey_template',
  'bean_name' => false,
  'vname' => 'LBL_BC_SURVEY_BC_SURVEY_TEMPLATE_FROM_BC_SURVEY_TEMPLATE_TITLE',
  'id_name' => 'bc_survey_bc_survey_templatebc_survey_template_ida',
);
$dictionary["bc_survey"]["fields"]["bc_survey_bc_survey_template_name"] = array (
  'name' => 'bc_survey_bc_survey_template_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_BC_SURVEY_TEMPLATE_FROM_BC_SURVEY_TEMPLATE_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_bc_survey_templatebc_survey_template_ida',
  'link' => 'bc_survey_bc_survey_template',
  'table' => 'bc_survey_template',
  'module' => 'bc_survey_template',
  'rname' => 'name',
);
$dictionary["bc_survey"]["fields"]["bc_survey_bc_survey_templatebc_survey_template_ida"]=array (
  'name' => 'bc_survey_bc_survey_templatebc_survey_template_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_BC_SURVEY_TEMPLATE_FROM_BC_SURVEY_TITLE',
  'id_name' => 'bc_survey_bc_survey_templatebc_survey_template_ida',
  'link' => 'bc_survey_bc_survey_template',
  'table' => 'bc_survey_template',
  'module' => 'bc_survey_template',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);



/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:57
$dictionary["bc_survey"]["fields"]["bc_survey_contacts"] = array (
  'name' => 'bc_survey_contacts',
  'type' => 'link',
  'relationship' => 'bc_survey_contacts',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'vname' => 'LBL_BC_SURVEY_CONTACTS_FROM_CONTACTS_TITLE',
);


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:57
$dictionary["bc_survey"]["fields"]["bc_survey_leads"] = array (
  'name' => 'bc_survey_leads',
  'type' => 'link',
  'relationship' => 'bc_survey_leads',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_BC_SURVEY_LEADS_FROM_LEADS_TITLE',
);


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:58
$dictionary["bc_survey"]["fields"]["bc_survey_pages_bc_survey"] = array (
  'name' => 'bc_survey_pages_bc_survey',
  'type' => 'link',
  'relationship' => 'bc_survey_pages_bc_survey',
  'source' => 'non-db',
  'module' => 'bc_survey_pages',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_BC_SURVEY_PAGES_BC_SURVEY_FROM_BC_SURVEY_PAGES_TITLE',
);


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:57
$dictionary["bc_survey"]["fields"]["bc_survey_prospects"] = array (
  'name' => 'bc_survey_prospects',
  'type' => 'link',
  'relationship' => 'bc_survey_prospects',
  'source' => 'non-db',
  'module' => 'Prospects',
  'bean_name' => 'Prospect',
  'vname' => 'LBL_BC_SURVEY_PROSPECTS_FROM_PROSPECTS_TITLE',
);


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:57
$dictionary["bc_survey"]["fields"]["bc_survey_submission_bc_survey"] = array (
  'name' => 'bc_survey_submission_bc_survey',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_bc_survey',
  'source' => 'non-db',
  'module' => 'bc_survey_submission',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_BC_SURVEY_FROM_BC_SURVEY_SUBMISSION_TITLE',
);


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:57
$dictionary["bc_survey"]["fields"]["bc_survey_users"] = array (
  'name' => 'bc_survey_users',
  'type' => 'link',
  'relationship' => 'bc_survey_users',
  'source' => 'non-db',
  'module' => 'Users',
  'bean_name' => 'User',
  'vname' => 'LBL_BC_SURVEY_USERS_FROM_USERS_TITLE',
);

?>