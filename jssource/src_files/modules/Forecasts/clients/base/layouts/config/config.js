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
 * Events Triggered
 *
 * modal:forecastsWizardConfig:open - to cause modal.js to pop up
 *      on: this
 *      by: _showConfigModal()
 */
({
    extendsFrom:"ForecastsIndexLayout",

    initialize:function (options) {
        // If is_setup == 1 and users come back to config, the context.forecasts will already be here
        // so only make this new config mode there is no forecasts object on the context
        if(_.isUndefined(options.context.forecasts)) {
            options.context = _.extend(options.context, this.initializeAllModels(options.context));
            options.context.forecasts = new Backbone.Model({'saveClicked' : false});

            // Initialize the config model
            var modelUrl = app.api.buildURL("Forecasts", "config"),
                modelSync = function(method, model, options) {
                    var url = _.isFunction(model.url) ? model.url() : model.url;
                    return app.api.call(method, url, model, options);
                };
            options.context.forecasts.config = this._getConfigModel(options, modelUrl, modelSync);

        }

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
            sync: syncFunction,
            //include metadata from config into the config model by default
            defaults:app.metadata.getModule('Forecasts').config
        });

        // jQuery.extend is used with the `true` parameter to do a deep copy
        return (_.has(options.context,'forecasts') && _.has(options.context.forecasts,'config')) ?
            new SettingsModel($.extend(true, {}, options.context.forecasts.config.attributes)) :
            new SettingsModel();
    },

    /**
     * Dropping in to _render to insert some code to display the config wizard for a user's first run on forecasts.  The render process itself is unchanged.
     *
     * @return {*}
     * @private
     */
    _render:function () {
        app.view.Layout.prototype._render.call(this);
        // initialize the alerts again.
        app.alert.init();
        this._showModal();
        return this;
    },

    _showModal:function () {
        var self = this,
            isAdmin = app.user.getAcls()['Forecasts'].admin == "yes",
            isSetup = this.context.forecasts.config.get('is_setup');

        if (isAdmin) {
            // begin building params to pass to modal
            var params = {
                title:app.lang.get("LBL_FORECASTS_CONFIG_TITLE", "Forecasts") + ":",
                span:10,
                before:{
                    hide: function() {
                        self.checkSettingsAndRedirect(isSetup,isAdmin);
                    }
                },
                components: [
                    { layout: (this.context.forecasts.config.get('is_setup') == 1) ? "tabbedConfig" : "wizardConfig" }
                ]
            };
            // callback has to be a function returning the checkSettingsAndRedirect function
            // to maintain the proper context otherwise from modal, "this" is the Window
            var callback = function () {
                return self.checkSettingsAndRedirect(isSetup,isAdmin)
            };
            this.trigger("modal:forecastsConfig:open", params, callback);
        } else {
            var alert = app.alert.show('no_access_error', {
                    level:'error',
                    messages:app.lang.get("LBL_FORECASTS_CONFIG_USER_SPLASH", "Forecasts"),
                    title:app.lang.get("LBL_FORECASTS_CONFIG_TITLE", "Forecasts") + ":"}
            );
            alert.getCloseSelector().on('click', function () {
                return self.checkSettingsAndRedirect(isSetup,isAdmin);
            })
        }

    },

    /**
     * Checks the is_setup config setting and determines where to send the user
     * @param isSetup variable to show whether
     * @param isAdmin
     */
    checkSettingsAndRedirect:function (isSetup, isAdmin) {
        var state = {
                isSetup: isSetup,
                isAdmin: isAdmin,
                saveClicked: this.context.forecasts.get('saveClicked')
            },
            location = this.getRedirectURL(state),
            self = this;

        /**
         * 2 conditions exist here.
         * 1) If the user is an admin and clicked save, then messages are displayed
         *    and the module is reloaded
         * 2) Otherwise, the user is not an admin or has clicked cancel/X, in which case
         *    we reload the module or forward them to the home module immediately.  Which
         *    is to occur is determined by the getRedirectURL
         */
        if(isAdmin && state.saveClicked == true) {
            // only sync the metadata
            app.metadata.sync();
            // can happen on both views but it's the same methods/messages
            // we have a success save, so we need to call the app.metadata.sync() and then redirect back to the index
            if(!isSetup){
                //issue notice about setting up Opportunities
                var alert = app.alert.show('forecast_opp_notice', {
                    level:'confirmation',
                    showCancel:false,
                    messages: app.lang.get("LBL_FORECASTS_WIZARD_REFRESH_NOTICE", "Forecasts")
                });

                //add alert listener for the close click, in case user clicks the X instead of the confirm button.
                alert.getCloseSelector().on('click', function() {
                    self.displaySuccessAndReload(location);
                });
            } else {
                this.displaySuccessAndReload(location);
            }
        } else {
            window.location = location;
        }
    },

    /**
     * Checks the variables provided to determine what the reload/forward location ought to be
     *
     * @param state object consisting of values of the current app state(isAdmin, isSetup, saveClicked, etc)
     * @return {string} url to send page to
     */
    getRedirectURL:function (state) {
        /**
         * 3 conditions exist here
         * 1a: If the user is not an admin, then the user will be redirected to the main Sugar index
         * 1b: User is an admin, and has clicked cancel/X to close the modal without saving
         *      and module has never been set up
         * 2: The user is an admin and forecasts has been setup.  At this point, it won't
         *    matter if cancel was clicked or save, the result is the same, the location
         *    to redirect to will be to reload the forecasts module
         */
        if (!state.isAdmin || (state.isAdmin && state.isSetup == 0 && state.saveClicked == false)) {
            // this should only ever happen on the wizard view and if the user accessing is not an admin
            return 'index.php?module=Home';
        } else {
            return 'index.php?module=Forecasts';
        }
    },

    /**
     * Displays an alert  and reloads the page
     */
    displaySuccessAndReload:function (location) {
        var alert = app.alert.show('success', {
            level: 'success',
            autoClose: true,
            closeable: false,
            showCancel: false,
            onAutoClose: function() {
                window.location = location;
            },
            title: app.lang.get("LBL_FORECASTS_WIZARD_SUCCESS_TITLE", "Forecasts") + ":",
            messages: [app.lang.get("LBL_FORECASTS_WIZARD_SUCCESS_MESSAGE", "Forecasts")]
        });

        alert.getCloseSelector().on('click', function() {
            window.location = location;
        });
    }
})
