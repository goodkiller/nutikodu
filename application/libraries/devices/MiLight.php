 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MiLight extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}
	
	function get_item_title( $item_info = array() ){
		
		return $item_info->title;
	}

	function get_item_body( $item_info = array() ){

		$classes = array( __CLASS__, 'fa', 'fa-lightbulb-o' );

		return '<i class="' . implode(' ', $classes) . '" aria-hidden="true"></i>';
	}

	function get_item_options( $item_info = array() ){

		return array(
			'event' => 'toggle'
		);
	}

	public function on( $item_info = array() ){

		$this->CI->load->model( 'miapi' );

		$status = $this->CI->miapi->channel_on( 1 );

		if( $status ){
			$this->CI->zitem->set_value( $item_info->id, 1 );
		}
	}

	public function off( $item_info = array() ){

		$this->CI->load->model( 'miapi' );

		$status = $this->CI->miapi->channel_off( 1 );

		if( $status ){
			$this->CI->zitem->set_value( $item_info->id, 0 );
		}
	}

	function increase( $channel = NULL ){
		
		$this->CI->load->model( 'miapi' );
	}

	function decrease( $channel = NULL ){

		$this->CI->load->model( 'miapi' );

	}
}