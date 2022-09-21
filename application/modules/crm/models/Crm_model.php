<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crm_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
  
    public function customer_type_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('crm_customer_type');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function loss_reasons_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('crm_loss_reasons');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }


    public function loss_reasons_list_active()
    {
        $this->db->select('*');
        $this->db->from('crm_loss_reasons');
        $this->db->where('status', 'active');
        $this->db->order_by('title', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    

    public function customer_type_list_active()
    {
        $this->db->select('*');
        $this->db->from('crm_customer_type');
        $this->db->where('status', 'active');
        $this->db->order_by('title', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function lead_source_list_active()
    {
        $this->db->select('*');
        $this->db->from('crm_lead_source');
        $this->db->where('status', 'active');
        $this->db->order_by('title', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function lead_category_list_active()
    {
        $this->db->select('*');
        $this->db->from('crm_lead_category');
        $this->db->where('status', 'active');
        $this->db->order_by('title', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function lead_stage_list_active()
    {
        $this->db->select('*');
        $this->db->from('crm_lead_stage');
        $this->db->where('status', 'active');
        $this->db->order_by('title', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function next_step_list_active()
    {
        $this->db->select('*');
        $this->db->from('crm_next_step');
        $this->db->where('status', 'active');
        $this->db->order_by('title', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }


    public function crm_list($id = 0)
    {
        $this->db->select('crm_detail.*,users.fname,users.lname,crm_customer_type.title as customer_type,crm_lead_source.title as lead_source_name,crm_lead_category.title as lead_category_name');
        $this->db->from('crm_detail');
        $this->db->join('crm_customer_type', 'crm_detail.customer_type_id = crm_customer_type.id', 'left');
        $this->db->join('crm_lead_source', 'crm_detail.lead_source_id = crm_lead_source.id', 'left');
        $this->db->join('crm_lead_category', 'crm_detail.lead_category_id = crm_lead_category.id', 'left');
        $this->db->join('users', 'crm_detail.assigned_to = users.id', 'left');
        if ($id != 0) {
            $this->db->where('crm_detail.id', $id);
        }
        // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
        $this->db->order_by('crm_detail.id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function crm_customers_list()
    {
        $this->db->select('id,email,name');
        $this->db->from('crm_detail');
        // $this->db->group_by('id');
        $this->db->group_by('id');

        $result = $this->db->get()->result_array();
        return $result;
    }


    public function crm_filter_list($where = '')
    {
        $this->db->select('crm_detail.*,users.fname,users.lname,crm_customer_type.title as customer_type,crm_lead_source.title as lead_source_name,crm_lead_category.title as lead_category_name');
        $this->db->from('crm_detail');
        $this->db->join('crm_customer_type', 'crm_detail.customer_type_id = crm_customer_type.id', 'left');
        $this->db->join('crm_lead_source', 'crm_detail.lead_source_id = crm_lead_source.id', 'left');
        $this->db->join('crm_lead_category', 'crm_detail.lead_category_id = crm_lead_category.id', 'left');
        $this->db->join('users', 'crm_detail.assigned_to = users.id', 'left');
        if ($where != '') {
            $this->db->where($where);
        }
        // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
        $this->db->order_by('crm_detail.id', 'desc');
        $query = $this->db->get();
    
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function crm_sales_list($id = 0)
    {
        $this->db->select('crm_detail.*,crm_customer_type.title as customer_type,crm_lead_source.title as lead_source_name,crm_lead_category.title as lead_category_name');
        $this->db->from('crm_detail');
        $this->db->join('crm_customer_type', 'crm_detail.customer_type_id = crm_customer_type.id', 'left');
        $this->db->join('crm_lead_source', 'crm_detail.lead_source_id = crm_lead_source.id', 'left');
        $this->db->join('crm_lead_category', 'crm_detail.lead_category_id = crm_lead_category.id', 'left');
        if ($id != 0) {
            $this->db->where('crm_detail.assigned_to', $id);
        }
        // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
        $this->db->order_by('crm_detail.id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
      public function insert_crm_sales_details($data)
    {
        $this->db->insert('crm_detail', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }


     public function save_customer_type($data)
    {
        $this->db->insert('crm_customer_type', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }

     public function save_loss_reason($data)
    {
        $this->db->insert('crm_loss_reasons', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }

     public function insert_crm_details($data)
    {
        $this->db->insert('crm_detail', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }

    public function update_customer_type($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('crm_customer_type', $data);
        return $query;
    }

    public function update_loss_reason($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('crm_loss_reasons', $data);
        return $query;
    }
     public function delete_customer_type_row($id)
    {
        $this->db->select('*');
        $this->db->from('crm_customer_type');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('crm_customer_type');
            return true;
        } else {
            return false;
        }
    }
     public function source_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('crm_lead_source');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
     public function save_source($data)
    {
        $this->db->insert('crm_lead_source', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }

    public function update_source($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('crm_lead_source', $data);
        return $query;
    }

    public function update_crm($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('crm_detail', $data);
        return $query;
    }
    public function delete_lead_source_row($id)
    {
        $this->db->select('*');
        $this->db->from('crm_lead_source');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('crm_lead_source');
            return true;
        } else {
            return false;
        }
    }

    public function delete_crm_detail_row($id)
    {
        $this->db->select('*');
        $this->db->from('crm_detail');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('crm_detail');
            return true;
        } else {
            return false;
        }
    }
      public function delete_crm_loss_reason($id)
    {
        $this->db->select('*');
        $this->db->from('crm_loss_reasons');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('crm_loss_reasons');
            return true;
        } else {
            return false;
        }
    }


    public function lead_category_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('crm_lead_category');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
     public function save_lead_category($data)
    {
        $this->db->insert('crm_lead_category', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }

    public function update_lead_category($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('crm_lead_category', $data);
        return $query;
    }
    public function delete_lead_category_row($id)
    {
        $this->db->select('*');
        $this->db->from('crm_lead_category');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('crm_lead_category');
            return true;
        } else {
            return false;
        }
    }

    public function lead_stage_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('crm_lead_stage');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
     public function save_lead_stage($data)
    {
        $this->db->insert('crm_lead_stage', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }

    public function update_lead_stage($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('crm_lead_stage', $data);
        return $query;
    }
public function delete_lead_stage_row($id)
    {
        $this->db->select('*');
        $this->db->from('crm_lead_stage');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('crm_lead_stage');
            return true;
        } else {
            return false;
        }
    }
        public function next_step_list($id = 0)
        {
        $this->db->select('*');
        $this->db->from('crm_next_step');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
     public function save_next_step($data)
    {
        $this->db->insert('crm_next_step', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }

    public function update_next_step($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('crm_next_step', $data);
        return $query;
    }
public function delete_next_step_row($id)
    {
        $this->db->select('*');
        $this->db->from('crm_next_step');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('crm_next_step');
            return true;
        } else {
            return false;
        }
    }
     public function get_max_id()
    {
         $query= $this->db->query('select max(id) from users');
        return $query->result_array();

    }
    
}
