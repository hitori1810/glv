{*

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




*}


{literal}
<script type="text/javascript">
if(typeof SugarFeed == 'undefined') { // since the dashlet can be included multiple times a page, don't redefine these functions
	SugarFeed = function() {
	    return {
	    	/**
	    	 * Called when the textarea is blurred
	    	 */
	        pushUserFeed: function(id) {
	        	ajaxStatus.showStatus('{/literal}{$saving}{literal}'); // show that AJAX call is happening
	        	// what data to post to the dashlet
    	    	
    	    	postData = 'to_pdf=1&module=Home&action=CallMethodDashlet&method=pushUserFeed&id=' + id ;
				YAHOO.util.Connect.setForm(document.getElementById('form_' + id));
				var cObj = YAHOO.util.Connect.asyncRequest('POST','index.php', 
								  {success: SugarFeed.saved, failure: SugarFeed.saved, argument: id}, postData);
	        },
		    /**
	    	 * handle the response of the saveText method
	    	 */
	        saved: function(data) {
	        	SUGAR.mySugar.retrieveDashlet(data.argument);
	           	ajaxStatus.flashStatus('{/literal}{$done}{literal}');
	        }, 
	        deleteFeed: function(record, id){
				postData = 'to_pdf=1&module=Home&action=CallMethodDashlet&method=deleteUserFeed&id=' + id + '&record=' +  record;
				var cObj = YAHOO.util.Connect.asyncRequest('POST','index.php', 
								  {success: SugarFeed.saved, failure: SugarFeed.saved, argument: id}, postData);	        
	        },
            buildReplyForm: function(record, id, elem) {
               // See if we already have a blockquote
               var myParentElem = elem.parentNode.parentNode.parentNode;
               
               var blockElem = myParentElem.getElementsByTagName('blockquote')[0];
               if ( typeof(blockElem) == 'undefined' || typeof(blockElem[0]) == 'undefined' ) {
                  // Need to create a blockquote element
                  // With a "clear" div in front of it.
                  var divElem = document.createElement('div');
                  divElem.className = 'clear';
                  myParentElem.appendChild(divElem);
                  blockElem = document.createElement('blockquote');
                  myParentElem.appendChild(blockElem);
               } else {
                 // Should only be one child blockquote element, so we'll just grab the first one
                 blockElem = blockElem[0];
               }

               // Move the reply form up over here
               var formElem = document.getElementById('SugarFeedReplyForm_'+id);
               formElem.parentNode.removeChild(formElem);
               blockElem.appendChild(formElem);
               formElem.getElementsByTagName('div')[0].style.display = 'block';
               formElem.parentFeed.value = record;

            },
            replyToFeed: function( id ) {
	        	ajaxStatus.showStatus('{/literal}{$saving}{literal}'); // show that AJAX call is happening
	        	// what data to post to the dashlet
    	    	
                var formElem = document.getElementById('SugarFeedReplyForm_' + id);
    	    	postData = 'to_pdf=1&module=Home&action=CallMethodDashlet&method=pushUserFeedReply&id=' + id ;
				YAHOO.util.Connect.setForm(formElem);
				var cObj = YAHOO.util.Connect.asyncRequest('POST','index.php', 
								  {success: SugarFeed.saved, failure: SugarFeed.saved, argument: id}, postData);
               
            },
            loaded: function(id) {
            	var scrollConent;
            	scrollContent = new iScroll('contentScroller' + id);
            }
	    };
	}();
}

if(SUGAR.util.isTouchScreen()) {
	document.addEventListener('DOMContentLoaded', function(){SugarFeed.loaded('{/literal}{$idjs}{literal}')}, false);	
}
</script>
{/literal}
<script type="text/javascript" src="{sugar_getjspath file="include/javascript/popup_helper.js"}">
</script>