<?php defined('BASEPATH') or exit('No direct script access allowed');
class Cronjob extends MX_Controller
{
    public $language = 'english';
    function __construct()
    {
        parent::__construct();
        $this->load->model('Cronjob_model', 'cronjob_model');
        $this->load->model('auction/Live_auction_model', 'lam');
        $this->load->model('files/Files_model','files_model');
        $this->load->model('customer/Customer_model', 'customer_model');
        $this->load->helper('general_helper');
        $this->language = ($this->session->userdata('site_lang')) ? $this->session->userdata('site_lang') : 'english';

        header("Access-Control-Allow-origin: *");
         header("Cache-Control: no-cache");
    }

    public function index(){
        print_r(date('Y-m-d H:i:s'));
        $time = $this->db->query('SELECT CURRENT_TIMESTAMP');
        print_r($time);
    }

    public function sale_out_email($item_id='', $buyer_id='')
    {
        $this->load->model('email/Email_model','email_model');
        if(!empty($item_id) && !empty($buyer_id)){
            //echo "//////////////";
            $buyer = $this->db->get_where('users', ['id' => $buyer_id])->row_array();
            $item = $this->db->get_where('item', ['id' => $item_id])->row_array();
            $contact_us = $this->db->get('contact_us')->row_array();

            $msg = $item['name'].' has been sold to '. $buyer['fname'].' - '.$buyer['email'];
            $to = array(
                $buyer['email']
            );
            if (!empty($contact_us['email'])) {
                $c_email = $contact_us['email'];
                array_push($to, $c_email);
            }
            $subject='Sale Out Notification';
            $notification = array(
                'title_english' => 'Sale Out Notification',
                'description_english' => $msg,
            );
            $data = [
                'btn' => 'true',
                'notification' => $notification
            ];
            $vars = [
                '{btn_link}' => base_url(),
                '{btn_text}' => 'Visit'
            ];
            $email_message = $this->load->view('email_templates/common_template', $data, true);
            if($vars){
                foreach ($vars as $key => $value) {
                    $email_message = str_replace($key, $value, $email_message);
                }
            }
            $send = $this->email->to($to)->subject($subject)->message($email_message)->send();
            /// sold sms ////
            $sms_buyer = $buyer['fname'].', you purchased '.$item['name'];
            $this->load->library('SendSmart');
            $number = '971561387755';
            $sms_response = $this->sendsmart->sms($number, $sms_buyer);
            /// sold sms end ////
            return $send;
        }
    }
    // Auto sale a auction Item
    public function auto_sale_auction_items()
    {
        $data = array();
        $data['current_page_auction'] = 'current-page';
        $this->db->where_in('access_type', ['online','closed']);
        $auctions = $this->db->get('auctions')->result_array();
        foreach ($auctions as $key => $auction) {
            $today_date = new DateTime(date('Y-m-d H:i:s'));
            $expiry_date_obj = new DateTime(date('Y-m-d H:i:s', strtotime($auction['expiry_time'])));
            $difference = $today_date->diff($expiry_date_obj);
            // print_r($difference);die();
            $remaning_minuts = ($difference->days * 24 *60) + ($difference->h * 60) + $difference->i;
            if($difference->invert == 1){
                $remaning_minuts = '-'.$remaning_minuts;
            }
                // print_r($remaning_minuts.',');
            if ($remaning_minuts == '-1') {

                $auction_items = $this->db->get_where('auction_items', ['auction_id' => $auction['id']])->result_array();
                foreach ($auction_items as $key => $auction_item) {
                    // print_r($auction_item);die();
                    $item = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                    if (!empty($item)) {
                        $query = $this->db->query('Select bid.buyer_id, bid.id, bid.bid_amount, bid.bid_status from bid inner join  ( Select max(bid.id) as LatestId, item_id  from bid Group by bid.item_id  ) SubMax on bid.id = SubMax.LatestId and bid.item_id = SubMax.item_id WHERE bid.item_id = '.$auction_item['item_id'].' AND bid.auction_id = '.$auction['id'].';');
                        $buyer = $query->row_array();
                        // print_r($auction_item);die();
                        if (!empty($buyer)) {
                            $sold = $this->db->get_where('sold_items', ['item_id' => $auction_item['item_id'],'buyer_id' => $buyer['buyer_id'], 'seller_id' => $item['seller_id']]);
                            
                            if ($sold->num_rows() == 0 && $buyer['bid_amount'] >= $item['price']) {

                                $this->db->update('bid', ['bid_status' => 'won'], ['id' => $buyer['id']]);
                                $sold_item = array(
                                    'item_id' => $item['id'],
                                    'auction_id' => $auction['id'],
                                    'auction_item_id' => $auction_item['id'],
                                    'buyer_id' => $buyer['buyer_id'],
                                    'seller_id' => $item['seller_id'],
                                    'price' => $buyer['bid_amount'],
                                    'payable_amount' => $buyer['bid_amount'],
                                    'created_by' => '1',
                                    'updated_by' => '1',
                                    'created_on' => date('Y-m-d H:i'),
                                    'sale_type' => 'online'
                                );
                                $insert = $this->db->insert('sold_items', $sold_item);
                                $result = $this->db->update('item', ['sold' => 'yes','updated_by' => '1','updated_on' => date('Y-m-d H:i')], ['id' => $item['id']]);
                                $this->db->update('auction_items', ['sold_status' => 'sold','updated_by' => '1','updated_on' => date('Y-m-d H:i')], ['item_id' => $item['id'], 'auction_id' => $auction['id']]);
                                if ($insert) {

                                    //send email to heighest bidder on sold item 
                                    $this->load->model('email/Email_model', 'email_model');
                                    $buyer_data = $this->db->get_where('users', ['id' => $buyer['buyer_id']])->row_array();
                                    $item_data = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                                    $item_link = base_url('auction/online-auction/details/').$auction['id'].'/'.$auction_item['item_id'];
                                    $email = $buyer_data['email'];
                                    $itm_name_language = json_decode($item_data['name']);
                                    $lan = $this->language;
                                    $vars = array(
                                        '{username}' => $buyer_data['fname'],
                                        '{item_name}' => '<a href="'.$item_link.'">'.$itm_name_language->$lan.'</a>',
                                        '{year}' => $item_data['year'],
                                        '{bid_price}' => $buyer['bid_amount'],
                                        '{lot_number}' => $auction_item['order_lot_no'],
                                        '{registration_number}' => $item_data['registration_no'],
                                        '{login_link}' => 'Please login:<a href="'.base_url("user-login").'" > Go to My Account </a>'
                                    );

                                    $this->email_model->email_template($email, 'item-win-email', $vars, false);
                                    // send email to seller
                                    $seller_data = $this->db->get_where('users',['id' => $item['seller_id']])->row_array();
                                    $vars['{username}'] = $seller_data['fname'];
                                    $seller_email = $seller_data['email'];
                                    $this->email_model->email_template($seller_email, 'item-sold-email', $vars, false);
                                    // $this->sale_out_email($sold_item['item_id'], $sold_item['buyer_id']);

                                    print_r($item['id'].'--sold');
                                    echo "<br>";
                                } else {
                                    print_r($item['id'].'--error');
                                    echo "<br>";
                                }
                            } elseif ($sold->num_rows() == 0) {
                            // print_r($buyer);die();
                                $this->db->update('auction_items', ['sold_status' => 'approval','updated_by' => '1','updated_on' => date('Y-m-d H:i')], ['item_id' => $item['id'], 'auction_id' => $auction['id']]);
                                print_r($item['id'].'--sold on approval');
                                echo "<br>";

                                //send email to heighest bidder on approval 
                                $this->load->model('email/Email_model', 'email_model');
                                $buyer_data = $this->db->get_where('users', ['id' => $buyer['buyer_id']])->row_array();
                                $item_data = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                                $item_link = base_url('auction/online-auction/details/').$auction['id'].'/'.$auction_item['item_id'];
                                $email = $buyer_data['email'];
                                $itm_name_lan = json_decode($item_data['name']);
                                $lang = $this->language;
                                $vars = array(
                                    '{username}' => $buyer_data['fname'],
                                    '{item_name}' => '<a href="'.$item_link.'">'.$itm_name_lan->$lang.'</a>',
                                    '{year}' => $item_data['year'],
                                    '{bid_price}' => $buyer['bid_amount'],
                                    '{lot_number}' => $auction_item['order_lot_no'],
                                    '{registration_number}' => $item_data['registration_no'],
                                    '{login_link}' => 'Please login:<a href="'.base_url("user-login").'" > Go to My Account </a>'
                                );

                                $this->email_model->email_template($email, 'need-approval-from-seller-email', $vars, false);
                                // $this->email_model->common_template($email, 'Approval Email', $body, $vars, false);

                                 // push notification 
                    $p_noti = ['lot_no'=> $auction_item['order_lot_no'], 'item_name' =>  $itm_name->$lan];
                    $this->getAuctionWinnnerPushNotification($buyer_data['fcm_token'], $p_noti);
                            }
                        } else {
                            // if no bid available then item status will be unsold
                            print_r($item['id'].'--unsold');
                            echo "<br>";
                            $this->db->update('auction_items', ['sold_status' => 'not_sold','updated_on' => date('Y-m-d H:i')], ['item_id' => $item['id'], 'auction_id' => $auction['id']]);
                        }
                    }
                }
            } elseif ($auction['expiry_time'] > date('Y-m-d H:i:s') && $auction['start_time'] < date('Y-m-d H:i:s')) {

                $auction_items = $this->db->get_where('auction_items', ['auction_id' => $auction['id'],'sold_status' => 'not','bid_end_time <' => date('Y-m-d H:i')])->result_array();
                foreach ($auction_items as $key => $auction_item) {
                    $item = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                    if (!empty($item)) {
                        $query = $this->db->query('Select bid.buyer_id, bid.id, bid.bid_amount, bid.bid_status from bid inner join  ( Select max(bid.id) as LatestId, item_id  from bid Group by bid.item_id  ) SubMax on bid.id = SubMax.LatestId and bid.item_id = SubMax.item_id WHERE bid.item_id = '.$item['id'].' AND bid.auction_id = '.$auction['id'].';');
                        $buyer = $query->row_array();
                        // print_r($auction_item);die();
                        if (!empty($buyer)) {
                            $sold = $this->db->get_where('sold_items', ['item_id' => $auction_item['item_id'],'buyer_id' => $buyer['buyer_id'], 'seller_id' => $item['seller_id']]);
                            // print_r($sold->num_rows());
                            if ($sold->num_rows() == 0 && $buyer['bid_amount'] >= $item['price']) {

                                $this->db->update('bid', ['bid_status' => 'won'], ['id' => $buyer['id']]);
                                $sold_item = array(
                                    'item_id' => $item['id'],
                                    'auction_id' => $auction['id'],
                                    'auction_item_id' => $auction_item['id'],
                                    'buyer_id' => $buyer['buyer_id'],
                                    'seller_id' => $item['seller_id'],
                                    'price' => $buyer['bid_amount'],
                                    'payable_amount' => $buyer['bid_amount'],
                                    'created_by' => '1',
                                    'updated_by' => '1',
                                    'created_on' => date('Y-m-d H:i'),
                                    'sale_type' => 'online',
                                );
                                $insert = $this->db->insert('sold_items', $sold_item);
                                $result = $this->db->update('item', ['sold' => 'yes','updated_by' => '1','updated_on' => date('Y-m-d H:i')], ['id' => $item['id']]);
                                $this->db->update('auction_items', ['sold_status' => 'sold','updated_on' => date('Y-m-d H:i')], ['item_id' => $item['id'], 'auction_id' => $auction['id']]);
                                if ($insert) {
                                    //send email to heighest bidder on sold item 
                                    $this->load->model('email/Email_model', 'email_model');
                                    $buyer_data = $this->db->get_where('users', ['id' => $buyer['buyer_id']])->row_array();
                                    $item_data = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                                    $item_link = base_url('auction/online-auction/details/').$auction['id'].'/'.$auction_item['item_id'];
                                    $email = $buyer_data['email'];
                                    $itm_name = json_decode($item_data['name']);
                                    $lang = $this->language;
                                    $vars = array(
                                        '{username}' => $buyer_data['fname'],
                                        '{item_name}' => '<a href="'.$item_link.'">'.$itm_name->$lang.'</a>',
                                        '{year}' => $item_data['year'],
                                        '{bid_price}' => $buyer['bid_amount'],
                                        '{lot_number}' => $auction_item['order_lot_no'],
                                        '{registration_number}' => $item_data['registration_no'],
                                        '{login_link}' => 'Please login:<a href="'.base_url("user-login").'" > Go to My Account </a>'
                                    );

                                    $this->email_model->email_template($email, 'item-win-email', $vars, false);
                                    // send email to seller
                                    $seller_data = $this->db->get_where('users',['id' => $item['seller_id']])->row_array();
                                    $vars['{username}'] = $seller_data['fname'];
                                    $seller_email = $seller_data['email'];
                                    $this->email_model->email_template($seller_email, 'item-sold-email', $vars, false);

                                    // $this->sale_out_email($sold_item['item_id'], $sold_item['buyer_id']);

                                    print_r($item['id'].'--sold');
                                    echo "<br>";
                                } else {
                                    print_r($item['id'].'--error');
                                    echo "<br>";
                                }
                            } elseif ($sold->num_rows() == '0') {
                                $this->db->update('auction_items', ['sold_status' => 'approval','updated_on' => date('Y-m-d H:i')], ['item_id' => $item['id'], 'auction_id' => $auction['id']]);
                                
                                print_r($item['id'].'--sold on approval');
                                echo "<br>";

                                //send email to heighest bidder on approval 
                                $this->load->model('email/Email_model', 'email_model');
                                $buyer_data = $this->db->get_where('users', ['id' => $buyer['buyer_id']])->row_array();
                                $item_data = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                                $item_link = base_url('auction/online-auction/details/').$auction['id'].'/'.$auction_item['item_id'];
                                $email = $buyer_data['email'];
                                $itm_name = json_decode($item_data['name']);
                                $lang = $this->language;
                                $vars = array(
                                    '{username}' => $buyer_data['fname'],
                                    '{item_name}' => '<a href="'.$item_link.'">'.$itm_name->$lang.'</a>',
                                    '{year}' => $item_data['year'],
                                    '{bid_price}' => $buyer['bid_amount'],
                                    '{lot_number}' => $auction_item['order_lot_no'],
                                    '{registration_number}' => $item_data['registration_no'],
                                    '{login_link}' => 'Please login:<a href="'.base_url("user-login").'" > Go to My Account </a>'
                                );

                                $this->email_model->email_template($email, 'need-approval-from-seller-email', $vars, false);
                                // $this->email_model->common_template($email, 'Approval Email', $body, $vars, false);

                                 // push notification 
                    $p_noti = ['lot_no'=> $auction_item['order_lot_no'], 'item_name' =>  $itm_name->$lan];
                    $this->getAuctionWinnnerPushNotification($buyer_data['fcm_token'], $p_noti);
                    
                            }
                        } else {
                            print_r($item['id'].'--unsold');
                            echo "<br>";
                            $this->db->update('auction_items', ['sold_status' => 'not_sold','updated_on' => date('Y-m-d H:i')], ['item_id' => $item['id'], 'auction_id' => $auction['id']]);
                        }
                    }
                }
            }
        }
        echo "Done.";
    }

