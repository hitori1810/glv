<?PHP
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
    * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
    */
    require_once('modules/J_Payment/J_Payment_sugar.php');
    class J_Payment extends J_Payment_sugar {

        /**
        * This is a depreciated method, please start using __construct() as this method will be removed in a future version
        *
        * @see __construct
        * @depreciated
        */
        function J_Payment(){
            self::__construct();
        }

        public function __construct(){
            parent::__construct();
        }
        //custom code File save  - by Lap Nguyen
        public function save($check_notify=false)
        {
            if (!empty($this->uploadfile)) {
                $this->filename = $this->uploadfile;
            }

            return parent::save($check_notify);
        }

        /**
        * @see SugarBean::fill_in_additional_detail_fields()
        */
        public function fill_in_additional_detail_fields()
        {
            global $app_list_strings;
            global $img_name;
            global $img_name_bare;

            $this->uploadfile = $this->filename;

            // Bug 41453 - Make sure we call the parent method as well
            parent::fill_in_additional_detail_fields();

            if (!$this->file_ext) {
                $img_name = SugarThemeRegistry::current()->getImageURL(strtolower($this->file_ext)."_image_inline.gif");
                $img_name_bare = strtolower($this->file_ext)."_image_inline";
            }

            //set default file name.
            if (!empty ($img_name) && file_exists($img_name)) {
                $img_name = $img_name_bare;
            }
            else {
                $img_name = "def_image_inline"; //todo change the default image.
            }
            $this->file_url_noimage = $this->id;

            if(!empty($this->status_id)) {
                $this->status = $app_list_strings['document_status_dom'][$this->status_id];
            }
        }


        /**
        * Method to delete an attachment
        *
        * @param string $isduplicate
        * @return bool
        */
        public function deleteAttachment($isduplicate = "false")
        {
            if ($this->ACLAccess('edit')) {
                if ($isduplicate == "true") {
                    return true;
                }
                $removeFile = "upload://{$this->id}";
            }
            if (file_exists($removeFile)) {
                if (!unlink($removeFile)) {
                    $GLOBALS['log']->error("*** Could not unlink() file: [ {$removeFile} ]");
                } else {
                    $this->uploadfile = '';$this->uploadfile = '';
                    $this->filename = '';
                    $this->file_mime_type = '';
                    $this->file_ext = '';
                    $this->save();
                    return true;
                }
            } else {
                $this->uploadfile = '';
                $this->filename = '';
                $this->file_mime_type = '';
                $this->file_ext = '';
                $this->save();
                return true;
            }
            return false;
        }

    }
?>