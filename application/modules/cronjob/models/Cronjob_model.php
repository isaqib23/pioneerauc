<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cronjob_Model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    

    public function get_auctions($id='')
    {
        $this->db->select('*');
        $this->db->from('auctions');
        if(!empty($id))
        {
            $this->db->where('id', $id);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getWinItems($buyerId='')
    {
        $select = "
            sold_items.payable_amount AS payable_amount,
            sold_items.auction_id AS auction_id,
            sold_items.item_id AS item_id,
            sold_items.auction_item_id AS auction_item_id,
            auction_items.bid_end_time AS bid_end_time,
        ";

        $where = [
            'sold_items.buyer_id' => $buyerId, 
            'sold_items.payment_status' => 0,
            'sold_items.sale_type' => 'online'
        ];

        $this->db->select($select);
        $this->db->from('sold_items');
        $this->db->join('auction_items', 'auction_items.id = sold_items.auction_item_id');
        $this->db->where($where);
        $this->db->order_by('auction_items.bid_end_time', 'ASC');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }

}//end model
