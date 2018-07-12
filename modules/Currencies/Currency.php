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

/**
 * Currency
 *
 * This is the Currency module for obtaining and manipulating
 * currency objects. Note that a lot of currency utility functions
 * such as conversion and formatting are now implemented in the
 * SugarCurrency utility class.
 *
 */
class Currency extends SugarBean
{
	// Stored fields
	var $id;
	var $iso4217;
	var $name;
	var $status;
	var $conversion_rate;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $symbol;
	var $hide = '';
	var $unhide = '';
	var $field_name_map;

	var $table_name = "currencies";
	var $object_name = "Currency";
	var $module_dir = "Currencies";
	var $new_schema = true;

	var $disable_num_format = true;

    /**
     * This is a depreciated method, please start using __construct() as this method will be removed in a future version
     *
     * @see __construct
     * @deprecated
     */
    public function Currency()
    {
        self::__construct();
    }


    /**
     * class constructor
     *
     * @access public
     */
    public function __construct() {
		parent::__construct();
		global $app_strings, $current_user, $sugar_config, $locale;
		$this->field_defs['hide'] = array('name'=>'hide', 'source'=>'non-db', 'type'=>'varchar','len'=>25);
		$this->field_defs['unhide'] = array('name'=>'unhide', 'source'=>'non-db', 'type'=>'varchar','len'=>25);
		$this->disable_row_level_security =true;
	}

    /**
     * convertToDollar
     *
     * This method accepts a currency amount and converts it to the US Dollar amount
     * This method is deprecated and has been moved to SugarCurrency::convertAmountToBase()
     *
     * @deprecated
     * @param  $amount    amount to convert to US Dollars
     * @param  $precision rounding precision scale
     * @return float      currency value in US Dollars from conversion
     */
	function convertToDollar($amount, $precision = 6) {
		return round(($amount / $this->conversion_rate), $precision);
	}

    /**
     * convertFromDollar
     *
     * This method accepts a US Dollar amount and returns a currency amount
     * with the conversion rate applied to it.
     * This method is deprecated and has been moved to SugarCurrency::convertAmountFromBase()
     *
     * @deprecated
     * @param  $amount    currency amount in US Dollars
     * @param  $precision rounding precision scale
     * @return float      currency value from US Dollar conversion
     */
	function convertFromDollar($amount, $precision = 6){
		return round(($amount * $this->conversion_rate), $precision);
	}

    /**
     * getDefaultCurrencyName
     *
     * Returns the default currency name as defined in application
     *
     * @return string value of default currency name
     */
	function getDefaultCurrencyName(){
		global $sugar_config;
		return isset($sugar_config['default_currency_name'])?$sugar_config['default_currency_name']:'';
	}

    /**
     * getDefaultCurrencySymbol
     *
     * Returns the default currency symbol in application
     *
     * @return string value of default currency symbol (e.g. $)
     */
	function getDefaultCurrencySymbol(){
		global $sugar_config;
		return isset($sugar_config['default_currency_symbol'])?$sugar_config['default_currency_symbol']:'';
	}

    /**
     * getDefaultISO4217
     *
     * Returns the default ISO 4217 standard currency code value
     *
     * @return string value for the ISO 4217 standard code (e.g. EUR)
     */
	function getDefaultISO4217(){
		global $sugar_config;
		return isset($sugar_config['default_currency_iso4217'])?$sugar_config['default_currency_iso4217']:'';
	}

    /**
     * retrieveIDBySymbol
     *
     * Returns the id value for given currency symbol in currencies table
     * and currency entry for symbol is not set to deleted.
     *
     * @param  $symbol currency symbol
     * @return string  id value for symbol defined in currencies table,
     *                 blank value for nothing found
     */
	function retrieveIDBySymbol($symbol) {
	 	$query = sprintf("SELECT id FROM currencies WHERE symbol='%s' AND deleted=0",
             $this->db->quote($symbol)
         );
	 	$result = $this->db->query($query);
	 	if($result){
	 	  $row = $this->db->fetchByAssoc($result);
	 	  if($row){
	 	     return $row['id'];
	 	  }
	 	}

	 	return '';
	 }

