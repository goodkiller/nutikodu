<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Model
{
	protected $settings = array();

	function __construct(){

		parent::__construct();

		// Read all settings from database
		$this->get_all();
	}

	/**
	 * Read all settings from database
	 * @method  get_all
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	private function get_all()
	{
		$this->db->from( 'settings' );

		$query = $this->db->get();

		foreach( $query->result() as $row ){
			$this->settings[ $row->key ] = $row->val;
		}
	}

	/**
	 * Get specific settings value by key
	 * @method  get
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	public function get( $key = '' ){
		return $this->settings[ $key ];
	}
}