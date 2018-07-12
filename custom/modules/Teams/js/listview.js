var setting = {
    view: {
        addHoverDom: addHoverDom,
        removeHoverDom: removeHoverDom,
        selectedMulti: false,
        dblClickExpand: false,
    },
    data: {
        simpleData: {
            enable: true,
            rootPId: '1'
        }
    },
    callback: {
        beforeClick: function(treeId, treeNode) {
            var is_adding = $("#is_adding").val();
            //            if(treeNode.id == '1') return false;
            if(is_adding == '1'){
                return false;
            }else{
                ajaxGetTeamInfo(treeNode);
            }
        },
    },
};
var newCount = 1;
function addHoverDom(treeId, treeNode){
    var sObj = $("#" + treeNode.tId + "_span");
    // da loai bo dieu kien  !treeNode.editNameFlag
    if ( $("#addBtn_"+treeNode.tId).length>0 ) return;
    var addStr = "<span id='addBtn_" + treeNode.tId + "' title='"+SUGAR.language.get('Teams','LBL_TITLE_ADD_CHILD')+"' onfocus='this.blur();'><i class='icon icon-plus'></i></span>";
    sObj.after(addStr);

    var btn = $("#addBtn_"+treeNode.tId);
    if (btn) btn.bind("click", function(){
        var is_adding = $("#is_adding").val();
        if(is_adding == '1') return false;

        var zTree = $.fn.zTree.getZTreeObj("teamNodes");
        zTree.addNodes(treeNode, {id:'', pId:treeNode.id, isParent:true, name:"New Team " + (newCount)});
        //        treeNode.editNameFlag = true;
        $("#is_adding").val('1');
        $('#is_editing').val('1');
        var newNode = zTree.getNodeByParam('id', '');
        zTree.selectNode(newNode, true);
        ajaxGetTeamInfo(newNode);

        newCount++;
        return false;
        });
};

function removeHoverDom(treeId, treeNode) {
    $("#addBtn_"+treeNode.tId).remove();
};

function expandNode(e) {
    var zTree = $.fn.zTree.getZTreeObj("teamNodes"),
    type = e.data.type;
    var globalNode = zTree.getNodeByParam('id', '1');
    if (type == "expandAll") {
        zTree.expandAll(true);
        $('#collapse_all').show();
        $('#expand_all').hide();
    } else if (type == "collapseAll") {
        var zTree = $.fn.zTree.getZTreeObj("teamNodes");
        var arr_child = zTree.transformToArray(globalNode);
        $.each( arr_child, function( key, team ) {
            if(arr_child[key].id != '1')
                zTree.expandNode(arr_child[key], false, true, null, 0);
        });

        $('#expand_all').show();
        $('#collapse_all').hide();
    }
}      

//Show/Hide fields 
function toggle_team(){    
    var is_editing = $('#is_editing').val();           
    var zTree = $.fn.zTree.getZTreeObj("teamNodes");
    nodes = zTree.getSelectedNodes();
    selectedNode = nodes[0];
    if(is_editing == '1'){
        $('.team_edit').show(); 
        $('.team_detail').hide();     
        $('#panel_user').hide();  
        $('#delete_bt').hide();
        //Add Sugar Validation
        delete validate['TeamEdit'];
        addToValidate('TeamEdit', 'team_name', 'text', true, SUGAR.language.get('Teams','LBL_NAME') );
        addToValidate('TeamEdit', 'prefix', 'text', true, SUGAR.language.get('Teams','LBL_PREFIX') );
        if(selectedNode.id == '1'){
            $("#parent_name").prop('disabled', true);
            $('#bt_select_parent, #bt_clear_parent').hide();

        }else{
            $("#parent_name").prop('disabled', false);
            $('#bt_select_parent, #bt_clear_parent').show();

            addToValidateBinaryDependency('TeamEdit', 'parent_name', 'alpha', true, SUGAR.language.get('app_strings', 'ERR_SQS_NO_MATCH_FIELD') + SUGAR.language.get('Teams','LBL_PARENT_ID') , 'parent_id' );     
        }    
        if($('#team_id').val() == "defaultteam"){
            $('.team_edit').hide(); 
            $('.team_detail').show(); 
            $('#team_order').show();
            $('#team_order_text').hide();            
            $('#save_bt').show();            
            $('#cancel_bt').show();            
            $('#edit_bt').hide(); 
            $('#bt_select_parent, #bt_clear_parent').hide();            
        }
    }else{
        $('.team_edit').hide(); 
        $('.team_detail').show();     
        $('#panel_user').show();
        if(selectedNode.check_Child_State >= 0)
            $('#delete_bt').hide();
        else
            $('#delete_bt').show();

        $('#team_order_text').closest('tr').show();
    }
}

