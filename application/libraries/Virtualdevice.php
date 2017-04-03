 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VirtualDevice
{
	var $CI;
	protected $item_info = array();
	
	function __construct(){
		$this->CI =& get_instance();
	}

	function get_item_title(){
		return '???';
	}

	function get_item_body(){
		return '<i class="fa fa-question" aria-hidden="true"></i>';
	}

	function get_item_options(){
		return array();
	}

	function get_item_toggle_content()
	{
		
	}

	function get_item_settings_content()
	{
		
	}

	function on(){
		return FALSE;
	}

	function off(){
		return FALSE;
	}

	function min(){
		return FALSE;
	}

	function max(){
		return FALSE;
	}

	function increase(){
		return FALSE;
	}

	function decrease(){
		return FALSE;
	}

	function update(){
		return FALSE;
	}

	function exact( $level = 0 ){
		return FALSE;
	}


	/**
	 * Set intem info
	 * @method  set_item_info
	 * @author  Marko Praakli
	 * @date    2017-01-04
	 */
	protected function set_item_info( $item_info = array() )
	{
		$this->item_info = $item_info;

		return $this;
	}

	/**
	 * Call a device library method
	 * @method  call
	 * @author  Marko Praakli
	 * @date    2017-01-04
	 */
	function call( $item_id = 0, $call = '', $params = array() )
	{
		// Get item info
		$item_info = $this->CI->zitem->get( $item_id );

		if( !empty($item_info) )
		{
			if( !empty($item_info->classname) )
			{
				// Load library
				$this->CI->load->library( 'devices/' . $item_info->classname );

				$class = strtolower($item_info->classname);
				$class = $this->CI->$class;

				// Check if sensor library is loaded
				if(class_exists($item_info->classname))
				{
					// Check if method exists
					if(method_exists($class, $call))
					{
						return $class->set_item_info( $item_info )->$call( $params );
					}
				}
			}
		}

		return FALSE;
	}

	function force_check(){
		return FALSE;
	}
}