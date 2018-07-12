<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');



class J_DiscountViewDetail extends ViewDetail {


    function display() {
        // get gift
        $schemaHtmlContent = '';
        if($this->bean->type == 'Gift'){
            $content=json_decode(htmlspecialchars_decode($this->bean->content), true);
            $schemaHtmlContent ='';
            $schemaHtmlContent .= '<ul style="margin-left: 0;">';
            foreach($content as $key => $value) {
                $schemaHtmlContent .= '<li>';
                $schemaHtmlContent .= $this->_buildGiftRow($key, $value);
                $schemaHtmlContent .= '</li>';
            }
            $schemaHtmlContent .= '</ul>';
        }
        // get Partnership
        $part = '';
        if($this->bean->type == 'Partnership'){
            $this->bean->load_relationship('j_discount_j_partnership_1');
            $partnership= $this->bean->j_discount_j_partnership_1->getBeans();
            $part .= '<ul style="margin-left: 0;">';
            foreach ($partnership as $partner)
            {   $part .= '<li>';
                $part .= '<a href=index.php?module=J_Partnership&offset=1&stamp=1442306303029459400&return_module=J_Partnership&action=DetailView&record='.$partner->id.' TARGET=_blank>'.$partner->name.'</a>';
                $part .= '</li>';
            }
            $part .= '</ul>';
        }
        // get "do not apply with" list - Tung Bui
        $disableList = json_decode(html_entity_decode($this->bean->disable_list),true);
        $schemaHtml .= '<ul style="margin-left: 0;">';
        foreach ($disableList as $value){
            $disable_discount = BeanFactory::getBean("J_Discount", $value);
            if (!empty($disable_discount->id)){
                $schemaHtml .= '<li>';
                $schemaHtml .= $disable_discount->type.': <a href="index.php?module=J_Discount&action=DetailView&record='.$disable_discount->id.'" target=_blank>'.$disable_discount->name.'</a> ('.$disable_discount->team_name.')';
                $schemaHtml .= '</li>';
            }
        }
        $schemaHtml .= '</ul>';
        //Get Hour Discount
        if($this->bean->type == 'Hour'){
            $html = '<table id="discount_by_hour" style="width: 40%;float:left;" border="1" class="list view">
            <thead>
            <tr>
            <th width="50%" style="text-align: center;">Tuition Hours</th>
            <th width="50%" style="text-align: center;">Promotion Hours</th>
            </tr>
            </thead>
            <tbody id="tbodydiscount_by_hour">
            ';
            $content = json_decode(htmlspecialchars_decode($this->bean->content), true);
            foreach($content['discount_by_hour'] as $key => $value)
                $html .= "<tr><td style='text-align: center;'>{$value['hours']}</td><td style='text-align: center;'>{$value['promotion_hours']}</td></tr>";
            //add Discount by Block
            $html .= '</tbody></table>';
            $html .= '<table id="discount_by_range" style="width: 40%; float:left; margin-left: 40px;" border="1" class="list view">
            <thead>
            <tr>
            <th width="60%" style="text-align: center;">Hour Range</th>
            <th width="40%" style="text-align: center;">Block</th>
            </tr>
            </thead>
            <tbody id="tbodydiscount_by_range">
            ';
            foreach($content['discount_by_range'] as $key => $value)
                $html .= "<tr><td style='text-align: center;'>{$value['start_hour']} - {$value['to_hour']}</td><td style='text-align: center;'>{$value['block']}</td></tr>";
            $html .= '</tbody></table>';
        }

        $this->ss->assign('SCHEMA', $schemaHtml);
        $this->ss->assign('CONTENT', $schemaHtmlContent);
        $this->ss->assign('CONTENT_2', $html);
        $this->ss->assign('PARTNERSHIP', $part);
        parent::display();
    }
    private function _buildGiftRow($id, $data) {
        $sql ="SELECT unit FROM product_templates WHERE id='".$id."'";
        $unit = $GLOBALS['db']->getone($sql);
        return '<a href=index.php?module=ProductTemplates&offset=2&stamp=1437618694097285200&return_module=ProductTemplates&action=DetailView&record='.$id.' TARGET=_blank>'.$data['book_name'].'</a> &nbsp; <label> '.$data['qty_book'].' '.$unit.'</label>';
    }
}
?>