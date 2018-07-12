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

require_once('include/SugarQueue/SugarJobQueue.php');

// User is used to store customer information.
class TimePeriod extends SugarBean {

    //Constants used by this class
    const ANNUAL_TYPE = 'Annual';
    const QUARTER_TYPE = 'Quarter';
    const MONTH_TYPE = 'Month';

	//time period stored fields.
	var $id;
	var $name;
	var $parent_id;
	var $start_date;
	var $end_date;
    var $start_date_timestamp;
   	var $end_date_timestamp;
	var $created_by;
	var $date_entered;
	var $date_modified;
	var $deleted;
	var $fiscal_year;
	var $is_fiscal_year = 0;
    var $is_fiscal;
	//end time period stored fields.
	var $table_name = "timeperiods";
	var $fiscal_year_checked;
	var $module_dir = 'TimePeriods';
    var $type;
    var $leaf_period_type;
    var $leaf_periods;
    var $leaf_cycle;
    var $periods_in_year;
    var $leaf_name_template;
    var $name_template;
	var $object_name = "TimePeriod";
	var $user_preferences;
    var $date_modifier;
	var $encodeFields = Array("name");
    var $priorSettings;
    var $currentSettings;

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('reports_to_name');

	var $new_schema = true;

    /**
     * This is a depreciated method, please start using __construct() as this method will be removed in a future version
     *
     * @see __construct
     * @deprecated
     */
    public function TimePeriod()
    {
        self::__construct();
    }

	public function __construct()
    {
		parent::__construct();
		$this->disable_row_level_security =true;
	}

	public function save($check_notify = false)
    {
        $timedate = TimeDate::getInstance();

        //TODO: change to check globals flag instead for cleaner if statement
        //override the unix time stamp setting here for setting start date timestamp by going with 00:00:00 for the time
        $date_start_datetime = $this->start_date;
        if ($timedate->check_matching_format($this->start_date, TimeDate::DB_DATE_FORMAT)) {
            $date_start_datetime = $timedate->fromDbDate($this->start_date);
        } else if ($timedate->check_matching_format($this->start_date, $timedate->get_user_date_format())) {
            $date_start_datetime = $timedate->fromUserDate($this->start_date, true);
        }

        $this->start_date_timestamp = $date_start_datetime->setTime(0,0,0)->getTimestamp();

        //override the unix time stamp setting here for setting end date timestamp by going with 23:59:59 for the time to get the max time of the day
        $date_close_datetime = $this->end_date;
        if ($timedate->check_matching_format($this->end_date, TimeDate::DB_DATE_FORMAT)) {
            $date_close_datetime = $timedate->fromDbDate($this->end_date);
        } else if ($timedate->check_matching_format($this->end_date, $timedate->get_user_date_format())) {
            $date_close_datetime = $timedate->fromUserDate($this->end_date, true);
        }

        $this->end_date_timestamp = $date_close_datetime->setTime(23,59,59)->getTimestamp();

		return parent::save($check_notify);
	}


    /**
     * Returns the summary text that should show up in the recent history list for this object.
     *
     * @return string
     */
    public function get_summary_text()
	{
		return $this->name;
	}

    /**
     * custom override of retrieve function to disable the date formatting and reset it again after the bean has been retrieved.
     *
     * @param string $id
     * @param bool $encode
     * @param bool $deleted
     * @return null|SugarBean
     */
    public function retrieve($id, $encode=false, $deleted=true)
    {
        global $disable_date_format;
        $previous_disable_date_format = $disable_date_format;
        $disable_date_format = 1;
   		$ret = parent::retrieve($id, $encode, $deleted);
        $disable_date_format = $previous_disable_date_format;
   		return $ret;
   	}

    public function is_authenticated()
	{
		return $this->authenticated;
	}

    public function fill_in_additional_list_fields()
    {
		$this->fill_in_additional_detail_fields();
	}

    public function fill_in_additional_detail_fields()
    {
		if (isset($this->parent_id) && !empty($this->parent_id)) {

		  $query ="SELECT name from timeperiods where id = '$this->parent_id' and deleted = 0";
		  $result =$this->db->query($query, true, "Error filling in additional detail fields") ;
		  $row = $this->db->fetchByAssoc($result);
		  $GLOBALS['log']->debug("additional detail query results: ".print_r($row, true));


		  if($row != null) {
			 $this->fiscal_year = $row['name'];
		  }
		}
	}

    public function get_list_view_data()
    {

		$timeperiod_fields = $this->get_list_view_array();
		$timeperiod_fields['FISCAL_YEAR'] = $this->fiscal_year;

		if ($this->is_fiscal_year == 1) {
			$timeperiod_fields['FISCAL_YEAR_CHECKED'] = "checked";
        }
		return $timeperiod_fields;
	}

    public function list_view_parse_additional_sections(&$list_form, $xTemplateSection)
    {
		return $list_form;
	}

