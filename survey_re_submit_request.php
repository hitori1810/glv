<?php
/**
 * When any customer are send request for resubmit the survey.
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
if (!defined('sugarEntry') || !sugarEntry)
    define('sugarEntry', true);
include_once('config.php');
require_once('include/entryPoint.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('include/database/DBManager.php');
require_once('include/database/DBManagerFactory.php');
require_once('modules/Administration/Administration.php');

global $sugar_config, $db;

$encoded_param = $_REQUEST['q'];
$decoded_param = base64_decode($encoded_param);

$survey_id = substr($decoded_param, 0, 36);
$module_type_array = split('=', substr($decoded_param, strpos($decoded_param, 'ctype='), 42));
$module_type_array = split('&', $module_type_array[1]);
$module_type = $module_type_array[0];

$module_id_array = split('=', substr($decoded_param, strpos($decoded_param, 'cid='), 40));
$module_id = $module_id_array[1];

$survey = new bc_survey();
$survey->retrieve($survey_id);
$survey->load_relationship('bc_survey_pages_bc_survey');
$query = "SELECT bc_survey_submission.status, bc_survey_submission.id AS submission_id,resubmit,resubmit_counter
FROM bc_survey_submission
  JOIN bc_survey_submission_bc_survey_c
    ON bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb = bc_survey_submission.id
      AND bc_survey_submission_bc_survey_c.deleted = 0
  JOIN bc_survey
    ON bc_survey.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida
      AND bc_survey.deleted = 0
WHERE bc_survey_submission.module_id = '{$module_id}'
    AND bc_survey_submission.target_module_name = '{$module_type}'
    AND bc_survey_submission.deleted = 0
    AND bc_survey.id = '{$survey_id}'";
$submission_result = $db->query($query);
$submission_row = $db->fetchByAssoc($submission_result);

$survey_submission = new bc_survey_submission();
$survey_submission->retrieve($submission_row['submission_id']);

if($survey_submission->change_request == "Pending"){
    $msg1 = "<div class='failure_msg'>Your have already requested for re-submit survey response !</div>";
}else{
    $survey_submission->change_request = "Pending";
    if($survey_submission->save()){
        $msg1 = "<div class='success_msg' style='text-align:left !important;height:auto !important;top:25%;'>Your request for re-submit survey response is submitted successfully. You will be sent a confirmation email once admin approves your request.</br></br>Thanks.</div>";
    }else{
        $msg1 = "<div class='failure_msg'>Your request for re-submit survey response is not submitted.</div>";
    }
}
$themeObject = SugarThemeRegistry::current();
$favicon = $themeObject->getImageURL('sugar_icon.ico',false);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Survey Template</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" href="<?php echo $favicon; ?>" type="image/x-icon">
        <link href="<?php echo $sugar_config['site_url'] . '/custom/include/css/survey_css/survey-form.css' ?>" rel="stylesheet">
        <link href="<?php echo $sugar_config['site_url'] . '/custom/include/css/survey_css/' . $survey->theme . '.css'; ?>" rel="stylesheet">
    </head>
    <body>
         <div class="main-container">
            <div id='tooltipDiv'></div>
        <form method="post" name="survey_submisssion" action="" id="survey_submisssion">
            <div class="top-section">
                <div class="header">
                    <div class="">
                        <h1 class="logo">
                            <img src="<?php
                            if ($survey->logo) {
                                echo "custom/include/surveylogo_images/{$survey->logo}";
                            }
                            ?>" alt="" title="">
                        </h1>
                        <div class="survey-header"><h2><?php echo $survey->name; ?></h2></div>
                    </div>
                </div>
            </div>
            <div class="survey-container">
                <?php
                if (isset($msg1) && $msg1 != '') {
                    echo "{$msg1}";
                }
                ?>
            </div>
        </form>
         </div>
    </body>
</html>




