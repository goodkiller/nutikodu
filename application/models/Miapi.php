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

	private function random_byte()
	{
		$length = 1;

		return strtoupper(bin2hex(openssl_random_pseudo_bytes( $length )));
	}

	private function get_gateway(){

		$gateway = array( 0xB0, 0xF9, 0x4C );

		return $gateway;
	}

	function send_command( $byte = 0 )
	{
		// Get gateway
		$bytes = $this->get_gateway();

		// Color slider
		array_push($bytes, 0x6B );

		// Brightness slider
		array_push($bytes, 0x39 );

		// Add command byte
		array_push($bytes, $byte);

		// Get random byte to the end
		array_push($bytes, $this->random_byte());

		// Command
		$exec_command = 'sudo ' . $this->settings->get( 'milight_file_path' ) . vsprintf($this->cmd_send, $bytes);

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
				ssh2_exec( $sh, $exec_command );

				// Sleep 500ms
				usleep(500000);
			}

			log_message('debug', 'Command "' . $exec_command . '" was sent to MiLight server.');

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
}