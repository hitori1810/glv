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

require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');

class SugarFieldDatetime extends SugarFieldBase {

    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {

        // Create Smarty variables for the Calendar picker widget
        if(!isset($displayParams['showMinutesDropdown'])) {
           $displayParams['showMinutesDropdown'] = false;
        }

        if(!isset($displayParams['showHoursDropdown'])) {
           $displayParams['showHoursDropdown'] = false;
        }

        if(!isset($displayParams['showNoneCheckbox'])) {
           $displayParams['showNoneCheckbox'] = false;
        }

        if(!isset($displayParams['showFormats'])) {
           $displayParams['showFormats'] = false;
        }

        if(!isset($displayParams['hiddeCalendar'])) {
           $displayParams['hiddeCalendar'] = false;
        }
        
        // jpereira@dri - #Bug49552 - Datetime field unable to follow parent class methods
        //jchi , bug #24557 , 10/31/2008
        if(isset($vardef['name']) && ($vardef['name'] == 'date_entered' || $vardef['name'] == 'date_modified')){
            return $this->getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
        }
        //end
        return parent::getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
        // ~ jpereira@dri - #Bug49552 - Datetime field unable to follow parent class methods
    }

    function getImportViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex)
    {
        $displayParams['showMinutesDropdown'] = false;
        $displayParams['showHoursDropdown'] = false;
        $displayParams['showNoneCheckbox'] = false;
        $displayParams['showFormats'] = true;
        $displayParams['hiddeCalendar'] = false;

        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch($this->findTemplate('EditView'));
    }

    function getWirelessEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
    	global $timedate;
    	$datetime_prefs = $GLOBALS['current_user']->getUserDateTimePreferences();
    	$datetime = explode(' ', $vardef['value']);

		// format date and time to db format
		$date_start = $timedate->swap_formats($datetime[0], $datetime_prefs['date'], $timedate->dbDayFormat);

    	// pass date parameters to smarty
    	if ($datetime_prefs['date'] == 'Y-m-d' || $datetime_prefs['date'] == 'Y/m/d' || $datetime_prefs['date'] == 'Y.m.d'){
    		$this->ss->assign('field_order', 'YMD');
    	}
    	else if ($datetime_prefs['date'] == 'd-m-Y' || $datetime_prefs['date'] == 'd/m/Y' || $datetime_prefs['date'] == 'd.m.Y'){
    		$this->ss->assign('field_order', 'DMY');
    	}
    	else{
    		$this->ss->assign('field_order', 'MDY');
    	}
    	$this->ss->assign('date_start', $date_start);

    	$this->setup($parentFieldArray, $vardef, $displayParams, $tabindex, false);
    	return $this->fetch($this->findTemplate('WirelessEditView'));
    }

    function getSearchViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        if($this->isRangeSearchView($vardef)) {
           $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
           $id = isset($displayParams['idName']) ? $displayParams['idName'] : $vardef['name'];
           $this->ss->assign('original_id', "{$id}");
           $this->ss->assign('id_range', "range_{$id}");
           $this->ss->assign('id_range_start', "start_range_{$id}");
           $this->ss->assign('id_range_end', "end_range_{$id}");
           $this->ss->assign('id_range_choice', "{$id}_range_choice");
           return $this->fetch('include/SugarFields/Fields/Datetimecombo/RangeSearchForm.tpl');
        }
    	return $this->getSmartyView($parentFieldArray, $vardef, $displayParams, $tabindex, 'EditView');
    }

    public function getEmailTemplateValue($inputField, $vardef, $context = null){
        global $timedate;
        // This does not return a smarty section, instead it returns a direct value
        if(isset($context['notify_user'])) {
            $user = $context['notify_user'];
        } else {
            $user = $GLOBALS['current_user'];
        }
        if($vardef['type'] == 'date') {
            if(!$timedate->check_matching_format($inputField, TimeDate::DB_DATE_FORMAT)) {
                return $inputField;
            }
            // convert without TZ
            return $timedate->to_display($inputField, $timedate->get_db_date_format(),  $timedate->get_date_format($user));
        } else {
            if(!$timedate->check_matching_format($inputField, TimeDate::DB_DATETIME_FORMAT)) {
                return $inputField;
            }
            return $timedate->to_display_date_time($inputField, true, true, $user);
        }
    }

    public function save($bean, $inputData, $field, $def, $prefix = '') {
        global $timedate;
        if ( !isset($inputData[$prefix.$field]) ) {
            return;
        }

        $offset = strlen(trim($inputData[$prefix.$field])) < 11 ? false : true;
        if ($timedate->check_matching_format($inputData[$prefix.$field], TimeDate::DB_DATE_FORMAT)) {
            $bean->$field = $inputData[$prefix.$field];
        } else {
            $bean->$field = $timedate->to_db_date($inputData[$prefix.$field], $offset);
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
        global $timedate;

        $format = $timedate->merge_date_time($settings->dateformat, $settings->timeformat);

        if ( !$timedate->check_matching_format($value, $format) ) {
            $parts = $timedate->split_date_time($value);
            if(empty($parts[0])) {
               $datepart = $timedate->getNow()->format($settings->dateformat);
            }
            else {
               $datepart = $parts[0];
            }
            if(empty($parts[1])) {
                $timepart = $timedate->fromTimestamp(0)->format($settings->timeformat);
            } else {
                $timepart = $parts[1];
                // see if we can get by stripping the seconds
                if(strpos($settings->timeformat, 's') === false) {
                    $sep = $timedate->timeSeparatorFormat($settings->timeformat);
                    // We are assuming here seconds are the last component, which
                    // is kind of reasonable - no sane time format puts seconds first
                    $timeparts = explode($sep, $timepart);
                    if(!empty($timeparts[2])) {
                        $timepart = join($sep, array($timeparts[0], $timeparts[1]));
                    }
                }
            }

            $value = $timedate->merge_date_time($datepart, $timepart);
            if ( !$timedate->check_matching_format($value, $format) ) {
                return false;
            }
        }

        try {
            $date = SugarDateTime::createFromFormat($format, $value, new DateTimeZone($settings->timezone));
        } catch(Exception $e) {
            return false;
        }
        return $date->asDb();
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
        $timedate =  TimeDate::getInstance();
        $db = DBManagerFactory::getInstance();

        //If it's in ISO format, convert it to db format
        if(preg_match('/(\d{4})\-?(\d{2})\-?(\d{2})T(\d{2}):?(\d{2}):?(\d{2})\.?\d*([Z+-]?)(\d{0,2}):?(\d{0,2})/i', $value)) {
           $value = $timedate->fromIso($value)->asDb();
        } else if(preg_match("/" . TimeDate::DB_DATE_FORMAT . "/", $value)) {
           $value = $timedate->fromDbDate($value)->asDb();
        }

        $value = $timedate->to_display_date_time($db->fromConvert($value, 'datetime'));
        return preg_replace('/([pm|PM|am|AM]+)/', ' \1', $value);
    }

    function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        global $timedate,$current_user;

        //check to see if the date is in the proper format
        $user_dateFormat = $timedate->get_date_format();
        if(!empty($vardef['value']) && !$timedate->check_matching_format( $vardef['value'],$user_dateFormat)){

            //date is not in proper user format, so get the SugarDateTiemObject and inject the vardef with a new element
            $sdt = $timedate->fromString($vardef['value'],$current_user);

            if (!empty($sdt)) {
                //the new 'date_formatted_value' array element will be used in include/SugarFields/Fields/Datetime/DetailView.tpl if it exists
                $vardef['date_formatted_value'] = $timedate->asUserDate($sdt,$current_user);
            }
        }

        return $this->getSmartyView($parentFieldArray, $vardef, $displayParams, $tabindex, 'DetailView');
    }

    /**
     * @see SugarFieldBase::apiFormatField
     */
    public function apiFormatField(array &$data, SugarBean $bean, array $args, $fieldName, $properties)
    {
        global $timedate;

        $date = $timedate->fromUserType($bean->$fieldName,$properties['type']);
        if ( $date == null ) {
            // Could not parse date... try DB format
            $date = $timedate->fromDbType($bean->$fieldName,$properties['type']);
            if ( $date == null ) {
                return;
            }
        }

        if ( $properties['type'] == 'date' ) {
            // It's just a date, not a datetime
            $data[$fieldName] = $timedate->asIsoDate($date);
        } else if ( $properties['type'] == 'time' ) {
            $data[$fieldName] = $timedate->asIsoTime($date);
        } else {
            $data[$fieldName] = $timedate->asIso($date);
        }
    }

    /**
     * @see SugarFieldBase::apiSave
     */
    public function apiSave(SugarBean $bean, array $params, $field, $properties) {
        global $timedate;

        $inputDate = $params[$field];

        if ( empty($inputDate) ) {
            $bean->$field = '';
            return;
        }

        if ( $properties['type'] == 'date' ) {
            // It's just a date, not a datetime
            $date = $timedate->fromIsoDate($inputDate);
        } else if ( $properties['type'] == 'time' ) {
            $date = $timedate->fromIsoTime($inputDate);
        } else {
            $date = $timedate->fromIso($inputDate);
        }

        if ( !$date ) {
            require_once('include/api/SugarApiException.php');
            throw new SugarApiExceptionInvalidParameter("Did not recognize $field as a date/time, it looked like {$params[$field]}");
        }


        $bean->$field = $timedate->asDbType($date,$properties['type']);
    }


}
