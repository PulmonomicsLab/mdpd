function getColorScale(colorScaleName) {
    if (colorScaleName == 'PRGn') {
        return [
            ['0.0', 'rgb(64,0,75)'],
            ['0.111111111111', 'rgb(118,42,131)'],
            ['0.222222222222', 'rgb(153,112,171)'],
            ['0.333333333333', 'rgb(194,165,207)'],
            ['0.444444444444', 'rgb(231,212,232)'],
            ['0.555555555556', 'rgb(217,240,211)'],
            ['0.666666666667', 'rgb(166,219,160)'],
            ['0.777777777778', 'rgb(90,174,97)'],
            ['0.888888888889', 'rgb(27,120,55)'],
            ['1.0', 'rgb(0,68,27)']
        ];
    } else if (colorScaleName == 'PrOr') {
        return [
            ['0.0', 'rgb(127,59,8)'],
            ['0.111111111111', 'rgb(179,88,6)'],
            ['0.222222222222', 'rgb(224,130,20)'],
            ['0.333333333333', 'rgb(253,184,99)'],
            ['0.444444444444', 'rgb(254,224,182)'],
            ['0.555555555556', 'rgb(216,218,235)'],
            ['0.666666666667', 'rgb(178,171,210)'],
            ['0.777777777778', 'rgb(128,115,172)'],
            ['0.888888888889', 'rgb(84,39,136)'],
            ['1.0', 'rgb(45,0,75)']
        ];
    }  else if (colorScaleName == 'RdYlBu') {
        return [
            ['0.0', 'rgb(165,0,38)'],
            ['0.111111111111', 'rgb(215,48,39)'],
            ['0.222222222222', 'rgb(244,109,67)'],
            ['0.333333333333', 'rgb(253,174,97)'],
            ['0.444444444444', 'rgb(254,224,144)'],
            ['0.555555555556', 'rgb(224,243,248)'],
            ['0.666666666667', 'rgb(171,217,233)'],
            ['0.777777777778', 'rgb(116,173,209)'],
            ['0.888888888889', 'rgb(69,117,180)'],
            ['1.0', 'rgb(49,54,149)']
        ];
    } else if (colorScaleName == 'RdYlBu_truncated') {
        return [
            ['0.0', 'rgb(244,109,67)'],
            ['0.2', 'rgb(253,174,97)'],
            ['0.4', 'rgb(254,224,144)'],
            ['0.6', 'rgb(224,243,248)'],
            ['0.8', 'rgb(171,217,233)'],
            ['1.0', 'rgb(116,173,209)'],
        ];
    } else if (colorScaleName == 'Spectral') {
        return [
            ['0.0', 'rgb(158,1,66)'],
            ['0.111111111111', 'rgb(213,62,79)'],
            ['0.222222222222', 'rgb(244,109,67)'],
            ['0.333333333333', 'rgb(253,174,97)'],
            ['0.444444444444', 'rgb(254,224,139)'],
            ['0.555555555556', 'rgb(230,245,152)'],
            ['0.666666666667', 'rgb(171,221,164)'],
            ['0.777777777778', 'rgb(102,194,165)'],
            ['0.888888888889', 'rgb(50,136,189)'],
            ['1.0', 'rgb(94,79,162)']
        ];
    } else {
        return 'Greys';
    }
}

// function reverseColorScales(colorscale) {
//     var reverse = new Array();
//     for (var i = 0; i < colorscale.length; ++i) {
//         var color = new Array(2);
//         color[0] = colorscale[i][0];
//         color[1] = colorscale[colorscale.length - i - 1][1];
//         reverse.push(color);
//     }
//     return reverse;
// }

function getDataMap(taxa, name, value, significance) {
    var dataMap = {taxa: [], name: [], values: [], significances: []};
    dataMap.taxa = [...new Set(taxa)].sort();
    dataMap.name = [...new Set(name)].sort();
    var valueMap = new Map();
    var significanceMap = new Map();
    for(var i=0; i<taxa.length; ++i) {
        valueMap.set(name[i] + taxa[i], value[i]);
        significanceMap.set(name[i] + taxa[i], significance[i])
    }
    for(var i=0; i < dataMap.taxa.length; ++i) {
        var valueRow = [];
        var significanceRow = [];
        for(var j=0; j < dataMap.name.length; ++j) {
            valueRow.push(valueMap.get(dataMap.name[j] + dataMap.taxa[i]));
            significanceRow.push(significanceMap.get(dataMap.name[j] + dataMap.taxa[i]));
        }
        dataMap.values.push(valueRow);
        dataMap.significances.push(significanceRow);
    }
    return dataMap;
}

function createDownloadLink(heatmapData) {
    var s = '';
    for(var i=0; i<heatmapData.name.length; ++i)
        s += '\t' + heatmapData.name[i];
    s += '\n';
    for(var i=0; i<heatmapData.taxa.length; ++i) {
        s += heatmapData.taxa[i];
        for(var j=0; j<heatmapData.name.length; ++j)
            s += '\t' + heatmapData.values[i][j];
        s += '\n';
    }
    var blob = new Blob([s], {type: 'text/csv;charset=utf-8;'});
    document.getElementById('download_button').href = URL.createObjectURL(blob);
}

function makePlot(div_id, heatmapData) {
    var graphDiv = document.getElementById(div_id);
    var minHeight = 500;
    var computedHeight = (20*heatmapData.taxa.length + 100);
    var yTitle = 'Taxa';
    
    var data = [{
        x: heatmapData.name,
        y: heatmapData.taxa,
        z: heatmapData.values,
        xgap: 2,
        ygap: 2,
        colorscale: getColorScale('RdYlBu_truncated'),
        reversescale: true,
        colorbar: {
            len: 0.5,
            outlinewidth: 0,
            tickfont: {color: '#000000'},
            ticks: 'inside',
            title: {
                text: 'Maaslin2 coefficient',
                side: 'right',
                font: {
                    size: 18,
                    color: '#000000'
                }
            }
        },
        type: 'heatmap'
    }];

    var annotations = [];
    for(var i=0; i<heatmapData.taxa.length; ++i)
        for(var j=0; j<heatmapData.name.length; ++j)
            annotations.push({
                xref: 'x1',
                yref: 'y1',
                x: heatmapData.name[j],
                y: heatmapData.taxa[i],
                text: heatmapData.significances[i][j],
                font: {color: '#000000'},
                valign: 'middle',
                showarrow: false
            });

    var layout = {
        annotations: annotations,
        plot_bgcolor: '#ffffff', //'#fff0f5',
        paper_bgcolor: '#ffffff', //'#fff0f5',
        height: ((computedHeight < minHeight) ? minHeight : computedHeight),
        margin: {
            t: 30,
//             l: 250,
//             b: 140
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
            automargin: true,
            color: 'black',
            linewidth: 2,
            mirror: true,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 14},
        },
        yaxis: {
            visible : true,
            automargin: true,
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
                        filename: 'covalriate_heatmap_plot',
                        format: 'svg'
                    }
                );
            }
        }]
    }

    Plotly.plot(graphDiv, data, layout, config);
}

function plotCovariateHeatmap(div_id, response) {
    var data = JSON.parse(response);
    if(data.taxa.length > 0) {
        var dataMap = getDataMap(data.taxa, data.name, data.value, data.significance);
        makePlot(div_id, dataMap);
        createDownloadLink(dataMap);
        document.getElementById('download_div').style.display = 'block';
    } else {
        document.getElementById(div_id).innerHTML = '<p>No significant taxa found</p>';
    }
}

