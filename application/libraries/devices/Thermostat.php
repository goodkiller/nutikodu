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

		// Get item last value
		if( $last_value_info = $this->CI->zitem->get_last_value( $this->item_info->id ) )
		{
			return $last_value_info->value . $this->item_info->unit;
		}
		else
		{
			return 'N/A';
		}
	}

	/**
	 * Set thermostat value
	 * @method  exact
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	function exact( $level = 0 ){

		$status = $this->CI->zapi->send_command( $this->item_info->address, 'exact', array( 'level' => $level ) );

		if( $status ){
			$this->CI->zitem->add_history( $this->item_info->id, $level );
		}

		return $status;
	}
}