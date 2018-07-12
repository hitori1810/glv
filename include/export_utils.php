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

    /*********************************************************************************

    * Description:
    * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
    * Reserved. Contributor(s): ______________________________________..
    * *******************************************************************************/

    /**
    * gets the system default delimiter or an user-preference based override
    * @return string the delimiter
    */
    function getDelimiter() {
        global $sugar_config;
        global $current_user;

        if (!empty($sugar_config['export_excel_compatible'])) {
            return "\t";
        }

        $delimiter = ','; // default to "comma"
        $userDelimiter = $current_user->getPreference('export_delimiter');
        $delimiter = empty($sugar_config['export_delimiter']) ? $delimiter : $sugar_config['export_delimiter'];
        $delimiter = empty($userDelimiter) ? $delimiter : $userDelimiter;

        return $delimiter;
    }


    /**
    * builds up a delimited string for export
    * @param string type the bean-type to export
    * @param array records an array of records if coming directly from a query
    * @return string delimited string for export
    */
    function export($type, $records = null, $members = false, $sample=false) {
        global $locale;
        global $beanList;
        global $beanFiles;
        global $current_user;
        global $app_strings;
        global $app_list_strings;
        global $timedate;
        global $mod_strings;
        global $current_language;
        $sampleRecordNum = 5;

        //Array of fields that should not be exported, and are only used for logic
        $remove_from_members = array("ea_deleted", "ear_deleted", "primary_address");
        $focus = 0;

        $focus = BeanFactory::newBean($type);
        $searchFields = array();
        $db = DBManagerFactory::getInstance();

        if($records) {
            $records = explode(',', $records);
            $records = "'" . implode("','", $records) . "'";
            $where = "{$focus->table_name}.id in ($records)";
        } elseif (isset($_REQUEST['all']) ) {
            $where = '';
        } else {
            if(!empty($_REQUEST['current_post'])) {
                $ret_array = generateSearchWhere($type, $_REQUEST['current_post']);
                $where = $ret_array['where'];
                $searchFields = $ret_array['searchFields'];
            } else {
                $where = '';
            }
        }
        $order_by = "";

        //Must order if it's ACCOUNTS module for building semi-colon separated non-primary mails
        if (strtolower($focus->module_dir) == "accounts") {
            $order_by = " id, email_addr_bean_rel.primary_address DESC";
        }

        if($focus->bean_implements('ACL')){
            if(!ACLController::checkAccess($focus->module_dir, 'export', true)){
                ACLController::displayNoAccess();
                sugar_die('');
            }
            if(ACLController::requireOwner($focus->module_dir, 'export')){
                if(!empty($where)){
                    $where .= ' AND ';
                }
                $where .= $focus->getOwnerWhere($current_user->id);
            }
        }


        if($focus->bean_implements('ACL')){
            if(!ACLController::checkAccess($focus->module_dir, 'export', true)){
                ACLController::displayNoAccess();
                sugar_die('');
            }
            $focus->addVisibilityWhere($where);
        }
        // Export entire list was broken because the where clause already has "where" in it
        // and when the query is built, it has a "where" as well, so the query was ill-formed.
        // Eliminating the "where" here so that the query can be constructed correctly.
        if($members == true){
            $query = $focus->create_export_members_query($records);
        }else{
            $beginWhere = substr(trim($where), 0, 5);
            if ($beginWhere == "where")
                $where = substr(trim($where), 5, strlen($where));
            $ret_array = create_export_query_relate_link_patch($type, $searchFields, $where);
            if(!empty($ret_array['join'])) {
                $query = $focus->create_export_query($order_by,$ret_array['where'],$ret_array['join']);
            } else {
                $query = $focus->create_export_query($order_by,$ret_array['where']);
            }
        }

        $result = null;
        $isTemplateExport = $sample;
        if($sample) {
            $result = $db->limitQuery($query, 0, $sampleRecordNum, true, $app_strings['ERR_EXPORT_TYPE'].$type.": <BR>.".$query);
            $sample = $focus->_get_num_rows_in_query($query) < 1;
        } else {
            $result = $db->query($query, true, $app_strings['ERR_EXPORT_TYPE'].$type.": <BR>.".$query);
        }

        return getExportContentFromResult($focus, $result, $members, $remove_from_members, $sample, $isTemplateExport);
    }


    /**
    * getExportContentsFromResult
    *
    * This is a function to handle the processing of generating the export contents.
    *
    * @param Mixed $focus SugarBean instance we are retrieving export results for
    * @param Mixed $result database result resource from the export SQL
    * @param bool $members used to indicate whether or not to apply filtering for header rows; false by default
    * @param array $remove_from_members Array of header columns to filter out; empty by default
    * @param bool $populate boolean used to indicate whether or not to populate with test data; false by default
    * @return string
    */
    function getExportContentFromResult(
        $focus,
        $result,
        $members=false,
        $remove_from_members=array(),
        $populate=false,
        $isTemplateExport=false
    ) {
        global $current_user, $locale, $app_strings;
        $sampleRecordNum = 5;
        $delimiter = getDelimiter();
        $timedate = TimeDate::getInstance();
        $db = DBManagerFactory::getInstance();
        $fields_array = $db->getFieldsArray($result,true);

        //set up the order on the header row
        $fields_array = get_field_order_mapping($focus->module_dir, $fields_array, true, $isTemplateExport);

        //set up labels to be used for the header row
        $field_labels = array();
        foreach($fields_array as $key=>$dbname){
            //Remove fields that are only used for logic
            if($members && (in_array($dbname, $remove_from_members)))
            {
                continue;
            }
            //default to the db name of label does not exist
            $field_labels[$key] = translateForExport($dbname,$focus);
        }

        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if ($locale->getExportCharset() == 'UTF-8' &&
            ! preg_match('/macintosh|mac os x|mac_powerpc/i', $user_agent)) // Bug 60377 - Mac Excel doesn't support UTF-8
        {
            //Bug 55520 - add BOM to the exporting CSV so any symbols are displayed correctly in Excel
            $BOM = "\xEF\xBB\xBF";
            $content = $BOM;
        }
        else
        {
            $content = '';
        }

        // setup the "header" line with proper delimiters
        $content .= "\"".implode("\"".getDelimiter()."\"", array_values($field_labels))."\"\r\n";

        $pre_id = '';

        if($populate){
            //this is a sample request with no data, so create fake datarows
            $content .= returnFakeDataRow($focus,$fields_array,$sampleRecordNum);
        }else{
            $records = array();

            //process retrieved record
            $isAdminUser = is_admin($current_user);
            while($val = $db->fetchByAssoc($result, false)) {

                //order the values in the record array
                $val = get_field_order_mapping($focus->module_dir, $val, true, $isTemplateExport);

                $new_arr = array();

                if(!$isAdminUser)
                {
                    $focus->id = (!empty($val['id']))?$val['id']:'';
                    $focus->assigned_user_id = (!empty($val['assigned_user_id']))?$val['assigned_user_id']:'' ;
                    $focus->created_by = (!empty($val['created_by']))?$val['created_by']:'';
                    $focus->ACLFilterFieldList($val, array(), array("blank_value" => true));
                }

                if($members)
                {
                    if(isset($val['id']) && $pre_id == $val['id'])
                    {
                        continue;
                    }

                    if(isset($val['ea_deleted']) && isset($val['primary_email_address']) &&
                        ($val['ea_deleted']==1 || $val['ear_deleted']==1))
                    {
                        $val['primary_email_address'] = '';
                    }
                    unset($val['ea_deleted']);
                    unset($val['ear_deleted']);
                    unset($val['primary_address']);
                }
                $pre_id = isset($val['id']) ? $val['id'] : '';

                require_once('include/SugarFields/SugarFieldHandler.php');
                foreach ($val as $key => $value)
                {
                    //getting content values depending on their types
                    $fieldNameMapKey = $fields_array[$key];

                    if (isset($focus->field_name_map[$fieldNameMapKey])  && $focus->field_name_map[$fieldNameMapKey]['type'])
                    {
                        $sfh = SugarFieldHandler::getSugarField($focus->field_name_map[$fieldNameMapKey]['type']);
                        $value = $sfh->exportSanitize($value, $focus->field_defs[$key], $focus, $val);
                    }

                    if(isset($focus->field_name_map[$fields_array[$key]]['custom_type']) && $focus->field_name_map[$fields_array[$key]]['custom_type'] == 'teamset'){
                        require_once('modules/Teams/TeamSetManager.php');
                        $value = TeamSetManager::getCommaDelimitedTeams($val['team_set_id'], !empty($val['team_id']) ? $val['team_id'] : '');
                    }

                    //replace user_name with full name if use_real_name preference setting is enabled
                    //and this is a user name field
                    $useRealNames = $current_user->getPreference('use_real_names');
                    if (!empty($useRealNames) && ($useRealNames && $useRealNames != 'off')
                        && !empty($focus->field_name_map[$fields_array[$key]]['type'])
                        && $focus->field_name_map[$fields_array[$key]]['type'] == 'relate'
                        && !empty($focus->field_name_map[$fields_array[$key]]['module'])
                        && $focus->field_name_map[$fields_array[$key]]['module'] == 'Users'
                        && !empty($focus->field_name_map[$fields_array[$key]]['rname'])
                        && $focus->field_name_map[$fields_array[$key]]['rname'] == 'user_name'
                    ) {
                        global $locale;
                        $userFocus = new User();
                        if ($userFocus->retrieve_by_string_fields(array('user_name' => $value))) {
                            $value = $locale->formatName($userFocus);
                        }
                    }


                    // Keep as $key => $value for post-processing
                    $new_arr[$key] = preg_replace("/\"/", "\"\"", $value);
                }

                // Use Bean ID as key for records
                $records[$pre_id] = $new_arr;
            }

            // Check if we're going to export non-primary emails
            if ($focus->hasEmails() && in_array('email_addresses_non_primary', $fields_array))
            {
                // $records keys are bean ids
                $keys = array_keys($records);

                // Split the ids array into chunks of size 100
                $chunks = array_chunk($keys, 100);

                foreach ($chunks as $chunk)
                {
                    // Pick all the non-primary mails for the chunk
                    $query =
                    "
                    SELECT eabr.bean_id, ea.email_address
                    FROM email_addr_bean_rel eabr
                    LEFT JOIN email_addresses ea ON ea.id = eabr.email_address_id
                    WHERE eabr.bean_module = '{$focus->module_dir}'
                    AND eabr.primary_address = '0'
                    AND eabr.bean_id IN ('" . implode("', '", $chunk) . "')
                    AND eabr.deleted != '1'
                    ORDER BY eabr.bean_id, eabr.reply_to_address, eabr.primary_address DESC
                    ";

                    $result = $db->query($query, true, $app_strings['ERR_EXPORT_TYPE'] . $focus->module_dir . ": <BR>." . $query);

                    while ($val = $db->fetchByAssoc($result, false)) {
                        if (empty($records[$val['bean_id']]['email_addresses_non_primary'])) {
                            $records[$val['bean_id']]['email_addresses_non_primary'] = $val['email_address'];
                        } else {
                            // No custom non-primary mail delimeter yet, use semi-colon
                            $records[$val['bean_id']]['email_addresses_non_primary'] .= ';' . $val['email_address'];
                        }
                    }
                }
            }

            foreach($records as $record)
            {
                $line = implode("\"" . getDelimiter() . "\"", $record);
                $line = "\"" . $line;
                $line .= "\"\r\n";
                $content .= $line;
            }

        }

        return $content;
    }


    function generateSearchWhere($module, $query)
    {//this function is similar with function prepareSearchForm() in view.list.php
        $seed = BeanFactory::newBean($module);
        if(SugarAutoLoader::fileExists('modules/'.$module.'/SearchForm.html')){
            if(SugarAutoLoader::fileExists('modules/' . $module . '/metadata/SearchFields.php')) {
                require_once('include/SearchForm/SearchForm.php');
                $searchForm = new SearchForm($module, $seed);
            }
            elseif(!empty($_SESSION['export_where'])) { //bug 26026, sometimes some module doesn't have a metadata/SearchFields.php, the searchfrom is generated in the ListView.php.
                // Currently, massupdate will not generate the where sql. It will use the sql stored in the SESSION. But this will cause bug 24722, and it cannot be avoided now.
                $where = $_SESSION['export_where'];
                $whereArr = explode (" ", trim($where));
                if ($whereArr[0] == trim('where')) {
                    $whereClean = array_shift($whereArr);
                }
                $where = implode(" ", $whereArr);
                //rrs bug: 31329 - previously this was just returning $where, but the problem is the caller of this function
                //expects the results in an array, not just a string. So rather than fixing the caller, I felt it would be best for
                //the function to return the results in a standard format.
                $ret_array['where'] = $where;
                $ret_array['searchFields'] =array();
                return $ret_array;
            }
            else {
                return;
            }
        } else {
            require_once('include/SearchForm/SearchForm2.php');

            $searchdefs_file = SugarAutoLoader::loadWithMetafiles($module, 'searchdefs');
            if($searchdefs_file) {
                require $searchdefs_file;
            }

            $searchFields = SugarAutoLoader::loadSearchFields($module);
            if(empty($searchdefs) || empty($searchFields)) {
                //for some modules, such as iframe, it has massupdate, but it doesn't have search function, the where sql should be empty.
                return;
            }
            $searchForm = new SearchForm($seed, $module);
            $searchForm->setup($searchdefs, $searchFields, 'SearchFormGeneric.tpl');
        }
        $searchForm->populateFromArray(unserialize(base64_decode($query)));
        $where_clauses = $searchForm->generateSearchWhere(true, $module);
        if (count($where_clauses) > 0 )$where = '('. implode(' ) AND ( ', $where_clauses) . ')';
        $GLOBALS['log']->info("Export Where Clause: {$where}");
        $ret_array['where'] = $where;
        $ret_array['searchFields'] = $searchForm->searchFields;
        return $ret_array;
    }
    /**
    * calls export method to build up a delimited string and some sample instructional text on how to use this file
    * @param string type the bean-type to export
    * @return string delimited string for export with some tutorial text
    */
    function exportSample($type) {
        global $app_strings;

        //first grab the
        $_REQUEST['all']=true;

        //retrieve the export content
        $content = export($type, null, false, true);

        // Add a new row and add details on removing the sample data
        // Our Importer will stop after he gets to the new row, ignoring the text below 
        return $content . "\n" . $app_strings['LBL_IMPORT_SAMPLE_FILE_TEXT'];

    }
    //this function will take in the bean and field mapping and return a proper value
    function returnFakeDataRow($focus,$field_array,$rowsToReturn = 5){

        if(empty($focus) || empty($field_array))
            return ;

        //include the file that defines $sugar_demodata
        include('install/demoData.en_us.php');

        $person_bean = false;
        if( isset($focus->first_name)){
            $person_bean = true;
        }

        global $timedate;
        $returnContent = '';
        $counter = 0;
        $new_arr = array();

        //iterate through the record creation process as many times as defined.  Each iteration will create a new row
        while($counter < $rowsToReturn){
            $counter++;
            //go through each field and populate with dummy data if possible
            foreach($field_array as $field_name){

                if(empty($focus->field_name_map[$field_name]) || empty($focus->field_name_map[$field_name]['type'])){
                    //type is not set, fill in with empty string and continue;
                    $returnContent .= '"",';
                    continue;
                }
                $field = $focus->field_name_map[$field_name];
                //fill in value according to type
                $type = $field['type'];

                switch ($type) {

                    case "id":
                    case "assigned_user_name":
                        //return new guid string
                        $returnContent .= '"'.create_guid().'",';
                        break;
                    case "int":
                        //return random number`
                        $returnContent .= '"'.mt_rand(0,4).'",';
                        break;
                    case "name":
                        //return first, last, user name, or random name string
                        if($field['name'] == 'first_name'){
                            $count = count($sugar_demodata['first_name_array']) - 1;
                            $returnContent .= '"'.$sugar_demodata['last_name_array'][mt_rand(0,$count)].'",';
                        }elseif($field['name'] == 'last_name'){
                            $count = count($sugar_demodata['last_name_array']) - 1;
                            $returnContent .= '"'.$sugar_demodata['last_name_array'][mt_rand(0,$count)].'",';
                        }elseif($field['name'] == 'user_name'){
                            $count = count($sugar_demodata['first_name_array']) - 1;
                            $returnContent .= '"'.$sugar_demodata['last_name_array'][mt_rand(0,$count)].'_'.mt_rand(1,111).'",';
                        }else{
                            //return based on bean
                            if($focus->module_dir =='Accounts'){
                                $count = count($sugar_demodata['company_name_array']) - 1;
                                $returnContent .= '"'.$sugar_demodata['company_name_array'][mt_rand(0,$count)].'",';

                            }elseif($focus->module_dir =='Bugs'){
                                $count = count($sugar_demodata['bug_seed_names']) - 1;
                                $returnContent .= '"'.$sugar_demodata['bug_seed_names'][mt_rand(0,$count)].'",';
                            }elseif($focus->module_dir =='Notes'){
                                $count = count($sugar_demodata['note_seed_names_and_Descriptions']) - 1;
                                $returnContent .= '"'.$sugar_demodata['note_seed_names_and_Descriptions'][mt_rand(0,$count)].'",';

                            }elseif($focus->module_dir =='Calls'){
                                $count = count($sugar_demodata['call_seed_data_names']) - 1;
                                $returnContent .= '"'.$sugar_demodata['call_seed_data_names'][mt_rand(0,$count)].'",';

                            }elseif($focus->module_dir =='Tasks'){
                                $count = count($sugar_demodata['task_seed_data_names']) - 1;
                                $returnContent .= '"'.$sugar_demodata['task_seed_data_names'][mt_rand(0,$count)].'",';

                            }elseif($focus->module_dir =='Meetings'){
                                $count = count($sugar_demodata['meeting_seed_data_names']) - 1;
                                $returnContent .= '"'.$sugar_demodata['meeting_seed_data_names'][mt_rand(0,$count)].'",';

                            }elseif($focus->module_dir =='ProductCategories'){
                                $count = count($sugar_demodata['productcategory_seed_data_names']) - 1;
                                $returnContent .= '"'.$sugar_demodata['productcategory_seed_data_names'][mt_rand(0,$count)].'",';


                            }elseif($focus->module_dir =='ProductTypes'){
                                $count = count($sugar_demodata['producttype_seed_data_names']) - 1;
                                $returnContent .= '"'.$sugar_demodata['producttype_seed_data_names'][mt_rand(0,$count)].'",';


                            }elseif($focus->module_dir =='ProductTemplates'){
                                $count = count($sugar_demodata['producttemplate_seed_data']) - 1;
                                $returnContent .= '"'.$sugar_demodata['producttemplate_seed_data'][mt_rand(0,$count)].'",';

                            }else{
                                $returnContent .= '"Default Name for '.$focus->module_dir.'",';

                            }

                        }
                        break;
                    case "relate":
                        if($field['name'] == 'team_name'){
                            //apply team names and user_name
                            $teams_count = count($sugar_demodata['teams']) - 1;
                            $users_count = count($sugar_demodata['users']) - 1;

                            $returnContent .= '"'.$sugar_demodata['teams'][mt_rand(0,$teams_count)]['name'].','.$sugar_demodata['users'][mt_rand(0,$users_count)]['user_name'].'",';

                        }else{
                            //apply GUID
                            $returnContent .= '"'.create_guid().'",';
                        }
                        break;
                    case "bool":
                        //return 0 or 1
                        $returnContent .= '"'.mt_rand(0,1).'",';
                        break;

                    case "text":
                        //return random text
                        $returnContent .= '"Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas porttitor congue massa. Fusce posuere, magna sed pulvinar ultricies, purus lectus malesuada libero, sit amet commodo magna eros quis urna",';
                        break;

                    case "team_list":
                        $teams_count = count($sugar_demodata['teams']) - 1;
                        //give fake team names (East,West,North,South)
                        $returnContent .= '"'.$sugar_demodata['teams'][mt_rand(0,$teams_count)]['name'].'",';
                        break;

                    case "date":
                        //return formatted date
                        $timeStamp = strtotime('now');
                        $value =    date($timedate->dbDayFormat, $timeStamp);
                        $returnContent .= '"'.$timedate->to_display_date_time($value).'",';
                        break;

                    case "datetime":
                    case "datetimecombo":
                        //return formatted date time
                        $timeStamp = strtotime('now');
                        //Start with db date
                        $value =    date($timedate->dbDayFormat.' '.$timedate->dbTimeFormat, $timeStamp);
                        //use timedate to convert to user display format
                        $value = $timedate->to_display_date_time($value);
                        //finally forma the am/pm to have a space so it can be recognized as a date field in excel
                        $value = preg_replace('/([pm|PM|am|AM]+)/', ' \1', $value);
                        $returnContent .= '"'.$value.'",';

                        break;
                    case "phone":
                        $value = '('.mt_rand(300,999).') '.mt_rand(300,999).'-'.mt_rand(1000,9999);
                        $returnContent .= '"'.$value.'",';
                        break;
                    case "varchar":
                        //process varchar for possible values
                        if($field['name'] == 'first_name'){
                            $count = count($sugar_demodata['first_name_array']) - 1;
                            $returnContent .= '"'.$sugar_demodata['last_name_array'][mt_rand(0,$count)].'",';
                        }elseif($field['name'] == 'last_name'){
                            $count = count($sugar_demodata['last_name_array']) - 1;
                            $returnContent .= '"'.$sugar_demodata['last_name_array'][mt_rand(0,$count)].'",';
                        }elseif($field['name'] == 'user_name'){
                            $count = count($sugar_demodata['first_name_array']) - 1;
                            $returnContent .= '"'.$sugar_demodata['last_name_array'][mt_rand(0,$count)].'_'.mt_rand(1,111).'",';
                        }elseif($field['name'] == 'title'){
                            $count = count($sugar_demodata['titles']) - 1;
                            $returnContent .= '"'.$sugar_demodata['titles'][mt_rand(0,$count)].'",';
                        }elseif(strpos($field['name'],'address_street')>0){
                            $count = count($sugar_demodata['street_address_array']) - 1;
                            $returnContent .= '"'.$sugar_demodata['street_address_array'][mt_rand(0,$count)].'",';
                        }elseif(strpos($field['name'],'address_city')>0){
                            $count = count($sugar_demodata['city_array']) - 1;
                            $returnContent .= '"'.$sugar_demodata['city_array'][mt_rand(0,$count)].'",';
                        }elseif(strpos($field['name'],'address_state')>0){
                            $state_arr = array('CA','NY','CO','TX','NV');
                            $count = count($state_arr) - 1;
                            $returnContent .= '"'.$state_arr[mt_rand(0,$count)].'",';
                        }elseif(strpos($field['name'],'address_postalcode')>0){
                            $returnContent .= '"'.mt_rand(12345,99999).'",';
                        }else{
                            $returnContent .= '"",';

                        }
                        break;
                    case "url":
                        $returnContent .= '"https://www.sugarcrm.com",';
                        break;

                    case "enum":
                        //get the associated enum if available
                        global $app_list_strings;

                        if(isset($focus->field_name_map[$field_name]['type']) && !empty($focus->field_name_map[$field_name]['options'])){
                            if ( !empty($app_list_strings[$focus->field_name_map[$field_name]['options']]) ) {

                                //get the values into an array
                                $dd_values = $app_list_strings[$focus->field_name_map[$field_name]['options']];
                                $dd_values = array_values($dd_values);

                                //grab the count
                                $count = count($dd_values) - 1;

                                //choose one at random
                                $returnContent .= '"'.$dd_values[mt_rand(0,$count)].'",';
                            } else{
                                //name of enum options array was found but is empty, return blank
                                $returnContent .= '"",';
                            }
                        }else{
                            //name of enum options array was not found on field, return blank
                            $returnContent .= '"",';
                        }
                        break;
                    default:
                        //type is not matched, fill in with empty string and continue;
                        $returnContent .= '"",';

                }
            }
            $returnContent .= "\r\n";
        }
        return $returnContent;
    }




    //expects the field name to translate and a bean of the type being translated (to access field map and mod_strings)
    function translateForExport($field_db_name,$focus){
        global $mod_strings,$app_strings;

        if (empty($field_db_name) || empty($focus)){
            return false;
        }

        //grab the focus module strings
        $temp_mod_strings = $mod_strings;
        global $current_language;
        $mod_strings = return_module_language($current_language, $focus->module_dir);
        $fieldLabel = '';

        //!! first check to see if we are overriding the label for export.
        if (!empty($mod_strings['LBL_EXPORT_'.strtoupper($field_db_name)])){
            //entry exists which means we are overriding this value for exporting, use this label
            $fieldLabel = $mod_strings['LBL_EXPORT_'.strtoupper($field_db_name)];

        }
        //!! next check to see if we are overriding the label for export on app_strings.
        elseif (!empty($app_strings['LBL_EXPORT_'.strtoupper($field_db_name)])){
            //entry exists which means we are overriding this value for exporting, use this label
            $fieldLabel = $app_strings['LBL_EXPORT_'.strtoupper($field_db_name)];

        }//check to see if label exists in mapping and in mod strings
        elseif (!empty($focus->field_name_map[$field_db_name]['vname']) && !empty($mod_strings[$focus->field_name_map[$field_db_name]['vname']])){
            $fieldLabel = $mod_strings[$focus->field_name_map[$field_db_name]['vname']];

        }//check to see if label exists in mapping and in app strings
        elseif (!empty($focus->field_name_map[$field_db_name]['vname']) && !empty($app_strings[$focus->field_name_map[$field_db_name]['vname']])){
            $fieldLabel = $app_strings[$focus->field_name_map[$field_db_name]['vname']];

        }//field is not in mapping, so check to see if db can be uppercased and found in mod strings
        elseif (!empty($mod_strings['LBL_'.strtoupper($field_db_name)])){
            $fieldLabel = $mod_strings['LBL_'.strtoupper($field_db_name)];

        }//check to see if db can be uppercased and found in app strings
        elseif (!empty($app_strings['LBL_'.strtoupper($field_db_name)])){
            $fieldLabel = $app_strings['LBL_'.strtoupper($field_db_name)];

        }else{
            //we could not find the label in mod_strings or app_strings based on either a mapping entry
            //or on the db_name itself or being overwritten, so default to the db name as a last resort
            $fieldLabel = $field_db_name;

        }
        //strip the label of any columns
        $fieldLabel= preg_replace("/([:]|\xEF\xBC\x9A)[\\s]*$/", '', trim($fieldLabel));

        //reset the bean mod_strings back to original import strings
        $mod_strings = $temp_mod_strings;
        return $fieldLabel;

    }

    //call this function to return the desired order to display columns for export in.
    //if you pass in an array, it will reorder the array and send back to you.  It expects the array
    //to have the db names as key values, or as labels
    function get_field_order_mapping($name='', $reorderArr = '', $exclude = true, $isTemplateExport = false){

    //define the ordering of fields, note that the key value is what is important, and should be the db field name
    $field_order_array = array();
    $field_order_array['accounts'] = array( 'name'=>'Name', 'id'=>'ID', 'website'=>'Website', 'email_address' =>'Email Address', 'email_addresses_non_primary' => 'Non Primary E-mails', 'phone_office' =>'Office Phone', 'phone_alternate' => 'Alternate Phone', 'phone_fax' => 'Fax', 'billing_address_street' => 'Billing Street', 'billing_address_city' => 'Billing City', 'billing_address_state' => 'Billing State', 'billing_address_postalcode' => 'Billing Postal Code', 'billing_address_country' => 'Billing Country', 'shipping_address_street' => 'Shipping Street', 'shipping_address_city' => 'Shipping City', 'shipping_address_state' => 'Shipping State', 'shipping_address_postalcode' => 'Shipping Postal Code', 'shipping_address_country' => 'Shipping Country', 'description' => 'Description', 'account_type' => 'Type', 'industry' =>'Industry', 'annual_revenue' => 'Annual Revenue', 'employees' => 'Employees', 'sic_code' => 'SIC Code', 'ticker_symbol' => 'Ticker Symbol', 'parent_id' => 'Parent Account ID', 'ownership' =>'Ownership', 'campaign_id' =>'Campaign ID', 'rating' =>'Rating', 'assigned_user_name' =>'Assigned to',  'assigned_user_id' =>'Assigned User ID', 'team_id' =>'Team Id', 'team_name' =>'Teams', 'team_set_id' =>'Team Set ID', 'date_entered' =>'Date Created', 'date_modified' =>'Date Modified', 'modified_user_id' =>'Modified By', 'created_by' =>'Created By', 'deleted' =>'Deleted');
    $field_order_array['contacts'] = array( 'last_name' => 'Last Name', 'first_name' => 'First Name', 'id'=>'ID', 'salutation' => 'Salutation', 'title' => 'Title', 'department' => 'Department', 'account_name' => 'Account Name', 'email_address' => 'Email Address', 'email_addresses_non_primary' => 'Non Primary E-mails for Import', 'phone_mobile' => 'Phone Mobile','phone_work' => 'Phone Work', 'phone_home' => 'Phone Home',  'phone_other' => 'Phone Other','phone_fax' => 'Phone Fax', 'primary_address_street' => 'Primary Address Street', 'primary_address_city' => 'Primary Address City', 'primary_address_state' => 'Primary Address State', 'primary_address_postalcode' => 'Primary Address Postal Code', 'primary_address_country' => 'Primary Address Country', 'alt_address_street' => 'Alternate Address Street', 'alt_address_city' => 'Alternate Address City', 'alt_address_state' => 'Alternate Address State', 'alt_address_postalcode' => 'Alternate Address Postal Code', 'alt_address_country' => 'Alternate Address Country', 'description' => 'Description', 'birthdate' => 'Birthdate', 'lead_source' => 'Lead Source', 'campaign_id' => 'campaign_id', 'do_not_call' => 'Do Not Call', 'portal_name' => 'Portal Name', 'portal_active' => 'Portal Active', 'portal_password' => 'Portal Password', 'portal_app' => 'Portal Application', 'reports_to_id' => 'Reports to ID', 'assistant' => 'Assistant', 'assistant_phone' => 'Assistant Phone', 'picture' => 'Picture', 'assigned_user_name' => 'Assigned User Name', 'assigned_user_id' => 'Assigned User ID', 'team_name' => 'Teams', 'team_id' => 'Team id', 'team_set_id' => 'Team Set ID', 'date_entered' =>'Date Created', 'date_modified' =>'Date Modified', 'modified_user_id' =>'Modified By', 'created_by' =>'Created By', 'deleted' =>'Deleted');
    $field_order_array['leads']    = array( 'last_name' => 'Last Name','first_name' => 'First Name','gender'=>'Gender', 'salutation' => 'Salutation', 'birthdate'=>'Birthdate', 'title' => 'Title', 'department' => 'Department', 'account_name' => 'Account Name', 'account_description' =>  'Account Description', 'website' =>  'Website', 'email_address' =>  'Email Address', 'email_addresses_non_primary' => 'Non Primary E-mails for Import', 'phone_mobile' =>  'Phone Mobile', 'phone_work' =>  'Phone Work', 'phone_home' =>  'Phone Home', 'phone_other' =>  'Phone Other', 'phone_fax' =>  'Phone Fax','primary_address_street' =>  'Primary Address Street', 'primary_address_city' =>  'Primary Address City', 'primary_address_state' =>  'Primary Address State','primary_address_postalcode' =>  'Primary Address Postal Code', 'primary_address_country' =>  'Primary Address Country', 'alt_address_street' =>  'Alt Address Street', 'alt_address_city' => 'Alt Address City', 'alt_address_state' =>  'Alt Address State', 'alt_address_postalcode' =>  'Alt Address Postalcode', 'alt_address_country' =>  'Alt Address Country','status' =>  'Status', 'status_description' =>  'Status Description', 'lead_source' =>  'Lead Source','lead_source_description' =>  'Lead Source Description', 'description'=>'Description','guardian_name'=>'Contact / Parent Name', 'converted' =>  'Converted', 'opportunity_name' =>  'Opportunity Name','opportunity_amount' =>  'Opportunity Amount', 'refered_by' =>  'Referred By', 'campaign_id' =>  'campaign_id', 'do_not_call' =>  'Do Not Call', 'portal_name' =>  'Portal Name','portal_app' =>  'Portal Application', 'reports_to_id' =>  'Reports To ID','assistant' =>  'Assistant', 'assistant_phone' =>  'Assistant Phone','contact_id' =>  'Contact ID', 'account_id' =>  'Account ID','opportunity_id' =>  'Opportunity ID',  'assigned_user_name' =>  'Assigned User Name','assigned_user_id' =>  'Assigned User ID', 'team_name' =>  'Teams', 'team_id' =>  'Team id', 'team_set_id' =>  'Team Set ID', 'date_entered' =>  'Date Created', 'date_modified' =>  'Date Modified', 'created_by' =>  'Created By ID','modified_user_id' =>  'Modified By ID', 'deleted' =>  'Deleted', 'potential'=>'Potential level','guardian_phone'=>'Contact / Parent Phone', 'id'=>'ID');
    $field_order_array['opportunities'] = array( 'name' => 'Opportunity Name', 'id'=>'ID', 'amount' => 'Opportunity Amount', 'currency_id' => 'Currency', 'date_closed' => 'Expected Close Date', 'sales_stage' => 'Sales Stage', 'probability' => 'Probability (%)', 'next_step' => 'Next Step', 'opportunity_type' => 'Opportunity Type', 'account_name' => 'Account Name', 'description' => 'Description', 'amount_usdollar' => 'Amount', 'lead_source' => 'Lead Source', 'campaign_id' => 'campaign_id', 'assigned_user_name' => 'Assigned User Name', 'assigned_user_id' => 'Assigned User ID', 'team_name' => 'Teams', 'team_id' => 'Team id', 'team_set_id' => 'Team Set ID', 'date_entered' => 'Date Created', 'date_modified' => 'Date Modified', 'created_by' => 'Created By ID', 'modified_user_id' => 'Modified By ID', 'deleted' => 'Deleted');
    $field_order_array['notes'] =         array( 'name' => 'Name', 'id'=>'ID', 'description' => 'Description', 'filename' => 'Attachment', 'parent_type' => 'Parent Type', 'parent_id' => 'Parent ID', 'contact_id' => 'Contact ID', 'portal_flag' => 'Display in Portal?', 'assigned_user_name' =>'Assigned to', 'assigned_user_id' => 'assigned_user_id', 'team_id' => 'Team id', 'team_set_id' => 'Team Set ID', 'date_entered' => 'Date Created', 'date_modified' => 'Date Modified',  'created_by' => 'Created By ID', 'modified_user_id' => 'Modified By ID', 'deleted' => 'Deleted' );
    $field_order_array['bugs'] =   array('bug_number' => 'Bug Number', 'id'=>'ID', 'name' => 'Subject', 'description' => 'Description', 'status' => 'Status', 'type' => 'Type', 'priority' => 'Priority', 'resolution' => 'Resolution', 'work_log' => 'Work Log', 'found_in_release' => 'Found In Release', 'fixed_in_release' => 'Fixed In Release', 'found_in_release_name' => 'Found In Release Name', 'fixed_in_release_name' => 'Fixed In Release', 'product_category' => 'Category', 'source' => 'Source', 'portal_viewable' => 'Portal Viewable', 'system_id' => 'System ID', 'assigned_user_id' => 'Assigned User ID', 'assigned_user_name' => 'Assigned User Name', 'team_name'=>'Teams', 'team_id' => 'Team id', 'team_set_id' => 'Team Set ID', 'date_entered' =>'Date Created', 'date_modified' =>'Date Modified', 'modified_user_id' =>'Modified By', 'created_by' =>'Created By', 'deleted' =>'Deleted');
    $field_order_array['tasks'] =   array( 'name'=>'Subject', 'id'=>'ID', 'description'=>'Description', 'status'=>'Status', 'date_start'=>'Date Start', 'date_due'=>'Date Due','priority'=>'Priority', 'parent_type'=>'Parent Type', 'parent_id'=>'Parent ID', 'contact_id'=>'Contact ID', 'assigned_user_name' =>'Assigned to', 'assigned_user_id'=>'Assigned User ID', 'team_name'=>'Teams', 'team_id'=>'Team id', 'team_set_id'=>'Team Set ID', 'date_entered'=>'Date Created', 'date_modified'=>'Date Modified', 'created_by'=>'Created By ID', 'modified_user_id'=>'Modified By ID', 'deleted'=>'Deleted');
    $field_order_array['calls'] =   array( 'name'=>'Subject', 'id'=>'ID', 'description'=>'Description', 'status'=>'Status', 'direction'=>'Direction', 'date_start'=>'Date', 'date_end'=>'Date End', 'duration_hours'=>'Duration Hours', 'duration_minutes'=>'Duration Minutes', 'reminder_time'=>'Reminder Time', 'parent_type'=>'Parent Type', 'parent_id'=>'Parent ID', 'outlook_id'=>'Outlook ID', 'assigned_user_name' =>'Assigned to', 'assigned_user_id'=>'Assigned User ID', 'team_name'=>'Teams', 'team_id'=>'Team id', 'team_set_id'=>'Team Set ID', 'date_entered'=>'Date Created', 'date_modified'=>'Date Modified', 'created_by'=>'Created By ID', 'modified_user_id'=>'Modified By ID', 'deleted'=>'Deleted');
    $field_order_array['meetings'] =array( 'name'=>'Subject','date_start'=>'Date Start','date_end'=>'Date End','duration_hours'=>'Duration Hours',  'duration_minutes'=>'Duration Minutes',  'description'=>'Description', 'type'=>'Meeting Type','assigned_user_name' =>'Assigned to', 'team_name' => 'Teams', 'status'=>'Status', 'location'=>'Location',  'reminder_time'=>'Reminder Time',  'external_id'=>'External ID', 'password'=>'Meeting Password', 'join_url'=>'Join Url', 'host_url'=>'Host Url', 'displayed_url'=>'Displayed Url', 'creator'=>'Meeting Creator', 'parent_type'=>'Related to', 'parent_id'=>'Related to', 'outlook_id'=>'Outlook ID','assigned_user_name' =>'Assigned to','assigned_user_id' => 'Assigned User ID', 'team_name' => 'Teams', 'team_id' => 'Team id', 'team_set_id' => 'Team Set ID', 'date_entered' => 'Date Created', 'date_modified' => 'Date Modified', 'created_by' => 'Created By ID', 'modified_user_id' => 'Modified By ID', 'deleted' => 'Deleted','id'=>'ID');  
    $field_order_array['cases'] =array( 'case_number'=>'Case Number', 'id'=>'ID', 'name'=>'Subject', 'description'=>'Description', 'status'=>'Status', 'type'=>'Type', 'priority'=>'Priority', 'resolution'=>'Resolution', 'work_log'=>'Work Log', 'portal_viewable'=>'Portal Viewable', 'account_name'=>'Account Name', 'account_id'=>'Account ID', 'assigned_user_id'=>'Assigned User ID', 'team_name'=>'Teams', 'team_id'=>'Team id', 'team_set_id'=>'Team Set ID', 'date_entered'=>'Date Created', 'date_modified'=>'Date Modified', 'created_by'=>'Created By ID', 'modified_user_id'=>'Modified By ID', 'deleted'=>'Deleted');
    $field_order_array['forecastworksheet'] = array('commit_stage' => 'Commit Stage', 'name' => 'Name', 'date_closed' => 'Expected Close', 'sales_stage' => 'Stage', 'probability' => 'Probability', 'likely_case' => 'Likely Case', 'best_case' => 'Best Case', 'worst_case' => 'Worst Case', 'id' => 'ID', 'product_id' => 'Product ID', 'assigned_user_id' => 'Assigned To', 'amount' => 'Amount', 'worksheet_id' => 'Worksheet ID', 'currency_id' => 'Currency ID', 'base_rate' => 'Base Rate');
    $field_order_array['forecastmanagerworksheet'] = array('name' => 'Name', 'quota' => 'Quota', 'likely_case' => 'Likely Case', 'likely_case_adjusted' => 'Likely Adjusted', 'best_case' => 'Best Case', 'best_case_adjusted' => 'Best Adjusted', 'worst_case' => 'Worst Case', 'worst_case_adjusted' => 'Worst Adjusted', 'amount' => 'Amount', 'quota_id' => 'Quota ID', 'forecast_id' => 'Forecast ID', 'worksheet_id' => 'Worksheet ID', 'currency_id' => 'Currency ID', 'base_rate' => 'Base Rate', 'show_opps' => 'Show Opps', 'timeperiod_id' => 'Timeperiod ID', 'user_id' => 'User ID', 'date_modified' => 'Date Modified');
    $field_order_array['prospects'] =array(  'last_name'=>'Last Name','first_name'=>'First Name','gender'=>'Gender', 'birthdate' => 'Birthdate', 'salutation'=>'Salutation', 'title'=>'Title', 'department'=>'Department', 'account_name'=>'Account Name', 'email_address'=>'Email Address', 'email_addresses_non_primary' => 'Non Primary E-mails for Import', 'phone_mobile' => 'Phone Mobile', 'phone_work' => 'Phone Work', 'phone_home' => 'Phone Home', 'phone_other' => 'Phone Other', 'phone_fax' => 'Phone Fax',  'primary_address_street' => 'Primary Address Street', 'primary_address_city' => 'Primary Address City', 'primary_address_state' => 'Primary Address State', 'primary_address_postalcode' => 'Primary Address Postal Code', 'primary_address_country' => 'Primary Address Country', 'alt_address_street' => 'Alternate Address Street', 'alt_address_city' => 'Alternate Address City', 'alt_address_state' => 'Alternate Address State', 'alt_address_postalcode' => 'Alternate Address Postal Code', 'alt_address_country' => 'Alternate Address Country', 'description' => 'Description', 'assistant'=>'Assistant', 'assistant_phone'=>'Assistant Phone', 'campaign_id'=>'campaign_id', 'tracker_key'=>'Tracker Key', 'do_not_call'=>'Do Not Call', 'lead_id'=>'Lead Id', 'assigned_user_name'=>'Assigned User Name','guardian_name'=>'Contact / Parent Name','guardian_phone'=>'Contact / Parent Phone', 'assigned_user_id'=>'Assigned User ID', 'team_id' =>'Team Id', 'team_name' =>'Teams', 'team_set_id' =>'Team Set ID', 'date_entered' =>'Date Created', 'date_modified' =>'Date Modified', 'modified_user_id' =>'Modified By', 'created_by' =>'Created By', 'deleted' =>'Deleted', 'id'=>'ID');
    $fields_to_exclude = array();
    $fields_to_exclude['accounts'] = array('account_name');
    $fields_to_exclude['bugs'] = array('system_id');
    $fields_to_exclude['cases'] = array('system_id', 'modified_by_name', 'modified_by_name_owner', 'modified_by_name_mod', 'created_by_name', 'created_by_name_owner', 'created_by_name_mod', 'assigned_user_name', 'assigned_user_name_owner', 'assigned_user_name_mod', 'team_count', 'team_count_owner', 'team_count_mod', 'team_name_owner', 'team_name_mod', 'account_name_owner', 'account_name_mod', 'modified_user_name',  'modified_user_name_owner', 'modified_user_name_mod');
    $fields_to_exclude['notes'] = array('last_name','first_name','file_mime_type','embed_flag');
    $fields_to_exclude['tasks'] = array('date_start_flag', 'date_due_flag');
    $field_to_exclude['forecastworksheet'] = array('version'=>'version');
    $field_to_exclude['forecastmanagerworksheet'] = array('version'=>'version', 'label'=>'label');

    if ($isTemplateExport == true) {
        $fields_to_exclude['leads'] = array('modified_user_id', 'date_entered',  'potential','created_by','date_modified','team_set_id', 'team_id', 'assigned_user_id', 'opportunity_id', 'account_id', 'contact_id', 'assistant_phone', 'assistant','assigned_user_name', 'reports_to_id', 'portal_app', 'portal_name', 'do_not_call', 'campaign_id','refered_by','opportunity_amount','opportunity_name','converted','description','lead_source_description','status_description','status','alt_address_country','alt_address_postalcode','alt_address_state','alt_address_city','alt_address_street','primary_address_country','primary_address_postalcode','primary_address_state','primary_address_city','phone_fax','phone_other','phone_home' ,'email_addresses_non_primary','website','account_description','account_name','department','title','salutation','deleted','preferred_language','nationality','occupation','speaking','writing','listening','reading','gpa','issues_content','issues_fee','issues_other','level','working_date','stage','voucher_code','school_name','nick_name','other_mobile','class_name','class_year','preferred_kind_of_course','guardian_email','company_name','phone_work','study_apollo_before','study_other_before','time_study_english','study_with_before','strong_skills','weak_skills','expectation','other_note','contact_rela','guardian_phone','team_type','describe_relationship'); 
        $fields_to_exclude['prospects'] = array('salutation','title', 'department',  'email_addresses_non_primary' , 'phone_work', 'phone_home', 'phone_other', 'phone_fax',  'primary_address_city', 'primary_address_state', 'primary_address_postalcode', 'primary_address_country' , 'alt_address_street', 'alt_address_city', 'alt_address_state', 'alt_address_postalcode', 'alt_address_country' , 'description' , 'assistant', 'do_not_call', 'lead_id','assigned_user_id', 'team_id', 'team_set_id', 'date_modified', 'modified_user_id', 'created_by', 'deleted', 'converted','status','tracker_key','lead_source','lead_source_description','campaign_id','assistant_phone','account_name','assigned_user_name','date_entered', 'team_type','guardian_phone');
        $fields_to_exclude['meetings'] = array('name', 'status', 'location', 'reminder_time',  'external_id', 'password', 'join_url', 'host_url', 'displayed_url', 'creator', 'parent_type', 'parent_id', 'outlook_id','assigned_user_id', 'team_id', 'team_set_id', 'date_entered', 'date_modified', 'created_by' , 'modified_user_id', 'deleted','email_reminder_time','session_status','sequence','email_reminder_sent','repeat_type','repeat_interval','repeat_dow','repeat_until','repeat_count','repeat_parent_id','recurring_source', 'type', 'option_date3', 'option_date2','meeting_module','option_date1','class_id','teacher_id','teacher_cover_id','room_id','duration','delivery_hour','teaching_hour','week_date','time_start_end','num_of_student_last_week','num_of_student_this_week','week_per_total','lesson_number','ju_class_id','ju_teacher_id','ju_room_id','type_of_class','first_time','first_duration','time_range');
        }

        if(!empty($name) && !empty($reorderArr) && is_array($reorderArr)){

            //make sure reorderArr has values as keys, if not then iterate through and assign the value as the key
            $newReorder = array();
            foreach($reorderArr as $rk=> $rv){
                if(is_int($rk)){
                    $newReorder[$rv]=$rv;
                }else{
                    $newReorder[$rk]=$rv;
                }
            }

            //if module is not defined, lets default the order to another module of the same type
            //this would apply mostly to custom modules
            if(!isset($field_order_array[strtolower($name)]) && isset($_REQUEST['module']))
            {
                if($name == 'ProspectLists')
                {
                    return $newReorder;
                }

                $focus = BeanFactory::getBean($_REQUEST['module']);

                //if module is of type person
                if($focus instanceof Person){
                    $name = 'contacts';
                }
                //if module is of type company
                else if ($focus instanceof Company){
                    $name = 'accounts';
                }
                //if module is of type Sale
                else if ($focus instanceof Sale){
                    $name = 'opportunities';
                }//if module is of type File
                else if ($focus instanceof Issue){
                    $name = 'bugs';
                }//all others including type File can use basic
                else{
                    $name = 'notes';
                }

            }

            //lets iterate through and create a reordered temporary array using
            //the  newly formatted copy of passed in array
            $temp_result_arr = array();
            $lname = strtolower($name);
            if(isset($field_order_array[$lname])) {
                foreach($field_order_array[$lname] as $fk=> $fv){
                    //if the value exists as a key in the passed in array, add to temp array and remove from reorder array.
                    //Do not force into the temp array as we don't want to violate acl's
                    if(array_key_exists($fk,$newReorder)){
                        $temp_result_arr[$fk] = $newReorder[$fk];
                        unset($newReorder[$fk]);
                    }
                }
            }

            //add in all the left over values that were not in our ordered list
            //array_splice($temp_result_arr, count($temp_result_arr), 0, $newReorder);
            foreach($newReorder as $nrk=>$nrv){
                $temp_result_arr[$nrk] = $nrv;
            }

            if($exclude){
                //Some arrays have values we wish to exclude
                if (isset($fields_to_exclude[$lname])){
                    foreach($fields_to_exclude[$lname] as $exclude_field){
                        unset($temp_result_arr[$exclude_field]);
                    }
                }
            }

            //return temp ordered list
            return $temp_result_arr;
        }

        //if no array was passed in, pass back either the list of ordered columns by module, or the entire order array
        if(empty($name)){
            return $field_order_array;
        }else{
            return $field_order_array[strtolower($name)];
        }

    }
