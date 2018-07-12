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

/**
 * View that displays a list of models pulled from the context's collection.
 * @class View.Views.TreeView
 * @alias SUGAR.App.layout.TreeView
 * @extends View.View
 */
({

    jsTree:{},
    reporteesEndpoint:'',
    currentTreeUrl:'',
    currentRootId:'',

    selectedUser: {},

    /**
     * Initialize the View
     *
     * @constructor
     * @param {Object} options
     */
    initialize:function (options) {
        app.view.View.prototype.initialize.call(this, options);

        this.reporteesEndpoint = app.api.serverUrl + "/Forecasts/reportees/";
        this.currentTreeUrl = this.reporteesEndpoint + app.user.get('id');
        this.currentRootId = app.user.get('id');

        this.selectedUser = app.user.toJSON();
    },

    /**
     * Only run the render if the user is a manager as that is the only time we want the tree to display.
     */
    render:function () {
        if(app.user.get('isManager')) {
            app.view.View.prototype.render.call(this);
        }
    },

    /**
     * Clean up any left over bound data to our context
     */
    unbindData : function() {
        if(this.context.forecasts) this.context.forecasts.off(null, null, this);
        app.view.View.prototype.unbindData.call(this);
    },

    /**
     * Handles if data changes on the context.forecasts.tree model (and when tree first initializes)
     * Tree is not handled like other components on Forecasts as it uses a 3rdParty lib (jstree)
     * to handle it's data model. The event handler is added here only because bindDataChange is
     * where other event handlers are added in other models and it fires once during initialization
     */
    bindDataChange:function () {
        if (this.context.forecasts) {
            this.context.forecasts.on("change:selectedUser", this.checkRender, this);
        }
    },

    /**
     * Function to give a final check before rendering to see if we really need to render
     * Any time the selectedUser changes on context.forecasts we run through this function to
     * see if we should render the tree again
     *
     * @param context
     * @param selectedUser {Object} the current selectedUser on the context
     */
    checkRender:function (context, selectedUser) {
        // handle the case for user clicking MyOpportunities first
        this.selectedUser = selectedUser;
        if (selectedUser.showOpps) {
            var nodeId = (selectedUser.isManager ? 'jstree_node_myopps_' : 'jstree_node_') + selectedUser.user_name;
            this.selectJSTreeNode(nodeId)
            // check before render if we're trying to re-render tree with a fresh root user
            // otherwise do not re-render tree
            // also make sure we're not re-rendering tree for a rep
        } else if (this.currentRootId != selectedUser.id) {
            if (selectedUser.isManager) {
                // if user is a manager we'll be re-rendering the tree
                // no need to re-render the tree if not a manager because the dataset
                // stays the same

                this.currentRootId = selectedUser.id;
                this.currentTreeUrl = this.reporteesEndpoint + selectedUser.id;
                this.rendered = false;
                this.render();
            } else {
                // user is not a manager but if this event is coming from the worksheets
                // we need to "select" the user on the tree to show they're selected

                // create node ID
                var nodeId = 'jstree_node_' + selectedUser.user_name;

                // select node only if it is not the already selected node
                if (this.jsTree.jstree('get_selected').attr('id') != nodeId) {
                    this.selectJSTreeNode(nodeId)
                }
            }
        }
    },

    /**
     * Function that handles deselecting any selected nodes then selects the nodeId
     *
     * @param nodeId {String} the node id starting with "jstree_node_"
     */
    selectJSTreeNode:function (nodeId) {
        // jstree kept trying to hold on to the root node css staying selected when
        // user clicked a user's name from the worksheet, so explicitly causing a deselection
        this.jsTree.jstree('deselect_all');

        this.jsTree.jstree('select_node', '#' + nodeId);
    },


    /**
     * Recursively step through the tree and for each node representing a tree node, run the data attribute through
     * the replaceHTMLChars function.  This function supports n-levels of the tree hierarchy.
     *
     * @param data The data structure returned from the REST API Forecasts/reportees endpoint
     * @param self A reference to the view's context so that we may recursively call _recursiveReplaceHTMLChars
     * @return The modified data structure after all the parent and children nodes have been stepped through
     * @private
     */
    _recursiveReplaceHTMLChars:function (data, self) {
        _.each(data, function (entry, index) {

            //Scan for the nodes with the data attribute.  These are the nodes we are interested in
            if (entry.data) {
                data[index].data = replaceHTMLChars(entry.data);

                if (entry.children) {
                    //For each children found (if any) then call _recursiveReplaceHTMLChars again.  Notice setting
                    //childEntry to an Array.  This is crucial so that the beginning _.each loop runs correctly.
                    _.each(entry.children, function (childEntry, index2) {
                        entry.children[index2] = self._recursiveReplaceHTMLChars([childEntry]);
                        if(childEntry.metadata.id == this.selectedUser.id) {
                            childEntry.data = app.utils.formatString(app.lang.get('LBL_MY_OPPORTUNITIES', 'Forecasts'), [childEntry.data]);
                        }
                    }, this);
                }
            }
        }, this);

        return data;
    },

    /**
     * Renders JSTree
     * @param ctx
     * @param options
     * @protected
     */
    _renderHtml:function (ctx, options) {
        app.view.View.prototype._renderHtml.call(this, ctx, options);

        var self = this;
        var treeData;

        app.api.call('read', self.currentTreeUrl, null, {
            success:function (data) {
                // make sure we're using an array
                // if the data coming from the endpoint is an array with one element
                // it gets converted to a JS object in the process of getting here
                if (!jQuery.isArray(data)) {
                    data = [ data ];
                }

                treeData = self._recursiveReplaceHTMLChars(data, self);

                self.jsTree = $(".jstree-sugar").jstree({
                    "plugins":["json_data", "ui", "crrm", "types", "themes"],
                    "json_data":{
                        "data":treeData
                    },
                    "ui":{
                        // when the tree re-renders, initially select the root node
                        "initially_select":[ 'jstree_node_' + self.context.forecasts.get('selectedUser').user_name ]
                    },
                    "types":{
                        "types":{
                            "types":{
                                "parent_link":{},
                                "manager":{},
                                "my_opportunities":{},
                                "rep":{},
                                "root":{}
                            }
                        }
                    }
                }).on("select_node.jstree", function (event, data) {
                        var jsData = data.inst.get_json();
                        var nodeType = jsData[0].attr.rel;
                        var userData = jsData[0].metadata;
                        var contextUser = self.context.forecasts.get("selectedUser");

                        var showOpps = false;

                        // if user clicked on a "My Opportunities" node
                        // set this flag true
                        if (nodeType == "my_opportunities" || nodeType == "rep") {
                            showOpps = true
                        }

                        var selectedUser = {
                            'id':userData.id,
                            'user_name':userData.user_name,
                            'full_name':userData.full_name,
                            'first_name':userData.first_name,
                            'last_name':userData.last_name,
                            'isManager':(nodeType != 'rep'),
                            'showOpps':showOpps
                        };

                        // update context with selected user which will trigger checkRender
                        self.context.forecasts.set("selectedUser", selectedUser);
                    });

                if (treeData) {
                    var showTree = false;
                    var rootId = -1;

                    if (treeData.length == 1) {
                        // this case appears when "Parent" is not present

                        rootId = treeData[0].metadata.id;

                        if (treeData[0].children.length > 0) {
                            showTree = true;
                        }
                    }
                    else if (treeData.length == 2) {
                        // this case appears with a "Parent" link label in the return set

                        // treeData[0] is the Parent link, treeData[1] is our root user node
                        rootId = treeData[1].metadata.id;
                    }

                    self.currentRootId = rootId;
                }

                // add proper class onto the tree
                $("#people").addClass("jstree-sugar");
            }
        });
    }
})
