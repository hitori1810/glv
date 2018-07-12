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
     * Manages routing behavior.
     *
     *
     * The default implementation provides `before` and `after` callbacks that are executed
     * before and after a route gets triggered.
     *
     * @class Core.Routing
     * @singleton
     * @alias SUGAR.App.routing
     */
    app.augment("routing", {

        /**
         * Checks if a user is authenticated before triggering a route.
         * @param {String} route Route name.
         * @param args(optional) Route parameters.
         * @return {Boolean} Flag indicating if the route should be triggered (`true`).
         */
        before: function(route, args) {
            // skip this check for all white-listed routes (app.config.unsecureRoutes)]
            if (_.indexOf(app.config.unsecureRoutes, route) >= 0) return true;

            // Check if a user is un-athenticated and redirect him to login
            if (!app.api.isAuthenticated()) {
                app.router.login();
                return false;
            }
            else if (!app.isSynced) {
                Backbone.history.stop();
                app.sync();
                return false;
            }
            return true;
        },

        /**
         * Gets called after a route gets triggered.
         *
         * The default implementation does nothing.
         * @param {String} route Route name.
         * @param args(optional) Route parameters.
         */
        after: function(route, args) {
            // Do nothing
        }

    });

    /**
     * Router manages the watching of the address hash and routes to the correct handler.
     * @class Core.Router
     * @singleton
     * @alias SUGAR.App.router
     */
    var Router = Backbone.Router.extend({

        /**
         * Routes hash map.
         * @property {Object}
         */
        routes: {
            "": "index",
            "logout": "logout",
            "logout/?clear=:clear": "logout",
            ":module": "list",
            ":module/layout/:view": "layout",
            ":module/create": "create",
            ":module/:id/:action": "record",
            ":module/:id": "record"
        },

        /**
         * Calls {@link Core.Routing#before} before invoking a route handler
         * and {@link Core.Routing#after} after the handler is invoked.
         *
         * @param {Function} handler Route callback handler.
         * @private
         */
        _routeHandler: function(handler) {
            var args = Array.prototype.slice.call(arguments, 1),
                route = handler.route;

            if (app.routing.before(route, args)) {
                handler.apply(this, args);
                app.routing.after(route, args);
            }
        },

        /**
         * Registeres a handler for a named route.
         *
         * This method wraps the handler into {@link Core.Router#_routeHandler} method.
         *
         * @param {String} route Route expression.
         * @param {String} name Route name.
         * @param {Function/String} callback Route handler.
         */
        route: function(route, name, callback) {
            if (!callback) callback = this[name];
            callback.route = name;
            callback = _.wrap(callback, this._routeHandler);
            Backbone.Router.prototype.route.call(this, route, name, callback);
        },

        /**
         * Navigates to the previous route in history.
         *
         * This method triggers route change event.
         */
        goBack: function() {
            app.logger.debug("Navigating back...");
            window.history.back();
        },

        /**
         * Navigates the window history.
         *
         * @param {Number} steps Number of steps to navigate (can be negative).
         */
        go: function(steps) {
            window.history.go(steps);
        },

        /**
         * Starts Backbone history which in turn starts routing the hashtag.
         *
         * See Backbone.history documentation for details.
         */
        start: function() {
            app.logger.info("Router Started");
            Backbone.history.stop();
            return Backbone.history.start();
        },

        /**
         * Builds a route.
         *
         * This is a convenience function.
         *
         * @param {Object/String} moduleOrContext The name of the module or a context object to extract the module from.
         * @param {String} id The model's ID.
         * @param {String} action(optional) Action name.
         * @param {Object} params(optional) Additional URL parameters. Should not include id/module/action.
         * @return {String} route The built route.
         */
        buildRoute: function(moduleOrContext, id, action, params) {
            var route;

            if (moduleOrContext) {
                // If module is a context object, then extract module from it
                route = (_.isString(moduleOrContext)) ? moduleOrContext : moduleOrContext.get("module");

                if (id) {
                    route += "/" + id;
                }

                if (action) {
                    route += "/" + action;
                }
            } else {
                route = action;
            }

            // TODO: Currently not supported and breaks other routes
//            if (params && _.isObject(params) && !_.isEmpty(params)) {
//                route += "?" + $.param(params);
//            }

            return route;
        },

        // ----------------------------------------------------
        // Route handlers
        // ----------------------------------------------------

        /**
         * Handles `index` route.
         *
         * Loads `home` layout for `Home` module or `list` route with default module defined in app.config
         */
        index: function() {
            app.logger.debug("Route changed to index");
            if (app.config.defaultModule) {
                this.list(app.config.defaultModule);
            }
            else if (app.acl.hasAccess('read', 'Home')) {
                this.layout("Home", "home");
            }
        },

        /**
         * Handles `list` route.
         * @param module Module name.
         */
        list: function(module) {
            app.logger.debug("Route changed to list of " + module);
            app.controller.loadView({
                module: module,
                layout: "list"
            });
        },

        /**
         * Handles arbitrary layout for a module that doesn't have a record associated with the layout.
         * @param module Module name.
         * @param layout Layout name.
         */
        layout: function(module, layout) {
            app.logger.debug("Route changed to layout: " + layout + " for " + module);
            app.controller.loadView({
                module: module,
                layout: layout
            });
        },

        /**
         * Handles `create` route.
         * @param module Module name.
         */
        create: function(module) {
            app.logger.debug("Route changed: create " + module);
            app.controller.loadView({
                module: module,
                create: true,
                layout: "edit"
            });
        },

        /**
         * Handles `login` route.
         */
        login: function() {
            app.logger.debug("Logging in");
            app.controller.loadView({
                module: "Login",
                layout: "login",
                create: true
            });
        },

        /**
         * Handles `logout` route.
         */
        logout: function(clear) {
            clear = clear == "1" ? true : false;
            app.logger.debug("Logging out: " + clear);
            app.logout({
                complete: function() {
                    app.router.navigate("#");
                    app.router.login();
                }
            }, clear);
        },

        /**
         * Handles `record` route.
         * @param module Module name.
         * @param id Record ID.
         * @param action(optional) Action name (`edit`, etc.). Defaults to `detail` if not specified.
         */
        record: function(module, id, action) {
            app.logger.debug("Route changed: " + module + "/" + id + "/" + action);

            action = action || "detail";
            app.controller.loadView({
                module: module,
                modelId: id,
                action: action,
                layout: action
            });
        
        }
        
    });

    app.augment("router", new Router(), false);

})(SUGAR.App);

