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

		$connection = ssh2_connect('192.168.1.201', 22);
		ssh2_auth_password($connection, 'pi', 'raspberry');

	
		$stdout = ssh2_exec($connection, 'sudo /opt/milight/openmilight');
		$stderr = ssh2_fetch_stream($stdout, SSH2_STREAM_STDERR);
		if (!empty($stdout)) {

		$t0 = time();
		$err_buf = null;
		$out_buf = null;

		// Try for 30s
		do {

		$err_buf.= fread($stderr, 4096);
		$out_buf.= fread($stdout, 4096);

		$done = 0;
		if (feof($stderr)) {
		    $done++;
		}
		if (feof($stdout)) {
		    $done++;
		}

		$t1 = time();
		$span = $t1 - $t0;

		// Info note
		echo "while (($span < 10) && ($done < 2));\n";

		// Wait here so we don't hammer in this loop
		sleep(1);

		} while (($span < 10) && ($done < 2));

		echo "STDERR:\n$err_buf\n";
		echo "STDOUT:\n$out_buf\n";

		echo "Done\n";

		} else {
		echo "Failed to Shell\n";
		}



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

	}

	function toggle( $item_id = 0 )
	{
		$status = NULL;
		$item_info = $this->zitem->get( $item_id );

		if( !empty($item_info) )
		{
			// Last value was off
			if( $item_info->last_value == 0 )
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
		}

		return $status;
	}
}
