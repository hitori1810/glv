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
({events:{'click [data-toggle=tab]':'closeSubdetail'},initialize:function(options){app.view.View.prototype.initialize.call(this,options);this.fallbackFieldTemplate="detail";},render:function(){},bindDataChange:function(){if(this.model){this.model.on("change",function(){app.view.View.prototype.render.call(this);},this);}},closeSubdetail:function(){this.model.clear();$('.nav-tabs').find('li').removeClass('on');this.$el.empty();}})