    public function create_export_query($order_by, $where)
    {
		$query = "SELECT timeperiods.* FROM timeperiods ";

		$where_auto = " timeperiods.deleted = 0";

		if($where != "")
			$query .= " WHERE $where AND " . $where_auto;
		else
			$query .= " WHERE " . $where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY timeperiods.name";

		return $query;
	}

    /**
     * sets the start date, based on a db formatted date string passed in.  If null is passed in, now is used.
     * The end date is adjusted as well to hold to the contract of this being a time period
     *
     * @param null $startDate db format date string to set the start date of the time period
     */
    public function setStartDate($start_date = null)
    {
        $timedate = TimeDate::getInstance();

        //check start_date, put it to now if it's not passed in
        if(is_null($start_date)) {
            $start_date = $timedate->asDbDate($timedate->getNow());
        }

        //set the start/end date
        $this->start_date = $start_date;

        $endDate = $this->determineEndDate($start_date);
        $this->end_date = $endDate->asDbDate(false);
    }

    /**
     * Determines the end date to hold to the contract of this being a TimePeriod
     *
     * @param $startDate String of the TimePeriod start_date in db format
     */
    public function determineEndDate($start_date) {
        $timedate = TimeDate::getInstance();
        $startDate = $timedate->fromDbDate($start_date);
        $startDateDay = $startDate->format('j');

        //Flag if the start date's day is the last day of the month
        if(!empty($this->parent_id)) {
            $parentTimePeriod = TimePeriod::getBean($this->parent_id);
            $parentStartDate = $timedate->fromDbDate($parentTimePeriod->start_date);
            $isStartDateDayLastDayOfMonth = $parentStartDate->format('j') == $parentStartDate->format('t');
        } else {
            $isStartDateDayLastDayOfMonth = $startDateDay == $startDate->format('t');
        }

        $endDate = $startDate->modify($this->next_date_modifier);
        $endDateDay = $endDate->format('j');

        //Handle special cases where start date day is towards the last days of the month
        if($startDateDay > 28 && $endDateDay < 4) {
            $endDate->modify("-{$endDateDay} day");
        } else if($isStartDateDayLastDayOfMonth) {
            $endDate->setDate($endDate->format('Y'), $endDate->format('n'), $endDate->format('t'));
        }

        $endDate->modify('-1 day');
        return $endDate;
    }

    public static function get_fiscal_year_dom()
    {
		static $fiscal_years;

		if (!isset($fiscal_years)) {

			$query = 'select id, name from timeperiods where deleted=0 and is_fiscal_year = 1 order by name';
			$db = DBManagerFactory::getInstance();
			$result = $db->query($query,true," Error filling in fiscal year domain: ");

			while (($row  =  $db->fetchByAssoc($result)) != null) {
				$fiscal_years[$row['id']]=$row['name'];
			}

			if (!isset($fiscal_years)) {
				$fiscal_years=array();
			}
		}
		return $fiscal_years;
	}


    /**
     * loads leaf TimePeriods and returns instances as an array
     *
     * @return mixed Array of leaf TimePeriod instances
     */
    public function getLeaves()
    {
        $leaves = array();
        $db = DBManagerFactory::getInstance();
        $query = "SELECT id, type FROM timeperiods WHERE parent_id = '{$this->id}' AND deleted = 0 ORDER BY start_date_timestamp ASC";
        $result = $db->query($query);
        while($row = $db->fetchByAssoc($result)) {
            $leaves[] = TimePeriod::getByType($row['type'], $row['id']);
        }
        return $leaves;
    }

    /**
     * Returns true if TimePeriod instance has leaves, false otherwise
     *
     * @return bool true if TimePeriod instance has leaves, false otherwise
     */
    public function hasLeaves()
    {
        return count($this->getLeaves()) > 0;
    }

    /**
     * removes related TimePeriods
     */
    public function removeLeaves()
    {
        $this->load_relationship('related_timeperiods');
        $this->related_timeperiods->delete($this->id);
    }


    /**
     * Return a TimePeriod object for a given database date
     *
     * @param $db_date String value of database date (ex: 2012-12-30)
     * @return bool|TimePeriod TimePeriod instance for corresponding database date; false if nothing found
     */
    public static function retrieveFromDate($db_date)
    {
        global $app_strings;
        $db = DBManagerFactory::getInstance();
        $db_date = $db->quote($db_date);
        $timeperiod_id = $db->getOne("SELECT id FROM timeperiods WHERE start_date <= '{$db_date}' AND end_date >= '{$db_date}' and is_fiscal_year = 0", false, string_format($app_strings['ERR_TIMEPERIOD_UNDEFINED_FOR_DATE'], array($db_date)));

        if(!empty($timeperiod_id)) {            
            $tp = BeanFactory::getBean('TimePeriods');
            $tp->retrieve($timeperiod_id);
            
            return $tp;
        }

        return false;
    }


