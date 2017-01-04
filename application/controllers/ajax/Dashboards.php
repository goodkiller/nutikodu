<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboards extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	private function output( $data = array() ){

		header( 'Content-Type: application/json' );

		echo json_encode( $data );
	}

	function get_items( $dashboard_id = 1 )
	{
		$items_list = array();
		$dynamic_methods = array(
			'title' => 'get_item_title', 
			'body' => 'get_item_body'
		);

		foreach( $this->zdashboard->get_items( $dashboard_id ) as $i => $item )
		{
			$items_list[ $i ] = $item;

			if( !empty($item->classname) )
			{
				foreach( $dynamic_methods as $key => $method ){
					$items_list[ $i ]->$key = $this->virtualdevice->call( $item->item_id, $method );
				}
			}

			// Recheck methods
			foreach( $dynamic_methods as $key => $method ){
				if( !isset($items_list[ $i ]->$key) ){
					$items_list[ $i ]->$key = $this->virtualdevice->$method();
				}
			}
		}

		$this->output(array(
			'items' => $items_list
		));
	}
}
