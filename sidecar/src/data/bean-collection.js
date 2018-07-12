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
 * Base bean collection class.
 *
 * **Filtering and searching**
 *
 * The collection's {@link Data.BeanCollection#fetch} method supports filter and seach options.
 * For example, to search favorite accounts that have `"Acme"` string in their name:
 * <pre><code>
 * (function(app) {
 *
 *     var accounts = app.data.createBeanCollection("Accounts");
 *     accounts.fetch({
 *         favorites: true,
 *         query: "Acme"
 *     });
 *
 * })(SUGAR.App);
 * </code></pre>
 *
 *
 * @class Data.BeanCollection
 * @alias SUGAR.App.BeanCollection
 * @extends Backbone.Collection
 */
(function(app) {

    app.augment("BeanCollection", Backbone.Collection.extend({
        constructor: function(models, options) {
            if (options && options.link) {
                this.link = options.link;
                delete options.link;
            }
            Backbone.Collection.prototype.constructor.call(this, models, options);
        },

        _prepareModel: function(model, options) {
            var acl = model._acl,
                searchInfo = model._search;
            delete model._acl;
            delete model._search;

            model = Backbone.Collection.prototype._prepareModel.call(this, model, options);
            if (model && !model.link) model.link = this.link;
            if (acl) {
                /**
                 * Record ACLs if different from module ACLs.
                 * @member Data.Bean
                 * @property {Object}
                 * @private
                 */
                model._acl = acl;
            }
            if (searchInfo) {
                /**
                 * FTS search results.
                 * @member Data.Bean
                 * @property {Object}
                 *
                 * Example:
                 * <pre>
                 * {
                 *   highlighted: {
                 *      account_name: {
                 *        label: "LBL_ACCOUNT_NAME",
                 *        module: "Leads",
                 *        text: "Kings Royalty &lt;span class="highlight"&gt;Trust&lt;/span&gt;"
                 *      }
                 *    },
                 *    score: 1
                 * }
                 * </pre>
                 */
                model.searchInfo = searchInfo;
            }
            return model;
        },

        /**
         * Fetches beans.
         *
         * @param options(optional) fetch options
         *
         * - relate: boolean flag indicating that relationships should be fetched.
         * - myItems: boolean flag indicating to fetch records assigned to the current user only
         * - favorites: boolean flag indicating to fetch favorites
         * - query: search query string
         * - add: boolean flag indicating if new records should be appended to the collection.
         * - success: success callback.
         * - error: error callback.
         *
         * See {@link Data.BeanCollection#paginate} for details about pagination options.
         */
        fetch: function(options) {
            options = options || {};
            /**
             * Field names.
             *
             * A list of fields that are populated on collection members.
             * This property is used to build `fields` URL parameter when fetching beans.
             * @member Data.BeanCollection
             * @property {Array}
             */
            options.fields = this.fields = options.fields || this.fields || null;

            options.myItems = _.isUndefined(options.myItems) ? this.myItems : options.myItems;
            options.favorites = _.isUndefined(options.favorites) ? this.favorites : options.favorites;
            options.query = _.isUndefined(options.query) ? this.query : options.query;

            return Backbone.Collection.prototype.fetch.call(this, options);
        },

        /**
         * Paginates a collection.
         *
         * @param options(optional) fetch options
         *
         * - page: page index (integer) from the current page to paginate to.
         *
         * For other options see {@link Data.BeanCollection#fetch} method.
         */
        paginate: function(options) {
            options = options || {};
            options.page = options.page || 1;

            // fix page number since our offset is already at the end of the collection subset
            options.page--;

            if (app.config.maxQueryResult) {
                options.offset = this.offset + (options.page * app.config.maxQueryResult);
            }

            this.fetch(options);
        },

        /**
         * Gets the current page of collection being displayed depending on the offset.
         * @return {Number} current page number.
         */
        getPageNumber: function() {
            var pageNumber = 1;
            if (this.offset && app.config.maxQueryResult) {
                pageNumber = Math.ceil(this.offset / app.config.maxQueryResult);
            }
            return pageNumber;
        },

        /**
         * Returns string representation useful for debugging:
         * <code>coll:[module-name]-[length]</code>  or
         * <code>coll:[related-module-name]/[id]/[module-name]-[length]</code> if it's a collection of related beans.
         * @return {String} string representation of this collection.
         */
        toString: function() {
            return "coll:" + (this.link ?
                (this.link.bean.module + "/" + this.link.bean.id + "/") : "") +
                this.module + "-" + this.length;
        }

    }), false);

}(SUGAR.App));