function removeNewTeam(){
    var zTree = $.fn.zTree.getZTreeObj("teamNodes");
    var newNode = zTree.getNodeByParam('id', '');
    if(!confirm("'"+$('#team_name').val()+"'"+SUGAR.language.get('Teams','LBL_ALERT_CANCEL'))) return false;
    //Remove new Note
    var parentNode = newNode.getParentNode();
    //    parentNode.editNameFlag = false;
    zTree.removeNode(newNode,true);

    //load Parent Node
    zTree.selectNode(parentNode, true);
    ajaxGetTeamInfo(parentNode);
    $('#is_editing').val('0');
    $('#is_adding').val('0');
}

//Ajax get TeamInfo
function ajaxGetTeamInfo(teamNode){
    $('#team_id').val(teamNode.id);
    $('#current_parent_id').val(teamNode.pId);
    if(teamNode.id != ''){
        ajaxStatus.showStatus(SUGAR.language.get('Teams','LBL_LOADING'));
        $.ajax({                                                                    
            url: "index.php?module=Teams&action=handleajax&sugar_body_only=true",
            type: "POST",
            async: true,
            data:
            {                         
                type            : "ajaxGetDetail",    
                team_id         : teamNode.id,  
            },
            dataType: "json",
            success: function(data){
                if(data.success == "1"){
                    $('#panel_user').show();             
                    $('div#table_user').html(data.html); 
                    $('#team_name_text').text(data.team_name);
                    $('#team_name').val(data.team_name);
                    $('#short_name_text').text(data.short_name);
                    $('#short_name').val(data.short_name);
                    $('#prefix_text').text(data.prefix);
                    $('#prefix').val(data.prefix);
                    $('#team_type_text').text(data.type);
                    $('#team_type').val(data.type);
                    $('#phone_number_text').text(data.phone_number);
                    $('#phone_number').val(data.phone_number);
                    $('#parent_name_text').text(data.parent_name);
                    $('#parent_name').val(data.parent_name);
                    $('#parent_id').val(data.parent_id);
                    $('#description_text').text(data.description);
                    $('#description').val(data.description);  
                    $('#team_order').val(data.team_order);
                    $('#team_order_text').text(data.team_order);  
                }
                toggle_team();
                ajaxStatus.hideStatus();
                getUserTable(teamNode.id); 
            },
        });
    }else{
        $('div#table_user').html('');
        $('#team_name').val(teamNode.name);
        $('#parent_id').val(teamNode.pId);
        $('#parent_name').val(teamNode.getParentNode().name);
        $('#description').text('');  
        toggle_team();
    }
}                                                                           

function addUserToTeam(popup_reply_data){
    var users_list = popup_reply_data.name_to_value_array;
    if(typeof(users_list)  === "undefined") 
        users_list = popup_reply_data.selection_list;
    var team_id = $('#team_id').val();
    ajaxStatus.showStatus(SUGAR.language.get('Teams','LBL_LOADING'));

    //get child List
    var zTree = $.fn.zTree.getZTreeObj("teamNodes");
    var node = zTree.getSelectedNodes();
    var arr_child = zTree.transformToArray(node);
    var team_list = [];
    $.each( arr_child, function( key, team ) {
        team_list[key] = team.id;
    });

    $.ajax({
        url: "index.php?module=Teams&action=handleajax&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            type: 'ajaxAddUser',
            users_list: users_list,
            team_list: team_list,     
        },
        dataType: "json", 
        success: function(res){
            if (res.success == '1') {
                var team_id = $('#team_id').val();
                var zTree = $.fn.zTree.getZTreeObj("teamNodes");
                var teamNode = zTree.getNodeByParam('id', team_id);
                ajaxGetTeamInfo(teamNode);
            }else{
                alert(SUGAR.language.get('Teams','LBL_AJAX_ERR'));
            }
            ajaxStatus.hideStatus();   
        },       
    });

}

