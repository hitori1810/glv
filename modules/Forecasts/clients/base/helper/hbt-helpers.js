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
(function(app){Handlebars.registerHelper('formatNumber',function(number){if(typeof number==="undefined"||number===null){number=0;}
var parts=number.toString().split(".");parts[0]=parts[0].replace(/\B(?=(\d{3})+(?!\d))/g,",");return parts.join(".");});Handlebars.registerHelper('formatPercentage',function(percent){if(typeof percent==="undefined"||percent===null){percent=0;}
percent=percent*100;percent=Math.round(percent*10)/10;if(percent>1){percent=parseInt(percent,10)||0;}
return percent+'%';});Handlebars.registerHelper("debugger",function(){debugger;});Handlebars.registerHelper("consoleLog",function(param){console.log(param);});Handlebars.registerHelper("str_format",function(key,module,args){module=_.isString(module)?module:null;var label=app.lang.get(key,module);if(_.isString(args)||args.length==1)
{args=(_.isString(args))?args:args[0];label=label.replace('{0}',args);return new Handlebars.SafeString(label);}
var len=args.length;for(var x=0;x<len;x++)
{label=label.replace('{'+x+'}',args[x]);}
return new Handlebars.SafeString(label);});})(SUGAR.App);