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

 * Description:
 ********************************************************************************/






Class ScalarFormat {
	
	var $interval_start;
	var $current_interval;
	var $interval;	
	
	function format_scalar($scalar, $scalar_type, $scalar_value){
		
		$split_query = preg_split('{{sc}}', $scalar_value);
		
		if(isset($split_query[1]) && is_numeric($split_query[1])){
			$this->interval_start = $split_query[1];
		} else {
			$this->interval_start = 0;

		}	
		if(isset($split_query[2]) && is_numeric($split_query[2])){
			$this->current_interval = $split_query[2];
		} else {
			$this->current_interval = 0;
		}
		
		$this->interval = $this->interval_start + $this->current_interval;
		
		if($scalar=="Year"){
			$display = $this->format_year($scalar_type);
		}
		
		if($scalar=="Quarter"){
			$display = $this->format_quarter($scalar_type);
		}
		
		if($scalar=="Month"){
			$display = $this->format_month($scalar_type);
		}
		
		if($scalar=="Week"){
			$display = $this->format_week($scalar_type);
		}
		
		if($scalar=="Day"){
			$display = $this->format_day($scalar_type);
		}
		
		return $display;
		
	//end function format scalar	
	}		
	
	
	function format_year($scalar_type){
		
		
		$scalar_unixstamp  = mktime(0, 0, 0, date("m"),  date("d"),  date("Y")+($this->interval));
		$scalar_display = date("Y", $scalar_unixstamp);
		
		return $scalar_display;

	//end function format_year;	
	}

	function format_quarter($scalar_type){
		
		
		$scalar_unixstamp  = mktime(0, 0, 0, date("m")+($this->interval*3),  date("d"),  date("Y"));
		
		//figure out what quarter this is in
		$month_number = date("n", $scalar_unixstamp);
		
		if($month_number<=3) $quarter_value = "Q1";
		if($month_number<=6 && $month_number>3) $quarter_value = "Q2";
		if($month_number<=9 && $month_number>6) $quarter_value = "Q3";
		if($month_number<=12 && $month_number>9) $quarter_value = "Q4";
		
		$scalar_display = date("Y", $scalar_unixstamp);
		$scalar_display = $quarter_value." ".$scalar_display;
		
		return $scalar_display;
			
	//end function format_year;	
	}	
	
	function format_month($scalar_type){
		
		
		$scalar_unixstamp  = mktime(0, 0, 0, date("m")+($this->interval),  date("d"),  date("Y"));
		$scalar_display = date("M Y", $scalar_unixstamp);
		
		
		//F would be a full representation of the Month
		//this is where the concept of Scalar Type comes into play.
		
		return $scalar_display;
			
	//end function format_year;	
	}	

	function format_week($scalar_type){
		
		$scalar_unixstamp  = mktime(0, 0, 0, date("m"),  date("d")+($this->interval*7),  date("Y"));
		
		$day_of_week = date("w", $scalar_unixstamp);

		$start_stamp = mktime(0, 0, 0, date("m", $scalar_unixstamp),  date("d", $scalar_unixstamp)-($day_of_week),  date("Y", $scalar_unixstamp));

		$scalar_display = "Week of: ".date("M jS, Y", $start_stamp);
		
		return $scalar_display;
			
	//end function format_year;	
	}

	function format_day($scalar_type){
		
		
		$scalar_unixstamp  = mktime(0, 0, 0, date("m"),  date("d")+($this->interval),  date("Y"));
		$scalar_display = date("D M jS, Y", $scalar_unixstamp);

		return $scalar_display;
			
	//end function format_year;	
	}



//end class ScalarFormat
}













?>