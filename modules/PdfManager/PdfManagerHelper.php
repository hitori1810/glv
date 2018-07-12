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



/*
 * Function used in vardefs to retrieve options list
 */
function getPdfManagerAvailableModules()
{
    return PdfManagerHelper::getAvailableModules();
}

class PdfManagerHelper
{

    /**
     * Returns a list of banned Fields and Links by module for PdfManager
     *
     * @return array
     */

    public static function getBannnedFieldsAndLinks()
    {

        $bannedPdfManagerFieldsAndLinks = array();

        foreach(SugarAutoLoader::existingCustom('modules/PdfManager/metadata/pdfmanagermodulesdefs.php') as $file) {
            include $file;
        }

        return $bannedPdfManagerFieldsAndLinks;
    }


    /**
     * Returns a list of banned modules for PdfManager
     *
     * @return array
     */

    public static function getBannnedModules()
    {

        $bannedPdfManagerModules = array();

        foreach(SugarAutoLoader::existingCustom('modules/PdfManager/metadata/pdfmanagermodulesdefs.php') as $file) {
            include $file;
        }

        return $bannedPdfManagerModules;
    }

    /**
     * Returns a list of available modules for PdfManager
     *
     * @return array
     */
    public static function getAvailableModules()
    {

        $bannedModules = PdfManagerHelper::getBannnedModules();

        $module_names = array_change_key_case ($GLOBALS['app_list_strings']['moduleList']);
        require_once 'modules/ModuleBuilder/Module/StudioBrowser.php';
        $studio_browser = new StudioBrowser();
        $studio_browser->loadModules();
        $studio_modules = array_keys($studio_browser->modules);
        foreach ($studio_modules as $module_name) {
            if (!in_array($module_name, $bannedModules)) {
                $available_modules[$module_name] = isset($module_names[strtolower($module_name)]) ? $module_names[strtolower($module_name)] : strtolower($module_name);
            }
        }
        asort($available_modules);

        return $available_modules;
    }

    /**
     * Returns a public name of a module
     *
     * @return string
     */
    public static function getModuleName($moduleKey)
    {
        return translate('moduleList', 'application', $moduleKey);
    }

    /**
     * Takes an module name and returns a list of fields and links available for this module in PdfManager
     *
     * @param  string  $moduleName
     * @param  boolean $addLinks
     * @return array
     */
    public static function getFields($moduleName, $addLinks = false)
    {
        $fieldsForSelectedModule = array();
        if (!empty($moduleName)) {
            // Retrieve the list of field
            $fieldsForSelectedModule = PdfManagerHelper::getRelatableFieldsForLink(array('module' => $moduleName));
            asort($fieldsForSelectedModule);

            if (!empty($fieldsForSelectedModule) && $addLinks) {
                $linksForSelectedModule = PdfManagerHelper::getLinksForModule($moduleName);
                if (count($linksForSelectedModule) > 0) {
                    //$fieldsForSelectedModule[''] = '-----';
                    $linksFieldsForSelectedModule = array();
                    foreach ($linksForSelectedModule as $linkName => $linkDef) {
                        $linksFieldsForSelectedModule['pdfManagerRelateLink_' . $linkName] = $linkDef['label'];
                    }
                    asort($linksFieldsForSelectedModule);
                    //$fieldsForSelectedModule += $linksFieldsForSelectedModule;
                    $fieldsForSelectedModule = array(
                        translate('LBL_FIELDS_LIST', 'PdfManager') => $fieldsForSelectedModule,
                        translate('LBL_LINK_LIST', 'PdfManager') => $linksFieldsForSelectedModule,
                    );

                }
            }
        }

        return $fieldsForSelectedModule;
    }

