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


function validatePortalName(e) {
    var portalName = document.getElementById('portal_name'); 
    var portalNameExisting = document.getElementById("portal_name_existing"); 
    var portalNameVerified = document.getElementById('portal_name_verified');
	if(typeof(portalName.parentNode.lastChild) != 'undefined' &&
        portalName.parentNode.lastChild.tagName =='SPAN'){
	   portalName.parentNode.lastChild.innerHTML = '';
	}

    if(portalName.value == portalNameExisting.value) {
       return;
    }
    
	var callbackFunction = function success(data) {
	    //data.responseText contains the count of portal_name that matches input field
		count = data.responseText;	
		if(count != 0) {
		   add_error_style('form_EmailQCView_Contacts', 'portal_name', SUGAR.language.get('app_strings', 'ERR_EXISTING_PORTAL_USERNAME'));
		   for(wp = 1; wp <= 10; wp++) {
			   window.setTimeout('fade_error_style(style, ' + wp * 10 + ')', 1000 + (wp * 50));
		   }
		   portalName.focus();
		}
		
	    if(portalNameVerified.parentNode.childNodes.length > 1) {
	       portalNameVerified.parentNode.removeChild(portalNameVerified.parentNode.lastChild);
	    }
	    
        verifiedTextNode = document.createElement('span');
        verifiedTextNode.innerHTML = '';
	    portalNameVerified.parentNode.appendChild(verifiedTextNode);
	    
		portalNameVerified.value = count == 0 ? "true" : "false";
		verifyingPortalName = false;
	}

    if(portalNameVerified.parentNode.childNodes.length > 1) {
       portalNameVerified.parentNode.removeChild(portalNameVerified.parentNode.lastChild);
    }

    if(portalName.value != '' && !verifyingPortalName) {    
       document.getElementById('portal_name_verified').value = "false";
	   verifiedTextNode = document.createElement('span');
	   portalNameVerified.parentNode.appendChild(verifiedTextNode);
       verifiedTextNode.innerHTML = SUGAR.language.get('app_strings', 'LBL_VERIFY_PORTAL_NAME');
       verifyingPortalName = true;
	   var cObj = YAHOO.util.Connect.asyncRequest('POST', 'index.php?module=Contacts&action=ValidPortalUsername&portal_name=' + portalName.value, {success: callbackFunction, failure: callbackFunction});
    }
}

