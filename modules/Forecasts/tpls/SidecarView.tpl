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
<link rel="stylesheet" type="text/css" href="{$yui_widget_css_url}" />
<link rel="stylesheet" type="text/css" href="{$css_url}" />
{$sprite_url}


<div class="content" id="forecasts">
    <div id="alerts" class="alert-top">
        <div class="alert alert-process">
            <strong>Loading</strong>
            <div class="loading">
                <span class="l1"></span><span class="l2"></span><span class="l3"></span>
            </div>
        </div>
    </div>
</div>
<div id="arrow" title="Show" class="up"><i class="icon-chevron-down"></i></div>
<footer id="footer">
    <div class="row-fluid">
        <div class="span5">
            <span class="logo" id="logo" title="&#169; 2004-{$copyYear} {$app_strings.LBL_SUGAR_COPYRIGHT_NAME_AND_RIGHTS} {$STATISTICS}">SugarCRM</span>
        </div>
        <div class="span2">
            <a href="http://www.sugarcrm.com" target="_blank" class="copyright">&copy; {$copyYear} SugarCRM Inc.</a>
            <script>
                var logoStats = "&#169; 2004-{$copyYear} {$app_strings.LBL_SUGAR_COPYRIGHT_NAME_AND_RIGHTS} {$STATISTICS|addslashes}";
            </script>
        </div>
        <div class="span5">
            <div class="btn-toolbar pull-right">
                <div class="btn-group">
                    <a data-toggle="modal" title="Activity View Tour" id="productTour" href="javascript: void(0);"  class="btn btn-invisible"><i class="icon-road"></i> {$app_strings.LBL_TOUR}</a>
                    <a title="Support" href="{$HELP_URL}" class="btn btn-invisible"><i class="icon-question-sign"></i> {$MODULE_NAME} {$app_strings.LNK_HELP}</a>
                </div>
            </div>
        </div>
    </div>
</footer>
<script src='{$configFile}'></script>
{literal}
<script language="javascript">
    var syncResult, view, layout, html;

    SUGAR.App.sugarAuthStore.set('AuthAccessToken', {/literal}'{$token}'{literal});
    SUGAR.App.sugarAuthStore.set('AuthRefreshToken', {/literal}'{$token}'{literal});

    (function(app) {
        if(!_.has(app, 'forecasts')) {
            app.forecasts = {}
        }
        app.augment("forecasts", _.extend(app.forecasts, {
            initForecast: function(authAccessToken) {
                app.viewModule = 'Forecasts';
                app.AUTH_ACCESS_TOKEN = authAccessToken;
                app.AUTH_REFRESH_TOKEN = authAccessToken;
                app.config.platform = "forecasts";
                app.init({
                    el: "forecasts",
                    contentEl: ".content",
                    //keyValueStore: app.sugarAuthStore, //override the keyValueStore
                    callback: function(app) {
                        var url = app.api.buildURL("Forecasts/init");
                        app.api.call('GET', url, null, {
                            success: function(forecastData) {
                                // get default selections for filter and ranges
                                app.defaultSelections = forecastData.defaultSelections;
                                app.initData = forecastData.initData;
                                app.user.set(app.initData.selectedUser);

                                if(forecastData.initData.forecasts_setup == 0) {
                                    window.location.hash = "#config";
                                }
                                // resize the top menu after the layout has been initialized
                                SUGAR.themes.resizeMenu();
                                app.start();
                            },
                            error:  app.error.handleForecastAPIError
                        });
                    }
                });
                return app;
            }
            }));
     })(SUGAR.App);

//Call initForecast with the session id as token
var App = SUGAR.App.forecasts.initForecast({/literal}'{$token}'{literal});
App.api.debug = App.config.debugSugarApi;

$("#productTour").click(function(){
    if($('#tour').length > 0){
        $('#tour').modal("show");
    }  else {
        SUGAR.tour.init({
            id: 'tour',
            modals: modals,
            modalUrl: "index.php?module=Home&action=tour&to_pdf=1",
            prefUrl: "index.php?module=Users&action=UpdateTourStatus&to_pdf=true&viewed=true",
            className: 'whatsnew',
            onTourFinish: function() {}
        });
    }
});


</script>
{/literal}
