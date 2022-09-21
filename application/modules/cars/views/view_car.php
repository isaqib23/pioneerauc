
    <div class="x_title">
        <h2>
            <?php echo $small_title; ?>
        </h2>
        
         <div class="clearfix"></div>
        <?php if ($this->session->flashdata('msg')) {?>
       
        <div class="alert">
            <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                  </button>
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        </div>
        <?php }?>                        
    </div>
    
    <div class="x_title">
        <div class="clearfix"></div>  
        <h1 style="color: black;">
               <b> <?php echo $car_list[0]['title']; ?></b>
        </h1>
       <div>
           <h4><b style="color: black;"> Mobile Number: </b>       <?php echo $car_list[0]['mobile_number']; ?></h4> 
           <h4><b style="color: black;"> Alternative Contact:</b>  <?php echo $car_list[0]['alternative_contact']; ?> 
                </h4>
           <h4><b style="color: black;"> Email:     </b>           <?php echo $car_list[0]['email']; ?></h4>   
           <h4><b style="color: black;"> Evaluation Date:</b>      <?php echo $car_list[0]['evaluation_date']; ?></h4>  
           <h4><b style="color: black;"> Location: </b>            <?php echo $car_list[0]['location']; ?></h4>  
       </div>
    </div>

    <div class="x_title">
                
                        
        <div>
            <h2>
                Car Info 
            </h2>
        </div>

        <div class="clearfix"></div> 
           <div>
               <h4><b style="color: black;"> Make: </b>       <?php echo $car_list[0]['make_title']; ?></h4> 
               <h4><b style="color: black;"> Model:</b>  <?php echo $car_list[0]['model_title']; ?> </h4>
               <h4><b style="color: black;"> Year:     </b>           <?php echo $car_list[0]['year']; ?></h4>   
               <h4><b style="color: black;"> Enigine Size:</b>      <?php echo $car_list[0]['engine_size_title']; ?></h4>  
               <h4><b style="color: black;"> Specs: </b>            <?php echo $car_list[0]['specs']; ?></h4>  
               <h4><b style="color: black;"> Mileage: </b>            <?php echo $car_list[0]['mileage_from']; ?></h4>  
               <h4><b style="color: black;"> Option: </b>            <?php echo $car_list[0]['options']; ?></h4>  
               <h4><b style="color: black;"> Paint: </b>            <?php echo $car_list[0]['paint']; ?></h4>  
           </div>
           
           <div>    
               <h4><b style="color: black;"> Status: </b>            <?php echo $car_list[0]['status']; ?></h4>  
           </div>
    </div>


