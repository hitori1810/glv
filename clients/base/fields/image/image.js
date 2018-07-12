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
({events:{"click .delete":"delete"},fileUrl:"",_render:function(){this.model.fileField=this.name;app.view.Field.prototype._render.call(this);return this;},format:function(value){if(value){value=this.buildUrl()+"&"+value;}
return value;},buildUrl:function(options){return app.api.buildFileURL({module:this.module,id:this.model.id,field:this.name},options);},delete:function(){var self=this;app.api.call('delete',self.buildUrl({htmlJsonFormat:false}),{},{success:function(data){self.model.set(self.name,"");self.render();},error:function(data){app.error.handleHttpError(data,{});}});},bindDomChange:function(){},bindDataChange:function(){if(this.model){this.model.on("change:"+this.name,function(){var isValue=this.$(this.fieldTag).val();if(!isValue){this.render();}},this);}}})