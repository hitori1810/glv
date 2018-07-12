<?php
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
require_once 'include/MVC/View/views/view.noaccess.php';

class Custombc_surveyViewNoAccess extends ViewNoaccess {

    function display() {
        echo '<p class="error">You can not edit this survey as it is already in use.</p>';
    }

}
