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

require_once('include/Expressions/Expression/Enum/EnumExpression.php');
/**
 * <b>getDropdownKeySet(String list_name)</b><br>
 * Returns a collection of the keys in the supplied dropdown list.<br/>
 * This list must be defined in the DropDown editor.<br/>
 * ex: <i>valueAt(2, getDropdownKeySet("my_list"))</i>
 */
class SugarDropDownExpression extends EnumExpression
{
	/**
	 * Returns the entire enumeration bare.
	 */
	function evaluate() {
		global $app_list_strings;
		$dd = $this->getParameters()->evaluate();;
		
		if (isset($app_list_strings[$dd]) && is_array($app_list_strings[$dd])) {
			return array_keys($app_list_strings[$dd]);
		}
		
		return array();
	}


	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
			var dd = this.getParameters().evaluate();
			var arr = SUGAR.language.get('app_list_strings', dd);
			var ret = [];
			if (arr == "undefined") return [];
          var keys = Object.keys(arr);
          var isNumIndex = true;
          var numberReg =/^\d+$/;
          for(var i in keys) {
              if ((typeof i == "string") && keys[i]!='' && !numberReg.test(keys[i])) {
                  isNumIndex = false;
                  break;
              }
          }
          if(isNumIndex) {
              return keys.sort();
          } else {
              for (var i in arr) {
                  if (typeof i == "string") {
                      ret[ret.length] = i;
                  }
              }
          }
			return ret;
EOQ;
	}


	/**
	 * Returns the exact number of parameters needed.
	 */
	static function getParamCount() {
		return 1;
	}
	
	/**
	 * All parameters have to be a string.
	 */
    static function getParameterTypes() {
		return AbstractExpression::$STRING_TYPE;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return array("getDropdownKeySet", "getDD");
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}

?>