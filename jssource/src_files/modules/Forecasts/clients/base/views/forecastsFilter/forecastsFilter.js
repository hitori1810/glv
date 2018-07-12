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
 * View that displays a list of models pulled from the context's collection.
 * @class View.Views.ForecastsFilterView
 * @alias SUGAR.App.layout.FilterView
 * @extends View.View
 */
({
    /**
     * Store the created fields by name in an array
     */
    fields:[],

    events:{
        'focus .chzn-container input': 'dropFocus',
        'click .chzn-container .chzn-drop' : 'chznClick',
        'click .chzn-select-legend': 'chznContainerClick'
    },

    dropFocus:function (evt) {
        var el = $(evt.target).parents('.chzn-container').find('.chzn-drop');
        var left = el.css('left');
        if (left == "-9000px") {
            el.width(0);
        } else {
            if(rtl && rtl!="undefined") {
                el.width(100).css("left", "0px").css("right", "auto");
            } else {
                el.width(100).css("left", "auto").css("right", "0px");
            }
        }
    },

    chznClick: function(evt) {
        $(evt.target).css("right","auto");
    },

    /**
     * handler for click event on filter
     * @param evt
     */
    chznContainerClick: function (evt)
    {
        var chosen = this.fields.ranges.$el.find('select').data('chosen');
        chosen.results_toggle();
    },

    /**
     * Initialize because we need to set the selectedUser variable
     * @param options
     */
    initialize:function (options) {
        app.view.View.prototype.initialize.call(this, options);
        this.selectedUser = {id:app.user.get('id'), isManager:app.user.get('isManager'), showOpps:false};
    },

    /**
     * Clean up any left over bound data to our context
     */
    unbindData : function() {
        if(this.context.forecasts) this.context.forecasts.off(null, null, this);
        app.view.View.prototype.unbindData.call(this);
    },

    /**
     * Watch for the selectedUser Change
     */
    bindDataChange:function () {

        var self = this;

        if (this.context && this.context.forecasts) {
            this.context.forecasts.on("change:selectedUser", function (context, user) {
                self.selectedUser = user;
                this.toggleRangesFieldVisibility();
            }, this);
        }
    },

    /**
     * Method to toggle the field visibility of the group by field
     */
    toggleRangesFieldVisibility:function () {
        if (!_.isUndefined(this.fields['ranges']) && this.selectedUser.isManager && this.selectedUser.showOpps === false) {
            this.fields['ranges'].$el.hide();
        } else {
            this.fields['ranges'].$el.show();
        }
    },

    /**
     * Overriding _renderField because we need to determine whether the config settings are set to show buckets or
     * binary for forecasts and adjusts the ranges filter accordingly
     * @param field
     * @private
     */
    _renderField:function (field) {
        if (field.name == 'ranges') {
            field.def.options = this.context.forecasts.config.get('buckets_dom') || 'show_binary_dom';
            field.def.value = this.context.forecasts.has("selectedRanges") ? this.context.forecasts.get("selectedRanges") : app.defaultSelections.ranges;
            field = this._setUpRangesField(field);
        }
        app.view.View.prototype._renderField.call(this, field);

        field.$el.find('.chzn-container').css("width", "100%");
        field.$el.find('.chzn-choices').prepend('<legend class="chzn-select-legend">Filter <i class="icon-caret-down"></i></legend>');
        field.$el.find('.chzn-results li').after("<span class='icon-ok' />");

        // override default behavior of chosen - @see #58125
        var chosen = field.$el.find('select').data('chosen');
        chosen.container_mousedown = function(){};

        this.fields[field.name] = field;
    },

    /**
     * Override the render to have call the group by toggle
     *
     * @private
     */
    _render:function () {
        app.view.View.prototype._render.call(this);

        // toggle the visibility of the group by field for the initial render
        this.toggleRangesFieldVisibility();

        return this;
    },

    /**
     * Sets up the save event and handler for the commit_stage dropdown fields in the worksheet.
     * @param field the commit_stage field
     * @return {*}
     * @private
     */
    _setUpRangesField:function (field) {

        field.events = _.extend({"change select":"_updateSelections"}, field.events);
        field.bindDomChange = function () {
        };

        /**
         * updates the selection when a change event is triggered from a dropdown/multiselect
         * @param event the event that was triggered
         * @param input the (de)selection
         * @private
         */
        field._updateSelections = function (event, input) {
            var selectedRanges = this.context.forecasts.get("selectedRanges");
            var selectElement = this.$el.find("select");
            var id;

            if (!_.isArray(selectedRanges)) {
                selectedRanges= new Array();
            }

            if (this.def.multi) { // if it's a multiselect we need to add or drop the correct values from the filter model
                if (_.has(input, "selected")) {
                    id = input.selected;
                    if (!_.contains(selectedRanges, id)) {
                        selectedRanges = _.union(selectedRanges, id);
                    }
                } else if (_.has(input, "deselected")) {
                    id = input.deselected;
                    if (_.contains(selectedRanges, id)) {
                        selectedRanges = _.without(selectedRanges, id);
                    }
                }
            } else {  // not multi, just set the selected filter
                selectedRanges = new Array(input.selected);
            }
            this.context.forecasts.set('selectedRanges', selectedRanges);
        };

        return field;
    }
})
