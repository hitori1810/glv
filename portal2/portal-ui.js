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

(function(app) {

    /**
     * Overrides View::_renderHtml() to enable bootstrap widgets after the element has been added to the DOM
     */
    var __superViewRender__ = app.view.View.prototype._renderHtml;
    app.view.View.prototype._renderHtml = function() {

        __superViewRender__.call(this);

        // do this if greater than 768px page width
        if ($(window).width() > 768) {
            this.$("[rel=tooltip]").tooltip({ placement: "bottom" });
        }
        //popover
        this.$("[rel=popover]").popover();
        this.$("[rel=popoverTop]").popover({placement: "top"});

        if ($.fn.timeago) {
            $("span.relativetime").timeago({
                logger: SUGAR.App.logger,
                date: SUGAR.App.date,
                lang: SUGAR.App.lang,
                template: SUGAR.App.template
            });
        }
        /**
         * Fix placeholder on global search on IE and old browsers
         */
        this.$("input[placeholder]").placeholder();
    };

    /**
     * Overrides View::initialize() to remove the bootstrap widgets element from all the page
     * The widget is actually bind to an element that will be removed from the DOM when the view changes. So we need to
     * manually remove elements automatically created by the widget.
     */
    var __superViewInit__ = app.view.View.prototype.initialize;
    app.view.View.prototype.initialize = function(options) {
        __superViewInit__.call(this, options);
        $('.popover, .tooltip').remove();
    };

    /**
     * Overrides Field::_render() to fix placeholders on IE and old browsers
     */
    var __superFieldRender__ = app.view.SupportPortalField.prototype._render;
    app.view.SupportPortalField.prototype._render = function() {
        _.each(this.$('[rel="tooltip"]'), function(element) {
            $(element).tooltip('hide');
        })
        __superFieldRender__.call(this);
        this.$("input[placeholder]").placeholder();
    };


})(SUGAR.App);
