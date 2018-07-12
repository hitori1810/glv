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
 * View that displays a chart
 * @class View.Views.ForecastsChartView
 * @alias SUGAR.App.layout.ForecastsChartView
 * @extends View.View
 */
({
    values: new Backbone.Model(),
    url:'rest/v10/Forecasts/chart',

    chart: null,

    chartRendered: false,

    chartDataSet : [],
    chartGroupByOptions : [],

    defaultDataset: '',
    defaultGroupBy: '',

    chartTitle: '',
    timeperiod_label: '',

    stopRender: false,

    /**
     * events on the view to watch for
     */
    events : {
        'click #forecastsChartDisplayOptions div.datasetOptions label.radio' : 'changeDisplayOptions',
        'click #forecastsChartDisplayOptions div.groupByOptions label.radio' : 'changeGroupByOptions'
    },

    initialize : function(options) {
        app.view.View.prototype.initialize.call(this, options);

        // clear out the values if the object is re-inited.
        this.values.clear({silent: true});
    },

    /**
     * event handler to update which dataset is used.
     */
    changeDisplayOptions : function(evt) {
        evt.preventDefault();
        this.handleRenderOptions({dataset: this.handleOptionChange(evt)})
    },

    /**
     * Handle any group by changes
     */
    changeGroupByOptions: function(evt) {
        evt.preventDefault();
        this.handleRenderOptions({group_by:_.first(this.handleOptionChange(evt))});
    },

    /**
     * Handle the click event for the optins menu
     *
     * @param evt
     * @return {Array}
     */
    handleOptionChange: function(evt) {
        el = $(evt.currentTarget);
        // get the parent
        pel = el.parents('div:first');

        // un-check the one that is currently checked
        pel.find('label.checked').removeClass('checked');

        // check the one that was clicked
        el.addClass('checked');

        // return the dataset from the one that was clicked
        return [el.attr('data-set')];
    },

    /**
     * find all the checkedOptions in a give option class
     *
     * @param {string} divClass
     * @return {Array}
     */
    getCheckedOptions : function(divClass) {
        // find the checked options
        var chkOptions = this.$el.find("div." + divClass + " label.checked");

        // parse the array to get the data-set attributed
        var options = [];
        _.each(chkOptions, function(o) {
            options.push($(o).attr('data-set'));
        });

        // return the found options
        return options;
    },

    /**
     * Override the _rerderHtml function
     *
     * @protected
     */
    _renderHtml: function(ctx, options) {
        //this.chartTitle = app.lang.get("LBL_CHART_FORECAST_FOR", "Forecasts") + ' ' + app.defaultSelections.timeperiod_id.label;
        this.timeperiod_label = app.defaultSelections.timeperiod_id.label;

        this.chartDataSet = this.getChartDatasets();
        this.chartGroupByOptions = app.metadata.getStrings('app_list_strings').forecasts_chart_options_group || [];
        this.defaultDataset = app.defaultSelections.dataset;
        this.defaultGroupBy = app.defaultSelections.group_by;

        // make sure that the default data set is actually shown, if it's not then we need
        // to set it to the first available option from the allowed dataset.
        if(_.isUndefined(this.chartDataSet[this.defaultDataset])) {
            this.defaultDataset = _.first(_.keys(this.chartDataSet));
        }

        app.view.View.prototype._renderHtml.call(this, ctx, options);

        var values = {
            user_id: app.user.get('id'),
            display_manager : app.user.get('isManager'),
            timeperiod_id : app.defaultSelections.timeperiod_id.id,
            group_by : _.first(this.getCheckedOptions('groupByOptions')),
            dataset : this.getCheckedOptions('datasetOptions'),
            ranges: app.defaultSelections.ranges
        };

        this.handleRenderOptions(values);
    },

    _render : function() {
        app.view.View.prototype._render.call(this);

        this.toggleRepOptionsVisibility();
    },

    toggleRepOptionsVisibility : function() {
        if(this.values.get('display_manager') === true) {
            this.$el.find('div.groupByOptions').hide();
        } else {
            this.$el.find('div.groupByOptions').show();
        }
    },

    /**
     * Clean up any left over bound data to our context
     */
    unbindData : function() {
        if(this.context.forecasts.worksheet) this.context.forecasts.worksheet.off(null, null, this);
        if(this.context.forecasts.worksheetmanager) this.context.forecasts.worksheetmanager.off(null, null, this);
        if(this.context.forecasts) this.context.forecasts.off(null, null, this);
        if(this.values) this.values.off(null, null, this);
        app.view.View.prototype.unbindData.call(this);
    },

    /**
     * Listen to changes in values in the context
     */
    bindDataChange:function () {
        var self = this;
        
        //This is fired when anything in the worksheets is saved.  We want to wait until this happens
        //before we go and grab new chart data.
        this.context.forecasts.on("forecasts:committed:saved", function(){
            self.renderChart();
        });

        this.context.forecasts.on("forecasts:worksheet:saved", function(totalSaved, worksheet, isDraft) {
            // we only want this to run if the totalSaved was greater than zero and we are saving the draft version
            if(totalSaved > 0 && isDraft == true) {
                self.renderChart();
            }
        });

        this.context.forecasts.on('change:selectedUser', function (context, user) {
            if(!_.isEmpty(self.chart)) {
                self.handleRenderOptions({user_id: user.id, display_manager : (user.showOpps === false && user.isManager === true)});
                self.toggleRepOptionsVisibility();
            }
        });
        this.context.forecasts.on('change:selectedTimePeriod', function (context, timePeriod) {
            if(!_.isEmpty(self.chart)) {
                self.timeperiod_label = timePeriod.label;
                self.handleRenderOptions({timeperiod_id: timePeriod.id});
            }
        });
        this.context.forecasts.on('change:selectedGroupBy', function (context, groupBy) {
            if(!_.isEmpty(self.chart)) {
                self.handleRenderOptions({group_by: groupBy});
            }
        });
        this.context.forecasts.on('change:selectedRanges', function(context, value) {
            if(!_.isEmpty(self.chart)) {
                self.handleRenderOptions({ranges: value});
            }
        });
        this.context.forecasts.on('change:hiddenSidebar', function(context, value){
            // set the value of the hiddenSidecar to we can stop the render if the sidebar is hidden
            self.stopRender = value;
            // if the sidebar is not hidden
            if(value == false){
                // we need to force the render to happen again
                self.renderChart();
            }
        });
        // watch for the change event to fire.  if it fires make sure something actually changed in the array
        this.values.on('change', function(context, value) {
            if(!_.isEmpty(value.changes)) {
                self.renderChart();
            }
        });
    },

    /**
     * Handle putting the options into the values array that is used to keep track of what changes
     * so we only render when something changes.
     * @param options
     */
    handleRenderOptions:function (options) {
        this.values.set(options);
    },

    /**
     * Initialize or update the chart
     */
    renderChart:function () {
        this._initializeChart();
    },

    /**
     * Get the Chart Datasets that are only shown in the Worksheet
     *
     * @return {Object}
     */
    getChartDatasets: function() {
        var self = this;
        var ds = app.metadata.getStrings('app_list_strings').forecasts_chart_options_dataset || [];

        cfg = this.context.forecasts.config;
        cfg_key = 'show_worksheet_';

        var returnDs = {};
        _.each(ds, function(value, key){
            if(cfg.get(cfg_key + key) == 1) {
                returnDs[key] = value
            }
        }, self);
        return returnDs;
    },

    /**
     * Render the chart for the first time
     *
     * @return {Object}
     * @private
     */
    _initializeChart:function () {

        if(this.stopRender) {
            return {};
        }

        var chart,
            chartId = "db620e51-8350-c596-06d1-4f866bfcfd5b",
            css = {
                "gridLineColor":"#cccccc",
                "font-family":"Arial",
                "color":"#000000"
            },
            chartConfig = {
                "orientation":"vertical",
                "barType": this.values.get('display_manager') ? "grouped" : "stacked",
                "tip":"name",
                "chartType":"barChart",
                "imageExportType":"png",
                "showNodeLabels":false,
                "showAggregates":false,
                "saveImageTo":"",
                "dataPointSize":"5"
            };

        var oldChart = $("#" + chartId + "-canvaswidget");
        if(!_.isEmpty(oldChart)) {
            oldChart.remove();
        }

        SUGAR.charts = $.extend(SUGAR.charts,
            {
              get : function(url, params, success)
              {
                  var data = {
                      r: new Date().getTime()
                  };
                  data = $.extend(data, params);

                  url = app.api.buildURL('Forecasts', 'chart', '', data);

                  app.api.call('read', url, data, {success : success});
              }
            }
        );

        if(this.values.get('display_manager') === true) {
            this.values.set({ranges: 'include'}, {silent: true});
        }

        // update the chart title
        var hb = Handlebars.compile("{{str_format key module args}}");
        var text = hb({'key' : "LBL_CHART_FORECAST_FOR", 'module' : 'Forecasts', 'args' : this.timeperiod_label});
        this.$el.find('h4').html(text);

        var params = this.values.toJSON() || {};
        params.contentEl = 'chart';
        params.minColumnWidth = 120;

        chart = new loadSugarChart(chartId, this.url, css, chartConfig, params, _.bind(function(chart){
            this.chart = chart;
        }, this));
        this.chartRendered = true;
    }
})