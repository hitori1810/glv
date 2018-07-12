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

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
 require_once('include/SugarObjects/templates/basic/Basic.php');
 class Sale extends Basic{

    /**
     * This is a depreciated method, please start using __construct() as this method will be removed in a future version
     *
     * @see __construct
     * @deprecated
     */
    public function Sale()
    {
        self::__construct();
    }
 	public function __construct(){
 		parent::__construct();

 	}
 	
 	function create_new_list_query($order_by, $where,$filter=array(),$params=array(), $show_deleted = 0,$join_type='', $return_array = false,$parentbean=null, $singleSelect = false)
 	{
 		//Ensure that amount is always on list view queries if amount_usdollar is as well.
 		if (!empty($filter) && isset($filter['amount_usdollar']) && !isset($filter['amount']))
 		{
 			$filter['amount'] = true;
 		}
 		return parent::create_new_list_query($order_by, $where, $filter, $params, $show_deleted, $join_type, $return_array, $parentbean, $singleSelect);
 	}
 	
 	function fill_in_additional_list_fields()
	{
    	parent::fill_in_additional_list_fields();
    		
		//Ensure that the amount_usdollar field is not null.
		if (empty($this->amount_usdollar) && !empty($this->amount))
		{
			$this->amount_usdollar = $this->amount;
		}
	}
 	
 	function fill_in_additional_detail_fields()
	{		
		parent::fill_in_additional_detail_fields();
		//Ensure that the amount_usdollar field is not null.
		if (empty($this->amount_usdollar) && !empty($this->amount))
		{
			$this->amount_usdollar = $this->amount;
		}
	}

 	
 	function save($check_notify = FALSE) {
 		//"amount_usdollar" is really amount_basecurrency. We need to save a copy of the amount in the base currency.
		if(isset($this->amount) && !number_empty($this->amount)){
            if (!number_empty($this->currency_id))
			{
                $currency = new Currency();
                $currency->retrieve($this->currency_id);
                $this->amount_usdollar = $currency->convertToDollar($this->amount);
			}
			else 
			{
			$this->amount_usdollar = $this->amount;
			}
		}
		
		return parent::save($check_notify);
 	}
 }