    /**
     * Return the current TimePeriod instance for the given TimePeriod type
     *
     * @param $type The TimePeriod string type constant (TimePeriod::Annual, TimePeriod::Quarter, TimePeriod::Month); defaults to using leaf interval from settings if none supplied
     */
    public static function getCurrentTimePeriod($type='')
    {
        $id = TimePeriod::getCurrentId($type);
        return !empty($id) ? TimePeriod::getByType($type, $id) : null;
    }


    /**
     * Returns the current TimePeriod name if a TimePeriod entry is found
     *
     * @param $type String CONSTANT for the TimePeriod type; if none supplied it will use the leaf type as defined in config settings
     * @return String name of the current TimePeriod for given type; null if none found
     */
    public static function getCurrentName($type='')
    {
        if(empty($type))
        {
            $admin = BeanFactory::getBean('Administration');
            $config = $admin->getConfigForModule('Forecasts', 'base');
            $type = $config['timeperiod_leaf_interval'];
        }

        $id = TimePeriod::getCurrentId($type);
        $tp = TimePeriod::getByType($type, $id);

        return (!empty($tp)) ? $tp->name : null;
    }

    /**
     * getCurrentId
     *
     * Returns the current TimePeriod instance's id if a leaf entry is found for the current date
     *
     * @param $type String CONSTANT for the TimePeriod type; if none supplied it will use the leaf type as defined in config settings
     * @return $currentId String id of the current TimePeriod's id; null if none found
     */
    public static function getCurrentId($type='')
    {
        if(empty($type)) {
            $admin = BeanFactory::getBean('Administration');
            $config = $admin->getConfigForModule('Forecasts', 'base');
            $type = $config['timeperiod_leaf_interval'];
        }

        $timedate = TimeDate::getInstance();
        $db = DBManagerFactory::getInstance();
        $queryDate = $timedate->getNow(true);
        $date = $db->convert($db->quoted($queryDate->asDbDate(false)), 'date');
        $query = "SELECT id FROM timeperiods WHERE start_date <= {$date} AND end_date >= {$date} AND type = '{$type}' AND deleted = 0 ORDER BY start_date_timestamp DESC";

        $result = $db->limitQuery($query, 0 , 1);
        if(!empty($result)) {
            $row = $db->fetchByAssoc($result);
            return $row['id'];
        }

        return null;
    }


    /**
     * get_timeperiods_dom
     *
     * @static
     * @return array
     */
    public static function get_timeperiods_dom()
    {
        static $timeperiods;

        if(empty($timeperiods)) {
            $db = DBManagerFactory::getInstance();
            $timeperiods = array();
            $result = $db->query('SELECT id, name FROM timeperiods WHERE deleted=0');
            while(($row = $db->fetchByAssoc($result))) {
                if(!isset($timeperiods[$row['id']])) {
                    $timeperiods[$row['id']]=$row['name'];
                }
            }
        }
        return $timeperiods;
    }

    public static function get_not_fiscal_timeperiods_dom()
    {
        static $not_fiscal_timeperiods;

        if(!isset($not_fiscal_timeperiods)) {
            $db = DBManagerFactory::getInstance();
            $not_fiscal_timeperiods = array();
            $result = $db->query('SELECT id, name FROM timeperiods WHERE is_fiscal_year = 0 AND parent_id IS NOT NULL AND deleted = 0 ORDER BY start_date ASC');
            while(($row = $db->fetchByAssoc($result))) {
                if(!isset($not_fiscal_timeperiods[$row['id']])) {
                    $not_fiscal_timeperiods[$row['id']]=$row['name'];
                }
            }
        }
        return $not_fiscal_timeperiods;
    }

    /**
     * Takes the current TimePeriod and finds the next one that is in the db of the same type.  If none exists it returns null
     *
     * @return mixed
     */
    public function getNextTimePeriod()
    {
        $timedate = TimeDate::getInstance();
        $queryDate = $timedate->fromDbDate($this->end_date);
        $queryDate = $queryDate->modify('+1 day');
        $query = sprintf("SELECT id FROM timeperiods WHERE type = %s AND start_date = %s AND deleted = 0 ",
            $this->db->quoted($this->type),
            $this->db->convert($this->db->quoted($queryDate->asDbDate()), 'date'));

        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result);

