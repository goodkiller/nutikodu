<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RemoteswitchTrigger extends Triggers
{
	function __construct(){
		parent::__construct();
	}

	function execute( $execution = array() )
	{
		// Check parameters
		if( isset($execution[ 'item_id' ]) && $execution[ 'item_id' ] > 0 && isset($execution[ 'command' ]) && !empty($execution[ 'command' ]) )
		{
			return $this->CI->virtualdevice->call( $execution[ 'item_id' ], $execution[ 'command' ] );
		}
		
		return FALSE;
	}
}