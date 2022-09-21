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
        <h2>Customers With Bank Detail <small></small></h2>
        <div class="nav navbar-right">
          <!-- <a href="<?//= base_url('admin/projects/add'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add Projects</a> -->
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <table id="dt-reports" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer Name</th>
              <th>Email</th>
              <th>Mobile</th>
              <th>Bank</th>
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
  var filters =[{
      name: '<?= $this->security->get_csrf_token_name();?>', value:'<?= $this->security->get_csrf_hash();?>'
    }];
  console.log(filters)
  var dtReports = dtBuilder({
    'tableId' : '#dt-reports',
    'orderColumn': 0,
    'order': 'ASC',
    'url' : "<?= base_url('reports/customers_with_bank_detail_processing'); ?>",
    'ajaxData' : filters,
    'filterFields': ['username','email','mobile','role'],
    'dbFields': [
      {'data':'id'},
      {'data':'username'},
      {'data':'email'},
      {'data':'mobile'},
      {'data':'bank'}
    ],
    '<?= $this->security->get_csrf_token_name();?>': '<?= $this->security->get_csrf_hash();?>',
  });
});
</script>