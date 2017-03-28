<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WebCam extends VirtualDevice
{
	protected $api_url = 'https://apidev.accuweather.com';

	function __construct(){
		parent::__construct();
	}

	function get_item_title(){
		
		return $this->item_info->title;
	}

	function get_item_body()
	{
		return '<video controls preload>
  <source src="192.168.1.10:8080/ees.mp4" type=\'video/mp4; codecs="avc1.42E01E, mp4a.40.2"\'>
</video>';
	}
}