function getColorScale(colorScaleName) {
    if (colorScaleName == 'Purples') {
        return [
            ['0.0', 'rgb(106,81,163)'],
            ['0.222222222222', 'rgb(128,125,186)'],
            ['0.444444444444', 'rgb(158,154,200)'],
            ['0.666666666667', 'rgb(188,189,220)'],
            ['0.888888888889', 'rgb(218,218,235)'],
            ['1.0', 'rgb(239,237,245)']
        ];
    } else if (colorScaleName == 'Oranges') {
        return [
            ['0.0', 'rgb(217,72,1)'],
            ['0.222222222222', 'rgb(241,105,19)'],
            ['0.444444444444', 'rgb(253,141,60)'],
            ['0.666666666667', 'rgb(253,174,107)'],
            ['0.888888888889', 'rgb(253,208,162)'],
            ['1.0', 'rgb(254,230,206)']
        ];
    } else if (colorScaleName == 'Greens') {
        return [
            ['0.0', 'rgb(35,139,69)'],
            ['0.222222222222', 'rgb(65,171,93)'],
            ['0.444444444444', 'rgb(116,196,118)'],
            ['0.666666666667', 'rgb(161,217,155)'],
            ['0.888888888889', 'rgb(199,233,192)'],
            ['1.0', 'rgb(229,245,224)']
        ];
    } else if (colorScaleName == 'PRGn') {
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
    } else if (colorScaleName == 'RdYlBu') {
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
    } else {
        return 'Greys';
    }
}

colorMap = new Map();

function getDataMapBar(taxa, subgroup, value) {
    var dataMap = new Map();
    for(var i=0; i<taxa.length; ++i) {
        if(dataMap.has(subgroup[i])) {
            dataMap.get(subgroup[i]).taxa.push(taxa[i]);
            dataMap.get(subgroup[i]).value.push(Math.abs(value[i]));
        } else {
            dataMap.set(subgroup[i], {taxa: [taxa[i]], value: [Math.abs(value[i])]});
        }
    }
    return dataMap;
}

function getDataMapHeatmap(taxa, name, value) {
    var dataMap = {taxa: [], name: [], values: []};
    dataMap.taxa = [...new Set(taxa)].sort();
    dataMap.name = [...new Set(name)].sort();
    var valueMap = new Map();
    for(var i=0; i<taxa.length; ++i)
        // valueMap.set(name[i] + taxa[i], Math.abs(value[i]));
        valueMap.set(name[i] + taxa[i], value[i]);
    for(var i=0; i < dataMap.name.length; ++i) {
        var valueRow = [];
        for(var j=0; j < dataMap.taxa.length; ++j)
                valueRow.push(valueMap.get(dataMap.name[i] + dataMap.taxa[j]));
        dataMap.values.push(valueRow);
    }
    return dataMap;
}

function createDownloadLink(method, p_adjust_method, taxa, subgroup, value, pval, significance) {
    var scoreLabel = '';
    if (method === 'lefse')
        scoreLabel = 'LDA score (log10)';
    if (method === 'ALDEx2_t')
        scoreLabel = 'diff.btw';
    if (method === 'linda')
        scoreLabel = 'Log2FC';
    if (method === 'ancombc2')
        scoreLabel = 'Log2FC';
    // var scoreLabel = (method == 'edgeR') ? 'Log2FC' : 'LDA score (log10)';
    var pvalLabel = (p_adjust_method == 'none') ? 'P-value' : 'FDR-adjusted p-value';
    var s = 'Taxa\tSubGroup\t' + scoreLabel + '\t' + pvalLabel + '\tSignificance\n';
    for(var i=0; i<taxa.length; ++i) {
        s += taxa[i] + '\t';
        s += subgroup[i] + '\t';
        s += value[i] + '\t';
        s += pval[i] + '\t';
        s += significance[i] + '\n';
    }
    var blob = new Blob([s], {type: 'text/csv;charset=utf-8;'});
    document.getElementById('download_button').href = URL.createObjectURL(blob);
}

