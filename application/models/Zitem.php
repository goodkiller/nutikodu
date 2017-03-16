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

		$this->db->from( 'items' );

		$query = $this->db->get();

		$items_list = $query->result();

		return $items_list;
	}

	function get( $item_id = 0 )
	{
		$this->db->from( 'items' );
		$this->db->where( 'id', $item_id );
	
		$query = $this->db->get();

		return $query->row();
	}

	function get_by_address( $address = '' )
	{
		$this->db->from( 'items' );
		$this->db->where( 'address', $address );
	
		$query = $this->db->get();

		return $query->row();
	}

	function get_last_value( $item_id = 0 )
	{
		$query = $this->db->query( "
		SELECT 	
			* 
		FROM history h1
		JOIN (
			SELECT 
				h2.item_id,
				max(h2.create_date) max_create_date
			FROM history h2
			GROUP BY h2.item_id
		) h2 ON h1.item_id = h2.item_id AND h1.create_date = h2.max_create_date
		WHERE h1.item_id = ?", array(
			$item_id
		));

		$info = $query->row();

		if( !empty($info) )
		{
			if( !empty($info->params) ){
				$info->params = json_decode($info->params);
			}

			return $info;
		}
		
		return FALSE;
	}

	function get_cron_items()
	{
		$this->db->from( 'items' );
		$this->db->where( 'check_delay > 0', NULL, FALSE );
		$this->db->where( "COALESCE( last_check_date, NOW() - ( ( check_delay + 1 ) * interval '1 sec') ) < NOW() - ( check_delay * interval '1 sec')", NULL, FALSE );
	
		$query = $this->db->get();

		return $query->result();
	}

	function add_history( $item_id = 0, $value = 0, $timestamp = 0, $extra_params = array() )
	{
		$item_id = (int)$item_id;
		$timestamp = (int)$timestamp;

		if( $item_id == 0 && !is_numeric($value) ){
			return FALSE;
		}

		if( $timestamp == 0 ){
			$timestamp = time();
		}

		// Insert history
		$this->db->query( 'INSERT INTO history (item_id, value, create_date, params) 
			SELECT 
				sl.item_id, sl.value, sl.create_date, sl.params 
			FROM ( VALUES ( ?, ?, TO_TIMESTAMP( ? ), ?::JSON ) ) sl ( item_id, value, create_date, params )
			LEFT JOIN (
				SELECT 	
					item_id, value, create_date 
				FROM history h1
				JOIN (
					SELECT MAX(h2.create_date) max_create_date FROM history h2 WHERE h2.item_id = ? 
				) h2 ON h1.create_date = h2.max_create_date
				WHERE h1.item_id = ? 
			) lr ON TRUE
			WHERE ( lr.value != sl.value OR lr.value IS NULL )
		ON CONFLICT ON CONSTRAINT "UNIQ_HIST_ITMDATE" DO NOTHING', array(
			$item_id, 
			$value, 
			$timestamp, 
			( !empty($extra_params) && is_array($extra_params) ) ? json_encode( $extra_params ) : NULL, 
			$item_id, 
			$item_id 
		));

		if( $this->db->affected_rows() == 1 ){
			return $this->db->insert_id();
		}

		return FALSE;
	}
}