<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MultilevelSwitch extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_title(){
		
		return $this->item_info->title;
	}

	function get_item_body(){

		$classes = array( 'fa' );

		// Off
		if( $this->item_info->last_value == 0 )
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

	function get_body( $event_type = 'click' ){

		$vars = array(
			'item_info' => $this->item_info
		);

		return $this->CI->load->view( 'devices/' . __CLASS__ . '/body/' . $event_type, $vars, TRUE );
	}

	function on(){
		
		$status = $this->CI->zapi->send_command( $this->item_info->address, 'on' );

		if( $status ){
			$this->CI->zitem->set_value( $this->item_info->id, 99 );
		}

		return $status;
	}

	function off(){
		
		$status = $this->CI->zapi->send_command( $this->item_info->address, 'off' );

		if( $status ){
			$this->CI->zitem->set_value( $this->item_info->id, 0 );
		}

		return $status;
	}

	function min(){
		return $this->CI->zapi->send_command( $this->item_info->address, 'min' );
	}

	function max(){
		return $this->CI->zapi->send_command( $this->item_info->address, 'max' );
	}

	function increase(){
		return $this->CI->zapi->send_command( $this->item_info->address, 'increase' );
	}

	function decrease(){
		return $this->CI->zapi->send_command( $this->item_info->address, 'decrease' );
	}

	function update(){
		return $this->CI->zapi->send_command( $this->item_info->address, 'update' );
	}

	function exact(){

		$status = $this->CI->zapi->send_command( $this->item_info->address, 'exact', array( 'level' => $level ) );

		if( $status ){
			$this->CI->zitem->set_value( $this->item_info->id, $level );
		}

		return $status;
	}

	/**
	 * Crontab
	 * @method  crontab
	 * @author  Marko Praakli
	 * @date    2017-01-08
	 */
	function crontab(){

	}
}