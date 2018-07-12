<?php

    class FieldHighlighterHelper {

        // Field name, types and sources that will not be used to check for dupplication
        static $excludeTypes = array('id', 'link', 'relate', 'multienum', 'radioenum', 'address');
        static $excludeFields = array(
            'webtolead_email1', 'webtolead_email2', 'webtolead_email_opt_out', 'webtolead_invalid_email',
            'primary_address_district', 'primary_address_street_2', 'primary_address_street_3',
            'alt_address_district', 'alt_address_street_2', 'alt_address_street_3', 
            'billing_address_street_2', 'billing_address_street_3', 'billing_address_street_4',
            'shipping_address_street_2', 'shipping_address_street_3', 'shipping_address_street_4',
            'email_opt_out', 'invalid_email', 'email_addresses_non_primary', 'email1', 'email2', 'full_name', 'salutation'
            // ADD MORE FIELDS HERE
        );
        
        // Get field list array: field_name => field_label
        public function getFieldList($moduleName) {
            require_once("custom/include/utils/FieldHelper.php");
            global $beanList, $dictionary;
            
            // Available fields
            $beanName = $beanList[$moduleName];
            if($moduleName == 'Cases') $beanName = 'Case';  // A bug of Case
            $fieldList = $dictionary[$beanName]['fields'];  
            $fieldListArr = array();
            foreach ($fieldList as $fieldName => $fieldDef) {
                if(!in_array($fieldDef['name'], self::$excludeFields) && !in_array($fieldDef['type'], self::$excludeTypes)) {
                    $label = FieldHelper::getLabel($moduleName, $fieldName);
                    
                    if(!empty($label))  // Don't get the empty label
                        $fieldListArr[$fieldName] = $label;
                }
            }
            
            return $fieldListArr;
        }
        
        // Extract hilighted value into an array of params
        function unHighlight($highlightedValue) {
            $wrapperBegin = '';
            $wrapperEnd = '';
            $hasWrapper = false;
            $value = $highlightedValue;
            
            // Extract the params if the value is highlighted
            if(strpos($value, 'span') != FALSE) {
                $hasWrapper = true;
                $wrapperBegin = substr($value, 0, strpos($value, '>') + 1);
                $wrapperEnd = '</span>';
                $value = str_replace(array($wrapperBegin, $wrapperEnd), '', $value);  // Remove the wrapper from the value
            }
            
            $result = array(
                'value' => $value, 
                'has_wrapper' => $hasWrapper,
                'wrapper_begin' => $wrapperBegin,
                'wrapper_end' => $wrapperEnd,
            );
            
            return $result;    
        }

    }
?>
