<?php
    // Do not store anything in this file that is not part of the array or the hook version.  This file will    
    // be automatically rebuilt in the future. 
    $hook_version = 1; 
    $hook_array = Array(); 
    // position, file, function 
    $hook_array['before_save'] = Array(); 
    
    $hook_array['before_save'][] = Array(1, 'Opportunities push feed', 'modules/Opportunities/SugarFeeds/OppFeed.php','OppFeed', 'pushFeed'); 
    $hook_array['before_save'][] = Array(2, 'Add Code', 'custom/include/_helper/AddCode.php', 'Code', 'addCode');
    $hook_array['before_save'][] = Array(3, 'Handle Save Enrollment', 'custom/modules/Opportunities/handleSaveEnr.php', 'handleSaveEnr', 'handleSaveEnr');
    
    $hook_array['after_save'] = Array();
    $hook_array['after_save'][] = Array(2, 'Update Save&Learn', 'custom/modules/Opportunities/after_save_opportunity.php', 'SaveLearn', 'updateSaveLearn');  

    
    $hook_array['process_record'] = Array(); 
    $hook_array['process_record'][] = Array(1, 'Color Status', 'custom/modules/Opportunities/listview_color.php','ListviewLogicHookOpportunities', 'listviewcolor_Opp'); 
    
    $hook_array['after_delete'] = Array();
    $hook_array['after_delete'][] = Array(1, 'Color Status', 'custom/modules/Opportunities/afterDeleteEnrollment.php','handleDelete', 'deleteinvoice_payment'); 
?>