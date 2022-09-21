<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cms_model extends CI_Model
{
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}   

  public function insert_terms($data){
      $this->db->insert('terms_condition', $data);
      $inserted_id = $this->db->insert_id();
      return $inserted_id;
  }

  public function update_terms($id, $data){
      $this->db->where('id', $id);
      $query = $this->db->update('terms_condition', $data);
      return $query;
  }
    public function get_faqs_list($id='')
    {
        $this->db->select('*');
        $this->db->from('ques_ans');
        if(!empty($id))
        {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
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

     public function get_ourteam_list($id='')
    {
        $this->db->select('*');
        $this->db->from('our_team');
        if(!empty($id))
        {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
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
    
    public function slider_info()
    {
        $this->db->select('*');
        $this->db->from('home_slider');
        if(!empty($id))
        {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
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

    public function get_slider_list($id='')
    {
        $this->db->select('*');
        $this->db->from('home_slider');
        if(!empty($id))
        {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
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

     public function get_ourteam_info($id='')
    {
        $this->db->select('*');
        $this->db->from('team_info');
        if(!empty($id))
        {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
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

    public function get_team_list($id='')
    {
        $this->db->select('*');
        $this->db->from('our_team');
        if(!empty($id))
        {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
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

    public function side_banner_info()
    {
        $this->db->select('*');
        $this->db->from('home_side_banner');
        if(!empty($id))
        {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'ASC');
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
    public function delete_row($id,$table)
    {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete($table);
            return true;
        } else {
            return false;
        }
    }
    public function delete_row_team($id,$table)
    {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete($table);
            return true;
        } else {
            return false;
        }
    }
    public function get_our_team($id='')
    {
        $this->db->select('*');
        $this->db->from('our_team');
        if(!empty($id))
        {
            $this->db->where('id', $id);
        }
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


 
}	