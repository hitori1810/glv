<?php
    /**
    * Overide Class tao cau query trong sugar bean.
    * Create By Thanh Le At 11/2012
    * Modified Bt Thanh Le At 9/4/2013
    * Source By OnlineCRM VietNam
    */
    Class CreateExportQuery extends SugarBean{
        function create_export_query(&$order_by, &$where, $relate_link_join=''){
            $table_name = $this->table_name;
            $custom_join['join'] = '';
            $custom_join = $this->custom_fields->getJOIN(true, true,$where);
            global $current_user, $module;
            $displayColumns = $_SESSION[$current_user->user_name.$module]['displayColumns']?$_SESSION[$current_user->user_name.$module]['displayColumns']:array();//neu ko co phan nay thi ko chạy export query khi ko co display column

            if($displayColumns != null){
                $test = parent::create_new_list_query("", $where, $displayColumns, null,false,"", true);
                $select_fields = "";
                $n=0;
                $export_cols = array();
                //while($field_name =  key($displayColumns)){ 
                while($field_name =  key($displayColumns)){
                    $field_name = strtolower($field_name); 
                    $field_defs = $this->field_defs[$field_name];
                    if($field_defs){
                        if(count($field_defs['fields'])>0){
                            for($i=0;$i<count($field_defs['fields']);$i++){
                                $sub_field = strtolower($field_defs['fields'][$i]);
                                if($sub_field != "id" && in_array($this->table_name. "." .$sub_field, $export_cols) == false){
                                    $export_cols[] = $this->table_name. "." .$sub_field;
                                }
                            }
                        }else{
                            if($field_name != "id" && $field_defs['source'] != 'non-db' && in_array($this->table_name. "." .$field_name, $export_cols) == false){
                                $export_cols[] = $this->table_name. "." .$field_name;
                            }elseif($field_defs['source'] == 'non-db'){
                                if($field_name == 'assigned_user_name'){
                                    $export_cols[] = "TRIM(CONCAT(IFNULL(users.first_name,''),' ',IFNULL(users.last_name,''))) AS assigned_user_name ";
                                    $custom_join['join'] .= " LEFT JOIN users ON users.id = {$this->table_name}.{$field_defs['id_name']} AND users.deleted=0 ";    
                                }
                                elseif($field_name == 'parent_name'){
                                    $export_cols[] = $this->table_name.'.parent_id';
                                    $export_cols[] = $this->table_name.'.parent_type';
                                }
                                else{
                                    $relationships = $this->field_defs[$field_defs['link']]['relationship'];
                                    $link = $GLOBALS['dictionary'][$relationships]; 
                                    if(!$link && $field_name != "created_by_name" && $field_name!= 'modified_by_name'  && $field_name!= 'email1'){
                                        if($field_defs['type'] == 'relate'){
                                            if(count($field_defs['db_concat_fields'])>0){
                                                for($i=0;$i<count($field_defs['db_concat_fields']);$i++){
                                                    $sub_field = strtolower($field_defs['db_concat_fields'][$i]);
                                                    if($sub_field != "id" && in_array($this->table_name. "." .$sub_field, $export_cols) == false){
                                                        $export_cols_temp[] = 'IFNULL('.$field_defs['table']. "." .$sub_field.',"")';
                                                    }
                                                }
                                                $export_cols_temp = implode(',',$export_cols_temp);
                                                $export_cols[] = 'CONCAT('.$export_cols_temp.') AS '.$field_name;
                                                $custom_join['join'] .= " LEFT JOIN ".$field_defs['table']." ON ".$field_defs['table'].".id = {$this->table_name}.{$field_defs['id_name']} AND ".$field_defs['table'].".deleted=0 "; 
                                            }
                                        } 
                                    }
                                    else{
                                        $left = $link['relationships'][$relationships]['join_key_lhs'];
                                        $right = $link['relationships'][$relationships]['join_key_rhs'];
                                        if($link['table'] != '' && $field_defs['table'] != ''){
                                            $table_list = explode('_',$field_defs['link']); // lay ra bang ten cua bang left
                                            if($table_list[0] == $this->table_name){ // Neu bang left == bang chinh thi id bang chinh = left
                                                $custom_join['join'] .= " LEFT JOIN {$link['table']} ON {$link['table']}.{$left} = {$this->table_name}.id AND {$link['table']}.deleted=0";
                                                $custom_join['join'] .= " LEFT JOIN {$field_defs['table']} ON {$field_defs['table']}.id = {$link['table']}.{$right} AND {$field_defs['table']}.deleted=0";
                                            }else{   // Nguoc lai id bang chinh bang =  right
                                                $custom_join['join'] .= " LEFT JOIN {$link['table']} ON {$link['table']}.{$right} = {$this->table_name}.id AND {$link['table']}.deleted=0";
                                                $custom_join['join'] .= " LEFT JOIN {$field_defs['table']} ON {$field_defs['table']}.id = {$link['table']}.{$left} AND {$field_defs['table']}.deleted=0";
                                            }
                                        }elseif($field_defs['name'] == 'email1'){
                                            $export_cols[] = ' email_addresses.email_address email_address ';
                                            $custom_join['join'] .= ' LEFT JOIN  email_addr_bean_rel on '.$table_name.'.id = email_addr_bean_rel.bean_id and email_addr_bean_rel.bean_module="'.$table_name.'" and email_addr_bean_rel.deleted=0 and email_addr_bean_rel.primary_address=1';
                                            $custom_join['join'] .= ' LEFT JOIN email_addresses on email_addresses.id = email_addr_bean_rel.email_address_id ';
                                        }elseif($field_defs['type'] != 'datetime'){
                                            if($field_defs['name'] == 'created_by_name'){
                                                $field_defs['name'] = 'created_by' ;
                                                $export_cols[] = 'created_by_temp.full_name AS created_by';
                                                $custom_join['join'] .= " LEFT JOIN (SELECT id, TRIM(CONCAT(IFNULL(users.first_name,''),' ',IFNULL(users.last_name,''))) AS full_name FROM users WHERE deleted =0 ) created_by_temp ON ".$table_name.".created_by = created_by_temp.id";
                                                 
                                            }elseif($field_defs['name'] == 'modified_by_name'){
                                                $field_defs['name'] = 'modified_user_id' ;
                                                $export_cols[] = 'modified_user_temp.full_name AS modified_by_name';
                                                $custom_join['join'] .= " LEFT JOIN (SELECT id, TRIM(CONCAT(IFNULL(users.first_name,''),' ',IFNULL(users.last_name,''))) AS full_name FROM users WHERE deleted =0 ) modified_user_temp ON ".$table_name.".modified_user_id = modified_user_temp.id";
                                            }
                                            else{
                                                $custom_join['join'] .= " LEFT JOIN users users_{$field_defs['name']} ON users_{$field_defs['name']}.id = {$this->table_name}.{$field_defs['name']} AND users_{$field_defs['name']}.deleted=0";
                                            }
                                        }
                                        if($field_defs['table'] != ''){
                                            if($field_defs['table'] == 'leads' || $field_defs['table'] == 'contacts'){
                                                $export_cols[] = " TRIM(CONCAT(IFNULL({$field_defs['table']}.first_name,''),' ',IFNULL({$field_defs['table']}.last_name,''))) AS {$field_name} ";
                                            }else{
                                                $export_cols[] = "{$field_defs['table']}.name as {$field_name}";
                                            }
                                        }
                                    }
                                }
                            }elseif(($field_defs['table']==$table_name ||$field_defs['table']=='users') && in_array($field_defs['table']. "." .$field_defs['rname'], $export_cols) == false){
                                if($field_defs['table']==$table_name){
                                    $export_cols[] = $field_defs['table']. "." .$field_defs['rname']." AS account_name";  
                                }else{
                                    $export_cols[] = " TRIM(CONCAT(IFNULL(users.first_name,''),' ',IFNULL(users.last_name,''))) AS customer_care_name ";
                                }
                            }elseif($field_name == 'email1'){  
                                $export_cols[] = ' email_addresses.email_address email_address '; 
                            }elseif($field_name == 'company_address') {
                                $export_cols[] = $table_name.'.address AS company_address ';
                            }elseif($field_name == 'company_phone'){
                                $export_cols[] = $table_name.'.phone_office AS company_phone';
                            }elseif($field_name == 'company_industry'){
                                $export_cols[] = $table_name.'.industry AS company_industry';   
                            } 
                        } 
                    }

                    next($displayColumns);
                    $n++;
                }
                if(count($export_cols))     
                    $select_fields = implode(",",$export_cols);
                else
                    $select_fields = "{$table_name}.*";
                $query = "SELECT DISTINCT
                {$select_fields} ";
                /*if($custom_join){
                $query .= $custom_join['select'];
                }*/
                $query .= " FROM {$table_name} ";
                if($custom_join){
                    $query .= $custom_join['join'];
                } 
                if($where != ""){
                    $query .= " where ($where)"; 
                }

                if(!empty($order_by))
                    $query .=  " ORDER BY ". $this->process_order_by($order_by, null);

                return $query;
            }
        }
    }

?>