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

(function(app) {

    /**
   	 * File module provides methods to help with hanlding uploading error checking and validation
   	 *
   	 * @class Utils.File
   	 * @singleton
   	 * @alias SUGAR.App.file
   	 */
    app.augment('file', (function() {

        return {

            checkFileFieldsAndProcessUpload : function(model, callbacks, options) {
                var file, $file, $files, filesToUpload, filesToUploadResults, fileField, successFn, errorFn;

                callbacks = callbacks || {};
                options = options || {};

                // Check if there are attachments
                $files = _.filter($(":file"), function(file) {
                    var $file = $(file);
                    return ($file.val() && $file.attr("name") && $file.attr("name") !== "") ? $file.val() !== "" : false;
                });
                filesToUpload = $files.length;
                filesToUploadResults = {};

                successFn = function(data) {
                    filesToUpload--;
                    _.extend(filesToUploadResults, data);
                    if (filesToUpload===0) {
                        app.alert.dismiss('upload');
                        if (callbacks.success) callbacks.success(filesToUploadResults);
                    }
                };

                errorFn = function(error) {
                    var errors = {};

                    // Set model to new by removing it's id attribute. Note that in our initial attempt
                    // to upload file(s) we set delete_if_fails true so server has marked record deleted: 1
                    // Since we may have only create privs (e.g. we can't edit/delete Notes), we'll start anew.
                    if (options.deleteIfFails !== false) {
                        model.unset('id', {silent: true});
                    }

                    // All or nothing .. if uploading 1..* attachments, if any one fails the whole atomic
                    // operation has failed; so we really want to trigger error and possibly and start over.
                    filesToUpload = 0;
                    app.alert.dismiss('upload');
                    errors[error.responseText] = {};
                    model.trigger('error:validation:' + this.field, errors);
                    model.trigger('error:validation');
                };

                // Process attachment uploads
                if (filesToUpload > 0) {
                    app.alert.show('upload', {level: 'process', title: 'LBL_UPLOADING', autoclose: false});

                    // Field by field
                    for (file in $files) {
                        $file = $($files[file]);
                        fileField = $file.attr("name");

                        model.uploadFile(fileField, $file, {
                            field: fileField,
                            success: successFn,
                            error: errorFn
                        },
                        options);
                    }
                } else {
                    if (callbacks.success) callbacks.success(filesToUploadResults);
                }
            }
        }

    })(), false);
})(SUGAR.App);
