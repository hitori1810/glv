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
({extendsFrom:"EditView",events:{'click [name=save_button]':'saveModel','click a.password':'changePassword'},initialize:function(options){this.options.meta=app.metadata.getView(this.options.module,'edit');app.view.views.EditView.prototype.initialize.call(this,options);this.template=app.template.get("edit");this.fallbackFieldTemplate="edit";this.model.on("change",function(){this.$('a.password').attr('href','javascript:void(0)').text(app.lang.get('LBL_CONTACT_EDIT_PASSWORD_LNK_TEXT'));},this);},changePassword:function(){this.layout.trigger("app:view:password:editmodal");return false;},saveModel:function(){var self=this,options;app.alert.show('save_profile_edit_view',{level:'process',title:app.lang.getAppString('LBL_SAVING')});options={success:function(){app.alert.dismiss('save_profile_edit_view');app.file.checkFileFieldsAndProcessUpload(self.model,{success:function(){var langKey=self.model.get('preferred_language');if(langKey&&langKey!=app.lang.getLanguage())
app.lang.setLanguage(langKey,null,{noUserUpdate:true});app.router.navigate('profile',{trigger:true});}},{deleteIfFails:false});},error:function(error){app.alert.dismiss('save_profile_edit_view');app.error.handleHttpError(error);},fieldsToValidate:self.getFields(this.model.module)};self.model.unset('portal_password',{silent:true});self.model.unset('portal_password1',{silent:true});self.model.save(null,options);}})