/*
 * Customize Subpanel as tabs
 * Author: Hieu Nguyen
 * Date: 08-01-2013
*/

$(document).ready(function(){

    // Only do when subpanel tabs is enabled
    if($('#groupTabs')[0] != null){

        var customStyle = '<style type="text/css">#groupTabs li{display: block; float: left; margin-bottom: 8px;}</style>';
        $('head').append(customStyle);
        function hideAllSubpanel(){
            $('#subpanel_list li').each(function(){
                $(this).hide();
            });
        }

        function showAllSubpanel(){
            $('#subpanel_list li').each(function(){
                $(this).show();
            });
        }

        function markActive(tab){
            $('#groupTabs li a.current').removeClass('current');
            $(tab).addClass('current');
        }

        // Hide all subpanel on load
        hideAllSubpanel();
        // Hide all default tabs
        $('#groupTabs li').hide();
        $('#groupTabs li a.current').removeClass('current');
        $('#groupTabs li:first').show();

        // Generate each subpanel as a tab
        $('#subpanel_list li').each(function(){
            var moduleName = $(this).find('h3').text();
            var subpanelID = $(this).attr('id');
            $('#groupTabs').append('<li><a data-subpanel="'+subpanelID+'" href="">'+moduleName+'</a></li>');
        });

        // Onclick on a tab
        $('#groupTabs li a').click(function(){
            markActive($(this));
            var subpanelID = $(this).attr('data-subpanel');
            hideAllSubpanel();
            $('#'+subpanelID).show();
            $('#subpanel_list li.sugar_action_button').show();
            $('#subpanel_list li.single').show();
            $('#subpanel_list li.sugar_action_button').find('.subnav').find('li').show();
            return false;
        });

        // Onclick show all
        $('#groupTabs li:first a').click(function(){
            markActive($(this));
            showAllSubpanel();
        });

        jQuery('a[data-subpanel="undefined"]').parent().hide();
    }
});