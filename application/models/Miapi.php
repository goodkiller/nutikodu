<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MiApi extends CI_Model
{
	protected $cmd_send = 'send_cmd "%02X %02X %02X %02X %02X %02X %02X"';
	protected $cmd_listen = 'openmilight';
	protected $retry_times = 2;

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Switch master ON
	 * @method  master_on
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	function master_on(){

		return $this->send_command( 0x01 );
	}

	/**
	 * Switch master OFF
	 * @method  master_off
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	function master_off(){

		return $this->send_command( 0x02 );
	}

	/**
	 * Switch channel ON
	 * @method  channel_on
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	function channel_on( $channel = 1 ){

		if( $channel = 1 ){
			$byte = 0x03;
		}
		elseif( $channel = 2 ){
			$byte = 0x05;
		}
		elseif( $channel = 3 ){
			$byte = 0x07;
		}
		elseif( $channel = 4 ){
			$byte = 0x09;
		}

		return $this->send_command( $byte );
	}

	/**
	 * Switch channel OFF
	 * @method  channel_off
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	function channel_off( $channel = 1 ){

		if( $channel = 1 ){
			$byte = 0x04;
		}
		elseif( $channel = 2 ){
			$byte = 0x06;
		}
		elseif( $channel = 3 ){
			$byte = 0x08;
		}
		elseif( $channel = 4 ){
			$byte = 0x0A;
		}

		return $this->send_command( $byte );
	}

	/**
	 * Send commant to RF
	 * @method  send_command
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	private function send_command( $byte = 0x00, $color = 0x6B, $brightness = 0x39 )
	{
		// Get gateway
		$bytes = $this->get_gateway();

		// Color slider
		array_push($bytes, $color );

		// Brightness slider
		array_push($bytes, $brightness );

		// Add command byte
		array_push($bytes, $byte);

		// Get random byte to the end
		array_push($bytes, $this->random_byte());

		// Command
		$exec_command = 'sudo ' . $this->settings->get( 'milight_file_path' ) . vsprintf($this->cmd_send, $bytes);

		// Localhost "same server"
		if( in_array( $this->settings->get( 'milight_ssh_host' ), array( 'localhost', '127.0.0.1' ) ) )
		{
			return $this->send_commant_via_unix( $exec_command );
		}
		else
		{
			return $this->send_commant_via_ssh( $exec_command );
		}
	}

	/**
	 * Send command via SSH
	 * @method  send_commant_via_ssh
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	private function send_commant_via_ssh( $command = 'pwd' )
	{
		// Connection
		$sh = ssh2_connect( $this->settings->get( 'milight_ssh_host' ), $this->settings->get( 'milight_ssh_port' ) );

		if( $sh )
		{
			log_message('debug', 'Connecting to MiLight server "' . $this->settings->get( 'milight_ssh_host' ) . ':' . $this->settings->get( 'milight_ssh_port' ) . '".');

			// Authentication
			ssh2_auth_password( $sh, $this->settings->get( 'milight_ssh_user' ), $this->settings->get( 'milight_ssh_password' ) );
			
			// Retry commands just in case
			for( $i = 0; $i <= $this->retry_times; $i++ ){

				// Execute command
				ssh2_exec( $sh, $command );

				// Sleep 500ms
				usleep(500000);
			}

			log_message('debug', 'Command "' . $command . '" was sent to MiLight server.');

			// Send exit commamd
			ssh2_exec($sh, 'exit');

			// Clean
			unset($sh);

			log_message('debug', 'MiLight server connection ended.');

			return TRUE;
		}
		else
		{
			log_message('error', 'Unable to connect MiLight server "' . $this->settings->get( 'milight_ssh_host' ) . ':' . $this->settings->get( 'milight_ssh_port' ) . '".');
		}

		return FALSE;
	}

	/**
	 * Send command via UNIX
	 * @method  send_commant_via_unix
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	private function send_commant_via_unix( $command = 'pwd' )
	{
		

		return FALSE;
	}

	/**
	 * Generate random byte
	 * @method  random_byte
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	private function random_byte(){

		$length = 1;

		return strtoupper(bin2hex(openssl_random_pseudo_bytes( $length )));
	}

	/**
	 * Get gateway
	 * @method  get_gateway
	 * @author  Marko Praakli
	 * @date    2017-01-03
	 */
	private function get_gateway(){

		$gateway = array( 0xB0, 0xF9, 0x4C );

		return $gateway;
	}
}