<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Live_auction_Model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        //$this->load->database();
    }

	public function get_live_auctions()
	{
		$where = [
		'auctions.status' => 'active',
		'auctions.access_type' => 'live',
		'auctions.start_time <=' => date('Y-m-d H:i:s'),
		'auctions.expiry_time >=' => date('Y-m-d H:i:s')
		];

		$auctions = $this->db->select('auctions.*,item_category.title as cat_title,item_category.category_icon as cat_icon')
			->from('auctions')
			->join('item_category', 'auctions.category_id = item_category.id', 'LEFT')
			->where($where)
			->get()->result_array();

		return $auctions;
	}

 // custom 
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

  public function fields_data($id) 
  {
    $this->db->select('*');
    $this->db->from('item_category_fields');
    $this->db->where('category_id',$id);
    $query=$this->db->get();
    return $query->result_array();
       
  }

  // end



	public function get_live_auction_items($auction_id,$limit='',$offset='',$item_ids=array(),$order_by='')
  {
    $select = '
      ai.id as auction_item_id,
      ai.item_id as item_id,
      ai.sold_status,
      ai.auction_id as auction_id,
      ai.bid_start_price as bid_start_price,
      ai.allowed_bids as allowed_bids,
      ai.minimum_bid_price as minimum_bid_price,
      ai.bid_start_time as bid_start_time,
      ai.security as security,
      ai.bid_end_time as bid_end_time,
      ai.lot_no as lot_no,
      ai.order_lot_no as order_lot_no,

      i.name as item_name,
      i.price as item_price,
      i.year as year,
      i.mileage as mileage,
      i.mileage_type as mileage_type,
      i.specification as specification,
      i.lot_id as item_lot_id,
      i.category_id as category_id,
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
      i.other_charges as item_other_charges,

      ic.include_make_model as item_make_model,
      ic.title as cat_title
      ';

    $where = [
      'ai.auction_id' => $auction_id,
      'i.sold' => 'no',
      'ai.sold_status' => 'not'
    ];

    $this->db->select($select);
    $this->db->from('auction_items ai');
    $this->db->join('item i', 'i.id = ai.item_id', 'LEFT');
    $this->db->join('item_category ic', 'i.category_id = ic.id', 'LEFT');
    $this->db->where($where);

    if( ! empty($item_ids)){
      $this->db->where_in('ai.item_id', $item_ids);
    }
    
    if( ! empty($limit)){
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
      $this->db->order_by('ai.id',"desc");
    }

    $auction_items = $this->db->get();
    $auction_items = $auction_items->result_array();
    // echo $this->db->last_query();die();
    return $auction_items;
  }

 

  public function get_close_auction_items($id,$limit='',$offset='',$item_ids=array(),$order_by='')
  {
    $select = '
      ai.id as auction_item_id,
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
      i.name as item_name,
      i.price as item_price,
      i.year as year,
      i.mileage as mileage,
      i.mileage_type as mileage_type,
      i.specification as specification,
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
      'i.sold' => 'no',
      'ai.sold_status' => 'not',
      'ai.bid_start_time <' => date('Y-m-d H:i')
    ];

    $this->db->select($select);
    $this->db->from('auction_items ai');
    $this->db->join('item i', 'i.id = ai.item_id', 'LEFT');
    $this->db->where($where);
    $this->db->where_in('ai.auction_id', $id);

    if( ! empty($item_ids)){
      $this->db->where_in('ai.item_id', $item_ids);
    }
    
    if( ! empty($limit)){
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
      $this->db->order_by('ai.id',"desc");
    }

    $auction_items = $this->db->get();
    $auction_items = $auction_items->result_array();
    // echo $this->db->last_query();die();
    return $auction_items;
  }

 //  public function get_items_details($item_id,$auction_id='')
 //  {

 //    $select = 'ai.id as auction_item_id,
 //              ai.item_id as item_id,
 //              ai.auction_id as auction_id,
 //              ai.category_id as category_id,
 //              ai.allowed_bids as allowed_bids,
 //              ai.bid_start_price as bid_start_price,
 //              ai.minimum_bid_price as minimum_bid_price,
 //              ai.security as security,
 //              ai.lot_no as lot_no,
 //              ai.bid_start_time as bid_start_time,
 //              ai.bid_end_time as bid_end_time,
 //              i.id as id,
 //              i.name as item_name,
 //              i.location as item_location,
 //              i.lat as item_lat,
 //              i.lng as item_lng,
 //              i.detail as item_detail,
 //              i.price as item_price,
 //              i.subcategory_id as subcategory_id,
 //              i.lot_id as item_lot_id,
 //              i.unique_code as item_unique_code,
 //              i.unique_code as item_unique_code,
 //              i.registration_no as item_registration_no,
 //              i.feature as item_feature,
 //              i.barcode as item_barcode,
 //              i.make as item_make,
 //              i.model as item_model,
 //              i.vin_number as item_vin_number,
 //              i.item_images as item_images,
 //              i.item_attachments as item_attachments,
 //              i.item_test_report as item_test_report,
 //              i.seller_id as item_seller_id,
 //              i.keyword as item_keyword,
 //              i.item_status as item_status,
 //              i.sold as item_sold,
 //              i.in_auction as item_in_auction,
 //              i.other_charges as item_other_charges,
 //              icf.label as field_label,
 //              ifd.value as field_value,
 //              bid.bid_amount as current_bid
 //              ';

 //    $where = [
 //      'i.id' => $item_id
 //    ];
 //    $this->db->select($select);
 //    $this->db->from('auction_items ai');
 //    $this->db->join('item i', 'i.id = ai.item_id', 'LEFT');
 //    $this->db->join('item_category_fields icf', 'icf.category_id = ai.category_id', 'LEFT');
 //    $this->db->join('item_fields_data ifd', 'ifd.fields_id = icf.id', 'LEFT');
 //    $this->db->join('bid', 'bid.item_id = ai.item_id and bid.auction_id = ai.auction_id', 'LEFT');
 //    $this->db->where($where);

 //    $this->db->order_by('bid.id',"desc");
 //    $item = $this->db->get();
 //    $item = $item->row_array();
 //    // echo $this->db->last_query();die();
 //    return $item  ;
 //  }

 //  public function get_properties($id)
 //  {
 //    $select = 'if.*,ic.label as label';
                
 //    $auction_items = $this->db->select($select)
 //      ->from('item_fields_data if')
 //      ->join('item_category_fields ic', 'if.fields_id = ic.id', 'LEFT')
 //      ->where(['if.category_id' => $id])
 //      ->get()->result_array();

 //      echo $this->db->last_query();
    
 //    return $auction_items;
 //  }
}