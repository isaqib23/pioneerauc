<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">

<script  src="<?= NEW_ASSETS_USER;?>js/datatableCustom.js"></script>
<?= $this->load->view('template/auction_cat');?>
<div class="main gray-bg">
    <div class="row custom-wrapper deposit-h">
    	<?= $this->load->view('template/template_user_leftbar') ?>
        <div class="right-col">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="section-title text-left"><?= $this->lang->line('invoices_heading');?></h1>
                </div>
                <div class="col-sm-6">
                    <form class="customform">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                        <div class="wrap">
                            <input type="text" placeholder="<?= $this->lang->line('search');?>" class="form-control" name="searchval" id="searchval">
                            <div class="icon">
                                <img src="<?= base_url() ?>/assets_user/images/search-icon.png">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="deposit-wrapper">
                    <table class="display table_id " id="payments_table">
                        <thead>
                            <tr>
					            <th class="item_name"><?= $this->lang->line('item');?></th>
					            <th class="win_date"><?= $this->lang->line('win_date');?></th>
					            <th class="win_bid_price"><?= $this->lang->line('price');?></th>
					            <th class="due_payment"><?= $this->lang->line('due_payment');?></th>
					            <th class="payable_amount"><?= $this->lang->line('payment');?></th>
					            <th class="adjustment" data-orderable="false"><?= $this->lang->line('adjustment');?></th>
					            <th class="action" data-orderable="false"><?= $this->lang->line('action');?></th>
					        </tr>
                        </thead>
                        <!-- <tbody>
                            <tr>
                                <td>1</td>
                                <td>Card</td>
                                <td>12000</td>
                                <td>DR</td>
                                <td>2020-07-06</td>
                                <td>Approved</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Card</td>
                                <td>15000</td>
                                <td>DR</td>
                                <td>2020-07-08</td>
                                <td>Approved</td>
                            </tr>
                        </tbody> -->
                    </table>
            </div>
        </div>
    </div>
</div>

<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

<script type="text/javascript">      

	$(document).ready(function(){
		$('#payments_table').DataTable().destroy();

		let exportOptions = {'rows': {selected: true} ,columns: ['2,3,4']};
		let exportOptionsSelect = { modifier: { selected: null} };
		let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
		let dt1 = customeDatatable({
        // index zero must be csrf token
        '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
		'div' : '#payments_table',
		'url' : '<?= base_url()?>customer/get_payments',
		'orderColumn' : 0,
		'iDisplayLength' : 10,
		// 'dom' : 'fr<t><"bottom"lip><"clear">', // r<t><"bottom"lip><"clear">
		"dom": '<"top"i>rt<"bottom"flp>',
		'columnCustomeTypeTarget' : 2,
		'defaultSearching': false,
		'responsive': true,
		'rowId': 'id',
		'stateSave': true,
        'language' : '<?= ($language == "english") ? "" : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Arabic.json"; ?>',
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
		'customFieldsArray': ['searchval'],
		'dataColumnArray': [{'data':'item_name'},{'data':'win_date'},{'data':'win_bid_price'},{'data':'due_payment'},{'data':'payable_amount'},{'data':'adjustment'},{'data':'action'}]
		});
    	$('#searchval').keyup(function(){
    		dt1.draw();
    	});
	});

    // show error and success message
    <?php if($this->session->flashdata('success')){ ?>
        var message = "<?php echo $this->session->flashdata('success'); ?>";
        new PNotify({
            text: message,
            type: 'success',
            addclass: 'custom-success',
            title: "<?= $this->lang->line('success_'); ?>",
            styling: 'bootstrap3'
        });
     <?php } ?>

    <?php if($this->session->flashdata('error')){ ?>
        var message = "<?php echo $this->session->flashdata('error'); ?>";
        new PNotify({
            text: message,
            type: 'error',
            addclass: 'custom-error',
            title: "<?= $this->lang->line('error_'); ?>",
            styling: 'bootstrap3'
        });     
    <?php } ?>


    </script>