<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<!-- <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet"> -->
<!-- <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet"> -->
<?= $this->load->view('template/auction_cat');?>
<div class="main gray-bg balance-page">
    <div class="row custom-wrapper">
    	<?= $this->load->view('template/template_user_leftbar') ?>
        <?php $limit = (isset($percentage_settings) && !empty($percentage_settings['value'])) ? $percentage_settings['value'] : '1'; ?>
        <div class="right-col">
            <h1 class="section-title text-left"><?= $this->lang->line('blnce');?></h1>
            <div class="current-balance">
                <h2><?= $this->lang->line('c_bid_limit');?></h2>
                <ul>
                    <li><?= $this->lang->line('total_d');?> :</li>
                    <li><?= $balance['amount']*1; ?> AED</li>
                </ul>
                <ul>
                    <li><?= $this->lang->line('bid_limit');?> :</li>
                    <li><?= $balance['amount']*$limit; ?> AED</li>
                </ul>
            </div>
        </div>
    </div>
</div>	
<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<!-- <script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script> -->

<script>
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
            title: "<?= $this->lang->line('error'); ?>",
            styling: 'bootstrap3'
        });		
	<?php } ?>

</script>

