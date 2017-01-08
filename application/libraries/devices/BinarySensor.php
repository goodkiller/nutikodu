<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BinarySensor extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_title(){
		
		return $this->item_info->title;
	}

	function get_item_body(){

		$classes = array( 'fa' );

		// Off
		if( $this->item_info->last_value == 0 )
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

	/**
	 * Crontab
	 * @method  crontab
	 * @author  Marko Praakli
	 * @date    2017-01-08
	 */
	function crontab(){

	}
}