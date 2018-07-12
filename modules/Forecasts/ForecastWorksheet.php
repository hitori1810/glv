<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
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


class ForecastWorksheet extends SugarBean
{

    public $id;
    public $worksheet_id;
    public $currency_id;
    public $base_rate;
    public $args;
    public $name;
    public $commit_stage;
    public $probability;
    public $best_case;
    public $likely_case;
    public $worst_case;
    public $sales_stage;
    public $product_id;
    public $assigned_user_id;
    public $timeperiod_id;
    public $draft = 0; // default to 0, it will be set to 1 by the args that get passed in;
    public $parent_type;
    public $parent_id;
    public $object_name = 'ForecastWorksheet';
    public $module_name = 'ForecastWorksheets';
    public $module_dir = 'Forecasts';
    public $table_name = 'forecast_worksheets';
    public $disable_custom_fields = true;

    /**
     * handle saving to the real tables for 6.7.  Currently forecast is mapped to opportunities
     * and likely_case, worst_case and best_case go to both worksheets and opportunities.
     *
     *
     * @param bool $check_notify        Should we send the notifications
     */
    public function saveWorksheet($check_notify = false)
    {
        $commitForecast = true;
        if ($this->draft == 1) {
            $commitForecast = false;
        }

        $opp_id = $this->findOpportunityId();

        //Update the Opportunities bean -- should update the product line item as well through SaveOverload.php
        /* @var $opp Opportunity */
        $opp = BeanFactory::getBean('Opportunities', $opp_id);
        $opp->probability = $this->probability;
        $opp->best_case = $this->best_case;
        $opp->amount = $this->likely_case;
        $opp->sales_stage = $this->sales_stage;
        $opp->commit_stage = $this->commit_stage;
        $opp->worst_case = $this->worst_case;
        $opp->commit_stage = $this->commit_stage;
        $opp->save($check_notify);

        if ($commitForecast) {
            // find the product
            /* @var $product Product */
            $product = BeanFactory::getBean('Products');
            $product->retrieve_by_string_fields(array(
                    'opportunity_id'=>$opp->id
                ));

            //Update the Worksheet bean
            /* @var $worksheet Worksheet */
            $worksheet = BeanFactory::getBean('Worksheet');
            $worksheet->retrieve_by_string_fields(array(
                    'related_id' => $product->id,
                    'related_forecast_type' => 'Product',
                    'forecast_type' => 'Direct'
            ));
            $worksheet->timeperiod_id = $this->timeperiod_id;
            $worksheet->user_id = $this->assigned_user_id;
            $worksheet->best_case = $this->best_case;
            $worksheet->likely_case = $this->likely_case;
            $worksheet->worst_case = $this->worst_case;
            $worksheet->op_probability = $this->probability;
            $worksheet->commit_stage = $this->commit_stage;
            $worksheet->forecast_type = 'Direct';
            $worksheet->related_forecast_type = 'Product';
            $worksheet->related_id = $product->id;
            $worksheet->currency_id = $this->currency_id;
            $worksheet->base_rate = $this->base_rate;
            $worksheet->version = 1; // default it to 1 as it will always be on since this is always
            $worksheet->save($check_notify);
        }
    }

    /**
     * Find the Opportunity Id for this worksheet
     *
     * @return bool|string      Return the SugarGUID for the opportunity if found, otherwise, return false
     */
    protected function findOpportunityId()
    {
        // check parent type
        if($this->parent_type == 'Opportunities') {
            return $this->parent_id;
        } else if ($this->parent_type == 'Products') {
            // load the product to get the opp id
            /* @var $product Product */
            $product = BeanFactory::getBean('Products', $this->parent_id);

            if($product->id == $this->parent_id) {
                return $product->opportunity_id;
            }
        }

        // this should never happen.
        return false;
    }

    /**
     * Sets Worksheet args so that we save the supporting tables.
     * @param array $args Arguments passed to save method through PUT
     */
    public function setWorksheetArgs($args)
    {
        // save the args variable
        $this->args = $args;

        // loop though the args and assign them to the corresponding key on the object
        foreach ($args as $arg_key => $arg) {
            $this->$arg_key = $arg;
        }
    }

