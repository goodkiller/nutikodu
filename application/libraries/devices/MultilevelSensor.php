<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MultilevelSensor extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_title(){
		
		return $this->item_info->title;
	}

	function get_item_body(){

		return $this->item_info->last_value . $this->item_info->unit;
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