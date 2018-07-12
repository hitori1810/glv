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
({timeperiod:{},initialize:function(options){app.view.View.prototype.initialize.call(this,options);this.timePeriodId=app.defaultSelections.timeperiod_id.id;_.each(this.meta.panels,function(panel){this.timeperiod=_.find(panel.fields,function(item){return _.isEqual(item.name,'timeperiod');});},this);},_renderField:function(field){if(field.name=="timeperiod"){field=this._setUpTimeperiodField(field);}
app.view.View.prototype._renderField.call(this,field);},_setUpTimeperiodField:function(field){field.events=_.extend({"change select":"_updateSelections"},field.events);field.bindDomChange=function(){};field._updateSelections=function(event,input){var label=this.$el.find('option:[value='+input.selected+']').text();app.defaultSelections.timeperiod_id.id=input.selected;this.view.context.forecasts.set('selectedTimePeriod',{"id":input.selected,"label":label});this.$el.find('div.chzn-container-active').removeClass('chzn-container-active');};app.api.call("read",app.api.buildURL("Forecasts","timeperiod"),'',{success:function(results){this.field.def.options=results;if(!this.field.disposed){this.field.render();}}},{field:field,view:this});field.def.value=app.defaultSelections.timeperiod_id.id;return field;}})