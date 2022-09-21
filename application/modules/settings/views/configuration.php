   <style> 
    hr.new3 {
  border-top: 3px dotted #799ed9;
}
</style>
      <div id="result"></div>

    <div class="x_title">
        <h3><?php  echo $title; ?></h3>
        <div class="clearfix"></div>
        <?php if ($this->session->flashdata('msg')) {?>
        <div class="alert">
            <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                  </button>
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        </div>
        <?php }?>                        
    </div>
	

    <div class="container" style="padding-top: 10px">
    	<form  method="post" action="<?php echo base_url('settings/save_buyer_comission');?>"  class="form-horizontal form-label-left">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if($title == 'Edit configuration' ){  ?>
                 <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
                <?php } ?>
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Add minimum Deposite Amount <span class="required">*</span>
                    <input type="hidden" name="code_key" value="min_deposit">
                    <input type="hidden" name="type" value="number">
                    <input type="hidden" name="category" value="bid">
                </label>
                    <!-- <?php if($buyer_comission){  ?>
                    <button type="submit" id="send" class="btn btn-success">update</button>
                    <?php } else{ ?>
                    <button id="send" type="submit" class="btn btn-success">Add</button>
                     <?php }?> -->
                     <button id="" type="submit" class="btn btn-success">Submit</button>
                     <?php 
                        $p_amount = $this->db->get_where('settings',['code_key' =>'min_deposit'])->row_array();
                    ?>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required="" maxlength="150" id="security" title="please add something" name="value" value="<?php
                    if(count($buyer_comission) >0){ echo $p_amount['value']; }?>" class="form-control col-md-7 col-xs-12">
                </div>
            </div>
        </form>
    </div>

    <div class="container" style="padding-top: 10px">
        <form  method="post"  action="<?php echo base_url('settings/save_payments');?>" class="form-horizontal form-label-left">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <div class="item form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">percentage Amount(%)<span class="required">*</span>
                    <input type="hidden" name="code_key" value="p_amount">
                    <input type="hidden" name="type" value="number">
                    <input type="hidden" name="category" value="bid">
                </label>
                    <button id="" type="submit" class="btn btn-success">Submit</button>
                    <?php 
                        $p_amount2 = $this->db->get_where('settings',['code_key' =>'p_amount'])->result_array();
                    ?>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required="" maxlength="150" id="security" title="please add something" name="value" value="<?php
                    if(count($p_amount2) >0){ echo $p_amount2[0]['value']; }?>" class="form-control col-md-7 col-xs-12">
                </div>
            </div>
        </form>
    </div>
        
        <!-- <div class="container" style="padding-top: 10px">
           <form  method="post" novalidate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
            <h3><?php  echo $title; ?></h3>
            <div class="clearfix"></div>
            <hr>
            <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Organization <span class="required">*</span>
                </label>
                 <?php if($title == 'Edit Commition' ){  ?>
                    <button type="submit" id="send" class="btn btn-success">update</button>
                    <?php } else{ ?>

                    <button id="send" type="submit" class="btn btn-success">Add</button>
                     <?php }?>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required="" maxlength="150" id="security" title="please add something" name="deposite" value="<?php 
                    if(count($buyer_comission) >0){ echo $buyer_comission[0]['deposite']; }?>" class="form-control col-md-7 col-xs-12">
                </div>
            </div>
           -->
            <!-- <div class="ln_solid"></div> -->
                
               <!--   <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <?php if($title == 'Edit Commition' ){  ?>
                    <button type="submit" id="send" class="btn btn-success">update</button>
                    <?php } else{ ?>

                    <button id="send" type="submit" class="btn btn-success">Add</button>
                     <?php }?>
                </div>
            </div> -->
            <!-- </form>
        </div> -->
        <hr class="new3">
        <h3><?php  echo $title2; ?></h3>
        <div class="clearfix"></div>

        <div class="container" style="padding-top: 10px">
        <form  method="post"  action="<?php echo base_url('settings/save_vat');?>" class="form-horizontal form-label-left">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <div class="item form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">VAT(%)<span class="required">*</span>
                    <!-- <input type="hidden" name="p_amount" value="p_amount"> -->
                    <?php 
                        $p_amount = $this->db->get_where('settings',['code_key' =>'vat'])->result_array();
                    ?>
                    <input type="hidden" name="code_key" value="vat">
                    <input type="hidden" name="type" value="percentage">
                    <input type="hidden" name="category" value="bid">
                </label>
                    <button id="" type="submit" class="btn btn-success">Submit</button>
                    <?php 
                        $p_amount = $this->db->get_where('settings',['code_key' =>'vat'])->result_array();
                    ?>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="number" required="" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" maxlength="150" id="security" title="please add something" name="value" value="<?php
                    if(count($p_amount) >0){ echo $p_amount[0]['value']; }?>" class="form-control col-md-7 col-xs-12">
                </div>
            </div>
        </form>
    </div>
 <hr class="new3">

 <h3><?php  echo $title3; ?></h3>
        <div class="clearfix"></div>
        <div class="container" style="padding-top: 10px">
        <form  method="post"  action="<?php echo base_url('settings/save_infoemail');?>" class="form-horizontal form-label-left">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <div class="item form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Info Email<span class="required">*</span>
                    <input type="hidden" name="code_key" value="info_email">
                    <input type="hidden" name="type" value="email">
                    <input type="hidden" name="category" value="organization">
                </label>
                    <button id="" type="submit" class="btn btn-success">Submit</button>
                    <?php 
                        $p_amount = $this->db->get_where('settings',['code_key' =>'info_email'])->result_array();
                    ?>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required="" maxlength="150" id="security" title="please add something" name="value" value="<?php
                    if(count($p_amount) >0){ echo $p_amount[0]['value']; }?>" class="form-control col-md-7 col-xs-12">
                </div>
            </div>
        </form>
    </div>
<hr class="new3">
<h3><?php  echo $title4; ?></h3>
        <div class="clearfix"></div>
        <div class="container" style="padding-top: 10px">
        <form  method="post"  action="<?php echo base_url('settings/save_defaultCurrency');?>" class="form-horizontal form-label-left">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <div class="item form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Default Currency<span class="required">*</span>
                    <input type="hidden" name="code_key" value="currency">
                    <input type="hidden" name="type" value="text">
                    <input type="hidden" name="category" value="system">
                </label>
                    <button id="" type="submit" class="btn btn-success">Submit</button>
                    <?php 
                        $p_amount = $this->db->get_where('settings',['code_key' =>'currency'])->result_array();
                    ?>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required="" maxlength="150" id="security" title="please add something" name="value" value="<?php
                    if(count($p_amount) >0){ echo $p_amount[0]['value']; }?>" class="form-control col-md-7 col-xs-12">
                </div>
            </div>
        </form>
    </div>
    <!-- <script>	
     var ok;
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
         $('#demo-form2').parsley().on('field:validated', function() {
    var ok = $('.parsley-error').length === 0;
  }) .on('form:submit', function() {
            var formData = new FormData($("#demo-form2")[0]);
            // console.log(formData);
            // return;
                    $.ajax({
                        url: url + 'settings/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                        var objData = jQuery.parseJSON(data);
                        if (objData.msg == 'success') {

                            window.location = url + 'settings/configuration';
                        } 
                        else {
                            // $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');

                        }
                    });
                      return false; 
                });
            });
    // });

    </script> -->


<!-- <script>
    var vat = $('#vat').val();
    if (vat) {
    alert(vat);
    $( "#vat" ).append( " % " );
    }
</script>
 -->