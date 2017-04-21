 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Triggers
{
	var $CI;

	function __construct()
	{
		$this->CI =& get_instance();
	}

	function load( $execution = array() )
	{
		$trigger_file = ucfirst( $execution[ 'type' ] ) . 'Trigger';

		// Trigger exists
		if( isset($execution[ 'type' ]) && file_exists( APPPATH . 'libraries/triggers/' . $trigger_file . '.php' ) )
		{
			// Load trigger
			$this->CI->load->library( 'triggers/' . $trigger_file );

			$class = strtolower( $trigger_file );
			$class = $this->CI->$class;

			// Check if sensor library is loaded
			if(class_exists($trigger_file))
			{
				// Check if method exists
				if(method_exists($class, 'execute'))
				{
					return $class->execute( $execution );
				}
			}
		}
		else
		{
			log_message( 'error', '[TRIGGER] Not found "' . $execution[ 'type' ] . '"!' );
		}
	}
}