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
 * View that displays a list of models pulled from the context's collection.
 * @class View.Views.ListViewBottom
 * @alias SUGAR.App.layout.ListViewBottom
 * @extends View.View
 */
    // We listen to event and keep track if search filter is toggled open/close
    filterOpened: false,
    events: {
        'click [name=show_more_button]': 'showMoreRecords',
        'click .search': 'showSearch'
    },
    _renderHtml: function() {
        if (app.acl.hasAccess('create', this.module)) {
            this.context.set('isCreateEnabled', true);
        }

        // Dashboard layout injects shared context with limit: 5. 
        // Otherwise, we don't set so fetches will use max query in config.
        this.limit = this.context.get('limit') ? this.context.get('limit') : null;

        app.view.View.prototype._renderHtml.call(this);

        // We listen for if the search filters are opened or not. If so, when 
        // user clicks show more button, we treat this as a search, otherwise,
        // normal show more for list view.
        this.layout.off("list:filter:toggled", null, this);
        this.layout.on("list:filter:toggled", this.filterToggled, this);
    },
    filterToggled: function(isOpened) {
        this.filterOpened = isOpened;
    },
    showMoreRecords: function(evt) {
        var self = this, options;
        // Mark current models as old, in order to animate the new one
        _.each(this.collection.models, function(model) {
            model.old = true;
        });
        
        // Display loading message
        app.alert.show('show_more_records_' + self.cid, {level:'process', title:app.lang.getAppString('LBL_PORTAL_LOADING')});
        
        // save current screen position
        var screenPosition = $(window).scrollTop();

        // If in "search mode" (the search filter is toggled open) set q:term param
        options = self.filterOpened ? self.getSearchOptions() : {};

        // Indicates records will be added to those already loaded in to view
        options.add = true;
            
        options.success = function() {
            // Hide loading message
            app.alert.dismiss('show_more_records_' + self.cid);
            self.layout.trigger("list:paginate:success");
            self.render();
            // retrieve old screen position
            window.scrollTo(0, screenPosition);

            // Animation for new records
            self.layout.$('tr.new').animate({
                opacity:1
            }, 500, function () {
                $(this).removeAttr('style class');
            });
        };
        options.limit = this.limit;
        this.collection.paginate(options);
    },
    showSearch: function() {
        // Toggle on search filter and off the pagination buttons
        this.$('.search').toggleClass('active');
        this.layout.trigger("list:search:toggle");
    },
    getSearchOptions: function() {
        var collection, options, previousTerms, term = '';
        collection = this.context.get('collection');

        // If we've made a previous search for this module grab from cache
        if(app.cache.has('previousTerms')) {
            previousTerms = app.cache.get('previousTerms');
            if(previousTerms) {
                term = previousTerms[this.module];
            } 
        }
        // build search-specific options and return
        options = {
            params: { 
                q: term
            },
            fields: collection.fields ? collection.fields : this.collection
        };
        return options;
    },
    bindDataChange: function() {
        if(this.collection) {
            this.collection.on("reset", this.render, this);
        }
    }

})
