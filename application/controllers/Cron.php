<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function jobs()
	{
		/*
		$cron_items = $this->zitem->get_cron_items();
		$updateable_items = array();

		foreach( $cron_items as $citem )
		{
			// Check updateable item
			if( $this->virtualdevice->call( $citem->id, 'force_check' ) ){
				$updateable_items[] = $citem->id;
			}
		}

		if( !empty($updateable_items) )
		{
			$this->db->set( 'last_check_date', 'NOW()', FALSE );
			$this->db->where_in( 'id', $updateable_items );
			$this->db->update( 'public.items' );
		}
		*/
	
		$this->load->library( 'Jobs' );

		$this->jobs->check_all();
	}
}
