<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<!-- datepicker -->
<link rel="stylesheet" href="<?= ASSETS_USER;?>new/css/bootstrap-datepicker.css">
<script src="<?= ASSETS_USER;?>new/js/bootstrap-datepicker.js"></script>
 <div class="main-wrapper account-page">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url(); ?>"><?= $this->lang->line('home');?></a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('customer/deposit'); ?>"><?= $this->lang->line('my_account_new');?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $this->lang->line('payments')?></li>
            </ol>
            <h2 class="my-account"><?= $this->lang->line('my_account_new');?></h2>
            <div class="account-wrapper">
                <?= $this->load->view('template/new/template_user_leftbar') ?>
                <div class="right-col">
                    <div class="payment-heading">
                        <h3><?= $this->lang->line('payments_new');?></h3>
                         <li class="<?= (isset($security_active)) ? $security_active : '';?>">
                            <a href="<?= base_url('customer/item_deposit');?>">
                                <svg width="19" height="13" viewBox="0 0 16 13" fill="#979797" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.8735 0.0476074H1.125C0.503125 0.0476074 0 0.550727 0 1.17261V3.05542V4.55542V10.8773C0 11.4992 0.503125 12.0023 1.125 12.0023H14.875C15.4969 12.0023 16 11.4992 16 10.8773V4.55542V3.05542V1.17261C15.9985 0.550727 15.4937 0.0476074 14.8735 0.0476074ZM14.8735 10.8773H1.125V4.55542H14.8735V10.8773ZM1.125 1.17261V3.05542H14.8735V1.17261H1.125ZM3.70313 9.02417C4.08281 9.02417 4.39063 8.71637 4.39063 8.3367C4.39063 7.95697 4.08281 7.64917 3.70313 7.64917C3.32344 7.64917 3.01563 7.95697 3.01563 8.3367C3.01563 8.71637 3.32344 9.02417 3.70313 9.02417ZM6.9922 8.3367C6.9922 8.71637 6.6844 9.02417 6.30469 9.02417C5.925 9.02417 5.61719 8.71637 5.61719 8.3367C5.61719 7.95697 5.925 7.64917 6.30469 7.64917C6.6844 7.64917 6.9922 7.95697 6.9922 8.3367Z"/>
                                </svg>                                    
                                <?= $this->lang->line('item_security');?>
                            </a>
                        </li>
                    </div>
                    <div class="payment-stats">
                        <ul  class="d-flex align-items-center justify-content-between payment-boxes">
                            <li class="bg-primary text-white">
                                <?php 
                                if($language=='arabic') :

                                    echo  (number_format($balance, 0, ".", ",") ?? '0') .' '.$this->lang->line('aed');

                                    
                                else :
                                    echo  $this->lang->line('aed') .' '.number_format($balance, 0, ".", ",") ?? '0';

                                endif;
                                ?>
                                <p>
                                    <?= $this->lang->line('available_balance');?> 
                                <!-- <a href="#">+ <?= $this->lang->line('increase_balance');?></a> -->
                                </p>
                            </li>
                            <li class="bg-primary text-white">
                                <?php echo (!empty($bids)) ? $bids : '0'; ?>
                                <p><?= $this->lang->line('my_bids');?></p>
                            </li>
                            <li class="bg-primary text-white">
                                  <?php $limit = (isset($percentage_settings) && !empty($percentage_settings['value'])) ? $percentage_settings['value'] : '1'; ?>

                                <?php 
                                if($language=='arabic') :
                                    echo number_format($balance*$limit, 0, ".", ","). ' ' .$this->lang->line('aed');
                                else :

                                    echo  $this->lang->line('aed') .' '.number_format($balance*$limit, 0, ".", ",");

                                endif;
                                ?>
                                
                                <p >
                                     <?= $this->lang->line('bid_limit');?>
                                  <!-- <a href="<?= base_url('deposit');?>"><?= $this->lang->line('limit');?> +</a> -->
                              </p>
                            </li>
                        </ul>
                    </div>

                    <?php if($this->session->flashdata('error')){ ?>
                      <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong><?= $this->lang->line('error_'); ?></strong> <?= $this->session->flashdata('error'); ?>
                      </div>
                    <?php } ?>

                    <?php if($this->session->flashdata('success')){ ?>
                      <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong><?= $this->lang->line('success_'); ?></strong> <?= $this->session->flashdata('success'); ?>
                      </div>
                    <?php } ?>


                    <div class="auction-detail">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?= $this->lang->line('cheque_new');?></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?= $this->lang->line('credit_card');?></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"><?= $this->lang->line('bank_transfer_new');?></a>
                            </li>
                            <!-- <li class="nav-item">
                              <a class="nav-link" id=""  href="<?//= base_url('user-payment') ?>" role="tab" aria-controls="contact" aria-selected="false"><?//= $this->lang->line('my_payable_new');?></a>
                            </li> -->
                        </ul>
                        <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="cheque-tab">
                                <p>
                                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/error-popup.png">
                                   <?= $this->lang->line('visit_location');?>.
                                </p>
                                <ul>
                                    <li><?= $this->lang->line('location');?>:</li>
                                    <li><?= $this->lang->line('dubai');?></li>
                                </ul>
                                <?php 
                                    $data= $this->db->get('contact_us')->row_array();
                                ?>
                                <ul>
                                    <li><?= $this->lang->line('timings'); ?>:</li>
                                    <li><?= $data['working_hr']; ?>: <?= $data['business_hr']; ?></li>
                                </ul>
                                <div class="map">
                                    <div dir="ltr" id="map" style="width: 100%; height: 100%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="credit-card">
                                <h5 class="tab-title"><?= $this->lang->line('card_pay');?></h5>
                                <p><?= $this->lang->line('p1');
                                 if($language=='arabic') {
                                    ?>

                                   <?= (isset($setting) && !empty($setting['value'])) ? number_format($setting['value'], 0, ".", ",") : '';  ?>
                                   <?= $this->lang->line('aed');?> 

                                  <?php }else{?>
                                       <?= $this->lang->line('aed');?> 
                                <?= (isset($setting) && !empty($setting['value'])) ? number_format($setting['value'], 0, ".", ",") : '';  ?>
                                  <?php }
                                    ?> 
                                <?= $this->lang->line('comma');?> <?= $this->lang->line('p1_2');?>.</p>
                                <p style="color: red">
                                    <?= $this->lang->line('p2');?>
                                </p>
                                <p>
                                    <?= $this->lang->line('p3');?>
                                </p>
                                <p>
                                    <?= $this->lang->line('p4');?>
                                    
                                </p>
                                <p>
                                    <?= $this->lang->line('p5');?>
                                </p>
                                <form action="post">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                                    <div class="form-group">
                                        <label><?= $this->lang->line('deposit_amount');?></label>
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

                                            <input type="hidden" name="rurl" id="rurl" value="<?= $_SERVER['REQUEST_URI']; ?>">
                                            <input type="text" placeholder="<?= $this->lang->line('enter_deposit_amount');?>" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="form-control" type="number" min="<?= (isset($setting) && !empty($setting['value'])) ? $setting['value']: 0;  ?>" value="<?= (isset($setting) && !empty($setting['value'])) ? ($setting['value']): 0;  ?>" id="amount" name="amount" autocomplete="off" maxlength="15"  max="2000000" />
                                         <span class="valid-error text-danger amount-error" style="display: none;"></span>

                                         <span class=" text-danger ">
                                            <?= $this->lang->line('bid_limit');?>
                                            <?php 
                                            if($language=='arabic') :
                                                echo '<em id="output">'.number_format((((isset($setting) && !empty($setting['value'])) ? $setting['value']: 0)*$limit)).' 
                                          </em>'.$this->lang->line('aed');

                                                else :

                                                echo $this->lang->line('aed').'<em id="output">'.number_format((((isset($setting) && !empty($setting['value'])) ? $setting['value']: 0)*$limit)).'
                                          </em>';

                                            endif;

                                             ?> 
                                      </span>
                                    </div>
                                    <div class="button-row">
                                        <button type="text" id="priceedbtn" class="btn btn-primary"><?=$this->lang->line('proceed');?></button>
                                        <!-- <a class="btn btn-default" href="<?= base_url('customer'); ?>"><?= $this->lang->line('skip');?></a> -->
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="account-detail">
                                <h5 class="tab-title"><?=$this->lang->line('pay_by_bank_transfer');?></h5>
                                <?php 
                                    $query= $this->db->get('bank_info')->row_array();
                                ?>
                                <ul>
                                    <li><?=$this->lang->line('ac_name');?>:</li>
                                    <li><?php  if(!empty($query) && !empty($query)) echo $query['ac_name'];?></li>
                                </ul>
                                <ul>
                                    <li><?=$this->lang->line('bank_name');?>:</li>
                                    <li><?php  if(!empty($query) && !empty($query)) echo $query['bank_name'];?></li>
                                </ul>
                                <ul>
                                    <li><?=$this->lang->line('iban');?>:</li>
                                    <li><?php  if(!empty($query) && !empty($query)) echo $query['iban'];?></li>
                                </ul>
                                <ul>
                                    <li><?=$this->lang->line('ac_number');?>:</li>
                                    <li><?php  if(!empty($query) && !empty($query)) echo $query['ac_number'];?></li>
                                </ul>
                                <ul>
                                    <li><?= $this->lang->line('swift_code');?>:</li>
                                    <li><?php  if(!empty($query) && !empty($query)) echo $query['swift_code'];?></li>
                                </ul>
                                <ul>
                                    <li><?= $this->lang->line('routing_number');?>:</li>
                                    <li><?php  if(!empty($query) && !empty($query)) echo $query['routing_number'];?></li>
                                </ul>
                                
                                <p class="font-weight-400"><?= $this->lang->line('p6');?> <?php  if(!empty($data) && !empty($data)) echo $data['email'];?> </p>
                                <p>
                                    <?= $this->lang->line('p7');?>
                                </p>
                                <h5 class="tab-title mt-4"><?= $this->lang->line('transfer_slip');?></h5>
                                <form id="demo-form2">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                                    <div class="form-group">
                                        <div style="display: flex; align-items: center;">
                                            <label for="slip" style="border: none; margin: 5px 0px; padding: 3px 10px; color: #fff; background-color: #5c02b5; border-radius: 3px;"><?= $this->lang->line('chose_file'); ?></label>&nbsp <span id="noFileChosen" style=""><?= $this->lang->line('no_file_choosen'); ?></span>
                                            <input type="file" name="slip" id="slip" style="visibility:hidden;" accept="image/x-png,image/jpeg">
                                        </div>
                                        <span class="valid-error text-danger slip-error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label><?= $this->lang->line('d_date');?></label>
                                        <div class="wrap">
                                            <input type="text" class="form-control" id="deposit_date" placeholder="<?= $this->lang->line('enter_deposit_date');?>" name="deposit_date">
                                            <span class="valid-error text-danger deposit_date-error"></span>
                                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/calendar-icon-default.svg">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><?= $this->lang->line('d_amount');?></label>
                                        <input type="text" class="form-control" id="deposit_amount" placeholder="<?= $this->lang->line('enter_deposit_amount');?>"   name="deposit_amount">
                                       <!--  oninput="this.value=this.value.replace(/[^0-9]/g,'');" -->
                                        <span class="glyphicon glyphicon-calendar"></span>
                                         <span class="valid-error text-danger deposit_amount-error"></span>
                                    </div>
                                    <div class="button-row">
                                        <button type="button" class="btn btn-primary" id="bankdepositbtn"><?=$this->lang->line('submit_your_deposit');?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

             <?= $this->load->view('template/new/faq_footer'); ?>
        </div>
    </div>

