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




SUGAR.ajaxUI = {
    loadingWindow : false,
    callback : function(o)
    {
        var cont;
        if (typeof window.onbeforeunload == "function")
            window.onbeforeunload = null;
        if (typeof scroll == "function")
            scroll(0,0);
        SUGAR.forms.AssignmentHandler.reset();
        try{
            var r = YAHOO.lang.JSON.parse(o.responseText);
            cont = r.content;

            if (r.title)
            {
                document.title = html_entity_decode(r.title);
            }
            if (r.action)
            {
                action_sugar_grp1 = r.action;
            }
            if (r.favicon)
            {
                SUGAR.ajaxUI.setFavicon(r.favicon);
            }

            var c = document.getElementById("content");
            // Bug #49205 : Subpanels fail to load when selecting subpanel tab
            // hide content of placeholder before apply new one
            // @see SUGAR.util.evalScript
            c.style.visibility = 'hidden';
            c.innerHTML = cont;
            SUGAR.util.evalScript(cont);
            // all javascripts have been processed - show content of placeholder
            c.style.visibility = 'visible';

            if ( r.moduleList)
            {
                SUGAR.themes.setModuleTabs(r.moduleList);
            }

            if (r.menu)
            {
                SUGAR.themes.setCurrentTab(r.menu);
            }
            if (r.record)
            {
                DCMenu.record = r.record;
            }
            if(r.menu && r.menu.module)
            {
                DCMenu.module = r.menu.module;

                // Fix the help link
                // Bug50676 - This can only be run when we have the module around
                var hl = $("#help");
                if (hl.length > 0) {
                    hl.find('a').each(function()
                        {
                            this.href = this.href.replace(new RegExp("help_action=([^\&]*)"), 'help_action=' + action_sugar_grp1)
                                .replace(new RegExp("help_module=([^\&]*)"), 'help_module=' + r.menu.module);
                        }
                    );
                    var label = (r.menu.label) ? r.menu.label: r.menu.module;
                    hl.find('span.title').text(label + ' ' + SUGAR.language.get('app_strings','LNK_HELP'));
                }
            }

            // set response time from ajax response
            if(typeof(r.responseTime) != 'undefined'){
                var rt = $("#responseTime");
                if(rt.length > 0){
                    rt.html(rt.html().replace(/[\d]+\.[\d]+/, r.responseTime));
                }
                else if(typeof(logoStats) != "undefined"){
                	$("#logo").attr("title", logoStats.replace(/[\d]+\.[\d]+/, r.responseTime)).tipTip({maxWidth: "auto", edgeOffset: 10});
                }
            }
            // Bug #49205 : Subpanels fail to load when selecting subpanel tab
            // hide ajax loading message after all scripts are processed
            SUGAR.ajaxUI.hideLoadingPanel();
        } catch (e){
            // Bug #49205 : Subpanels fail to load when selecting subpanel tab
            // hide ajax loading message after all scripts are processed
            SUGAR.ajaxUI.hideLoadingPanel();
            SUGAR.ajaxUI.showErrorMessage(o.responseText);
        }
        SUGAR_callsInProgress--;
    },
    showErrorMessage : function(errorMessage)
    {
        if (!SUGAR.ajaxUI.errorPanel) {
                SUGAR.ajaxUI.errorPanel = new YAHOO.widget.Panel("ajaxUIErrorPanel", {
                    modal: false,
                    visible: true,
                    constraintoviewport: true,
                    width	: "800px",
                    height : "600px",
                    close: true
                });
            }
            var panel = SUGAR.ajaxUI.errorPanel;
            panel.setHeader(SUGAR.language.get('app_strings','ERR_AJAX_LOAD')) ;
            panel.setBody('<iframe id="ajaxErrorFrame" style="width:780px;height:550px;border:none;marginheight="0" marginwidth="0" frameborder="0""></iframe>');
            panel.setFooter(SUGAR.language.get('app_strings','ERR_AJAX_LOAD_FOOTER')) ;
            panel.render(document.body);
            SUGAR.util.doWhen(
				function(){
					var f = document.getElementById("ajaxErrorFrame");
					return f != null && f.contentWindow != null && f.contentWindow.document != null;
				}, function(){
					document.getElementById("ajaxErrorFrame").contentWindow.document.body.innerHTML = errorMessage;
					window.setTimeout('throw "AjaxUI error parsing response"', 300);
			});

            //fire off a delayed check to make sure error message was rendered.
            SUGAR.ajaxUI.errorMessage = errorMessage;
            window.setTimeout('if((typeof(document.getElementById("ajaxErrorFrame")) == "undefined" || typeof(document.getElementById("ajaxErrorFrame")) == null  || document.getElementById("ajaxErrorFrame").contentWindow.document.body.innerHTML == "")){document.getElementById("ajaxErrorFrame").contentWindow.document.body.innerHTML=SUGAR.ajaxUI.errorMessage;}',3000);

            panel.show();
            panel.center();

            throw "AjaxUI error parsing response";
    },
    canAjaxLoadModule : function(module)
    {
        var checkLS = /&LicState=check/.exec(window.location.search);

        // Return false if ajax ui is completely disabled, or if license state is set to check
        if( checkLS || (typeof(SUGAR.config.disableAjaxUI) != 'undefined' && SUGAR.config.disableAjaxUI == true)){
            return false;
        }
        
        var bannedModules = SUGAR.config.stockAjaxBannedModules;
        //If banned modules isn't there, we are probably on a page that isn't ajaxUI compatible
        if (typeof(bannedModules) == 'undefined')
            return false;
        // Mechanism to allow for overriding or adding to this list
        if(typeof(SUGAR.config.addAjaxBannedModules) != 'undefined'){
            bannedModules.concat(SUGAR.config.addAjaxBannedModules);
        }
        if(typeof(SUGAR.config.overrideAjaxBannedModules) != 'undefined'){
            bannedModules = SUGAR.config.overrideAjaxBannedModules;
        }
        
        return SUGAR.util.arrayIndexOf(bannedModules, module) == -1;
    },

    loadContent : function(url, params)
    {
        if(YAHOO.lang.trim(url) != "")
        {
            //Don't ajax load certain modules
            var mRegex = /module=([^&]*)/.exec(url);
            var module = mRegex ? mRegex[1] : false;
            if (module && SUGAR.ajaxUI.canAjaxLoadModule(module))
            {
                YAHOO.util.History.navigate('ajaxUILoc',  url);
            } else {
                window.location = url;
            }
        }
    },

    go : function(url)
    {
        if(YAHOO.lang.trim(url) != "")
        {
            var con = YAHOO.util.Connect, ui = SUGAR.ajaxUI;
            if (ui.lastURL == url)
                return;
            var inAjaxUI = /action=ajaxui/.exec(window.location.href);
            if (typeof (window.onbeforeunload) == "function" && window.onbeforeunload())
            {
                //If there is an unload function, we need to check it ourselves
                if (!confirm(window.onbeforeunload()))
                {
                    if (!inAjaxUI)
                    {
                        //User doesn't want to navigate
                        window.location.hash = "";
                    }
                    else
                    {
                        YAHOO.util.History.navigate('ajaxUILoc',  ui.lastURL);
                    }
                    return;
                }
                window.onbeforeunload = null;
            }
            if (ui.lastCall && con.isCallInProgress(ui.lastCall)) {
                con.abort(ui.lastCall);
            }
            var mRegex = /module=([^&]*)/.exec(url);
            var module = mRegex ? mRegex[1] : false;
            //If we can't ajax load the module (blacklisted), set the URL directly.
            if (!ui.canAjaxLoadModule(module)) {
                window.location = url;
                return;
            }
            ui.lastURL = url;
            ui.cleanGlobals();
            var loadLanguageJS = '';
            if(module && typeof(SUGAR.language.languages[module]) == 'undefined'){
                loadLanguageJS = '&loadLanguageJS=1';
            }

            if (!inAjaxUI) {
                //If we aren't in the ajaxUI yet, we need to reload the page to get setup properly
                if (!SUGAR.isIE)
                    window.location.replace("index.php?action=ajaxui#ajaxUILoc=" + encodeURIComponent(url));
                else {
                    //if we use replace under IE, it will cache the page as the replaced version and thus no longer load the previous page.
                    window.location.hash = "#";
                    window.location.assign("index.php?action=ajaxui#ajaxUILoc=" + encodeURIComponent(url));
                }
            }
            else {
                SUGAR_callsInProgress++;
                SUGAR.ajaxUI.showLoadingPanel();
                ui.lastCall = YAHOO.util.Connect.asyncRequest('GET', url + '&ajax_load=1' + loadLanguageJS, {
                    success: SUGAR.ajaxUI.callback,
                    failure: function(){
                        SUGAR_callsInProgress--;
                        SUGAR.ajaxUI.hideLoadingPanel();
                        SUGAR.ajaxUI.showErrorMessage(SUGAR.language.get('app_strings','ERR_AJAX_LOAD_FAILURE'));
                    }
                });
            }
        }
    },

    submitForm : function(formname, params)
    {
        var con = YAHOO.util.Connect, SA = SUGAR.ajaxUI;
        if (SA.lastCall && con.isCallInProgress(SA.lastCall)) {
            con.abort(SA.lastCall);
        }
        //Reset the EmailAddressWidget before loading a new page
        SA.cleanGlobals();
        //Don't ajax load certain modules
        var form = YAHOO.util.Dom.get(formname) || document.forms[formname];
        if (SA.canAjaxLoadModule(form.module.value)
            //Do not try to submit a form that contains a file input via ajax.
            && typeof(YAHOO.util.Selector.query("input[type=file]", form)[0]) == "undefined"
            //Do not try to ajax submit a form if the ajaxUI is not initialized
            && /action=ajaxui/.exec(window.location.href))
        {
            var string = con.setForm(form);
            var baseUrl = "index.php?action=ajaxui#ajaxUILoc=";
            SA.lastURL = "";
            //Use POST for long forms and GET for short forms (GET allow resubmit via reload)
            if(string.length > 200)
            {
                SUGAR.ajaxUI.showLoadingPanel();
                form.onsubmit = function(){ return true; };
                form.submit();
            } else {
                con.resetFormState();
                window.location = baseUrl + encodeURIComponent("index.php?" + string);
            }
            return true;
        } else {

            if( typeof(YAHOO.util.Selector.query("input[type=submit]", form)[0]) != "undefined"
                    && YAHOO.util.Selector.query("input[type=submit]", form)[0].value == "Save")
            {
                ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVING'));
            }

            form.submit();
            return false;
        }
    },

    cleanGlobals : function()
    {
        sqs_objects = {};
        QSProcessedFieldsArray = {};
        collection = {};
        //Reset the EmailAddressWidget before loading a new page
        if (SUGAR.EmailAddressWidget){
            SUGAR.EmailAddressWidget.instances = {};
            SUGAR.EmailAddressWidget.count = {};
        }
        YAHOO.util.Event.removeListener(window, 'resize');
        //Hide any connector dialogs
        if(typeof(dialog) != 'undefined' && typeof(dialog.destroy) == 'function'){
            dialog.destroy();
            delete dialog;
        }

    },
    firstLoad : function()
    {
        //Setup Browser History
        var url = YAHOO.util.History.getBookmarkedState('ajaxUILoc');
        var aRegex = /action=([^&#]*)/.exec(window.location.href);
        var action = aRegex ? aRegex[1] : false;
        var mRegex = /module=([^&#]*)/.exec(window.location.href);
        var module = mRegex ? mRegex[1] : false;
        if (module != "ModuleBuilder")
        {
            var go = url != null || action == "ajaxui";
            url = url ? url : 'index.php?module=Home&action=index';
            YAHOO.util.History.register('ajaxUILoc', url, SUGAR.ajaxUI.go);
            YAHOO.util.History.initialize("ajaxUI-history-field", "ajaxUI-history-iframe");
            SUGAR.ajaxUI.hist_loaded = true;
            if (go)
                SUGAR.ajaxUI.go(url);
        }
        SUGAR_callsInProgress--;
    },
    print: function()
    {
        var url = YAHOO.util.History.getBookmarkedState('ajaxUILoc');
        SUGAR.util.openWindow(
            url + '&print=true',
            'printwin',
            'menubar=1,status=0,resizable=1,scrollbars=1,toolbar=0,location=1'
        );
    },
    showLoadingPanel: function()
    {
        if (!SUGAR.ajaxUI.loadingPanel)
        {
            SUGAR.ajaxUI.loadingPanel = new YAHOO.widget.Panel("ajaxloading",
            {
                width:"240px",
                fixedcenter:true,
                close:false,
                draggable:false,
                constraintoviewport:false,
                modal:true,
                visible:false
            });
            SUGAR.ajaxUI.loadingPanel.setBody('<div id="loadingPage" align="center" style="vertical-align:middle;"><img src="' + SUGAR.themes.loading_image + '" align="absmiddle" /> <b>' + SUGAR.language.get('app_strings', 'LBL_LOADING_PAGE') +'</b></div>');
            SUGAR.ajaxUI.loadingPanel.render(document.body);
        }

        if (document.getElementById('ajaxloading_c'))
            document.getElementById('ajaxloading_c').style.display = '';
        
        SUGAR.ajaxUI.loadingPanel.show();

    },
    hideLoadingPanel: function()
    {
        SUGAR.ajaxUI.loadingPanel.hide();
        
        if (document.getElementById('ajaxloading_c'))
            document.getElementById('ajaxloading_c').style.display = 'none';
    },
    setFavicon: function(data)
    {
        var head = document.getElementsByTagName("head")[0];

        // first remove all rel="icon" links as long as updating an existing one
        // could take no effect
        var links = head.getElementsByTagName("link");
        var re = /\bicon\b/i;
        for (var i = 0; i < links.length; i++)        {
            if (re.test(links[i].rel))
            {
                head.removeChild(links[i]);
            }
        }

        var link = document.createElement("link");

        link.href = data.url;
        // type attribute is important for Google Chrome browser
        link.type = data.type;
        link.rel = "icon";
        head.appendChild(link);
    }
};
