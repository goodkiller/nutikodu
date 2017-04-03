 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jobs
{
	var $CI;
	protected $job_info = array();
	
	function __construct(){
		$this->CI =& get_instance();
	}

	function run(){

	}

	protected function set_job_info( $job_info = array() )
	{
		$this->job_info = $job_info;

		return $this;
	}

	function check_all()
	{
		// Get jobs
		$jobs_list = $this->CI->db->get_where( 'jobs', array( 'enabled' => TRUE ) )->result();

		foreach( $jobs_list as $job_info )
		{
			log_message( 'debug', '[JOB] Start "' . $job_info->name . '"' );

			// Load library
			$this->CI->load->library( 'jobs/' . $job_info->classname );

			$class = strtolower($job_info->classname);
			$class = $this->CI->$class;

			// Check if sensor library is loaded
			if(class_exists($job_info->classname))
			{
				// Check if method exists
				if(method_exists($class, 'run'))
				{
					return $class->set_job_info( $job_info )->run();
				}
			}

			log_message( 'debug', '[JOB] End "' . $job_info->name . '"' );
		}

		return FALSE;
	}
}