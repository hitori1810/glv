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

    /**
     * Saved Labels to use for the Breadcrumbs
     */
    breadCrumbLabels: [],

    initialize: function (options) {
        var modelUrl = app.api.buildURL("Forecasts", "config"),
            modelSync = function(method, model, options) {
                var url = _.isFunction(model.url) ? model.url() : model.url;
                return app.api.call(method, url, model, options);
            },
            settingsModel = this._getConfigModel(options, modelUrl, modelSync);

        settingsModel.fetch();

        options.context.set("model", settingsModel);

        app.view.Layout.prototype.initialize.call(this, options);
    },

    /**
     * Gets a config model for the config settings dialog.
     *
     * If we're using this layout from inside the Forecasts module and forecasts already has a config model, config
     * will use that config model as our current context so we're updating a clone of the same model.
     * The clone facilitates not saving to a "live" model, so if a user hits cancel, the values will go back to the
     * correct setting the next time the admin panel is accessed.
     *
     * If we're not coming in from the Forecasts module (e.g. Admin)
     * creates a new model and config will use that to change/save
     * @return {Object} the model for config
     */
    _getConfigModel: function(options, syncUrl, syncFunction) {
        var SettingsModel = Backbone.Model.extend({
            url: syncUrl,
            sync: syncFunction
        });

        // jQuery.extend is used with the `true` parameter to do a deep copy
        return (_.has(options.context,'forecasts') && _.has(options.context.forecasts,'config')) ?
            new SettingsModel($.extend(true, {}, options.context.forecasts.config.attributes)) :
            new SettingsModel();
    },

    /**
     * Register a new breadcrumb label
     *
     * @param {string} label
     */
    registerBreadCrumbLabel : function(label) {
        var labelObj = {
                'index': this.breadCrumbLabels.length,
                'label': label
            },
            found = false;
        _.each(this.breadCrumbLabels, function(crumb) {
            if(crumb.label == label) {
                found = true;
            }
        })
        if(!found) {
            this.breadCrumbLabels.push(labelObj);
        }
    },

    /**
     * Get the current registered breadcrumb labels
     *
     * @return {*}
     */
    getBreadCrumbLabels : function(){
        return this.breadCrumbLabels;
    }

})