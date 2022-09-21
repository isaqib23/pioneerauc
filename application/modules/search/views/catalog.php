<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discription</title>

    <style>

        .logo{
            height: 25px;
            }

        #discription{
                        font-family: Arial, Helvetica, sans-serif;
                        border-collapse: collapse;
                        width: 100%;
                    }

        #discription td, #customers th {
                                            padding: 0px;
                                            font-size: 10px;
                                        }

        #discription tr:nth-child(even){background-color: #f2f2f2;}

        /* #discription tr:hover {background-color: #ddd;} */

        #discription th{
                            padding-top: 12px;
                            padding-bottom: 5px;
                            text-align: left;
                        }

        .headind-row{
                            background-color: transparent;
                            border-bottom: 2px solid gainsboro;
                            font-size: 12px;
                            
                    }

        #discription tr {
                            border-bottom: 1px solid gainsboro;
                        }

        .car{
                height: 40px;
                padding: 5px;
            }

            .car-detail{
                display: flex;
            }
        .exp-btn{
            position: absolute;
            right: 2%;
            border: none;
            color: #E57417;
            background-color: #5C02B5;
            padding: 10px 20px;
            font-weight: 800;
            cursor: pointer;
        }

        td{
            padding-right: 5px !important;
        }

    </style>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

</head>
<body>
    <img class="logo" src="<?= NEW_ASSETS_USER; ?>new/images/Pioneer-logo.png" alt="">    
    <button class="exp-btn">Export</button>
    <table id="discription">
        <tr class="headind-row">
            <th>Lot</th>
            <th>Make & Type</th>
            <th>Vehicle Detail</th>
            <th>Model</th>
        </tr>

        <?php if($catalog){
            foreach ($catalog as $key => $item) {
                $itemName = json_decode($item['item_name']);
                $itemDetail = json_decode($item['item_detail']);
                //$itemImages = explode(',', $item['item_images']);
                //$imageFile = $this->db->get_where('files', ['id' => $itemImages[0]])->row_array();
                $itemMake = !empty($item['item_make_title']) ? json_decode($item['item_make_title']) : '' ;
                $itemModel = !empty($item['item_model_title']) ? json_decode($item['item_model_title']) : '';
            ?>
                <tr>
                    <td><?= $item['order_lot_no']; ?></td>
                    <td class="car-detail">
                        <!-- <img class="car" src="<?php //$imageFile['path'].$imageFile['name']; ?>" alt=""> -->
                        <p><b><?= $itemName->$language; ?></b> <!-- <br> <?//= !empty($itemMake->$language) ? $itemMake->$language : ''; ?> --></p>
                    </td>
                    <td><?= $itemDetail->$language; ?></td>
                    <td><?= !empty($itemModel->$language) ? $itemModel->$language : 'N/A'; ?></td>
                </tr>
            <?php }
        } ?>
    </table>
</body>
</html>

<script type="text/javascript">
    $("button").click(function(){
        $("#discription").table2excel({
            // exclude CSS class
            exclude:".noExl",
            name:"Worksheet Name",
            filename:"Catalog-"+"<?= $this->uri->segment(3); ?>",//do not include extension
            fileext:".xls" // file extension
        });
    });

</script>