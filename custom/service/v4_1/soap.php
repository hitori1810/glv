<?php    
    if(!defined('sugarEntry'))define('sugarEntry', true);

    chdir('../../..');
    require_once('SugarWebServiceImplv4_1_custom.php');
    $webservice_class = 'SugarSoapService2';
    $webservice_path = 'service/v2/SugarSoapService2.php';
    $webservice_impl_class = 'SugarWebServiceImplv4_1_custom';
    $registry_class = 'registry_v4_1_custom';
    $registry_path = 'custom/service/v4_1/registry.php';
    $location = '/custom/service/v4_1/soap.php';
    require_once('service/core/webservice.php');    
?>