<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">

<div class="main gray-bg login-page change-password">
    <div class="container">
			<?php if($this->session->flashdata('success')){ ?>
				<div class="alert">
					<div class="alert alert-domain alert-success alert-dismissible in" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						</button>
						<?php echo $this->session->flashdata('success'); ?>
					</div>
				</div>
			<?php }?>

			<?php if($this->session->flashdata('error')){ ?>
				<div class="alert">
					<div class="alert alert-domain alert-danger alert-dismissible in" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						</button>
						<?php echo $this->session->flashdata('error'); ?>
					</div>
				</div>
			<?php }?>

      <h1 class="section-title"><?=$this->lang->line('reset_password');?></h1>

        <form method="post" action="<?php echo base_url('home/update_password'); ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            <input type="hidden" name="code" value="<?=$this->session->flashdata('item');?>" />

            <div class="row">
                <div class="col-sm-6">
                    <label><?= $this->lang->line('password_small');?></label>
                    <input id="pass" type="password" class="form-control" name="first_pass">
                    <span class="valid-error text-danger first_pass-error"></span>
                </div>
                <div class="col-sm-6">
                    <label><?= $this->lang->line('confirm_password_small');?></label>
                    <input  id="cpass" type="password"  class="form-control" name="second_pass">
                    <span class="valid-error text-danger second_pass-error"></span>
                </div>
                <div class="col-sm-12">
                  <span class=" text-success" id="log-msgs" ></span>
                  <span class=" text-success" id="error-msgs" ></span>
                  <div style="margin-bottom: 20px"><span id="errorSpan" style="color:red;"></span></div>

                    <div class="button-row">
                        <button class="btn btn-default bg-btn" type="button" id="update_p"><?= $this->lang->line('update');?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

    <script>

    $('#update_p').on('click',function(e){
     e.preventDefault();
      // let length  = $('#pass').val().length;
      // let clength  = $('#cpass').val().length;
      // if (length < 5 && clength < 5) {
      //   $('.password-error').html =  "Password must be 5 digits long";
      //   e.preventDefault();
      // }
      var validation = false;
      var language = "<?= $language; ?>";


      //console.log(language);

      selectedInputs = ['first_pass','second_pass'];
      validation = validateFields(selectedInputs,language);
		console.log(validation);
      if(validation == false){
        return false;
      }

      if(validation == true){
          var pass = $('#pass').val();
          var cpass = $('#cpass').val();
          if (pass.length < 5) {
              new PNotify({
                  type: 'error',
                  addclass: 'custom-error',
                  text: "<?= $this->lang->line('password_length_should_be_long'); ?>",
                  styling: 'bootstrap3',
                  title: "<?= $this->lang->line('error');?>"
              });
          } else{
              if (pass != cpass) {
                  new PNotify({
                      type: 'error',
                      addclass: 'custom-error',
                      text: "<?= $this->lang->line('Password_confirm_not_matched'); ?>",
                      styling: 'bootstrap3',
                      title: "<?= $this->lang->line('error');?>"
                  });
              } else {
                  $(this).closest("form").submit();
              }
          }
      }
      if (status == false) {
        $(".password-error").html = "<?= $this->lang->line('password_six_digit_long');?>";
      }
      //  if(status == true){
      //  $('#error-msgs').html('<div class="alert alert-success alert-dismissible" id="valid-msgs"><button type="button" class="close" data-dismiss="alert">&times;</button><strong></strong> </div>');
      //    $('#error-msgss').hide();

      // }
      // else{
      //  $('#error-msgs').html('<div class="alert alert-danger alert-dismissible" id="valid-msgss"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Password not matched ! Try again </strong> </div>');
      //  //   // window.location.replace('<?= base_url('home/forgot_screen/'); ?>');
      // }

    });
       </script>

<!-- <script>
    function checkPasswordLength(password){
         if (password.length < 5) {
             $(".password-error").html = "Password must be 6 digits long";
         }
         else {
             $("password-error").html = "";
         }
    }
</script> -->
