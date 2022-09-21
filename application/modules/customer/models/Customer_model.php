<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customer_model extends CI_Model
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


      ////check duplicate email address
      public function get_item_subcategory_list($category_id='')
    {
        $this->db->select('*');
        if($category_id != '')
        {
            $this->db->where('category_id', $category_id);
        }
        $this->db->where('status' , 'active');
        $this->db->from('item_subcategories');
        $result = $this->db->get()->result_array();
        return $result;
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

     public function user_balance($id)
     {
        $dr_balance = 0;
        $cr_balance = 0;
        $this->db->select_sum('amount');
        // $this->db->select('total_deposite');
        $this->db->from('auction_deposit');
        $this->db->where('deposit_type', 'permanent');
        $this->db->where('status', 'approved');
        $this->db->where('deleted', 'no');
        $this->db->where('account', 'DR');
        $this->db->where('user_id',$id);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            $dr_balance = $query->row_array();
        }
        $this->db->select_sum('amount');
        // $this->db->select('total_deposite');
        $this->db->from('auction_deposit');
        $this->db->where('deposit_type', 'permanent');
        $this->db->where('status', 'approved');
        $this->db->where('deleted', 'no');
        $this->db->where('account', 'CR');
        $this->db->where('user_id',$id);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            $cr_balance = $query->row_array();
        }
        $amount['amount'] = $dr_balance['amount'] - $cr_balance['amount'];
        return $amount;
     } 

     public function user_current_temporary_dposit($user_id, $auction_id='')
     {
        $dr_balance = 0;
        $cr_balance = 0;
        $this->db->select_sum('amount');
        // $this->db->select('total_deposite');
        $this->db->from('auction_deposit');
        $this->db->where('deposit_type', 'temporary');
        $this->db->where('status', 'approved');
        $this->db->where('deleted', 'no');
        $this->db->where('account', 'DR');
        $this->db->where('user_id',$user_id);
        if(!empty($auction_id)){
            $this->db->where('auction_id',$auction_id);
        }
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            $dr_balance = $query->row_array();
        }
        $this->db->select_sum('amount');
        // $this->db->select('total_deposite');
        $this->db->from('auction_deposit');
        $this->db->where('deposit_type', 'temporary');
        $this->db->where('status', 'approved');
        $this->db->where('deleted', 'no');
        $this->db->where('account', 'CR');
        $this->db->where('user_id',$user_id);
        if(!empty($auction_id)){
            $this->db->where('auction_id',$auction_id);
        }
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            $cr_balance = $query->row_array();
        }
        $amount['amount'] = $dr_balance['amount'] - $cr_balance['amount'];
        return $amount;
     } 

     public function user_bids($id)
     {
        $query = $this->db->query('SELECT COUNT(DISTINCT(item_id)) as count FROM `bid` WHERE user_id = '.$id.'');
        $bid_count = $query->row_array();
        // $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
     }

     public function user_bids_count($id) {
        $this->db->select('*');
        $this->db->from('bid');
        $this->db->where('user_id',$id);
        $id = $this->db->get()->num_rows();
        return $id;
}


    
}
