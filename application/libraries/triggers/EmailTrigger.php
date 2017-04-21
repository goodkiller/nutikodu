<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class EmailTrigger extends Triggers
{
	function __construct(){
		parent::__construct();
	}

	function execute( $execution = array() )
	{
		if( isset($execution['email']) && !empty($execution['email']) )
		{
			return $this->send( $execution['email'], $execution['title'], $execution['message'] );
		}

		return FALSE;
	}

	private function send( $to = '', $title = '', $message = '' )
	{
		// Parameters can not be empty
		if( !empty($to) && !empty($title) && !empty($message) )
		{
			$this->CI->load->library( 'email' );

			log_message( 'debug', '[' . __CLASS__ . '] Send email to: ' . $to );

			$this->CI->email->from('alarm@nutikodu.info', 'Nutikodu');
			$this->CI->email->to( $to );
			$this->CI->email->subject( $title );
			$this->CI->email->message( $message );

			return $this->CI->email->send();
		}
		else
		{
			log_message( 'error', '[' . __CLASS__ . '] Unable to send email!' );
		}

		return FALSE;
	}
}