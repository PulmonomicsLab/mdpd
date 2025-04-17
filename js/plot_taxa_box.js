function getDataMap(subgroup, abundances) {
    var dataMap = new Map();
    for(var i=0; i<subgroup.length; ++i) {
        if(dataMap.has(subgroup[i])) {
            dataMap.get(subgroup[i]).push(abundances[i]);
        } else {
            dataMap.set(subgroup[i], [abundances[i]]);
        }
    }
    return dataMap;
}

function createDownloadLinkSubGroup(subgroup, bioproject, abundances) {
    var s = 'SubGroup\tBioProject\tAbundance\n';
    for(var i=0; i<subgroup.length; ++i) {
        s += subgroup[i] + '\t';
        s += bioproject[i] + '\t';
        s += abundances[i] + '\n';
    }
    var blob = new Blob([s], {type: 'text/csv;charset=utf-8;'});
    document.getElementById('download_div_taxa_distribution_subgroup').style.display = 'block';
    document.getElementById('download_button_taxa_distribution_subgroup').href = URL.createObjectURL(blob);
}

function createDownloadLinkBiome(biome, bioproject, abundances) {
    var s = 'Biome\tBioProject\tAbundance\n';
    for(var i=0; i<biome.length; ++i) {
        s += biome[i] + '\t';
        s += bioproject[i] + '\t';
        s += abundances[i] + '\n';
    }
    var blob = new Blob([s], {type: 'text/csv;charset=utf-8;'});
    document.getElementById('download_div_taxa_distribution_biome').style.display = 'block';
    document.getElementById('download_button_taxa_distribution_biome').href = URL.createObjectURL(blob);
}

function makePlotSubGroup(div_id, dataMap) {
    var graphDiv = document.getElementById(div_id);

    var data = [];
    for(var subgroup of dataMap.keys()){
        var box = {
            type: 'box',
            name: subgroup,
            x: dataMap.get(subgroup),
        };
        data.push(box);
    }

    var layout = {
        plot_bgcolor: '#ffffff', //'#fff0f5',
        paper_bgcolor: '#ffffff', //'#fff0f5',
        height: (25 * Array.from(dataMap.keys()).length + 100),
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
            zeroline: false,
            color: 'black',
            linewidth: 2,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 16},
            title : {
                text : 'Mean relative abundance within BioProjects (%)',
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
            tickfont: {size: 12},
            title : {
                text : 'SubGroup',
                font: {size: 22}
            }
        },
        showlegend: false,
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

function makePlotBiome(div_id, dataMap) {
    var graphDiv = document.getElementById(div_id);

    var data = [];
    var biomeColors = {
        'Anus': '#BD777A', 'Gut': '#FF4D6F', 'Large Intestine': '#579EA4',
        'Lower Respiratory Tract': '#DF7713', 'Lung': '#F9C000',
        'Nasal': '#86AD34', 'Oral': '#5D7298', 'Rectum': '#7E1A2F',
        'Stomach': '#C8350D', 'Upper Respiratory Tract': '#2D2651'
    }
    for(var biome of Object.keys(biomeColors)){
        var box = {
            type: 'box',
            name: biome,
            y: (dataMap.has(biome)) ? dataMap.get(biome) : [0],
            marker: {color: biomeColors[biome], opacity:1}
        };
        data.push(box);
    }

    var layout = {
        plot_bgcolor: '#ffffff', //'#fff0f5',
        paper_bgcolor: '#ffffff', //'#fff0f5',
        height: 400,
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
                text : 'Biome',
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
                text : 'Mean relative abundance <br>within BioProjects (%)',
                font: {size: 22}
            }
        },
        showlegend: false,
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


function plotBoxSubGroup(div_id, response) {
    var data = JSON.parse(response);
    if (data.subgroup.length > 0) {
        var dataMap = getDataMap(data.subgroup, data.abundances);
        document.getElementById(div_id).innerHTML = '';
        makePlotSubGroup(div_id, dataMap);
        createDownloadLinkSubGroup(data.subgroup, data.bioproject, data.abundances);
    }
}

function plotBoxBiome(div_id, response) {
    var data = JSON.parse(response);
    if (data.biome.length > 0) {
        var dataMap = getDataMap(data.biome, data.abundances);
        document.getElementById(div_id).innerHTML = '';
        makePlotBiome(div_id, dataMap);
        createDownloadLinkBiome(data.biome, data.bioproject, data.abundances);
    }
}
