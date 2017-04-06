 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jobs
{
	var $CI;
	var $last_run_date = 0;

	protected $job_info = array();
	
	function __construct()
	{
		$this->CI =& get_instance();

		// Set last run date as now
		$this->last_run_date = time();
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
					// Job run was successful
					if( $class->set_job_info( $job_info )->run() )
					{
						// Update last run date
						if( $this->last_run_date > 0)
						{
							$this->CI->db->set( 'last_run_date', 'TO_TIMESTAMP(' . $this->last_run_date . ')', FALSE );
							$this->CI->db->where( 'name', $job_info->name );
							$this->CI->db->update( 'jobs' );

							return TRUE;
						}
					}
				}
			}

			log_message( 'debug', '[JOB] End "' . $job_info->name . '"' );
		}

		return FALSE;
	}
}