    public function paytabsVoidTransaction()
    {
        //$postedData = json_decode(file_get_contents("php://input"), true);
        $postedData = file_get_contents("php://input");
        //print_r($postedData);die();
        
        $myfile = fopen("./uploads/newfile.txt", "a") or die("Unable to open file!");

        //$txt = json_encode($_POST);

        fwrite($myfile, $postedData);
        fclose($myfile);
        die();


        //STEP 1: Get data from paytabs from post method
        //STEP 2: Analyze data and react accordingly using payment status
        //STEP 3: Update database as per requirement
        //STEP 4: Send Email/SMS to user for current status of his deposit

        if($postedData['authcode'] == 'G14677'){
            $tranRef = $postedData['order_id'];
            $auctionDeposit = $this->db->get_where('auction_deposit', ['transaction_id' => $tranRef, 'account' => 'DR']);
            if($auctionDeposit->num_rows() > 0){
                $auctionDeposit = $auctionDeposit->row_array();
                $buyerId = $auctionDeposit['user_id'];
                $buyer_data = $this->db->get_where('users', ['id' => $buyerId])->row_array();

                //add reverse transaction detail in auction deposit
                $transaction_data['transaction_id'] = $tranRef;
                $transaction_data['user_id'] = $buyerId;
                $transaction_data['amount'] = $auctionDeposit['amount'];
                $transaction_data['deposit_type'] = 'permanent';
                $transaction_data['payment_type'] = 'card';
                $transaction_data['account'] = 'CR';
                $transaction_data['description'] = json_encode($postedData);
                $transaction_data['created_on'] = date('Y-m-d H:i:s');
                $transaction_data['status'] = 'refund';
                $transaction_data['deleted'] = 'no';

                $tranReversed = $this->db->insert('auction_deposit', $transaction_data);
                if($tranReversed){
                    //send email for void transaction 
                    $this->load->model('email/Email_model', 'email_model');
                    $email = $buyer_data['email'];
                    $vars = array(
                        '{username}' => $buyer_data['username'],
                        '{transaction_id}' => $transaction_data['transaction_id'],
                        '{amount}' => $transaction_data['amount']
                    );

                    $this->email_model->email_template($email, 'void-transaction', $vars, false);
                }

                //get buyer current balance and bid limit detail
                $balance = $this->customer_model->user_balance($buyerId);
                $balance = (float)$balance['amount'];
                $bidLimit = $balance * 10;

                //check all won bids of current buyer
                /*$onlineWonBidsItemsIds = [];
                $liveWonBidsItemsIds = [];
                
                $onlineWonBidsItemsIdsQuery = $this->db->select('item_id')->get_where('bid', ['buyer_id' => $buyerId, 'bid_status' => 'won'])->result_array();
                if(count($onlineWonBidsItemsIdsQuery) > 0){
                    foreach ($onlineWonBidsItemsIdsQuery as $key => $value) {
                        array_push($onlineWonBidsItemsIds, $value['item_id']);
                    }
                }
                
                $liveWonBidsItemsIdsQuery = $this->db->select('item_id')->get_where('live_auction_bid_log', ['user_id' => $buyerId, 'bid_status' => 'win'])->result_array();
                if(count($liveWonBidsItemsIdsQuery) > 0){
                    foreach ($liveWonBidsItemsIdsQuery as $key => $value) {
                        array_push($liveWonBidsItemsIds, $value['item_id']);
                    }
                }

                $wonItems = [];
                if(!empty($onlineWonBidsItemsIds) && !empty($liveWonBidsItemsIds)){
                    $wonItems = array_merge($onlineWonBidsItemsIds, $liveWonBidsItemsIds);
                }*/

                $payableAmountQuery = $this->db->select_sum('payable_amount')
                    ->where(['buyer_id' => $buyerId, 'payment_status' => 0])
                    ->group_by('buyer_id')
                    ->get('sold_items')->row_array();
                    //->where_in('item_id', $wonItems)

                $payableAmount = 0;
                if($payableAmountQuery){
                    $payableAmount = (float)$payableAmountQuery['payable_amount'];
                }

                if($payableAmount > $bidLimit){
                    $requiredDeposit = $payableAmount - $bidLimit;
                    
                    //Item wise expiry time checking 
                    $winItem = $this->cronjob_model->getWinItems($buyerId);
                    if($winItem){
                        $currentTime = time();
                        $timeLimit = $this->config->item('required_deposit_email_time');
                        $timeLimitSeconds = (int)$timeLimit * 3600;

                        $deadline = $currentTime + $timeLimitSeconds;
                        $expiryTime = strtotime($winItem['bid_end_time']);

                        if($expiryTime > $deadline){
                            $allowedTime = $timeLimit;
                        }else{
                            $allowedTime = $expiryTime / 3600; //in hours
                        }

                        //Email for required deposit
                        $vars = array(
                            '{username}' => $buyer_data['username'],
                            '{amount}' => $requiredDeposit,
                            '{time_limit}' => $allowedTime
                        );
                        $email = $buyer_data['email'];
                        $this->email_model->email_template($email, 'deposit-required', $vars, false);
                    }
                }
            }
        }
    }

