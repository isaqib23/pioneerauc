<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<?php 
  $rurl = '';
  if (!empty($_GET['rurl'])) {
    $rurl = $_GET['rurl'];
  }
?>
<?= $this->load->view('template/auction_cat');?>
<div class="main gray-bg profile-page">
  <div class="row custom-wrapper">
    <?= $this->load->view('template/template_user_leftbar') ?>
    <div class="right-col" id="pform">
      <h1 class="section-title text-left"><?= $this->lang->line('profile');?></h1>
      <div class="row row-reverse">
        <div class="col-sm-8">
          <form class="customform" method="post" >
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            <div class="row">
              <div class="col-sm-6">
                <label><?= $this->lang->line('f_name');?> *</label>
                <input type="text" class="form-control" name="fname" value="<?php if(isset($list)){ echo $list['fname']; }?>">
                <span class="valid-error text-danger fname-error"></span> 
              </div>
              <div class="col-sm-6">
                <label><?= $this->lang->line('l_name');?> *</label>
                <input type="text" class="form-control" name="lname" value="<?php if(isset($list)){ echo $list['lname']; }?>">
                <span class="valid-error text-danger lname-error"></span>
              </div>
              <div class="col-sm-6">
                <label><?= $this->lang->line('email');?> *</label>
                <input type="text" class="form-control" readonly name="email" value="<?php if(isset($list)){ echo $list['email']; }?>">
                <span class="valid-error text-danger email-error"></span>
              </div>  
              <div class="col-sm-6">
                <label><?= $this->lang->line('remarks');?> *</label>
                <input type="text" class="form-control" name="remarks" value="<?php if(isset($list)){ echo $list['remarks']; }?>">
                <span class="valid-error text-danger remarks-error"></span>
              </div>
              <div class="col-sm-6">
                <label><?= $this->lang->line('mobile');?> *</label>
                <input type="text" class="form-control" readonly name="mobile" value="<?php if(isset($list)){ echo $list['mobile']; }?>">
                <span class="valid-error text-danger mobile-error"></span>
                <span class="valid-error text-danger" id="error-msgs" ></span>
              </div>
              <div class="col-sm-6">
                <label><?= $this->lang->line('address');?> *</label>
                <input type="text" class="form-control" name="address" value="<?php if(isset($list)){ echo $list['address']; }?>">
                <span class="valid-error text-danger address-error"></span>
              </div>
              <div class="col-sm-6">
                <label><?= $this->lang->line('city');?> *</label>
                <input type="text" class="form-control" oninput="this.value=this.value.replace(/[^aA-zZ]/g,'');" name="city" value="<?php if(isset($list)){ echo $list['city']; }?>">
                <span class="valid-error text-danger city-error"></span>
              </div>
              <div class="col-sm-6">
                <label><?= $this->lang->line('states');?> *</label>
                <input type="text" class="form-control" name="state" value="<?php if(isset($list)){ echo $list['state']; }?>">
                <span class="valid-error text-danger state-error"></span>
              </div>
              <div class="col-sm-6">
                <label><?= $this->lang->line('country');?> *</label>
                <!-- <input type="text" class="form-control" name="country" value="<?php if(isset($list)){ echo $list['country']; }?>"> -->
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
            <div class="row">
              <div class="col-sm-6">
                <label><?= $this->lang->line('id_nmbr');?> *</label>
                <input type="text" class="form-control" name="id_number" value="<?php if(isset($list)){ echo $list['id_number']; }?>">
                <span class="valid-error text-danger id_number-error"></span>
              </div>
              <div class="col-sm-6">
                <label><?= $this->lang->line('po_box');?> *</label>
                <input type="text" class="form-control" name="po_box" value="<?php if(isset($list)){ echo $list['po_box']; }?>">
                <span class="valid-error text-danger po_box-error"></span>
              </div>
              <div class="col-sm-6">
                <label><?= $this->lang->line('job_title');?> *</label>
                <input type="text" class="form-control" name="job_title" value="<?php if(isset($list)){ echo $list['job_title']; }?>">
                <span class="valid-error text-danger job_title-error"></span>
              </div>  
              <div class="col-sm-6">
                <label><?= $this->lang->line('company_name');?> *</label>
                <input type="text" class="form-control" name="company_name" value="<?php if(isset($list)){ echo $list['company_name']; }?>">
                <span class="valid-error text-danger company_name-error"></span>
              </div>
              <div class="col-sm-12">
                <label><?= $this->lang->line('description');?></label>
                <textarea class="form-control" name="description"><?php if(isset($list)){ echo $list['description']; }?></textarea>
              </div>
            </div>
            <div class="button-row">
              <button id="update" name="up" class="btn btn-default"><?= $this->lang->line('update');?></button>
            </div>
          </form>  
        </div>
        <div class="col-sm-4">
          <form id="demo-form2" method="post" action="<?= base_url('customer/save_profile_image'); ?>" enctype="multipart/form-data">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            <div class="our-profile">
                <div class="upload-img">
                    <div class="image">
                      <?php
                      if(isset($list['picture']) && !empty($list['picture'])){
                        $file = $this->db->get_where('files',['id' => $list['picture']])->row_array();
                      ?>
                        <img id="img" src="<?= base_url($file['path'] . $file['name']); ?>" alt="">
                      <?php }else{ ?>
                        <img id="img" src="<?php echo base_url('assets_user/images/no_image');?>" height="130" width="130" alt="">
                      <?php } ?>
                    </div>
                    <a href="javascript:void(0)" id="save_pic" style="display: none;"><?= $this->lang->line('save');?></a>
                </div>
                <div class="uploader">
                  <a href="#"><?= $this->lang->line('upload_photo');?>
                  <input type="file" class="img" name="profile_picture" accept=".jpg,.jpeg,.png" class="profile_picture" ></a>
                  <input type="hidden" name="old_profile_picture" value="<?php echo (isset($all_users[0]['picture']) && !empty($all_users[0]['picture'])) ? $all_users[0]['picture'] : ''; ?>">
                </div> 
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>  

