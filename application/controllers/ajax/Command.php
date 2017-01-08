<?php
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(5);

class Command extends CI_Controller {

	protected $response = array(
		'status' => 'ERROR'
	);

	function __construct()
	{
		parent::__construct();
	}

	function __destruct(){

		header( 'Content-Type: application/json' );

		echo json_encode( $this->response );
	}

	function send()
	{
		$item_id = (int)$this->input->post( 'item_id' );
		$command = $this->input->post( 'command' );

		if( $item_id > 0 && !empty($command) )
		{
			$item_info = $this->zitem->get( $item_id );

			if( !empty($item_info) )
			{
				$this->response = $this->virtualdevice->call( $item_id, $command );
			}
		}
	}
}
