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
$dictionary["Prospect"]["fields"]["bc_survey_prospects"] = array (
  'name' => 'bc_survey_prospects',
  'type' => 'link',
  'relationship' => 'bc_survey_prospects',
  'source' => 'non-db',
  'module' => 'bc_survey',
  'bean_name' => false,
  'vname' => 'LBL_BC_SURVEY_PROSPECTS_FROM_BC_SURVEY_TITLE',
);


// created: 2014-10-08 08:28:57
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$dictionary["Prospect"]["fields"]["bc_survey_submission_prospects"] = array (
  'name' => 'bc_survey_submission_prospects',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_prospects',
  'source' => 'non-db',
  'module' => 'bc_survey_submission',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_PROSPECTS_FROM_BC_SURVEY_SUBMISSION_TITLE',
);


// created: 2015-10-16 12:04:54
$dictionary["Prospect"]["fields"]["j_school_prospects_1"] = array (
  'name' => 'j_school_prospects_1',
  'type' => 'link',
  'relationship' => 'j_school_prospects_1',
  'source' => 'non-db',
  'module' => 'J_School',
  'bean_name' => 'J_School',
  'side' => 'right',
  'vname' => 'LBL_J_SCHOOL_PROSPECTS_1_FROM_PROSPECTS_TITLE',
  'id_name' => 'j_school_prospects_1j_school_ida',
  'link-type' => 'one',
);
$dictionary["Prospect"]["fields"]["j_school_prospects_1_name"] = array (
  'name' => 'j_school_prospects_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_SCHOOL_PROSPECTS_1_FROM_J_SCHOOL_TITLE',
  'save' => true,
  'id_name' => 'j_school_prospects_1j_school_ida',
  'link' => 'j_school_prospects_1',
  'table' => 'j_school',
  'module' => 'J_School',
  'rname' => 'name',
);
$dictionary["Prospect"]["fields"]["j_school_prospects_1j_school_ida"] = array (
  'name' => 'j_school_prospects_1j_school_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_SCHOOL_PROSPECTS_1_FROM_PROSPECTS_TITLE_ID',
  'id_name' => 'j_school_prospects_1j_school_ida',
  'link' => 'j_school_prospects_1',
  'table' => 'j_school',
  'module' => 'J_School',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
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
$dictionary['Prospect']['fields']['email1']['required'] = true;



 // created: 2015-08-05 15:47:38
$dictionary['Contact']['fields']['birthdate']['required']=true;
$dictionary['Contact']['fields']['birthdate']['comments']='The birthdate of the contact';
$dictionary['Contact']['fields']['birthdate']['merge_filter']='disabled';
$dictionary['Contact']['fields']['birthdate']['calculated']=false;
$dictionary['Contact']['fields']['birthdate']['enable_range_search']=true;
$dictionary['Contact']['fields']['birthdate']['options']='date_range_search_dom';

 

 // created: 2015-08-25 16:10:03
$dictionary['Prospect']['fields']['email1']['duplicate_merge']='enabled';
$dictionary['Prospect']['fields']['email1']['duplicate_merge_dom_value']='1';
$dictionary['Prospect']['fields']['email1']['merge_filter']='disabled';
$dictionary['Prospect']['fields']['email1']['calculated']=false;
$dictionary['Prospect']['fields']['email1']['importable']='true';
$dictionary['Prospect']['fields']['email1']['required']=false;

 

 // created: 2014-11-28 10:45:49
$dictionary['Prospect']['fields']['first_name']['comments']='First name of the contact';
$dictionary['Prospect']['fields']['first_name']['importable']='true';
$dictionary['Prospect']['fields']['first_name']['merge_filter']='disabled';
$dictionary['Prospect']['fields']['first_name']['unified_search']=false;
$dictionary['Prospect']['fields']['first_name']['calculated']=false;

 

 // created: 2014-11-28 10:47:24
$dictionary['Prospect']['fields']['last_name']['comments']='Last name of the contact';
$dictionary['Prospect']['fields']['last_name']['importable']='true';
$dictionary['Prospect']['fields']['last_name']['merge_filter']='disabled';
$dictionary['Prospect']['fields']['last_name']['unified_search']=false;
$dictionary['Prospect']['fields']['last_name']['calculated']=false;

 

 // created: 2015-08-25 16:10:22
$dictionary['Prospect']['fields']['phone_mobile']['comments']='Mobile phone number of the contact';
$dictionary['Prospect']['fields']['phone_mobile']['duplicate_merge']='enabled';
$dictionary['Prospect']['fields']['phone_mobile']['duplicate_merge_dom_value']='1';
$dictionary['Prospect']['fields']['phone_mobile']['merge_filter']='disabled';
$dictionary['Prospect']['fields']['phone_mobile']['unified_search']=false;
$dictionary['Prospect']['fields']['phone_mobile']['calculated']=false;
$dictionary['Prospect']['fields']['phone_mobile']['required']=true;
$dictionary['Prospect']['fields']['phone_mobile']['importable']='required';
$dictionary['Prospect']['fields']['phone_mobile']['audited']=true;
$dictionary['Prospect']['fields']['birthdate']['audited']=true;

 

 // created: 2015-08-05 10:25:28
$dictionary['Prospect']['fields']['status']['required']=false;
$dictionary['Prospect']['fields']['status']['comments']='Status of the target';
$dictionary['Prospect']['fields']['status']['merge_filter']='disabled';
$dictionary['Prospect']['fields']['status']['calculated']=false;
$dictionary['Prospect']['fields']['status']['dependency']=false;

 

    $dictionary['Prospect']['fields']['potential']=array (
        'name' => 'potential',
        'vname' => 'LBL_POTENTIAL',
        'type' => 'enum',
        'comments' => '',
        'help' => '',
        'default' => 'Low',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'unified_search' => false,
        'massupdate' => false,
        'merge_filter' => 'disabled',
        'len' => 20,
        'size' => '20',
        'options' => 'level_lead_list',
        'studio' => 'visible',
    );
    $dictionary['Prospect']['fields']['working_date']=array (
        'name' => 'working_date',
        'vname' => 'LBL_WORKING_DATE',
        'type' => 'date',
        'required' => true,
        'massupdate' => false,
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => true,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'size' => '20',
        //        'enable_range_search' => true,
        //        'options' => 'date_range_search_dom',
        'display_default' => 'now',
        'source' => 'non-db',
    );

    //Bo Sung Lead source cho Target
    $dictionary['Prospect']['fields']['lead_source']=array (
        'name' => 'lead_source',
        'vname' => 'LBL_LEAD_SOURCE',
        'type' => 'enum',
        'options'=> 'lead_source_list',
        'len' => '100',
        'massupdate' => true,
        'required' => true,
    );
    $dictionary["Prospect"]["fields"]["lead_source_description"] = array (
        'name' => 'lead_source_description',
        'type' => 'text',
        'rows' => '2',
        'cols' => '30',
        'studio' => 'visible',
        'vname'=> 'LBL_SOURCE_DESCRIPTION',
    );
    //Bo Sung Status : New Assigned
    $dictionary['Prospect']['fields']['status']=array (
        'name' => 'status',
        'vname' => 'LBL_STATUS',
        'type' => 'enum',
        'len' => '100',
        'options' => 'target_status_dom',
        'default' => 'New',
        'comment' => 'Status of the target',
        'massupdate' => false,
    );
    $dictionary["Prospect"]["fields"]["age"] = array (
        'name' => 'age',
        'type' => 'varchar',
        'len' => '30',
        'studio' => 'visible',
        'vname'=> 'LBL_CONTACT_AGE',
    );

    //Add Checking Duplicate Field mobile
   /* $dictionary['Prospect']['indices'][] = array(
        'name' => 'idx_mobile_phone_cstm',
        'type' => 'index',
        'fields' => array(
            0 => 'phone_mobile',
            1 => 'birthdate',
        ),
        'source' => 'non-db',
    );*/
    $dictionary['Prospect']['fields']['guardian_name']=array (
        'name' => 'guardian_name',
        'vname' => 'LBL_GUARDIAN_NAME',
        'type' => 'varchar',
        'len' => '100',
        'comment' => '',
        'merge_filter' => 'disabled',
    );
    //add team type
    $dictionary['Prospect']['fields']['team_type'] = array(
        'name' => 'team_type',
        'vname' => 'LBL_TEAM_TYPE',
        'type' => 'enum',
        'importable' => 'false',
        'reportable' => true,
        'len' => 30,
        'size' => '20',
        'options' => 'type_team_list',
        'studio' => 'visible',
        'massupdate' => 0,
    );
    // END: add team type
    $dictionary['Prospect']['fields']['gender']=array (
        'name' => 'gender',
        'vname' => 'LBL_GENDER',
        'type' => 'enum',
        'massupdate' => 0,
        'default' => ' ',
        'comments' => '',
        'help' => '',
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => true,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 20,
        'size' => '20',
        'options' => 'gender_lead_list',
        'studio' => 'visible',
        'dbType' => 'enum',
        'required'=>false,
    );

?>