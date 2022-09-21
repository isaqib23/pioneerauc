<style type="text/css">
    .rmv-bg{
        background-color: #fff !important;
    }
    .add-bg{
        background-color: #e9e9e9 !important;
    }
</style>
<div class="main-wrapper account-page profile-page">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>"><?= $this->lang->line('home');?></a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('customer/deposit'); ?>"><?= $this->lang->line('my_account_new');?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $this->lang->line('my_profile');?></li>
        </ol>
        <h2><?= $this->lang->line('my_account_new');?></h2>
        <div class="account-wrapper">
            <?= $this->load->view('template/new/template_user_leftbar') ?>
            <div class="right-col">
                <h3><?= $this->lang->line('my_details');?></h3>

                <!-- add-new -->
                <div class="user-photo show-on-1000">
                    <!-- <div class="image flex">
                        <?php
                          //if(isset($list['picture']) && !empty($list['picture'])){
                            //$file = $this->db->get_where('files',['id' => $list['picture']])->row_array();
                          ?>
                            <img id="img" src="<?//= base_url($file['path'] . $file['name']); ?>" alt="">
                          <?php //}else{ ?>
                            <img id="img" src="<?php// echo base_url('assets_user/images/no_image');?>" height="130" width="130" alt="">
                        <?php //} ?>
                    </div> -->
                </div>

                <!-- <form class="account-form" > -->

                <!-- add-new end-->


                <h4 class="border-title personal-title"><?= $this->lang->line('personal_information');?></h4>
                <form class="account-form" id="profileForm">
                	<input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                    <div class="row col-gap-24 rmv">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('f_name');?> <span>*</span></label>
                                <input  type="text" class=" form-control add-bg" placeholder="<?= $this->lang->line('enter_first_name'); ?>" name="fname" value="<?php if(isset($list)){ echo $list['fname']; }?>">
            					<span class="valid-error text-danger fname-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('l_name');?> <span>*</span></label>
                                <input type="text" class="form-control  add-bg" placeholder="<?= $this->lang->line('enter_last_name'); ?>" name="lname" value="<?php if(isset($list)){ echo $list['lname']; }?>">
            					<span class="valid-error text-danger lname-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('email');?> <span>*</span></label>
                                <input type="text" class="form-control  add-bg" readonly name="email" value="<?php if(isset($list)){ echo $list['email']; }?>">
            					<span class="valid-error text-danger email-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('mobile');?> <span>*</span></label>
                                <input type="text" dir="ltr" class="form-control  add-bg" placeholder="<?= $this->lang->line('enter_mobile_num'); ?>" readonly name="mobile" value="<?php if(isset($list)){ echo $list['mobile']; }?>">
				                <span class="valid-error text-danger mobile-error"></span>
				                <span class="valid-error text-danger" id="error-msgs" ></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('address');?> <span>*</span></label>
                                <input type="text" class="form-control  add-bg" name="address" placeholder="<?= $this->lang->line('address'); ?>" value="<?php if(isset($list)){ echo $list['address']; }?>">
           						<span class="valid-error text-danger address-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('city');?> <span>*</span></label>
                                <input type="text" class="form-control  add-bg" oninput="this.value=this.value.replace(/[^aA-zZ]/g,'');" name="city" placeholder="<?= $this->lang->line('enter_city'); ?>" value="<?php if(isset($list)){ echo $list['city']; }?>">
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
         					 <select name="country" class="validThis location-selector form-control  add-bg" id="countryId" required="" >
				                <?php 
				                if (!empty($country_name)) {?>
				                          <option value="<?= $countries[0]['name']; ?>" data-countrycode="<?=  $country_name['id']; ?>"><?= $country_name['name']; ?></option>
				                <?php }else{?>
				                          <option value="United Arab Emirates" data-countrycode="199">United Arab Emirates</option>
				               <?php }?>
				                      <?php if($list):
				                          $countries = $this->db->get('country')->result_array();
				                          foreach ($countries as $key => $country):
				                        //  if( ($country['id'] != 199)):
				                        ?>
				                          <option value="<?= $country['name']; ?>" data-countrycode="<?= $country['id']; ?>"><?= $country['name']; ?></option>
				                      <?php
				                     // endif;
				                      endforeach;
				                      endif;?>
				              </select>
				                <span class="valid-error text-danger country-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('states');?> <span>*</span></label>
                                <input type="text" class="form-control add-bg" name="state" value="<?php if(isset($list)){ echo $list['state']; }?>">
            					<span class="valid-error text-danger state-error"></span>
                            </div>
                        </div>
                        <!-- <div class="col-sm-4">
                            <div class="form-group">
                                <label><?//= $this->lang->line('remarks');?> <span>*</span></label>
                               <input type="text" class="form-control" name="remarks" value="<?php //if(isset($list)){ echo $list['remarks']; }?>">
            					<span class="valid-error text-danger remarks-error"></span>
                            </div>
                        </div> -->
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('id_nmbr');?> <span>*</span></label>
                                <input type="text" class="form-control add-bg" name="id_number" value="<?php if(isset($list)){ echo $list['id_number']; }?>">
            					<span class="valid-error text-danger id_number-error"></span>
                            </div>
                        </div>
                       
                    
          				<button id="update" name="up" class="btn btn-primary"><?= $this->lang->line('update');?></button>
                    </div>
                </form>
                <br>

                <!-- Profile img portion -->
                <div class="profile profile-part">
                <h4 class="border-title"><?= $this->lang->line('account_information');?></h4>
                <div class="profile-photo-part">
                <div class="user-photo">
                    <div class="image flex">
                        <?php
	                      if(isset($list['picture']) && !empty($list['picture'])){
	                        $file = $this->db->get_where('files',['id' => $list['picture']])->row_array();
	                      ?>
	                        <img id="img" src="<?= base_url($file['path'] . $file['name']); ?>" alt="">
	                      <?php }else{ ?>
	                        <img id="img" src="<?php echo base_url('assets_user/images/no_image');?>" height="130" width="130" alt="">
                  		<?php } ?>
                    </div>
                </div>

                <!-- <form class="account-form" > -->
                <form id="profileImageForm" class=" account-form" method="post"  enctype="multipart/form-data">
                	<input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                	 <div class="upload-btn">
                        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.457 15.9535L6.9375 18.2902C6.62578 18.4214 6.31172 18.33 6.1125 18.1285C5.91328 17.9269 5.82188 17.6152 5.95312 17.3058L8.29219 11.7886C8.32969 11.6996 8.38359 11.6175 8.45156 11.5496L17.7609 2.24019C18.4195 1.5816 19.493 1.57691 20.1516 2.2355L22.0102 4.0941C22.6687 4.75269 22.6641 5.82613 22.0055 6.48472L12.6961 15.7941C12.6258 15.8621 12.5461 15.916 12.457 15.9535ZM2.34375 21.0558H21.6562C22.1227 21.0558 22.5 21.4332 22.5 21.8996C22.5 22.366 22.1227 22.7433 21.6562 22.7433H2.34375C1.87734 22.7433 1.5 22.366 1.5 21.8996C1.5 21.4332 1.87734 21.0558 2.34375 21.0558ZM18.9539 3.43316L18.9586 3.42847L20.8148 5.28472L20.8102 5.28941L19.357 6.74254L17.5008 4.88629L18.9539 3.43316ZM16.3078 6.07925L9.77344 12.6136L8.40938 15.8339L11.6297 14.4699L18.1641 7.9355L16.3078 6.07925Z" fill="#5C02B5"/>
                        </svg>
                        <?= $this->lang->line('upload_photo'); ?>
                    </div><br>
                    <div style="display: flex; align-items: center;">
                        <label for="files" class="btn" style="border: 1px solid black; margin: 5px 0px; padding: 0px 10px; background-color: #F3F0F5; border-radius: 3px;"><?= $this->lang->line('chose_file'); ?></label>&nbsp <span id="noFileChosen" style=""><?= $this->lang->line('no_file_choosen'); ?></span>
                    </div>
                	<input type="file" class="img" id="files" style="visibility:hidden;" name="profile_picture" accept=".jpg,.jpeg,.png" class="profile_picture" ></a>
              		<input type="hidden" name="old_profile_picture" value="<?php echo (isset($all_users[0]['picture']) && !empty($all_users[0]['picture'])) ? $all_users[0]['picture'] : ''; ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                    <div class="row col-gap-24">
                        <div class="col-sm-4">
                            <div class="button-row mt-4">
                                <button type="button" id="save_pic" class="btn btn-primary"><?= $this->lang->line('save');?></button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
                </div>
                <br>
                  <h4 class="border-title"><?= $this->lang->line('change_password');?></h4>
                  <span class=" text-success" id="valid-msgs" ></span>
                    <form class="account-form">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                        <div class="row col-gap-24" id="empty">
                            <div class="col-sm-4">
                                <div class="form-group mb-0">
                                    <label><?= $this->lang->line('enter_current_password');?> <span>*</span></label>
                                    <input type="password" class="form-control  empty-me" placeholder="<?= $this->lang->line('enter_current_password'); ?>" name="current">
                                    <span class="valid-error text-danger current-error"></span>
                                    <span class="valid-error text-danger" id="error-msgss" ></span>
                                </div>
                                <br>
                                <div class="form-group mb-0">
                                    <label><?= $this->lang->line('enter_new_password');?> <span>*</span></label>
                                    <input type="password" class="form-control  empty-me" placeholder="<?= $this->lang->line('enter_new_password'); ?>" name="new_password">
                                    <span class="valid-error text-danger new_password-error"></span>
                                </div>
                                <br>
                                <div class="form-group mb-0">
                                    <label><?= $this->lang->line('confirm_password');?> <span>*</span></label>
                                    <input type="password" class="form-control  empty-me" placeholder="<?= $this->lang->line('confirm_new_password'); ?>" name="c_password" >
                                    <span class="valid-error text-danger c_password-error"></span>
                                </div>
                                <br>
                                <div class="button-row mt-4">
                                    <button type="button" class="btn btn-primary" id="change"><?= $this->lang->line('update');?></button>
                                </div>
                            </div>  
                        </div>

                    </form>
                    <br>
                     <h4 class="border-title"></h4>
                    <div class="right-col">
                <h3>
                    <?= $this->lang->line('uploaded_documents_new')?>
                </h3>
                <table class="customtable datatable table-responsive" cellpadding="0" cellspacing="0" >
                    <thead>
                        <th width="24%"><?= $this->lang->line('name_new')?></th>
                        <th class="item-arrow" width="30%"><?= $this->lang->line('items')?></th>
                        <th class="hide-on-768" width="18%"><?= $this->lang->line('created_on_new')?></th>
                    </thead>
                    <tbody>
                        <?php 
                        if($docs){
                            foreach ($docs as $key => $doc) {
                                $doc_name = json_decode($doc['document_type']);
                                $file_ids = explode(',', $doc['file_id']);
                                $file_ids = array_filter($file_ids, 'is_numeric');
                                
                                $files = [];
                                if($file_ids){
                                    $files = $this->db->where_in('id', $file_ids)->get('files')->result_array();
                                }
                                
                                if($files){ ?>
                                    <tr>
                                        <td><?= $doc_name->$language; ?></td>
                                        <td><?php
                                                $i = 0;
                                                foreach ($files as $k => $file) {
                                                    $i++;
                                                    ?>
                                                    <li>
                                                        <a download="" href="<?= base_url('uploads/users_documents/').$user_id.'/'.$file['name']; ?>"><?= 'FILE-'.$i; ?></a>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                        </td>
                                        <td class="hide-on-768">
                                            <?php 
                                            $docCreated = strtotime($doc['created_on']);
                                            if($language == 'arabic'){
                                                $fmt = datefmt_create("ar_AE", IntlDateFormatter::LONG, IntlDateFormatter::MEDIUM, 'Asia/Dubai', IntlDateFormatter::GREGORIAN);
                                                echo datefmt_format( $fmt , $docCreated);
                                            }else{
                                                echo date('d/m/Y h:i A', $docCreated); 
                                                // echo date('dS M Y H:i', $bidTime); 
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                            }
                        } ?>
                        <!-- <tr>
                            <td>Passport</td>
                            <td>File 2</td>
                            <td>2020-09-20 08:54:07</td>
                        </tr> -->
                    </tbody>
                </table>
                <div class="uploadDocument">
                <a href="<?= base_url('customer/docs');?>" data-toggle="tooltip" data-placement="top" title="<?= $this->lang->line('upload_documents'); ?>">
                          <!-- <span class="material-icons">
                            add_circle
                        </span> -->
                        <span><?= $this->lang->line('upload_documents'); ?></span>
                    </a> 
                </div>
            </div>

            </div>
        </div>

        <?= $this->load->view('template/new/faq_footer') ?>
    </div>
</div>


 <!-- PNotify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

<script>
  $(document).ready(function(){
   $('#update').html('<?= $this->lang->line('edit');?>');
      $('#profileForm').click( function() {
        var btnUpdate = $('#update');
        // $('html, body').animate({ scrollBottom: btnUpdate.offset().top }, 'slow');
        if (btnUpdate.text() == '<?= $this->lang->line('edit');?>') {
            btnUpdate.focus();
            PNotify.removeAll();
            new PNotify({
                text: '<?= $this->lang->line('press_edit_button_to_apply_changes');?>',
                type: 'info',
                addclass: 'custom-info',
                title: '<?= $this->lang->line('error');?>',
                styling: 'bootstrap3'
            });
        }
      });
  });
</script>

<script>

  $(document).ready(function() {
    $(".alert").delay(1000).fadeOut();
      <?php if($this->session->flashdata('error')){ ?>
      var message = "<?php echo $this->session->flashdata('error'); ?>";
      PNotify.removeAll();
        new PNotify({
            text: message,
            type: 'error',
             delay:1000,
            addclass: 'custom-error',
            title: "<?= $this->lang->line('error_'); ?>",
            styling: 'bootstrap3'
        });
    <?php } ?>

    $("#pform :input").not("[name=up]").prop("disabled", true);
  });
 
  $('#update').on('click', function(event){
    event.preventDefault();
    var mode = $(this).text();
    if(mode == '<?= $this->lang->line('edit');?>'){
      $("#pform :input").removeAttr('disabled');
    //  $("input.rmv").removeAttr('disabled');
      $(this).text('<?= $this->lang->line('update');?>');
      $("div.rmv input").addClass('rmv-bg');
      $("div.rmv input").removeClass('add-bg');
      $("div.rmv select").addClass('rmv-bg');
      $("div.rmv select").removeClass('add-bg');
    }
    if(mode == '<?= $this->lang->line('update');?>'){
      //  $("input.rmv").prop("disabled", true);
      $("div.rmv input").addClass('add-bg');
      $("div.rmv input").removeClass('rmv-bg');
      $("div.rmv select").addClass('add-bg');
      $("div.rmv select").removeClass('rmv-bg');

      var validation = false;
      var language = '<?= $language; ?>';
      selectedInputs = ['fname','lname','address','city','state','id_number'];
      validation = validateFields(selectedInputs, language);

      if (validation == true) {
        var form = $('form').serializeArray();
        $.ajax({
          type: 'post',
          url: '<?= base_url('customer/update_profile'); ?>',
          data: form,
          success: function(msg){
            $('#update').text('<?= $this->lang->line('edit');?>');
            var response = JSON.parse(msg);
            PNotify.removeAll();
            
            if(response.msg == 'Number already exist.'){
              new PNotify({
                  text: response.msg,
                  type: 'error',
                  title: 'Error',
                   delay:1000,
                  addclass: 'custom-error',
                  styling: 'bootstrap3'
                });
            }

            if(response.msg == 'Email already exist.'){
              new PNotify({
                  text: response.msg,
                  type: 'error',
                  title: 'Error',
                   delay:1000,
                  addclass: 'custom-error',
                  styling: 'bootstrap3'
                });
            }
            if(response.error == false){
              new PNotify({
                  text: "<?= $this->lang->line('profile_updated_successfully');?>",
                  type: 'success',
                  delay:1000,
                  addclass: 'custom-success',
                  title: '<?= $this->lang->line('success_');?>',
                  styling: 'bootstrap3'
                });
              <?php if (isset($_GET["r"]) && !empty($_GET["r"])) { ?>
                var url = '<?= $this->redirect_model->custom_redirect($_GET["r"]); ?>';
                location.replace('<?= base_url(); ?>'+url); 
              <?php } ?>
              $("#pform :input").not("[name=up]").prop("disabled", true);
            }
          } 
        });
      }
    }
  });
       
</script>


<script>
   
   $("#files").change(function() {
      filename = this.files[0].name
      $('#noFileChosen').html(filename);
      console.log(filename);
    });
  // function readURL(input) {
  //   if (input.files && input.files[0]) {
  //     var reader = new FileReader();
  //     reader.onload = function (e) {
  //       $("#img").attr('src', e.target.result);
  //     }
  //     reader.readAsDataURL(input.files[0]);
  //   }
  // }
  // $(".img").change(function(input){
  //   readURL(this);
  //   $('#save_pic').show();
  // }); 

    //console.log(form);
  $('#save_pic').on('click', function(event){
    var formData = new FormData($("#profileImageForm")[0]);
    console.log(formData);
    $.ajax({
      type: 'post',
      url: '<?= base_url('customer/save_profile_image');?>',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,

    }).then(function(data) {
      PNotify.removeAll();
      var response = JSON.parse(data);
      if(response.status == true){
        new PNotify({
          text: response.message,
          type: 'success',
          addclass: 'custom-success',
          title: "<?= $this->lang->line('success_'); ?>",
          styling: 'bootstrap3'
        });
        setTimeout(function(){ 
            window.location.reload(true);
        }, 3000);
      } else{
      	new PNotify({
          text: response.message,
          type: 'error',
          addclass: 'custom-error',
          title: '<?= $this->lang->line('error'); ?>',
          styling: 'bootstrap3'
        });
      }
        // window.location.reload(true);
    });  

  });
    
</script>


<script>
    $('#change').on('click', function(event){
    event.preventDefault();
    var validation = false;
    var language = '<?= $language; ?>';
    selectedInputs = ['current','new_password','c_password'];
    validation = validateFields(selectedInputs, language);

    if (validation == true) {
        var form = $(this).closest('form').serializeArray();
        console.log(form);
  $.ajax({

          type: 'post',
          url: '<?= base_url('customer/update_password'); ?>',
          data: form,
           beforeSend: function(){
              $('#loading').html('<div class="loader-wrapper" ><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div> <div class="sk-rect2"></div> <div class="sk-rect3"></div> <div class="sk-rect4"></div> <div class="sk-rect5"></div> </div> </div>');
          },
          success: function(msg){
              console.log(msg);
              var response = JSON.parse(msg);
              
              if(response.error == true){
                $('#loading').html('');
                PNotify.removeAll();
                    new PNotify({
                        text: response.responce,
                        type: 'error',
                        addclass: 'custom-error',
                        title: '<?= $this->lang->line('error');?>',
                        styling: 'bootstrap3'
                    });
                return;
              }else{
                    PNotify.removeAll();
                    new PNotify({
                        text: response.msg,
                        type: 'success',
                        addclass: 'custom-success',
                        title: '<?= $this->lang->line('success_');?>',
                        styling: 'bootstrap3'
                    });
                        $('.empty-me').val('');
                        $('#loading').html('');
                }
          }
      });
    }
});

</script>