    public static function getLinksForModule($module)
    {
        global $app_list_strings;

        $bannedFieldsAndLinks = PdfManagerHelper::getBannnedFieldsAndLinks();

        $focus = BeanFactory::newBean($module);
        $focus->id = create_guid();

        $fields = PdfManagerHelper::cleanFields($focus->field_defs, $module);

        $links = array();

        if ($module == 'Quotes') {
            $focusBundle = BeanFactory::newBean('ProductBundles');
            $focusBundle->id = create_guid();
            $name = 'products';
            $def = $focusBundle->field_defs[$name];
            $focusBundle->load_relationship($name);
            $fieldsBundle = PdfManagerHelper::cleanFields($focusBundle->field_defs, 'Products');
            $label = empty($def['vname']) ? $name : str_replace(":" , "", translate($def['vname'], $module));
            $relatedModule = (!empty($app_list_strings['moduleListSingular']['Product'])) ?
                                $app_list_strings['moduleListSingular']['Product'] : 'Product';
            $links[$name] = array (
                "label" => "$label ($relatedModule)",
                "module" => $relatedModule
            );

            $name = 'product_bundles';
            $def = $focus->field_defs[$name];
            $focus->load_relationship($name);
            $relatedModule = (!empty($app_list_strings['moduleListSingular'][$focus->$name->getRelatedModuleName()])) ?
                                $app_list_strings['moduleListSingular'][$focus->$name->getRelatedModuleName()] : $focus->$name->getRelatedModuleName();
            $label = empty($def['vname']) ? $name : str_replace(":" , "", translate($def['vname'], $module));
            $links[$name] = array(
                "label" => "$label ($relatedModule)",
                "module" => $relatedModule
            );

        }

        //Next, get a list of all links and the related modules
        foreach ($fields as $val) {
            $name = $val[0];
            $def = $focus->field_defs[$name];
            if ($val[1] == "relate" && $focus->load_relationship($name)) {
                $relatedModule = (!empty($app_list_strings['moduleListSingular'][$focus->$name->getRelatedModuleName()])) ?
                                $app_list_strings['moduleListSingular'][$focus->$name->getRelatedModuleName()] : $focus->$name->getRelatedModuleName();
                if (
                    (isset($def['link_type']) && $def['link_type'] == 'one') ||
                    ($focus->$name->_relationship->relationship_type == 'one-to-one') ||
                    ($focus->$name->_relationship->relationship_type == 'one-to-many' && !$focus->$name->_get_bean_position()) ||
                    ($focus->$name->_relationship->relationship_type == 'many-to-one' && $focus->$name->_get_bean_position()) ||
                    ($focus->$name->_relationship->relationship_type == 'many-to-many' && !isset($def['side']) && $focus->$name->_get_link_table_definition($focus->$name->_relationship_name, 'true_relationship_type') == 'one-to-many' && !$focus->$name->_get_bean_position()) ||
                    ($focus->$name->_relationship->relationship_type == 'many-to-many' && !isset($def['side']) && $focus->$name->_get_link_table_definition($focus->$name->_relationship_name, 'true_relationship_type') == 'many-to-one' && $focus->$name->_get_bean_position()) ||
                    ($focus->$name->_relationship->relationship_type == 'many-to-many' && !isset($def['side']) && $focus->$name->_get_link_table_definition($focus->$name->_relationship_name, 'true_relationship_type') == 'one-to-one' && $focus->$name->_get_bean_position())
                ) {
                    //MB will sometimes produce extra link fields that we need to ignore
                    if (!empty($def['side']) && (substr($name, -4) == "_ida" || substr($name, -4) == "_idb")) {
                        continue;
                    }

                    if (isset($bannedFieldsAndLinks[$module]) && isset($bannedFieldsAndLinks[$module]['relationships']) && in_array($name, $bannedFieldsAndLinks[$module]['relationships'])) {
                        continue;
                    }

                    $label = empty($def['vname']) ? $name : str_replace(":" , "", translate($def['vname'], $module));
                    $links[$name] = array(
                        "label" => "$label ($relatedModule)",
                        "module" => $relatedModule
                    );
                }
            }
        }

        return $links;
    }

    /**
     * @static
     * @param  array     $link
     * @param  MBPackage $package
     * @param  array     $allowedTypes list of types to allow as related fields
     * @return array
     */
    public static function getRelatableFieldsForLink($link, $package = null, $allowedTypes = array())
    {
        $rfields = array();
        $relatedModule = $link["module"];
        $mbModule = null;
        if (!empty($package)) {
            $mbModule = $package->getModuleByFullName($relatedModule);
        }
        //First, create a dummy bean to access the relationship info
        if (empty($mbModule)) {
            $relatedBean = BeanFactory::getBean($relatedModule);
            $field_defs = $relatedBean->field_defs;
        } else {
            $field_defs = $mbModule->getVardefs(false);
            $field_defs = $field_defs['fields'];
        }

        // Adding special fields not available in vardefs
        if ($relatedModule == 'Quotes') {
            $field_defs['taxrate_value'] = array(
                'name' => 'taxrate_value',
                'vname' => 'LBL_TAXRATE',
                'type' => 'decimal'
            );
            $field_defs['currency_iso'] = array(
                'name' => 'currency_iso',
                'vname' => 'LBL_CURRENCY',
                'type' => 'varchar'
            );
        } elseif ($relatedModule == 'Products') {
            $field_defs['discount_amount'] = array(
                'name' => 'discount_amount',
                'vname' => 'LBL_EXT_PRICE',
                'type' => 'decimal'
            );
        }

        $relatedFields = PdfManagerHelper::cleanFields($field_defs, $relatedModule, false, true);
        foreach ($relatedFields as $val) {
            $name = $val[0];
            //Rollups must be either a number or a possible number (like a string) to roll up
            if (!empty($allowedTypes) && !in_array($val[1], $allowedTypes)) {
                continue;
            }

            $def = $field_defs[$name];
            if (empty($mbModule)) {
                $rfields[$name] = empty($def['vname']) ? $name : str_replace(":", "", translate($def['vname'], $relatedModule));
            } else {
                $rfields[$name] = empty($def['vname']) ? $name : str_replace(":", "", $mbModule->mblanguage->translate($def['vname']));
            }
            //Strip the ":" from any labels that have one
            if (substr($rfields[$name], -1) == ":") {
                $rfields[$name] = substr($rfields[$name], 0, strlen($rfields[$name]) - 1);
            }
        }

        return $rfields;
    }

