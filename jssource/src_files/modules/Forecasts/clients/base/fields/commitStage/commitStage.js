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
     * Attach a Change event to the field
     */
    events: { 'change' : 'bucketsChanged' },   
    
    /**
     * flag for if the field should render disabled
     */
    disabled: false,
    
    /**
     * Language string value of the data in the view.
     */
    langValue: "",
    
    /**
     * Current view type this field is rendered.
     * 
     * This is needed because this.def.view is shared across all instances of the view.
     */
    currentView: "",
    
    /**
     * Initialize
     */
    initialize: function(options){
        app.view.Field.prototype.initialize.call(this, options);
        var self = this,
            forecastRanges = self.context.forecasts.config.get("forecast_ranges");
          
        //Check to see if the field is editable
        self.isEditable();
        
        //show_binary, show_buckets, show_n_buckets logic
        if(forecastRanges == "show_binary"){
            //If we're in binary mode
            self.def.view = "bool";
            self.currentView = "bool";
            self.format = function(value){
                return value == "include";
            };
            self.unformat = function(value){
                return self.$el.find(".checkbox").prop('checked') ? "include" : "exclude";
            };
        }
        else if(forecastRanges == "show_buckets"){
            self.def.view = "default";
            self.currentView = "default";
            self.getLanguageValue();
            self.createCTEIconHTML();
            //create buckets, but only if we are on our sheet.
            if(!self.disabled){                
                self.createBuckets();                
            }            
        }
    },
    
    /**
     * Render Field
     */
    _render:function () {
        var self = this;
        app.view.Field.prototype._render.call(this);
               
        /* If we are on our own sheet, and need to show the dropdown, init things
         * and disable events
         */
        if(!self.disabled && self.currentView == "enum"){
            self.$el.off("click");            
                        
            //custom namespaced window click event to destroy the chosen dropdown on "blur".
            //this is removed in this.resetBuckets
            $(window).on("click." + self.model.get("id"), function(e){
                if(!_.isEqual(self.model.get("id"), $(e.target).attr("itemid"))){
                    self.resetBucket();
                }
            });
            
           //custom click handler for the dropdown to set things up for the global click to not fire
            self.$el.on("click", function(e){
                $(e.target).attr("itemid", self.model.get("id"));
            });
                        
            self.$el.off("mouseenter");
            self.$el.off("mouseleave");            
            self.$el.find("option[value=" + self.value + "]").attr("selected", "selected");
            self.$el.find("select").chosen({
                disable_search_threshold: self.def.searchBarThreshold?self.def.searchBarThreshold:0
            });
        }
    },
    
    /**
     * Change handler for the buckets field
     */
    bucketsChanged: function(){
        var self = this,
            values = {},
            moduleName = self.moduleName;
        
        if(self.currentView == "bool"){
            self.value = self.unformat();
            values[self.def.name] = self.value;
        }
        else if(self.currentView == "enum"){
            self.value = self.$el.find("select")[0].value;
            values[self.def.name] = self.value;
        }
        
        self.model.set(values);
        
        if(self.currentView == "enum"){
            self.resetBucket();
        }
    },
    
    /**
     * Creates the HTML for the bucket selectors
     * 
     * This function is used to create the select tag for the buckets.  For performance reasons, we only want
     * to iterate over the option list once, so we do that here and store it as a jQuery data element on the Body tag.
     * Also, we check to make sure this hasn't already been done (so we don't do it again, of course).
     */
    createBuckets: function(){
        var self = this;
        self.buckets = $.data(document.body, "commitStageBuckets");
        
        if(_.isUndefined(self.buckets)){
            var options = app.lang.getAppListStrings(this.def.options) || 'commit_stage_dom';
            self.buckets =  "<select data-placeholder=' ' name='" + self.name + "' style='width: 100px;'>";
            self.buckets +=     "<option value='' selected></option>";
            _.each(options, function(item, key){
                self.buckets += "<option value='" + key + "'>" + item + "</options>"
            });
            self.buckets += "</select>";
            $.data(document.body, "commitStageBuckets", self.buckets);
        }
    },
    
    /**
     * Sets up CTE Icon HTML
     * 
     * If the HTML hasn't been set up yet, create it and store it on the DOM.  If it has, simply use it
     */
    createCTEIconHTML: function(){
        var self = this,
            cteIcon = $.data(document.body, "cteIcon"),
            events = self.events || {},
            sales_stage = self.model.get("sales_stage");
        
        if(_.isUndefined(cteIcon)){
            cteIcon = '<span class="edit-icon"><i class="icon-pencil icon-sm"></i></span>';
            $.data(document.body, "cteIcon", cteIcon);
        }             
        
        //Events
        /* if it's not a bucket, and it's not editable, we don't want to try to add the pencil
         */
        self.showCteIcon = function() {
            if((self.currentView != "enum") && (!self.disabled)){
                self.$el.find("span").before($(cteIcon));
            }
        };
        
        /* if it's not a bucket, and it's not editable, we don't want to try to remove the pencil         
         */
        self.hideCteIcon = function() {
            if((self.currentView != "enum") && (!self.disabled)){
                self.$el.parent().find(".edit-icon").detach();
            }
        };
        
        self.events = _.extend(events, {
            'mouseenter': 'showCteIcon',
            'mouseleave': 'hideCteIcon',
            'click'     : 'clickToEdit'
        });            
    },
    
    /**
     * Gets proper value of the item out of the language file.
     * 
     * If we are in buckets mode and are on a non-editable sheet, we need to display the proper value of this
     * field as determined by the language file.  This function sets the proper key in the field for the hbt to pick it up and
     * display it.
     */
    getLanguageValue: function(){
        var options = app.lang.getAppListStrings(this.def.options) || 'commit_stage_dom';
        this.langValue = options[this.model.get(this.def.name)]; 
    },
    
    /**
     * Click to edit handler
     * 
     * Handles the click to make the field editable.
     */
    clickToEdit: function(e){
        var self = this,
            sales_stage = self.model.get("sales_stage");
        if(!self.disabled){
            $(e.target).attr("itemid", self.model.get("id"));
            self.def.view = "enum";
            self.currentView = "enum";
            self.render();
        }
    },
    
    /**
     * Removes chosen dropdown from unfocused field
     */
    resetBucket: function(){
        var self = this;
        
        //remove custom click handler
        $(window).off("click." + self.model.get("id"));
        self.$el.off("click");
        self.def.view = "default";
        self.currentView = "default";
        self.getLanguageValue();
        self.delegateEvents();
        self.render();        
    },
    
    /**
     * Utility Method to check if the field is editable
     */
    isEditable: function() {
        var self = this,
            sales_stages,
            hasStage = false
            isOwner = true;
        
        if(!_.isUndefined(self.context.forecasts)){
            //Check to see if the sales stage is one of the configured lost or won stages.
            if (!_.isUndefined(self.context.forecasts.config)) {    
                sales_stages = self.context.forecasts.config.get("sales_stage_won").concat(self.context.forecasts.config.get("sales_stage_lost"));
                hasStage = _.contains(sales_stages, self.model.get('sales_stage'));
            }
            
            //Check to see if you're a manager on someone else's sheet, disable changes
            if(self.context.forecasts.get("selectedUser")["id"] != app.user.id){
                isOwner = false;
            }
        }
        
        self.disabled = hasStage || !isOwner; 
    },
})
