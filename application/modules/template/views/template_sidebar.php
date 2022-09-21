<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

  <div class="menu_section">

    <ul class="nav side-menu">

      <!-- Acl Roles -->
      <?php if ($this->acl_model->has_permission('Acl_roles','index',$logged_in['role'])){ ?>
        <li><a href="<?php echo base_url();?>acl/Acl_roles"> <i class="fa fa-user"></i> ACL </span></a></li>
      <?php } ?>

      <!-- Users Managemant -->
      <?php $main_permission_check2 = $this->acl_model->main_permission('users',$logged_in['role']);
        if($main_permission_check2){ ?>
          <li class="<?php echo (($this->router->fetch_class() == 'users') ?  'active' : ''); ?>"><a ><i class="fa fa-users"></i> User Management <span class="active variant-border"></span></a>
            <ul class="nav child_menu"  <?php echo (($this->router->fetch_class() == 'users') ?  'style="display:block;"' : ''); ?>>
              <?php if ($this->acl_model->has_permission('users','index',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_users) && !empty($current_page_users)) ? $current_page_users : ''; ?>"><a href="<?php echo base_url();?>users"> Administrators </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('users','users_sellers_buyers',$logged_in['role'])){?>
                <li class="<?php echo (isset($current_page_customers) && !empty($current_page_customers)) ? $current_page_customers : ''; ?>"><a href="<?php echo base_url();?>customers"> Customers </span></a></li>
              <?php } ?>

            </ul>
          </li>
      <?php } ?>

      <!-- Reports -->
      <?php $main_permission_check2 = $this->acl_model->main_permission('reports',$logged_in['role']);
        if($main_permission_check2){ ?>
          <li class="<?php echo (($this->router->fetch_class() == 'reports') ?  'active' : ''); ?>"><a ><i class="fa fa-bar-chart"></i> Reports <span class="fa fa-chevron-down"><span class="active variant-border"></span></a>
            <ul class="nav child_menu"  <?php echo (($this->router->fetch_class() == 'reports') ?  'style="display:block;"' : ''); ?>>

              <?php if ($this->acl_model->has_permission('reports','customers_with_docs',$logged_in['role'])){ ?>
                <!-- Customers Reports -->
                <li class="<?php echo (isset($current_page_users) && !empty($current_page_users)) ? $current_page_users : ''; ?>"><a>Customers<span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li class="sub_menu"><a href="<?php echo base_url('reports/customers_with_docs');?>"> Customers With Docs</a></li>
                    <li><a href="<?php echo base_url('reports/customers_with_bank_detail');?>"> Customers With Bank Details</a></li>
                    <li><a href="<?php echo base_url('reports/customers_without_bank_detail');?>"> Customers Without Bank Details</a></li>
                    <li><a href="<?php echo base_url('reports/country_wise_customers');?>"> Country Wise Customers</a></li>
                    <li><a href="<?php echo base_url('reports/preferred_language_customers');?>"> Preferred Language Wise Customers</a></li>
                    <li><a href="<?php echo base_url('reports/registration_wise_customers');?>"> Registration Wise Customers</a></li>
                  </ul>
                </li>

                <!-- Deposits Reports -->
                <li class="<?php echo (isset($current_page_users) && !empty($current_page_users)) ? $current_page_users : ''; ?>"><a>Deposits<span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="<?php echo base_url('reports/customers_with_deposit');?>"> Customers With Deposit</a></li>
                    <li><a href="<?php echo base_url('reports/live_auction_wise_deposits');?>"> Live Auction Deposits</a></li>
                    <li><a href="<?php echo base_url('reports/customer_with_security_deposit');?>"> Customers With Security Deposits</a></li>
                    <li><a href="<?php echo base_url('reports/receive_ables');?>"> Receivables</a></li>
                    <li><a href="<?php echo base_url('reports/payables');?>"> Payables</a></li>
                    <li><a href="<?php echo base_url('reports/deposit_adjustments');?>"> Deposit Adjustments</a></li>
                    <li><a href="<?php echo base_url('reports/security_adjustments');?>"> Security Adjustments</a></li>
                    <li><a href="<?php echo base_url('reports/customer_cradit_card_deposit');?>"> Credit Card Deposit</a></li>
                    <li><a href="<?php echo base_url('reports/customer_bank_transfer');?>"> Bank Transfer</a></li>
                    <li><a href="<?php echo base_url('reports/customer_cash_deposit');?>"> Cash Deposit</a></li>
                    <li><a href="<?php echo base_url('reports/customer_cheque_deposit');?>"> Cheque Deposit</a></li>
                  </ul>
                </li>

                <!-- Controller Reports -->
                <li class="<?php echo (isset($current_page_users) && !empty($current_page_users)) ? $current_page_users : ''; ?>"><a>Controller<span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="<?php echo base_url('reports/live_auction_online_users');?>"> Live Auction Online Users</a></li>
                    <li><a href="<?php echo base_url('reports/controller_sales_summary');?>"> Sales Summary</a></li>
                    <li><a href="<?php echo base_url('reports/live_auction_report');?>"> Live Auction Report</a></li>
                  </ul>
                </li>

                <!-- Live Auction Reports -->
                <li class="<?php echo (isset($current_page_users) && !empty($current_page_users)) ? $current_page_users : ''; ?>"><a>Live Auction<span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="<?php echo base_url('reports/live_auction_sales_report');?>">  Sales Report</a></li>
                    <li><a href="<?php echo base_url('reports/live_auction_sales_summary');?>">  Sales Summary</a></li>
                    <li><a href="<?php echo base_url('reports/temporary_depost_refund');?>">  Temporary Deposit And Refund</a></li>
                  </ul>
                </li>

                <!-- Online/Closed Auctions Reports -->
                <li class="<?php echo (isset($current_page_users) && !empty($current_page_users)) ? $current_page_users : ''; ?>"><a>Online/Closed Auctions<span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="<?php echo base_url('reports/top_bidder'); ?>"> Top Bidder</a></li>
                    <li><a href="<?php echo base_url('reports/bidding_summary'); ?>"> Bidding Summary</a></li>
                    <li><a href="<?php echo base_url('reports/winner_bidder'); ?>"> Winner Bidder</a></li>
                    <li><a href="<?php echo base_url('reports/item_report_auction_wise'); ?>"> Item Report Auction Wise</a></li>
                    <li><a href="<?php echo base_url('reports/item_report_reserved_price'); ?>"> Item Report By Reserved Price</a></li>
                    <li><a href="<?php echo base_url('reports/online_auction_sales_report');?>">  Sales Report</a></li>
                  </ul>
                </li>

                <!-- item reports -->
                <li class="<?php echo (isset($current_page_users) && !empty($current_page_users)) ? $current_page_users : ''; ?>"><a>Stock Management<span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="<?php echo base_url('reports/inventory_report_model_wise'); ?>"> Inventory Report Model wise</a></li>
                    <li><a href="<?php echo base_url('reports/item_stock'); ?>"> Stock Report</a></li>
                    <li><a href="<?php echo base_url('reports/item_sold_by_auction_type'); ?>"> Item Sold By Auction Type</a></li>
                    <li><a href="<?php echo base_url('reports/item_movement_history'); ?>"> Item Movement Report</a></li>
                    <li><a href="<?php echo base_url('reports/seller_wise_inventory'); ?>"> Seller Wise Inventory</a></li>
                  </ul>
                </li>

                <!-- Accounts -->
                <li class="<?php echo (isset($current_page_users) && !empty($current_page_users)) ? $current_page_users : ''; ?>"><a>Accounts<span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="<?php echo base_url('reports/daily_transections'); ?>">Daily Transaction</a></li>
                    <li><a href="<?php echo base_url('reports/daily_summary'); ?>">Daily Summary</a></li>
                    <li><a href="<?php echo base_url('reports/top_buyer'); ?>">Top Buyer</a></li>
                    <li><a href="<?php echo base_url('reports/top_seller'); ?>">Top Seller</a></li>
                    <li><a href="<?php echo base_url('reports/top_seller_inventory'); ?>">Top Seller By Inventory</a></li>
                    <li><a href="<?php echo base_url('reports/permanent_depost_refund');?>">  Cashier Report</a></li>
                  </ul>
                </li>
              <?php } ?>

              <?php if ($this->acl_model->has_permission('reports','customer_with_security_deposit',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_users) && !empty($current_page_users)) ? $current_page_users : ''; ?>"><a href="<?php echo base_url('reports/customer_with_security_deposit');?>"> Customers With Security Deposit</span></a></li>
              <?php } ?>

            </ul>
          </li>
      <?php } ?>

      <!-- Live Controller -->
      <?php $main_permission_check2 = $this->acl_model->main_permission('livecontroller',$logged_in['role']);if($main_permission_check2){
        ?>
        <li class="" ><a ><i class="fa fa-gavel"></i> Auction <span class="variant-border"></span></a>
         <ul class="nav child_menu"  >
            <?php
             if ($this->acl_model->has_permission('livecontroller','index',$logged_in['role']))
             {
              ?>
              <li class=""><a href="<?php echo base_url();?>livecontroller">  Auction Controller </span></a></li>
            <?php
             }
             ?>
          </ul>
        </li>
      <?php } ?>

      <!-- History Managemant -->
      <?php $main_permission_check2 = $this->acl_model->main_permission('transaction',$logged_in['role']);
        if($main_permission_check2){ ?>
          <li class="<?php echo (($this->router->fetch_class() == 'transaction') ?  'active' : ''); ?>"><a><i class="fa fa-users"></i> History Management<span class="active variant-border"></span></a>
            <ul class="nav child_menu"  <?php echo (($this->router->fetch_class() == 'transaction') ?  'style="display:block;"' : ''); ?>>
              <?php if ($this->acl_model->has_permission('transaction','manage_deposite_acount',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_deposite) && !empty($current_page_deposite)) ? $current_page_deposite : ''; ?>"><a href="<?php echo base_url();?>transaction/manage_deposite_acount">Deposite History </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('transaction','user_security_history',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_customer) && !empty($current_page_customers)) ? $current_page_customers : ''; ?>"><a href="<?php echo base_url();?>transaction/user_security_history"> Security History </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('transaction','bid_history',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_bid_history) && !empty($current_page_bid_history)) ? $current_page_bid_history : ''; ?>"><a href="<?php echo base_url();?>transaction/bid_history"> Bid History </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('transaction','bank_deposit_list',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_deposit) && !empty($current_page_deposit)) ? $current_page_deposit : ''; ?>"><a href="<?php echo base_url();?>transaction/bank_deposit_list">Bank Deposit </span></a></li>
              <?php } ?>

            </ul>
          </li>
      <?php } ?>

      <!-- Jobcard Management -->
      <?php $main_permission_check2 = $this->acl_model->main_permission('jobcard',$logged_in['role']);
        if($main_permission_check2){
          ?>
          <li class="<?php echo (($this->router->fetch_class() == 'jobcard') ?  'active' : ''); ?>">
            <a><i class="fa fa-list"></i> Job Card <span class="active variant-border"></span></a>

            <ul class="nav child_menu" <?php echo (($this->router->fetch_class() == 'jobcard') ?  'style="display:block;"' : ''); ?>>
              <?php if ($this->acl_model->has_permission('jobcard','index',$logged_in['role']) || $this->acl_model->has_permission('jobcard','tasker_tasks_list',$logged_in['role'])){

                if ($this->acl_model->has_permission('jobcard','tasker_tasks_list',$logged_in['role'])){
                ?>
                  <li class="<?php echo (isset($current_page_assign_task) && !empty($current_page_assign_task)) ? $current_page_assign_task : ''; ?>">
                    <a href="<?php echo base_url();?>jobcard/tasker_tasks_list"> Assign Tasks <span class="active variant-border"></span></a>
                  </li>
                <?php } ?>

                <?php if ($this->acl_model->has_permission('jobcard','index',$logged_in['role'])){?>
                  <li class="<?php echo (isset($current_page_jobcard_user) && !empty($current_page_jobcard_user)) ? $current_page_jobcard_user : ''; ?>"><a href="<?php echo base_url();?>jobcard"> Job Card Users List <span class="active variant-border"></span></a></li>
                <?php } ?>

                <?php if ($this->acl_model->has_permission('jobcard','task_category_list',$logged_in['role'])){ ?>
                  <li class="<?php echo (isset($current_task_category) && !empty($current_task_category)) ? $current_task_category : ''; ?>"><a href="<?php echo base_url();?>jobcard/task_category_list"> Task Category List <span class="active variant-border"></span></a></li>
                <?php }  ?>

                <?php if ($this->acl_model->has_permission('jobcard','task_list',$logged_in['role'])){ ?>
                  <li class="<?php echo (isset($current_page_task) && !empty($current_page_task)) ? $current_page_task : ''; ?>"><a href="<?php echo base_url();?>jobcard/task_list"> Task List <span class="active variant-border"></span></a></li>
                <?php }  ?>

                <?php if ($this->acl_model->has_permission('jobcard','assigned_task',$logged_in['role'])){?>
                  <li class="<?php echo (isset($current_page_assigned_task) && !empty($current_page_assigned_task)) ? $current_page_assigned_task : ''; ?>"><a href="<?php echo base_url();?>jobcard/assigned_task"> Assigned Task <span class="active variant-border"></span></a></li>
                <?php } ?>

                <?php if ($this->acl_model->has_permission('jobcard','tasker_report',$logged_in['role'])){?>
                  <li class="<?php echo (isset($current_page_tasker_report) && !empty($current_page_tasker_report)) ? $current_page_tasker_report : ''; ?>"><a href="<?php echo base_url();?>jobcard/tasker_report"> Tasker Reports <span class="active variant-border"></span></a></li>
                <?php } ?>
              <?php } ?>
            </ul>
          </li>
      <?php } ?>

      <!-- Evaluation Management -->
      <?php $main_permission_check = $this->acl_model->main_permission('cars',$logged_in['role']);
        if($main_permission_check){ ?>
          <li class="<?php echo (($this->router->fetch_class() == 'cars') ?  'active' : ''); ?>"><a ><i class="fa fa-car"></i> Evaluation <span class="active variant-border"></span></a>
           <ul class="nav child_menu"  <?php echo (($this->router->fetch_class() == 'cars') ?  'style="display:block;"' : ''); ?>>
              <?php
               // if ($this->acl_model->has_permission('Cars','index',$logged_in['role'])){
                ?>
                <!-- <li><a href="<?php // echo base_url();?>cars"> Cars </span></a></li> -->
                <?php // } ?>
              <?php if ($this->acl_model->has_permission('cars','car_valuation',$logged_in['role'])){ ?>
               <!--  <li class="<?php echo (isset($current_page_cars) && !empty($current_page_cars)) ? $current_page_cars : ''; ?>"><a href="<?php echo base_url();?>cars/car_valuation">Car Valuation</a></li> -->
              <?php } ?>
              <?php if ($this->acl_model->has_permission('cars','makes',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_makes) && !empty($current_page_makes)) ? $current_page_makes : ''; ?>"><a href="<?php echo base_url();?>cars/makes">Makes</a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('cars','models',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_models) && !empty($current_page_models)) ? $current_page_models : ''; ?>"><a href="<?php echo base_url();?>cars/models">Models</a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('cars','valuation_mileage',$logged_in['role'])){ ?>
             <!--    <li class="<?php echo (isset($current_page_valuation_mileage_depreciation) && !empty($current_page_valuation_mileage_depreciation)) ? $current_page_valuation_mileage_depreciation : ''; ?>"><a href="<?php echo base_url();?>cars/valuation_mileage">Mileage Deprciation</a></li> -->
              <?php } ?>
              <?php if ($this->acl_model->has_permission('cars','mileage',$logged_in['role'])){?>
                <li class="<?php echo (isset($current_page_mileage) && !empty($current_page_mileage)) ? $current_page_mileage : ''; ?>"><a href="<?php echo base_url();?>cars/mileage">Mileage</a></li>
              <?php } ?>
              <?php // if ($this->acl_model->has_permission('cars','location',$logged_in['role'])){ ?>
                <!-- <li><a href="<?php // echo base_url();?>cars/location">Locations</a></li> -->
              <?php // } ?>
              <?php // if ($this->acl_model->has_permission('cars','dates',$logged_in['role'])){ ?>
                <!-- <li><a href="<?php // echo base_url();?>cars/dates">Dates</a></li> -->
              <?php // } ?>
              <?php if ($this->acl_model->has_permission('cars','engine_sizes',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_engine_size) && !empty($current_page_engine_size)) ? $current_page_engine_size : ''; ?>"><a href="<?php echo base_url();?>cars/engine_sizes">Engine Sizes</a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('cars','prices',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_prices) && !empty($current_page_prices)) ? $current_page_prices : ''; ?>"><a href="<?php echo base_url();?>cars/prices">Prices</a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('cars','evaluation_config',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_evaluation) && !empty($current_page_evaluation)) ? $current_page_evaluation : ''; ?>"><a href="<?php echo base_url();?>cars/evaluation_config">Evaluation Config</a></li>
              <?php } ?>
            </ul>
          </li>
      <?php  } ?>

      <!-- CRM Management -->
      <?php $main_permission_check2 = $this->acl_model->main_permission('crm',$logged_in['role']);
        if($main_permission_check2){ ?>
          <li class="<?php echo (($this->router->fetch_class() == 'crm') ?  'active' : ''); ?>"><a ><i class="fa fa-gears"></i> CRM <span class="active variant-border"></span></a>
            <ul class="nav child_menu" <?php echo (($this->router->fetch_class() == 'crm') ?  'style="display:block;"' : ''); ?>>
              <?php if ($this->acl_model->has_permission('crm','index',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_crm) && !empty($current_page_crm)) ? $current_page_crm : ''; ?>"><a href="<?php echo base_url();?>crm"> CRM </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('crm','reports',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_reports) && !empty($current_page_reports)) ? $current_page_reports : ''; ?>"><a href="<?php echo base_url();?>crm/reports"> CRM Reports</span></a></li>
              <?php } ?>
               <?php if ($this->acl_model->has_permission('crm','crm_sales_list',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_sales_list) && !empty($current_page_sales_list)) ? $current_page_sales_list : ''; ?>"><a href="<?php echo base_url();?>crm/crm_sales_list">CRM Sales</a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('crm','customer_type',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_customer_type) && !empty($current_page_customer_type)) ? $current_page_customer_type : ''; ?>"><a href="<?php echo base_url();?>crm/customer_type">Customer Type</a></li>
              <?php } ?>

              <?php if ($this->acl_model->has_permission('crm','loss_reasons',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_loss_reason) && !empty($current_page_loss_reason)) ? $current_page_loss_reason : ''; ?>"><a href="<?php echo base_url();?>crm/loss_reasons">Loss Reasons </a></li>
              <?php } ?>

              <?php if ($this->acl_model->has_permission('crm','source',$logged_in['role'])){ ?>
               <li class="<?php echo (isset($current_page_source) && !empty($current_page_source)) ? $current_page_source : ''; ?>"><a href="<?php echo base_url();?>crm/source">Lead Source</a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('crm','lead_category',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_lead_category) && !empty($current_page_lead_category)) ? $current_page_lead_category : ''; ?>"><a href="<?php echo base_url();?>crm/lead_category">Lead Category</a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('crm','lead_stage',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_lead_stage) && !empty($current_page_lead_stage)) ? $current_page_lead_stage : ''; ?>"><a href="<?php echo base_url();?>crm/lead_stage">Lead Stage</a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('crm','next_step',$logged_in['role'])){ ?>
                <li class="<?php echo (isset($current_page_next_step) && !empty($current_page_next_step)) ? $current_page_next_step : ''; ?>"><a href="<?php echo base_url();?>crm/next_step">Next Step</a></li>
              <?php } ?>

            </ul>
          </li>
      <?php } ?>

      <!-- Item Stock Management -->
      <?php $main_permission_check2 = $this->acl_model->main_permission('items',$logged_in['role']);
        if($main_permission_check2){ ?>
          <li class="<?php echo (($this->router->fetch_class() == 'items') ?  'active' : ''); ?>" ><a ><i class="fa fa-cubes"></i> Stock Management <span class="active variant-border"></span></a>
            <ul class="nav child_menu" <?php echo (($this->router->fetch_class() == 'items') ?  'style="display:block;"' : ''); ?> >
              <?php if ($this->acl_model->has_permission('items','categories',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_category) && !empty($current_page_category)) ? $current_page_category : ''; ?>"><a href="<?php echo base_url();?>items/categories"> Items Categories </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('items','makes',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_item_make) && !empty($current_page_item_make)) ? $current_page_item_make : ''; ?>"><a href="<?php echo base_url();?>items/makes"> Items Makes </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('items','index',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_list) && !empty($current_page_list)) ? $current_page_list : ''; ?>"><a href="<?php echo base_url();?>items"> Items List </span></a></li>
              <?php } ?>
            </ul>
          </li>
      <?php } ?>

      <!-- CMS Management -->
      <?php $main_permission_check2 = $this->acl_model->main_permission('cms',$logged_in['role']);
        if($main_permission_check2){ ?>
          <li class="<?php echo (($this->router->fetch_class() == 'cms') ?  'active' : ''); ?>" ><a ><i class="fa fa-shopping-cart"></i> CMS <span class="active variant-border"></span></a>
            <ul class="nav child_menu" <?php echo (($this->router->fetch_class() == 'cms') ?  'style="display:block;"' : ''); ?> >
              <?php if ($this->acl_model->has_permission('cms','terms',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_terms) && !empty($current_page_terms)) ? $current_page_terms : ''; ?>"><a href="<?php echo base_url();?>cms/terms"> Terms & Conditions </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('cms','policy',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_policy) && !empty($current_page_policy)) ? $current_page_policy : ''; ?>"><a href="<?php echo base_url();?>cms/policy"> Privacy Policy </span></a></li>
              <?php } ?>

              <?php if ($this->acl_model->has_permission('cms','socialLink',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_socialLinks) && !empty($current_page_socialLinks)) ? $current_page_socialLinks : ''; ?>"><a href="<?php echo base_url();?>cms/socialLink"> Social Links </span></a></li>
              <?php } ?>

              <?php if ($this->acl_model->has_permission('cms','storeLinks',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_storeLinks) && !empty($current_page_storeLinks)) ? $current_page_storeLinks : ''; ?>"><a href="<?php echo base_url();?>cms/storeLinks"> App Store Links </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('cms','faqs_listing',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_ques_ans) && !empty($current_page_ques_ans)) ? $current_page_ques_ans : ''; ?>"><a href="<?php echo base_url();?>cms/faqs_listing/"> Question & Answer </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('cms','contact_us',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_contact) && !empty($current_page_contact)) ? $current_page_contact : ''; ?>"><a href="<?php echo base_url();?>cms/contact_us"> Contact Us </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('cms','partners_listing',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_partners) && !empty($current_page_partners)) ? $current_page_partners : ''; ?>"><a href="<?php echo base_url();?>cms/partners_listing"> Our Partners </span></a></li>
              <?php } ?>

               <?php if ($this->acl_model->has_permission('cms','how_to_register',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_how_to_register) && !empty($current_page_how_to_register)) ? $current_page_how_to_register : ''; ?>"><a href="<?php echo base_url();?>cms/how_to_register"> How To Register</span></a></li>
              <?php } ?>

              <?php if ($this->acl_model->has_permission('cms','how_to_deposit',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_how_to_deposit) && !empty($current_page_how_to_deposit)) ? $current_page_how_to_deposit : ''; ?>"><a href="<?php echo base_url();?>cms/how_to_deposit"> How To Deposit</span></a></li>
              <?php } ?>

              <?php if ($this->acl_model->has_permission('cms','auction_guide',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_auction_guide) && !empty($current_page_auction_guide)) ? $current_page_auction_guide : ''; ?>"><a href="<?php echo base_url();?>cms/auction_guide"> Guide To Auction</span></a></li>
              <?php } ?>

              <?php if ($this->acl_model->has_permission('cms','quality_policy',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_quality_policy) && !empty($current_page_quality_policy)) ? $current_page_quality_policy : ''; ?>"><a href="<?php echo base_url();?>cms/quality_policy"> Quality Policy</span></a></li>
              <?php } ?>

                <!-- ////////////// -->
                  <li class="<?php echo ((isset($current_page_our_team) || isset($current_page_team_information)) ?  'active' : ''); ?>"><a > Our Team </span></a>

                  <ul class="nav child_menu" <?php echo ((isset($current_page_our_team) || isset($current_page_team_information)) ?  'style="display: block;"' : ''); ?> >
                    <li class="<?php echo (isset($current_page_our_team) && !empty($current_page_our_team)) ? $current_page_our_team : ''; ?>"><a href="<?php echo base_url();?>cms/our_team"> Members Information </span></a></li>
                    <li class="<?php echo (isset($current_page_team_information) && !empty($current_page_team_information)) ? $current_page_team_information : ''; ?>"><a href="<?php echo base_url();?>cms/team_information"> Page Information </span></a></li>
                  </ul>
                </li>

                 <li class="<?php echo ((isset($current_page_header_info) || isset($current_page_banner)) ?  'active' : ''); ?>"><a > Home Information </span></a>

                  <ul class="nav child_menu" <?php echo ((isset($current_page_header_info) || isset($current_page_banner)) ?  'style="display: block;"' : ''); ?> >
                    <li class="<?php echo (isset($current_page_header_info) && !empty($current_page_header_info)) ? $current_page_header_info : ''; ?>"><a href="<?php echo base_url();?>cms/side_banner"> Banner Information </span></a></li>
                    <li class="<?php echo (isset($current_page_banner) && !empty($current_page_banner)) ? $current_page_banner : ''; ?>"><a href="<?php echo base_url();?>cms/slider"> Home Slider </span></a></li>
                  </ul>
                </li>


                <!-- /////////// -->
                <li class="<?php echo ((isset($current_page_aboutus) || isset($about_us_history)) ?  'active' : ''); ?>"><a > About Us </span></a>

                  <ul class="nav child_menu" <?php echo ((isset($current_page_aboutus) || isset($about_us_history)) ?  'style="display: block;"' : ''); ?>>
                    <li class="<?php echo (isset($current_page_aboutus) && !empty($current_page_aboutus)) ? $current_page_aboutus : ''; ?>"><a href="<?php echo base_url();?>cms/about_us"> About Us </span></a></li>
                    <li class="<?php echo (isset($about_us_history) && !empty($about_us_history)) ? $about_us_history : ''; ?>"><a href="<?php echo base_url();?>cms/aboutus_history_listing"> About Us History </span></a></li>
                  </ul>
                </li>

            </ul>
          </li>
      <?php } ?>

      <!-- Auction Items Management  -->
      <?php $main_permission_check2 = $this->acl_model->main_permission('auction',$logged_in['role']);
        if($main_permission_check2){ ?>
          <li class="<?php echo (($this->router->fetch_class() == 'auction') ?  'active' : ''); ?>" ><a ><i class="fa fa-gavel"></i> Auction <span class="active variant-border"></span></a>
            <ul class="nav child_menu" <?php echo (($this->router->fetch_class() == 'auction') ?  'style="display:block;"' : ''); ?> >
              <?php if ($this->acl_model->has_permission('auction','index',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_auction) && !empty($current_page_auction)) ? $current_page_auction : ''; ?>"><a href="<?php echo base_url();?>auction"> Online Auction / Sales List </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('auction','live',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_live_auction) && !empty($current_page_live_auction)) ? $current_page_live_auction : ''; ?>"><a href="<?php echo base_url();?>auction/live"> Live Auction / Sales List </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('auction','live_auction_controller',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_live_setting_auction) && !empty($current_page_live_setting_auction)) ? $current_page_live_setting_auction : ''; ?>"><a href="<?php echo base_url();?>auction/live_auction_controller"> Live Auction Setting </span></a></li>
              <?php } ?>
              <!-- <?php
               if ($this->acl_model->has_permission('auction','direct_sale',$logged_in['role']))
               {
                ?>
                <li class="<?php echo (isset($current_page_directsale) && !empty($current_page_directsale)) ? $current_page_directsale : ''; ?>"><a href="<?php echo base_url();?>auction/direct_sale"> Direct Sale </span></a></li>
              <?php
               }
               ?>  -->

            </ul>
          </li>
      <?php } ?>


          <!-- //////Users Accounts List/////// -->
      <?php $main_permission_check2 = $this->acl_model->main_permission('accounts',$logged_in['role']);
        if($main_permission_check2){ ?>
          <li class="<?php echo (($this->router->fetch_class() == 'accounts') ?  'active' : ''); ?>" ><a ><i class="fa fa-money"></i> Accounts <span class="active variant-border"></span></a>
           <ul class="nav child_menu" <?php echo (($this->router->fetch_class() == 'accounts') ?  'style="display:block;"' : ''); ?> >
              <?php if ($this->acl_model->has_permission('accounts','index',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_auction) && !empty($current_page_auction)) ? $current_page_auction : ''; ?>"><a href="<?php echo base_url();?>accounts"> Users Accounts List</span></a></li>
              <?php } ?>
            </ul>
          </li>
      <?php } ?>

      <!-- Sales Item List -->
      <!-- <?php $main_permission_check2 = $this->acl_model->main_permission('sales',$logged_in['role']);
        if($main_permission_check2){ ?>
          <li class="<?php echo (($this->router->fetch_class() == 'sales') ?  'active' : ''); ?>" ><a ><i class="fa fa-shopping-cart"></i> Sales <span class="active variant-border"></span></a>
           <ul class="nav child_menu" <?php echo (($this->router->fetch_class() == 'sales') ?  'style="display:block;"' : ''); ?> >
              <?php if ($this->acl_model->has_permission('sales','index',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_sales) && !empty($current_page_sales)) ? $current_page_sales : ''; ?>"><a href="<?php echo base_url();?>sales"> Sales / Sales List </span></a></li>
              <?php } ?> 
            </ul>
          </li>  
      <?php } ?> -->

      <!-- Settings -->
      <?php $main_permission_check2 = $this->acl_model->main_permission('settings',$logged_in['role']);
        if($main_permission_check2){ ?>
          <li class="<?php echo (($this->router->fetch_class() == 'settings') ?  'active' : ''); ?>"><a ><i class="fa fa-gear"></i> Settings <span class="active variant-border"></span></a>
            <ul class="nav child_menu" class="nav child_menu" <?php echo (($this->router->fetch_class() == 'settings') ?  'style="display:block;"' : ''); ?>>
              <?php if ($this->acl_model->has_permission('settings','email_template',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_email_template) && !empty($current_page_email_template)) ? $current_page_email_template : ''; ?>"><a href="<?php echo base_url();?>settings/email_template_list"> Email Templates </span></a></li>
              <?php } ?>
               <?php if ($this->acl_model->has_permission('settings','notification',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_notification) && !empty($current_page_notification)) ? $current_page_notification : ''; ?>"><a href="<?php echo base_url();?>settings/notification"> Email Notification </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('settings','seller',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_seller_settings) && !empty($current_page_seller_settings)) ? $current_page_seller_settings : ''; ?>"><a href="<?php echo base_url();?>settings/seller"> Seller Setting </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('settings','buyer',$logged_in['role'])) { ?>
                <!-- <li><a href="<?php //echo base_url();?>settings/configuration"> Buyer Setting </span></a></li> -->
                <li class="<?php echo (isset($current_page_buyer_settings) && !empty($current_page_buyer_settings)) ? $current_page_buyer_settings : ''; ?>"><a href="<?php echo base_url();?>settings/buyer"> Buyer Setting </span></a></li>
              <?php } ?>
              <?php if ($this->acl_model->has_permission('settings','buyer',$logged_in['role'])) { ?>
                <li class="<?php echo (isset($current_page_general_setting) && !empty($current_page_general_setting)) ? $current_page_general_setting : ''; ?>"><a href="<?php echo base_url();?>settings/configuration"> General Settings </span></a></li>
              <?php } ?>
              <?php
                if ($this->acl_model->has_permission('settings','bankDetail',$logged_in['role'])) { ?>
                  <li class="<?php echo (isset($current_page_bankDetails) && !empty($current_page_bankDetail)) ? $current_page_bankDetail: ''; ?>"><a href="<?php echo base_url();?>settings/bankDetail"> Bank Details </span></a></li>
              <?php } ?>
                <li class="<?php echo (isset($current_page_pop_up) && !empty($current_page_pop_up)) ? $current_page_pop_up : ''; ?>"><a href="<?php echo base_url();?>settings/popup"> Popup Banner </span></a></li>



            </ul>
          </li>
      <?php } ?>
    </ul>

        <!--   <ul style="display: none;"  >
                <li><a href="<?php echo base_url();?>cars">Cars</a></li>
                <li><a href="<?php echo base_url();?>admin/cars/carlist">Biddings</a></li>
                <li><a href="<?php echo base_url();?>admin/cars/vehicles">Vehicle Detail</a></li>
                <li><a href="<?php echo base_url();?>admin/cars/vehicle_specs">Vehicle Specs</a></li>
                <li><a href="<?php echo base_url();?>admin/cars/locations">Generals</a></li>
                <li><a href="<?php echo base_url();?>admin/cars/dates">Vehicle Body Repots</a></li>
                <li><a href="<?php echo base_url();?>admin/cars/engine_sizes">Engine Condition</a></li>
                <li><a href="<?php echo base_url();?>admin/cars/prices">Engine Noises</a></li>
                <li><a href="<?php echo base_url();?>admin/cars/evaluation_config">Gear Box Condition
                <li><a href="<?php echo base_url();?>admin/cars/ehicles">AC Condition
                <li><a href="<?php echo base_url();?>admin/cars/vehicle">Accessory Parts
                <li><a href="<?php echo base_url();?>admin/cars/vehicls">Fluide
                <li><a href="<?php echo base_url();?>admin/cars/vehices">Test Drive General
                <li><a href="<?php echo base_url();?>admin/cars/vehiles">Electrical System
                <li><a href="<?php echo base_url();?>admin/cars/vehcles">Tyres and Wheels
                <li><a href="<?php echo base_url();?>admin/cars/veicles">Interiors
                <li><a href="<?php echo base_url();?>admin/cars/vhicles">Breaks
                </a></li>
          </ul>
      </li> -->


      <!--
      <li <?php if($this->uri->segment(3)=='add_city' || $this->uri->segment(3)=='edit_city'){?> class="current-page" <?php } else{} ?> ><a href="<?php echo base_url();?>admin/City"><i class="fa fa-building"></i>Cities</a> </li>

      <li <?php if($this->uri->segment(3)=='save_doctor_type' || $this->uri->segment(3)=='edit_doctor_type'){?> class="current-page" <?php } else{} ?> ><a href="<?php echo base_url();?>admin/Doctor_Type"><i class="fa fa-stethoscope"></i>Doctor Types</a> </li>
      
      <li><a href="<?php echo base_url();?>admin/Practice"><i class="fa fa-stethoscope"></i>Practices</a> </li>
      
      <li><a href="<?php echo base_url();?>admin/Doctor"><i class="fa fa-user-md"></i>Doctors</a> </li>
      
      <li><a href="<?php echo base_url();?>admin/Education"><i class="fa fa-graduation-cap"></i>Education</a> </li>

      <li><a href="<?php echo base_url();?>admin/Patient"><i class="fa fa-bed"></i>Patients</a> </li>
      
      <li <?php if($this->uri->segment(3)=='Feedback'){?> class="current-page" <?php } else{} ?> ><a href="<?php echo base_url();?>admin/Feedback"><i class="fa fa-comments"></i>Feedbacks
       </a> </li>

      <li><a href="<?php echo base_url();?>admin/Insurance"><i class="fa fa-money"></i>Insurance</a> </li>
      <li><a href="<?php echo base_url();?>admin/Payment"><i class="fa fa-money"></i>Payment</a> </li>
      <li><a href="<?php echo base_url();?>admin/Chat"><i class="fa fa-comments"></i>Chats</a> </li>
      <li><a href="<?php echo base_url();?>admin/Payment/payment_configration"><i class="fa fa-comments"></i>Configration</a> </li> -->

  </div>
</div>



