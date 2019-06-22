<div class="container">

<div class="card page-content">
<div class="row">
    <div class="col-md-12">

        <div class="row">
            <div class="col-9">
                <h2>Bulk Transfer</h2>
            </div>
            <div class="col-3 text-right">
                <a class="btn btn-sm btn-secondary" href="<?php echo base_url(); ?>dashboard/">Home</a>
            </div>
        </div><br>

        <div>
            <div class="instruction">
                <h5>Hint:</h5>
                <ul>
                    <li>Enter the amount to transfer to each supplier in Kobo.</li>
                    <li>Then click on the "Continue" button.</li>
                </ul>
            </div><br>

            <?php echo form_open('payments/confirm_bulk_transfer/'); ?>
            <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">Supplier</th>
                <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($suppliers as $supplier){
                        echo '<tr><td>'.$supplier['name'].'</td><td><input type="text" class="form-control" name="'.$supplier['recipient_code'].'" placeholder="Enter amount in kobo"></td></tr>';
                    }
                ?>
            </tbody>
            </table>
            <div class="form-group text-center">
                <input class="btn btn-primary" type="submit" name="submit" value="Continue" />
            </div>
            <?php form_close(); ?>
        </div>

    </div>

</div>
</div>
</div>