function createTaxaButtons(taxa) {
    var unique_taxa = [...new Set(taxa)];
    var s = '';
    for(var i=0; i<unique_taxa.length; ++i)
        s += '<div style="float:left; margin:5px;"><a href="taxa.php?key=' + unique_taxa[i].substr(3).replace(/_/g, " ") + '" target="_blank"><button style="padding:2px 5px;">' + unique_taxa[i] + '</button></a></div>';
    s += '<div style="clear:both;" />';
    document.getElementById('taxa_button_group').innerHTML = s;
}

function createPlotDataBar(dataMap) {
    var data = [];
    for(var subgroup of dataMap.keys()){
        var color = colorMap.get(subgroup);
        var barChart = {
            type: 'bar',
            orientation: 'h',
            name: subgroup,
            x: dataMap.get(subgroup).value,
            y: dataMap.get(subgroup).taxa,
            marker: {
                color: 'rgb(' + color.R + ',' + color.G + ',' + color.B + ')',
                opacity: 0.6
            },
        };
        data.push(barChart);
    }
    return data;
}

function getTaxaNumber(dataMap) {
    var nTaxa = 0;
    for(var subgroup of dataMap.keys())
        nTaxa += dataMap.get(subgroup).taxa.length;
    return nTaxa;
}

function makePlotBar(div_id, dataMap, method) {
    var graphDiv = document.getElementById(div_id);

    var computedHeight = (getTaxaNumber(dataMap) * 15) + 200;
    var xTitle = 'LDA score (log<sub>10</sub>)';
    // var xTitle = (method == 'edgeR') ? 'Log<sub>2</sub> fold change' : 'LDA score (log<sub>10</sub>)';
    var yTitle = 'Differentially<br> abundant<br> taxa';
    var data = createPlotDataBar(dataMap)

    var layout = {
        plot_bgcolor: '#ffffff', //'#fff0f5',
        paper_bgcolor: '#ffffff', //'#fff0f5',
        height: computedHeight,
        dragmode: 'pan',
//         bargap: 0.1,
        modebar: {
            color: '#262626',
            activecolor: '#262626'
        },
        hoverlabel: {
            bgcolor: 'white',
            font: {size: 18, color: '#000000'}
        },
        hovertext: {
            font: {color: '#000000'}
        },
        margin: {
            t: 30,
            l: 300,
            r: 300
        },
        xaxis: {
            visible : true,
            automargin: true,
            color: '#000000',
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
            color: '#000000',
            linewidth: 1,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 11},
            title : {
                text : yTitle,
                font: {size: 22}
            }
        },
        showlegend: true,
        legend: {
            font: {size: 16, color: '#000000'},
            borderwidth: 2,
            bordercolor: '#000000',
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
                        filename: 'lda_plot_',
                        format: 'svg'
                    }
                );
            }
        }]
    }

//     Plotly.plot(graphDiv, {data: data, layout: layout, config: config, frames: frames})
    Plotly.plot(graphDiv, {data: data, layout: layout, config: config})
}

function makePlotHeatmap(div_id, heatmapData, method, colorScaleName) {
    var graphDiv = document.getElementById(div_id);
    var computedHeight = (30*heatmapData.name.length + 250);
    var xTitle = 'Taxa';
    var scoreLabel = '';
    if (method === 'lefse')
        scoreLabel = 'LDA score (log10)';
    if (method === 'ALDEx2_t')
        scoreLabel = 'diff.btw';
    if (method === 'linda')
        scoreLabel = 'Log2FC';
    if (method === 'ancombc2')
        scoreLabel = 'Log2FC';

    var data = [{
        x: heatmapData.taxa,
        y: heatmapData.name,
        z: heatmapData.values,
        zmid: 0,
        xgap: 2,
        ygap: 2,
        colorscale: getColorScale(colorScaleName),
        reversescale: true,
        colorbar: {
            len: (computedHeight > 300) ? 150 : 1,
            lenmode: (computedHeight > 300) ? 'pixels' : 'fraction',
            outlinewidth: 0,
            tickfont: {color: '#000000'},
            ticks: 'inside',
            title: {
                text: scoreLabel,
                side: 'bottom',
                font: {
                    size: 14,
                    color: '#000000'
                }
            },
        },
        type: 'heatmap'
    }];

    var layout = {
        plot_bgcolor: '#ffffff', //'#fff0f5',
        paper_bgcolor: '#ffffff', //'#fff0f5',
        height: computedHeight,
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
        yaxis: {
            visible : true,
            automargin: true,
            showgrid: false,
            color: 'black',
            linewidth: 2,
            mirror: true,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 10},
        },
        xaxis: {
            visible : true,
            automargin: true,
            showgrid: false,
            color: 'black',
            linewidth: 2,
            mirror: true,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 10},
            title : {
                text : xTitle,
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
                        filename: 'discriminant_analysis_heatmap_plot',
                        format: 'svg'
                    }
                );
            }
        }]
    }

    Plotly.plot(graphDiv, data, layout, config);
}

