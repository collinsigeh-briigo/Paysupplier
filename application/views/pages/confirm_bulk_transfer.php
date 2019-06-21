<div class="container">

<div class="card page-content">
<div class="row">
    <div class="col-md-12">

        <div class="row">
            <div class="col-9">
                <h2>Confirm Bulk Transfer</h2>
            </div>
            <div class="col-3 text-right">
                <a class="btn btn-sm btn-secondary" href="<?php echo base_url(); ?>dashboard/">Home</a>
            </div>
        </div><br>

        <div>
            <div class="instruction">
                <h5>Caution:</h5>
                <ul>
                    <li>You are about make the following fund transfers.</li>
                    <li>Kindly confirm your action.</li>
                </ul>
                <hr>
                <div class="fund-transfers">
                <table class="table table-striped">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Transfer Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td>1</td>
                    <td>NGN 12,000.00 to OGORO FLOWERS</td>
                    </tr>
                    <tr>
                    <td>2</td>
                    <td>NGN 12,000.00 to OGORO FLOWERS</td>
                    </tr>
                </tbody>
                </table>
                </div>
                <hr>
                <div class="confirmation">
                    <?php echo form_open('payments/send_bulk_transfer/'); ?>
                        <p><strong>Do you wish to continue?</strong></p>
                        <div class="form-group">
                            <span style="background-color: #f7f7f7; padding: 7px; border-radius: 3px;"><input id="confirm-box" type="checkbox" name="confirmaton" value="Confirm"> <label for="confirm-box"> Yes, execute payment</label><span>
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