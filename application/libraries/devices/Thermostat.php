<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Thermostat extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_title(){
		
		return $this->item_info->title;
	}

	function get_item_body(){

		return $this->item_info->last_value . '&deg;';
	}

	/**
	 * Set thermostat value
	 * @method  exact
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	function exact(){

		$status = $this->CI->zapi->send_command( $this->item_info->address, 'exact', array( 'level' => $level ) );

		if( $status ){
			$this->CI->zitem->set_value( $this->item_info->id, $level );
		}

		return $status;
	}
}