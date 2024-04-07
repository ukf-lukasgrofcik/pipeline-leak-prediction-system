var latest_timestamp = null;

function makeTable() {

    fetch("/table.php").then(response => response.json()).then(addToTable);

    setInterval(() => {
        let query = latest_timestamp ? `?latest_timestamp=${latest_timestamp}` : ''

        fetch(`/table.php${query}`).then(response => response.json()).then(addToTable);
    }, 2000)
}

function addToTable(data) {
     if (data.data.length == 0) document.location.reload();

     latest_timestamp = data.latest_timestamp;

    let tbody = document.querySelector('tbody')
    let content = data.data.reduce((carry, row) => carry + buildRow(row), "")

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

makeTable()