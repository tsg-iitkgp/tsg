if (window['google'] !== undefined) {
    google.setOnLoadCallback(function () {
        var chart = document.getElementById('chart');
        var table = document.querySelector('table > tbody');
        var dataRaw = window.printData;
        var dataTable = google.visualization.arrayToDataTable(dataRaw);
        var instance = new google.visualization.PieChart(chart);

        // Draw the chart
        instance.draw(
            dataTable,
            {
                pieHole: 0.5,
                legend: {position: 'labeled'},
                chartArea: {width: '95%', height: '95%'},
                pieSliceText: 'none',
                sliceVisibilityThreshold: 0,
                vAxis: {
                    format: 'short',
                },
                hAxis: {
                    format: 'short',
                }
            }
        );

        // Get chart slices
        var slices = document.querySelectorAll('svg > g > path');

        // Iterate
        dataRaw.forEach(function (value, index) {
            // Skip for the label
            if (index === 0) {
                return;
            }
            // Get the slice
            var slice = slices[(index - 1)];
            // Create elements
            var container = document.createElement('tr');
            var color = document.createElement('td');
            var label = document.createElement('td');
            var votes = document.createElement('td');

            // Set elements content
            color.innerHTML = '<div style="display: inline-block;background: %color%;width: 0.75em;height: 0.75em;"></div>'.replace('%color%', slice.getAttribute('fill'));
            label.innerHTML = value[0];
            votes.innerHTML = value[1];

            // Add elements to the container
            container.appendChild(color);
            container.appendChild(label);
            container.appendChild(votes);

            // Add container to table
            table.appendChild(container);
        });

        // Print (1s delay)
        setTimeout(window.print, 1000);

    });
}