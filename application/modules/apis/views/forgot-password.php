
			<main class="page-wrapper register-page forgot-pass">	
				<div class="container">
					<div class="content-wrapper">
						<h2>Change Password</h2>
						<div class="tabs">
							<div class="tab-content">
								<div class="tab-pane active">
									<div class="row">
										<div class="col-lg-12">
											<div class="contact-form login-page">
												<form method="post" enctype="multipart/form-data">
													<input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label>Password   *</label>	
																<input type="password" class="form-control" name="password">
																<span class="valid-error text-danger password-error"></span>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label>Confirm Password  *</label>	
																<input type="password" class="form-control" name="cpassword">
																<span class="valid-error text-danger cpassword-error"></span>
															</div>
														</div>
                            <input type="hidden" name="email_code" value="<?= (!empty($this->uri->segment(3))) ? $this->uri->segment(3) : ''?>">
                            
                            <span class=" text-success" id="valid-msgs" ></span>
														<div class="col-lg-12">
															<div class="button-row">
																<button type="button" id="update" class="btn btn-default">UPDATE</button>
                                <span class="valid-error text-danger" id="error-msgs" ></span>
															</div>
														</div>
													</div>
												</form>
											</div>	
										</div>
									</div>	
								</div>
							</div>
						</div>
					</div>	
				</div>
			</main>

  <script type="text/javascript">

          $('#update').on('click', function(event){
        // event.preventDefault();
        console.log('dddd');
        var validation = false;
            var errorMsg = "This value is required.";
            var errorClass = ".valid-error";
            var e; 

        // if (validation == true) {
            var form = $(this).closest('form').serializeArray(); 
      $.ajax({

        type: 'post',
        url: '<?= base_url('api/updatePassword/').$this->uri->segment(3); ?>',
        data: form,
        success: function(msg){
          // console.log(msg);
          var response = JSON.parse(msg);
          
          if(response.error == true){
            $('#error-msgs').html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button>' + response.message + '</div></div>');
          }else{
            window.location.replace('<?= base_url('home/login/'); ?>');
          }
        }
      });
        // }
  });
        </script>
