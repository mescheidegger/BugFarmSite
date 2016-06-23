<?php
        include($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/AccountRegistration/GetSessionUser.php");
        //Server side clickjacking protection
        header("X-Frame-Options: DENY");
        header("Content-Security-Policy: frame-ancestors 'none'", false);
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Imago</title>
        <meta content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="/BugFarmSite/CSS/public/bootstrap.min.css" />
        <link href="/BugFarmSite/CSS/public/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
        <link href="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" type="text/css" href="/BugFarmSite/CSS/BugFarm.css" />
    </head>