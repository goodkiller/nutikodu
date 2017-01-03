<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BinarySensor extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_title( $item_info = array() ){
		
		return $item_info->title;
	}

	function get_item_body( $item_info = array() ){

		$classes = array( 'fa' );

		// Off
		if( $item_info->last_value == 0 )
		{
			$classes[] = 'fa-bell-slash-o';
		}

		// On
		else
		{
			$classes[] = 'fa-bell-o';
			$classes[] = 'text-danger';
		}

		return '<i class="' . implode( ' ', $classes ) . '" aria-hidden="true"></i>';
	}
}