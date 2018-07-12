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
 * SugarCurrency
 *
 * A class for manipulating currencies and currency amounts
 *
 * @author Monte Ohrt <mohrt@sugarcrm.com>
 */
class SugarCurrency
{

    /**
     * get a currency object
     *
     * @access protected
     * @param  string $currencyId Optional if empty, base currency is returned
     * @return object   currency object
     */
    protected static function _getCurrency( $currencyId = null ) {
        if(empty($currencyId)) {
            $currencyId = '-99';
        }
        $currency = BeanFactory::getBean('Currencies', $currencyId);
        //$currency->retrieve($currencyId);
        return $currency;
    }

    /**
     * convert a currency from one to another
     *
     * @access public
     * @param  float  $amount
     * @param  string $fromId source currency_id
     * @param  string $toId target currency_id
     * @return float   converted amount
     */
    public static function convertAmount( $amount, $fromId, $toId ) {
        if($fromId == $toId) {
            return $amount;
        }
        $currency1 = self::_getCurrency($fromId);
        $currency2 = self::_getCurrency($toId);
        // NOTE: we always calculate in maximum precision, which the database defines to 6
        // formatting to two decimals is done with formatting functions
        return round($amount / $currency1->conversion_rate * $currency2->conversion_rate, 6);
    }

    /**
     * convenience function: convert a currency to base currency
     *
     * @access public
     * @param  float  $amount
     * @param  string $fromId source currency_id
     * @return float   converted amount
     */
    public static function convertAmountToBase( $amount, $fromId ) {
        return self::convertAmount($amount, $fromId, '-99');
    }

    /**
     * convenience function: convert a currency from base currency
     *
     * @access public
     * @param  float  $amount
     * @param  string $toId source currency_id
     * @return float   converted amount
     */
    public static function convertAmountFromBase( $amount, $toId ) {
        return self::convertAmount($amount, '-99', $toId);
    }

    /**
     * convert a currency with a given rate
     *
     * @access public
     * @param  float  $amount
     * @param  float  $rate rate to convert with
     * @return float   converted amount
     */
    public static function convertWithRate( $amount, $rate ) {
        return round($amount / $rate, 6);
    }

    /**
     * format a currency amount with symbol and defined formatting
     *
     * @access public
     * @param  float  $amount
     * @param  string $currencyId
     * @param  int    $decimalPrecision Optional the number of decimal places to use
     * @param  string $decimalSeparator Optional the string to use as decimal separator
     * @param  string $numberGroupingSeparator Optional the string to use for thousands separator
     * @param  bool   $showSymbol Optional show symbol along with currency default true
     * @param  string $symbolSeparator Optional string between symbol and amount
     * @return string  formatted amount
     */
    public static function formatAmount(
        $amount,
        $currencyId,
        $decimalPrecision = 2,
        $decimalSeparator = '.',
        $numberGroupingSeparator = ',',
        $showSymbol = true,
        $symbolSeparator = ''
    ) {
        $currency = self::_getCurrency($currencyId);

        return $showSymbol
            ? $currency->symbol . $symbolSeparator . number_format($amount, $decimalPrecision, $decimalSeparator, $numberGroupingSeparator)
            : number_format($amount, $decimalPrecision, $decimalSeparator, $numberGroupingSeparator);
    }

    /**
     * format a currency amount with symbol and user defined formatting
     *
     * @access public
     * @param  float  $amount
     * @param  string $currencyId
     * @param  bool   $showSymbol Optional show symbol along with currency default true
     * @param  string $symbolSeparator Optional string between symbol and amount
     * @return string  formatted amount
     */
    public static function formatAmountUserLocale(
        $amount,
        $currencyId,
        $showSymbol=true,
        $symbolSeparator = ''
    ) {
        global $locale;
        // get user defined preferences
        $decimalPrecision = $locale->getPrecision();
        $decimalSeparator = $locale->getDecimalSeparator();
        $numberGroupingSeparator = $locale->getNumberGroupingSeparator();

        return self::formatAmount($amount, $currencyId, $decimalPrecision, $decimalSeparator, $numberGroupingSeparator, $showSymbol, $symbolSeparator);
    }

    /**
     * get system base currency object
     *
     * @access public
     * @return object  currency object
     */
    public static function getBaseCurrency( ) {
        // the base currency has a hard-coded currency id of -99
        return self::_getCurrency('-99');
    }

    /**
     * get a currency object by currency id
     *
     * @access public
     * @param  string $currencyId
     * @return object  currency object
     */
    public static function getCurrencyByID( $currencyId = null ) {
        return self::_getCurrency($currencyId);
    }

    /**
     * get a currency object by ISO
     *
     * @access public
     * @param  string $ISO ISO4217 value
     * @return object  currency object
     */
    public static function getCurrencyByISO( $ISO ) {
        $currency = self::_getCurrency('-99');
        $currencyId = $currency->retrieveIDByISO($ISO);
        $currency = self::_getCurrency($currencyId);
        return $currency;
    }

    /**
     * get a currency object by user preferences
     *
     * @access public
     * @param  object $user Optional the user object
     * @return object  currency object
     */
    public static function getUserLocaleCurrency( $user = null ) {
        if(empty($user))
        {
            global $current_user;
            $user = $current_user;
        }
        $currencyId = empty($user) ? '-99' : $user->getPreference('currency');
        return self::_getCurrency($currencyId);
    }

}