    /**
     * retrieveIDByISO
     *
     * Returns the id value for given currency iso4217 in Currencies table
     * and currency entry for ISO is not set to deleted.
     *
     * @param  $ISO   iso4217 value
     * @return string id value for symbol defined in currencies table,
     *                blank value for nothing found
     */
    function retrieveIDByISO($ISO) {
        $query = sprintf("SELECT id FROM currencies WHERE iso4217='%s' AND deleted=0",
            $this->db->quote($ISO)
        );
        $result = $this->db->query($query);
        if($result){
            $row = $this->db->fetchByAssoc($result);
            if($row){
                return $row['id'];
            }
        }

        return '';
    }


    /**
     * list_view_parse_additional_sections
     *
     * @return object $list_form with merged currency id
     */
    function list_view_parse_additional_sections(&$list_form) {
		global $isMerge;

		if(isset($isMerge) && $isMerge && $this->id != '-99'){
		    $list_form->assign('PREROW', '<input name="mergecur[]" type="checkbox" value="'.$this->id.'">');
		}
		return $list_form;
	}

    /**
     * retrieveIDByName
     *
     * Returns the id value for given currency name in Currencies table
     * and currency entry for name is not set to deleted.
     *
     * @param  $name  currency name
     * @return string id value for symbol defined in currencies table,
     *                blank value for nothing found
     */
    function retrieveIDByName($name) {
	 	$query = sprintf("select id from currencies where name='%s' and deleted=0",
             $this->db->quote($name)
         );
	 	$result = $this->db->query($query);
	 	if($result){
	 	$row = $this->db->fetchByAssoc($result);
	 	if($row){
	 		return $row['id'];
	 	}
	 	}
	 	return '';
	}

    /**
     * retrieve_id_by_name
     *
     * deprecated, see retrieveIDByName
     * @deprecated
     */
    function retrieve_id_by_name($name) {
        $this->retrieveIDByName($name);
    }

    /**
     * retrieve
     *
     * returns currency object for given id, or base if not found
     *
     * @param  $id      currency id
     * @param  $encode  boolean
     * @param  $deleted boolean
     * @return object   currency object, base currency if id is not found
     */
    function retrieve($id, $encode = true, $deleted = true){
     	if($id == '-99'){
     		$this->name = 	$this->getDefaultCurrencyName();
     		$this->symbol = $this->getDefaultCurrencySymbol();
     		$this->id = '-99';
     		$this->conversion_rate = 1;
     		$this->iso4217 = $this->getDefaultISO4217();
     		$this->deleted = 0;
     		$this->status = 'Active';
     		$this->hide = '<!--';
     		$this->unhide = '-->';
     	}else{
     		parent::retrieve($id, $encode, $deleted);
     	}
     	if(!isset($this->name) || $this->deleted == 1){
     		$this->name = 	$this->getDefaultCurrencyName();
     		$this->symbol = $this->getDefaultCurrencySymbol();
     		$this->conversion_rate = 1;
     		$this->iso4217 = $this->getDefaultISO4217();
     		$this->id = '-99';
     		$this->deleted = 0;
     		$this->status = 'Active';
     		$this->hide = '<!--';
     		$this->unhide = '-->';
     	}
        return $this;
     }

    /**
     * Method for returning the currency symbol, must return chr(2) for the € symbol
     * to display correctly in pdfs
     * Parameters:
     * 	none
     * Returns:
     * 	$symbol otherwise chr(2) for euro symbol
     */
     function getPdfCurrencySymbol() {
     	if($this->symbol == '&#8364;' || $this->symbol == '€')
     		return chr(2);
     	return $this->symbol;
     }
	function get_list_view_data() {
		$this->conversion_rate = format_number($this->conversion_rate, 10, 10);
		$data = parent::get_list_view_data();
		return $data;
	}
    function save($check_notify = FALSE) {
        sugar_cache_clear('currency_list');

        // The per-module cache doesn't need to be cleared here
        MetaDataManager::clearAPICache(false);

        return parent::save($check_notify);
    }
} // end currency class

