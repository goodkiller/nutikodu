<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PioneerVSX extends VirtualDevice
{
	protected $timeout = 3;
	protected $port = 23;

	function __construct(){
		parent::__construct();
	}

	function get_item_title(){
		
		return $this->item_info->title;
	}

	function get_item_body(){

		$last_value_info = $this->CI->zitem->get_last_value( $this->item_info->id );

		$item_content = '<i class="fa fa-chevron-up" aria-hidden="true" data-id="' . $this->item_info->id . '" data-command="increase"></i>';
		$item_content .= '<div class="dbvalue">' . $this->get_db( $last_value_info->value ) . $this->item_info->unit . '</div>';
		$item_content .= '<i class="fa fa-chevron-down" aria-hidden="true" data-id="' . $this->item_info->id . '" data-command="decrease"></i>';

		return $item_content;
	}

	/**
	 * Power on
	 * @method  on
	 * @author  Marko Praakli
	 * @date    2017-04-07
	 */
	function on()
	{
		return $this->send_command( array( 'PO' ) );
	}

	/**
	 * Power off
	 * @method  off
	 * @author  Marko Praakli
	 * @date    2017-04-07
	 */
	function off()
	{
		return $this->send_command( array( 'PF' ) );
	}

	/**
	 * Volume UP
	 * @method  increase
	 * @author  Marko Praakli
	 * @date    2017-04-07
	 */
	function increase()
	{
		return $this->send_command( array( 'VU' ) );
	}

	/**
	 * Volume DOWN
	 * @method  decrease
	 * @author  Marko Praakli
	 * @date    2017-04-07
	 */
	function decrease()
	{
		return $this->send_command( array( 'VD' ) );
	}

	/**
	 * Mute
	 * @method  mute
	 * @author  Marko Praakli
	 * @date    2017-04-07
	 */
	function mute()
	{
		return $this->send_command( array( 'MO' ) );
	}

	/**
	 * Unmute
	 * @method  unmute
	 * @author  Marko Praakli
	 * @date    2017-04-07
	 */
	function unmute()
	{
		return $this->send_command( array( 'MF' ) );
	}

	/**
	 * Send commands
	 * @method  send
	 * @author  Marko Praakli
	 * @date    2017-04-07
	 */
	function send( $commands = array() )
	{
		if( !empty($commands) )
		{
			return $this->send_command( $commands );
		}		

		return FALSE;
	}

	/**
	 * Send command to Pioneer VSX via Telnet
	 * @method  send_command
	 * @author  Marko Praakli
	 * @date    2017-04-07
	 */
	private function send_command( $commands = array() )
	{
		$responses = array();

		log_message( 'debug', '[PioneerVSX] Connect to : ' . $this->CI->settings->get( 'pioneer_vsx_hostname' ) . ':'. $this->port );
		
		if ( !$fp = @fsockopen( $this->CI->settings->get( 'pioneer_vsx_hostname' ), $this->port, $errno, $errstr, $this->timeout) ) 
        {
        	log_message( 'debug', '[PioneerVSX] Connection failed.' );

            return $this->parse_response( $responses );
        }
        else
        {
        	log_message( 'debug', '[PioneerVSX] Connected.' );

            if( $fp )
			{
				foreach( $commands as $item_id => $cmd )
				{
					log_message( 'debug', '[PioneerVSX] Send command: ' . $cmd . ', Item ID: ' . $item_id );

					fputs($fp, $cmd . "\r\n");

					$responses[ $item_id ] = fgets($fp);
				}

				fclose($fp);
			}

			return $this->parse_response( $responses );
        }
	}

	/**
	 * Parse Pioneer VSX command resposnes
	 * @method  parse_response
	 * @author  Marko Praakli
	 * @date    2017-04-07
	 */
	private function parse_response( $responses = array() )
	{
		$parsed_responses = array();

		// Fetch all commands
		foreach( $responses as $item_id => $resp )
		{
			$resp = trim( $resp );
			$resp = strtoupper( $resp );

			log_message( 'debug', '[PioneerVSX] Command response: ' . $resp . ', Item ID: ' . $item_id );

			// Volume parse 
			// VOL000 - VOL131
			if( preg_match( '/^VOL([0-9]{3})$/', $resp, $out ) ){
				$parsed_responses[ $item_id ] = (int)$out[1];
			}

			// Power parse
			// PWR0 - power on
			// PWR2 - stand by
			if( preg_match( '/^PWR([0-9]{1})$/', $resp, $out ) ){
				$parsed_responses[ $item_id ] = (int)$out[1];
			}

			// Input parse
			// 00FN 	PHONO
			// 01FN 	CD
			// 03FN 	CD-R/TAPE
			// 04FN 	DVD
			// 05FN 	TV/SAT
			// 10FN 	VIDEO 1
			// 14FN 	VIDEO 2
			// 15FN 	DVR/BDR
			// 17FN 	iPod/USB
			// 19FN 	HDMI1
			// 20FN 	HDMI2
			// 21FN 	HDMI3
			// 22FN 	HDMI4
			// 23FN 	HDMI5
			// 24FN 	HDMI6
			// 25FN 	BD
			// 38FN 	NETRADIO
			if( preg_match( '/^FN([0-9]{2})$/', $resp, $out ) ){
				$parsed_responses[ $item_id ] = (int)$out[1];
			}
		}

		return $parsed_responses;
	}

	private function get_db( $volume = 0 )
	{
		$fromRange = 131 - 0;
		$toRange = 12 - -65;
		$scaleFactor = $toRange / $fromRange;

		$tmpValue = $volume - 0;
		$tmpValue *= $scaleFactor;

		return round( $tmpValue + -65 );
	}
}