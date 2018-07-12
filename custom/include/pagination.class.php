<?php

/**
 * pagination at survey report and detailview of survey
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */

  class pagination
  {

    /**
     * Properties array
     * @var array   
     * @access private 
     */
    private $_properties = array();

    /**
     * Default configurations
     * @var array  
     * @access public 
     */
    public $_defaults = array(
      'page' => 1,
      'perPage' => 1
    );

    /**
     * Constructor
     * 
     * @param array $array   Array of results to be paginated
     * @param int   $curPage The current page interger that should used
     * @param int   $perPage The amount of items that should be show per page
     * @return void    
     * @access public  
     */
    public function __construct($array, $curPage = null, $perPage = null)
    {
      $this->array   = $array;
      $this->curPage = ($curPage == null ? $this->defaults['page']    : $curPage);
      $this->perPage = ($perPage == null ? $this->defaults['perPage'] : $perPage);
    }

    /**
     * Global setter
     * 
     * Utilises the properties array
     * 
     * @param string $name  The name of the property to set
     * @param string $value The value that the property is assigned
     * @return void    
     * @access public  
     */
    public function __set($name, $value) 
    { 
      $this->_properties[$name] = $value;
    } 

    /**
     * Global getter
     * 
     * Takes a param from the properties array if it exists
     * 
     * @param string $name The name of the property to get
     * @return mixed Either the property from the internal
     * properties array or false if isn't set
     * @access public  
     */
    public function __get($name)
    {
      if (array_key_exists($name, $this->_properties)) {
        return $this->_properties[$name];
      }
      return false;
    }

    /**
     * Set the show first and last configuration
     * 
     * This will enable the "<< first" and "last >>" style
     * links
     * 
     * @param boolean $showFirstAndLast True to show, false to hide.
     * @return void    
     * @access public  
     */
    public function setShowFirstAndLast($showFirstAndLast)
    {
        $this->_showFirstAndLast = $showFirstAndLast;
    }

    /**
     * Set the main seperator character
     * 
     * By default this will implode an empty string
     * 
     * @param string $mainSeperator The seperator between the page numbers
     * @return void    
     * @access public  
     */
    public function setMainSeperator($mainSeperator)
    {
      $this->mainSeperator = $mainSeperator;
    }

    /**
     * Get the result portion from the provided array 
     * 
     * @return array Reduced array with correct calculated offset 
     * @access public 
     */
    public function getResults()
    {
      // Assign the page variable
      if (empty($this->curPage) !== false) {
        $this->page = $this->curPage; // using the get method
      } else {
        $this->page = 1; // if we don't have a page number then assume we are on the first page
      }
      
      // Take the length of the array
      $this->length = count($this->array);
      
      // Get the number of pages
      $this->pages = ceil($this->length / $this->perPage);
      
      // Calculate the starting point 
      $this->start = ceil(($this->page - 1) * $this->perPage);
      
      // return the portion of results
        if($this->length == 1){
            return $this->array;
        }else{
            return array_slice($this->array, $this->start, $this->perPage);
        }

    }
    
    /**
     * Get the html links for the generated page offset
     * 
     * @param array $params A list of parameters (probably get/post) to
     * pass around with each request
     * @return mixed  Return description (if any) ...
     * @access public 
     */
    public function getLinks($params = array(),$survey_id='',$page='',$module_id='',$submission_id,$customer_name)
    {
        if($page){
            $this->page = $page;
          //  $this->pages = $page;
        }
      // Initiate the links array
      $plinks = array();
      $links = array();
      $slinks = array();



      // Concatenate the get variables to add to the page numbering string
      $queryUrl = '';
      if (!empty($params) === true) {
          unset($params['page']);
          unset($params['ajax_load']);
          unset($params['loadLanguageJS']);
        $queryUrl = '&amp;'.http_build_query($params);

      }
      
      // If we have more then one pages
      if (($this->pages) > 1) {
        // Assign the 'previous page' link into the array if we are not on the first page
        if ($this->page != 1) {
          if ($this->_showFirstAndLast) {
              if($survey_id) {
                  $setAjax = "onclick='getReports(\"{$survey_id}\",1,\"{$module_id}\",\"{$submission_id}\",\"{$customer_name}\")'";
                  $first_last_link = '<a href="javascript:void(0)"'.$setAjax.'>&laquo;&laquo; First </a>';
              }else{
                  $first_last_link = ' <a href="?page=1'.$queryUrl.'">&laquo;&laquo; First </a> ';
              }
            $plinks[] = $first_last_link;
          }
            if($survey_id) {
                $page_prev = $this->page - 1;
                $setAjax = "onclick='getReports(\"{$survey_id}\",{$page_prev},\"{$module_id}\",\"{$submission_id}\",\"{$customer_name}\")'";
                $prev_link = '<a href="javascript:void(0)"'.$setAjax.'>&laquo; Prev</a>';
            }else{
                $prev_link = ' <a href="?page='.($this->page - 1).$queryUrl.'">&laquo; Prev</a> ';
            }
            $plinks[] = $prev_link;
        }
        
        // Assign all the page numbers & links to the array
        for ($j = 1; $j < ($this->pages + 1); $j++) {
            if($survey_id){
                $setAjax = "onclick='getReports(\"{$survey_id}\",{$j},\"{$module_id}\",\"{$submission_id}\",\"{$customer_name}\")'";
                $link = '<a href="javascript:void(0)"'.$setAjax.'>'.$j.'</a>';
            }else{
                $link = '<a href="?page='.$j.$queryUrl.'">'.$j.'</a> ';
            }
          if ($this->page == $j) {
              if($survey_id){
                  $setAjax = "onclick='getReports(\"{$survey_id}\",{$j},\"{$module_id}\",\"{$submission_id}\" ,\"{$customer_name}\")'";
                  $link = '<a href="javascript:void(0)"'.$setAjax.' class="selected">'.$j.'</a>';
              }else{
                  $link = '<a class="selected">'.$j.'</a> ';
              }
            $links[] = $link; // If we are on the same page as the current item
          } else {
            $links[] = $link; // add the link to the array
          }
        }

        // Assign the 'next page' if we are not on the last page
        if ($this->page < $this->pages) {
            if($survey_id) {
                $page_next = $this->page + 1;
                $setAjax = "onclick='getReports(\"{$survey_id}\",{$page_next},\"{$module_id}\",\"{$submission_id}\",\"{$customer_name}\")'";
                $next_link = '<a href="javascript:void(0)"'.$setAjax.'>Next &raquo;</a>';
            }else{
                $next_link = ' <a href="?page='.($this->page + 1).$queryUrl.'"> Next &raquo; </a> ';
            }
          $slinks[] = $next_link;
          if ($this->_showFirstAndLast) {
              if($survey_id) {
                  $setAjax = "onclick='getReports(\"{$survey_id}\",{$this->pages},\"{$module_id}\",\"{$submission_id}\",\"{$customer_name}\")'";
                  $first_last_link = '<a href="javascript:void(0)"'.$setAjax.'> Last &raquo;&raquo; </a>';
              }else{
                  $first_last_link = '<a href="?page='.($this->pages).$queryUrl.'"> Last &raquo;&raquo; </a>';
              }
            $slinks[] = $first_last_link;
          }
        }
        
        // Push the array into a string using any some glue
        return implode(' ', $plinks).implode($this->mainSeperator, $links).implode(' ', $slinks);
      }
      return;
    }

      public function getIndividual_SearchLinks($params = array(),$survey_id='',$page='')
      {
          if($page){
              $this->page = $page;
              //  $this->pages = $page;
          }
          // Initiate the links array
          $plinks = array();
          $links = array();
          $slinks = array();



          // Concatenate the get variables to add to the page numbering string
          $queryUrl = '';
          if (!empty($params) === true) {
              unset($params['page']);
              unset($params['ajax_load']);
              unset($params['loadLanguageJS']);
              $queryUrl = '&amp;'.http_build_query($params);

          }

          // If we have more then one pages
          if (($this->pages) > 1) {
              // Assign the 'previous page' link into the array if we are not on the first page
              if ($this->page != 1) {
                  if ($this->_showFirstAndLast) {
                      if($survey_id) {
                          $setAjax = "onclick='getSearchResult(\"individual\",\"{$survey_id}\",1,\"search\")'";
                          $first_last_link = '<a href="javascript:void(0)"'.$setAjax.'>&laquo;&laquo; First </a>';
                      }else{
                          $first_last_link = ' <a href="?page=1'.$queryUrl.'">&laquo;&laquo; First </a> ';
                      }
                      $plinks[] = $first_last_link;
                  }
                  if($survey_id) {
                      $page_prev = $this->page - 1;
                      $setAjax = "onclick='getSearchResult(\"individual\",\"{$survey_id}\",{$page_prev},\"search\")'";
                      $prev_link = '<a href="javascript:void(0)"'.$setAjax.'>&laquo; Prev</a>';
                  }else{
                      $prev_link = ' <a href="?page='.($this->page - 1).$queryUrl.'">&laquo; Prev</a> ';
                  }
                  $plinks[] = $prev_link;
              }

              // Assign all the page numbers & links to the array
              for ($j = 1; $j < ($this->pages + 1); $j++) {
                  if($survey_id){
                      $setAjax = "onclick='getSearchResult(\"individual\",\"{$survey_id}\",{$j},\"search\")'";
                      $link = '<a href="javascript:void(0)"'.$setAjax.'>'.$j.'</a>';
                  }else{
                      $link = '<a href="?page='.$j.$queryUrl.'">'.$j.'</a> ';
                  }
                  if ($this->page == $j) {
                      if($survey_id){
                          $setAjax = "onclick='getSearchResult(\"individual\",\"{$survey_id}\",{$j},\"search\")'";
                          $link = '<a href="javascript:void(0)"'.$setAjax.' class="selected">'.$j.'</a>';
                      }else{
                          $link = '<a class="selected">'.$j.'</a> ';
                      }
                      $links[] = $link; // If we are on the same page as the current item
                  } else {
                      $links[] = $link; // add the link to the array
                  }
              }

              // Assign the 'next page' if we are not on the last page
              if ($this->page < $this->pages) {
                  if($survey_id) {
                      $page_next = $this->page + 1;
                      $setAjax = "onclick='getSearchResult(\"individual\",\"{$survey_id}\",{$page_next},\"search\")'";
                      $next_link = '<a href="javascript:void(0)"'.$setAjax.'>Next &raquo;</a>';
                  }else{
                      $next_link = ' <a href="?page='.($this->page + 1).$queryUrl.'"> Next &raquo; </a> ';
                  }
                  $slinks[] = $next_link;
                  if ($this->_showFirstAndLast) {
                      if($survey_id) {
                          $setAjax = "onclick='getSearchResult(\"individual\",\"{$survey_id}\",{$this->pages},\"search\")'";
                          $first_last_link = '<a href="javascript:void(0)"'.$setAjax.'> Last &raquo;&raquo; </a>';
                      }else{
                          $first_last_link = '<a href="?page='.($this->pages).$queryUrl.'"> Last &raquo;&raquo; </a>';
                      }
                      $slinks[] = $first_last_link;
                  }
              }

              // Push the array into a string using any some glue
              return implode(' ', $plinks).implode($this->mainSeperator, $links).implode(' ', $slinks);
          }
          return;
      }
  }