function removeUserFromTeam(user_id){
    ajaxStatus.showStatus(SUGAR.language.get('Teams','LBL_LOADING'));

    //get child List
    var zTree = $.fn.zTree.getZTreeObj("teamNodes");
    var node = zTree.getSelectedNodes();
    var arr_child = zTree.transformToArray(node);
    var team_list = [];
    $.each( arr_child, function( key, team ) {
        team_list[key] = team.id;
    });

    $.ajax({
        url: "index.php?module=Teams&action=handleajax&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  {
            type: 'ajaxRemoveUser',
            user_id: user_id,
            team_list: team_list,  
        },
        dataType: "json", 
        success: function(res){
            if (res.success == '1') {
                var table = $('#celebs').DataTable();
                var rows = table.rows('#'+user_id).remove().draw();  
            }else{
                alert(SUGAR.language.get('Teams','LBL_AJAX_ERR'));
            }  
            ajaxStatus.hideStatus();                        
        },       
    });   
}

function ajaxUpdateTeam(action, team_id){
    var copy_user_flag = false;
    //validation and moving team 
    if($('#team_id').val() != '1'){     
        var zTree = $.fn.zTree.getZTreeObj("teamNodes");
        var node = zTree.getSelectedNodes();
        var team_list = zTree.transformToArray(node);
        var arr_child = [];
        $.each( team_list, function( key, team ) {
            arr_child[key] = team.id;
        });
        if(arr_child.indexOf($('#parent_id').val()) != '-1'){
            alert(SUGAR.language.get('Teams','LBL_ALERT_UPDATE'));
            return false;  
        }
        if($('#parent_id').val() != $('#current_parent_id').val()){
            copy_user_flag = true;
        }
    }  
    ajaxStatus.showStatus(SUGAR.language.get('Teams','LBL_LOADING'));
    $.ajax({                                                                 
        url:'index.php?module=Teams&action=handleajax&sugar_body_only=true',
        type: "POST",
        data:  {
            type            : 'ajaxUpdateTeam',         
            copy_user_flag  : copy_user_flag, 
            team_id         : $('#team_id').val(),        
            team_name       : $('#team_name').val(),        
            short_name      : $('#short_name').val(),        
            prefix          : $('#prefix').val(),        
            phone_number    : $('#phone_number').val(),        
            parent_id       : $('#parent_id').val(),        
            parent_name     : $('#parent_name').val(),        
            team_order      : $('#team_order').val(),        
            description     : $('#description').val(),        
        },               
        dataType: "json",
        success: function(res){
            if(res.success == "1"){
                var zTree = $.fn.zTree.getZTreeObj("teamNodes");
                if(res.act == "save"){
                    $('#team_name_text').text(res.team_name);
                    $('#team_name').val(res.team_name);
                    $('#short_name_text').text(res.short_name);
                    $('#short_name').val(res.short_name);
                    $('#prefix_text').text(res.prefix);
                    $('#prefix').val(res.prefix);
                    $('#team_type_text').text(res.type);
                    $('#team_type').val(res.type);
                    $('#phone_number_text').text(res.phone_number);
                    $('#phone_number').val(res.phone_number);
                    $('#parent_name_text').text(res.parent_name);
                    $('#parent_name').val(res.parent_name);
                    $('#parent_id').val(res.parent_id);
                    $('#description_text').text(res.description);
                    $('#description').text(res.description);
                    $('#team_order').val(res.team_order);
                    $('#team_order_text').text(res.team_order);
                    $('#is_editing').val('0');
                    $('#is_adding').val('0');    
                    //rename node
                    if(res.call_back == 'update')
                        var node = zTree.getNodeByParam('id', res.team_id);
                    else if(res.call_back == 'create'){
                        var node = zTree.getNodeByParam('id', '');
                    }

                    //rename node
                    if(res.call_back == 'update')
                        var node = zTree.getNodeByParam('id', res.team_id);   
                    else if(res.call_back == 'create'){
                        var node = zTree.getNodeByParam('id', '');
                    }

                    node.name = res.team_name;
                    node.id = res.team_id;
                    node.pId = res.parent_id;
                    zTree.updateNode(node);
                    targetNode = zTree.getNodeByParam('id', $('#parent_id').val());
                    targetNode = zTree.moveNode(targetNode, node, "inner");
                    ajaxGetTeamInfo(node);
                }
                else{
                    alert(SUGAR.language.get('Teams','LBL_AJAX_ERR'));   
                }
                ajaxStatus.hideStatus();
            }else{
                alert(SUGAR.language.get('Teams','LBL_AJAX_ERR'));
            }
            ajaxStatus.hideStatus();
        },
        error: function(res){
            alert(SUGAR.language.get('Teams','LBL_AJAX_ERR'));
        }
    });
}

