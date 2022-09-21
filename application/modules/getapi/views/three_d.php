<?php
header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <title>Pioneer Auction</title>
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        canvas {
            max-height: 100vh;
            object-fit: contain;
        }
    </style>
</head>
<body>
<div
    class="cloudimage-360"
    data-folder="<?php echo base_url(); ?>uploads/items_threed/<?php echo $item_id ?>/"
    data-filename="{index}.jpg"
    data-amount="36"
    data-spin-reverse
    data-hide-360-logo
></div>
<script src="https://cdn.scaleflex.it/plugins/js-cloudimage-360-view/2.6.0/js-cloudimage-360-view.min.js"></script>
<!-- https://demos.evox.com/SquareSpaceWeb/13227/images/img_0_0_31.jpg -->
</body>
</html>
