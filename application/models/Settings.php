<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Model
{
	protected $settings = array();

	function __construct(){

		parent::__construct();

		// Read all settings
		$this->get_all();
	}

	// Read all settings
	private function get_all()
	{
		$this->db->from( 'settings' );

		$query = $this->db->get();

		foreach( $query->result() as $row ){
			$this->settings[ $row->key ] = $row->val;
		}
	}

	public function get( $key = '' ){
		return $this->settings[ $key ];
	}
}