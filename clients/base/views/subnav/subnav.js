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
({events:{'click [name=save_button]':'saveModel'},initialize:function(options){app.view.View.prototype.initialize.call(this,options);this.context.set('subnavModel',new Backbone.Model());this.subnavModel=this.context.get('subnavModel');$(window).on("resize.subnav",_.bind(this.resize,this));},saveModel:function(){this.context.trigger("subnav:save");},bindDataChange:function(){if(this.subnavModel){this.subnavModel.on("change",this.render,this);}},_renderHtml:function(){app.view.View.prototype._renderHtml.call(this);this.resize();},resize:function(){var self=this;if(self.resizeDetectTimer){clearTimeout(this.resizeDetectTimer);}
self.resizeDetectTimer=setTimeout(function(){var $el=self.$('h1');if($el[0].offsetWidth<$el[0].scrollWidth){$el.attr({'data-original-title':$el.text(),'rel':'tooltip'}).tooltip({placement:"bottom"});}
else{$el.removeAttr('data-original-title rel');}},250);},_dispose:function(){$(window).off("resize.subnav");app.view.View.prototype._dispose.call(this);}})