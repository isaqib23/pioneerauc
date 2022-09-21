<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Terms_condition_model extends CI_Model
{

  	function __construct()
    {
        // Call the Model constructor
        parent::__construct(); 
        $this->load->database();
    }
    
    public function get($id='')
    {
        $this->db->select('*');
        $this->db->from('terms_condition');
        if(!empty($id))
        {
            $this->db->where('id', $id);
        }
        $this->db->order_by('created_on', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) 
        {
        	$result = $query->result_array();
        }
        else
        {
        	$result = array();
        }
        return $result;
    }

    public function insert($data)
    {
        $this->db->insert('terms_condition', $data);
        $inserted_id = $this->db->insert_id();
        if($inserted_id)
        {
            return $inserted_id;
        }
        else
        {
            return false;
        }
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('terms_condition', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }
	

    public function delete_terms_condition_row($id)
    {
        $this->db->select('*');
        $this->db->from('terms_condition');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('terms_condition');
            return true;
        } else {
            return false;
        }
    }


    public function delete_media_MultipleRow($ids_array)
    {
        $ids_array = explode(",",$ids_array);
        $this->db->select('*');
        $this->db->from('media');
        $this->db->where_in('id', $ids_array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where_in('id', $ids_array);
            $this->db->delete('media');
            return true;
        } else {
            return false;
        }
    }    


}

