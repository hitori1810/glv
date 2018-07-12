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


require_once('include/SugarForecasting/AbstractForecast.php');
class SugarForecasting_ReportingUsers extends SugarForecasting_AbstractForecast
{
    /**
     * Process to get an array of Users for the user that was passed in
     *
     * @return array|string
     */
    public function process()
    {

        /* @var $user User */
        $user = BeanFactory::getBean('Users', $this->getArg('user_id'));

        if (User::isManager($user->id)) {
            $children = $this->getChildren($user);
        } else {
            $children = array();
        }

        $tree = $this->formatForTree($user, $children);

        if ($GLOBALS['current_user']->id != $this->getArg('user_id')) {
            // we need to create a parent record
            if (!empty($user->reports_to_id)) {
                $parent = $this->getParentLink($user->reports_to_id);
                // the open user should be marked as a manager now
                $tree['attr']['rel'] = 'manager';

                // put the parent link and the tree in the same level
                $tree = array($parent, $tree);
            }
        }

        return $tree;
    }

    /**
     * Load up all the reporting users for a give user
     *
     * @param User $user
     * @return array
     */
    protected function getChildren(User $user)
    {
        $query = $user->create_new_list_query(
            '',
            'users.reports_to_id = ' . $user->db->quoted($user->id) . ' AND users.status = \'Active\''
        );
        $response = $user->process_list_query($query, 0);
        return $response['list'];
    }

    /**
     * Format the main part of the tree
     *
     * @param User  $user
     * @param array $children
     *
     * @return array
     */
    protected function formatForTree(User $user, array $children)
    {
        $tree = $this->getTreeArray($user, 'root');

        if (!empty($children)) {
            // we have children
            // add the manager again as the my opportunities bunch
            $tree['children'][] = $this->getTreeArray($user, 'my_opportunities');

            foreach ($children as $child) {
                $tree['children'][] = $this->getTreeArray($child, 'rep');
            }

            $tree['state'] = 'open';
        }

        return $tree;
    }

    /**
     * Utility method to get the Parent Link
     *
     * @param string $manager_reports_to
     * @return array
     */
    protected function getParentLink($manager_reports_to)
    {
        /* @var $parentBean User */
        $parentBean = BeanFactory::getBean('Users', $manager_reports_to);
        $parent = $this->getTreeArray($parentBean, 'parent_link');

        global $current_language;
        $current_module_strings = return_module_language($current_language, 'Forecasts');
        $parent['data'] = $current_module_strings['LBL_TREE_PARENT'];

        // overwrite the whole attr array for the parent
        $parent['attr'] = array(
            'rel' => 'parent_link',
            'class' => 'parent',
            // adding id tag for QA's voodoo tests
            'id' => 'jstree_node_parent'
        );

        return $parent;
    }

    /**
     * Utility method to build out a tree node array
     *
     * @param User $user
     * @param string $rel
     * @return array
     */
    protected function getTreeArray(User $user, $rel)
    {
        global $locale;
        $fullName = $locale->formatName($user);

        $qa_id = 'jstree_node_';
        if ($rel == "my_opportunities") {
            $qa_id .= 'myopps_';
        }

        $state = '';

        if ($rel == 'rep' && User::isManager($user->id)) {
            // check if the user is a manager and if they are change the rel to be 'manager'
            $rel = 'manager';
            $state = 'closed';
        }

        return array(
            'data' => $fullName,
            'children' => array(),
            'metadata' => array(
                'id' => $user->id,
                'user_name' => $user->user_name,
                'full_name' => $fullName,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'reports_to_id' => $user->reports_to_id,
            ),
            'state' => $state,
            'attr' => array(
                // set all users to rep by default
                'rel' => $rel,
                // adding id tag for QA's voodoo tests
                'id' => $qa_id . $user->user_name
            )
        );
    }

}
