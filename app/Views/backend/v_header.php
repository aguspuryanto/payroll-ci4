<!DOCTYPE html>
<html>
<head>
	<title>Keuangan</title>

	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta charset="utf-8" name="copyright" content="">
  <meta name="keywords" content="keuangan">
  <meta name="description" content="Keuangan">
  <meta name="author" content="hEnDRa DiNatA">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/main.css'); ?>">
  
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
  <script
	  src="https://code.jquery.com/jquery-3.6.0.min.js"
	  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
	  crossorigin="anonymous"></script>

  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery.toast.min.css'); ?>">

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
  <body>

  <!-- Ini perlu di crosscheck -->
<?php if($uri->getSegment(1) !== 'login' && $session->username_citra) { ?> 
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="<?= base_url(); ?>" style="color: #f1c53b;">Keuangan</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="form-control form-control-dark text-end p-1">
    <div class="navbar-nav float-end h-100">
      <div class="nav-item text-nowrap">
        <a class="nav-link px-3" href="<?= base_url('logout'); ?>">Sign out</a>
      </div>
    </div>
    <div class="float-md-end float-start pt-2 ms-2">
      Halo, <?= $session->nama_citra; ?>
    </div>
</div>
</header>
<?php } ?>

<div class="container-fluid">