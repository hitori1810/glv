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
({events:{'click a.worksheetManagerLink':'linkClicked'},uid:'',initialize:function(options){this.uid=this.model.get('user_id');app.view.Field.prototype.initialize.call(this,options);return this;},format:function(value){var su=this.context.forecasts.get('selectedUser');if(value==su.full_name){var hb=Handlebars.compile("{{str_format key module args}}");value=hb({'key':'LBL_MY_OPPORTUNITIES','module':'Forecasts','args':su.full_name});}
return value;},_render:function(){if(this.name=='name'){this.options.viewName='userLink';}
app.view.Field.prototype._render.call(this);return this;},linkClicked:function(event){var uid=$(event.target).data('uid');var selectedUser={id:'',user_name:'',full_name:'',first_name:'',last_name:'',isManager:false,showOpps:this.model.get("show_opps")};var options={dataType:'json',context:selectedUser,success:_.bind(function(data){selectedUser.id=data.id;selectedUser.user_name=data.user_name;selectedUser.full_name=data.full_name;selectedUser.first_name=data.first_name;selectedUser.last_name=data.last_name;selectedUser.isManager=data.isManager;this.context.forecasts.set({selectedUser:selectedUser})},this)};myURL=app.api.buildURL('Forecasts','user/'+uid);app.api.call('read',myURL,null,options);},hideIcon:function(){$('.pull-right').hide();this.context.forecasts.off('change:commitForecastFlag',this.hideIcon);}})