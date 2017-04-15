<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zitems extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	private function output( $data = array() ){

		header( 'Content-Type: application/json' );

		echo json_encode( $data );
	}

	function discover()
	{
		$this->zapi->discover();

		$this->output(array(
			'status' => 'OK'
		));
	}

	function test()
	{

		echo bin2hex(openssl_random_pseudo_bytes(1));
	}

	function get()
	{
		$items_list = array();

		foreach( $this->zitem->get() as $i => $item )
		{
			$items_list[ $i ] = $item;

			if( !empty($item->classname) ){
				$items_list[ $i ]->body = $this->virtualdevice->call( $item_id, 'get_item_body' );
			}

			// If no body defined in device library, return default body
			if( !isset($items_list[ $i ]->body) ){
				$items_list[ $i ]->body = $this->virtualdevice->get_item_body();
			}
		}

		$this->output(array(
			'items' => $items_list
		));
	}

	function click( $item_id = 0 )
	{
		$item_info = $this->zitem->get( $item_id );

		if( !empty($item_info) )
		{
			echo $this->virtualdevice->call( $item_id, 'get_body', array( 'click' ) );
		}
	}

	function toggle( $item_id = 0 )
	{
		$response = array(
			'status' => 'ERROR'
		);

		// Get item last value
		$last_value_info = $this->zitem->get_last_value( $item_id );
	
		// Last value was off
		if( empty($last_value_info) || $last_value_info->value == 0 )
		{
			// Set on
			$status = $this->virtualdevice->call( $item_id, 'on' );
		}

		// Last value was on
		else
		{
			// Set on
			$status = $this->virtualdevice->call( $item_id, 'off' );
		}

		$response[ 'status' ] = $status ? 'OK' : 'ERROR';

		$this->output( $response );
	}

	function exact( $item_id = 0, $value = 0 )
	{
		$response = array(
			'status' => 'ERROR'
		);

		$item_info = $this->zitem->get( $item_id );

		if( !empty($item_info) )
		{
			$status = $this->virtualdevice->call( $item_id, 'exact', $value );
		}

		$response[ 'status' ] = $status ? 'OK' : 'ERROR';

		$this->output( $response );
	}
}
