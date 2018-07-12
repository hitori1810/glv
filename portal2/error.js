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
 * Portal specific error handlers.
 */
(function(app) {
    app.error = _.extend(app.error);

    function backToLogin(bDismiss) {
        if(bDismiss) app.alert.dismissAll();
        app.router.login();
    }

    function alertUser(key,title,msg) {
        app.alert.show(key, {level: "error", messages: app.lang.getAppString(msg), title:app.lang.get(title), autoClose: true});
    }
    
    /**
     * This is caused by attempt to login with invalid creds. 
     */
    app.error.handleNeedLoginError = function(error) {
        backToLogin(true);
        alertUser("needs_login_error" , "LBL_PORTAL_INVALID_CREDS_TITLE", "LBL_PORTAL_INVALID_CREDS");
    };

    /**
     * This is caused by expired or invalid token. 
     */
    app.error.handleInvalidGrantError = function(error) {
        backToLogin(true);
        alertUser("invalid_grant_error", "LBL_PORTAL_INVALID_GRANT_TITLE", "LBL_PORTAL_INVALID_GRANT");
    };

    /**
     * Client authentication handler. 
     */
    app.error.handleInvalidClientError = function(error) {
        backToLogin(true);
        alertUser("invalid_client_error","LBL_PORTAL_AUTH_FAILED_TITLE","LBL_PORTAL_AUTH_FAILED");
    };
    
    /**
     * Invalid request handler. 
     */
    app.error.handleInvalidRequestError = function(error) {
        backToLogin(true);
        alertUser("invalid_request_error", "LBL_PORTAL_INVALID_REQUEST_TITLE", "LBL_PORTAL_INVALID_REQUEST");
    };

    /**
     * 0 Timeout error handler. If server doesn't respond within timeout.
     */
    app.error.handleTimeoutError = function(error) {
        backToLogin(true);
        alertUser("timeout_error", "LBL_PORTAL_REQUEST_TIMEOUT_TITLE", "LBL_PORTAL_REQUEST_TIMEOUT");
    };

    /**
     * 401 Unauthorized error handler. 
     */
    app.error.handleUnauthorizedError = function(error) {
        backToLogin(true);
        alertUser("unauthorized_request_error", "LBL_PORTAL_UNAUTHORIZED_TITLE", "LBL_PORTAL_UNAUTHORIZED");
    };

    /**
     * 403 Forbidden error handler. 
     */
    app.error.handleForbiddenError = function(error) {
        app.alert.dismissAll();
        // If portal is not configured, return to login screen if necessary
        if(error.code == "portal_not_configured"){
            backToLogin(true);
        }
        alertUser("forbidden_request_error", "LBL_PORTAL_RESOURCE_UNAVAILABLE_TITLE", error.message ? error.message : "LBL_PORTAL_RESOURCE_UNAVAILABLE");
    };
    
    /**
     * 404 Not Found handler. 
     */
    app.error.handleNotFoundError = function(error) {
        app.controller.loadView({
            layout: "error",
            errorType: "404",
            module: "Error",
            create: true
        });    
    };

    /**
     * 405 Method not allowed handler.
     */
    app.error.handleMethodNotAllowedError = function(error) {
        backToLogin(true);
        alertUser("not_allowed_error", "LBL_PORTAL_METHOD_NOT_ALLOWED_TITLE", "LBL_PORTAL_METHOD_NOT_ALLOWED");
    };

    /**
     * 412 Precondtion failure error.
     */
    app.error.handlePreconditionFailureError = function(error) {
        backToLogin(true);
        // TODO: For finer grained control we could sniff the {error: <code>} in the response text (JSON) for one of:
        // missing_parameter, invalid_parameter, request_failure
        alertUser("precondtion_failure_error", "LBL_PORTAL_PRECONDITION_MISSING_TITLE", "LBL_PORTAL_PRECONDITION_MISSING");
    };
       
    /**
     * 500 Internal server error handler. 
     */
    app.error.handleServerError = function(error) {
        app.controller.loadView({
            layout: "error",
            errorType: "500",
            module: "Error",
            create: true
        });
    };

})(SUGAR.App);

