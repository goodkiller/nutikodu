<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rules extends CI_Model
{
	protected $rule = array();
	protected $sql_condition = '';

	function __construct(){

		parent::__construct();
	}

	public function get_rules()
	{
		$rules = array();

		$this->db->from( 'rules' );
		$this->db->where( 'enabled', TRUE );

		$query = $this->db->get();

		foreach( $query->result() as $rule )
		{
			$rule->active = ( $rule->active == 't' ) ? TRUE : FALSE;
			$rule->enabled = ( $rule->enabled == 't' ) ? TRUE : FALSE;

			$rules[ $rule->id ] = $rule;
		}

		return $rules;
	}

	public function check_all()
	{
		$rules_list = $this->get_rules();

		foreach( $rules_list as $rule )
		{
			$this->parse( $rule );
		}
	}

	public function parse( $rule = array() )
	{
		$this->rule = $rule;
		$this->sql_condition = '';
		
		$conditions_array = json_decode( $rule->condition, TRUE );

		$this->parse_group( $conditions_array );

		// Validate rule
		if( $this->validate() )
		{
			// Trigger rule
			return $this->trigger_action( $rule->execution );
		}
		else
		{
			// Deactivate rule
			return $this->deactivate();
		}
	}

	private function parse_group( $condition = array() )
	{
		$this->sql_condition .= '(';

		$i = count($condition[ 'rules' ]) - 1;
		foreach( $condition[ 'rules' ] as $rule )
		{
			// Parse value with operator
			$value = $this->parse_value( $rule[ 'type' ], $rule[ 'operator' ], $rule[ 'value' ] );

			if( !empty($value) )
			{
				$this->sql_condition .= $this->parse_field( $rule[ 'field' ], $rule[ 'id' ] ) . ' ' . $value . ' ';

				if( $i > 0 ){
					$this->sql_condition .= $condition[ 'condition' ] . ' ';
				}
			}

			$i--;
		}

		$this->sql_condition .= ')';
	}

	private function validate()
	{
		$query = $this->db->query( 'SELECT ( ' . $this->sql_condition . '  ) AS status', array(
			(int)$this->rule->id 
		));

		log_message( 'debug', '[RULE] Validate: ' . $this->db->last_query() );

		if( $query->row( 'status' ) == 't' ){
			return TRUE;
		}

		return FALSE;
	}

	private function trigger_action( $execution = '' )
	{
		// Rule must be not active for triggering
		if( !$this->rule->active )
		{
			// Activate rule
			$this->activate();

			$execution = json_decode( $execution, TRUE );

			if( !empty($execution) )
			{
				$this->load->library( 'triggers' );

				return $this->triggers->load( $execution );
			}
		}

		return FALSE;
	}

	private function activate()
	{
		if( !$this->rule->active )
		{
			$this->db->set( 'active', TRUE );
			$this->db->where( 'id', (int)$this->rule->id );
			$this->db->update( 'rules' );

			return TRUE;
		}
		
		return FALSE;
	}

	private function deactivate()
	{
		if( $this->rule->active )
		{
			$this->db->set( 'active', FALSE );
			$this->db->where( 'id', (int)$this->rule->id );
			$this->db->update( 'rules' );

			return TRUE;
		}
		
		return FALSE;
	}

	private function parse_field( $field = '', $id = '' )
	{
		switch( $field )
		{
			case 'get_item_value':

				list(,$id) = explode( '_', $id );

				return 'get_item_value(' . (int)$id . ')';
			break;
			
			default:
				return $field;
		}
	}

	private function parse_value( $type = '', $operator = '', $value = '' )
	{
		// Time parser
		if( $type == 'time' ){
			return $this->get_operator( $operator ) . ' ' . $this->db->escape( $value );
		}

		// Double
		elseif( $type == 'double' ){
			return $this->get_operator( $operator ) . ' ' . (float)$this->db->escape_str( $value );
		}

		// Integer
		elseif( $type == 'integer' ){
			return $this->get_operator( $operator ) . ' ' . (int)$this->db->escape_str( $value );
		}

		return NULL;
	}

	private function get_operator( $operator = '' )
	{
		switch( $operator )
		{
			case 'equal':				return "=";		break;
			case 'not_equal':			return "!=";	break;
			case 'less':				return "<";		break;
			case 'less_or_equal':		return "<=";	break;
			case 'greater':				return ">";		break;
			case 'greater_or_equal':	return ">=";	break;
		}

		return NULL;
	}
}
