function getDataMap(csv) {
    var dataMap = new Map();
    for(var i=1; i<csv.length; ++i) {
        var group = csv[i][2];
        var name = csv[i][1];
        var score = parseFloat(csv[i][3]);
        if(dataMap.has(group)) {
            dataMap.get(group).names.push(name);
            dataMap.get(group).scores.push(score);
        } else {
            dataMap.set(group, {names: [name], scores: [score]});
        }
    }
    return dataMap;
}

function makePlot(div_id, dataMap, disease_pair, assayType, biome, isolation_scource) {
    var graphDiv = document.getElementById(div_id);
    
    var data = [];
    var colors = ['#e9967a', '#b0c4de', '#f1ce8e', '#9ec08c'];
    var i = 0;
    var yTitle = (assayType == 'Amplicon') ? 'Differentially abundant genus' : 'Differentially abundant species';

    for(var x of dataMap.keys()){
        var barChart = {
            type: 'bar',
            name: x,
            x: dataMap.get(x).scores,
            y: dataMap.get(x).names,
            orientation: 'h',
            marker: {color: colors[i]}
        };
        i++;
        data.push(barChart);
    }

    var layout = {
        plot_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
        paper_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
        height: 800,
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
            t: 30
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
                text : 'LDA score (log<sub>10</sub>)',
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
            tickfont: {size: 10},
            title : {
                text : yTitle,
                font: {size: 22}
            }
        },
        showlegend: true,
        legend: {
            font: {
                size: 16,
                color: 'black'
            },
            borderwidth: 2,
            bordercolor: 'black',
            y: 0.5
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
                        filename: 'lda_plot_' + disease_pair + '_' + assayType + '_' + biome + '_' + isolation_scource,
                        format: 'svg'
                    }
                );
            }
        }]
    }

    Plotly.plot(graphDiv, data, layout, config);
}

function plotLDA(div_id, response, disease_pair, assayType, biome, isolation_scource, score) {
    var csv = $.csv.toArrays(response);
    var dataMap = getDataMap(csv);
    
//     var msg = dataMap.size + '<br/>';
//     for(var x of dataMap.keys())
//         msg += x + ' => ' + JSON.stringify(dataMap.get(x)) + '<br/>';
//     document.getElementById(div_id).innerHTML = msg;
    
    makePlot(div_id, dataMap, disease_pair, assayType, biome, isolation_scource);
}

