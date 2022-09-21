<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter ACL Class
 *
 * This class enables apply permissions to controllers, controller and models, as well as more fine tuned permissions '
 * at code level.
 *
 * @package     CodeIgniter
 * @subpackage  Models
 * @category    Models
 * @author      David Freerksen
 * @link        https://github.com/dfreerksen/ci-acl
 */
class Acl_Model extends CI_Model {

	/**
	 * Get permissions from database
	 *
	 * @param   int $role
	 * @return  array
	 */
	public function has_permission($controller,$action,$role_id)
	{
		$query = $this->db->select("p.controller as k")
		->from('acl_permissions p')
		->join('acl_role_permissions rp', "rp.acl_permission_id = p.id")
		->where("rp.acl_role_id", $role_id)
		->where("p.controller", $controller)
		->where("p.action", $action)
		->get();
		$permissions = array();
		if(count($query->result_array())>0)
			return true;
		else
			return false;
	}

	public function get_roles(){
		$this->db->select('*');
		$this->db->from('acl_roles');
		$this->db->where('id !=',1);
        $this->db->order_by('id', 'ASC');
		$result = $this->db->get()->result_array();
		return $result;
	}
	public function get_all_roles(){
		$this->db->select('*');
		$this->db->from('acl_roles');
        $this->db->order_by('id', 'ASC');
		$result = $this->db->get()->result_array();
		return $result;
	}
	public function get_rolesbyid($id){
		$this->db->select('*');
		$this->db->from('acl_roles');
		$this->db->where('id',$id);
		$result = $this->db->get()->result_array();
		return $result;
	}
	public function get_rolesbyname($name){
		$this->db->select('*');
		$this->db->from('acl_roles');
		$this->db->where('name',$name);
		$result = $this->db->get()->first_row();
		return $result;
	}
	public function save_acl_role($data){
		$this->db->insert('acl_roles',$data);
		$inserted_id = $this->db->insert_id();
		return $inserted_id;
	}
	public function check_acl_permissions($controller,$action){
		$this->db->select('*');
		$this->db->from('acl_permissions');
		$this->db->where('controller',$controller);
		$this->db->where('action',$action);
		$query = $this->db->get();
		if($query->num_rows() >0){ 
			return true;
		}else{
			return false;
		}
	}
	public function get_permission(){
		$this->db->select('*');
		$this->db->from('acl_permissions');
		$this->db->where('availability','permission_needed');
		$result = $this->db->get()->result_array();
		return $result;
	}

	public function get_permission_ByController($controller){
		$this->db->select('id');
		$this->db->from('acl_permissions');
		$this->db->where('availability','permission_needed');
		$this->db->where('controller',$controller);
		$result = $this->db->get()->result_array();
		return $result;
	}

	public function main_permission($controller,$roleid)
	{
		$id_list = array();
		$controller_ids_array = $this->get_permission_ByController($controller);
		foreach ($controller_ids_array as $value) 
		{
			$id_list[] = $value['id'];
		}
		if(count($id_list) > 0)
		{
			$id_list_str = implode("','", $id_list);
			$controller_count = count($controller_ids_array);
			$this->db->select('id');
			$this->db->from('acl_role_permissions');
			$this->db->where('acl_role_id',$roleid);
			$this->db->where_in('acl_permission_id', $id_list);
			$query = $this->db->get();
			$num = $query->num_rows();
		}
		else
		{
		 	$num = 0;			
		}
		if($num > 0){
			return true; // some permissions assigned
		}else{
			return false; // not any permission assigned 
		}
	}

	public function save_permission($data){
		$this->db->insert('acl_permissions',$data);
		$inserted_id = $this->db->insert_id();
		return $inserted_id;
	}


	public function check_acl_role_permissions($data){
		$this->db->select('*');
		$this->db->from('acl_role_permissions');
		$this->db->where('acl_role_id',$data['acl_role_id']);
		$this->db->where('acl_permission_id',$data['acl_permission_id']);
		$query = $this->db->get();
		if($query->num_rows() >0){ 
		$result = $this->db->get()->result_array();
		return $result;
			// return true;
		}else{
			return false;
		}
	}

	public function get_role_permission_byid($id){
		$this->db->select('*');
		$this->db->from('acl_role_permissions');
		$this->db->where('acl_role_id',$id);
		$result = $this->db->get()->result_array();
		return $result;
	}
	public function save_acl_role_permission($role_id,$permission_id){

			$data = array(

					'acl_role_id' => $role_id, 
					'acl_permission_id' => $permission_id, 
				);
		$this->db->insert('acl_role_permissions',$data);
		$inserted_id = $this->db->insert_id();
		return $inserted_id;
	}

	public function update_acl_role_permission($roleid,$data){
         $this->db->where('id', $id);
		$return =  $this->db->update('acl_role_permissions', $data);
		return $return;
	}

	public function update_acl_role($id,$data){
		$this->db->where('id', $id);
		$return =  $this->db->update('acl_roles', $data);
		return $return;
	}

	public function clean_acl_role_permission($rid){
		$this->db->select('*');
		$this->db->from('acl_role_permissions');
		$this->db->where('acl_role_id',$rid); 
		$query = $this->db->get();
		if($query->num_rows() >0){
		$this->db->where('acl_role_id',$rid); 
			$this->db->delete('acl_role_permissions'); 
			return true;
		}else{
			return false;
		}
	}
	public function delete_aclrole_list_row($id){
		$this->db->select('*');
		$this->db->from('acl_roles');
		$this->db->where('id',$id);
		$query = $this->db->get();
		if($query->num_rows() >0){
			$this->db->where('id',$id);
			$this->db->delete('acl_roles'); 
			return true;
		}else{
			return false;
		}
	}

}
// END Acl_model class

/* End of file acl_model.php */
/* Location: ./application/models/acl_model.php */