<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script>

    $("#slip").change(function() {
      filename = this.files[0].name
      $('#noFileChosen').html(filename);
      console.log(filename);
    });
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';
    $(document).ready(function(){

        $('#deposit_date').datepicker({
            format: 'yyyy-mm-d',
            // startDate: '-30d',
            ignoreReadonly: true,
            autoclose: true
        });
        // $('#deposit_date').datepicker();
        $('#cheque').show();
        $('#cradit_card').hide();
        $('#bank').hide();

        $('#crad').on('click', function(event) {  
            $('#cradit_card').show();
            $('#cheque').hide();
            $('#bank').hide();
        });

        $('#cheq').on('click', function(event) {    
            $('#cheque').show();
            $('#cradit_card').hide();
            $('#bank').hide();
        });

        $('#banktrns').on('click', function(event) {    
            $('#bank').show();
            $('#cheque').hide();
            $('#cradit_card').hide();
        });

        $('#priceedbtn').on('click', function(event) {  
            event.preventDefault();
            $('.amount-error').hide();
            var base_url = '<?php base_url() ?>';
            var amount = $('#amount').val();
            var rurl = $('#rurl').val();
          //   $("#ProfileUpdateModel").modal('show');
            var redirect = '<?= (isset($_GET["r"])) ? $_GET["r"] : ""; ?>';
            if (redirect) {
                var url = 'customer/cradit_card?r='+redirect;
            } else {
                var url = 'customer/cradit_card';
            }


            var min_amount = '<?= (isset($setting) && !empty($setting['value'])) ?
                                    $setting['value']: '';  ?>';
            //console.log(min_amount);
            if (amount < min_amount) {
                $('.amount-error').html('<small class="font-weight-bold"> <?= $this->lang->line("min_deposit_amount_is"); ?> '+min_amount+' </samll>').show();
                $("input[name=amount]").focus();
                $('.amount-error').show();
            } else {
                // alert('not allowed. get permission from Zain sab');
                $.ajax({
                    url: base_url + url,
                    type: 'POST',
                    data: {amount:amount, rurl:rurl, [token_name]:token_value},
                    beforeSend: function(){
                        $('.loading_oz_premium').html('<img src="'+base_url+'assets_admin/images/load.gif" align="center" />');
                        $('.loading_oz_premium').parent().attr("disabled",true);
                        $('.loading_oz').parent().attr("disabled",true);
                    },
                }).then(function(data) {
                    console.log(data);
                    var objData = jQuery.parseJSON(data);
                    PNotify.removeAll();
                    if (objData.msg == 'success') 
                    {
                        if(objData.showModel==true){
                          $("#ProfileUpdateModel").modal('show');
                        }else{
                         window.location = objData.redirect;
                        }
                    } else {
                        new PNotify({
                            text: objData.response,
                            type: 'error',
                            title: "<?= $this->lang->line('error'); ?>",
                            styling: 'bootstrap3'
                        });
                    }
                });
            }
        });

        $('#bankdepositbtn').on('click', function(event) {  
            event.preventDefault();
            console.log('click');

            var validation = false;
            var language = '<?= $language; ?>';
            selectedInputs = ['slip', 'deposit_date', 'deposit_amount'];
            validation = validateFields(selectedInputs, language);

            var base_url = '<?php base_url() ?>';
            var formData = new FormData($("#demo-form2")[0]);
            if (validation == true) {
            console.log(formData);
                $.ajax({
                 url: base_url + 'customer/add_bank_slip',
                 type: 'POST',
                 data: formData,
                 cache: false,
                 contentType: false,
                 processData: false,
                 beforeSend: function(){
                 // $('#bankdepositbtn').html('<img src="'+base_url+'assets_admin/images/load.gif" align="center" />');
                 $('#bankdepositbtn').parent().attr("disabled",true);
                 },
                }).then(function(data) 
                {
                    var objData = jQuery.parseJSON(data);
                    if (objData.msg == 'success') 
                    {
                        new PNotify({
                            text: objData.response,
                            type: 'success',
                            addclass: 'custom-success',
                            title: "<?= $this->lang->line('success_'); ?>",
                            styling: 'bootstrap3'
                        });
                        $('#slip').val('');
                        $('#deposit_date').val('');
                        $('#deposit_amount').val('');
                        $('#bankdepositbtn').attr('disabled', false);
                        $('#noFileChosen').html("<?= $this->lang->line('no_file_choosen'); ?>");
                    }
                    else{
                        new PNotify({
                            text: objData.response,
                            type: 'error',
                            title: "<?= $this->lang->line('error'); ?>",
                            styling: 'bootstrap3'
                        });
                        $('#bankdepositbtn').attr('disabled', false);
                    }
                });
            }
        });

        // update profile

        $('#profileupdatebtn').on('click', function(event){
    event.preventDefault();
    var mode = $(this).text();
    
      var validation = false;
      var language = '<?= $language; ?>';
      selectedInputs = ['fname','lname','address', 'city', 'state', 'id_number'];
      validation = validateFields(selectedInputs, language);

      if (validation == true) {
        var form = $('#profileForm').serializeArray();
        $.ajax({
          type: 'post',
          url: '<?= base_url('customer/update_profile'); ?>',
          data: form,
          success: function(msg){
            $('#profileupdatebtn').text('<?= $this->lang->line('edit');?>');
            var response = JSON.parse(msg);
            PNotify.removeAll();
            
            if(response.msg == 'Number already exist.'){
              new PNotify({
                  text: response.msg,
                  type: 'error',
                  title: 'Error',
                  styling: 'bootstrap3'
                });
            }

            if(response.msg == 'Email already exist.'){
              new PNotify({
                  text: response.msg,
                  type: 'error',
                  title: 'Error',
                  styling: 'bootstrap3'
                });
            }
            if(response.error == false){
              new PNotify({
                  text: "<?= $this->lang->line('profile_updated_successfully');?>",
                  type: 'success',
                  title: '<?= $this->lang->line('success_');?>',
                  styling: 'bootstrap3'
                });
             // $("#ProfileUpdateModel").hide(100);
               //$('#ProfileUpdateModel').modal('hide');
               setTimeout(function() { $("#ProfileUpdateModel").modal('hide'); }, 2000);
               $( "#priceedbtn" ).trigger( "click" );
            }
          } 
        });
      }
  });
       

    });

    $(".alert-dismissible").fadeOut(10000);

    function initializePA() {
        var lat = "25.238820";
        var lng = "55.371237";
        var latlng = new google.maps.LatLng(lat,lng);
        var map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(lat,lng),
            zoom: 13
        });
        
        var marker = new google.maps.Marker({
            map: map,
            position: latlng,
            draggable: false,
            anchorPoint: new google.maps.Point(0, -29)
        });
    }



