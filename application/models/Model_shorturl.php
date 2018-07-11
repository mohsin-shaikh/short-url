<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_shorturl extends CI_Model {

	private $primary_key 	= 'url_id';
	private $table_name 	= 'urls';

	public function __construct()
	{
		$config = array(
			'primary_key' 	=> $this->primary_key,
		 	'table_name' 	=> $this->table_name,
		);
		parent::__construct();
		//Do your magic here
	}

	public function get_all_data()
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->limit(100);
        $query = $this->db->order_by('counter', "DESC")->get();

        return $query->result();
    }

    public function get_single($where)
    {
        $query = $this->db->get_where($this->table_name, $where);

        return $query->row();
    }


	public function change($id = NULL, $data = array())
    {        
        $this->db->where($this->primary_key, $id);
        $this->db->update($this->table_name, $data);

        return $this->db->affected_rows();
    }

	public function store($data = array())
    {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }	

}

/* End of file Model_shorturl.php */
/* Location: ./application/models/Model_shorturl.php */