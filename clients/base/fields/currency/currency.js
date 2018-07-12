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
({transactionValue:'',_render:function(){if(this.def.convertToBase&&this.def.showTransactionalAmount&&this.model.get('currency_id')!=='-99'){this.transactionValue=app.currency.formatAmountLocale(this.model.get(this.name),this.model.get('currency_id'));}
app.view.Field.prototype._render.call(this);return this;},unformat:function(value){return app.currency.unformatAmountLocale(value);},format:function(value){var base_rate=this.model.get('base_rate');var currencyId=this.model.get('currency_id');if(this.def.convertToBase){value=app.currency.convertWithRate(value,base_rate);currencyId='-99';}
return app.currency.formatAmountLocale(value,currencyId);}})