/**
 * CurrencyWithISO
 * This is an extension of the Currency class
 * requiring the ISO as the currency identifier
 *
 * @param $ISO the ISO of the currency
 */
class CurrencyWithISO extends Currency
{
    public function __construct($ISO)
    {
        parent::__construct();
        if(empty($ISO) || strlen($ISO) !== 3) {
            throw new Exception('Invalid ISO: ' . $ISO);
        }
        $currency_id = $this->retrieveIDByISO($ISO);
        if(empty($currency_id)) {
            throw new Exception('No currency found for ISO: ' . $ISO);
        }
        $this->retrieve($currency_id);
    }
}

/**
 * CurrencyWithID
 * This is an extension of the Currency class
 * requiring the currency_id as the currency identifier
 *
 * @param $currency_id the currency_id of the currency
 */
class CurrencyWithID extends Currency
{
    public function __construct($currency_id)
    {
        parent::__construct();
        if(empty($currency_id)) {
            throw new Exception('Invalid Currency ID: ' . $currency_id);
        }
        $this->retrieve($currency_id);
    }
}


/**
 * currency_format_number
 *
 * This method is a wrapper designed exclusively for formatting currency values
 * with the assumption that the method caller wants a currency formatted value
 * matching his/her user preferences(if set) or the system configuration defaults
 *(if user preferences are not defined).
 *
 * @param $amount The amount to be formatted
 * @param $params Optional parameters(see @format_number)
 * @return String representation of amount with formatting applied
 */
function currency_format_number($amount, $params = array()) {

    global $locale;
    if(isset($params['round']) && is_int($params['round'])){
	    $real_round = $params['round'];
    }else{
        $real_round = 0;
    }
	if(isset($params['decimals']) && is_int($params['decimals'])){
        $real_decimals = $params['decimals'];
	}else{
	    $real_decimals = 0;
	}
	$real_round = $real_round == '' ? 0 : $real_round;
	$real_decimals = $real_decimals == '' ? 0 : $real_decimals;

	$showCurrencySymbol = $locale->getPrecedentPreference('default_currency_symbol') != '' ? true : false;
	if($showCurrencySymbol && !isset($params['currency_symbol'])) {
	   $params["currency_symbol"] = true;
	}
	return format_number($amount, $real_round, $real_decimals, $params);

}

/**
 * format_number(deprecated)
 *
 * This method accepts an amount and formats it given the user's preferences.
 * Should the values set in the user preferences be invalid then it will
 * apply the system wide Sugar configuration values.  Calls to
 * getPrecendentPreference() method in Localization.php are made that
 * handle this logic.
 *
 * Going forward with Sugar 4.5.0e+ implementations, users of this class should
 * simple call this function with $amount parameter and leave it to the
 * class to locate and apply the appropriate formatting.
 *
 * One of the problems is that there is considerable legacy code that is using
 * this method for non currency formatting.  In other words, the format_number
 * method may be called to just display a number like 1,000 formatted appropriately.
 *
 * Also, issues about responsibilities arise.  Currently the callers of this function
 * are responsible for passing in the appropriate decimal and number rounding digits
 * as well as parameters to control displaying the currency symbol or not.
 *
 * @param $amount The currency amount to apply formatting to
 * @param $round Integer value for number of places to round to
 * @param $decimals Integer value for number of decimals to round to
 * @param $params Array of additional parameter values
 *
 *
 * The following are passed in as an array of params:
 *        boolean $params['currency_symbol'] - true to display currency symbol
 *        boolean $params['convert'] - true to convert from USD dollar
 *        boolean $params['percentage'] - true to display % sign
 *        boolean $params['symbol_space'] - true to have space between currency symbol and amount
 *        String  $params['symbol_override'] - string to over default currency symbol
 *        String  $params['type'] - pass in 'pdf' for pdf currency symbol conversion
 *        String  $params['currency_id'] - currency_id to retreive, defaults to current user
 *        String  $params['human'] - formatting that truncates the first thousands and appends "k"
 * @return String formatted currency value
 * @see include/Localization/Localization.php
 */
