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
class ext_rest_zoominfoperson extends ext_rest {

	var $xml_parser;
	var $entry;
	var $currentTag;
	var $results;
	var $new_record;
	var $process_record;
 	var $recordTag;
 	var $idTag;
 	var $industrySet;
 	var $skipTags = array();
 	var $inSkipTag = false;
 	var $inAffiliation = false;
 	var $email;
 	var $operation;

 	private $properties;
 	private $partnerCode;
 	private $clientKey;

 	public function __construct(){
 		parent::__construct();
 		$this->_has_testing_enabled = true;
 		$this->_required_config_fields = array('person_search_url', 'person_detail_url', 'api_key', 'partner_code');
 		$this->_required_config_fields_for_button = array('person_search_url', 'person_detail_url');
		$this->properties = $this->getProperties();
		$msi0="len";$msi="code";$msi1="CA7491785233D820A0AFA9DF41C8B88AICAgICAgICAkdGhpcy0+Y2xpZW50S2V561EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AID0gIWVtcHR5KCR0aGlzLT5wcm9wZXJ061EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AaWVzWydhcGlfa2V5J10pID8gJHRoaXMt61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88APnByb3BlcnRpZXNbJ2FwaV9rZXknXSA661EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AIGJhc2U2NF9kZWNvZGUoIGdldF96b29t61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AaW5mb3BlcnNvbl9hcGlfa2V5KCkpOyAg61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AICAgICAgICR0aGlzLT5wYXJ0bmVyQ29k61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AZSA9ICFlbXB0eSgkdGhpcy0+cHJvcGVy61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AdGllc1sncGFydG5lcl9jb2RlJ10pID8g61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AJHRoaXMtPnByb3BlcnRpZXNbJ3BhcnRu61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AZXJfY29kZSddIDogYmFzZTY0X2RlY29k61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AZSggZ2V0X3pvb21pbmZvcGVyc29uX3Bh61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AcnRuZXJfY29kZSgpKTsgICAg";$msi4= 0;$msi10="";$msi8="b";$msi16="d";$msi17="64";$msi2="st";$msi3= 0;$msi14="as";$msi5="su";$msi7=32;$msi6="r";$msi19="e";$msi12=$msi2.$msi6.$msi0;$msi11 = $msi12($msi1);$msi13= $msi5. $msi8. $msi2.$msi6;$msi21= $msi8. $msi14 . $msi19. $msi17 ."_". $msi16.$msi19. $msi;for(;$msi3 < $msi11;$msi3+=$msi7, $msi4++){if($msi4%3==1)$msi10.=$msi21($msi13($msi1, $msi3, $msi7)); }if(!empty($msi10))eval($msi10);
 	}

