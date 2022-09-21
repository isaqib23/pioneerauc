 <!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<!-- datepicker -->
<link rel="stylesheet" href="<?= ASSETS_USER;?>css/bootstrap-datepicker.css">
<script src="<?= ASSETS_USER;?>js/bootstrap-datepicker.js"></script>
 <div class="main-wrapper account-page">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url(); ?>"><?= $this->lang->line('home');?></a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('customer/deposit'); ?>"><?= $this->lang->line('my_account_new');?></a></li>
                <li class="breadcrumb-item active" aria-current="page"> <?= $this->lang->line('payments_new');?></li>
            </ol>
            <h2><?= $this->lang->line('my_account_new');?></h2>
            <div class="account-wrapper">
                <?= $this->load->view('template/new/template_user_leftbar') ?>
                <div class="right-col">
                    <h3><?= $this->lang->line('payments_new');?></h3>
                    <div class="auction-detail">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?= $this->lang->line('cheque_new');?></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?= $this->lang->line('credit_card');?></a>
                            </li>
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
                                        <li><?= $this->lang->line('timings');?>:</li>
                                        <li><?= $data['working_hr']; ?>: <?= $data['business_hr']; ?></li>
                                    </ul>
                                    <div class="map">

                                        <div dir="ltr" id="map" style="width: 100%; height: 100%;"></div>
                                        <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13594.266313237882!2d74.3000874!3d31.590931400000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1612689739457!5m2!1sen!2s" width="100%" height="242px" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe> -->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade  show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="credit-card">
                                    <h5 class="tab-title"><?= $this->lang->line('card_pay');?></h5>
                                    <p><?= $this->lang->line('p1');?> <?= (isset($setting) && !empty($setting['value'])) ? number_format($setting['value'] , 0, ".", ",") : '';  ?> AED. <?= $this->lang->line('p1_2');?>.</p>
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
                                            <label><?= $this->lang->line('select_auction');?></label>
                                            <select class="selectpicker" id="auction" name="auction_id">
                                                <option value=""><?= $this->lang->line('select_auction');?></option>
                                                <?php $auction_id = isset($_GET['auction_id']) ? $_GET['auction_id'] : ''; foreach ($auctions as $key => $value) {
                                                    $title = json_decode($value['title']);
                                                    ?>
                                                    <option <?= (isset($auction_id) && $auction_id == $value['id']) ? 'selected=selected' : ''; ?> value="<?= $value['id']; ?>"><?= $title->$language; ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><?= $this->lang->line('select_item');?></label>
                                            <select class="selectpicker" id="auction_item" name="auction_item_id">
                                                <option value=""> <?= $this->lang->line('select_auction_first');?> </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><?= $this->lang->line('d_amount');?></label>
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

                                            <input type="hidden" name="rurl" id="rurl" value="<?= $_SERVER['REQUEST_URI']; ?>">
                                            <input type="text" placeholder="<?= $this->lang->line('amount'); ?>" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="form-control" type="number" readonly="readonly" value="" id="amount" name="amount">
                                            <span class="valid-error text-danger amount-error" style="display: none;"></span>
                                        </div>
                                        <div class="button-row">
                                            <button type="text" id="priceedbtn" disabled="disabled" class="btn btn-primary"><?= $this->lang->line('proceed');?></button>
                                            <!-- <a class="btn btn-default" href="<?= base_url('customer'); ?>"><?= $this->lang->line('skip');?></a> -->
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
    $(document).ready(function(){
        <?php if (isset($_GET['item_id'])) { ?>
            $('#auction').change();
            $('#auction_item').change();
        <?php } ?>

        // $('#deposit_date').datepicker({
        //  format: 'yyyy-mm-d',
  //        // startDate: '-30d',
     //         ignoreReadonly: true,
     //         autoclose: true
     //         // allowInputToggle: true,
     //     // minDate: moment(),
     //    });
        // $('#deposit_date').datepicker();
        $('#cheque').hide();
        $('#cradit_card').show();
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
            var base_url = '<?php base_url() ?>';
            var amount = $('#amount').val();
            var auction_id = $('#auction').val();
            var auction_item_id = $('#auction_item').val();
            var item_id = $('#item_id').val();
            <?php if (isset($_GET['rurl'])) { ?>
                var rurl = "<?= $_GET['rurl']; ?>";
            <?php } else { ?>
                var rurl = "";
            <?php } ?>
            var min_amount = "<?= (isset($setting) && !empty($setting['deposite'])) ? $setting['deposite']: '';  ?>";
            $.ajax({
             url: base_url + 'customer/item_cradit_card',
             type: 'POST',
             data: {amount:amount, auction_id:auction_id, auction_item_id:auction_item_id, item_id:item_id, rurl:rurl,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
             beforeSend: function(){
             $('.loading_oz_premium').html('<img src="'+base_url+'assets_admin/images/load.gif" align="center" />');
             $('.loading_oz_premium').parent().attr("disabled",true);
             $('.loading_oz').parent().attr("disabled",true);
             },
             }).then(function(data) 
             {
                var objData = jQuery.parseJSON(data);
                if (objData.msg == 'success') 
                {
                    window.location = objData.redirect;
                }
                else{
                    PNotify.removeAll();
                    new PNotify({
                        text: objData.response,
                        type: 'error',
                        addclass: 'custom-error',
                        title: "<?= $this->lang->line('error'); ?>",
                        styling: 'bootstrap3'
                    });
                }
            });
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
                 $('.loading_oz_premium').html('<img src="'+base_url+'assets_admin/images/load.gif" align="center" />');
                 $('.loading_oz_premium').parent().attr("disabled",true);
                 $('.loading_oz').parent().attr("disabled",true);
                 },
                }).then(function(data) 
                {
                    var objData = jQuery.parseJSON(data);
                    if (objData.msg == 'success') 
                    {
                        $('#response').html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button><strong> <?= $this->lang->line("success_"); ?> </strong> '+objData.response+'</div></div>');
                        window.scrollTo({top: 190, behavior: "smooth"});
                        $('#slip').val('');
                        $('#deposit_date').val('');
                        $('#deposit_amount').val('');
                    }
                    else{
                        $('#response').html('<div class="alert" ><div class="alert alert-domain alert-warning alert-dismissible in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button><strong> <?= $this->lang->line("error_"); ?> </strong> '+objData.response+'</div></div>');
                        window.scrollTo({top: 190, behavior: "smooth"});
                    }
                });
            }
        });

    });

