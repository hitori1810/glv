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
({events:{'keyup .chzn-search input':'throttleSearch'},initialize:function(options){_.bindAll(this);app.view.Field.prototype.initialize.call(this,options);this.optionsTemplateC=app.template.getField(this.type,"options");},_render:function(){var self=this;var result=app.view.Field.prototype._render.call(this);this.$(".relateEdit").chosen({no_results_text:"Searching for "}).change(function(event){var selected=$(event.target).find(':selected');self.model.set(self.def.id_name,self.unformat(selected.attr('id')));self.model.set(self.def.name,self.unformat(selected.attr('value')));});return result;},throttleSearch:function(e,interval){if(interval===0&&e.target.value!=""){this.search(e);return;}else{interval=500;clearTimeout(this.throttling);delete this.throttling;}
this.throttling=setTimeout(this.throttleSearch,interval,e,0);},search:function(event){var self=this;var collection=app.data.createBeanCollection(this.def.module);collection.fetch({params:{basicSearch:event.target.value},success:function(data){if(data.models.length>0){self.options=data.models;var options=self.optionsTemplateC(self);self.$('select').html(options);self.$('select').trigger("liszt:updated");}else{}}});}})