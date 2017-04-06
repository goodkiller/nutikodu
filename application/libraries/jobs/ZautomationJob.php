<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ZautomationJob extends Jobs
{
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
					log_message( 'debug', '[JOB] zautomation: Address "' . $device[ 'id' ] . '", item id: "' . $item_info->id . '"' );

					// Add history
					$this->CI->zitem->add_history( $item_info->id, $device[ 'metrics' ][ 'level' ],  $device[ 'metrics' ][ 'modificationTime' ] );
					
					// Set last run date
					if( isset($device[ 'updateTime' ])){
						$this->last_run_date = max( $this->job_info->last_run_date, $device[ 'updateTime' ] );
					}
				}
			}

			return TRUE;
		}

		return FALSE;
	}
}