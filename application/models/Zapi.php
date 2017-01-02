<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zapi extends CI_Model
{
	protected $api_path = '/ZAutomation/api/v1/';

	function __construct()
	{
		parent::__construct();
	}

	private function get_url( $url_segments = '' )
	{
		$url = 'http://admin:mypassword@192.168.1.201:8083/' . $this->api_path . '/' . $url_segments;

		$url = preg_replace('/([^:])(\/{2,})/', '$1/', $url);

		return $url;
	}

	private function _curl_get( $url_segments = '' )
	{
		$ch = curl_init();  
 
		curl_setopt( $ch, CURLOPT_URL, $this->get_url( $url_segments ) );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
	 
		$output = curl_exec($ch);
	 
		curl_close($ch);

		$output = json_decode( $output, TRUE );

		return $output;
	}

	function get_devices()
	{
		$devices_list = array();

		$zdevices = $this->_curl_get( 'devices' );

		if( !empty($zdevices[ 'data' ][ 'devices' ]) ){
			foreach( $zdevices[ 'data' ][ 'devices' ] as $i => $zdev )
			{
				$devices_list[ $i ] = $zdev;

				// Not numeric value
				if( !is_numeric($zdev[ 'metrics' ][ 'level' ]) )
				{
					if( $zdev[ 'metrics' ][ 'level' ] == 'on' ){
						$devices_list[ $i ][ 'metrics' ][ 'level' ] = 1;
					}
					else if( $zdev[ 'metrics' ][ 'level' ] == 'off' ){
						$devices_list[ $i ][ 'metrics' ][ 'level' ] = 0;
					}
				}
			}
		}

		return $devices_list;
	}

	function send_command( $device_id = '', $command = '', $query_params = array() )
	{
		$url = 'devices/' . $device_id . '/command/' . $command;

		if( !empty($query_params) ){
			$url .= '?' . http_build_query($query_params);
		}

		// Send command
		$response = $this->_curl_get( $url );

		if( $response[ 'code' ] == 200 ){
			return TRUE;
		}

		return FALSE;
	}

	function discover()
	{
		$zdevices = $this->get_devices();

		 if( !empty($zdevices) )
		{
			foreach( $zdevices as $zdev )
			{
				$this->db->query( 'INSERT INTO items (title, address, type, icon, create_date, update_date, last_value) 
					VALUES( ?, ?, ?, ?, TO_TIMESTAMP(?), TO_TIMESTAMP(?), ? ) 
					ON CONFLICT ON CONSTRAINT "UNIQ_ITM_ADDR" 
					DO UPDATE SET 
						title = ?, 
						address = ?, 
						type = ?, 
						icon = ?, 
						create_date = TO_TIMESTAMP(?), 
						update_date = TO_TIMESTAMP(?), 
						last_value = ?', array(

					// Insert
					$zdev[ 'metrics' ][ 'title' ] ?? $zdev[ 'id' ],
					$zdev[ 'id' ], 
					$zdev[ 'deviceType' ], 
					$zdev[ 'metrics' ][ 'icon' ] ?? NULL,
					$zdev[ 'creationTime' ],
					$zdev[ 'updateTime' ],
					$zdev[ 'metrics' ][ 'level' ] ?? NULL,

					// Update
					$zdev[ 'metrics' ][ 'title' ] ?? $zdev[ 'id' ],
					$zdev[ 'id' ], 
					$zdev[ 'deviceType' ], 
					$zdev[ 'metrics' ][ 'icon' ] ?? NULL,
					$zdev[ 'creationTime' ], 
					$zdev[ 'updateTime' ], 
					$zdev[ 'metrics' ][ 'level' ] ?? NULL
				));
			}
		}
	}
}