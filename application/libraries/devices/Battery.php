<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Battery extends VirtualDevice
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
			if( $last_value_info->value <= 5 ){
				$battery_level = 0;
			}
			else if( $last_value_info->value <= 25 ){
				$battery_level = 1;
			}
			else if( $last_value_info->value <= 45 ){
				$battery_level = 2;
			}
			else if( $last_value_info->value <= 75 ){
				$battery_level = 3;
			}
			else{
				$battery_level = 4;
			}

			$classes = array( __CLASS__, 'fa', 'fa-battery-' . $battery_level );

			return '<i class="' . implode( ' ', $classes ) . '" aria-hidden="true"></i><br />' . $last_value_info->value . $this->item_info->unit;
		}
		else
		{
			return 'N/A';
		}
	}

	/**
	 * Force check
	 * @method  force_check
	 * @author  Marko Praakli
	 * @date    2017-02-25
	 */
	function force_check()
	{
		$zinfo = $this->CI->zapi->get_device( $this->item_info->address );

		// Add history
		if( $this->CI->zitem->add_history( $this->item_info->id, $zinfo[ 'metrics' ][ 'level' ],  $zinfo[ 'metrics' ][ 'modificationTime' ] ) ){
			return TRUE;
		}

		return FALSE;
	}
}