<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RemoteSwitch extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_title(){
		
		return $this->item_info->title;
	}

	function get_item_body(){

		$vars = array(
			'item_info' => $this->item_info,
			'last_value_info' => $this->CI->zitem->get_last_value( $this->item_info->id )
		);

		return $this->CI->load->view( 'devices/' . __CLASS__ . '/item_body/switch', $vars, TRUE );
	}

	function on(){
		
		$status = $this->send_command( 1 );

		if( $status ){
			$this->CI->zitem->add_history( $this->item_info->id, 1 );
		}

		return $status;
	}

	function off(){
		
		$status = $this->send_command( 0 );

		if( $status ){
			$this->CI->zitem->add_history( $this->item_info->id, 0 );
		}

		return $status;
	}

	private function send_command( $command = 0 )
	{
		$this->CI->load->library( 'curl' );

		// Get switch settings
		$settings = $this->get_switch_settings();

		if( !empty($settings) )
		{
			$settings[ 'data' ] = json_decode(sprintf( $settings[ 'data' ], (int)$command ), TRUE);

			// Check if POST data exists
			if( is_array($settings[ 'data' ]) )
			{
				$ch = curl_init();

				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($settings[ 'data' ]));
				curl_setopt($ch, CURLOPT_URL, $settings[ 'url' ]);
				curl_setopt($ch, CURLOPT_HEADER, FALSE);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);

				$response = curl_exec($ch);

				curl_close($ch);

				$response = json_decode( $response, TRUE );

				if( !empty($response) && isset($response[ 'return_value' ]) ){
					return TRUE;
				}
			}
		}

		return FALSE;
	}

	private function get_switch_settings()
	{
		$key = trim(strtolower( $this->item_info->address ));
		$settings = array();

		$this->CI->db->from( 'settings' );
		$this->CI->db->like( 'key', $key, 'after' );
		
		$query = $this->CI->db->get();

		foreach( $query->result() as $row )
		{
			list(,$arg) = explode( '_', $row->key, 2);

			$settings[ $arg ] = $row->val;
		}

		return $settings;
	}
}