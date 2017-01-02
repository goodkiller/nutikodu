 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MiLight extends VirtualDevice
{
	function get_item_body( $item_info = array() ){

		$classes = array( __CLASS__, 'fa', 'fa-lightbulb-o' );

		return '<i class="' . implode(' ', $classes) . '" aria-hidden="true"></i>';
	}

	function get_item_options( $item_info = array() ){

		return array(
			'event' => 'click'
		);
	}

	public function listen()
	{

	}


	public function install()
	{
		// /opt/milight/
	}
}