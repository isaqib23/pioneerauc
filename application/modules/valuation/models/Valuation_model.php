<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Valuation_model extends CI_Model
{
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}  

	public function makes_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('valuation_make');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->where('status' , 1);
        // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function milleage_list($id = 0)
    {
        $this->db->select('milleage.*');
        $this->db->from('milleage');
        $this->db->join('valuation_millage', 'valuation_millage.mileage_id = milleage.id', 'right');
        if ($id != 0) {
            
            $this->db->where('id', $id);
        }
        $this->db->order_by('milleage.milleage', 'ASC');
        $query = $this->db->get();
        // return $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function get_valuate_Option($id = 0)
    {

        $this->db->select('*');
        $this->db->from('valuate_cars_options');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();

    }
     public function get_makes_data($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_model');
        $this->db->where('valuation_make_id', $id);
        $this->db->where('status',1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

}	