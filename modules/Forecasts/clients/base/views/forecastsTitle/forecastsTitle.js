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
({fullName:'',initialize:function(options){app.view.View.prototype.initialize.call(this,options);this.setFullNameFromUser(app.user);},setFullNameFromUser:function(user){if(_.isFunction(user.get)){this.fullName=user.get('full_name');}else{this.fullName=user.full_name;}},unbindData:function(){if(this.context.forecasts)this.context.forecasts.off(null,null,this);app.view.View.prototype.unbindData.call(this);},bindDataChange:function(){var self=this;this.context.forecasts.on('change:selectedUser',function(context,selectedUser){this.setFullNameFromUser(selectedUser);this.render();},this);}})