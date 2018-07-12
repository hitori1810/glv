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
 * Manages bean model and collection classes.
 *
 * **Data manager provides:**
 *
 * - Interface to declare bean model and collection classes from metadata.
 * - Factory methods for creating instances of beans and bean collections.
 * - Factory methods for creating instances of bean relations and relation collections.
 * - Custom implementation of <code>Backbone.sync</code> pattern.
 *
 * **Data model metadata**
 *
 * Metadata that describes the data model contains information about module fields and its relationships.
 * From the following sample metadata, data manager would declare two classes: Opportunities and Contacts.
 * <pre><code>
 * var metadata =
 * {
 *   "modules": {
 *     "Opportunities": {
 *        "fields": {
 *            "name": { ... },
 *            ...
 *        }
 *      },
 *      "Contacts": { ... }
 *    }
 *    "relationships": {
 *        "opportunities_contacts": { ... },
 *         ...
 *    }
 *
 * }
 * </code></pre>
 *
 * **Working with beans**
 *
 * <pre><code>
 * (function(app) {
 *
 * // Declare bean classes from metadata payload.
 * // This method should be called at application start-up and whenever the metadata changes.
 * app.data.declareModels(metadata);
 * // You may now create bean instances using factory methods.
 * var opportunity = app.data.createBean("Opportunities", { name: "Cool opportunity" });
 * // You can save a bean using standard Backbone.Model.save method.
 * // The save method will use data manager's sync method to communicate changes to the remote server.
 * opportunity.save();
 *
 * // Create an empty collection of contacts.
 * var contacts = app.data.createBeanCollection("Contacts");
 * // Fetch a list of contacts
 * contacts.fetch();
 *
 * })(SUGAR.App);
 * </code></pre>
 *
 * **Working with relationships**
 *
 * <pre><code>
 * (function(app) {
 *
 * var attrs = {
 *   firstName: "John",
 *   lastName: "Smith",
 *   // relationship field
 *   opportunityRole: "Influencer"
 * }
 * // Create a new instance of a contact related to an existing opportunity
 * var contact = app.data.createRelatedBean(opportunity, null, "contacts", attrs);
 * // This will save the contact and create the relationship
 * contact.save(null, { relate: true });
 *
 * // Create an instance of contact collection related to an existing opportunity
 * var contacts = app.data.createRelatedCollection(opportunity, "contacts");
 * // This will fetch related contacts
 * contacts.fetch({ relate: true });
 *
 * })(SUGAR.App);
 * </code></pre>
 *
 * @class Data.DataManager
 * @alias SUGAR.App.data
 * @singleton
 */
