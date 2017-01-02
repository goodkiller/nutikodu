<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zitem extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_all()
	{
		$items_list = array();

		$this->db->from('items');

		$query = $this->db->get();

		$items_list = $query->result();

		return $items_list;
	}

	function get( $item_id = 0 )
	{
		$this->db->from('items');
		$this->db->where('id', $item_id);
	
		$query = $this->db->get();

		return $query->row();
	}

	function set_value( $item_id = 0, $value = 0 )
	{
		$this->db->set('last_value', $value);
		$this->db->where('id', $item_id);
		$this->db->update('items');
	
		return ( $this->db->affected_rows() == 1 ) ? TRUE : FALSE;
	}
}