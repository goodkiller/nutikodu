 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MiLight extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_title(){
		
		return $this->item_info->title;
	}

	function get_item_body(){

		$classes = array( __CLASS__, 'fa', 'fa-lightbulb-o' );

		return '<i class="' . implode(' ', $classes) . '" aria-hidden="true"></i>';
	}

	function get_body( $event_type = 'click' ){

		$vars = array(
			'item_info' => $this->item_info
		);

		return $this->CI->load->view( 'devices/' . __CLASS__ . '/body/' . $event_type, $vars, TRUE );
	}

	public function on(){

		$this->CI->load->model( 'miapi' );

		$status = $this->CI->miapi->channel_on( 1 );

		if( $status ){
			$this->CI->zitem->set_value( $this->item_info->id, 1 );
		}

		return $status;
	}

	public function off(){

		$this->CI->load->model( 'miapi' );

		$status = $this->CI->miapi->channel_off( 1 );

		if( $status ){
			$this->CI->zitem->set_value( $this->item_info->id, 0 );
		}

		return $status;
	}

	function increase( $channel = NULL ){
		
		$this->CI->load->model( 'miapi' );
	}

	function decrease( $channel = NULL ){

		$this->CI->load->model( 'miapi' );

	}
}