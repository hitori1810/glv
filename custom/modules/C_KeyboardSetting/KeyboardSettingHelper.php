<?php

    class KeyboardSettingHelper {

        // Field name, types and sources that will not be used to check for dupplication
        static $excludeTypes = array('id', 'link', 'relate', 'enum', 'multienum', 'radioenum');
        static $excludeFields = array(
            'webtolead_email1', 'webtolead_email2', 'webtolead_email_opt_out', 'webtolead_invalid_email',
            'primary_address_district', 'primary_address_street_2', 'primary_address_street_3',
            'alt_address_district', 'alt_address_street_2', 'alt_address_street_3', 
            'billing_address_street_2', 'billing_address_street_3', 'billing_address_street_4',
            'shipping_address_street_2', 'shipping_address_street_3', 'shipping_address_street_4',
            'email_opt_out', 'invalid_email', 'email_addresses_non_primary', 'email1', 'email2'
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
                    if(isset($fieldList['first_name']) && isset($fieldList['last_name'])) {
                        if($fieldName == 'name' || $fieldName == 'full_name') continue;  // Don't show the Name field of Person module   
                    }
                    
                    $label = FieldHelper::getLabel($moduleName, $fieldName);
                    
                    if(!empty($label))  // Don't get the empty label
                        $fieldListArr[$fieldName] = $label;
                }
            }
            
            return $fieldListArr;
        }
        
    }
?>
