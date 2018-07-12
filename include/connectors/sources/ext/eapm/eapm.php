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


require_once('include/connectors/sources/default/source.php');

/**
 * Generic source connected using EAPM access details
 * @api
 */
abstract class ext_eapm extends source{

    /**
     * The ExternalAPI Base that instantiated this connector.
     * @var _eapm
     */
    protected $_eapm;

    public function setEAPM(ExternalAPIBase $eapm)
    {
        $GLOBALS['log']->debug("Connector is setting eapm");
        $this->_eapm = $eapm;
    }

    public function getEAPM()
    {
        $GLOBALS['log']->debug("Connector is getting eapm");
        return $this->_eapm;
    }

}
?>
