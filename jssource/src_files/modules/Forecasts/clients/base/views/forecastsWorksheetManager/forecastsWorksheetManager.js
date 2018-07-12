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
 * @class View.Views.WorksheetView
 * @alias SUGAR.App.layout.WorksheetView
 * @extends View.View
 *
 *
 * Events Triggered
 *
 * forecasts:commitButtons:enabled
 *      on: context.forecasts
 *      by: _render()
 *      when: done rendering if enableCommit is true
 *
 * forecasts:worksheetmanager:rendered
 *      on: context.forecasts
 *      by: _render()
 *      when: done rendering
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
    url: 'rest/v10/ForecastManagerWorksheets',
    show: false,
    viewModule: {},
    selectedUser: {},
    timePeriod: '',
    gTable:'',
    // boolean for enabled expandable row behavior
    isExpandableRows:'',
    _collection:{},
    // boolean to denote that a fetch is currently in progress
    fetchInProgress :  false,


    /**
     * Template to use when updating the likelyCase on the committed bar
     */
    commitLogTemplate : _.template('<article><%= text %><br><date><%= text2 %></date></article>'),

    /**
     * Template to use when we are fetching the commit history
     */
    commitLogLoadingTemplate : _.template('<div class="extend results"><article><%= loadingMessage %></article></div>'),

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
     * A Collection to keep track of draft models
     */
    draftModels: new Backbone.Collection(),
    
    /**
     * If the timeperiod is changed and we have draftModels, keep the previous one to use if they save the models
     */
    draftTimeperiod : '',

    /**
     * If the timeperiod is changed and we have draftModels, keep the previous one to use if they save the models
     */
    draftUser : '',    

    /**
     * Handle Any Events
     */
    events:{
        'click a[rel=historyLog] i.icon-exclamation-sign':'displayHistoryLog'
    },

    /**
     * Initialize the View
     *
     * @constructor
     * @param {Object} options
     */
    initialize:function (options) {
        this.viewModule = app.viewModule;
        var self = this;

        app.view.View.prototype.initialize.call(this, options);

        //set up base selected user
    	this.selectedUser = {id: app.user.get('id'), "isManager":app.user.get('isManager'), "showOpps": false};
        this.timePeriod = app.defaultSelections.timeperiod_id.id
        this.ranges = app.defaultSelections.ranges.id

        this._collection = this.context.forecasts.worksheetmanager;
        this._collection.url = this.createURL();

        this.totalModel = new (Backbone.Model.extend(
            {
                amount : 0,
                quota : 0,
                best_case : 0,
                best_adjusted : 0,
                likely_case : 0,
                likely_adjusted : 0,
                worst_case : 0,
                worst_adjusted : 0
            }
        ));
    },

    /**
     * Event Handler for updating the worksheet by a selected user
     *
     * @param params is always a context
     */
    updateWorksheetBySelectedUser:function (selectedUser) {
        if(this.isDirty()) {
            // since the model is dirty, save it so we can use it later
            this.dirtyUser = this.selectedUser;
            this.draftUser = this.selectedUser;
        }
        this.selectedUser = selectedUser;
        if(!this.showMe()){
        	return false;
        }
        this.context.forecasts.worksheetmanager.url = this.createURL();
        this.safeFetch(true);
    },

    /**
     * Clean up any left over bound data to our context
     */
    unbindData : function() {
        if(this._collection) this._collection.off(null, null, this);
        if(this.context.forecasts) this.context.forecasts.off(null, null, this);
        if(this.context.forecasts.worksheetmanager) this.context.forecasts.worksheetmanager.off(null, null, this);
        //if we don't unbind this, then recycle of this view if a change in rendering occurs will result in multiple bound events to possibly out of date functions
        $(window).unbind("beforeunload");
        app.view.View.prototype.unbindData.call(this);
    },

    bindDataChange: function() {
        if (this._collection) {
            this._collection.on("reset", function() {
                self.cleanUpDirtyModels();
                self.cleanUpDraftModels();
                self.render();
            }, this);

            this._collection.on("change", function(model, changed) {
                // The Model has changed via CTE. save it in the isDirty
                this.dirtyModels.add(model);
                this.context.forecasts.trigger('forecasts:worksheet:dirty', model, changed);
            }, this);
        }
        // listening for updates to context for selectedUser:change
        if (this.context.forecasts) {
            var self = this;

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
            this.context.forecasts.worksheetmanager.on("change", function() {
            	this.calculateTotals();
            }, this);
            this.context.forecasts.on("forecasts:committed:saved forecasts:worksheet:saved", function(){
            	if(this.showMe()){
            		this.context.forecasts.worksheetmanager.url = this.createURL();
            		this.safeFetch();
            	}
            }, this);

            this.context.forecasts.on('forecasts:worksheet:saveWorksheet', function(isDraft) {
                this.saveWorksheet(isDraft);
            }, this);
            
            /*
             * // TODO: tagged for 6.8 see SFA-253 for details
            this.context.forecasts.config.on('change:show_worksheet_likely', function(context, value) {
                // only trigger if this component is rendered
                if(!_.isEmpty(self.el.innerHTML)) {
                    self.setColumnVisibility(['likely_case', 'likely_adjusted'], value, self);
                }
            });

            this.context.forecasts.config.on('change:show_worksheet_best', function(context, value) {
                // only trigger if this component is rendered
                if(!_.isEmpty(self.el.innerHTML)) {
                    self.setColumnVisibility(['best_case', 'best_adjusted'], value, self);
                }
            });

            this.context.forecasts.config.on('change:show_worksheet_worst', function(context, value) {
                // only trigger if this component is rendered
                if(!_.isEmpty(self.el.innerHTML)) {
                    self.setColumnVisibility(['worst_case', 'worst_adjusted'], value, self);
                }
            });
            */
            
            var worksheet = this;
            $(window).bind("beforeunload",function(){
                if(worksheet.isDirty()){
                	return app.lang.get("LBL_WORKSHEET_SAVE_CONFIRM_UNLOAD", "Forecasts");
                }            	
            });
        }
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
        var self = this,
            saveObj = {totalToSave: 0, 
                       saveCount: 0, 
                       model: "", 
                       isDraft: isDraft, 
                       timeperiod:self.dirtyTimeperiod, 
                       userId:self.dirtyUser.id};
        
        if(this.showMe()) {
            /**
             * If the sheet is dirty, save the dirty rows. Else, if the save is for a commit, and we have 
             * draft models (things saved as draft), we need to resave those as committed (version 1). If neither
             * of these conditions are true, then we need to fall through and signal that the save is complete so other
             * actions listening for this can continue.
             */
            if(this.isDirty()) {
                saveObj.totalToSave = self.dirtyModels.length;
                
                self.dirtyModels.each(function(model){
                    saveObj.model = model;
                    self._worksheetSaveHelper(saveObj);
                                       
                    //add to draft structure so committing knows what to save as non-draft
                    if(isDraft == true){
                        self.draftModels.add(model, {merge: true});
                    }
                });

                self.cleanUpDirtyModels();
            } else if(!isDraft && self.draftModels.length > 0){
                saveObj.totalToSave = self.draftModels.length;
                
                self.draftModels.each(function(model){
                    saveObj.model = model;
                    self._worksheetSaveHelper(saveObj);
                });
                
                //Need to clean up dirty models too as the save event above triggers a change event on the worksheet.
                self.cleanUpDirtyModels();
                self.cleanUpDraftModels();
            } else {
                this.context.forecasts.trigger('forecasts:worksheet:saved', saveObj.totalToSave, 'mgr_worksheet', isDraft);
            }
        }

        return saveObj.totalToSave
    },
    
    /**
     * Helper function for worksheet save
     */
    _worksheetSaveHelper: function(saveObj){
        var self = this;
        saveObj.model.set({
            draft : (saveObj.isDraft == true) ? 1 : 0,
            timeperiod_id : saveObj.timeperiod || self.timePeriod,
            current_user : saveObj.userId|| self.selectedUser.id
        }, {silent:true});   
        saveObj.model.url = self.url.split("?")[0] + "/" + saveObj.model.get("id");
        
        saveObj.model.save({}, {success: function() {
            saveObj.saveCount++;
            //if this is the last save, go ahead and trigger the callback;
            if(saveObj.totalToSave === saveObj.saveCount) {
                self.context.forecasts.trigger('forecasts:worksheet:saved', saveObj.totalToSave, 'mgr_worksheet', saveObj.isDraft);
            }
        }, silent: true});
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
     * Clean Up the Draft Modules Collection and dirtyVariables
     */
    cleanUpDraftModels : function() {
        // clean up the draft records and variables
        this.draftModels.reset();
        this.draftTimeperiod = '';
        this.draftUser = '';
    },


    /**
     * Sets the visibility of a column or columns if array is passed in
     *
     * @param cols {Array} the sName of the columns to change
     * @param value {*} int or Boolean, 1/true or 0/false to show the column
     * @param ctx {Object} the context of this view to have access to the checkForColumnsSetVisibility function
     */
    setColumnVisibility: function(cols, value, ctx) {
        var aoColumns = this.gTable.fnSettings().aoColumns;

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
     * @param fetch {boolean} Tells the function to go ahead and fetch if true, or runs dirty checks (saving) w/o fetching if false 
     */
    safeFetch: function(fetch){
        //fetch currently already in progress, no need to duplicate
        if(this.fetchInProgress) {
            return;
        }
        //mark that a fetch is in process so no duplicate fetches begin
        this.fetchInProgress = true;
        if(_.isUndefined(fetch)) {
            fetch = true;
        }
    	var collection = this._collection;
    	var self = this;
    	if(this.isDirty()){
    		//unsaved changes, ask if you want to save.
    		if(confirm(app.lang.get("LBL_WORKSHEET_SAVE_CONFIRM", "Forecasts"))){
                self.context.forecasts.set({reloadCommitButton: true});
                var svWkFn = function() {
                    self.context.forecasts.off('forecasts:worksheet:saved', svWkFn);
                    collection.fetch();
                };

                self.context.forecasts.on('forecasts:worksheet:saved', svWkFn);
                this.saveWorksheet()
		    } else {
    			//ignore, fetch still
    			self.context.forecasts.set({reloadCommitButton: true});
    			if(fetch){
    				collection.fetch();
    			}
    			
    		}
    	}
    	else{
    		//no changes, fetch like normal.
    		if(fetch){
    			collection.fetch();
    		}
    		
    	}
        this.fetchInProgress = false;
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
        app.view.View.prototype._renderField.call(this, field);
        if (field.viewName !="edit" && field.def.clickToEdit === true && _.isEqual(this.selectedUser.id, app.user.get('id'))) {
            field = new app.view.ClickToEditField(field, this);
        }
    },

    /**
     * Renders view
     */
    _render:function () {
        var self = this;
      
        if(!this.showMe()){
        	return false;
        }
        $("#view-sales-rep").addClass('hide').removeClass('show');
        $("#view-manager").addClass('show').removeClass('hide');
        this.context.forecasts.set({currentWorksheet: "worksheetmanager"});
        
        app.view.View.prototype._render.call(this);

        // parse metadata into columnDefs
        // so you can sort on the column's "name" prop from metadata
        var columnDefs = [];
        var fields = this.meta.panels[0].fields;

        for( var i = 0; i < fields.length; i++ )  {
            if(fields[i].enabled) {
                // in case we add column rearranging
                var fieldDef = {
                    "sName": fields[i].name,
                    "bVisible" : this.checkConfigForColumnVisibility(fields[i].name)
                };

                //Apply sorting for the worksheet
                if(!_.isUndefined(fields[i].type))
                {
                    switch(fields[i].type)
                    {
                        case "int":
                        case "currency":
                        case "editableCurrency":
                            fieldDef["sSortDataType"] = "dom-number";
                            fieldDef["sType"] = "numeric";
                            fieldDef["sClass"] = "number";
                            break;
                    }
                    switch(fields[i].name)
                    {
                        case "name":
                            fieldDef["sWidth"] = "30%";
                            break;
                    }
                }

                columnDefs.push(fieldDef);
            }
        }

        this.gTable = this.$el.find(".worksheetManagerTable").dataTable(
            {
                "bAutoWidth": false,
                "aaSorting": [],
                "aoColumns": columnDefs,
                "bInfo":false,
                "bPaginate":false
            }
        );

        if (this.getDraftModels().length > 0) {
            self.context.forecasts.trigger("forecasts:commitButtons:enabled");
        }

        this.calculateTotals();
        self.context.forecasts.trigger('forecasts:worksheetmanager:rendered');

    },
    
    getDraftModels: function(){
      //see if anything in the model is a draft version
        return this._collection.filter(function(model) {
            if (model.get("version") == "0") {
                this.draftModels.add(model, {merge: true});
                return true;
            }            
            return false;            
        }, this);
    },

    /**
     * Handle the click event from a history log icon click.
     *
     * @param event
     */
    displayHistoryLog:function (event) {
        var self = this;
        var nTr = _.first($(event.target).parents('tr'));
        // test to see if it's open
        if (self.gTable.fnIsOpen(nTr)) {
            // if it's open, close it
            self.gTable.fnClose(nTr);
        } else {
            //Open this row

            var colspan = $(nTr).children('td').length;

            self.gTable.fnOpen(nTr, this.commitLogLoadingTemplate({'loadingMessage' : App.lang.get("LBL_LOADING_COMMIT_HISTORY", 'Forecasts')}) , 'details');
            $(nTr).next().children("td").attr("colspan", colspan);

            self.fetchUserCommitHistory(event, nTr);
        }
    },

    /**
     * Event handler when popoverIcon is clicked,
     * @param event
     * @return {*}
     */
    fetchUserCommitHistory: function(event, nTr) {
        var jTarget = $(event.target),
            dataCommitDate = jTarget.data('commitdate'),
            options = {
                timeperiod_id : this.timePeriod,
                user_id : jTarget.data('uid'),
                forecast_type : (jTarget.data('showopps')) ? 'Direct' : 'Rollup'
            };

        return app.api.call('read',
             app.api.buildURL('Forecasts', 'committed', null, options),
            null,
            {
                success : function(data) {
                    var commitDate = new Date(dataCommitDate),
                        newestModel = new Backbone.Model(_.first(data)),
                        // get everything that is left but the first item.
                        otherModels = _.last(data, data.length-1),
                        oldestModel = {};

                    // using for because you can't break out of _.each
                    for(var i = 0; i < otherModels.length; i++) {
                        // check for the first model equal to or past the forecast commit date
                        // we want the last commit just before the whole forecast was committed
                        if(new Date(otherModels[i].date_modified) <= commitDate) {
                            oldestModel = new Backbone.Model(otherModels[i]);
                            break;
                        }
                    }

                    // create the history log
                    outputLog = app.forecasts.utils.createHistoryLog(oldestModel,newestModel,this.context.forecasts.config);
                    // update the div that was created earlier and set the html to what was the commit log
                    $(nTr).next().children("td").children("div").html(this.commitLogTemplate(outputLog));
                }
            },
            { context : this }
        );
    },


    calculateTotals:function () {
        var self = this,
            amount = 0,
            quota = 0,
            best_case = 0,
            best_adjusted = 0,
            likely_case = 0,
            likely_adjusted = 0,
            worst_adjusted = 0,
            worst_case = 0,
            included_opp_count = 0,
            pipeline_opp_count = 0,
            pipeline_amount = 0,
            closed_amount = 0;
      
        if(!this.showMe()){
            // if we don't show this worksheet set it all to zero
            this.context.forecasts.set({
                updatedManagerTotals : {
                    'amount' : amount,
                    'quota' : quota,
                    'best_case' : best_case,
                    'best_adjusted' : best_adjusted,
                    'likely_case' : likely_case,
                    'likely_adjusted' : likely_adjusted,
                    'worst_adjusted' : worst_adjusted,
                    'worst_case' : worst_case,
                    'included_opp_count' : included_opp_count
                }
            }, {silent:true});
            return false;
        }

        self._collection.forEach(function (model) {
            var base_rate = parseFloat(model.get('base_rate')),
                mPipeline_opp_count = model.get("pipeline_opp_count"),
                mPipeline_amount = model.get("pipeline_amount"),
                mClosed_amount = model.get("closed_amount"),
                mOpp_count = model.get("opp_count");
            
            amount 			+= app.currency.convertWithRate(model.get('amount'), base_rate);
            quota 			+= app.currency.convertWithRate(model.get('quota'), base_rate);
            best_case 		+= app.currency.convertWithRate(model.get('best_case'), base_rate);
            best_adjusted 	+= app.currency.convertWithRate(model.get('best_adjusted'), base_rate);
            likely_case 		+= app.currency.convertWithRate(model.get('likely_case'), base_rate);
            likely_adjusted 	+= app.currency.convertWithRate(model.get('likely_adjusted'), base_rate);
            worst_case       += app.currency.convertWithRate(model.get('worst_case'), base_rate);
            worst_adjusted 	+= app.currency.convertWithRate(model.get('worst_adjusted'), base_rate);
            included_opp_count += (_.isUndefined(mOpp_count))? 0 : parseInt(mOpp_count);
            pipeline_opp_count += (_.isUndefined(mPipeline_opp_count))? 0 : parseInt(mPipeline_opp_count);
            if (!_.isUndefined(mPipeline_amount)) {
                pipeline_amount = app.math.add(pipeline_amount, mPipeline_amount);
            }
            if (!_.isUndefined(mClosed_amount)) {
                closed_amount = app.math.add(closed_amount, mClosed_amount);
            }
        });

        self.totalModel.set({
            amount : amount,
            quota : quota,
            best_case : best_case,
            best_adjusted : best_adjusted,
            likely_case : likely_case,
            likely_adjusted : likely_adjusted,
            worst_case : worst_case,
            worst_adjusted : worst_adjusted,
            included_opp_count : included_opp_count,
            pipeline_opp_count : pipeline_opp_count,
            pipeline_amount : pipeline_amount,
            closed_amount: closed_amount
        });
        
        //in case this is needed later..
        var totals = {
            'amount' : amount,
            'quota' : quota,
            'best_case' : best_case,
            'best_adjusted' : best_adjusted,
            'likely_case' : likely_case,
            'likely_adjusted' : likely_adjusted,
            'worst_case' : worst_case,
            'worst_adjusted' : worst_adjusted,
            'included_opp_count' : included_opp_count,
            'pipeline_opp_count' : pipeline_opp_count,
            'pipeline_amount' : pipeline_amount,
            'closed_amount' : closed_amount
        };
        
        // we need to remove it, just in case it's the same to force it to re-render
        this.context.forecasts.unset("updatedManagerTotals", {silent: true});
        this.context.forecasts.set("updatedManagerTotals", totals);
    },

    /**
     * Determines if this Worksheet should be rendered
     */
    showMe: function(){
    	var selectedUser = (this.isDirty() && this.dirtyUser) ? this.dirtyUser : this.selectedUser;

        return (!selectedUser.showOpps && selectedUser.isManager)
    },

    /**
     * Event Handler for updating the worksheet by a selected ranges
     *
     * @param params is always a context
     */
    updateWorksheetBySelectedRanges:function (params) {
        if (this.context.forecasts.config.get('forecast_ranges') != 'show_binary') {
            // TODO: this.
        } else {
            this.ranges = _.first(params);
        }

        var model = this.context.forecasts.worksheetmanager;
        if(!this.showMe()){
            return false;
        }
        model.url = this.createURL();
        this.safeFetch(true);
    },

    /**
     * Event Handler for updating the worksheet by a timeperiod id
     *
     * @param params is always a context
     */
    updateWorksheetBySelectedTimePeriod:function (params) {
        if(this.isDirty()) {
            // since the model is dirty, save it so we can use it later
            this.dirtyTimeperiod = this.timePeriod;
            this.draftTimeperiod = this.timePeriod;
        }
    	this.timePeriod = params.id;
        var model = this.context.forecasts.worksheetmanager;
        if(!this.showMe()){
        	return false;
        }
        model.url = this.createURL();
        this.safeFetch(true);
    },

    createURL:function() {
        var url = this.url;
        var args = {};
        if(this.timePeriod) {
           args['timeperiod_id'] = this.timePeriod;
        }

        if(this.ranges) {
            args['ranges'] = this.ranges;
        }

        if(this.selectedUser)
        {
           args['user_id'] = this.selectedUser.id;
        }

        url = app.api.buildURL('ForecastManagerWorksheets', '', '', args);
        return url;
    },

    /**
     * Returns an array of column headings
     *
     * @param dTable datatable param so we can grab all the column headings from it
     * @param onlyVisible -OPTIONAL, defaults true- if we want to return only visible column headings or not
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
     * @param columnName the column sName you're checking for.  NOT the Column sTitle/heading
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
