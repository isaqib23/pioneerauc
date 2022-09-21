<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends CI_Model {

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
        // $this->db->select('*');
        $this->db->from('users');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
      }

    public function get_list_item($id)
    {

        $this->db->select('*');
        $this->db->from('item');
        $this->db->where('id',$id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
        // $this->db->where('document_type_id',$ids);
        // $result = $this->db->get()->result_array();
        // return $result;
    }

      function get_login_user($id)
      {
        $this->db->select('users.*,files.id as fileId,files.name as picture');
        $this->db->from('users');
        $this->db->where('users.id',$id);
        $this->db->join('files','files.id = users.picture');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
          return $query->row_array();
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
        $this->db->select('item.*');
        $this->db->from('item');
        $this->db->where('item.seller_id',$id);
        $this->db->order_by('created_on','desc');
        // $this->db->where('item.status','active');
        $query=$this->db->get();
        if ($query->num_rows()> 0) {
          return $query->result_array();
        }
      }

      function inventory_listByItems($id)
      {
         $this->db->select('item.*,files.name as file_name');
         $this->db->from('item');
         $this->db->join('files', 'files.id = item.item_images');
         $this->db->where('item.id',$id);
         $this->db->where('item.status','active');
         $query=$this->db->get();
         if ($query->num_rows()> 0) {
           return $query->result_array();
         }
      }

      public function user_balance($id)
      {
        $this->db->select_sum('amount');
        // $this->db->select('total_deposite');
        $this->db->from('auction_deposit');
        $this->db->where('deposit_type', 'permanent');
        $this->db->where('status', 'approved');
        $this->db->where('deleted', 'no');
        $this->db->where('user_id',$id);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
      }

      /////for home cat
      public function home_cats()
      {
        $where_condition = [
          'item_category.status' => 'active',
          'show_web' => 'yes'
        ];

        $online_auctions = $this->db->select('item_category.id as cat_id,item_category.category_icon,item_category.title')
        // $online_auctions = $this->db->select('*')
        ->from('item_category')
        // ->join('files','files.id = item_category.category_icon')
        ->where($where_condition)
        ->order_by('sort_order', 'ASC')
        ->limit(9, 0)
        ->get()->result_array();
        return $online_auctions;
      }

      ///get icons for cats

      public function get_icons($category_id){
        $this->db->select('files.id as files_id,files.name ,files.path');
        $this->db->from('files');
        $this->db->where('id',$category_id);
        $query = $this->db->get();
        return $query->row_array();
      }

      ///for home auctions
      public function get_auctions_home($category_id='')
        {
        $where_condition = [
          'auctions.status' => 'active',
          'auctions.access_type' => 'online',
          'auctions.start_time <=' => date('Y-m-d H:i:s'),
          'auctions.expiry_time >=' => date('Y-m-d H:i:s')
        ];
        if (!empty($category_id)) {
          $where_condition['category_id'] = $category_id;
        }

        $online_auctions = $this->db->select('auctions.*,item_category.id as cat_id,item_category.title as cat_title,item_category.category_icon as cat_icon')
        ->from('auctions')
        ->join('item_category', 'auctions.category_id = item_category.id', 'LEFT')
        ->where($where_condition)
        ->get()->row_array();

        return $online_auctions;
      }

      public function get_amount_sum($deposite_user_id)
      {
          $this->db->select_sum('amount');
          $this->db->from('transaction');
          $this->db->where('user_id',$deposite_user_id);
          $query = $this->db->get();
          return $query->row();
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

      public function get_online_auctions($id)
      {
        $this->db->select('auctions.id,auctions.title,auctions.category_id,auctions.start_time,auctions.expiry_time,item_category.category_icon,files.name as image,files.path');
        $this->db->from('auctions');
        $this->db->join('item_category','auctions.category_id = item_category.id','LEFT');
        $this->db->join('files','item_category.category_icon = files.id','LEFT');
        $this->db->where('auctions.access_type', 'online');
        $this->db->where('auctions.status', 'active');

         $this->db->where('auctions.expiry_time >',date('Y-m-d H:i'));
        $this->db->where('auctions.start_time <',date('Y-m-d H:i'));
        // $this->db->join('item','item.category_id = item_category.id','LEFT');
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
          return $query->result_array();
        }
        else
          return false;
      }
    
      public function upcoming_auctions($date)
      {
        $date= new DateTime("now");
        $current_date=$date->format('Y-m-d');
        $this->db->select('auctions.id,auctions.title, files.name as file_name');
        $this->db->from('auctions');
        $this->db->where('auctions.start_time >',$current_date);
        $this->db->join('item_category','item_category.id = auctions.category_id','left');   
        $this->db->join('files','files.id = item_category.category_icon','left');   
        $query=$this->db->get(); 
        if ($query->num_rows() > 0) {
          return $query->result_array();
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

      public function check_user_number($number,$id='')
        {
        $this->db->where('mobile',$number);
        if ($id) {
          $this->db->where('id !=',$id);
        }
        $query = $this->db->get('users');
        if ($query->num_rows() > 0){
          return true;
        }
        else{
          return false;
        }
      }

      public function check_email_user($email,$id='')
      {
        $this->db->where('email',$email);
        if ($id) {
          $this->db->where('id !=',$id);
        }
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
        }else{
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
        }else{
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

      public function update_user($id, $data)
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

      public function update_item_docs($id, $data)
      {
        $this->db->where('id', $id);
        $return=$this->db->update('item', $data);  
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
         
      public function update_password($id, $data)
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

      public function item_docs_listing($id = 0)
      {
        $this->db->select('*');
        $this->db->from('item');
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

      public function update_mobile($id, $data)
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

      public function add_wishlist($data){
        $return=$this->db->insert('favorites_items', $data);  
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

      public function deleteWish($id,$item_id)
      {
        $this->db->from('favorites_items');
        $this->db->where('favorites_items.user_id', $id);
        $this->db->where('favorites_items.item_id', $item_id);
        $this->db->delete('favorites_items');
        // print_r($this->db->last_query());die();
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
      }
  
      public function get_docs_details($id)
      {
       $this->db->select('documents');
       $this->db->from('users');
       $this->db->where('users.id',$id);
       $this->db->join('files','files.id = users.documents');

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

      public function get_items_details($id){
        $this->db->select('favorites_items.item_id,item.*,auctions.bid_options');
        $this->db->from('favorites_items');
        $this->db->join('item','item.id=favorites_items.item_id');
        $this->db->join('auctions','auctions.category_id = item.category_id');
        $return=$this->db->get();
        if($return)
        {
            return $return->result_array();
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
        $this->db->select('item.id as itm_id,item.name,item.category_id as cat_id,item.detail as itm_detail,item.seller_id,item.status,item.price,item.item_images as file_name,item_category.title as cat_title,auctions.start_time,auction_items.bid_end_time as expiry_time,files.name as file_name');
        $this->db->from('item');
        $this->db->join('item_category','item_category.id = item.id', 'LEFT');
        $this->db->join('auction_items','auction_items.item_id = item.id', 'LEFT');
        $this->db->join('auctions','auctions.category_id = item.category_id', 'LEFT');
        $this->db->join('files ','files.id=item.item_images', 'left', 'LEFT');
        $this->db->where('item.seller_id',$id);
        $this->db->where('item.status','active');
        $this->db->where('auction_items.bid_end_time >', date('Y-m-d H:i'));
        $this->db->where('auction_items.bid_start_time <', date('Y-m-d H:i'));
        $this->db->where('sold', 'no');
        $this->db->group_by('item.id');
        $this->db->order_by('auctions.id','desc');
        // return $num_rows = $this->db->count_all_results();

        $query=$this->db->get();
        // $this->db->count($query);
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
       public function get_auction_items($id,$offset='',$limit='')
      {
        // date_default_timezone_set('Australia/Melbourne');
        
        $this->db->select('auction_items.id as auction_items_id, auction_items.bid_end_time as expiry_time,auction_items.bid_start_price as start_price, auctions.title, auctions.start_time,  item.id as itm_id,item.name ,item.price,item.created_on, item.detail as itm_detail, files.name as file_name,auction_items.sold_status');
        $this->db->from('auction_items');
        $this->db->join('auctions','auction_items.auction_id = auctions.id','left'); 
        $this->db->join('item','auction_items.item_id = item.id','left');             
        $this->db->join('files ','files.id = item.item_images', 'left');

        $this->db->where('auctions.id',$id); 
        $this->db->where('auction_items.bid_end_time >',date('Y-m-d H:i'));
        $this->db->where('auction_items.bid_start_time <',date('Y-m-d H:i'));
        // $this->db->where('item.sold', 'no');
        $this->db->where('sold_status', 'not');
        if(isset($offset) && !empty($offset)) {
          $this->db->offset($offset);
        }
        if(isset($limit) && !empty($limit)) {
          $this->db->limit($limit);
        } 

        $query=$this->db->get();
        if ($query->num_rows()) {
          return $query->result_array();
        }
        else
        {
          return array();
        }
      }

      /////pagination for item_list api
      public function item_list_pagination($offset='',$limit='' ,$cat_id='')
      {
        $this->db->select('*');
        $this->db->from('item');
        // $this->db->where('item_status','created');
        // $this->db->like('category_id',$cat_id);
        $this->db->order_by('updated_on' , 'DESC');
        if(isset($offset) && !empty($offset)) {
          $this->db->offset($offset);
        }
        if(isset($limit) && !empty($limit)) {
          $this->db->limit($limit);
        } 
        if(!empty($cat_id)) {
          // $this->db->like('category_id', $cat_id);
          $this->db->where('category_id', $cat_id);
        }
        $query=$this->db->get();
        if ($query->num_rows()) {
          return $query->result_array();
        }
        else
        {
          return array();
        }
      }
      // public function get_online_auctions($id)
      // {
      //     $where = [
      //       'auctions.status' => 'active',
      //       'auctions.access_type' => 'online',
      //       'auctions.start_time <=' => date('Y-m-d H:i:s'),
      //       'auctions.expiry_time >=' => date('Y-m-d H:i:s')
      //     ];

      //     $auctions = $this->db->select('auctions.*,item_category.title as cat_title,item_category.category_icon as cat_icon')
      //     // $auctions = $this->db->select('auctions.*,item_category.title as cat_title,item_category.category_icon as cat_icon,auction_items.auction_id,auction_items.sold_status')
      //       ->from('auctions')
      //       ->join('item_category', 'auctions.category_id = item_category.id', 'LEFT')
      //       // ->join('auction_items','auction_items.auction_id = auctions.id')
      //       ->where($where)
      //       ->get()->result_array();

      //     return $auctions;
      // }

      public function item_details_cat($id)
      {
        $this->db->select('
          auction_items.item_id as auc_item_id,
          auction_items.auction_id, 
          auction_items.bid_end_time as expiry_time,
          auction_items.bid_start_price as start_price, 
          auction_items.minimum_bid_price as minimum_bid_price,
          item.name, 
          item.item_images,
          item.category_id,
          item_makes.title as make,
          item_models.title as model,
          item_models.title as model,
          auctions.start_time,
          auctions.bid_options,
          vehicle_detail.model_type,
          vehicle_detail.body,
          vehicle_detail.car_title,
          bid.id,
          bid.end_price');

        $this->db->from('auction_items');  
        $this->db->join('item','item.id = auction_items.item_id', 'left');  
        $this->db->join('files','files.id = item.item_images', 'left');  
        $this->db->join('item_makes','item_makes.id = item.make', 'left');  
        $this->db->join('item_models','item_models.id = item.model', 'left');  

        $this->db->join('auctions','auctions.category_id = item.category_id', 'left');  
        $this->db->join('vehicle_detail','vehicle_detail.car_id = item.category_id', 'left');  
        $this->db->join('bid','item.id = bid.item_id', 'left');


        $this->db->where('item.id',$id);
        $this->db->where('auction_items.status','active');  
        $this->db->order_by('bid.id','DESC');  
        $query=$this->db->get();
        if ($query->num_rows()) {
          return $query->row_array();
        }
        else
        {
          return array();
        }
      }

      public function item_Inventory_items($id)
      {
        $this->db->select('auction_items.item_id as auc_item_id,item.name, item.item_images ,files.name as file_name,auctions.start_time,auctions.bid_options,auctions.expiry_time,vehicle_detail.model_type,vehicle_detail.body,vehicle_detail.car_title,bid.id,bid.end_price');
        $this->db->from('auction_items');  
        $this->db->join('item','item.id = auction_items.item_id');  
        $this->db->join('files','files.id = item.item_images');  

        $this->db->join('auctions','auctions.category_id = item.category_id');  
        $this->db->join('vehicle_detail','vehicle_detail.car_id = item.category_id');  
        $this->db->join('bid','bid.item_id = item.category_id');  

        $this->db->where('auction_items.item_id',$id);  
        $this->db->where('auction_items.status','active');  

        $query=$this->db->get();
        if ($query->num_rows()) {
          return $query->row_array();
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

      public function get_item_category_active()
      {
      $this->db->select('*');
      $this->db->from('item_category');
      $this->db->where('status', 'active');
      $this->db->order_by('title', 'asc');

      $result = $this->db->get()->result_array();
      return $result;
      }

      public function get_all_sales_user()
      {
        $this->db->select('users.*,acl_roles.name as role_name');
        $this->db->from('users');
        $this->db->join('acl_roles',' acl_roles.id = users.role');
        $this->db->where('users.status',1);
        $this->db->where('users.role',4);
        $this->db->order_by('id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
      }

      public function checkDeposit($userId)
      {
        $this->db->select('*');
        $this->db->from('user_deposit_detail');
        $this->db->where('user_id',$userId);
        $query= $this->db->get()->row_array();
        return $query;
      }

      public function updateDeposit($id,$data)
      {
        $this->db->where('user_id', $id);
        $return = $this->db->update('user_deposit_detail',array('amount' => $data));
        // print_r($this->db->last_query());
        return  $this->db->affected_rows();
      }

      public function catList($id)
      {
        $this->db->select(' item.category_id, item.make,item.model,valuation_make.title as make_title,valuation_model.title as model_title');
        $this->db->from('item');
        $this->db->join('valuation_make','valuation_make.id=item.make');
        $this->db->join('valuation_model','valuation_model.valuation_make_id=item.model');
        $this->db->where('category_id',$id);
        $this->db->where('item.status','active');
        $query= $this->db->get()->result_array();
        return $query;
      }

      public function GeneralList()
      {
        $this->db->select('item.category_id,item.subcategory_id,item.status,item_subcategories.category_id,item_subcategories.title');
        $this->db->from('item');
        $this->db->join('item_subcategories','item_subcategories.category_id = item.category_id');
        $this->db->where('item.status','active');
        $query= $this->db->get()->result_array();
        return $query;
      }
      //get faqs 
      public function getFaq()
      {
        $this->db->select('*');
        $this->db->from('ques_ans');
        $query= $this->db->get()->result_array();
        return $query;
      }
      //insert item
      public function insert_item($result)
      {
        $this->db->insert('item', $result);
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

      public function insert_item_fields_data($data)
      {
        $this->db->insert('item_fields_data', $data);
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

      public function check_items_field_data($item_id,$category_id,$field_id)
      {
          $this->db->select('*');
          $this->db->from('item_fields_data');
          $this->db->where('category_id', $category_id);
          $this->db->where('item_id', $item_id);
          $this->db->where('fields_id', $field_id);
          $query = $this->db->get();
          if ($query->num_rows() > 0) {
              return true;
          } else {
              return false;
          }
      }
      
      public function update_item_fields_data($item_id,$category_id,$field_id, $data)
      {
          $this->db->where('category_id', $category_id);
          $this->db->where('item_id', $item_id);
          $this->db->where('fields_id', $field_id);
          $query = $this->db->update('item_fields_data', $data);
          if($query)
          {
              return $query;
          }
          else
          {
              return false;
          }
      }
      //upadte item
      public function update_item($id, $data)
      {
        $this->db->where('id', $id);
        $query = $this->db->update('item', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
      }

      public function get_item_byid($id)
      {
          $this->db->select('*');
          $this->db->from('item');
          $this->db->where('id',$id);
          $result = $this->db->get()->result_array();
          return $result;
      }

      //get makes
      public function get_makes_list_active()
      {
        $this->db->select('*');
        $this->db->from('item_makes');
        $this->db->where('status', 'active');
        $this->db->order_by('title', 'asc');
        $result = $this->db->get()->result_array();
        return $result;
      }
      //item sub categories
      public function get_item_subcategory_list($category_id='')
      {
        $this->db->select('*');
        if($category_id != '')
        {
            $this->db->where('category_id', $category_id);
            $this->db->order_by('title', 'asc');
        }
        $this->db->from('item_subcategories');
        $result = $this->db->get()->result_array();
        return $result;
      }
      //get models
      public function get_item_model_list_active($make_id='')
      {
        $this->db->select('*');
        if($make_id != '')
        {
            $this->db->where('make_id', $make_id);
        }
        $this->db->where('status', 'active');
        $this->db->order_by('title', 'asc');
        $this->db->from('item_models');
        $result = $this->db->get()->result_array();
        return $result;
      }
      //item dynamic fields
      public function fields_data($id) 
      {
        $this->db->select('*');
        $this->db->from('item_category_fields');
        $this->db->where('category_id',$id);
        $query=$this->db->get();
        return $query->result_array();
           
      }

      public function get_item_fields_with_data($category_id,$item_id)
      {
        $select = 'item_category_fields.*,item_fields_data.value as selected_value';
        $this->db->select($select);
        $this->db->from('item_category_fields');
        $this->db->join('item_fields_data', 'item_category_fields.id = item_fields_data.fields_id', 'LEFT');
        $this->db->where(['item_fields_data.category_id' => $category_id, 'item_fields_data.item_id'=>$item_id]);
        $this->db->order_by('id','asc');
        $query = $this->db->get();
        return $query->result_array();
      }
    //////////////////// save item  ////////////////////////
      // public function get_item_category_activee()
      // {
      // $this->db->select('*');
      // $this->db->from('item_category');
      // $this->db->where('status', 'active');                 
      // $result = $this->db->get()->result_array();
      // return $result;
      // }

      // public function get_all_sales_userr()
      // {
      //   $this->db->select('users.*,acl_roles.name as role_name');
      //   $this->db->from('users');
      //   $this->db->join('acl_roles',' acl_roles.id = users.role');
      //   $this->db->where('users.status',1);
      //   $this->db->where('users.role',4);
      //   $this->db->order_by('id', 'desc');
      //   $result = $this->db->get()->result_array();
      //   return $result;
      // }

      public function categoryCount($id)
      {
        $this->db->select('itme.category_id,item_category.id');
        $this->db->from('item');
        $this->db->where('item.category_id',$id);
        $this->db->join('item_category','item_category.id = item.category_id');
        return $num_rows = $this->db->count_all_results();
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
         return $query->result_array();
        }
      }

      public function notificationStatus($id,$status)
      {
        $this->db->select('*');
        $this->db->from('notification');
        $this->db->where('id',$id);
        $this->db->update('notification' , array('status' => $status));
        // print_r($this->db->last_query());
        return $this->db->affected_rows();
      }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////inspection app/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


      public function appraise_login_check($username, $password)
      {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $username);
        $this->db->where('password', $password);
        $this->db->where('role', '9');
        $this->db->where('status', '1');
        $query = $this->db->get();
        if($query->num_rows() > 0)
          return $query->result();
        else
          return false;
      }


      /////new functions 
      public function get_catagories_home()
      {
        $this->db->select('item_category.id as cat_id,item_category.title as cat_title,files.name as image_name ,files.id,files.path');
        $this->db->from('item_category');
        $this->db->join('files','files.id = item_category.category_icon');
        $this->db->where('item_category.status','active');
        $this->db->where('show_web' , 'yes');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
         return $query->result_array();
        }
      }

      public function get_item_list_against_cat($id,$type,$offset='',$limit=''){
        $this->db->select('auctions.*');
        $this->db->from('auctions');
        // $this->db->join('auction_items','auction_items.auction_id = auctions.id');
        $this->db->where('auctions.category_id',$id);
        $this->db->where('auctions.access_type',$type);
        $this->db->where('auctions.status','active');
        if(isset($offset) && !empty($offset)) {
          $this->db->offset($offset);
        }
        if(isset($limit) && !empty($limit)) {
          $this->db->limit($limit);
        } 
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
         return $query->row_array();
        }
      }

}
?>
