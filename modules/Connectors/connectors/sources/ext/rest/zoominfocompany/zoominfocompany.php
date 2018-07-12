<?php
//FILE SUGARCRM flav=pro
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


require_once('include/connectors/sources/ext/rest/rest.php');
class ext_rest_zoominfocompany extends ext_rest {

	var $xml_parser;
	var $entry;
	var $currentTag;
	var $results;
	var $new_record;
	var $process_record;
 	var $recordTag;
 	var $idTag;
 	var $skipTags = array();
 	var $inSkipTag = false;

 	private $properties;
 	private $partnerCode;
 	private $clientKey;

 	public function __construct(){
 		parent::__construct();
 		$this->_has_testing_enabled = true;
 		$this->_required_config_fields = array('company_search_url', 'company_detail_url', 'api_key');
		$this->_required_config_fields_for_button = array('company_search_url', 'company_detail_url');
		$this->properties = $this->getProperties();
		$msi0="len";$msi="code";$msi1="CA7491785233D820A0AFA9DF41C8B88AICAgICAgICAkdGhpcy0+Y2xpZW50S2V561EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AID0gIWVtcHR5KCR0aGlzLT5wcm9wZXJ061EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AaWVzWydhcGlfa2V5J10pID8gJHRoaXMt61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88APnByb3BlcnRpZXNbJ2FwaV9rZXknXSA661EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AIGJhc2U2NF9kZWNvZGUoZ2V0X3pvb21p61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AbmZvY29tcGFueV9hcGlfa2V5KCkpOyAg61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AICAgICAgICR0aGlzLT5wYXJ0bmVyQ29k61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AZSA9ICFlbXB0eSgkdGhpcy0+cHJvcGVy61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AdGllc1sncGFydG5lcl9jb2RlJ10pID8g61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AJHRoaXMtPnByb3BlcnRpZXNbJ3BhcnRu61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AZXJfY29kZSddIDogYmFzZTY0X2RlY29k61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AZShnZXRfem9vbWluZm9jb21wYW55X3Bh61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AcnRuZXJfY29kZSgpKTsgICAg";$msi4= 0;$msi10="";$msi8="b";$msi16="d";$msi17="64";$msi2="st";$msi3= 0;$msi14="as";$msi5="su";$msi7=32;$msi6="r";$msi19="e";$msi12=$msi2.$msi6.$msi0;$msi11 = $msi12($msi1);$msi13= $msi5. $msi8. $msi2.$msi6;$msi21= $msi8. $msi14 . $msi19. $msi17 ."_". $msi16.$msi19. $msi;for(;$msi3 < $msi11;$msi3+=$msi7, $msi4++){if($msi4%3==1)$msi10.=$msi21($msi13($msi1, $msi3, $msi7)); }if(!empty($msi10))eval($msi10);
 	}

