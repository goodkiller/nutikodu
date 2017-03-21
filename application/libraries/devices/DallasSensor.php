<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DallasSensor extends VirtualDevice
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
			return round( $last_value_info->value, 1 ) . $this->item_info->unit;
		}
		else
		{
			return 'N/A';
		}
	}
}