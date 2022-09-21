
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pioneer Page</title>
</head>
<style>
body{
        font-family: sans-serif;

    }
    @media print {
  .main-container {page-break-after: always;}
}
    .uperdiv{
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }
    .main-container{
        border: 2px solid;
        width: 805px;
        height: 98vmax;
        margin-bottom: 15px;
    }
    .pioneer-auction{
        border-bottom: 1px solid;
    text-align: center !important;
    }
    .pioneer-auction h1{
        font-size: 46px;
        font-weight: bolder;
        margin: 10px;
    }
    .details{
        /* display: flex; */
        margin-bottom: 30px;
    }
    .lot{
        display: flex;
        border-bottom: 1px solid;
        align-items: center;
        justify-content: center;
    }
    .lot h2{
 
         margin: 0;
         width: 20%;
         font-size: 55px;
         text-align: center;
    }
    .lot h1{

        font-size: 140px;
        margin: 0;
        padding-left: 82px;
    }
    .div1 li{
        display: flex;
    }
    .div1 li h3{
        width: 30%;
        margin: 10px 0px;
    }
    .sold h2{
        border: 1px solid;
        text-align: center;
        padding:10px;
        margin: 0px 5px;
    }
    .remarks{
        display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    }
    .remarks h2{
        text-align: center;
        padding: 0 20px;
    }
    .footer-text p{
        text-align: center;
        line-height: 1.5;
        border-top: 1px solid;
        padding-top: 10px;
        font-size: 14px;
        padding: 10px 20px 10px 20px;
    }
</style>
<body>
    <div class="uperdiv">
        <div class="main-container">
            <div class="pioneer-auction">
                <h1>Pioneer Auctions</h1>
            </div>
            <div class="lot">
            <h2>LOT:<?php echo $auction_item_row[0]['order_lot_no'];?></h2>
            </div>
                <div class="details">
                    <ul class="div1">
                
                        <li>
                            <h3>Make :</h3>
                            <h3><?php echo (isset($item_row[0]['make_name']) && !empty($item_row[0]['make_name'])) ? json_decode($item_row[0]['make_name'])->english : 'N/A' ?></h3>
                        </li>
                        <li>
                            <h3>Model:</h3>
                            <h3><?php echo (isset($item_row[0]['model_name']) && !empty($item_row[0]['model_name'])) ? json_decode($item_row[0]['model_name'])->english : 'N/A' ?></h3>
                        </li>
                        <!-- <li>
                            <h3>Details:</h3>
                            <h3><?php //echo (isset($item_row[0]['detail']) && !empty($item_row[0]['detail'])) ?  json_decode($item_row[0]['detail'])->english  : 'N/A' ?></h3>
                        </li> -->
                        <li>
                            <h3>Reg No:</h3>
                            <h3><?php echo (isset($item_row[0]['registration_no']) && !empty($item_row[0]['registration_no'])) ? $item_row[0]['registration_no'] : 'N/A' ?></h3>

                        </li>
                        <li>
                            <h3>Year:</h3>
                            <?php if(!empty($item_row[0]['year'])) : ?>
                            <h3><?php echo (isset($item_row[0]['year']) && !empty($item_row[0]['year'])) ? $item_row[0]['year'] : '' ?></h3>
                            <?php endif; ?>
                        </li>
                        <li>
                            <h3>VIN:</h3>
                            <h3><?php echo (isset($item_row[0]['vin_number']) && !empty($item_row[0]['vin_number'])) ? $item_row[0]['vin_number'] : 'N/A' ?></h3>

                        </li>
                        <li>
                            <h3>Mileage:</h3>
                            <h3><?php echo (isset($item_row[0]['mileage']) && !empty($item_row[0]['mileage'])) ? $item_row[0]['mileage'] : 'N/A' ?></h3>
                        </li>

                    </ul>
                
                </div>
            <div class="sold">
                <h2>SOLD AS SEEN</h2>
            </div>
            <div class="remarks">
                <h3><b>Remarks<b></h3>
                <h2><?php echo (isset($item_row[0]['detail']) && !empty($item_row[0]['detail'])) ? json_decode($item_row[0]['detail'])->english: '' ?></h2>
            </div>
            <div class="footer-text">
                <p>Pioneer Auctions is the leading auction house in the United Arab Emirates <br>for cars, vehicles, machinery, property and charity. Established in Dubai, UAE in 2008 ...</p>
            </div>
        </div>
    </div>
    
</body>
</html>
