<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function jobs()
	{
		log_message( 'debug', '[CRON] Start cron.' );
	
		// Check status
		if( $this->get_status() === 0 )
		{
			// Lock cron
			$this->lock();

			$this->load->library( 'Jobs' );

			$this->jobs->check_all();

			// Unclock cron
			$this->unlock();

			log_message( 'debug', '[CRON] End cron.' );
		}
		else
		{
			log_message( 'debug', '[CRON] End cron: locked!' );
		}
	}

	public function rules()
	{
		$this->load->model( 'rules' );

		$this->rules->check_all();
	}

	private function get_status()
	{
		$lock_status = (int)$this->db->get_where( 'settings', array( 'key' => 'cron_lock' ) )->row( 'val' );

		return $lock_status;
	}

	private function lock()
	{
		// Add lock
		$this->db->set( 'val', 1 );
		$this->db->where( 'key', 'cron_lock' );
		$this->db->update( 'settings' );

		log_message( 'debug', '[CRON] Lock cron.' );

		return TRUE;
	}

	private function unlock()
	{
		// Remove lock
		$this->db->set( 'val', 0 );
		$this->db->where( 'key', 'cron_lock' );
		$this->db->update( 'settings' );

		// Update last run date
		$this->db->set( 'val', 'NOW()', FALSE );
		$this->db->where( 'key', 'cron_last_run' );
		$this->db->update( 'settings' );

		log_message( 'debug', '[CRON] Unlock cron.' );

		return TRUE;
	}
}
