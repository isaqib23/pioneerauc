<style type="text/css">
    .dz-image img{
    max-width: 150px;
    max-height: 150px;
    width: 100%;
    height: 100%;
    }
  .spinner-border-load
  {
    width:100%;
    height:100%;
    position:fixed;
    z-index:9999;
    background:url("<?php echo base_url(); ?>assets_admin/images/loading2.gif") no-repeat center center rgba(0,0,0,0.25)
  }
#myImg:hover {
  opacity: 0.7;
}
</style>
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">


<div class="spinner-border-load" style="display: none;"></div>
  <!-- <a type="button" href="<?php echo base_url().$back_url.$this->uri->segment(4); ?>"  class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</a> -->
  <a type="button" href="<?php echo base_url().$back_url; ?>"  class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</a>
            <div id="result"></div>

            <?php if($this->session->flashdata('error')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            </div>
            <?php }?>
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title collapse-link" style="cursor: pointer;">
      <h2>Images </h2>
      <ul class="nav navbar-right panel_toolbox">
        <li><a class=""><i class="fa fa-chevron-up"></i></a>
        </li> 
      </ul>
      <div class="clearfix"></div>
    </div>
        
    <div class="x_content">

      <div class="panebody">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Image</th>
              <th>Status</th>
              <th>Order</th>
            </tr>
          </thead>
          <tbody>
            <form method="post" name="images_order" id="image_form">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
              <?php
              if(isset($item_images) && !empty($item_images)){ 
                  $i = 0;
                  $item_id = $this->uri->segment(3);
                foreach ($item_images as $value) 
                {
                  // print_r($value['name']);
                  $i++;
              ?>
                  <tr>
                    <td scope="row"><?php echo $i; ?></td>
                    <td>
                      <span class="image">
                         <a href="#" class="pop"> 
                          <img id="myImg" style="width:50px;height: 50px;" src="<?php echo base_url().'uploads/items_documents/'.$item_id.'/'.$value['name'] ?>" class="" alt="<?php echo $value['name']; ?>" title="<?php echo $value['name']; ?>">
                         </a>
                      </span>
                      </td>
                    <td><?php echo $value['status']; ?></td>
                    <td>
                      <input type="text" name="images_orders[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" value="<?php echo $value['file_order']; ?>"> 
                    </td>
                  </tr>
              <?php
              } 
                }
              ?>
            </form>
          </tbody>
        </table>
        <button type="button" id="images_order" class="btn btn-primary">Update </button>
      </div>
      <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">              
            <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <img src="" class="imagepreview" style="width: 100%;" title="" >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- documents section  -->
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title collapse-link" style="cursor: pointer;">
            <h2>Private Documents </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class=""><i class="fa fa-chevron-up"></i></a>
              </li> 
            </ul>
            <div class="clearfix"></div>
        </div>
        
        <div class="x_content" style="display: none;">

            <div class="panbody">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                          <th>#</th>
                          <th>Doc</th>
                          <th>Status</th>
                          <th>Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="post" name="documents_order" id="documents_form">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                            <?php
                            if(isset($item_documents) && !empty($item_documents)){ 
                              $i = 0;
                              $item_id = $this->uri->segment(3);
                            foreach ($item_documents as $value) 
                            {
                              $i++; ?>
                                <tr>
                                    <td scope="row"><?php echo $i; ?></td>
                                    <td>
                                        <!-- <span class="image"> -->
                                          <span class="image" style="cursor: pointer;" data-toggle="modal" data-target="#ozDocModel" onclick="openDocumentViewer(this);" data-path="<?= base_url('uploads/items_documents').'/'.$item_id.'/'.$value['name'];?>">
                                        <!-- <a href="<?//= base_url('uploads/items_documents').'/'.$item_id.'/'.$value['name'];?>" download> -->
                                            <?php 
                                            $name_file_arr = explode('.', basename($value['name']));
                                            if(isset($value['name']) && (strtolower(end($name_file_arr)) == 'docx' || strtolower(end($name_file_arr)) == 'doc') )
                                            {
                                                ?>

                                            <img style="width:50px;height: 50px;" src="<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>" class="" alt="avatar">

                                            <?php }else if(isset($value['name']) && strtolower(end($name_file_arr)) == 'pdf' )
                                            { ?>
                                                
                                            <img style="width:50px;height: 50px;" src="<?= base_url('assets_admin/images/file-icons/pdf-icon.png'); ?>" class="" alt="avatar">

                                            <?php }else if(isset($value['name']))
                                            { ?>
                                                <img style="width:50px;height: 50px;" src="<?= base_url('assets_admin/images/file-icons/txt-icon.png'); ?>" class="" alt="avatar">
                                            <?php } ?>
                                        </a>
                                        <!-- </span> -->
                                        <?= (!empty($value['orignal_name'])) ? $value['orignal_name'] : $value['name']; ?>
                                    </td>
                                    <td><?php echo $value['status']; ?></td>
                                    <td><input type="text" name="documents_order[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" value="<?php echo $value['file_order']; ?>"> </td>
                                </tr>
                            <?php
                            } 
                              }
                            ?>
                        </form>
                    </tbody>
                </table>
            <button type="button" id="documents_order" class="btn btn-primary">Update </button>
            </div>
        </div> 
    </div>
