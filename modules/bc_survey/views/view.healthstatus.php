<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class ViewHealthstatus extends SugarView {

    function display() {
        
        require_once('modules/Administration/Administration.php');
        require_once ('custom/include/utilsfunction.php');
        $health_status=getHealthStatus();
        echo "<script src='custom/include/js/survey_js/custom_code.js'></script>";

        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('SurveyPlugin');

        $html = '<table id="health_status" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td colspan="100">
                        <div class="moduleTitle">
                            <h2>Health Status</h2>
                            <div class="clear"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="100">
                        <div class="add_table" style="margin-bottom:5px">
                        <table  class="edit view" style="margin-bottom:0px;" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr><th align="left" colspan="4" scope="row"><h4>License Configuration</h4></th></tr>
                            <tr>
                                <td scope="row" nowrap="nowrap" style="width: 10%;"><label for="name_basic" > License Configuration Status : </label></td>
                                <td nowrap="nowrap" style="width: 85%;height:30px;" id="license_status">'.$health_status['license_status'].'</td></tr>
                            <tr><td></td></tr>
                            
                            <tr><th align="left" colspan="4" scope="row"><h4>Scheduler</h4></th></tr>
                            <tr>
                                <td scope="row" nowrap="nowrap" style="width: 10%;"><label for="name_basic" > Scheduler Description : </label></td>
                                <td nowrap="nowrap" style="width: 85%;height:30px;" id="scheduler_desc">'.$health_status['scheduler_status']['desc'].'</td></tr>
                            <tr>
                                <td scope="row" nowrap="nowrap" style="width: 10%;"><label for="name_basic"> Scheduler Status : </label></td>
                                <td nowrap="nowrap" style="width: 85%;height:30px;" id="scheduler_status">'.$health_status['scheduler_status']['status'].'</td></tr>
                            <tr><td></td></tr>
                                
                            <tr><th align="left" colspan="4" scope="row"><h4>PHP Version</h4></th></tr>
                            <tr>
                                <td scope="row" nowrap="nowrap" style="width: 10%;"><label for="name_basic" > PHP Status : </label></td>
                                <td nowrap="nowrap" style="width: 85%;height:30px;" id="php_status">'.$health_status['php_status'].'</td></tr>
                            <tr><td></td></tr>
                                
                            <tr><th align="left" colspan="4" scope="row"><h4>Site URL Configuration</h4></th></tr>
                            <tr>
                                <td scope="row" nowrap="nowrap" style="width: 10%;"><label for="name_basic" > Site URL Status : </label></td>
                                <td nowrap="nowrap" style="width: 85%;height:30px;" id="siteurl_status">'.$health_status['siteurl_status'].'</td></tr>
                            <tr><td></td></tr>
                                
                             <tr><th align="left" colspan="4" scope="row"><h4>SMTP Configuration</h4></th></tr>
                            <tr>
                                <td scope="row" nowrap="nowrap" style="width: 10%;"><label for="name_basic" > SMTP Configuration Status : </label></td>
                                <td nowrap="nowrap" style="width: 85%;height:30px;" id="smtp_status">'.$health_status['smtp_status'].'</td></tr>
                            <tr><td></td></tr>
                            
                             <tr><th align="left" colspan="4" scope="row"><h4>File Permission</h4></th></tr>
                            <tr>
                                <td scope="row" nowrap="nowrap" style="width: 10%;"><label for="name_basic" > File Permission Status : </label></td>
                                <td nowrap="nowrap" style="width: 85%;height:30px;" id="file_permission_status">'.$health_status['file_permission_status'].'</td></tr>
                            <tr><td></td></tr>
                                
                             <tr><th align="left" colspan="4" scope="row"><h4>cURL Setting</h4></th></tr>
                            <tr>
                                <td scope="row" nowrap="nowrap" style="width: 10%;"><label for="name_basic" > cURL Status : </label></td>
                                <td nowrap="nowrap" style="width: 85%; height:30px;" id="curl_status">'.$health_status['curl_status'].'</td></tr>
                         </tbody>
                         </table> 
                    </td>
                </tr>
                
                </tbody>
                </table>
                <br/>
                <a href="index.php?module=Administration&action=index"><input title="Back to Administration" class="button primary back" name="back" value="Back to Administrator" type="button"></a>';
        parent::display();
        echo $html;
    }
}