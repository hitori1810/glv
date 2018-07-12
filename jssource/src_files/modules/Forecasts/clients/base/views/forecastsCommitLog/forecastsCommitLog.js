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
 * View that displays committed forecasts for current user.  If the manager view is selected, the Forecasts
 * of Rollup type are shown; otherwise the Forecasts of Direct type are shown.
 *
 * @class View.Views.GridView
 * @alias SUGAR.App.layout.GridView
 * @extends View.View
 */
({
    /**
     * The url for the REST endpoint
     */
    url : 'rest/v10/Forecasts/committed',

    /**
     * The class selector representing the element which contains the view output
     */
    viewSelector : '.forecastsCommitted',

    /**
     * Stores the Backbone collection of Forecast models
     */
    collection : {},

    /*
     * Stores the name to display in the view
     */
    fullName : '',

    /**
     * Stores the best case to display in the view
     */
    bestCase : 0,

    /**
     * Stores the likely case to display in the view
     */
    likelyCase : 0,

    /**
     * Stores the worst case to display in the view
     */
    worstCase : 0,

    /**
     * Used to query for the user_id value in Forecasts
     */
    userId : '',

    /**
     * Used to query for the timeperiod_id value in Forecasts
     */
    timePeriodId : '',

    /**
     * Used to query for the forecast_type value in Forecasts
     */
    forecastType : 'Direct',

    /**
     * Stores the historical log of the Forecast entries
     */
    historyLog: [],

    /**
     * Stores the Forecast totals to use when creating a new entry
     */
    totals : null,

    /**
     * Template to use when updating the bestCase on the committed bar
     */
    bestTemplate : _.template('<%= bestCase %>&nbsp;<span class="icon-sm committed_arrow<%= bestCaseCls %>"></span>'),

    /**
     * Template to use wen updating the likelyCase on the committed bar
     */
    likelyTemplate : _.template('<%= likelyCase %>&nbsp;<span class="icon-sm committed_arrow<%= likelyCaseCls %>"></span>'),

    /**
     * Template to use wen updating the WorstCase on the committed bar
     */
    worstTemplate : _.template('<%= worstCase %>&nbsp;<span class="icon-sm committed_arrow<%= worstCaseCls %>"></span>'),

    runningFetch : false,

    /**
     * Used to determine whether or not to visibly show the Commit log
     */
    showHistoryLog : false,

    /**
     * Used to determine whether or not to visibly show the extended Commit log
     */
    showMoreLog : false,

    /**
     * the timeperiod field metadata that gets used at render time
     */
    timeperiod: {},

    /**
     * Store the Best Case Number from the very last commit in the log
     */
    previousBestCase: '',
    /**
     * Store the Likely Case Number from the very last commit in the log
     */
    previousLikelyCase: '',
    /**
     * Store the Worst Case Number from the very last commit in the log
     */
    previousWorstCase: '',

    events : {
        'click i[id=show_hide_history_log]' : 'showHideHistoryLog'
    },

    initialize : function(options) {
        app.view.View.prototype.initialize.call(this, options);
        this.collection = this.context.forecasts.committed;

        this.fullName = app.user.get('full_name');
        this.userId = app.user.get('id');
        this.forecastType = (app.user.get('isManager') == true && app.user.get('showOpps') == false) ? 'Rollup' : 'Direct';
        this.timePeriodId = app.defaultSelections.timeperiod_id.id;
        this.selectedUser = {id: app.user.get('id'), "isManager":app.user.get('isManager'), "showOpps": false};

        this.bestCase = "";
        this.likelyCase = "";
        this.worstCase = "";
        this.showHistoryLog = false;
    },

    /**
     * Switch showHistoryLog flag for expanding/collapsing log after commit
     */
    showHideHistoryLog: function() {
        this.$el.find('i[id=show_hide_history_log]').toggleClass('icon-caret-down icon-caret-up');
        this.$el.find('div[id=history_log_results]').toggleClass('hide');

    },

    /**
     * Renders the component
     */
    _renderHtml : function(ctx, options) {
        app.view.View.prototype._renderHtml.call(this, ctx, options);
        
        if(this.showHistoryLog) {

            if(this.showMoreLog) {
                this.$el.find('div[id=more_log_results]').removeClass('hide');
                this.$el.find('div[id=more]').html('<p><span class=" icon-minus-sign">&nbsp;' + App.lang.get('LBL_LESS', 'Forecasts') + '</span></p><br />');
            }
        }
        
        this.$el.parents('div.topline').find("span.lastBestCommit").html(this.previousBestCase);
        this.$el.parents('div.topline').find("span.lastLikelyCommit").html(this.previousLikelyCase);
        this.$el.parents('div.topline').find("span.lastWorstCommit").html(this.previousWorstCase);
    },



    bindDataChange: function() {
        var self = this;
        this.collection = this.context.forecasts.committed;
        this.collection.on("reset change", function() {
            self.buildForecastsCommitted();
        }, this);       
    },

    /**
     * Utility method to get the arrow and color depending on how the values match up.
     *
     * @param newValue
     * @param currentValue
     * @return {String}
     */
    getColorArrow: function(newValue, currentValue)
    {
        var cls = '';

        cls = (newValue > currentValue) ? ' icon-arrow-up font-green' : ' icon-arrow-down font-red';
        cls = (newValue == currentValue) ? '' : cls;

        return cls
    },

    /**
     * Utility method to reset the committed log in the event that no models are returned for the 
     * selected user/timeperiod
     */
    resetCommittedLog:function(){
        this.bestCase = "";
        this.likelyCase = "";
        this.worstCase = "";
        this.previousBestCase = "";
        this.previousLikelyCase = "";
        this.previousWorstCase = "";
        this.showHistoryLog = false;
        this.previousDateEntered = "";
    },

    buildForecastsCommitted:function () {
        var self = this;
        var count = 0;
        var previousModel;
        
        //Reset the history log
        self.historyLog = [];

        // if we have no models, exit out of the method
        if (_.isEmpty(self.collection.models)) {
            self.resetCommittedLog();
            self.render();
            return;
        }
       
        // get the first model so we can get the previous date entered
        previousModel = _.first(self.collection.models);

        // parse out the previous date entered
        var dateEntered = new Date(Date.parse(previousModel.get('date_entered')));
        if (dateEntered == 'Invalid Date') {
            dateEntered = previousModel.get('date_entered');
        }
        // set the previous date entered in the users format
        self.previousDateEntered = app.date.format(dateEntered, app.user.getPreference('datepref') + ' ' + app.user.getPreference('timepref'));
        
        //loop through from oldest to newest to build the log correctly
        var loopPreviousModel = '';
        var models = _.clone(self.collection.models).reverse();
        _.each(models, function (model) {
            self.historyLog.push(app.forecasts.utils.createHistoryLog(loopPreviousModel, model, self.context.forecasts.config));
            loopPreviousModel = model;           
        });
        
        //reset the order of the history log for display
        self.historyLog.reverse();

        // save the values from the last model to display in the dataset line on the interface
        this.previousBestCase = app.currency.formatAmountLocale(previousModel.get('best_case'));
        this.previousLikelyCase = app.currency.formatAmountLocale(previousModel.get('likely_case'));
        this.previousWorstCase = app.currency.formatAmountLocale(previousModel.get('worst_case'));

        self.render();
    }
})
