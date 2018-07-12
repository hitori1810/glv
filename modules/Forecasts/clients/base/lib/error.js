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
(function(app){app.error.handleForecastAPIError=function(error){$('#alerts').empty()
var key='',errorMsg='',errorTitle='';switch(error.status){default:key=error.code;errorTitle=error.errorThrown;errorMsg=error.message;break;}
app.alert.show(key,{level:'error',messages:errorMsg,title:errorTitle});};})(SUGAR.App);