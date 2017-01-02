<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Log extends CI_Log {

	public function __construct()
	{
		$config =& get_config();

		$this->_log_path = $config['log_path'];

		if (is_numeric($config['log_threshold']))
		{
			$this->_threshold = (int) $config['log_threshold'];
		}
		elseif (is_array($config['log_threshold']))
		{
			$this->_threshold = 0;
			$this->_threshold_array = array_flip($config['log_threshold']);
		}

		// Syslog
		if( $this->_log_path == 'syslog' )
		{
			openlog( $config['log_ident'], LOG_PID | LOG_PERROR, LOG_LOCAL0);
		}
		else
		{
			parent::__construct();
		}
	}

	public function __destruct(){
		closelog();
	}

	public function write_log( $level, $msg )
	{
		if( $this->_log_path == 'syslog' )
		{
			if ($this->_enabled === FALSE){
				return FALSE;
			}

			$level = strtoupper($level);

			if (( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
				&& ! isset($this->_threshold_array[$this->_levels[$level]]))
			{
				return FALSE;
			}
		
			syslog( $this->get_priority( $level ), $level . ' - ' . $msg );
			
			return 1;
		}
		else
		{
			return parent::write_log( $level, $msg );
		}
	}

	private function get_priority( $level = 'INFO' )
	{
		switch( $level )
		{
			case 'ERROR':
				return LOG_ERR;
			break;

			case 'DEBUG':
				return LOG_DEBUG;
			break;

			case 'INFO':
				return LOG_INFO;
			break;
			
			default:
				return LOG_NOTICE;
			break;
		}
	}
}