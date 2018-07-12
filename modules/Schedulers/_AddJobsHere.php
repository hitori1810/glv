<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/


/**
 * Set up an array of Jobs with the appropriate metadata
 * 'jobName' => array (
 * 		'X' => 'name',
 * )
 * 'X' should be an increment of 1
 * 'name' should be the EXACT name of your function
 *
 * Your function should not be passed any parameters
 * Always  return a Boolean. If it does not the Job will not terminate itself
 * after completion, and the webserver will be forced to time-out that Job instance.
 * DO NOT USE sugar_cleanup(); in your function flow or includes.  this will
 * break Schedulers.  That function is called at the foot of cron.php
 */

/**
 * This array provides the Schedulers admin interface with values for its "Job"
 * dropdown menu.
 */
$job_strings = array (
	0 => 'refreshJobs',
	1 => 'pollMonitoredInboxes',
	2 => 'runMassEmailCampaign',
    5 => 'pollMonitoredInboxesForBouncedCampaignEmails',
	3 => 'pruneDatabase',
	4 => 'trimTracker',
	/*4 => 'securityAudit()',*/
    6 => 'processWorkflow',
	7 => 'processQueue',
    9 => 'updateTrackerSessions',
    12 => 'sendEmailReminders',
    13 => 'performFullFTSIndex',
    14 => 'cleanJobQueue',
    //Add class to build additional TimePeriods as necessary
    15 => 'class::SugarJobCreateNextTimePeriod',
    16 => 'trimSugarFeeds',
    15 => 'removeDocumentsFromFS',

);

/**
 * Job 0 refreshes all job schedulers at midnight
 * DEPRECATED
 */
function refreshJobs() {
	return true;
}


/**
 * Job 1
 */