    /**
     * Takes an array of field defs and returns a formated list of fields that are valid for use in select list.
     *
     * @param  array $fieldDef
     * @return array
     */
    public static function cleanFields($fieldDef, $moduleName = '', $includeLinks = true, $forRelatedField = false, $returnKeys = false)
    {

        $bannedFieldsAndLinks = PdfManagerHelper::getBannnedFieldsAndLinks();
        $fieldArray = array();
        foreach ($fieldDef as $fieldName => $def) {
            if (!is_array($def) || $fieldName == 'deleted' || empty($def['type'])) {
                continue;
            }

            if (isset($bannedFieldsAndLinks[$moduleName]) && isset($bannedFieldsAndLinks[$moduleName]['fields']) && in_array($fieldName, $bannedFieldsAndLinks[$moduleName]['fields'])) {
                continue;
            }

            //Check the studio property of the field def.
            if (isset($def['studio']) && (self::isFalse($def['studio']) || (is_array($def['studio']) && (
                (isset($def['studio']['formula']) && self::isFalse($def['studio']['formula'])) ||
                ($forRelatedField && isset($def['studio']['related']) && self::isFalse($def['studio']['related']))
            ))))
            {
                continue;
            }

            switch ($def['type']) {
                case "int":
                case "float":
                case "decimal":
                case "currency":
                    $fieldArray[$fieldName] = array($fieldName, 'number');
                    break;
                case "bool":
                    $fieldArray[$fieldName] = array($fieldName, 'boolean');
                    break;
                case "varchar":
                case "name":
                case "user_name":
                case "phone":
                case "text":
                case "url":
                case "encrypt":
                case "enum":
                case "radio":
                case "radioenum":
                case "multienum":
                    $fieldArray[$fieldName] = array($fieldName, 'string');
                    break;
                case "relate":
                    if (!empty($def['ext2'])) {
                        $fieldArray[$fieldName] = array($fieldName, 'string');
                    }
                    break;
                case "date":
                case "datetime":
                case "datetimecombo":
                    $fieldArray[$fieldName] = array($fieldName, 'date');
                    break;
                case "link":
                    if ($includeLinks) {
                        $fieldArray[$fieldName] = array($fieldName, 'relate');
                    }
                    break;
                default:
                    //Do Nothing
                    break;
            }
        }

        if ($returnKeys) {
            return $fieldArray;
        }

        return array_values($fieldArray);
    }

    protected static function isFalse($v)
    {
        if (is_string($v)) {
            return strToLower($v) == "false";
        }
        if (is_array($v)) {
            return false;
        }

        return $v == false;
    }

    /**
     * Get the available templates for a specific module
     *
     * @param  string $module
     * @return array
     *
     */
    public static function getPublishedTemplatesForModule($module)
    {
        $pdfManager = BeanFactory::newBean('PdfManager');

        return $pdfManager->get_full_list('', "base_module='" .  $GLOBALS['db']->quote($module) . "' AND published = 'yes'");
    }

