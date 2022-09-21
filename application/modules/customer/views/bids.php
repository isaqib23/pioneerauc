
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<script  src="<?= NEW_ASSETS_USER;?>js/datatableCustom.js"></script>
<?= $this->load->view('template/auction_cat');?>
<div class="main gray-bg">
    <div class="row custom-wrapper deposit-h bid">
    	<?= $this->load->view('template/template_user_leftbar') ?>
        <div class="right-col">
            <div class="row align-items-center">
                <div class="col-sm-4">
                    <h1 class="section-title text-left">MY BIDS</h1>
                </div>
                <div class="col-sm-8">
                    <div class="customform">
                        <div class="wrap">
                            <input type="text" placeholder="Search" class="form-control"  name="bids" id="bids">
                            <div class="icon">
                                <img src="<?= NEW_ASSETS_IMAGES; ?>search-icon.png">
                            </div>
                        </div>
                        <select class="selectpicker" title="Select Category" id="bids_cat">
                            <option value="">Select Category</option>
							<?php foreach ($category as $key => $value) { ?>
							<?php $title = json_decode($value['title']); ?>
								<option value="<?= $value['id']; ?>"><?= $title->$language;  ?></option>
							<?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="deposit-wrapper">
                    <table id="bids_table" class="table_id display">
                        <thead>
                            <tr>
					            <th class="id"> Id</th>
					            <th data-orderable="false"></th>
					            <th class="name">Name</th>
					            <th class="bid_amount">Price</th>
					            <!-- <th class="type">Type</th> -->
					            <th class="status" data-orderable="false">Status</th>
					            <th class="action" data-orderable="false"></th>
					            <!-- <th class="empty"></th> -->
					        </tr>
                        </thead>
                    </table>
            </div>
        </div>
    </div>
</div>
<!-- <main class="page-wrapper inventory">
	<div class="listing-wrapper">
         <?//= $this->load->view('template/template_user_leftbar') ?>
		<div class="right-col">
			<section class="datatable">
				<div class="container padding-0">
					<div class="table-head">
						<div class="row align-items-center">
							<div class="col-md-6">
								<h1>MY BIDS</h1>
							</div>
							<div class="col-md-6">
								<ul>
									<li>
										<div class="search-box">
											<input type="" class="form-control" placeholder="Search" name="bids" id="bids">
											<button type="button">
												<img src="<?//= ASSETS_USER?>/images/search-icon.png">
											</button>
										</div>
									</li>
									<li>
										<select class="selectpicker sm" id="bids_cat">
											<option value="">Select Category</option>
											<?php //foreach ($category as $key => $value) { ?>
											<?php// $title = json_decode($value['title']); ?>
												<option value="<?//= $value['id']; ?>"><?= $title->$language;  ?></option>
											<?php //} ?>
										</select>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<table class="bids-table data-table table table-striped dt-responsive nowrap" id="bids_table">
					    <thead>
					        <tr>
					            <th class="id"> Id</th>
					            <th data-orderable="false"></th>
					            <th class="name">Name</th>
					            <th class="price">Price</th>
					            <th class="status">Status</th>
					            <th class="action" data-orderable="false"></th>
					        </tr>
					    </thead>
					</table>
				</div>	
			</section>
		</div>
	</div>
</main> -->

<script type="text/javascript">
      

$(document).ready(function(){

	$('#bids_table').DataTable().destroy();

	let exportOptions = {'rows': {selected: true} ,columns: ['2,3,4']};
	let exportOptionsSelect = { modifier: { selected: null} };
	let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
	let dt1 = customeDatatable({
	'div' : '#bids_table',
	'url' : '<?= base_url()?>customer/get_bids',
	'orderColumn' : 0,
	'iDisplayLength' : 10,
	// 'dom' : 'fr<t><"bottom"lip><"clear">', // r<t><"bottom"lip><"clear">
	"dom": '<"top"i>rt<"bottom"flp>',
	'columnCustomeTypeTarget' : 2,
	'defaultSearching': false,
	'responsive': true,
	'rowId': 'id',
	'stateSave': true,
	'lengthMenu': [[ 10, 25, 50, 100, 150], [10, 25, 50, 100, 150]], // [5, 10, 25, 50, 100], [5, 10, 25, 50, 100]
	'order' : 'desc',
	'selectStyle' : 'multi',
	'collectionButtons' : dtButtons,
	// 'exportOptionsSelect' : exportOptionsSelect,
	'processing' : true,
	'serverSide' : true,
	'processingGif' : '<div class="loader-wrapper" ><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div> <img src="<?= NEW_ASSETS_IMAGES; ?>load.gif"> <div class="sk-rect2"></div> <div class="sk-rect3"></div> <div class="sk-rect4"></div> <div class="sk-rect5"></div> </div> </div>',
	'buttonClassName' : 'btn-sm',
	'columnCustomType' : '',
	'customFieldsArray': ['bids_cat','bids'],
	'dataColumnArray': [{'data':'id'},{'data':'image'},{'data':'name'},{'data':'bid_amount'},{'data':'status'},{'data':'action'}]
	});

	$('#bids_cat').change(function(){
		dt1.draw();
	});

	$('#bids').keyup(function(){
  		var x = event.which || event.keyCode;
  		if(x === 13){
		  	return;
		}
		dt1.draw();
	});
});

    </script>