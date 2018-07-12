<?php
   if(isset($_POST)){
    $bean = BeanFactory::getBean($_POST['modulename'],$_POST['id']);
    if($bean->load_relationship($_POST['ref'])){
      $relBean = $bean->$_POST['ref']->getBeans();
      $relBean = reset($relBean);
      $result = array();
      for($i=0;$i<count($_POST['fields']);$i++){
        $result[$_POST['pop_list'][$i]] = $relBean->$_POST['fields'][$i]; 
      }  
    }
    echo json_encode($result);    
   }

