<div class="container">

<div class="card page-content">
<div class="row">
    <div class="col-md-12">

        <div class="row">
            <div class="col-9">
                <h2>Confirm Payment</h2>
            </div>
            <div class="col-3 text-right">
                <a class="btn btn-sm btn-secondary" href="<?php echo base_url(); ?>dashboard/">Home</a>
            </div>
        </div><br>

        <div>
            <div class="instruction">
                <h5>Caution:</h5>
                <p>You are about to transfer <b><?php echo("NGN ".$display_amount);?></b> to <b><?php echo $supplier_name; ?></b>.</p>
                <p>Being payment for <b><?php echo $reason; ?></b>.</p>
                <hr>
                <div class="confirmation">
                    <?php echo form_open('payments/send_payment'); ?>
                        <input type="hidden" name="reason" value="<?php echo $reason; ?>">
                        <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                        <input type="hidden" name="recipient" value="<?php echo $recipient; ?>">
                        <p><strong>Do you wish to continue?</strong></p>
                        <div class="form-group">
                            <span style="background-color: #f7f7f7; padding: 7px; border-radius: 3px;"><input id="confirm-box" type="checkbox" name="confirmaton" value="Confirm" required> <label for="confirm-box"> Yes, execute payment</label><span>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <input class="btn btn-primary" type="submit" value="Send">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group text-right">
                                    <a class="btn btn-light" href="<?php echo base_url(); ?>dashboard/">Cancel</a>
                                </div>
                            </div>
                        </div>
                    <?php form_close(); ?>
                </div>
            </div>
        </div>

    </div>

</div>
</div>
</div>