    /**
     * Make array from bean
     *
     * @param  array   $module_instance -- Instance of module
     * @param  boolean $recursive       -- If TRUE parse related one-to-many fields
     * @return array   -- key    : field Name
     *                                     value  : field Value
     */
    public static function parseBeanFields($module_instance, $recursive = FALSE)
    {
        global $app_list_strings;

        $module_instance->ACLFilterFields();

        $fields_module = array();
        foreach ($module_instance->toArray() as $name => $value) {

            if (isset($module_instance->field_defs[$name]['type']) &&
                ($module_instance->field_defs[$name]['type'] == 'enum' || $module_instance->field_defs[$name]['type'] == 'radio' || $module_instance->field_defs[$name]['type'] == 'radioenum') &&
                isset($module_instance->field_defs[$name]['options']) &&
                isset($app_list_strings[$module_instance->field_defs[$name]['options']]) &&
                isset($app_list_strings[$module_instance->field_defs[$name]['options']][$value])
               ) {
                $fields_module[$name] = $app_list_strings[$module_instance->field_defs[$name]['options']][$value];
                $fields_module[$name] = str_replace(array('&#39;', '&#039;'), "'", $fields_module[$name]);
            } elseif (isset($module_instance->field_defs[$name]['type']) &&
                $module_instance->field_defs[$name]['type'] == 'multienum' &&
                isset($module_instance->field_defs[$name]['options']) &&
                isset($app_list_strings[$module_instance->field_defs[$name]['options']])
               ) {
                $multienums = unencodeMultienum($value);
                $multienums_value = array();
                foreach ($multienums as $multienum) {
                  if (isset($app_list_strings[$module_instance->field_defs[$name]['options']][$multienum])) {
                      $multienums_value[] = $app_list_strings[$module_instance->field_defs[$name]['options']][$multienum];
                  } else {
                      $multienums_value[] = $multienum;
                  }
                }
                $fields_module[$name] = implode(', ', $multienums_value);
                $fields_module[$name] = str_replace(array('&#39;', '&#039;'), "'", $fields_module[$name]);
            } elseif ($recursive &&
                isset($module_instance->field_defs[$name]['type']) &&
                $module_instance->field_defs[$name]['type'] == 'link' &&
                $module_instance->load_relationship($name) &&
                (
                    (isset($module_instance->field_defs[$name]['link_type']) && $module_instance->field_defs[$name]['link_type'] == 'one') ||
                    ($module_instance->$name->_relationship->relationship_type == 'one-to-one') ||
                    ($module_instance->$name->_relationship->relationship_type == 'one-to-many' && !$module_instance->$name->_get_bean_position()) ||
                    ($module_instance->$name->_relationship->relationship_type == 'many-to-one' && $module_instance->$name->_get_bean_position()) ||
                    ($module_instance->$name->_relationship->relationship_type == 'many-to-many' && !isset($module_instance->field_defs[$name]['side']) && $module_instance->$name->_get_link_table_definition($module_instance->$name->_relationship_name, 'true_relationship_type') == 'one-to-many' && !$module_instance->$name->_get_bean_position()) ||
                    ($module_instance->$name->_relationship->relationship_type == 'many-to-many' && !isset($module_instance->field_defs[$name]['side']) && $module_instance->$name->_get_link_table_definition($module_instance->$name->_relationship_name, 'true_relationship_type') == 'many-to-one' && $module_instance->$name->_get_bean_position())
                ) &&
                count($module_instance->$name->get()) == 1
               ) {
                $related_module = $module_instance->$name->getRelatedModuleName();
                $related_module = $GLOBALS['beanList'][$related_module];
                $related_file   = $GLOBALS['beanFiles'][$related_module];
                require_once $related_file;
                $related_instance = new $related_module;
                $related_instance_id = $module_instance->$name->get();
                if ($related_instance->retrieve($related_instance_id[0]) === null) {
                    $GLOBALS['log']->fatal(__FILE__ . ' Failed loading module ' . $related_module . ' with id ' . $related_instance_id[0]);
                }

                $fields_module[$name] = self::parseBeanFields($related_instance, FALSE);
            } elseif (
                isset($module_instance->field_defs[$name]['type']) &&
                (
                        $module_instance->field_defs[$name]['type'] == 'currency'
                    ||
                        (
                                $module_instance->field_defs[$name]['type'] == 'decimal'
                            &&  in_array($module_instance->object_name , array('Product', 'ProductBundle', 'Quotes'))
                        )
                ) &&
                isset($module_instance->currency_id)
               ) {
                global $locale;
                $format_number_array = array(
                    'currency_symbol' => true,
                    'currency_id' => $module_instance->currency_id,
                    'type' => 'sugarpdf',
                    'charset_convert' => true,
                );

                $fields_module[$name] = format_number_sugarpdf($module_instance->$name, $locale->getPrecision(), $locale->getPrecision(), $format_number_array);
            } elseif (
                isset($module_instance->field_defs[$name]['type']) &&
                ($module_instance->field_defs[$name]['type'] == 'decimal')
               ) {
                global $locale;
                $format_number_array = array(
                    'convert' => false,
                );
                if (!isset($module_instance->$name)) {
                    $module_instance->$name = 0;
                }

                $fields_module[$name] = format_number_sugarpdf($module_instance->$name, $locale->getPrecision(), $locale->getPrecision(), $format_number_array);
            } elseif (
                isset($module_instance->field_defs[$name]['type']) &&
                ($module_instance->field_defs[$name]['type'] == 'image')
               ) {
                $fields_module[$name] = $GLOBALS['sugar_config']['upload_dir']."/".$value;
            } elseif (is_string($value)) {
                $fields_module[$name] = htmlspecialchars_decode(nl2br(stripslashes($value)), ENT_QUOTES);
            }
        }

        return $fields_module;
    }
}