$('#amount').on("input", function() {
  var dInput = this.value;
  var v4 = $('#amount').val();
  console.log(dInput);
  $('#output').text(numberWithCommas(dInput* <?php echo $limit ?>));
});



/*$('#amount').keyup(function(event) {

  // skip for arrow keys
  if(event.which >= 37 && event.which <= 40) return;

  // format number
  $(this).val(function(index, value) {
    return value
    .replace(/\D/g, "")
    .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    ;
  });
});
*/
</script>
<?php 
if($language == 'arabic'){
    $ln = '&region=EG&language=ar';
}else{
    $ln = '';
}
?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item('google_map_key'); ?>&callback=initializePA<?= $ln; ?>"></script>


  <!-- You are placing AED 10,000 Increment. -->
<div class="modal fade login-modal style-2" id="ProfileUpdateModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <div class="increment-modal">
                  <div class="img">
                      <img src="<?= NEW_ASSETS_USER; ?>/new/images/hammer-icon.png">
                  </div>


                 <form class="account-form" id="profileForm">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                    <div class="row col-gap-24">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('f_name');?> <span>*</span></label>
                                <input type="text" id="fname" class="form-control" placeholder="<?= $this->lang->line('enter_first_name'); ?>" name="fname" value="<?php echo $list['fname'] ?? ''?>">
                                <span class="valid-error text-danger fname-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('l_name');?> <span>*</span></label>
                                <input type="text" class="form-control" placeholder="<?= $this->lang->line('enter_last_name'); ?>" name="lname" id="lname" value="<?php echo $list['lname'] ?? '';?>">
                                <span class="valid-error text-danger lname-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('email');?> <span>*</span></label>
                                <input type="text"  class="form-control" readonly name="email" value="<?php echo $list['email'] ?? '';?>">
                                <span class="valid-error text-danger email-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('mobile');?> <span>*</span></label>
                                <input type="text" dir="ltr" class="form-control" placeholder="<?= $this->lang->line('enter_mobile_num'); ?>" readonly name="mobile" value="<?php echo $list['mobile'] ?? '';?>">
                                <span class="valid-error text-danger mobile-error"></span>
                                <span class="valid-error text-danger" id="error-msgs" ></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('address');?> <span>*</span></label>
                                <input type="text" id="address" class="form-control" name="address" placeholder="<?= $this->lang->line('address'); ?>" value="<?php echo $list['address'] ?? '';?>">
                                <span class="valid-error text-danger address-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('city');?> <span>*</span></label>
                                <input type="text" id="city" class="form-control" oninput="this.value=this.value.replace(/[^aA-zZ]/g,'');" name="city" placeholder="<?= $this->lang->line('enter_city'); ?>" value="<?php echo $list['city'] ?? '';?>">
                                <span class="valid-error text-danger city-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('country');?> <span>*</span></label>
                             <?php
                                 $country_name = $this->db->get_where('country',['name'=>$list['country']])->row_array();
                                 $countries = $this->db->get('country')->result_array();
                                 ?>
                             <select name="country" class="validThis location-selector form-control" id="countryId" required="" >
                                <?php 
                                if (!empty($country_name)) {?>
                                          <option value="<?= $countries[0]['name']; ?>" data-countrycode="<?=  $country_name['id']; ?>"><?= $country_name['name']; ?></option>
                                <?php }else{?>
                                          <option value="United Arab Emirates" data-countrycode="199">United Arab Emirates</option>
                               <?php }?>
                                      <?php if($list):
                                          $countries = $this->db->get('country')->result_array();
                                          foreach ($countries as $key => $country):
                                          if( ($country['id'] != 199)):
                                        ?>
                                          <option value="<?= $country['name']; ?>" data-countrycode="<?= $country['id']; ?>"><?= $country['name']; ?></option>
                                      <?php
                                      endif;
                                      endforeach;
                                      endif;?>
                              </select>
                                <span class="valid-error text-danger country-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('states');?> <span>*</span></label>
                                <input type="text" id="state" class="form-control" name="state" value="<?php echo $list['state'] ?? '';?>">
                                <span class="valid-error text-danger state-error"></span>
                            </div>
                        </div>
                      
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('id_nmbr');?> <span>*</span></label>
                                <input type="text" id="id_number" class="form-control" name="id_number" value="<?php echo $list['id_number'] ?? '';?>">
                                <span class="valid-error text-danger id_number-error"></span>
                            </div>
                        </div>
                        
                    </div>
                  
                  
                  <div class="row">
                      <div class="col-6 pr-0">
                          <button data-dismiss="modal" class="btn btn-default"><?= $this->lang->line('cancel');?></button>
                      </div>
                      <div class="col-6 pr-0">
                          <button type="button" class="btn btn-primary" id="profileupdatebtn">Update</button>
                      </div>
                  </div>
                   </form>
              </div>
          </div>
      </div>
    </div>
  </div>