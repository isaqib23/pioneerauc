<style> .pull-right{margin-right: 22px !important} </style>
<div class="page-title">
  <div class="title_left">
    <h3>Reports Center<small></small></h3>
  </div>
  <div class="title_right"></div>
</div>

<?php if($this->session->flashdata('reports_error')){ ?>
  <div class="alert alert-danger alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error!</strong> <?= $this->session->flashdata('reports_error'); ?>
  </div>
<?php } ?>

<?php if($this->session->flashdata('reports_success')){ ?>
  <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Success!</strong> <?= $this->session->flashdata('reports_success'); ?>
  </div>
<?php } ?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Live Auction Active Online Users <small></small></h2>
        <div class="nav navbar-right">
          <!-- <a href="<?//= base_url('admin/projects/add'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add Projects</a> -->
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">


        <form method="post" id="rptfrm" action="">

          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

          <div class="form-group col-md-3 col-sm-6 col-xs-6">
            <select name="auctionId" class="form-control col-md-7 col-xs-12">
              <option selected value="">Select Live Auction</option>
              <?php
              if($liveAuctions){
                foreach ($liveAuctions as $key => $liveAuction) {
                  $title = json_decode($liveAuction['title']);
                  ?>
                    <option value="<?= $liveAuction['id']; ?>"><?= $title->english.' ('.$liveAuction['registration_no'].')'; ?></option>
                  <?php
                }
              }
              ?>
            </select>
          </div>

          <div class="form-group col-md-3 col-sm-6 col-xs-6">
              <input type="submit" id="filter" class="btn btn-success" value="Filter" />
          </div>

        </form>


      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_content">
        <table id="dt-reports" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer Name</th>
              <th>Email</th>
              <th>Mobile</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url('assets_admin/js/zee.js');?>"></script>

<script>

$(document).ready(function(){
  //$("#filter").click();
});

//search filters
$("#filter").on("click", function(event){
  event.preventDefault();

  var filters = $(this).closest("form").serializeArray();

  //dataTable builder
  var dtReports = dtBuilder({
    'tableId' : '#dt-reports',
    'orderColumn': 0,
    'order': 'ASC',
    'url' : "<?= base_url('reports/live_auction_online_users_process'); ?>",
    'ajaxData' : filters,
    'filterFields': [],
    'dbFields': [
      {'data':'id'},
      {'data':'username'},
      {'data':'email'},
      {'data':'mobile'}
    ]
  });
});
</script>