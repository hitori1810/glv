<?php
    class ItemSelector {
        /**
        * Show table get Promotions
        * 
        */
        function getItemSelector() {
            $html = '
                <link rel="stylesheet" type="text/css" href="custom/include/javascripts/DataTables/css/jquery.dataTables.css" />
                <link rel="stylesheet" type="text/css" href="custom/include/utils/ItemSelector/ItemSelector.css" />
                <script type="text/javascript" src="custom/include/javascripts/DataTables/js/jquery.dataTables.min.js"></script>
                <script type="text/javascript" src="custom/include/javascripts/jquery.freezeheader.js"></script>
                <script type="text/javascript" src="custom/include/utils/ItemSelector/ItemSelector.js"></script>
                <div id="itemSelector" class="itemSelector" style="display: none;" title="'. $GLOBALS['app_strings']['LBL_ITEM_SELECTOR_TITLE'] .'">
                    <table id="tblItemSelector">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th width="20%">'. $GLOBALS['app_strings']['LBL_PROMOTION_NAME'] .'</th>
                                <th width="10%">'. $GLOBALS['app_strings']['LBL_PROMOTION_DISCOUNT'] .'</th>
                                <th width="20%">'. $GLOBALS['app_strings']['LBL_PROMOTION_MARKETING%'] .'</th>
                                <th width="20%">'. $GLOBALS['app_strings']['LBL_PROMOTION_CENTER%'] .'</th>
                                <th width="25%">'. $GLOBALS['app_strings']['LBL_PROMOTION_NOTE'] .'</th>
                            </tr>
                        </thead>
                        <tbody>{$ROWS}</tbody>
                    </table>
                    <br/>
                    <br/>
                    <div id="btnHolder" class="normal">
                        <button type="button" id="btnAddItems" class="button primary">'. $GLOBALS['app_strings']['LBL_BTN_ADD_ITEM'] .'</button>
                        <button type="button" id="btnClose" class="button">'. $GLOBALS['app_strings']['LBL_BTN_CLOSE'] .'</button>
                    <div>
                    <br/>
                </div>
            ';
            
            $promotion_items_html = ItemSelector::getAllPromotion();
            
            return str_replace('{$ROWS}', $promotion_items_html, $html);
        }
        /**
        * Get All promotion
        * 
        */
        static function getAllPromotion() {
            $defautTeam = $GLOBALS['current_user']->default_team;
            $now = $GLOBALS['timedate']->nowDbDate();
            $sql = "
                SELECT 
                    *
                FROM
                    c_promotions
                WHERE deleted = '0'
                AND team_id = '$defautTeam'
                AND start_date <= '$now'
                AND end_date >= '$now';";        
            $result = $GLOBALS['db']->query($sql);
            $data = array();
            $html = '';
            $i = 0;
            while($row = $GLOBALS['db']->fetchByAssoc($result)) {
                
                $html .= '
                    <tr class="'. (($i % 2) == 0 ? 'odd' : 'event') .'">
                        <td class="cbxHolder center"><input type="radio" name="rdItemId" class="rdItemId" value="'. $row['id'] .'"/></td>
                        <td><input class="promotionId" type="hidden" value="'.$row['id'].'"> <span class="promotionName">'. $row['name'] .'</span></td>
                        <td><span class="promotionDiscount">'. $row['discount'] .'</span></td>
                        <td><span class="marketingPercent">'. $row['marketing_percent'] .'</span></td>
                        <td><span class="centerPercent">'. $row['center_percent'] .'</span></td>
                        <td><span class="promotionNote">'. $row['description'] .'</span></td>
                    </tr>
                ';
                $i++;       
            }
            return $html;    
        }
        //end Get AllPromotion
    }
?>