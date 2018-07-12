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
({filterOpened:false,events:{'click [name=show_more_button]':'showMoreRecords','click .search':'showSearch'},_renderHtml:function(){if(app.acl.hasAccess('create',this.module)){this.context.set('isCreateEnabled',true);}
this.limit=this.context.get('limit')?this.context.get('limit'):null;app.view.View.prototype._renderHtml.call(this);this.layout.off("list:filter:toggled",null,this);this.layout.on("list:filter:toggled",this.filterToggled,this);},filterToggled:function(isOpened){this.filterOpened=isOpened;},showMoreRecords:function(evt){var self=this,options;_.each(this.collection.models,function(model){model.old=true;});app.alert.show('show_more_records_'+self.cid,{level:'process',title:app.lang.getAppString('LBL_PORTAL_LOADING')});var screenPosition=$(window).scrollTop();options=self.filterOpened?self.getSearchOptions():{};options.add=true;options.success=function(){app.alert.dismiss('show_more_records_'+self.cid);self.layout.trigger("list:paginate:success");self.render();window.scrollTo(0,screenPosition);self.layout.$('tr.new').animate({opacity:1},500,function(){$(this).removeAttr('style class');});};options.limit=this.limit;this.collection.paginate(options);},showSearch:function(){this.$('.search').toggleClass('active');this.layout.trigger("list:search:toggle");},getSearchOptions:function(){var collection,options,previousTerms,term='';collection=this.context.get('collection');if(app.cache.has('previousTerms')){previousTerms=app.cache.get('previousTerms');if(previousTerms){term=previousTerms[this.module];}}
options={params:{q:term},fields:collection.fields?collection.fields:this.collection};return options;},bindDataChange:function(){if(this.collection){this.collection.on("reset",this.render,this);}}})