function format_number($amount, $round = null, $decimals = null, $params = array()) {
	global $app_strings, $current_user, $sugar_config, $locale;
	static $current_users_currency = null;
	static $last_override_currency = null;
	static $override_currency_id = null;
	static $currency;

	$seps = get_number_seperators();
	$num_grp_sep = $seps[0];
	$dec_sep = $seps[1];

	// cn: bug 8522 - sig digits not honored in pdfs
	if(is_null($decimals)) {
		$decimals = $locale->getPrecision();
	}
	if(is_null($round)) {
		$round = $locale->getPrecision();
	}

	// only create a currency object if we need it
	if((!empty($params['currency_symbol']) && $params['currency_symbol']) ||
	  (!empty($params['convert']) && $params['convert']) ||
	  (!empty($params['currency_id']))) {
	   		// if we have an override currency_id
	   		if(!empty($params['currency_id'])) {
	   			if($override_currency_id != $params['currency_id']) {
		   			$override_currency_id = $params['currency_id'];
		   			$currency = new Currency();
		   			$currency->retrieve($override_currency_id);
		   			$last_override_currency = $currency;
	   			} else {
	   				$currency = $last_override_currency;
	   			}

	   		} elseif(!isset($current_users_currency)) { // else use current user's
				$current_users_currency = new Currency();
				if($current_user->getPreference('currency')) $current_users_currency->retrieve($current_user->getPreference('currency'));
				else $current_users_currency->retrieve('-99'); // use default if none set
				$currency = $current_users_currency;
			}
	}
	if(!empty($params['convert']) && $params['convert']) {
		$amount = $currency->convertFromDollar($amount, 6);
	}

	if(!empty($params['currency_symbol']) && $params['currency_symbol']) {
		if(!empty($params['symbol_override'])) {
			$symbol = $params['symbol_override'];
		}
		elseif(!empty($params['type']) && $params['type'] == 'pdf') {
			$symbol = $currency->getPdfCurrencySymbol();
			$symbol_space = false;
		} else {
			if(empty($currency->symbol))
				$symbol = $currency->getDefaultCurrencySymbol();
			else
				$symbol = $currency->symbol;
			$symbol_space = true;
		}
	} else {
		$symbol = '';
	}

	if(isset($params['charset_convert'])) {
		$symbol = $locale->translateCharset($symbol, 'UTF-8', $locale->getExportCharset());
	}

	if(empty($params['human'])) {
	   $amount = number_format(round($amount, $round), $decimals, $dec_sep, $num_grp_sep);
	   $amount = format_place_symbol($amount, $symbol,(empty($params['symbol_space']) ? false : true));
	} else {
		// If amount is more greater than a thousand(positive or negative)
	    if(strpos($amount, '.') > 0) {
	       $checkAmount = strlen(substr($amount, 0, strpos($amount, '.')));
	    }

		if($checkAmount >= 1000 || $checkAmount <= -1000) {
			$amount = round(($amount / 1000), 0);
			$amount = number_format($amount, 0, $dec_sep, $num_grp_sep); // add for SI bug 52498
			$amount = $amount . 'k';
			$amount = format_place_symbol($amount, $symbol,(empty($params['symbol_space']) ? false : true));
		} else {
			$amount = format_place_symbol($amount, $symbol,(empty($params['symbol_space']) ? false : true));
		}
	}

	if(!empty($params['percentage']) && $params['percentage']) $amount .= $app_strings['LBL_PERCENTAGE_SYMBOL'];
	return $amount;

} //end function format_number



