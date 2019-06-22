<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/custom.css">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
    
    <title>Paysupplier</title>
  </head>
  <body>
<section class="bg-light">
<div class="container">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Paysupplier</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
  <?php if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'Yes'){ ?>  
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url(); ?>">Dashboard</span></a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Suppliers
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?php echo base_url(); ?>suppliers/create/">Add Supplier</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo base_url(); ?>suppliers/">My Suppliers</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Payments
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?php echo base_url(); ?>payments/make_payment/">Pay a Supplier</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo base_url(); ?>payments/bulk_transfer/">Pay Multiple Suppliers</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo base_url(); ?>payments/">My Payouts</a>
        </div>
      </li>
    </ul>
    <a class="btn btn-sm btn-secondary" href="<?php echo base_url(); ?>dashboard/logout/">Log out</a>
    <?php }else{ ?>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url(); ?>pages/contact/">Contact Developer</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url(); ?>dashboard/login/">Login</span></a>
      </li>
    </ul>
    <?php } ?>
  </div>
</nav>
</div>
</section>
<section class="body">
<pre>

</pre>
<?php 
    if(isset($_SESSION['errors'])){
        echo '<div class="alert alert-danger text-center" role="alert">'.$_SESSION['errors'].'</div>'; 
        unset($_SESSION['errors']);
    }
    if(isset($_SESSION['success'])){
        echo '<div class="alert alert-success text-center" role="alert">'.$_SESSION['success'].'</div>'; 
        unset($_SESSION['success']);
    }
?>