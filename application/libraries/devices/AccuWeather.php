<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AccuWeather extends VirtualDevice
{
	protected $api_url = 'https://apidev.accuweather.com';

	function __construct(){
		parent::__construct();
	}

	function get_item_title( $item_info = array() ){
		
		return $item_info->title;
	}

	function get_item_body( $item_info = array() ){

		$info = $this->get_weather_info();

		return '<img src="' . $info[ 'icon' ] . '">' . $info[ 'value' ] . $info[ 'symbol' ];
	}

	private function get_weather_info()
	{
		$this->CI->load->library( 'curl' );

		$url = $this->api_url . '/currentconditions/v1/' . $this->CI->settings->get( 'accuweather_location_id' ) . '.json?language=en&apikey=' . $this->CI->settings->get( 'accuweather_api_key' );

		$response = $this->CI->curl->simple_get( $url );

		if( !empty($response) )
		{
			$response = json_decode( $response );
			$response = reset( $response );

			return array(
				'timestamp' => strtotime( $response->LocalObservationDateTime ),
				'value' => $response->Temperature->Metric->Value,
				'icon' => $this->api_url . '/developers/Media/Default/WeatherIcons/' . $response->WeatherIcon . '-s.png',
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