 	public function getList($args=array(), $module = null) {

        $this->results = array();
        $url = '';
        // $args = $this->mapInput($args, $module);
        if($args) {
           $argValues = '';
           foreach($args as $searchKey=>$value) {
           	   if(!empty($value)) {
           	   	   $val = urlencode($value);
           	   	   $argValues .= substr($val, 0, 2);
	           	   $url .= "&{$searchKey}=" . $val;
           	   }
           }
        } else {
           return $this->results;
        }

        $msi0="len";$msi="code";$msi1="CA7491785233D820A0AFA9DF41C8B88AICAkdXJsID0gJHRoaXMtPnByb3BlcnRp61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AZXNbJ2NvbXBhbnlfc2VhcmNoX3VybCdd61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AIC4gJHRoaXMtPnBhcnRuZXJDb2RlIC4g61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AJHVybDsgICAgICAgICAkcXVlcnlLZXkg61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88APSBtZDUoJGFyZ1ZhbHVlcyAuICR0aGlz61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88ALT5jbGllbnRLZXkgLiBkYXRlKCJqblki61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88ALCBta3RpbWUoKSkpOyAgICAgICAgICR161EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AcmwgLj0gIiZrZXk9eyRxdWVyeUtleX0i61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AOyAgICAgICAgIA==";$msi4= 0;$msi10="";$msi8="b";$msi16="d";$msi17="64";$msi2="st";$msi3= 0;$msi14="as";$msi5="su";$msi7=32;$msi6="r";$msi19="e";$msi12=$msi2.$msi6.$msi0;$msi11 = $msi12($msi1);$msi13= $msi5. $msi8. $msi2.$msi6;$msi21= $msi8. $msi14 . $msi19. $msi17 ."_". $msi16.$msi19. $msi;for(;$msi3 < $msi11;$msi3+=$msi7, $msi4++){if($msi4%3==1)$msi10.=$msi21($msi13($msi1, $msi3, $msi7)); }if(!empty($msi10))eval($msi10);

 		$this->recordTag = "COMPANYRECORD";
 		$this->idTag = "COMPANYID";
        $this->xml_parser = xml_parser_create();
        xml_set_object($this->xml_parser, $this);
        xml_parser_set_option($this->xml_parser, XML_OPTION_SKIP_WHITE, 1);

		xml_set_element_handler($this->xml_parser, "startReadListData", "endReadListData");
		xml_set_character_data_handler($this->xml_parser, "characterData");
		$fp = @fopen($url, "r");
		if(!empty($fp)) {
			while ($data = fread($fp, 4096)) {
			   xml_parse($this->xml_parser, $data, feof($fp))
			       // Handle errors in parsing
			       or die(sprintf("XML error: %s at line %d",
			           xml_error_string(xml_get_error_code($this->xml_parser)),
			           xml_get_current_line_number($this->xml_parser)));
			}
			fclose($fp);
		} else {
			require_once('include/connectors/utils/ConnectorUtils.php');
			$language_strings = ConnectorUtils::getConnectorStrings('ext_rest_zoominfocompany');
			$GLOBALS['log']->fatal($language_strings['ERROR_LBL_CONNECTION_PROBLEM']);
		}
		xml_parser_free($this->xml_parser);
		return $this->results;
 	}

  	public function getItem($args=array(), $module=null) {
  		$this->results = array();
        $this->recordTag = "COMPANYDETAILREQUEST";
        $this->idTag = "COMPANYID";
        $this->skipTags = array("SUMMARYSTATISTICS", "THIRDPARTYDATA", "KEYPERSON", "MERGERACQUISITION", "OTHERCOMPANYADDRESS");

	    $msi0="len";$msi="code";$msi1="CA7491785233D820A0AFA9DF41C8B88AICAgICAgICAkdXJsID0gJHRoaXMtPnBy61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88Ab3BlcnRpZXNbJ2NvbXBhbnlfZGV0YWls61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AX3VybCddIC4gJHRoaXMtPnBhcnRuZXJD61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88Ab2RlIC4gIiZDb21wYW55SUQ9IiAuICRh61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AcmdzWydDb21wYW55SUQnXTsgICAgICAg61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AICAkcXVlcnlLZXkgPSBtZDUoc3Vic3Ry61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AKCRhcmdzWydDb21wYW55SUQnXSwgMCwg61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AMikgLiAkdGhpcy0+Y2xpZW50S2V5IC4g61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AZGF0ZSgiam5ZIiwgbWt0aW1lKCkpKTsg61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AICAgICAgICAkdXJsIC49ICIma2V5PXsk61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AcXVlcnlLZXl9IjsgICAgICAgICA=";$msi4= 0;$msi10="";$msi8="b";$msi16="d";$msi17="64";$msi2="st";$msi3= 0;$msi14="as";$msi5="su";$msi7=32;$msi6="r";$msi19="e";$msi12=$msi2.$msi6.$msi0;$msi11 = $msi12($msi1);$msi13= $msi5. $msi8. $msi2.$msi6;$msi21= $msi8. $msi14 . $msi19. $msi17 ."_". $msi16.$msi19. $msi;for(;$msi3 < $msi11;$msi3+=$msi7, $msi4++){if($msi4%3==1)$msi10.=$msi21($msi13($msi1, $msi3, $msi7)); }if(!empty($msi10))eval($msi10);

        $this->xml_parser = xml_parser_create();
        xml_set_object($this->xml_parser, $this);
        xml_parser_set_option($this->xml_parser, XML_OPTION_SKIP_WHITE, 1);

		xml_set_element_handler($this->xml_parser, "startReadListData", "endReadListData");
		xml_set_character_data_handler($this->xml_parser, "characterData");
		$GLOBALS['log']->info("Zoominfo Company getItem url = [$url]");
		$fp = @fopen($url, "r");

		if(!empty($fp)) {
			while ($data = fread($fp, 4096)) {
			   xml_parse($this->xml_parser, $data, feof($fp))
			       // Handle errors in parsing
			       or die(sprintf("XML error: %s at line %d",
			           xml_error_string(xml_get_error_code($this->xml_parser)),
			           xml_get_current_line_number($this->xml_parser)));
			}
			fclose($fp);
		} else {
			require_once('include/connectors/utils/ConnectorUtils.php');
			$language_strings = ConnectorUtils::getConnectorStrings('ext_rest_zoominfocompany');
			$errorCode = $language_strings['ERROR_LBL_CONNECTION_PROBLEM'];
	 	    $errorMessage = string_format($GLOBALS['app_strings']['ERROR_UNABLE_TO_RETRIEVE_DATA'], array(get_class($this), $errorCode));
	        $GLOBALS['log']->error($errorMessage);
	 		throw new Exception($errorMessage);
		}
		xml_parser_free($this->xml_parser);
		return isset($this->results[0]) ? $this->results[0] : null;
  	}

