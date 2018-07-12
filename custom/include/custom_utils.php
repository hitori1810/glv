<?php
/**
 * get current user time formet
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */

/**
 * get current user time formet
 *
 * @author     Original Author Biztech Co.
 * @param      date - $date
 * @return     date formet
 */
function convertDateInGMT($date) {
    global $current_user;
    $user_tz = $current_user->getUserDateTimePreferences();
    $match = array();
    preg_match('/\(GMT(.*)\)$/i', $user_tz['userGmt'], $match);
    $date_time = explode(' ', $date);
    $formattted_date_time = TimeDate::getInstance()->to_db_date_time($date_time[0], $date_time[1]);
    $gmtdatetime = gmdate('Y-m-d H:i:s', strtotime($formattted_date_time[0] . 'T' . $formattted_date_time[1] . $match[1]));
    return $gmtdatetime;
    
}
