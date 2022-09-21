<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<main class="page-wrapper inventory">
		<div class="listing-wrapper">
			<?= $this->load->view('template/template_user_leftbar') ?>
			<div class="right-col">
				<section class="datatable">
					<div class="container padding-0">
						<div class="table-head">
							<div class="row align-items-center">
								<div class="col-md-6">
									<h1><?= $this->lang->line('inventory');?></h1>
								</div>
								<div class="col-md-6">
									<ul>
										<li>
											<div class="search-box">
												<input type="" class="form-control" placeholder="Search" name="invn_search" id="invn_search">
												<button type="button">
													<img src="<?= ASSETS_USER?>/images/search-icon.png">
												</button>
											</div>
										</li>
										<li>
											<select class="selectpicker sm" id="inven_cat">
												<option value="">Select Category</option>
												<?php foreach ($category as $key => $value) { ?>
												<?php $title = json_decode($value['title']); ?>
													<option value="<?= $value['id']; ?>"><?= $title->$language;  ?></option>
												<?php } ?>
											</select>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<table class="bids-table data-table2 with-checkbox table table-striped dt-responsive nowrap" id="inven_table">
						    <thead>
						        <tr>
						        	<!-- <th></th> -->
						            <th class=""> <?= $this->lang->line('id');?></th>
						            <th data-orderable="false"></th>
						            <th class="name"><?= $this->lang->line('name');?></th>
						            <th class="price"><?= $this->lang->line('price');?></th>
						            <!-- <th class="type">Type</th> -->
						            <th class="status"><?= $this->lang->line('status');?></th>
						            <th class="empty"></th>
						        </tr>
						    </thead>
						 <!--    <tbody>
						        <tr>
						        	<td></td>
						            <td class="counting">1</td>
						            <td class="name" >
						            	<div class="profile">
						            		<div class="image"><img src="assets/images/our-team.png"></div>
						            		<div class="desc">Chevrolet Camaro 2013</div>
						            	</div>
						            </td>
						            <td>10,0000 ADED</td>
						            <td>Timed</td>
						            <td class="green-text"><b>Won</b></td>
						            <td><i class="fa fa-ellipsis-h"></i></td>
						        </tr>
						        
						    </tbody> -->
						</table>
					</div>	
				</section>
			</div>
		</div>
	</main>

	<script type="text/javascript">
      

		// $(document).ready(function(){

    	let exportOptions = {'rows': {selected: true} ,columns: ['2,3,4']};
    	let exportOptionsSelect = { modifier: { selected: null} };
      	let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
      	let dt1 = customeDatatable({
      		'div' : '#inven_table',
      		'url' : '<?= base_url()?>customer/get_inventory',
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
		    'processingGif' : '<div class="loader-wrapper" ><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div> <div class="sk-rect2"></div> <div class="sk-rect3"></div> <div class="sk-rect4"></div> <div class="sk-rect5"></div> </div> </div>',
		    'buttonClassName' : 'btn-sm',
		    'columnCustomType' : '',
		    'customFieldsArray': ['inven_cat','invn_search'],
		    'dataColumnArray': [{'data':'id'},{'data':'image'},{'data':'name'},{'data':'price'},{'data':'status'},{'data':'action'}]
		});
  		// });

      $('#inven_cat').change(function(){
      	dt1.draw();
      });

      $('#invn_search').on('keyup', function(){
      	dt1.draw();
      });

    </script>