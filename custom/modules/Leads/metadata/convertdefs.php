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

    $viewdefs['Contacts']['ConvertLead'] = array(
        'copyData' => true,
        'required' => true,
        //    'select' => "report_to_name",
        'default_action' => 'create',

        'templateMeta' => array(
            'form'=>array(
                'hidden'=>array(
                    '<input type="hidden" name="opportunity_id" value="{$smarty.request.opportunity_id}">',
                    '<input type="hidden" name="case_id" value="{$smarty.request.case_id}">',
                    '<input type="hidden" name="bug_id" value="{$smarty.request.bug_id}">',
                    '<input type="hidden" name="email_id" value="{$smarty.request.email_id}">',
                    '<input type="hidden" name="inbound_email_id" value="{$smarty.request.inbound_email_id}">'
                )
            ),
            'maxColumns' => '2',
            'widths' => array(
                array('label' => '10', 'field' => '30'),
                array('label' => '10', 'field' => '30'),
            ),
        ),
        'panels' =>array (
            'LNK_NEW_CONTACT' => array (
                array (
                    array (
                        'name' => 'name',
                        'customCode' => '<input name="Contactslast_name" id="Contactslast_name" placeholder="{$MOD.LBL_LAST_NAME|replace:\':\':\'\'}" size="20" type="text"  value="{$fields.last_name.value}">
                        &nbsp;<input name="Contactsfirst_name" id="Contactsfirst_name" placeholder="{$MOD.LBL_FIRST_NAME|replace:\':\':\'\'}" style="width:120px !important" size="15" type="text" value="{$fields.first_name.value}">',
                    ),
                    'picture',
                ),
                array (

                    'nick_name',
                    'birthdate',
                ),
                array (
                    array(
                        'name' =>  'gender',
                        'displayParams' =>
                        array (
                            'required' => true,
                        ),
                    ),
                    array(
                        'name' =>  'j_school_contacts_1_name',
                        'displayParams' =>
                        array (
                            'required' => true,
                        ),
                    )
                ),
                array(
                    'description',
                ),
            ),
            'LNK_CONTACT' => array (
                array (
                    array (
                        'name' => 'c_contacts_contacts_1_name',
                        'label' => 'LBL_PARENT',
                        'displayParams' =>
                        array (
                            'class' => 'sqsNoAutofill',
                        ),
                    ),
                    'contact_rela',


                ),
                array (

                    'phone_mobile',
                    'other_mobile',
                ),
                array (

                    '',
                    'phone_other',
                ),
                array (
                    array(
                        'name' => 'relationship',
                        'studio' => 'visible',
                        'label' => 'LBL_RELATIONSHIP',
                        'customCode' => '{include file ="custom/modules/Leads/tpls/addRelationship.tpl"}',
                    ),
                ),
                array (
                    'describe_relationship',
                ),
                array (
                    'email1',
                ),
                array (
                    array (
                        'name' => 'primary_address_street',
                        'hideLabel' => true,
                        'type' => 'address',
                        'displayParams' =>
                        array (
                            'key' => 'primary',
                            'rows' => 2,
                            'cols' => 30,
                            'maxlength' => 150,
                        ),
                    ),
                ),

            ),

           /* '' => array (
                array (
                    array(
                        'name' =>'preferred_kind_of_course',
                        'customCode' => '{$KOC}',
                    )

                ),
                array (
                    'lead_source',
                ),
                array (
                    'lead_source_description',
                    'campaign_name',
                ),
            )*/

        ),
    );

    /*    $viewdefs['Accounts']['ConvertLead'] = array(
    'copyData' => true,
    'required' => true,
    'select' => "account_name",
    'default_action' => 'create',
    'relationship' => 'accounts_contacts',
    'templateMeta' => array(
    'form'=>array(
    'hidden'=>array(
    '<input type="hidden" name="opportunity_id" value="{$smarty.request.opportunity_id}">',
    '<input type="hidden" name="case_id" value="{$smarty.request.case_id}">',
    '<input type="hidden" name="bug_id" value="{$smarty.request.bug_id}">',
    '<input type="hidden" name="email_id" value="{$smarty.request.email_id}">',
    '<input type="hidden" name="inbound_email_id" value="{$smarty.request.inbound_email_id}">'
    )
    ),
    'maxColumns' => '2',
    'widths' => array(
    array('label' => '10', 'field' => '30'),
    array('label' => '10', 'field' => '30'),
    ),
    ),
    'panels' =>array (
    'LNK_NEW_ACCOUNT' => array (
    array (
    'name',
    'phone_office',
    ),
    array (
    'website',
    ),
    array(
    'description'
    ),
    )
    ),
    );
    $viewdefs['Opportunities']['ConvertLead'] = array(
    'copyData' => true,
    'required' => false,
    'templateMeta' => array(
    'form'=>array(
    'hidden'=>array(
    )
    ),
    'maxColumns' => '2',
    'widths' => array(
    array('label' => '10', 'field' => '30'),
    array('label' => '10', 'field' => '30'),
    ),
    ),
    'panels' =>array (
    'LNK_NEW_OPPORTUNITY' => array (
    array (
    'name',
    'currency_id'
    ),
    array (
    'sales_stage',
    'amount'
    ),
    array (
    'date_closed',
    ''
    ),
    array (
    'description'
    ),
    )
    ),
    );
    $viewdefs['Notes']['ConvertLead'] = array(
    'copyData' => false,
    'required' => false,
    'templateMeta' => array(
    'form'=>array(
    'hidden'=>array(
    '<input type="hidden" name="opportunity_id" value="{$smarty.request.opportunity_id}">',
    '<input type="hidden" name="case_id" value="{$smarty.request.case_id}">',
    '<input type="hidden" name="bug_id" value="{$smarty.request.bug_id}">',
    '<input type="hidden" name="email_id" value="{$smarty.request.email_id}">',
    '<input type="hidden" name="inbound_email_id" value="{$smarty.request.inbound_email_id}">'
    )
    ),
    'maxColumns' => '2',
    'widths' => array(
    array('label' => '10', 'field' => '30'),
    array('label' => '10', 'field' => '30'),
    ),
    ),
    'panels' =>array (
    'LNK_NEW_NOTE' => array (
    array (
    array('name'=>'name', 'displayParams'=>array('size'=>90)),
    ),
    array (
    array('name' => 'description', 'displayParams' => array('rows'=>10, 'cols'=>90) ),
    ),
    )
    ),
    );

    $viewdefs['Calls']['ConvertLead'] = array(
    'copyData' => false,
    'required' => false,
    'templateMeta' => array(
    'form'=>array(
    'hidden'=>array(
    '<input type="hidden" name="opportunity_id" value="{$smarty.request.opportunity_id}">',
    '<input type="hidden" name="case_id" value="{$smarty.request.case_id}">',
    '<input type="hidden" name="bug_id" value="{$smarty.request.bug_id}">',
    '<input type="hidden" name="email_id" value="{$smarty.request.email_id}">',
    '<input type="hidden" name="inbound_email_id" value="{$smarty.request.inbound_email_id}">',
    '<input type="hidden" name="Callsstatus" value="{sugar_translate label=\'call_status_default\'}">',
    )
    ),
    'maxColumns' => '2',
    'widths' => array(
    array('label' => '10', 'field' => '30'),
    array('label' => '10', 'field' => '30'),
    ),
    ),
    'panels' =>array (
    'LNK_NEW_CALL' => array (
    array (
    array('name'=>'name', 'displayParams'=>array('size'=>90)),
    ),
    array (
    'date_start',
    array (
    'name' => 'duration_hours',
    'label' => 'LBL_DURATION',
    'customCode' => '{literal}
    <script type="text/javascript">
    function isValidCallsDuration() {
    form = document.getElementById(\'ConvertLead\');
    if ( form.duration_hours.value + form.duration_minutes.value <= 0 ) {
    alert(\'{/literal}{sugar_translate label="NOTICE_DURATION_TIME" module="Calls"}{literal}\');
    return false;
    }
    return true;
    }
    </script>{/literal}
    <input name="Callsduration_hours" tabindex="1" size="2" maxlength="2" type="text" value="{$fields.duration_hours.value}"/>
    {php}$this->_tpl_vars["minutes_values"] = $this->_tpl_vars["bean"]->minutes_values;{/php}
    {html_options name="Callsduration_minutes" options=$minutes_values selected=$fields.duration_minutes.value} &nbsp;
    <span class="dateFormat">{sugar_translate label="LBL_HOURS_MINUTES" module="Calls"}',
    'displayParams' =>
    array (
    'required' => true,
    ),
    ),
    ),
    array (
    array('name' => 'description', 'displayParams' => array('rows'=>10, 'cols'=>90) ),
    ),
    )
    ),
    );

    $viewdefs['Meetings']['ConvertLead'] = array(
    'copyData' => false,
    'required' => false,
    'relationship' => 'meetings_users',
    'templateMeta' => array(
    'form'=>array(
    'hidden'=>array(
    '<input type="hidden" name="opportunity_id" value="{$smarty.request.opportunity_id}">',
    '<input type="hidden" name="case_id" value="{$smarty.request.case_id}">',
    '<input type="hidden" name="bug_id" value="{$smarty.request.bug_id}">',
    '<input type="hidden" name="email_id" value="{$smarty.request.email_id}">',
    '<input type="hidden" name="inbound_email_id" value="{$smarty.request.inbound_email_id}">',
    '<input type="hidden" name="Meetingsstatus" value="{sugar_translate label=\'meeting_status_default\'}">',
    )
    ),
    'maxColumns' => '2',
    'widths' => array(
    array('label' => '10', 'field' => '30'),
    array('label' => '10', 'field' => '30'),
    ),
    ),
    'panels' =>array (
    'LNK_NEW_MEETING' => array (
    array (
    array('name'=>'name', 'displayParams'=>array('size'=>90)),
    ),
    array (
    'date_start',
    array (
    'name' => 'duration_hours',
    'label' => 'LBL_DURATION',
    'customCode' => '{literal}
    <script type="text/javascript">
    function isValidMeetingsDuration() {
    form = document.getElementById(\'ConvertLead\');
    if ( form.duration_hours.value + form.duration_minutes.value <= 0 ) {
    alert(\'{/literal}{sugar_translate label="NOTICE_DURATION_TIME" module="Calls"}{literal}\');
    return false;
    }
    return true;
    }
    </script>{/literal}
    <input name="Meetingsduration_hours" tabindex="1" size="2" maxlength="2" type="text" value="{$fields.duration_hours.value}" />
    {php}$this->_tpl_vars["minutes_values"] = $this->_tpl_vars["bean"]->minutes_values;{/php}
    {html_options name="Meetingsduration_minutes" options=$minutes_values selected=$fields.duration_minutes.value} &nbsp;
    <span class="dateFormat">{sugar_translate label="LBL_HOURS_MINUTES" module="Calls"}',
    'displayParams' =>
    array (
    'required' => true,
    ),
    ),
    ),
    array (
    array('name' => 'description', 'displayParams' => array('rows'=>10, 'cols'=>90) ),
    ),
    )
    ),
    );

    $viewdefs['Tasks']['ConvertLead'] = array(
    'copyData' => false,
    'required' => false,
    'templateMeta' => array(
    'form'=>array(
    'hidden'=>array(
    '<input type="hidden" name="opportunity_id" value="{$smarty.request.opportunity_id}">',
    '<input type="hidden" name="case_id" value="{$smarty.request.case_id}">',
    '<input type="hidden" name="bug_id" value="{$smarty.request.bug_id}">',
    '<input type="hidden" name="email_id" value="{$smarty.request.email_id}">',
    '<input type="hidden" name="inbound_email_id" value="{$smarty.request.inbound_email_id}">'
    )
    ),
    'maxColumns' => '2',
    'widths' => array(
    array('label' => '10', 'field' => '30'),
    array('label' => '10', 'field' => '30'),
    ),
    ),
    'panels' =>array (
    'LNK_NEW_TASK' => array (
    array (
    array('name'=>'name', 'displayParams'=>array('size'=>90)),
    ),
    array (
    'status', 'priority'
    ),

    array (
    array('name' => 'description', 'displayParams' => array('rows'=>10, 'cols'=>90) ),
    ),
    )
    ),
    ); */


?>