        return ($row != null) ? TimePeriod::getByType($this->type, $row['id']) : null;
    }


    /**
     * Grabs the time period previous of this one and returns it.  If none is found, it returns null
     *
     * @return null|TimePeriod instance
     */
    public function getPreviousTimePeriod()
    {
        $timedate = TimeDate::getInstance();
        $queryDate = $timedate->fromDbDate($this->start_date);
        $queryDate = $queryDate->modify('-1 day');
        $query = sprintf("SELECT id FROM timeperiods WHERE type = %s AND end_date = %s AND deleted = 0 ",
            $this->db->quoted($this->type),
            $this->db->convert($this->db->quoted($queryDate->asDbDate()), 'date'));

        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result);

        return ($row != null) ? TimePeriod::getByType($this->type, $row['id']) : null;
    }

    /**
     * Examines the config values and rebuilds the time periods based on the settings
     * from the config table.  The settings are retrieved from the Administration bean.
     *
     * @param $priorSettings Array of the previous forecast settings
     * @param $currentSettings Array of the current forecast settings
     *
     * @return void
     */
    public function rebuildForecastingTimePeriods($priorSettings, $currentSettings)
    {
       $timedate = TimeDate::getInstance();
       $currentDate = $timedate->getNow();

       $db = DBManagerFactory::getInstance();
       $count = $db->getOne('SELECT count(id) FROM timeperiods WHERE parent_id IS NULL AND deleted = 0');

       $isUpgrade = !empty($currentSettings['is_upgrade']);

       //If this an upgrade AND there are existing non-fiscal leaf TimePeriods
       if ($isUpgrade && !empty($count)) {
           $this->upgradeLegacyTimePeriods();
       }
       $this->createTimePeriods($priorSettings, $currentSettings, $currentDate);
    }

    /**
     * Just load up any TimePeriods that are missing their DateStamps and save them, so they will be generated,
     * and usable in the ForecastModule
     *
     * @return int      The number of TimePeriods Updated
     */
    public function upgradeLegacyTimePeriods()
    {
        $sql = "SELECT id FROM timeperiods
                WHERE deleted = 0
                    AND (start_date_timestamp is null OR end_date_timestamp is null);";
        $result = $this->db->query($sql);

        $updated = 0;
        while($row = $this->db->fetchByAssoc($result)) {
            $tp = BeanFactory::getBean('TimePeriods');
            $tp->retrieve($row['id']);
            $tp->save();

            $updated++;
        }
        return $updated;
    }


    /**
     * createTimePeriods
     *
     * This function creates the TimePeriods for new installations or upgrades where no previous TimePeriods exist
     *
     * @param $priorSettings Array of the previous forecast settings
     * @param $currentSettings Array of the current forecast settings
     * @param $currentDate TimeDate instance of the current date
     */
    public function createTimePeriods($priorSettings, $currentSettings, $currentDate)
    {
        $timedate = TimeDate::getInstance();
        $settingsDate = $timedate->fromDbDate($currentSettings["timeperiod_start_date"]);
        //set the target date based on the current year and the selected start month and day
        $targetStartDate = $timedate->getNow()->setDate($currentDate->format("Y"), $settingsDate->format("m"), $settingsDate->format("d"));

        //if the target start date is in the future then keep going back one TimePeriod interval
        while($currentDate < $targetStartDate) {
            $targetStartDate->modify($this->previous_date_modifier);
        }

        //Set the time period parent and leaf types according to the configuration settings
        $this->type = $currentSettings['timeperiod_interval']; // TimePeriod::Annual by default
        $this->leaf_period_type = $currentSettings['timeperiod_leaf_interval']; // TimePeriod::Quarter by default

        //Now check if we need to add more TimePeriods
        //If we are coming from an upgrade, we do not create any backward TimePeriods
        $shownBackwardDifference = $this->getShownDifference($priorSettings, $currentSettings, 'timeperiod_shown_backward');
        $shownForwardDifference = $this->getShownDifference($priorSettings, $currentSettings, 'timeperiod_shown_forward');

        //If there were no existing TimePeriods we go back one year and create an extra set (for the current TimePeriod set)
        $latestTimeperiod = TimePeriod::getLatest($this->type);

        if(empty($latestTimeperiod)) {
            $targetEndDate = $this->determineEndDate($targetStartDate->asDbDate());
            //now we keep incrementing the targetStartDate until we reach the currentDate
            while($targetStartDate < $currentDate && $targetEndDate < $currentDate) {
                $targetStartDate->modify($this->next_date_modifier);
                $targetEndDate = $this->determineEndDate($targetStartDate->asDbDate());
            }

            $this->setStartDate($targetStartDate->asDbDate());
            $shownForwardDifference++;
        }

        return $this->buildLeaves($shownBackwardDifference, $shownForwardDifference);
    }

    /**
     * createTimePeriodsForUpgrade
     *
     * This function creates the TimePeriods for the case where the system is handling upgrades
     *
     * In the case of upgrades we take the following steps:
     * 1) We find out what the current TimePeriod is (if one exists)
     * 2) We then use the supplied start date of the fiscal period and build leaf TimePeriods backwards until we reach
     * the current TimePeriod
     * 3) The current TimePeriod's end date is adjusted to extend up to the most recent backward leaf TimePeriod(s), if
     * available, or extend out to the day before the start date of the fiscal period
     * 4) We then build out any new forward TimePeriod(s) based on the configuration settings
     *
     * @param $currentSettings Array of the current forecast settings
     * @param $currentDate TimeDate instance of the current date when upgrade was run
     * @return Array of TimePeriod instances created for the upgrade
     */
    public function createTimePeriodsForUpgrade($currentSettings, $currentDate)
    {
        $created = array();
        $isLeafTimePeriod = true;
        $timeperiodInterval = $currentSettings['timeperiod_interval'];
        $timePeriodLeafInterval = $currentSettings['timeperiod_leaf_interval'];

        //Now try to find the current leaf TimePeriod.  We have no way of knowing what the leaf type is so we cannot use TimePeriod::getCurrentId since
        //that assumes a type argument is supplied or it will use the defaults from the config.  So instead we just search based on the closest TimePeriod
        $timedate = TimeDate::getInstance();
        $db = DBManagerFactory::getInstance();

        $query = sprintf("SELECT id FROM timeperiods WHERE start_date <= %s AND (parent_id IS NOT NULL OR parent_id <> '') AND deleted = 0 ORDER BY start_date DESC",
            $db->convert($db->quoted($currentDate->asDbDate()), 'date'));

        $result = $db->limitQuery($query, 0 , 1);

        $currentTimePeriod = null;

        if(!empty($result)) {
           $row = $db->fetchByAssoc($result);
           if(!empty($row)) {
               $currentTimePeriod = new TimePeriod();
               $currentTimePeriod->retrieve($row['id']);
           }
        }


        if(empty($currentTimePeriod)) {

            //If no currentTimePeriod instance was found, just use the most recent upcoming TimePeriod instance
            $query = sprintf("SELECT id FROM timeperiods WHERE start_date > %s AND parent_id IS NOT NULL AND deleted = 0 ORDER BY start_date ASC",
                $db->convert($db->quoted($currentDate->asDbDate()), 'date'));

            $result = $db->limitQuery($query, 0, 1);
            $row = $db->fetchByAssoc($result);
            if(!empty($row)) {
               $currentTimePeriod = new TimePeriod();
               $currentTimePeriod->retrieve($row['id']);
            }

            if(empty($currentTimePeriod)) {
                //One last attempt using timeperiods without parent_id
                $query = sprintf("SELECT id FROM timeperiods WHERE start_date <= %s AND end_date >= %s AND parent_id IS NULL AND deleted = 0 ORDER BY start_date ASC",
                   $db->convert($db->quoted($currentDate->asDbDate()), 'date'),
                   $db->convert($db->quoted($currentDate->asDbDate()), 'date'));

                $result = $db->limitQuery($query, 0, 1);


                $row = $db->fetchByAssoc($result);
                if(!empty($row)) {
                    $currentTimePeriod = new TimePeriod();
                    $currentTimePeriod->retrieve($row['id']);
                    $isLeafTimePeriod = false;
                }

                //If a current TimePeriod still cannot be determined, just create a leaf with the correct current start date for the current date
                if(empty($currentTimePeriod)) {
                    $currentTimePeriod = TimePeriod::getByType($timePeriodLeafInterval);
                    $currentTimePeriod->setStartDate($this->determineCurrentStartDateByType($timePeriodLeafInterval, $timedate->getNow()->asDbDate()));
                    $currentTimePeriod->save();
                    $created[] = $currentTimePeriod;
                }
            }
        }

        //Now mark all TimePeriods that start after the current TimePeriod to be deleted
        $db->query(sprintf("UPDATE timeperiods SET deleted = 1 WHERE start_date >= %s", $db->convert($db->quoted($currentTimePeriod->start_date), 'date')));

        //Delete the current TimePeriod itself since we will re-create it with the appropriate type attributes and adjusted end date
        $db->query(sprintf("DELETE FROM timeperiods WHERE id = %s", $db->quoted($currentTimePeriod->id)));

        $currentEndDate = $timedate->fromDbDate($currentTimePeriod->end_date);
        $selectedStartDate = $timedate->fromDbDate($currentSettings["timeperiod_start_date"]);
        $targetStartDate = $timedate->getNow()->setDate($currentEndDate->format('Y'), $selectedStartDate->format('m'), $selectedStartDate->format('d'));

        //Now create the new TimePeriods with the forward date modifier
        $timePeriod = TimePeriod::getByType($timeperiodInterval);

        //If the target starting date is before the current year's starting date, keep incrementing the year until we are past the current TimePeriod's end date
        while($targetStartDate < $currentEndDate) {
          $targetStartDate->modify($timePeriod->next_date_modifier);
        }

        //Create a parent TimePeriod instance
        $currentParentTimePeriodInstance = TimePeriod::getByType($timeperiodInterval);
        $currentParentTimePeriodInstance->setStartDate($timedate->fromDbDate($targetStartDate->asDbDate())->modify($currentParentTimePeriodInstance->previous_date_modifier)->asDbDate());
        $currentParentTimePeriodInstance->name = $currentParentTimePeriodInstance->getTimePeriodName(1);
        $currentParentTimePeriodInstance->save();
        $created[] = $currentParentTimePeriodInstance;

        //Now get the leaf type we will be building
        $leaf = TimePeriod::getByType($timePeriodLeafInterval);
        $leafCycle = $timePeriod->leaf_periods;

        //If we are to create any TimePeriods leading back to the current TimePeriod's end date, we'd first start with
        //a TimePeriod one back of the target start date
        $previousLeafTimePeriodStartDate = $timedate->fromDbDate($targetStartDate->asDbDate())->modify($leaf->previous_date_modifier);

        //While the current date is before any potential previous leaf TimePeriod start date, create leaf TimePeriod
        while(($isLeafTimePeriod && $currentEndDate < $previousLeafTimePeriodStartDate) || ($currentDate < $previousLeafTimePeriodStartDate && $isLeafTimePeriod == false)) {
           $previousLeafTimePeriod = TimePeriod::getByType($leaf->type);
           $previousLeafTimePeriod->setStartDate($previousLeafTimePeriodStartDate->asDbDate());
           $previousLeafTimePeriod->leaf_cycle = $leafCycle;
           $previousLeafTimePeriod->name = $previousLeafTimePeriod->getTimePeriodName($leafCycle);
           $previousLeafTimePeriod->parent_id = $currentParentTimePeriodInstance->id;
           $previousLeafTimePeriod->save();
           $created[] = $previousLeafTimePeriod;
           $leafCycle--;
           $previousLeafTimePeriodStartDate = $previousLeafTimePeriodStartDate->modify($leaf->previous_date_modifier);
        }

        //The current TimePeriod end date is now the last unadjusted previousLeafTimePeriodStartDate value
        $newCurrentTimePeriod = TimePeriod::getByType($leaf->type);
        $newCurrentTimePeriod->new_with_id = true;
        $newCurrentTimePeriod->id = $currentTimePeriod->id;
        $newCurrentTimePeriod->parent_id = $currentParentTimePeriodInstance->id;
        if($isLeafTimePeriod) {
            $newCurrentTimePeriod->setStartDate($currentTimePeriod->start_date);
        } else {
            $newCurrentTimePeriod->setStartDate($previousLeafTimePeriodStartDate->asDbDate());
        }
        $newCurrentTimePeriod->end_date = $previousLeafTimePeriodStartDate->modify($leaf->next_date_modifier)->modify('-1 day')->asDbDate();
        $newCurrentTimePeriod->leaf_cycle = $leafCycle;
        $newCurrentTimePeriod->name = $newCurrentTimePeriod->getTimePeriodName($leafCycle);
        $newCurrentTimePeriod->save();

        $created[] = $newCurrentTimePeriod;
        //We set it back once here since the buildTimePeriods code triggers the modification immediately
        //$targetStartDate->modify($timePeriod->previous_date_modifier);
        $timePeriod->setStartDate($targetStartDate->asDbDate());
        return array_merge($created, $timePeriod->buildTimePeriods($currentSettings['timeperiod_shown_forward'], 'forward'));
    }

    /**
     * buildLeaves
     *
     * Builds the leaves based on the TimePeriods earliest and latest start dates and the
     * specified backward and forward values for the number of TimePeriods to build
     *
     * @param $shownBackwardDifference int value of the shown backward difference
     * @param $shownForwardDifference int value of the shown forward
     *
     * @return Array of TimePeriod instances created
     */
    public function buildLeaves($shownBackwardDifference, $shownForwardDifference)
    {
          $created = array();
          $timedate = TimeDate::getInstance();

          if($shownBackwardDifference > 0) {
              $earliestTimePeriod = $this->getEarliest($this->type);
              if(is_null($earliestTimePeriod)) {
                  $earliestTimePeriod = TimePeriod::getByType($this->type);
                  $earliestTimePeriod->setStartDate($this->start_date);
              }
              $earliestTimePeriod->start_date = $timedate->fromDbDate($earliestTimePeriod->start_date)->modify($earliestTimePeriod->previous_date_modifier)->asDbDate(false);
              $created = $earliestTimePeriod->buildTimePeriods($shownBackwardDifference, 'backward');
          }

          if($shownForwardDifference > 0) {
              $latestTimePeriod = $this->getLatest($this->type);
              if(is_null($latestTimePeriod)) {
                  $latestTimePeriod = TimePeriod::getByType($this->type);
                  $latestTimePeriod->setStartDate($this->start_date);
              }
              $latestTimePeriod->start_date = $timedate->fromDbDate($latestTimePeriod->start_date)->modify($latestTimePeriod->next_date_modifier)->asDbDate(false);
              $created = array_merge($created, $latestTimePeriod->buildTimePeriods($shownForwardDifference, 'forward'));
          }

          return $created;
    }

    /**
     * buildTimePeriods
     *
     * @param $timePeriods int value of the number of parent level TimePeriods to create
     * @param $direction String value of the direction we are building leaves ('forward' or 'backward')
     * @return Array of TimePeriod instances created
     */
    public function buildTimePeriods($timePeriods, $direction)
    {
        $created = array();
        $timedate = TimeDate::getInstance();
        $startDate = $timedate->fromDbDate($this->start_date); //->modify($dateModifier);

        for($i=0; $i < $timePeriods; $i++) {
            //Create the parent TimePeriod instance
            $timePeriod = TimePeriod::getByType($this->type);
            $timePeriod->setStartDate($startDate->asDbDate(false));
            $startDateDay = $timedate->fromDbDate($timePeriod->start_date)->format('j');

            $remainder = $i % $this->periods_in_year;

            if($direction == 'forward') {
                $timePeriod->name = $timePeriod->getTimePeriodName($remainder == 0 ? 1 : $remainder + 1);
            } else {
                $timePeriod->name = $timePeriod->getTimePeriodName($this->periods_in_year - $remainder);
            }
            $timePeriod->save();
            $created[] = $timePeriod;

            $leafStartDate = $timePeriod->start_date;
            $leavesCreated = array();

            for($x=1; $x <= $this->leaf_periods; $x++) {
                $leafPeriod = TimePeriod::getByType($this->leaf_period_type);
                $leafPeriod->parent_id = $timePeriod->id;
                $leafPeriod->setStartDate($leafStartDate);
                $leafPeriod->name = $leafPeriod->getTimePeriodName($x);
                $leafPeriod->leaf_cycle = $x;
                $leafPeriod->save();
                $leavesCreated[] = $leafPeriod;
                $created[] = $leafPeriod;
                $leafStartDate = $timedate->fromDbDate($leafPeriod->end_date)->modify('+1 day')->asDbDate(false);
            }

            if($direction == 'forward') {
                //Use the last leaf period's end date and add one day from there
                $startDate = $timedate->fromDbDate($leafPeriod->end_date)->modify('+1 day');
            } else {
                $startDate = $timedate->fromDbDate($leavesCreated[0]->start_date)->modify($timePeriod->previous_date_modifier);
                $newStartDateDay = $startDate->format('j');
                if($startDateDay > 28 && $newStartDateDay < 4) {
                    $startDate->modify("-{$newStartDateDay} day");
                }
            }
        }

        return $created;
    }

    /**
     * Checks if the targetStartDate is different based on prior settings
     *
     * @param $targetStartDate SugarDateTime instance of start date based on current settings
     *
     * @return bool true if different false otherwise
     */
    public function isTargetDateDifferentFromPrevious($targetStartDate, $priorSettings)
    {
        //First check if prior settings are empty
        if(empty($priorSettings) || !isset($priorSettings['timeperiod_start_date'])) {
            return true;
        }

        $timedate = TimeDate::getInstance();
        $priorDate = $timedate->getNow();
        $settingsDate = $timedate->fromDbDate($priorSettings['timeperiod_start_date']);

        $priorDate->setDate(intval($targetStartDate->format("Y")), $settingsDate->format("m"), $settingsDate->format("d"));

        return $targetStartDate != $priorDate;
    }


    /**
     * Checks if the interval settings are different based on prior settings
     *
     * @param $priorSettings Array of the previous TimePeriod admin properties
     * @param $currentSettings Array of the current TimePeriod admin settings
     *
     * @return bool true if different false otherwise
     */
    public function isTargetIntervalDifferent($priorSettings, $currentSettings)
    {
        //First check if prior settings are empty
        if(empty($priorSettings) || !isset($priorSettings['timeperiod_interval']) || !isset($priorSettings['timeperiod_leaf_interval'])) {
            return true;
        }

        return $priorSettings['timeperiod_interval'] != $currentSettings['timeperiod_interval'] || $priorSettings['timeperiod_leaf_interval'] != $currentSettings['timeperiod_leaf_interval'];
    }

    /**
     * reflags all current TimePeriods as deleted based on the previous and current settings
     *
     * @param $priorSettings Array of the previous TimePeriod admin properties
     * @param $currentSettings Array of the current TimePeriod admin settings
     * @return void
     */
    public function deleteTimePeriods($priorSettings, $currentSettings)
    {
        $db = DBManagerFactory::getInstance();
        $db->query("UPDATE timeperiods SET deleted = 1");
    }

    /**
     * getShownDifference
     *
     * This function returns the numeric difference of the shown backward or forward differences
     *
     * @param $priorSettings Array of previous forecast settings
     * @param $currentSettings Array of current forecast settings
     * @param $key String value of the key (timeperiod_shown_forward or timeperiod_shown_backward)
     */
    public function getShownDifference($priorSettings, $currentSettings, $key)
    {
        //If no prior settings exists, the difference is the new setting
        if(!isset($priorSettings[$key])) {
           return $currentSettings[$key];
        }
        return $currentSettings[$key] - $priorSettings[$key];
    }

    /**
     * This function compares two Arrays of settings and returns boolean indicating whether they are identical or not
     *
     * @param $priorSettings
     * @param $currentSettings
     *
     * @return bool True if settings are the same, false otherwise
     */
    public function isSettingIdentical($priorSettings, $currentSettings)
    {
        if(!isset($priorSettings['timeperiod_interval']) || ($currentSettings['timeperiod_interval'] != $priorSettings['timeperiod_interval'])) {
            return false;
        }
        if(!isset($priorSettings['timeperiod_type']) || ($currentSettings['timeperiod_type'] != $priorSettings['timeperiod_type'])) {
            return false;
        }
        if(!isset($priorSettings['timeperiod_start_date']) || ($currentSettings['timeperiod_start_date'] != $priorSettings['timeperiod_start_date'])) {
            return false;
        }
        if(!isset($priorSettings['timeperiod_leaf_interval']) || ($currentSettings['timeperiod_leaf_interval'] != $priorSettings['timeperiod_leaf_interval'])) {
            return false;
        }
        if(!isset($priorSettings['timeperiod_shown_backward']) || ($currentSettings['timeperiod_shown_backward'] != $priorSettings['timeperiod_shown_backward'])) {
            return false;
        }
        if(!isset($priorSettings['timeperiod_shown_forward']) || ($currentSettings['timeperiod_shown_forward'] != $priorSettings['timeperiod_shown_forward'])) {
            return false;
        }

        return true;
    }


    /**
     * subtracts the end from the start date to return the date length in days
     *
     * @return mixed
     */
    public function getLengthInDays()
    {
        return ceil(($this->end_date_timestamp - $this->start_date_timestamp) / 86400);
    }

    /**
     * getTimePeriodName
     *
     * Returns the TimePeriod name.  The TimePeriod base implementation simply returns the $count argument passed
     * in from the code
     *
     * @param $count The TimePeriod series count
     * @return string The formatted name of the TimePeriod
     */
    public function getTimePeriodName($count)
    {
        return $count;
    }


    /**
     * Returns the formatted chart label data for the TimePeriod
     *
     * @param $chartData Array of chart data values
     * @return formatted Array of chart data values where the labels are broken down by the TimePeriod's increments
     */
    public function getChartLabels($chartData)
    {
        return $chartData;
    }


    /**
     * Returns the key for the chart label data for the date closed value
     *
     * @param String The date_closed value in db date format
     * @return String value of the key to use to map to the chart labels
     */
    public function getChartLabelsKey($dateClosed)
    {
        return $dateClosed;
    }


    /**
     * Returns the TimePeriod bean instance for the given time period id
     *
     * @param $id String id of the bean
     * @return $bean TimePeriod bean instance
     */
    public static function getBean($id)
    {
        $db = DBManagerFactory::getInstance();
        $result = $db->query(sprintf("SELECT id, type FROM timeperiods WHERE id = '%s' AND deleted = 0", $id));
        if($result) {
            $row = $db->fetchByAssoc($result);
            if($row) {
                return BeanFactory::getBean($row['type'] . 'TimePeriods', $id);
            }
        }

        return null;
    }


    /**
     * Returns the earliest TimePeriod bean instance for the given TimePeriod interval type
     *
     * @param $type String value of the TimePeriod interval type
     * @return $bean The earliest TimePeriod bean instance; null if none found
     */
    public static function getEarliest($type)
    {
        $db = DBManagerFactory::getInstance();
        $result = $db->limitQuery(sprintf("SELECT * FROM timeperiods WHERE type = '%s' AND deleted = 0 ORDER BY start_date_timestamp ASC", $type), 0, 1);
        if($result) {
            $row = $db->fetchByAssoc($result);
            if(!empty($row)) {
               $bean = BeanFactory::getBean("{$type}TimePeriods");
               $bean->retrieve($row['id']);
               return $bean;
            }
        }
        return null;
    }

    /**
     * Returns the latest TimePeriod bean instance for the given timeperiod interval type
     *
     * @param $type String value of the TimePeriod interval type
     * @return $bean The latest TimePeriod bean instance; null if none found
     */
    public static function getLatest($type)
    {
        $db = DBManagerFactory::getInstance();
        $result = $db->limitQuery(sprintf("SELECT * FROM timeperiods WHERE type = '%s' AND deleted = 0 ORDER BY start_date_timestamp DESC", $type), 0, 1);
        if($result) {
            $row = $db->fetchByAssoc($result);
            if(!empty($row)) {
               return TimePeriod::getByType($type, $row['id']);
            }
        }
        return null;
    }

    /**
     * Returns a TimePeriod bean instance based on the given interval type
     *
     * @param $type String value of the TimePeriod interval type
     * @param $id String value of optional id for TimePeriod
     *
     * @return TimePeriod A TimePeriod instance bean based on the interval type
     */
    public static function getByType($type, $id='')
    {
        if(empty($id)) {
            return BeanFactory::getBean("{$type}TimePeriods");
        }
        return BeanFactory::getBean("{$type}TimePeriods", $id);
    }

}

function get_timeperiods_dom()
{
    return TimePeriod::get_timeperiods_dom();
}

function get_not_fiscal_timeperiods_dom()
{
    return TimePeriod::get_not_fiscal_timeperiods_dom();
}
