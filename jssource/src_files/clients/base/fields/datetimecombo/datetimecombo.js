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
     * DateTimeCombo Widget
     * 
     * Extends from Date widget but adds the time component.
     */   
    extendsFrom:'DateField',

    stripIsoTZ: true,

    // This is dynamically detected and represents whether meridien is appropriate
    showAmPm: false,

    // used by hbt template (note we inherit dateValue from basedate)
    timeValue: '', 

    serverTimeFormat: 'H:i:s',

    /**
     * Renders widget, sets up date and time pickers, etc.
     * @param  {String} value  
     */
    _render:function(value) {
        var self = this, viewName;
        
        // Set our internal time and date values so hbt picks up
        self._presetDateValues();

        app.view.fields.DateField.prototype._render.call(self);
        viewName = self._getViewName();
        $(function() {
            if (self._isEditView(viewName)) {
                // Note: The Datepicker should be setup in parent DateField
                self._setupTimepicker();
            }
        });
    },

    /**
     * Formats value
     * @param  {String} value The value
     * @return {String} formatted value
     */
    format:function(value) {
        var jsDate, output, myUser = app.user, d, parts, before24Hours;
        if (this.stripIsoTZ) {
            value = app.date.stripIsoTimeDelimterAndTZ(value);
        }

        if (this._isNewEditViewWithNoValue(value)) {
            jsDate = this._setDateIfDefaultValue();
            if (!jsDate) {
                return value;
            }
        } else if (!value) {
            return value;
        } else {
            // If the date value in our datebox is invalid, leave it alone and return. It will
            // get handled upstream by sidecar (which uniformly handles field validation errors).
            if (!this._verifyDateString(value)) {
                return value;
            }
            // In case ISO 8601 get it back to js native date which date.format understands
            // Note: if stripIsoTZ true, time zone won't matter since it's already been removed.
            jsDate = new Date(value);
        }

        // Save the 24 hour based hours in case we're using ampm to determine if am or pm later 
        before24Hours = jsDate.getHours();
        value  = app.date.format(jsDate, this.usersDatePrefs)+' '+app.date.format(jsDate, this.userTimePrefs);

        // round time to the nearest 15th if this is a edit which is consitent with rest of app
        if (this.view.name === 'edit') {
            jsDate = app.date.roundTime(jsDate);
        }

        value = {
            date: app.date.format(jsDate, this.usersDatePrefs),
            time: app.date.format(jsDate, this.userTimePrefs),
            amPm: this.showAmPm ? (before24Hours < 12 ? 'am' : 'pm') : ''
        };
        this.timeValue = value['time'];
        this.dateValue = value['date'];
        this.$(".datepicker").datepicker('update', this.dateValue);

        return value;
    },

    unformat:function(value) {
        var jsDate;
        if (value) {
            // The are unformatting a value that's like "Y-m-d H:i:s" and preparing push to server
            jsDate = app.date.parse(value, this.serverDateFormat + ' ' + this.serverTimeFormat);
            if (jsDate) {
                return this._setServerDateString(jsDate);
            } else {
                app.logger.error("Issue setting the server date string for value: " + value);
                return value;
            }
        }
        return value;
    },
        
    /**
     * Sets up the timepicker.
     */
    _setupTimepicker: function() {
        this.$(".ui-timepicker-input").attr('placeholder', this.userTimePrefs);
        this.$(".ui-timepicker-input").timepicker({
            // TODO: 'lang' is only used on time "durations" (e.g. 3 horas, etc.) We can later pull 
            // this from meta, but, this only makes sense if we implement durations. To my mind, this
            // is really a client specific customization for which they can set this themselves.
            // "lang": {"decimal": '.', "mins": 'minutos', "hr": 'hora', "hrs": 'horas'},
            'timeFormat': this.userTimePrefs, // And this will localize their time format anyway ;-)
            'scrollDefaultNow': true,         // detects user's time (e.g if 1pm, dropdown jumps to 1:00)
            'step': 15                        // consistent w/Sugar proper ... may need to be dynamic later
        });

        // Bind Timepicker to proxy functions
        this.$('.ui-timepicker-input').on({
            changeTime: _.bind(this.changeTime, this),
            blur: _.bind(this._handleTimepickerBlur, this)
        });

    }, 
    /**
     * Main hook to update model when timepicker selected
     * @param {event} ev The event 
     */
    changeTime: function(ev) {
        var model     = this.model,
            fieldName = this.name,
            timeValue = '',
            hrsMins= {},
            dateValue = '', timeParts, hour, hours, minutes;

        // Get hours, minutes, and date peices, then set our model
        hrsMins    = this._getHoursMinutes($(ev.currentTarget));
        dateValue  = this._getDatepickerValue();
        this._setDatepickerValue(dateValue);
        model.set(fieldName, this._buildUnformatted(dateValue, hrsMins.hours, hrsMins.minutes), {silent: true});
    },
    /**
     * Precondition: timeAsDate must be date and hrsMins must be object literal.
     * No checking done!
     * 
     * @param  {HTMLElement} The timepicker element 
     * @param  {Date} timeAsDate Date representing time portion
     * @param  {Ojbect} hrsMins Object literal for hours and minutes
     * @return {Object} Object representing hours and minutes. If not edit view 
     * this will simply return hrsMins passed in
     */
    _forceRoundedTime: function(timepicker, timeAsDate, hrsMins) {
        var minutes, hours;

        // If edit view we force time to our 15 minutes blocks
        if (this.view.name === 'edit') {
            timeAsDate = app.date.roundTime(timeAsDate);
            minutes    = this._forceTwoDigits(timeAsDate.getMinutes().toString());
            hours      = this._forceTwoDigits(timeAsDate.getHours().toString());

            // Update timepicker element's value with rounded time
            this._setTimepickerValue($(timepicker), hours, minutes);
            hrsMins.hours   = hours;
            hrsMins.minutes = minutes;
        }
        return hrsMins;
    },
    /**
     * The timepicker plugin doesn't provide blur hook for when the user types in
     * time and then focuses out. Essentially, we update our this.timeValue and model. 
     * @param {event} ev The event 
     */
    _handleTimepickerBlur: function(ev) {
        var dateValue, hrsMins, timeAsDate, hours, minutes, 
            timepicker = ev.currentTarget;

        // First get current hours/minutes, then round to blocks (if edit view)
        hrsMins    = this._getHoursMinutes($(ev.currentTarget));
        timeAsDate = this._getTimepickerValueAsDate($(timepicker));
        hrsMins    = this._forceRoundedTime(timepicker, timeAsDate, hrsMins);
        this._setTimeValue();

        // Get datepicker value and finally set our model
        dateValue  = this._getDatepickerValue();
        this.model.set(this.name, this._buildUnformatted(dateValue, hrsMins.hours, hrsMins.minutes), {silent: true});
    },
    _setTimeValue: function() {
        this.timeValue = this.$('.ui-timepicker-input').val();
        this.timeValue = (this.timeValue) ? this.timeValue : '';
    },
    /**
     * Returns the current value in the timepicker element. 
     * @param {String} $timepickerElement the element. 
     * @return {String} timeValue The time value.
     */
    _getTimepickerValue: function($timepickerElement) {
        var timeValue  = $timepickerElement.val();
        this.timeValue = timeValue; // so hbt template will pick up on next render

        return timeValue;
    },
    /**
     * Get the time using a Javascript Date object, relative to today's date.
     * @param {HTMLElement} $timepickerElement the element. 
     * @return {Date} time relative to today's date in date object 
     */
    _getTimepickerValueAsDate: function($timepickerElement) {
        return this.$($timepickerElement).timepicker('getTime');
    },
    /**
     * Sets the timepicker element and plugin to hours and minutes passed in. 
     * If neither hours or minutes provided defaults to midnight.
     * @param {Object} jQuery wrapped timepicker element. 
     * @param {String} hours optional hours 
     * @param {String} minutes optional minutes
     */
    _setTimepickerValue: function($timepickerElement, hours, minutes) {
        var date = new Date();

        // If no time value set to midnight
        if (!hours && !minutes) {
            // Shorthand allows us to set mins, secs, ms all at once
            date.setHours(0, 0, 0, 0);
        }
        else {
            // If we have minutes or hours set each one conditionally
            if (minutes) {
                date.setMinutes(minutes);
            }
            if (hours) {
                date.setHours(hours);
            }
        }
        $timepickerElement.timepicker('setTime', date);
        this.timeValue = $timepickerElement.val();// so hbt template will pick up on next render
    },
    /**
     * If the field def has a display_default property, or, is required, this
     * will set the model with corresponding date time.
     * @return {Date} jsDate The date.
     */
    _setDateIfDefaultValue: function() {
        var value, jsDate; 

        // If there's a display default 'string' value like "yesterday", format it as a date
        if (this.def.display_default) {
            jsDate = app.date.parseDisplayDefault(this.def.display_default);
            this.model.set(this.name, this._setServerDateString(jsDate), {silent: true}); 
        } else {
            return null;  
        }
        return jsDate;
    },

    /**
     * Takes the time value and returns hours and minutes parts.
     * @param {HTMLElement} $timepickerElement the element. 
     * @return {Object} An object literal like: {hours: <hours>, minutes: <minutes>}
     */
    _getHoursMinutes: function(el) {
        var timeParts, hour, hours, minutes, timeValue, amPm = null;
        timeValue  = this._getTimepickerValue(el) || '';
        timeParts = timeValue.toLowerCase().match(/(\d+)(?::(\d\d?))?\s*([pa]?)/);

        // If timeValue is empty we may get back null for regex. If so, set to default.
        if (!timeParts) {
            return this._setIfNoTime(null, null);
        }

        hour = parseInt(timeParts[1]*1, 10);

        // We have am/pm part (ostensibly 12 hour format)
        if (!_.isEmpty(timeParts[3])) {

            amPm = (timeParts[3] === 'a') ? 'am' : 'pm';

            if (hour == 12) {
                // If 12 and am force 12 to 0, otherwise leave alone
                hours = (timeParts[3] == 'a') ? 0 : hour;
            } else {
                // If pm add 12 to hour e.g. 2 becomes 14, etc.
                hours = (hour + (timeParts[3] == 'p' ? 12 : 0));
            }
        } 
        // Otherwise, we don't have am/pm part (ostensibly 24 hr format)
        else {
            hours = hour;
        }
        minutes = ( timeParts[2]*1 || 0 );

        // Convert above to two character strings
        minutes = this._forceTwoDigits(minutes.toString());
        hours = this._forceTwoDigits(hours.toString());

        return this._setIfNoTime(hours, minutes, amPm);
    },
    /**
     * Helper to set hours and minutes with 12am and 00pm edge cases in mind.
     * @param {String} h hours 
     * @param {String} m minutes
     * @param {String} ampm optional 'am' or 'pm'  
     * @return {Object} object literal with hours, minutes, amPm properties
     */
    _setIfNoTime: function(h, m, ampm) {
        var o = {};

        o.amPm = ampm ? ampm : 'am';

        // Essentially, if we have no time parts, we're going to default to 12:00am
        if (!h && !m) {
            o.amPm = 'am';
        }
        o.hours   = h ? h : '00'; // may display as 12:00am but internally needs to be 00
        o.minutes = m ? m : '00';
        //Convert 12am to 00 and also 00pm to 12
        o.hours   = o.hours === '12' && o.amPm==='am' ? '00' : o.hours;
        o.hours   = o.hours === '00' && o.amPm==='pm' ? '12' : o.hours;

        return o;
    }
})
