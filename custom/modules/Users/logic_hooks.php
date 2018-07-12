<?php
    // Do not store anything in this file that is not part of the array or the hook version.  This file will
    // be automatically rebuilt in the future.
    $hook_version = 1;
    $hook_array = Array();
    // position, file, function
    $hook_array['login_failed'] = Array();
    $hook_array['login_failed'][] = Array(1, 'Save login history', 'custom/modules/C_LoginHistory/LoginHistoryLogicHooks.php','LoginHistoryLogicHooks', 'saveLoginHistory');

    $hook_array['after_login'] = Array();
     $hook_array['after_login'][] = Array(1, 'check protal user', 'custom/modules/Users/UsersLogicHook.php','UsersLogicHook', 'checkProtalUser');
    $hook_array['after_login'][] = Array(1, 'SugarFeed old feed entry remover', 'modules/SugarFeed/SugarFeedFlush.php','SugarFeedFlush', 'flushStaleEntries');
    $hook_array['after_login'][] = Array(2, 'Create Remember Me Cookie', 'custom/modules/Users/UsersLogicHook.php','UsersLogicHook', 'saveCookieRememberMe');
    $hook_array['after_login'][] = Array(3, 'Remember Me Cookie', 'custom/modules/Users/UsersLogicHook.php','UsersLogicHook', 'SaveCookieRememberMe');
    $hook_array['after_login'][] = Array(4, 'Save login history', 'custom/modules/C_LoginHistory/LoginHistoryLogicHooks.php','LoginHistoryLogicHooks', 'saveLoginHistory');
    $hook_array['after_login'][] = Array(67, 'Check users Homepage', 'custom/modules/Users/HomepageManagerLogicHook.php','defaultHomepage', 'afterLogin');

    $hook_array['before_logout'] = Array();
    $hook_array['before_logout'][] = Array(1, 'Save login history', 'custom/modules/C_LoginHistory/LoginHistoryLogicHooks.php','LoginHistoryLogicHooks', 'saveLoginHistory');

    $hook_array['after_logout'] = Array();
    $hook_array['after_logout'][] = Array(1, 'Remove Remember Me Cookie', 'custom/modules/Users/UsersLogicHook.php','UsersLogicHook', 'removeCookieRememberMe');

    $hook_array['before_save'] = Array();
    $hook_array['before_save'][] = Array(1, 'Validate data', 'custom/modules/Users/UsersLogicHook.php', 'UsersLogicHook', 'checkUserLimit');
    $hook_array['before_save'][] = Array(2, 'Crop the avatar', 'custom/modules/Users/UsersLogicHook.php','UsersLogicHook', 'cropImage');
    
    $hook_array['after_save'] = Array();
    $hook_array['after_save'][] = Array(1, 'Apply user template', 'custom/modules/Users/UsersLogicHook.php','UsersLogicHook', 'applyTemplate');
       
    $hook_array['process_record'] = Array();
    $hook_array['process_record'][] = Array(1, 'Show the Login As button', 'custom/modules/Users/UsersLogicHook.php','UsersLogicHook', 'showLoginAsButton');
      
?>