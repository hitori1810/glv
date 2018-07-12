{*
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

*}
<link rel="stylesheet" type="text/css" href="{$css_url}" />
<style>
    h2{literal}{line-height: 100%;}{/literal}
    body{literal}{padding-top: 0px;}{/literal}
</style>
<h1>Customize Theme</h1>
<div class="themes" style="">
    <div class="content">
        <br>
        <div class="alert alert-process">
            <strong>Loading</strong>

            <div class="loading">
                <span class="l1"></span><span class="l2"></span><span class="l3"></span>
            </div>
        </div>
    </div>
</div>




{literal}

<script language="javascript">
// bootstrap our config
(function (app) {
    app.augment("config", {
        appId:'portal',
        env:'dev',
        debugSugarApi:false,
        logLevel:'FATAL',
        logWriter:'ConsoleWriter',
        logFormatter:'SimpleFormatter',
        serverUrl:'./rest/v10',
        serverTimeout:30,
        maxQueryResult:20,
        maxSearchQueryResult:3,
        fieldsToDisplay:5,
        platform:"portal",
        defaultModule:"Cases",
        additionalComponents:{
            alert:{
                target:'#alert'
            }
        },
        clientID:"sugar",
        authStore:"sugarAuthStore",
        loadCss: false,
        syncConfig: false
    }, false);

})(SUGAR.App);

// set our auth Token
SUGAR.App.sugarAuthStore.set('AuthAccessToken', {/literal}'{$token}'{literal});

// bootstrap token
(function (app) {
    app.augment("theme", {
        initTheme:function (authAccessToken) {
            app.AUTH_ACCESS_TOKEN = authAccessToken;
            app.AUTH_REFRESH_TOKEN = authAccessToken;
            app.init({
                el:".themes",
                contentEl:".content"
            });
            return app;
        }
    });
})(SUGAR.App);
// Reset app if it already exists
if (App){
    App.destroy();
}
// Call initTheme with the session id as token
var App = SUGAR.App.theme.initTheme({/literal}'{$token}'{literal});

// should already be logged in to sugar, don't need to log in to sidecar.
App.api.isAuthenticated = function () {
    return true;
};

// Disabling the app sync complete event which starts sidecars competing router
App.events.off("app:sync:complete");
//force app sync and load the appropriate view on success
App.sync(
        {
            callback:function (data) {
                //TODO the module probably shouldnt be cases
                App.controller.loadView({
                    module:'Cases',
                    layout:'themeroller',
                    create:true
                });
            },
            err:function (data) {
                console.log("app sync error");
            }
        }
);

</script>
{/literal}
