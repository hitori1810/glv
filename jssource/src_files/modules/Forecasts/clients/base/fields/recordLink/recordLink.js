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
     * Holds the record id for passing into recordTemplate
     */
    record_id: '',

    /**
     * Holds the module for passing into recordTemplate
     */
    module: '',

    /**
     * Holds the action for passing into recordTemplate
     */
    action: '',

    /**
     * Holds the link text for passing into recordTemplate
     */
    linkText: '',

    _render:function() {
        if(this.name == 'name') {
            var route = this.def.route;
            this.recordLink = {
                    record_id: this.model.get(route.recordID),
                    module: route.module,
                    action: route.action,
                    linkText: this.model.get(this.name)};
            // setting the viewName allows us to explicitly set the template to use
            this.options.viewName = 'recordLink';
        }
        app.view.Field.prototype._render.call(this);
        return this;
    }
})
