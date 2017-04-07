<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function jobs()
	{
		log_message( 'debug', '[CRON] Start jobs.' );
	
		$this->load->library( 'Jobs' );

		$this->jobs->check_all();

		log_message( 'debug', '[CRON] End jobs.' );
	}
}
