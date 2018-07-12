<?php
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

/*
 * This is the array that is used to determine how to group/concatenate js files together
 * The format is to define the location of the file to be concatenated as the array element key
 * and the location of the file to be created that holds the child files as the array element value.
 * So: $original_file_location => $Concatenated_file_location
 *
 * If you wish to add a grouping that contains a file that is part of another group already,
 * add a '.' after the .js in order to make the element key unique.  Make sure you pare the extension out
 *
 */
        if(!function_exists('getSubgroupForTarget'))
        {
            /**
             * Helper to allow for getting sub groups of combinations of includes that are likely to be required by
             * many clients (so that we don't end up with duplication from client to client).
             * @param  string $subGroup The sub-group
             * @param  string $target The target file to point to e.g. '<app>/<app>.min.js',
             * @return array array of key vals where the keys are source files and values are the $target passed in. 
             */
            function getSubgroupForTarget ($subGroup, $target) {

                // Add more sub-groups as needed here if client include duplication in $js_groupings
                switch ($subGroup) {
                    case 'bootstrap':
                        return array(
                            'styleguide/assets/js/bootstrap-button.js'  => $target,
                            'styleguide/assets/js/bootstrap-tooltip.js' => $target,
                            'styleguide/assets/js/bootstrap-dropdown.js'=>  $target,
                            'styleguide/assets/js/bootstrap-popover.js' => $target,
                            'styleguide/assets/js/bootstrap-modal.js'   => $target,
                            'styleguide/assets/js/bootstrap-alert.js'   => $target,
                            'styleguide/assets/js/bootstrap-datepicker.js' => $target,
                            'styleguide/assets/js/bootstrapx-clickover.js' => $target,
                        );
                        break;
                    case 'bootstrap_core':
                        return array(
                            'include/javascript/jquery/bootstrap/bootstrap.min.js'       =>   $target,
                            'include/javascript/jquery/jquery.popoverext.js'             =>   $target,
                        );
                        break;
                    // these are the only files not in bootstrap.min.js that we need in forecasts
                    // as bootstrap.min.js is already included in sugar_grp1_bootstrap.js
                    case 'bootstrap_forecasts':
                        return array(
                            'styleguide/assets/js/bootstrap-datepicker.js' => $target,
                            'styleguide/assets/js/bootstrapx-clickover.js' => $target,
                        );
                        break;
                    case 'jquery_core':
                        return array (
                            'include/javascript/jquery/jquery-min.js'             =>    $target,
                            'include/javascript/jquery/jquery-ui-min.js'          =>    $target,
                            'include/javascript/jquery/jquery.json-2.3.js'        =>    $target,
                        );
                        break;
                    case 'jquery_menus':
                        return array(
                            'include/javascript/jquery/jquery.hoverIntent.js'            =>   $target,
                            'include/javascript/jquery/jquery.hoverscroll.js'            =>   $target,
                            'include/javascript/jquery/jquery.hotkeys.js'                =>   $target,
                            'include/javascript/jquery/jquery.superfish.js'              =>   $target,
                            'include/javascript/jquery/jquery.tipTip.js'              	 =>   $target,
                            'include/javascript/jquery/jquery.sugarMenu.js'              =>   $target,
                            'include/javascript/jquery/jquery.highLight.js'              =>   $target,
                            'include/javascript/jquery/jquery.showLoading.js'            =>   $target,
                            'include/javascript/jquery/jquery.dataTables.min.js'         =>   $target,
                            'include/javascript/jquery/jquery.dataTables.customSort.js'  =>   $target,
                            'include/javascript/jquery/jquery.jeditable.js'              =>   $target,
                            'include/javascript/jquery/jquery.chosen.min.js'             =>   $target,
                            'include/javascript/jquery/jquery.jstree.js'              	 =>   $target,                            
                            'include/javascript/jquery/jquery.effects.custombounce.js'   =>   $target,
                        );
                        break;
                    case 'sidecar_libs':
                        return array(
                                'sidecar/lib/handlebars/handlebars-1.0.rc.1.js'                  =>   $target,
                                'sidecar/lib/jquery-ui/js/jquery-ui-1.8.18.custom.min.js'        =>   $target,
                                'sidecar/lib/backbone/underscore.js'                             =>   $target,
                                'sidecar/lib/backbone/backbone.js'                               =>   $target,
                                'sidecar/lib/stash/stash.js'                                     =>   $target,
                                'sidecar/lib/async/async.js'                                     =>   $target,
                                'sidecar/lib/chosen/chosen.jquery.js'                            =>   $target,
                                'sidecar/lib/jquery/jquery.iframe.transport.js'                  =>   $target,
                                'sidecar/lib/jquery-timepicker/jquery.timepicker.js'             =>   $target,
                                'sidecar/lib/php-js/version_compare.js'                          =>   $target
                        );
                        break;
                    default:
                        break;
                }
            }
        }

        $js_groupings = array(
           $sugar_grp1 = array(
                //scripts loaded on first page
                'include/javascript/sugar_3.js'         => 'include/javascript/sugar_grp1.js',
                'include/javascript/ajaxUI.js'          => 'include/javascript/sugar_grp1.js',
                'include/javascript/cookie.js'          => 'include/javascript/sugar_grp1.js',
                'include/javascript/menu.js'            => 'include/javascript/sugar_grp1.js',
                'include/javascript/calendar.js'        => 'include/javascript/sugar_grp1.js',
                'include/javascript/quickCompose.js'    => 'include/javascript/sugar_grp1.js',
                'include/javascript/yui/build/yuiloader/yuiloader-min.js' => 'include/javascript/sugar_grp1.js',
                //HTML decode
                'include/javascript/phpjs/get_html_translation_table.js' => 'include/javascript/sugar_grp1.js',
                'include/javascript/phpjs/html_entity_decode.js' => 'include/javascript/sugar_grp1.js',
                'include/javascript/phpjs/htmlentities.js' => 'include/javascript/sugar_grp1.js',
	            //Expression Engine
	            'include/Expressions/javascript/expressions.js'  => 'include/javascript/sugar_grp1.js',
	            'include/Expressions/javascript/dependency.js'   => 'include/javascript/sugar_grp1.js',
               'include/EditView/Panels.js'   => 'include/javascript/sugar_grp1.js',
            ),
			// solo jquery libraries
			$sugar_grp_jquery_core = getSubgroupForTarget('jquery_core', 'include/javascript/sugar_grp1_jquery_core.js'),

            //bootstrap
            $sugar_grp_bootstrap = getSubgroupForTarget('bootstrap_core', 'include/javascript/sugar_grp1_bootstrap.js'),

            //jquery for moddule menus
            $sugar_grp_jquery_menus = getSubgroupForTarget('jquery_menus', 'include/javascript/sugar_grp1_jquery_menus.js'),

            //core app jquery libraries
			$sugar_grp_jquery = array_merge(getSubgroupForTarget('jquery_core', 'include/javascript/sugar_grp1_jquery.js'),
                getSubgroupForTarget('bootstrap_core', 'include/javascript/sugar_grp1_jquery.js'),
                getSubgroupForTarget('jquery_menus', 'include/javascript/sugar_grp1_jquery.js')
            ),

            //sidecar 3rd party libs
            $sugar_grp_sidecar_libs = getSubgroupForTarget('sidecar_libs', 'include/javascript/sugar_grp1_sidecar_libs.js'),

           $sugar_field_grp = array(
               'include/SugarFields/Fields/Collection/SugarFieldCollection.js' => 'include/javascript/sugar_field_grp.js',
               'include/SugarFields/Fields/Teamset/Teamset.js' => 'include/javascript/sugar_field_grp.js',
               'include/SugarFields/Fields/Datetimecombo/Datetimecombo.js' => 'include/javascript/sugar_field_grp.js',
           ),
            $sugar_grp1_yui = array(
			//YUI scripts loaded on first page
            'include/javascript/yui3/build/yui/yui-min.js'              => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui3/build/loader/loader-min.js'        => 'include/javascript/sugar_grp1_yui.js',
			'include/javascript/yui/build/yahoo/yahoo-min.js'           => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/dom/dom-min.js'               => 'include/javascript/sugar_grp1_yui.js',
			'include/javascript/yui/build/yahoo-dom-event/yahoo-dom-event.js'
			    => 'include/javascript/sugar_grp1_yui.js',
			'include/javascript/yui/build/event/event-min.js'           => 'include/javascript/sugar_grp1_yui.js',
			'include/javascript/yui/build/logger/logger-min.js'         => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/animation/animation-min.js'   => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/connection/connection-min.js' => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/dragdrop/dragdrop-min.js'     => 'include/javascript/sugar_grp1_yui.js',
            //Ensure we grad the SLIDETOP custom container animation
            'include/javascript/yui/build/container/container-min.js'   => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/element/element-min.js'       => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/tabview/tabview-min.js'       => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/selector/selector.js'     => 'include/javascript/sugar_grp1_yui.js',
            //This should probably be removed as it is not often used with the rest of YUI
            'include/javascript/yui/ygDDList.js'                        => 'include/javascript/sugar_grp1_yui.js',
            //YUI based quicksearch
            'include/javascript/yui/build/datasource/datasource-min.js' => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/json/json-min.js'             => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/autocomplete/autocomplete-min.js'=> 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/quicksearch.js'                         => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/menu/menu-min.js'             => 'include/javascript/sugar_grp1_yui.js',
			'include/javascript/sugar_connection_event_listener.js'     => 'include/javascript/sugar_grp1_yui.js',
			'include/javascript/yui/build/calendar/calendar.js'     => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/history/history.js'     => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/resize/resize-min.js'     => 'include/javascript/sugar_grp1_yui.js',
            ),

            $sugar_grp_yui_widgets = array(
			//sugar_grp1_yui must be laoded before sugar_grp_yui_widgets
            'include/javascript/yui/build/datatable/datatable-min.js'   => 'include/javascript/sugar_grp_yui_widgets.js',
            'include/javascript/yui/build/treeview/treeview-min.js'     => 'include/javascript/sugar_grp_yui_widgets.js',
			'include/javascript/yui/build/button/button-min.js'         => 'include/javascript/sugar_grp_yui_widgets.js',
            'include/javascript/yui/build/calendar/calendar-min.js'     => 'include/javascript/sugar_grp_yui_widgets.js',
			'include/javascript/sugarwidgets/SugarYUIWidgets.js'        => 'include/javascript/sugar_grp_yui_widgets.js',
            // Include any Sugar overrides done to YUI libs for bugfixes
            'include/javascript/sugar_yui_overrides.js'   => 'include/javascript/sugar_grp_yui_widgets.js',
            ),

			$sugar_grp_yui_widgets_css = array(
				"include/javascript/yui/build/fonts/fonts-min.css" => 'include/javascript/sugar_grp_yui_widgets.css',
				"include/javascript/yui/build/treeview/assets/skins/sam/treeview.css"
					=> 'include/javascript/sugar_grp_yui_widgets.css',
				"include/javascript/yui/build/datatable/assets/skins/sam/datatable.css"
					=> 'include/javascript/sugar_grp_yui_widgets.css',
				"include/javascript/yui/build/container/assets/skins/sam/container.css"
					=> 'include/javascript/sugar_grp_yui_widgets.css',
                "include/javascript/yui/build/button/assets/skins/sam/button.css"
					=> 'include/javascript/sugar_grp_yui_widgets.css',
				"include/javascript/yui/build/calendar/assets/skins/sam/calendar.css"
					=> 'include/javascript/sugar_grp_yui_widgets.css',
			),

            $sugar_grp_yui2 = array(
            //YUI combination 2
            'include/javascript/yui/build/dragdrop/dragdrop-min.js'    => 'include/javascript/sugar_grp_yui2.js',
            'include/javascript/yui/build/container/container-min.js'  => 'include/javascript/sugar_grp_yui2.js',
            ),

            //Grouping for emails module.
            $sugar_grp_emails = array(
            'include/javascript/yui/ygDDList.js' => 'include/javascript/sugar_grp_emails.js',
            'include/SugarEmailAddress/SugarEmailAddress.js' => 'include/javascript/sugar_grp_emails.js',
            'include/SugarFields/Fields/Collection/SugarFieldCollection.js' => 'include/javascript/sugar_grp_emails.js',
            'include/SugarRouting/javascript/SugarRouting.js' => 'include/javascript/sugar_grp_emails.js',
            'include/SugarDependentDropdown/javascript/SugarDependentDropdown.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/InboundEmail/InboundEmail.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/EmailUIShared.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/EmailUI.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/EmailUICompose.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/ajax.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/grid.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/init.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/complexLayout.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/composeEmailTemplate.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/displayOneEmailTemplate.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/viewPrintable.js' => 'include/javascript/sugar_grp_emails.js',
            'include/javascript/quicksearch.js' => 'include/javascript/sugar_grp_emails.js',

            ),

            //Grouping for the quick compose functionality.
            $sugar_grp_quick_compose = array(
            'include/javascript/jsclass_base.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'include/javascript/jsclass_async.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'modules/Emails/javascript/vars.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'include/SugarFields/Fields/Collection/SugarFieldCollection.js' => 'include/javascript/sugar_grp_quickcomp.js', //For team selection
            'modules/Emails/javascript/EmailUIShared.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'modules/Emails/javascript/ajax.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'modules/Emails/javascript/grid.js' => 'include/javascript/sugar_grp_quickcomp.js', //For address book
            'modules/Emails/javascript/EmailUICompose.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'modules/Emails/javascript/composeEmailTemplate.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'modules/Emails/javascript/complexLayout.js' => 'include/javascript/sugar_grp_quickcomp.js',
            ),

            $sugar_grp_jsolait = array(
                'include/javascript/jsclass_base.js'    => 'include/javascript/sugar_grp_jsolait.js',
                'include/javascript/jsclass_async.js'   => 'include/javascript/sugar_grp_jsolait.js',
                'modules/Meetings/jsclass_scheduler.js'   => 'include/javascript/sugar_grp_jsolait.js',
            ),
            $sugar_grp_portal2 = array_merge(
                array('sidecar/lib/jquery/jquery.placeholder.min.js' => 'portal2/portal.min.js'), // preserve ordering
                getSubgroupForTarget('bootstrap', 'portal2/portal.min.js'),
                array(
                    'portal2/error.js'               => 'portal2/portal.min.js',
                    'portal2/user.js'                => 'portal2/portal.min.js',
                    'portal2/portal.js'              => 'portal2/portal.min.js',
                    'portal2/portal-ui.js'           => 'portal2/portal.min.js',
                    'include/javascript/jquery/jquery.popoverext.js'           => 'portal2/portal.min.js',
                    'include/javascript/jquery/jquery.effects.custombounce.js'           => 'portal2/portal.min.js',
                )
            ),
        );

    // groupings for sidecar forecast
    // use sidecar/src/include-manifest.php file to define what files should be loaded
    // exclude lib/jquery/jquery.min.js b/s jquery is loaded and extended with sugar_grp1_jquery.js
    $sidecar_forecasts = array();
    $cached_file = 'include/javascript/sidecar_forecasts.js';

    $sidecar_forecasts = array();
    $sidecar_forecasts = array_merge($sidecar_forecasts, getSubgroupForTarget('bootstrap_forecasts', $cached_file));
    $sidecar_forecasts['include/javascript/sugarAuthStore.js'] = $cached_file;
    $sidecar_forecasts['include/SugarCharts/Jit/js/Jit/jit.js'] = $cached_file;
    $sidecar_forecasts['include/SugarCharts/Jit/js/sugarCharts.js'] = $cached_file;
    $sidecar_forecasts['modules/Forecasts/clients/base/helper/hbt-helpers.js'] = $cached_file;
    $sidecar_forecasts['modules/Forecasts/clients/base/lib/ForecastsUtils.js'] = $cached_file;
    $sidecar_forecasts['modules/Forecasts/clients/base/lib/error.js'] = $cached_file;
    $sidecar_forecasts['modules/Forecasts/tpls/SidecarView.js'] = $cached_file;
    // Forecast and portal2 should include same styleguide bootstrap files
    $sidecar_forecasts['include/javascript/jquery/jquery.nouislider.js'] = $cached_file;

    $js_groupings[] = $sidecar_forecasts;

    /**
     * Check for custom additions to this code
     */

    if(!class_exists('SugarAutoLoader')) {
        // This block is required because this file could be called from a non-entrypoint (such as jssource/minify.php).
        require_once('include/utils/autoloader.php');
        SugarAutoLoader::init();
    }

    foreach(SugarAutoLoader::existing("custom/jssource/JSGroupings.php", SugarAutoLoader::loadExtension("jsgroupings")) as $file) {
        require $file;
    }