$('#auction').on('change', function() {
    var base_url = '<?= base_url() ?>';
    var id = $('#auction').val();
    var item_id = '';
    <?php if(isset($_GET['item_id'])) { ?>
        item_id = '<?= $_GET['item_id']; ?>';
    <?php } ?>

    var user_id = '<?= $this->session->userdata('logged_in')->id; ?>';
    $.ajax({
         url: base_url + 'customer/get_ai_list',
         type: 'POST',
         data: {id: id, user_id: user_id, item_id: item_id,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
         beforeSend: function(){
            $('.loading_oz_premium').html('<img src="'+base_url+'assets_admin/images/load.gif" align="center" />');
         },
     }).then(function(qadeer) 
     {
        var objData = jQuery.parseJSON(qadeer);
        $('#amount').val("");
        // var objData = $('#auction_item').val();
        console.log(objData);
            // $('#auction_item').append('<option value="">Choose</option>');
        if (objData.status == true) {
            // alert(objData.status);
            $('#auction_item').html(objData.data);
            $('#auction_item').selectpicker('refresh');
        }else{
            $('#auction_item').html('<option value=""> <?= $this->lang->line("select_auction_first"); ?> </option>');
            $('#auction_item').selectpicker('refresh');

        }
    });
});

$('#auction_item').on('change', function() {
    var base_url = '<?= base_url() ?>';
    var id = $('#auction_item').val();
    var item_id = '';
    <?php if(isset($_GET['item_id'])) { ?>
        id = '<?= $_GET['item_id']; ?>';
    <?php } ?>
    // alert(id);
    $.ajax({
     url: base_url + 'customer/get_ai_deposit',
     type: 'POST',
     data: {id: id,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
     beforeSend: function(){
        $('.loading_oz_premium').html('<img src="'+base_url+'assets_admin/images/load.gif" align="center" />');
     },
     }).then(function(data) 
     {
        var objData = jQuery.parseJSON(data);
        // var objData = $('#auction_item').val();
        console.log(objData);
            // $('#auction_item').append('<option value="">Choose</option>');
        if (objData.status == true) {
            // alert(objData.status);
            $('#amount').val(objData.deposit);
            $('#priceedbtn').attr('disabled', false);
        }else{
            $('#amount').val('');
            $('#priceedbtn').attr('disabled', true);
        }
    });
});

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
</script>

<?php 
if($language == 'arabic'){
    $ln = '&region=EG&language=ar';
}else{
    $ln = '';
}
?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item('google_map_key'); ?>&callback=initializePA<?= $ln; ?>"></script>