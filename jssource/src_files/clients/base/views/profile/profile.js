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

/**
 * View that displays profile detail.
 * @class View.Views.ProfileView
 * @alias SUGAR.App.view.ProfileView
 * @extends View.Views.DetailView
 */
({
    extendsFrom: "DetailView",
    initialize: function(options) {
        this.options.meta   = app.metadata.getView(this.options.module, 'detail');
        app.view.views.DetailView.prototype.initialize.call(this, options);
        this.template = app.template.get("detail");
    },
    getFullName: function() {
        var full_name = this.model.get('full_name') || this.model.get('first_name') + ' ' + this.model.get('last_name') || this.model.get('name'),
            salutation = this.model.get('salutation');
        if (!_.isEmpty(salutation)) {
            var salutation_dom = app.lang.getAppListStrings(this.model.fields.salutation.options);
            salutation = salutation_dom[salutation] || salutation;
            full_name = salutation + ' ' + full_name;
        }
        return full_name;
    },
    bindDataChange:function () {
        if (this.model) {
            this.model.on("change", function () {
                this.fieldsToDisplay = _.toArray(this.model.fields).length;
                if (this.context.get('subnavModel')) {
                    this.context.get('subnavModel').set({
                        'title':this.getFullName(),
                        'meta':this.meta
                    });
                    this.model.isNotEmpty = true;
                    this.render();
                }
            }, this);
        }
    }
})

