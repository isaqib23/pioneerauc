<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Visitor_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

     public function password_check($id, $password)
        {
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('id', $id);
            $this->db->where('password', $password);
            $this->db->where('status', '1');
            $query = $this->db->get();
            if($query->num_rows() > 0)
                return $query->result();
            else
                return false;
        }

         public function check_mobile($number,$id) /// model for check duplicate number
        {
        $this->db->where('mobile',$number);
         $this->db->where('id !=',$id);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
       }
       public function get_user_list($id)
       {
       	$this->db->select('*');
       	$this->db->from('users');
       	$this->db->where('id',$id);
       	$result=$this->db->get();
       	if ($result->num_rows() > 0) {
       	return $result->row_array();	
       		return true;
       	}
       }
         
         ///update the mobile number if unique
       public function update_mobile_number($id, $data)
       { 
           $this->db->where('id', $id);
        $return=$this->db->update('users', $data);  
        if($return)
        {
            return $return;
        }
        else
        {
            return false;
        } 
    // return $return;
      }


      ////check duplicate email address
      public function check_email($email,$id)
   	 {
	    $this->db->where('email',$email);
	    $this->db->where('id !=',$id);
	    $query = $this->db->get('users');
	    if ($query->num_rows() > 0){
        return true;
    	}
	    else
	    {
	        return false;
	    }
	   }

        //////////update profile////////
	    public function update_profile($id, $data)
        {
        $this->db->where('id', $id);
        $return = $this->db->update('users',$data);
        return  $this->db->affected_rows();
        }



   ////////////update user if email unique
    public function update_user($id, $data){
        $this->db->where('id', $id);
        $return=$this->db->update('users', $data);  
        if($return)
        {
            return $return;
        }
        else
        {
            return false;
        } 
    // return $return;
       }

     public function users_listing_profile($id = 0)
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

     public function get_docs($id)
     {
     	$this->db->select('documents');
     	$this->db->from('users');
     	$this->db->where('id',$id);
     	// $this->db->where('card',$card_id);
     	$query= $this->db->get();
     	if ($query->num_rows() > 0) {
     		return $query->row_array();
     	}
     }


     public function items($id='',$order='',$offset='',$limit='')
     {
        $this->db->select('*');
        $this->db->from('item');
        if (isset($id) && !empty($id)) {
            $this->db->where('category_id', $id);
        }
        if (isset($order) && ($order == 'low')) {
            // return $order;
            $this->db->order_by('price',"asc");
        }
        elseif (isset($order) && ($order == 'high')) {
            // return $order;
            $this->db->order_by('price',"desc");
        }else{
            $this->db->order_by('id',"desc");
        }
        if(isset($offset) && !empty($offset)) {

        $this->db->offset($offset);
        }
        if(isset($limit) && !empty($limit)) {
            $this->db->limit($limit);
        }else{            
            // $this->db->limit(8);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result_array();
        // $data['list'] = $list->result_array();

     }


     public function count_item($id='')
     {
        $this->db->select('*');
        $this->db->from('item'); 
        if (isset($id) && !empty($id)) {
            $this->db->where('category_id', $id);                                           
        }
        $query = $this->db->get();
        return $query->num_rows();
        // $data['list'] = $list->result_array();

     }


      
    
}
