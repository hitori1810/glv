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
    events: {
        "click .login-submit": "login",
        "click [name=signup_button]": "signup",
        "keypress form" : "submitOnEnter"
    },
    initialize: function(options) {
        // Adds the metadata for the Login module
        app.metadata.set(this._metadata);
        app.data.declareModels();

        // Reprepare the context because it was initially prepared without metadata
        app.controller.context.prepare(true);

        // Attach the metadata to the view
        this.options.meta = this._metadata.modules[this.options.module].views[this.options.name].meta;
        app.view.View.prototype.initialize.call(this, options);

        // use modal template for the fields
        this.fallbackFieldTemplate = "modal";
    },
    render: function() {
        if (app.config && app.config.logoURL) {
            this.logoURL = app.config.logoURL;
        }
        app.view.View.prototype.render.call(this);
        /**
         * Added browser version check for MSIE since we are dropping support
         * for MSIE 8.0 for portal in Sugar 6.6  (Bug56031)
         */
        if (!this._isSupportedBrowser()) {
            app.alert.show('unsupported_browser', {
                level:'warning',
                title: '',
                messages: new Handlebars.SafeString(app.lang.getAppString('WARN_BROWSER_VERSION_WARNING'))
            });
        }
        return this;
    },
    login: function() {
        var self = this;
        // hack - for some unknown reason, app is undefined in success/error hooks below. This rectifies that.
        self.__app = app;

        if (this.model.isValid()) {
            $('#content').hide();
            app.alert.show('login', {level:'process', title:app.lang.getAppString('LBL_PORTAL_LOADING'), autoClose:false});
            var args = {password: this.model.get("password"), username: this.model.get("username")};

            app.login(args, null, {
                error: function() {
                    var app = self.__app;
                    app.alert.dismiss('login');
                    $('#content').show();
                },
                success: function() {
                    var app = self.__app;
                    app.events.on('app:sync:complete', function() {
                        app.alert.dismiss('login');
                        $(".navbar").show();
                        $('#content').show();
                    });
                }
            });
        }
    },
    submitOnEnter: function(event) {
        if (event.which == 13 || event.keyCode == 13) {
            this.$('input,select').blur();
            this.login();
         }
    },
    signup : function() {
        app.router.navigate('#signup');
        app.router.start();
    },
    /**
     * Taken from sugar_3. returns true if the users browser is recognized
     * @return {Boolean}
     * @private
     */
    _isSupportedBrowser:function () {
        var supportedBrowsers = {
            msie:{min:9},
            safari:{min:500},
            mozilla:{min:13},
            chrome:{min:500}
        };
        for (var b in supportedBrowsers) {
            if ($.browser[b]) {
                var current = parseInt($.browser.version);
                var supported = supportedBrowsers[b];
                return current >= supported.min && (!supported.max || current <= supported.max);
            }
        }
    },
    _metadata : {
        _hash: '',
        "modules": {
            "Login": {
                "fields": {
                    "username": {
                        "name": "username",
                        "type": "varchar",
                        "required": true
                    },
                    "password": {
                        "name": "password",
                        "type": "password",
                        "required": true
                    }
                },
                "views": {
                    "login": {
                        "meta": {
                            "buttons": [
                                {
                                    name: "login_button",
                                    type: "button",
                                    label: "LBL_LOGIN_BUTTON_LABEL",
                                    'class': "login-submit pull-right",
                                    value: "login",
                                    primary: true
                                },
                                {
                                    name: "signup_button",
                                    type: "button",
                                    label: "LBL_SIGNUP_BUTTON_LABEL",
                                    value: "signup",
                                    'class': 'pull-left'
                                }
                            ],
                            "panels": [
                                {
                                    "fields": [
                                        {name: "username", label: "LBL_PORTAL_LOGIN_USERNAME"},
                                        {name: "password", label: "LBL_PORTAL_LOGIN_PASSWORD"}
                                    ]
                                }
                            ]
                        }
                    }
                },
                "layouts": {
                    "login": {
                        "meta": {
                            //Default layout is a single view
                            "type": "simple",
                            "components": [
                                {view: "login"}
                            ]
                        }
                    }
                }
            }
        }
    }
})
