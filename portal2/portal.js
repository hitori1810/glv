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

    // Add custom events here for now
    app.events.on("app:init", function() {

        // Load dashboard route.
        app.router.route("", "dashboard", function() {
            app.controller.loadView({
                layout: "dashboard"
            });
        });

        // Load the search results route.
        app.router.route("search/:query", "search", function(query) {
            // For Safari and FF, the query always comes in as URI encoded.
            // Decode here so we don't accidently double encode it later. (bug55572)
            try{
                var decodedQuery = decodeURIComponent(query);
                app.controller.loadView({
                    mixed: true,
                    module: "Search",
                    layout: "search",
                    query: decodedQuery,
                    skipFetch: true
                });
            }catch(err){
                // If not a validly encoded URI, decodeURIComponent will throw an exception
                // If URI is not valid, don't navigate.
                app.logger.error("Search term not a valid URI component.  Will not route search/"+query);
            }

        });

        // Load the profile
        app.router.route("profile", "profile", function() {
            app.controller.loadView({
                layout: "profile",
                module: "Contacts",
                modelId: app.user.get("id")
            });
        });
        // Loadds profile edit
        app.router.route("profile/edit", "profileedit", function() {
            app.controller.loadView({
                layout: "profile-edit",
                module: "Contacts",
                modelId: app.user.get("id")
            });
        });
    });

    // bug57318: Mulitple alert warning when multiple views get render denied on same page.
    var oHandleRenderError = app.error.handleRenderError;
    app.error.handleRenderError = function(component, method, additionalInfo) {
        function handlePortalRenderDenied(c) {
            var title, message;
            title = app.lang.getAppString('ERR_NO_VIEW_ACCESS_TITLE');
            message = app.utils.formatString(app.lang.getAppString('ERR_NO_VIEW_ACCESS_MSG'),[c.module]);
            // TODO: We can later create some special case handlers if we DO wish to alert warn,
            // but since we have recursive views that's usually going to be overbearing.
            app.logger.warn(title + ":\n" + message);
        }
        // Only hijack view_render_denied error case, otherwise, delegate all else to sidecar handler
        if(method === 'view_render_denied') {
            handlePortalRenderDenied(component);
        } else {
            oHandleRenderError(component, method, additionalInfo);
        }
    };

    var oRoutingBefore = app.routing.before;
    app.routing.before = function(route, args) {
        var dm, nonModuleRoutes;
        nonModuleRoutes = [
            "search",
            "error",
            "profile",
            "profileedit",
            "logout"
        ];

        app.logger.debug("Loading route. " + (route?route:'No route or undefined!'));

        if(!oRoutingBefore.call(this, route, args)) return false;

        function alertUser(msg) {
            // TODO: Error messages should later be put in lang agnostic app strings. e.g. also in layout.js alert.
            msg = msg || "LBL_PORTAL_MIN_MODULES";

            app.alert.show("no-sidecar-access", {
                level: "error",
                title: app.lang.getAppString("LBL_PORTAL_ERROR"),
                messages: [app.lang.getAppString(msg)]
            });
        }

        // Handle index case - get default module if provided. Otherwise, fallback to Home if possible or alert.
        if (route === 'index') {
            dm = typeof(app.config) !== undefined && app.config.defaultModule ? app.config.defaultModule : null;
            if (dm && app.metadata.getModule(dm) && app.acl.hasAccess('read', dm)) {
                app.router.list(dm);
            } else if (app.acl.hasAccess('read', 'Home')) {
                app.router.index();
            } else {
                alertUser();
                return false;
            }
            // If route is NOT index, and NOT in non module routes, check if module (args[0]) is loaded and user has access to it.
        } else if (!_.include(nonModuleRoutes, route) && args[0] && !app.metadata.getModule(args[0]) || !app.acl.hasAccess('read', args[0])) {
            app.logger.error("Module not loaded or user does not have access. ", route);
            alertUser("LBL_PORTAL_ROUTE_ERROR");
            return false;
        }
        return true;
    };

    app.view.SupportPortalField = app.view.Field.extend({
        
        /**
         * Handles how validation errors are appended to the email "sub fields" (inputs).
         *
         * @param {Object} errors hash of validation errors
         */
        handleEmailValidationError: function(emailErrorsArray) {
            var self = this, emails;
            this.$el.find('.control-group.email').removeClass("error");
            emails = this.$el.find('.existing .email');
            
            // Remove any and all previous exclamation then add back per field error
            $(emails).removeClass("error").find('.add-on').remove();

            // For each error add to error help block
            _.each(emailErrorsArray, function(emailWithError, i) {

                // For each of our "sub-email" fields
                _.each(emails, function(e) {
                    var emailFieldValue = $(e).data('emailaddress');

                    // if we're on an email sub field where error occured, add error help block
                    if(emailFieldValue === emailWithError) {
                        
                        // First remove in case already there and then add back. Note add-on and help-block are adjacent
                        $(e).addClass("error").find('.row-fluid .help-block').remove().find('add-on').remove();
                        $(e).find('.row-fluid')
                            .append('<span class="add-on"><i class="icon-exclamation-sign"></i></span><p class="help-block">'+app.error.getErrorString('email', [emailFieldValue])+'</p>');
                    }
                });
            });
        },

        /**
         * Handles how validation errors are appended to the fields dom element
         *
         * By default errors are appended to the dom into a .help-block class if present
         * and the .error class is added to any .control-group elements in accordance with
         * bootstrap.
         *
         * @param {Object} errors hash of validation errors
         */
        handleValidationError: function(errors) {
            var self = this;

            // Email is special case as each input email is a sort of field within the one email 
            // field itself; and we need to append errors directly beneath said sub-fields
            if(self.type==='email' && self.view.name != "signup") {
                self.handleEmailValidationError(errors.email);
                return;
            }

            // need to add error styling to parent view element
            this.$el.parents('.control-group').addClass("error");
            var ftag = this.fieldTag || '';

            // Reset Field
            if (this.$el.parents('.control-group').find('.input-append').length > 0) {
                this.$el.unwrap()
            }
            self.$('.help-block').html('');
            // Remove previous exclamation then add back.
            this.$('.add-on').remove();


            // Add error styling
            this.$el.wrap('<div class="input-append  '+ftag+'">');
            // For each error add to error help block
            _.each(errors, function(errorContext, errorName) {
                self.$('.help-block').append(app.error.getErrorString(errorName, errorContext));
            });
            $('<span class="add-on"><i class="icon-exclamation-sign"></i></span>').insertBefore(this.$('.help-block'));
        },


        bindDomChange: function() {
            if (!(this.model instanceof Backbone.Model)) return;
            var self = this;
            var el = this.$el.find(this.fieldTag);
            // need to clear error styling on data change
            el.on("change", function() {
                self.$el.parent().parent().removeClass("error");
            });
            app.view.Field.prototype.bindDomChange.call(this);
        }
    });

    app.Controller = app.Controller.extend({
        loadView: function(params) {
            var self = this, 
                callbackAppNotAvailable, options;

            // If login page request we always need to present the login page, but we 
            // also must deal with status 'offline' which means portal not enabled.
            if (params.layout === 'login') {
                app.Controller.__super__.loadView.call(this, params);
            }

            if (app.config && app.config.appStatus == 'offline') {

                // We only want to redirect back to login if not already on login!
                if (params.layout !== 'login') {
                    options = {
                        module: "Login",
                        layout: "login",
                        create: true
                    };
                    app.Controller.__super__.loadView.call(self, options);
                }

                callbackAppNotAvailable = function(data) {
                    app.alert.show('appOffline', {
                        level: "error",
                        title: app.lang.getAppString('LBL_PORTAL_ERROR'),
                        messages: app.lang.getAppString('LBL_PORTAL_OFFLINE'),
                        autoclose: false
                    });
                };
                if(app.api.isAuthenticated()) {
                    app.logout({success: callbackAppNotAvailable, error: callbackAppNotAvailable}, {clear:true});
                } else {
                    callbackAppNotAvailable();
                }
                return;
            } 

            // If it wasn't login and wasn't offline we just load'er up
            if (params.layout !== 'login') {
                app.Controller.__super__.loadView.call(this, params);
            }
        }
    });


    /**
     * Extends the `save` action to add `portal` specific params to the payload.
     *
     * @param {Object} attributes(optional) model attributes
     * @param {Object} options(optional) standard save options as described by Backbone docs and
     * optional `fieldsToValidate` parameter.
     */
    var __superBeanSave__ = app.Bean.prototype.save;
    app.Bean.prototype.save = function(attributes, options) {
        //Here is the list of params that must be set for portal use case.
        var defaultParams = {
            portal_flag: 1,
            portal_viewable: 1
        };
        var moduleFields = app.metadata.getModule(this.module).fields || {};
        for (var field in defaultParams) {
            if (moduleFields[field]) {
                this.set(field, defaultParams[field], {silent:true});
            }
        }
        //Call the prototype
        __superBeanSave__.call(this, attributes, options);
    };

    var _rrh = {
        /**
         * Handles `signup` route.
         */
        signup: function() {
            app.logger.debug("Route changed to signup!");
            app.controller.loadView({
                module: "Signup",
                layout: "signup",
                create: true
            });
        }
    };

    app.events.on("app:init", function() {
        // Register portal specific routes
        app.router.route("signup", "signup", _rrh.signup);
    });

    /**
     * Checks if there are `file` type fields in the view. If yes, process upload of the files
     *
     * @param {Object} model Model
     * @param {callbackAppNotAvailable} callbackAppNotAvailable(optional) success and error callbackAppNotAvailable
     */
    // TODO: This piece of code may move in the core files
    app.view.View.prototype.checkFileFieldsAndProcessUpload = function(model, callbackAppNotAvailable) {
        var file, $file, $files, filesToUpload, fileField, successFn, errorFn;

        callbackAppNotAvailable = callbackAppNotAvailable || {};

        // Check if there are attachments
        $files = _.filter($(":file"), function(file) {
            var $file = $(file);
            return ($file.val() && $file.attr("name") && $file.attr("name") !== "") ? $file.val() !== "" : false;
        });

        filesToUpload = $files.length;

        successFn = function() {
            filesToUpload--; 
            if (filesToUpload===0) {
                app.alert.dismiss('upload'); 
                if (callbackAppNotAvailable.success) callbackAppNotAvailable.success();
            }
        };

        errorFn = function(error) {
            var errors = {};
            
            // Set model to new by removing it's id attribute. Note that in our initial attempt
            // to upload file(s) we set delete_if_fails true so server has marked record deleted: 1
            // Since we may have only create privs (e.g. we can't edit/delete Notes), we'll start anew.  
            model.unset('id', {silent: true});

            // All or nothing .. if uploading 1..* attachments, if any one fails the whole atomic
            // operation has failed; so we really want to trigger error and possibly and start over.
            filesToUpload = 0;
            app.alert.dismiss('upload');
            errors[error.responseText] = {};
            model.trigger('error:validation:' + this.field, errors);
            model.trigger('error:validation');
        };

        // Process attachment uploads
        if (filesToUpload > 0) {
            app.alert.show('upload', {level: 'process', title: app.lang.get('LBL_UPLOADING', model.module), autoclose: false});

            // Field by field
            for (file in $files) {
                $file = $($files[file]);
                fileField = $file.attr("name");

                model.uploadFile(fileField, $file, {
                    field: fileField,
                    success: successFn,
                    error: errorFn
                });
            }
        } else {
            if (callbackAppNotAvailable.success) callbackAppNotAvailable.success();
        }
    };

})(SUGAR.App);
