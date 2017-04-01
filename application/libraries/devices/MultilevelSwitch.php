<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MultilevelSwitch extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_title(){
		
		return $this->item_info->title;
	}

	function get_item_body( $dashboard_item_info = array() ){

		$vars = array(
			'item_info' => $this->item_info,
			'last_value_info' => $this->CI->zitem->get_last_value( $this->item_info->id )
		);

		return $this->CI->load->view( 'devices/' . __CLASS__ . '/item_body/' . $dashboard_item_info->size, $vars, TRUE );
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
			$this->CI->zitem->add_history( $this->item_info->id, 99 );
		}

		return $status;
	}

	function off(){
		
		$status = $this->CI->zapi->send_command( $this->item_info->address, 'off' );

		if( $status ){
			$this->CI->zitem->add_history( $this->item_info->id, 0 );
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

	function exact( $level = 0 ){

		$status = $this->CI->zapi->send_command( $this->item_info->address, 'exact', array( 'level' => $level ) );

		if( $status ){
			$this->CI->zitem->add_history( $this->item_info->id, $level );
		}

		return $status;
	}
}