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
     * Do we show this field alert
     */
    showFieldAlert: false,

    /**
     * The User Id
     */
    uid: '',

    /**
     * Show Opportunities - this param gets set on the model as a way to flag the data:
     * TRUE - rep worksheet link or manager's Opportunities link (forecast_type: direct)
     * FALSE - link to manager worksheet (forecast_type: rollup)
     */
    showOpps: '',

    /**
     * Commit Date
     */
    commitDate: '',

    /**
     * Deferred object for manager worksheet render
     */
    mDeferred: $.Deferred(),

    /**
     * Deferred object for worksheet model being ready
     */
    wDeferred:$.Deferred(),

    bindDataChange: function() {
        var self = this;

        if(self.context && self.context.forecasts) {
            //Bind to the worksheetmanager render event so we know that the view has been rendered
            self.context.forecasts.on("forecasts:worksheetmanager:rendered", function() {
                self.mDeferred.resolve();
            });
            //Bind to the committed model being reset so we know that the model has been updated
            self.context.forecasts.committed.on("reset", function() {
                self.wDeferred.resolve();
            });
        }

        self.handleDeferredRender();
    },

    /**
     * Handles setting up the listeners for the two deferred objects.  When both conditions are satisfied
     * it calls _render and sets itself up again.
     *
     */
    handleDeferredRender: function() {
        var self = this;
        $.when(self.wDeferred, self.mDeferred).done(function() {
            self._render();
            //Reset the deferred objects
            self.wDeferred = self.mDeferred = $.Deferred();
            self.handleDeferredRender();
        });
    },

    /**
     * Overwrite the render method.  This function also does some checks to determine whether or not to show an
     * alert indicating a commit entry.  The alert is shown for a reportee if
     *
     * 1) The reportee's forecast was commit at a time after the most recent manager's forecast commit
     * or
     * 2) If the manager had no history of a forecast commit
     *
     * @return {*}
     * @private
     */
    _render:function () {
        if(this.context) {
            this.showFieldAlert = false;
            this.uid = this.model.get('user_id');
            this.showOpps = this.model.get('show_opps');

            var fieldDate;

            if(this.model.get('date_modified')) {
               fieldDate = new Date(this.model.get('date_modified'));
            }
            
            if(!_.isEmpty(this.context.forecasts.committed.models)) {
                var lastCommittedDate = new Date(_.first(this.context.forecasts.committed.models).get('date_modified'));

                // if fieldDate is newer than the forecast commitDate value, then we want to show the field
                if (_.isDate(fieldDate) && _.isDate(lastCommittedDate)) {
                    this.showFieldAlert = (fieldDate.getTime() > lastCommittedDate.getTime());
                }
            } else if(_.isDate(fieldDate)) {
                this.showFieldAlert = true;
            }

            this.commitDate = fieldDate;
            this.options.viewName = 'historyLog';
            app.view.Field.prototype._render.call(this);
        }
        return this;
    }

})