function pollMonitoredInboxes() {

    $_bck_up = array('team_id' => $GLOBALS['current_user']->team_id, 'team_set_id' => $GLOBALS['current_user']->team_set_id);
	$GLOBALS['log']->info('----->Scheduler fired job of type pollMonitoredInboxes()');
	global $dictionary;
	global $app_strings;


	require_once('modules/Emails/EmailUI.php');

	$ie = new InboundEmail();
	$emailUI = new EmailUI();
	$r = $ie->db->query('SELECT id, name FROM inbound_email WHERE is_personal = 0 AND deleted=0 AND status=\'Active\' AND mailbox_type != \'bounce\'');
	$GLOBALS['log']->debug('Just got Result from get all Inbounds of Inbound Emails');

	while($a = $ie->db->fetchByAssoc($r)) {
		$GLOBALS['log']->debug('In while loop of Inbound Emails');
		$ieX = new InboundEmail();
		$ieX->retrieve($a['id']);
        $GLOBALS['current_user']->team_id = $ieX->team_id;
        $GLOBALS['current_user']->team_set_id = $ieX->team_set_id;
		$mailboxes = $ieX->mailboxarray;
		foreach($mailboxes as $mbox) {
			$ieX->mailbox = $mbox;
			$newMsgs = array();
			$msgNoToUIDL = array();
			$connectToMailServer = false;
			if ($ieX->isPop3Protocol()) {
				$msgNoToUIDL = $ieX->getPop3NewMessagesToDownloadForCron();
				// get all the keys which are msgnos;
				$newMsgs = array_keys($msgNoToUIDL);
			}
			if($ieX->connectMailserver() == 'true') {
				$connectToMailServer = true;
			} // if

			$GLOBALS['log']->debug('Trying to connect to mailserver for [ '.$a['name'].' ]');
			if($connectToMailServer) {
				$GLOBALS['log']->debug('Connected to mailserver');
				if (!$ieX->isPop3Protocol()) {
					$newMsgs = $ieX->getNewMessageIds();
				}
				if(is_array($newMsgs)) {
					$current = 1;
					$total = count($newMsgs);
					require_once("include/SugarFolders/SugarFolders.php");
					$sugarFolder = new SugarFolder();
					$groupFolderId = $ieX->groupfolder_id;
					$isGroupFolderExists = false;
					$users = array();
					if ($groupFolderId != null && $groupFolderId != "") {
						$sugarFolder->retrieve($groupFolderId);
						$isGroupFolderExists = true;
						$_REQUEST['team_id'] = $sugarFolder->team_id;
						$_REQUEST['team_set_id'] = $sugarFolder->team_set_id;
					} // if
					$messagesToDelete = array();
					if ($ieX->isMailBoxTypeCreateCase()) {
						$users[] = $sugarFolder->assign_to_id;
						require_once("modules/Teams/TeamSet.php");
						require_once("modules/Teams/Team.php");
						$GLOBALS['log']->debug('Getting users for teamset');
						$teamSet = new TeamSet();
						$usersList = $teamSet->getTeamSetUsers($sugarFolder->team_set_id, true);
						$GLOBALS['log']->debug('Done Getting users for teamset');
						$users = array();
						foreach($usersList as $userObject) {
							if ($userObject->is_group) {
								continue;
							} // if
							$users[] = $userObject->id;
						} // foreach

						$distributionMethod = $ieX->get_stored_options("distrib_method", "");
						if ($distributionMethod != 'roundRobin') {
							$counts = $emailUI->getAssignedEmailsCountForUsers($users);
						} else {
							$lastRobin = $emailUI->getLastRobin($ieX);
						}
						$GLOBALS['log']->debug('distribution method id [ '.$distributionMethod.' ]');
					}
					foreach($newMsgs as $k => $msgNo) {
						$uid = $msgNo;
						if ($ieX->isPop3Protocol()) {
							$uid = $msgNoToUIDL[$msgNo];
						} else {
							$uid = imap_uid($ieX->conn, $msgNo);
						} // else
						if ($isGroupFolderExists) {
							$_REQUEST['team_id'] = $sugarFolder->team_id;
							$_REQUEST['team_set_id'] = $sugarFolder->team_set_id;
							if ($ieX->importOneEmail($msgNo, $uid)) {
								// add to folder
								$sugarFolder->addBean($ieX->email);
								if ($ieX->isPop3Protocol()) {
									$messagesToDelete[] = $msgNo;
								} else {
									$messagesToDelete[] = $uid;
								}
								if ($ieX->isMailBoxTypeCreateCase()) {
									$userId = "";
									if ($distributionMethod == 'roundRobin') {
										if (sizeof($users) == 1) {
											$userId = $users[0];
											$lastRobin = $users[0];
										} else {
											$userIdsKeys = array_flip($users); // now keys are values
											$thisRobinKey = $userIdsKeys[$lastRobin] + 1;
											if(!empty($users[$thisRobinKey])) {
												$userId = $users[$thisRobinKey];
												$lastRobin = $users[$thisRobinKey];
											} else {
												$userId = $users[0];
												$lastRobin = $users[0];
											}
										} // else
									} else {
										if (sizeof($users) == 1) {
											foreach($users as $k => $value) {
												$userId = $value;
											} // foreach
										} else {
											asort($counts); // lowest to highest
											$countsKeys = array_flip($counts); // keys now the 'count of items'
											$leastBusy = array_shift($countsKeys); // user id of lowest item count
											$userId = $leastBusy;
											$counts[$leastBusy] = $counts[$leastBusy] + 1;
										}
									} // else
									$GLOBALS['log']->debug('userId [ '.$userId.' ]');
									$ieX->handleCreateCase($ieX->email, $userId);
								} // if
							} // if
						} else {
								if($ieX->isAutoImport()) {
									$ieX->importOneEmail($msgNo, $uid);
								} else {
									/*If the group folder doesn't exist then download only those messages
									 which has caseid in message*/
									$ieX->getMessagesInEmailCache($msgNo, $uid);
									$email = new Email();
									$header = imap_headerinfo($ieX->conn, $msgNo);
									$email->name = $ieX->handleMimeHeaderDecode($header->subject);
									$email->from_addr = $ieX->convertImapToSugarEmailAddress($header->from);
									$email->reply_to_email  = $ieX->convertImapToSugarEmailAddress($header->reply_to);
									if(!empty($email->reply_to_email)) {
										$contactAddr = $email->reply_to_email;
									} else {
										$contactAddr = $email->from_addr;
									}
									$mailBoxType = $ieX->mailbox_type;
									if (($mailBoxType == 'support') || ($mailBoxType == 'pick')) {
										if(!class_exists('aCase')) {

										}
										$c = new aCase();
										$GLOBALS['log']->debug('looking for a case for '.$email->name);
										if ($ieX->getCaseIdFromCaseNumber($email->name, $c)) {
											$ieX->importOneEmail($msgNo, $uid);
										} else {
											$ieX->handleAutoresponse($email, $contactAddr);
										} // else
									} else {
										$ieX->handleAutoresponse($email, $contactAddr);
									} // else
								} // else
						} // else
						$GLOBALS['log']->debug('***** On message [ '.$current.' of '.$total.' ] *****');
						$current++;
					} // foreach
					// update Inbound Account with last robin
					if ($ieX->isMailBoxTypeCreateCase() && $distributionMethod == 'roundRobin') {
						$emailUI->setLastRobin($ieX, $lastRobin);
					} // if

				} // if
				if ($isGroupFolderExists)	 {
					$leaveMessagesOnMailServer = $ieX->get_stored_options("leaveMessagesOnMailServer", 0);
					if (!$leaveMessagesOnMailServer) {
						if ($ieX->isPop3Protocol()) {
							$ieX->deleteMessageOnMailServerForPop3(implode(",", $messagesToDelete));
						} else {
							$ieX->deleteMessageOnMailServer(implode($app_strings['LBL_EMAIL_DELIMITER'], $messagesToDelete));
						}
					}
				}
			} else {
				$GLOBALS['log']->fatal("SCHEDULERS: could not get an IMAP connection resource for ID [ {$a['id']} ]. Skipping mailbox [ {$a['name']} ].");
				// cn: bug 9171 - continue while
			} // else
		} // foreach
		imap_expunge($ieX->conn);
		imap_close($ieX->conn, CL_EXPUNGE);
	} // while
    $GLOBALS['current_user']->team_id = $_bck_up['team_id'];
    $GLOBALS['current_user']->team_set_id = $_bck_up['team_set_id'];
	return true;
}

