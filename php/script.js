var latest_timestamp = null;
var chart = null;
const threshold = 2.2;
const leak = '2024-01-13 19:03:42';
const sensor_leak = '2024-01-13 19:04:18';

const icon_danger = '<svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"/><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/><g id="SVGRepo_iconCarrier"> <path opacity="0.4" d="M21.7605 15.92L15.3605 4.4C14.5005 2.85 13.3105 2 12.0005 2C10.6905 2 9.50047 2.85 8.64047 4.4L2.24047 15.92C1.43047 17.39 1.34047 18.8 1.99047 19.91C2.64047 21.02 3.92047 21.63 5.60047 21.63H18.4005C20.0805 21.63 21.3605 21.02 22.0105 19.91C22.6605 18.8 22.5705 17.38 21.7605 15.92Z" fill="#ec3636"/> <path d="M12 14.75C11.59 14.75 11.25 14.41 11.25 14V9C11.25 8.59 11.59 8.25 12 8.25C12.41 8.25 12.75 8.59 12.75 9V14C12.75 14.41 12.41 14.75 12 14.75Z" fill="#ec3636"/> <path d="M12 18.0001C11.94 18.0001 11.87 17.9901 11.8 17.9801C11.74 17.9701 11.68 17.9501 11.62 17.9201C11.56 17.9001 11.5 17.8701 11.44 17.8301C11.39 17.7901 11.34 17.7501 11.29 17.7101C11.11 17.5201 11 17.2601 11 17.0001C11 16.7401 11.11 16.4801 11.29 16.2901C11.34 16.2501 11.39 16.2101 11.44 16.1701C11.5 16.1301 11.56 16.1001 11.62 16.0801C11.68 16.0501 11.74 16.0301 11.8 16.0201C11.93 15.9901 12.07 15.9901 12.19 16.0201C12.26 16.0301 12.32 16.0501 12.38 16.0801C12.44 16.1001 12.5 16.1301 12.56 16.1701C12.61 16.2101 12.66 16.2501 12.71 16.2901C12.89 16.4801 13 16.7401 13 17.0001C13 17.2601 12.89 17.5201 12.71 17.7101C12.66 17.7501 12.61 17.7901 12.56 17.8301C12.5 17.8701 12.44 17.9001 12.38 17.9201C12.32 17.9501 12.26 17.9701 12.19 17.9801C12.13 17.9901 12.06 18.0001 12 18.0001Z" fill="#ec3636"/> </g></svg>';
const icon_ok = '<svg width="800px" height="800px" viewBox="0 0 1024 1024" class="icon"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M512 512m-448 0a448 448 0 1 0 896 0 448 448 0 1 0-896 0Z" fill="#4CAF50" /><path d="M738.133333 311.466667L448 601.6l-119.466667-119.466667-59.733333 59.733334 179.2 179.2 349.866667-349.866667z" fill="#CCFF90" /></svg>';

var helper_data = {
    leak_passed: false,
    leak_passed_timestamp: null,
    sensor_leak_passed: false,
    sensor_leak_timestamp: null,
    threshold_passed: false,
    threshold_timestamp: null,
}