function ajaxDeleteTeam(team_id){
    ajaxStatus.showStatus(SUGAR.language.get('Teams','LBL_LOADING'));
    $.ajax({
        url:'index.php?module=Teams&action=handleajax&sugar_body_only=true',
        type: "POST",
        data:  {
            type            : 'ajaxDeleteTeam',
            team_id         : team_id,                      
        },    
        dataType: "json",
        success: function(res){
            if(res.success == "1"){
                var zTree = $.fn.zTree.getZTreeObj("teamNodes");   
                var node = zTree.getNodeByParam('id', $('#team_id').val());
                zTree.removeNode(node, true);
                var parentNode = node.getParentNode();      
                zTree.selectNode(parentNode, true);
                ajaxGetTeamInfo(parentNode);
            }
            else{
                alert(SUGAR.language.get('Teams','LBL_AJAX_ERR'));   
            }
            ajaxStatus.hideStatus();
        },
        error: function(res){
            alert(SUGAR.language.get('Teams','LBL_AJAX_ERR'));
        }
    });
}

function clickTeamNode(team_id){
    if(team_id != null && team_id != ''){
        var zTree       = $.fn.zTree.getZTreeObj("teamNodes");
        var targetNode  = zTree.getNodeByParam('id', team_id);
        zTree.selectNode(targetNode, true);
        ajaxGetTeamInfo(targetNode);
    }
}

function addRoleTeam(user_id, primary_team_id, roles, status){
    ajaxStatus.showStatus(SUGAR.language.get('Teams','LBL_LOADING'));
    $.ajax({
        url: "index.php?module=Teams&action=handleajax&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  {
            user_id: user_id,
            primary_team_id: primary_team_id,
            roles: roles,
            status: status,
            type: 'ajaxUpdateUser',
        },
        dataType: "json", 
        success: function(res){
            if (res.success == '1') {               
                setTimeout(function() {
                    ajaxStatus.hideStatus();
                    }, 1500);  
            }else{
                alert(SUGAR.language.get('Teams','LBL_AJAX_ERR'));
                ajaxStatus.hideStatus();  
            } 
        },       
    });
}

$(document).ready(function(){
    $.fn.zTree.init($("#teamNodes"), setting, treeNodes);
    var zTree =  $.fn.zTree.getZTreeObj("teamNodes");
    var globalNode = zTree.getNodeByParam('id', '1');
    zTree.selectNode(globalNode, true);
    ajaxGetTeamInfo(globalNode); 
    
    $('#edit_bt').live('click',function(){
        $('#is_editing').val('1');   
        toggle_team();   
    });

    $('#cancel_bt').live('click',function(){
        var is_adding = $('#is_adding').val();
        if(is_adding != '1'){
            //Show Detail
            $('#is_editing').val('0');
            toggle_team();
        }else{
            removeNewTeam();
        }
    });

    $('#save_bt').bind('click',function(){
        if(check_form('TeamEdit')){ //validate form
            ajaxUpdateTeam('save');
        }
    });

    $('#delete_bt').bind('click',function(){
        var team_id = $('#team_id').val();
        if(!confirm(SUGAR.language.get('Teams','LBL_CONFIRM_DELETE')+"'"+$('#team_name_text').text()+"' ?")) return false;
        ajaxDeleteTeam(team_id);
    });

    $('.remove_user').live('click',function(){
        if(!confirm(SUGAR.language.get('Teams','LBL_CONFIRM_REMOVE_USER'))) return false;
        var user_id = $(this).closest('tr').find('.user_id').val(); 
        removeUserFromTeam(user_id);
    });


    $('#add_user_bt').live('click',function(){
        open_popup("Users", 600, 400, "", true, true, {
            "call_back_function": "addUserToTeam",
            "form_name": "DetailView",
            "field_to_name_array": {
                "id": "user_id",
                "user_name": "user_name_2"
            },
            "passthru_data": {}}, "MultiSelect", true);
    });

    $('.save_user').live('click', function(){                      
        var roles           = $(this).closest('tr').find('.select_role').val();
        var primary_team_id = $(this).closest('tr').find('.select_team').val();
        var status          = $(this).closest('tr').find('.select_status').val();
        var user_id         = $(this).closest('tr').find('.user_id').val(); 
        addRoleTeam(user_id, primary_team_id, roles, status);
        $(this).closest('tr').find('.save_user').css("color", "#0b578f");
    });
    //Highlight row not save
    $('.select_role, .select_team, .select_status').live('change', function(){
        $(this).closest('tr').find('.save_user').css("color", "#DA0000");
    });

    $('#expand_all').hide();
    $("#expand_all").bind("click", {type:"expandAll"}, expandNode);
    $("#collapse_all").bind("click", {type:"collapseAll"}, expandNode);

    // Select Parent
    $('#bt_select_parent').click(function(){
        open_popup("Teams", 600, 400, '&private=0', true, false, {"call_back_function":"set_return","form_name":"TeamEdit","field_to_name_array":{"id":"parent_id","name":"parent_name",}});
    });
    $('#bt_clear_parent').click(function(){
        $('#parent_name, #parent_id').val('');
    });

});     

