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
({fieldTag:"file",events:{"click a.file":"startDownload"},fileUrl:"",_render:function(){this.model=this.model||this.view.model;app.view.Field.prototype._render.call(this);return this;},format:function(value){var attachments=[];if(_.isArray(value)){_.each(value,function(file){var fileObj={name:file.name,url:file.uri};attachments.push(fileObj);},this);}else if(value){var fileObj={name:value,url:app.api.buildFileURL({module:this.module,id:this.model.id,field:this.name},{htmlJsonFormat:false,passOAuthToken:false})};attachments.push(fileObj);}
return attachments;},startDownload:function(e){var self=this;App.api.call('read',App.api.buildURL('ping'),{},{success:function(data){var uri=self.$(e.currentTarget).data("url")+"?oauth_token="+app.api.getOAuthToken();self.$el.prepend('<iframe class="hide" src="'+uri+'"></iframe>');},error:function(data){app.error.handleHttpError(data,{});}});},bindDataChange:function(){if(this.view.name!="edit"&&this.view.fallbackFieldTemplate!="edit"){app.view.Field.prototype.bindDataChange.call(this);}}})