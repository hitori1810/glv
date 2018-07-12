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
({activePanel:0,totalPanels:null,panels:[],navTabs:[],breadCrumbLabels:[],events:{'click [name=close_button]':'close','click [name=save_button]':'save','click ul.nav li a':'breadcrumb'},initialize:function(options){app.view.View.prototype.initialize.call(this,options);this.breadCrumbLabels=this.layout.getBreadCrumbLabels();},_renderHtml:function(ctx,options){app.view.View.prototype._renderHtml.call(this,ctx,options);this.$el.parents('div.modal-body').addClass('with-tab-nav');},bindDataChange:function(){var self=this;this.model.on('change',function(){self.$el.find('[name=save_button]').removeClass('disabled');})},close:function(evt){this.layout.context.trigger("modal:close");},save:function(evt){if(!$(evt.target).hasClass('disabled')){var self=this;this.context.forecasts.config.set(this.model.toJSON());this.context.forecasts.set({saveClicked:true},{silent:true});this.context.forecasts.config.save({},{success:function(){self.layout.context.trigger("modal:close");}});}},breadcrumb:function(evt){evt.preventDefault();if(!_.isNumber(this.totalPanels)){this.panels=this.$el.parent().find('div.modal-content');this.totalPanels=this.panels.length-1;this.navTabs=this.$el.parent().find('div.modal-navigation li');}
if($(evt.target).parent().is(".active,.disabled")==false){var clickedCrumb=$(evt.target).data('index');if(clickedCrumb!=this.activePanel){this.switchPanel(clickedCrumb);this.switchNavigationTab(clickedCrumb);this.activePanel=clickedCrumb;}}},handleDirectionSwitch:function(way){if(!_.isNumber(this.totalPanels)){this.panels=this.$el.parent().find('div.modal-content').not('.modal-wizard-start');this.totalPanels=this.panels.length-1;this.navTabs=this.$el.parent().find('div.modal-navigation li');}
var nextPanel=-1;if(way=="next"){nextPanel=this.activePanel+1;}else{nextPanel=this.activePanel-1;}
if(nextPanel<0){nextPanel=0;}else if(nextPanel>this.totalPanels){nextPanel=this.totalPanels;}
this.switchPanel(nextPanel);this.switchNavigationTab(nextPanel);this.activePanel=nextPanel;},switchPanel:function(nextPanel){$(this.panels[this.activePanel]).toggleClass('show hide');$(this.panels[nextPanel]).toggleClass('show hide');},switchNavigationTab:function(next){$(this.navTabs[this.activePanel]).toggleClass('active');$(this.navTabs[next]).toggleClass('active');}})