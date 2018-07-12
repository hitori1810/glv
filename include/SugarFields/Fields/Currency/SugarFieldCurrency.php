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


require_once('include/SugarFields/Fields/Float/SugarFieldFloat.php');

class SugarFieldCurrency extends SugarFieldFloat 
{

    public function getListViewSmarty($parentFieldArray, $vardef, $displayParams, $col)
    {
        global $locale, $current_user;
        $tabindex = 1;
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex, false);

        $amount = $parentFieldArray[strtoupper($vardef['name'])];
        $currencyId = isset($parentFieldArray['CURRENCY_ID']) ? $parentFieldArray['CURRENCY_ID'] : "";
        $currencySymbol = isset($parentFieldArray['CURRENCY_SYMBOL']) ? $parentFieldArray['CURRENCY_SYMBOL'] : "";

        // we no longer show amounts in user preferred currency, only transactional or base
        if (empty($currencyId) || stripos($vardef['name'], 'usdoll') !== false) {
            $baseCurrency = SugarCurrency::getBaseCurrency();
            $currencyId = $baseCurrency->id;
            $currencySymbol = $baseCurrency->symbol;
        }

        if (empty($currencySymbol)) {
            $currency = SugarCurrency::getCurrencyByID($currencyId);
            $currencySymbol = $currency->symbol;
        }

        $this->ss->assign('currency_id', $currencyId);
        $this->ss->assign('currency_symbol', $currencySymbol);
        $this->ss->assign('amount', $amount);

        return $this->fetch($this->findTemplate('ListView'));
    }

    /**
     * @see SugarFieldBase::importSanitize()
     */
    public function importSanitize(
        $value,
        $vardef,
        $focus,
        ImportFieldSanitize $settings
        )
    {
        $value = str_replace($settings->currency_symbol,"",$value);
        
        return $settings->float($value,$vardef,$focus);
    }


    /**
     * Handles export field sanitizing for field type
     *
     * @param $value string value to be sanitized
     * @param $vardef array representing the vardef definition
     * @param $focus SugarBean object
     * @param $row Array of a row of data to be exported
     *
     * @return string sanitized value
     */
    public function exportSanitize($value, $vardef, $focus, $row=array())
    {
        require_once('include/SugarCurrency/SugarCurrency.php');
        //If the row has a currency_id set, use that instead of the $focus->currency_id value
        return SugarCurrency::formatAmountUserLocale($value, isset($row['currency_id']) ? $row['currency_id'] : $focus->currency_id);
    }

    /**
     * format the currency field based on system locale values for currency
     * Note that this may be different from the precision specified in the vardefs.
     * @param string $rawfield value of the field
     * @param string $somewhere vardef for the field being processed
     * @return number formatted according to currency settings
     */
    public function formatField($rawField, $vardef)
    {
        // for currency fields, use the user or system precision, not the precision in the vardef
        //this is achived by passing in $precision as null
        $precision = null;
        if ( $rawField === '' || $rawField === NULL ) {
            return '';
        }
        return format_number($rawField, $precision, $precision);
    }
}

