<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">


<link rel="stylesheet" href="<?= base_url(); ?>assets_user/tools/PhoneMask/css/intlTelInput.min.css">
<!-- Phone Mask -->
<script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/intlTelInput-jquery.min.js"></script>
<script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/utils.js"></script>

<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.62/jquery.inputmask.bundle.js"></script>
<style type="text/css">
    .contact-banner {
    padding: 20px 0px 1px 0px;
    text-align: center;
    margin: 0 0 10px;
}
.contact-us p {
    font-size: 18px;
    color: #2F2F2F;
    line-height: 1.6;
    padding: 19px;
    text-align:center;
}

.auction-bid-button {
    background: #5C02B5;
    width: 100%;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
}
.auction-bid-button-text {
    font-family: Montserrat;
    font-style: normal;
    font-weight: 600;
    font-size: 16px;
    line-height: 28px;
    text-align: center;
    letter-spacing: -0.18px;
    color: #FFFFFF;
    padding: 15px;
    background: #5c02b5;
    width: 100%;
    display: inline-block;
    text-align: center;
}
</style>
<div class="main-wrapper contact-us">
        <div class="contact-banner bg-primary">
            <h1><?= $this->lang->line('live_streaming_page')?></h1>
        </div>
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-2 col-lg-2 col-md-2">

                </div>
                <div class="col-xl-8 col-lg-8 col-md-8">
                    <div class="live-aution">
                            <?php if (isset($lvl) && !empty($lvl)) { ?>
                                <iframe width="100%" height="500" src="<?= $lvl; ?>" frameborder="0"
                                        allowfullscreen></iframe>
                            <?php } else { ?>
                                <div class="video">
                                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/liveauction2.png">
                                </div>
                                <div class="icons d-flex">
                                    <div class="video-play flex">
                                        <a href="#">
                                                <span class="material-icons">
                                                    play_arrow
                                                </span>
                                        </a>
                                    </div>
                                    <div class="label flex">Live</div>
                                </div>
                            <?php } ?>
                        </div>

                        <section id="mainContent"> 
      <p ><?= $this->lang->line('live_streaming_page_description')?></p>
        <a href="<?php echo base_url('customer/deposit'); ?>" class="btn-counter auction-bid-button-text"><?= $this->lang->line('bid_now')?>

    </a>

    </section>

                </div>
                <div class="col-xl-2 col-lg-2 col-md-2">
                    

                </div>
                
            </div>
        </div>
    </div>

    <!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