<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

<script>
  $(document).ready(function(){
   $('#update').html('<?= $this->lang->line('edit');?>');
      $('form').click( function() {
        var btnUpdate = $('#update');
        // $('html, body').animate({ scrollBottom: btnUpdate.offset().top }, 'slow');
        if (btnUpdate.text() == '<?= $this->lang->line('edit');?>') {
            btnUpdate.focus();
            PNotify.removeAll();
            new PNotify({
                text: '<?= $this->lang->line('press_edit_button_to_apply_changes');?>',
                type: 'info',
                title: '<?= $this->lang->line('error');?>',
                styling: 'bootstrap3'
            });
        }
      });
  });
</script>

<script>
  $(document).ready(function() {
    $(".alert").delay(3000).fadeOut();
      <?php if($this->session->flashdata('error')){ ?>
      var message = "<?php echo $this->session->flashdata('error'); ?>";
      PNotify.removeAll();
        new PNotify({
            text: message,
            type: 'error',
            addclass: 'custom-error',
            title: "<?= $this->lang->line('error_'); ?>",
            styling: 'bootstrap3'
        });
    <?php } ?>

    $("#pform :input").not("[name=up]").prop("disabled", true);
    // $("#pform :input").prop("disabled", true);
  });
 
  $('#update').on('click', function(event){
    event.preventDefault();
    var mode = $(this).text();
    if(mode == '<?= $this->lang->line('edit');?>'){
      $("#pform :input").removeAttr('disabled');
      $(this).text('<?= $this->lang->line('update');?>');
    }
    if(mode == '<?= $this->lang->line('update');?>'){
      // alert('ddd');
      var validation = false;
      var language = '<?= $language; ?>';
      selectedInputs = ['fname','lname', 'remarks','address','city','state','id_number','po_box','job_title','company_name'];
      validation = validateFields(selectedInputs, language);

      if (validation == true) {
        //   $('#user-phone').val($("#user-phone").intlTelInput('getNumber'));
        // var form = $(this).closest('form').serializeArray();
        var form = $('form').serializeArray();
        //console.log(form);
        
        
        $.ajax({
          type: 'post',
          url: '<?= base_url('customer/update_profile'); ?>',
          data: form,
          // beforeSend: function(){
          //     $('#loading').html('<div class="loader-wrapper" ><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div> <div class="sk-rect2"></div> <div class="sk-rect3"></div> <div class="sk-rect4"></div> <div class="sk-rect5"></div> </div> </div>');
          // },
          success: function(msg){
            // console.log(msg);
            $('#update').text('<?= $this->lang->line('edit');?>');
            var response = JSON.parse(msg);
            PNotify.removeAll();
            
            if(response.msg == 'Number already exist.'){
              new PNotify({
                  text: response.msg,
                  type: 'error',
                  addclass: 'custom-error',
                  title: 'Error',
                  styling: 'bootstrap3'
                });
            }

            if(response.msg == 'Email already exist.'){
              new PNotify({
                  text: response.msg,
                  type: 'error',
                  addclass: 'custom-error',
                  title: 'Error',
                  styling: 'bootstrap3'
                });
            }
            if(response.error == false){
              new PNotify({
                  text: "<?= $this->lang->line('profile_updated_successfully');?>",
                  type: 'success',
                  addclass: 'custom-success',
                  title: '<?= $this->lang->line('success_');?>',
                  styling: 'bootstrap3'
                });
              <?php if (isset($_GET["r"]) && !empty($_GET["r"])) { ?>
                var url = '<?= $this->redirect_model->custom_redirect($_GET["r"]); ?>';
                location.replace('<?= base_url(); ?>'+url); 
              <?php } ?>
              $("#pform :input").not("[name=up]").prop("disabled", true);
              // var rurl= '<?= $rurl; ?>';
              //   // window.location.href = 
              // if (rurl) {
              // } else {
              // }
                
               
            }
          } 
        });
      }
    }
  });
       
</script>



<script>
 
  
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $("#img").attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  $(".img").change(function(input){
    readURL(this);
    $('#save_pic').show();
  }); 

    //console.log(form);
  $('#save_pic').on('click', function(event){
    var formData = new FormData($("#demo-form2")[0]);
    $.ajax({
      type: 'post',
      url: '<?= base_url('customer/save_profile_image');?>',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,

    }).then(function(data) {
      PNotify.removeAll();
      // alert('dc');
      var response = JSON.parse(data);
      if(response.status == true){
        new PNotify({
          text: response.message,
          type: 'success',
          addclass: 'custom-success',
          title: "<?= $this->lang->line('success_'); ?>",
          styling: 'bootstrap3'
        });
        window.location.reload(true);
      } else {
        new PNotify({
          text: response.message,
          type: 'error',
          addclass: 'custom-error',
          title: '<?= $this->lang->line('error'); ?>',
          styling: 'bootstrap3'
        });
      }
    });  

  });
    
</script>

 