    /**
     * Save an Opportunity as a worksheet
     *
     * @param Opportunity $opp      The Opportunity that we want to save a snapshot of
     * @param bool $isCommit        Is the Opportunity being committed
     */
    public function saveRelatedOpportunity(Opportunity $opp, $isCommit = false)
    {
        $this->retrieve_by_string_fields(
            array(
                'parent_type' => 'Opportunities',
                'parent_id' => $opp->id,
                'draft' => ($isCommit === false) ? 1 : 0,
                'deleted' => 0,
            )
        );

        $fields = array(
            'name',
            'account_id',
            array('likely_case' => 'amount'),
            'best_case',
            'base_rate',
            'worst_case',
            'currency_id',
            'date_closed',
            'date_closed_timestamp',
            'sales_stage',
            'probability',
            'commit_stage',
            'assigned_user_id',
            'created_by',
            'date_entered',
            'deleted',
            'team_id',
            'team_set_id'
        );

        $this->copyValues($fields, $opp);

        // set the parent types
        $this->parent_type = 'Opportunities';
        $this->parent_id = $opp->id;
        $this->draft = ($isCommit === false) ? 1 : 0;

        $this->save(false);


        // now save all related products to the opportunity
        // commit every product associated with the Opportunity
        $products = $opp->get_linked_beans('products', 'Products');
        /* @var $product Product */
        foreach($products as $product) {
            /* @var $product_wkst ForecastWorksheet */
            $product_wkst = BeanFactory::getBean('ForecastWorksheets');
            $product_wkst->saveRelatedProduct($product, $isCommit, $opp);
            unset($product_wkst);   // clear the cache
        }

    }

    /**
     * Save a snapshot of a Product to the ForecastWorksheet Table
     *
     * @param Product $product          The Product to commit
     * @param bool $isCommit            Are we committing a product for the forecast
     * @param Opportunity $opp          The related Opportunity to get the sales_stage from
     */
    public function saveRelatedProduct(Product $product, $isCommit = false, Opportunity $opp = null)
    {
        $this->retrieve_by_string_fields(
            array(
                'parent_type' => 'Products',
                'parent_id' => $product->id,
                'draft' => ($isCommit === false) ? 1 : 0,
                'deleted' => 0,
            )
        );

        // since we don't have sales_stage in 6.7 we need to pull it from the related opportunity
        /* @var $opp Opportunity */
        $product->sales_stage = '';
        if(empty($opp)) {
            $opp = BeanFactory::getBean('Opportunities', $product->opportunity_id);
        }
        if($opp instanceof Opportunity) {
            $product->sales_stage = $opp->sales_stage;
        }

        $fields = array(
            'name',
            'account_id',
            'likely_case',
            'best_case',
            'base_rate',
            'worst_case',
            'currency_id',
            'date_closed',
            'date_closed_timestamp',
            'probability',
            'commit_stage',
            'sales_stage',
            'assigned_user_id',
            'created_by',
            'date_entered',
            'deleted',
            'team_id',
            'team_set_id'
        );

        $this->copyValues($fields, $product);

        // set the parent types
        $this->parent_type = 'Products';
        $this->parent_id = $product->id;
        $this->draft = ($isCommit === false) ? 1 : 0;

        $this->save(false);
    }

    /**
     * Copy the fields from the $seed bean to the worksheet object
     *
     * @param array $fields
     * @param SugarBean $seed
     */
    protected function copyValues($fields, SugarBean $seed)
    {
        foreach ($fields as $field) {
            $key = $field;
            if (is_array($field)) {
                // if we have an array it should be a key value pair, where the key is the destination value and the value,
                // is the seed value
                $key = array_shift(array_keys($field));
                $field = array_shift($field);
            }
            // make sure the field is set, as not to cause a notice since a field might get unset() from the $seed class
            if(isset($seed->$field)) {
                $this->$key = $seed->$field;
            }
        }
    }

