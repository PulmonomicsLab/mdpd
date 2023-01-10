function extractDataFromCSV(csv) {
    var x = [];
    var y = [];
    var z = [];
    for(var i=1; i<csv[0].length; ++i)
        x.push(csv[0][i]);
    for(var i=1; i<csv.length; ++i) {
        y.push(csv[i][0]);
        row = [];
        for(var j=1; j<csv[i].length; ++j)
            row.push(parseFloat(csv[i][j]));
        z.push(row);
    }
    return [x, y, z];
}

function makePlot(div_id, heatmapData) {
    var graphDiv = document.getElementById(div_id);
    
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
        plot_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
        paper_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
        height: (20*heatmapData[1].length),
        bargap: 10,
        margin: {
            t: 10,
            l: 220,
            b: 140
        },
        hoverlabel: {
            font: {size: 16}
        },
        xaxis: {
            visible : true,
            color: 'black',
            linewidth: 2,
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
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 10},
            title : {
                text : 'Taxa',
                font: {size: 22}
            }
        }
    };

    Plotly.plot(graphDiv, data, layout, {showSendToCloud:true});
}

function plotHeatmap(div_id, response, score) {
    var csv = $.csv.toArrays(response);
    var data = extractDataFromCSV(csv);
    
    makePlot(div_id, data);
}

