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
({showFieldAlert:false,uid:'',showOpps:'',commitDate:'',mDeferred:$.Deferred(),wDeferred:$.Deferred(),bindDataChange:function(){var self=this;if(self.context&&self.context.forecasts){self.context.forecasts.on("forecasts:worksheetmanager:rendered",function(){self.mDeferred.resolve();});self.context.forecasts.committed.on("reset",function(){self.wDeferred.resolve();});}
self.handleDeferredRender();},handleDeferredRender:function(){var self=this;$.when(self.wDeferred,self.mDeferred).done(function(){self._render();self.wDeferred=self.mDeferred=$.Deferred();self.handleDeferredRender();});},_render:function(){if(this.context){this.showFieldAlert=false;this.uid=this.model.get('user_id');this.showOpps=this.model.get('show_opps');var fieldDate;if(this.model.get('date_modified')){fieldDate=new Date(this.model.get('date_modified'));}
if(!_.isEmpty(this.context.forecasts.committed.models)){var lastCommittedDate=new Date(_.first(this.context.forecasts.committed.models).get('date_modified'));if(_.isDate(fieldDate)&&_.isDate(lastCommittedDate)){this.showFieldAlert=(fieldDate.getTime()>lastCommittedDate.getTime());}}else if(_.isDate(fieldDate)){this.showFieldAlert=true;}
this.commitDate=fieldDate;this.options.viewName='historyLog';app.view.Field.prototype._render.call(this);}
return this;}})