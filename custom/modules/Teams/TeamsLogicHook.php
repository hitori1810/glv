<?php
/**
* Created by PhpStorm.
* User: HaiLong
* Date: 2/5/2015
* Time: 6:25 PM
*/
class TeamsLogicHook{    
    //After save - add by Tung Bui
    function applyJuniorType($bean, $event, $arguments){        
        $bean->type = 'Junior';
    }
}