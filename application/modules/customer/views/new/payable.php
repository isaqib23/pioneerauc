<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">

<div class="main-wrapper account-page">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>"><?= $this->lang->line('home')?></a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('customer/deposit'); ?>"><?= $this->lang->line('my_account_new')?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $this->lang->line('my_payable')?></li>
        </ol>
        <h2><?= $this->lang->line('my_account_new')?></h2>
        <div class="account-wrapper">
            <?= $this->load->view('template/new/template_user_leftbar') ?>
            <div class="right-col">
                <h3><?= $this->lang->line('Payables_history');?></h3>
                <table class="customtable datatable table-responsive" id="inventory_table" cellpadding="0" cellspacing="0" >
                    <thead>
                        <th class="item_name" ><?= $this->lang->line('item');?></th>
                        <th width="15%" class="win_date hide-on-768"><?= $this->lang->line('win_date');?></th>
                        <th class="win_bid_price hide-on-768"><?= $this->lang->line('price');?></th>
                        <th width="10%" class="due_payment"><?= $this->lang->line('payable');?></th>
                        <th class="adjustment hide-on-768" data-orderable="false"><?= $this->lang->line('adjustment');?></th>
                        <th class="action hide-on-768" data-orderable="false"><?= $this->lang->line('action');?></th>
                    </thead>
                    <tbody>
                        <?php foreach ($won_items as $key => $value) : ?>
                            <tr>
                                <td><?= json_decode($value['item_name'])->$language; ?>
                                </td>
                                <td class="hide-on-768"><?= date('Y-m-d',strtotime($value['created_on'])); ?></td>
                                <td><?= $value['win_bid_price']; ?></td>
                                <td class="hide-on-768"><?= ($value['payment_status'] == 0) ? $value['payable_amount'] : '0'; ?></td>
                                <td class="hide-on-768"><?= $this->lang->line('security').':'.$value['adjusted_security'].'<br>'.$this->lang->line('deposit_small').':'.$value['adjusted_deposit']; ?></td>
                                <td class="hide-on-768"><?= $value['action']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?= $this->load->view('template/new/faq_footer') ?>
    </div>
</div>


<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

<script type="text/javascript">      

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