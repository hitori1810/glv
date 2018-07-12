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

<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=8, IE=9, IE=10" >
        <link rel="stylesheet" href="sidecar/lib/chosen/chosen.css"/>

        <!-- App Scripts -->
        <script src='sidecar/minified/sidecar.min.js'></script>
        <!-- <script src='sidecar/minified/sugar.min.js'></script> -->
        <script src='{$configFile}'></script>

        <!-- CSS -->
        <link rel="stylesheet" href="sidecar/lib/chosen/chosen.css"/>
        <link rel="stylesheet" href="styleguide/styleguide/bootstrap.css"/>
        <link rel="stylesheet" href="sidecar/lib/jquery-ui/css/smoothness/jquery-ui-1.8.18.custom.css"/>
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet" type="text/css">
    </head>
    <body>
    	<div id="sugarcrm">
			<div id="sidecar">
                <div id="alert" class="alert-top"></div>
                <div id="header"></div>
                <div id="content">
                    <div class="container welcome">
                        <div class="row">
                            <div class="span4 offset4 thumbnail">
                                <div class="modal-header tcenter">
                                    <img src="include/javascript/twitterbootstrap/img/throbber.gif"></img>
                                </div>
                                <div class="modal-footer">
                                    Loading...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="footer"></div>
			</div>
		</div>
        {literal}
		<script language="javascript">
			var syncResult, view, layout, html;
			var App = SUGAR.App.init({
                el: "#sidecar",
                callback: function(app){
                    app.start();
                }
            });
            App.api.debug = App.config.debugSugarApi;
		</script>
        {/literal}
    </body>
</html>