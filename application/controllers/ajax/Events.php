<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	private function output( $data = array() ){

		header( 'Content-Type: application/json' );

		echo json_encode( $data );
	}

	function items()
	{
		$this->output(array(
			'post' => $this->input->post(),
			'status' => 'OK'
		));
	}
}
