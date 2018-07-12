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

class SugarFieldHandler {
    var $sugarFieldObjects = array();

    function SugarFieldHandler() {
    }

    /**
     * return the singleton of the SugarField
     *
     * @param field string field type
     */
    /*
    function getSugarField($field) {
        if(!isset($this->sugarFieldObjects[$field])) {
            require_once('include/SugarFields/Fields/SugarField' . $field . '/SugarField' . $field . '.php');
            $class = 'SugarField' . $field; // ex. SugarFieldVarchar, SugarFieldInt, etc.
            $this->sugarFieldObjects[$field] = new $class();
        }
        return $this->sugarFieldObjects[$field];
    }
    */

    function getSugarField($field) {
        if(!isset($this->sugarFieldObjects[$field])) {
        	if(file_exists('include/SugarFields/Fields/SugarField'.$field.'/SugarField'.$field.'.php')){
           		$type = $field;
        	}else{
        		$type = 'Base';
        	}

        	require_once('include/SugarFields/Fields/SugarField'.$type.'/SugarField'.$type.'.php');
            $class = 'SugarField' . $type; // ex. SugarFieldVarchar, SugarFieldInt, etc.
            $this->sugarFieldObjects[$field] = new $class($field);
        }
        return $this->sugarFieldObjects[$field];
    }

    /**
     * Returns the smarty code to be used in a template built by TemplateHandler
     * The SugarField class is choosen dependant on the vardef's type field.
     *
     * @param parentFieldArray string name of the variable in the parent template for the bean's data
     * @param vardef vardef field defintion
     * @param displayType string the display type for the field (eg DetailView, EditView, etc)
     * @param displayParam parameters for displayin
     *      available paramters are:
     *      * labelSpan - column span for the label
     *      * fieldSpan - column span for the field
     */
    function displaySmarty($parentFieldArray, $vardef, $displayType, $displayParams = array()) {
        $string = '';
        $displayType = 'get' . $displayType . 'Smarty'; // getDetailViewSmarty, getEditViewSmarty, etc...

        if(!empty($vardef['type'])) {
            switch($vardef['type']) {
                case 'varchar':
                case 'char':
                    $field = $this->getSugarField('Varchar');
                    break;
                case 'bool':
                    $field = $this->getSugarField('Bool');
                    break;
                case 'name':
                    $field = $this->getSugarField('Name');
                    break;
                case 'phone':
                    $field = $this->getSugarField('Phone');
                    break;
                case 'email':
                    $field = $this->getSugarField('Email');
                    break;
                case 'int':
                    $field = $this->getSugarField('Int');
                    break;
                case 'float':
                   $field = $this->getSugarField('Int');
                    break;
                case 'date':
                	$field = $this->getSugarField('Date');
                	break;
                case 'enum':
                    $field = $this->getSugarField('Enum');
                    break;
                case 'datetime':
                    $field = $this->getSugarField('Datetime');
                    break;
                case 'text':
                    $field = $this->getSugarField('Text');
                    break;
                case 'password':
                    $field = $this->getSugarField('Password');
                    break;
                case 'assigned_user_name':
                    $field = $this->getSugarField('Username');
                    break;
                case 'radioenum':
                    $field = $this->getSugarField('Radioenum');
                    break;
                case 'multienum':
                    $field = $this->getSugarField('Multienum');
                    break;
                case 'html':
                	$field = $this->getSugarField('Html');
                	break;
                case 'url':
                	$field = $this->getSugarField('Link');
                	break;
                default:
                    $field = $this->getSugarField('Base');
                    break;
            }

            $string = $field->$displayType($parentFieldArray, $vardef, $displayParams);
        }

        return $string;
    }

    function displayJSValidation($vardef, $formname, $displayParams = array()) {
        $string = '';
        if(!empty($vardef['type'])) {
                switch($vardef['type']) {
                    case 'varchar':
                    case 'char':
                        $field = $this->getSugarField('Varchar');
                        break;
                    case 'bool':
                        $field = $this->getSugarField('Bool');
                        break;
                    case 'name':
                        $field = $this->getSugarField('Name');
                        break;
                    case 'phone':
                        $field = $this->getSugarField('Phone');
                        break;
                    case 'email':
                        $field = $this->getSugarField('Email');
                        break;
                    case 'float':
                    case 'int':
                        $field = $this->getSugarField('Int');
                        break;
                    case 'enum':
                        $field = $this->getSugarField('Enum');
                        break;
                    case 'datetime':
                        $field = $this->getSugarField('Datetime');
                        break;
                    case 'text':
                        $field = $this->getSugarField('Text');
                        break;
                    case 'password':
                        $field = $this->getSugarField('Password');
                        break;
                    default:
                        $field = $this->getSugarField('Base');
                        break;
                }
            }
            $string = $field->getJSValidation($vardef, $formname, $displayParams);
            return $string;
    }

}

?>