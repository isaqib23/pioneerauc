<div class="left-col">
  <ul>
    <li class="<?= (isset($account_active)) ? $account_active : '';?>">
      <a class="toggle-link">
        
        <i class="account-icon"></i>
        <!-- <img src="<?= NEW_ASSETS_IMAGES;?>accunt-icon.png"> -->
        <?= $this->lang->line('my_account');?>
      </a>
        <ul class="sub-menu" style="display: none;">
          <li><a href="<?= base_url('customer');?>"> <?= $this->lang->line('my_bids');?></a></li>
          <!-- <li><a href="<?= base_url('user-bids');?>"> My Bids</a></li> -->
          <!-- <li><a href="<?= base_url('customer');?>"> My Purchases</a></li> -->
          <li><a href="<?= base_url('customer/uploaded_docs');?>"><?= $this->lang->line('my_docs');?></a></li>
          <li><a href="<?= base_url('customer/inventory');?>"><?= $this->lang->line('my_stock');?></a></li>
        </ul>
    </li>

    <!-- <li class="<?= (isset($bid_active)) ? $bid_active : '';?>">
      <a href="<?= base_url('user-bids');?>">
        <img src="<?= NEW_ASSETS_IMAGES;?>bid-icon.png"> Bids
      </a>
    </li> -->
    <!-- <li class="<?= (isset($inventory_active)) ? $inventory_active : '';?>">
      <a href="<?= base_url('customer/inventory');?>">
        <img src="<?= NEW_ASSETS_IMAGES;?>inventory-icon.png"> Inventory
      </a>
    </li> -->
   <!--  <li class="<?= (isset($balance_active)) ? $balance_active : '';?>">
      <a href="<?= base_url('balance');?>">
        <img src="<?= NEW_ASSETS_IMAGES;?>blance-icon.png"> Balance
      </a>
    </li> -->
    <!-- <li class="<?= (isset($deposits_active)) ? $deposits_active : '';?>">
      <a href="<?= base_url('deposits');?>">
        <img src="<?= NEW_ASSETS_IMAGES;?>deposit-icon.png"> Deposit History
      </a>
    </li>
    <li class="<?= (isset($security_active)) ? $security_active : '';?>">
      <a href="<?= base_url('customer/security');?>">
        <img src="<?= NEW_ASSETS_IMAGES;?>Security Deposite.png"> Security History
      </a>
    </li> -->
    
    <li class="<?= (isset($payments_active)) ? $payments_active : '';?>">
      <a class="toggle-link">
        <i class="payment-icon"></i>
        <!-- <img src="<?= NEW_ASSETS_IMAGES;?>Payments.png">  -->
        <?= $this->lang->line('payments');?>
      </a>
        <ul class="sub-menu" style="display: none;">
          <li><a href="<?= base_url('deposits');?>"> <?= $this->lang->line('pay_deposit');?></a></li>
         <!--  <li><a href="<?= base_url('customer/security');?>"><?= $this->lang->line('security_deposit');?></a></li>  -->
          <li><a href="<?= base_url('user-payment');?>"><?= $this->lang->line('invoice_payment');?></a></li>
        </ul>
    </li>
    
    <li class="<?= (isset($favorite_active)) ? $favorite_active : '';?>">
      <a href="<?= base_url('customer/favorite');?>">
        <i class="favorite-icon"></i>
        <!-- <img src="<?= NEW_ASSETS_IMAGES;?>favorite-icon.png">  -->
        <?= $this->lang->line('favorite_items');?>
        
      </a>
    </li>
    <!-- <li class="<?= (isset($document_active)) ? $document_active : '';?>">
      <a href="<?= base_url('customer/uploaded_docs');?>">
        <img src="<?= NEW_ASSETS_IMAGES;?>document-icon.png"> Documents
      </a>
    </li> --> 
    <?php $id = $this->session->userdata('logged_in')->id;
     $count = $this->db->select('count(*) as count')->from('notification')->where('receiver_id', $id)->where('status', 'unread')->get()->row_array(); ?>
     <li class="<?= (isset($notification_active)) ? $notification_active : '';?>">
      <a href="<?= base_url('customer/notification');?>" class="<?= (!empty($count) && $count['count'] > 0) ? 'red-dot' : '';  ?>">
        <i class="notification-icon"></i>
        <!-- <img src="<?= NEW_ASSETS_IMAGES;?>notification-icon.png">  -->
        <?= $this->lang->line('notification');?> <?= ($count['count'] > 0) ? '('.$count['count'].')' : '';  ?>
      </a>
    </li> 
    <li class="<?= (isset($faq_active)) ? $faq_active : '';?>">
      <a href="<?= base_url('faqs');?>">
        <i class="faq-icon"></i>
        <!-- <img src="<?= NEW_ASSETS_IMAGES;?>faq-icon.png" style="width: 36px; margin-left: -8px;">  -->
        <?= $this->lang->line('faq_left');?>
      </a>
    </li>
    <li class="<?= (isset($terms_active)) ? $terms_active : '';?>">
      <a href="<?= base_url('terms-conditions');?>">
        <!-- <img src="<?= NEW_ASSETS_IMAGES;?>T&C.png">  -->
        <i class="term-icon"></i>
        <?= $this->lang->line('terms_condition');?>
      </a>
    </li>
    <li class="<?= (isset($sell_my_item)) ? $sell_my_item : '';?>">
      <a href="<?= base_url('sell-item');?>">
        <i class="sell-icon"></i>   
        <!-- <img src="<?= NEW_ASSETS_IMAGES;?>Sell My Items.png">  -->
         <?= $this->lang->line('sell_my_item');?>
      </a>
    </li>
    <!-- <li class="<?= (isset($deposit_active)) ? $deposit_active : '';?>">
      <a href="<?= base_url('deposit');?>">
        <img src="<?= NEW_ASSETS_IMAGES;?>add-deposite.png"> Add Deposit
      </a>
    </li> -->
    <!-- <li class="<?= (isset($item_deposit_active)) ? $item_deposit_active : '';?>">
      <a href="<?= base_url('customer/item_deposit');?>">
        <img src="<?= NEW_ASSETS_IMAGES;?>Security Deposite.png"> Add Security
      </a>
    </li> -->
    <!-- <li class="<?//= (isset($my_item_active)) ? $my_item_active : '';?>">
      <a href="<?//= base_url('customer/items');?>">
        <img src="<?//= NEW_ASSETS_IMAGES;?>shopping-icon.png"> Sell my item
      </a>
    </li> -->
  </ul>
</div>