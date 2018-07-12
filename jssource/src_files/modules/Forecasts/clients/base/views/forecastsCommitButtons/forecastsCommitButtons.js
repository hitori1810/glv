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
 * forecasts:commitButtons:disabled
 *      on: context.forecasts
 *      by: change:selectedUser, change:selectedTimePeriod
 * 
 * forecasts:worksheet:saveWorksheet
 *      on:context.forecasts
 *      by: triggerCommit(), triggerSaveDraft()
 *
 * modal:forecastsTabbedConfig:open - to cause modal.js to pop up
 *      on: layout
 *      by: triggerConfigModal()
 *      
 * forecasts:committed:commit
 *      on: context.forecasts
 *      by: triggerCommit()
 */
({

    /**
     * Used to determine whether or not to visibly show the Commit button
     */
    showCommitButton : true,

    /**
     * Used to determine whether or not the Commit button is enabled
     */
    commitButtonEnabled: false,

    /**
     * Used to determine whether the config setting cog button is displayed
     */
    showConfigButton: false,
    
    /**
     * Used to know which version to save, draft or live
     */
    draft: 0,
            
    /**
     * Adds event listener to elements
     */
    events: {
        "click a[id=commit_forecast]" : "triggerCommit",
        "click a[id=save_draft]" : "triggerSaveDraft",
        "click a.drawerTrig" : "triggerRightColumnVisibility",
        "click a[id=export]" : "triggerExport",
        "click a[id=print]" : "triggerPrint"
    },

    initialize: function (options) {
        app.view.View.prototype.initialize.call(this, options);
        this.showConfigButton = (app.user.getAcls()['Forecasts'].admin == "yes");
    },

    /**
     * Clean up any left over bound data to our context
     */
    unbindData : function() {
        if(this.context.forecasts.worksheet) this.context.forecasts.worksheet.off(null, null, this);
        if(this.context.forecasts.worksheetmanager) this.context.forecasts.worksheetmanager.off(null, null, this);
        if(this.context.forecasts) this.context.forecasts.off(null, null, this);
        app.view.View.prototype.unbindData.call(this);
    },

    /**
     * Fires during initialization and if any data changes on this model
     */
    bindDataChange: function() {
        var self = this;
        if(this.context && this.context.forecasts) {
            this.context.forecasts.on("change:selectedUser", function(context, user) {
                var oldShowButtons = self.showCommitButton;
                self.showCommitButton = self.checkShowCommitButton(user.id);
                // if show buttons has changed, need to re-render
                if(self.showCommitButton != oldShowButtons) {
                    self._render();
                }
            });           
            this.context.forecasts.on("change:reloadCommitButton", function(){
            	self._render();
            }, self);
            //this.context.forecasts.worksheet.on("change", this.showSaveButton, self);
            this.context.forecasts.on('forecasts:worksheet:dirty', function(model, changed){
                this.$el.find('#save_draft').removeClass("disabled");
		        this.context.forecasts.trigger("forecasts:commitButtons:enabled");
            }, self);
            this.context.forecasts.on("forecasts:commitButtons:triggerCommit", this.triggerCommit, self);
            this.context.forecasts.on("forecasts:commitButtons:triggerSaveDraft", this.triggerSaveDraft, self);
            this.context.forecasts.on("change:selectedUser", function(){
            	this.context.forecasts.trigger("forecasts:commitButtons:disabled");
            }, this);
            this.context.forecasts.on("change:selectedTimePeriod", function(){
            	this.context.forecasts.trigger("forecasts:commitButtons:disabled");
            }, this);
            this.context.forecasts.on("forecasts:commitButtons:enabled", this.enableCommitButton, this);
            this.context.forecasts.on("forecasts:commitButtons:disabled", this.disableCommitButton, this);
        }
    },

    /**
     * Renders the component
     */
    _renderHtml : function(ctx, options) {
        app.view.View.prototype._renderHtml.call(this, ctx, options);
        if(this.showCommitButton) {
            if(this.commitButtonEnabled) {
                this.$el.find('a[id=commit_forecast]').removeClass('disabled');
            } else {
                this.$el.find('a[id=commit_forecast]').addClass('disabled');               
            }
        }        
    },

    /**
     * Event handler to disable/reset the commit/save button
     */
    disableCommitButton: function(){
    	var commitbtn =  this.$el.find('#commit_forecast');
    	var savebtn = this.$el.find('#save_draft');
    	commitbtn.addClass("disabled");
    	savebtn.addClass("disabled");
    	
    	this.commitButtonEnabled = true;
    },
    
    /**
     * Event handler to disable/reset the commit button
     */
    enableCommitButton: function(){
    	var commitbtn =  this.$el.find('#commit_forecast');
    	commitbtn.removeClass("disabled");
    	this.commitButtonEnabled = false;
    },

    /**
     * Sets the flag on the context so forecastsCommitted.js will call commitForecast
     * as long as commit button is not disabled
     */
    triggerCommit: function() {
    	var commitbtn =  this.$el.find('#commit_forecast'),
    	    savebtn = this.$el.find('#save_draft');
    	
        if(!commitbtn.hasClass("disabled")){
            var self = this;
            this.disableCommitButton();
            
            wkstCallBack = function(totalSaved, worksheet){
                // turn off the event
                self.context.forecasts.off('forecasts:worksheet:saved', wkstCallBack);
                // now actually commit the forecast
                self.context.forecasts.trigger('forecasts:committed:commit');
            };

            self.context.forecasts.on('forecasts:worksheet:saved', wkstCallBack);
            this.context.forecasts.trigger("forecasts:worksheet:saveWorksheet", false);            
    	}        
    },

    /**
     * Handles Save Draft button being clicked
     */
    triggerSaveDraft: function() {
    	var savebtn = this.$el.find('#save_draft');
    	
    	if(!savebtn.hasClass("disabled")){
            this.context.forecasts.trigger("forecasts:worksheet:saveWorksheet", true);
    	    savebtn.addClass("disabled");
    		this.enableCommitButton();
    	}
    },

    /**
     * returns boolean value indicating whether or not to show the commit button
     */
    checkShowCommitButton: function(id) {
        return app.user.get('id') == id;
    },

    /**
     * Toggle the right Column Visibility
     * @param evt
     */
    triggerRightColumnVisibility : function(evt) {
        // we need to use currentTarget so we always get the a and not any child that was clicked on
        var el = $(evt.currentTarget);
        el.find('i').toggleClass('icon-chevron-right icon-chevron-left');

        // we need to go up and find the parent containg the two rows
        el.parents('#contentflex').find('>div.row-fluid').find('>div:first').toggleClass('span8 span12');
        el.parents('#contentflex').find('>div.row-fluid').find('>div:last').toggleClass('span4 hide');

        // toggle the "event" to make the chart stop rendering if the sidebar is hidden
        this.context.forecasts.set({hiddenSidebar: el.find('i').hasClass('icon-chevron-left')});
    },

    /**
     * Trigger the export to send csv data
     * @param evt
     */
    triggerExport : function(evt) {
        var savebtn = this.$el.find('#save_draft');
        var url = 'index.php?module=Forecasts&action=';
        url += (this.context.forecasts.get("currentWorksheet") == 'worksheetmanager') ?  'ExportManagerWorksheet' : 'ExportWorksheet';
        url += '&user_id=' + this.context.forecasts.get('selectedUser').id;
        url += '&timeperiod_id=' + $("#timeperiod").val();
        
        if(savebtn.length > 0 && !savebtn.hasClass("disabled")){
            if(confirm(app.lang.get("LBL_WORKSHEET_EXPORT_CONFIRM", "Forecasts"))){
                this.runExport(url);
            }
        }
        else{
            this.runExport(url);
        }
    },
    
    /**
     * runExport
     * triggers the browser to download the exported file
     * @param url URL to the file to download
     */
    runExport: function(url){
        var dlFrame = $("#forecastsDlFrame");
        //check to see if we got something back
        if(dlFrame.length == 0)
        {
            //if not, create an element
            dlFrame = $("<iframe>");
            dlFrame.attr("id", "forecastsDlFrame");
            dlFrame.css("display", "none");
            $("body").append(dlFrame);
        }
        dlFrame.attr("src", url);
    },

    /**
     * Trigger print by calling window.print()
     *
     * @param evt
     */
    triggerPrint : function(evt) {
        window.print();
    }

})