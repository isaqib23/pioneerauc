<h4 class="inner-title"><?= $this->lang->line('upcoming_auction');?></h4>
<div class="slider">
    <?php
    if($auction_items){
        foreach ($auction_items as $key => $auction_item) {
            $images = explode(',', $auction_item['item_images']);
            $files = $this->db->get_where('files', ['id' => $images[0]])->row_array();
            $item_name = json_decode($auction_item['item_name']);
            $item_detail = json_decode($auction_item['item_detail']);
            ?>
            <div class="col">
                <div class="pro-box">
                    <div class="image">
                        <img src="<?= base_url('uploads/items_documents/').$auction_item['item_id'].'/'.$files['name']; ?>" alt="">
                    </div>
                    <div>
                        <h5>LOT <?= $auction_item['order_lot_no']; ?></h5>
                        <h6><?= strtoupper($item_name->$language); ?> </h6>
                        <p><?= substr($item_detail->$language, 0, 100).'...'; ?></p>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>

<script>
    $('.related-items .slider').slick({
        // infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        dots: false,
        responsive: [
            {
                breakpoint: 1101,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 993,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 676,
                settings: {
                    slidesToShow: 1,
                }
            },
        ]
    });
</script>