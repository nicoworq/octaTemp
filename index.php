<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'Temperature.php';
$Temp = new Temperature();
$data = $Temp->getTemperaturesForChart24hs();

$dataOutside = $Temp->getTemperaturesOutsideForChart24hs();

$tempActual = $Temp->getActualTemperature();

$tempExterior = $Temp->getActualOutsideTemperature();
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


        <title>Octa | Temperatura sala minado</title>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

        <link rel="stylesheet"  href="css/style.css"/>
        <link rel="stylesheet"  href="css/bootstrap.min.css"/>
    </head>

    <body>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="titulo-seccion">Temperatura Actual</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div id="actual" class="temperatura-valor">
                        <h1>Interior</h1>
                        <h3><?php echo $tempActual['temperatura'] ?>°C</h3>
                        <h5><?php echo $tempActual['fecha'] ?></h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="actual-afuera" class="temperatura-valor">
                        <h1>Exterior</h1>
                        <h3><?php echo $tempExterior['temperatura']; ?>°C</h3>
                        <h5><?php echo $tempExterior['fecha'] ?></h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="titulo-seccion">Últimas 24hs</h3>
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>



        <script>

            window.chartColors = {
                red: 'rgb(255, 99, 132)',
                orange: 'rgb(255, 159, 64)',
                yellow: 'rgb(255, 205, 86)',
                green: 'rgb(75, 192, 192)',
                blue: 'rgb(54, 162, 235)',
                purple: 'rgb(153, 102, 255)',
                grey: 'rgb(201, 203, 207)'
            };

            var labels = <?php echo json_encode($data['fechas']) ?>;

            var data = {
                labels: labels,
                datasets: [
                    {
                        label: "Temperatura Sala",
                        backgroundColor: window.chartColors.red,
                        borderColor: window.chartColors.red,
                        fill: false,
                        data: <?php echo json_encode($data['temperaturas']) ?>
                    },
                    {
                        label: "Temperatura Afuera",
                        backgroundColor: window.chartColors.blue,
                        borderColor: window.chartColors.blue,
                        fill: false,
                        data: <?php echo json_encode($dataOutside['temperaturas']) ?>
                    },
                ]
            };

            // Get the context of the canvas element we want to select
            var ctx = document.getElementById("myChart").getContext("2d");



            var myChart = new Chart(ctx, {
                type: "line",
                data: data,
            });





        </script>
    </body>


</html>