/**
 * Job 2
 */
function runMassEmailCampaign() {
	if (!class_exists('LoggerManager')){

	}
	$GLOBALS['log'] = LoggerManager::getLogger('emailmandelivery');
	$GLOBALS['log']->debug('Called:runMassEmailCampaign');

	if (!class_exists('DBManagerFactory')){
		require('include/database/DBManagerFactory.php');
	}

	global $beanList;
	global $beanFiles;
	require("config.php");
	require('include/modules.php');
	if(!class_exists('AclController')) {
		require('modules/ACL/ACLController.php');
	}

	require('modules/EmailMan/EmailManDelivery.php');
	return true;
}

/**
 *  Job 3
 */
function pruneDatabase() {
	$GLOBALS['log']->info('----->Scheduler fired job of type pruneDatabase()');
	$backupDir	= sugar_cached('backups');
	$backupFile	= 'backup-pruneDatabase-GMT0_'.gmdate('Y_m_d-H_i_s', strtotime('now')).'.php';

	$db = DBManagerFactory::getInstance();
	$tables = $db->getTablesArray();
    $queryString = array();

	if(!empty($tables)) {
		foreach($tables as $kTable => $table) {
			// find tables with deleted=1
			$columns = $db->get_columns($table);
			// no deleted - won't delete
			if(empty($columns['deleted'])) continue;

			$custom_columns = array();
			if(array_search($table.'_cstm', $tables)) {
			    $custom_columns = $db->get_columns($table.'_cstm');
			    if(empty($custom_columns['id_c'])) {
			        $custom_columns = array();
			    }
			}

			$qDel = "SELECT * FROM $table WHERE deleted = 1";
			$rDel = $db->query($qDel);

			// make a backup INSERT query if we are deleting.
			while($aDel = $db->fetchByAssoc($rDel, false)) {
				// build column names

				$queryString[] = $db->insertParams($table, $columns, $aDel, null, false);

				if(!empty($custom_columns) && !empty($aDel['id'])) {
                    $qDelCstm = 'SELECT * FROM '.$table.'_cstm WHERE id_c = '.$db->quoted($aDel['id']);
                    $rDelCstm = $db->query($qDelCstm);

                    // make a backup INSERT query if we are deleting.
                    while($aDelCstm = $db->fetchByAssoc($rDelCstm)) {
                        $queryString[] = $db->insertParams($table, $custom_columns, $aDelCstm, null, false);
                    } // end aDel while()

                    $db->query('DELETE FROM '.$table.'_cstm WHERE id_c = '.$db->quoted($aDel['id']));
                }
			} // end aDel while()
			// now do the actual delete
			$db->query('DELETE FROM '.$table.' WHERE deleted = 1');
		} // foreach() tables

		if(!file_exists($backupDir) || !file_exists($backupDir.'/'.$backupFile)) {
			// create directory if not existent
			mkdir_recursive($backupDir, false);
		}
		// write cache file

		write_array_to_file('pruneDatabase', $queryString, $backupDir.'/'.$backupFile);
		return true;
	}
	return false;
}


