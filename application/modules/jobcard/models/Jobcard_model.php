<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jobcard_Model extends CI_Model
{
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function login_check($username, $password)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $username);
		$this->db->where('password', $password);
		$this->db->where('status', '1');
		$query = $this->db->get();
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}


     public function tasker_list($id = '')
    {
       
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('role',6);
        $this->db->where('status' , 1);
        // $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

     public function jobcard_user_list($id)
    {
        $this->db->select('*');
        $this->db->from('users');
        if($id != ''){
        $this->db->where_in('role',$id);
        }  
        // $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

     public function jobcard_roles($ids_array)
    {
        $this->db->select('*');
        $this->db->from('acl_roles');
        $this->db->where_in('id', $ids_array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }


    public function check_jobcard_user_email($email)
    {
        $this->db->where('email',$email);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function check_jobcard_user_mobile($mobile)
    {
        $this->db->where('mobile',$mobile);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_all_sales_user(){
        $this->db->select('users.*,acl_roles.name as role_name');
        $this->db->from('users');
        $this->db->join('acl_roles',' acl_roles.id = users.role');
        $this->db->where('users.status',1);
        $this->db->where('users.role',4);
        $this->db->order_by('id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
     }

     public function get_all_customer_user(){
        $this->db->select('users.*,acl_roles.name as role_name');
        $this->db->from('users');
        $this->db->join('acl_roles',' acl_roles.id = users.role');
        $this->db->where('users.status',1);
        $this->db->where('users.role',4);
        $this->db->order_by('id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
     }

     
	public function update_user($id, $data){
        // print_r($data);die('aaa');
		$this->db->where('id', $id); 
		$return=$this->db->update('users', $data);		
		return $return;
	}
	public function insert_tasker_detail($data)
    {
        $this->db->insert('users', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }

    public function get_max_id()
    {
         $query= $this->db->query('select max(id) from users');
        return $query->result_array();

    }
   
    public function insert_user_bank_details($data)
    {
        $this->db->insert('user_bank_detail', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }
    public function update_user_task_detail($id, $data){
        $this->db->where('id', $id);
        $return=$this->db->update('task', $data);       
        return $return;
    }
    public function bank_detail_list($id)
    {
        $this->db->select('user_bank_detail.*');
        $this->db->from('user_bank_detail');
        $this->db->where('user_id', $id);
        // $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function edit_task_list($id = 0)
    {


        $this->db->select('*');
        $this->db->from('task');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();

        
    }
    public function all_task_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('task');
        if ($id != 0) {
            
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function update_edit_task($id,$data)
    {
        $this->db->where('id', $id);
        $return = $this->db->update('task', $data);
        return $return;
    }

    public function deposit_detail_list($id)
    {
        $this->db->select('user_deposit_detail.*,item_category.title as category_name');
        $this->db->from('user_deposit_detail');
        $this->db->join('item_category','user_deposit_detail.category_id = item_category.id');
        $this->db->where('user_deposit_detail.user_id', $id);
        // $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
     public function insert_task($data)
    {
        $this->db->insert('task', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }  
    public function tasks_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('task');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function assigned_task_list_admin($id = 0)
    {
        $this->db->select('*');
        $this->db->from('assigned_task');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    
    
      public function update_user_deposit_detail($id, $data){
        $this->db->where('id', $id);
        $return=$this->db->update('user_deposit_detail', $data);       
        return $return;
    }
	
    public function delete_user_row($id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('users');
            return true;
        } else {
            return false;
        }
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
    public function delete_user_bank_detail($id)
    {
        $this->db->select('*');
        $this->db->from('user_bank_detail');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('user_bank_detail');
            return true;
        } else {
            return false;
        }
    }
    public function delete_user_deposit_detail($id)
    {
        $this->db->select('*');
        $this->db->from('user_deposit_detail');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('user_deposit_detail');
            return true;
        } else {
            return false;
        }
    }
    public function get_deposit_detail_by_userid($userid){
        
    }
    public function check_email($email)
    {
    $this->db->where('email',$email);
    $query = $this->db->get('users');
    if ($query->num_rows() > 0){
        return true;
    }
    else{
        return false;
    }
}

    public function users_listing($id = 0)
    {
        $this->db->select('users.*');
        $this->db->from('users');
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
    public function get_roles(){
        $this->db->select('*');
        $this->db->from('acl_roles');
        $this->db->where('id >',1);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_application_user_roles(){
        $app_user = array(2,3);
        $this->db->select('*');
        $this->db->from('acl_roles');
        $this->db->where_in('id',$app_user);
        // $this->db->where('id',3);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_application_users(){
        $app_user = array(2,3);
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where_in('role',$app_user);
        $this->db->where('status',1);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_tasker_roles(){
        $this->db->select('*');
        $this->db->from('acl_roles');
        $this->db->where('id',4);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    public function tasks_listing($where)
    {
        $this->db->select('*');
        $this->db->from('task');
        $this->db->where($where);
          $query = $this->db->get();
    
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function assigned_task_filter($where)
    {
        $this->db->select('*');
        $this->db->from('assigned_task');
        $this->db->where($where);
          $query = $this->db->get();
    
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    

    public function get_users_list_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('role',$id);
        $query = $this->db->get();
         if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function get_user_byid($id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id',$id);
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function get_users_record_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('task');
        $this->db->where('id',$id);
        $query = $this->db->get();
         if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function get_deposit_detail_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('user_deposit_detail');
        $this->db->where('id',$id);
        $query = $this->db->get();
         if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function change_status($status_id,$user_id)
    {
        $this->db->where('id',$user_id);
        $return = $this->db->update('users',$status_id);

        return $return;
            }   

    public function users_sellers_buyers()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('role', 4);    
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            return $query->result_array();
        }
        return array();
    }
    public function sellers_list()
    {
        $seller = "seller";
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('type', $seller);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            return $query->result_array();
        }
        return array();
    }
    
    public function get_transactiom_detail($id)
    {
        $this->db->select('*');
        $this->db->from('transaction');
        $this->db->where('user_id', $id);
        $q = $this->db->get();
        $query = $q->result_array();
        
            return $query;
     
    }
public function tasker_tasks_list($id)
{
    $this->db->select('*');
    $this->db->from('task');
    $this->db->where('assign_to',$id);
    $q = $this->db->get();
    return $q->result_array();
}
public function update_task_status($task_id,$user_id,$data)
{

   
    $this->db->where('id',$task_id);
    // $this->db->where('assign_to',$user_id);
    $return = $this->db->update('task',$data);
    return $return;
}
public function jobcard_user_filter_list($where)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where($where);
        $this->db->where('users.role',6);
        // print_r($where);
          $query = $this->db->get();
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function insert_task_category($data)
    {
        $this->db->insert('task_category',$data);
        return true;
    }
    public function update_task_category($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('task_category',$data);
        return true;
    }
    public function insert_assigned_task($data)
    {
        $this->db->insert('assigned_task',$data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }

    public function insert_to_asigned_tasks_detail_table($data)
    {
        $this->db->insert('assigned_tasks_detail',$data);
        return true;
    }
    public function insert_to_notify_me($data)
    {
        $this->db->insert('notify_me',$data);
        return true;
    }
    public function update_assigned_task($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('assigned_task',$data);
        return true;
    }   
    public function update_asigned_tasks_detail($task_id,$assigned_task_id,$data)
    {
        $this->db->where('task_id',$task_id);
        $this->db->where('assigned_task_id',$assigned_task_id);
        // print_r($data);die('');
        $this->db->update('assigned_tasks_detail',$data);
        return true;
    }   
    public function atempt_to_work($id,$data)
    {
        $this->db->where('id', $id);
        $this->db->update('assigned_tasks_detail',$data);
        return true;
    }
    public function complete_task($id,$data)
    {
        $this->db->where('id', $id);
        $this->db->update('assigned_tasks_detail',$data);
        return true;

    }
    public function get_users_for_report()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('role',6);
        $this->db->where('status', 1);
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function seen_notification($id)
    {
        $data['status'] = "seen";
        $this->db->where('tasker_id',$id);
        $this->db->update('notify_me',$data);
        return true;
    }
}	