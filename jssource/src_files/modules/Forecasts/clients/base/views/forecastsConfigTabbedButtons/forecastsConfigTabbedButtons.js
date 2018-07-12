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
 * modal:close
 *      on: layout.context
 *      by: close()
 *      when: the user closes the modal
 *
 * modal:close
 *      on: layout.context
 *      by: save()
 *      when: the new config model has saved successfully
 */
({
    /**
     * The Current Active Panel Index
     */
    activePanel:0,

    /**
     * This is a 0 base number, so 0 equals 1 panel
     */
    totalPanels:null,

    /**
     * All the admin panels
     */
    panels:[],

    navTabs:[],

    breadCrumbLabels: [],

    events:{
        'click [name=close_button]':'close',
        'click [name=save_button]':'save',
        'click ul.nav li a':'breadcrumb'
    },

    initialize: function(options) {
        app.view.View.prototype.initialize.call(this, options);

        this.breadCrumbLabels = this.layout.getBreadCrumbLabels();
    },

    _renderHtml : function(ctx, options) {
        app.view.View.prototype._renderHtml.call(this, ctx, options);

        this.$el.parents('div.modal-body').addClass('with-tab-nav');
    },

    bindDataChange: function() {
        var self = this;
        this.model.on('change', function(){
            self.$el.find('[name=save_button]').removeClass('disabled');
        })
    },

    /**
     * Handle the close button click, don't save anything
     *
     * //TODO: maybe add a dialog if something changed and you are closing the model
     * @param evt
     */
    close:function (evt) {
        this.layout.context.trigger("modal:close");
    },

    /**
     * Handle the Save button click.
     * @param evt
     */
    save:function (evt) {
        // If button is disabled, do nothing
        if(!$(evt.target).hasClass('disabled')) {
            var self = this;

            // push this model back to the main config model
            this.context.forecasts.config.set(this.model.toJSON());

            // set the saveClicked flag without dispatching change events separate from
            // the set event below, this is silent
            this.context.forecasts.set({ saveClicked : true }, {silent:true});

            this.context.forecasts.config.save({}, {
                success: function() {
                    // only trigger modal close after save api call has returned
                    self.layout.context.trigger("modal:close");
                }
            });
        }
    },

    breadcrumb:function (evt) {
        evt.preventDefault();
        // we need to know how many panels there are
        if (!_.isNumber(this.totalPanels)) {
            this.panels = this.$el.parent().find('div.modal-content');
            this.totalPanels = this.panels.length - 1;

            this.navTabs = this.$el.parent().find('div.modal-navigation li');
        }
        // ignore the click if the crumb is already active
        if ($(evt.target).parent().is(".active,.disabled") == false) {
            // get the index of the clicked crumb
            var clickedCrumb = $(evt.target).data('index');

            if (clickedCrumb != this.activePanel) {
                this.switchPanel(clickedCrumb);
                this.switchNavigationTab(clickedCrumb);

                this.activePanel = clickedCrumb;
            }
        }
    },

    /**
     * Implement the wizard functionality for the previous and next buttons
     * @param way   Which way to move the wizard.
     */
    handleDirectionSwitch:function (way) {
        // we need to know how many panels there are
        if (!_.isNumber(this.totalPanels)) {
            this.panels = this.$el.parent().find('div.modal-content').not('.modal-wizard-start');
            this.totalPanels = this.panels.length - 1;

            this.navTabs = this.$el.parent().find('div.modal-navigation li');
        }

        var nextPanel = -1;

        // find the next panel
        if (way == "next") {
            nextPanel = this.activePanel + 1;
        } else {
            nextPanel = this.activePanel - 1;
        }

        // make sure that the next panel is not under 0 or over the total amount of panels
        if (nextPanel < 0) {
            nextPanel = 0;
        } else if (nextPanel > this.totalPanels) {
            // make sure we never go over the max panels
            nextPanel = this.totalPanels;
        }

        this.switchPanel(nextPanel);
        this.switchNavigationTab(nextPanel);

        this.activePanel = nextPanel;
    },

    /**
     * handle the switching of the panels
     * @param nextPanel
     */
    switchPanel:function (nextPanel) {
        // hide the current active panel
        $(this.panels[this.activePanel]).toggleClass('show hide');
        // show the new panel
        $(this.panels[nextPanel]).toggleClass('show hide');
    },

    /**
     * handle the switching of the navigation crumbs
     * @param next
     */
    switchNavigationTab:function (next) {
        $(this.navTabs[this.activePanel]).toggleClass('active');
        $(this.navTabs[next]).toggleClass('active');
    }
})
