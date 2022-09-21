<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
    .dz-image img{
    max-width: 120px;
    max-height: 120px;
    }
</style>
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php echo $small_title; ?>
            </h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div id="result"></div>         
            <?php if ($this->session->flashdata('msg')) {?>
            <div class="alert">
                <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                      </button>
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
            </div>
            <?php }?>
            <br />


            
            <form method="post"  novalidate="" name="myForm" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            
                <?php
                if(count($all_users) > 0){?>
                    <input type="hidden" name="post_id" value="<?= $all_users[0]['id']; ?>">
                <?php
                }
                ?>
                <input type="hidden" id="user_id" name="user_id" value="<?php echo  $this->uri->segment(3);?>">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="skip_credit_check">Category </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
           
                        <select required="" class="form-control select_withoutcross" id="category_id" name="category_id" onchange="appendAmount(this)">
                            <option disabled selected="">Select Category</option>
                            <?php
                            if(count($category_list) > 0)
                            {
                                foreach ($category_list as $key => $value) { ?>
                                    <option <?php if(count($all_users) > 0 && $all_users[0]['category_id'] == $value['id']){ echo 'selected'; }?> value="<?= $value['id']; ?>" data-amount="<?= $value['auction_fee']; ?>"><?= $value['title']; ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Category Fee <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="category_amount" name="category_amount" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['amount'];}?>" class="form-control col-md-7 col-xs-12" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Payment Type<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="payment_type"  required="required" name="payment_type" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['payment_type'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="button" id="sendbtn" class="btn btn-success">Submit</button>
                        <a href="<?php echo base_url().'users/manage_deposite_acount/'.$this->uri->segment(3); ?>" class="btn btn-primary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<script>
    $(document).ready(function(){
        // $("#category_id").trigger('change');
      // Call Geo Complete
      // $("#city").geocomplete({details:"form#demo-form2"});
    });
    $('#dateto').datetimepicker({
                format: 'MM/DD/YYYY',
            });
    var url = '<?php echo base_url(); ?>';
    var formaction_path = '<?php echo $formaction_path; ?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        $(function() {
            $("#sendbtn").on('click', function(e) { //e.preventDefault();
                var formData = new FormData($("#demo-form2")[0]);
                if (!validator.checkAll($("#demo-form2")[0])) {} else {
                    var user_id = $("#user_id").val();
                    // alert(formaction_path);
                    $.ajax({
                        url: url + 'users/users/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                         var objData = jQuery.parseJSON(data);
                        ;
                        if (objData.msg == 'user_listing') {
                             // dz2.processQueue();
                            window.location = url + 'users/manage_deposite_acount/'+user_id;

                        }
                         else {
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>' + data + '</div></div>');
                              $("#result").fadeTo(2000, 500).slideUp(500, function(){
                                $("#result").slideUp(500);
                                });
                        }
                    });
                }
            });
        });
    });

    // $(document).ready(function(){
    function appendAmount(xp){
        $("#category_amount").val($('option:selected', xp).data('amount'));
    }
    // });
    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert").slideUp(500);
    });
</script>