function plotLDA(div_id, response, method, taxa_level) {
    var data = JSON.parse(response);
    if(data.taxa.length > 0) {
        document.getElementById(div_id).innerHTML = '';
        if (method === "lefse") {
            var dataMap = getDataMapBar(data.taxa, data.subgroup, data.value);
            makePlotBar(div_id, dataMap, method);
        } else if (method === "ALDEx2_t") {
            var dataMap = getDataMapHeatmap(data.taxa, data.subgroup, data.value);
            makePlotHeatmap(div_id, dataMap, method, 'RdYlBu_truncated');
        } else if (method === "linda") {
            var dataMap = getDataMapHeatmap(data.taxa, data.subgroup, data.value);
            makePlotHeatmap(div_id, dataMap, method, 'RdYlBu_truncated');
        } else if (method === "ancombc2") {
            var dataMap = getDataMapHeatmap(data.taxa, data.subgroup, data.value);
            makePlotHeatmap(div_id, dataMap, method, 'RdYlBu_truncated');
        }
        if (data.p_adjust == 'none')
            createDownloadLink(method, data.p_adjust, data.taxa, data.subgroup, data.value, data.padj, data.significance);
        else
            createDownloadLink(method, data.p_adjust, data.taxa, data.subgroup, data.value, data.padj, data.significance);
        document.getElementById('download_div').style.display = 'block';
        if (taxa_level == 'Species' || taxa_level == 'Genus') {
            createTaxaButtons(data.taxa);
            document.getElementById('taxa_button_group_heading').style.display = 'block';
            document.getElementById('taxa_button_group').style.display = 'block';
            document.getElementById('bar_legend').style.display = 'block';
        }
    } else {
        document.getElementById(div_id).innerHTML = '<p>No significant taxa found</p>';
    }
}

function getLDAData(div_id, dataJSON) {
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const rows = this.responseText.split('\n');
            for (var i=1; i<rows.length; ++i) {
                var elements = rows[i].split('\t');
                colorMap.set(elements[0], {R: elements[1], G: elements[2], B: elements[3]});
            }
        }
    };
    httpReq.open('POST', 'input/color_codes.tsv', false);
    httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    httpReq.send();

    var data = JSON.parse(dataJSON);
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            plotLDA(div_id, this.responseText, data.method, data.taxa_level)
        }
    };
    httpReq.open('POST', 'lda_data.php', true);
    httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    httpReq.send(
        'bioproject=' + encodeURIComponent(data.bioproject) +
        '&' + 'at=' + encodeURIComponent(data.at) +
        '&' + 'is=' + encodeURIComponent(data.is) +
        '&' + 'method=' + encodeURIComponent(data.method) +
        '&' + 'alpha=' + encodeURIComponent(data.alpha) +
        '&' + 'p_adjust_method=' + encodeURIComponent(data.p_adjust_method) +
        '&' + 'filter_thres=' + encodeURIComponent(data.filter_thres) +
        '&' + 'taxa_level=' + encodeURIComponent(data.taxa_level) +
        '&' + 'threshold=' + encodeURIComponent(data.threshold)
    );
}
