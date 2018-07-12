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




/**
 * @deprecated use DBManager::convert() instead.
 */
function db_convert($string, $type, $additional_parameters=array(),$additional_parameters_oracle_only=array())
	{
    return $GLOBALS['db']->convert($string, $type, $additional_parameters, $additional_parameters_oracle_only);
            }

/**
 * @deprecated use DBManager::concat() instead.
 */
function db_concat($table, $fields)
	{
    return $GLOBALS['db']->concat($table, $fields);
}

/**
 * @deprecated use DBManager::fromConvert() instead.
 */
function from_db_convert($string, $type)
	{
    return $GLOBALS['db']->fromConvert($string, $type);
	}

$toHTML = array(
	'"' => '&quot;',
	'<' => '&lt;',
	'>' => '&gt;',
	"'" => '&#039;',
);
$GLOBALS['toHTML_keys'] = array_keys($toHTML);
$GLOBALS['toHTML_values'] = array_values($toHTML);
$GLOBALS['toHTML_keys_set'] = implode("", $GLOBALS['toHTML_keys']);
/**
 * Replaces specific characters with their HTML entity values
 * @param string $string String to check/replace
 * @param bool $encode Default true
 * @return string
 *
 * @todo Make this utilize the external caching mechanism after re-testing (see
 *       log on r25320).
 *
 * Bug 49489 - removed caching of to_html strings as it was consuming memory and
 * never releasing it
 */
function to_html($string, $encode=true){
	if (empty($string)) {
		return $string;
	}

	global $toHTML;

	if($encode && is_string($string)){
		/*
		 * cn: bug 13376 - handle ampersands separately
		 * credit: ashimamura via bug portal
		 */
		//$string = str_replace("&", "&amp;", $string);

        if(is_array($toHTML))
        { // cn: causing errors in i18n test suite ($toHTML is non-array)
            $string = str_ireplace($GLOBALS['toHTML_keys'],$GLOBALS['toHTML_values'],$string);
		}
	}

    return $string;
}


/**
 * Replaces specific HTML entity values with the true characters
 * @param string $string String to check/replace
 * @param bool $encode Default true
 * @return string
 */
function from_html($string, $encode=true) {
    if (!is_string($string) || !$encode) {
        return $string;
    }

	global $toHTML;
    static $toHTML_values = null;
    static $toHTML_keys = null;
    static $cache = array();
    if (!empty($toHTML) && is_array($toHTML) && (!isset($toHTML_values) || !empty($GLOBALS['from_html_cache_clear']))) {
        $toHTML_values = array_values($toHTML);
        $toHTML_keys = array_keys($toHTML);
    }

    // Bug 36261 - Decode &amp; so we can handle double encoded entities
	$string = str_ireplace("&amp;", "&", $string);

    if (!isset($cache[$string])) {
        $cache[$string] = str_ireplace($toHTML_values, $toHTML_keys, $string);
    }
    return $cache[$string];
}

/*
 * Return a version of $proposed that can be used as a column name in any of our supported databases
 * Practically this means no longer than 25 characters as the smallest identifier length for our supported DBs is 30 chars for Oracle plus we add on at least four characters in some places (for indices for example)
 * @param string $name Proposed name for the column
 * @param string $ensureUnique
 * @param int $maxlen Deprecated and ignored
 * @return string Valid column name trimmed to right length and with invalid characters removed
 */
function getValidDBName ($name, $ensureUnique = false, $maxLen = 30)
{
    return DBManagerFactory::getInstance()->getValidDBName($name, $ensureUnique);
}


/**
 * isValidDBName
 *
 * Utility to perform the check during install to ensure a database name entered by the user
 * is valid based on the type of database server
 * @param string $name Proposed name for the DB
 * @param string $dbType Type of database server
 * @return bool true or false based on the validity of the DB name
 */
function isValidDBName($name, $dbType)
{
    $db = DBManagerFactory::getTypeInstance($dbType);
    return $db->isDatabaseNameValid($name);
}
