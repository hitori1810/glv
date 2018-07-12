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

({
/**
 * Top view that displays a list of models pulled from the context's collection.
 * @class View.Views.ListViewTop
 * @alias SUGAR.App.layout.ListViewTop
 * @extends View.View
 */
    events: {
        'click [rel=tooltip]': 'fixTooltip'
    },
    fixTooltip: function() {
        console.log("click on a tooltip");
        this.$(".tooltip").hide();
    }

})
