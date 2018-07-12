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
({events:{'change':'bucketsChanged'},disabled:false,langValue:"",currentView:"",initialize:function(options){app.view.Field.prototype.initialize.call(this,options);var self=this,forecastRanges=self.context.forecasts.config.get("forecast_ranges");self.isEditable();if(forecastRanges=="show_binary"){self.def.view="bool";self.currentView="bool";self.format=function(value){return value=="include";};self.unformat=function(value){return self.$el.find(".checkbox").prop('checked')?"include":"exclude";};}
else if(forecastRanges=="show_buckets"){self.def.view="default";self.currentView="default";self.getLanguageValue();self.createCTEIconHTML();if(!self.disabled){self.createBuckets();}}},_render:function(){var self=this;app.view.Field.prototype._render.call(this);if(!self.disabled&&self.currentView=="enum"){self.$el.off("click");$(window).on("click."+self.model.get("id"),function(e){if(!_.isEqual(self.model.get("id"),$(e.target).attr("itemid"))){self.resetBucket();}});self.$el.on("click",function(e){$(e.target).attr("itemid",self.model.get("id"));});self.$el.off("mouseenter");self.$el.off("mouseleave");self.$el.find("option[value="+self.value+"]").attr("selected","selected");self.$el.find("select").chosen({disable_search_threshold:self.def.searchBarThreshold?self.def.searchBarThreshold:0});}},bucketsChanged:function(){var self=this,values={},moduleName=self.moduleName;if(self.currentView=="bool"){self.value=self.unformat();values[self.def.name]=self.value;}
else if(self.currentView=="enum"){self.value=self.$el.find("select")[0].value;values[self.def.name]=self.value;}
self.model.set(values);if(self.currentView=="enum"){self.resetBucket();}},createBuckets:function(){var self=this;self.buckets=$.data(document.body,"commitStageBuckets");if(_.isUndefined(self.buckets)){var options=app.lang.getAppListStrings(this.def.options)||'commit_stage_dom';self.buckets="<select data-placeholder=' ' name='"+self.name+"' style='width: 100px;'>";self.buckets+="<option value='' selected></option>";_.each(options,function(item,key){self.buckets+="<option value='"+key+"'>"+item+"</options>"});self.buckets+="</select>";$.data(document.body,"commitStageBuckets",self.buckets);}},createCTEIconHTML:function(){var self=this,cteIcon=$.data(document.body,"cteIcon"),events=self.events||{},sales_stage=self.model.get("sales_stage");if(_.isUndefined(cteIcon)){cteIcon='<span class="edit-icon"><i class="icon-pencil icon-sm"></i></span>';$.data(document.body,"cteIcon",cteIcon);}
self.showCteIcon=function(){if((self.currentView!="enum")&&(!self.disabled)){self.$el.find("span").before($(cteIcon));}};self.hideCteIcon=function(){if((self.currentView!="enum")&&(!self.disabled)){self.$el.parent().find(".edit-icon").detach();}};self.events=_.extend(events,{'mouseenter':'showCteIcon','mouseleave':'hideCteIcon','click':'clickToEdit'});},getLanguageValue:function(){var options=app.lang.getAppListStrings(this.def.options)||'commit_stage_dom';this.langValue=options[this.model.get(this.def.name)];},clickToEdit:function(e){var self=this,sales_stage=self.model.get("sales_stage");if(!self.disabled){$(e.target).attr("itemid",self.model.get("id"));self.def.view="enum";self.currentView="enum";self.render();}},resetBucket:function(){var self=this;$(window).off("click."+self.model.get("id"));self.$el.off("click");self.def.view="default";self.currentView="default";self.getLanguageValue();self.delegateEvents();self.render();},isEditable:function(){var self=this,sales_stages,hasStage=false
isOwner=true;if(!_.isUndefined(self.context.forecasts)){if(!_.isUndefined(self.context.forecasts.config)){sales_stages=self.context.forecasts.config.get("sales_stage_won").concat(self.context.forecasts.config.get("sales_stage_lost"));hasStage=_.contains(sales_stages,self.model.get('sales_stage'));}
if(self.context.forecasts.get("selectedUser")["id"]!=app.user.id){isOwner=false;}}
self.disabled=hasStage||!isOwner;},})