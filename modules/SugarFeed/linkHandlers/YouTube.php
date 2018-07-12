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

require_once('modules/SugarFeed/linkHandlers/Link.php');

class FeedLinkHandlerYoutube extends FeedLinkHandlerLink {
    function getDisplay(&$data) {
        return '<div style="padding-left:10px"><object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/' . $data['LINK_URL'] . '&hl=en&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="wmode" value="opaque" /><embed src="http://www.youtube.com/v/' . $data['LINK_URL'] . '&hl=en&fs=1" type="application/x-shockwave-flash" allowfullscreen="false" wmode="opaque" width="425" height="344"></embed></object></div>';
    }
    
    function handleInput($feed, $link_type, $link_url) {
        $match = array();
        preg_match('/v=([^\&]+)/', $link_url, $match);
		
        if(!empty($match[1])){
            $feed->link_type = $link_type;
            $feed->link_url = $match[1];
        }
    }
}