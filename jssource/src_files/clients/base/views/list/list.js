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
 * @class View.Views.ListView
 * @alias SUGAR.App.layout.ListView
 * @extends View.View
 */
    events: {
        'click [class*="orderBy"]': 'setOrderBy',
        'mouseenter tr': 'showActions',
        'mouseleave tr': 'hideActions'
    },
    calculateRelativeWidths: function() {
        var totalWidth = 0;
        for (var p in this.meta.panels) {
            for (var f in this.meta.panels[p].fields) {
                var field = this.meta.panels[p].fields[f];
                totalWidth += parseInt(field.width) || 10;
            }
            var adjustment = 100 / totalWidth;
            //Adjust to make sure total is 100
            for (var f in this.meta.panels[p].fields) {
                var field = this.meta.panels[p].fields[f];
                field.width = Math.floor((parseInt(field.width) || 10) * adjustment);
            }
        }
    },
    _renderHtml: function() {
        
        //Calculate relative column widths.
        this.calculateRelativeWidths();

        //Set a flag true to the collection if there are new records (see list-bottom.js)
        //in order to animate the new records
        this.collection.newRecords = _.find(this.collection.models, function(model) {
            return model.old === true;
        })
        app.view.View.prototype._renderHtml.call(this);
        // off prevents multiple bindings for each render
        this.layout.off("list:search:fire", null, this);
        this.layout.off("list:paginate:success", null, this);
        this.layout.on("list:search:fire", this.fireSearch, this);
        this.layout.on("list:paginate:success", this.render, this);
        this.layout.off("list:filter:toggled", null, this);
        this.layout.on("list:filter:toggled", this.filterToggled, this);

        // Dashboard layout injects shared context with limit: 5. 
        // Otherwise, we don't set so fetches will use max query in config.
        this.limit = this.context.get('limit') ? this.context.get('limit') : null;
    },
    filterToggled: function(isOpened) {
        this.filterOpened = isOpened;
    },
    fireSearch: function(term) {
        var options = {
            limit: this.limit || null,
            params: { 
                q: term
            },
            fields: this.collection.fields || {}
        };
        this.collection.fetch(options);
    },

    /**
     * Sets order by on collection and view
     * @param {Object} event jquery event object
     */
    setOrderBy: function(event) {
        var orderMap, collection, fieldName, nOrder, options, eventTarget, orderBy;
        var self = this;
        //set on this obj and not the prototype
        self.orderBy = self.orderBy || {};

        //mapping for css
        orderMap = {
            "desc": "_desc",
            "asc": "_asc"
        };

        //TODO probably need to check if we can sort this field from metadata
        collection = self.collection;
        eventTarget = self.$(event.target);
        fieldName = eventTarget.data('fieldname');

        // first check if alternate orderby is set for column
        orderBy = eventTarget.data('orderby');
        // if no alternate orderby, use the field name
        if (!orderBy) {
            orderBy = eventTarget.data('fieldname');
        }

        if (!collection.orderBy) {
            collection.orderBy = {
                field: "",
                direction: "",
                columnName: ""
            };
        }

        nOrder = "desc";

        // if same field just flip
        if (orderBy === collection.orderBy.field) {
            if (collection.orderBy.direction === "desc") {
                nOrder = "asc";
            }
            collection.orderBy.direction = nOrder;
        } else {
            collection.orderBy.field = orderBy;
            collection.orderBy.direction = "desc";
        }
        collection.orderBy.columnName = fieldName;

        // set it on the view
        self.orderBy.field = orderBy;
        self.orderBy.direction = orderMap[collection.orderBy.direction];
        self.orderBy.columnName = fieldName;

        // Treat as a "sorted search" if the filter is toggled open
        options = self.filterOpened ? self.getSearchOptions() : {};

        // If injected context with a limit (dashboard) then fetch only that 
        // amount. Also, add true will make it append to already loaded records.
        options.limit   = self.limit || null;

        // Display loading message
        app.alert.show('loading_' + self.cid, {level:'process', title:app.lang.getAppString('LBL_PORTAL_LOADING')});

        options.success = function() {
            // Hide loading message
            app.alert.dismiss('loading_' + self.cid);
            self.render();
        };
        
        // refetch the collection
        collection.fetch(options);
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
    showActions: function(e) {
        $(e.currentTarget).children("td").children("span").children(".btn-group").show();
    },
    hideActions: function(e) {
        $(e.currentTarget).children("td").children("span").children(".btn-group").hide();
    },
    bindDataChange: function() {
        if (this.collection) {
            this.collection.on("reset", this.render, this);
        }
    }
})

