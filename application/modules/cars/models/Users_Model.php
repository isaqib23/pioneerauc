<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users_Model extends CI_Model
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
	public function update_user($id, $data){
		$this->db->where('id', $id);
		$return=$this->db->update('users', $data);		
		return $return;
	}

	public function get_user_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id', $id);
		$item = $this->db->get();
		if($item->num_rows()> 0 ){
			return $item->result_array();
		}else{
			return false;
		}
		exit();	
	}

	public function get_all_user_with_role_for_admin(){
		$this->db->select('users.*,acl_roles.name');
        $this->db->from('users');
        $this->db->order_by('id', 'desc');
        $this->db->join('acl_roles',' acl_roles.id = users.role_id');
        $result = $this->db->get()->result_array();
        return $result;
	}


	public function get_all_users_active(){
		$this->db->select('*');
        $this->db->from('users');
        $this->db->where('status','1');
        $result = $this->db->get()->result_array();
        return $result;
		
	}
	public function check_mail($mail)
	{
		$array = array('email' => $mail, 'status' => '1');
		$this->db->where($array);
		$query=$this->db->get('users');
		if($query->num_rows() >0)
		{
			return $query->result_array();
		}
		return false;
	}
	public function is_login(){
		if(!$this->session->userdata('logged_in')) {
			//die("ssdsds");
			redirect(base_url().'loginuser/login');
		}
	}
	public function check_user_id($id)
	{
		$array = array('id' => $id);
		$this->db->where($array);
		$query=$this->db->get('users');
		if($query->num_rows() >0)
		{
			// return $query->result_array();
			return true;
		}
		return false;
	}

	public function save_user($data)
	{
		$this->db->insert('users',$data);
		$inserted_id = $this->db->insert_id();
		
		return $inserted_id;
	}
	public function check_users_oldpassword($id,$password){
		$this->db->select('*');
        $this->db->from('users'); 
        $this->db->where('id',$id);
        $this->db->where('password',$password);
        $result = $this->db->get()->first_row();
        return $result;
	}
	public function get_usersbyname($username){
		$this->db->select('*');
        $this->db->from('users');
        // $where = "coupon_image_url = '".$img."' or coupon_url = '".$url."' ";
        $this->db->where('username',$username);
        $result = $this->db->get()->first_row();
        return $result;
	} 

	public function get_usersbysame_email($id,$email){
		$this->db->select('*');
        $this->db->from('users'); 
        $this->db->where('id',$id);
        $this->db->where('email',$email);
        $result = $this->db->get()->first_row();
        return $result;
	}
	public function get_usersbyemail($email){
		$this->db->select('*');
        $this->db->from('users');
        $this->db->where('email',$email);
        $result = $this->db->get()->first_row();
        return $result;
	}
	
	 public function delete_users_list_row($id){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id',$id);
        $query = $this->db->get();
        if($query->num_rows() >0){
        $this->db->where('id',$id);
        $this->db->delete('users'); 
		return true;
		}else{
		return false;
		}
	 }

	 // public function get_all_user_with_role($id){
  //       $this->db->select('users.*,acl_roles.name');
  //       $this->db->from('users');
  //       $this->db->where('referral_user_id',$id)->or_where('users.id',$id);
  //       $this->db->join('acl_roles',' acl_roles.id = users.role_id');
  //       $this->db->order_by('id', 'desc');
  //       $result = $this->db->get()->result_array();
  //       return $result;
	 // }

	 public function get_all_user_with_role($referral_user_id){
        $this->db->select('users.*,acl_roles.name');
        $this->db->from('users');
        $this->db->where('referral_user_id',$referral_user_id);
        $this->db->or_where('users.id',$referral_user_id);
        $this->db->join('acl_roles',' acl_roles.id = users.role_id');
        $this->db->order_by('id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
	 }


	public function sendMail($template_name,$arr,$from_name,$from_email,$subject,$message_user)
	{
		$from_name   = $from_name;
		$from_email  = $from_email;
		$subject     = $subject;

		foreach($arr as $key=>$val){
			if(preg_match("/\[(".$key.")]/",$from_name,$m)){
			//if(!strpos($subject,'['.$key.']')===false){
				$from_name=str_replace('['.$key.']',$val,$from_name);
			}
		}
		foreach($arr as $key=>$val){
			if(preg_match("/\[(".$key.")]/",$from_email,$m)){
			//if(!strpos($subject,'['.$key.']')===false){
				$from_email=str_replace('['.$key.']',$val,$from_email);
			}
		}
		
		foreach($arr as $key=>$val){
			if(preg_match("/\[(".$key.")]/",$subject,$m)){
			//if(!strpos($subject,'['.$key.']')===false){
				$subject=str_replace('['.$key.']',$val,$subject);
			}
		}
		$message= $message_user;
		$header="From: $from_name <$from_email>\r\n";
		$header.="Content-type:text/html";
	    $this->load->library('email');
		$ok=true;
		$this->email->to($arr['email']);
		$this->email->from($from_email, $from_name);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->reply_to($from_email, $from_name);
		$this->email->set_mailtype("html");
		$result = $this->email->send();
		$ok=1;
		if($result){
			return true;
		}
		else{
			return false;
		}
	}

	public function user_list_according_to_venue($venue_id)
	{

		$this->db->select('*');
		$this->db->from('venue_users');
		$this->db->where('venue_id', $venue_id);		
		$query = $this->db->get();
		if($query->num_rows() >0){
			return $query->result_array();
		}
		return false;
	}

	public function upload_files_venue_picture_with_extra($newname,$path,$image,$name,$thumb_width_set,$thumb_height_set) 
	{
		list($width, $height, $type, $attr) = @getimagesize($_FILES[$name]['tmp_name']);
		$this->load->library('image_lib');
		$ext = strrchr($image, ".");
		$fileName = $newname.''. $ext;
		$config = array(
			'upload_path' => $path,
			'allowed_types' => 'jpg|gif|png|jpeg',
			'max_size' => 5000,
			'max_width' => 50000,
			'max_height' => 50000,
			'file_name' => $fileName,
			'overwrite' => 1,
		);
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ($this->upload->do_upload($name)) 
		{
			$uploadreturn = $this->upload->data();
			$config2 = array();
			$config2['image_library'] = 'gd2';
			$config2['source_image'] = $path . '' . $fileName;
			$config2['new_image'] = $path.'' . $fileName;
			$config2['create_thumb'] = TRUE;
			$config2['maintain_ratio'] = TRUE;
			$config2['thumb_marker'] = false;
			$config2['height'] = $thumb_height_set;
			$config2['master_dim'] = 'height';
			$ratio  = $width/$height;
			$calculated_width = $ratio * $thumb_height_set;
			if($calculated_width>= $thumb_width_set)
			{
				
			}
			else
			{
				$corping_height = ($width/$thumb_width_set) * $thumb_height_set;
				$config_crop = array(
					'image_library' => 'gd2',
					'maintain_ratio' => false, 
				);
				$config_crop['source_image'] = $path . '' . $fileName;
				$config_crop['new_image']= $path . '' . $fileName;
				$config_crop['x_axis'] = 0;
				$config_crop['width']= $width;
				$config_crop['height']= (int)($corping_height);
				$config_crop['y_axis'] = (int)(($height - $corping_height)/2);
				$this->image_lib->initialize($config_crop);
				if ( ! $this->image_lib->crop())
				{
					echo $this->image_lib->display_errors();die;
				}
				list($width, $height, $type, $attr) = @getimagesize($path . '' . $fileName);
			}
			$this->image_lib->clear();
			$this->image_lib->initialize($config2);
			$this->image_lib->resize();
			$this->image_lib = new CI_Image_Lib();
			unset($this->image_lib);
			return $uploadreturn;
		}
		else 
		{
			return false;
		}
	}

}	