<?php
$viewdefs['Contracts'] = 
array (
  'QuickCreate' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'javascript' => '<script type="text/javascript" language="javascript">
		function setvalue(source)  {ldelim} 
			src= new String(source.value);
			target=new String(source.form.name.value);
	
			if (target.length == 0)  {ldelim} 
				lastindex=src.lastIndexOf("\\"");
				if (lastindex == -1)  {ldelim} 
					lastindex=src.lastIndexOf("\\\\\\"");
				 {rdelim}  
				if (lastindex == -1)  {ldelim} 
					source.form.name.value=src;
					source.form.escaped_name.value = src;
				 {rdelim}  else  {ldelim} 
					source.form.name.value=src.substr(++lastindex, src.length);
					source.form.escaped_name.value = src.substr(lastindex, src.length);
				 {rdelim} 	
			 {rdelim} 			
		 {rdelim} 
	
		function set_expiration_notice_values(form)  {ldelim} 
			if (form.expiration_notice_flag.checked)  {ldelim} 
				form.expiration_notice_flag.value = "on";
				form.expiration_notice_date.value = "";
				form.expiration_notice_time.value = "";
				form.expiration_notice_date.readonly = true;
				form.expiration_notice_time.readonly = true;
				if(typeof(form.due_meridiem) != \'undefined\')  {ldelim} 
					form.due_meridiem.disabled = true;
				 {rdelim} 
				
			 {rdelim}  else  {ldelim} 
				form.expiration_notice_flag.value="off";
				form.expiration_notice_date.readOnly = false;
				form.expiration_notice_time.readOnly = false;
				
				if(typeof(form.due_meridiem) != \'undefined\')  {ldelim} 
					form.due_meridiem.disabled = false;
				 {rdelim} 
				
			 {rdelim} 
		 {rdelim} 
	</script>',
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_CONTRACT_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'lbl_contract_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'contract_id',
            'label' => 'LBL_CONTRACT_ID',
          ),
          1 => 'status',
        ),
        1 => 
        array (
          0 => 'name',
          1 => 'type',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'amount_per_student',
            'comment' => 'The overall value of the contract',
            'label' => 'LBL_AMOUNT_PER_STUDENT',
          ),
          1 => 
          array (
            'name' => 'customer_signed_date',
            'displayParams' => 
            array (
              'showFormats' => true,
            ),
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'hours_per_student',
            'label' => 'LBL_HOURS_PER_STUDENT',
          ),
          1 => 
          array (
            'name' => 'company_signed_date',
            'displayParams' => 
            array (
              'showFormats' => true,
            ),
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'total_contract_value',
            'displayParams' => 
            array (
              'size' => 15,
              'maxlength' => 25,
            ),
          ),
          1 => '',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'discount_amount',
            'comment' => 'Discount Amount',
            'label' => 'LBL_DISCOUNT_AMOUNT',
          ),
          1 => '',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'total_after_discount',
            'comment' => 'Total After Discount',
            'label' => 'LBL_TOTAL_AFTER_DISCOUNT',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'description',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'payment_amount_1',
            'comment' => 'Payment 1',
            'label' => 'LBL_PAYMENT_AMOUNT_1',
          ),
          1 => 
          array (
            'name' => 'payment_date_1',
            'comment' => '',
            'label' => 'LBL_PAYMENT_DATE_1',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'payment_amount_2',
            'comment' => 'Payment 2',
            'label' => 'LBL_PAYMENT_AMOUNT_2',
          ),
          1 => 
          array (
            'name' => 'payment_date_2',
            'comment' => '',
            'label' => 'LBL_PAYMENT_DATE_2',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'payment_amount_3',
            'comment' => 'Payment 3',
            'label' => 'LBL_PAYMENT_AMOUNT_3',
          ),
          1 => 
          array (
            'name' => 'payment_date_3',
            'comment' => '',
            'label' => 'LBL_PAYMENT_DATE_3',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'payment_amount_4',
            'comment' => 'Payment 4',
            'label' => 'LBL_PAYMENT_AMOUNT_4',
          ),
          1 => 
          array (
            'name' => 'payment_date_4',
            'comment' => '',
            'label' => 'LBL_PAYMENT_DATE_4',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'payment_amount_5',
            'comment' => 'Payment 5',
            'label' => 'LBL_PAYMENT_AMOUNT_5',
          ),
          1 => 
          array (
            'name' => 'payment_date_5',
            'comment' => '',
            'label' => 'LBL_PAYMENT_DATE_5',
          ),
        ),
      ),
    ),
  ),
);
