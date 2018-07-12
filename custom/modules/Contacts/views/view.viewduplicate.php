<?php    
    class ContactsViewViewDuplicate extends SugarView{    
        function ContactsViewViewDuplicate(){      
            
            parent::SugarView();
        }
        function Display(){
            global $mod_strings;   
            $sql = "SELECT c.id, c.first_name, c.last_name, c.contact_id, c.full_student_name, c.birthdate, c.phone_mobile, c.guardian_name, e.email_address
                FROM contacts c
                INNER JOIN (
                    SELECT c_t.first_name, c_t.last_name, c_t.birthdate, c_t.phone_mobile, count(c_t.id)
                    FROM contacts c_t
                    WHERE c_t.deleted = 0
                    GROUP BY c_t.first_name, c_t.last_name, c_t.birthdate, c_t.phone_mobile
                    HAVING count(c_t.id) > 1
                ) AS c1 ON c1.first_name = c.first_name AND c1.last_name = c.last_name
                AND c1.birthdate = c.birthdate AND c1.phone_mobile = c.phone_mobile
                LEFT JOIN email_addr_bean_rel eb ON eb.bean_id = c.id
                LEFT JOIN email_addresses e ON eb.email_address_id = e.id
                WHERE c.deleted = 0
                ORDER by c.first_name, c.last_name, c.birthdate, c.phone_mobile
            ";   
            $result = $GLOBALS['db']->query($sql);
            $rowData = "";
            
            while($row = $GLOBALS['db']->fetchByAssoc($result)) {             
                $rowData .= "<tr>
                                <td><a href = 'index.php?module=Contacts&action=DetailView&record=".$row['id']."'>". $row['full_student_name'] ."</a></td>
                                <td align='center'>". $row['phone_mobile'] ."</td>
                                <td>". $row['email_address'] ."</td>
                                <td align='center'>". $GLOBALS['timedate']->to_display_date($row['birthdate']) ."</td>
                                <td>". $row['guardian_name'] ."</td>
                            </tr>";   
            }
            
            $ss = new Sugar_Smarty();
            $ss->assign('MOD', $mod_strings);
            $ss->assign('ROW_DATA', $rowData);
            $ss->display('custom/modules/Contacts/tpls/viewDuplicate.tpl');
            parent::Display();                        
        }
    }
?>

