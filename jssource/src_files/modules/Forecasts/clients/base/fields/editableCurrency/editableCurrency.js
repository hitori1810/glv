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
    extendsFrom: 'CurrencyField',

    symbol: '',

    events: {
        'mouseenter span.editable': 'togglePencil',
        'mouseleave span.editable': 'togglePencil',
        'click span.editable': 'onClick',
        'blur span.edit input': 'onBlur',
        'keyup span.edit input': 'onKeyup'
    },

    inputSelector: 'span.edit input',

    errorMessage: '',

    isErrorState: false,

    _canEdit: true,

    initialize: function (options) {

        app.view.fields.IntField.prototype.initialize.call(this, options);

        this.checkIfCanEdit();

        this.symbol = app.currency.getCurrencySymbol(this.model.get('currency_id'));
    },

    /**
     * Utility Method to check if we can edit again.
     */
    checkIfCanEdit: function() {
        var selectedUser = this.context.forecasts.get('selectedUser');
        if (!_.isUndefined(this.context.forecasts) && !_.isUndefined(this.context.forecasts.config)) {
            this._canEdit = _.isEqual(app.user.get('id'), selectedUser.id) && !_.contains(
                // join the two variable together from the config
                this.context.forecasts.config.get("sales_stage_won").concat(
                    this.context.forecasts.config.get("sales_stage_lost")
                ), this.model.get('sales_stage'));
        }
    },

    /**
     * Overwrite bindDomChange
     *
     * Since we need to do custom logic when a field changes, we have to overwrite this with out ever calling
     * the parent.
     *
     */
    bindDomChange: function () {
        // override parent, do nothing
    },

    /**
     * Only one CTE field can be open/active at a time.
     * When a CTE field is clicked, it sends a message to inform the others.
     * If another field is open with an error, it sends a message
     * and any other open fields will immediately close. This keeps
     * other fields from opening while an errored field is active.
     */
    bindDataChange: function () {
        var self = this;
        self.context.on('field:editable:open', function() {
            // another CTE field has been opened
            if(self.isErrorState) {
                // I am open with an error, send the message
                self.context.trigger('field:editable:error', self.cid);
            }
        }, self);
        self.context.on('field:editable:error', function(cid) {
            if (!_.isEqual(cid, self.cid) && this.options.viewName == 'edit') {
                // some other field is open with an error, close myself
                self.renderDetail();
            }
        }, self);
    },

    /**
     * handle click/blur/keypress events in one place
     *
     * @param {Object} evt
     * @return {Boolean}
     */
    handleEvent: function (evt) {
        if(!_.isObject(evt)
            || this.options.viewName != 'edit'
            || !this.isEditable()
            || !(this.model instanceof Backbone.Model)) {
            return false;
        }
        var self = this;
        var el = this.$el.find(this.fieldTag);
        // test if value changed
        if(!this.compareValuesLocale(self.$el.find(self.inputSelector).val(), this.model.get(this.name))) {
            var value = self.parsePercentage(self.$el.find(self.inputSelector).val());
            if (self.isValid(value)) {
                self.model.set(self.name, self.unformat(value));
                self.renderDetail();
            } else {
                // render error
                self.isErrorState = true;
                var hb = Handlebars.compile("{{str_format key module args}}"),
                    args = [app.lang.get(self.def.label,'Forecasts')];

                self.errorMessage = hb({'key' : 'LBL_EDITABLE_INVALID', 'module' : 'Forecasts', 'args' : args});

                self.showErrors();
                self.$el.find(self.inputSelector).focus().select();
                // Focus doesn't always change when tabbing through inputs on IE9 (Bug54717)
                // This prevents change events from being fired appropriately on IE9
                if ($.browser.msie && el.is("input")) {
                    el.on("input", function () {
                        // Set focus on input element receiving user input
                        el.focus();
                    });
                }
            }
        } else {
            self.renderDetail();
        }
        return true;
    },

    /**
     * renders the detail view
     */
    renderDetail: function () {
        this.isErrorState = false;
        this.options.viewName = 'detail';
        this.render();
    },

    /**
     * Toggles the pencil icon on and off depending on the mouse state
     *
     * @param evt
     */
    togglePencil: function (evt) {
        evt.preventDefault();
        if (!this.isEditable()) return;
        if(evt.type == 'mouseenter') {
            this.$el.find('.edit-icon').removeClass('hide');
            this.$el.find('.edit-icon').addClass('show');
        } else {
            this.$el.find('.edit-icon').removeClass('show');
            this.$el.find('.edit-icon').addClass('hide');
        }
    },

    /**
     * Switch the view to the Edit view if the field is editable and it's clicked on
     * @param evt
     */
    onClick : function(evt) {
        evt.preventDefault();
        if (!this.isEditable()) return;

        this.options.viewName = 'edit';
        this.render();

        // set the edit input string to an unformatted number
        var formattedValue = app.utils.formatNumber(
            this.model.get(this.name),
            app.user.getPreference('decimal_precision'),
            app.user.getPreference('decimal_precision'),
            '',
            app.user.getPreference('decimal_separator')
        );
        this.$el.find(this.inputSelector).val(formattedValue);

        // put the focus on the input
        this.$el.find(this.inputSelector).focus().select();

        // inform other fields that I am opening
        this.context.trigger('field:editable:open');

    },

    /**
     * Handle when esc/enter/tab and tab keys are pressed
     *
     * @param evt
     */
    onKeyup: function (evt) {
        evt.preventDefault();
        if (evt.which == 27) {
            // esc key, cancel edits
            this.cancelEdits(evt);
        } else if (evt.which == 13 || evt.which == 9) {
            // enter or tab, handle event
            this.handleEvent(evt);
        }
    },

    /**
     * reset value to model and view detail template
     *
     * evt {Object}
     */
    cancelEdits: function(evt) {
        this.$el.find(this.inputSelector).val(this.value);
        this.renderDetail();
    },

    /**
     * Handle when field is blurred
     *
     * @param evt
     */
    onBlur: function (evt) {
        evt.preventDefault();
        this.handleEvent(evt);
    },

    /**
     * compare two numeric values according to user locale
     *
     * @param val1
     * @param val2
     * @return {Boolean} true if equal
     */
    compareValuesLocale: function(val1, val2) {
        var ogVal = app.utils.formatNumber(
                app.utils.unformatNumberStringLocale(val1),
                app.user.getPreference('decimal_precision'),
                app.user.getPreference('decimal_precision'),
                '',
                app.user.getPreference('decimal_separator')
            ),
            ngVal = app.utils.formatNumber(
                val2,
                app.user.getPreference('decimal_precision'),
                app.user.getPreference('decimal_precision'),
                '',
                app.user.getPreference('decimal_separator')
            );
        return _.isEqual(ogVal, ngVal);
    },

    /**
     * field validator
     *
     * @param value
     * @return {Boolean}
     */
    isValid: function (value) {

        // trim off any whitespace
        value = value.toString().trim();

        var ds = app.utils.regexEscape(app.user.getPreference('decimal_separator')) || '.',
            gs = app.utils.regexEscape(app.user.getPreference('number_grouping_separator')) || ',',
            // matches a valid positive decimal number
            reg = new RegExp("^\\+?(\\d+|\\d{1,3}("+gs+"\\d{3})*)?("+ds+"\\d+)?\\%?$");

        // always make sure that we have a string here, since match only works on strings
        if (value.length == 0 || _.isNull(value.match(reg))) {
            return false;
        }

        // the value passed all validation, return true
        return true;
    },

    /**
     * Can we edit this?
     *
     * @return {boolean}
     */
    isEditable: function () {
        return this._canEdit;
    },

    /**
     * Check the value to see if it's a percentage, if it is, then figure out the change.
     *
     * @param value
     * @return {*}
     */
    parsePercentage : function(value) {
        var orig = this.model.get(this.name);
        var parts = value.toString().match(/^([+-]?)(\d+(\.\d+)?)\%$/);
        if(parts) {
            // use original number to apply calculations
            value = app.math.mul(app.math.div(parts[2],100),orig);
            if(parts[1] == '+') {
                value = app.math.add(orig,value);
            } else if(parts[1] == '-') {
                value = app.math.sub(orig,value);
            }
            value = app.math.round(value);
        }
        return value;
    },

    /**
     * Method to show the error message
     */
    showErrors : function() {
        // attach error styles
        var self = this;
        this.$el.find('.error-message').html(this.errorMessage);
        this.$el.find('.control-group').addClass('error');
        this.$el.find('.help-inline.editable-error').removeClass('hide').addClass('show');
        // make error message button cancel edits
        this.$el.find('.btn.btn-danger').on("click", function(evt) {
            self.cancelEdits.call(self, evt);
        });
    }

})