    public static function reassignForecast($fromUserId, $toUserId)
    {
        global $current_user;

        $db = DBManagerFactory::getInstance();

        // reassign Opportunities
        $_object = BeanFactory::getBean('Opportunities');
        $_query = "update {$_object->table_name} set " .
            "assigned_user_id = '{$toUserId}', " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
            "modified_user_id = '{$current_user->id}' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.assigned_user_id = '{$fromUserId}'";
        $res = $db->query($_query, true);
        $affected_rows = $db->getAffectedRowCount($res);

        // Products
        // reassign only products that have related opportunity - products created from opportunity::save()
        // other products will be reassigned if module Product is selected by user
        $_object = BeanFactory::getBean('Products');
        $_query = "update {$_object->table_name} set " .
            "assigned_user_id = '{$toUserId}', " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
            "modified_user_id = '{$current_user->id}' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.assigned_user_id = '{$fromUserId}' and {$_object->table_name}.opportunity_id IS NOT NULL ";
        $db->query($_query, true);

        // delete Forecasts
        $_object = BeanFactory::getBean('Forecasts');
        $_query = "update {$_object->table_name} set " .
            "deleted = 1, " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.user_id = '{$fromUserId}'";
        $db->query($_query, true);

        // delete Expected Opportunities
        $_object = BeanFactory::getBean('ForecastSchedule');
        $_query = "update {$_object->table_name} set " .
            "deleted = 1, " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.user_id = '{$fromUserId}'";
        $db->query($_query, true);

        // delete Quotas
        $_object = BeanFactory::getBean('Quotas');
        $_query = "update {$_object->table_name} set " .
            "deleted = 1, " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.user_id = '{$fromUserId}'";
        $db->query($_query, true);

        // clear reports_to for inactive users
        $objFromUser = BeanFactory::getBean('Users');
        $objFromUser->retrieve($fromUserId);
        $fromUserReportsTo = !empty($objFromUser->reports_to_id) ? $objFromUser->reports_to_id : '';
        $objFromUser->reports_to_id = '';
        $objFromUser->save();

        if (User::isManager($fromUserId)) {
            // setup report_to for user
            $objToUserId = BeanFactory::getBean('Users');
            $objToUserId->retrieve($toUserId);
            $objToUserId->reports_to_id = $fromUserReportsTo;
            $objToUserId->save();

            // reassign users (reportees)
            $_object = BeanFactory::getBean('Users');
            $_query = "update {$_object->table_name} set " .
                "reports_to_id = '{$toUserId}', " .
                "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
                "modified_user_id = '{$current_user->id}' " .
                "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.reports_to_id = '{$fromUserId}' " .
                "and {$_object->table_name}.id != '{$toUserId}'";
            $db->query($_query, true);
        }

        // Worksheets
        // reassign worksheets for products (opportunities)
        $_object = BeanFactory::getBean('Worksheet');
        $_query = "update {$_object->table_name} set " .
            "user_id = '{$toUserId}', " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
            "modified_user_id = '{$current_user->id}' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.user_id = '{$fromUserId}' ";
        $db->query($_query, true);

        // delete worksheet where related_id is user id - rollups
        $_object = BeanFactory::getBean('Worksheet');
        $_query = "update {$_object->table_name} set " .
            "deleted = 1, " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
            "modified_user_id = '{$current_user->id}' " .
            "where {$_object->table_name}.deleted = 0 " .
            "and {$_object->table_name}.forecast_type = 'Rollup' and {$_object->table_name}.related_forecast_type = 'Direct' " .
            "and {$_object->table_name}.related_id = '{$fromUserId}' ";
        $db->query($_query, true);

        // ForecastWorksheets
        // reassign entries in forecast_worksheets
        $_object = BeanFactory::getBean('ForecastWorksheets');
        $_query = "update {$_object->table_name} set " .
            "assigned_user_id = '{$toUserId}', " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
            "modified_user_id = '{$current_user->id}' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.assigned_user_id = '{$fromUserId}' ";
        $db->query($_query, true);

        // ForecastManagerWorksheets
        // reassign entries in forecast_manager_worksheets
        $_object = BeanFactory::getBean('ForecastManagerWorksheets');
        $_query = "update {$_object->table_name} set " .
            "assigned_user_id = '{$toUserId}', " .
            "user_id = '{$toUserId}', " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
            "modified_user_id = '{$current_user->id}' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.assigned_user_id = '{$fromUserId}' ";
        $db->query($_query, true);

        //todo: forecast_tree
        return $affected_rows;
    }
}