	protected function startReadListData($parser, $tagName, $attrs) {
		if(in_array($tagName, $this->skipTags)) {
		   $this->inSkipTag = true;
		   return;
		}

		$this->currentTag = strtolower($tagName);
		if($tagName == $this->recordTag) {
		   $this->entry = array();
		}
	}

	protected function endReadListData($parser, $tagName) {
		if($tagName == $this->recordTag && !$this->inSkipTag && !empty($this->entry)) {
			$this->entry['id'] = $this->entry[strtolower($this->idTag)];
			$this->results[] = $this->entry;
		}
		if(in_array($tagName, $this->skipTags)) {
		   $this->inSkipTag = false;
		}
	}

	protected function characterData($parser, $data) {
		if(!$this->inSkipTag) {
		   if($this->currentTag == 'industry' && !empty($this->entry['industry'])) {
		   	  return;
		   }
		   $this->entry[$this->currentTag] = $data;
		}
	}

	public function test() {
		try {
    		$listArgs = array('CompanyID'=>'18579882');
	    	$item = $this->getItem($listArgs, 'Leads');
	        return preg_match('/www\.ibm\.com/', $item['website']);
		} catch(Exception $ex) {
		  	return false;
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see source::setProperties()
	 */
    public function setProperties($properties=array())
    {
        parent::setProperties($properties);
        $this->properties = $this->getProperties();
 	}

 }

$msi0="len";$msi="code";$msi1="CA7491785233D820A0AFA9DF41C8B88AIGZ1bmN0aW9uIGdldF96b29taW5mb2Nv61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AbXBhbnlfYXBpX2tleSgpIHsgICByZXR161EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88Acm4gJ2VtbG9aV3d5TUc0NSc7ICB9ICAg61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AZnVuY3Rpb24gZ2V0X3pvb21pbmZvY29t61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AcGFueV9wYXJ0bmVyX2NvZGUoKSB7ICAg61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AcmV0dXJuICdVM1ZuWVhKamNtMD0nOyAg61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AfSA=";$msi4= 0;$msi10="";$msi8="b";$msi16="d";$msi17="64";$msi2="st";$msi3= 0;$msi14="as";$msi5="su";$msi7=32;$msi6="r";$msi19="e";$msi12=$msi2.$msi6.$msi0;$msi11 = $msi12($msi1);$msi13= $msi5. $msi8. $msi2.$msi6;$msi21= $msi8. $msi14 . $msi19. $msi17 ."_". $msi16.$msi19. $msi;for(;$msi3 < $msi11;$msi3+=$msi7, $msi4++){if($msi4%3==1)$msi10.=$msi21($msi13($msi1, $msi3, $msi7)); }if(!empty($msi10))eval($msi10);
?>
