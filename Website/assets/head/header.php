<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php if (isset($pageTitle)) { echo $pageTitle; } else { echo "Pagina"; } ?> | Ixat Taxi's</title>

    <!-- Bootstrap CSS & JS
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script> -->
    <link href="/assets/bootstrap-4.1.0/dist/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <script src="/assets/bootstrap-4.1.0/dist/js/bootstrap.min.js"></script>
    <!-- Ixat CSS -->
    <link href="/assets/css/ixat.css" type="text/css" rel="stylesheet">
    <link href="/assets/css/ixat.1.css" type="text/css" rel="stylesheet">
    <script src="/assets/js/ixat.js"></script>
  </head>
  <body>

<?php
include "nav.php";
?>