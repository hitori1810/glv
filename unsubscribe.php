<!DOCTYPE html>
<?php
/**
 * When any person unsubscribe from mail.
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
require_once('include/entryPoint.php');
global $sugar_config, $app_strings, $db;

$systemSettings = $db->fetchByAssoc($db->query("SELECT value AS system_name FROM config WHERE category='system' AND name='name'"));

$email_add_id = $_GET['target'];
$emailAddress = new EmailAddress();

$themeObject = SugarThemeRegistry::current();
$companyLogoURL = $themeObject->getImageURL('company_logo.png');
$favicon = $themeObject->getImageURL('sugar_icon.ico',false);
?>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        #divElement {
            width: 380px;
            position: absolute;
            left: 0px;
            right: 0px;
            margin: auto;
            background: none repeat scroll 0% 0% #EEE;
            padding: 30px;
            top: 10%;
            border: 1px solid #DDD;
            box-shadow: 0px 0px 4px 0px #DDD;
            border-radius: 5px;
            text-shadow: 1px 1px #FFF;
        }

        #divElement span {
            display: inline-block;
            padding-bottom: 15px;
            width: auto;
            vertical-align: middle;
        }

        #divElement h1 {
            color: #333;
            width: 100%;
            display: inline-block;
            padding-bottom: 15px;
            font-family: arial, georgia, serif;
            font-size: 16px;
            font-weight: blod;
        }

        #divElement h2 {
            display: inline-block;
            width: 45%;
            font-size: 17px;
            font-family: arial;
            margin-left: 5%;
        }

        .title{
            width: 100%;
            display: inline-block;
        }
    </style>
    <link rel="icon" href="<?php echo $favicon; ?>" type="image/x-icon">
</head>
<body>


<div id="divElement">
    <div class="title">
    <span>
        <img src="<?php echo $companyLogoURL; ?>" alt="Company logo" border="0"></span>

        <h2><?php echo $systemSettings['system_name'] ?></h2>
    </div>

    <?php
    if ($emailAddress->retrieve($email_add_id)) {
        $emailAddress->opt_out = 1;
        $emailAddress->save();
        $themeObject = SugarThemeRegistry::current();
        $companyLogoURL = $themeObject->getImageURL('company_logo.png');
        $company_logo_attributes = sugar_cache_retrieve('company_logo_attributes');

        ?>
        <h1>You have successfully unsubscribed from <i><?php echo $systemSettings['system_name'] ?></i> emails.</h1>
    <?php

    } else {
        ?>
        <h1>Can not perform operation,Invalid Target Id.</h1>

    <?php
    }
    ?>
</div>
</body>
</html>

