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
 * View that displays header for current app
 *
 * @class View.Views.WorksheetView
 * @alias SUGAR.App.layout.WorksheetView
 * @extends View.View
 *
 *
 * Events Triggered
 *
 * forecasts:commitButtons:triggerCommit
 *      on: context.forecasts
 *      by: safeFetch()
 *      when: user clicks ok on confirm dialog that they want to commit data
 *
 * forecasts:worksheet:rendered
 *      on: context.forecasts
 *      by: _render
 *      when: the worksheet is done rendering
 *
 * forecasts:worksheet:filtered
 *      on: context.forecasts
 *      by: updateWorksheetBySelectedRanges()
 *      when: dataTable is finished filtering itself
 *
 * forecasts:worksheet:filtered
 *      on: context.forecasts
 *      by: updateWorksheetBySelectedRanges()
 *      when: dataTable is finished filtering itself and has destroyed and redrawn itself
 *      
 * forecasts:worksheet:saved
 *      on: context.forecasts
 *      by: saveWorksheet()
 *      when: saving the worksheet.
 *      
 * forecasts:worksheet:dirty
 *      on: context.forecasts
 *      by: change:worksheet
 *      when: the worksheet is changed.
 */
({

    url: 'rest/v10/ForecastWorksheets',
    show: false,
    viewModule: {},
    selectedUser: {},
    timePeriod : '',
    gTable:'',
    gTableDefs:{},
    aaSorting:[],
    // boolean for enabled expandable row behavior
    isExpandableRows:'',
    isEditableWorksheet:false,
    _collection:{},
    columnDefs: [],
    mgrNeedsCommitted: false,
    commitButtonEnabled: false,
    commitFromSafeFetch: false,
    // boolean to denote that a fetch is currently in progress
    fetchInProgress: false,

    /**
     * A Collection to keep track of all the dirty models
     */
    dirtyModels : new Backbone.Collection(),

    /**
     * If the timeperiod is changed and we have dirtyModels, keep the previous one to use if they save the models
     */
    dirtyTimeperiod : '',

    /**
     * If the timeperiod is changed and we have dirtyModels, keep the previous one to use if they save the models
     */
    dirtyUser : '',
    
    /**
     * Initialize the View
     *
     * @constructor
     * @param {Object} options
     */
    initialize:function (options) {
        var self = this;
        
        self.gTableDefs = {
                "bAutoWidth": false,
                "aoColumnDefs": self.columnDefs,
                "aaSorting": self.aaSorting,
                "bInfo":false,
                "bPaginate":false
          };
        
        this.viewModule = app.viewModule;

        //set expandable behavior to false by default
        this.isExpandableRows = false;

        app.view.View.prototype.initialize.call(this, options);
        this._collection = this.context.forecasts.worksheet;

        //set up base selected user
        this.selectedUser = {id: app.user.get('id'), "isManager":app.user.get('isManager'), "showOpps": false};

        // INIT tree with logged-in user       
        this.timePeriod = app.defaultSelections.timeperiod_id.id;
        this.updateWorksheetBySelectedRanges(app.defaultSelections.ranges);
        this._collection.url = this.createURL();
    },

    /**
     *
     * @return {String}
     */
    createURL:function() {
        var url = this.url;
        var args = {};
        if(this.timePeriod) {
           args['timeperiod_id'] = this.timePeriod;
        }

        if(this.selectedUser)
        {
           args['user_id'] = this.selectedUser.id;
        }
        
        url = app.api.buildURL('ForecastWorksheets', '', '', args);
        return url;
    },

    /**
     * Renders a field.
     *
     * This method sets field's view element and invokes render on the given field.  If clickToEdit is set to true
     * in metadata, it will also render it as clickToEditable.
     * @param {View.Field} field The field to render
     * @protected
     */
    _renderField: function(field) {
        this._createFieldColumnDef(field.def);
        app.view.View.prototype._renderField.call(this, field);

        if (this.isEditableWorksheet === true && field.viewName !="edit" && field.def.clickToEdit === true && !_.contains(this.context.forecasts.config.get("sales_stage_won"), field.model.get('sales_stage')) && !_.contains(this.context.forecasts.config.get("sales_stage_lost"), field.model.get('sales_stage'))) {
            new app.view.ClickToEditField(field, this);
        }        
    },

    /**
     * Adding the field to the ColumnDef for the DataTables Plugin.  If the field is already in the array it will not be
     * added again.
     *
     * @param field {Object}        Field Def Information
     * @private
     */
    _createFieldColumnDef: function(field) {
        // make sure we don't already have the field in the list.
        if(!_.isEmpty(this.columnDefs) && _.find(this.columnDefs, _.bind(function(obj) {return obj.sName == this.name }, field))) {
            // we have the field in the columnDefs, just ignore it now.
            return;
        }
        if(field.enabled) {
            var fieldDef = {
                "sName": field.name,
                "aTargets": [ this.columnDefs.length ],
                "bVisible" : this.checkConfigForColumnVisibility(field.name)
            };

            if(!_.isUndefined(field.type)) {
                //Apply sorting for the worksheet
                switch(field.type)
                {
                    case "commitStage":
                    case "enum":
                    case "bool":
                        // disable sorting for non-numerical fields
                        fieldDef["bSortable"] = false;
                        break;
                    case "int":
                    case "editableInt":
                    case "currency":
                    case "editableCurrency":
                        fieldDef["sSortDataType"] = "dom-number";
                        fieldDef["sType"] = "numeric";
                        break;
                }
                // apply class and width
                switch(field.name) {
                    case "likely_case":
                        fieldDef["sClass"] = "number likely";
                        fieldDef["sWidth"] = "22%";
                        break;
                    case "best_case":
                        fieldDef["sClass"] = "number best";
                        fieldDef["sWidth"] = "22%";
                        break;
                    case "worst_case":
                        fieldDef["sClass"] = "number worst";
                        fieldDef["sWidth"] = "22%";
                        break;
                    case "probability":
                        fieldDef["sClass"] = "number";
                        break;
                }
            }

            this.columnDefs.push(fieldDef);
        }
    },

    /**
     * Clean up any left over bound data to our context
     */
    unbindData : function() {
        if(this._collection) { this._collection.off(null, null, this) };
        if(this.context.forecasts) { this.context.forecasts.off(null, null, this) };
        if(this.context.forecasts.config) { this.context.forecasts.config.off(null, null, this) };
        if(this.context.forecasts.worksheet) { this.context.forecasts.worksheet.off(null, null, this) };
        //if we don't unbind this, then recycle of this view if a change in rendering occurs will result in multiple bound events to possibly out of date functions
        $(window).unbind("beforeunload");
        app.view.View.prototype.unbindData.call(this);
    },

    /**
     * Is this worksheet dirty or not?
     * @return {boolean}
     */
    isDirty : function() {
        return (this.dirtyModels.length > 0);
    },

    /**
     *
     * @triggers forecasts:worksheet:saved
     * @return {Number}
     */
    saveWorksheet : function(isDraft) {
        // only run the save when the worksheet is visible and it has dirty records
        var totalToSave = 0;
        if(this.showMe()) {
            var self = this,
                saveCount = 0;

            totalToSave = self.dirtyModels.length;

            if(this.isDirty()) {
                self.dirtyModels.each(function(model){
                   //set properties on model to aid in save
                    model.set({
                        "draft" : (isDraft && isDraft == true) ? 1 : 0,
                        "timeperiod_id" : self.dirtyTimeperiod || self.timePeriod,
                        "current_user" : self.dirtyUser.id || self.selectedUser.id
                    }, {silent:true});

                    //set what url  is used for save
                    model.url = self.url.split("?")[0] + "/" + model.get("id");
                    model.save({}, {success: function() {
                        saveCount++;
                        //if this is the last save, go ahead and trigger the callback;
                        if(totalToSave === saveCount) {
                            self.context.forecasts.trigger('forecasts:worksheet:saved', totalToSave, 'rep_worksheet', isDraft);
                        }
                    }, silent: true});
                });

                self.cleanUpDirtyModels();
            } else {
                this.context.forecasts.trigger('forecasts:worksheet:saved', totalToSave, 'rep_worksheet', isDraft);
            }
        }

        return totalToSave
    },

    /**
     * Clean Up the Dirty Modules Collection and dirtyVariables
     */
    cleanUpDirtyModels : function() {
        // clean up the dirty records and variables
        this.dirtyModels.reset();
        this.dirtyTimeperiod = '';
        this.dirtyUser = '';
    },

    /**
     *
     * @param {Object} params
     */
    bindDataChange: function(params) {
        var self = this;
        if (this._collection) {
            this._collection.on("reset", function() {
                self.cleanUpDirtyModels();
                self.render();
            }, this);

            this._collection.on("change", function(model, changed) {
                if(_.include(_.keys(changed.changes), 'commit_stage')) {
                    this.gTable.fnDestroy();
                    this.gTable = this.$('.worksheetTable').dataTable(self.gTableDefs);
                }
                // The Model has changed via CTE. save it in the isDirty
                this.dirtyModels.add(model);
                this.context.forecasts.trigger('forecasts:worksheet:dirty', model, changed);
            }, this);
        }

        // listening for updates to context for selectedUser:change
        if (this.context.forecasts) {
            this.context.forecasts.on("change:selectedUser",
                function(context, selectedUser) {
                    this.updateWorksheetBySelectedUser(selectedUser);
                }, this);
            this.context.forecasts.on("change:selectedTimePeriod",
                function(context, timePeriod) {
                    this.updateWorksheetBySelectedTimePeriod(timePeriod);
                }, this);
            this.context.forecasts.on("change:selectedRanges",
                function(context, ranges) {
                    this.updateWorksheetBySelectedRanges(ranges);
                },this);
            this.context.forecasts.worksheet.on("change", function() {
                this.calculateTotals();
            }, this);
            this.context.forecasts.on("forecasts:committed:saved", function(){
                if(this.showMe()){
                    var model = this.context.forecasts.worksheet;
                    model.url = this.createURL();
                    this.safeFetch();
                    if(!this.commitFromSafeFetch){
                        this.mgrNeedsCommitted = true;
                    }
                    else{
                        this.commitFromSafeFetch = false;
                    }

                }
            }, this);

            this.context.forecasts.on("forecasts:commitButtons:enabled", function(){
                if(_.isEqual(app.user.get('id'), self.selectedUser.id)){
                    self.commitButtonEnabled = true;
                }
            },this);

            this.context.forecasts.on("forecasts:commitButtons:disabled", function(){
                self.commitButtonEnabled = false;
            },this);

            this.context.forecasts.on('forecasts:worksheet:saveWorksheet', function(isDraft) {
                this.saveWorksheet(isDraft);
            }, this);
            

            /*
             * // TODO: tagged for 6.8 see SFA-253 for details
            this.context.forecasts.config.on('change:show_worksheet_likely', function(context, value) {
                // only trigger if this component is rendered
                if(!_.isEmpty(self.el.innerHTML)) {
                    self.setColumnVisibility(['likely_case'], value, self);
                }
            });

            this.context.forecasts.config.on('change:show_worksheet_best', function(context, value) {
                // only trigger if this component is rendered
                if(!_.isEmpty(self.el.innerHTML)) {
                    self.setColumnVisibility(['best_case'], value, self);
                }
            });

            this.context.forecasts.config.on('change:show_worksheet_worst', function(context, value) {
                // only trigger if this component is rendered
                if(!_.isEmpty(self.el.innerHTML)) {
                    self.setColumnVisibility(['worst_case'], value, self);
                }
            });
            */
            this.context.forecasts.config.on('change:buckets_dom change:forecast_ranges', this.render, this);

            $(window).bind("beforeunload",function(){
                //if the record is dirty, warn the user.
                if(self.isDirty()){
                    return app.lang.get("LBL_WORKSHEET_SAVE_CONFIRM_UNLOAD", "Forecasts");
                }
                //special manager cases for messages
                else if(!_.isUndefined(self.context.forecasts) && (self.context.forecasts.get("currentWorksheet") == "worksheet") && self.selectedUser.isManager && self.context.forecasts.config.get("show_forecasts_commit_warnings")){
                    /*
                     * If the manager has a draft version saved, but hasn't committed that yet, they need to be shown a dialog that
                     * lets them know, and gives them the option of committing before the page reloads. This happens if the commit button
                     * is enabled and they are on the rep worksheet.
                     */
                    if(self.commitButtonEnabled ){
                        var msg = app.lang.get("LBL_WORKSHEET_COMMIT_CONFIRM", "Forecasts").split("<br>");
                        //show dialog
                        return msg[0];
                    }
                    else if(self.mgrNeedsCommitted){
                        return app.lang.get("LBL_WORKSHEET_COMMIT_ALERT", "Forecasts");
                    }
                }
            });
        }
    },

    /**
     * Sets the visibility of a column or columns if array is passed in
     *
     * @param cols {Array} the sName of the columns to change
     * @param value {*} int or Boolean, 1/true or 0/false to show the column
     * @param ctx {Object} the context of this view to have access to the checkForColumnsSetVisibility function
     */
    setColumnVisibility: function(cols, value, ctx) {
        var aoColumns = ctx.gTable.fnSettings().aoColumns;

        for(var i in cols) {
            var columnName = cols[i];
            for(var k in aoColumns) {
                if(aoColumns[k].sName == columnName)  {
                    this.gTable.fnSetColumnVis(k, value == 1);
                    break;
                }
            }
        }
    },

    /**
     * Checks if colKey is in the table config keymaps on the context
     *
     * @param colKey {String} the column sName to check in the keymap
     * @return {*} returns null if not found in the keymap, returns true/false if it did find it
     */
    checkConfigForColumnVisibility: function(colKey) {
        return app.forecasts.utils.getColumnVisFromKeyMap(colKey, this.name, this.context.forecasts.config);
    },

    /**
     * This function checks to see if the worksheet is dirty, and gives the user the option
     * of saving their work before the sheet is fetched.
     *
     * @param fetch {boolean} Tells the function to go ahead and fetch if true, or runs dirty checks (saving) w/o fetching if false
     */
    safeFetch: function(fetch){
        //fetch currently already in progress, no need to duplicate
        if(this.fetchInProgress) {
            return;
        }
        //mark that a fetch is in process so no duplicate fetches begin
        this.fetchInProgress = true;
        if(_.isUndefined(fetch))
        {
            fetch = true;
        }
        var collection = this._collection;
        var self = this;

        /*
         * First we need to see if the collection is dirty. This is marked if any of the models
         * is marked as dirty. This will show the "unsaved changes" dialog
         */
        if(self.isDirty()){
            //unsaved changes, ask if you want to save.
            if(confirm(app.lang.get("LBL_WORKSHEET_SAVE_CONFIRM", "Forecasts"))){
                self.context.forecasts.set({reloadCommitButton: true});

                var svWkFn = function() {
                    self.context.forecasts.off('forecasts:worksheet:saved', svWkFn);
                    collection.fetch();
                };

                self.context.forecasts.on('forecasts:worksheet:saved', svWkFn);
                this.saveWorksheet()
            }
            //user clicked cancel, ignore and fetch if fetch is enabled
            else{
                
                collection.isDirty = false;
                self.context.forecasts.set({reloadCommitButton: true});
                if(fetch){
                    collection.fetch();
                }
            }
        }
        /*
         * Next, we need to check to see if the user is a manager.  They have their own requirements and dialogs (those described below)
         */
        else if(self.selectedUser.isManager && (self.context.forecasts.get("currentWorksheet") == "worksheet") && self.context.forecasts.config.get("show_forecasts_commit_warnings")){
            /*
             * If the manager has a draft version saved, but hasn't committed that yet, they need to be shown a dialog that
             * lets them know, and gives them the option of committing before the page reloads. This happens if the commit button
             * is enabled and they are on the rep worksheet.
             */
            if(self.commitButtonEnabled){
                var msg = app.lang.get("LBL_WORKSHEET_COMMIT_CONFIRM", "Forecasts").split("<br>");
                //show dialog
                if(confirm(msg[0] + "\n\n" + msg[1])){
                    self.context.forecasts.trigger("forecasts:commitButtons:triggerCommit");
                    self.commitFromSafeFetch = true;
                }
                //canceled, continue fetching
                else{
                    if(fetch){
                        collection.fetch();
                    }
                }

            }
            else if(self.mgrNeedsCommitted){
                alert(app.lang.get("LBL_WORKSHEET_COMMIT_ALERT", "Forecasts"));
                self.mgrNeedsCommitted = false;
                if(fetch){
                    collection.fetch();
                }

            }
            //No popups needed, fetch like normal
            else{
                if(fetch){
                    collection.fetch();
                }
            }
        }
        //default case, fetch like normal
        else{    
            if(fetch){
                collection.fetch();
            }    
        }
        //mark that the fetch is over
        this.fetchInProgress = false;
    },

    /**
     * renders the view
     *
     * @return {Object} this
     * @private
     */
    _render: function() {
        var self = this;
        
        if(!this.showMe()){
            return false;
        }
        $("#view-sales-rep").addClass('show').removeClass('hide');
        $("#view-manager").addClass('hide').removeClass('show');

        this.context.forecasts.set({currentWorksheet: "worksheet"});
        this.isEditableWorksheet = this.isMyWorksheet();

        // empty out the columnDefs if it's be re-rendred again
        this.columnDefs = [];

        app.view.View.prototype._render.call(this);

        // if there is no data for the worksheet, this.columnDefs will be empty
        // but we still need the column visibility definitions
        if(_.isEmpty(this.columnDefs)) {
            _.each(this.options.meta.panels[0].fields, function(field) {
                // creates column def and adds it to this.columnDefs
                self._createFieldColumnDef(field);
            });
        }

        // set the columnDefs back into the tableDefs
        this.gTableDefs['aoColumnDefs'] = this.columnDefs;

        // render the table
        this.gTable = this.$('.worksheetTable').dataTable(this.gTableDefs);

        self.adjustCurrencyColumnWidths();
        self.calculateTotals();

        // fix the style on the rows that contain a checkbox
        this.$el.find('td:has(span>input[type=checkbox])').addClass('center');
                
        // Trigger event letting other components know worksheet finished rendering
        self.context.forecasts.trigger("forecasts:worksheet:rendered");

        //Check to see if any worksheet entries are older than the source data.  If so, that means that the
        //last commit is older, and that we need to enable the commit buttons
        var enableCommit = self._collection.find(function(model) {
            return !_.isEmpty(model.get("w_date_modified")) && (new Date(model.get("w_date_modified")) < new Date(model.get("date_modified")))
        }, this);
        if (_.isObject(enableCommit)) {
            self.context.forecasts.trigger("forecasts:commitButtons:enabled");
        }

        return this;
    },

    /**
     * set dynamic widths on currency columns showing original currency
     */
    adjustCurrencyColumnWidths : function() {

        var likelyConverted = this.$el.find('.likely .converted'),
            likelyOriginal = this.$el.find('.likely label.original'),
            bestConverted = this.$el.find('.best .converted'),
            bestOriginal = this.$el.find('.best label.original'),
            worstConverted = this.$el.find('.worst .converted'),
            worstOriginal = this.$el.find('.worst label.original');

        var likelyWidths= likelyConverted.map(function() {
            return $(this).width();
        }).get();

        var likelyLabelWidths= likelyOriginal.map(function() {
            return $(this).width();
        }).get();

        var bestWidths= bestConverted.map(function() {
            return $(this).width();
        }).get();

        var bestLabelWidths= bestOriginal.map(function() {
            return $(this).width();
        }).get();

        var worstWidths= worstConverted.map(function() {
            return $(this).width();
        }).get();

        var worstLabelWidths= worstOriginal.map(function() {
            return $(this).width();
        }).get();

        likelyConverted.width(_.max(likelyWidths));
        likelyOriginal.width(_.max(likelyLabelWidths));
        bestConverted.width(_.max(bestWidths));
        bestOriginal.width(_.max(bestLabelWidths));
        worstConverted.width(_.max(worstWidths));
        worstOriginal.width(_.max(worstLabelWidths));

        // now set table column width from this value
        this.$el.find('.number .likely').width(likelyConverted.width()+likelyOriginal.width());
        this.$el.find('.number .best').width(bestConverted.width()+bestOriginal.width());
        this.$el.find('.number .worst').width(worstConverted.width()+worstOriginal.width());
    },

    /**
     * Determines if this Worksheet belongs to the current user, applicable for determining if this view should show,
     * or whether to render the clickToEdit field
     *
     * @return {Boolean} true if it is the worksheet of the logged in user, false if not.
     */
    isMyWorksheet: function() {
        return _.isEqual(app.user.get('id'), this.selectedUser.id);
    },

    /**
     * Determines if this Worksheet should be rendered
     *
     * @return {Boolean} this.show
     */
    showMe: function(){
        var selectedUser = (this.isDirty() && this.dirtyUser) ? this.dirtyUser : this.selectedUser;

        return selectedUser.showOpps || !selectedUser.isManager;
    },

    /**
     *
     * @param selectedUser
     */
    calculateTotals: function() {
        var self = this,
            includedAmount = 0,
            includedBest = 0,
            includedWorst = 0,
            overallAmount = 0,
            overallBest = 0,
            overallWorst = 0,
            includedCount = 0,
            lostCount = 0,
            lostAmount = 0,
            wonCount = 0,
            wonAmount = 0,
            totalCount = 0;
            includedClosedCount = 0;
            includedClosedAmount = 0;

        if(!this.showMe()){
            // if we don't show this worksheet set it all to zero
            this.context.forecasts.set({
                updatedTotals : {
                    'amount' : includedAmount,
                    'best_case' : includedBest,
                    'worst_case' : includedWorst,
                    'overall_amount' : overallAmount,
                    'overall_best' : overallBest,
                    'overall_worst' : overallWorst,
                    'timeperiod_id' : self.timePeriod,
                    'lost_count' : lostCount,
                    'lost_amount' : lostAmount,
                    'won_count' : wonCount,
                    'won_amount' : wonAmount,
                    'included_opp_count' : includedCount,
                    'total_opp_count' : totalCount,
                    'closed_count' : 0,
                    'closed_amount' : 0
                }
            }, {silent:true});
            return false;
        }

        //Get the excluded_sales_stage property.  Default to empty array if not set
        var sales_stage_won_setting = this.context.forecasts.config.get('sales_stage_won') || [];
        var sales_stage_lost_setting = this.context.forecasts.config.get('sales_stage_lost') || [];

        _.each(self._collection.models, function (model) {
            var won = _.include(sales_stage_won_setting, model.get('sales_stage'))
                lost = _.include(sales_stage_lost_setting, model.get('sales_stage')),
                amount = parseFloat(model.get('likely_case')),
                commit_stage = model.get('commit_stage'),
                best = parseFloat(model.get('best_case')),
                base_rate = parseFloat(model.get('base_rate')),
                worst = parseFloat(model.get('worst_case')),
                worst_base =  app.currency.convertWithRate(worst, base_rate),
                amount_base = app.currency.convertWithRate(amount, base_rate),
                best_base = app.currency.convertWithRate(best, base_rate);

            if(won) {
                wonAmount = app.math.add(wonAmount, amount_base);
                wonCount++;
            } else if(lost) {
                lostAmount = app.math.add(lostAmount, amount_base);
                lostCount++;
            }
            
            if(commit_stage === 'include') {
                includedAmount += amount_base;
                includedBest += best_base;
                includedWorst += worst_base;
                includedCount++;
                if(won || lost) {
                    includedClosedCount++;
                    includedClosedAmount = app.math.add(amount_base, includedClosedAmount);
                }
            }

            overallAmount += amount_base;
            overallBest += best_base;
            overallWorst += worst_base;
        });


        var totals = {
            'amount' : includedAmount,
            'best_case' : includedBest,
            'worst_case' : includedWorst,
            'overall_amount' : overallAmount,
            'overall_best' : overallBest,
            'overall_worst' : overallWorst,
            'timeperiod_id' : self.timePeriod,
            'lost_count' : lostCount,
            'lost_amount' : lostAmount,
            'won_count' : wonCount,
            'won_amount' : wonAmount,
            'included_opp_count' : includedCount,
            'total_opp_count' : self._collection.models.length,
            'closed_count': includedClosedCount,
            'closed_amount': includedClosedAmount
            
        };
       
        this.context.forecasts.unset("updatedTotals", {silent: true});
        this.context.forecasts.set("updatedTotals", totals);
    },

    /**
     * Event Handler for updating the worksheet by a selected user
     *
     * @param params is always a context
     */
    updateWorksheetBySelectedUser:function (selectedUser) {
        //do a dirty check before fetching. Safe fetch uses selected user for some of its checks, so we need to check
        //things before this.selectedUser is replaced.
        if(this.isDirty()) {
            // since the model is dirty, save it so we can use it later
            this.dirtyUser = this.selectedUser;
        }
        this.safeFetch(false);        
        this.selectedUser = selectedUser;
        if(!this.showMe()){
            return false;
        }
        this._collection.url = this.createURL();
        this._collection.fetch();
    },

    /**
     * Event Handler for updating the worksheet by a selected category
     *
     * @param params array of selected filters
     */
    updateWorksheetBySelectedRanges:function (params) {
        // Set the filters for the datatable then re-render
        var self = this,
            forecast_ranges_setting = this.context.forecasts.config.get('forecast_ranges') || 'show_binary';
        
        // start with no filters, i. e. show everything.
        if(!_.isUndefined($.fn.dataTableExt)) {
            $.fn.dataTableExt.afnFiltering.splice(0, $.fn.dataTableExt.afnFiltering.length);
            if (!_.isEmpty(params)) {
                $.fn.dataTableExt.afnFiltering.push (
                    function(oSettings, aData, iDataIndex) {
                        // This is required to prevent manager view from filtering incorrectly, since datatables does filtering globally
                        if(oSettings.nTable == _.first($('.worksheetManagerTable'))) {
                            return true;
                        }

                        var editable = self.isMyWorksheet(),
                            selectVal,
                            rowCategory = $(_.first(aData)),
                            checkState;

                        //If we are in an editable worksheet get the selected dropdown/checkbox value; otherwise, get the detail/default text
                        if (forecast_ranges_setting == 'show_binary') {
                            checkState = rowCategory.find('input').attr('checked');
                            selectVal = ((checkState == "checked") || (checkState == "on") || (checkState == "1")) ? 'include' : 'exclude';
                        } else {
                            //we need to check to see if the select exists, because this gets fired before the commitStage field re-renders itself back
                            //to a text field.
                            if(rowCategory.find("select").length == 0){
                                selectVal = rowCategory.text().trim().toLowerCase();
                            } else {
                                selectVal = rowCategory.find("select")[0].value.toLowerCase();                               
                            }
                        }

                        self.context.forecasts.trigger('forecasts:worksheet:filtered');
                        return (_.contains(params, selectVal));
                    }
                );
            }
        }
        
        if(!_.isUndefined(this.gTable.fnDestroy)){
            this.gTable.fnDestroy();
            this.gTable = this.$('.worksheetTable').dataTable(self.gTableDefs);
            // fix the style on the rows that contain a checkbox
            this.$el.find('td:has(span>input[type=checkbox])').addClass('center');
            this.context.forecasts.trigger('forecasts:worksheet:filtered');
        }
    },

    /**
     * Event Handler for updating the worksheet by a timeperiod id
     *
     * @param {Object} params is always a context
     */
    updateWorksheetBySelectedTimePeriod:function (params) {
        if(this.isDirty()) {
            // since the model is dirty, save it so we can use it later
            this.dirtyTimeperiod = this.timePeriod;
        }
        this.timePeriod = params.id;
        if(!this.showMe()){
            return false;
        }
        this._collection.url = this.createURL();
        this.safeFetch(true);
    },

    /**
     * Returns an array of column headings
     *
     * @param {Object} dTable datatable param so we can grab all the column headings from it
     * @param {Boolean} onlyVisible -OPTIONAL, defaults true- if we want to return only visible column headings or not
     * @return {Array} column heading title strings in an array ["heading","heading2"...]
     */
    getColumnHeadings:function (dTable, onlyVisible) {
        // onlyVisible needs to default to true if it is not false
        if (onlyVisible !== false) {
            onlyVisible = typeof onlyVisible !== 'undefined' ? onlyVisible : true;
        }

        var cols = dTable.fnSettings().aoColumns;
        var retColumns = [];

        for (var i in cols) {

            var title = this.app.lang.get(cols[i].sTitle);

            if (onlyVisible) {
                if (cols[i].bVisible) {
                    retColumns.push(title);
                }
            } else {
                retColumns.push(title);
            }
        }

        return retColumns;
    },

    /***
     * Checks current gTable to see if a particular column name exists
     *
     * @param {String} columnName the column sName you're checking for.  NOT the Column sTitle/heading
     * @return {Boolean} true if it exists, false if not
     */
    hasColumn:function(columnName) {
        var containsColumnName = false;
        var cols = this.gTable.fnSettings().aoColumns;

        for (var i in cols) {
            if(cols[i].sName == columnName)  {
                containsColumnName = true;
                break;
            }
        }

        return containsColumnName;
    }
})
