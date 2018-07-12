<?php
    class ProgramHelper {
        static function getProgram($id) {
            global $db;
            $sql = 'SELECT * FROM c_programs WHERE id = "'. $id .'" and deleted = 0';
            $result = $db->query($sql);
            $row = $db->fetchByAssoc($result);
            return $row;    
        }
    }
?>