function format_place_symbol($amount, $symbol, $symbol_space) {
	if($symbol != '') {
		if($symbol_space == true) {
			$amount = $symbol . '&nbsp;' . $amount;
		} else {
			$amount = $symbol . $amount;
		}
	}
	return $amount;
}

function unformat_number($string) {
	static $currency = null;
	if(!isset($currency)) {
		global $current_user;
		$currency = new Currency();
		if(!empty($current_user->id)){
			if($current_user->getPreference('currency')){
				$currency->retrieve($current_user->getPreference('currency'));
			}
			else{
				$currency->retrieve('-99'); // use default if none set
	}
		}else{
			$currency->retrieve('-99'); // use default if none set
		}
	}

	$seps = get_number_seperators();
	// remove num_grp_sep and replace decimal separator with decimal
	$string = trim(str_replace(array($seps[0], $seps[1], $currency->symbol), array('', '.', ''), $string));
    if(preg_match('/^[+-]?\d(\.\d+)?[Ee]([+-]?\d+)?$/', $string)) $string = sprintf("%.0f", $string);//for scientific number format. After round(), we may get this number type.
    preg_match('/[\-\+]?[0-9\.]*/', $string, $string);

    $out_number = trim($string[0]);
    if ( $out_number == '' ) {
        return '';
    } else {
        return (float)$out_number;
    }
}

// deprecated use format_number() above
function format_money($amount, $for_display = TRUE) {
	// This function formats an amount for display.
	// Later on, this should be converted to use proper thousand and decimal seperators
	// Currently, it stays closer to the existing format, and just rounds to two decimal points
	if(isset($amount)) {
		if($for_display) {
			return sprintf("%0.02f",$amount);
		} else {
			// If it's an editable field, don't use a thousand seperator.
			// Or perhaps we will want to, but it doesn't matter right now.
			return sprintf("%0.02f",$amount);
		}
	} else {
		return;
	}
}

/**
 * Returns user/system preference for number grouping separator character(default ",") and the decimal separator
 *(default ".").  Special case: when num_grp_sep is ".", it will return NULL as the num_grp_sep.
 * @return array Two element array, first item is num_grp_sep, 2nd item is dec_sep
 */
function get_number_seperators($reset_sep = false)
{
	global $current_user, $sugar_config;

	static $dec_sep = null;
	static $num_grp_sep = null;

    // This is typically only used during unit-tests
    // TODO: refactor this. unit tests should not have static dependencies
	if ($reset_sep)
	{
        $dec_sep = $num_grp_sep = null;
    }

	if ($dec_sep == null)
	{
		$dec_sep = $sugar_config['default_decimal_seperator'];
		if (!empty($current_user->id))
		{
			$user_dec_sep = $current_user->getPreference('dec_sep');
			$dec_sep = (empty($user_dec_sep) ? $sugar_config['default_decimal_seperator'] : $user_dec_sep);
		}
	}

	if ($num_grp_sep == null)
	{
		$num_grp_sep = $sugar_config['default_number_grouping_seperator'];
		if (!empty($current_user->id))
		{
 			$user_num_grp_sep = $current_user->getPreference('num_grp_sep');
			$num_grp_sep = (empty($user_num_grp_sep) ? $sugar_config['default_number_grouping_seperator'] : $user_num_grp_sep);
		}
	}

	return array($num_grp_sep, $dec_sep);
}

/**
 * toString
 *
 * Utility function to print out some information about Currency instance.
 */
function toString($echo = true) {
	$s = "\$m_currency_round=$m_currency_round \n" .
         "\$m_currency_decimal=$m_currency_decimal \n" .
         "\$m_currency_symbol=$m_currency_symbol \n" .
         "\$m_currency_iso=$m_currency_iso \n" .
         "\$m_currency_name=$m_currency_name \n";

    if($echo) {
       echo $s;
    }

    return $s;
}