///**
// * Job 4
// */

//function securityAudit() {
//	// do something
//	return true;
//}

function trimTracker()
{
    global $sugar_config, $timedate;
	$GLOBALS['log']->info('----->Scheduler fired job of type trimTracker()');
	$db = DBManagerFactory::getInstance();

	$admin = new Administration();
	$admin->retrieveSettings('tracker');
	require('modules/Trackers/config.php');
	$trackerConfig = $tracker_config;

    require_once('include/utils/db_utils.php');
    $prune_interval = !empty($admin->settings['tracker_prune_interval']) ? $admin->settings['tracker_prune_interval'] : 30;
	foreach($trackerConfig as $tableName=>$tableConfig) {

		//Skip if table does not exist
		if(!$db->tableExists($tableName)) {
		   continue;
		}

	    $timeStamp = db_convert("'". $timedate->asDb($timedate->getNow()->get("-".$prune_interval." days")) ."'" ,"datetime");
		if($tableName == 'tracker_sessions') {
		   $query = "DELETE FROM $tableName WHERE date_end < $timeStamp";
		} else {
		   $query = "DELETE FROM $tableName WHERE date_modified < $timeStamp";
		}

	    $GLOBALS['log']->info("----->Scheduler is about to trim the $tableName table by running the query $query");
		$db->query($query);
	} //foreach
    return true;
}

/* Job 5
 *
 */
function pollMonitoredInboxesForBouncedCampaignEmails() {
	$GLOBALS['log']->info('----->Scheduler job of type pollMonitoredInboxesForBouncedCampaignEmails()');
	global $dictionary;


	$ie = new InboundEmail();
	$r = $ie->db->query('SELECT id FROM inbound_email WHERE deleted=0 AND status=\'Active\' AND mailbox_type=\'bounce\'');

	while($a = $ie->db->fetchByAssoc($r)) {
		$ieX = new InboundEmail();
		$ieX->retrieve($a['id']);
		$ieX->connectMailserver();
        $ieX->importMessages();
	}

	return true;
}

/**
 * Job 6
 */
function processWorkflow() {
	include_once('process_workflow.php');
	return true;
}

/**
 * Job 7
 */
function processQueue() {
    include_once('process_queue.php');
    return true;
}


/**
 * Job 9
 */
function updateTrackerSessions() {
    global $sugar_config, $timedate;
	$GLOBALS['log']->info('----->Scheduler fired job of type updateTrackerSessions()');
	$db = DBManagerFactory::getInstance();
    require_once('include/utils/db_utils.php');
	//Update tracker_sessions to set active flag to false
	$sessionTimeout = db_convert("'".$timedate->getNow()->get("-6 hours")->asDb()."'" ,"datetime");
	$query = "UPDATE tracker_sessions set active = 0 where date_end < $sessionTimeout";
	$GLOBALS['log']->info("----->Scheduler is about to update tracker_sessions table by running the query $query");
	$db->query($query);
	return true;
}

/**
 * Job 12
 */
function sendEmailReminders(){
	$GLOBALS['log']->info('----->Scheduler fired job of type sendEmailReminders()');
	require_once("modules/Activities/EmailReminder.php");
	$reminder = new EmailReminder();
	return $reminder->process();
}
function performFullFTSIndex()
{
    require_once('include/SugarSearchEngine/SugarSearchEngineFullIndexer.php');
    $indexer = new SugarSearchEngineFullIndexer();
    $indexer->initiateFTSIndexer();
    $GLOBALS['log']->info("FTS Indexer initiated.");
    return true;
}

