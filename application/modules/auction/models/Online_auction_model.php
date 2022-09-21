<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Online_auction_Model extends CI_Model
{

  function __construct()
  {
      parent::__construct();
  }

	public function get_online_auctions()
	{
		$where = [
  		'auctions.status' => 'active',
  		'auctions.access_type' => 'online',
  		'auctions.start_time <=' => date('Y-m-d H:i:s'),
  		'auctions.expiry_time >=' => date('Y-m-d H:i:s')
		];

		$auctions = $this->db->select('auctions.*,item_category.title as cat_title,item_category.category_icon as cat_icon')
    // $auctions = $this->db->select('auctions.*,item_category.title as cat_title,item_category.category_icon as cat_icon,auction_items.auction_id,auction_items.sold_status')
			->from('auctions')
			->join('item_category', 'auctions.category_id = item_category.id', 'LEFT')
      // ->join('auction_items','auction_items.auction_id = auctions.id')
			->where($where)
			->get()->result_array();

		return $auctions;
	}

  public function get_sold()
  {
    $this->db->select('auctions.id,auction_items.*');
    $this->db->from('auction_items');
    $this->db->where('sold_status','sold');
    $this->db->join('auctions','auctions.id = auction_items.auction_id');

    $query = $this->db->get();
    if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
  }

  public function get_limit($limit, $start) {
    $this->db->limit($limit, $start);
    $this->db->from('auction_items');
    $query = $this->db->get();
    return $query->result();
  }


	public function get_online_auction_items($auction_id,$limit='',$offset='',$item_ids=array(),$order_by='')
	{
		$select = 'ai.id as auction_item_id,
              ai.item_id as item_id,
              ai.sold_status,
              ai.auction_id as auction_id,
              ai.bid_start_price as bid_start_price,
              ai.allowed_bids as allowed_bids,
              ai.minimum_bid_price as minimum_bid_price,
              ai.bid_start_time as bid_start_time,
              ai.category_id as category_id,
              ai.security as security,
              ai.bid_end_time as bid_end_time,
              ai.lot_no as lot_no,
              ai.order_lot_no as order_lot_no,
              i.specification as specification,
              i.name as item_name,
              i.price as item_price,
              i.year as year,
              i.mileage as mileage,
              i.mileage_type as mileage_type,
              i.lot_id as item_lot_id,
              i.subcategory_id as subcategory_id,
              i.detail as item_detail,
              i.feature as item_feature,
              i.unique_code as item_unique_code,
              i.registration_no as item_registration_no,
              i.barcode as item_barcode,
              i.unique_code as item_unique_code,
              i.vin_number as item_vin_number,
              i.make as item_make,
              i.item_images as item_images,
              i.model as item_model,
              i.item_attachments as item_attachments,
              i.keyword as item_keyword,
              i.item_test_report as item_test_report,
              i.seller_id as item_seller_id,
              i.item_status as item_status,
              i.sold as item_sold,
              i.in_auction as item_in_auction,
              i.other_charges as item_other_charges
              ';

    $where = [
      'ai.auction_id' => $auction_id,
      'i.sold' => 'no',
      'ai.bid_start_time <' => date('Y-m-d H:i'),
      'ai.bid_end_time >' => date('Y-m-d H:i'),
      // 'ai.sold_status' => 'not'
    ];

    $this->db->select($select);
    $this->db->from('auction_items ai');
    $this->db->join('item i', 'i.id = ai.item_id', 'LEFT');
    $this->db->where('ai.sold_status','not');
    $this->db->where($where);

    if(! empty($item_ids)){
      $this->db->where_in('ai.item_id', $item_ids);
    }
    
    if(! empty($limit)){
      $this->db->limit($limit, $offset);
    }

    if (! empty($order_by)) {
      if ($order_by == 'hp') {
        $this->db->order_by('i.price',"desc");
      }
      if ($order_by == 'lp') {
        $this->db->order_by('i.price',"asc");
      }
    }else{
      $this->db->order_by('ai.order_lot_no',"asc");
    }

    $auction_items = $this->db->get();
    $auction_items = $auction_items->result_array();
    // echo $this->db->last_query();die();
    return $auction_items;
	}

  // public function item_heighest_bid_data($item_id='',$auction_id='')
  // {
  //   $select = 'Select bid.bid_amount as current_bid, bid.bid_status, bid.user_id from bid inner join  ( Select max(bid_time) as LatestDate, item_id  from bid  Group by bid.item_id  ) SubMax on bid.bid_time = SubMax.LatestDate and bid.item_id = SubMax.item_id  WHERE bid.item_id = '.$item_id.' AND bid.auction_id = '.$auction_id.';';

  //   $query = $this->db->query($select);
  //   if ($query->num_rows() > 0) {
  //     return $query->row_array();
  //   } else {
  //     return array();
  //   }
  // }
  public function item_heighest_bid_data($item_id='',$auction_id='')
  {
    $select = "Select bid.bid_amount as current_bid, bid.bid_status, bid.user_id from bid inner join  ( Select max(bid.id) as bidId, item_id  from bid  Group by bid.item_id  ) SubMax on bid.id = SubMax.bidId and bid.item_id = SubMax.item_id  WHERE bid.item_id = '".$item_id."' AND bid.auction_id = '".$auction_id."'";

    $query = $this->db->query($select);
    if ($query->num_rows() > 0) {
      return $query->row_array();
    } else {
      return array();
    }
  }

  public function get_items_details($item_id='',$auction_id='')
  {

    $select = 'ai.id as auction_item_id,
              ai.item_id as item_id,
              ai.allowed_bids as allowed_bids,
              ai.auction_id as auction_id,
              ai.security as security,
              ai.deposit as deposit,
              ai.bid_start_price as bid_start_price,
              ai.minimum_bid_price as minimum_bid_price,
              ai.bid_start_time as bid_start_time,
              ai.lot_no as lot_no,
              ai.bid_end_time as bid_end_time,
              ai.order_lot_no as order_lot_no,

              i.id as id,
              i.name as item_name,
              i.category_id as category_id,
              i.specification as specification,
              i.location as item_location,
              i.lat as item_lat,
              i.lng as item_lng,
              i.price as item_price,
              i.registration_no as item_registration_no,
              i.subcategory_id as subcategory_id,
              i.lot_id as item_lot_id,
              i.detail as item_detail,
              i.unique_code as item_unique_code,
              i.unique_code as item_unique_code,
              i.barcode as item_barcode,
              i.item_images as item_images,
              i.make as item_make,
              i.mileage_type as mileage_type,
              i.mileage as mileage,
              i.year as year,
              i.feature as item_feature,
              i.model as item_model,
              i.vin_number as item_vin_number,
              i.keyword as item_keyword,
              i.item_attachments as item_attachments,
              i.item_test_report as item_test_report,
              i.item_status as item_status,
              i.inspected as inspected,
              i.sold as item_sold,
              i.in_auction as item_in_auction,
              i.seller_id as item_seller_id,
              i.other_charges as item_other_charges,
              i.terms as terms,
              i.additional_info as additional_info
              ';

    $where = [
      'i.id' => $item_id
    ];

    $this->db->select($select);
    $this->db->from('auction_items ai');
    $this->db->join('item i', 'ai.item_id = i.id', 'LEFT');
    $this->db->where($where);
    if (!empty($auction_id)) {
      $this->db->where('ai.auction_id', $auction_id);
    }

    $item = $this->db->get();
    $item = $item->row_array();
    // echo $this->db->last_query();die();
    return $item  ;
  }


  //////////////
 
  public function get_properties($id)
  {
    $select = 'if.*,ic.label as label';
                
    $auction_items = $this->db->select($select)
      ->from('item_fields_data if')
      ->join('item_category_fields ic', 'if.fields_id = ic.id', 'LEFT')
      ->where(['if.category_id' => $id])
      ->get()->result_array();

    //echo $this->db->last_query();
    return $auction_items;
  }

  public function fields_data($id) 
  {
    $this->db->select('*');
    $this->db->from('item_category_fields');
    $this->db->where('category_id',$id);
    $query=$this->db->get();
    return $query->result_array();
       
  }

  public function get_itemfields_byItemid($item_id,$fields_id='')
    {
    $this->db->select('*');
    $this->db->from('item_fields_data');
    $this->db->where('item_id',$item_id);
    $this->db->where('fields_id',$fields_id);
    $this->db->order_by('id','asc');
    $result = $this->db->get()->row_array();
    return $result;
  }

  public function used_bid_limit($user_id, $item_id){
    $query = 'Select bid.buyer_id, sum(bid.bid_amount) as total_bid
    from bid 
    inner join  ( 
        Select max(bid.id) as LatestId, item_id  
        from bid 
        Group by bid.item_id  
    ) SubMax on bid.id = SubMax.LatestId and bid.item_id = SubMax.item_id 
    inner join auction_items on bid.item_id = auction_items.item_id and bid.auction_id = auction_items.auction_id
    left join invoices on bid.item_id = invoices.item_id and bid.auction_id = invoices.auction_id
    WHERE bid.buyer_id = '.$user_id.' AND bid.item_id !='.$item_id.' AND auction_items.sold_status IN("not") and invoices.id IS NULL
    GROUP BY bid.buyer_id';
    $heighest_bids = $this->db->query($query)->row_array();
    return $heighest_bids;
  }

  public function sum_user_heighest_bids($user_id){
    $query = 'Select bid.buyer_id, sum(bid.bid_amount) as total_bid
    from bid 
    inner join  ( 
        Select max(bid.id) as LatestId, item_id  
        from bid 
        Group by bid.item_id  
    ) SubMax on bid.id = SubMax.LatestId and bid.item_id = SubMax.item_id 
    inner join auction_items on bid.item_id = auction_items.item_id and bid.auction_id = auction_items.auction_id
    left join invoices on bid.item_id = invoices.item_id and bid.auction_id = invoices.auction_id
    WHERE bid.buyer_id = '.$user_id.' AND auction_items.sold_status IN("not", "sold","approval") and invoices.id IS NULL
    GROUP BY bid.buyer_id';
    $heighest_bids = $this->db->query($query)->row_array();
    return $heighest_bids;
  }

}// end model