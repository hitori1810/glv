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
     * used to hold the label string from metadata to get rendered in the template.
     */
    label: '',

    /**
     * used to hold the metadata for the forecasts_ranges field, used to manipulate and render out as the radio buttons
     * that correspond to the fieldset for each bucket type.
     */
    forecast_ranges_field: {},

    /**
     * Used to hold the buckets_dom field metadata, used to retrieve and set the proper bucket dropdowns based on the
     * selection for the forecast_ranges
     */
    buckets_dom_field: {},

    /**
     * Used to hold the category_ranges field metadata, used for rendering the sliders that correspond to the range
     * settings for each of the values contained in the selected buckets_dom dropdown definition.
     */
    category_ranges_field: {},

    /**
     * Used to keep track of the selection as it changes so that it can be used to determine how to hide and show the
     * sub-elements that contain the fields for setting the category ranges
     */
    selection: '',

    /**
     * a placeholder for the individual range sliders that will be used to build the range setting
     */
    fieldRanges: {},

    //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
    /**
     * This is used to determine whether we need to lock the module or not, based on whether forecasts has been set up already
     */
    disableRanges: false,

    /**
     * Initializes the view, and then initializes up the parameters for the field metadata holder parameters that get
     * used to render the fields in the view, since they are not rendered in a standard way.
     * @param options
     */
    initialize: function(options) {
        app.view.View.prototype.initialize.call(this, options);

        this.label = _.first(this.meta.panels).label;

        // sets this.<array_item>_field to the corresponding field metadata, which gets used by the template to render these fields later.
        _.each(['forecast_ranges', 'buckets_dom', 'category_ranges'], function(item){
            var fields = _.first(this.meta.panels).fields;

            this[item + '_field'] = function(fieldName, fieldMeta) {
                return _.find(fieldMeta, function(field) { return field.name == this; }, fieldName);
            }(item, fields);

        }, this);

        // set the values for forecast_ranges_field and buckets_dom_field from the model, so it can be set to selected properly when rendered
        this.forecast_ranges_field.value = this.model.get('forecast_ranges');
        this.buckets_dom_field.value = this.model.get('buckets_dom');

        if(!_.isUndefined(options.meta.registerLabelAsBreadCrumb) && options.meta.registerLabelAsBreadCrumb == true) {
            this.layout.registerBreadCrumbLabel(options.meta.panels[0].label);
        }
    },

    _render: function() {
        //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
        // This will be set to true if the forecasts ranges setup should be disabled
        this.disableRanges = this.context.forecasts.config.get('has_commits');
        this.selection = this.context.forecasts.config.get('forecast_ranges');

        app.view.View.prototype._render.call(this);

        this._addForecastRangesSelectionHandler();

        return this;
    },

    /**
     * Adds the selection event handler on the forecast ranges radio which sets on the model the value of the bucket selection, the
     * correct dropdown list based on that selection, as well as opens up the element to show the range setting sliders
     * @private
     */
    _addForecastRangesSelectionHandler: function (){
        // finds all radiobuttons with this name
        var elements = this.$el.find(':radio[name="' + this.forecast_ranges_field.name + '"]');

        // apply the change handler to each of the ranges radio button elements.
        _.each(elements, function(el) {
            $(el).change({
                view:this
            }, this.selectionHandler);
            // of the elements find the one that is checked
            if($(el).prop('checked')) {
                // manually trigger the handler on the checked element so that it will render
                // for the default/previously set value
                $(el).triggerHandler("change");
            }
        }, this);
    },

    selectionHandler: function(event) {
        var view = event.data.view,
            oldValue,
            bucket_dom,
            hideElement,
            showElement,
            ranges_options;

        // get the value of the previous selection so that we can hide that element
        oldValue = view.selection;
        // now set the new selection, so that if they change it, we can later hide the things we are about to show.
        view.selection = this.value;

        bucket_dom = view.buckets_dom_field.options[this.value];

        hideElement = view.$el.find('#' + oldValue + '_ranges');
        showElement = view.$el.find('#' + this.value + '_ranges');

        if (showElement.children().length == 0) {
            ranges_options = app.lang.getAppListStrings(bucket_dom);
            // add the things here...
            view.fieldRanges[this.value] = {};
            showElement.append('<p>' + app.lang.get('LBL_FORECASTS_CONFIG_' + this.value.toUpperCase() + '_RANGES_DESCRIPTION', 'Forecasts') + '</p>');
            _.each(ranges_options, function(label, key) {
                if (key != 'exclude') {

                    var rangeField,
                        model = new Backbone.Model(),
                        fieldSettings;

                    // get the value in the current model and use it to display the slider
                    model.set(key, this.view.model.get(this.ranges + '_ranges')[key]);

                    // build a range field
                    fieldSettings = {
                        view: this.view,
                        def: _.find(
                            _.find(
                                _.first(this.view.meta.panels).fields,
                                function(field) {
                                    return field.name == 'category_ranges';
                                }
                            ).ranges,
                            function(range) {
                                return range.name == this.key
                            },
                            {key: key}
                        ),
                        viewName:'edit',
                        context: this.view.context,
                        module: this.view.module,
                        model: model,
                        meta: app.metadata.getField('range')
                    };

                    //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
                    if(this.view.disableRanges) {
                        fieldSettings.viewName = 'detail';
                        fieldSettings.def.view = 'detail';
                    }

                    rangeField = app.view.createField(fieldSettings);
                    this.showElement.append('<b>'+ label +':</b>').append(rangeField.el);
                    rangeField.render();

                    // now give the view a way to get at this field's model, so it can be used to set the value on the
                    // real model.
                    view.fieldRanges[this.ranges][key] = rangeField;

                    // this gives the field a way to save to the view's real model.  It's wrapped in a closure to allow us to
                    // ensure we have everything when switching contexts from this handler back to the view.
                    rangeField.sliderDoneDelegate = function(ranges, key, view) {
                        return function (value) {
                            view.updateRangeSettings(ranges, key, value);
                        };
                    }(this.ranges, key, this.view);
                }
            }, {view: view, showElement:showElement, ranges: this.value});
            showElement.append('<b>'+ ranges_options['exclude'] +':</b>').append($('<p>' + app.lang.get("LBL_FORECASTS_CONFIG_RANGES_EXCLUDE_INFO", "Forecasts")+ '</p>'));
            // use call to set context back to the view for connecting the sliders
            view.connectSliders.call(view, this.value, view.fieldRanges);
        }

        if (hideElement) {
            hideElement.toggleClass('hide', true);
        }
        if (showElement){
            showElement.toggleClass('hide', false);
        }

        // set the forecast ranges and associated dropdown dom on the model
        view.model.set(this.name, this.value);
        view.model.set(view.buckets_dom_field.name, bucket_dom);
    },

    /**
     * updates the setting in the model for the specific range types.
     * This gets triggered when the range after the user changes a range slider
     * @param category - the selected category: `show_buckets` or `show_binary`
     * @param range - the range being set, i. e. `include`, `exclude` or `upside` for `show_buckets` category
     * @param value - the value being set
     */
    updateRangeSettings: function(category, range, value) {
        var catRange = category + '_ranges',
            setting = this.model.get(catRange);
        setting[range] = value;
        this.model.unset(catRange, {silent: true});
        this.model.set(catRange, setting);
    },

    /**
     * Graphically connects the sliders to the one below, so that they move in unison when changed, based on category.
     * @param ranges - the forecasts category that was selected, i. e. 'show_binary' or 'show_buckets'
     * @param sliders - an object containing the sliders that have been set up in the page.  This is created in the
     * selection handler when the user selects a category type.
     */
    connectSliders: function(ranges, sliders) {
        var rangeSliders = sliders[ranges];

        if(ranges == 'show_binary') {
            rangeSliders.include.sliderChangeDelegate = function (value) {
                // lock the upper handle to 100, as per UI/UX requirements to show a dual slider
                rangeSliders.include.$el.find(rangeSliders.include.fieldTag).noUiSlider('move', {handle: 'upper', to: rangeSliders.include.def.maxRange});
                // set the excluded range based on the lower value of the include range
                this.view.setExcludeValueForLastSlider(value, ranges, rangeSliders.include);
            };
        } else if (ranges == 'show_buckets') {
            rangeSliders.include.sliderChangeDelegate = function (value) {
                // lock the upper handle to 100, as per UI/UX requirements to show a dual slider
                rangeSliders.include.$el.find(rangeSliders.include.fieldTag).noUiSlider('move', {handle: 'upper', to: rangeSliders.include.def.maxRange});

                rangeSliders.upside.$el.find(rangeSliders.upside.fieldTag).noUiSlider('move', {handle: 'upper', to: value.min-1});
                if(value.min <= rangeSliders.upside.$el.find(rangeSliders.upside.fieldTag).noUiSlider('value')[0] + 1) {
                    rangeSliders.upside.$el.find(rangeSliders.upside.fieldTag).noUiSlider('move', {handle: 'lower', to: value.min-2});
                }
            };
            rangeSliders.upside.sliderChangeDelegate = function (value) {
                rangeSliders.include.$el.find(rangeSliders.include.fieldTag).noUiSlider('move', {handle: 'lower', to: value.max+1});
                // set the excluded range based on the lower value of the upside range
                this.view.setExcludeValueForLastSlider(value, ranges, rangeSliders.upside);
            };
        }
    },

    /**
     * Provides a way for the last of the slider fields in the view, to set the value for the exclude range.
     * @param value the range value of the slider
     * @param ranges the selected config range
     * @param slider the slider
     */
    setExcludeValueForLastSlider: function(value, ranges, slider) {
        var excludeRange = {
            min: 0,
            max: 100
        },
        settingName = ranges + '_ranges',
        setting = this.model.get(settingName);

        excludeRange.max = value.min - 1;
        excludeRange.min = slider.def.minRange;
        setting.exclude = excludeRange;
        this.model.set(settingName, setting);
    }
})