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
        <h2>Item Report (Auction Wise)<small></small></h2>
        <div class="nav navbar-right">
          <!-- <a href="<?//= base_url('admin/projects/add'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add Projects</a> -->
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">

        <form method="post" id="rptfrm" action="">

          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

          <div class="form-group col-md-3 col-sm-6 col-xs-6">
            <div class="control-group">
              <div class="controls">
                <div class="input-prepend input-group">
                  <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                  <input type="text" name="rangeDate" id="rangeDate" class="form-control" value="01/01/2016 - 01/25/2016" />
                </div>
              </div>
            </div>
          </div>
          <div class="form-group col-md-3 col-sm-6 col-xs-6">
            <select name="auctionId" id="auction_list" class="form-control col-md-7 col-xs-12">
              <option selected value="">Select Auction</option>
            </select>
            <div class="auctionId-error text-danger"></div>
          </div>
          
          <div class="form-group col-md-3 col-sm-6 col-xs-6">
            <select name="item_sold_status" id="item_sold_status" class="form-control col-md-7 col-xs-12">
              <option selected value="all">All Statuses</option>
              <option value="sold">Sold</option>
              <option value="approval">On Approval</option>
              <option value="not">Available</option>
              <option value="return">Returned</option>
              <option value="not_sold">Expired</option>
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
              <th>Seller</th>
              <th>Mobile</th>
              <th>Email</th>
              <th>Item Name</th>
              <th>Status</th>
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

  $("#rangeDate").daterangepicker({
    startDate: moment().startOf('day').add(-30, 'day'),
    endDate: moment().startOf('day'),
    locale: {
      format: 'YYYY-MM-DD'
    }
  });

  var startDate =  $("#rangeDate").data('daterangepicker').startDate.format('YYYY-MM-DD');
  var endDate =  $("#rangeDate").data('daterangepicker').endDate.format('YYYY-MM-DD');
  get_auctions_between_dates(startDate,endDate);

  $('#rangeDate').on('apply.daterangepicker', function(ev, picker) {
    var startDate = picker.startDate.format('YYYY-MM-DD');
    var endDate = picker.endDate.format('YYYY-MM-DD');
    get_auctions_between_dates(startDate,endDate);
  });

  function get_auctions_between_dates(startDate, endDate){
    $.ajax({
      type: 'POST',
      url: "<?= base_url('reports/auctions_between_dates'); ?>",
      data: {'endDate':endDate,'startDate':startDate,"<?= $this->security->get_csrf_token_name(); ?>":"<?= $this->security->get_csrf_hash(); ?>"},
      success: function (result) {
        $("#auction_list").html(result);
      }
    });
  }
  
});

//search filters
$("#filter").on("click", function(event){
  event.preventDefault();

  $(".text-danger").html('');

  var validation = false;
  selectedInputs = ['auctionId'];
  validation = validateFields(selectedInputs);
  if(validation == false){
    return false;
  }

  if (validation == true) {
    var filters = $(this).closest("form").serializeArray();

    //dataTable builder
    var dtReports = dtBuilder({
      'tableId' : '#dt-reports',
      'orderColumn': 0,
      'order': 'ASC',
      'url' : "<?= base_url('reports/item_report_auction_wise_process'); ?>",
      'ajaxData' : filters,
      'filterFields': [],
      'dbFields': [
        {'data':'seller'},
        {'data':'mobile'},
        {'data':'email'},
        {'data':'item_name'},
        {'data':'status'}
      ]
    });
  }
});

    

    function cb(start, end) {
        var searched_date =  start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY');
        ajaxCallForSearchedData(searched_date);
    }
</script>