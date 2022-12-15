<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home_model extends CI_Model
{
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}  


  public function get_active_auction_categories()
  {
    $where_condition = [
      'status' => 'active',
      'show_web' => 'yes'
    ];

    $online_auctions = $this->db->select('*')
      ->from('item_category')
      ->where($where_condition)
	  ->where('id !=' , 1)
      ->order_by('sort_order', 'ASC')
      ->limit(9, 0)
      ->get()->result_array();

    return $online_auctions;
  }

  public function get_online_auctions($category_id='')
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

    $online_auctions = $this->db->select('auctions.*,item_category.title as cat_title,item_category.category_icon as cat_icon')
      ->from('auctions')
      ->join('item_category', 'auctions.category_id = item_category.id', 'LEFT')
      ->where($where_condition)
      ->get()->row_array();

    return $online_auctions;
  }

	public function check_numbers($mobile)
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

      public function login_check($email, $password)
        {
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('email', $email);
            $this->db->where('password', $password);
            // $this->db->where('status', '1');
            $query = $this->db->get();
            if($query->num_rows() > 0)
                return $query->result();
            else
                return false;
        }


         function check_email_reset($email)
      {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email',$email);
        $query=$this->db->get();
        if ($query->num_rows()> 0) {
          return $query->result_array();
        }
        else
        {
          return array();
        }
      }

      public function check_user_email_reset($email){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email',$email);
        $this->db->where('role',4);
        $query=$this->db->get();
        if ($query->num_rows()> 0) {
          return $query->result_array();
        }
        else
        {
          return array();
        }
      }

      public function insert_code($email)
      {
        $this->db->insert('reset_password_code');
        $this->db->where('email',$email);
      }

         public function check_email($email)
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

     public function insert_user($data)
    {
        $this->db->insert('users', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }

     public function verify_code($id)
    {
      $this->db->select('code');
      $this->db->from('users');
      $this->db->where('id',$id);
      $query=$this->db->get();
      if ($query->num_rows() > 0) {
        return $query->row_array();
      }
      else
      {
        return array();
      }
    }

  public function upcoming_auctions_home($date)
  {
    $date= new DateTime("now");
    $current_date=$date->format('Y-m-d H:i:s');
    $this->db->select('auctions.id,auctions.title as auction_title, auctions.detail as auctionDetail, item_category.category_icon as categoryIcon, item_category.id as categoryId,auction_items.auction_id,auctions.start_time,auction_items.item_id,item.id,item.item_images,files.id,files.name,files.path,item.name as item_name');
    $this->db->from('auctions');
    $this->db->where('auctions.start_time >',$current_date);
    $this->db->or_where('auctions.expiry_time >',$current_date);
    $this->db->where('auctions.access_type','live');
    $this->db->where('item_category.status','active');
    $this->db->join('auction_items','auction_items.auction_id = auctions.id','left'); 
    $this->db->join('item_category','item_category.id = auctions.category_id','inner'); 
    $this->db->join('item','item.id = auction_items.item_id','left'); 
    $this->db->join('files','files.id = item_category.category_icon','left');
    $this->db->order_by('auctions.start_time','ASC');
    $query=$this->db->get(); 
    if ($query->num_rows() > 0) {
      return $query->result_array();
    }
    else
      return false;
  }

  public function singleOnlineAuctionsWithItem()
  {
    $date= new DateTime("now");
    $current_date=$date->format('Y-m-d H:i:s');
    $this->db->select('auctions.id, auctions.title as auction_title, auctions.detail as auctionDetail, auction_items.auction_id,auctions.start_time,auction_items.item_id,item_category.id as categoryId,item.id,item.item_images,files.id,files.name,files.path,item.name as item_name');
    $this->db->from('auctions');
    $this->db->where('auctions.start_time <=',$current_date);
    $this->db->where('auctions.expiry_time >=',$current_date);
    $this->db->where('access_type','online');
    $this->db->where('auctions.status','active');
    $this->db->where('item_category.status','active');
    // $this->db->where('auction_items.sold_status','not');
    $this->db->join('auction_items','auction_items.auction_id = auctions.id','left');
    $this->db->join('item_category','item_category.id = auctions.category_id','left');
    $this->db->join('item','item.id = auction_items.item_id','left'); 
    $this->db->join('files','files.id = item_category.category_icon','left');
    $this->db->order_by('auctions.start_time','ASC');
    $query=$this->db->get();
    if ($query->num_rows() > 0) {
      return $query->row_array();
    }
    else
      return false;
  }

  public function latestBidsOnItems($cat_id, $aucId)
  {
    $query=$this->db->query('Select bid.id, bid.auction_id, bid.bid_amount , item.item_images, files.name,files.path,item.id as item_id,item.name as item_name,item.detail as item_detail, item.year as itemYear,item.specification as itemSpecification,item.mileage as itemMileage,  item.mileage_type as mileageType, COUNT(online_auction_item_visits.id) AS visits from bid inner join  ( Select max(bid_time) as LatestDate, item_id  from bid  Group by bid.item_id  ) SubMax on bid.bid_time = SubMax.LatestDate and bid.item_id = SubMax.item_id
    INNER JOIN auction_items ON bid.auction_id = auction_items.auction_id AND bid.item_id = auction_items.item_id
    LEFT JOIN item ON item.id = bid.item_id LEFT JOIN files ON files.id = item.item_images 
    LEFT JOIN online_auction_item_visits ON online_auction_item_visits.item_id = auction_items.item_id AND online_auction_item_visits.auction_id = auction_items.auction_id
    WHERE bid.bid_status = "pending"
    AND auction_items.category_id = '.$cat_id.'
    AND auction_items.auction_id = '.$aucId.'
    AND auction_items.sold_status = "not"
    AND (CURRENT_TIMESTAMP() BETWEEN auction_items.bid_start_time AND auction_items.bid_end_time) Group BY item.id ORDER BY bid.id DESC LIMIT 10');
    // $query=$this->db->get();
    if ($query->num_rows() > 0) {
      return $query->result_array();
    }
    else
      return false;
  }

  public function popularBidsOnItems($cat_id, $aucId){
    $select = "SELECT bid.item_id AS itemId, bid.auction_id as auctionId, max(bid.bid_amount) as bidAmount, item.name as itemName,item.item_images as itemImages,item.specification as itemSpecification,item.mileage as itemMileage, item.mileage_type as mileageType, item.year as itemYear, item.detail as itemDetail, COUNT(bid.id) AS bidCount FROM bid
INNER JOIN auction_items ON bid.auction_id = auction_items.auction_id AND bid.item_id = auction_items.item_id
INNER JOIN item ON bid.item_id = item.id
WHERE auction_items.sold_status = 'not'
AND auction_items.category_id = ".$cat_id."
AND auction_items.auction_id = ".$aucId."
AND (CURRENT_TIMESTAMP() BETWEEN auction_items.bid_start_time AND auction_items.bid_end_time)
GROUP BY itemId
ORDER BY bidCount DESC LIMIT 10";
    $query=$this->db->query($select);
    // $query=$this->db->get();
    if ($query->num_rows() > 0) {
      return $query->result_array();
    }
    else
      return false;

  }

  public function nearlyCloseAuctions(){
    $select = "SELECT auction_items.id as auctionItemId, item.id as item_id, item.name, item.item_images, item.detail, auction_items.bid_start_time, auction_items.bid_end_time, auction_items.auction_id, COUNT(online_auction_item_visits.id) AS visits
      FROM auction_items 
      INNER JOIN item ON item.id = auction_items.item_id
      LEFT JOIN online_auction_item_visits ON online_auction_item_visits.item_id = auction_items.item_id AND online_auction_item_visits.auction_id = auction_items.auction_id
      WHERE (auction_items.bid_end_time BETWEEN CURRENT_TIMESTAMP() and NOW() + INTERVAL 1 DAY)   AND auction_items.sold_status = 'not' 
      GROUP BY item_id,auction_items.category_id
      ORDER BY auction_items.bid_end_time ASC

      LIMIT 10";
      // (CURRENT_TIMESTAMP() BETWEEN auction_items.bid_start_time AND auction_items.bid_end_time)
      // ORDER BY auction_items.bid_end_time ASC


     // print_r( $select);
    $query=$this->db->query($select);
    // $query=$this->db->get();
    //AND auction_items.category_id = ".$cat_id."
    //AND auction_items.auction_id = ".$aucId."
    if ($query->num_rows() > 0) {
      return $query->result_array();
    }
    else
    return false;

  }


  //  public function featured_item()
  // {
  //   $this->db->select('auction_items.auction_id,auction_items.item_id,auction_items.sold_status,auction_items.live_status,item.feature,item.sold,item.name,auctions.start_status');
  //   $this->db->from('auction_items');

  //   $this->db->join('auctions','auctions.id = auction_items.auction_id');
  //   $this->db->join('item','item.id = auction_items.item_id');

  //   $this->db->where('auction_items.sold_status','not');
  //   $this->db->where('auctions.start_status','start');
  //   $this->db->where('auction_items.live_status','yes');
  //   $this->db->where('item.sold','no');
  //   $this->db->where('item.feature','yes');
  //   $query = $this->db->get();
  //   if ($query->num_rows() > 0) {
  //     return $query->result_array();
  //   }
  //   else
  //     return false;
  // }
  public function get_items_categories()
  {
    $this->db->select('auction_items.auction_id,item_category.id,item_category.title,item_category.category_icon, COUNT(online_auction_item_visits.id) AS visits');
    //$this->db->select('auction_items.*,item.id as itemId,item.name, item.detail as item_datail, item.feature, item.item_images, item.year as itemYear,item.specification as itemSpecification, item.mileage as itemMileage, item.mileage_type as mileageType, auctions.expiry_time, auctions.access_type , COUNT(online_auction_item_visits.id) AS visits');
    $this->db->from('auction_items');
    $this->db->join('item','item.id = auction_items.item_id');
    $this->db->join('auctions','auctions.id = auction_items.auction_id');
    $this->db->join('item_category','item_category.id = auction_items.category_id');
    $this->db->join('online_auction_item_visits', 'online_auction_item_visits.item_id = auction_items.item_id AND online_auction_item_visits.auction_id = auction_items.auction_id', 'LEFT');
    $this->db->where('item.feature','yes');
    $this->db->where('item.sold','no');
    $this->db->where('auction_items.sold_status','not');
    $this->db->where('auctions.status','active');
    $this->db->where('auctions.start_time <=', date('Y-m-d H:i:s'));
   $this->db->where('auctions.expiry_time >=', date('Y-m-d H:i:s'));
    $this->db->where('auction_items.bid_start_time <=', date('Y-m-d H:i:s'));
   $this->db->where('auction_items.bid_end_time >=', date('Y-m-d H:i:s'));
   
   $this->db->group_by('item_category.id');
   $this->db->order_by('item_category.sort_order', 'ASC');
    $categories = $this->db->get()->result_array();
    return $categories;
  }
  public function featured_item($cat_id, $auction_id)
  {
    $this->db->select('auction_items.*,item.id as itemId,item.name, item.detail as item_datail, item.feature, item.item_images, item.year as itemYear,item.specification as itemSpecification, item.mileage as itemMileage, item.mileage_type as mileageType, auctions.expiry_time, auctions.access_type ');
    //$this->db->select('auction_items.*,item.id as itemId,item.name, item.detail as item_datail, item.feature, item.item_images, item.year as itemYear,item.specification as itemSpecification, item.mileage as itemMileage, item.mileage_type as mileageType, auctions.expiry_time, auctions.access_type , COUNT(online_auction_item_visits.id) AS visits');
    $this->db->from('auction_items');
    $this->db->join('item','item.id = auction_items.item_id');
    $this->db->join('auctions','auctions.id = auction_items.auction_id');
    $this->db->join('item_category','item_category.id = auction_items.category_id');
    $this->db->join('online_auction_item_visits', 'online_auction_item_visits.item_id = auction_items.item_id AND online_auction_item_visits.auction_id = auction_items.auction_id', 'LEFT');
    $this->db->where('item.feature','yes');
    $this->db->where('item.sold','no');
    $this->db->where('auction_items.sold_status','not');
    $this->db->where('auctions.status','active');
      $this->db->where('auctions.id', $auction_id);
      $this->db->where('item_category.id', $cat_id);
       
    $this->db->where('auctions.start_time <=', date('Y-m-d H:i:s'));
   $this->db->where('auctions.expiry_time >=', date('Y-m-d H:i:s'));
    $this->db->where('auction_items.bid_start_time <=', date('Y-m-d H:i:s'));
   $this->db->where('auction_items.bid_end_time >=', date('Y-m-d H:i:s'));
   $this->db->group_by('item.id');
    $this->db->order_by('auction_items.order_lot_no', 'ASC');
    $this->db->order_by('rand()');
    $featured_items = $this->db->get()->result_array();
    return $featured_items;
  }

  /*public function featured_item($cat_id='')
  {
    $this->db->select('auction_items.*,item.id as itemId,item.name, item.detail as item_datail, item.feature, item.item_images, item.year as itemYear,item.specification as itemSpecification, item.mileage as itemMileage, item.mileage_type as mileageType, auctions.expiry_time, auctions.access_type , COUNT(online_auction_item_visits.id) AS visits');
    $this->db->from('auction_items');
    $this->db->join('item','item.id = auction_items.item_id');
    $this->db->join('auctions','auctions.id = auction_items.auction_id');
    $this->db->join('online_auction_item_visits', 'online_auction_item_visits.item_id = auction_items.item_id AND online_auction_item_visits.auction_id = auction_items.auction_id', 'LEFT');
    $this->db->where('item.feature','yes');
    $this->db->where('item.sold','no');
    $this->db->where('auction_items.sold_status','not');
    $this->db->where('auctions.status','active');
    if ($cat_id) {
      $this->db->where('auction_items.category_id', $cat_id);
    }
    $this->db->where('auction_items.bid_start_time <=', date('Y-m-d H:i:s'));
    $this->db->where('auction_items.bid_end_time >=', date('Y-m-d H:i:s'));
    $this->db->group_by('item.id');
    $featured_items = $this->db->get()->result_array();
    return $featured_items;
  }*/

  public function get_home_slider(){
    $select = "SELECT * FROM home_slider WHERE status = 'active' AND start_date <= NOW() AND end_date >= NOW() ORDER BY sort_order ASC ";
    $query=$this->db->query($select);
    if ($query->num_rows() > 0) {
      return $query->result_array();
    }
    else
      return false;

  }

}
