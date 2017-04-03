<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	private function output( $data = array() ){

		header( 'Content-Type: application/json' );

		echo json_encode( $data );
	}

	function items()
	{
		$items = $this->input->post( 'items' );

		$items_list = array();
		foreach( $this->dashboard->get_items_by_items( $items ) as $i => $item )
		{
			$items_list[ $i ] = $item;
			$items_list[ $i ]->body = $this->virtualdevice->call( $item->id, 'get_item_body', $item );
		}

		$this->output(array(
			'items' => $items_list,
			'status' => 'OK'
		));
	}
}
