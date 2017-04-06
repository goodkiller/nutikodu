<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AccuWeather extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_title(){
		
		return $this->item_info->title;
	}

	function get_item_body()
	{
		// Get item last value
		if( $last_value_info = $this->CI->zitem->get_last_value( $this->item_info->id ) )
		{
			return '<img src="' . $last_value_info->params->icon . '">' . $last_value_info->value . $last_value_info->params->symbol;
		}
		else
		{
			return 'N/A';
		}
	}
}