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

 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/**
 * This class used to perform json encode / decode functions but has now been replaced by
 * the built in php.
 *
 * Note: We no longer eval our json so there is no more need for security envelopes. The parameter
 * has been left for backwards compatibility.
 * @api
 */
class JSON
{

    /**
     * JSON encode a string
     *
     * @param string $string
     * @param bool $addSecurityEnvelope defaults to false
     * @param bool $encodeSpecial
     * @return string
     */
    public static function encode($string, $addSecurityEnvelope = false, $encodeSpecial = false)
    {
        $encodedString = json_encode($string);

        if ($encodeSpecial)
        {
            $charMap = array('<' => '\u003C', '>' => '\u003E', "'" => '\u0027', '&' => '\u0026');
            foreach($charMap as $c => $enc)
            {
                $encodedString = str_replace($c, $enc, $encodedString);
            }
        }

        return $encodedString;
    }

    /**
     * JSON decode a string
     *
     * @param string $string
     * @param bool $examineEnvelope Default false, true to extract and verify envelope
     * @param bool $assoc
     * @return string
     */
    public static function decode($string, $examineEnvelope=false, $assoc = true)
    {
        return json_decode($string,$assoc);
    }

    /**
     * @deprecated use JSON::encode() instead
     */
    public static function encodeReal($string)
    {
        return self::encode($string);
    }

    /**
     * @deprecated use JSON::decode() instead
     */
    public static function decodeReal($string)
    {
        return self::decode($string);
    }
}
