<header class="front-screen">
            <div class="container">
                <div class="row">
                    <?php 
                       $date = date('m/d/Y');
                    ?>
                    <div class="col-sm-4">
                        <h1><?php echo $date?></h1>
                    </div>
                    <div class="col-sm-4">
                        <h1 class="live-text text-center">LIVE BID</h1>
                    </div>
                     <?php 
                       $time = date('h:i:s A');
                    ?>
                    <div class="col-sm-4">
                        <h1 class="text-right"><?php echo $time?></h1>
                    </div>
                </div>
            </div>
        </header>