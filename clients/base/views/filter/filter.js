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
({previousTerms:{},events:{'keyup .dataTables_filter input':'queueAndDelay'},_renderHtml:function(){this.searchFields=this.getSearchFields();app.view.View.prototype._renderHtml.call(this);this.layout.off("list:search:toggle",null,this);this.layout.on("list:search:toggle",this.toggleSearch,this);},getSearchFields:function(){var self=this;var moduleMeta=app.metadata.getModule(this.module);var results=new Array();var allowedFields=["int","varchar","name"];_.each(moduleMeta.fields,function(fieldMeta,fieldName){var fMeta=fieldMeta;if(fMeta.unified_search&&_.indexOf(self.collection.fields,fieldName)>=0&&_.indexOf(allowedFields,fMeta.type)!==-1){results.push(app.lang.get(fMeta.vname,self.module));}});return results;},queueAndDelay:function(evt){var self=this;if(!self.debounceFunction){self.debounceFunction=_.debounce(function(){var term,previousTerm;previousTerm=self.getPreviousTerm(this.module);term=self.$(evt.currentTarget).val();self.setPreviousTerm(term,this.module);if(term&&term.length){self.setPreviousTerm(term,this.module);self.fireSearchRequest(term);}else if(previousTerm&&!term.length){this.collection.fetch({limit:this.context.get('limit')||null});}},app.config.requiredElapsed||500);}
self.debounceFunction();},fireSearchRequest:function(term){var self=this;self.setPreviousTerm(term,this.module);this.layout.trigger("list:search:fire",term);},setPreviousTerm:function(term,module){if(app.cache.has('previousTerms')){this.previousTerms=app.cache.get('previousTerms');}
if(module){this.previousTerms[module]=term;}
app.cache.set("previousTerms",this.previousTerms);},getPreviousTerm:function(module){if(app.cache.has('previousTerms')){this.previousTerms=app.cache.get('previousTerms');return this.previousTerms[module];}},toggleSearch:function(){var isOpened,previousTerm=this.getPreviousTerm(this.module);this._renderHtml();this.$('.dataTables_filter').toggle();isOpened=this.$('.dataTables_filter').is(':visible');this.layout.trigger('list:filter:toggled',isOpened);this.$('.dataTables_filter input').val('').focus();if(!isOpened){this.collection.fetch({limit:this.context.get('limit')||null});}
return false;}})