<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Waterreadings extends CI_Controller {

	public function index()
	{
		$data['readings'] = $this->db->from( 'watermeter_readings' )->order_by('eventdate', 'desc')->get()->result();

		$this->load->view('waterreadings', $data);
	}
}
