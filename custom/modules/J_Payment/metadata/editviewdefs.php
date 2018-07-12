<?php
$module_name = 'J_Payment';
$viewdefs[$module_name] =
array (
    'EditView' =>
    array (
        'templateMeta' =>
        array (
            'form' =>
            array (
                'enctype' => 'multipart/form-data',
                'hidden' =>
                array (
                    1 => '<input type="hidden" name="content" value="{$fields.content.value}">',
                    2 => '{include file="custom/modules/J_Payment/tpl/discountTable.tpl"}
                    {include file="custom/modules/J_Payment/tpl/loyatyTable.tpl"}
                    {include file="custom/modules/J_Payment/tpl/sponTable.tpl"}',
                    3 => '{$discount_list}{$sponsor_list}{$loyalty_list}',
                    4 => '<input type="hidden" name="payment_list" id="payment_list" value="{$fields.payment_list.value}">',
                    6 => '<input type="hidden" name="class_list" id="class_list" value="{$fields.class_list.value}">',
                    7 => '<input type="hidden" name="sponsor_id" id="sponsor_id" value="">',
                    8 => '
                    <input type="hidden" name="sub_discount_amount" id="sub_discount_amount" value="{sugar_number_format var=$fields.sub_discount_amount.value}">
                    <input type="hidden" name="sub_discount_percent" id="sub_discount_percent" value="{sugar_number_format var=$fields.sub_discount_percent.value precision=2}">',
                    9 => '<input type="hidden" name="lead_id" id="lead_id" value="{$fields.lead_id.value}">',
                    10 => '{$lock_assigned_to}',
                    11 => '<input type="hidden" name="outstanding_list" value="{$fields.outstanding_list.value}">',
                    12 => '<input type="hidden" name="is_outstanding" value="{$fields.is_outstanding.value}">',
                    13 => '<input type="hidden" name="ratio" id="ratio" value="{$ratio}">',
                    14 => '<input type="hidden" name="catch_limit" id="catch_limit" value="{$fields.catch_limit.value}">',
                    15 => '<input type="hidden" name="limited_discount_amount" id="limited_discount_amount" value="{$fields.limited_discount_amount.value}">',
                    16 => '<input type="hidden" name="is_outing" id="is_outing" value="{$fields.is_outing.value}">',
                    17 => '<input type="hidden" name="aims_id" id="aims_id" value="{$fields.aims_id.value}">',
                    18 => '<input type="hidden" name="team_type" id="team_type" value="{$team_type}">',
                    19 => '<input type="hidden" name="student_corporate_name" id="student_corporate_name" value="">',
                    20 => '<input type="hidden" name="student_corporate_id" id="student_corporate_id" value="">',
                    21 => '<input type="hidden" name="current_team_id" id="current_team_id" value="{$fields.team_id.value}">',
                    22 => '<input type="hidden" name="parent_type" id="parent_type" value="{$parent_type}">',
                ),
            ),
            'maxColumns' => '2',
            'widths' =>
            array (
                0 =>
                array (
                    'label' => '11',
                    'field' => '50',
                ),
                1 =>
                array (
                    'label' => '11',
                    'field' => '20',
                ),
            ),
            'javascript' => '
            {sugar_getscript file="custom/include/javascripts/Bootstrap/bootstrap.min.js"}
            {sugar_getscript file="custom/include/javascripts/BootstrapSelect/bootstrap-select.min.js"}
            {sugar_getscript file="custom/include/javascripts/AjaxBootstrapSelect/js/ajax-bootstrap-select.js"}
            {sugar_getscript file="custom/include/javascripts/BootstrapMultiselect/js/bootstrap-multiselect.js"}
            <link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/Bootstrap/bootstrap.min.css}"/>
            <link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/BootstrapSelect/bootstrap-select.min.css}"/>
            <link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/AjaxBootstrapSelect/css/ajax-bootstrap-select.css}"/>
            <link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/BootstrapMultiselect/css/bootstrap-multiselect.css}"/>
            <link rel="stylesheet" href="{sugar_getjspath file=custom/modules/J_Payment/css/custom_style.css}"/>
            {$limit_discount_percent}
            {$min_points_loyalty}
            {sugar_getscript file="custom/include/javascripts/Multifield/jquery.multifield.min.js"}

            {if $fields.payment_type.value == "Moving Out" || $fields.payment_type.value == "Transfer Out" || $fields.payment_type.value == "Refund"}
            {sugar_getscript file="custom/modules/J_Payment/js/edit_moving_transfer_refund.js"}   
            {else}                                                                 
            {sugar_getscript file="custom/modules/J_Payment/js/edit_enrollment.js"}
            {sugar_getscript file="custom/modules/J_Payment/js/extention_layout.js"}
            {/if}

            {if $fields.payment_type.value == "Cashholder" || $fields.payment_type.value == "Deposit" || $fields.payment_type.value == "Enrollment"}
            {sugar_getscript file="custom/include/javascripts/Select2/select2.min.js"}
            <link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/Select2/select2.css}"/>
            {/if}
            ',
            'useTabs' => false,
            'tabDefs' =>
            array (
                'LBL_SELECT_PAYMENT' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_ENROLLMENT' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PRICING' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PAYMENT_PLAN' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ), 
                'LBL_OTHER_PAYMENT' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PAYMENT_MOVING' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PAYMENT_TRANSFER' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PAYMENT_REFUND' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => false,
        ),
        'panels' =>
        array (      
            'LBL_SELECT_PAYMENT' =>
            array (
                0 =>
                array (
                    0 => array (
                        'hideLabel' => true,
                        'customCode' => '{include file="custom/modules/J_Payment/tpl/paymentList.tpl"}'
                    )
                ),
            ),
            'LBL_ENROLLMENT' =>
            array (    
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'contacts_j_payment_1_name',
                        'customLabel' => '{$MOD.LBL_STUDENT_INFO}: <span class="required">*</span>',
                        'customCode' => '{include file="custom/modules/J_Payment/tpl/fieldStudent.tpl"}'
                    ),   
                    1 => 
                    array(
                        'hideLabel' => true,
                        'customCode' => '{include file="custom/modules/J_Payment/tpl/paymentList.tpl"}'
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'customLabel' => '{$MOD.LBL_SCLASSES}: <span class="required">*</span>',
                        'customCode' => '
                        <table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="padding: 0px !important; width: 40%" >
                        <select id="classes" name="classes[]" multiple="multiple"> {$CLASS_OPTIONS} </select>
                        </td>
                        <td width="20%">
                        <div id="div_sclass_schedule" style="display:none;"></div>
                        <input class="date_input input_readonly" placeholder="dd/mm/yyyy" style="display:none;" autocomplete="off" type="text" name="class_end" id="class_end" value="{$fields.class_end.value}" tabindex="0" size="11" maxlength="10" readonly>
                        <input type="button" id="btn_show_schedule" value="{$MOD.LBL_SHOW_SCHEDULE}" onclick="showClassSchedule();"/>
                        </td>
                        <td width="35%">
                        </td>
                        </tr></tbody>
                        </table>
                        
                        
                        
                        ',
                    ),  
                    1 => array(
                        'name' => 'payment_type',
                        'customCode' => '{$payment_type}',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'start_study',
                        'customCode' => '
                        <table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="padding: 0px !important; width:40%">
                        <span class="dateTime">
                        <input class="date_input" placeholder="dd/mm/yyyy" autocomplete="off" type="text" name="start_study" id="start_study" value="{$fields.start_study.value}" title="{$MOD.LBL_START_STUDY}" tabindex="0" size="11" maxlength="10">
                        <img src="themes/RacerX/images/jscalendar.png" alt="Enter Date" style="position:relative; top:6px" border="0" id="start_study_trigger">
                        </span>
                        {literal}
                        <script type="text/javascript">
                        Calendar.setup ({
                        inputField : "start_study",
                        daFormat : cal_date_format,
                        button : "start_study_trigger",
                        singleClick : true,
                        dateStr : "",
                        step : 1,
                        weekNumbers:false
                        });</script>{/literal}
                        </td>
                        
                        <td width="20%" scope="col"><label>{$MOD.LBL_END_STUDY} <span class="required">*</span>: </label></td>
                        
                        <td width="35%">
                        <span class="dateTime">
                        <input class="date_input" placeholder="dd/mm/yyyy" autocomplete="off" type="text" name="end_study" id="end_study" value="{$fields.end_study.value}" title="{$MOD.LBL_END_STUDY}" tabindex="0" size="11" maxlength="10">
                        <img src="themes/RacerX/images/jscalendar.png" alt="Enter Date" style="position:relative; top:6px" border="0" id="end_study_trigger">
                        </span>
                        {literal}
                        <script type="text/javascript">
                        Calendar.setup ({
                        inputField : "end_study",
                        daFormat : cal_date_format,
                        button : "end_study_trigger",
                        singleClick : true,
                        dateStr : "",
                        step : 1,
                        weekNumbers:false
                        });</script>{/literal}
                        </td>     
                        </tr></tbody>
                        </table>
                        
                        ',
                    ),  
                    1 =>
                    array (
                        'name' => 'payment_date',
                    ),
                ),  
                5 =>
                array (
                    0 =>
                    array (
                        'name' => 'tuition_hours',
                        'customLabel' => '{$MOD.LBL_SESSIONS} (1):',
                        'customCode' => '
                        <table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="padding: 0px !important; width:40%">
                        <input class="input_readonly" autocomplete="off" type="text" name="sessions" id="sessions" value="{$fields.sessions.value}" tabindex="0" size="4" maxlength="10" readonly>
                        </td>
                        <td width="20%" scope="col">
                        <label>{$MOD.LBL_TUITION_HOURS} (2): </label>
                        </td>
                        <td width="35%">
                        <input class="input_readonly" autocomplete="off" type="text" name="tuition_hours" id="tuition_hours" value="{sugar_number_format var=$fields.tuition_hours.value precision=2}" tabindex="0" size="4" maxlength="10" style="color: rgb(165, 42, 42);" readonly>
                        </td>
                        </tr></tbody>
                        </table>',
                    ),  
                    1 => array(
                        'hideLabel' => true,
                    ),  
                ), 
            ),
            'LBL_PRICING' =>
            array(
                0 =>
                array ( 
                    0 =>
                    array (
                        'name' => 'j_coursefee_j_payment_1j_coursefee_ida',
                        'customLabel' => '{$MOD.LBL_COURSE_FEE_ID}: <span class="required">*</span>',
                        'customCode' => '
                        <table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="padding: 0px !important; width:40%">{$coursefee}</td>
                        <td width="20%" scope="col"><label>
                        {$MOD.LBL_LIST_PRICE} (3): </label></td>
                        <td width="35%"><input class="currency input_readonly" type="text" name="list_price_per_hour" id="list_price_per_hour" size="20" maxlength="26" style="color: rgb(165, 42, 42); font-weight: bold;" readonly></td>
                        </tr></tbody>
                        </table>
                        
                        ',                                                                            
                    ), 
                    1 =>
                    array (
                        'name' => 'tuition_fee',                   
                        'customLabel' => '{$MOD.LBL_TUITION_FEE} (4):
                        <br>
                        <i>(4)=(2)x(3)</i>',
                        'customCode' => '<input class="currency input_readonly" type="text" name="tuition_fee" id="tuition_fee" size="20" maxlength="26" value="{sugar_number_format var=$fields.tuition_fee.value}" title="{$MOD.LBL_TUITION_FEE}" tabindex="0"  style="font-weight: bold;" readonly>
                        
                        ',
                    ),  
                ),     
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'paid_amount',   
                        'customLabel' => '
                        <input class="button enrollment_btn" type="button" name="btn_select_balance_hour" value="{$MOD.LBL_SELECT_BALANCE_HOUR}" id="btn_select_balance_hour" title="{$MOD.LBL_SELECT_BALANCE_HOUR_TITLE}" onclick="displayDialogSelectBalance(\'Hour\');">',
                        'customCode' => '<table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="width:40%" class="td_available">
                            <label>{$MOD.LBL_AVAILABLE}: </label>
                            <label id="lbl_avai_balance_hour">0</label>
                        </td>
                        <td width="20%" scope="col"><label style="color: green;">{$MOD.LBL_BALANCE_HOURS} (5): </label></td>
                        <td width="35%"><input class="input_readonly" autocomplete="off" type="text" name="paid_hours" id="paid_hours" value="{sugar_number_format var=$fields.paid_hours.value precision=2}" tabindex="0" size="4" maxlength="10" readonly></td>
                        </tr></tbody>
                        </table>',
                    ),   
                    1 =>
                    array (
                        'hideLabel' => true,     
                    ),
                ),  
                2 =>
                array(  
                    0 =>
                    array (
                        'name' => 'amount_bef_discount',       
                        'customLabel' => '',
                        'customCode' => '<table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="padding: 0px !important; width:40%">  
                        </td>
                        <td width="20%" scope="col"><label>{$MOD.LBL_TOTAL_HOURS} (6): 
                        <br>
                        <i>(6)=(2)-(5)</i></label></td>
                        <td width="35%"><input class="input_readonly" autocomplete="off" type="text" name="total_hours" id="total_hours" value="{sugar_number_format var=$fields.total_hours.value precision=2}" tabindex="0" size="4" maxlength="10" readonly></td>
                        </tr></tbody>
                        </table>',
                    ),
                    1 => array(
                        'name' => 'amount_bef_discount',
                        'customLabel' => '{$MOD.LBL_AMOUNT_BEF_DISCOUNT} (7):
                        <br>
                        <i>(7)=(6)x(3)</i>',
                        'customCode' => '<input class="currency input_readonly" type="text" name="amount_bef_discount" id="amount_bef_discount" size="20" maxlength="26" value="{sugar_number_format var=$fields.amount_bef_discount.value}" title="{$MOD.LBL_AMOUNT_BEF_DISCOUNT}" tabindex="0"  style="font-weight: bold;" readonly>
                        ',
                    ),                        
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'customLabel' => '
                        {if $lisence_version <> "Free" && $lisence_version <> "Standard"}
                        <input class="button enrollment_btn" type="button" name="btn_get_discount" value="{$MOD.LBL_ADD_DISCOUNT}" id="btn_get_discount">
                        {/if}
                        ',
                        'customCode' => '<table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="padding: 0px !important; width:40%">
                        {if $lisence_version == "Free" || $lisence_version == "Standard"}
                        <label class="warning">{$MOD.LBL_YOU_CAN_NOT_USE_DISCOUNT_FREE_STANDARD}</label>
                        {/if}
                        </td>
                        <td width="20%" scope="col"><label>{$MOD.LBL_DISCOUNT_PERCENT} (8):<br>
                        <i>(8)=(9)/(7)</i>
                        </label></td>
                        <td width="35%">
                            <input class="input_readonly" autocomplete="off" type="text" name="discount_percent" id="discount_percent" value="{sugar_number_format var=$fields.discount_percent.value precision=2}" tabindex="0" size="4" maxlength="10" readonly>
                        </td>
                        </tr></tbody>
                        </table>',
                    ), 
                    1 =>
                    array (
                        'name' => 'discount_amount',        
                        'customLabel' => '{$MOD.LBL_DISCOUNT_AMOUNT} (9):',
                        'customCode' => '
                        <input class="currency input_readonly" type="text" name="discount_amount" id="discount_amount" size="20" maxlength="26" value="{sugar_number_format var=$fields.discount_amount.value}" title="{$MOD.LBL_DISCOUNT_AMOUNT}" tabindex="0"  style="font-weight: bold;" readonly>
                        ',
                    ),
                ),
                5 =>
                array (    
                    0 =>
                    array (
                        'customLabel' => '
                        {if $lisence_version <> "Free" && $lisence_version <> "Standard"}
                        <input class="button enrollment_btn" type="button" name="btn_add_sponsor" value="{$MOD.LBL_ADD_SPONSOR}" id="btn_add_sponsor">
                        {/if}',
                        'customCode' => '<table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="padding: 0px !important; width:40%">
                        {if $lisence_version == "Free" || $lisence_version == "Standard"}
                        <label class="warning">{$MOD.LBL_YOU_CAN_NOT_USE_VOUCHER_FREE_STANDARD}</label>
                        {/if}
                        </td>
                        <td width="20%" scope="col"><label>{$MOD.LBL_FINAL_SPONSOR_PERCENT} (10):<br>
                        <i>(10)=(11)/(7)</i>
                        </label></td>
                        <td width="35%">
                            <input class="input_readonly" autocomplete="off" type="text" name="final_sponsor_percent" id="final_sponsor_percent" value="{sugar_number_format var=$fields.final_sponsor_percent.value precision=2}" tabindex="0" size="4" maxlength="10" readonly>
                        </td>
                        </tr></tbody>
                        </table>',
                    ),  
                    1 =>array (
                        'name' => 'final_sponsor',       
                        'customLabel' => '{$MOD.LBL_FINAL_SPONSOR} (11):',
                        'customCode' => '
                        <input readonly size="20" maxlength="26" class="currency input_readonly" name="final_sponsor" type="text" id="final_sponsor" value="{sugar_number_format var=$fields.final_sponsor.value}" tabindex="0"  style="font-weight: bold;">
                        ',
                    ),
                ),         
                7 =>
                array ( 
                    0 =>
                    array (            
                        'name' => ''     
                    ),
                    1 => array(
                        'name' => 'total_after_discount',
                        'customLabel' => '{$MOD.LBL_TOTAL_AFTER_DISCOUNT} (12):
                        <br>
                        <i>(12)=(7)-(9)-(11)</i>',
                        'customCode' => '<input class="currency input_readonly" type="text" name="total_after_discount" id="total_after_discount" size="20" maxlength="26" value="{sugar_number_format var=$fields.total_after_discount.value}" title="{$MOD.LBL_TOTAL_AFTER_DISCOUNT}" tabindex="0"  style="font-weight: bold;" readonly>',
                    ),  
                ),
                8 =>
                array(
                    0 => array(
                        'customLabel' => '
                        <input class="button enrollment_btn" type="button" name="btn_select_balance_amount" value="{$MOD.LBL_SELECT_BALANCE_AMOUNT}" id="btn_select_balance_amount" title="{$MOD.LBL_SELECT_BALANCE_AMOUNT_TITLE}" onclick="displayDialogSelectBalance(\'Amount\');">
                        ',
                        'customCode' => '<table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="width:40%" class="td_available">
                            <label>{$MOD.LBL_AVAILABLE}: </label>
                            <label id="lbl_avai_balance_amount">0</label>
                        </td>
                        <td width="20%"></td>
                        <td width="35%"></td>
                        </tr></tbody>
                        </table>',
                    ), 
                    1 =>
                    array (                         
                        'name' => 'deposit_amount',         
                        'customLabel' => '<label style="color: green;">{$MOD.LBL_DEPOSIT_AMOUNT} (13): </label>',                                
                        'customCode' => '
                        <input class="currency input_readonly" type="text" name="deposit_amount" id="deposit_amount" size="20" maxlength="26" value="{sugar_number_format var=$fields.deposit_amount.value}" title="{$MOD.LBL_DISCOUNT_AMOUNT}" tabindex="0"  style="font-weight: bold;" readonly>
                        ',    
                    ),                         
                ),    
                15 =>
                array (
                    0 => array(
                        'hideLabel' => true,
                    ),
                    1 =>
                    array (
                        'name' => 'payment_amount',
                        'customLabel' => '<b>{$MOD.LBL_GRAND_TOTAL} (14):<br>
                        <i>(14)=(12)-(13)</i></b>',
                        'customCode' => '<input class="currency input_readonly" type="text" name="payment_amount" id="payment_amount" size="20" maxlength="26" value="{sugar_number_format var=$fields.payment_amount.value}" title="{$MOD.LBL_GRAND_TOTAL}" tabindex="0"  style="font-weight: bold; color: rgb(165, 42, 42);" readonly>
                        ',
                    ),                   
                ),   
            ),       
            // PAYMENT OTHER JUNIOR
            'LBL_OTHER_PAYMENT' =>
            array (
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'contacts_j_payment_1_name',
                        'customLabel' => '{$MOD.LBL_STUDENT}: <span class="required">*</span>',
                        'customCode' => '{include file="custom/modules/J_Payment/tpl/fieldStudent.tpl"}'
                    ),
                    1 =>
                    array (
                        'name' => 'payment_type',
                        'customCode' => '
                        {if !empty($fields.id.value)}
                        {$payment_type}
                        {else}
                        {html_options name="payment_type" id="payment_type" options=$payment_type selected=$payment_type_selected}{/if}
                        ',
                    ),
                ),
                2 =>
                array (
                    0 => 'payment_date',
                    1 =>array (
                        'name' => 'kind_of_course',
                        'customCode'=> '
                        {html_options name="kind_of_course" id="kind_of_course" options=$fields.kind_of_course.options selected=$fields.kind_of_course.value}
                        '
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (       
                        'name' => 'j_coursefee_j_payment_1j_coursefee_ida',
                        'customLabel' => '{$MOD.LBL_COURSE_FEE_ID}: <span class="required">*</span>',
                        'customCode' => '
                        <table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="padding: 0px !important; width:40%">{$coursefee}</td>
                        <td width="20%"><label>
                         </label></td>
                        <td width="35%"></td>
                        </tr></tbody>
                        </table>
                        
                        ',                                                                            
                    ), 
                    1 => array(
                        'name' => 'amount_bef_discount',
                        'customLabel' => '{$MOD.LBL_LIST_PRICE} (1):',
                        'customCode' => '<input class="currency input_readonly" type="text" name="list_price_per_hour" id="list_price_per_hour" size="20" maxlength="26" style="color: rgb(165, 42, 42); font-weight: bold;" readonly>
                        ',
                    ), 

                ),
               
                4 =>
                array (   
                    0 =>
                    array (
                        'name' => 'amount_bef_discount',
                        'customLabel' => '',
                        'customCode' => '<table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="padding: 0px !important; width:40%">
                        <input type="hidden" name="tuition_fee" id="tuition_fee" value="{sugar_number_format var=$fields.tuition_fee.value}">
                        </td>
                        <td width="20%" scope="col" class="tuition_hours">
                        {$MOD.LBL_TUITION_HOURS} (2):</td>
                        <td width="35%">
                        <input autocomplete="off" type="text" class="tuition_hours" name="tuition_hours" id="tuition_hours" value="{sugar_number_format var=$fields.tuition_hours.value precision=2}" tabindex="0" size="4" maxlength="10"  style="color: rgb(165, 42, 42);" >
                        <input type="hidden" name="total_hours" id="total_hours" value="{$fields.total_hours.value}">
                        </td>
                        </tr></tbody>
                        </table>',
                    ), 
                    1 => array(
                        'name' => 'amount_bef_discount',
                        'customLabel' => '{$MOD.LBL_AMOUNT_BEF_DISCOUNT} (3):
                        <br>
                        <i>(3)=(1)x(2)</i>',
                        'customCode' => '<input class="currency input_readonly" type="text" name="amount_bef_discount" id="amount_bef_discount" size="20" maxlength="26" value="{sugar_number_format var=$fields.amount_bef_discount.value}" title="{$MOD.LBL_AMOUNT_BEF_DISCOUNT}" tabindex="0"  style="font-weight: bold;" readonly>
                        ',
                    ),   
                ),     
                5 =>
                array (    
                    0 =>
                    array (
                        'customLabel' => '
                        {if $lisence_version <> "Free" && $lisence_version <> "Standard"}
                        <input class="button enrollment_btn" type="button" name="btn_get_discount" value="{$MOD.LBL_ADD_DISCOUNT}" id="btn_get_discount">
                        {/if}                                                                                                                            
                        ',
                        'customCode' => '<table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="padding: 0px !important; width:40%">
                        {if $lisence_version == "Free" || $lisence_version == "Standard"}
                        <label class="warning">{$MOD.LBL_YOU_CAN_NOT_USE_DISCOUNT_FREE_STANDARD}</label>
                        {/if}
                        </td>
                        <td width="20%" scope="col"><label>{$MOD.LBL_DISCOUNT_PERCENT} (4):<br>
                        <i>(4)=(5)/(3)</i>
                        </label></td>
                        <td width="35%">
                            <input class="input_readonly" autocomplete="off" type="text" name="discount_percent" id="discount_percent" value="{sugar_number_format var=$fields.discount_percent.value precision=2}" tabindex="0" size="4" maxlength="10" readonly>
                        </td>
                        </tr></tbody>
                        </table>',
                    ), 
                    1 =>
                    array (
                        'name' => 'discount_amount',        
                        'customLabel' => '{$MOD.LBL_DISCOUNT_AMOUNT} (5):',
                        'customCode' => '
                        <input class="currency input_readonly" type="text" name="discount_amount" id="discount_amount" size="20" maxlength="26" value="{sugar_number_format var=$fields.discount_amount.value}" title="{$MOD.LBL_DISCOUNT_AMOUNT}" tabindex="0"  style="font-weight: bold;" readonly>
                        ',
                    ),
                ),
                6 =>
                array (    
                    0 =>
                    array (
                        'customLabel' => '
                        {if $lisence_version <> "Free" && $lisence_version <> "Standard"}
                        <input class="button enrollment_btn" type="button" name="btn_add_sponsor" value="{$MOD.LBL_ADD_SPONSOR}" id="btn_add_sponsor">
                        {/if}',
                        'customCode' => '<table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="padding: 0px !important; width:40%">
                        {if $lisence_version == "Free" || $lisence_version == "Standard"}
                        <label class="warning">{$MOD.LBL_YOU_CAN_NOT_USE_VOUCHER_FREE_STANDARD}</label>
                        {/if}
                        </td>
                        <td width="20%" scope="col"><label>{$MOD.LBL_FINAL_SPONSOR_PERCENT} (6):<br>
                        <i>(6)=(7)/(3)</i>
                        </label></td>
                        <td width="35%">
                            <input class="input_readonly" autocomplete="off" type="text" name="final_sponsor_percent" id="final_sponsor_percent" value="{sugar_number_format var=$fields.final_sponsor_percent.value precision=2}" tabindex="0" size="4" maxlength="10" readonly>
                        </td>
                        </tr></tbody>
                        </table>',
                    ),  
                    1 =>array (
                        'name' => 'final_sponsor',       
                        'customLabel' => '{$MOD.LBL_FINAL_SPONSOR} (7):',
                        'customCode' => '
                        <input readonly size="20" maxlength="26" class="currency input_readonly" name="final_sponsor" type="text" id="final_sponsor" value="{sugar_number_format var=$fields.final_sponsor.value}" tabindex="0"  style="font-weight: bold;">
                        ',
                    ),
                ),            
                7 =>
                array (
                    0 => array(
                        'hideLabel' => true,
                        'customCode' => '
                        {include file="custom/modules/J_Payment/tpl/BookTemplate.tpl"}
                        '
                    ),
                    1 =>
                    array (
                        'name' => 'payment_amount',
                        'customLabel' => '<b>{$MOD.LBL_GRAND_TOTAL}:</b>',
                        'customCode' => '
                        <input class="currency input_readonly" type="hidden" name="total_after_discount" id="total_after_discount" size="20" maxlength="26" value="{sugar_number_format var=$fields.total_after_discount.value}" title="{$MOD.LBL_TOTAL_AFTER_DISCOUNT}" tabindex="0"  style="font-weight: bold;" readonly>
                        <input class="currency input_readonly" type="text" name="payment_amount" id="payment_amount" size="20" maxlength="26" value="{sugar_number_format var=$fields.payment_amount.value}" title="{$MOD.LBL_GRAND_TOTAL}" tabindex="0"  style="font-weight: bold; color: rgb(165, 42, 42);" readonly>
                        ',
                    ),                   
                ),                
            ),
            // Payment Moving
            'LBL_PAYMENT_MOVING' =>
            array (
                1 =>
                array (
                    0 => array (
                        'name' => 'contacts_j_payment_1_name',
                        'customLabel' => '{$MOD.LBL_STUDENT}: <span class="required">*</span>',
                    ),
                    1 => array (
                        'name' => 'move_to_center_name',
                    ),
                ),
                2 => array (
                    0 => array (
                        'name' => 'total_hours',
                        'customLabel' => '{if $team_type == "Junior"}
                        {$MOD.LBL_MOVING_HOURS}
                        {else}
                        {$MOD.LBL_MOVING_DAYS}
                        {/if} : <span class="required">*</span>',
                        'customCode' => '<input class="input_readonly" type="text" name="total_hours" id="total_hours" size="20" maxlength="26" value="{sugar_number_format var=$fields.total_hours.value precision=2}" title="{$MOD.LBL_MOVING_HOURS}" tabindex="0"  style="font-weight: bold; text-align:right; color: rgb(165, 42, 42);" readonly>
                        {$payment_type}
                        <input type="hidden" id="json_student_info" name="json_student_info">
                        <input type="hidden" id="json_payment_list" name="json_payment_list">
                        ',
                    ),
                    1 => array (
                        'name' => 'payment_amount',
                        'customLabel' => '{$MOD.LBL_MOVING_AMOUNT}: <span class="required">*</span>',
                        'customCode' => '<input class="currency input_readonly" type="text" name="payment_amount" id="payment_amount" size="20" maxlength="26" value="{sugar_number_format var=$fields.payment_amount.value}" title="{$MOD.LBL_MOVING_AMOUNT}" tabindex="0"  style="font-weight: bold;color: rgb(165, 42, 42);" readonly>',
                    ),
                ),
                3 =>
                array (
                    0 =>  array(
                        'name' => 'moving_tran_out_date',
                        'label' => 'LBL_MOVING_OUT_DATE',
                        'customCode' => '<span class="dateTime"><input class="date_input input_readonly" placeholder="dd/mm/yyyy" autocomplete="off" type="text" name="moving_tran_out_date" id="moving_tran_out_date" value="{$fields.moving_tran_out_date.value}" tabindex="0" size="11" maxlength="10" readonly></span>',
                    ),
                    1 =>  array(
                        'name' => 'moving_tran_in_date',
                        'label' => 'LBL_MOVING_IN_DATE',
                        'customCode' => '<span class="dateTime"><input class="date_input input_readonly" placeholder="dd/mm/yyyy" autocomplete="off" type="text" name="moving_tran_in_date" id="moving_tran_in_date" value="{$fields.moving_tran_in_date.value}" tabindex="0" size="11" maxlength="10" readonly></span>',
                    ),
                ),
                4 =>
                array (
                    0 =>  array (
                        'name' => 'description',
                        'label' => 'LBL_DESCRIPTION',
                        'displayParams' =>
                        array (
                            'required' => true,
                        ),
                    ),
                ),
                5 =>
                array (
                    0 =>  array (
                        'name' => 'assigned_user_name', 
                    ),
                ),
            ),
            //Panel Transfer
            'LBL_PAYMENT_TRANSFER' =>
            array (
                1 =>
                array (
                    0 => array (
                        'name' => 'contacts_j_payment_1_name',
                        'customLabel' => '{$MOD.LBL_STUDENT}: <span class="required">*</span>',
                    ),
                    1 => array (
                        'name' => 'transfer_to_student_name',
                        'displayParams' =>
                        array (
                            'field_to_name_array' =>
                            array (
                                'id' => 'transfer_to_student_id',
                                'name' => 'transfer_to_student_name',
                                'account_id' => 'student_corporate_id',
                                'account_name' => 'student_corporate_name',
                            ),
                            'required' => true,
                            'class' => 'sqsNoAutofill',
                        ),
                    ),
                ),
                2 => array (
                    0 => array (
                        'name' => 'total_hours',
                        'customLabel' => '
                        {if $team_type == "Junior"}
                        {$MOD.LBL_TRANSFER_HOURS}
                        {else}
                        {$MOD.LBL_TRANSFER_DAYS}
                        {/if}

                        : <span class="required">*</span>',
                        'customCode' => '<input class="input_readonly" type="text" name="total_hours" id="total_hours" size="20" maxlength="26" value="{sugar_number_format var=$fields.total_hours.value precision=2}" title="{$MOD.LBL_TRANSFER_HOURS}" tabindex="0"  style="font-weight: bold; text-align:right; color: rgb(165, 42, 42);" readonly>
                        {$payment_type}
                        <input type="hidden" id="json_student_info" name="json_student_info">
                        <input type="hidden" id="json_payment_list" name="json_payment_list">',
                    ),
                    1 => array (
                        'name' => 'payment_amount',
                        'customLabel' => '{$MOD.LBL_TRANSFER_AMOUNT}: <span class="required">*</span>',
                        'customCode' => '<input class="currency input_readonly" type="text" name="payment_amount" id="payment_amount" size="20" maxlength="26" value="{sugar_number_format var=$fields.payment_amount.value}" title="{$MOD.LBL_TRANSFER_AMOUNT}" tabindex="0"  style="font-weight: bold;color: rgb(165, 42, 42);" readonly>',
                    ),
                ),
                3 =>
                array (
                    0 =>  array(
                        'name' => 'moving_tran_out_date',
                        'label' => 'LBL_TRANSFER_OUT_DATE',
                        'customCode' => '<span class="dateTime"><input class="date_input input_readonly" placeholder="dd/mm/yyyy" autocomplete="off" type="text" name="moving_tran_out_date" id="moving_tran_out_date" value="{$fields.moving_tran_out_date.value}" tabindex="0" size="11" maxlength="10" readonly></span>',

                    ),
                    1 =>  array(
                        'name' => 'moving_tran_in_date',
                        'label' => 'LBL_TRANSFER_IN_DATE',
                        'customCode' => '<span class="dateTime"><input class="date_input input_readonly" placeholder="dd/mm/yyyy" autocomplete="off" type="text" name="moving_tran_in_date" id="moving_tran_in_date" value="{$fields.moving_tran_in_date.value}" tabindex="0" size="11" maxlength="10" readonly></span>',

                    ),
                ),
                4 =>
                array (
                    0 =>  array (
                        'name' => 'description',
                        'displayParams' =>
                        array (
                            'required' => true,
                        ),

                    ),
                ),
                5 =>
                array (
                    0 =>  array (
                        'name' => 'assigned_user_name',       
                    ),
                ),
            ),
            //Panel Transfer
            'LBL_PAYMENT_TRANSFER_FROM_AIMS' =>
            array (
                1 =>
                array (
                    0 => array (
                        'customLabel' => '{$MOD.LBL_TRANSFER_FROM} AIMS Center:',
                        'customCode' => '{html_options name="from_AIMS_center_id" id="from_AIMS_center_id" options=$from_AIMS_center_id}',
                        'displayParams' =>
                        array (
                            'required' => true,
                        ),
                    ),
                    1 => array (
                        'name' => 'contacts_j_payment_1_name',
                        'label' => 'LBL_TRANSFER_TO_STUDENT_NAME',
                        'displayParams' =>
                        array (
                            'required' => true,
                        ),
                    ),
                ),
                2 => array (
                    // 0 =>  '',
                    0 =>  array(
                        'name' => 'payment_date',
                        'label' => 'LBL_TRANSFER_IN_DATE',
                    ),
                    1 => array (
                        'name' => 'use_type',
                        'customLabel' => '{$MOD.LBL_TRANSFER_TYPE}:',
                        //                        'customCode' => '{html_options name="use_type" id="use_type" style="width: 100px;margin-left:10px;" options=$fields.use_type.options selected=$fields.use_type.value}
                        //                        <label class="total_hours" width="12.5%" style="background-color:#eee; color: #444; padding:.6em">{$MOD.LBL_TRANSFER_HOURS}: <span class="required">*</span></label>
                        //                        <input class="currency total_hours" type="text" name="total_hours" id="total_hours" size="5" maxlength="26" value="{sugar_number_format var=$fields.total_hours.value precision=2}" title="{$MOD.LBL_TRANSFER_HOURS}" tabindex="0">',
                    ),
                ),
                3 => array (
                    0 =>  '',
                    1 => array (
                        'name' => 'payment_amount',
                        'customLabel' => '{$MOD.LBL_TRANSFER_AMOUNT}: <span class="required">*</span>',
                        'customCode' => '<input class="currency" type="text" name="payment_amount" id="payment_amount" size="20" maxlength="26" value="{sugar_number_format var=$fields.payment_amount.value}" title="{$MOD.LBL_TRANSFER_AMOUNT}" tabindex="0"  style="font-weight: bold; color: rgb(165, 42, 42);">{$payment_type}',
                    ),
                ),
                4 => array (
                    0 =>  '',
                    1 => array (
                        'name' => 'total_hours',
                        'customLabel' => '{$MOD.LBL_TRANSFER_HOURS}: <span class="required">*</span>',
                        'customCode' => '<input type="text" name="total_hours" id="total_hours" size="5" maxlength="5" value="{sugar_number_format var=$fields.total_hours.value precision=2}" title="{$MOD.LBL_TRANSFER_HOURS}" tabindex="0"  style="text-align: right; color: rgb(165, 42, 42);">',
                    ),
                ),
                5 =>
                array (
                    0 =>  array (
                        'name' => 'description',
                        'displayParams' =>
                        array (
                            'rows' => 2,
                            'cols' => 60,
                            'required' => true,
                        ),
                    ),
                ),
                6 =>
                array (
                    0 =>  array (
                        'name' => 'assigned_user_name',      
                    ),
                ),
            ),
            //Panel Refund
            'LBL_PAYMENT_REFUND' =>
            array (
                1 =>
                array (
                    0 => array (
                        'name' => 'contacts_j_payment_1_name',
                        'customLabel' => '{$MOD.LBL_STUDENT}: <span class="required">*</span>',
                    ),
                ),
                2 => array (
                    0 => array (
                        'name' => 'payment_amount',
                        'customLabel' => '{$MOD.LBL_REFUND_AMOUNT}: ',
                        'customCode' => '<input class="currency" type="text" name="payment_amount" id="payment_amount" size="20" maxlength="26" value="{sugar_number_format var=$fields.payment_amount.value}" title="{$MOD.LBL_REFUND_AMOUNT}" tabindex="0"  style="font-weight: bold;color: rgb(165, 42, 42);" >',
                    ),

                    1 => array (
                        'name' => 'refund_revenue',
                        'customLabel' => '{$MOD.LBL_DROP_REVENUE}: ',
                        'customCode' => '<input type="text" name="refund_revenue" class="currency" id="refund_revenue" size="20" maxlength="26" value="{sugar_number_format var=$fields.refund_revenue.value}" title="{$MOD.LBL_REFUND_REVENUE}" tabindex="0"  style="font-weight: bold; text-align:right; color: rgb(165, 42, 42);" >
                        <input type="hidden" id="payment_type" name="payment_type" value={$fields.payment_type.value}>
                        <input type="hidden" id="json_student_info" name="json_student_info">
                        <input type="hidden" id="json_payment_list" name="json_payment_list">',
                    ),

                ),
                3 => array (
                    0 => array(
                        'hideLabel' => true,
                        'customCode' => '<img src="themes/RacerX/images/helpInline.png" class="paidAmountHelpTip">{$MOD.LBL_DESCRIPTION_REFUND_AMOUNT}'
                    ),
                    1 => array(
                        'hideLabel' => true,
                        'customCode' => '<img src="themes/RacerX/images/helpInline.png" class="paidAmountHelpTip">{$MOD.LBL_DESCRIPTION_ADMIN_CHARGE}'
                    ),
                ),
                4 =>
                array (
                    0 =>  array(
                        'name' => 'moving_tran_out_date',
                        'label' => 'LBL_REFUND_DATE',
                        'customCode' => '<span class="dateTime"><input class="date_input input_readonly" placeholder="dd/mm/yyyy" autocomplete="off" type="text" name="moving_tran_out_date" id="moving_tran_out_date" value="{$fields.moving_tran_out_date.value}" tabindex="0" size="11" maxlength="10" readonly></span>',
                    ),
                    1 =>
                    array (
                        'name' => 'uploadfile',
                        'displayParams' =>
                        array (
                            'onchangeSetFileNameTo' => 'document_name',
                        ),
                    ),
                ),
                5 =>
                array (
                    0 =>  array (
                        'name' => 'description',
                        'displayParams' =>
                        array (
                            'required' => true,
                        ),
                    ),
                ),
                6 =>
                array (
                    0 =>  array (
                        'name' => 'assigned_user_name',       
                    ),
                ),
            ),
            'LBL_PAYMENT_PLAN' => 
            array(  
                16 =>
                array (
                    0 =>
                    array (
                        'name' => 'is_corporate',
                    ),
                    1 =>
                    array (
                        'name' => 'number_of_payment',
                        'customLabel' => '{$MOD.LBL_SPLIT_PAYMENT}:',
                        'customCode' => '
                        {if $lisence_version <> "Free"}
                        {html_options name="number_of_payment" id="number_of_payment" options=$fields.number_of_payment.options selected=$fields.number_of_payment.value}
                        {else}
                        <label>&nbsp;&nbsp;1 &nbsp;&nbsp;&nbsp;({$MOD.LBL_LIMIT_BY_FREE_VERSION})</label>
                        <input type="hidden" name="number_of_payment" id="number_of_payment" value="1" />
                        {/if}
                        ',
                    ), 
                ),
                17 =>
                array (
                    0 =>
                    array (
                        'hideLabel' => true,
                        'customCode' => '{include file="custom/modules/J_Payment/tpl/is_corporate.tpl"}'
                    ),
                    1 =>
                    array (
                        'hideLabel' => true,                                             
                        'customCode' => '{include file="custom/modules/J_Payment/tpl/payment_detail.tpl"}'
                    ),  
                ),
            ),  
            'LBL_OTHER' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'description',
                        'label' => 'LBL_DESCRIPTION',
                        'displayParams' =>
                        array (
                            'rows' => 4,
                            'cols' => 60,
                        ),
                    ),                           
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'assigned_user_name',     
                    ),
                    1 =>
                    array (
                        'name' => 'team_name',
                        'displayParams' =>
                        array (
                            'display' => true,
                        ),
                    ),
                ),
            ),
        ),
    ),
);
