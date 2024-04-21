var latest_timestamp = null;

function getData() {
    let query = latest_timestamp ? `?latest_timestamp=${latest_timestamp}` : ''

    fetch(`/tableData.php${query}`).then(response => response.json()).then(addToTable)
}

function addToTable(data) {
    if (data.data.length === 0) document.location.reload()

    latest_timestamp = data.latest_timestamp

    let tbody = document.querySelector('tbody')
    let content = data.data.map(buildRow).join("")

    tbody.innerHTML = content + tbody.innerHTML
}

function buildRow(row) {
    return (
        `<tr>` +
            `<td>${row.id}</td>` +
            `<td>${row.f1}</td>` +
            `<td>${row.f2}</td>` +
            `<td>${row.p1}</td>` +
            `<td>${row.p2}</td>` +
            `<td>${row.prediction}</td>` +
            `<td>${row.timestamp}</td>` +
        `</tr>`
    )
}

setInterval(getData, 2000)