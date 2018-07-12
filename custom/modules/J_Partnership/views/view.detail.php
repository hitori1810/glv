<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');



    class J_DiscountViewDetail extends ViewDetail {


        function display() {     
            $this->bean->load_relationship('j_discount_j_discount_1');
            $discountrela= $this->bean->j_discount_j_discount_1->getBeans(); 
            $html .= '<ul style="margin-left: 0;">';
            foreach ($discountrela as $dis)
            {   $html .= '<li>';
                $html .= '<a href=index.php?module=J_Discount&offset=4&stamp=1436156623065410800&return_module=J_Discount&action=DetailView&record='.$dis->id.' TARGET=_blank>'.$dis->name.'</a>';                
                $html .= '</li>';
            }
            $html .= '</ul>';
            // get gift
            $content=json_decode(htmlspecialchars_decode($this->bean->content), true);
            $htmlContent ='';  
            $htmlContent .= '<ul style="margin-left: 0;">';
            foreach($content as $key => $value) {
                $htmlContent .= '<li>'; 
                $htmlContent .= $this->_buildGiftRow($key, $value); 
                $htmlContent .= '</li>';
            }
            $htmlContent .= '</ul>';
            $this->ss->assign('CONTENT', $htmlContent);
            $this->ss->assign('SCHEMA', $html); 
            parent::display();
        }
        private function _buildGiftRow($id, $data) {
            $sql ="SELECT unit FROM product_templates WHERE id='".$id."'";
            $unit = $GLOBALS['db']->getone($sql); 
            return '<a href=index.php?module=ProductTemplates&offset=2&stamp=1437618694097285200&return_module=ProductTemplates&action=DetailView&record='.$id.' TARGET=_blank>'.$data['book_name'].'</a> &nbsp; <label> '.$data['qty_book'].' '.$unit.'</label>';
        }
    }
?>