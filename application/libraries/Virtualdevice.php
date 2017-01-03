 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VirtualDevice
{
	var $CI;
	
	function __construct(){
		$this->CI =& get_instance();
	}

	function get_item_title( $item_info = array() ){
		return '???';
	}

	function get_item_body( $item_info = array() ){
		return '<i class="fa fa-question" aria-hidden="true"></i>';
	}

	function get_item_options( $item_info = array() ){
		return array();
	}

	function get_item_toggle_content( $item_info = array() )
	{
		
	}

	function get_item_settings_content( $item_info = array() )
	{
		
	}

	function on( $item_info = array() ){
		return FALSE;
	}

	function off( $item_info = array() ){
		return FALSE;
	}

	function min( $item_info = array() ){
		return FALSE;
	}

	function max( $item_info = array() ){
		return FALSE;
	}

	function increase( $item_info = array() ){
		return FALSE;
	}

	function decrease( $item_info = array() ){
		return FALSE;
	}

	function update( $item_info = array() ){
		return FALSE;
	}

	function exact( $item_info = array() ){
		return FALSE;
	}




	function call( $item_id = 0, $func = '', $params = array() )
	{
		// Get item info
		$item_info = $this->CI->zitem->get( $item_id );

		if( !empty($item_info) )
		{
			// Load library
			$this->CI->load->library( 'devices/' . $item_info->classname, $item_info->classname );

			$user_func_params = array( $item_info );
			$user_func_params = array_merge( $user_func_params, $params );

			// Check if sensor library is loaded
			if(class_exists($item_info->classname))
			{
				// Check if method exists
				if(method_exists($item_info->classname, $func))
				{
					return call_user_func_array( array(new $item_info->classname, $func), $user_func_params );
				}
			}
		}


		return FALSE;
	}

}