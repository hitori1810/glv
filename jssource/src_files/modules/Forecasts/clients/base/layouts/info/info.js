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

        extendsFrom: "ForecastsIndexLayout",

        /**
         * Holds the metadata for each of the components used in forecasts
         */
        componentsMeta: {},

        initialize:function (options) {
            this.componentsMeta = options.meta.components;

            options.context = _.extend(options.context, this.initializeAllModels(options.context));
            app.view.Layout.prototype.initialize.call(this, options);
        },

        /**
         * Dropping in to _render to insert some code to display the config wizard for a user's first run on forecasts.  The render process itself is unchanged.
         *
         * @return {*}
         * @private
         */
        _render: function () {

            this.loadData();

            app.view.Layout.prototype._render.call(this);

            return this;
        }
})