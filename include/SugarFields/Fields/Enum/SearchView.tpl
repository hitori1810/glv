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
{{capture name=display_size assign=size}}{{$displayParams.size|default:6}}{{/capture}}
{html_options id='{{$vardef.name}}' name='{{$vardef.name}}[]' options={{sugarvar key='options' string=true}} size="{{$size}}" style="width: 150px" {{if $size > 1}}multiple="1"{{/if}} selected={{sugarvar key='value' string=true}}}
