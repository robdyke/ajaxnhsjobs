<?php
/*
Plugin Name: AJAX NHS Jobs Search
Plugin URI: 
Description: A plugin that adds AJAX functionality to the St George's jobs page
Version: 1.0
Author: Colin Wren
Author URI: http://colinwren.is/awesome
Author Email: colin.wren@stgeorges.nhs.uk
License:

  Copyright 2013 St George's Healthcare NHS Trust (communications@stgeorges.nhs.uk)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

class AJAXNHSJobsSearch {
	 
	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/
	
	/**
	 * Initializes the plugin
	 */
	function __construct() {
	
		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_styles' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_scripts' ) );
		
		// Include the Ajax library on the front end
		add_action( 'wp_head', array( &$this, 'add_ajax_library' ) );
		
		// Setup the event handler for marking this post as read for the current user
		add_action( 'wp_ajax_refresh_job_search', array( &$this, 'refresh_job_search' ) );
		
		// Setup the filter for rendering the checkbox
		//add_filter( 'the_content', array( &$this, 'add_checkbox' ) );

	} // end constructor

	/*--------------------------------------------*
	 * Action Functions
	 *--------------------------------------------*/

	/**
	 * Adds the WordPress Ajax Library to the frontend.
	 */
	public function add_ajax_library() {
		
		$html = '<script type="text/javascript">';
			$html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"';
		$html .= '</script>';
		
		echo $html;	
		
	} // end add_ajax_library

	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	public function register_plugin_styles() {
	
		wp_register_style( 'ajaxnhsjobssearch', plugins_url( 'ajaxnhsjobssearch/css/plugin.css' ) );
		wp_enqueue_style( 'ajaxnhsjobssearch' );
	
	} // end register_plugin_styles
	
	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	public function register_plugin_scripts() {
	
		wp_register_script( 'ajaxnhsjobssearch', plugins_url( 'ajaxnhsjobssearch/js/plugin.js' ), array( 'jquery' ) );
		wp_enqueue_script( 'ajaxnhsjobssearch' );
	
	} // end register_plugin_scripts
	
	/**
	 * Takes the data sent over to the server, uses this to call up the NHS Jobs API and returns the response to the JS function
	 */
	public function refresh_job_search() {
		
		// First, we need to make sure the post ID parameter has been set and that's it's a numeric value
		$vacanciesToReturn = array();
		//if(isset($_POST['keyword']) && isset($_POST['type']) && isset($_POST['sort']) && isset($_POST['pay']) && isset($_POST['new'])){
			$AJAXtype = $_POST["type"];
			$AJAXsort = $_POST["sort"];
			$AJAXkeyword = $_POST["keyword"];
			$AJAXpay = $_POST["pay"];
			$AJAXnew = $_POST["newq"];

			$AJAX = array();

			if($AJAXtype){
				$AJAX["type"]= $AJAXtype;
			}
			
			if($AJAXsort){
				$AJAX["sort"] = $AJAXsort;
			}

			if($AJAXkeyword){
				$AJAX["keyword"] = $AJAXkeyword;
			}

			if($AJAXpay){
				$AJAX["pay"] = $AJAXpay;
			}

			if($AJAXnew){
				$AJAX["new"] = $AJAXnew;
			}


			$url = "http://www.jobs.nhs.uk/extsearch?client_id=121185&infonly=1";

			switch($AJAX["sort"]){
				case "recent":
					$url .= "&sort_order=D";
					break;
				case "high":
					$url .= "&sort_order=S";
					break;
				case "low":
					$url .= "&sort_order=S";
					break;
			}
			
			switch($AJAX["type"]){
				case "all":
					$url .= "&jobtype=E";
					break;
				case "permanent":
					$url .= "&jobtype=P";
					break;
				case "fixedtermtemporary":
					$url .= "&jobtype=F";
					break;
				case "temporary":
					$url .= "&jobtype=T";
					break;
				case "bank":
					$url .= "&jobtype=B";
					break;
				case "honorary":
					$url .= "&jobtype=H";
					break;
				case "locum":
					$url .= "&jobtype=L";
					break;
				case "voluntary":
					$url .= "&jobtype=V";
					break;
			}

			switch($AJAX["pay"]){
				case "A02":
					$url .= "&reqd_salary_lower=A02";
					break;
				case "A03":
					$url .= "&reqd_salary_lower=A03";
					break;
				case "A04":
					$url .= "&reqd_salary_lower=A04";
					break;
				case "A05":
					$url .= "&reqd_salary_lower=A05";
					break;
				case "A06":
					$url .= "&reqd_salary_lower=A06";
					break;
				case "A07":
					$url .= "&reqd_salary_lower=A07";
					break;
				case "A08":
					$url .= "&reqd_salary_lower=A08";
					break;
				case "A09":
					$url .= "&reqd_salary_lower=A09";
					break;
				case "A10":
					$url .= "&reqd_salary_lower=A10";
					break;
				case "H02":
					$url .= "&reqd_salary_lower=H02";
					break;
				case "H03":
					$url .= "&reqd_salary_lower=H03";
					break;
				case "H04":
					$url .= "&reqd_salary_lower=H04";
					break;
				case "H05":
					$url .= "&reqd_salary_lower=H05";
					break;
				case "H06":
					$url .= "&reqd_salary_lower=H06";
					break;
				case "H07":
					$url .= "&reqd_salary_lower=H07";
					break;
				case "H08":
					$url .= "&reqd_salary_lower=H08";
					break;
				case "H09":
					$url .= "&reqd_salary_lower=H09";
					break;
				case "H10":
					$url .= "&reqd_salary_lower=H10";
					break;
				case "1":
					$url .= "&pay_scheme=AC&pay_band=1";
					break;
				case "2":
					$url .= "&pay_scheme=AC&pay_band=2";
					break;
				case "3":
					$url .= "&pay_scheme=AC&pay_band=3";
					break;
				case "4":
					$url .= "&pay_scheme=AC&pay_band=4";
					break;
				case "5":
					$url .= "&pay_scheme=AC&pay_band=5";
					break;
				case "6":
					$url .= "&pay_scheme=AC&pay_band=6";
					break;
				case "7":
					$url .= "&pay_scheme=AC&pay_band=7";
					break;
				case "8a":
					$url .= "&pay_scheme=AC&pay_band=8a";
					break;
				case "8b":
					$url .= "&pay_scheme=AC&pay_band=8b";
					break;
				case "8c":
					$url .= "&pay_scheme=AC&pay_band=8c";
					break;
				case "8d":
					$url .= "&pay_scheme=AC&pay_band=8d";
					break;
				case "9":
					$url .= "&pay_scheme=AC&pay_band=9";
					break;
				case "CN":
					$url .= "&pay_scheme=MD&pay_band=CN";
					break;
				case "AS":
					$url .= "&pay_scheme=MD&pay_band=AS";
					break;
				case "SG":
					$url .= "&pay_scheme=MD&pay_band=SG";
					break;
				case "SR":
					$url .= "&pay_scheme=MD&pay_band=SR";
					break;
				case "SM":
					$url .= "&pay_scheme=SM";
					break;
			}
			
			if($AJAX["keyword"] && $AJAX["keyword"] != ""){
				$url .= "&skill_keywords=".urlencode($AJAX["keyword"])."&skill_match=ALL";
			}
			
			if($AJAX["new"] == "true"){
				$url .= "&suitable_for_newly_qualified=Y";
			}
			
			
			$xml = simplexml_load_file($url);
			
			$status = $xml->status->number_of_jobs_found;
			$jobsadded = 0;
			if($status != 0){
				$vacanciesToReturn["items"] = array();
				foreach($xml->vacancy_details as $vacancy){
					//Make variables for each object
					$jobsadded++;
					$vacObj = array();
					$vacObj["id"] = (string)$vacancy->id;
					$vacObj["ref"] = (string)$vacancy->job_reference;
					$vacObj["title"] = (string)$vacancy->job_title;
					$vacObj["desc"] = (string)$vacancy->job_description;
					$vacObj["type"] = (string)$vacancy->job_type;
					$vacObj["specialty"] = (string)$vacancy->job_specialty;
					$vacObj["salary"] = (string)$vacancy->job_salary;
					$vacObj["location"] = (string)$vacancy->job_location;
					$vacObj["close"] = (string)$vacancy->job_closedate;
					$vacObj["post"] = (string)$vacancy->job_postdate;
					$vacObj["url"] = (string)$vacancy->job_url;
				
					array_push($vacanciesToReturn["items"], $vacObj);
				
				}
				
				if($AJAX["sort"] == "low"){
					$vacanciesToReturn["items"] = array_reverse($vacanciesToReturn["items"]);
				}
				
				
				$vacanciesToReturn["status"] = $jobsadded." jobs found";
				//$htmlToReturn .= json_encode($vacancies);
				
				/*foreach($vacancies as $vac){
				
					//build the html to return	
					$htmlToReturn .= "<div".$hidder." class=\"row job ".str_replace(" ", "", strtolower($vac["type"]))."\">";
					$htmlToReturn .= "<h3><a href=\"".$vac["url"]."\">".$vac["title"]."</a></h3>";
					$htmlToReturn .= "<p>".$vac["desc"]."</p>";
					$htmlToReturn .= "<dl class=\"dl-horizontal\">";
					$htmlToReturn .= "<dt>Job Type</dt><dd>".$vac["type"]."</dd>";
					$htmlToReturn .= "<dt>Salary</dt><dd>".$vac["salary"]."</dd>";
					$htmlToReturn .= "<dt>Close date</dt><dd>".$vac["close"]."</dd>";
					$htmlToReturn .= "</dl>";
					$htmlToReturn .= "<p><a href=\"".$vac["url"]."\" class=\"btn btn-info pull-right\">Apply now</a></p>";
					$htmlToReturn .= "</div>"; 
				}*/
			}else{
				$vacanciesToReturn["status"] = "error";
			}
			
		//}
		echo json_encode($vacanciesToReturn);
		
		die();
		
	} // end refresh_job_search
	
	/*--------------------------------------------*
	 * Filter Functions
	 *--------------------------------------------*/
	 
	 /**
	  * Adds a checkbox to the end of a post in single view that allows users who are logged in
	  * to mark their post as read.
	  * 
	  * @param	$content	The post content
	  * @return				The post content with or without the added checkbox
	  
	 public function add_checkbox( $content ) {
		 
		 // We only want to modify the content if the user is logged in
		 if( is_single() ) {

			 // If the user is logged in...
			 if( is_user_logged_in() ) {
				 
				 // And if they've previously read this post...
				 if( 'ive_read_this' == AJAX_user_meta( wp_AJAX_current_user()->ID, AJAX_the_ID(), true ) ) {
				  
					 // Build the element to indicate this post has been read
					 $html = '<div id="ive-read-this-container">';
					 	$html .= '<strong>';
					 		$html .= __( "I've read this post.", 'ive-read-this' );
					 	$html .= '</strong>';
					 $html .= '</div><!-- /#ive-read-this-container -->';
				 
				 // Otherwise, give them the option to mark this post as read
				 } else {

				 	// Build the element that will be used to mark this post as read
					 $html = '<div id="ive-read-this-container">';
					 	$html .= '<label for="ive-read-this">';
					 		$html .= '<input type="checkbox" name="ive-read-this" id="ive-read-this" value="0" />';
					 		$html .= __( "I've read this post.", 'ive-read-this' );
					 	$html .= '</label>';
					 $html .= '</div><!-- /#ive-read-this-container -->';				 
				 
				 } // end if
				 
				 // Append it to the content
				 $content .= $html;
				 
			 } // end if
			 
		 } // end if
		 
		 return $content;
		 
	 } // end add_checkbox */
  
} // end class

new AJAXNHSJobsSearch();
?>