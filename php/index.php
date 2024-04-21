<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chart</title>

    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<div class="menu">
    <a href="index.php">Chart</a>

    <a href="table.php">Table</a>
</div>

<div style="display: flex;">
    <div class="chart" style="flex: 75%;">
        <canvas id="myChart"></canvas>
    </div>

    <div class="chart-table" style="flex: 25%;">
        <table>
            <tr>
                <td>Leak</td>
                <td id="leak">
                    <svg width="800px" height="800px" viewBox="0 0 1024 1024" class="icon"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M512 512m-448 0a448 448 0 1 0 896 0 448 448 0 1 0-896 0Z" fill="#4CAF50" /><path d="M738.133333 311.466667L448 601.6l-119.466667-119.466667-59.733333 59.733334 179.2 179.2 349.866667-349.866667z" fill="#CCFF90" /></svg>
                </td>
            </tr>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/script.js"></script>

</body>
</html>
