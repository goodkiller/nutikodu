<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PioneerVSX extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_title(){
		
		return $this->item_info->title;
	}

	function get_item_body(){

		$item_content = '<i class="fa fa-chevron-up" aria-hidden="true" data-id="' . $this->item_info->id . '" data-command="increase"></i>';
		$item_content .= '<div class="dbvalue">-40dB</div>';
		$item_content .= '<i class="fa fa-chevron-down" aria-hidden="true" data-id="' . $this->item_info->id . '" data-command="decrease"></i>';

		return $item_content;
	}

	function on()
	{
		$command = 'PO';
	}

	function off()
	{
		$command = 'PF';
	}

	function increase()
	{
		$command = 'VU';

		$response = $this->send_command( $command );

		print_r($response);
	}

	function decrease()
	{
		$command = 'VD';

		$response = $this->send_command( $command );

		print_R($response);
	}

	function mute()
	{
		$command = 'MO';
	}

	function unmute()
	{
		$command = 'MF';
	}

	function get_power()
	{
		$command = '?P';

		$response = $this->send_command( $command );

		print_R($response);
	}

	function get_input()
	{
		$command = '?F';

		$response = $this->send_command( $command );

		print_R($response);
	}

	private function send_command( $command = '' )
	{
		$response = NULL;

		$fp = @fsockopen( $this->CI->settings->get( 'pioneer_vsx_hostname' ), 23 );
	
		if( $fp )
		{
			fputs($fp, $command . "\r\n");

			$response = fgets($fp);

			fclose($fp);
		}
		
		return $this->parse_response( $response );
	}

	private function parse_response( $response = NULL ){

		$response = trim( $response );
		$response = strtoupper( $response );

		// Volume parse
		if( preg_match( '/^VOL([0-9]{3})$/', $response, $out ) ){
			return $out[1];
		}

		// Power parse
		if( preg_match( '/^PWR([0-9]{1})$/', $response, $out ) ){
			return $out[1];
		}

		return $response;
	}

	/**
	 * Crontab
	 * @method  crontab
	 * @author  Marko Praakli
	 * @date    2017-01-08
	 */
	function crontab(){

	}
}