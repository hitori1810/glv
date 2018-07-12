<?PHP

/**
 * The file used to handle survey actions
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
/**
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */
require_once('modules/bc_survey/bc_survey_sugar.php');

/**
 * when survey deleted from list view
 *
 * @author     Original Author Biztech Co.
 */
class bc_survey extends bc_survey_sugar {

    function bc_survey() {
        parent::bc_survey_sugar();
    }

    /**
     * This function should be overridden in each module.  It marks an item as deleted.
     *
     * If it is not overridden, then marking this type of item is not allowed
     */
    public function mark_deleted($id) {
        global $current_user, $db;
        $date_modified = $GLOBALS['timedate']->nowDb();
        if (isset($_SESSION['show_deleted'])) {
            $this->mark_undeleted($id);
        } else {
            // call the custom business logic
            $custom_logic_arguments['id'] = $id;
            $this->call_custom_logic("before_delete", $custom_logic_arguments);
            //check default record or not
            $query = "SELECT
                    bc_survey_submission.id AS submission_id 
                  FROM bc_survey_submission
                    JOIN bc_survey_submission_bc_survey_c
                      ON bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb = bc_survey_submission.id
                        AND bc_survey_submission.deleted = 0
                    JOIN bc_survey
                      ON bc_survey.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida
                        AND bc_survey_submission_bc_survey_c.deleted = 0
                  WHERE bc_survey.deleted = 0  AND bc_survey.id = '{$id}'";
            $result = $db->query($query);
            while ($row = $db->fetchByAssoc($result)) {
            // delete related relationship records wirh bc_survey_submission
            $deleteRelationQry = 'update bc_survey_submission set deleted = "1" where id="' . $row['submission_id'] . '"';
            $db->query($deleteRelationQry);
            }
            $this->deleted = 1; // delete given record
            $automizer_delete = "SELECT id FROM bc_automizer_actions WHERE survey_id = '{$id}'";
            $ans = $db->query($automizer_delete);
            while($answer = $db->fetchByAssoc($ans)){
                $deleteRelationQry = 'update bc_automizer_actions set deleted = "1" where id="' . $answer['id'] . '"';
                $db->query($deleteRelationQry);
            }
            $this->mark_relationships_deleted($id);
            if (isset($this->field_defs['modified_user_id'])) {
                if (!empty($current_user)) {
                    $this->modified_user_id = $current_user->id;
                } else {
                    $this->modified_user_id = 1;
                }
                $query = "UPDATE $this->table_name set deleted=1 , date_modified = '$date_modified', modified_user_id = '$this->modified_user_id' where id='$id'";
            } else {
                $query = "UPDATE $this->table_name set deleted=1 , date_modified = '$date_modified' where id='$id'";
            }
            $this->db->query($query, true, "Error marking record deleted: ");

            SugarRelationship::resaveRelatedBeans();

            // Take the item off the recently viewed lists
            $tracker = new Tracker();
            $tracker->makeInvisibleForAll($id);
        }

        // call the custom business logic
        $this->call_custom_logic("after_delete", $custom_logic_arguments);
    }

}

?>