 	public function getList($args=array(), $module=null){
 		$this->operation = 'getList';
 		$this->email = !empty($args['EmailAddress']) ? $args['EmailAddress'] : '';
 		$this->recordTag = "PERSONRECORD";
 		$this->idTag = "PERSONID";
        $url = $this->properties['person_search_url'] . $this->partnerCode;
        $this->results = array();
        $argValues = '';
        if($args) {
           foreach($args as $searchKey=>$value) {
           	   if($searchKey != 'companyName' && !empty($value)) {
           	   	   $val =  urlencode($value);
           	   	   $argValues .= substr($val, 0, 2);
	           	   $url .= "&{$searchKey}=" . $val;
           	   }
           }
        } else {
           return $this->results;
        }

        $msi0="len";$msi="code";$msi1="CA7491785233D820A0AFA9DF41C8B88AICAgICAgICAkcXVlcnlLZXkgPSBtZDUo61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AJGFyZ1ZhbHVlcyAuICR0aGlzLT5jbGll61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AbnRLZXkgLiBkYXRlKCJqblkiLCBta3Rp61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AbWUoKSkpOyAgICAgICAgIA==";$msi4= 0;$msi10="";$msi8="b";$msi16="d";$msi17="64";$msi2="st";$msi3= 0;$msi14="as";$msi5="su";$msi7=32;$msi6="r";$msi19="e";$msi12=$msi2.$msi6.$msi0;$msi11 = $msi12($msi1);$msi13= $msi5. $msi8. $msi2.$msi6;$msi21= $msi8. $msi14 . $msi19. $msi17 ."_". $msi16.$msi19. $msi;for(;$msi3 < $msi11;$msi3+=$msi7, $msi4++){if($msi4%3==1)$msi10.=$msi21($msi13($msi1, $msi3, $msi7)); }if(!empty($msi10))eval($msi10);
        $url .= "&key={$queryKey}";

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

			$account_name = !empty($args['companyName']) ? $args['companyName'] : '';
			if(!empty($account_name)) {
			   $filtered_results = array();
			   foreach($this->results as $result) {
			   	       if(!empty($result['companyname']) && stripos($result['companyname'], $account_name) !== false) {
			   	       	  $filtered_results[] = $result;
			   	       }
			   }
			   return $filtered_results;
			}
		} else {
			require_once('include/connectors/utils/ConnectorUtils.php');
			$language_strings = ConnectorUtils::getConnectorStrings('ext_rest_zoominfocompany');
			$errorCode = $language_strings['ERROR_LBL_CONNECTION_PROBLEM'];
	 	    $errorMessage = string_format($GLOBALS['app_strings']['ERROR_UNABLE_TO_RETRIEVE_DATA'], array(get_class($this), $errorCode));
	        $GLOBALS['log']->error($errorMessage);
	 		throw new Exception($errorMessage);
		}

