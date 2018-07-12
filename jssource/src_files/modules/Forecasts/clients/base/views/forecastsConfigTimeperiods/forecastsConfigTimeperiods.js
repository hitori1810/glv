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
 * Events Triggered
 *
 * liszt:updated
 *      on: timperiod_start_day
 *      by: _setUpTimeperiodStartMonthBind()
 */
({

    initialize: function(options) {
        app.view.View.prototype.initialize.call(this, options);

        if(!_.isUndefined(options.meta.registerLabelAsBreadCrumb) && options.meta.registerLabelAsBreadCrumb == true) {
            this.layout.registerBreadCrumbLabel(options.meta.panels[0].label);
        }
    },

    /**
     * Overriding _renderField because we need to set up a binding to the start month drop down to populate the day drop down on change
     * @param field
     * @private
     */
    _renderField: function(field) {

        field = this._setUpTimeperiodConfigField(field);

        // TODO-sfa this will get removed when the timeperiod mapping functionality is added (SFA-214)
        /**
         * This is needed to make sure that this view is read only when forecasts module has been set up.
         */
        if(this.model.get('is_setup')) {
            // if forecasts has been setup, this is read only!
            field.options.def.view = 'detail';
        }
        app.view.View.prototype._renderField.call(this, field);

    },

    /**
     * Sets up the fields with the handlers needed to properly get and set their values for the timeperiods config view.
     * @param field the field to be setup for this config view.
     * @return {*} field that has been properly setup and augmented to function for this config view.
     * @private
     */
    _setUpTimeperiodConfigField: function(field) {
        switch(field.name) {
            case "timeperiod_shown_forward":
            case "timeperiod_shown_backward":
                return this._setUpTimeperiodShowField(field);
            case "timeperiod_interval":
                return this._setUpTimeperiodIntervalBind(field);
            default:
                return field;
        }
    },

    /**
     * Sets up the timeperiod_shown_forward and timeperiod_shown_backward dropdowns to set the model and values properly
     * @param field The field being set up.
     * @return {*} The configured field.
     * @private
     */
    _setUpTimeperiodShowField: function (field) {
        // ensure Date object gets an additional function
        field.events = _.extend({"change select":  "_updateSelection"}, field.events);
        field.bindDomChange = function() {};

        field._updateSelection = function(event, input) {
            var value =  parseInt(input.selected);
            this.def.value = value;
            this.model.set(this.name, value);
        };

        field.def.value = this.model.get(field.name) || 1;
        return field;
    }

    ,
    /**
     * Sets up the change event on the timeperiod_interval drop down to maintain the interval selection
     * and push in the default selction for the leaf period
     * @param field the dropdown interval field
     * @return {*}
     * @private
     */
    _setUpTimeperiodIntervalBind: function(field) {

        field.def.value = this.model.get(field.name);

        // ensure selected day functions like it should
        field.events = _.extend({"change select":  "_updateIntervals"}, field.events);
        field.bindDomChange = function() {};

        if(typeof(field.def.options) == 'string') {
            field.def.options = app.lang.getAppListStrings(field.def.options);
        }

        /**
         * function that updates the selected interval
         * @param event
         * @param input
         * @private
         */
        field._updateIntervals = function(event, input) {
            //get the timeperiod interval selector
            var selected_interval = "Annual";
            if(_.has(input, "selected")) {
                selected_interval = input.selected;
            }
            this.def.value = selected_interval;
            this.model.set(this.name, selected_interval);
            this.model.set('timeperiod_leaf_interval', selected_interval == 'Annual' ? 'Quarter' : 'Month');
        }
        return field;

    }
})