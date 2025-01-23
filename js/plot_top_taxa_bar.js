function makePlot(div_id, taxa, abundances) {
    var graphDiv = document.getElementById(div_id);

    var yTitle = 'Top 10 taxa';

    var data = [
        {
            type: 'bar',
            x: abundances,
            y: taxa,
            orientation: 'h',
            marker: {opacity: 0.5, color: 'green'},
        }
    ];

    var layout = {
        plot_bgcolor: '#ffffff', //'#fff0f5',
        paper_bgcolor: '#ffffff', //'#fff0f5',
        height: 600,
        bargap: 0.2,
        modebar: {
            color: '#262626',
            activecolor: '#262626'
        },
        hoverlabel: {
            bgcolor: 'white',
            font: {size: 18, color: 'black'}
        },
        hovertext: {
            font: {color: 'black'}
        },
        margin: {
            t: 20,
//             l: 200
        },
        xaxis: {
            visible : true,
            automargin: true,
            color: 'black',
            linewidth: 2,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 16},
            title : {
                text : 'Relative abundance (%)',
                font: {size: 22}
            }
        },
        yaxis: {
            visible : true,
            automargin: true,
            color: 'black',
            linewidth: 2,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 16},
            title : {
                text : yTitle,
                font: {size: 22}
            }
        }
    };

    var config = {
        showSendToCloud: false,
        displayModeBar: true,
        modeBarButtonsToRemove: ['toggleSpikelines', 'zoom2d', 'select2d', 'lasso2d'],
        modeBarButtonsToAdd: [{
            name: 'Export as SVG',
            icon: {
                width: 600,
                height: 600,
                path: 'M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z'
            },
            click: function(img) {
                Plotly.downloadImage(
                    img,
                    {
                        filename: 'bar_plot_',
                        format: 'svg'
                    }
                );
            }
        }]
    }

    Plotly.plot(graphDiv, data, layout, config);
}

function plotBar(div_id, response) {
    var data = JSON.parse(response);
    var taxa = data.taxa;
    var abundances = data.abundances;
    for(var i=0; i<taxa.length; ++i) {
        taxa[i] = '<i>' + taxa[i] + '</i>';
    };
    document.getElementById(div_id).innerHTML = '';
    makePlot(div_id, taxa, abundances);
}

function getTopTaxaData(div_id, dataJSON) {
    var data = JSON.parse(dataJSON);
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            plotBar(div_id, this.responseText)
        }
    };
    httpReq.open('POST', 'run_id_data.php', true);
    httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    httpReq.send(
        'run=' + encodeURIComponent(data.run) +
        '&' + 'bioproject=' + encodeURIComponent(data.bioproject) +
        '&' + 'at=' + encodeURIComponent(data.at)
    );
}
