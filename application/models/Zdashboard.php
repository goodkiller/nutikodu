<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zdashboard extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_items( $dashboard_id = 0 )
	{
		$items_list = array();

		$this->db->select('di.item_id, di.bg_color, di.event_type');
		$this->db->select("di.width || 'x' || di.height AS size", FALSE);
		$this->db->select('i.classname');
		$this->db->from('dashboard_items di');
		$this->db->join('items i', 'i.id = di.item_id');
		$this->db->where('di.dashboard_id', $dashboard_id);
		$this->db->order_by('i.create_date', 'ASC');

		$query = $this->db->get();

		$items_list = $query->result();

		return $items_list;
	}
}