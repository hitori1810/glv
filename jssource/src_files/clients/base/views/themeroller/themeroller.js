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
 * View that displays edit view on a model
 * @class View.Views.EditView
 * @alias SUGAR.App.layout.EditView
 * @extends View.View
 */
({
    events: {
        "click [name=save_button]": "saveTheme",
        "click [name=refresh_button]": "loadTheme",
        "click [name=reset_button]": "toggleModal",
        "click #modal-confirm-reset #buttonYes": "resetTheme",
        "click #modal-confirm-reset #buttonNo": "toggleModal",
        "click #modal-confirm-reset .close": "toggleModal",
        "blur input": "previewTheme"
    },
    initialize: function(options) {
        app.view.View.prototype.initialize.call(this, options);
        this.customTheme = "default";
        this.loadTheme();
    },
    parseLessVars: function() {
        if (this.lessVars && this.lessVars.rel && this.lessVars.rel.length > 0) {
            _.each(this.lessVars.rel, function(obj, key) {
                this.lessVars.rel[key].relname = this.lessVars.rel[key].value;
                this.lessVars.rel[key].relname = this.lessVars.rel[key].relname.replace('@', '');
            }, this);
        }
    },
    _renderHtml: function() {
        this.parseLessVars();
        app.view.View.prototype._renderHtml.call(this);
        _.each(this.$('.hexvar[rel=colorpicker]'), function(obj, key) {
            $(obj).blur(function() {
                $(this).parent().parent().find('.swatch-col').css('backgroundColor', $(this).val());
            });
        }, this);
        this.$('.hexvar[rel=colorpicker]').colorpicker();
        this.$('.rgbavar[rel=colorpicker]').colorpicker({format: 'rgba'});
    },
    loadTheme: function() {
        this.themeApi('read', {}, function(data) {
            this.lessVars = data;
            this.render();
            this.previewTheme();
        });
    },
    saveTheme: function() {
        // get the value from each input
        var colors = this.getInputValues();

        this.showMessage('LBL_SAVE_THEME_PROCESS');
        this.themeApi('create', colors, function() {
            this.showMessage('LBL_REQUEST_PROCESSED', 3000);
        });
    },
    resetTheme: function() {
        this.toggleModal();

        this.showMessage('LBL_RESET_THEME_PROCESS');
        this.themeApi('create', {"reset": true}, function(data) {
            this.showMessage('LBL_REQUEST_PROCESSED', 3000);
            this.loadTheme();
        });
    },
    previewTheme: function() {
        var colors = this.getInputValues();
        this.context.set("colors", colors);
    },
    themeApi: function(method, params, successCallback) {
        var self = this;
        _.extend(params, {
            platform: app.config.platform,
            themeName: self.customTheme
        });
        var paramsGET   = (method==='read') ? params : {},
            paramsPOST  = (method==='read') ? {} : params;
        var url = app.api.buildURL('theme', '', {}, paramsGET);
        app.api.call(method, url, paramsPOST,
            {
                success: successCallback ,
                error: function(error) {
                    app.error.handleHttpError(error);
                }
            },
            { context: self }
        );
    },
    toggleModal: function() {
        this.$('#modal-confirm-reset').toggleClass('hide');
    },
    getInputValues: function() {
        var colors = {};
        this.$('input').each(function() {
            var $this = $(this);
            colors[$this.attr("name")] = $this.hasClass('bgvar') ? '"' + $this.val() + '"' : $this.val();
        });
        return colors;
    },
    showMessage: function(messageKey, timer) {

        var message = app.lang.getAppString(messageKey);

        ajaxStatus = new SUGAR.ajaxStatusClass() || null;

        if (ajaxStatus) {
            if (timer) {
                ajaxStatus.flashStatus(message, timer);
                window.setTimeout('ajaxStatus.hideStatus();', timer);
            } else {
                ajaxStatus.showStatus(message);
            }
        }
    }
})