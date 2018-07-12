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

(function (app) {
    /**
     * Currency module provides utility methods for working with currencies.
     *
     * @class Utils.Currency
     * @singleton
     * @alias SUGAR.App.currency
     */
    app.augment('currency', {
        /**
         * Get the symbol for the given currency
         * @param {String}  currencyId
         * @return {String} currency symbol
         */
        getCurrencySymbol:function (currencyId) {
            var currency = app.metadata.getCurrency(currencyId);
            return currency ? currency.symbol : '';
        },
        /**
         * Format currency amount
         * @param {Number}  amount
         * @param {String}  currencyId
         * @param {Number}  decimalPrecision number of digits for decimal
         * @param {String}  numberGroupingSeparator string between thousands
         * @param {String}  decimalSeparator string between number and decimals
         * @param {String}  symbolSeparator string between symbol and amount
         * @return {String} formatted currency amount
         */
        formatAmount:function (amount, currencyId, decimalPrecision, numberGroupingSeparator, decimalSeparator, symbolSeparator) {
            var currencySymbol;
            // default to base currency
            currencyId = currencyId || '-99';
            symbolSeparator = symbolSeparator || '';
            // use reasonable defaults if none exist
            decimalPrecision = decimalPrecision || 2;
            decimalSeparator = decimalSeparator || '.';
            numberGroupingSeparator = (!_.isUndefined(numberGroupingSeparator)) ? numberGroupingSeparator : ',';
            currencySymbol = this.getCurrencySymbol(currencyId) || '$';
            amount = app.utils.formatNumber(
                amount,
                decimalPrecision,
                decimalPrecision,
                numberGroupingSeparator,
                decimalSeparator);
            return currencySymbol + symbolSeparator + amount;
        },
        /**
         * Format currency amount to current user locale
         * @param {Number}  amount
         * @param {String}  currencyId
         * @param {String}  symbolSeparator string between symbol and amount
         * @return {String} formatted currency amount
         */
        formatAmountLocale:function (amount, currencyId, symbolSeparator) {
            // get user preferred formatting
            var decimalPrecision = app.user.getPreference('decimal_precision');
            var decimalSeparator = app.user.getPreference('decimal_separator');
            var numberGroupingSeparator = app.user.getPreference('number_grouping_separator');
            return this.formatAmount(
                amount,
                currencyId,
                decimalPrecision,
                numberGroupingSeparator,
                decimalSeparator,
                symbolSeparator);
        },
        /**
         * Unformat currency amount
         * @param {Number}  amount
         * @param {String}  numberGroupingSeparator string between thousands
         * @param {String}  decimalSeparator string between number and decimals
         * @param {Boolean} toFloat convert string to float value
         * @return {String} formatted currency amount
         */
        unformatAmount:function (amount, numberGroupingSeparator, decimalSeparator, toFloat) {
            toFloat = toFloat || false;
            // strip off currency symbol, or anything prefixed that is not a digit or separator
            amount = amount.toString().replace(/^[^\d\.\,]+/, '');
            return app.utils.unformatNumberString(
                amount,
                numberGroupingSeparator,
                decimalSeparator,
                toFloat);
        },
        /**
         * Unformat currency amount with user locale
         * @param {Number}  amount
         * @param {Boolean} toFloat convert string to float value
         * @return {String} formatted currency amount
         */
        unformatAmountLocale:function (amount, toFloat) {
            var decimalSeparator;
            var numberGroupingSeparator;
            // use user locale or reasonable defaults
            decimalSeparator = app.user.getPreference('decimal_separator') || '.';
            numberGroupingSeparator = app.user.getPreference('number_grouping_separator') || ',';
            return this.unformatAmount(
                amount,
                numberGroupingSeparator,
                decimalSeparator,
                toFloat);
        },
        /**
         * convert a currency from one to another
         *
         * @param  {Number} amount
         * @param  {String} fromId source currency id
         * @param  {String} toId target currency id
         * @return {Number} converted amount
         */
        convertAmount:function (amount, fromId, toId) {
            var currency1;
            var currency2;
            if(fromId == toId) {
                return app.math.round(amount);
            }
            currency1 = app.metadata.getCurrency(fromId);
            currency2 = app.metadata.getCurrency(toId);
            return this.convertWithRate(amount, currency1.conversion_rate, currency2.conversion_rate);
        },
        /**
         * convert a currency to base currency
         *
         * @param  {Number} amount
         * @param  {String} fromId source currency id
         * @return {Number} converted amount
         */
        convertToBase:function (amount, fromId) {
            return this.convertAmount(amount, fromId, '-99');
        },
        /**
         * convert base currency to another currency
         *
         * @param  {Number} amount
         * @param  {String} toId target currency id
         * @return {Number} converted amount
         */
        convertFromBase:function (amount, toId) {
            return this.convertAmount(amount, '-99', toId);
        },
        /**
         * convert a currency with given conversion rate
         *
         * @param  {Number} amount
         * @param  {Number} fromRate the origin conversion rate
         * @param  {Number} toRate the target conversion rate
         * @return {Number} converted amount
         */
        convertWithRate:function (amount, fromRate, toRate) {
            fromRate = fromRate || 1.0;
            toRate = toRate || 1.0;
            return app.math.mul(app.math.div(amount, fromRate), toRate);
        }

    }, false);
})(SUGAR.App);
