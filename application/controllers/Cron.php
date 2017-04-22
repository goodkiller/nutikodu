<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	/**
	 * Run cron
	 * @method  run
	 * @author  Marko Praakli
	 * @date    2017-04-22
	 */
	public function run()
	{
		$end_message = '[CRON] End cron: ';

		log_message( 'debug', '[CRON] Start cron.' );

		$this->db->trans_begin();
	
		// Check status
		if( $this->get_status() === 0 )
		{
			// Lock cron
			$this->lock();

				// Run jobs
				$this->jobs();

				// Run rules
				$this->rules();

			// Unclock cron
			$this->unlock();
		}
		else
		{
			$end_message .= 'LOCKED, ';
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			$end_message .= 'ERROR, rollback!';
		}
		else
		{
			$this->db->trans_commit();

			$end_message .= 'OK';
		}

		log_message( 'debug', $end_message );
	}

	/**
	 * Check all jobs
	 * @method  jobs
	 * @author  Marko Praakli
	 * @date    2017-04-22
	 */
	private function jobs()
	{
		$this->load->library( 'Jobs' );

		return $this->jobs->check_all();
	}

	/**
	 * Check all rules
	 * @method  rules
	 * @author  Marko Praakli
	 * @date    2017-04-22
	 */
	private function rules()
	{
		$this->load->model( 'rules' );

		return $this->rules->check_all();
	}

	/**
	 * Get cron status
	 * @method  get_status
	 * @author  Marko Praakli
	 * @date    2017-04-22
	 */
	private function get_status()
	{
		$lock_status = (int)$this->db->get_where( 'settings', array( 'key' => 'cron_lock' ) )->row( 'val' );

		return $lock_status;
	}

	/**
	 * Lock cron
	 * @method  lock
	 * @author  Marko Praakli
	 * @date    2017-04-22
	 */
	private function lock()
	{
		// Add lock
		$this->db->set( 'val', 1 );
		$this->db->where( 'key', 'cron_lock' );
		$this->db->update( 'settings' );

		log_message( 'debug', '[CRON] Lock cron.' );

		return TRUE;
	}

	/**
	 * Unlock cron
	 * @method  unlock
	 * @author  Marko Praakli
	 * @date    2017-04-22
	 */
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
