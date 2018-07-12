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


require_once("include/SugarForecasting/Progress/AbstractProgress.php");
class SugarForecasting_Progress_Individual extends SugarForecasting_Progress_AbstractProgress
{
    /**
     * Process the code to return the values that we need
     *
     * @return array
     */
    public function process()
    {
        return $this->getIndividualProgress();
    }

    /**
     * Get the Numbers for the Individual (Sales Rep) View, this number comes from the quota right now
     *
     * @return array
     */
    protected function getIndividualProgress()
    {
        //get the quota data for user
        /* @var $quota Quota */
        $quota = BeanFactory::getBean('Quotas');
        $quotaData = $quota->getRollupQuota($this->getArg('timeperiod_id'), $this->getArg('user_id'));

		$progressData = array(
            "quota_amount"      => isset($quotaData["amount"]) ? $quotaData["amount"] : 0
		);

		return $progressData;
    }
}