    public function broadcast_pusher_without_image($item_id, $auction_id){

        //item data
        $item = $this->lam->get_live_auction_items($auction_id,'','',[$item_id]);
        $item = $item[0];

        $item['vehicle'] = false;
        if(isset($item['item_make_model']) && $item['item_make_model'] == 'yes'){
            $make = $this->db->get_where('item_makes', ['id' => $item['item_make']])->row_array();
            $model = $this->db->get_where('item_models', ['id' => $item['item_model']])->row_array();
            $item['vehicle'] = true;
            $item['make'] = $make;
            $item['model'] = $model;
        }

//        //item images
//        $images = explode(',', $item['item_images']);
//        $item_images = $this->files_model->get_multiple_files_by_ids($images);
        $item_images = array();

        //current bid data
        $currentBidAmount = 0;
        $current_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id, 'item_id' => $item_id])->row_array();
        if (!empty($current_bid)) {
            $currentBidAmount = $current_bid['bid_amount'];
        }

        // $item_category_fields = $this->db->get_where('item_category_fields',['category_id' => $item['category_id'],'name' => 'transmission'])->result_array();

        $select = "sort_catagories.sort_id AS sortId, item_category_fields.label AS fieldName, item_fields_data.value AS fieldValue";
        $sortedCateFields = $this->db->select($select)->from('sort_catagories')
            ->join('item_category_fields', 'sort_catagories.field_id = item_category_fields.id')
            ->join('item_fields_data', 'item_category_fields.id = item_fields_data.fields_id')
            ->where(['sort_catagories.category_id' => $item['category_id'], 'item_fields_data.item_id' => $item_id])
            ->order_by('sort_catagories.sort_id', 'ASC')
            ->get()->result_array();

        $item_fields_data = array();
        if($sortedCateFields){
            foreach ($sortedCateFields as $key => $field) {
                $fieldNames = explode("|", $field['fieldName']);
                $item_fields_data[$key]['fieldName'] = ($fieldNames[0]) ? $fieldNames[0] : '';
                $fieldValues = explode("|", $field['fieldValue']);
                $item_fields_data[$key]['fieldValue'] = ($fieldValues[0]) ? $fieldValues[0] : '';
            }
        }


        //auction data
        $auction = $this->db->get_where('auctions', ['id' => $auction_id])->row_array();
        $auction_blink_text = $this->db->get_where('auction_items', ['item_id' => $item_id, 'auction_id' => $auction_id])->row_array();

        $push['item_id'] = $item_id;
        $push['auction_id'] = $auction_id;
        $push['auction'] = $auction;
        $push['cb_amount'] = @$currentBidAmount;
        $push['current_bid'] = $current_bid;
        $push['data'] = $item;
        $push['item_fields_data'] = $item_fields_data;
        $push['item_images'] = $item_images;
        $push['lot_number'] = $item['order_lot_no'];
        $push['blink_text'] = $auction_blink_text['blink_text'];
        $push['item_model'] = isset($model['title']) ? json_decode($model['title'])->english : '';
        echo json_encode($push);
        // return $push;
    }


    public function broadcast_pusher($item_id, $auction_id){
        
        //item data
        $item = $this->lam->get_live_auction_items($auction_id,'','',[$item_id]);
        $item = $item[0];
        
        $item['vehicle'] = false;
        if(isset($item['item_make_model']) && $item['item_make_model'] == 'yes'){
            $make = $this->db->get_where('item_makes', ['id' => $item['item_make']])->row_array();
            $model = $this->db->get_where('item_models', ['id' => $item['item_model']])->row_array();
            $item['vehicle'] = true;
            $item['make'] = $make;
            $item['model'] = $model;
        }
        
        //item images
        $images = explode(',', $item['item_images']);
        $item_images = $this->files_model->get_multiple_files_by_ids($images);

        //current bid data
        $currentBidAmount = 0;
        $current_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id, 'item_id' => $item_id])->row_array();
        if (!empty($current_bid)) {
            $currentBidAmount = $current_bid['bid_amount'];
        }

        // $item_category_fields = $this->db->get_where('item_category_fields',['category_id' => $item['category_id'],'name' => 'transmission'])->result_array();
        
        $select = "sort_catagories.sort_id AS sortId, item_category_fields.label AS fieldName, item_fields_data.value AS fieldValue";
        $sortedCateFields = $this->db->select($select)->from('sort_catagories')
            ->join('item_category_fields', 'sort_catagories.field_id = item_category_fields.id')
            ->join('item_fields_data', 'item_category_fields.id = item_fields_data.fields_id')
            ->where(['sort_catagories.category_id' => $item['category_id'], 'item_fields_data.item_id' => $item_id])
            ->order_by('sort_catagories.sort_id', 'ASC')
            ->get()->result_array();

        $item_fields_data = array();
        if($sortedCateFields){ 
            foreach ($sortedCateFields as $key => $field) {
                $fieldNames = explode("|", $field['fieldName']);
                $item_fields_data[$key]['fieldName'] = ($fieldNames[0]) ? $fieldNames[0] : '';
                $fieldValues = explode("|", $field['fieldValue']);
                $item_fields_data[$key]['fieldValue'] = ($fieldValues[0]) ? $fieldValues[0] : '';
            } 
        }
        

        //auction data
        $auction = $this->db->get_where('auctions', ['id' => $auction_id])->row_array();
        $auction_blink_text = $this->db->get_where('auction_items', ['item_id' => $item_id, 'auction_id' => $auction_id])->row_array();

        $push['item_id'] = $item_id;
        $push['auction_id'] = $auction_id;
        $push['auction'] = $auction;
        $push['cb_amount'] = @$currentBidAmount;
        $push['current_bid'] = $current_bid;
        $push['data'] = $item;
        $push['item_fields_data'] = $item_fields_data;
        $push['item_images'] = $item_images;
        $push['lot_number'] = $item['order_lot_no'];
        $push['blink_text'] = $auction_blink_text['blink_text'];
        $push['item_model'] = isset($model['title']) ? json_decode($model['title'])->english : '';
        echo json_encode($push);
        // return $push;
    }

    public function broadcast_pusher_low_load($item_id, $auction_id){

        //item data
        $item = $this->lam->get_live_auction_items_low_load($auction_id,'','',[$item_id]);
        $item = $item[0];

        //current bid data
        $currentBidAmount = 0;
        $current_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id, 'item_id' => $item_id])->row_array();
        if (!empty($current_bid)) {
            $currentBidAmount = $current_bid['bid_amount'];
        }


        $push['item_id'] = $item_id;
        $push['auction_id'] = $auction_id;
        $push['cb_amount'] = @$currentBidAmount;
        $push['current_bid'] = $current_bid;
        $push['data'] = $item;
        $push['lot_number'] = $item['order_lot_no'];
        echo json_encode($push);
        // return $push;
    }

    // public function sitemap_xml() {

      //$xmlString = '<?xml version="1.0" encoding="UTF-8">'; 
    //     $xmlString .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
    //     $xmlString .= '<url>';
    //     $xmlString .= '<loc>http://example.com/</loc>';
    //     $xmlString .= '<lastmod>'.date(DATE_ATOM,time()).'</lastmod>';
    //     $xmlString .= '<changefreq>daily</changefreq>';
    //     $xmlString .= '<priority>1.0</priority>';
    //     $xmlString .= '</url>';

    //     $xmlString .= '<url>';
    //     $xmlString .= '<loc>http://example.com/videos/</loc>';
    //     $xmlString .= '<lastmod>'.date(DATE_ATOM,time()).'</lastmod>';
    //     $xmlString .= '<changefreq>daily</changefreq>';
    //     $xmlString .= '<priority>1.0</priority>';
    //     $xmlString .= '</url>';

    //     $xmlString .= '<url>';
    //     $xmlString .= '<loc>http://example.com/contact/</loc>';
    //     $xmlString .= '<lastmod>'.date(DATE_ATOM,time()).'</lastmod>';
    //     $xmlString .= '<changefreq>daily</changefreq>';
    //     $xmlString .= '<priority>1.0</priority>';
    //     $xmlString .= '</url>';

    //     $xmlString .= '<url>';
    //     $xmlString .= '<loc>http://example.com/blog/</loc>';
    //     $xmlString .= '<lastmod>'.date(DATE_ATOM,time()).'</lastmod>';
    //     $xmlString .= '<changefreq>daily</changefreq>';
    //     $xmlString .= '<priority>1.0</priority>';
    //     $xmlString .= '</url>';

    //     // $sql = "SELECT * FROM categories";
    //     // $stmt = DB::run($sql);
    //     // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    //     //     $url = $row["url"];
    //     //     $xmlString .= '<url>';
    //     //     $xmlString .= '<loc>http://example.com/category/'.htmlentities($url).'/</loc>';
    //     //     $xmlString .= '<lastmod>'.date(DATE_ATOM,time()).'</lastmod>';
    //     //     $xmlString .= '<changefreq>daily</changefreq>';
    //     //     $xmlString .= '<priority>1.0</priority>';
    //     //     $xmlString .= '</url>';
    //     // }

    //     $xmlString .= '</urlset>';

    //     $dom = new DOMDocument;
    //     $dom->preserveWhiteSpace = FALSE;
    //     $dom->loadXML($xmlString);

    //     $dom->save('sitemap.xml');

    // }

    private function getAuctionWinnnerPushNotification($_id, $_data){

       if($this->language=='arabic'){
            //$title = $arabic_l['push_winner_title'];
            $message = $this->lang->line('push_winner_message_2').$_data['lot_no'].' - '.$_data['item_name'].$this->lang->line('push_winner_message_1');    
          
        }else{
            //$title = $english_l['push_winner_title'];
            $message = $this->lang->line('push_winner_message_1').$_data['lot_no'].' - '.$_data['item_name'].$this->lang->line('push_winner_message_2');
        }
        $title = $this->lang->line('push_winner_title');
           $arr = [
            'title'=> $title,
           'message'=>$message,
           'link' => 'pioneerauctions://home'
       ];

    
        return $this->sendPushNotification($_id, $arr);
     }

    public function sendPushNotification($to = "", $data=false)
    {

        $language = "english";
        $version = $this->config->item('appVersion');

        if(!empty($to)) {

            $title = $data['title'] ?? 'Test Title';
            $body = $data['message'] ?? 'test message body';
            $image = 'https://staging.pioneerauctions.ae/uploads/items_documents/2649/eNfVsQZlyo.jpg';
            $link = $data['link'] ?? 'pioneerauctions://home';

            $data = [
                "notification" => [
                    "body" => $body,
                    "title" => $title,
                    "image" => $image,
                    "deeplink" => $link 
                ],
                "priority" => "high",
                "data" => [
                    "deeplink" =>$link 
                ],
                "to" => $to
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_POST, 1);

            $headers = array();
            $headers[] = 'Content-Type: application/json';
           $headers[] = 'Authorization: key=AAAAASZ7Vwk:APA91bF6wZpCDm86aSx4HpBM6td3rvdytZqlViClzsqgy4OjdonZFlwpPxuZK0Ew1DLhwdGHPvcQ9ddZWI7hiaFfnTJWQIXkr6-VXR4p355fHnC1p-BPBo6j4GXj_Es3EJf4PP7Wyi84';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            curl_close($ch);

            $codee = http_response_code(200);
            $response = json_encode(array("status" => true, 'code' => $codee, "message" => 'Push Notification Send Successfully', 'language' => $this->language, 'appVersion' => $version));

        }
        return $response;

    }


}//end controller
