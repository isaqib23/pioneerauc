
<div class="main-wrapper account-page inventory-page">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>"><?= $this->lang->line('home')?></a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('customer/deposit'); ?>"><?= $this->lang->line('my_account_new')?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $this->lang->line('inventory');?></li>
        </ol>
        <h2><?= $this->lang->line('my_account_new')?></h2>
        <div class="account-wrapper">
            <?= $this->load->view('template/new/template_user_leftbar') ?>
            <div class="right-col">
                 <h3>
                   <?= $this->lang->line('inventory');?>
                    <!-- <a href="<?php echo base_url('sell-item');?>" data-toggle="tooltip" data-placement="top" title="<?= $this->lang->line('add_item'); ?>">
                       <span>Add Inventory</span>
                    </a> -->
                </h3>
                  
                <table class="customtable datatable table-responsive inventory-table" id="inventory_table" cellpadding="0" cellspacing="0" >
                    <thead>
                        <th class="hide-on-768" width="5%"><?= $this->lang->line('id');?></th>
                        <th class="name" class="pl-0"><?= $this->lang->line('name');?></th>
                        <th class="price"><?= $this->lang->line('price');?></th>
                        <th class="status" width="10%"><?= $this->lang->line('status');?></th>
                        <th class="action hide-on-768" data-orderable="false"><?= $this->lang->line('payment_status');?></th>
                    </thead>
                    <tbody>
                        <?php foreach ($inventory as $key => $value) : ?>
                            <tr id="<?= $value['id']; ?>">
                                <td class="hide-on-768"><?= $value['id']; ?></td>
                                <td class="pl-0">
                                    <div class="item">
                                        <div class="image">
                                            <?= $value['image']; ?>
                                        </div>
                                        <div class="desc hide-on-768">
                                            <h6><?= json_decode($value['name'])->$language; ?></h6>
                                        </div>
                                    </div>
                                </td>
                                <td class=""><?= number_format($value['price'] , 0, ".", ","); ?></td>
                                <td><?= $value['current_status']; ?></td>
                                <td class="hide-on-768"><?= $value['action']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div  class="add-invt">
                    <a href="<?php echo base_url('sell-item');?>" data-toggle="tooltip" data-placement="top" title="<?= $this->lang->line('add_item'); ?>">
                        <!-- <span class="material-icons">
                            add_circle
                        </span> -->
                       <span><?= $this->lang->line('add_inventory'); ?></span>
                    </a>
                </div>
            </div>
        </div>
        <?= $this->load->view('template/new/faq_footer') ?>
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
