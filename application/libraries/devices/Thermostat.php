<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Thermostat extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_body( $item_info = array() ){

		return $item_info->last_value . '&deg;';
	}

	function exact( $item_info = array() ){

		$status = $this->CI->zapi->send_command( $item_info->address, 'exact', array( 'level' => $level ) );

		if( $status ){
			$this->CI->zitem->set_value( $item_info->id, $level );
		}

		return $status;
	}
}