function getCurrencyDropDown($focus, $field='currency_id', $value='', $view='DetailView'){
    $view = ucfirst($view);
	if($view == 'EditView' || $view == 'MassUpdate' || $view == 'QuickCreate' || $view == 'ConvertLead'){
        if ( isset($_REQUEST[$field]) && !empty($_REQUEST[$field]) ) {
            $value = $_REQUEST[$field];
	    } elseif ( empty($focus->id) ) {
            $value = $GLOBALS['current_user']->getPreference('currency');
            if ( empty($value) ) {
                // -99 is the system default currency
                $value = -99;
            }
        }
		require_once('modules/Currencies/ListCurrency.php');
		$currency_fields = array();
		//Bug 18276 - Fix for php 5.1.6
		$defs=$focus->field_defs;
		//
		foreach($defs as $name=>$key){
			if($key['type'] == 'currency'){
				$currency_fields[]= $name;
			}
		}
		$currency = new ListCurrency();
        $selectCurrency = $currency->getSelectOptions($value);

		$currency->setCurrencyFields($currency_fields);
		$html = '<select name="';
		// If it's a lead conversion (ConvertLead view), add the module_name before the $field
		if ($view == "ConvertLead") {
			$html .= $focus->module_name;
		}
		$html .= $field. '" id="' . $field  . '_select" ';
		if($view != 'MassUpdate')
			$html .= 'onchange="CurrencyConvertAll(this.form);"';
		$html .= '>'. $selectCurrency . '</select>';
		if($view != 'MassUpdate')
			$html .= $currency->getJavascript();
		return $html;
	}else{

		$currency = new Currency();
		$currency->retrieve($value);
		return $currency->name;
	}

}

function getCurrencyNameDropDown($focus, $field='currency_name', $value='', $view='DetailView')
{
    if($view == 'EditView' || $view == 'MassUpdate' || $view == 'QuickCreate'){
		require_once('modules/Currencies/ListCurrency.php');
		$currency_fields = array();
		//Bug 18276 - Fix for php 5.1.6
		$defs=$focus->field_defs;
		//
		foreach($defs as $name=>$key){
			if($key['type'] == 'currency'){
				$currency_fields[]= $name;
			}
		}
		$currency = new ListCurrency();
        $currency->lookupCurrencies();
        $listitems = array();
        foreach ( $currency->list as $item )
            $listitems[$item->name] = $item->name;
        return '<select name="'.$field.'" id="'.$field.'" />'.
            get_select_options_with_id($listitems,$value).'</select>';
	}else{

		$currency = new Currency();
        if ( isset($focus->currency_id) ) {
            $currency_id = $focus->currency_id;
        } else {
            $currency_id = -99;
        }
		$currency->retrieve($currency_id);
		return $currency->name;
	}
}

function getCurrencySymbolDropDown($focus, $field='currency_name', $value='', $view='DetailView')
{
    if($view == 'EditView' || $view == 'MassUpdate' || $view == 'QuickCreate'){
		require_once('modules/Currencies/ListCurrency.php');
		$currency_fields = array();
		//Bug 18276 - Fix for php 5.1.6
		$defs=$focus->field_defs;
		//
		foreach($defs as $name=>$key){
			if($key['type'] == 'currency'){
				$currency_fields[]= $name;
			}
		}
		$currency = new ListCurrency();
        $currency->lookupCurrencies();
        $listitems = array();
        foreach ( $currency->list as $item )
            $listitems[$item->symbol] = $item->symbol;
        return '<select name="'.$field.'" id="'.$field.'" />'.
            get_select_options_with_id($listitems,$value).'</select>';
	}else{

		$currency = new Currency();
        if ( isset($focus->currency_id) ) {
            $currency_id = $focus->currency_id;
        } else {
            $currency_id = -99;
        }
		$currency->retrieve($currency_id);
		return $currency->name;
	}
}

?>
