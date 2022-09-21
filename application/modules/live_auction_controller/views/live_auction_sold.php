
<!-- PNotify -->
    <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.css">

<style>
  body {background: #f5f5f5}
  .main-wapper {padding: 40px 0;}
  .auction-nam h2 {font-size: 20px; background: #e68522; text-align: center; padding: 6px 15px; width: 100%; margin-bottom: 2px; }
  .auction-nam h3 {font-size: 15px; background: #f1c370; text-align: center; padding: 3px 15px; width: 100%; margin-bottom: 4px; }
  .summary h3 {color: #2828c1; font-size: 19px; line-height: 1.1; font-weight: normal;}
  .summary h2 {color: #d60e0e; font-size: 28px; line-height: 1; font-weight: normal; }
  .summary h4 {color: gray; font-size: 18px; line-height: 1; font-weight: normal; }

 fieldset {
    border: solid 1px #b3b3b3;
    padding: 0 1.4em 6px 1.4em;
    border-radius: 0px;
    margin: 0 0 15px 0;
    box-shadow: 0px 0px 0px 0px #000;
}

 fieldset legend {
    font-size: 13px;
    text-align: left;
    width: auto;
    padding: 0 8px 0px 2px;
    border-bottom: none;
}

.detail-table {
  border: 2px solid gray;
  height: 250px;
  overflow: scroll;
}
.ozStarted{
  background: #1ABB9C;
  color: #fff;
}
.ozStoped{
  background: #d60e0e;
  color: #fff;
}
table { width: 100%;}

table th {background: lightgray;}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
  font-size: 13px;
  font-weight: normal;
}

table tbody tr:hover {
    background: #F7F8FB;
}

td {padding: 0px 9px}

.lot,
.reg,
.make,
.colour,
.reverse{width: 12%;}

.status {width: 40%;}

.bolder {font-weight: bolder;}

ul {list-style: none; padding: 0; margin: 0;}

.bid-update ul {display: flex; align-items: center; flex-wrap: wrap; justify-content: space-between; width: 100%}
.bid-update li {width: 31%; margin-bottom: 15px;}

.btn-counter {font-size: 16px; padding: 8px 10px; color: gray; background: #f5f5f5; border-radius: 0; width: 100%; min-height: 44px; box-shadow: none; outline: none !important;  }

tbody tr:hover {box-shadow: 0px 0px 6px inset gray}
.detail-table tbody tr:hover .bolder {color: #000 !important;}
.detail-table tbody tr {background: #FFF; }

thead tr, th, td { border: 1px solid gray !important; }

.table table {height: 200px; border: 1px solid lightgray; margin-bottom: 10px; width: 100%;}
.table table tr th:first-child {width: 35%;}
.table table tr th:nth-child(2) {width: 35%;}
.table table tr th:nth-child(3) {width: 15%;}
.table table tr th:nth-child(4) {width: 15%;}
.table tbody .empty {background: #fff; height: 104px;}

.table tbody .empty td{ min-height: 150px; padding: 20px 10px; font-size: 22px; font-weight: 600; line-height: 1.3;}

select {width: 100%; padding: 5px 20px; font-size: 16px; font-weight: 600;}

.sold-items {margin: 10px 0; }

.sold-items ul {display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; width: 90%; margin: auto;}

.sold-items li {width: 19.7%; border: 1px solid gray; text-align: center; font-size: 12px; background: rgba(234, 164, 34, 0.25);}

.green-text {color: green;}
.blue-text {color: blue;}
.red-text {color: red;}

.start-auction {width: 100%; font-size: 13px; line-height: 1; padding: 10px 10px; font-weight: 600;}

/*.bids-increment {display: flex; align-items: center;}*/
.bids-increment p {font-size: 15px; font-weight: 600;}
.bids-increment input {width: 100%; padding: 4px 10px;}
.bids-increment .btn-inc {padding: 6px 10px;}

.btn-inc {width: 100%; color: gray; background: #f5f5f5; padding: 4px 10px; font-size: 12px; outline: none !important;}

.box {height: 250px; background: #fff; overflow: scroll; border: 1.5px solid gray; padding: 5px 15px;}
.box li {font-size: 14px; line-height: 1.4; position: relative; }
.box li:before {content: ''; box-shadow: 0px 0px 1px inset; position: absolute; left: -10px; top: 0; width: 5px; height: 100%; background: lightblue; opacity: 0;}

.box li:hover:before,
.box li:focus:before,
.box li.active:before {opacity: 1;} 

.btn-box li:not(:last-child) {margin-bottom: 15px;}

@media screen and (max-width: 1180px){

  .table table tr th {vertical-align: middle;}
}

@media screen and (max-width: 1024px){

  .btn-box {margin: 20px 0;}

  select {margin-bottom: 20px;}

}

@media screen and (max-width: 991px){

  .bid-update li {width: 49%;}

  .sold-items ul {width: 100%; max-width: 100%;}
}

.aution-table select {width: 70px; padding: 0; font-size: 12px; margin: 10px auto;}

@media only screen and (max-width: 1000px)
{

.table tbody .empty td {font-size: 18px}

.responsive-on-1200 thead {display: none;}
.responsive-on-1200 tr {display: block; border-bottom: 1px solid rgba(35, 57, 79, 0.2); margin-bottom: 10px;}
.responsive-on-1200 tr td {width: 100% !important; display: flex; padding: 12px !important; text-align: left !important; border: 1px solid rgba(35, 57, 79, 0.2); border-bottom: 0;}
.responsive-on-1200 tr td:before {content: attr(data-label); padding-left: 10px; font-size: 14px; text-align: left; width: 35%; border-right: 1px solid rgba(35, 57, 79, 0.2); display: inline-flex; align-items: center; margin-right: 15px; padding: 14px 0; margin: -12px 0; margin-right: 15px; color: #000 !important}
.aution-table select {width: 120px; margin: 10px; padding: 2px;}
}
.yellow-box {background: yellow; border: 1px solid #ccc; padding: 20px 30px; display: table; margin: auto; margin-right: 0;}
.summary .yellow-box + h4 {color: #000;}

.bids-detail .box {height: auto !important; max-height: 230px;}

.button-row {text-align: center;}

.button-row button { width: 130px; margin: 30px auto; }

.auction-detail ul {display: flex; align-items: center; justify-content: center; flex-wrap: wrap;}
.auction-detail li {width: 48%; margin: auto; }

table tbody tr.active {background: lightblue; color: #000;}

.aution-table {height: 330px; overflow: scroll;}


</style>

  <main class="main-wapper">
    <div class="container">
        <div id="result"></div>
         <div class="row">
          <input type="hidden" id="item_buyer_id">
          <input type="hidden" id="auction_item_id">
            <div class="col-md-8">
              <?php if ($this->session->flashdata('msg')) {?>
            <div class="alert">
                <div class="alert alert-domain alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                      </button>
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
            </div>
            <?php }?>
              <select class="auction_list_option">
                <option value="">Select Sales for auctions</option>
                <?php 
                    if(isset($active_live_auctions) && !empty($active_live_auctions)){
                        foreach ($active_live_auctions as $value) {
                          $title = json_decode($value['title'], true);
                 ?>
                    <option value="<?= $value['id'] ?>"><?= $title['english'] ?></option>
                <?php 
                        }
                    }else{ ?>
                    <option value="">No Active Bid</option>
                <?php } ?>
              </select>
            </div>
            <div class="col-md-4">
              <button class="btn btn-info" id="find_button">Find</button>
            <a class="btn btn-info fa fa-plus" target="_blank" href="<?php echo base_url().'auction/save_user?sh=au'; ?>" pid="find_button">Add User</a>
            </div>
          </div>
      <hr>
      <div class="aution-table responsive-on-1200" >
        <table>
          <thead>
            <tr>
              <th>Entered</th>
              <th>Lot</th>
              <th>Item Name</th>
              <th>Reg No</th>
              <th>Make</th>
              <th>Model</th>
              <th>Seller code</th>
              <th>Seller Name</th>
              <th>Reserve</th>
              <th>Sale price</th>
              <th>Buyer</th>
              <th>B/No</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody class="sold_item_list">
            <tr>
              <td colspan="13" class="text-center">
                No Item Found
              </td>
            </tr>
           <!--  <tr>
              <td data-label="Entered">28/02/2020</td>
              <td data-label="Lot">1</td>
              <td data-label="Reg No">1161180</td>
              <td data-label="Make">Office</td>
              <td data-label="Model">Furnitures</td>
              <td data-label="Saller code">001234</td>
              <td data-label="Saller Name">Shaker Mohamed Ismail</td>
              <td data-label="Reserve">6000</td>
              <td data-label="Sale price">0.00</td>
              <td data-label="Buyer">*PB* Mohamed Ali</td>
              <td data-label="B/No">1171</td>
              <td data-label="Status">
                <select>
                  <option>Sold</option>
                  <option>Un-Sold</option>
                  <option>On approval </option>
                </select>
              </td>
            </tr>  -->
          </tbody>
        </table>
      </div> 
    </div>
  </main> 

    <!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';

  window.setInterval(function(){
    getSoldItemsdata();
  }, 120000);


  $('#find_button').on('click', function(){
    getSoldItemsdata();
  });

  // getAuctionSoldItems
  function getSoldItemsdata(){
    
      console.log('hide');
    var auction_id = $('.auction_list_option').val();
    // var item_id = $('#item_id').val();
    if(auction_id){

      $.ajax({
        url: "<?php echo base_url(); ?>" + "livecontroller/getAuctionSoldItems",
        type: 'post',
        data: {auction_id: auction_id, [token_name]:token_value},
        beforeSend: function(){
          //$('.sold_items_list').html('<img src="<?php echo base_url(); ?>assets_admin/images/load.gif">');
        },
        success: function(data) {

          var objData = jQuery.parseJSON(data);
          if(objData.error == false){
            $('.sold_item_list').html(objData.response);
            $('.sold_hide').hide();
          }else{
            // $('#auctions_items_list_tab tbody').html('');
          }
        }
      }); 
    }
  }

  // sold on approval live auction bid
  function updateSoldStatus(e){
      var auction_id = $(e).data('auctionids');
      var item_id = $(e).data('ids');
      var status = $(e).val();
      // alert(status);
      // ajax for sale on approva live auction item
      if (status == 'not_sold') {
          swal({
              title: "Are You Sure ",
              text: "Are you sure you want to un-sold this item.",
              type: "info",
              showCancelButton: true,
              cancelButtonClass: 'btn-default btn-md waves-effect',
              confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
              confirmButtonText:  "<?= $this->lang->line('confirm');?> ",
              cancelButtonText:  "<?= $this->lang->line('cancel');?> "
          },function(isConfirm) {
              if (isConfirm) {
                  $.ajax({
                      url: "<?php echo base_url(); ?>" + "livecontroller/updateSoldstatus",
                      type: 'post',
                      data: {auction_id: auction_id,item_id: item_id,status: status, [token_name]:token_value},
                      success: function(data) {
                          var objData = jQuery.parseJSON(data);
                          if(objData.error == false){
                              getSoldItemsdata();          
                          }else{
                        
                          }
                      }
                  }); 
              }
          });
      } else {
        $.ajax({
            url: "<?php echo base_url(); ?>" + "livecontroller/updateSoldstatus",
            type: 'post',
            data: {auction_id: auction_id,item_id: item_id,status: status, [token_name]:token_value},
            success: function(data) {
                var objData = jQuery.parseJSON(data);
                if(objData.error == false){
                    getSoldItemsdata();          
                }else{
              
                }
            }
        });
      }

  }

  // add item buyer
  function update_item_buyer(){
    
    var auction_item_id = $('#auction_item_id').val();
    if (auction_item_id != '') {
      var item_buyer_id = $('#item_buyer_id').val();
      // alert(auction_item_id);
      // alert(item_buyer_id);
     // ajax for sale on approva live auction item
      $.ajax({
        url: "<?php echo base_url(); ?>" + "livecontroller/updateitembuyer",
        type: 'post',
        data: {auction_item_id: auction_item_id,item_buyer_id: item_buyer_id, [token_name]:token_value},
        success: function(data) {
          var objData = jQuery.parseJSON(data);
          if(objData.error == false){
          getSoldItemsdata();          
        }else{
      
        }
        }
      }); 
    }
  }

  //Load buyer popup
  function selectbuyer(e){
    event.preventDefault();
    var url = '<?php echo base_url();?>';
    var auctionitem_id = $(e).attr('auctionitem_id');
    $('#auction_item_id').val(auctionitem_id);
    console.log('select buyer');
    $.ajax({ 
      type: "post", //create an ajax request to display.php
      url: url + "auction/load_user_popup",    
      data:{[token_name]:token_value},
      success: function(data) {
          objdata = $.parseJSON(data);
          if(objdata.success == true)
          {
            $('#data_table').html('');
            $('#data_table').html(objdata.opup_data);
            $('#users').modal({
              backdrop: 'static'
            })
            $('#users').modal();
          }
          else
          {
            // $('.make_case').hide();
            $('#model').attr('disabled',false);
            $('#model').html('<option value="">Select Model</option>');
          }
      }
    });
  }

  
</script>
<script>
  $(document).ready(function(){
   $('footer').hide();
  });
</script>