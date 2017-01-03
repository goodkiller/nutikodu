<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MultilevelSwitch extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_title( $item_info = array() ){
		
		return $item_info->title;
	}

	function get_item_body( $item_info = array() ){

		$classes = array( 'fa' );

		// Off
		if( $item_info->last_value == 0 )
		{
			$classes[] = 'fa-toggle-off text-muted';
		}

		// On
		else
		{
			$classes[] = 'fa-toggle-on';
		}

		return '<i class="' . implode( ' ', $classes ) . '" aria-hidden="true"></i>';
	}

	function get_item_options( $item_info = array() ){
		return array(
			'event' => 'toggle'
		);
	}

	function on( $item_info = array() ){
		
		$status = $this->CI->zapi->send_command( $item_info->address, 'on' );

		if( $status ){
			$this->CI->zitem->set_value( $item_info->id, 99 );
		}

		return $status;
	}

	function off( $item_info = array() ){
		
		$status = $this->CI->zapi->send_command( $item_info->address, 'off' );

		if( $status ){
			$this->CI->zitem->set_value( $item_info->id, 0 );
		}

		return $status;
	}

	function min( $item_info = array() ){
		return $this->CI->zapi->send_command( $item_info->address, 'min' );
	}

	function max( $item_info = array() ){
		return $this->CI->zapi->send_command( $item_info->address, 'max' );
	}

	function increase( $item_info = array() ){
		return $this->CI->zapi->send_command( $item_info->address, 'increase' );
	}

	function decrease( $item_info = array() ){
		return $this->CI->zapi->send_command( $item_info->address, 'decrease' );
	}

	function update( $item_info = array() ){
		return $this->CI->zapi->send_command( $item_info->address, 'update' );
	}

	function exact( $item_info = array() ){

		$status = $this->CI->zapi->send_command( $item_info->address, 'exact', array( 'level' => $level ) );

		if( $status ){
			$this->CI->zitem->set_value( $item_info->id, $level );
		}

		return $status;
	}
}