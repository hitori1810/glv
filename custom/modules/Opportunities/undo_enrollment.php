<?php
require_once("custom/include/_helper/class_utils.php");
$enr = BeanFactory::getBean('Opportunities',$_POST['oppid']);
//$enr->load_relationship('c_classes_opportunities_1');
//$classes = $enr->c_classes_opportunities_1->getBeans();
//foreach($classes as $class_id => $class){
//	addPubToClass($enr->id, $class_id, '1');
//}
//
$q2 = "UPDATE opportunities SET sales_stage='Success', description = '' WHERE id='{$enr->id}'";
$GLOBALS['db']->query($q2);

$q3 = "UPDATE c_deliveryrevenue SET deleted='1' WHERE enrollment_id='{$enr->id}' AND passed = 0";
$GLOBALS['db']->query($q3);

echo json_encode(array(
	"success" => "1",
));

?>
