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
 * Represents base view class for layouts, views, and fields.
 *
 * This is an abstract class.
 * @class View.Component
 * @alias SUGAR.app.view.Component
 */
(function(app) {

    app.view.Component = Backbone.View.extend({

        /**
         * Initializes a component.
         * @constructor
         * @param options
         *
         * - context
         * - meta
         * - module
         * - model
         * - collection
         *
         * `context` is the only required option.
         * @return {View.Component}
         */
        initialize: function(options) {

            /**
             * Reference to the context (required).
             * @property {Core.Context}
             */
            this.context = options.context || app.controller.context;

            /**
             * Component metadata (optional).
             * @property {Object}
             */
            this.meta = options.meta;

            /**
             * Module name (optional).
             * @property {String}
             */
            this.module = options.module || this.context.get("module");

            /**
             * Reference to the model this component is bound to.
             * @property {Data.Bean}
             */
            this.model = options.model || this.context.get("model");

            /**
             * Reference to the collection this component is bound to.
             * @property {Data.BeanCollection}
             */
            this.collection = options.collection || this.context.get("collection");

            // Adds classes to the component based on the metadata.
            if(this.meta && this.meta.css_class) {
                this.$el.addClass(this.meta.css_class);
            }
        },

        /**
         * Add a callback/hook to be fired before an action is taken. If that callback returns false,
         * the action will not be taken. The only action supported in the base component is render.
         *
         * @param event String event to trigger before
         * @param callback Function to be called
         * @param params Object to pass as the paramter to the callback
         * @param scope Object|Boolean If scope is an object, it will be assign to this when the callback is fired.
         * If scope is true, then params will be used as this for the callback.
         * @return {*}
         */
        before : function(event, callback, params, scope){
            var events = this._before = this._before || {};
            events[event] = events[event] || [];

            events[event].push({
                fn:callback,
                params:params,
                overrideContext:scope
            });

            return this;
        },

        /**
         * Trigger the before listener for an action.
         * @param String event the action to check before on
         * @param Object params parameter object to pass to the callbacks
         * @return {Boolean}
         */
        triggerBefore : function(event, params) {
            var stop = false;
            if (this._before && this._before[event]) {
                var calls = this._before[event];
                for (var i = 0; i < calls.length; i++) {
                    var c = calls[i];
                    var context = this;
                    if (c.overrideContext) {
                        if (c.overrideContext === true) {
                            context = c.params;
                        } else {
                            context = c.overrideContext;
                        }
                    }
                    if (params && c.params)
                        c.params = _.extend(params, c.params);
                    else
                        c.params = c.params || params;
                    if (c.fn) {
                        stop = stop || (c.fn.call(context, c.params) === false);
                    }
                }
            }

            return !stop;
        },

        /**
         * Removes listeners to the before events.
         * @param String event Event to remove the listeners for. If ommited all listeners to all event will be removed.
         * @param Function callback Optional callback to remove specifically for a given event.
         * @param Object scope optinal scope to use when determining which callback to remove
         * @return {Boolean}
         */
        offBefore : function(event, callback, scope) {
            //Remove all before listeners
            if (!event)
                return delete this._before;

            var events = this._before = this._before || {};

            //No event found
            if (!events[event])
                return false;
            //Delete all listeners for this before event
            if (!callback && !scope)
                return delete events[event];

            var calls = events[event];
            for (var i = calls.length - 1; i >= 0 ; i--) {
                var c = calls[i];
                var context = this;
                if (c.overrideContext) {
                    if (c.overrideContext === true) {
                        context = item.params;
                    } else {
                        context = item.overrideContext;
                    }
                }
                if (c.fn == callback && (!scope || scope == context))
                    calls.splice(i, 1);
            }
            return true;
        },

        /**
         * Renders a component.
         *
         * Override this method to provide custom logic.
         * The default implementation does nothing.
         * See Backbone.View documentation for details.
         * @protected
         */
        _render: function() {
            // Do nothing. Override.
        },

        /**
         * Renders a component.
         *
         * IMPORTANT: Do not override this method.
         * Instead, override {@link View.Component#_render} to provide render logic.
         * @return {View.Component} Instance of this component.
         */
        render: function() {
            if (this.disposed === true) throw new Error("Unable to render component because it's disposed: " + this);
            if(!this.triggerBefore("render"))
                return false;
            this._render();

            this.trigger("render");

            return this;
        },

        /**
         * Binds data changes to this component.
         *
         * This method should be overridden by derived views.
         */
        bindDataChange: function() {
            // Override this method to wire up model/collection events
        },

        /**
         * Removes this component's event handlers from model and collection.
         *
         * Performs the opposite of what {@link View.Component#bindDataChange} method does.
         * Override this method to provide custom logic.
         */
        unbindData: function() {
            if (this.model) this.model.off(null, null, this);
            if (this.collection) this.collection.off(null, null, this);
        },

        /**
         * Removes all event callbacks registered within this component
         * and undelegates Backbone events.
         *
         * Override this method to provide custom logic.
         */
        unbind: function() {
            this.off();
            this.offBefore();
            this.undelegateEvents();
            app.events.off(null, null, this);
            app.events.unregister(this);
        },

        /**
         * Fetches data for layout's model or collection.
         *
         * The default implementation does nothing.
         * See {@link View.Layout#loadData} and {@link View.View#loadData} methods.
         */
        loadData: function() {
            // Do nothing (view and layout will override)
        },

        /**
         * Disposes a component.
         *
         * This method:
         *
         * - unbinds the component from model and collection.
         * - removes all event callbacks registered within this component.
         * - removes the component from the DOM.
         *
         * Override this method to provide custom logic:
         * <pre><code>
         * app.view.views.MyView = app.view.View.extend({
         *      _dispose: function() {
         *          // Perform custom clean-up. For example, clear timeout handlers, etc.
         *          ...
         *          // Call super
         *          app.view.View.prototype._dispose.call(this);
         *      }
         * });
         * </code></pre>
         * @protected
         */
        _dispose: function() {
            this.unbindData();
            this.unbind();
            this.remove();
            this.model = null;
            this.collection = null;
            this.context = null;
        },

        /**
         * Disposes a component.
         *
         * Once the component gets disposed it can not be rendered.
         * Do not override this method. Instead override {@link View.Component#_dispose} method
         * if you need custom disposal logic.
         */
        dispose: function() {
            if (this.disposed === true) return;
            this._dispose();
            this.disposed = true;
        },

        /**
         * Removes a component from the DOM.
         */
        remove: function() {
            return this.$el.remove();
        },

        /**
         * Gets a string representation of this component.
         * @return {String} String representation of this component.
         */
        toString: function() {
            return this.cid +
                "-" + (this.$el && this.$el.id ? this.$el.id : "<no-id>") +
                "/" + this.module +
                "/" + this.model +
                "/" + this.collection;
        },

        /**
         * Pass through function to jQuery's show to show view.
         */
        show: function() {
            this.$el.removeClass("hide").show();
        },

        /**
         * Pass through function to jQuery's hide to hide view.
         */
        hide: function() {
            this.$el.addClass("hide").hide();
        }
    });

})(SUGAR.App);
