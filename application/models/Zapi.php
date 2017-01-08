<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zapi extends CI_Model
{
	protected $api_path = '/ZAutomation/api/v1/';

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get all devices
	 * @method  get_devices
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	function get_devices()
	{
		$this->load->library( 'curl' );

		$devices_list = array();

		$zdevices = $this->curl->simple_get( $this->get_url( 'devices' ) );
		$zdevices = json_decode( $zdevices, TRUE );

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

	/**
	 * Send command to ZEay API
	 * @method  send_command
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	function send_command( $device_id = '', $command = '', $query_params = array() )
	{
		$this->load->library( 'curl' );

		$url_segments = 'devices/' . $device_id . '/command/' . $command;

		if( !empty($query_params) ){
			$url_segments .= '?' . http_build_query($query_params);
		}

		$url = $this->get_url( $url_segments );

		log_message('debug', 'Sending request to ZWay API: "' . $url . '".');

		// Send command
		$response = $this->curl->simple_get( $url );

		log_message('debug', 'ZWay API response: "' . $response . '".');

		$response = json_decode( $response, TRUE );

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
				$this->db->query( 'INSERT INTO items (title, address, type, icon, unit, create_date, update_date, last_value) 
					VALUES( ?, ?, ?, ?, ?, TO_TIMESTAMP(?), TO_TIMESTAMP(?), ? ) 
					ON CONFLICT ON CONSTRAINT "UNIQ_ITM_ADDR" 
					DO UPDATE SET 
						title = ?, 
						address = ?, 
						type = ?, 
						icon = ?, 
						unit = ?, 
						create_date = TO_TIMESTAMP(?), 
						update_date = TO_TIMESTAMP(?), 
						last_value = ?', array(

					// Insert
					$zdev[ 'metrics' ][ 'title' ] ?? $zdev[ 'id' ],
					$zdev[ 'id' ], 
					$zdev[ 'deviceType' ], 
					$zdev[ 'metrics' ][ 'icon' ] ?? NULL,
					$zdev[ 'metrics' ][ 'scaleTitle' ] ?? NULL,
					$zdev[ 'creationTime' ],
					$zdev[ 'updateTime' ],
					$zdev[ 'metrics' ][ 'level' ] ?? NULL,

					// Update
					$zdev[ 'metrics' ][ 'title' ] ?? $zdev[ 'id' ],
					$zdev[ 'id' ], 
					$zdev[ 'deviceType' ], 
					$zdev[ 'metrics' ][ 'icon' ] ?? NULL,
					$zdev[ 'metrics' ][ 'scaleTitle' ] ?? NULL,
					$zdev[ 'creationTime' ], 
					$zdev[ 'updateTime' ], 
					$zdev[ 'metrics' ][ 'level' ] ?? NULL
				));
			}
		}
	}

	/**
	 * Get ZWay API url
	 * @method  get_url
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	private function get_url( $url_segments = '' )
	{
		$url = 'http://';
		$url .= $this->settings->get( 'zway_controller_user' ) . ':' . $this->settings->get( 'zway_controller_password' ) . '@';
		$url .= $this->settings->get( 'zway_controller_addr' ) . ':' . $this->settings->get( 'zway_controller_port' );
		$url .= '/' . $this->api_path;
		$url .= '/' . $url_segments;

		$url = preg_replace('/([^:])(\/{2,})/', '$1/', $url);

		return $url;
	}
}