<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<!-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script> -->

<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script  src="<?= NEW_ASSETS_USER;?>js/datatableCustom.js"></script>
<?= $this->load->view('template/auction_cat');?>
<div class="main gray-bg">
    <div class="row custom-wrapper deposit-h">
    	<?= $this->load->view('template/template_user_leftbar') ?>
        <div class="right-col">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="section-title text-left"><?= $this->lang->line('deposit_history');?>
                    <a id="security" href="<?= base_url('deposit');?>" class="btn btn-default sm"><?=$this->lang->line('add_deposit');?></a>
                         <script>
                                $( document ).ready(function() {
                                    $('#security').tooltip({
                                      title:'<?= $this->lang->line('add_deposit'); ?>',
                                      placement:'bottom'
                                    });
                                });
                        </script>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <form class="customform">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                        <div class="wrap">
                            <input type="text" placeholder="<?= $this->lang->line('search');?>" class="form-control" name="searchval" id="searchval">
                            <div class="icon">
                                <img src="<?= base_url() ?>assets_user/images/search-icon.png">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="deposit-wrapper">
                    <table class="display table_id " id="bids_table">
                        <thead>
                            <tr>
					            <th class="id">#</th>
					            <th class="payment_type"><?= $this->lang->line('payment_type');?></th>
					            <th class="amount"><?= $this->lang->line('amount');?></th>
					            <th class="account"><?= $this->lang->line('account');?></th>
					            <th class="created_on"><?= $this->lang->line('deposit_date');?></th>
					            <th class="status"><?= $this->lang->line('status');?></th>
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




<script type="text/javascript">
$(document).ready(function(){
	$('#bids_table').DataTable().destroy();

	let exportOptions = {'rows': {selected: true} ,columns: ['2,3,4']};
	let exportOptionsSelect = { modifier: { selected: null} };
	let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
	let dt1 = customeDatatable({
    // index zero must be csrf token
    '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
	'div' : '#bids_table',
	'url' : '<?= base_url()?>customer/get_deposits',
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
	'customFieldsArray': ['searchval'],
	'dataColumnArray': [{'data':'id'},{'data':'payment_type'},{'data':'amount'},{'data':'account'},{'data':'created_on'},{'data':'status'}]
	});
	
	$('#searchval').keyup(function(){
		dt1.draw();
	});
});


    </script>