<script  src="<?= NEW_ASSETS_USER;?>js/datatableCustom.js"></script>
<?= $this->load->view('template/auction_cat');?>
<div class="main gray-bg">
    <div class="row custom-wrapper deposit-h bid">
    	<?= $this->load->view('template/template_user_leftbar') ?>
        <div class="right-col">
            <div class="row align-items-center">
                <div class="col-sm-4">
                    <h1 class="section-title text-left"><?= $this->lang->line('inventory');?></h1>
                </div>
                <div class="col-sm-8">
                    <form class="customform">
                    	<input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                        <div class="wrap">
                            <input type="text" placeholder="<?= $this->lang->line('search'); ?>" class="form-control"  name="invn_search" id="invn_search">
                            <div class="icon">
                                <img src="<?= NEW_ASSETS_IMAGES; ?>search-icon.png">
                            </div>
                        </div>
                        <select class="selectpicker" title="<?= $this->lang->line('select_cat'); ?>" id="inven_cat">
                            <option value=""><?= $this->lang->line('select_search');?></option>
							<?php foreach ($category as $key => $value) { ?>
							<?php $title = json_decode($value['title']); ?>
								<option value="<?= $value['id']; ?>"><?= $title->$language;  ?></option>
							<?php } ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="deposit-wrapper">
                <table id="inven_table" class="table_id display">
                    <thead>
                        <tr>
				            <th class=""><?= $this->lang->line('id');?></th>
				            <th data-orderable="false"></th>
				            <th class="name"><?= $this->lang->line('name');?></th>
				            <th class="price"><?= $this->lang->line('price');?></th>
				            <!-- <th class="type">Type</th> -->
				            <th class="status" data-orderable="false"><?= $this->lang->line('status');?></th>
				            <th class="action" data-orderable="false"></th>
				        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade max-width" id="paymentsModal-2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-title">
            <div class="modal-body">
                <h3><?= $this->lang->line('expense_detail');?></h3>
                 <div class="deposit-wrapper" id="payments">
                        <!--  -->
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	var token_name = '<?= $this->security->get_csrf_token_name();?>';
	var token_value = '<?=$this->security->get_csrf_hash();?>';
	  

	$(document).ready(function(){
		$('#inven_table').DataTable().destroy();

		let exportOptions = {'rows': {selected: true} ,columns: ['2,3,4']};
		let exportOptionsSelect = { modifier: { selected: null} };
			let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
			let dt1 = customeDatatable({
			// index zero must be csrf token
			'<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
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
      		'language' : '<?= ($language == "english") ? "" : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Arabic.json"; ?>',
		    'processingGif' : '<div class="loader-wrapper" ><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div> <img src="<?= NEW_ASSETS_IMAGES; ?>load.gif"> <div class="sk-rect2"></div> <div class="sk-rect3"></div> <div class="sk-rect4"></div> <div class="sk-rect5"></div> </div> </div>',
		    'buttonClassName' : 'btn-sm',
		    'columnCustomType' : '',
		    'customFieldsArray': ['inven_cat','invn_search'],
		    'dataColumnArray': [{'data':'id'},{'data':'image'},{'data':'name'},{'data':'price'},{'data':'status'},{'data':'action'}]
		});
		$('#inven_cat').change(function(){
			dt1.draw();
		});

		$('#invn_search').on('keyup', function(){
			dt1.draw();
		});
	});

	function show_details(e){
		var item_id = $(e).data('item-id');
		var sold_item_id = $(e).data('sold-item-id');
		$.ajax({
        type: 'post',
        url: '<?= base_url('customer/get_seller_charges'); ?>',
        data: {'item_id': item_id, 'sold_item_id': sold_item_id, [token_name]:token_value},
        // beforeSend: function(){
        //     $('#loading').html('<div class="loader-wrapper" ><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div> <div class="sk-rect2"></div> <div class="sk-rect3"></div> <div class="sk-rect4"></div> <div class="sk-rect5"></div> </div> </div>');
        // },
        success: function(msg){
			// console.log(msg);
			var response = JSON.parse(msg);

			if(response.msg == 'success'){
				$('#payments').html('');
				$('#payments').html(response.data);
				$('#paymentsModal-2').modal('show');

			
			}
        } 
      });
		// href="'.base_url('customer/seller_invoice/').$record->id.'"
	};


    </script>