function makeChart(labels, data) {
    let ctx = document.getElementById('myChart');

    fetch("/predictions.php")
        .then(response => response.json())
        .then(data => {
            let _labels = data.labels;
            let _data = data.data;

            latest_timestamp = data.latest_timestamp;

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: _labels,
                    datasets: [
                        {
                            label: 'Reconstruction error',
                            borderColor: 'blue',
                            backgroundColor: 'blue',
                            pointRadius: 0, // Set pointRadius to 0 to remove dots
                            data: _data
                        },
                        {
                            label: `Leak (${leak})`,
                            borderColor: 'red',
                            backgroundColor: 'red'
                        },
                        {
                            label: `Threshold (${threshold})`,
                            borderColor: 'green',
                            backgroundColor: 'green'
                        },
                        {
                            label: `Sensor leak (${sensor_leak})`,
                            borderColor: 'orange',
                            backgroundColor: 'orange'
                        },
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Reconstruction error of autoencoder'
                        },
                        drawVerticalLine: {
                            value: threshold,
                            borderColor: 'green',
                            borderWidth: 2,
                            borderDash: [10, 10], // Set the pattern for dashed line
                        },
                        drawHorizontalLine1: {
                            value: leak,
                            borderColor: 'red',
                            borderWidth: 2,
                            borderDash: [10, 10], // Set the pattern for dashed line
                        },
                        drawHorizontalLine2: {
                            value: sensor_leak,
                            borderColor: 'orange',
                            borderWidth: 2,
                            borderDash: [10, 10], // Set the pattern for dashed line
                        },
                    },
                    scales: {
                        y: {
                            suggestedMin: 0,
                            suggestedMax: threshold * 2,
                        }
                    }
                },
                plugins: [
                    {
                        id: 'drawVerticalLine',
                        afterDraw: (chart, args, options) => {
                            let { ctx, chartArea, scales } = chart;
                            let { value, borderColor, borderWidth, borderDash } = options;
                            let yPixel = scales.y.getPixelForValue(value);

                            ctx.save();
                            ctx.strokeStyle = borderColor;
                            ctx.lineWidth = borderWidth;
                            ctx.setLineDash(borderDash);
                            ctx.beginPath();
                            ctx.moveTo(chartArea.left, yPixel);
                            ctx.lineTo(chartArea.right, yPixel);
                            ctx.stroke();
                            ctx.restore();
                        },
                    },
                    {
                        id: 'drawHorizontalLine1',
                        afterDraw: (chart, args, options) => {
                            let { ctx, chartArea, scales } = chart;
                            let { value, borderColor, borderWidth, borderDash } = options;
                            let xPixel = scales.x.getPixelForValue(value);

                            ctx.save();
                            ctx.strokeStyle = borderColor;
                            ctx.lineWidth = borderWidth;
                            ctx.setLineDash(borderDash);
                            ctx.beginPath();
                            ctx.moveTo(xPixel, chartArea.top);
                            ctx.lineTo(xPixel, chartArea.bottom);
                            ctx.stroke();
                            ctx.restore();
                        },
                    },
                    {
                        id: 'drawHorizontalLine2',
                        afterDraw: (chart, args, options) => {
                            let { ctx, chartArea, scales } = chart;
                            let { value, borderColor, borderWidth, borderDash } = options;
                            let xPixel = scales.x.getPixelForValue(value);

                            ctx.save();
                            ctx.strokeStyle = borderColor;
                            ctx.lineWidth = borderWidth;
                            ctx.setLineDash(borderDash);
                            ctx.beginPath();
                            ctx.moveTo(xPixel, chartArea.top);
                            ctx.lineTo(xPixel, chartArea.bottom);
                            ctx.stroke();
                            ctx.restore();
                        },
                    }
                ]
            });
        });

    setInterval(() => {
        fetch("/predictions.php" + (latest_timestamp ? '?latest_timestamp='+latest_timestamp : ''))
            .then(response => response.json())
            .then(data => {
                let _labels = data.labels;
                let _data = data.data;

                if (_data.length == 0) {
                    document.location.reload();
                }

                latest_timestamp = data.latest_timestamp;

                for (let i = 0; i < _labels.length - 1; i++) {
                    addData(chart, _labels[i], _data[i]);

                    calculateText(_labels[i], _data[i]);
                }
            });
    }, 5000)
}

function addData(chart, x, y) {
    chart.data.labels.push(x);
    chart.data.datasets.forEach(dataset => dataset.label == 'Reconstruction error' ? dataset.data.push(y) : void(0));
    chart.update();
}
calculateText()
function calculateText(timestamp, data) {
    if (data > threshold && ! helper_data.threshold_passed) {
        helper_data.threshold_passed = true;
        helper_data.threshold_timestamp = timestamp;
    }
    if (timestampDiff(timestamp, sensor_leak) > 0 && ! helper_data.sensor_leak_passed) helper_data.sensor_leak_passed = true;
    if (timestampDiff(timestamp, leak) > 0 && ! helper_data.leak_passed) helper_data.leak_passed = true;

    document.getElementById('autoencoder').innerHTML = helper_data.threshold_passed ? (icon_danger + `<span>${((new Date(helper_data.threshold_timestamp) - (new Date(leak))) / 1000)}</span>`) : icon_ok;
    document.getElementById('sensor').innerHTML = helper_data.sensor_leak_passed ? (icon_danger + `<span>${((new Date(sensor_leak) - (new Date(leak))) / 1000)}s</span>`) : icon_ok;
    document.getElementById('leak').innerHTML = helper_data.leak_passed ? icon_danger : icon_ok;
}

function timestampDiff(ts1, ts2) {
    return (new Date(ts1) - new Date(ts2)) / 1000;
}

makeChart();