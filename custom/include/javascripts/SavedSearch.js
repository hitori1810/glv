jQuery(document).ready(function(){
});
function ChangeUser(){       
    $('span#publish_saved_search_status').hide();
    $('span#availability_status_publish').hide();

    if ($("#user_id option:selected").size() == 0){
        jQuery("#btnPublishSavedSearch").hide();
    }
    else{     
        jQuery("#btnPublishSavedSearch").show();                               
    }                                           
}

function ChangeDepartment(){
    $('select#user_id + .ms-parent').hide();
    $('span#availability_status_user_id').show();
    $('span#publish_saved_search_status').hide();
    $('span#availability_status_publish').hide();
    jQuery.ajax({  //Make the Ajax Request
        type: "POST",
        //async: false,  
        url: "index.php?module=SavedSearch&action=getUserGroup&sugar_body_only=true",
        data: {
            department: jQuery("#department").val(),
        } ,

        success: function(data){  
            $('span#availability_status_user_id').hide();
            $('select#user_id').html(data);
            $('select#user_id').multipleSelect({
                selectAllText: 'All Users',
                filter: true,
                allSelected: false,
                minimumCountSelected: 1000000
            });
            $('select#user_id + .ms-parent').show();
        }  

    });//end Ajax
}

function CheckSavedSearchClick(){    
    if ($('#saved_search_select').closest('td').attr('colspan') == 5) {
        $('#saved_search_select').closest('td').attr('colspan', 6);
    }
    if ($('td.help').length > 0) {
        $('td.help').remove();
    }
    $('#btnPublishSavedSearch').hide();
    $('span#publish_saved_search_status').hide();
    $('span#availability_status_publish').hide();
    savedsearchpublish_chk = jQuery('#publish_saved_search_chk').is(':checked');
    if(savedsearchpublish_chk == false) {
        $('select#department + .ms-parent').hide();
        $('select#user_id + .ms-parent').hide();
        return;
    }
    //make Ajax request 
    $('select#department + .ms-parent').hide();
    $('select#user_id + .ms-parent').hide();
    $('span#availability_status_department').show();
    jQuery.ajax({  //Make the Ajax Request
        type: "POST",
        //async: false,  
        url: "index.php?module=SavedSearch&action=getGroups&sugar_body_only=true",

        success: function(data){
            $('span#availability_status_department').hide();
            $('select#department').html(data);
            $('select#department').multipleSelect({
                selectAll: false,
                filter: true,
                allSelected: false,
                minimumCountSelected: 1000000
            });
            $('select#department + .ms-parent').show();
        } 

    });//end Ajax


}


function PublishSavedSearch(){

    // check report option have checked
    savedsearchpublish_chk = jQuery('#publish_saved_search_chk').is(':checked');
    savedsearch_sel = jQuery('#saved_search_select').val();
    if(savedsearchpublish_chk == false || savedsearch_sel =='_none' ){
        alert(SUGAR.language.translate('SavedSearch','LBL_SAVED_SEARCH_NO_CHECK_OR_NONE_SEL'));
        jQuery('#saved_search_select').focus();
        return false;
    }else{
        var confirmSavedSearch = SUGAR.language.translate('','LBL_CONFIRM_PUBLISH_SAVED_SEARCH');
        var userIdArray = $("#user_id").val();
        if (userIdArray.length > 1) {
            confirmSavedSearch = confirmSavedSearch.replace('%i', userIdArray.length);
            confirmSavedSearch = confirmSavedSearch.replace('%c', 's');
            confirmSavedSearch = confirmSavedSearch.replace('%s', 'these');
        } else {
            confirmSavedSearch = confirmSavedSearch.replace('%i', userIdArray.length);
            confirmSavedSearch = confirmSavedSearch.replace('%c', '');
            confirmSavedSearch = confirmSavedSearch.replace('%s', 'this');
        }
        var r=confirm(confirmSavedSearch);
        if (r==true){ 
            //make Ajax request 
            $('span#publish_saved_search_status').hide();
            $('span#availability_status_publish').show();
            jQuery.ajax({  //Make the Ajax Request
                type: "POST",
                //async: false,  
                url: "index.php?module=SavedSearch&action=CopyToAll&sugar_body_only=true",
                data: {
                    savedsearch: jQuery("#saved_search_select").val(),
                    user_id: jQuery("#user_id").val(),
                } ,

                success: function(data){  


                    $('span#publish_saved_search_status').html(data);
                    $('span#publish_saved_search_status').show();
                    $('span#availability_status_publish').hide();


                } 

            });//end Ajax

        }
    }



    return false;
}
SUGAR.util.doWhen(
    function () {
        return $('select#department + .ms-parent').length > 0;
    },
    function () {
        $('select#department + .ms-parent').hide();
    }
);
SUGAR.util.doWhen(
    function () {
        return $('select#user_id + .ms-parent').length > 0;
    },
    function () {
        $('select#user_id + .ms-parent').hide();
    }
);
SUGAR.util.doWhen(
    function() {
        return $('#saved_search_select').length > 0;
    },
    function() {
        if ($('#saved_search_select').val() == '_none') {
            $('input#publish_saved_search_chk').hide();
            $('input#btnPublishSavedSearch').hide();
        }
    }
);