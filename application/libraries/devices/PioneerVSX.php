<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PioneerVSX extends VirtualDevice
{
	function __construct(){
		parent::__construct();
	}

	function get_item_title(){
		
		return $this->item_info->title;
	}

	function get_item_body(){

		return '<i class="fa fa-chevron-up" aria-hidden="true"></i><div class="dbvalue">-40dB</div><i class="fa fa-chevron-down" aria-hidden="true"></i>';
	}

	function on()
	{
		$command = 'PO';
	}

	function off()
	{
		$command = 'PF';
	}

	function increase()
	{
		$command = 'VU';
	}

	function decrease()
	{
		$command = 'VD';
	}

	function mute()
	{
		$command = 'VD';
	}

	function unmute()
	{
		$command = 'MF';
	}

	private function send_command()
	{

	}

}