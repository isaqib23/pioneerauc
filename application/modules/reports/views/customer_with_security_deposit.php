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
        <h2>Customers With Security Deposits<small></small></h2>
        <div class="nav navbar-right">
          <!-- <a href="<?//= base_url('admin/projects/add'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add Projects</a> -->
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">


        <form method="post" id="rptfrm" action="">

          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

          <div class="form-group col-md-3 col-sm-6 col-xs-6">
            <select id="auctionId" onchange="auctionAjaxList(this);" name="auctionId" class="form-control col-md-7 col-xs-12">
              <option selected value="">Select Auction</option>
              <?php
              if($allAuctions){
                foreach ($allAuctions as $key => $allAuction) {
                  $title = json_decode($allAuction['title']);
                  ?>
                    <option value="<?= $allAuction['id']; ?>"><?= $title->english.' ('.$allAuction['registration_no'].')'; ?></option>
                  <?php
                }
              }
              ?>
            </select>
            <span class="valid-error text-danger auctionId-error"></span>
          </div>

          <div class="form-group col-md-3 col-sm-6 col-xs-6">
            <select  name="item_id" id="items_list" class="form-control col-md-7 col-xs-12">
              <option selected value="">Select Items</option>
            </select>
            <span class="valid-error text-danger items_list-error"></span>
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
              <th data-orderable="false">Security Deposit</th>
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

  var validation = false;
  selectedInputs = ['auctionId','item_id'];
  validation = validateFields(selectedInputs);

    if (validation == true) {
      var filters = $(this).closest("form").serializeArray();
      //dataTable builder
      var dtReports = dtBuilder({
        'tableId' : '#dt-reports',
        'orderColumn': 0,
        'order': 'ASC',
        'url' : "<?= base_url('reports/item_securityDeposits_process'); ?>",
        'ajaxData' : filters,
        'filterFields': [],
        'dbFields': [
          {'data':'id'},
          {'data':'username'},
          {'data':'email'},
          {'data':'mobile'},
          {'data':'security_deposit'}
        ]
      });
    }
});

function auctionAjaxList(e){
  var url = '<?php echo base_url();?>';
  var auction_id = document.getElementById("auctionId");
  var selectedId = auctionId.options[auctionId.selectedIndex].value;
  $.ajax({
    url: url + 'reports/items_deposit_against_auction',
      type: 'POST',
      data: {'auction_id':selectedId,"<?= $this->security->get_csrf_token_name(); ?>":"<?= $this->security->get_csrf_hash(); ?>"},
  }).then(function(resp){
    if (resp == true) {
      $("#items_list").html('vdf');
    }else{
      $("#items_list").html(resp);
    }
  });
}
</script>