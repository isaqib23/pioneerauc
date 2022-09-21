<div class="main gray-bg login-page change-password" id="empty">
        <div class="container">
          <div id="loading"></div>
            <h1 class="section-title"><?= $this->lang->line('change_password');?></h1>
            <span class=" text-success" id="valid-msgs" ></span>
            <form class="customform">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <div class="row">
                    <div class="col-sm-12">
                        <label><?= $this->lang->line('current_password');?></label>
                        <input type="password" class="form-control empty-me" name="current" >
                        <span class="valid-error text-danger current-error"></span>
                        <span class="valid-error text-danger" id="error-msgss" ></span>
                    </div>
                    <div class="col-sm-12">
                        <label><?= $this->lang->line('new_password');?></label>
                        <input type="password" class="form-control empty-me" name="new_password">
                        <span class="valid-error text-danger new_password-error"></span>
                    </div>
                    <div id="success_message"></div>
                    <div class="col-sm-12">
                        <label><?= $this->lang->line('confirm_password');?></label>
                        <input type="password" class="form-control empty-me" name="c_password">
                        <span class="valid-error text-danger c_password-error"></span>
                    </div>
                    <div class="col-sm-12">
                        <div class="button-row center">
                            <button type="submit" class="btn btn-default" id="change"><?= $this->lang->line('update');?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script type="text/javascript">

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
                    $('#error-msgs').html(response.responce);
                    $('#valid-msgs').html('<div class="alert alert-danger alert-dismissible" id="valid-msgs"><button type="button" class="close" data-dismiss="alert">&times;</button><strong><?= $this->lang->line("error_"); ?></strong> ' +response.responce+'</div>');
                    $('#loading').html('');
                    return;
                  }else{
                      $('#valid-msgs').html('<div class="alert alert-success alert-dismissible" id="valid-msgs"><button type="button" class="close" data-dismiss="alert">&times;</button><strong><?= $this->lang->line("updated"); ?></strong> <?= $this->lang->line("password_changed_successfully"); ?></div>');
                        $('.empty-me').val('');

                        // $(this).removeAttr("value", " ");
                        $('#loading').html('');
                    }
              }
          });
        }
  });
        </script>