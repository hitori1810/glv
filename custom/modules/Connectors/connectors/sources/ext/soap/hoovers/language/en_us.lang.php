<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/


$connector_strings = array (
    //licensing information shown in config screen
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel"><image src="' . getWebPath('modules/Connectors/connectors/sources/ext/soap/hoovers/images/hooversLogo.gif') . '" border="0"></td><td width="65%" valign="top" class="dataLabel">' .
                            'Hoovers&#169; provides free up-to-date information on companies to users of SugarCRM products.  To get more comprehensive information and reports on companies, industries, and executives go to <a href="http://www.hoovers.com" target="_blank">http://www.hoovers.com</a>.</td></tr></table>',    
  
    //vardef labels
	'LBL_ID' => 'ID',
	'LBL_NAME' => 'Company Name',
	'LBL_DUNS' => 'DUNS',
	'LBL_PARENT_DUNS' => 'Parent DUNS',
	'LBL_STREET' => 'Street',
	'LBL_ADDRESS_STREET1' => 'Street Address1',
    'LBL_ADDRESS_STREET2' => 'Street Address2',
	'LBL_CITY' => 'City',
	'LBL_STATE' => 'State',
	'LBL_COUNTRY' => 'Country',
	'LBL_ZIP' => 'Postal Code',
	'LBL_FINSALES' => 'Annual Sales',
    'LBL_HQPHONE' => 'Phone Office',
    'LBL_TOTAL_EMPLOYEES' => 'Total Employees',
	'LBL_PRIMARY_URL' => 'Primary URL',
	'LBL_DESCRIPTION' => 'Description',
	'LBL_SYNOPSIS' => 'Synopsis',
	'LBL_LOCATION_TYPE' => 'Location Type',
	'LBL_COMPANY_TYPE' => 'Company Type',
	
    //error messages
    'ERROR_NULL_CLIENT' => 'Unable to create SoapClient to connect to Hoovers.  The service may be unavailable or your license key may be expired or have reached a daily usage limit.',
    'ERROR_MISSING_SOAP_LIBRARIES' => 'Error: Unable to load the SOAP libraries (SoapClient, SoapHeader).',

	//Configuration labels
	'hoovers_endpoint' => 'Endpoint URL',
	'hoovers_wsdl' => 'WSDL URL',
	'hoovers_api_key' => 'API Key',
);

?>
