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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html {$langHeader}>
<head>
    <link rel="SHORTCUT ICON" href="{$FAVICON_URL}">
    <meta http-equiv="Content-Type" content="text/html; charset={$APP.LBL_CHARSET}">
    <title>{$SYSTEM_NAME}</title>
    {$SUGAR_CSS}

    {if $AUTHENTICATED}
    <link rel='stylesheet' type="text/css" href='{sugar_getjspath file="include/ytree/TreeView/css/folders/tree.css"}'/>
    <link rel='stylesheet' type="text/css" href='{sugar_getjspath file="include/SugarCharts/Jit/css/base.css"}'/>
    <link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/include/javascripts/MultipleSelect/multiple-select.css'}"/>
    <link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/include/css/CustomStyle.css'}"/>
    <link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/include/javascripts/alertifyjs/alertify.min.css'}"/>
    <link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/include/javascripts/Spinner/Spinner.css'}"/>
    <link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Notifications/tpls/notifications.css'}" />
    {/if}
    {$SUGAR_JS}
    {sugar_getscript file="custom/include/javascripts/alertifyjs/alertify.min.js"}
    {sugar_getscript file="custom/include/javascripts/CustomDCMenus.js"}
    {sugar_getscript file="custom/include/javascripts/DCBoxFix.js"}
    {sugar_getscript file="custom/include/javascripts/SavedSearch.js" script_tag_attrs="async defer"}
    {sugar_getscript file="custom/include/javascripts/currency_fm.js"}
    {sugar_getscript file="custom/include/javascripts/Numeric.js"}
    {sugar_getscript file="custom/include/javascripts/DateJS/date.js"}
    {sugar_getscript file="custom/include/javascripts/CursorPosition.js"} 
    {sugar_getscript file="custom/include/javascripts/jquery.tooltip.js" script_tag_attrs="async defer"}
    {sugar_getscript file="custom/include/javascripts/CustomSearchForm.js" script_tag_attrs="async defer"}
    {sugar_getscript file="custom/include/javascripts/CustomGlobalLanguage.js" script_tag_attrs="async defer"}   
    {sugar_getscript file="custom/include/javascripts/StringUtil.js"}
    {sugar_getscript file="custom/include/javascripts/MultipleSelect/jquery.multiple.select.js"}
    {sugar_getscript file="custom/include/javascripts/CustomMultiSelectFields.js"}    
    {sugar_getscript file="custom/modules/C_DuplicationDetection/js/duplicationHandler.js"}  
    {sugar_getscript file="custom/modules/C_FieldHighlighter/js/FieldHighlighterHandler.js" script_tag_attrs="async defer"}  
    {sugar_getscript file="modules/Notifications/tpls/notifications.js" script_tag_attrs="async defer"}
    {sugar_getscript file="custom/modules/C_HelpTextConfig/js/HelpTextConfigHandler.js" script_tag_attrs="async defer"}
        
    {if $smarty.get.action neq 'repair'}<script type="text/javascript" src="index.php?entryPoint=GetJSLanguage"></script>{/if}
    
      
    {literal}
    <script type="text/javascript">
    //LOCK-DATA VARIABLE - Add By Lap Nguyen
     var sugar_config_lock_info     = '{/literal}{$sugar_config_lock_info}{literal}';
     var sugar_config_lock_date     = '{/literal}{$sugar_config_lock_date}{literal}';
     var except_lock_for_user_name  = '{/literal}{$except_lock_for_user_name}{literal}';
     var current_user_name          = '{/literal}{$current_user_name}{literal}';
     var is_admin                   = '{/literal}{$is_admin}{literal}';
     var max_tabs                   = '{/literal}{$default_max_tabs}{literal}';
     var maximum_sms                = '{/literal}{$maximum_sms_messages}{literal}';
    </script>
    {/literal}
    {sugar_getscript file="themes/RacerX/js/util.js"}

    {literal}
    <style type="text/css" id="jstree-stylesheet">
        .input_readonly, .input_readonly:focus{
        background-color: rgb(221, 221, 221);
        color: rgb(165, 42, 42);
        }
        option[value='not_empty'] {
            font-weight: bold;
        }
        .curency_readonly, .curency_readonly:focus{
        font-weight: bold;
        color: rgb(165, 42, 42);
        text-align: right;
        background-color: rgb(221, 221, 221);
        }
        .vr {
    display: inline;
    height: 30px;
    width: 0px;
    border: 1px dashed #cccccc;
    margin-left: 10px;
    margin-right: 10px;
    float: left;
            }
    </style>
    <script type="text/javascript">
        <!--
        SUGAR.themes.theme_name      = '{/literal}{$THEME}{literal}';
        SUGAR.themes.hide_image      = '{/literal}{sugar_getimagepath file="hide.gif"}{literal}';
        SUGAR.themes.show_image      = '{/literal}{sugar_getimagepath file="show.gif"}{literal}';
        SUGAR.themes.loading_image      = '{/literal}{sugar_getimagepath file="img_loading.gif"}{literal}';
        if ( YAHOO.env.ua )
        UA = YAHOO.env.ua;
        -->
    </script>
    {/literal}
</head>
