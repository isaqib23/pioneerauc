<style>
    .mdi-36px {
        font-size: 39px;
        margin-right: 10px
    }

    @media (min-width: 768px) {
        .text-md-left {
            text-align: left !important;
        }
    }

    @media (min-width: 768px) {
        .pr-md-3, .px-md-3 {
            padding-right: 1rem !important;
        }
    }

    @media (min-width: 576px) {
        .mb-sm-0, .my-sm-0 {
            margin-bottom: 0 !important;
        }
    }

    @media (min-width: 576px) {
        .mr-sm-0, .mx-sm-0 {
            margin-right: 0 !important;
        }

        .pl-0, .px-0 {
            padding-left: 0 !important;
        }

        .mb-2, .my-2 {
            margin-bottom: 0.5rem !important;
        }

        .mr-2, .mx-2 {
            margin-right: 0.5rem !important;
        }
    }

    @media (min-width: 768px) {
        .pr-md-3, .px-md-3 {
            padding-right: 1rem !important;
        }

        .pl-0, .px-0 {
            padding-left: 0 !important;
        }
    }

</style>

<div class="footer-wrapper">
    <div class="outer-footer">
        <div class="container">
            <div class="links-wrapper">
                <?php
                $itemCategories = $this->db->where('show_web', 'yes')->where('status', 'active')->order_by('sort_order', 'ASC')->get('item_category')->result_array();
                foreach ($itemCategories as $key => $itemCategory) {
                    $categoryTitle = json_decode($itemCategory['title']);
                    $itemSubCat = $this->db->get_where('item_subcategories', ['category_id' => $itemCategory['id']])->result_array();
                    ?>
                    <div class="links">
                        <h3>
                            <a href="<?php echo base_url('search/' . $itemCategory['slug'] ?? '#'); ?>"><?php echo $categoryTitle->$language; ?></a>
                        </h3>
                        <ul>
                            <?php
                            foreach ($itemSubCat as $key => $itemSubCatData) {
                                $subCategoryTitle = json_decode($itemSubCatData['title']); ?>
                                <li>
                                    <a href="<?php echo base_url('search/' . $itemCategory['slug'] ?? '#'); ?>"><?php echo $subCategoryTitle->$language; ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
    $contact_us_info = $this->db->get('contact_us')->row_array();
    ?>
    <footer>
        <div class="container">
            <div class="footer-links">
                <div class="links">
                    <h4><?= $this->lang->line('information') ?></h4>
                    <ul>
                        <li>
                            <a href="<?php echo base_url('home/auction_guide'); ?>"><?= ucwords($this->lang->line('guide_on_auction')); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('home/how_to_register'); ?>"><?= ucwords($this->lang->line('how_to_register')); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('home/how_to_deposit'); ?>"><?= ucwords($this->lang->line('how_to_deposite')); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('terms-conditions') ?>"><?= ucwords($this->lang->line('terms_and_conditions_new')); ?></a>
                        </li>
                    </ul>
                </div>
                <div class="links">
                    <h4><?= $this->lang->line('about_us_new') ?></h4>
                    <ul>
                        <li>
                            <a href="<?php echo base_url('visitor/about_us'); ?>"><?= $this->lang->line('about_us_new') ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('visitor/contact_us'); ?>"><?= ucwords($this->lang->line('contact_us_new')); ?></a>
                        </li>

                        <li>
                            <a href="<?php echo base_url('home/policy'); ?>"><?= ucwords($this->lang->line('privacy_policy')); ?></a>
                        </li>
                    </ul>
                </div>
                <div class="links">
                    <h4><?= $this->lang->line('quick_links') ?></h4>
                    <ul>
                        <li>
                            <a href="<?php echo base_url('visitor/gallery'); ?>"><?= $this->lang->line('new_media') ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('visitor/faqs'); ?>"><?= $this->lang->line('faq_new') ?></a>
                        </li>
                        <li>
                            <a href="<?= (!empty($headerCategoryId)) ? base_url('search/') . $headerCategoryId : 'javascript:void(0)'; ?>"><?= $this->lang->line('auctions') ?></a>
                        </li>

                    </ul>
                </div>
                <div class="links">
                    <h4><?= $this->lang->line('our_address') ?></h4>
                    <ul>
                        <li>
                            <a href="tel:<?= $contact_us_info['toll_free']; ?>"><?= $this->lang->line('toll_free') ?>:
                                <span dir="ltr"><?php echo $contact_us_info['toll_free']; ?></span></a>
                        </li>
                        <li>
                            <a href="<?php echo 'mailto:' . $contact_us_info['email']; ?>"><?= $this->lang->line('email_new') ?>
                                : <?php echo $contact_us_info['email']; ?></a>
                        </li>
                        <!-- <li><a href="javascript:void(0)"><?= $this->lang->line('business_hours') ?>: <?php echo $contact_us_info['business_hr']; ?> <br><?php echo $contact_us_info['working_hr']; ?></a>
                        </li> -->
                        <li><a href="javascript:void(0)"><?= $this->lang->line('business_hours') ?>
                                : <?php echo $contact_us_info['business_hr']; ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="newsletter">
                <div class="row align-items-center" style="justify-content: space-between">

                    <div class="col-md-5" ">


                        <form action="https://pioneerauctions.us14.list-manage.com/subscribe/post-json?u=86fd28cf6f70b17f0e19c4862&amp;id=a20c9109ed&c=?"
                              method="get" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                              class="validate" style="max-width:100%!important;justify-content: center;">
                            <input type="email" value="" name="EMAIL" class="email form-control"
                                   placeholder="<?= $this->lang->line('sub_to_email'); ?>" id="mce-EMAIL"
                                   placeholder="lovely-human@example.com" required="required">
                            <div style="display:none;position: absolute; left: -5000px;" aria-hidden="true"><input
                                        type="text" name="b_86fd28cf6f70b17f0e19c4862_a20c9109ed" tabindex="-1"
                                        value=""></div>
                            <button value="subscribe" name="subscribe" id="mc-embedded-subscribe"
                                    class="mc-button btn btn-default"
                                    type="submit"><?= $this->lang->line('sign_up') ?></button>

                        </form>
					</div>
                    <div class="col-md-4">
                        <div class="float-md-left text-center text-md-right" >
                            <a class="pl-0 pr-md-3 mr-2 mr-sm-0 mb-2 mb-sm-0"
                               href="https://apps.apple.com/us/app/pioneer-online-auctions/id1533962112"
                               target="_blank">
                                <img style="max-width: 40%;" src="<?= base_url('uploads/'); ?>app_pio.png"
                                     alt="Appstore">
                            </a>
                            <a class="pl-0 pr-md-3"
                               href="https://play.google.com/store/apps/details?id=com.pioneerauctions.app"
                               target="_blank">
                                <img style="max-width: 40%;" src="<?= base_url('uploads/'); ?>go_pio.png"
                                     alt="Googlepaly"> </a>
                        </div>
                    </div>
                    <?php $social = $this->db->get('social_links')->row_array(); ?>
                    <div class="col-md-3">
                        <ul class="social-links h-list justify-content-center">
                            <?php if (isset($social['google_plus']) && !empty($social['google_plus'])) : ?>
                                <li>
                                    <a href="<?php echo $social['google_plus']; ?>">
                                        <i class="fa fa-youtube-play"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (isset($social['facebook']) && !empty($social['facebook'])) : ?>
                                <li>
                                    <a href="<?php echo $social['facebook']; ?>">
                                        <i class="fa fa-facebook-square"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (isset($social['twitter']) && !empty($social['twitter'])) : ?>
                                <li>
                                    <a href="<?php echo $social['twitter']; ?>">
                                        <i class="fa fa-instagram"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (isset($social['linked_in']) && !empty($social['linked_in'])) : ?>
                                <li>
                                    <a href="<?php echo $social['linked_in']; ?>">
                                        <i class="fa fa-linkedin"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <div class="row align-items-center ">
                    <div class="col-md-8">
                        <p>© <?= date('Y'); ?> <?= $this->lang->line('pioneer_auction_right_reserved') ?> <span
                                    class="none-425">|</span> <a
                                    href="<?php echo base_url('home/policy'); ?>"><?= $this->lang->line('privacy_policy') ?></a>
                        </p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="logo">
                            <?php if ($language == 'english') {
                                $logo = 'logo_footer_english.svg';
                            } else {
                                $logo = 'logo_footer_arabic.svg';
                            } ?>
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/logo/<?= $logo ?>" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <a href="https://wa.me/971552647504" target="_blank" class="chat-icon flex" id="chat-icon">
        <img src="<?= NEW_ASSETS_USER; ?>/new/images/whatapplogo.png" alt="">
    </a>

    <!-- <a href="javascript:void(Tawk_API.toggle())" class="chat-icon flex" id="chat-icon">
        <img src="<?= NEW_ASSETS_USER; ?>/new/images/icons/chat-icon.svg" alt="">
    </a> -->

</div>


<!-- You are placing AED 10,000 Increment. -->
<div class="modal fade login-modal style-2" id="subscribe-result" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="increment-modal">
                    <h3><?= $this->lang->line('subscribe_success_message') ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Script Whatsapp -->
<!-- <script>
    var url = 'https://wati-integration-service.clare.ai/ShopifyWidget/shopifyWidget.js?35420';
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.src = url;
    var options = {
  "enabled":true,
  "chatButtonSetting":{
      "backgroundColor":"#4dc247",
      "ctaText":"",
      "borderRadius":"25",
      "marginLeft":"0",
      "marginBottom":"50",
      "marginRight":"50",
      "position":"right"
  },
  "brandSetting":{
      "brandName":"WATI",
      "brandSubTitle":"Typically replies within a day",
      "brandImg":"https://cdn.clare.ai/wati/images/WATI_logo_square_2.png",
      "welcomeText":"Hi there!\nHow can I help you?",
      "messageText":"Hello, I have a question about {{page_link}}",
      "backgroundColor":"#0a5f54",
      "ctaText":"Start Chat",
      "borderRadius":"25",
      "autoShow":false,
      "phoneNumber":"971552647504"
  }
};
    s.onload = function() {
        CreateWhatsappChatWidget(options);
    };
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
</script> -->
<!-- End Whatrsapp -->

<!--Start of Tawk.to Script-->
<!-- <script type="text/javascript">
    Tawk_API = Tawk_API || {};

    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/5d402989e5ae967ef80d7bbc/1f64ehoir';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
</script> -->


<!--End of Tawk.to Script-->
<!--End of Tawk.to Script-->

<script>
    $('#confirm-verification2').on('click', function (event) {
        // var phoneNumber2 = $('#nmbr').html();
        // alert(phoneNumber2);
        event.preventDefault();
        var validation = false;
        var language = '<?= $language; ?>';
        selectedInputs = ['codee'];
        validation = validateFields(selectedInputs, language);

        if (validation == true) {
            var code = $('input[name=codee]').val();
            var phoneNumber = $('input[name=verifyPhone]').val();
            $.ajax({
                type: 'post',
                url: '<?= base_url('home/verify_code'); ?>',
                data: {
                    'code': code,
                    'verifyPhone': phoneNumber,
                    '<?= $this->security->get_csrf_token_name();?>': "<?=$this->security->get_csrf_hash();?>"
                },
                success: function (msg) {
                    console.log(msg);
                    var response = JSON.parse(msg);

                    if (response.error == true) {
                        // $('#ragree-error').html(response.msg);
                        PNotify.removeAll();
                        new PNotify({
                            text: response.msg,
                            type: 'error',
                            addclass: 'custom-error',

                            title: "<?= $this->lang->line('error_'); ?>",
                            styling: 'bootstrap3'
                        });
                    }

                    if (response.success == true) {
                        PNotify.removeAll();
                        new PNotify({
                            text: response.msg,
                            type: 'success',
                            addclass: 'custom-success',

                            title: "<?= $this->lang->line('success_'); ?>",
                            styling: 'bootstrap3'
                        });
                        $('#verificationModel').modal('hide');
                        window.setTimeout(function () {
                            window.location.replace('<?= base_url('?loginUrl=loginFirst'); ?>');
                        }, 1001);
                    }
                }
            });
        }
    });

    function resendCode() {
        var phoneNumber = $('#nmbr').html();
        $.ajax({
            type: 'post',
            url: '<?= base_url('home/resend_mobile_code'); ?>',
            data: {
                'phone': phoneNumber,
                "<?= $this->security->get_csrf_token_name();?>": "<?=$this->security->get_csrf_hash();?>"
            },
            success: function (msg) {
                console.log(msg);
                var response = JSON.parse(msg);

                if (response.error == true) {
                    $('#valid-error').html(response.msg);
                }
                if (response.success == true) {
                    // window.location.replace('<?= base_url('customer/dashboard/  '); ?>' + response.user_id); 
                    // $('#resendModal').modal();
                    // $('#loginModal').hide();
                    PNotify.removeAll();
                    new PNotify({
                        text: response.msg,

                        type: 'success',
                        addclass: 'custom-success',
                        title: "<?= $this->lang->line('success_'); ?>",
                        styling: 'bootstrap3'
                    });

                }
                $('#verificationModel').modal('show');
            }
        });
    }

    $('#confirm-email').on('click', function (event) {
        event.preventDefault();
        var validation = false;
        var language = '<?= $language; ?>';
        selectedInputs = ['email2'];
        validation = validateFields(selectedInputs, language);
        if (validation == true) {
            $('#confirm-email').attr('disabled', true);
            var code = $('input[name=email2]').val();
            $.ajax({
                type: 'post',
                url: '<?= base_url('home/forgot_password'); ?>',
                data: {
                    'email2': code,
                    '<?= $this->security->get_csrf_token_name();?>': "<?=$this->security->get_csrf_hash();?>"
                },
                success: function (msg) {
                    console.log(msg);
                    var response = JSON.parse(msg);

                    if (response.error == true) {
                        PNotify.removeAll();
                        new PNotify({

                            text: response.msg,
                            type: 'error',
                            addclass: 'custom-error',
                            title: "<?= $this->lang->line('error_'); ?>",
                            styling: 'bootstrap3'
                        });
                        $('#confirm-email').attr('disabled', false);

                    }
                    else {
                        PNotify.removeAll();
                        new PNotify({

                            text: response.msg,
                            type: 'success',
                            addclass: 'custom-success',
                            title: "<?= $this->lang->line('success_'); ?>",
                            styling: 'bootstrap3'
                        });
                        $('#forgotPasswordModel').modal('hide');
                        $('#confirm-email').attr('disabled', false);
                    }

                }
            });
        }
    });


    $('#loginVerify').on('click', function (event) {

        event.preventDefault();
        var validation = false;
        var language = '<?= $language; ?>';
        selectedInputs = ['email', 'password1'];
        validation = validateFields(selectedInputs, language);

        if (validation == true) {
            var form = $(this).closest('form').serializeArray();

            //console.log(form);
            $.ajax({
                type: 'post',
                url: '<?= base_url('home/login_process'); ?>',
                data: form,
                success: function (msg) {
                    console.log(msg);
                    var response = JSON.parse(msg);

                    if (response.error == true) {
                        PNotify.removeAll();
                        new PNotify({

                            text: response.msg,
                            type: 'error',
                            addclass: 'custom-error',
                            title: "<?= $this->lang->line('error_'); ?>",
                            styling: 'bootstrap3'
                        });

                    }

                    if (response.status == false) {
                        $('#error-msg').html(response.msg);
                    }

                    if (response.error == false && response.status == true) {
                        var rurl = '<?= isset($_GET['rurl']) ? $_GET['rurl'] : ''; ?>';
                        if (rurl == '') {
                            window.location.reload(true);
                        } else {
                            window.location.replace(rurl);
                        }
                    }
                }
            });
        }
    });

    $(document).ready(function () {
        $("#user-phone").intlTelInput({
            allowDropdown: true,
            autoPlaceholder: "polite",
            placeholderNumberType: "MOBILE",
            formatOnDisplay: true,
            separateDialCode: true,
            // nationalMode: false,
            // autoHideDialCode: true,
            hiddenInput: "mobile",
            preferredCountries: ["ae", "sa"]
        });
    });


    $('#register-verify').on('click', function (event) {

        event.preventDefault();
        // $('#agree-error').html('');
        // if( ! $('#agree').is(':checked')){
        //   $('#agree-error').html("<?= $this->lang->line('please_indicate_you_accept_policy'); ?>");
        //   return false;
        // }

        var validation = false;
        var language = '<?= $language; ?>';
        selectedInputs = ['username', 'email1', 'phone', 'password'];
        validation = validateFields(selectedInputs, language);

        if (validation == true) {
            $('input[name=mobile]').val($("#user-phone").intlTelInput('getNumber').replace("+",""));
            var form = $(this).closest('form').serializeArray();
            console.log(form);


            $.ajax({
                type: 'post',
                url: '<?= base_url('home/register_user'); ?>',
                data: form,
                beforeSend: function () {
                    $("#loading").show();
                    // $("#register-verify").hide();
                    $("#register-verify").attr('disabled', true);
                },
                complete: function () {
                    $("#loading").hide();
                },
                success: function (msg) {

                    console.log(msg);

                    var response = JSON.parse(msg);
                    console.log(response);

                    if (response.error == true) {
                        // $('#error-msg').html('<div class="alert alert-danger alert-dismissible" id="valid-msgss"><button type="button" class="close" data-dismiss="alert" style="top: -14px;">&times;</button><strong><?= $this->lang->line("error_"); ?> </strong>'+ response.msg+' </div>');
                        $("#register-verify").removeAttr('disabled');
                        PNotify.removeAll();
                        new PNotify({

                            text: response.msg,
                            type: 'error',
                            addclass: 'custom-error',
                            title: "Try Again!",
                            styling: 'bootstrap3'
                        });
                        // window.location.reload(true);
                    }

                    if (response.error == false) {
                        new PNotify({
                            text: response.msg,

                            type: 'success',
                            addclass: 'custom-success',
                            title: "<?= $this->lang->line('success_'); ?>",
                            styling: 'bootstrap3'
                        });
                        // $('#exampleModal').modal();
                        // $('#nmbr').html(response.number);

                        // // console.info($('input[name=mobile]').val());
                        // console.log(response.codeGenerated);
                    }
                    if (response.error == false) {
                        $('#verificationModel').modal('show');
                        $('#registerModal').modal('hide');
                        $('#nmbr').html(response.number);
                        $('input#verifyPhone').val(response.number);
                    }
                }
            });
        }
    });

    function check_username() {
        var username = $('#email1').val();
        jQuery.ajax({
            type: 'POST',
            url: '<?= base_url('home/email_check'); ?>',
            data: 'email1=' + username,
            cache: false,
            success: function (response) {
                if (response.msg == 'success') {

                } else {
                    $('#error-msg').html('<div class="alert alert-danger alert-dismissible" id="valid-msgs"><button type="button" class="close" data-dismiss="alert">&times;</button><strong><?= $this->lang->line("email_already_registered"); ?></strong> </div>');
                }
            }
        });
    }

    function varify(e) {
        var mobile = $(e).data('mobile');
        $('#verificationModel').modal('show');
        $('#loginModal').modal('hide');
        $('#registerModal').modal('hide');
        $('#nmbr').html(mobile);
        $('input#verifyPhone').val(mobile);
    }

    //open forgot password popup
    $('#forgotPassword').on('click', function (event) {
        event.preventDefault();
        $('#forgotPasswordModel').modal('show');
        $('#registerModal').modal('hide');
        $('#loginModal').modal('hide');
    });

    /// register and login linking
    $('#loginToRegister, #registerClick').on('click', function (event) {
        event.preventDefault();
        $('#registerModal').modal('show');
        $('#loginModal').modal('hide');
    });

    $('#registerToLogin , #loginClick').on('click', function (event) {
        event.preventDefault();
        $('#loginModal').modal('show');
        $('#registerModal').modal('hide');
    });

    $('#loginModal').on('show.bs.modal', function (e) {
        $(".valid-error").html('');
        createCaptcha('recaptchaLogin');
    });

    $('#registerModal').on('show.bs.modal', function (e) {
        $(".valid-error").html('');
        createCaptcha('recaptchaRegister');
    });

    var onloadCallback = function () {
        var loginUrl = '<?= isset($_GET['loginUrl']) ? $_GET['loginUrl'] : ''; ?>';
        var user = "<?= ($this->session->userdata('logged_in')) ? TRUE : FALSE; ?>";
        if (!user && loginUrl) {
            $('#loginModal').modal('show');
        }
    };

    function createCaptcha(captchaType) {
        try {
            var recaptchaObj = grecaptcha.render(captchaType, {
                'sitekey': '<?= $this->config->item('user_captcha_site_key'); ?>',
                'size': "invisible",
                'callback': function () {
                    if (captchaType == 'recaptchaRegister') {
                        document.getElementById("register-verify").disabled = false;
                    }
                    if (captchaType == 'recaptchaLogin') {
                        document.getElementById("loginVerify").disabled = false;
                    }
                }
            });
            grecaptcha.execute(recaptchaObj);
        } catch (err) {
            grecaptcha.reset(recaptchaObj);
        }
    }

    function isCaptchaChecked(recaptchaObj) {
        return grecaptcha.getResponse(recaptchaObj).length !== 0;
    }


    $('.toggle-password').on('click', function () {
        $(this).toggleClass('fa-eye fa-eye-slash');
        let input = $($(this).attr('toggle'));
        if (input.attr('type') == 'password') {
            input.attr('type', 'text');
        }
        else {
            input.attr('type', 'password');
        }
    });

    //     $(".toggle-password").click(function() {

    //   $(this).toggleClass("fa-eye fa-eye-slash");
    //   var input = $($(this).attr("toggle"));
    //   if (input.attr("type") == "password") {
    //     input.attr("type", "text");
    //   } else {
    //     input.attr("type", "password");
    //   }
    // });

</script>

<script type="text/javascript">
    $(document).ready(function () {
        var $form = $('#mc-embedded-subscribe-form')
        if ($form.length > 0) {
            $('form button#mc-embedded-subscribe').bind('click', function (event) {
                if (event) event.preventDefault()
                register($form)
            })
        }
    })

    function register($form) {
        $('#mc-embedded-subscribe').val('Sending...');
        var _lang = '<?= $language; ?>';
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize(),
            cache: false,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            error: function (err) {
                alert('Could not connect to the registration server. Please try again later.')
            },
            success: function (data) {
                var language = '<?= $language; ?>';
                $('#mc-embedded-subscribe').val('subscribe')
                if (data.result === 'success') {
                    // Yeahhhh Success
                    console.log(data.msg)
                    $("#subscribe-result").modal('show');
                    $('#mce-EMAIL').val('')
                } else {
                    // Something went wrong, do something to notify the user.
                    console.log(data.msg)
                    var rpText = data.msg;
                    var regex = /0 - /i;

                    if (_lang == 'arabic') {
                        if (rpText.replace(regex, '') == 'Please enter a value') {
                            var msgs = '<?= $this->lang->line("subscribe_empty_error"); ?>';

                        } else {
                            var msgs = rpText.replace(regex, '');

                        }

                    } else {
                        if (rpText.replace(regex, '') == 'Please enter a value') {
                            var msgs = '<?= $this->lang->line("subscribe_empty_error"); ?>';

                        } else {
                            var msgs = rpText.replace(regex, '');

                        }

                    }


                    console.log(rpText.replace(regex, ''))

                    PNotify.removeAll();
                    new PNotify({
                        //  text: data.msg,
                        text: msgs,
                        type: 'error',
                        addclass: 'custom-error',

                        title: '<?= $this->lang->line("error"); ?>',
                        styling: 'bootstrap3'
                    });
                    //  $('#mce-EMAIL').css('borderColor', '#ff8282')
                    //  $('#subscribe-result').css('color', '#ff8282')
                    //  $('#subscribe-result').html('<p>' + data.msg.substring(4) + '</p>')
                }
            }
        })
    };

</script>


<script src="<?= NEW_ASSETS_USER; ?>js/zee.js"></script>

</body>
</html>
