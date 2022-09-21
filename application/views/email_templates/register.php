<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- <meta charset="utf-8"> -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style type="text/css">
        @media screen {
            @font-face {
                font-family: "Montserrat", Sans-Serif;
                font-style: normal;
                font-weight: 400;
                src: local('Montserrat Regular'), local('Lato-Regular'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format('woff');
            }
            body {
                /* font-family: "Lato", "Lucida Grande", "Lucida Sans Unicode", Tahoma, Sans-Serif;*/
                font-family: "Montserrat", Sans-Serif;
                background: rgb(250, 250, 250);
            }

            .startup-btns {
                font-size: 16px !important;
                color: rgb(255, 255, 255) !important;
                text-align: center;
                background-color: rgb(124, 10, 2);
                text-transform: uppercase;
                width: 400px !important;
                height: 40px !important;
                margin: 50px auto !important;
                padding: 10px 20px;
                line-height: 2.2;
                font-weight: 600;
                border-radius: 0px !important;
                border: 0px !important;
                text-decoration: none;
            }

            .startup-btns:hover, .startup-btns:focus {
                background-color: rgb(11, 57, 84);
                border: 0px !important;
                box-shadow: none !important;
            }

            .border-lite-grey {

                background-color: white;

            }

            .btn-md-grey {
                font-family: inherit !important;
                font-weight: 500 !important;
                line-height: 1.1 !important;
                color: white !important;
                border: 1px solid !important;

                background-color: #4E4E4E !important;
                width: 230px !important;
                height: 34px !important;
            }

            .btn-md-grey:hover {
                background-color: rgb(46, 220, 133);
                color: white;
                font-family: inherit;
                font-weight: 500;
                line-height: 1.1;
            }

            .heading-content {
                margin-top: 30px;
                text-align: center;
            }

            .footer-content p {
                font-size: 14px;
                color: #455561;
                font-weight: 600;
            }

            .email-footer-links {

                color: #455561;
                cursor: pointer;
            }

            .email-footer-links:hover {
                text-decoration: none;
            }

            .email-content p {
                font-weight: 400;
                color: #455561;
            }

            .email-content .activation-link {
                margin-bottom: 10px;
                font-size: 14px;
                text-decoration: none;
            }

            .email-content .activation-link:hover {
                color: #000;
            }

            .margin-top2 {
                margin: 20px 0px !important;
            }

            @media screen and (max-width: 736px) {

                .main-content {
                    width: 100% !important;
                }

                .btn-md-grey {
                    width: 100% !important;
                    width: 220px;
                }
            }

            .main-content {

                border: 1px solid #eaeeee;
                margin: 0px auto;
                max-width: 800px;
                width: 100%;
            }

            .email-content {

                padding: 10px 20px;
            }

            .bold {
                font-weight: 600 !important;
                font-size: 16px;
            }

            .message {
                margin-bottom: 20px;
            }

            .small {
                font-size: 15px;
                margin: 10px 0px !important;
            }

            .xs-small, .xs-small a {
                font-size: 13px;
            }

            .footer-content {
                text-align: center;
                margin: 0px auto;
                max-width: 800px;
                width: 100%;
                padding: 30px 0px;
            }
    </style>
</head>
<body>
<div class="container">
    <div class="row main-content border-lite-grey">
        <div class="col-md-12">
            <div class=" text-center heading-content">
                <h4 class="heading">
                </h4>
            </div>
            <div class="content email-content">
                <p class="bold">Hello ,Your Account activation OTP code is   <span class="bold message" style="font-size: 15px">{otp}</span></p>

                <p class="bold message">Please Copy and paste the activation code on the mobile Application</p>
<!--                    <br>Please click the Link below to-->
<!--                    activate your account.-->
<!--                <a href="{link_activation}" class="startup-btns btn">ACTIVATE YOUR ACCOUNT</a>-->
<!--                <br>-->
<!--                <p class="margin-top small">Or copy and paste this link into your browser:</p>-->
<!--                <p class=" small"><a href="{link_activation}" class="email-footer-links activation-link">{link_activation}</a>-->
<!--                </p>-->
                <p class=" small">Your username: {email}</p>
                <!-- <p class=" small">Please activate your account within 7 days of this email.</p><br> -->
                <p class=" small">Thanks,</p>
                <p class=" small">Pioneer Team</p>
                <!-- <p class=" small"><a href="{link_activation}" class="email-footer-links activation-link">{link_activation}</a></p><br> -->
                <p class=" xs-small">This is an automated message, please do not reply.</p>

            </div>
        </div>
    </div><!-- end of row-->
</div>

</body>
</html>