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

({
    fields: null,
    getPlaceholder : function(){
        var ret = "";
        var self = this;
        if (!this.fields){
            this.fields = [];
            _.each(this.def.fields, function(fieldDef){
                var field = app.view.createField({
                    def: fieldDef,
                    view: self.view,
                    model: self.model
                });
                self.fields.push(field);
                ret += field.getPlaceholder();
            });
        }
        return new Handlebars.SafeString(ret);
    }
})