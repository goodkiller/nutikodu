<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Battery extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_title( $item_info = array() ){
		
		return $item_info->title;
	}

	function get_item_body( $item_info = array() ){

		if( $item_info->last_value <= 5 ){
			$battery_level = 0;
		}
		else if( $item_info->last_value <= 25 ){
			$battery_level = 1;
		}
		else if( $item_info->last_value <= 45 ){
			$battery_level = 2;
		}
		else if( $item_info->last_value <= 75 ){
			$battery_level = 3;
		}
		else{
			$battery_level = 4;
		}

		$classes = array( __CLASS__, 'fa', 'fa-battery-' . $battery_level );

		return '<i class="' . implode( ' ', $classes ) . '" aria-hidden="true"></i><br />' . $item_info->last_value . '%';
	}
}