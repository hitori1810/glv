<?php
    function getCenter($center_id) {
        global $db, $current_user;
        $qr = "";
        if(!$current_user->isAdmin()) {
            $sql_get_my_team = "SELECT DISTINCT
            rel.team_id
            FROM
            team_memberships rel
            RIGHT JOIN teams ON (rel.team_id = teams.id)
            WHERE rel.user_id = '".$current_user->id."' AND teams.private = 0
            AND rel.deleted = 0 AND teams.deleted = 0";
            $result = $db->query($sql_get_my_team);

            $teamIds = array();
            while($row = $db->fetchByAssoc($result)) {
                $teamIds[] = $row['team_id'] ;
            }
            $qr = " AND t1.id IN ('".implode("','", $teamIds)."') ";
        }
        

        $sql_get_team = "SELECT DISTINCT t1.id, t1.name
        FROM teams t1
        WHERE t1.deleted <> 1 AND t1.private <> 1
        $qr                                                             
        ORDER BY t1.name";
        $result = $db->query($sql_get_team);
        $team_array = array("" => "--None--");
        while($row = $db->fetchByAssoc($result)) {
            $team_array[$row['id']] = $row['name'];
        }
        $team_options = get_select_options_with_id($team_array,$center_id);
        return $team_options;
    }
?>
