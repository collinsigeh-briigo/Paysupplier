<div class="container">

<div class="card page-content">
<div class="row">
    <div class="col-md-12">

        <div class="row">
            <div class="col-9">
                <h2>My Payouts</h2>
            </div>
            <div class="col-3 text-right">
                <a class="btn btn-sm btn-secondary" href="<?php echo base_url(); ?>dashboard/">Home</a>
            </div>
        </div><br>

        <table class="table">
        <thead>
            <tr>
            <th scope="col">Transfer Details</th>
            <th scope="col">Recipient Account</th>
            <th scope="col">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($payouts as $payout){
                    echo '<tr><td>NGN '.$payout['amount'].' to '.$payout['recipient']['name'].'</td><td>'.$payout['recipient']['details']['bank_name'].' ('.$payout['recipient']['details']['bank_name'].')</td><td><small>'.$payout['createdAt'].'</small></td></tr>';
                }
            ?>
        </tbody>
        </table>

    </div>

</div>
</div>
</div>