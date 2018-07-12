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


require_once("data/Relationships/SugarRelationship.php");

/**
 * Represents a many to many relationship that is table based.
 * @api
 */
class M2MRelationship extends SugarRelationship
{
    var $type = "many-to-many";

    public function __construct($def)
    {
        $this->def = $def;
        $this->name = $def['name'];

        $lhsModule = $def['lhs_module'];
        $this->lhsLinkDef = $this->getLinkedDefForModuleByRelationship($lhsModule);
        $this->lhsLink = $this->lhsLinkDef['name'];

        $rhsModule = $def['rhs_module'];
        $this->rhsLinkDef = $this->getLinkedDefForModuleByRelationship($rhsModule);
        $this->rhsLink = $this->rhsLinkDef['name'];

        $this->self_referencing = $lhsModule == $rhsModule;
    }

    /**
     * Find the link entry for a particular relationship and module.
     *
     * @param $module
     * @return array|bool
     */
    public function getLinkedDefForModuleByRelationship($module)
    {
        $results = VardefManager::getLinkFieldForRelationship( $module, BeanFactory::getObjectName($module), $this->name);
        //Only a single link was found
        if( isset($results['name']) )
        {
            return $results;
        }
        //Multiple links with same relationship name
        else if( is_array($results) )
        {
            $GLOBALS['log']->error("Warning: Multiple links found for relationship {$this->name} within module {$module}");
            return $this->getMostAppropriateLinkedDefinition($results);
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Find the most 'appropriate' link entry for a relationship/module in which there are multiple link entries with the
     * same relationship name.
     *
     * @param $links
     * @return bool
     */
    protected function getMostAppropriateLinkedDefinition($links)
    {
        //First priority is to find a link name that matches the relationship name
        foreach($links as $link)
        {
            if( isset($link['name']) && $link['name'] == $this->name )
            {
                return $link;
            }
        }
        //Next would be a relationship that has a side defined
        foreach($links as $link)
        {
            if( isset($link['id_name']))
            {
                return $link;
            }
        }
        //Unable to find an appropriate link, guess and use the first one
        $GLOBALS['log']->error("Unable to determine best appropriate link for relationship {$this->name}");
        return $links[0];
    }
    /**
     * @param  $lhs SugarBean left side bean to add to the relationship.
     * @param  $rhs SugarBean right side bean to add to the relationship.
     * @param  $additionalFields key=>value pairs of fields to save on the relationship
     * @return boolean true if successful
     */
    public function add($lhs, $rhs, $additionalFields = array())
    {
        $lhsLinkName = $this->lhsLink;
        $rhsLinkName = $this->rhsLink;

        if (empty($lhs->$lhsLinkName) && !$lhs->load_relationship($lhsLinkName))
        {
            $lhsClass = get_class($lhs);
            $GLOBALS['log']->fatal("could not load LHS $lhsLinkName in $lhsClass");
            return false;
        }
        if (empty($rhs->$rhsLinkName) && !$rhs->load_relationship($rhsLinkName))
        {
            $rhsClass = get_class($rhs);
            $GLOBALS['log']->fatal("could not load RHS $rhsLinkName in $rhsClass");
            return false;
        }

        if ((empty($_SESSION['disable_workflow']) || $_SESSION['disable_workflow'] != "Yes"))
        {
            $lhs->$lhsLinkName->addBean($rhs);
            $rhs->$rhsLinkName->addBean($lhs);

            $this->callBeforeAdd($lhs, $rhs, $lhsLinkName);
            $this->callBeforeAdd($rhs, $lhs, $rhsLinkName);
        }

        //Many to many has no additional logic, so just add a new row to the table and notify the beans.
        $dataToInsert = $this->getRowToInsert($lhs, $rhs, $additionalFields);

        $this->addRow($dataToInsert);

        if ($this->self_referencing)
            $this->addSelfReferencing($lhs, $rhs, $additionalFields);

        if ((empty($_SESSION['disable_workflow']) || $_SESSION['disable_workflow'] != "Yes"))
        {
            $lhs->$lhsLinkName->addBean($rhs);
            $rhs->$rhsLinkName->addBean($lhs);

            $this->callAfterAdd($lhs, $rhs, $lhsLinkName);
            $this->callAfterAdd($rhs, $lhs, $rhsLinkName);
        }

        return true;
    }

    protected function getRowToInsert($lhs, $rhs, $additionalFields = array())
    {
        $row = array(
            "id" => create_guid(),
            $this->def['join_key_lhs'] => $lhs->id,
            $this->def['join_key_rhs'] => $rhs->id,
            'date_modified' => TimeDate::getInstance()->nowDb(),
            'deleted' => 0,
        );


        if (!empty($this->def['relationship_role_column']) && !empty($this->def['relationship_role_column_value']) && !$this->ignore_role_filter )
        {
            $row[$this->relationship_role_column] = $this->relationship_role_column_value;
        }

        if (!empty($this->def['fields']))
        {
            foreach($this->def['fields'] as $fieldDef)
            {
                if (!empty($fieldDef['name']) && !isset($row[$fieldDef['name']]) && !empty($fieldDef['default']))
                {
                    $row[$fieldDef['name']] = $fieldDef['default'];
                }
            }
        }
        if (!empty($additionalFields))
        {
            $row = array_merge($row, $additionalFields);
        }

        return $row;
    }

    /**
     * Adds the reversed version of this relationship to the table so that it can be accessed from either side equally
     * @param $lhs
     * @param $rhs
     * @param array $additionalFields
     * @return void
     */
    protected function addSelfReferencing($lhs, $rhs, $additionalFields = array())
    {
        if ($rhs->id != $lhs->id)
        {
            $dataToInsert = $this->getRowToInsert($rhs, $lhs, $additionalFields);
            $this->addRow($dataToInsert);
        }
    }

    public function remove($lhs, $rhs)
    {
        if(!($lhs instanceof SugarBean) || !($rhs instanceof SugarBean)) {
            $GLOBALS['log']->fatal("LHS and RHS must be beans");
            return false;
        }
        $lhsLinkName = $this->lhsLink;
        $rhsLinkName = $this->rhsLink;

        if (!($lhs instanceof SugarBean)) {
            $GLOBALS['log']->fatal("LHS is not a SugarBean object");
            return false;
        }
        if (!($rhs instanceof SugarBean)) {
            $GLOBALS['log']->fatal("RHS is not a SugarBean object");
            return false;
        }
        if (empty($lhs->$lhsLinkName) && !$lhs->load_relationship($lhsLinkName))
        {
            $GLOBALS['log']->fatal("could not load LHS $lhsLinkName");
            return false;
        }
        if (empty($rhs->$rhsLinkName) && !$rhs->load_relationship($rhsLinkName))
        {
            $GLOBALS['log']->fatal("could not load RHS $rhsLinkName");
            return false;
        }

        if (empty($_SESSION['disable_workflow']) || $_SESSION['disable_workflow'] != "Yes")
        {
            if ($lhs->$lhsLinkName instanceof Link2)
            {
                $lhs->$lhsLinkName->load();
                $this->callBeforeDelete($lhs, $rhs, $lhsLinkName);
            }

            if ($rhs->$rhsLinkName instanceof Link2)
            {
                $rhs->$rhsLinkName->load();
                $this->callBeforeDelete($rhs, $lhs, $rhsLinkName);
            }
        }

        $dataToRemove = array(
            $this->def['join_key_lhs'] => $lhs->id,
            $this->def['join_key_rhs'] => $rhs->id
        );

        $this->removeRow($dataToRemove);

        if ($this->self_referencing)
            $this->removeSelfReferencing($lhs, $rhs);

        if (empty($_SESSION['disable_workflow']) || $_SESSION['disable_workflow'] != "Yes")
        {
            if ($lhs->$lhsLinkName instanceof Link2)
            {
                $lhs->$lhsLinkName->load();
                $this->callAfterDelete($lhs, $rhs, $lhsLinkName);
            }

            if ($rhs->$rhsLinkName instanceof Link2)
            {
                $rhs->$rhsLinkName->load();
                $this->callAfterDelete($rhs, $lhs, $rhsLinkName);
            }
        }

        return true;
    }

    /**
     * Removes the reversed version of this relationship
     * @param $lhs
     * @param $rhs
     * @param array $additionalFields
     * @return void
     */
    protected function removeSelfReferencing($lhs, $rhs, $additionalFields = array())
    {
        if ($rhs->id != $lhs->id)
        {
            $dataToRemove = array(
                $this->def['join_key_lhs'] => $rhs->id,
                $this->def['join_key_rhs'] => $lhs->id
            );
            $this->removeRow($dataToRemove);
        }
    }

    /**
     * @param  $link Link2 loads the relationship for this link.
     * @return void
     */
    public function load($link, $params = array())
    {
        $db = DBManagerFactory::getInstance();
        $query = $this->getQuery($link, $params);
        $result = $db->query($query);
        $rows = Array();
        $idField = $link->getSide() == REL_LHS ? $this->def['join_key_rhs'] : $this->def['join_key_lhs'];
        while ($row = $db->fetchByAssoc($result, FALSE))
        {
            if (empty($row['id']) && empty($row[$idField]))
                continue;
            $id = empty($row['id']) ? $row[$idField] : $row['id'];
            $rows[$id] = $row;
        }
        return array("rows" => $rows);
    }

    protected function linkIsLHS($link) {
        return $link->getSide() == REL_LHS;
    }

    public function getQuery($link, $params = array())
    {
        if ($this->linkIsLHS($link)) {
            $knownKey = $this->def['join_key_lhs'];
            $targetKey = $this->def['join_key_rhs'];
            $relatedSeed = BeanFactory::getBean($this->getRHSModule());
            $relatedSeedKey = $this->def['rhs_key'];
            $whereTable = "";
            if (empty($params['right_join_table_alias'])){
                if ($relatedSeed !== false){
                    $whereTable = $relatedSeed->table_name;
                }
            } else {
                $whereTable = $params['right_join_table_alias'];
            }
        }
        else
        {
            $knownKey = $this->def['join_key_rhs'];
            $targetKey = $this->def['join_key_lhs'];
            $relatedSeed = BeanFactory::getBean($this->getLHSModule());
            $relatedSeedKey = $this->def['lhs_key'];
            $whereTable = "";
            if (empty($params['left_join_table_alias'])){
                if ($relatedSeed !== false){
                    $whereTable = $relatedSeed->table_name;
                }
            } else {
                $whereTable = $params['left_join_table_alias'];
            }
        }
        $rel_table = $this->getRelationshipTable();

        $where = "$rel_table.$knownKey = '{$link->getFocus()->id}'" . $this->getRoleWhere();

        //Add any optional where clause
        if (!empty($params['where']) && !empty($whereTable)){
            $add_where = is_string($params['where']) ? $params['where'] : "$whereTable." . $this->getOptionalWhereClause($params['where']);
            if (!empty($add_where))
                $where .= " AND $rel_table.$targetKey=$whereTable.id AND $add_where";
        }

        $deleted = !empty($params['deleted']) ? 1 : 0;
        $from = $rel_table . " ";
        if (!empty($params['enforce_teams']) && $relatedSeed !== false)
        {
            if ($rel_table != $relatedSeed->table_name) {
                $from .= "JOIN {$relatedSeed->table_name} ON {$rel_table}.{$targetKey} = {$relatedSeed->table_name}.{$relatedSeedKey} ";
            }
            $relatedSeed->add_team_security_where_clause($from);
        }
        if ((!empty($params['where']) || !empty($params['orderby'])) && !empty($whereTable)) {
            $from .= " LEFT JOIN $whereTable on $rel_table.$targetKey=$whereTable.id";
            if (isset($relatedSeed->custom_fields)) {
                $customJoin = $relatedSeed->custom_fields->getJOIN();
                $from .= $customJoin ? $customJoin['join'] : '';
            }
        }

        $select = "$targetKey id";
        foreach($this->getAdditionalFields() as $field=>$def){
            $select .= ", $rel_table.$field";
        }

        if (empty($params['return_as_array'])) {
            $orderby = (!empty($params['orderby']) && !empty($whereTable)) ? " ORDER BY $whereTable.{$params['orderby']}": "";
            $query = "SELECT $select FROM $from WHERE $where AND $rel_table.deleted=$deleted $orderby";
            //Limit is not compatible with return_as_array
            if (!empty($params['limit']) && $params['limit'] > 0) {
                $offset = isset($params['offset']) ? $params['offset'] : 0;
                $query = DBManagerFactory::getInstance()->limitQuery($query, $offset, $params['limit'], false, "", false);
            }
            return $query;
        }
        else
        {
            return array(
                'select' => "SELECT $select",
                'from' => "FROM $from",
                'where' => "WHERE $where AND $rel_table.deleted=$deleted",
            );
        }
    }

    public function getJoin($link, $params = array(), $return_array = false)
    {
        $linkIsLHS = $link->getSide() == REL_LHS;
        if ($linkIsLHS) {
            $startingTable = (empty($params['left_join_table_alias']) ? $link->getFocus()->table_name : $params['left_join_table_alias']);
        } else {
            $startingTable = (empty($params['right_join_table_alias']) ? $link->getFocus()->table_name : $params['right_join_table_alias']);
        }

        $startingKey = $linkIsLHS ? $this->def['lhs_key'] : $this->def['rhs_key'];
        $startingJoinKey = $linkIsLHS ? $this->def['join_key_lhs'] : $this->def['join_key_rhs'];
        $joinTable = $this->getRelationshipTable();
        $joinTableWithAlias = $joinTable;
        $joinKey = $linkIsLHS ? $this->def['join_key_rhs'] : $this->def['join_key_lhs'];
        $targetTable = $linkIsLHS ? $this->def['rhs_table'] : $this->def['lhs_table'];
        $targetTableWithAlias = $targetTable;
        $targetKey = $linkIsLHS ? $this->def['rhs_key'] : $this->def['lhs_key'];
        $join_type= isset($params['join_type']) ? $params['join_type'] : ' INNER JOIN ';

        $join = '';

        //Set up any table aliases required
        if (!empty($params['join_table_link_alias']))
        {
            $joinTableWithAlias = $joinTable . " ". $params['join_table_link_alias'];
            $joinTable = $params['join_table_link_alias'];
        }
        if ( ! empty($params['join_table_alias']))
        {
            $targetTableWithAlias = $targetTable . " ". $params['join_table_alias'];
            $targetTable = $params['join_table_alias'];
        }

        $join1 = "$startingTable.$startingKey=$joinTable.$startingJoinKey";
        $join2 = "$targetTable.$targetKey=$joinTable.$joinKey";
        $where = "";


        //First join the relationship table
        $join .= "$join_type $joinTableWithAlias ON $join1 AND $joinTable.deleted=0\n"
        //Next add any role filters
               . $this->getRoleWhere($joinTable) . "\n"
        //Then finally join the related module's table
               . "$join_type $targetTableWithAlias ON $join2 AND $targetTable.deleted=0\n";

        if($return_array){
            return array(
                'join' => $join,
                'type' => $this->type,
                'rel_key' => $joinKey,
                'join_tables' => array($joinTable, $targetTable),
                'where' => $where,
                'select' => "$targetTable.id",
            );
        }
        return $join . $where;
    }

    /**
     * Similar to getQuery or Get join, except this time we are starting from the related table and
     * searching for items with id's matching the $link->focus->id
     * @param  $link
     * @param array $params
     * @param bool $return_array
     * @return String|Array
     */
    public function getSubpanelQuery($link, $params = array(), $return_array = false)
    {
        $targetIsLHS = $link->getSide() == REL_RHS;
        $startingTable = $targetIsLHS ? $this->def['lhs_table'] : $this->def['rhs_table'];;
        $startingKey = $targetIsLHS ? $this->def['lhs_key'] : $this->def['rhs_key'];
        $startingJoinKey = $targetIsLHS ? $this->def['join_key_lhs'] : $this->def['join_key_rhs'];
        $joinTable = $this->getRelationshipTable();
        $joinTableWithAlias = $joinTable;
        $joinKey = $targetIsLHS ? $this->def['join_key_rhs'] : $this->def['join_key_lhs'];
        $targetKey = $targetIsLHS ? $this->def['rhs_key'] : $this->def['lhs_key'];
        $join_type= isset($params['join_type']) ? $params['join_type'] : ' INNER JOIN ';

        $query = '';

        //Set up any table aliases required
        if (!empty($params['join_table_link_alias']))
        {
            $joinTableWithAlias = $joinTable . " ". $params['join_table_link_alias'];
            $joinTable = $params['join_table_link_alias'];
        }

        $where = "$startingTable.$startingKey=$joinTable.$startingJoinKey AND $joinTable.$joinKey='{$link->getFocus()->$targetKey}'";

        //Check if we should ignore the role filter.
        $ignoreRole = !empty($params['ignore_role']);

        //First join the relationship table
        $query .= "$join_type $joinTableWithAlias ON $where AND $joinTable.deleted=0\n"
        //Next add any role filters
               . $this->getRoleWhere($joinTable, $ignoreRole) . "\n";

        if (!empty($params['return_as_array'])) {
            $return_array = true;
        }
        if($return_array){
            return array(
                'join' => $query,
                'type' => $this->type,
                'rel_key' => $joinKey,
                'join_tables' => array($joinTable),
                'where' => "",
                'select' => " ",
            );
        }
        return $query;

    }

    protected function getRoleFilterForJoin()
    {
        $ret = "";
        if (!empty($this->relationship_role_column) && !$this->ignore_role_filter)
        {
            $ret .= " AND ".$this->getRelationshipTable().'.'.$this->relationship_role_column;
            //role column value.
            if (empty($this->relationship_role_column_value))
            {
                $ret.=' IS NULL';
            } else {
                $ret.= "='".$this->relationship_role_column_value."'";
            }
            $ret.= "\n";
        }
        return $ret;
    }

    /**
     * @param  $lhs
     * @param  $rhs
     * @return bool
     */
    public function relationship_exists($lhs, $rhs)
    {
        $query = "SELECT id FROM {$this->getRelationshipTable()} WHERE {$this->join_key_lhs} = '{$lhs->id}' AND {$this->join_key_rhs} = '{$rhs->id}'";

        //Roles can allow for multiple links between two records with different roles
        $query .= $this->getRoleWhere() . " and deleted = 0";

        return $GLOBALS['db']->getOne($query);
    }

    /**
     * @return Array - set of fields that uniquely identify an entry in this relationship
     */
    protected function getAlternateKeyFields()
    {
        $fields = array($this->join_key_lhs, $this->join_key_rhs);

        //Roles can allow for multiple links between two records with different roles
        if (!empty($this->def['relationship_role_column']) && !$this->ignore_role_filter)
        {
            $fields[] = $this->relationship_role_column;
        }

        return $fields;
    }

    public function getRelationshipTable()
    {
        if (!empty($this->def['table']))
            return $this->def['table'];
        else if(!empty($this->def['join_table']))
            return $this->def['join_table'];

        return false;
    }

    public function getFields()
    {
        if (!empty($this->def['fields']))
            return $this->def['fields'];
        return $this->getStandardFields();
    }

    protected function getStandardFields(){
        $fields = array(
            "id" => array('name' => 'id'),
            'date_modified' => array('name' => 'date_modified'),
            'modified_user_id' => array('name' => 'modified_user_id'),
            'created_by' => array('name' => 'created_by'),
            $this->def['join_key_lhs'] => array('name' => $this->def['join_key_lhs']),
            $this->def['join_key_rhs'] => array('name' => $this->def['join_key_rhs'])
        );
        if (!empty($this->def['relationship_role_column']))
        {
            $fields[$this->def['relationship_role_column']] = array("name" => $this->def['relationship_role_column']);
        }
        $fields['deleted'] = array('name' => 'deleted');

        return $fields;
    }

    protected function getAdditionalFields(){
        $ret = array();
        if (!empty($this->def['fields']))
        {
            $standardFields = $this->getStandardFields();
            foreach($this->def['fields'] as $def){
                if (!isset($standardFields[$def['name']]))
                    $ret[$def['name']] = $def;
            }
        }
        return $ret;
    }

}
