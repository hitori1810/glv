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
 * View that displays header for current app
 * @class View.Views.HeaderView
 * @alias SUGAR.App.layout.HeaderView
 * @extends View.View
 */
    events: {
        'click #module_list li a': 'onModuleTabClicked',
        'click #createList li a': 'onCreateClicked',
        'click .typeahead a': 'clearSearch',
        'click .navbar-search .btn': 'gotoFullSearchResultsPage'
    },

    /**
     * Renders Header view
     */
    initialize: function(options) {
        app.events.on("app:sync:complete", this.render, this);
        app.events.on("app:logout", this.render, this);
        app.view.View.prototype.initialize.call(this, options);
    },
    _renderHtml: function() {
        var self = this,
            menuTemplate;

        if (!app.api.isAuthenticated() || app.config.appStatus == 'offline') {
            this.$el.empty();
            return;
        }

        self.setModuleInfo();
        self.setCreateTasksList();
        self.setCurrentUserName();
        app.view.View.prototype._renderHtml.call(self);

        // Search ahead drop down menu stuff
        menuTemplate = app.template.getView('dropdown-menu');

        this.$('.search-query').searchahead({
            request:  self.fireSearchRequest,
            compiler: menuTemplate,
            throttleMillis: (app.config.requiredElapsed || 500),
            throttle: function(callback, millis) {
               if(!self.debounceFunction) {
                    self.debounceFunction = _.debounce(function(){
                        callback();
                    }, millis || 500);
                } 
                self.debounceFunction();
            },
            onEnterFn: function(hrefOrTerm, isHref) {
                // if full href treat as user clicking link
                if(isHref) {
                    window.location = hrefOrTerm;
                } else {
                    // It's the term only (user didn't select from drop down
                    // so this is essentially the term typed
                    app.router.navigate('#search/'+hrefOrTerm, {trigger: true});
                }
            }
        });
    },
    /** 
     * Callback for the searchahead plugin .. note that
     * 'this' points to the plugin (not the header view!)
     */
    fireSearchRequest: function (term) {
        var plugin = this, mlist, params;

        mlist = app.metadata.getModuleNames(true).join(','); // visible
        params = {q: term, fields: 'name, id', module_list: mlist, max_num: app.config.maxSearchQueryResult};

        app.api.search(params, {
            success:function(data) {
                data.module_list = app.metadata.getModuleNames(true,"create");
                plugin.provide(data);
            },
            error:function(error) {
                app.error.handleHttpError(error, plugin);
                app.logger.error("Failed to fetch search results in search ahead. " + error);
            }
        });
    },
    /**
     * Takes user to full search results page 
     */
    gotoFullSearchResultsPage: function(evt) {
        var term;
        // Don't let plugin kick in. Navigating directly to search results page
        // when clicking on adjacent button is, to my mind, special case portal
        // application requirements so I'd rather do here than change plugin.
        evt.preventDefault();
        evt.stopPropagation();

        // URI encode search query string so that it can be safely
        // decoded by search handler (bug55572)
        term = encodeURIComponent(this.$('.search-query').val());

        if(term && term.length) {
            // Bug 57853 Shouldn't show the search result pop up window after click the global search button.
            // This prevents anymore dropdowns (note we re-init if/when _renderHtml gets called again)
            this.$('.search-query').searchahead('disable', 1000);
            app.router.navigate('#search/'+term, {trigger: true});
        }
    },

    /**
     * When user clicks tab navigation in header
     */
    onModuleTabClicked: function(evt) {
        evt.preventDefault();
        evt.stopPropagation();
        var moduleHref = this.$(evt.currentTarget).attr('href');
        this.$('#module_list li').removeClass('active');
        this.$(evt.currentTarget).parent().addClass('active');
        app.router.navigate(moduleHref, {trigger: true});
    },
    onCreateClicked: function(evt) {
        var moduleHref, hashModule;
        moduleHref = evt.currentTarget.hash;
        hashModule = moduleHref.split('/')[0];
        this.$('#module_list li').removeClass('active');
        this.$('#module_list li a[href="'+hashModule+'"]').parent().addClass('active');
    },
    hide: function() {
        this.$el.children().hide();
    },
    show: function() {
        this.$el.children().show();
    },
    setCurrentUserName: function() {
        this.fullName = app.user.get('full_name');
    },
    /**
     * Creates the task create drop down list 
     */
    setCreateTasksList: function() {
        var self = this, singularModules;
        self.createListLabels = [];

        try {
            singularModules = SUGAR.App.lang.getAppListStrings("moduleListSingular");
            if(singularModules) {
                self.createListLabels = this.creatableModuleList;
            }
        } catch(e) {
            return;
        }
    },
    setModuleInfo: function() {
        this.currentModule = this.module;
        this.module_list = [];
        var LBL_MODULE_NAME = app.lang.getAppString('LBL_MODULE_NAME');
        var module_list = app.metadata.getModuleNames(true, 'list');
        //use _.every in order to stop the loop if it detects modStrings are missing.
        _.every(module_list, function(module) {
            if (app.lang.get('LBL_MODULE_NAME', module) !== LBL_MODULE_NAME){
                this.module_list.push(module);
                return true;
            } else {
                return false;
            }
        }, this);
        this.creatableModuleList = _.intersection(this.module_list, app.metadata.getModuleNames(true,"create"));
    },

    /**
     * Clears out search upon user following search result link in menu
     */
    clearSearch: function(evt) {
        this.$('.search-query').val('');
    }

})
