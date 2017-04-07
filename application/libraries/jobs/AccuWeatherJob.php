<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AccuWeatherJob extends Jobs
{
	protected $api_url = 'https://apidev.accuweather.com';
	protected $delay = 300;

	function __construct(){
		parent::__construct();
	}

	function run()
	{
		if( strtotime( $this->job_info->last_run_date ) < time() - $this->delay )
		{
			// Get weather item
			$item_info = $this->get_weather_item();

			// Get weather info
			$weather_info = $this->get_weather_info();

			// Add history
			$this->CI->zitem->add_history( $item_info->id, $weather_info[ 'value' ],  $weather_info[ 'timestamp' ], $weather_info );

			return TRUE;
		}
		else
		{
			log_message( 'debug', '[JOB] AccuWeather: WAITING ...' );
		}

		return FALSE;
	}

	private function get_weather_item()
	{
		$this->CI->db->from( 'items' );
		$this->CI->db->where( 'classname', 'AccuWeather' );
	
		$query = $this->CI->db->get();

		return $query->row();
	}

	private function get_weather_info()
	{
		$this->CI->load->library( 'curl' );

		$url = $this->api_url . '/currentconditions/v1/' . $this->CI->settings->get( 'accuweather_location_id' ) . '.json?language=en&apikey=' . $this->CI->settings->get( 'accuweather_api_key' );

		log_message( 'debug', '[JOB] AccuWeather GET: ' . $url );

		$response = $this->CI->curl->simple_get( $url );

		if( !empty($response) )
		{
			$response = json_decode( $response );
			$response = reset( $response );

			return array(
				'timestamp' => strtotime( $response->LocalObservationDateTime ),
				'value' => $response->Temperature->Metric->Value,
				'icon' => $this->api_url . '/developers/Media/Default/WeatherIcons/' . str_pad($response->WeatherIcon, 2, 0, STR_PAD_LEFT) . '-s.png',
				'unit' => $response->Temperature->Metric->Unit,
				'symbol' => '&deg;'
			);
		}

		return array(
			'timestamp' => time(),
			'value' => -99,
			'icon' => $this->api_url . '/developers/Media/Default/WeatherIcons/01-s.png',
			'unit' => 'C',
			'symbol' => '&deg;'
		);
	}
}