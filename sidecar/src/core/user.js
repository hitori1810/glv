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

(function(app) {

    /**
     * Represents application's current user object.
     *
     * The user object contains settings that are fetched from the server
     * and whatever settings application wants to store.
     *
     * <pre><code>
     * // Sample user object that is fetched from the server:
     * {
     *      id: "1",
     *      full_name: "Administrator",
     *      user_name: "admin",
     *      preferences: {
     *          timezone: "America\/Los_Angeles",
     *          datepref: "m\/d\/Y",
     *          timepref: "h:ia"
     *      }
     * }
     *
     * // Use it like this:
     * var userId = SUGAR.App.user.get('id');
     * // Set app specific settings
     * SUGAR.App.user.set("sortBy:Cases", "case_number");
     *
     * // Bind event handlers if necessary
     * SUGAR.App.user.on("change", function() {
     *     // Do your thing
     * });
     * 
     * </code></pre>
     *
     * @class Core.User
     * @singleton
     * @alias SUGAR.App.user
     */
    var User = Backbone.Model.extend({

        load: function(callback) {
            app.api.me("read", null, null, {
                success: function(data) {
                    if (data.current_user) {
                        app.user.set(data.current_user);
                        var language = app.user.getPreference('language');
                        if (app.lang.getLanguage() != language) {
                            app.lang.setLanguage(language, null, {noUserUpdate: true, noSync: true});
                        }
                    }
                    if (callback) callback();
                },
                error: function(err) {
                    app.error.handleHttpError(err);
                    if (callback) callback(err);
                }
            });
        },

        /**
         * Updates the preferred language of the user.
         *
         * @param {String} language language Key
         * @param {Function} callback(optional) Callback called when update completes.
         */
        getLanguage: function() {
            return app.user.getPreference('language') || app.cache.get("lang");
        },

        /**
         * Updates the preferred language of the user.
         *
         * @param {String} language language Key
         * @param {Function} callback(optional) Callback called when update completes.
         */
        updateLanguage: function(language, callback) {

            var done = function() {
                app.lang.updateLanguage(language);
                if (callback) callback();
            };

            if (app.api.isAuthenticated()) {
                // if we're authenticated update the current user
                // TODO update preferred_language to language once it's moved to preferences table
                app.api.me("update", {preferred_language: language}, null, {
                    success: function() {
                        done();
                    },
                    error: function(err) {
                        app.error.handleHttpError(err);
                        if (callback) callback(err);
                    }
                });
            }
            else {
                done();
            }
        },

        /**
         * Gets ACLs.
         *
         * @return Dictionary of ACLs. Precondition - user's logged in or a _reset call has set the user manually.
         */
        getAcls: function() {
            return app.user.get('acl') || {};
        },

        /**
         * Get preference by name.
         *
         * TODO support category parameter for preferences.
         *
         * @param {String} name The preference name.
         * @return {Array/Object/String/Number/Boolean} The value of the user preference.
         */
        getPreference: function(name) {

            var preferences = app.user.get('preferences') || {};
            return preferences[name];
        },

        /**
         * Set preference by name, will only be stored locally.
         *
         * TODO support category parameter for preferences.
         * TODO support save preferences on server.
         *
         * @param {String} name The preference name.
         * @param {Array/Object/String/Number/Boolean} value The new value of the user preference.
         * @return {Object} the instance of this user.
         */
        setPreference: function(name, value) {
            var preferences = app.user.get('preferences') || {};
            preferences[name] = value;
            return app.user.set('preferences', preferences);
        }
    });

    app.events.on("app:logout", function(clear) {
        if (clear === true) {
            app.user.clear({silent:true});
        }
    });

    app.augment("user", new User(), false);

})(SUGAR.App);
