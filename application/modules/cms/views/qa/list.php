
<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
</style>
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php echo $small_title; ?>
            </h2>
            <div class="clearfix"></div>
        </div>
        <?php if($this->session->flashdata('success')){ ?>
        <div class="alert">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('success');   ?>
          </div>
        </div>
        <?php }?>
        <div> 
          <a type="button" href="<?php echo base_url().'cms/add_faqs'; ?>"  class="btn btn-success"><i class="fa fa-plus"></i> Add</a>
          <div class="clearfix"></div>
        </div>
            <?php 
            if(isset($ques_ans_info) && !empty($ques_ans_info)) {
                $question = json_decode($ques_ans_info[0]['question']);
                $answer = json_decode($ques_ans_info[0]['answer']);
            }
            ?>

        <div class="x_content">
        <?php
        
        if(!empty($ques_ans_info))
        {

        ?>
        <!-- <table id="datatable-responsive_2" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%"> -->
        <table id="datatable" class="table table-striped" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Question English</th>
                    <th>Question Arabic</th>
                    <th>Answer English</th>
                    <th>Answer Arabic</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody class="content_auction_items">
            <?php 
                $j = 0;

                foreach($ques_ans_info as $value)
                {
                    $question = json_decode($value['question']);
                    $answer = json_decode($value['answer']);
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                      
                        <td><?php echo $question->english; ?></td>
                        <td><?php echo $question->arabic; ?></td>
                        <td><?php echo (isset($answer) && !empty($answer)) ? $answer->english : ''; ?></td>
                        <td><?php echo (isset($answer) && !empty($answer)) ? $answer->arabic  : ''; ?></td>
                        <td>
                            <a href="<?php echo base_url().'cms/edit_faqs/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                              
                            <button onclick="deleteRecord(this)" type="button" data-obj="ques_ans" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>cms/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button> 
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
    <?php 
        }
        else
        {
            echo "<h1>No Record Found</h1>";
        }
    ?>
        </div>
    </div>
</div>
