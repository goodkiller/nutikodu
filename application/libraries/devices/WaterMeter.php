<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WaterMeter extends VirtualDevice
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
			$date = date( 'd.m.y', strtotime($last_value_info->create_date) );

			return round( $last_value_info->value, 1 ) . $this->item_info->unit . '<p class="footer">' . $date . '</p>';
		}
		else
		{
			return 'N/A';
		}
	}
}