function getUserTable(team_id){      
    ajaxStatus.showStatus(SUGAR.language.get('Teams','LBL_LOADING'));  
    $('#celebs').dataTable({                                     
        "drawCallback": function( settings ) {
            $('.selectpicker').selectpicker();
            ajaxStatus.hideStatus();
        },                                                                 
        oLanguage:{
            sLengthMenu: SUGAR.language.get('app_strings','LBL_JDATATABLE1') + "_MENU_" + SUGAR.language.get('app_strings','LBL_JDATATABLE2'),
            sZeroRecords: SUGAR.language.get('app_strings','LBL_JDATATABLE13'),
            sInfo: SUGAR.language.get('app_strings','LBL_JDATATABLE6')+ "_START_" + SUGAR.language.get('app_strings','LBL_JDATATABLE7') + "_END_" + SUGAR.language.get('app_strings','LBL_JDATATABLE8') + "_TOTAL_" + SUGAR.language.get('app_strings','LBL_JDATATABLE2'), 
            sInfoEmpty: SUGAR.language.get('app_strings','LBL_JDATATABLE15'), 
            sEmptyTable: SUGAR.language.get('app_strings','LBL_JDATATABLE14'), 
            sInfoFiltered: '',
            oPaginate: {
                sFirst: SUGAR.language.get('app_strings','LBL_JDATATABLE9'), 
                sNext: SUGAR.language.get('app_strings','LBL_JDATATABLE10'),   
                sPrevious: SUGAR.language.get('app_strings','LBL_JDATATABLE11'), 
                sLast: SUGAR.language.get('app_strings','LBL_JDATATABLE12'), 
            },
            iTabIndex: 1,                                 
            sSearch: SUGAR.language.get('app_strings','LBL_JDATATABLE3'), 
            sProcessing: SUGAR.language.get('app_strings','LBL_JDATATABLE16'),
        },
        "processing": false,
        "serverSide": true,
        "ajax" : {
            url : "index.php?module=Teams&action=handleajax&sugar_body_only=true",
            type: "post", 
            data: {                                 
                'type'          : "ajaxGetTeamMembers", 
                'team_id'       : team_id,         
            },   
        },
        searchDelay: 500,
        "columnDefs": [ {
            "targets": "_all",           
            "render": function ( data, type, row, meta ) {      
                switch(data.custom) {
                    case "user_name":
                        return data.html;
                        break;
                    case "teams":
                        return data.html;
                        break;
                    case "roles":
                        return data.html;
                        break;
                    case "status":
                        return data.html;
                        break;
                    case "action":
                        return data.html;
                        break;
                    default:
                        return data;
                }
            }
        } ]
    })
    .on( 'processing.dt', function ( e, settings, processing ) {
        if(processing && !$("#celebs_filter").find("input").is(':focus')){
            ajaxStatus.showStatus(SUGAR.language.get('Teams','LBL_LOADING'));
        }
        else{
            ajaxStatus.hideStatus();
        }  
    });                                       
}  