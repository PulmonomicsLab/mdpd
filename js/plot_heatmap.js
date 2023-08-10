function extractDataFromCSV(csv) {
    var x = [];
    var y = [];
    var z = [];
    var labels = new Map();
    for(var i=1; i<csv[0].length; ++i)
        labels.set(csv[0][i], i-1);
    labels = new Map([...labels.entries()].sort());
    var order = Array.from(labels.values());
    for(var i=1; i<csv[0].length; ++i)
        x.push(csv[0][order[i-1]+1]);
    for(var i=1; i<csv.length; ++i) {
        y.push(csv[i][0]);
        row = [];
        for(var j=1; j<csv[i].length; ++j)
            row.push(parseFloat(csv[i][order[j-1]+1]).toFixed(3));
        z.push(row);
    }
    return [x, y, z];
}

function makePlot(div_id, disease_pair, assayType, biome, isolation_scource, heatmapData) {
    var graphDiv = document.getElementById(div_id);
    var minHeight = 400;
    var computedHeight = (30*heatmapData[1].length + 100);
    var yTitle = (assayType == 'Amplicon') ? 'Genus' : 'Species';
    
    var data = [{
            x: heatmapData[0],
            y: heatmapData[1],
            z: heatmapData[2],
            xgap: 0,
            ygap: 0,
            colorscale: 'Greys',
            reversescale: true,
            colorbar: {
                len: 0.5,
                title: {
                    text: 'Normalized abundance',
                    side: 'right',
                    font: {size: 18}
                }
            },
            type: 'heatmap'
        }];

    var layout = {
        plot_bgcolor: '#ffffff', //'#fff0f5',
        paper_bgcolor: '#ffffff', //'#fff0f5',
        height: ((computedHeight < minHeight) ? minHeight : computedHeight),
        bargap: 10,
        margin: {
            t: 30,
            l: 220,
            b: 140
        },
        modebar: {
            color: '#262626',
            activecolor: '#262626'
        },
        hoverlabel: {
            font: {size: 16}
        },
        xaxis: {
            visible : true,
            color: 'black',
            linewidth: 2,
            mirror: true,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 14},
            title : {
                text : 'BioProjects',
                font: {size: 22}
            }
        },
        yaxis: {
            visible : true,
            color: 'black',
            linewidth: 2,
            mirror: true,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 10},
            title : {
                text : yTitle,
                font: {size: 22}
            }
        }
    };

    var config = {
        showSendToCloud: false,
        displayModeBar: true,
        modeBarButtonsToRemove: ['toggleSpikelines', 'zoom2d'],
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
                        filename: 'heatmap_plot_' + disease_pair + '_' + assayType + '_' + biome + '_' + isolation_scource,
                        format: 'svg'
                    }
                );
            }
        }]
    }

    Plotly.plot(graphDiv, data, layout, config);
}

function plotHeatmap(div_id, response, disease_pair, assayType, biome, isolation_scource, score) {
    var csv = $.csv.toArrays(response);
    var data = extractDataFromCSV(csv);
    
    makePlot(div_id, disease_pair, assayType, biome, isolation_scource, data);
}

