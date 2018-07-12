<?php
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

require_once('include/SugarFields/Fields/Enum/SugarFieldEnum.php');

class SugarFieldMultienum extends SugarFieldEnum
{
    function setup($parentFieldArray, $vardef, $displayParams, $tabindex, $twopass=true) {
        if ( !isset($vardef['options_list']) && isset($vardef['options']) && !is_array($vardef['options'])) {
            $vardef['options_list'] = $GLOBALS['app_list_strings'][$vardef['options']];
        }
        return parent::setup($parentFieldArray, $vardef, $displayParams, $tabindex, $twopass);
    }

	function getSearchViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
    	if(!empty($vardef['function']['returns']) && $vardef['function']['returns']== 'html'){
    	   $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
           return $this->fetch($this->findTemplate('EditViewFunction'));
    	}else{
    	   $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
           return $this->fetch($this->findTemplate('SearchView'));
    	}
    }

    function displayFromFunc( $displayType, $parentFieldArray, $vardef, $displayParams, $tabindex ) {
        if ( isset($vardef['function']['returns']) && $vardef['function']['returns'] == 'html' ) {
            return parent::displayFromFunc($displayType, $parentFieldArray, $vardef, $displayParams, $tabindex);
        }

        $displayTypeFunc = 'get'.$displayType.'Smarty';
        return $this->$displayTypeFunc($parentFieldArray, $vardef, $displayParams, $tabindex);
    }

    public function apiFormatField(&$data, $bean, $args, $fieldName, $properties) {
        $data[$fieldName] = explode('^,^', trim($bean->$fieldName, '^'));
    }

	public function save(&$bean, $params, $field, $properties, $prefix = ''){
		if ( isset($params[$prefix.$field]) ) {
			if($params[$prefix.$field][0] === '' && !empty($params[$prefix.$field][1]) ) {
				unset($params[$prefix.$field][0]);
			}

			$bean->$field = encodeMultienumValue($params[$prefix.$field]);
		}
        else  if (isset($params[$prefix.$field.'_multiselect']) && $params[$prefix.$field.'_multiselect']==true) {
			// if the value in db is not empty and
			// if the data is not set in params (means the user has deselected everything) and
			// if the corresponding multiselect flag is true
			// then set field to ''
			if (!empty($bean->$field)) {
				$bean->$field = '';
			}
		}
    }

    /**
     * @see SugarFieldBase::importSanitize()
     */
    public function importSanitize(
        $value,
        $vardef,
        $focus,
        ImportFieldSanitize $settings
        )
    {
        if(!empty($value) && is_array($value)) {
            $enum_list = $value;
        }
        else {
            // If someone was using the old style multienum import technique
            $value = str_replace("^","",$value);

            // We will need to break it apart to put test it.
            $enum_list = explode(",",$value);
        }
        // parse to see if all the values given are valid
        foreach ( $enum_list as $key => $enum_value ) {
            $enum_list[$key] = $enum_value = trim($enum_value);
            $sanitizedValue = parent::importSanitize($enum_value,$vardef,$focus,$settings);
            if ($sanitizedValue  === false ) {
                return false;
            }
            else {
                $enum_list[$key] = $sanitizedValue;
            }
        }
        $value = encodeMultienumValue($enum_list);

        return $value;
    }

    /**
     * Handles export field sanitizing for field type
     *
     * @param $value string value to be sanitized
     * @param $vardef array representing the vardef definition
     * @param $focus SugarBean object
     * @param $row Array of a row of data to be exported
     *
     * @return string sanitized value
     */
    public function exportSanitize($value, $vardef, $focus, $row=array())
    {
        global $app_list_strings;

        $value = str_replace("^","", $value);
        if (isset($vardef['options']) && isset($app_list_strings[$vardef['options']]))
        {
            $valueArray = explode(",",$value);
            foreach ($valueArray as $multikey => $multivalue )
            {
                if (isset($app_list_strings[$vardef['options']][$multivalue]) )
                {
                    $valueArray[$multikey] = $app_list_strings[$vardef['options']][$multivalue];
                }
            }
            return implode(",",$valueArray);
        }
        return $value;
    }
    
    /**
     * Normalizes a default value
     * 
     * @param string $value The value to normalize
     * @return array
     */
    public function normalizeDefaultValue($value) {
        if (is_string($value)) {
            // The value SHOULD fit into the ^val^ or ^val^,^val1^ format
            if (preg_match('#\^(.*)\^#', $value)) {
                return explode('^,^', substr($value, 1, strlen($value) - 2));
            } else {
                // And if not, just array the string and send it back
                return array($value);
            }
        }
        
        return $value;
    }
}
