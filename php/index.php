<?php



?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    td {
        border: 1px solid #cfcfcf;
        padding: 10px;
        width: 50%;
    }
    svg {
        max-width: 30px;
        max-height: 30px;
    }
    td div {
        display: flex;
    }
    td div span {
        margin: auto 10px;
    }
</style>

<div style="display: flex;">
    <div style="flex: 75%;">
        <canvas id="myChart"></canvas>
    </div>

    <div style="flex: 25%">
        <div style="margin: 100px 30px; font-family: system-ui; box-shadow: 0px 0px 3px 0px black; border-radius: 5px;">
            <table>
                <tr>
                    <td>Autoencoder</td>
                    <td>
                        <div id="autoencoder"></div>
                    </td>
                </tr>
                <tr>
                    <td>Sensor</td>
                    <td>
                        <div id="sensor"></div>
                    </td>
                </tr>
                <tr>
                    <td>Leak</td>
                    <td>
                        <div id="leak"></div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="script.js"></script>

</body>
</html>
