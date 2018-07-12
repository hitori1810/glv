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
    fieldTag : "file",
    events: {
        "click a.file": "startDownload"
    },
    fileUrl: "",
    _render: function() {
        // This array will contain objects accessible in the view
        this.model = this.model || this.view.model;
        app.view.Field.prototype._render.call(this);
        return this;
    },
    format: function(value) {
        var attachments = [];
        // Not the same behavior either the value is a string or an array of files
        if (_.isArray(value)) {
            // If it's an array, we get the uri for each files in the response
            _.each(value, function(file) {
                var fileObj = {
                    name: file.name,
                    url: file.uri
                };
                attachments.push(fileObj);
            }, this);
        } else if (value) {
            // If it's a string, build the uri with the api library
            var fileObj = {
                name: value,
                url: app.api.buildFileURL({
                        module: this.module,
                        id: this.model.id,
                        field: this.name
                    },
                    {
                        htmlJsonFormat: false,
                        passOAuthToken: false
                    })};
            attachments.push(fileObj);
        }
        return attachments;
    },
    startDownload: function(e) {
        var self = this;
        // Starting a download.
        // First, we do an ajax call to the `ping` API. This is supposed to check if the token hasn't expired before we
        // append it to the uri of the file. Thus the token will be valid anytime we append it to the url and start the
        // download.
        App.api.call('read', App.api.buildURL('ping'), {}, {
                success: function(data) {
                   // Second, start the download with the "iframe" hack
                   var uri = self.$(e.currentTarget).data("url") + "?oauth_token=" + app.api.getOAuthToken();
                   self.$el.prepend('<iframe class="hide" src="' + uri + '"></iframe>');
                },
                error: function(data) {
                    // refresh token if it has expired
                    app.error.handleHttpError(data, {});
                }}
        );
    },
    bindDataChange: function() {
        if (this.view.name != "edit" && this.view.fallbackFieldTemplate != "edit") {
            //Keep empty because you cannot set a value of a type `file` input
            app.view.Field.prototype.bindDataChange.call(this);
        }
    }
})
