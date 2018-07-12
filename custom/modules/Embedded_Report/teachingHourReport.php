<?php
     $this->$query_name = str_replace('ju_class_id=l1.id AND l1.deleted=0','ju_class_id=l1.id AND l1.deleted=0 and meetings.id in (select distinct meeting_id from meetings_contacts where deleted = 0)',$this->$query_name);
?>
