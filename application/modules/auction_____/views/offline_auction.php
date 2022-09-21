
<!-- <link rel="stylesheet" href="<?php //echo base_url();?>assets_admin/css/bootstrap4.min.css"> -->

<style type="text/css">
  
/*.over_all_body {font-family: 'Gotham-regular', 'Frutiger-regular'; margin:0; font-size: 16px; line-height: 1;}*/

/*img {max-width: 100%;}*/

/*ul {padding: 0; margin: 0; list-style: none;}*/

/*a {text-decoration: none !important; color: #000; transition: all .4s ease;}*/

/*h1,h2,h3,h4,h5,h6 {margin: 0;}*/
@media print {
  #print_btn {
    display: none;
  }
  #buy_btn {
    display: none;
  }
  #select_btn {
    display: none;
  }
  #item_select {
    display: none;
  }
}

textarea {resize: none;}

.container2 {max-width: 1240px; margin: 0 auto;}

.button-row {text-align: center;}

button,
button:focus,
.btn {outline: none !important}

.btn.btn-default { background: linear-gradient(168.83deg, #1B75BC 18.61%, #192297 80.39%); padding: 34px 25px; border-radius: 0; transition: all .4s ease; font-size: 50px; line-height: .8; color: #FFFFFF;position: relative; border: 0; text-transform: uppercase;}
.btn.btn-default:hover,
.btn.btn-default:focus {background: #1B75BC !important; color: #fff;}
.btn.btn-default i {font-size: 30px; opacity: .78; font-style: normal; vertical-align: top;}

.btn.btn-primary { background:  linear-gradient(168.81deg, #1B75BC 18.61%, #192297 80.39%); padding: 17px 28px; border-radius: 0; transition: all .4s ease; font-size: 20px; line-height: 24px; color: #FFFFFF; /*border: 5px solid #fff;*/ margin: 5px; position: relative; border: 0; text-transform: uppercase;}
.btn.btn-primary:before {content: ''; width: calc(100% + 10px); height: calc(100% + 10px); border: 1px solid  #1B75BC /*#192297*/ ; position: absolute; left: -5px; top: -5px; transition: all .4s ease;}
.btn.btn-primary:hover,
.btn.btn-primary:focus {background: #E11F26 !important; color: #fff;}
.btn.btn-primary:hover:before,
.btn.btn-primary:focus:before {border-color: #E11F26;}

.btn.btn-default:hover,
.btn.btn-default:focus {background:  linear-gradient(168.81deg, #1B75BC 18.61%, #192297 80.39%) !important; color: #fff;}
.btn.btn-default:hover:before,
.btn.btn-default:focus:before {border-color:  linear-gradient(168.81deg, #1B75BC 18.61%, #192297 80.39%);}

.form-group label {  font-size: 14px; line-height: 22px; color: rgba(0, 0, 0, 0.9); display: block;}
.form-control { background: #F7F8FB; color: rgba(0, 0, 0, 0.4); border-radius: 0; border: none !important; min-height: 54px; padding: 17px 19px; font-size: 16px; line-height: 19px; outline: none !important; box-shadow: none;}
.form-control input { color: #000000; opacity: 0.2;}
.form-control::placeholder {color: #00000033;}
.form-control:focus {border: 0 !important; outline: none; box-shadow: inset 0 0 0 1.5px #FABE13 !important;}

.over_all_body {padding: 20px 0;}
h1 { font-size: 40px; color: #000; text-align: center; margin: 0 0 20px 0;}
h2 { font-size: 28px; color: #000; text-align: center; margin: 0 0 20px 0;}

.nav-tabs {border: 0; margin: 30px 0 30px 0; justify-content: center;}
.nav-tabs .nav-item {margin: 0;}
.nav-tabs .nav-link { font-size: 18px; border: 0; background: transparent; color: #000 !important; padding: 12px 25px; border-radius: 0; border-bottom: 2px solid transparent;}
.nav-tabs .nav-link:hover {border-color: transparent;}
.nav-tabs .nav-link.active {border-color: #FABE13;}


.info-table {width: 100%; table-layout: fixed; margin: 40px 0 30px 0;}
.info-table tr {width: 100%;}
.info-table tr th { font-size: 16px;}
.info-table th,
.info-table td {padding: 10px 12px; border: 1px solid #ccc;}
</style>
<div class="over_all_body">
  <div class="container2" id="container2">
    <h1>Direct Sale</h1>
    <h2></h2>
    <div id="result"></div>
    <?php if($this->session->flashdata('success')){ ?>
      <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> <?= $this->session->flashdata('success'); ?>
      </div>
    <?php } ?>
    <form>
      <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label>Item</label>
            <input type="text" id="item_name" disabled class="form-control">
            <input type="hidden" id="item_id" disabled class="form-control">
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label>&nbsp;</label>
            <button class="btn btn-primary" data-toggle="modal" data-target="#items" style="font-size: 15px; padding: 5px 10px;" id="item_select">Select</button>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label>Lot:</label>
            <input type="text" id="lot_no" disabled class="form-control">
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label>Reg:</label>
            <input type="text" id="registration_no" disabled class="form-control">
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label>Make:</label>
            <input type="text" disabled id="make" class="form-control">
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label>Model:</label>
            <input type="text" disabled id="model" class="form-control">
          </div>
        </div>
      </div>
    </form>

    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Seller</a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane  show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <form>
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Seller</label>
                <input type="text" disabled id="code" class="form-control" name="">
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label>&nbsp;</label>
                <input type="text" disabled id="name" class="form-control" name="">
                <input type="hidden" id="seller_id" class="form-control" name="seller_id">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label>% Comission:</label>
                <input type="text" id="seller_percentage_comission" class="form-control" name="" style="background-color: #FFFFFF;" >
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label>Price Comission:</label>
                <input type="text" id="seller_price_comission" class="form-control" name="" style="background-color: #FFFFFF;" >
              </div>
            </div>
          </div>
        </form>
        <table class="info-table">
          <thead>
            <th>Transaction Date</th>
            <th>Description</th>
            <th>Credit</th>
            <th>Debit</th>
            <th>Type</th>
          </thead>
          <tbody id="seller_tblrows">
            
            </tr>
          </tbody>
        </table>
        <div class="row justify-content-end">
          <div class="col-sm-6">
            <div class="form-group">
              <label>Seller Balance:</label>
              <input type="text" disabled id="seller_T_balance" class="form-control" name="">
            </div>
          </div>
        </div>
      </div>
    </div>

    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Buyer</a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
          <form>
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Buyer</label>
                  <input type="text" id="B_code" disabled class="form-control" name="">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>&nbsp;</label>
                  <button class="btn btn-primary" style="font-size: 15px; padding: 5px 10px;" id="select_btn">Select</button>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>&nbsp;</label>
                  <input type="text" disabled id="B_name" class="form-control" name="">
                  <input type="hidden" id="buyer_id">
                </div>
              </div>
              <div class="col-sm-6">
              <div class="form-group">
                <label>% Comission:</label>
                <input type="text" id="buyer_percentage_comission" class="form-control" name="" style="background-color: #FFFFFF;" >
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label>Price Comission:</label>
                <input type="text" id="buyer_price_comission" class="form-control buyer_price_comission" name="" style="background-color: #FFFFFF;" >
              </div>
            </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Amount:</label>
                  <input type="text" id="D_S_amount" class="form-control" name="" style="background-color: #FFFFFF;" >
                </div>
              </div>
              <!-- <div class="col-sm-6">
                <div class="form-group">
                  <label>From:</label>
                  <input type="text" class="form-control" name="" style="background-color: #FFFFFF;" >
                </div>
              </div> -->
            </div>
          </form>
          <table class="info-table">
            <thead>
              <th>Transactoin Date</th>
              <th>Description</th>
              <th>Credit</th>
              <th>Debit</th>
              <th>Type</th>
            </thead>
            <tbody id="buyer_tblrows">
              <!-- <tr>
                <td>2787387838</td>
                <td>text-293293023</td>
                <td>5000.00</td>
                <td>350.00</td>
                <td>A</td>
              </tr>
              <tr>
                <td>2787387838</td>
                <td>text-293293023</td>
                <td>5000.00</td>
                <td>350.00</td>
                <td>B</td>
              </tr> -->
            </tbody>
          </table>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Buyer Balance:</label>
                <input type="text" disabled id="buyer_Total_balance" class="form-control" name="" >
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label>&nbsp;</label>
              <button class="btn btn-primary" style="font-size: 15px; padding: 5px 10px; " id="buy_btn">Buy</button><br><br>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
        <button class="btn btn-primary" style="font-size: 15px; padding: 5px 10px;" id="print_btn">Print</button>
</div>

<!-- items popup -->
<div class="modal" id="items" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
      </div>

      <div class="modal-body">
        <div class="x_content">
          <table id="allitemstableid" class="table jambo_table bulk_action" cellspacing="0" width="100%">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Registration No</th>
                      <th>Item Name</th>
                      <th>Item Make</th>
                      <th>Item Model</th>
                  </tr>
              </thead>
              <tbody>
                <?php foreach ($items_list as $key => $value) { ?>
                  <tr>
                    <td><?= $value['id']; ?></td>
                    <td><?= $value['registration_no']; ?></td>
                    <td><?= json_decode($value['name'])->english; ?></td>
                    <td><?= @json_decode($value['make_name'])->english; ?></td>
                    <td><?= @json_decode($value['model_name'])->english; ?></td>
                  </tr>
                <?php } ?>
                 
              </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <a id="ok_btn" class="btn btn-success btnid">Ok</a>
      </div>
    </div>
  </div>
</div>
  
<!-- <script src="<?php echo base_url()?>assets_admin/js/bootstrap4.min.js"></script> -->
<script type="text/javascript">
 $('#profile-tab').on('click', function(event) {
   //event.preventDefault();
   $(this).removeClass('active');
   $('#profile-tab').addClass('active');
   $('#profile').css('display','block');
   // $('#profile').addClass('active show');
   $('#home-tab').removeClass('active');
   $('#home').removeClass('active show');
   $('#home').css('display','none');
 });
 $('#home-tab').on('click', function(event) {
   //event.preventDefault();
   $(this).removeClass('active');
   $('#home-tab').addClass('active');
   $('#home').addClass('active show');
   $('#home').css('display','block');
   $('#profile-tab').removeClass('active');
   // $('#profile').removeClass('active show');
   $('#profile').css('display','none');
 });
</script>
<script>

  var auction_id = '<?= base64_decode(urldecode($this->uri->segment(3))); ?>';
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';
  $(document).on("click", "#allitemstableid tr", function() {
    var item_id = $(this).find("td:nth-child(1)").text();
    var url = '<?= base_url(); ?>';
    var auction_id = '<?= base64_decode(urldecode($this->uri->segment(3))); ?>';

    if (item_id !='') {
      $.ajax({
        url: url + 'auction/item_detail_ajax/' + item_id,
        type: 'POST',
        data: {'item_id':item_id, 'auction_id':auction_id, [token_name]:token_value},
        success : function(data) {
          var objData = jQuery.parseJSON(data);
          if (objData.msg == 'success') { 
            // console.log(objData.tr);
            console.log(objData.tr);
            console.log(objData);
            $('#item_id').val(objData.data['item_row'][0]['id']);
            $('#item_name').val(jQuery.parseJSON(objData.data['item_row'][0]['name']).english);
            if (objData.data['item_row'][0]['make_name'] && objData.data['item_row'][0]['model_name']) {
              $('#make').val(jQuery.parseJSON(objData.data['item_row'][0]['make_name']).english);
              $('#model').val(jQuery.parseJSON(objData.data['item_row'][0]['model_name']).english);
            }
            $('#registration_no').val(objData.data['item_row'][0]['registration_no']);
            $('#code').val(objData.data['item_row'][0]['id']);
            $('#name').val(objData.data['item_row'][0]['fname']+' '+objData.data['item_row'][0]['lname']);
            $('#lot_no').val(objData.data['lot_no']['order_lot_no']);
            $('#seller_id').val(objData.data['item_row'][0]['seller_id']);
            $('#seller_percentage_comission').val(objData.data['seller_charges']['commission']);
            $('#seller_price_comission').val(objData.data['seller_per_charges']['commission']);
            // $('#seller_tblrows').empty();
            // $('#seller_tblrows').append(objData.tr);

              // window.location = url + 'auction';
          }else{
            $('#make').val('');
            $('#model').val('');
            $('#registration_no').val('');
            $('#code').val('');
            $('#name').val('');
            $('#lot_no').val('');
            $('.msg-alert').css('display', 'block');
            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
          }  
        }
      });
    }else{
      $('#make').val('');
      $('#model').val('');
      $('#registration_no').val('');
      $('#code').val('');
      $('#name').val('');
      $('#lot_no').val('');
    }
  });

  $('#item_select').on('click', function(event){
    event.preventDefault();
  });

  $('#select_btn').on('click', function(event){
    event.preventDefault();
    var url = '<?php echo base_url();?>';
    console.log('jhdv cxj');
    $.ajax({   
      type: "post", //create an ajax request to display.php
      url: url + "auction/load_buyer_popup",    
      data:{[token_name]:token_value},
      success: function(data) {
          objdata = $.parseJSON(data);
          if(objdata.success == true)
          {
              $("#id").val('');
              $("#id").attr('disabled','disabled');
              $("#user_id").val('');
              $("#username").val('');
              $("#amount").val('');
     
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
  });

  $('#buy_btn').on('click', function(event){
    event.preventDefault();
    var url = '<?php echo base_url();?>';
    var item_id = $('#item_id').val();
    var seller_id = $('#seller_id').val();
    var buyer_id = $('#buyer_id').val();
    var amount = $('#D_S_amount').val();
    var buyer_balance = $('#buyer_Total_balance').val();
    console.log(seller_id);
    console.log(buyer_id);
    if (seller_id == '' || buyer_id == '' || amount == '') {
      $('.msg-alert').css('display', 'block');
      $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + 'Make sure you have select item, buyer and amount of item. ' + '</div></div>');
            window.scrollTo({top: 0, behavior: "smooth"});
    }else{
      $.ajax({   
        type: "post", //create an ajax request to direct_sale.php
        url: url + "auction/deposit_direct_sale",    
        data:{item_id:item_id, auction_id:auction_id, seller_id:seller_id, buyer_id:buyer_id, price:amount, [token_name]:token_value},
        success: function(data) {
          objdata = $.parseJSON(data);
          if(objdata.msg == 'success')
          {
            window.location.reload();  
          }
          else
          {
            // $('.make_case').hide();
            $('.msg-alert').css('display', 'block');
            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
            window.scrollTo({top: 0, behavior: "smooth"});
              
          }
        }
      });
    }
    
  });

  $("#ok_btn").on('click', function(){
    $("#items").modal("hide");
  });

  $(document).ready(function() {
    $('#allitemstableid').DataTable();
  });


  $('#print_btn').on('click', function(event){ 
    window.print();
    window.focus();
    // var divToPrint=document.getElementById("container2");
    // newWin= window.open("");
    // newWin.document.write(divToPrint.outerHTML);
    // newWin.print();
    // newWin.close();
  });


  $('#D_S_amount').on('keyup', function(){ 
    var seller_percentage = $('#seller_percentage_comission').val();
    var seller_price = $('#seller_price_comission').val();
    var buyer_percentage = $('#buyer_percentage_comission').val();
    var buyer_price = $('#buyer_price_comission').val();
    var total_price = $(this).val();
    var seller_commission = 0;
    var buyer_commission = 0;
    if (seller_percentage != '') {
      seller_commission = parseInt((seller_percentage/100)*total_price);
      // seller_commission = seller_commission.toFixed(0);
    }
    if (seller_price != '') {
      seller_commission = parseInt(seller_commission) + parseInt(seller_price);
    }
    total_price_after_comission = total_price - seller_commission;
    var tr = '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> sale-'+ $('#registration_no').val() +'</td>\
              <td>'+ total_price +'</td>\
              <td>'+'</td>\
              <td> Cash </td>\
            </tr>';
        tr += '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Comission </td>\
              <td></td>\
              <td>'+seller_commission+'</td>\
              <td> Cash </td>\
            </tr>';

    $('#seller_T_balance').val(total_price_after_comission);
    $('#seller_tblrows').empty();
    $('#seller_tblrows').append(tr);

    if (buyer_percentage != '') {
      buyer_commission = parseInt((buyer_percentage/100)*total_price);
      // buyer_commission = buyer_commission.toFixed(0);
    }
    if (buyer_price != '') {
      buyer_commission = parseInt(buyer_commission) + parseInt(buyer_price);
    }
    total_buyer_price_after_comission = parseInt(total_price) + parseInt(buyer_commission);
    var tr = '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Payment </td>\
              <td>'+ total_buyer_price_after_comission +'</td>\
              <td>'+'</td>\
              <td> Cash </td>\
            </tr>';
        tr += '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Sale-'+ $('#registration_no').val() +' </td>\
              <td></td>\
              <td>'+total_buyer_price_after_comission+'</td>\
              <td> Cash </td>\
            </tr>';

    $('#buyer_Total_balance').val('0.00');
    $('#buyer_tblrows').empty();
    $('#buyer_tblrows').append(tr);
  });

  $('#seller_price_comission').on('keyup', function(){ 
    var seller_percentage = $('#seller_percentage_comission').val();
    var seller_price = $('#seller_price_comission').val();
    var buyer_percentage = $('#buyer_percentage_comission').val();
    var buyer_price = $('#buyer_price_comission').val();
    var total_price = $('#D_S_amount').val();
    var seller_commission = 0;
    var buyer_commission = 0;
    if (seller_percentage != '') {
      seller_commission = parseInt((seller_percentage/100)*total_price);
      // seller_commission = seller_commission.toFixed(0);
    }
    if (seller_price != '') {
      seller_commission = parseInt(seller_commission) + parseInt(seller_price);
    }
    total_price_after_comission = total_price - seller_commission;
    var tr = '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> sale-'+ $('#registration_no').val() +'</td>\
              <td>'+ total_price +'</td>\
              <td>'+'</td>\
              <td> Cash </td>\
            </tr>';
        tr += '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Comission </td>\
              <td></td>\
              <td>'+seller_commission+'</td>\
              <td> Cash </td>\
            </tr>';

    $('#seller_T_balance').val(total_price_after_comission);
    $('#seller_tblrows').empty();
    $('#seller_tblrows').append(tr);

    if (buyer_percentage != '') {
      buyer_commission = parseInt((buyer_percentage/100)*total_price);
      // buyer_commission = buyer_commission.toFixed(0);
    }
    if (buyer_price != '') {
      buyer_commission = parseInt(buyer_commission) + parseInt(buyer_price);
    }
    total_buyer_price_after_comission = parseInt(total_price) + parseInt(buyer_commission);
    var tr = '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Payment </td>\
              <td>'+ total_buyer_price_after_comission +'</td>\
              <td>'+'</td>\
              <td> Cash </td>\
            </tr>';
        tr += '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Sale-'+ $('#registration_no').val() +' </td>\
              <td></td>\
              <td>'+total_buyer_price_after_comission+'</td>\
              <td> Cash </td>\
            </tr>';

    $('#buyer_Total_balance').val('0.00');
    $('#buyer_tblrows').empty();
    $('#buyer_tblrows').append(tr);
  });

  $('#seller_percentage_comission').on('keyup', function(){ 
    var seller_percentage = $('#seller_percentage_comission').val();
    var seller_price = $('#seller_price_comission').val();
    var buyer_percentage = $('#buyer_percentage_comission').val();
    var buyer_price = $('#buyer_price_comission').val();
    var total_price = $('#D_S_amount').val();
    var seller_commission = 0;
    var buyer_commission = 0;
    if (seller_percentage != '') {
      seller_commission = parseInt((seller_percentage/100)*total_price);
      // seller_commission = seller_commission.toFixed(0);
    }
    if (seller_price != '') {
      seller_commission = parseInt(seller_commission) + parseInt(seller_price);
    }
    total_price_after_comission = total_price - seller_commission;
    var tr = '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> sale-'+ $('#registration_no').val() +'</td>\
              <td>'+ total_price +'</td>\
              <td>'+'</td>\
              <td> Cash </td>\
            </tr>';
        tr += '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Comission </td>\
              <td></td>\
              <td>'+seller_commission+'</td>\
              <td> Cash </td>\
            </tr>';

    $('#seller_T_balance').val(total_price_after_comission);
    $('#seller_tblrows').empty();
    $('#seller_tblrows').append(tr);

    if (buyer_percentage != '') {
      buyer_commission = parseInt((buyer_percentage/100)*total_price);
      // buyer_commission = buyer_commission.toFixed(0);
    }
    if (buyer_price != '') {
      buyer_commission = parseInt(buyer_commission) + parseInt(buyer_price);
    }
    total_buyer_price_after_comission = parseInt(total_price) + parseInt(buyer_commission);
    var tr = '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Payment </td>\
              <td>'+ total_buyer_price_after_comission +'</td>\
              <td>'+'</td>\
              <td> Cash </td>\
            </tr>';
        tr += '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Sale-'+ $('#registration_no').val() +' </td>\
              <td></td>\
              <td>'+total_buyer_price_after_comission+'</td>\
              <td> Cash </td>\
            </tr>';

    $('#buyer_Total_balance').val('0.00');
    $('#buyer_tblrows').empty();
    $('#buyer_tblrows').append(tr);
  });

  $('#buyer_price_comission').on('keyup', function(){ 
    var seller_percentage = $('#seller_percentage_comission').val();
    var seller_price = $('#seller_price_comission').val();
    var buyer_percentage = $('#buyer_percentage_comission').val();
    var buyer_price = $('#buyer_price_comission').val();
    var total_price = $('#D_S_amount').val();
    var seller_commission = 0;
    var buyer_commission = 0;
    if (seller_percentage != '') {
      seller_commission = parseInt((seller_percentage/100)*total_price);
      // seller_commission = seller_commission.toFixed(0);
    }
    if (seller_price != '') {
      seller_commission = parseInt(seller_commission) + parseInt(seller_price);
    }
    total_price_after_comission = total_price - seller_commission;
    var tr = '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> sale-'+ $('#registration_no').val() +'</td>\
              <td>'+ total_price +'</td>\
              <td>'+'</td>\
              <td> Cash </td>\
            </tr>';
        tr += '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Comission </td>\
              <td></td>\
              <td>'+seller_commission+'</td>\
              <td> Cash </td>\
            </tr>';

    $('#seller_T_balance').val(total_price_after_comission);
    $('#seller_tblrows').empty();
    $('#seller_tblrows').append(tr);

    if (buyer_percentage != '') {
      buyer_commission = parseInt((buyer_percentage/100)*total_price);
      // buyer_commission = buyer_commission.toFixed(0);
    }
    if (buyer_price != '') {
      buyer_commission = parseInt(buyer_commission) + parseInt(buyer_price);
    }
    total_buyer_price_after_comission = parseInt(total_price) + parseInt(buyer_commission);
    var tr = '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Payment </td>\
              <td>'+ total_buyer_price_after_comission +'</td>\
              <td>'+'</td>\
              <td> Cash </td>\
            </tr>';
        tr += '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Sale-'+ $('#registration_no').val() +' </td>\
              <td></td>\
              <td>'+total_buyer_price_after_comission+'</td>\
              <td> Cash </td>\
            </tr>';

    $('#buyer_Total_balance').val('0.00');
    $('#buyer_tblrows').empty();
    $('#buyer_tblrows').append(tr);
  });

  $('#buyer_percentage_comission').on('keyup', function(){ 
    var seller_percentage = $('#seller_percentage_comission').val();
    var seller_price = $('#seller_price_comission').val();
    var buyer_percentage = $('#buyer_percentage_comission').val();
    var buyer_price = $('#buyer_price_comission').val();
    var total_price = $('#D_S_amount').val();
    var seller_commission = 0;
    var buyer_commission = 0;
    if (seller_percentage != '') {
      seller_commission = parseInt((seller_percentage/100)*total_price);
      // seller_commission = seller_commission.toFixed(0);
    }
    if (seller_price != '') {
      seller_commission = parseInt(seller_commission) + parseInt(seller_price);
    }
    total_price_after_comission = total_price - seller_commission;
    var tr = '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> sale-'+ $('#registration_no').val() +'</td>\
              <td>'+ total_price +'</td>\
              <td>'+'</td>\
              <td> Cash </td>\
            </tr>';
        tr += '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Comission </td>\
              <td></td>\
              <td>'+seller_commission+'</td>\
              <td> Cash </td>\
            </tr>';

    $('#seller_T_balance').val(total_price_after_comission);
    $('#seller_tblrows').empty();
    $('#seller_tblrows').append(tr);

    if (buyer_percentage != '') {
      buyer_commission = parseInt((buyer_percentage/100)*total_price);
      // buyer_commission = buyer_commission.toFixed(0);
    }
    if (buyer_price != '') {
      buyer_commission = parseInt(buyer_commission) + parseInt(buyer_price);
    }
    total_buyer_price_after_comission = parseInt(total_price) + parseInt(buyer_commission);
    var tr = '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Payment </td>\
              <td>'+ total_buyer_price_after_comission +'</td>\
              <td>'+'</td>\
              <td> Cash </td>\
            </tr>';
        tr += '<tr>\
              <td>'+'<?= date('Y/m/d') ?>'+'</td>\
              <td> Sale-'+ $('#registration_no').val() +' </td>\
              <td></td>\
              <td>'+total_buyer_price_after_comission+'</td>\
              <td> Cash </td>\
            </tr>';

    $('#buyer_Total_balance').val('0.00');
    $('#buyer_tblrows').empty();
    $('#buyer_tblrows').append(tr);
  });
</script>