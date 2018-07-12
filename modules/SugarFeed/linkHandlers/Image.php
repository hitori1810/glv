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

class FeedLinkHandlerImage extends FeedLinkHandlerLink {
    function getDisplay(&$data) {
        $imageData = unserialize(base64_decode($data['LINK_URL']));
        if ( $imageData['width'] != 0 ) {
            $image_style = 'width: '.$imageData['width'].'px; height: '.$imageData['height'].'px; border: 0px;';
        } else {
            // Unknown width/height
            // Set it to a max width of 425 px, and include a tweak so that IE 6 can actually handle it.
            $image_style = 'width: expression(this.scrollWidth > 425 ? \'425px\':\'auto\'); max-width: 425px;';
        }
        return '<div style="padding-left:10px"><!--not_in_theme!--><img src="'. $imageData['url']. '" style="'.$image_style.'"></div>';
    }

    function handleInput($feed, $link_type, $link_url) {
        parent::handleInput($feed, $link_type, $link_url);

        // The FeedLinkHandlerLink class will help sort this url out for us
        $link_url = $feed->link_url;

        $imageData = @getimagesize($link_url);

        if ( ! isset($imageData) ) {
            // The image didn't pull down properly, could be a link and allow_url_fopen could be disabled
            $imageData[0] = 0;
            $imageData[1] = 0;
        } else {
            if ( max($imageData[0],$imageData[1]) > 425 ) {
                // This is a large image, we need to set some specific width/height properties so that the browser can scale it.
                $scale = 425 / max($imageData[0],$imageData[1]);
                $imageData[0] = floor($imageData[0]*$scale);
                $imageData[1] = floor($imageData[1]*$scale);
            }
        }

        $feed->link_url = base64_encode(serialize(array('url'=>$link_url,'width'=>$imageData[0],'height'=>$imageData[1])));
    }
}