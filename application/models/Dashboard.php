<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_items_by_items( $items = array() )
	{
		$this->db->where_in('di.item_id', $items);

		return $this->get_items();
	}

	function get_items_by_dashboard( $dashboard_id = 0 )
	{
		$this->db->where('di.dashboard_id', $dashboard_id);

		return $this->get_items();
	}

	function get_items()
	{
		$items_list = array();

		$this->db->select('di.bg_color, di.event_type, di.display_type');
		$this->db->select('di.id AS did', FALSE);
		$this->db->select("di.width || 'x' || di.height AS size", FALSE);
		$this->db->select('i.id, i.classname');
		$this->db->from('dashboard_items di');
		$this->db->join('items i', 'i.id = di.item_id');
		$this->db->order_by('i.create_date', 'ASC');

		$query = $this->db->get();

		$items_list = $query->result();

		return $items_list;
	}
}