<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PioneerVSXJob extends Jobs
{
	protected $delay = 60;

	function __construct(){
		parent::__construct();
	}

	function run()
	{
		if( strtotime( $this->job_info->last_run_date ) < time() - $this->delay )
		{
			$vsx_items = $this->get_items();

			$commands_list = array();

			foreach( $vsx_items as $item )
			{
				list(, $address ) = explode( '.', $item->address );

				// Power status
				if( $address == 'PWR' ){
					$commands_list[ $item->id ] = '?P';
				}

				// Input status
				else if( $address == 'FN' ){
					$commands_list[ $item->id ] = '?F';
				}

				// Volume status
				else if( $address == 'VOL' ){
					$commands_list[ $item->id ] = '?V';
				}
			}

			if( $response = $this->CI->virtualdevice->call( $item->id, 'send', $commands_list ) )
			{
				foreach( $response as $item_id => $value )
				{
					// Add history
					$this->CI->zitem->add_history( $item_id, $value );
				}

				return TRUE;
			}

			return FALSE;
		}
		else
		{
			log_message( 'debug', '[JOB] PioneerVSX: WAITING ...' );
		}

		return FALSE;
	}

	private function get_items()
	{
		$this->CI->db->from( 'items' );
		$this->CI->db->where( 'classname', 'PioneerVSX' );
	
		$query = $this->CI->db->get();

		return $query->result();
	}
}