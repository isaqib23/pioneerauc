<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Apis_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        
        //load database library
        $this->load->database();
         $this->load->library('session');
    }
    function insert($data){
       $this->db->insert('users',$data);
       $this->db->where('field is  NULL', NULL, FALSE);

}
    function get()
    {
      $this->db->select('*');
      $this->db->from('users');
      $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }

    }
    function get_login_user($id)
    {
      $this->db->select('*');
      $this->db->from('users');
      $this->db->where('id',$id);
      $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }

    }
    function login_user_list()
    {
       $this->db->select('fname,lname,email,phone');
       $this->db->from('users');
       // $this->db->where('id',$id);
       $query=$this->db->get();
       if ($query->num_rows()> 0) {
         return $query->result_array();
       }
    }

    function inventory_list($id)
    {
       $this->db->select('*');
       $this->db->from('item');
       $this->db->where('seller_id',$id);
       // $this->db->join('item_fields_data','item_fields_data.category_id=item.category_id');     
       $query=$this->db->get();
       // echo $this->db->last_query();
       if ($query->num_rows()> 0) {
         return $query->result_array();
       }
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
  public function get_catagories()
  {
    $this->db->select('*');
    $this->db->from('item_category');
    $this->db->join('auctions','auctions.category_id = item_category.id');
    $query=$this->db->get();
    if ($query->num_rows() > 0) {
      return $query->result();
    }
    else
      return false;
  }
    
   public function upcoming_auctions($date)
  {
    $date= new DateTime("now");
    $current_date=$date->format('Y-m-d');
    $this->db->select('*');
    $this->db->from('auctions');
    $this->db->where('start_time >',$current_date);
    $query=$this->db->get(); 
    if ($query->num_rows() > 0) {
      return $query->result();
    }
    else
      return false;
  }

   public function user_account()
  {
    $this->db->select('*');
    $this->db->from('user_bank_detail');
    $query=$this->db->get();
    if ($query->num_rows() > 0) {
      return $query->result();
    }
    else
      return false;
  }

  function product_detail()
  {
    $this->db->select('name,detail,keyword');
    $this->db->from('item');
    $query=$this->db->get();
    if ($query->num_rows() > 0) {
      return $query->result_array();
    }
    else{
      return false;
    }
  }

  function check_email($email)
  {
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where('email',$email);
    $query=$this->db->get();
    if ($query->num_rows()> 0) {
      return $query->row_array();
    }
    else
    {
      return array();
    }
  }

    public function check_user_number($number,$id)
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

    public function check_email_user($email,$id)
  {
    $this->db->where('email',$email);
      $this->db->where('id !=',$id);
    $query = $this->db->get('users');
    if ($query->num_rows() > 0){
        return true;
    }
    else{
        return false;
    }
  }

   public function check_password($password)
  {
    $this->db->where('password',$password);
    $query = $this->db->get('users');
    if ($query->num_rows() > 0){
        return true;
    }
    else{
        return false;
    }
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

   public function multiUpload($field="", $config = array())
    {
        if( ($config) && (!empty($field)) ){
          
          $filesCount = count($_FILES[$field]['name']);
          for($i = 0; $i < $filesCount; $i++){
                $_FILES['file']['name']     = $_FILES[$field]['name'][$i];
                $_FILES['file']['type']     = $_FILES[$field]['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES[$field]['tmp_name'][$i];
                $_FILES['file']['error']    = $_FILES[$field]['error'][$i];
                $_FILES['file']['size']     = $_FILES[$field]['size'][$i];

                $config['file_name'] = random_string('alnum', 5).md5(time());
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if ( ! $this->upload->do_upload('file')){
                return array('error' => $this->upload->display_errors());
            }else{

                $uploaded = $this->upload->data();
                $file = array(
                  'name' => $uploaded['file_name'],
                  'type' => $uploaded['file_type'],
                  'size' => $uploaded['file_size'],
                  'created_by' => $this->session->userdata('logged_in')->id,
                  'created_on' => date('Y-m-d H:i:s')
                );

                //$vigo[] = array('id' => 'uploaded ' . $i, 'filesCount' => $filesCount);
                
                $result = $this->db->insert('documents', $file);
                if($result){
                  $result_upload[] = $this->db->insert_id();
                }else{
                  $result_upload['error'] = 'Database error: unable to update file detail in database.';
                }
            }
        }
        return $result_upload;
        }else{
          return array('error' => 'Invalid data pass to upload!');
        }
    }


     public function insert_user_details($data)
    {
        $this->db->insert('users', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }

      public function verify_code($code)
    {
        $this->db->select('*');
         $this->db->from('users');
          $this->db->where('code',$code);
          $query=$this->db->get();
        if ($query->num_rows() > 0) {
          return $query->row_array();
        }
        else
        {
          return array();
        }

    }

        public function forgot_password_update($email,$password)
    {
        $this->db->where('email', $email);
    $return = $this->db->update('users', array('password' => $password));
    return  $this->db->affected_rows();
    }

     public function get_auction_cat()
     {
      
     $query=$this->db->query('select c.title, a.title as auction_title, a.start_time  from item_category c join auctions a on c.id = a.category_id where c.status = "active" ')->result_array();
      // $query=$this->db->get('auctions');
      return $query;
     }


     public function detail_of_auctions()
     {
      $this->db->select('*');
      $this->db->from('auctions');
      $query=$this->db->get();
      if ($query->num_rows()) {
        return $query->result_array();
      }
      else
       {
        return array();
       } 
     }

        //  public function edit_profile($email,$password,$city,$fname,$lname,$id,$mobile)
        // {
        // $this->db->where('id', $id);
        // $return = $this->db->update('users', array('password' => $password,  'fname' => $fname , 'lname' => $lname,'city,' => $city,)'mobile' =>$mobile,'email'=>$email);
        // return  $this->db->affected_rows();
        // }

       public function edit_profile($id, $data)
        {
        $this->db->where('id', $id);
        $return = $this->db->update('users',$data);
        return  $this->db->affected_rows();
        }


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
         
        public function update_password($id, $data){
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
     public function update_mobile($id, $data){
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
  public function update_password_new($id, $data){
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
  public function add_wishlist($id, $data){
    $this->db->where('customer_id', $id);
    $return=$this->db->update('favorites_items', $data);  
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
    
  public function get_terms($id)
  {
    $this->db->select('*');
    $this->db->from('terms_condition');
    // $this->db->join('item','item.id=favorites_items.item_id');

   
    $return=$this->db->get();
    if($return)
    {
        return $return->row_array();
    }
    else
    {
        return false;
    } 
    // return $return;
  }

        public function get_docs_details($id){
          $this->db->select('*');
          $this->db->from('users');
          $this->db->where('id',$id);
          $return=$this->db->get();
          if($return)
          {
              return $return->row_array();
          }
          else
          {
              return false;
          }   
          // return $return;
        }


      public function get_items_details($id){
        $this->db->select('*');
        $this->db->from('favorites_items');
        $this->db->join('item','item.id=favorites_items.item_id');
        $return=$this->db->get();
        if($return)
        {
            return $return->row_array();
        }
        else
        {
            return false;
        } 
    // return $return;
  }


      public function get_items_images($id){
           $this->db->select('*');
        $this->db->from('item');
         $this->db->join('files','files.id=item.item_images','files.name=item_images');
        $return=$this->db->get();
        if($return)
        {
            return $return->row_array();
        }
        else
        {
            return false;
        } 
    // return $return;
  }

       public function get_users($id)
        {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id',$id);
         $query=$this->db->get();
         if ($query->num_rows()) {
           return $query->row_array();
         }
        }

        public function user_history_auctions($id)
        {
          $this->db->select('*');
          $this->db->from('item');
          $this->db->where('seller_id',$id);
          // $this->db->join('users','users.id=bid.user_id','left');
          $query=$this->db->get();
          if ($query->num_rows()) {
            return $query->result_array();
          }
          else
          {
            return array();
          }
        }

         public function user_history_bids($id)
        {
          $this->db->select('*');
          $this->db->from('bid');
          $this->db->where('user_id',$id);
          $this->db->join('item','item.seller_id=bid.user_id');
          $this->db->join('item_category','item_category.id=item.category_id');
         // if(!empty($this->db->join('files','files.id=item.item_images')));
          // $this->db->join('item','item.item_images=files.id');
          $query=$this->db->get();
          // echo $this->db->last_query();
          if ($query->num_rows()) {
            return $query->result_array();
          }
          else
          {
            return array();
          }
        }

         public function get_auction_visitors($id)
        {
          // date_default_timezone_set('Australia/Melbourne');
          
          $this->db->select('*');
          $this->db->from('auctions');  
          $this->db->where('auctions.category_id',$id);  
          $this->db->join('item_category','item_category.id=auctions.category_id','left'); 

          $this->db->join('item','item.category_id=auctions.category_id','left');             
          $this->db->join('files','files.id=item.item_images');             
          $query=$this->db->get();
          if ($query->num_rows()) {
            return $query->result_array();
          }
          else
          {
            return array();
          }
        }


        public function contact($data)
        {
          $this->db->insert('contact_us',$data);
          return true;
        }

        public function car_valuation()
        {
          $this->db->select('*');
          $this->db->from('valuation_car');
          $query=$this->db->get();
          if ($query->num_rows()) {
            return $query->result_array();
          }
          else
          {
            return array();
          }
        }

        public function contactUs()
        {
          $this->db->select('email');
          $this->db->from('contact_us');
          $query= $this->db->get();
          if ($query->num_rows() > 0) {
            return $query->row_array();
          }
        }

         public function updateBid($id,$data)
        {
          $this->db->where('user_id', $id);
          $return = $this->db->update('user_deposit_detail',array('amount' => $data));
          // print_r($this->db->last_query());
          return  $this->db->affected_rows();
        }


}
?>