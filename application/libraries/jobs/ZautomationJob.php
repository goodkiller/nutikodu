<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ZautomationJob extends Jobs
{
	protected $last_run_date = 0;

	function __construct(){
		parent::__construct();
	}

	function run()
	{
		$since = strtotime( $this->job_info->last_run_date ) + 1;
		$devices = $this->CI->zapi->get_devices( $since );

		if(!empty( $devices ))
		{
			foreach( $devices as $device )
			{
				// Get device info
				$item_info = $this->CI->zitem->get_by_address( $device[ 'id' ] );

				// Device info exists
				if( !empty($item_info) )
				{
					log_message( 'debug', '[JOB] Zautomation: Address "' . $device[ 'id' ] . '", item id: "' . $item_info->id . '"' );

					// Add history
					$this->CI->zitem->add_history( $item_info->id, $device[ 'metrics' ][ 'level' ],  $device[ 'metrics' ][ 'modificationTime' ] );
					
					// Set last run date
					if( isset($device[ 'updateTime' ])){
						$this->last_run_date = max( $this->job_info->last_run_date, $device[ 'updateTime' ] );
					}
				}
			}

			if( $this->last_run_date > 0)
			{
				$this->CI->db->set( 'last_run_date', 'TO_TIMESTAMP(' . $this->last_run_date . ')', FALSE );
				$this->CI->db->where( 'name', $this->job_info->name );
				$this->CI->db->update( 'jobs' );
			}

			return TRUE;
		}

		return FALSE;
	}
}