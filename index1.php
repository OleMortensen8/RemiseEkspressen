<?php 
include "bootstrap.php";
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gedser Remise Ekspressen (Backup 1655855812758) (Backup 1655869464055)</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/calendar.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <header>
        <h1 class="text-center">Remise Ekspressen</h1>
    </header>
    <section class="py-4 py-xl-5">
        <div class="container"><?php

    $booking = new Booking();
    $bookableCell = new BookableCell($booking);
    $calendar = new Calendar();
    $calendar->setSundayFirst(false);
    $calendar->attachObserver('showCell', $bookableCell);
    echo $calendar->show();
            ?>
        </div>
    </section>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>