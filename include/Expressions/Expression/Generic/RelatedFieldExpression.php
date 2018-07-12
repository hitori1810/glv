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

require_once('include/Expressions/Expression/Generic/GenericExpression.php');
/**
 * <b>related(Relationship <i>link</i>, String <i>field</i>)</b><br>
 * Returns the value of <i>field</i> in the related module <i>link</i><br/>
 * ex: <i>related($accounts, "industry")</i>
 */
class RelatedFieldExpression extends GenericExpression
{
	/**
	 * Returns the entire enumeration bare.
	 */
	function evaluate() {
		$params = $this->getParameters();
        //This should be of relate type, which means an array of SugarBean objects
        $linkField = $params[0]->evaluate();
        $relfield = $params[1]->evaluate();

        //return blank if the field is empty or a non-array type with the exception of string type
        if ((empty($linkField)||!is_array($linkField)) && !is_string($linkField)) {
            return "";
        }
        //if LinkedField value is a string, then return the string value
        if (is_string($linkField)) {
            return $linkField;
        }

        foreach($linkField as $id => $bean)
        {
            if (!empty($bean->field_defs[$relfield]) && isset($bean->$relfield))
            {
                if (!empty($bean->field_defs[$relfield]['type']))
                {
                    global $timedate;
                    if ($bean->field_defs[$relfield]['type'] == "date")
                    {
                        $ret = $timedate->fromDbDate($bean->$relfield);
                        if (!$ret)
                            $ret = $timedate->fromUserDate($bean->$relfield);
                        if (!$ret) {
                            return $ret;
                        }
                        $ret->isDate = true;
                        return $ret;
                    }
                    if ($bean->field_defs[$relfield]['type'] == "datetime")
                    {
                        $ret = $timedate->fromDb($bean->$relfield);
                        if (!$ret)
                            $ret = $timedate->fromUser($bean->$relfield);
                        return $ret;
                    }
                    if ($bean->field_defs[$relfield]['type'] == "bool")
                    {
                        require_once("include/Expressions/Expression/Boolean/BooleanExpression.php");
                        if ($bean->$relfield)
                            return BooleanExpression::$TRUE;
                        else
                            return BooleanExpression::$FALSE;
                    }
                }
                return $bean->$relfield;
            }
        }
        
        return "";
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
		    var params = this.getParameters(),
			    linkField = params[0].evaluate(),
			    relField = params[1].evaluate(),
			    AH = SUGAR.forms.AssignmentHandler;



			if (typeof(linkField) == "string" && linkField != "")
			{
                //We just have a field name, assume its the name of a link field
                //and the parent module is the current module.
                //Try and get the current module and record ID
                var module = AH.getValue("module");
                var record = AH.getValue("record");
                var linkDef = AH.getLink(linkField);
                var linkId = false, url = "index.php?";
                
                if (linkDef && linkDef.id_name && linkDef.module) {
                    var idField = document.getElementById(linkDef.id_name);
                    if (idField && (idField.tagName == "INPUT" || idField.hasAttribute("data-id-value")))
                    {
                        linkId = AH.getValue(linkDef.id_name, false, true);
                        module = linkDef.module;
                    }
                    //Clear the cache for this link if the id has changed
                    if (linkDef.relId && linkDef.relId != linkId)
                        AH.clearRelatedFieldCache(linkField);
                }

                return AH.getRelatedField(linkField, 'related', relField);
			} else if (typeof(rel) == "object") {
			    //Assume we have a Link object that we can delve into.
			    //This is mostly used for n level dives through relationships.
			    //This should probably be avoided on edit views due to performance issues.
			}

			return "";
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return array("related");
	}

	/**
	 * The first parameter is a number and the second is the list.
	 */
    static function getParameterTypes() {
		return array(AbstractExpression::$RELATE_TYPE, AbstractExpression::$STRING_TYPE);
	}

	/**
	 * Returns the maximum number of parameters needed.
	 */
	static function getParamCount() {
		return 2;
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}

?>
