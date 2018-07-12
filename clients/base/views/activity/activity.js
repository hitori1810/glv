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
({events:{'click .search':'showSearch','click .addNote':'openNoteModal','click .activity a':'loadChildDetailView','click [name=show_more_button]':'showMoreRecords'},bindDataChange:function(){if(this.collection){this.collection.on("reset",this.render,this);}},showSearch:function(){var $searchEl=$('.search');$searchEl.toggleClass('active');$searchEl.parent().parent().parent().find('.dataTables_filter').toggle();$searchEl.parent().parent().parent().find('.form-search').toggleClass('hide');return false;},openNoteModal:function(){this.layout.trigger("app:view:activity:editmodal");this.$('li.open').removeClass('open');return false;},loadChildDetailView:function(e){this.$("li.activity").removeClass("on");var $parent=this.$(e.currentTarget).parents("li.activity");$parent.addClass("on");var activityId=$parent.data("id");var activity=this.collection.get(activityId);this.model.clear().set(activity.toJSON());},showMoreRecords:function(evt){var self=this,options;app.alert.show('show_more_records',{level:'process',title:app.lang.getAppString('LBL_PORTAL_LOADING')});options=self.filterOpened?self.getSearchOptions():{};options.add=true;if(this.collection.link){options.relate=true;}
options.success=function(){app.alert.dismiss('show_more_records');self.layout.trigger("list:paginate:success");self.render();window.scrollTo(0,document.body.scrollHeight);};options.limit=this.limit;this.collection.paginate(options);},_render:function(){var oViewName=this.name;this.name='list';app.view.View.prototype._render.call(this);this.name=oViewName;}})