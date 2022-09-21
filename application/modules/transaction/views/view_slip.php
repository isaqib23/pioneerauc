<style type="text/css">
.zee {
  border: 0; box-shadow: none
}
</style>

<!-- Design Management start -->
<div class="page-title">
  <div class="title_left">
    <h3>Bank Deposit Management <small></small></h3>
  </div>
  <div class="title_right"></div>
</div>

<?php if($this->session->flashdata('error')){ ?>
  <div class="alert alert-danger alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error!</strong> <?= $this->session->flashdata('error'); ?>
  </div>
<?php } ?>

<?php if($this->session->flashdata('success')){ ?>
  <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Success!</strong> <?= $this->session->flashdata('success'); ?>
  </div>
<?php } ?>

<?php //print_r($list); ?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Deposit Detail <small></small></h2>
        <div class="nav navbar-right">
          <img id="loading" style="display: none;" src="<?= ASSETS_ADMIN.'images/load.gif'; ?>" />
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">

        <div method="post" class="form-horizontal form-label-left">

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Order ID</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="form-control col-md-7 col-xs-12 zee"><?= isset($row['id']) ? $row['id'] : ''; ?></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Customer Name</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="form-control col-md-7 col-xs-12 zee"><?= isset($row['username']) ? $row['username'] : ''; ?></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12"> Amount </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="form-control col-md-7 col-xs-12 zee"><?= isset($row['deposit_amount']) ? $row['deposit_amount'] : ''; ?></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Deposit Date</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="form-control col-md-7 col-xs-12 zee"><?= isset($row['deposit_date']) ? date('Y-m-d', strtotime($row['deposit_date'])) : ''; ?></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Created Date</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="form-control col-md-7 col-xs-12 zee"><?= isset($row['created_on']) ? date('Y-m-d', strtotime($row['created_on'])) : ''; ?></div>
            </div>
          </div>

          <!-- <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Documents</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div id="documents" class="dropzone"></div>
            </div>
          </div> -->

          <div class="" style="width: 400px; margin-left: 170px;" >
            <div class="panbody">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Bank Doc</th>
                  </tr>
                </thead>
                <tbody>
                  <td>
                    <?php $name_file_arr = explode('.', basename($row['name']));
                        if(isset($row['name']) && (strtolower(end($name_file_arr)) == 'docx' || strtolower(end($name_file_arr)) == 'doc') ||strtolower(end($name_file_arr)) == 'pdf' ) { ?>
                    <span class="image" style="cursor: pointer;" data-toggle="modal" data-target="#ozDocModel" onclick="openDocumentViewer(this);" data-path="<?= base_url('uploads/bank_slips').'/'.$row['name'];?>">
                        <?php 
                        $name_file_arr = explode('.', basename($row['name']));
                        if(isset($row['name']) && (strtolower(end($name_file_arr)) == 'docx' || strtolower(end($name_file_arr)) == 'doc') )
                        {
                          ?>

                        <img style="width:50px;height: 50px;" src="<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>" class="" alt="avatar">

                        <?php }else if(isset($row['name']) && strtolower(end($name_file_arr)) == 'pdf' )
                        { 
                            ?>
                        <img style="width:50px;height: 50px;" src="<?= base_url('assets_admin/images/file-icons/pdf-icon.png'); ?>" class="" alt="avatar">
                      <?php   } ?>
                      </span>
                      <?php }else if(isset($row['name']) && strtolower(end($name_file_arr)) == 'png' || strtolower(end($name_file_arr)) == 'jpg' || strtolower(end($name_file_arr)) == 'ico'|| strtolower(end($name_file_arr)) == 'jpeg' )
                        { 
                            ?>
                          <a href="#" class="pop"> 
                            <img id="myImg" style="width:50px;height: 50px;" src="<?php echo base_url().'uploads/bank_slips/'.$row['name'] ?>" class="" alt="<?php echo $row['name']; ?>" title="<?php echo $row['name']; ?>">
                          </a>
                      <?php } ?>
                    
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>



          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <a href="<?= base_url('transaction/bank_deposit_list'); ?>" class="btn btn-primary">Back</a>
              <?php if ($row['status'] == 0) { ?>
                <a href="<?= base_url('users/confirm_payment/').$row['id']; ?>" class="btn btn-info"> Confirm </a>
              <?php } ?>
              <?php if ($row['status'] == 0) { ?>
                <a href="<?= base_url('users/reject_payment/').$row['id']; ?>" class="btn btn-info"> Reject </a>
              <?php } ?>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>
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
    function openDocumentViewer(e){
      var docpath = $(e).data('path');
      var filePath = 'https://docs.google.com/viewer?url='+docpath+'&embedded=true';
      console.log(filePath);
       // https://docs.google.com/gview?url="url"&embedded=true
      $('.docUrl').attr('src', filePath);
    }

    $(function() {
        $('.pop').on('click', function() {
          $('.imagepreview').attr('src', $(this).find('img').attr('src'));
          $('.imagepreview').attr('title', $(this).find('img').attr('title'));
          $('#imagemodal').modal('show');   
        });   
    });
  </script>