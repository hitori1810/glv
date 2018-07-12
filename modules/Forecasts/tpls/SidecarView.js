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
(function(app){app.events.on("app:init",function(){app.logger.debug("Route changed to "+app.viewModule+" index!");app.router.route("","index",function(){app.controller.loadView({module:'Forecasts',layout:'index'});});app.router.route("config","config",function(){app.controller.loadView({module:"Forecasts",layout:"config"});});});})(SUGAR.App);