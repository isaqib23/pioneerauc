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
        <h2>Security Deposit Adjustments<small></small></h2>
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
              <th>id</th>
              <th>Customer</th>
              <th>Email</th>
              <th>Mobile</th>
              <th>Adjustment Amount</th>
              <th>Created On</th>
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

  //range picker
  $("#rangeDate").daterangepicker({
    startDate: moment().startOf('day').add(-30, 'day'),
    endDate: moment().startOf('day'),
    locale: {
      format: 'YYYY-MM-DD'
    }
  });

  $("#filter").click();
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
    'url' : "<?= base_url('reports/security_adjustments_process'); ?>",
    'ajaxData' : filters,
    'filterFields': [],
    'dbFields': [
      {'data':'id'},
      {'data':'customer'},
      {'data':'email'},
      {'data':'mobile'},
      {'data':'adjustment_amount'},
      {'data':'adjustment_date'}
    ]
  });
});
</script>