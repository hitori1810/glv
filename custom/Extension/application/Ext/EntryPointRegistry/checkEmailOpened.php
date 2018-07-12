<?php
/**
 * From this entry point admin can access the inner file in their instance from out side.
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$entry_point_registry['checkEmailOpened'] = array(
    'file' => 'modules/bc_survey/check_email_opened.php',
    'auth' => false
);
$entry_point_registry['preview_survey'] = array(
    'file' => 'modules/bc_survey/surveycontroller.php',
    'auth' => false
);
