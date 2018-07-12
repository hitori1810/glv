<?php
// NOTE => Defining the field definition 
require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');
class SugarFieldWysiwyg extends SugarFieldBase {

  function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
    return parent::getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }

  function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
      require_once('include/SugarTinyMCE.php');
      global $json;
    
    if(empty($json)) {
      $json = getJSONobj();
    }
    $tiny = new SugarTinyMCE();
    $tiny->buttonConfigs['default']['buttonConfig'] = 'code,separator,help,separator,bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,forecolor,backcolor,separator,formatselect';
    $tiny->buttonConfigs['default']['buttonConfig2'] = "selectall,separator,search,separator,replace,separator,bullist,separator,numlist,separator,outdent,separator,indent,separator,undo,separator,redo,separator,link,separator,unlink,separator,anchor,separator,image,separator,hr,separator,insertdate,separator,inserttime";
    $tiny->defaultConfig['apply_source_formatting']=false;
    $tiny->defaultConfig['cleanup_on_startup']=true;
    $tiny->defaultConfig['relative_urls']=false;
    $tiny->defaultConfig['convert_urls']=false;
    $tiny->defaultConfig['strict_loading_mode'] = true;
    $tiny->defaultConfig['width'] = '50%';
    $config = $tiny->defaultConfig;
    $config['elements'] = $vardefs['name'];
    $config['theme_advanced_buttons1'] = $tiny->buttonConfigs['default']['buttonConfig']; 
    $config['theme_advanced_buttons2'] = $tiny->buttonConfigs['default']['buttonConfig2']; 
    //$config['theme_advanced_buttons3'] = $tiny->buttonConfigs['default']['buttonConfig3']; 
    $jsConfig = $json->encode($config);
    $initiate = '<script type="text/javascript" language="Javascript">tinyMCE.init('.$jsConfig.');</script>';
    $this->ss->assign("tiny", $initiate);
    return parent::getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }
}
?>