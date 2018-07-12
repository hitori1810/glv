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
({fieldTag:"select",_render:function(){var optionsKeys=[];if(_.isString(this.def.options)){optionsKeys=_.keys(app.lang.getAppListStrings(this.def.options));}else if(_.isObject(this.def.options)){optionsKeys=_.keys(this.def.options);}
if(_.isUndefined(this.model.get(this.name))){var defaultValue=_.first(optionsKeys);if(defaultValue){this.$(this.fieldTag).val(defaultValue);this.model.set(this.name,defaultValue);}}
var chosenOptions={};var emptyIdx=_.indexOf(optionsKeys,"");if(emptyIdx!==-1){chosenOptions.allow_single_deselect=true;if(emptyIdx>1){this.hasBlank=true;}}
chosenOptions.disable_search_threshold=this.def.searchBarThreshold?this.def.searchBarThreshold:0;app.view.Field.prototype._render.call(this);this.$(this.fieldTag).chosen(chosenOptions);this.$(".chzn-container").addClass("tleft");return this;},unformat:function(value){return value;},format:function(value){var newval='',optionsObject,optionLabels;if(this.def.isMultiSelect&&this.view.name!=='edit'){optionsObject=app.lang.getAppListStrings(this.def.options);_.each(value,function(p){if(_.has(optionsObject,p)){newval+=optionsObject[p]+', ';}});newval=newval.slice(0,newval.length-2);}else{newval=this.model.get(this.name);}
if(this.def.isMultiSelect&&this.view.name==='edit'&&this.def.default&&typeof newval==='string'){newval=this.convertMultiSelectDefaultString(newval);}
return newval;},convertMultiSelectDefaultString:function(defaultString){var result=defaultString.split(",");_.each(result,function(value,key){result[key]=value.replace(/\^/g,"");})
return result;}})