<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE HTML>
<!--[if lt IE 7 ]> <html xmlns="http://www.w3.org/1999/xhtml" class="ie ie6"> <![endif]-->
<!--[if IE 7 ]>    <html xmlns="http://www.w3.org/1999/xhtml" class="ie ie7"> <![endif]-->
<!--[if IE 8 ]>    <html xmlns="http://www.w3.org/1999/xhtml" class="ie ie8"> <![endif]-->
<!--[if IE 9 ]>    <html xmlns="http://www.w3.org/1999/xhtml" class="ie ie9"> <![endif]-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="keywords" content="Website"/>
<meta name="description" content="Website Description"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<title><?php echo ucwords(!empty($this->page_title)?$this->page_title:$this->config->item('sitename')); ?></title>

<!-- Favicon Icon -->
<link rel="icon" href="<?php echo base_url(); ?>images/favicon/favicon.png" type="image/png">
<!-- <link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/icon"> -->

<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,400i,700,800|Varela+Round" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>style.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>responsive.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="<?= $this->config->item('js_url') ?>jquery-3.1.1.min.js"></script>
</head>

<body>
<!-- <div class="fh5co-loader"></div> -->
  
  <header class="header1">
        <nav class="navbar navbar-default navbar-static-top fluid_header centered">
            <div class="container">
                
                <!-- Logo -->
                <div class="col-md-2 col-sm-6 col-xs-8 nopadding">
                    <a class="navbar-brand nomargin" href="<?= base_url() ?>"><img src="<?= $this->config->item('image_url') ?>logo.svg" alt="logo"></a>
                </div>
            </div>
        </nav>
    </header>
	<section class="ptb160" id="page-not-found">
        <div class="container">
            
            <!-- First Column -->
            <div class="col-md-6">
                <h2>404</h2>
                <h3 class="capitalize">page not found</h3>
            </div>
            
            <!-- Second Column -->
            <div class="col-md-6">
                <p class="mt40">Lorem ipsum dolor sit amet, consect etuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. </p>
                <a href="<?= base_url() ?>" class="btn btn-blue btn-effect mt20">back home</a>
            </div>
            
        </div>
    </section>