(function(app) {

    // Bean class cache
    var _models = {};
    // Bean collection class cache
    var _collections = {};

    var _dataManager = _.extend({

        /**
         * Reference to the base bean model class. Defaults to {@link Data.Bean}.
         * @property {Data.Bean}
         */
        beanModel: app.Bean,
        /**
         * Reference to the base bean collection class. Defaults to {@link Data.BeanCollection}.
         * @property {Data.BeanCollection}
         */
        beanCollection: app.BeanCollection,

        /**
         * Initializes data manager.
         * @method
         */
        init: function() {
            var sync = _.bind(this.sync, this);
            app.Bean.prototype.sync = sync;
            app.BeanCollection.prototype.sync = sync;

            app.events.register(
                /**
                 * Fires when the sync operation starts.
                 *
                 * Two parameters are passed to the callback:
                 *
                 *  - operation name (`method`)
                 *  - reference to the model/collection
                 *  - options
                 *
                 * <pre><code>
                 * (function(app) {
                 *     app.events.on("data:sync:start", function(method, model, options) {
                 *         app.logger.debug("Started operation " + method + " on " + model);
                 *     });
                 * })(SUGAR.App);
                 * </code></pre>
                 * @event
                 */
                "data:sync:start",
                this
            );

            app.events.register(
                /**
                 * Fires when the sync operation ends.
                 *
                 * Three parameters are passed to the callback:
                 *
                 *  - operation name (`method`)
                 *  - reference to the model/collection
                 *  - options
                 *  - error or undefined if operation has been successful
                 *
                 * <pre><code>
                 * (function(app) {
                 *     app.events.on("data:sync:end", function(method, model, options, error) {
                 *         app.logger.debug("Finished operation " + method + " on " + model);
                 *     });
                 * })(SUGAR.App);
                 * </code></pre>
                 * @event
                 */
                "data:sync:end",
                this
            );
        },

        /**
         * Resets class declarations.
         * @param {String} module(optional) module name. If not specified, resets models of all modules.
         * @method
         */
        reset: function(module) {
            if (module) {
                delete _models[module];
                delete _collections[module];
            }
            else {
                _models = {};
                _collections = {};
            }
        },

        /**
         * Declares bean model and collection classes for a given module.
         * @param {String} moduleName module name.
         * @param module module metadata object.
         * @method
         */
        declareModel: function(moduleName, module) {
            this.reset(moduleName);
            // Bug 54814 init fields to something sane if module metadata is empty
            var fields = module ? module.fields : {};
            var defaults = null;

            _.each(_.values(fields), function(field) {
                if (!_.isUndefined(field["default"])) {
                    if (defaults === null) {
                        defaults = {};
                    }
                    defaults[field.name] = field["default"];
                }
            });

            var model = this.beanModel.extend({
                // We don't want to populate model with default values using Backbone's 'defaults' property
                // We do it manually in Bean.initialize method only if the given model is new
                _defaults: defaults,
                /**
                 * Module name.
                 * @member Data.Bean
                 * @property {String}
                 */
                module: moduleName,
                /**
                 * Vardefs metadata.
                 * @member Data.Bean
                 * @property {Object}
                 */
                fields: fields
            });

            _collections[moduleName] = this.beanCollection.extend({
                model: model,
                /**
                 * Module name.
                 * @member Data.BeanCollection
                 * @property {String}
                 */
                module: moduleName,
                /**
                 * Pagination offset.
                 * @member Data.BeanCollection
                 * @property {Number}
                 */
                offset: 0
            });

            _models[moduleName] = model;
        },

        /**
         * Declares bean models and collections classes for each module definition.
         * @param modules(optional) metadata hash in which keys are module names and values are module definitions.
         * Data manager uses {@link Core.MetadataManager#getModules} method to fetch metadata
         * if `modules` parameter is not specified.
         */
        declareModels: function(modules) {
            modules = modules || app.metadata.getModules();
            _.each(modules, function(module, name) {
                this.declareModel(name, module);
            }, this);
        },

        /**
         * Gets declaration of a bean class.
         *
         * This method is for internal use only.
         * @param {String} module Module name.
         * @ignore
         */
        getBeanClass: function(module) {
            return _models[module] || Backbone.Model;
        },

        /**
         * Creates instance of a bean.
         *
         * <pre><code>
         * (function(app) {
         *
         * // Create an account bean. The account's name property will be set to "Acme".
         * var account = app.data.createBean("Accounts", { name: "Acme" });
         *
         * // Create a team set bean with a given ID
         * var teamSet = app.data.createBean("TeamSets", { id: "xyz" });
         *
         * })(SUGAR.App);
         * </code></pre>
         * @param {String} module Sugar module name.
         * @param attrs(optional) initial values of bean attributes, which will be set on the model.
         * @return {Data.Bean} A new instance of bean model.
         */
        createBean: function(module, attrs) {
            return _models[module] ?  new _models[module](attrs) : new Backbone.Model();
        },

        /**
         * Creates instance of a bean collection.
         *
         * <pre><code>
         * (function(app) {
         * // Create an empty collection of account beans.
         * var accounts = app.data.createBeanCollection("Accounts");
         *
         * })(SUGAR.App);
         * </code></pre>
         * @param {String} module Sugar module name.
         * @param {Data.Bean[]} models(optional) initial array or collection of models.
         * @param {Object} options(optional) options hash.
         * @return {Data.BeanCollection} A new instance of bean collection.
         */
        createBeanCollection: function(module, models, options) {
            return _collections[module] ? 
                new _collections[module](models, options) : 
                new Backbone.Collection();
        },

        /**
         * Creates an instance of related {@link Data.Bean} or updates an existing bean with link information.
         *
         * <pre><code>
         * (function(app) {
         *
         * // Create a new contact related to the given opportunity.
         * var contact = app.data.createRelatedBean(opportunity, "1", "contacts", {
         *    "first_name": "John",
         *    "last_name": "Smith",
         *    "contact_role": "Decision Maker"
         * });
         * contact.save(null, { relate: true });
         *
         * })(SUGAR.App);
         * </code></pre>
         *
         * @param {Data.Bean} bean1 instance of the first bean
         * @param {Data.Bean/String} beanOrId2 instance or ID of the second bean. A new instance is created if this parameter is <code>null</code>
         * @param {String} link relationship link name
         * @param {Object} attrs(optional) bean attributes hash
         * @return {Data.Bean} a new instance of the related bean or existing bean instance updated with relationship link information.
         */
        createRelatedBean: function(bean1, beanOrId2, link, attrs) {
            var relatedModule = this.getRelatedModule(bean1.module, link);

            attrs = attrs || {};
            if (_.isString(beanOrId2)) {
                attrs.id = beanOrId2;
                beanOrId2 = this.createBean(relatedModule, attrs);
            }
            else if (_.isNull(beanOrId2)) {
                beanOrId2 = this.createBean(relatedModule, attrs);
            }
            else {
                beanOrId2.set(attrs);
            }

            /**
             * Relationship link information.
             *
             * <pre>
             * {
             *   name: link name,
             *   bean: reference to the related bean
             *   isNew: flag indicating that it is a new relationship
             * }
             * </pre>
             *
             * The `link.isNew` flag is used to distinguish between an existing relationship and a relationship
             * that is about to be created. Please, refer to REST API specification for details.
             * In brief, REST API supports creating a new relationship for two existing records as well as
             * updating an existing relationship (updating relationship fields).
             * The `link.isNew` flag equals to `true` by default. The flag is set to `false` by data manager
             * once a relationship is created and whenever relationships are fetched from the server.
             *
             * @member Data.Bean
             */
            beanOrId2.link = {
                name: link,
                bean: bean1,
                isNew: true
            };

            return beanOrId2;
        },

        /**
         * Creates a new instance of related beans collection.
         *
         * <pre><code>
         * (function(app) {
         *
         * // Create contacts collection for an existing opportunity.
         * var contacts = app.data.createRelatedCollection(opportunity, "contacts");
         * contacts.fetch({ relate: true });
         *
         * })(SUGAR.App);
         * </code></pre>
         *
         * The newly created collection is cached in the given bean instance.
         *
         * @param {Data.Bean} bean the related beans are linked to the specified bean
         * @param {String} link relationship link name
         * @param {Array/Data.BeanCollection} models(optional) an array of related beans to populate the newly created collection with
         * @return {Data.BeanCollection} a new instance of the bean collection
         */
        createRelatedCollection: function(bean, link, models) {
            var relatedModule = this.getRelatedModule(bean.module, link);
            var collection = this.createBeanCollection(relatedModule, models, {
                /**
                 * Link information.
                 *
                 * <pre>
                 * {
                 *   name: link name,
                 *   bean: reference to the related bean
                 * }
                 * </pre>
                 *
                 * @member Data.BeanCollection
                 */
                link: {
                    name: link,
                    bean: bean
                }
            });

            bean._setRelatedCollection(link, collection);
            return collection;
        },

        /**
         * Creates a collection of beans of different modules.
         * @param {Array/Data.BeanCollection} models(optional) A list of models to populate the new collection with.
         * @return {Data.MixedBeanCollection} Collection of mixed module collection.
         */
        createMixedBeanCollection: function(models) {
            return new app.MixedBeanCollection(models);
        },

        /**
         * Checks if a given module can have multiple relationships via a given link.
         * @param {String} module Name of the module to do the check for.
         * @param {String} link Relationship link name.
         * @return {Boolean} `true` if the module's link is 'many'-type relationship, `false` otherwise.
         */
        canHaveMany: function(module, link) {
            var meta = app.metadata.getModule(module);
            var name = meta.fields[link].relationship;
            var relationship = app.metadata.getRelationship(name);
            var t = relationship.relationship_type.split("-");
            var type = module === relationship.rhs_module ? t[0] : t[2];
            return type === "many";
        },

        /**
         * Gets fields of type `relate` for a link.
         *
         * Suppose a module `parentModule` has a link field named `link`.
         * For example, `Accounts` module has a link field `cases`.
         * It is one-to-many relationship: an account may have many related cases,
         * however a case can have only one associated account and this association is
         * made via a `relate` field called `Cases.account_name`.
         *
         * So, for the above example the following call:
         * <pre><code>
         * var relateField = app.data.getRelateField("Accounts", "cases");
         * </code></pre>
         * would return definition of `Cases.account_name` field.
         *
         * @param {String} parentModule Name of the module that has a link field named `link`.
         * @param {String} link Link name.
         * @return {Array} Definitions of the `relate` fields if found or empty array if not found.
         * The array will contain one item most of the time. However, some modules have multiple
         * `relate` fields that have the same link.
         */
        getRelateFields: function(parentModule, link) {
            var relationship = app.metadata.getModule(parentModule).fields[link].relationship;
            var relatedModule = this.getRelatedModule(parentModule, link);
            var fields = app.metadata.getModule(relatedModule).fields;

            // Find the opposite link field on related module
            var f = _.find(fields, function(field) {
                return field.type == "link" && field.relationship == relationship;
            });

            if (f) {
                f = _.filter(fields, function(field) {
                    return field.type == "relate" && field.link == f.name;
                });
            }

            return f || [];
        },

        /**
         * Gets relationship fields for a complex relationship.
         *
         * Some relationships have custom fields.
         * For example, the `opporutnities_contacts` relationship has a custom field `contact_role`.
         * The `Contacts` module has a field called `opportunity_role` that corresponds to the `contact_role`
         * relationship field:
         * <pre><code>
         * // ['opportunity_role']
         * var relationshipFields = app.data.getRelationshipFields("Opportunities", "contacts");
         * </code></pre>
         *
         * Use this method to determine a list of relationship fields that should be rendered
         * on views for related record(s). In the above use case, the edit view for a contact related
         * to an opportunity should display a drop-down field for the opportunity role.
         * The list view and detail view should also display this field.
         *
         * @param {String} parentModule Name of the module that has a link field called `link`.
         * @param {String} link Link name.
         * @return {Array} Relationship fields.
         */
        getRelationshipFields: function(parentModule, link) {
            var ff = null;
            var linkField = app.metadata.getModule(parentModule).fields[link];
            if (linkField.rel_fields) {
                var relationship = linkField.relationship;
                var relatedModule = this.getRelatedModule(parentModule, link);
                var fields = app.metadata.getModule(relatedModule).fields;

                // Find the opposite link field on related module
                var f = _.find(fields, function(field) {
                    return field.type == "link" && field.relationship == relationship;
                });

                // Find relationship_info field
                if (f) {
                    f = _.find(fields, function(field) {
                        return field.link == f.name && field.link_type == "relationship_info";
                    });
                }

                // Extract relationship fields
                if (f && f.relationship_fields) {
                    var fieldNames = _.keys(linkField.rel_fields);
                    _.each(f.relationship_fields, function(field, name) {
                        if (_.include(fieldNames, name)) {
                            if (!ff) ff = [];
                            ff.push(field);
                        }
                    });
                }
            }

            return ff;

        },

        /**
         * Gets related module name.
         * @param {String} module Name of the parent module.
         * @param {String} link Relationship link name.
         * @return {String} The name of the related module.
         */
        getRelatedModule: function(module, link) {
            var meta = app.metadata.getModule(module);
            var name = meta.fields[link].relationship;
            var relationship = app.metadata.getRelationship(name);

            return module === relationship.rhs_module ?
                relationship.lhs_module : relationship.rhs_module;
        },

        /**
         * Gets editable fields.
         * @param {Data.Bean/Data.BeanCollection} model to get fields from.
         * @param {Array} fields(optional) names to be checked.
         * @return {Object} Hash of editable fields.
         */
        getEditableFields: function(model, fields) {
            var editableFields = {}, module = model.module, metadata, ignoreTypeList = ["parent", "relate"];
            fields = fields || [];

            // No fields were specified, try the model's attributes instead
            if(fields.length == 0)
                _.each(model.attributes, function(value, key){fields.push(key);});

            if (module) {
                var metadata = app.metadata.getModule(module);
            }

            // Always have the id included (without the id, the routing will now work correctly)
            editableFields["id"] = model.get("id");

            // Editible fields are fields that are either DB fields, such as name, or related fields that do have a real DB field behind them, such as opportunity_role (contact_role), that the user has access to edit.
            // The following code will filter out fields such as assigned_user_name.
            _.each(fields, function(fieldName) {
                if(model.has(fieldName) && // Model has that field AND
                    (metadata && metadata.fields[fieldName] && // Field exists in the metadata AND
                        (!metadata.fields[fieldName].source || // (The field does not have a source specified OR
                            metadata.fields[fieldName].source !== 'non-db' || // the field's source is something other than 'non-db' OR
                            !metadata.fields[fieldName].type || // The field does not have a field type specified OR
                            ignoreTypeList.indexOf(metadata.fields[fieldName].type) === -1)) && // The field's source is 'non-db', but the field's type is not in our ignore list) AND
                    app.acl.hasAccessToModel("edit", model, fieldName)) { // The user has access to edit the field
                    editableFields[fieldName] = model.get(fieldName);
                }
            });
            return editableFields;
        },

        /**
         * Custom implementation of <code>Backbone.sync</code> pattern. Syncs models with remote server using Sugar.Api lib.
         * @param {String} method the CRUD method (<code>"create", "read", "update", or "delete"</code>)
         * @param {Data.Bean/Data.BeanCollection} model the model to be synced (or collection to be read)
         * @param options(optional) standard Backbone options as well as Sugar specific options
         */
        sync: function(method, model, options) {
            var self = this;

            app.logger.trace('remote-sync-' + (options.relate ? 'relate-' : '') + method + ": " + model);

            options = options || {};
            options.params = options.params || {};
            
            if (options.fields && method == "read") {
                options.params.fields = options.fields.join(",");
            }

            if ((method == "read") && (model instanceof app.BeanCollection)) {
                if (options.offset && options.offset !== 0) {
                    options.params.offset = options.offset;
                }

                if (options.limit || (app.config && app.config.maxQueryResult)) {
                    options.params.max_num = options.limit || app.config.maxQueryResult;
                }

                if (model.orderBy && model.orderBy.field) {
                    options.params.order_by = model.orderBy.field + ":" + model.orderBy.direction;
                }

                if (options.myItems === true) {
                    options.params.my_items = "1";
                }

                if (options.favorites === true) {
                    options.params.favorites = "1";
                }

                if (!_.isEmpty(options.query)) {
                    options.params.q = options.query;
                }

                if (options.module_list) {
                    options.params.module_list = options.module_list.join(",");
                }
            }

            var success = function(data) {
                model.original_assigned_user_id = model.get("assigned_user_id");
                if ((method == "read") && (model instanceof app.BeanCollection)) {
                    if (data.next_offset) {
                        model.offset = data.next_offset != -1 ? data.next_offset : model.offset + (data.records || []).length;
                        model.next_offset = data.next_offset;
                        model.page = model.getPageNumber();
                    }
                    

                    data = data.records || [];

                    // Update collection filter/search properties on success
                    /**
                     * Flag indicating if a collection contains items assigned to the current user (read-only).
                     * @member Data.BeanCollection
                     * @property {Boolean}
                     */
                    model.myItems = options.myItems;
                    /**
                     * Flag indicating if a collection contains current user's favorite items (read-only).
                     * @member Data.BeanCollection
                     * @property {Boolean}
                     */
                    model.favorites = options.favorites;
                    /**
                     * Search query (read-only).
                     * @member Data.BeanCollection
                     * @property {String}
                     */
                    model.query = options.query;
                    /**
                     * List of modules searched (read-only).
                     * @member Data.MixedBeanCollection
                     * @property {String}
                     */
                    model.modelList = options.modelList;
                }
                // not a collection
                else if ((method == "read") && (model instanceof app.Bean)) {
                    // hash of the ACL's
                    model._acl = data._acl || {};
                    delete data._acl;
                }

                if (options.relate === true) {
                    // Reset the flag to indicate that relationship(s) do exist.
                    model.link.isNew = false;

                    if (method != "read") {
                        // The response for create/update/delete relationship contains updated beans
                        if (model.link.bean) {
                            model.link.bean.set(data.record);
                        }
                        data = data.related_record;
                        // Attributes will be set automatically for create/update but not for delete
                        // Also, break the link
                        if (method == "delete") {
                            model.set(data);
                            delete model.link;
                        }
                    }
                }

                if (options.success) options.success(data);
            };

            var err;
            var error = function(error) {
                err = error;
                app.error.handleHttpError(error, model);
                if (options.error) options.error(error);
            };

            // 'complete' fires after success and error
            var complete = function() {
                self.trigger("data:sync:end", method, model, options, err);
                if (options.complete) options.complete();
            };

            var callbacks = {
                success: success,
                error: error,
                complete: complete
            };

            this.trigger("data:sync:start", method, model, options);

            if (options.relate === true) {
                // Related data is an object should contain:
                // - related bean (including relationship fields) in case of create method
                // - just relationship fields in case of update method
                // - null for read/delete method
                var relatedData = null;
                if (method == "create" || method == "update") {
                    // Pass all fields: bean fields + relationship fields
                    relatedData = this.getEditableFields(model, options.fields);
                    // Change 'update' method to 'create' if the relationship is a new one
                    if (method == "update" && model.link.isNew) {
                        method = "create";
                    }
                }
                app.api.relationships(
                    method,
                    model.link.bean.module,
                    {
                        id: model.link.bean.id,
                        link: model.link.name,
                        relatedId: model.id,
                        related: relatedData
                    },
                    options.params,
                    callbacks
                );
            }
            else if (options.favorite) {

                app.api.favorite(
                    model.module,
                    model.id,
                    model.isFavorite(),
                    callbacks
                );
            }
            else {
                // Use global search API for mixed collection
                if (_.isFunction(app.MixedBeanCollection) && (model instanceof app.MixedBeanCollection)) {
                    app.api.search(
                        options.params,
                        callbacks
                    );
                }
                else {
                    app.api.records(
                        method,
                        model.module,
                        method == "update" || method == "create" ? this.getEditableFields(model, options.fields) : model.attributes,
                        options.params,
                        callbacks
                    );
                }
            }
        }

    }, Backbone.Events);

    app.augment("data", _dataManager, false);

})(SUGAR.App);
