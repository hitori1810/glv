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
({initialize:function(options){app.view.View.prototype.initialize.call(this,options);if(!_.isUndefined(options.meta.registerLabelAsBreadCrumb)&&options.meta.registerLabelAsBreadCrumb==true){this.layout.registerBreadCrumbLabel(options.meta.panels[0].label);}},_renderField:function(field){field=this._setUpTimeperiodConfigField(field);if(this.model.get('is_setup')){field.options.def.view='detail';}
app.view.View.prototype._renderField.call(this,field);},_setUpTimeperiodConfigField:function(field){switch(field.name){case"timeperiod_shown_forward":case"timeperiod_shown_backward":return this._setUpTimeperiodShowField(field);case"timeperiod_interval":return this._setUpTimeperiodIntervalBind(field);default:return field;}},_setUpTimeperiodShowField:function(field){field.events=_.extend({"change select":"_updateSelection"},field.events);field.bindDomChange=function(){};field._updateSelection=function(event,input){var value=parseInt(input.selected);this.def.value=value;this.model.set(this.name,value);};field.def.value=this.model.get(field.name)||1;return field;},_setUpTimeperiodIntervalBind:function(field){field.def.value=this.model.get(field.name);field.events=_.extend({"change select":"_updateIntervals"},field.events);field.bindDomChange=function(){};if(typeof(field.def.options)=='string'){field.def.options=app.lang.getAppListStrings(field.def.options);}
field._updateIntervals=function(event,input){var selected_interval="Annual";if(_.has(input,"selected")){selected_interval=input.selected;}
this.def.value=selected_interval;this.model.set(this.name,selected_interval);this.model.set('timeperiod_leaf_interval',selected_interval=='Annual'?'Quarter':'Month');}
return field;}})