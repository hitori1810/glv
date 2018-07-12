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

require_once('include/ytree/Tree.php');
require_once('include/ytree/Node.php');
class MBPackageTree{
	
	function MBPackageTree(){
		$this->tree = new Tree('package_tree');
		$this->tree->id = 'package_tree';
		$this->mb = new ModuleBuilder();
		$this->populateTree($this->mb->getNodes(), $this->tree);
	}
	
	function getName(){
		return 'Packages';
	}
	
	function populateTree($nodes, &$parent){
		foreach($nodes as $node){
			if(empty($node['label']))$node['label'] = $node['name'];
			$yn = new Node($parent->id . '/' . $node['name'],$node['label']);
			if(!empty($node['action']))
			$yn->set_property('action', $node['action']);
			$yn->set_property('href', 'javascript:void(0);');
			$yn->id = $parent->id . '/' . $node['name'];
			if(!empty($node['children']))$this->populateTree($node['children'], $yn);
			$parent->add_node($yn);
		}
	}
	
	function fetch(){
		//return $this->tree->generate_header() . $this->tree->generate_nodes_array();
		return $this->tree->generate_nodes_array();
	}
	
	function fetchNodes(){
		return $this->tree->generateNodesRaw();
	}
	
	
	
	
	
}
?>