<div class="left-col">
          <ul>
            <li class="<?= (isset($account_active)) ? $account_active : '';?>">
              <a href="<?= base_url('customer/dashboard');?>">
                <img src="<?= ASSETS_IMAGES;?>accunt-icon.png"> Account
              </a>
            </li>
            <li class="<?= (isset($profile_active)) ? $profile_active : '';?>">
              <a href="<?= base_url('customer/profile');?>">
                <img src="<?= ASSETS_IMAGES;?>profile-icon.png"> Profile
              </a>
            </li>
            <li class="<?= (isset($bid_active)) ? $bid_active : '';?>">
              <a href="<?= base_url('customer/bid');?>">
                <img src="<?= ASSETS_IMAGES;?>bids-icon.png"> Bids
              </a>
            </li>
            <li class="<?= (isset($inventory_active)) ? $inventory_active : '';?>">
              <a href="<?= base_url('customer/inventory');?>">
                <img src="<?= ASSETS_IMAGES;?>inventory-icon.png"> Inventory
              </a>
            </li>
            <li class="<?= (isset($balance_active)) ? $balance_active : '';?>">
              <a href="<?= base_url('customer/balance');?>">
                <img src="<?= ASSETS_IMAGES;?>payment-icon-2.png"> Balance
              </a>
            </li>
            <li class="<?= (isset($deposits_active)) ? $deposits_active : '';?>">
              <a href="<?= base_url('customer/deposits');?>">
                <img src="<?= ASSETS_IMAGES;?>payment-icon-2.png"> Deposit History
              </a>
            </li>
            <li class="<?= (isset($security_active)) ? $security_active : '';?>">
              <a href="<?= base_url('customer/security');?>">
                <img src="<?= ASSETS_IMAGES;?>payment-icon-2.png"> Security History
              </a>
            </li>
            <li class="<?= (isset($payments_active)) ? $payments_active : '';?>">
              <a href="<?= base_url('user-payment');?>">
                <img src="<?= ASSETS_IMAGES;?>payment-icon-2.png"> Payments
              </a>
            </li>
            <li class="<?= (isset($favorite_active)) ? $favorite_active : '';?>">
              <a href="<?= base_url('customer/favorite');?>">
                <img src="<?= ASSETS_IMAGES;?>payment-icon.png"> Favorite
              </a>
            </li>
            <li class="<?= (isset($document_active)) ? $document_active : '';?>">
              <a href="<?= base_url('customer/documents');?>">
                <img src="<?= ASSETS_IMAGES;?>document-icon.png"> Documents
              </a>
            </li>
            <?php $id = $this->session->userdata('logged_in')->id;
             $count = $this->db->select('count(*) as count')->from('notification')->where('receiver_id', $id)->where('status', 'unread')->get()->row_array(); ?>
            <li class="<?= (isset($notification_active)) ? $notification_active : '';?>">
              <a href="<?= base_url('customer/notification');?>" class="<?= (!empty($count) && $count['count'] > 0) ? 'red-dot' : '';  ?>">
                <i></i>
                <img src="<?= ASSETS_IMAGES;?>notification-icon.png"> Notifications <?= ($count['count'] > 0) ? '('.$count['count'].')' : '';  ?>
              </a>
            </li>
            <li class="<?= (isset($faq_active)) ? $faq_active : '';?>">
              <a href="<?= base_url('customer/faq');?>">
                <img src="<?= ASSETS_IMAGES;?>tearms.png"> FAQ
              </a>
            </li>
            <li class="<?= (isset($terms_active)) ? $terms_active : '';?>">
              <a href="<?= base_url('customer/terms');?>">
                <img src="<?= ASSETS_IMAGES;?>tearms.png"> Terms & Conditions
              </a>
            </li>
            <li class="<?= (isset($sell_my_item)) ? $sell_my_item : '';?>">
              <a href="<?= base_url('customer/sell_item');?>">
                <img src="<?= ASSETS_IMAGES;?>tearms.png"> Sell My Items
              </a>
            </li>
            <li class="<?= (isset($deposit_active)) ? $deposit_active : '';?>">
              <a href="<?= base_url('customer/deposit');?>">
                <img src="<?= ASSETS_IMAGES;?>tearms.png"> Add Deposit
              </a>
            </li>
            <li class="<?= (isset($item_deposit_active)) ? $item_deposit_active : '';?>">
              <a href="<?= base_url('customer/item_deposit');?>">
                <img src="<?= ASSETS_IMAGES;?>tearms.png"> Add Security
              </a>
            </li>
            <!-- <li class="<?//= (isset($my_item_active)) ? $my_item_active : '';?>">
              <a href="<?//= base_url('customer/items');?>">
                <img src="<?//= ASSETS_IMAGES;?>shopping-icon.png"> Sell my item
              </a>
            </li> -->
          </ul>
        </div>