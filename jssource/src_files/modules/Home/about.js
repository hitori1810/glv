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

var abouter = function(){
	return {
		display:function(){
			abouter.div = document.getElementById('abouterdiv');
			abouter.div.style.display ='';
			abouter.div.src = "index.php?module=Home&action=PopupSugar&to_pdf=true&style=" + abouter.style;
		},
		ab:function(index, style){
			if(abouter.starter == 3){
				abouter.style = style;
				abouter.display();
			}else{
				if(index == abouter.starter + 1){
					abouter.starter++;
				}else{
					abouter.starter= 0;
				}
			}

		}



	}



}();
abouter.starter = 0;
abouter.style = 'inc';
