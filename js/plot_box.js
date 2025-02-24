function getDataMap(taxa, subgroup, abundances) {
    var dataMap = new Map();
    for(var i=1; i<taxa.length; ++i) {
        if(dataMap.has(subgroup[i])) {
            dataMap.get(subgroup[i]).taxa.push(taxa[i]);
            dataMap.get(subgroup[i]).abundances.push(abundances[i]);
        } else {
            dataMap.set(subgroup[i], {taxa: [taxa[i]], abundances: [abundances[i]]});
        }
    }
    return dataMap;
}

function createDownloadLink(sample, taxa, subgroup, abundances) {
    var s = 'Sample\tTaxa\tSubGroup\tAbundance\n';
    for(var i=0; i<taxa.length; ++i) {
        s += sample[i] + '\t';
        s += taxa[i] + '\t';
        s += subgroup[i] + '\t';
        s += abundances[i] + '\n';
    }
    var blob = new Blob([s], {type: 'text/csv;charset=utf-8;'});
    document.getElementById('download_div_taxa_distribution').style.display = 'block';
    document.getElementById('download_button_taxa_distribution').href = URL.createObjectURL(blob);
}

function createTaxaButtons(taxa) {
    var s = ''
    for(var t of taxa.keys())
        s += '<div style="float:left; margin:5px;"><a href="taxa.php?key=' + t.substr(3).replace(/_/g, " ") + '" target="_blank"><button style="padding:2px 5px;">' + t + '</button></a></div>'
    s += '<div style="clear:both;" />'
    document.getElementById('taxa_button_group').innerHTML = s;
}

function makePlot(div_id, dataMap) {
    var graphDiv = document.getElementById(div_id);

//     var colors = ['#e9967a', '#b0c4de', '#f1ce8e', '#9ec08c'];
//     var i = 0;
    var xTitle = 'Top 10 taxa';

    var data = [];
    for(var subgroup of dataMap.keys()){
        var box = {
            type: 'box',
            name: subgroup,
            x: dataMap.get(subgroup).taxa,
            y: dataMap.get(subgroup).abundances,
//             marker: {color: colors[i]}
        };
//         i++;
        data.push(box);
    }

    var layout = {
        plot_bgcolor: '#ffffff', //'#fff0f5',
        paper_bgcolor: '#ffffff', //'#fff0f5',
        height: 600,
        boxmode: 'group',
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
            t: 20
//             l: 100
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
                text : xTitle,
                font: {size: 22}
            }
        },
        yaxis: {
            visible : true,
            automargin: true,
            zeroline: false,
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
        showlegend: true,
        legend: {
            orientation: 'h',
            opacity: 0,
            font: {
                size: 13,
                color: 'black'
            },
            borderwidth: 2,
            bordercolor: 'black',
            y: 1.1,
            xanchor: 'center',
            x: 0.5,
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
                        filename: 'box_plot_',
                        format: 'svg'
                    }
                );
            }
        }]
    }

    Plotly.plot(graphDiv, data, layout, config);
}

function plotBox(div_id, response) {
    var data = JSON.parse(response);
    if (data.taxa.length > 0) {
        var dataMap = getDataMap(data.taxa, data.subgroup, data.abundances);
        document.getElementById(div_id).innerHTML = '';
        makePlot(div_id, dataMap);
        createDownloadLink(data.sample, data.taxa, data.subgroup, data.abundances);
        createTaxaButtons(new Set(data.taxa));
        document.getElementById('taxa_button_group_heading').style.display = 'block';
        document.getElementById('taxa_button_group').style.display = 'block';
    }
}

function getBoxPlotData(div_id, dataJSON) {
    var data = JSON.parse(dataJSON);
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            plotBox(div_id, this.responseText)
        }
    };
    httpReq.open('POST', 'bioproject_taxonomic_profile_data.php', true);
    httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    httpReq.send(
        'bioproject=' + encodeURIComponent(data.bioproject) +
        '&' + 'at=' + encodeURIComponent(data.at) +
        '&' + 'is=' + encodeURIComponent(data.is)
    );
}
