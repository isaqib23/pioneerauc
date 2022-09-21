<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Search_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}  

	public function getItemSelect($auctionType='online')
	{
		$select = 'ai.id AS auction_item_id,
			ai.item_id AS item_id,
			ai.sold_status AS sold_status,
			ai.auction_id AS auction_id,
			ai.bid_start_price AS bid_start_price,
			ai.allowed_bids AS allowed_bids,
			ai.minimum_bid_price AS minimum_bid_price,
			ai.bid_start_time AS bid_start_time,
			ai.category_id AS category_id,
			ai.security AS security,
			ai.bid_end_time AS bid_end_time,
			ai.lot_no AS lot_no,
			ai.order_lot_no AS order_lot_no,
			i.name AS item_name,
			i.price AS item_price,
			i.year AS year,
			i.mileage AS mileage,
			i.mileage_type AS mileage_type,
			i.lot_id AS item_lot_id,
			i.subcategory_id AS subcategory_id,
			i.detail AS item_detail,
			i.feature AS item_feature,
			i.unique_code AS item_unique_code,
			i.registration_no AS item_registration_no,
			i.barcode AS item_barcode,
			i.unique_code AS item_unique_code,
			i.vin_number AS item_vin_number,
			i.make AS item_make,
			i.item_images AS item_images,
			i.model AS item_model,
			i.item_attachments AS item_attachments,
			i.keyword AS item_keyword,
			i.item_test_report AS item_test_report,
			i.seller_id AS item_seller_id,
			i.item_status AS item_status,
			i.sold AS item_sold,
			i.in_auction AS item_in_auction,
			i.other_charges AS item_other_charges,
			i.inspected AS item_test_report,
			i.specification AS specification,
			imake.title AS item_make_title,
			imodel.title AS item_model_title
		';

		if($auctionType != 'live'){
            $select = $select.', MAX(b.bid_amount) AS current_bid_price, 
            (CASE 
                WHEN MAX(b.bid_amount) IS NULL 
                    THEN ai.bid_start_price
                WHEN MAX(b.bid_amount) IS NOT NULL 
                    THEN MAX(b.bid_amount)
                ELSE 0
            END) AS final_price';
        }

        return $select;
	}

	public function getItemCatalog()
	{
		$select = 'ai.id AS auction_item_id,
			ai.item_id AS item_id,
			ai.sold_status AS sold_status,
			ai.auction_id AS auction_id,
			ai.bid_start_price AS bid_start_price,
			ai.allowed_bids AS allowed_bids,
			ai.minimum_bid_price AS minimum_bid_price,
			ai.bid_start_time AS bid_start_time,
			ai.category_id AS category_id,
			ai.security AS security,
			ai.bid_end_time AS bid_end_time,
			ai.lot_no AS lot_no,
			ai.order_lot_no AS order_lot_no,
			i.name AS item_name,
			i.price AS item_price,
			i.year AS year,
			i.mileage AS mileage,
			i.mileage_type AS mileage_type,
			i.lot_id AS item_lot_id,
			i.subcategory_id AS subcategory_id,
			i.detail AS item_detail,
			i.feature AS item_feature,
			i.unique_code AS item_unique_code,
			i.registration_no AS item_registration_no,
			i.barcode AS item_barcode,
			i.unique_code AS item_unique_code,
			i.vin_number AS item_vin_number,
			i.make AS item_make,
			i.item_images AS item_images,
			i.model AS item_model,
			i.item_attachments AS item_attachments,
			i.keyword AS item_keyword,
			i.item_test_report AS item_test_report,
			i.seller_id AS item_seller_id,
			i.item_status AS item_status,
			i.sold AS item_sold,
			i.in_auction AS item_in_auction,
			i.other_charges AS item_other_charges,
			i.inspected AS item_test_report,
			i.specification AS specification,
			imake.title AS item_make_title,
			imodel.title AS item_model_title
		';

        return $select;
	}
}	