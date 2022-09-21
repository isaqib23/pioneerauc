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
        <h2>Daily Transaction Report <small></small></h2>
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
                  <input type="text" name="invoiceDate" readonly id="selectDate" class="form-control" value="<?= date('Y-m-d') ?>" />
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
              <th>Customer Code</th>
              <th>Customer Name</th>
              <th>Customer Email</th>
              <th>Transaction Mode</th> 
              <th>Account Type</th>
              <th>Amount</th>
              <th>Transaction Date</th>
              <th>Transaction Type</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url('assets_admin/js/zee.js');?>"></script>

<script>

    $('#selectDate').datetimepicker({
        maxDate: "<?php echo date('Y-m-d H:i:s'); ?>",
        format: 'YYYY-MM-DD',
        useCurrent: true, //Important! See issue #1075
        ignoreReadonly : true
    });
    $('document').ready(function(){
        $("#filter").click();
    })

//search filters
$("#filter").on("click", function(event){
    event.preventDefault();

    var filters = $(this).closest("form").serializeArray();
    //console.log(filters);
    //return false;

    //dataTable builder
    var dtReports = dtBuilder({
        'tableId' : '#dt-reports',
        'orderColumn': 0,
        'order': 'ASC',
        'url' : "<?= base_url('reports/daily_transections_process'); ?>",
        'ajaxData' : filters,
        'filterFields': [],
        'dbFields': [
            {'data':'userCode'},
            {'data':'username'},
            {'data':'email'},
            {'data':'transection_mode'},
            {'data':'account_type'},
            {'data':'amount'},
            {'data':'trans_date'},
            {'data':'transection_type'},
        ]
    });
});
</script>