</div>
  <!-- documents section  -->
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title collapse-link" style="cursor: pointer;">
            <h2>Public Documents </h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class=""><i class="fa fa-chevron-up"></i></a>
                </li> 
            </ul>
            <div class="clearfix"></div>
        </div>
        
        <div class="x_content" style="display: none;">
            <div class="panbody">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Doc</th>
                      <th>Status</th>
                      <th>Order</th>
                    </tr>
                  </thead>
                  <tbody>
                  <form method="post" name="test_documents" id="test_documents_form">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                    <?php
                    if(isset($item_test_documents) && !empty($item_test_documents)){ 
                        $i = 0;
                        $item_id = $this->uri->segment(3);
                      foreach ($item_test_documents as $value) 
                      {
                        $i++;
                    ?>
                        <tr>
                          <td scope="row"><?php echo $i; ?></td>
                          <td>
                            <span class="image" style="cursor: pointer;" data-toggle="modal" data-target="#ozDocModel" onclick="openDocumentViewer(this);" data-path="<?= base_url('uploads/items_documents').'/'.$item_id.'/'.$value['name'];?>">
                            <!-- <a href="<?//= base_url('uploads/items_documents').'/'.$item_id.'/'.$value['name'];?>" download> -->
                                <?php 
                                $name_file_arr = explode('.', basename($value['name']));
                                if(isset($value['name']) && (strtolower(end($name_file_arr)) == 'docx' || strtolower(end($name_file_arr)) == 'doc') )
                                { ?>
                                <img style="width:50px;height: 50px;" src="<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>" class="" alt="avatar">
                                <?php }else if(isset($value['name']) && strtolower(end($name_file_arr)) == 'pdf' )
                                { ?>
                                    <img style="width:50px;height: 50px;" src="<?= base_url('assets_admin/images/file-icons/pdf-icon.png'); ?>" class="" alt="avatar">
                                <?php }else if(isset($value['name']))
                                { ?>
                                    <img style="width:50px;height: 50px;" src="<?= base_url('assets_admin/images/file-icons/txt-icon.png'); ?>" class="" alt="avatar">
                                <?php } ?>
                            </a>
                            <!-- </span> -->
                            <?= (!empty($value['orignal_name'])) ? $value['orignal_name'] : $value['name']; ?>
                            </td>
                          <td><?php echo $value['status']; ?></td>
                          <td><input type="text" name="test_documents_order[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" value="<?php echo $value['file_order']; ?>"> </td>
                        </tr>
                    <?php
                    } 
                      }
                    ?>
                  </form>
                  </tbody>
                </table>
                <button type="button" id="test_documents_order" class="btn btn-primary">Update </button>
            </div>
        </div> 
    </div>
  </div>
</div>
 

  <div class="modal fade bs-example-modal-docViewer" id="ozDocModel" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">Document Viewer</h4>
              </div>
              <div id="DivIdToPrintDoc" class="modal-docViewer-body container-fluid">
                  <div class="col-md-10">
                      <iframe id="viewer" frameborder="0" scrolling="no" width="400" height="600" class="docUrl" src=""></iframe>
                  </div>
              </div>
              <div class="modal-footer">
              
              </div>
          </div>
      </div>
  </div>


<script>
      // enlarge image
    $(function() {
        $('.pop').on('click', function() {
          $('.imagepreview').attr('src', $(this).find('img').attr('src'));
          $('.imagepreview').attr('title', $(this).find('img').attr('title'));
          $('#imagemodal').modal('show');   
        });   
    });

    function openDocumentViewer(e){

      var docpath = $(e).data('path');
      var filePath = 'https://docs.google.com/viewer?url='+docpath+'&embedded=true';
      console.log(filePath);
       // https://docs.google.com/gview?url="url"&embedded=true
      $('.docUrl').attr('src', filePath);
    }
      

    $('.select2').select2();
    $('#send').hide();
    let itemId = '<?php echo $this->uri->segment(3);?>';
    var baseurl = '<?php echo base_url();?>uploads/items_documents/'+itemId+'/';
    
    var url = '<?php echo base_url();?>';

    const imagebutton = document.querySelector('#images_order');
    const docbutton = document.querySelector('#documents_order');
    const testdocbutton = document.querySelector('#test_documents_order');
    testdocbutton.addEventListener('click', sort_order_test_docs);
    imagebutton.addEventListener('click', sort_order);
    docbutton.addEventListener('click', sort_order_docs);

    function sort_order()
    {
             var id = itemId;
             var formData = $('#image_form').serialize();
               $.ajax({
                url: url + 'auction/update_files_order',
                type: 'POST',
                data: formData,
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                if (objData.msg == 'success') 
                {
                  $("#result").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');
                }
                else
                {
                   $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');
                }

                 
              window.setTimeout(function() 
              {
                $(".alert").fadeTo(500, 0).slideUp(500, function()
                {
                    $(this).remove(); 
                });
              }, 4000);
            });
    }

    function sort_order_docs()
    {
             var id = itemId;
             var formData = $('#documents_form').serialize();
               $.ajax({
                url: url + 'auction/update_files_order',
                type: 'POST',
                data: formData,
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                if (objData.msg == 'success') 
                {
                  $("#result").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');
                }
                else
                {
                   $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');
                }

                window.setTimeout(function() 
                {
                  $(".alert").fadeTo(500, 0).slideUp(500, function()
                  {
                      $(this).remove(); 
                  });
                }, 4000);
            });
    }

    function sort_order_test_docs()
    {
             var id = itemId;
             var formData = $('#test_documents_form').serialize();
               $.ajax({
                url: url + 'auction/update_files_order',
                type: 'POST',
                data: formData,
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                if (objData.msg == 'success') 
                {
                  $("#result").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');
                }
                else
                {
                   $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');
                }

                window.setTimeout(function() 
                {
                  $(".alert").fadeTo(500, 0).slideUp(500, function()
                  {
                      $(this).remove(); 
                  });
                }, 4000);
            });
    }

$(document).ready(function() {
        
});
   
</script>