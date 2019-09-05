<?php
// application/core/MY_Controller.php

class MY_Controller extends CI_Controller {
	public $ArrSession = array ();
	public $UserGroup;
	public $dataAudit;
	public function __construct() {
		parent::__construct ();
		// print_r($this->session->all_userdata());
		// session_start();
		// var_dump($_SESSION);
		// /$role=$_SESSION['MM_UserGroup'];
		//
		
		$is_logged_in = $this->session->userdata ( 'logged_in' );
		if (! isset ( $is_logged_in ) || $is_logged_in != true) {
		
			$this->load->view("session");
			
			//redirect(base_url(),'refresh');
			//die ();
		} else {
			//$time_since = time() - $this->session->userdata('last_activity');
			//var_dump($time_since);
		}
		
		
	
	}
	
	// do whatever here - i often use this method for authentication
	// controller
}
