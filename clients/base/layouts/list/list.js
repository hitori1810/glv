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
({_placeComponent:function(comp,def){var size=def.size||12;function createLayoutContainers(self){if(!self.$el.children()[0]){self.$el.addClass("container-fluid").append($('<div/>').addClass('row-fluid').append($('<div/>').addClass("span"+size).append($('<div/>').addClass("thumbnail list"))));}}
createLayoutContainers(this);this.$el.find('.thumbnail').append(comp.el);}})