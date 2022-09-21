<?= $this->load->view('template/auction_cat');?>
<div class="main gray-bg notification">
    <div class="row custom-wrapper">
        <?= $this->load->view('template/template_user_leftbar') ?>
        <div class="right-col">
            <h1 class="section-title"><?= $this->lang->line('notification');?></h1>

            <?php 
            if (isset($list) && !empty($list)) :
             foreach ($list as $key => $value) : ?>
                <div class="list">
                    <h2><?= ucfirst($value['type']); ?></h2>
                    <h3><?= ucfirst($value['message']); ?></h3>
                    <p><?= $value['created_on']; ?></p>
                </div>

            <?php 
             endforeach;?>
             <?php else :?>
                <div>
                    <h2><?= $this->lang->line('no_record');?></h2>
                </div>
                <?php endif;?>
                <!-- <div class="list">
                    <h2>Sold</h2>
                    <h3>Your item is sold out</h3>
                    <p>2020-01-21 00:00:00</p>
                </div>
                <div class="list">
                    <h2>Sold</h2>
                    <h3>Your item is sold out</h3>
                    <p>2020-01-21 00:00:00</p>
                </div> -->
        </div>
    </div>
</div>			