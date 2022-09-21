<!-- Design Management start -->
<div class="page-title">
  <div class="title_left">
    <h3>Auction Management <small></small></h3>
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

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Closed Auction List <small></small></h2>
        <!-- <div class="nav navbar-right">
          <a href="<?= base_url('admin/email/add'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add Email Template</a>
        </div> -->
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <table id="datatable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th data-orderable="false">
                  <input type="checkbox" id="check-all" class="flat ozproceed_check" name="table_records">
              </th>
              <th>Name</th>
              <th>Auction Type</th>
              <th>Auction Category</th>
              <th>Start Date</th>
              <th>Expiry Date</th>
              <th>Status</th>
              <th data-orderable="false">Actions</th>
          </tr>
          </thead>
          <tbody>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>