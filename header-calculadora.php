<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Calculadoras Maxco">
  <meta name="author" content="Maxco S.A">
  <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/layouts/img/logo.png" type="image/x-icon">
  <title>Maxco Ventas </title>
  <!-- Custom fonts for this template -->
  <?php wp_head(); ?>
  <link href="<?php echo get_template_directory_uri(); ?>/layouts/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="<?php echo get_template_directory_uri(); ?>/layouts/css/sb-admin-2.css" rel="stylesheet">
  <!-- Custom styles for this page -->
  <link href="<?php echo get_template_directory_uri(); ?>/layouts/css/select2.min.css" rel="stylesheet" />
  <link href="<?php echo get_template_directory_uri(); ?>/layouts/css/select2-bootstrap4.min.css" rel="stylesheet" />
  <link href="<?php echo get_template_directory_uri(); ?>/layouts/css/styles.css" rel="stylesheet">
</head>

<body <?php body_class(); ?>>
  <?php do_action('woodmart_after_body_open'); ?>

  <div class="website-wrapper">

    <?php if (woodmart_needs_header()) : ?>

      <!-- HEADER -->
      <header <?php woodmart_get_header_classes(); // location: inc/functions.php 
              ?>>

        <?php
        whb_generate_header();
        ?>

      </header>
      <!--END MAIN HEADER-->

      <?php/*  woodmart_page_top_part(); */ ?>

    <?php endif ?>
    <div>
      <div class="d-flex flex-column">
        <div>