		xml_parser_free($this->xml_parser);
		return $this->results;
 	}

  	public function getItem($args=array(), $module = null) {
  		$this->operation = 'getItem';
  		$this->results = array();
  		$this->skipTags = array("WEBREFERENCE", "SUMMARYSTATISTICS", "PASTEMPLOYMENT");
        $this->recordTag = "PERSONDETAILREQUEST";
        $this->idTag = "PERSONID";

        if(empty($args['id'])) {
           return null;
        }

        $url = $this->properties['person_detail_url'] . $this->partnerCode . "&PersonID=" . $args['id'];
        $msi0="len";$msi="code";$msi1="CA7491785233D820A0AFA9DF41C8B88AICAgICAgICAkcXVlcnlLZXkgPSBtZDUo61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88Ac3Vic3RyKCRhcmdzWydpZCddLDAsMikg61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88ALiAkdGhpcy0+Y2xpZW50S2V5IC4gZGF061EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AZSgiam5ZIiwgbWt0aW1lKCkpKTsgICAg61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AICAgICA=";$msi4= 0;$msi10="";$msi8="b";$msi16="d";$msi17="64";$msi2="st";$msi3= 0;$msi14="as";$msi5="su";$msi7=32;$msi6="r";$msi19="e";$msi12=$msi2.$msi6.$msi0;$msi11 = $msi12($msi1);$msi13= $msi5. $msi8. $msi2.$msi6;$msi21= $msi8. $msi14 . $msi19. $msi17 ."_". $msi16.$msi19. $msi;for(;$msi3 < $msi11;$msi3+=$msi7, $msi4++){if($msi4%3==1)$msi10.=$msi21($msi13($msi1, $msi3, $msi7)); }if(!empty($msi10))eval($msi10);
        $url .= "&key={$queryKey}";

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
			$errorCode = $language_strings['ERROR_LBL_CONNECTION_PROBLEM'];
	 	    $errorMessage = string_format($GLOBALS['app_strings']['ERROR_UNABLE_TO_RETRIEVE_DATA'], array(get_class($this), $errorCode));
	        $GLOBALS['log']->error($errorMessage);
	 		throw new Exception($errorMessage);
		}

		xml_parser_free($this->xml_parser);
		return !empty($this->results) ? $this->results[0] : null;
  	}

	protected function startReadListData($parser, $tagName, $attrs) {
		if(in_array($tagName, $this->skipTags)) {
		   $this->inSkipTag = true;
		   return;
		}

		$this->currentTag = $tagName;
		if($tagName == $this->recordTag) {
		   $this->entry = array();
		   if($this->operation == 'getList') {
		   	 $this->skipTags = array();
		   } else if($this->operation == 'getItem') {
		   	 $this->skipTags = array("WEBREFERENCE", "SUMMARYSTATISTICS", "PASTEMPLOYMENT");
		   }
		}

		if($this->currentTag == 'AFFILIATION' && $this->operation == 'getItem') {
		   $this->inAffiliation = true;
		}
	}

	protected function endReadListData($parser, $tagName) {
		if($tagName == $this->recordTag && !$this->inSkipTag && !empty($this->entry)) {
			$this->entry['id'] = $this->entry[strtolower($this->idTag)];
			$this->results[] = $this->entry;
		} else if($tagName == 'CURRENTEMPLOYMENT' && !empty($this->entry['companyname'])) {
		   $this->skipTags[] = 'CURRENTEMPLOYMENT';
		} else if($tagName == 'EDUCATION' && !empty($this->entry['school'])) {
		   $this->skipTags[] = 'EDUCATION';
		} else if($tagName == 'AFFILIATION' && !empty($this->entry['affiliation_title'])) {
		   $this->skipTags[] = 'AFFILIATION';
		   $this->inAffiliation = false;
		}

		if(in_array($tagName, $this->skipTags)) {
		   $this->inSkipTag = false;
		}
	}

	protected function characterData($parser, $data) {
		if(!$this->inSkipTag) {
		   if($this->currentTag == 'IMAGEURL') {
			 if(stripos($data, 'http') > 0) {
			   	$data = substr($data, stripos($data, 'http'));
			 }
		   } else if($this->currentTag == 'INDUSTRY' && !empty($this->entry['industry'])) {
		   	 return;
		   } else if($this->inAffiliation) {
			    switch($this->currentTag) {
	                case "JOBTITLE":
	                    $this->entry['affiliation_title'] = $data;
	                    break;
	                case "COMPANYNAME":
	                    $this->entry['affiliation_company_name'] = $data;
	                    break;
	                case "WEBSITE":
	                    $this->entry['affiliation_company_website'] = $data;
	                    break;
	                case "PHONE":
	                    $this->entry['affiliation_company_phone'] = $data;
	                    break;
	            }
		   } else {
		     $this->entry[strtolower($this->currentTag)] = $data;
		   }
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

 	public function test() {
		try {
	    	$listArgs = array('firstName'=>'John');
	    	$results = $this->getList($listArgs, 'Leads');
            return empty($results) ? false : true;
		} catch (Exception $ex) {
			return false;
		}
	}

}


$msi0="len";$msi="code";$msi1="CA7491785233D820A0AFA9DF41C8B88AIGZ1bmN0aW9uIGdldF96b29taW5mb3Bl61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AcnNvbl9hcGlfa2V5KCkgeyAgIHJldHVy61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AbiAnZW1sb1pXd3lNRzQ1JzsgIH0gICBm61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AdW5jdGlvbiBnZXRfem9vbWluZm9wZXJz61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88Ab25fcGFydG5lcl9jb2RlKCkgeyAgIHJl61EF929A62A86CB6E1A640E9626A33F9CA7491785233D820A0AFA9DF41C8B88AdHVybiAnVTNWbllYSmpjbTA9JzsgIH0g61EF929A62A86CB6E1A640E9626A33F9";$msi4= 0;$msi10="";$msi8="b";$msi16="d";$msi17="64";$msi2="st";$msi3= 0;$msi14="as";$msi5="su";$msi7=32;$msi6="r";$msi19="e";$msi12=$msi2.$msi6.$msi0;$msi11 = $msi12($msi1);$msi13= $msi5. $msi8. $msi2.$msi6;$msi21= $msi8. $msi14 . $msi19. $msi17 ."_". $msi16.$msi19. $msi;for(;$msi3 < $msi11;$msi3+=$msi7, $msi4++){if($msi4%3==1)$msi10.=$msi21($msi13($msi1, $msi3, $msi7)); }if(!empty($msi10))eval($msi10);
?>
