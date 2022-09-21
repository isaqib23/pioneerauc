<!-- Cheque fields -->
<div id="payment">
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cheque_date">Cheque Date <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class='input-group'>
                <input type="text" class="form-control" value="<?php if(isset($data) && !empty($data)){ echo $data['cheque_date'];}?>" name="cheque_date" id="cheque_date" readonly="" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <div class="cheque_date-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cheque_title">Cheque Title <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="cheque_title" 
                id="cheque_title"
                value="<?php if(isset($data) && !empty($data)){ echo $data['cheque_title'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="cheque_title-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cheque_type">Cheque Type <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="cheque_type" 
                id="cheque_type"
                 value="<?php if(isset($data) && !empty($data)){ echo $data['cheque_type'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="cheque_type-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cheque_number">Cheque Number <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="cheque_number" 
                id="cheque_number"
                value="<?php if(isset($data) && !empty($data)){ echo $data['cheque_number'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="cheque_number-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account_title">Account Title <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="account_title" 
                id="account_title"
                value="<?php if(isset($data) && !empty($data)){ echo $data['account_title'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="account_title-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account_number">Account Number <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="account_number" 
                id="account_number"
                value="<?php if(isset($data) && !empty($data)){ echo $data['account_number'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="account_number-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name">Bank Name <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="bank_name" 
                id="bank_name"
                value="<?php if(isset($data) && !empty($data)){ echo $data['bank_name'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="bank_name-error text-danger"></div>
        </div>
    </div>
</div>

<?php if (isset($data)) { ?>
    <script>
        $( document ).ready(function() {
            $('#payment input').attr('readonly', 'readonly');
        });
    </script>
<?php }else{ ?>
    <script>
        /*selectedInputs = ['auction_id','auction_item_id','deposit','deposit_mode','cheque_date',
        'cheque_title','cheque_type','cheque_number','account_title','account_number','bank_name'];*/
        selectedInputs = ['auction_id','auction_item_id','deposit','deposit_mode','cheque_date',
        'cheque_title'];
        $('#cheque_date').datetimepicker({
                format: 'YYYY-MM-DD',
                ignoreReadonly : true
        });
    </script>
<?php } ?>


