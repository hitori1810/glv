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
 * View manager is used to create views, layouts, and fields based on optional metadata inputs.
 *
 * The view manager's factory methods (`createView`, `createLayout`, and `createField`) first checks
 * `views`, `layouts`, and `fields` hashes respectively for custom class declaration before falling back the base class.
 *
 * Put declarations of your custom views, layouts, fields in the corresponding hash:
 * <pre><code>
 * app.view.views.MyCustomView = app.view.View.extend({
 *  // Put your custom logic here
 * });
 *
 * app.view.layouts.MyCustomLayout = app.view.Layout.extend({
 *  // Put your custom logic here
 * });
 *
 * app.view.fields.MyCustomField = app.view.Field.extend({
 *  // Put your custom logic here
 * });
 *
 * </code></pre>
 *
 *
 * @class View.ViewManager
 * @alias SUGAR.App.view
 * @singleton
 */
(function(app) {

    // Ever incrementing field ID
    var _sfId = 0;
    // A list of classes declared by _extendClass method
    var _classes = [];
    // Creates a new subclass of the given super class based on the controller definition passed.
    var _extendClass = function(cache, base, className, controller) {
        var klass = null, evaledController = null;
        if (_.isObject(controller)) {
            base = _.isObject(controller.extendsFrom) ?
                controller.extendsFrom :
                    cache[controller.extendsFrom] ||
                    base;
            klass = cache[className] = base.extend(controller);
        } else{
            klass = cache[className] = base;
        }
        _classes.push(className);

        return klass;
    };

    var _viewManager = {

        /**
         * Resets class declarations of custom components.
         */
        reset: function() {
            var className;
            for(var i = 0; i < _classes.length; ++i) {
                className = _classes[i];
                delete this.layouts[className];
                delete this.views[className];
                delete this.fields[className];
            }
            _classes = [];
        },

        /**
         * Gets ID of the last created field.
         * @return {Number} ID of the last created field.
         */
        getFieldId: function() {
            return _sfId;
        },

        /**
         * Hash of view classes.
         */
        views: {},
        /**
         * Hash of layout classes.
         */
        layouts: {},
        /**
         * Hash of field classes.
         */
        fields: {},

        /**
         * Creates an instance of a component and binds data changes to it.
         *
         * @param type Component type (`layout`, `view`, `field`).
         * @param name Component name.
         * @param params Parameters to pass to the Component's class constructor.
         * @return {View.Component} New instance of a component.
         * @private
         */
        _createComponent: function(type, name, params) {
            var Klass = this.declareComponent(type, params.type || name, params.module, params.controller);
            var component = new Klass(params);
            component.bindDataChange();

            return component;
        },

        /**
         * Creates an instance of a view.
         *
         * Parameters define creation rules as well as view properties.
         * The `params` hash must contain at least `name` property which is the view name.
         * Other parameters may be:
         *
         * - context: context to associate the newly created view with
         * - module: module name
         * - meta: custom metadata
         *
         * If context is not specified the controller's current context is assigned to the view (`SUGAR.App.controller.context`).
         *
         * Examples:
         *
         * * Create a list view. The view manager will use metadata for the view named 'list' defined in Contacts module.
         * The controller's current context will be set on the new view instance.
         * <pre><code>
         * var listView = app.view.createView({
         *    name: 'list',
         *    module: 'Contacts'
         * });
         * </code></pre>
         *
         * * Create a custom view class.
         * <pre><code>
         * // Declare your custom view class.
         * app.view.views.MyCustomView = app.view.View.extend({
         *  // Put your custom logic here
         * });
         *
         * var myCustomView = app.view.createView({
         *    name: 'myCustom'
         * });
         * </code></pre>
         *
         * * Create a view with custom metadata payload.
         * <pre><code>
         * var view = app.view.createView({
         *     name: 'detail',
         *     meta: { ... your custom metadata ... }
         * });
         * </code></pre>
         *
         * @param params view parameters
         * @return {View.View} new instance of view.
         */
        createView: function(params) {
            // context is always defined on the controller
            params.context = params.context || app.controller.context;
            params.module  = params.module || params.context.get("module");
            params.meta    = params.meta || app.metadata.getView(params.module, params.name);

            return this._createComponent("view", params.name, params);
        },

        /**
         * Creates an instance of a layout.
         *
         * Parameters define creation rules as well as layout properties.
         * The factory needs either layout name or type.
         * The layout type is retrieved either from `params` hash or layout metadata.
         *
         * Parameters may be:
         *
         * - name: layout name (list, simple, complex, etc.)
         * - context: context to associate the newly created layout with
         * - module: module name
         * - meta: custom metadata
         * - type: layout type (fluid, columns, etc.). If not specified, it is retrieved from metadata definition.
         *
         * If context is not specified the controller's current context is assigned to the layout (`SUGAR.App.controller.context`).
         *
         * Examples:
         *
         * * Create a list layout. The view manager will use metadata for the layout named 'list' defined in Contacts module.
         * The controller's current context will be set on the new layout instance.
         * <pre><code>
         * var listLayout = app.view.createLayout({
         *    name: 'list',
         *    module: 'Contacts'
         * });
         * </code></pre>
         *
         * * Create a custom layout class.
         * <pre><code>
         * // Declare your custom layout class.
         * app.view.layouts.MyCustomLayout = app.view.Layout.extend({
         *  // Put your custom logic here
         * });
         *
         * var myCustomLayout = app.view.createLayout({
         *    name: 'myCustom'
         * });
         * </code></pre>
         *
         * * Create a layout with custom metadata payload.
         * <pre><code>
         * var layout = app.view.createLayout({
         *     name: 'detail',
         *     meta: { ... your custom metadata ... }
         * });
         * </code></pre>
         *
         * @param params layout parameters
         * @return {View.Layout} New instance of the layout.
         */
        createLayout: function(params) {
            params.context = params.context || app.controller.context;
            params.module  = params.module || params.context.get("module");
            params.meta    = params.meta || app.metadata.getLayout(params.module, params.name);
            params.type    = params.type || (params.meta ? params.meta.type : null);

            return this._createComponent("layout", params.name || params.type, params);
        },

        /**
         * Creates an instance of a field and registers it with the parent view (`params.view`).
         *
         * The parameters define creation rules as well as field properties.
         *
         * The `params` hash must contain `def` property which is the field definition and `view`
         * property which is the reference to the parent view. For example,
         * <pre>
         * var params = {
         *    view: new Backbone.View,
         *    def: {
         *      type: 'text',
         *      name: 'first_name',
         *      label: 'LBL_FIRST_NAME'
         *    },
         *    context: optional context (if not specified, app.controller.context is used)
         *    model: optional model (if not specified, the model which is set on the context is used)
         *    meta: optional custom metadata
         *    viewName: optional view name to determine the field template (if not specified, view.name is used)
         * }
         * </pre>
         *
         * View manager queries metadata manager for field type specific metadata (templates and JS controller) unless custom metadata
         * is passed in the `params` hash.
         *
         * To create instances of custom fields, first declare its class in `app.view.fields` hash:
         * <pre><code>
         * app.view.views.MyCustomField = app.view.Field.extend({
         *  // Put your custom logic here
         * });
         *
         * var myCustomField = app.view.createField({
         *   view: someView,
         *   def: {
         *      type: 'myCustom',
         *      name: 'my_custom'
         *   }
         * });
         * </code></pre>
         *
         * @param params field parameters.
         * @return {View.Field} a new instance of field.
         */
        createField: function(params) {
            var type       = params.def.type;
            params.context = params.context || params.view.context || app.controller.context;
            params.model   = params.model || params.context.get("model");
            params.module  = params.module || (params.model && params.model.module ? params.model.module : params.context.get('module')) || "";
            params.meta    = params.meta || app.metadata.getField(type, params.module);
            if(params.meta && params.meta.controller) params.controller = params.meta.controller;
            params.sfId = ++_sfId;
            
            var field = this._createComponent("field", type, params);
            // Register new field within its parent view.
            params.view.fields[field.sfId] = field;
            return field;
        },

        /**
         * Retrieves class declaration for a component or creates a new component class.
         *
         * This method creates a subclass of the base class if controller parameter is not null
         * and such subclass hasn't been created yet.
         * Otherwise, the method tries to retrieve the most appropriate class by searching in the following order:
         *
         * - Custom class name: `<module><component-name><component-type>`.
         * For example, for Contacts module one could have:
         * `ContactsDetailLayout`, `ContactsFluidLayout`, `ContactsListView`.
         *
         * - Class name: `<component-name><component-type>`.
         * For example: `ListLayout`, `ColumnsLayout`, `DetailView`, `IntField`.
         *
         * - Custom base class: `<capitalized-appId><component-type>`
         * For example, if `app.config.appId == 'portal'`, custom base classes would be:
         * `PortalLayout`, `PortalView`, `PortalField`.
         * Declarations of such classes must be in app.view namespace.
         * There are use cases when an app has some common component code.
         * In such cases, using custom base classes is beneficial. For example, any app may need
         * to override validation error handling for fields:
         * <pre>
         * // Assuming app.config.appId === 'portal':
         * app.view.PortalField = app.view.Field.extend({
         *      initialize: function(options) {
         *         // Call super
         *         app.view.Field.prototype.initialize.call(this, options);
         *         // Custom initialization code...
         *      },
         *
         *      handleValidationError: function (errors) {
         *        // Custom validation logic
         *      }
         * });
         * </pre>
         * Above declaration will make all field controllers extend `app.view.PortalField` instead of `app.view.Field`.
         *
         * - Base class: `<component-type>` - `Layout`, `View`, `Field`.
         *
         * Note 1. Although the view manager supports module specific fields like `ContactsIntField`,
         * the server does not provide such customization.
         *
         * Note 2. The layouts is a special case because their class name is built both from layout name
         * and layout type. One could have `ListLayout` or `ColumnsLayout` including their
         * module specific counterparts like `ContactsListView` and `ContactsColumnsLayout`.
         * The "named" class name is checked first.
         *
         *
         * @param {String} type Lower-cased component type: layout, view, or field.
         * @param {String} name Lower-cased component name. For example, list (layout or view), bool (field).
         * @param {String} module(optional) Module name.
         * @param {String} controller(optional) Controller source code string.
         * @param {String} layoutType(optional) Layout type. For example, fluid, rows, columns.
         * @param {Boolean} overwrite(optional) Will overwrite if duplicate custom class or layout is cached. Note, 
         * if no controller passed, overwrite is ignored since we can't create a meaningful component without a controller.
         * @return {Function} Component class.
         */
        declareComponent: function(type, name, module, controller, overwrite) {
            var ucType                  = app.utils.capitalize(type),
                className               = app.utils.capitalizeHyphenated(name) + ucType,
                customClassName         = (module || "") + className,
                cache                   = app.view[type + "s"],
                customBaseClassName     = app.utils.capitalize(app.config.appId) + ucType,
                baseClass               = cache[className] ||
                                          cache["Base" + ucType] ||
                                          app.view[customBaseClassName] ||
                                          app.view[ucType];

            if(overwrite && controller) {
                if(cache[customClassName]) delete cache[customClassName];
            }

            return  cache[customClassName] ||
                    _extendClass(cache, baseClass, customClassName, controller) ||
                    baseClass;
        },

        getComponentClassName : function(type, name, module){

        }
    };

    app.augment("view", _viewManager, false);

})(SUGAR.App);
