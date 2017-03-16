<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			if(empty($_POST)){
				$_POST = (array)json_decode(file_get_contents('php://input'));
			}
		}
	}

	public function add_history( $item_address = '' )
	{
		$history_values = $this->input->post() ?? array();
		$item_info = $this->zitem->get_by_address( $item_address );

		// Empty values
		if( empty($history_values) )
		{
			echo 'ERROR';
		}
		else
		{
			// Item not exists
			if( empty($item_info) ){

				// Add new item
				$this->db->set( 'title', $item_address );
				$this->db->set( 'address', $item_address );
				$this->db->insert( 'items' );

				$item_info = (object)array(
					'id' => $this->db->insert_id(),
					'title' => $item_address,
					'address' => $item_address
				);
			}

			// Item ID exists
			if( $item_info->id > 0 )
			{
				foreach( $history_values as $row )
				{
					list($time, $value) = $row;

					$this->zitem->add_history( $item_info->id, $value );
				}
			}

			echo 'OK';
		}
	}
}
