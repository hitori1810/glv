<?php
$viewdefs['Campaigns'] = 
array (
    'EditView' => 
    array (
        'templateMeta' => 
        array (
            'maxColumns' => '2',
            'widths' => 
            array (
                0 => 
                array (
                    'label' => '10',
                    'field' => '30',
                ),
                1 => 
                array (
                    'label' => '10',
                    'field' => '30',
                ),
            ),
            'javascript' => '<script type="text/javascript" src="include/javascript/popup_parent_helper.js?v=ccjaZyVwRaSQMvronviLOQ"></script>
            <script type="text/javascript">
            function type_change() {ldelim}
            type = document.getElementsByName(\'campaign_type\');
            if(type[0].value==\'NewsLetter\') {ldelim}
            document.getElementById(\'freq_label\').style.display = \'\';
            document.getElementById(\'freq_field\').style.display = \'\';
            {rdelim} else {ldelim}
            document.getElementById(\'freq_label\').style.display = \'none\';
            document.getElementById(\'freq_field\').style.display = \'none\';
            {rdelim}

            if(type[0].value==\'Other\') {ldelim}
            document.getElementById(\'other_type\').style.display = \'\';
            {rdelim} else {ldelim}
            document.getElementById(\'other_type\').style.display = \'none\';
            {rdelim}
            {rdelim}
            type_change();

            function ConvertItems(id)  {ldelim}
            var items = new Array();

            //get the items that are to be converted
            expected_revenue = document.getElementById(\'expected_revenue\');
            budget = document.getElementById(\'budget\');
            actual_cost = document.getElementById(\'actual_cost\');
            expected_cost = document.getElementById(\'expected_cost\');

            //unformat the values of the items to be converted
            expected_revenue.value = unformatNumber(expected_revenue.value, num_grp_sep, dec_sep);
            expected_cost.value = unformatNumber(expected_cost.value, num_grp_sep, dec_sep);
            budget.value = unformatNumber(budget.value, num_grp_sep, dec_sep);
            actual_cost.value = unformatNumber(actual_cost.value, num_grp_sep, dec_sep);

            //add the items to an array
            items[items.length] = expected_revenue;
            items[items.length] = budget;
            items[items.length] = expected_cost;
            items[items.length] = actual_cost;

            //call function that will convert currency
            ConvertRate(id, items);

            //Add formatting back to items
            expected_revenue.value = formatNumber(expected_revenue.value, num_grp_sep, dec_sep);
            expected_cost.value = formatNumber(expected_cost.value, num_grp_sep, dec_sep);
            budget.value = formatNumber(budget.value, num_grp_sep, dec_sep);
            actual_cost.value = formatNumber(actual_cost.value, num_grp_sep, dec_sep);
            {rdelim}
            </script>',
            'useTabs' => false,
            'tabDefs' => 
            array (
                'LBL_CAMPAIGN_INFORMATION' => 
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PANEL_ASSIGNMENT' => 
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => 
        array (
            'lbl_campaign_information' => 
            array (
                0 => 
                array (
                    0 => 
                    array (
                        'name' => 'name',
                    ),
                    1 => 
                    array (
                        'name' => 'status',
                    ),
                ),
                1 => 
                array (
                    0 => 
                    array (
                        'name' => 'start_date',
                        'displayParams' => 
                        array (
                            'required' => false,
                            'showFormats' => true,
                        ),
                    ),
                    1 => 
                    array (
                        'name' => 'campaign_type',       
                    ),  
                ),
                2 => 
                array (
                    0 => 
                    array (
                        'name' => 'end_date',
                        'displayParams' => 
                        array (
                            'showFormats' => true,
                        ),
                    ), 
                    1 => 
                    array (
                        'name' => 'lead_source',
                        'customCode' => '{$lead_source}'
                    ),       
                ),
                6 => 
                array (
                    0 => 
                    array (
                        'name' => 'objective',
                        'displayParams' => 
                        array (
                            'rows' => 8,
                            'cols' => 80,
                        ),
                    ),
                ),
                7 => 
                array (
                    0 => 
                    array (
                        'name' => 'content',
                        'displayParams' => 
                        array (
                            'rows' => 8,
                            'cols' => 80,
                        ),
                    ),
                ),
            ),
            'LBL_PANEL_ASSIGNMENT' => 
            array (
                0 => 
                array (
                    0 => 
                    array (
                        'name' => 'assigned_user_name',
                        'label' => 'LBL_ASSIGNED_TO',
                    ),
                    1 => 
                    array (
                        'name' => 'team_name',
                        'displayParams' => 
                        array (
                            'display' => true,
                        ),
                    ),
                ),
            ),
        ),
    ),
);
