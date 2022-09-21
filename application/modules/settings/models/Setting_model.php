<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Setting_Model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
  
   
    public function get_email_template($id)
    {
        
        $this->db->select('*');
        $this->db->from('crm_email_template');
        $this->db->where('id',$id);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_setting_row()
    {
        $this->db->select('*');
        $this->db->from('settings');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $result = $query->row_array();
            return $result; 
        }else{
            return array();
        }
       
    }

  	public function get_email_template_active()
  	{
		$this->db->select('id,template_name,body,status');
		$this->db->from('crm_email_template');
        $this->db->where('status', 'active');
		$result = $this->db->get()->result_array();
		return $result;
	}

    public function get_email_body($id = 0)
    {
        $this->db->select('body');
        $this->db->from('crm_email_template');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }


    public function insert_popup($data)
    {
        $this->db->truncate('popup');
        $this->db->insert('popup', $data);
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

    public function save_email_template($id = '', $data)
    {
        if($id != '')
        {
            $this->db->where('id', $id);
            $query = $this->db->update('crm_email_template', $data);
        }
        else
        {
            $this->db->insert('crm_email_template', $data);
            $query = $this->db->insert_id();
        }

        return $query;
    }

    public function delete_setting_row($id)
    {
        $this->db->select('*');
        $this->db->from('crm_email_template');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('crm_email_template');
            return true;
        } else {
            return false;
        }
    } 
    public function insert_buyer_comission($data)
    {
       
        $this->db->insert('settings',$data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }
    public function buyer_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('settings');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();

    }
    public function update_buyer_comission($data)
    {
        $query = $this->db->update('settings',$data);
        return $query;
    }
    public function email_template_list()
    {
        $this->db->select('*');
        $this->db->from('crm_email_template');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function delete_select_row($id,$table)
    {
        $this->db->where('id', $id);
        $query = $this->db->delete($table);
  
        if ($query) {
            return true;
        } else {
            return false;
        }
    } 

     public function chahge_status($status,$user_id)
    {
        $this->db->where('id',$user_id);
        $return = $this->db->update('crm_email_template',$status);

        return $return;
    }
     public function email_listing($id = 0)
    {
        $this->db->select('crm_email_template.*');
        $this->db->from('crm_email_template');
         if($id != 0)
        {
         $this->db->where('id',$id);
        }
         $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }  

    public function check_user_number($number)
        {
        $this->db->where('mobile',$number);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

}
