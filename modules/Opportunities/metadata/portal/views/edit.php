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


$viewdefs['Opportunities']['portal']['view']['edit'] = array(
        'buttons' =>
        array(
            0 =>
            array(
                'name' => 'save_button',
                'type' => 'button',
                'label' => 'Save',
                'value' => 'save',
                'primary' => true,
                'events' =>
                array(
                    'click' => 'function(){ var self = this; this.model.save(null, {success:function(){self.app.navigate(self.context, self.model, \'detail\');}});}',
                ),
            ),
            1 =>
            array(
                'name' => 'cancel_button',
                'type' => 'button',
                'label' => 'Cancel',
                'value' => 'cancel',
                'route' =>
                array(
                    'action' => 'detail',
                    'module' => 'Opportunities',
                ),
                'primary' => false,
            ),
        ),
        'panels' =>
        array(
            0 =>
            array(
                'label' => 'Details',
                'fields' =>
                array(
                    0 =>
                    array(
                        'name' => 'name',
                        'label' => 'Name',
                    ),
                    1 =>
                    array(
                        'name' => 'amount',
                        'label' => 'Opportunity Amount',
                    ),
                    2 =>
                    array(
                        'name' => 'opportunity_type',
                        'label' => 'Opp. Type',
                    ),
                    3 =>
                    array(
                        'name' => 'lead_source',
                        'label' => 'Lead Source',
                    ),
                    4 =>
                    array(
                        'name' => 'date_modified',
                        'label' => 'Modifed Date',
                    ),
                ),
            ),
        ),
);
