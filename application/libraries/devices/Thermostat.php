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
	function exact(){

		$status = $this->CI->zapi->send_command( $this->item_info->address, 'exact', array( 'level' => $level ) );

		if( $status ){
			$this->CI->zitem->add_history( $this->item_info->id, $level );
		}

		return $status;
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