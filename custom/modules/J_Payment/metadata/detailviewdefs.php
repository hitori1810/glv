<?php
$module_name = 'J_Payment';
$viewdefs[$module_name] =
array (
    'DetailView' =>
    array (
        'templateMeta' =>
        array (
            'form' =>
            array (
                'buttons' =>
                array (
                    0 => 'EDIT',
                    1 =>
                    array (
                        'customCode' => '{$CUSTOM_DELETE}',
                    ),
                    2 =>
                    array (
                        'customCode' => '{$EXPORT_FROM_BUTTON} {$CUSTOM_BUTTON}',
                    ),
                    3 =>
                    array (
                        'customCode' => '{$BTN_UNDO}',
                    ),
                ),
                'hidden' =>
                array (
                    0 => '{include file="custom/modules/J_Payment/tpl/paymentTemplate.tpl"}',
                    1 => '                      
                    {include file="custom/modules/J_Payment/tpl/delayPayment.tpl"} 
                    <input type="hidden" id="team_type" type="team_type" value="{$team_type}"/>
                    ',
                    2 => '{include file="custom/modules/J_Payment/tpl/convert_payment.tpl"}',
                    3 => '<input type="hidden" name="is_corporate" id="is_corporate" value="{$fields.is_corporate.value}">',
                    4 => '<input type="hidden" name="payment_type" id="payment_type" value="{$fields.payment_type.value}">',
                    5 => '<input type="hidden" name="team_id" id="team_id" value="{$fields.team_id.value}">',
                    6 => '<input type="hidden" name="status" id="status" value="{$fields.status.value}">',
                    7 => '<input type="hidden" name="is_paid" id="is_paid" value="{$is_paid}">',
                    8 => '<input type="hidden" name="end_study" id="end_study" value="{$fields.end_study.value}">',
                    9 => '{include file="custom/modules/J_Payment/tpl/addToClassAdult.tpl"}',
                    10 => '{include file="custom/modules/J_Payment/tpl/export_invoice.tpl"}',
                ),
            ),
            'maxColumns' => '3',
            'widths' =>
            array (
                0 =>
                array (
                    'label' => '10',
                    'field' => '20',
                ),
                1 =>
                array (
                    'label' => '10',
                    'field' => '20',
                ),
                2 =>
                array (
                    'label' => '10',
                    'field' => '20',
                ),
            ),
            //'javascript' => '
            //            <link rel="stylesheet" type="text/css" href="{sugar_getjspath file=\'custom/include/javascripts/Bootstrap/bootstrap.min.css\'}">
            //            ',
            'useTabs' => false,
            'tabDefs' =>
            array (
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
                'LBL_PLACE_HOLDER' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PAYMENT_PT_BOOK' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_OTHER' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => true,
            'includes' =>
            array (
                0 =>
                array (
                    'file' => 'custom/modules/J_Payment/js/detail.js',
                ),
            ),
        ),
        'panels' =>
        array (
            //Payment Enrollment
            'LBL_ENROLLMENT' =>
            array (
                0 => array (
                    0 => array(
                        'name'=>'name',
                        'customCode' => '{$payment_id}',
                    ),
                    1 => array (                                         
                        'hideLabel' => true,
                    ),
                    2 =>
                    array (
                        'name' => 'sale_type',
                        'customCode' => '{$sale_typeQ}',
                    ),
                ),
                1 => array (
                    0 => array (
                        'name' => 'contacts_j_payment_1_name',
                        'customCode' => '{$student_link}'
                    ),
                    1 => array (
                        'name' => 'payment_type',
                    ),
                    2 =>
                    array (
                        'name' => 'sale_type_date',
                        'customCode' => '{$sale_type_dateQ}',
                    ),
                ),
                2 => array (
                    0 => array (
                        'label' => 'LBL_CLASSES_NAME',
                        'customCode' => '{$html_class}'
                    ),
                    1 =>
                    array (
                        'name' => 'kind_of_course_string',
                        'label' => 'LBL_KIND_OF_COURSE',
                    ),
                    2 => array (
                        'name' => 'payment_date',
                    ),

                ),
                3 => array (
                    0 => array (
                        'name' => 'start_study',
                        'label' => 'LBL_START_STUDY',
                    ), 
                    1 => array (
                        'name' => 'end_study',
                        'label' => 'LBL_END_STUDY',
                    ),  
                    2 =>
                    array (
                        'name' => 'payment_expired',
                        'customCode' => '{$payment_expiredQ}',
                    ),
                ),  
                5 => array (
                    0 => array (                        
                        'name' => 'sessions',
                        'customLabel' => '{$MOD.LBL_SESSIONS} (1):',
                    ),
                    1 => array (                        
                        'name' => 'tuition_hours',
                        'customLabel' => '{$MOD.LBL_TUITION_HOURS} (2):',
                    ),                    
                    2 => '',

                ),     
            ),
            'LBL_PRICING' =>
            array(
                0 => array (
                    0 => array(
                        'name'=>'j_coursefee_j_payment_1_name',  
                    ),
                    1 => array (               
                        'name' => 'tuition_fee',   
                        'customLabel' => '{$MOD.LBL_LIST_PRICE} (3):',    
                        'customCode' => '{$LIST_PRICE_PER_HOUR} /h'
                    ),
                    2 =>
                    array (
                        'name' => 'tuition_fee',   
                        'customLabel' => '{$MOD.LBL_TUITION_FEE} (4):',       
                        'customCode' => '{sugar_number_format var=$fields.amount_bef_discount.value } &nbsp;&nbsp;&nbsp; <i>(4)=(2)x(3)</i>'
                    ),
                ),
                1 => array (
                    0 => array(                              
                        'hideLabel' => true,
                    ),
                    1 => array (                
                        'name'=>'paid_hours', 
                        'customLabel' => '{$MOD.LBL_BALANCE_HOURS} (5):', 
                    ),
                    2 =>
                    array (                                       
                        'hideLabel' => true,       
                    ),
                ),
                2 => array (
                    0 => array(                              
                        'hideLabel' => true,
                    ),
                    1 => array (                
                        'name'=>'total_hours', 
                        'customLabel' => '{$MOD.LBL_TOTAL_HOURS} (6):', 
                        'customCode' => '{sugar_number_format var=$fields.total_hours.value } &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>(6)=(2)-(5)</i>'
                    ),
                    2 =>
                    array (                    
                        'name'=>'amount_bef_discount',     
                        'customLabel' => '{$MOD.LBL_AMOUNT_BEF_DISCOUNT} (7):',  
                        'customCode' => '{sugar_number_format var=$fields.amount_bef_discount.value }  &nbsp;&nbsp;&nbsp;<i>(7)=(6)x(3)</i>'
                    ),
                ),
                3 => array (
                    0 => array(                              
                        'hideLabel' => true,
                    ),
                    1 => array (                
                        'name'=>'discount_percent', 
                        'customLabel' => '{$MOD.LBL_DISCOUNT_PERCENT} (8):', 
                        'customCode' => '{sugar_number_format var=$fields.final_sponsor_percent.value }  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>(8)=(9)/(7)</i>'
                    ),
                    2 =>
                    array (                    
                        'name'=>'discount_amount',   
                        'customLabel' => '{$MOD.LBL_DISCOUNT_AMOUNT} (9):',    
                    ),
                ),
                4 => array (
                    0 => array(                              
                        'hideLabel' => true,
                    ),
                    1 => array (                
                        'name'=>'final_sponsor_percent', 
                        'customLabel' => '{$MOD.LBL_FINAL_SPONSOR_PERCENT} (10):', 
                        'customCode' => '{sugar_number_format var=$fields.final_sponsor_percent.value }  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>(10)=(11)/(7)</i>'
                    ),
                    2 =>
                    array (                    
                        'name'=>'final_sponsor',  
                        'customLabel' => '{$MOD.LBL_FINAL_SPONSOR} (11):',    
                    ),
                ),
                5 => array (
                    0 => array(                              
                        'hideLabel' => true,
                    ),
                    1 => array (                                        
                        'hideLabel' => true,
                    ),
                    2 =>
                    array (                    
                        'name'=>'total_after_discount', 
                        'customLabel' => '{$MOD.LBL_TOTAL_AFTER_DISCOUNT} (12):',     
                        'customCode' => '{sugar_number_format var=$fields.total_after_discount.value }  &nbsp;&nbsp;&nbsp;<i>(12)=(7)-(9)-(11)</i>'
                    ),
                ),
                6 => array (
                    0 => array(                              
                        'hideLabel' => true,
                    ),
                    1 => array (                                        
                        'hideLabel' => true,
                    ),
                    2 =>
                    array (                    
                        'name'=>'deposit_amount',   
                        'customLabel' => '{$MOD.LBL_DEPOSIT_AMOUNT} (13):',    
                    ),
                ),
                7 => array (
                    0 => array(                              
                        'hideLabel' => true,
                    ),
                    1 => array (                                        
                        'hideLabel' => true,
                    ),
                    2 =>
                    array (                    
                        'name'=>'payment_amount',  
                        'customLabel' => '{$MOD.LBL_GRAND_TOTAL} (14):',      
                        'customCode' => '{sugar_number_format var=$fields.payment_amount.value }  &nbsp;&nbsp;&nbsp;<i>(14)=(12)-(13)</i>'
                    ),
                ),
            ),   
            //Payment Place Holder
            'LBL_PLACE_HOLDER' =>
            array (
                0 => array (
                    0 => array(
                        'name'=>'name',
                        'customCode' => '{$payment_id}',
                    ),                                                     
                    1 => array (                                           
                        'hideLabel' => true,
                    ),
                    2 =>
                    array (
                        'name' => 'sale_type',
                        'customCode' => '{$sale_typeQ}',
                    ),
                ),
                1 => array (
                    0 => array (
                        'name' => 'contacts_j_payment_1_name',
                        'customCode' => '{$student_link}'
                    ),
                    1 => array (
                        'name' => 'payment_type',
                    ),
                    2 =>
                    array (
                        'name' => 'sale_type_date',
                        'customCode' => '{$sale_type_dateQ}',
                    ),
                ),
                2 => array(
                    0 => array (
                        'name' => 'kind_of_course',
                        'customCode'=>  '             
                        {$fields.kind_of_course.value}'
                    ), 
                    1 => 'payment_date',
                    2 =>
                    array(
                        'name' => 'payment_expired',
                        'label'   => 'LBL_PAYMENT_EXPIRED',
                        'customCode'   => '
                        {$payment_expiredQ}',
                    )
                ),
                
                
                
                3 => array (
                    0 => 'j_coursefee_j_payment_1_name',   
                    1 => array (
                        'name' => 'tuition_hours',
                        'label' => 'LBL_TUITION_HOURS',
                    ),
                    2 => 'amount_bef_discount'
                ),
                    

                4 => array (
                    0 => '', 
                    1 => 'discount_percent', 
                    2 => 'discount_amount',      
                ),
                5 => array (
                    0 => '',
                    1 => 'final_sponsor_percent',
                    2 => 'final_sponsor',   
                ),     
                6 => array (
                    0 => '',
                    1 => '',
                    2 => array (   
                        'name' => 'payment_amount',
                    ),          
                ),           
            ),

            //Payment Deposit
            'LBL_DEPOSIT' =>
            array (
                0 => array (
                    0 => array(
                        'name'=>'name',
                        'customCode' => '{$payment_id}',
                    ),
                    1 => array (                                             
                        'hideLabel' => true,
                    ),
                    2 =>
                    array (
                        'name' => 'sale_type',
                        'customCode' => '{$sale_typeQ}',
                    ),
                ),
                1 => array (
                    0 => array (
                        'name' => 'contacts_j_payment_1_name',
                        'customCode' => '{$student_link}'
                    ),
                    1 => array (
                        'name' => 'payment_type',
                    ),
                    2 =>
                    array (
                        'name' => 'sale_type_date',
                        'customCode' => '{$sale_type_dateQ}',
                    ),
                ),
                2 => array (
                    0 =>
                    array (                                       
                        'name'          => 'kind_of_course',
                        'customCode' => '{$fields.kind_of_course.value}'
                    ),  
                    1 => 'payment_date',
                    2 => array( 
                        'name' => 'payment_expired',
                        'customCode' => '{$payment_expiredQ}',                                         
                    ),

                ),
                3 => array (
                    0 => 'payment_amount',
                    1 => array (
                        'hideLabel' => 'true',
                    ),
                    2 => array(                              
                    ),
                ),       
            ),

            //Payment BookGift & Payment Placement Test
            'LBL_BOOK_PLACEMENT_TEST' => array (
                0 => array (
                    0 => array(
                        'name'=>'name',
                        'customCode' => '{$payment_id}',
                    ),
                ),
                1 => array (
                    0 => array (
                        'name' => 'contacts_j_payment_1_name',
                        'customCode' => '{$student_link}'
                    ),
                    1 => array (
                        'name' => 'payment_type',
                    ),
                    2 => 'payment_date',
                ),
                2 => array (
                    0 => array(
                        'customCode' => '
                        <table id="tblbook" style="width: 50%;" border="1" class="list view">
                        <thead>
                        <tr>
                        <th width="30%" style="text-align: center;">Name</th>
                        <th width="10%" style="text-align: center;">Unit</th>
                        <th width="20%" style="text-align: center;">Quantity</th>
                        <th width="25%" style="text-align: center;">Price</th>
                        <th width="25%" style="text-align: center;">Amount</th>
                        </tr>
                        </thead>
                        <tbody id="tbodybook">
                        {$bookList}
                        </tbody>
                        <tfoot>
                        <tr>
                        <td style="text-align: center;" colspan="2"><b>Total:</b></td>
                        <td style="text-align: center;"><b>{$total_book_quantity}</b></td><td></td>
                        <td style="text-align: center;"><b>{$total_book_amount}</b></td>
                        </tr>
                        </tfoot>
                        </table>',
                        'cuscomLabel'=> ''
                    ),
                ),         
                6 => array (
                    0 =>
                    array (
                        'customLabel'   => '{$MOD.LBL_PAYMENT_AMOUNT}:',
                        'name'          => 'payment_amount',
                    ),
                    1 => array(
                        'hideLabel' => 'true',
                    ),
                    2 => '',
                ),     
            ),

            //Payment Moving Out
            'LBL_MOVING' => array (
                0 => array (
                    0 => 'name',
                    1 => array (
                        'customLabel' => '{$PAYMENT_RELA_LABEL}:',
                        'customCode' => '{$PAYMENT_RELA}',
                    ),
                    2 => array (
                        'hideLabel' => true,
                    ),
                ),
                1 => array (
                    0 => array (
                        'name' => 'contacts_j_payment_1_name',
                        'customCode' => '{$student_link}'
                    ),
                    1 => 'payment_type',
                    2 => 'payment_date',
                ),
                2 => array (
                    0 =>
                    array (
                        'label'   => 'LBL_TOTAL_DAYS',
                        'name'          => 'total_hours',
                    ),
                    1 =>
                    array (
                        'customLabel'   => '{$MOD.LBL_PAYMENT_AMOUNT}:',
                        'name'          => 'payment_amount',
                    ),
                    2 =>
                    array (
                        'customCode'   => '{$use_type}',
                        'name'          => 'use_type',
                    ),
                ),
                3 => array (
                    0 =>
                    array (
                        'label'   => 'LBL_REMAIN_DAYS',
                        'name'          => 'remain_hours',
                    ),
                    1 => 'remain_amount',
                    2 =>
                    array (
                        'name' => 'payment_expired',
                        'customCode' => '{$payment_expiredQ}',
                    ),
                ),
            ),

            //Payment Transfer
            'LBL_TRANSFER' => array (
                0 => array (
                    0 => 'name',
                    1 => array (
                        'customLabel' => '{$PAYMENT_RELA_LABEL}',
                        'customCode' => '{$PAYMENT_RELA}',
                    ),
                    2 => 'payment_type',
                ),
                1 => array (
                    0 => array (
                        'name' => 'contacts_j_payment_1_name',
                        'customCode' => '{$student_link}'
                    ),
                    1 => array (
                        'customLabel' => '{$STUDENT_RELA_LABEL}',
                        'customCode' => '{$STUDENT_RELA}',
                    ),
                    2 => 'payment_date',
                ),
                2 => array (
                    0 =>
                    array (
                        'label'   => 'LBL_TOTAL_DAYS',
                        'name'          => 'total_hours',
                    ),
                    1 =>
                    array (
                        'customLabel'   => '{$MOD.LBL_PAYMENT_AMOUNT}:',
                        'name'          => 'payment_amount',
                    ),
                    2 =>
                    array (
                        'customCode'   => '{$use_type}',
                        'name'          => 'use_type',
                    ),
                ),
                3 => array (
                    0 =>
                    array (
                        'label'   => 'LBL_REMAIN_DAYS',
                        'name'          => 'remain_hours',
                    ),
                    1 => 'remain_amount',
                    2 =>
                    array (
                        'name' => 'payment_expired',
                        'customCode' => '{$payment_expiredQ}',
                    ),
                ),
            ),                               
            //Payment Refund
            'LBL_REFUND' => array (
                0 => array (
                    0 => 'name',
                    1 => array (
                        'hideLabel' => true,
                    ),
                    2 => array (
                        'hideLabel' => true,
                    ),
                ),
                1 => array (
                    0 => array (
                        'name' => 'contacts_j_payment_1_name',
                        'customCode' => '{$student_link}'
                    ),
                    1 => array (
                        'hideLabel' => true,
                    ),
                    2 => 'payment_type',
                ),
                2 => array (
                    0 => array (
                        'name' => 'payment_amount',
                        'label' => 'LBL_REFUND_AMOUNT',
                    ),
                    1 => array (
                        'hideLabel' => true,
                    ),
                    2 => array (
                        'name' => 'refund_revenue',
                        'label' => 'LBL_DROP_REVENUE',
                    ),
                ),
                3 => array (
                    0 => array (
                        'name' => 'payment_date',
                        'label' => 'LBL_REFUND_DATE',
                    ),
                    1 => array (
                        'hideLabel' => true,
                    ),
                    2 => 'uploadfile',

                ),
            ),
            // Payment Delay
            'LBL_DELAY' => array (
                0 => array (
                    0 => array(
                        'name'=>'name',
                        'customCode' => '{$payment_id}',
                    ),
                    1 => array (
                        'hideLabel' => true,
                    ),
                    2 => 'payment_type'
                ),
                1 => array (
                    0 => array (
                        'name' => 'contacts_j_payment_1_name',
                        'customCode' => '{$student_link}'
                    ),
                    1 =>
                    array (
                        'customCode'   => '{$use_type}',
                        'name'          => 'use_type',
                    ),
                    2 => 'payment_date',
                ),
                2 => array (
                    0 =>
                    array (
                        'label'   => 'LBL_TOTAL_DAYS',
                        'name'          => 'total_hours',
                    ),
                    1 =>
                    array (
                        'customLabel'   => '{$MOD.LBL_PAYMENT_AMOUNT}:',
                        'name'          => 'payment_amount',
                    ),
                    2 => array(
                        'name' => 'payment_expired',
                        'customCode' => '{$payment_expiredQ}',
                    ),
                ),
                3 => array (
                    0 =>
                    array (
                        'label'   => 'LBL_REMAIN_HOURS',
                        'name'          => 'remain_hours',
                    ),
                    1 => 'remain_amount',
                    2 => array (
                        'hideLabel' => true,
                    ),
                ),
            ),
            'LBL_PAYMENT_PLAN' =>
            array(         
                0 => array (                  
                    0 => array(
                        'name' => 'status_paid',             
                    ),
                    1 => array(
                        'label' => 'LBL_PAID_AMOUNT_2',
                        'customCode' => '{$PAID_AMOUNT}',
                    ),
                    2 => array(
                        'label' => 'LBL_UNPAID_AMOUNT',
                        'customCode' => '{$UNPAID_AMOUNT}',
                    ),
                ),    
                1 => array ( 
                    0 =>
                    array (
                        'customCode'   => '{$use_type}',
                        'name'          => 'use_type',
                    ),    
                    1 => array(
                        'name' =>'remain_amount',   
                    ),
                    2 =>
                    array (
                        'label'   => 'LBL_REMAIN_HOURS',
                        'name'          => 'remain_hours',
                        'hideIf' => '$fields.use_type.value == "Amount"'
                    ), 
                ),     
            ),
            //Desctiption & Assign To & Team
            'LBL_OTHER' => array (
                0 => array (
                    0 => 'description',
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 =>  array (
                        'hideLabel' => 'true',
                    ),
                ),
                1 => array (
                    0 =>
                    array (
                        'name' => 'assigned_user_name',
                        'customCode' => '{$assigned_user_idQ}',
                        'label'  => 'LBL_ASSIGNED_USER',
                    ),
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 => array (
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
                2 => array (
                    0 => 'team_name',
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 =>
                    array (
                        'name' => 'date_modified',
                        'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),  
            ),
        ),
    ),
);