function removeDocumentsFromFS()
{
    $GLOBALS['log']->info('Starting removal of documents if they are not present in DB');

    /**
     * @var DBManager $db
     * @var SugarBean $bean
     */
    global $db;

    // temp table to store id of files without memory leak
    $tableName = 'cron_remove_documents';

    $resource = $db->limitQuery("SELECT * FROM cron_remove_documents WHERE 1=1 ORDER BY date_modified ASC", 0, 100);
    $return = true;
    while ($row = $db->fetchByAssoc($resource)) {
        $bean = BeanFactory::getBean($row['module']);
        $bean->retrieve($row['bean_id'], true, false);
        if (empty($bean->id)) {
            $isSuccess = true;
            $bean->id = $row['bean_id'];
            $directory = $bean->deleteFileDirectory();
            if (!empty($directory) && is_dir('upload://deleted/' . $directory)) {
                if ($isSuccess = rmdir_recursive('upload://deleted/' . $directory)) {
                    $directory = explode('/', $directory);
                    while (!empty($directory)) {
                        $path = 'upload://deleted/' . implode('/', $directory);
                        if (is_dir($path)) {
                            $directoryIterator = new DirectoryIterator($path);
                            $empty = true;
                            foreach ($directoryIterator as $item) {
                                if ($item->getFilename() == '.' || $item->getFilename() == '..') {
                                    continue;
                                }
                                $empty = false;
                                break;
                            }
                            if ($empty) {
                                rmdir($path);
                            }
                        }
                        array_pop($directory);
                    }
                }
            }
            if ($isSuccess) {
                $db->query('DELETE FROM ' . $tableName . ' WHERE id=' . $db->quoted($row['id']));
            } else {
                $return = false;
            }
        } else {
            $db->query('UPDATE ' . $tableName . ' SET date_modified=' . $db->convert($db->quoted(TimeDate::getInstance()->nowDb()), 'datetime') . ' WHERE id=' . $db->quoted($row['id']));
        }
    }

    return $return;
}

/**
 * Job 16
 * this will trim all records in sugarfeeds table that are older than 30 days or specified interval
 */

function trimSugarFeeds()
{
    global $sugar_config, $timedate;
    $GLOBALS['log']->info('----->Scheduler fired job of type trimSugarFeeds()');
    $db = DBManagerFactory::getInstance();

    //get the pruning interval from globals if it's specified
    $prune_interval = !empty($GLOBALS['sugar_config']['sugarfeed_prune_interval']) && is_numeric($GLOBALS['sugar_config']['sugarfeed_prune_interval']) ? $GLOBALS['sugar_config']['sugarfeed_prune_interval'] : 30;


    //create and run the query to delete the records
    $timeStamp = $db->convert("'". $timedate->asDb($timedate->getNow()->get("-".$prune_interval." days")) ."'" ,"datetime");
    $query = "DELETE FROM sugarfeed WHERE date_modified < $timeStamp";


    $GLOBALS['log']->info("----->Scheduler is about to trim the sugarfeed table by running the query $query");
    $db->query($query);

    return true;
}




function cleanJobQueue($job)
{
    $td = TimeDate::getInstance();
    // soft delete all jobs that are older than cutoff
    $soft_cutoff = 7;
    if(isset($GLOBALS['sugar_config']['jobs']['soft_lifetime'])) {
        $soft_cutoff = $GLOBALS['sugar_config']['jobs']['soft_lifetime'];
    }
    $soft_cutoff_date = $job->db->quoted($td->getNow()->modify("- $soft_cutoff days")->asDb());
    $job->db->query("UPDATE {$job->table_name} SET deleted=1 WHERE status='done' AND date_modified < ".$job->db->convert($soft_cutoff_date, 'datetime'));
    // hard delete all jobs that are older than hard cutoff
    $hard_cutoff = 21;
    if(isset($GLOBALS['sugar_config']['jobs']['hard_lifetime'])) {
        $hard_cutoff = $GLOBALS['sugar_config']['jobs']['hard_lifetime'];
    }
    $hard_cutoff_date = $job->db->quoted($td->getNow()->modify("- $hard_cutoff days")->asDb());
    $job->db->query("DELETE FROM {$job->table_name} WHERE status='done' AND date_modified < ".$job->db->convert($hard_cutoff_date, 'datetime'));
    return true;
}

if (SugarAutoLoader::existing('custom/modules/Schedulers/_AddJobsHere.php')) {
	require('custom/modules/Schedulers/_AddJobsHere.php');
}

$extfile = SugarAutoLoader::loadExtension('schedulers');
if($extfile) {
    require $extfile;
}
